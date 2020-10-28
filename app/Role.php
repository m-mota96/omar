<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'role', 
    ];

    public $timestamps = false;

    public function user() {
        return $this->hasOne(User::class);
    }
}
