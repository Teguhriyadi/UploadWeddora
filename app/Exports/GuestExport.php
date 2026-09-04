<?php

namespace App\Exports;

use App\Models\Guest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class GuestExport implements
    FromCollection,
    WithHeadings,
    WithColumnWidths,
    WithMapping,
    WithTitle
{
    protected $guest, $request, $event_id;

    private $no = 0;

    public function __construct($request, $event_id)
    {
        $this->request = $request;
        $this->event_id = $event_id;
    }

    public function title(): string
    {
        return 'Data Tamu Undangan';
    }

    public function collection()
    {
        $this->guest = Guest::with('kategori')
            ->where('event_id', $this->event_id)
            ->filter($this->request)
            ->orderBy('created_at', 'DESC')
            ->get();

        return $this->guest;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kategori',
            'Kode Token',
            'Nama Tamu',
            'Nama Undangan',
            'Status Undangan',
            'Kehadiran',
            'Relasi',
            'Jenis Undangan',
            'Keterangan'
        ];
    }

    public function map($guest): array
    {
        return [
            ++$this->no,
            optional($guest->kategori)->nama_kategori,
            $guest->kode_token,
            $guest->nama_tamu,
            $guest->nama_undangan,
            $guest->status_undangan == "1" ? "Sudah Terkirim" : "Belum Terkirim",
            $guest->status_kehadiran  == "1" ? "Sudah Hadir" : "Tidak Hadir",
            $guest->relasi,
            $guest->jenis_undangan,
            $guest->keterangan
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 30,
            'D' => 30,
            'E' => 20,
            'F' => 15,
            'G' => 20,
            'H' => 20,
            'I' => 30,
            'J' => 15
        ];
    }
}
