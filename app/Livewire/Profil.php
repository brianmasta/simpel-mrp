<?php

namespace App\Livewire;

use App\Models\Marga;
use App\Models\Profil as ModelsProfil;
use App\Models\Wilayah\Kabupaten;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kelurahan;
use App\Models\Wilayah\Provinsi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use thiagoalessio\TesseractOCR\TesseractOCR;

class Profil extends Component
{
    use WithFileUploads;
    public $user, $profil;

    public $nik, $no_kk, $nama_lengkap, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $alamat, $nama_ayah, $nama_ibu, $email, $no_hp;
    
    public $searchProvinsi = '';
    public $searchKabupaten = '';
    public $searchKecamatan = '';
    public $searchKelurahan = '';

    public $provinsi_id;
    public $kabupaten_id;
    public $kecamatan_id;
    public $kelurahan_id;

    public $provinsis = [];
    public $kabupatens = [];
    public $kecamatans = [];
    public $kelurahans = [];

    public $provinsi;
    public $kabupaten;
    public $kecamatan;
    public $kelurahan;

    public $marga, $cekMarga;
    public $margaDitemukan = null; // true / false / null

    public $status_oap = false; // ✅ status hasil verifikasi

    public $dataDariAPI = false;

    public $foto_ktp, $foto_kk;

    public $sumberMarga;

    protected $rules = [
        'nik' => 'required|digits:16',
        'no_kk' => 'required|digits:16',
        'nama_lengkap' => 'required|string|max:255',
        'tempat_lahir' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|string',
        'alamat' => 'nullable|string',
        'nama_ayah' => 'nullable|string',
        'nama_ibu' => 'nullable|string',
        'email' => 'nullable|email',
        'no_hp' => 'nullable|numeric',
        'provinsi_id' => 'nullable|exists:provinsis,id',
        'kabupaten_id' => 'nullable|exists:kabupatens,id',
        'kecamatan_id' => 'nullable|exists:kecamatans,id',
        'kelurahan_id' => 'nullable|exists:kelurahans,id',
    ];

    public function updatedFotoKtp()
    {
        $this->extractTextFromImage($this->foto_ktp, 'ktp');
    }

    public function updatedFotoKk()
    {
        $this->extractTextFromImage($this->foto_kk, 'kk');
    }


    private function extractTextFromImage($file, $type)
    {
        if (!$file) return;

        // Simpan file sementara ke storage
        $path = $file->store('temp', 'public');
        $filePath = storage_path('app/public/' . $path);

        try {
            // Kirim ke API OCR.Space
            $response = Http::attach(
                'file',
                fopen($filePath, 'r'),
                basename($filePath)
            )->post(env('OCR_API_URL', 'https://api.ocr.space/parse/image'), [
                'apikey' => env('OCR_API_KEY'),
                'language' => 'eng', // gunakan 'eng' karena 'ind' sering error
                'isOverlayRequired' => 'false', // harus string, bukan boolean
            ]);

            $data = $response->json();
            // dd($data);

            if (!empty($data['ParsedResults'][0]['ParsedText'])) {
                $parsedText = strtoupper($data['ParsedResults'][0]['ParsedText']);

                if ($type === 'ktp') {
                    $this->extractNikFromKtp($parsedText);
                    $this->extractNamaFromKtp($parsedText);
                } elseif ($type === 'kk') {
                    $this->extractNoKkFromKk($parsedText);
                }

                session()->flash('success', '✅ Data berhasil dibaca otomatis dari ' . strtoupper($type) . '.');
            } else {
                session()->flash('error', '⚠️ Tidak ada teks terbaca dari gambar ' . strtoupper($type) . '.');
            }
        } catch (\Exception $e) {
            $this->addError('ocr', 'Gagal membaca gambar: ' . $e->getMessage());
        }
    }

private function extractNikFromKtp($text)
{
    // Normalisasi teks agar mudah diproses
    $text = strtoupper($text);

    // Ambil NIK (angka 16 digit)
    if (preg_match('/\b\d{16}\b/', $text, $matches)) {
        $this->nik = $matches[0];
    }

    // Ambil baris yang mengandung kata "NAMA"
    // dan ambil kata setelahnya (biasanya satu baris saja)
    if (preg_match('/NAMA\s*[:\-]?\s*([A-Z\s]+)(?:\n|$)/', $text, $matches)) {
        $nama = trim($matches[1]);

        // Hapus teks yang jelas bukan nama (seperti PROVINSI / KABUPATEN)
        if (str_contains($nama, 'PROVINSI') || str_contains($nama, 'KABUPATEN')) {
            $nama = ''; // kosongkan kalau salah baca
        }

        // Bersihkan karakter aneh dan spasi berlebih
        $nama = preg_replace('/[^A-Z\s]/', '', $nama);
        $nama = preg_replace('/\s+/', ' ', $nama);

        if (!empty($nama)) {
            $this->nama_lengkap = $nama;
        }
    }

    $this->dispatchBrowserEvent('ocr-success', [
        'message' => 'Data KTP berhasil dibaca. Silakan periksa hasilnya sebelum disimpan.'
    ]);
}

private function extractNamaFromKtp($text)
{
    // Cari kata "Nama" atau "NAMA" lalu ambil teks setelahnya
    if (preg_match('/NAMA\s*[:\-]?\s*([A-Z\s]+)/i', $text, $matches)) {
        // Bersihkan hasil nama agar rapi
        $nama = trim(preg_replace('/\s+/', ' ', $matches[1]));
        $this->nama = ucwords(strtolower($nama));
    }
}

