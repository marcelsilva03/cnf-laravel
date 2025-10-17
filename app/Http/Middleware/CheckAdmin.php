<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role->name !== 'admin') {
            return redirect('/unauthorized'); // Redirect or abort if not admin
        }

        return $next($request);
    }
}
