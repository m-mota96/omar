<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GalleryEvent extends Model
{
    protected $fillable = [
        'event_id', 'name', 'type', 
    ];
}
