<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class V_Payment extends Model
{
    protected $table = 'v_payments';

    public function accesses() {
        return $this->hasMany(Access::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }
}
