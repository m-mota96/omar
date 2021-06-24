<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Auth;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasRole() {
        $role_id = Auth::user()->role_id;
        return Role::find($role_id)->role;
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function documents() {
        return $this->hasMany(Document::class);
    }

    public function taxData() {
        return $this->hasOne(TaxData::class);
    }

    public function bankData() {
        return $this->hasOne(BankData::class);
    }
}
