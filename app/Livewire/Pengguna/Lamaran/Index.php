<?php

namespace App\Livewire\Pengguna\Lamaran;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Lowongan;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use WithFileUploads;

    public $judul_pekerjaan, $nama_perusahaan, $lokasi, $kategori, $deskripsi,
    $gaji_min, $gaji_max, $tanggal_berakhir, $emailHRD, $logo_perusahaan;

    public $search = '';
    public $useDummy = true; // Ubah ini jadi true kalau ingin pakai dummy

    #[Layout('layouts.app')]
    public function render()
    {
        if ($this->useDummy) {
            // Dummy data sementara (tanpa database)
            $lowongans = collect([
                (object) [
                    'id' => 1,
                    'judul_pekerjaan' => 'Frontend Developer',
                    'nama_perusahaan' => 'TechNova Inc.',
                    'lokasi' => 'Jakarta',
                    'kategori' => 'Full-Time',
                    'tanggal_berakhir' => now()->addDays(10),
                ],
                (object) [
                    'id' => 2,
                    'judul_pekerjaan' => 'UI/UX Designer',
                    'nama_perusahaan' => 'Kreativa Studio',
                    'lokasi' => 'Bandung',
                    'kategori' => 'Internship',
                    'tanggal_berakhir' => now()->addDays(15),
                ],
                (object) [
                    'id' => 3,
                    'judul_pekerjaan' => 'Backend Engineer',
                    'nama_perusahaan' => 'ByteForge',
                    'lokasi' => 'Yogyakarta',
                    'kategori' => 'Remote',
                    'tanggal_berakhir' => now()->addDays(7),
                ],
            ]);
        } else {
            // Data dari database
            $lowongans = Lowongan::when($this->search, function ($query) {
                $query->where('lokasi', 'like', '%' . $this->search . '%');
            })->latest()->get();
        }

        return view('livewire.pengguna.lamaran.index', compact('lowongans'));
    }

    public function submit()
    {
        $this->validate([
            'judul_pekerjaan' => 'required|string',
            'nama_perusahaan' => 'required|string',
            'lokasi' => 'required|string',
            'kategori' => 'required|string',
            'deskripsi' => 'required|string',
            'gaji_min' => 'required|numeric',
            'gaji_max' => 'required|numeric|gte:gaji_min',
            'tanggal_berakhir' => 'required|date',
            'emailHRD' => 'required|email',
            'logo_perusahaan' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $logo = $this->logo_perusahaan
            ? $this->logo_perusahaan->store('logos', 'public')
            : null;

        Lowongan::create([
            'judul_pekerjaan' => $this->judul_pekerjaan,
            'nama_perusahaan' => $this->nama_perusahaan,
            'lokasi' => $this->lokasi,
            'kategori' => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'gaji_min' => $this->gaji_min,
            'gaji_max' => $this->gaji_max,
            'tanggal_posting' => now(),
            'tanggal_berakhir' => $this->tanggal_berakhir,
            'logo_perusahaan' => $logo,
            'emailHRD' => $this->emailHRD,
            'id_perusahaan' => 0,
            'favorite' => 0,
        ]);

        session()->flash('message', 'Lowongan berhasil ditambahkan!');
        $this->reset();
    }
}
