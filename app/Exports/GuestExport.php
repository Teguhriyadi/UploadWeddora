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
            'Nama Tamu',
            'Nama Undangan',
            'Status Undangan',
            'Kehadiran',
            'Relasi',
            'Jenis Undangan',
            'Keterangan',
            'QR Code',
        ];
    }

    public function map($guest): array
    {
        return [
            optional($guest->kategori)->nama_kategori,
            $guest->kode_token,
            $guest->nama_tamu,
            $guest->nama_undangan,
            $guest->status_undangan,
            $guest->status_kehadiran ? 'Sudah Hadir' : 'Tidak Hadir',
            $guest->relasi,
            $guest->jenis_undangan,
            $guest->keterangan,
            '',
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2;

        foreach ($this->guest as $guest) {

            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . $guest->kode_token;

            $image = imagecreatefromstring(
                file_get_contents($qrUrl)
            );

            $drawing = new MemoryDrawing();
            $drawing->setName('QR Code');
            $drawing->setDescription('QR Code');
            $drawing->setImageResource($image);
            $drawing->setRenderingFunction(
                MemoryDrawing::RENDERING_PNG
            );
            $drawing->setMimeType(
                MemoryDrawing::MIMETYPE_DEFAULT
            );
            $drawing->setHeight(70);
            $drawing->setCoordinates('J' . $row);

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
                    $event->sheet
                        ->getRowDimension($i)
                        ->setRowHeight(60);
                }

                $event->sheet
                    ->getStyle('A1:J1')
                    ->getFont()
                    ->setBold(true);

                $event->sheet
                    ->getStyle('A1:J' . $total)
                    ->getAlignment()
                    ->setHorizontal(
                        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                    );

                $event->sheet
                    ->getStyle('A1:J' . $total)
                    ->getAlignment()
                    ->setVertical(
                        \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    );
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 30,
            'D' => 30,
            'E' => 20,
            'F' => 15,
            'G' => 20,
            'H' => 20,
            'I' => 30,
            'J' => 15,
        ];
    }
}
