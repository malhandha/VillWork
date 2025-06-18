<?php

namespace App\Livewire\Pengguna\Lamaran;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Mail\NotifikasiLamaranMasuk;

class Create extends Component
{
    use WithFileUploads;

    public $lowongan_id;
    public $nama, $email, $telepon, $linkedin, $pesan, $cv;

    public $useDummy = true; // kalau pakai dummy

    protected $dummyLowongans = [
        1 => [
            'id' => 1,
            'judul_pekerjaan' => 'Frontend Developer',
            'nama_perusahaan' => 'TechNova Inc.',
            'lokasi' => 'Jakarta',
            'kategori' => 'Full-Time',
            'tanggal_berakhir' => null, // nanti set di render
            'emailHRD' => 'hr@technova.id',
        ],
        2 => [
            'id' => 2,
            'judul_pekerjaan' => 'UI/UX Designer',
            'nama_perusahaan' => 'Kreativa Studio',
            'lokasi' => 'Bandung',
            'kategori' => 'Internship',
            'tanggal_berakhir' => null,
            'emailHRD' => 'hr@kreativa.id',
        ],
        3 => [
            'id' => 3,
            'judul_pekerjaan' => 'Backend Engineer',
            'nama_perusahaan' => 'ByteForge',
            'lokasi' => 'Yogyakarta',
            'kategori' => 'Remote',
            'tanggal_berakhir' => null,
            'emailHRD' => 'hr@byteforge.id',
        ],
    ];

    public function mount($lowongan)
    {
        $this->lowongan_id = (int) $lowongan;

        $user = Auth::user();
        $this->nama = $user->name ?? '';
        $this->email = $user->email ?? '';
    }

    public function submit()
    {
        $this->validate([
            'telepon' => 'required|min:8',
            'linkedin' => 'nullable|url',
            'pesan' => 'required|min:10',
            'cv' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Jika dummy, skip simpan ke DB, hanya flash message
        if ($this->useDummy) {
            // Simulasikan penyimpanan...
            session()->flash('message', 'Simulasi: Lamaran berhasil dikirim untuk Lowongan ID ' . $this->lowongan_id);
            return redirect()->route('pengguna.lamaran.index');
        }

        // Mode nyata: simpan ke DB
        $path = $this->cv->store('cv', 'public');
        $lamaran = Lamaran::create([
            'user_id' => Auth::id(),
            'lowongan_id' => $this->lowongan_id,
            'pesan' => $this->pesan,
            'cv_path' => $path,
            'status' => 'Diproses',
        ]);
        // Kirim email ke HRD jika ada emailHRD
        $emailHRD = $lamaran->lowongan->emailHRD;
        if ($emailHRD) {
            Mail::to($emailHRD)->send(new NotifikasiLamaranMasuk($lamaran));
        }
        session()->flash('message', 'Lamaran berhasil dikirim!');
        return redirect()->route('pengguna.lamaran.index');
    }

    public function render()
    {
        if ($this->useDummy) {
            // Cari dummy berdasarkan ID
            if (!isset($this->dummyLowongans[$this->lowongan_id])) {
                abort(404);
            }
            $d = $this->dummyLowongans[$this->lowongan_id];
            // Simulasikan tanggal berakhir misalnya 10 hari ke depan
            $d['tanggal_berakhir'] = now()->addDays(10);
            $lowongan = (object) $d;
        } else {
            $lowongan = Lowongan::findOrFail($this->lowongan_id);
        }

        return view('livewire.pengguna.lamaran.create', ['lowongan' => $lowongan])
            ->layout('layouts.app');
    }
}