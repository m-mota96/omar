<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    protected $fillable = [
        'user_id', 'name', 'url', 'description', 'quantity', 'email', 'phone', 'twitter', 'facebook', 'instagram', 'website', 'final_date', 'authorization', 'cost_type','model_payment', 'status','category_id', 
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

    public function paymentsAgruped() {
        return $this->hasOne(Payment::class)->selectRaw('payments.event_id, SUM(payments.amount) as total')->groupBy('payments.event_id')->where('status', 'payed');
    }

    public function assistance() {
        return $this->hasOne(Verified::class)->selectRaw('verifieds.event_id, SUM(verifieds.quantity) as assistance')->groupBy('verifieds.event_id')->where('verifieds.access_id', '!=', null);
    }
        public function category(){
        return $this->belongsTo(Category::class);
    }
}