    private function extractNoKkFromKk($text)
    {
        // Cari pola angka 16 digit
        if (preg_match('/\b\d{16}\b/', $text, $matches)) {
            $this->no_kk = $matches[0];
        }
    }


    /**
     * Fungsi utama untuk verifikasi OAP berdasarkan marga.
     */
    public function verifikasiMarga()
    {
        $hasil = $this->deteksiMargaDariUserAtauIbu();

        $this->marga = $hasil['marga'];
        $this->margaDitemukan = $hasil['ditemukan'];
        $this->status_oap = $hasil['ditemukan'];
        $this->sumberMarga = $hasil['sumber']; 
    }

    /**
     * Jalankan otomatis saat nama lengkap diubah.
     */
    public function updatedNamaLengkap()
    {
        $this->verifikasiMarga();
    }

    /**
     * Jalankan otomatis saat nama ibu diubah (kalau nama lengkap belum berhasil).
     */
    public function updatedNamaIbu()
    {
        $this->verifikasiMarga();
    }

    // protected function cekMargaOtomatis($namaLengkap, $isNamaIbu = false)
    // {
    //     // Ubah jadi Title Case biar pencarian seragam
    //     $namaArray = explode(' ', \Illuminate\Support\Str::title($namaLengkap));

    //     // Cek tiap kata apakah ada di tabel margas
    //     $margaDitemukan = \App\Models\Marga::whereIn('marga', $namaArray)->first();

    //     if ($margaDitemukan) {
    //         $this->marga = $margaDitemukan->nama;
    //         $this->margaDitemukan = true;
    //         $this->status_oap = true; // ✅ otomatis set status OAP = true
    //         $this->marga_terverifikasi = $margaDitemukan->nama;
    //     } else {
    //         $this->marga = end($namaArray);
    //         $this->margaDitemukan = false;
    //         $this->status_oap = false; // ❌ belum OAP
    //         $this->marga_terverifikasi = null;

    //         // Jika dicek lewat nama ibu dan belum ketemu
    //         if ($isNamaIbu) {
    //             $namaArrayIbu = explode(' ', \Illuminate\Support\Str::title($namaLengkap));
    //             $margaIbu = \App\Models\Marga::whereIn('marga', $namaArrayIbu)->first();

