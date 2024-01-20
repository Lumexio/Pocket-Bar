<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethods extends Model
{
    use HasFactory;

    public function tenantUser()
    {
        return $this->belongsTo(TenantUser::class);
    }
}
