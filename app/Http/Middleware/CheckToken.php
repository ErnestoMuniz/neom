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
            if (
                $request->header("Token") != null &&
                Token::getToken($request->header("Token")) != null
            ) {
                return $next($request);
            }
        } elseif (env("EXTERNAL_AUTH_ENDPOINT") != "") {
            if (env("EXTERNAL_AUTH_METHOD") == "GET") {
                $response = Http::withHeaders([
                    env("EXTERNAL_AUTH_HEADER", "Authorization") => str_replace(
                        "ext ",
                        str_replace("\s", " ", env("EXTERNAL_AUTH_HEADER_PREFIX")),
                        $request->header("Token")
                    ),
                ])->get(env("EXTERNAL_AUTH_ENDPOINT"), [
                    "token" => str_replace(
                        "ext ",
                        str_replace("\s", " ", env("EXTERNAL_AUTH_HEADER_PREFIX")),
                        $request->header(env("EXTERNAL_AUTH_HEADER", "Authorization"))
                    ),
                ]);
            } else {
                $response = Http::withHeaders([
                    env("EXTERNAL_AUTH_HEADER", "Authorization") => str_replace(
                        "ext ",
                        str_replace("\s", " ", env("EXTERNAL_AUTH_HEADER_PREFIX")),
                        $request->header("Token")
                    ),
                ])->post(env("EXTERNAL_AUTH_ENDPOINT"), [
                    "token" => str_replace(
                        "ext ",
                        str_replace("\s", " ", env("EXTERNAL_AUTH_HEADER_PREFIX")),
                        $request->header(env("EXTERNAL_AUTH_HEADER", "Authorization"))
                    ),
                ]);
            }
            if ($response->status() == 200) {
                return $next($request);
            }
        }
        return response()->json(["status" => "401", "message" => "You are not logged in"], 401);
    }
}
