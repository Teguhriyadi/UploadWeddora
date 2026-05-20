<?php

namespace App\Imports;

use App\Models\Event;
use App\Models\Guest;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class GuestSheetImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $dataRows = $rows->slice(11); // mulai dari baris 12

        $event = Event::first();

        foreach ($rows as $index => $row) {

            if ($index < 11) continue;

            $nama = $row[1] ?? null;

            if (!$nama) continue;

            Guest::create([
                'nama_tamu'       => $nama,
                'nama_undangan'   => $row[2] ?? null,
                'status_undangan' => $this->mapStatus($row[3] ?? null),
                'relasi'          => $row[4] ?? null,
                'jenis_undangan'  => $row[5] ?? null,
                'kehadiran'       => $this->mapKehadiran($row[6] ?? null),
                'keterangan'      => $row[7] ?? null,
                'kode_token'           => $row[9] ?? null,
                'event_id'        => $event["id"]
            ]);
        }
    }

    private function mapStatus($value)
    {
        $value = strtolower(trim($value));

        return match ($value) {
            'terkirim' => '1',
            'belum terkirim' => '0',
            default => '0',
        };
    }

    private function mapKehadiran($value)
    {
        $value = strtolower(trim($value));

        return match ($value) {
            'Pasti Hadir' => '1',
            'Kemungkinan Tidak Hadir' => '0',
            default => '0',
        };
    }
}
