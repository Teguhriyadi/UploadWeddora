<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = [
            ["nama" => "Cabang Indramayu", "kota" => "Indramayu", "alamat" => "Jln. LohBener Lama"],
            ["nama" => "Cabang Cirebon", "kota" => "Cirebon", "alamat" => "VIlla Intan 2 Blok i4 No.1"],
            ["nama" => "Cabang Kuningan", "kota" => "Kuningan", "alamat" => "Jln. Cilimus Raya"],
            ["nama" => "Cabang Jakarta", "kota" => "Jakarta", "alamat" => "Jln. Cengkareng"],
        ];

        foreach ($cabang as $data) {
            Cabang::create([
                "nama" => $data["nama"],
                "kota" => $data["kota"],
                "alamat" => $data["alamat"],
                "status" => "AKTIF"
            ]);
        }
    }
}
