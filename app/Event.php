<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'user_id', 'name', 'url', 'description', 'quantity', 'email', 'phone', 'twitter', 'facebook', 'instagram', 'website', 'final_date', 'authorization', 'model_payment', 'status', 
    ];

    public function profile() {
        return $this->hasOne(GalleryEvent::class)->where('type', 'index');
    }

    public function logo() {
        return $this->hasOne(GalleryEvent::class)->where('type', 'logo');
    }

    public function eventDates() {
        return $this->hasMany(EventDate::class);
    }

    public function location() {
        return $this->hasOne(LocationEvent::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class)->where('status', 'payed');
    }
}
