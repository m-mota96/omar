<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxData extends Model
{
    protected $fillable = [
        'user_id', 'legal_representative', 'business_name', 'rfc', 'address', 'external_number', 'internal_number', 'colony', 'postal_code', 'city', 'state', 
    ];
}
