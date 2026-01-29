<?php

namespace App\Livewire;

use App\Imports\MargaImport;
use App\Models\Marga;
use App\Models\MargaHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class DataMarga extends Component
{
    use WithFileUploads, WithPagination;

    public $wilayah_adat, $suku, $marga, $berkas;
    public $selectedId = null;
    public $deleteId = null;

    // Tambahan properti untuk import Excel
    public $file;

    public $existingBerkas;

    // Jika mau pakai bootstrap untuk pagination:
    protected $paginationTheme = 'bootstrap';

    public $search = '';



    // Import Excel
    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        Excel::import(new MargaImport, $this->file->getRealPath());
        $this->reset('file');
        session()->flash('message', 'Data marga berhasil diimpor dari Excel!');
    }

    public function resetForm()
    {
        $this->reset(['wilayah_adat', 'suku', 'marga', 'berkas', 'selectedId']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        $marga = Marga::findOrFail($id);
        $this->selectedId = $marga->id;
        $this->wilayah_adat = $marga->wilayah_adat;
        $this->suku = $marga->suku;
        $this->marga = $marga->marga;
        $this->existingBerkas = $marga->berkas;
    }

    public function save()
    {

    $rules = [
            'wilayah_adat' => 'required|string|max:255',
            'suku' => 'required|string|max:255',
            'marga' => [
                'required',
                'string',
                'max:255',
                Rule::unique('margas')
                    ->where(fn ($q) =>
                        $q->where('suku', $this->suku)
                        ->where('wilayah_adat', $this->wilayah_adat)
                    )
                    ->ignore($this->selectedId), // abaikan data yg sedang diupdate
            ],
            'berkas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

    $messages = [
        'marga.unique' => 'Marga sudah terdaftar pada suku dan wilayah adat ini.',
    ];


        $this->validate($rules, $messages);

        $filePath = $this->berkas ? $this->berkas->store('berkas-marga', 'public') : $this->existingBerkas;

        if ($this->selectedId) {
            $marga = Marga::findOrFail($this->selectedId);
            $marga->update([
                'wilayah_adat' => $this->wilayah_adat,
                'suku' => $this->suku,
                'marga' => $this->marga,
                'berkas' => $filePath ?? $marga->berkas,
            ]);

            MargaHistory::create([
                'marga_id' => $marga->id,
                'user_id' => Auth::id(),
                'action' => 'update',
            ]);

            session()->flash('message', 'Data marga berhasil diupdate.');
        } else {
            $marga = Marga::create([
                'wilayah_adat' => $this->wilayah_adat,
                'suku' => $this->suku,
                'marga' => $this->marga,
                'berkas' => $filePath,
            ]);

            MargaHistory::create([
                'marga_id' => $marga->id,
                'user_id' => Auth::id(),
                'action' => 'create',
            ]);

            session()->flash('message', 'Data marga berhasil disimpan.');
        }

        $this->resetForm();
    }


    // Trigger modal konfirmasi hapus
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('show-delete-modal');
    }

    public function delete()
    {
        $marga = Marga::findOrFail($this->deleteId);

        MargaHistory::create([
            'marga_id' => $marga->id,
            'user_id' => Auth::id(),
            'action' => 'delete',
        ]);

        $marga->delete();
        $this->deleteId = null;

        $this->dispatch('hide-delete-modal');
        session()->flash('message', 'Data marga berhasil dihapus.');
        // $this->loadData();
    }

    public function render()
    {
        $query = Marga::with('user')->latest();

        if ($this->search) {
            $query->where('marga', 'like', '%'.$this->search.'%')
                ->orWhere('suku', 'like', '%'.$this->search.'%')
                ->orWhere('wilayah_adat', 'like', '%'.$this->search.'%');
        }

        $margas = $query->paginate(10); // 10 data per halaman

        return view('livewire.data-marga', compact('margas'))->layout('layouts.app');
        }

}
