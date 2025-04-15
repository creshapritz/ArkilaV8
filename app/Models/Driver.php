<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'name',
        'email',
        'contact_number',
        'profile_picture',
        'license_front',
        'license_back',
        'second_id_front',
        'second_id_back',
        'company_name',
        'status'
    ];
    public function driverAdmin()
    {
        return $this->hasMany(DriverAdmin::class);
    }
    public function partner()
    {
        return $this->belongsTo(Partner::class); // assuming drivers belong to a partner
    }
    public function favoredByClients()
    {
        return $this->belongsToMany(Client::class, 'favorite_drivers')->withTimestamps();
    }



}
