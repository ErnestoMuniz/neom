<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;

class LogController extends Controller
{
  public function actionCount()
  {
    $log = fopen('../storage/logs/neom.log', 'r') or die(response()->json(['status' => '404', 'message' => 'No log file found!'], 404));
    $log = fread($log, filesize('../storage/logs/neom.log'));
    $log = explode("\n", $log);
    array_pop($log);
    return response()->json(['count' => count($log)], 200);
  }

  public function actionUserCount(Request $req)
  {
    $id = Token::getToken($req->header('Token'))->user->id;
    $log = fopen('../storage/logs/neom.log', 'r') or die(response()->json(['status' => '404', 'message' => 'No log file found!'], 404));
    $log = fread($log, filesize('../storage/logs/neom.log'));
    $linhas = explode("\n", $log);
    array_pop($linhas);
    $linhasUser = [];
    foreach ($linhas as $linha) {
      if (strstr($linha, '"user":'.$id.',')) {
        array_push($linhasUser, $linha);
      }
    }
    return response()->json(['count' => count($linhasUser)], 200);
  }
}
