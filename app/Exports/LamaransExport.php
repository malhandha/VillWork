<?php

namespace App\Exports;

use App\Models\Lamaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Str;

class LamaransExport implements FromCollection, WithHeadings, WithMapping
{

    public function collection()
    {
        return Lamaran::with(['user', 'lowongan'])->get();
    }

    public function headings(): array
    {
        return [
            'ID Lamaran',
            'Nama Pelamar',
            'Email Pelamar',
            'Lowongan Dilamar',
            'Status Lamaran',
            'Tanggal Melamar',
        ];
    }
    public function map($lamaran): array
    {
        return [
            $lamaran->id,
            $lamaran->user?->name ?? 'Pengguna Dihapus',
            $lamaran->user?->email ?? 'Email Tidak Ada',
            $lamaran->lowongan?->judul_pekerjaan ?? 'Lowongan Tidak Tersedia',
            Str::title($lamaran->status ?? 'Tidak Diketahui'),
            $lamaran->created_at?->format('d M Y, H:i') ?? '-',
        ];
    }
}
