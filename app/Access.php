<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $fillable = [
        'payment_id', 'ticket_id', 'folio', 'status', 'quantity', 'date_validation', 
    ];

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }

    public function payment() {
        return $this->belongsTo(Payment::class);
    }

    public function turns() {
        return $this->belongsToMany(Turn::class);
    }
}
