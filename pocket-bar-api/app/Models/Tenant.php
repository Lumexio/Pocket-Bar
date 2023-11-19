<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'tenant_user_id',
            'expiration_date',
        ];
    }

    public function suscription_expired()
    {
        return $this->expiration_date < now();
    }

    // crear una relación con el modelo TenantUser de muchos a uno
    public function tenantUser()
    {
        return $this->belongsTo(TenantUser::class);
    }
}
