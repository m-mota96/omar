<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankData extends Model
{
    protected $fillable = [
        'user_id', 'name_propietary', 'bank', 'key', 'number_account', 
    ];
}