    //             if ($margaIbu) {
    //                 $this->margaDitemukan = true;
    //                 $this->status_oap = true;
    //                 $this->marga_terverifikasi = $margaIbu->nama;
    //             }
    //         }
    //     }
    // }

    // protected function deteksiMargaUserAtauIbu()
    // {
    //     // 1️⃣ Coba dari nama lengkap user
    //     if ($this->nama_lengkap) {
    //         $namaArray = explode(' ', Str::title($this->nama_lengkap));
    //         $marga = Marga::whereIn('marga', $namaArray)->first();

    //         if ($marga) {
    //             return [
    //                 'status_oap' => true,
    //                 'marga' => $marga->marga,
    //                 'sumber' => 'user'
    //             ];
    //         }
    //     }

    //     // 2️⃣ Fallback: nama ibu kandung
    //     if ($this->nama_ibu) {
    //         $namaIbuArray = explode(' ', Str::title($this->nama_ibu));
    //         $margaIbu = Marga::whereIn('marga', $namaIbuArray)->first();

    //         if ($margaIbu) {
    //             return [
    //                 'status_oap' => true,
    //                 'marga' => $margaIbu->marga,
    //                 'sumber' => 'ibu'
    //             ];
    //         }
    //     }

    //     // 3️⃣ Tidak ditemukan
    //     return [
    //         'status_oap' => false,
    //         'marga' => null,
    //         'sumber' => null
    //     ];
    // }

    private function isPapuaTengah($value)
    {
        $prefix = substr($value, 0, 2); // ambil 2 digit pertama

        $papuaTengahCodes = ['91', '95', '92', '94', '96', '97']; // kode provinsi Papua Tengah

        return in_array($prefix, $papuaTengahCodes);
    }

    public function updatedNik($value)
    {
        if (strlen($value) < 4) return;

        // Cek duplikasi NIK
        if (ModelsProfil::where('nik', $value)->exists()) {
            $this->addError('nik', '❌ NIK sudah terdaftar di database!');
            return;
        }

        if (!$this->isPapuaTengah($value)) {
            $this->addError('nik', '❌ NIK tidak terdaftar di Provinsi Di Wilayah Papua.');
        } else {
            $this->addError('nik', '✅ NIK terdeteksi berasal dari Provinsi Di Wilayah Papua.');
            session()->flash('info_nik', "✅ NIK terdeteksi berasal dari Provinsi Di Wilayah Papua.");
        }
    }

    public function updatedNoKk($value)
    {
        if (!$value || strlen($value) < 4) return;

        if (!$this->isPapuaTengah($value)) {
            $this->addError('no_kk', '❌ Nomor KK tidak terdaftar di provinsi Papua Tengah.');
        } else {
            $this->resetErrorBag('no_kk');
            session()->flash('info_kk', "✅ Nomor KK terdeteksi berasal dari provinsi Papua Tengah.");
        }
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->profil = $this->user->profil; // ambil data profil terkait

        // Ambil email dari user
        $this->email = $this->user->email;

        // Ambil data profil
        // dd($this->profil);
        if($this->profil){
            $this->nik = $this->profil->nik;
            $this->no_kk = $this->profil->no_kk;
            $this->nama_lengkap = $this->profil->nama_lengkap;
            $this->tempat_lahir = $this->profil->tempat_lahir;
            $this->tanggal_lahir = $this->profil->tanggal_lahir;
            $this->jenis_kelamin = $this->profil->jenis_kelamin;
            $this->alamat = $this->profil->alamat;
            $this->nama_ayah = $this->profil->nama_ayah;
            $this->nama_ibu = $this->profil->nama_ibu;
            $this->no_hp = $this->profil->no_hp;

            $this->provinsi_id = $this->profil->provinsi_id;
            $this->kabupaten_id = $this->profil->kabupaten_id;
            $this->kecamatan_id = $this->profil->kecamatan_id;
            $this->kelurahan_id = $this->profil->kelurahan_id;

            $this->searchProvinsi = $this->profil->provinsi?->nama ?? '';
            $this->searchKabupaten = $this->profil->kabupaten?->nama ?? '';
            $this->searchKecamatan = $this->profil->kecamatan?->nama ?? '';
            $this->searchKelurahan = $this->profil->kelurahan?->nama ?? '';

            // dd($this->searchProvinsi = $this->profil->provinsi?->nama);
        }
    }


