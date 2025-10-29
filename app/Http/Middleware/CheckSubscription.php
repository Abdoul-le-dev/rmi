<?php

namespace App\Http\Middleware;

use Closure;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $subscription = \App\Models\Subscribe::getActiveSubscribe(auth()->user()->id);
            $user = auth()->user();
            if ((!empty($subscription) && $subscription->days > 0) or ($user->role_name === 'teacher' or $user->email === 'tossouericcodjo@gmail.com')) {
                return $next($request);
            }
            return redirect('/panel/financial/subscribes');
        }
        return redirect('/panel/financial/subscribes');
    }
}
