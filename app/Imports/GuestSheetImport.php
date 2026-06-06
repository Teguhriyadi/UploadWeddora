<?php

namespace App\Imports;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Kategori;
use App\Support\ActivityLogger;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GuestSheetImport implements ToCollection, WithCalculatedFormulas, WithStartRow
{
    public function startRow(): int
    {
        return 6; // 🔥 data mulai dari baris 6 (B6 ke bawah)
    }

    public function collection(Collection $rows)
    {
        $event = Event::first(['*']);

        $created = 0;
        $skipped = 0;

        $cek_kategori = Kategori::where('nama_kategori', '=', "VIP", 'and')->first();

        Guest::withoutEvents(function () use ($rows, $event, $cek_kategori, &$created, &$skipped) {
            foreach ($rows as $row) {
                $data = array_values($row->toArray());

                $nama         = trim($data[1] ?? '');
                $namaUndangan = trim($data[2] ?? '');
                $status       = $this->mapStatus($data[3] ?? null);
                $relasi       = $this->mapRelasi($data[4] ?? null);
                $jenis        = $this->mapJenis($data[5] ?? null);
                $kehadiran    = $this->mapKehadiran($data[6] ?? null);
                $keterangan   = $this->mapKeterangan($data[7] ?? null);
                $kategori     = isset($data[8]) ? strtoupper(trim($data[8])) : null;
                $token        = isset($data[11]) ? strtoupper(trim($data[11])) : null;

                if ($nama === '' && $namaUndangan === '') {
                    $skipped++;
                    continue;
                }

                if ($nama !== '' && stripos($nama, 'nama_di_undangan') !== false) {
                    $skipped++;
                    continue;
                }

                Guest::create([
                    'nama_tamu'       => $nama ?: null,
                    'nama_undangan'   => $namaUndangan ?: null,
                    'status_undangan' => $status,
                    'relasi'          => $relasi,
                    'jenis_undangan'  => $jenis,
                    'kehadiran'       => $kehadiran,
                    'keterangan'      => $keterangan,
                    'kode_token'      => $token,
                    'event_id'        => $event->id,
                    'kategori_id'     => empty($kategori) ? null : ($cek_kategori?->id)
                ]);

                $created++;
            }
        });

        ActivityLogger::log('Tamu Undangan', 'import_excel', null, null, null, [
            'sheet' => 'Daftar Tamu',
            'created' => $created,
            'skipped' => $skipped,
        ]);
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
        if (is_null($value) || trim($value) === '') {
            return null;
        }

        $value = strtolower(trim($value));

        return match ($value) {
            'pasti hadir' => '1',
            'kemungkinan tidak hadir' => '0',
            'tidak hadir' => '2',
            default => null,
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
