<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CheckToken
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
        if (Token::internal($request)) {
            if ($request->header('Token') != null && Token::getToken($request->header('Token')) != null) {
                return $next($request);
            }
        } else if (env('EXTERNAL_AUTH') != "") {
            $response = Http::post(env('EXTERNAL_AUTH'), [
                'token' => $request->header('Token')
            ]);
            if ($response->status() == 200) {
                return $next($request);
            }
        }
        return response()->json(['status' => '401', 'message' => 'You are not logged in'], 401);
    }
}
