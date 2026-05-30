<?php

namespace App\Imports;

use App\Models\Event;
use App\Models\Guest;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GuestSheetImport implements ToCollection, WithCalculatedFormulas, WithStartRow
{
    public function startRow(): int
    {
        return 6; // 🔥 data mulai dari baris 6 (B6 ke bawah)
    }

    public function collection(Collection $rows)
    {
        $event = Event::first();

        foreach ($rows as $row) {

            $data = array_values($row->toArray());

            $nama         = trim($data[1] ?? '');
            $namaUndangan = trim($data[2] ?? '');
            $status       = $this->mapStatus($data[3] ?? null);
            $relasi       = $this->mapRelasi($data[4] ?? null);
            $jenis        = $this->mapJenis($data[5] ?? null);
            $kehadiran    = $this->mapKehadiran($data[6] ?? null);
            $keterangan   = $this->mapKeterangan($data[7] ?? null);
            $token        = isset($data[10]) ? strtoupper(trim($data[11])) : null;

            if ($nama === '' && $namaUndangan === '') {
                continue;
            }

            if (stripos($nama, 'nama_di_undangan') !== false) continue;

            Guest::create([
                'nama_tamu'       => $nama ?: null,
                'nama_undangan'   => $namaUndangan ?: null,
                'status_undangan' => $status,
                'relasi'          => $relasi,
                'jenis_undangan'  => $jenis,
                'kehadiran'       => $kehadiran,
                'keterangan'      => $keterangan,
                'kode_token'      => $token,
                'event_id'        => $event->id
            ]);
        }
    }

    private function mapRelasi($value)
    {
        $value = strtolower(trim($value));

        return match (true) {
            str_contains($value, 'saudara') => 'Saudara',
            str_contains($value, 'kerja') => 'Teman Kerja',
            str_contains($value, 'sma') => 'Teman SMA',
            str_contains($value, 'ortu') => 'Relasi Ortu',
            str_contains($value, 'kuliah') => 'Teman Kuliah',
            str_contains($value, 'atasan') => 'Atasan',
            default => 'Saudara', // fallback aman
        };
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

    private function mapJenis($value)
    {
        $value = strtolower(trim($value));

        return match (true) {
            str_contains($value, 'digital') => 'Digital',
            str_contains($value, 'cetak') => 'Cetak',
            default => 'Digital', // fallback aman
        };
    }

    private function mapKeterangan($value)
    {
        $value = strtoupper(trim($value));

        return match ($value) {
            'CPP' => 'CPP',
            'CPW' => 'CPW',
            default => 'CPP', // fallback aman
        };
    }
}
