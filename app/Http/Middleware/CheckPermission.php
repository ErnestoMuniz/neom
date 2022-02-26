<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
   * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
   */
  public function handle(Request $request, Closure $next, string $permission)
  {
    $user = Token::getToken($request->header('Token'))->user;
    if ($user != null) {
      if (in_array($permission, $user->role->permissions->pluck('name')->toArray())) {
        return $next($request);
      }
    }
    return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
  }
}
