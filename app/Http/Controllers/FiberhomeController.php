<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;

class FiberhomeController extends Controller
{
  public function pon($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/fiberhome/fh_count.py '$olt->ip' '$olt->username' '$olt->password' '$req->pon'");
      $arr = explode("\n", $output);
      unset($arr[count($arr) - 1]);
      unset($arr[count($arr) - 1]);
      $res = [];
      foreach ($arr as $onu) {
        array_push($res, [
          "pos" => explode(' ', $onu)[0] . '/' . explode(' ', $onu)[1] . '/' . explode(' ', $onu)[2],
          "status" => (explode(' ', $onu)[6] === 'up') ? 'Active' : 'Inactive',
          "description" => explode(' ', $onu)[3],
          "signal" => explode(' ', $onu)[8],
          "sn" => explode(' ', $onu)[7],
        ]);
      }
      return response()->json($res, 200);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function pending($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/fiberhome/fh_pending.py '$olt->ip' '$olt->username' '$olt->password'");
      $arr = explode("\n", $output);
      unset($arr[count($arr) - 1]);
      unset($arr[count($arr) - 1]);
      $onu = [];
      foreach ($arr as $line) {
        array_push($onu, [
          'pos' => explode(' ', $line)[0],
          'sn' => explode(' ', $line)[1]
        ]);
      }
      return response()->json($onu, 200);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function onu($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/fiberhome/fh_search.py '$olt->ip' '$olt->username' '$olt->password' '$req->onu'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function reboot($req, $olt)
  {
    if (Token::checkPermission($req, 'reboot_onu')) {
      $output = shell_exec("python python/fiberhome/fh_reboot.py '$olt->ip' '$olt->username' '$olt->password' '$req->pos'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function remove($req, $olt)
  {
    if (Token::checkPermission($req, 'remove_onu')) {
      $output = shell_exec("python python/fiberhome/fh_remove.py '$olt->ip' '$olt->username' '$olt->password' '$req->pos'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function add($req, $olt)
  {
    if (Token::checkPermission($req, 'add_onu')) {
      $output = shell_exec("python python/fiberhome/fh_add.py '$olt->unm' '$olt->ip' '$olt->username' '$olt->password' '$req->pos' '$req->desc' '$req->serial' '$req->vlan' '$req->type' '$req->username' '$req->password'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function firmware($req, $olt)
  {
    if (Token::checkPermission($req, 'edit_olts')) {
      $output = shell_exec("python python/fiberhome/fh_firmware.py '$olt->ip' '$olt->username' '$olt->password'");
      $olt->firmware = $output;
      $olt->save();
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function cpu($req, $olt)
  {
    if (Token::checkPermission($req, 'edit_olts')) {
      $output = shell_exec("python python/fiberhome/fh_cpu.py '$olt->ip' '$olt->username' '$olt->password'");
      $olt->cpu = $output;
      $olt->save();
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }
}