    public function updatedSearchProvinsi($value)
    {
        $this->provinsi_id = null;

            if (strlen($value) < 2) {
                $this->provinsis = [];
                return;
            }

        $this->provinsis = Provinsi::where('nama', 'like', "%{$value}%")->get();
    }


    public function selectProvinsi($id)
    {
        $this->provinsi_id = $id;
        $prov = Provinsi::find($id);
        $this->searchProvinsi = $prov->nama;
        $this->provinsis = [];

        // Reset downstream
        $this->kabupaten_id = null;
        $this->kecamatan_id = null;
        $this->kelurahan_id = null;
        $this->searchKabupaten = '';
        $this->searchKecamatan = '';
        $this->searchKelurahan = '';
    }

    public function updatedSearchKabupaten($value)
    {
        if ($this->provinsi_id) {
            $this->kabupatens = Kabupaten::where('provinsi_id', $this->provinsi_id)
                                ->where('nama', 'like', "%{$value}%")->get();
        }
    }

    public function selectKabupaten($id)
    {
        $this->kabupaten_id = $id;
        $kab = Kabupaten::find($id);
        $this->searchKabupaten = $kab->nama;
        $this->kabupatens = [];

        // Reset downstream
        $this->kecamatan_id = null;
        $this->kelurahan_id = null;
        $this->searchKecamatan = '';
        $this->searchKelurahan = '';
    }

    public function updatedSearchKecamatan($value)
    {
        if ($this->kabupaten_id) {
            $this->kecamatans = Kecamatan::where('kabupaten_id', $this->kabupaten_id)
                                ->where('nama', 'like', "%{$value}%")->get();
        }
    }

    public function selectKecamatan($id)
    {
        $this->kecamatan_id = $id;
        $kec = Kecamatan::find($id);
        $this->searchKecamatan = $kec->nama;
        $this->kecamatans = [];

        // Reset downstream
        $this->kelurahan_id = null;
        $this->searchKelurahan = '';
    }

    public function updatedSearchKelurahan($value)
    {
        if ($this->kecamatan_id) {
            $this->kelurahans = Kelurahan::where('kecamatan_id', $this->kecamatan_id)
                                ->where('nama', 'like', "%{$value}%")->get();
        }
    }

    public function selectKelurahan($id)
    {
        $this->kelurahan_id = $id;
        $kel = Kelurahan::find($id);
        $this->searchKelurahan = $kel->nama;
        $this->kelurahans = [];
    }

