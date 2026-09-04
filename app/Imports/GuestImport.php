<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class GuestImport implements WithMultipleSheets
{
    protected $eventId;
    
    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function sheets(): array
    {
        return [
            'Daftar Tamu' => new GuestSheetImport($this->eventId),
        ];
    }
}
