<?php

namespace App\Exports;

use App\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Support\Facades\DB;

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
            'Monto',
            'Método de pago',
            'Estatus',
            'Fecha'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 35,            
            'C' => 45,            
            'D' => 25,
            'E' => 10,
            'F' => 15,
            'G' => 15,
            'H' => 20
        ];
    }

    public function collection()
    {
        return Payment::where('event_id', $this->id_event)->select(
            'id', 
            'name', 
            'email', 
            'phone', 
            'amount', 
            DB::raw("IF(type = 'card', 'Tarjeta', 'Oxxo')"), 
            DB::raw("IF(status = 'payed', 'Pagado', IF(status = 'pending', 'Pendiente', 'Expirado'))"), 
            DB::raw("STR_TO_DATE(created_at, '%Y-%m-%d %H:%i:%s')")
        )->orderBy('id', 'DESC')->get();
    }
}
