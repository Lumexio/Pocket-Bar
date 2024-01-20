<?php

namespace App\Models;

use Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TenantUser extends Model
{
    use HasFactory, HasApiTokens;

    protected $hidden = [
        'password',
    ];

    // crear un mutador para encriptar la contraseña
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // crear una relación con el modelo Tenant de uno a muchos
    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethods::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }
}
