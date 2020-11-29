<?php

namespace App\Http\Middleware;

use App\Exceptions\UserNotActivatedException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class UserActivation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $email = $request->get('email');

        if (auth()->user()->isActivated()) {
            throw new UserNotActivatedException();
        }

        return $next($request);
    }
}
