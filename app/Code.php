<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = [
        'email', 'customer_name', 'code', 'quantity', 'discount', 'expiration', 'status', 
    ];

    public function tickets() {
        return $this->belongsToMany(Ticket::class)->withPivot('used');
    }
}
