<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public function subscription_expired()
    {
        return $this->ends_at < now();
    }

    public function tenantUser()
    {
        return $this->belongsTo(TenantUser::class);
    }
}
