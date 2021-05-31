<?php

namespace App\Exports;

use App\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PaymentsExport implements FromCollection, WithHeadings, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($idEvent) {
        $this->id_event = $idEvent;
    }

    public function headings(): array
    {
        return [
            '#',
            'Nombre',
            'Correo',
            'Teléfono',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 35,            
            'C' => 45,            
            'D' => 25,            
        ];
    }

    public function collection()
    {
        return Payment::where('event_id', $this->id_event)->select('id', 'name', 'email', 'phone')->get()->groupBy('name');
    }
}
