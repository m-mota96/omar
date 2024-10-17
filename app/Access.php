<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $fillable = [
        'payment_id', 'ticket_id', 'code_id', 'folio', 'name', 'email', 'phone', 'status', 'quantity', 'date_validation', 
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
    
    public function code() {
        return $this->belongsTo(Code::class);
    }

    public function codes() {
        return $this->belongsToMany(Code::class)->withPivot('ticket_price', 'discount');
    }
}
