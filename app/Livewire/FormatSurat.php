<?php

namespace App\Livewire;

use App\Models\FormatSurat as ModelsFormatSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class FormatSurat extends Component
{
    public $jenis;
    public $isi;
    public $daftarFormat = [];
    public $selectedFormat;
    public $showModal = false;
    public $formatId = null; // untuk edit
    public $pdfContent; // untuk menampung PDF yang di-stream
    public $showPdfModal = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->daftarFormat = ModelsFormatSurat::latest()->get();
    }

    public function simpan()
    {
        $this->validate([
            'jenis' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        if ($this->formatId) {
            // Mode edit
            $format = ModelsFormatSurat::findOrFail($this->formatId);
            $format->update([
                'jenis' => $this->jenis,
                'isi' => $this->isi,
            ]);
            session()->flash('success', 'Format surat berhasil diperbarui.');
        } else {
            // Mode tambah
            ModelsFormatSurat::create([
                'jenis' => $this->jenis,
                'isi' => $this->isi,
            ]);
            session()->flash('success', 'Format surat berhasil disimpan.');
        }

        $this->resetForm();
        $this->loadData();

        // Reset isi editor di frontend
        $this->dispatch('reset-editor');
    }

    public function edit($id)
    {
        $format = ModelsFormatSurat::findOrFail($id);
        $this->formatId = $format->id;
        $this->jenis = $format->jenis;
        $this->isi = $format->isi;

        // Kirim isi ke editor Quill
        $this->dispatch('set-editor', $this->isi);
    }

    public function hapus($id)
    {
        $format = ModelsFormatSurat::find($id);
        if ($format) {
            $format->delete();
            session()->flash('success', 'Format surat berhasil dihapus.');
            $this->loadData();
        }
    }
    
    public function preview($id)
    {
        $format = ModelsFormatSurat::findOrFail($id);

        // Generate PDF dari view
        $pdf = Pdf::loadView('pdf.format-surat', ['format' => $format]);

        // Simpan hasil output PDF ke properti Livewire
        $this->pdfContent = base64_encode($pdf->output());
        $this->showPdfModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function closePdfModal()
    {
        $this->showPdfModal = false;
        $this->pdfContent = null;
    }

    public function resetForm()
    {
        $this->reset(['jenis', 'isi', 'formatId']);
    }

    public function render()
    {
        return view('livewire.format-surat');
    }
}

