<?php

namespace App\Http\Middleware;

use App\Exceptions\SuscriptionExpiredException;
use Closure;
use Illuminate\Http\Request;

class VerifyTenantSuscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $tenant = tenancy()->tenant;
        if ($tenant->suscription_expired()) {
            throw new SuscriptionExpiredException('Suscription expired', 403);
        }
        return $next($request);
    }
}
