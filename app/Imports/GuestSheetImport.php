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
        $event = Event::first();

        foreach ($rows as $index => $row) {

            if ($index < 11) continue;

            $nama  = trim($row[1] ?? '');
            $token = trim($row[10] ?? '');

            // wajib ada nama
            if (empty($nama)) {
                continue;
            }

            // cek duplicate HANYA kalau token ada
            if (!empty($token)) {

                $exists = Guest::where('kode_token', $token)->exists();

                if ($exists) {
                    continue;
                }
            }

            Guest::create([
                'nama_tamu'       => $nama,
                'nama_undangan'   => $row[2] ?? null,
                'status_undangan' => $this->mapStatus($row[3] ?? null),
                'relasi'          => $row[4] ?? null,
                'jenis_undangan'  => $row[5] ?? null,
                'kehadiran'       => $this->mapKehadiran($row[6] ?? null),
                'keterangan'      => $row[7] ?? null,
                'kode_token'      => $token ?: null,
                'event_id'        => $event->id
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
            'pasti hadir' => '1',
            'kemungkinan tidak hadir' => '0',
            default => '0',
        };
    }
}
