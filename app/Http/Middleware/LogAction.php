<?php

namespace App\Http\Middleware;

use App\Models\Olt;
use App\Models\Token;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogAction
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
    try {
      $olt = !strstr($request->route('olt'), '.') ? Olt::find($request->route('olt')) : Olt::where('ip', $request->route('olt'))->first();
      $commands = [
        'pon' => 'List PON ONUs',
        'pending' => 'List pending ONUs',
        'onu' => 'Search for ONU',
        'onuStatus' => 'Get ONU status',
        'add' => 'Provision ONU',
        'remove' => 'Remove ONU',
        'reboot' => 'Reboot ONU',
        'cpu' => 'Get OLT CPU usage',
        'firmware' => 'Get OLT firmware version',
        'sp' => 'Get Service Ports',
        'rmSp' => 'Remove Service Port'
      ];
      Log::channel('actions')->info($commands[$request->route('cmd')], ['user' => Token::getToken($request->header('Token'))->user->id, 'olt' => $olt->id]);
    } catch (\Throwable $th) {
    }
    return $next($request);
  }
}
