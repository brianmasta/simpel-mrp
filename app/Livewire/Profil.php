<?php

namespace App\Livewire;

use App\Models\Marga;
use App\Models\Wilayah\Kabupaten;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kelurahan;
use App\Models\Wilayah\Provinsi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profil extends Component
{
        
    public $user, $profil;

    public $nik, $nama_lengkap, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $alamat, $nama_ayah, $nama_ibu, $email, $no_hp;
    
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

    public $marga;
    public $margaDitemukan = null; // true / false / null


    public $dataDariAPI = false;

    protected $rules = [
        'nik' => 'required|digits:16',
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

    public function updatedNamaLengkap($value)
    {
        // Ambil kata terakhir sebagai marga
        $parts = explode(' ', trim($value));
        $this->marga = ucfirst(strtolower(end($parts)));

        // Cek apakah marga ada di database
        if ($this->marga) {
            $cekMarga = Marga::where('marga', $this->marga)->first();

            if ($cekMarga) {
                $this->margaDitemukan = true;
            } else {
                $this->margaDitemukan = false;
            }
        } else {
            $this->margaDitemukan = null;
        }
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->profil = $this->user->profil; // ambil data profil terkait

        // Ambil email dari user
        $this->email = $this->user->email;

        // Ambil data profil
        if($this->profil){
            $this->nik = $this->profil->nik;
            $this->nama_lengkap = $this->profil->nama_lengkap;
            $this->tempat_lahir = $this->profil->tempat_lahir;
            $this->tanggal_lahir = $this->profil->tanggal_lahir;
            $this->jenis_kelamin = $this->profil->jenis_kelamin;
            $this->alamat = $this->profil->alamat;
            $this->nama_ayah = $this->profil->nama_ayah;
            $this->nama_ibu = $this->profil->nama_ibu;
            $this->no_hp = $this->profil->no_hp;

            $this->provinsi_id = $this->profil->provinsi_id;
            $this->kabupaten_id = $this->profil->provinsi_id;
            $this->kecamatan_id = $this->profil->provinsi_id;
            $this->kelurahan_id = $this->profil->provinsi_id;

            $this->searchProvinsi = $this->profil->provinsi?->nama ?? '';
            $this->searchKabupaten = $this->profil->kabupaten?->nama ?? '';
            $this->searchKecamatan = $this->profil->kecamatan?->nama ?? '';
            $this->searchKelurahan = $this->profil->kelurahan?->nama ?? '';

            // dd($this->searchProvinsi = $this->profil->provinsi?->nama);
        }
    }


    public function updatedSearchProvinsi($value)
    {
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
            'nik' => 'required|digits:16',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'email' => ['required','email', Rule::unique('users','email')->ignore($this->user->id)],
        ]);

        // Simpan email ke user
        $this->user->email = $this->email;
        $this->user->save();

        // Simpan data profil
        if(!$this->profil){
            // Buat instance baru tanpa insert dulu
            $profil = new \App\Models\Profil();
            $profil->user_id = $this->user->id;
        }else{
            $profil = $this->profil;
        }

        $profil->nik = $this->nik;
        $profil->nama_lengkap = $this->nama_lengkap;
        $profil->tempat_lahir = $this->tempat_lahir;
        $profil->tanggal_lahir = $this->tanggal_lahir;
        $profil->jenis_kelamin = $this->jenis_kelamin;
        $profil->alamat = $this->alamat;
        $profil->nama_ayah = $this->nama_ayah;
        $profil->nama_ibu = $this->nama_ibu;
        $profil->no_hp = $this->no_hp;

        $profil->provinsi_id = $this->provinsi_id;
        $profil->provinsi_id = $this->kabupaten_id;
        $profil->provinsi_id = $this->kecamatan_id;
        $profil->provinsi_id = $this->kelurahan_id;

        $profil->save();
        session()->flash('message', 'Profil berhasil disimpan');
        // $this->dispatchBrowserEvent('profil-saved', ['message' => 'Profil berhasil disimpan']);
    }


    public function render()
    {
        return view('livewire.profil');
    }
}
