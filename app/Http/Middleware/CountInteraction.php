<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Interaction;

class CountInteraction
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
        Interaction::create([
            'user_id' => $request->user()->id,
            'olt_id' => $request->query('id'),
            'action' => $request->route('function')
        ]);

        return $next($request);
    }
}
