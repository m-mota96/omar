<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Verified extends Model
{
    protected $fillable = [
        'event_id', 'access_id', 'quantity', 'date', 'time', 
    ];

    public $timestamps = false;

    public function access() {
        return $this->belongsTo(Access::class);
    }
}
