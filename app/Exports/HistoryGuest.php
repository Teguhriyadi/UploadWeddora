<?php

namespace App\Exports;

use App\Models\GuestCheckin;
use App\Models\GuestPublic;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HistoryGuest implements FromCollection, WithHeadings
{
    protected $dari;
    protected $sampai;
    protected $tab;

    public function __construct($dari, $sampai, $tab)
    {
        $this->dari = $dari;
        $this->sampai = $sampai;
        $this->tab = $tab;
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
                        'kategori' => $item->guest->kategori->nama_kategori,
                        'nama' => $item->guest->nama_tamu,
                        'keluarga' => $item->guest->keluarga,
                        'metode' => strtoupper($item->metode),
                        'waktu' => $item->waktu_checkin
                    ];
                });
        }
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
                'Waktu Checkin'
            ];
        } else {

            return [
                'No',
                'Kategori',
                'Nama Tamu',
                'Keluarga',
                'Metode',
                'Waktu Checkin'
            ];
        }
    }
}
