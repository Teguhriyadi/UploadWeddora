<?php

namespace App\Exports;

use App\Models\Guest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class GuestExport implements FromCollection, WithHeadings, WithColumnWidths, WithMapping, WithDrawings, WithEvents, WithTitle
{
    protected $guest;

    public function title(): string
    {
        return 'Data Tamu Undangan';
    }

    public function collection()
    {
        $this->guest = Guest::with('kategori')->get();
        return $this->guest;
    }

    public function headings(): array
    {
        return [
            'Kategori',
            'Kode Token',
            'Nama Undangan',
            'Keluarga',
            'Jumlah Undangan',
            'QR Code'
        ];
    }

    public function map($guest): array
    {
        return [
            $guest->kategori->nama_kategori,
            $guest->kode_token,
            $guest->nama_tamu,
            $guest->keluarga,
            $guest->jumlah_undangan,
            ''
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2;

        foreach ($this->guest as $guest) {

            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . $guest->kode_token;

            $image = imagecreatefromstring(file_get_contents($qrUrl));

            $drawing = new MemoryDrawing();
            $drawing->setName('QR Code');
            $drawing->setDescription('QR Code');
            $drawing->setImageResource($image);
            $drawing->setRenderingFunction(MemoryDrawing::RENDERING_PNG);
            $drawing->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
            $drawing->setHeight(70);
            $drawing->setCoordinates('F' . $row);

            $drawings[] = $drawing;

            $row++;
        }

        return $drawings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function ($event) {

                $total = count($this->guest) + 1;

                for ($i = 2; $i <= $total; $i++) {
                    $event->sheet->getRowDimension($i)->setRowHeight(60);
                }

                $event->sheet->getStyle('A1:F1')->getFont()->setBold(true);

                $event->sheet->getStyle('A1:F' . $total)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getStyle('A1:F' . $total)
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 15,
            'C' => 25,
            'D' => 20,
            'E' => 15,
            'F' => 15
        ];
    }
}
