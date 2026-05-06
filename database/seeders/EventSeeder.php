<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::create([
            "nama_event" => "Lomba 17 Agustus",
            "tanggal" => "2002-02-02 10:00:00",
            "lokasi" => "Hotel Zamrud, Cirebon"
        ]);
    }
}
