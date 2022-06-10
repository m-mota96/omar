<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = [
        'ticket_id', 'code', 'quantity', 'used', 'discount', 'status', 
    ];

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }
}
