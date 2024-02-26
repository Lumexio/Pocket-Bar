<?php

namespace App\Http\Middleware;

use App\Exceptions\SuscriptionExpiredException;
use Auth;
use Closure;
use Illuminate\Http\Request;

class VerifyUserSubscription
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
        /**
         * @var \App\Models\Subscription $subscription
         */
        $subscription = Auth::user()->subscription->orderBy('created_at', 'desc')->first();
        if (isset($subscription) and $subscription->subscription_expired()) {
            throw new SuscriptionExpiredException('Suscription expired', 403);
        }
        return $next($request);
    }
}
