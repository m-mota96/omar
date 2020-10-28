<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationEvent extends Model
{
    protected $fillable = [
        'event_id', 'name', 'address', 'latitude', 'longitude', 
    ];

    public $timestamps = false;
}
