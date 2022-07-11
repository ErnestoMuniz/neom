<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;

class DatacomController extends Controller
{
  public static function pon($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/datacom/datacom_count.py '$olt->ip:$olt->port' '$olt->username' '$olt->password' '$req->pon'");
      $arr = explode("\n", $output);
      array_pop($arr);
      $idx = 0;
      $res = [];
      foreach ($arr as $onu) {
        $res[$idx] = [
          'pos' => (string) explode(' ', $onu)[0],
          'sn' => (string) explode(' ', $onu)[3],
          'status' => (string) explode(' ', $onu)[1],
          'signal' => (string) explode(' ', $onu)[2],
          'description' => '',
        ];
        $idx++;
      }
      return response()->json($res, 200);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function cpu($req, $olt)
  {
    if (Token::checkPermission($req, 'edit_olts')) {
      $output = shell_exec("python python/datacom/datacom_cpu.py '$olt->ip:$olt->port' '$olt->username' '$olt->password'");
      $olt->cpu = $output;
      $olt->save();
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function firmware($req, $olt)
  {
    if (Token::checkPermission($req, 'edit_olts')) {
      $output = shell_exec("python python/datacom/datacom_firmware.py '$olt->ip:$olt->port' '$olt->username' '$olt->password'");
      $olt->firmware = $output;
      $olt->save();
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function onu($req, $olt)
  {
    if (Token::checkPermission($req, 'edit_olts')) {
      $output = shell_exec("python python/datacom/datacom_search.py '$olt->ip:$olt->port' '$olt->username' '$olt->password' '$req->onu'");
      $pon = explode(' ', $output)[0];
      $pos = explode(' ', $output)[5];
      $pon = explode('/', $pon)[1] . '/' . explode('/', $pon)[2];
      echo "$pon/$pos";
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }
}
