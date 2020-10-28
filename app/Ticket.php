<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'event_id', 'name', 'description', 'price', 'quantity', 'sales', 'start_sale', 'stop_sale', 'min_reservation', 'max_reservation', 'status', 
    ];

    public $timestamps = false;
}
