<?php

namespace App\Exports;

use App\Models\GuestCheckin;
use App\Models\GuestPublic;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class HistoryGuest implements FromCollection, WithHeadings, WithTitle, WithColumnWidths
{
    protected $dari, $sampai, $tab;

    public function __construct($dari, $sampai, $tab)
    {
        $this->dari = $dari;
        $this->sampai = $sampai;
        $this->tab = $tab;
    }

    public function title(): string
    {
        return $this->tab == 'tamu-luar'
            ? 'Daftar Tamu Luar'
            : 'Tamu Undangan';
    }

    public function collection()
    {
        if ($this->tab == 'tamu-luar') {

            return GuestPublic::whereBetween('waktu_checkin', [
                $this->dari . ' 00:00:00',
                $this->sampai . ' 23:59:59'
            ])
                ->orderBy('waktu_checkin', 'DESC')
                ->get()
                ->map(function ($item, $index) {

                    return [
                        'no' => $index + 1,
                        'nama' => $item->nama,
                        'pekerjaan' => $item->pekerjaan ?? '-',
                        'no_hp' => $item->nomor_handphone ?? '-',
                        'alamat' => $item->alamat ?? '-',
                        'relasi' => $item->relasi,
                        'keterangan' => $item->keterangan,
                        'waktu' => Carbon::parse($item->waktu_checkin)
                            ->locale('id')
                            ->translatedFormat('d F Y H:i:s')
                    ];
                });
        } else {

            return GuestCheckin::with('guest.kategori')
                ->whereBetween('waktu_checkin', [
                    $this->dari . ' 00:00:00',
                    $this->sampai . ' 23:59:59'
                ])
                ->orderBy('waktu_checkin', 'DESC')
                ->get()
                ->map(function ($item, $index) {

                    return [
                        'no' => $index + 1,
                        'kategori' => empty($item->guest->kategori) ? null : $item->guest->kategori->nama_kategori,
                        'token' => $item->guest->kode_token,
                        'nama' => $item->guest->nama_tamu,
                        'nama_undangan' => $item->guest->nama_undangan,
                        'jenis_undangan' => $item->guest->jenis_undangan,
                        'relasi' => $item->guest->relasi,
                        'keterangan' => $item->guest->keterangan,
                        'metode' => strtoupper($item->metode),
                        'waktu' => Carbon::parse($item->waktu_checkin)
                            ->locale('id')
                            ->translatedFormat('d F Y H:i:s') . ' WIB'
                    ];
                });
        }
    }

    public function columnWidths(): array
    {
        if ($this->tab == 'tamu-luar') {

            return [
                'A' => 8,
                'B' => 30,
                'C' => 25,
                'D' => 20,
                'E' => 40,
                'F' => 25,
                'G' => 20,
                'H' => 15
            ];
        }

        return [
            'A' => 8,
            'B' => 20,
            'C' => 18,
            'D' => 30,
            'E' => 30,
            'F' => 20,
            'G' => 20,
            'H' => 35,
            'I' => 20,
            'J' => 25,
        ];
    }

    public function headings(): array
    {
        if ($this->tab == 'tamu-luar') {

            return [
                'No',
                'Nama',
                'Pekerjaan',
                'No. Handphone',
                'Alamat',
                'Relasi',
                'Keterangan',
                'Waktu Checkin'
            ];
        } else {

            return [
                'No',
                'Kategori',
                'Kode Token',
                'Nama Tamu',
                'Nama Undangan',
                'Jenis Undangan',
                'Relasi',
                'Keterangan',
                'Metode Kehadiran',
                'Waktu Checkin'
            ];
        }
    }
}
