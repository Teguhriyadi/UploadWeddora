<?php

namespace App\Exports;

use App\Models\GuestPublic;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;

class GuestPublicExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    WithDefaultStyles,
    WithTitle
{
    private $no = 0;

    public function title(): string
    {
        return 'Daftar Tamu Luar';
    }

    public function collection()
    {
        return GuestPublic::all();
    }

    public function map($guest): array
    {
        return [
            ++$this->no,
            $guest->nama,
            $guest->nomor_handphone,
            $guest->pekerjaan,
            $guest->alamat,
            $guest->waktu_checkin
                ? Carbon::parse($guest->waktu_checkin)
                ->locale('id')
                ->translatedFormat('d F Y H:i:s') . ' WIB'
                : '-',
            $guest->jumlah_kedatangan,
            $guest->relasi,
            $guest->keterangan,
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Nomor Handphone',
            'Pekerjaan',
            'Alamat',
            'Waktu Checkin',
            'Jumlah Kedatangan',
            'Relasi',
            'Keterangan',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 25,
            'C' => 20,
            'D' => 25,
            'E' => 50,
            'F' => 25,
            'G' => 20,
            'H' => 25,
            'I' => 50,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // baris header
                'font' => [
                    'bold' => true,
                    'name' => 'Arial',
                    'size' => 11,
                ],
            ],
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        $defaultStyle->getFont()
            ->setName('Arial')
            ->setSize(11);

        return $defaultStyle;
    }
}
