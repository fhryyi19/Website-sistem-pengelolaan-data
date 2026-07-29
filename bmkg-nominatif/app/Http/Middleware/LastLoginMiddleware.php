<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LastLoginMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ($user->last_login === null || $user->last_login->diffInMinutes(now()) > 30)) {
            $user->timestamps = false; // Don't trigger updated_at
            $user->last_login = now();
            $user->save();
        }

        return $next($request);
    }
}