    public function save()
    {
        $this->validate([
            'foto_ktp' => 'nullable|image|max:2048', // 2MB
            'foto_kk' => 'nullable|image|max:2048',
            'nik' => 'required|digits:16',
            'no_kk' => 'required|digits:16',
            'nama_lengkap' => 'required|string|max:255',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_hp' => 'required|regex:/^08[0-9]{8,13}$/',
            'email' => ['required','email', Rule::unique('users','email')->ignore($this->user->id)],
            'provinsi_id' => 'required|exists:provinsis,id',
            'kabupaten_id' => 'required|exists:kabupatens,id',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'alamat' => 'required|string|min:1|max:500',
        ], [
            'provinsi_id.required' => 'Provinsi wajib dipilih.',
            'kabupaten_id.required' => 'Kabupaten wajib dipilih.',
            'kecamatan_id.required' => 'Kecamatan wajib dipilih.',
            'kelurahan_id.required' => 'Kelurahan wajib dipilih.',
            'alamat' => 'Alamat wajib dipilih',
            'no_hp' => 'No hp wajib dipilih',
        ]);

        // ✅ Validasi wilayah NIK & KK Papua Tengah
        if (!$this->isPapuaTengah($this->nik)) {
            $this->addError('nik', 'NIK bukan berasal dari wilayah Papua Tengah (91xx / 95xx).');
            return;
        }

        if ($this->no_kk && !$this->isPapuaTengah($this->no_kk)) {
            $this->addError('no_kk', 'Nomor KK bukan berasal dari wilayah Papua Tengah (91xx / 95xx).');
            return;
        }

        // ✅ Simpan email ke user
        $this->user->email = $this->email;
        $this->user->save();

        // ✅ Ambil atau buat profil baru
        if (!$this->profil) {
            $profil = new \App\Models\Profil();
            $profil->user_id = $this->user->id;
        } else {
            $profil = $this->profil;
        }

        // 🔍 Logika verifikasi OAP otomatis
        $hasilMarga = $this->deteksiMargaDariUserAtauIbu();

        $profil->status_oap = $hasilMarga['ditemukan'];
        $profil->marga_terverifikasi = $hasilMarga['marga'];
        $profil->sumber_marga = $hasilMarga['sumber'];

        // ✅ Simpan data profil
        $profil->nik = $this->nik;
        $profil->no_kk = $this->no_kk;
        $profil->nama_lengkap = Str::title($this->nama_lengkap);
        $profil->tempat_lahir = $this->tempat_lahir;
        $profil->tanggal_lahir = $this->tanggal_lahir;
        $profil->jenis_kelamin = $this->jenis_kelamin;
        $profil->alamat = $this->alamat;
        $profil->nama_ayah = Str::title($this->nama_ayah);
        $profil->nama_ibu = Str::title($this->nama_ibu);
        $profil->no_hp = $this->no_hp;

        $profil->provinsi_id = $this->provinsi_id;
        $profil->kabupaten_id = $this->kabupaten_id;
        $profil->kecamatan_id = $this->kecamatan_id;
        $profil->kelurahan_id = $this->kelurahan_id;

        // ✅ Simpan hasil verifikasi OAP


        $profil->save();

        // ✅ Pesan sukses dinamis
    if ($hasilMarga['ditemukan']) {
        $this->dispatch('toast', [
            'message' => 'Profil berhasil disimpan dengan status OAP. Sekarang Anda sudah bisa mengajukan surat OAP.',
            'type' => 'success'
        ]);
    } else {
        $this->dispatch('toast', [
            'message' => 'Profil berhasil disimpan, namun status OAP belum terverifikasi. Silakan ajukan penambahan marga.',
            'type' => 'warning'
        ]);
    }

    }

    protected function deteksiMargaDariUserAtauIbu()
    {
        // 1️⃣ cek dari nama lengkap user
        if ($this->nama_lengkap) {
            $parts = explode(' ', Str::title(trim($this->nama_lengkap)));
            $marga = Marga::whereIn('marga', $parts)->first();

            if ($marga) {
                return [
                    'ditemukan' => true,
                    'marga' => $marga->marga,
                    'sumber' => 'user'
                ];
            }
        }

        // 2️⃣ fallback: nama ibu kandung
        if ($this->nama_ibu) {
            $partsIbu = explode(' ', Str::title(trim($this->nama_ibu)));
            $margaIbu = Marga::whereIn('marga', $partsIbu)->first();

            if ($margaIbu) {
                return [
                    'ditemukan' => true,
                    'marga' => $margaIbu->marga,
                    'sumber' => 'ibu'
                ];
            }
        }

        // 3️⃣ tidak ditemukan
        $namaSumber = $this->nama_lengkap ?? $this->nama_ibu ?? '';
        $parts = explode(' ', Str::title(trim($namaSumber)));
        $margaTerakhir = end($parts);

        return [
            'ditemukan' => false,
            'marga' => $margaTerakhir ?: null,
            'sumber' => null
        ];
    }



    public function render()
    {
        return view('livewire.profil')->layout('components.layouts.app');
    }
}
