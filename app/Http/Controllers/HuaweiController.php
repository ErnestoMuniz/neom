<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;

class HuaweiController extends Controller
{
  public static function pon($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/huawei/huawei_count.py '$olt->ip:$olt->port' '$olt->username' '$olt->password' '$req->pon'");
      $arr = explode("\n", $output);
      $res = [];
      foreach ($arr as $onu) {
        try {
          array_push($res, [
            'pos' => $req->pon . "/" . explode(' ', $onu)[0],
            'status' => explode(' ', $onu)[3] != '-' ? 'Active' : 'Inactive',
            'description' => strlen(explode(' ', $onu)[5]) > 20 ? explode(' ', $onu)[5] . '...' : explode(' ', $onu)[5],
            'signal' => explode('/', explode(' ', $onu)[4])[0],
            'sn' => "HWTC-" . substr(explode(' ', $onu)[1], -8)
          ]);
        } catch (\Throwable $th) {
        }
      }
      return response()->json($res, 200);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function position($req)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      return response(127);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function pending($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/huawei/huawei_pending.py '$olt->ip:$olt->port' '$olt->username' '$olt->password'");
      $onus = explode("\n", $output);
      $res = [];
      foreach ($onus as $onu) {
        try {
          array_push($res, [
            'pos' => explode(' ', $onu)[0],
            'sn' => explode(' ', $onu)[1]
          ]);
        } catch (\Throwable $th) {
        }
      }
      return response()->json($res, 200);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function firmware($req, $olt)
  {
    if (Token::checkPermission($req, 'edit_olts')) {
      $output = shell_exec("python python/huawei/huawei_firmware.py '$olt->ip:$olt->port' '$olt->username' '$olt->password'");
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
      $output = shell_exec("python python/huawei/huawei_cpu.py '$olt->ip:$olt->port' '$olt->username' '$olt->password'");
      $olt->cpu = $output;
      $olt->save();
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function onu($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/huawei/huawei_search.py '$olt->ip:$olt->port' '$olt->username' '$olt->password' '$req->onu'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function remove($req, $olt)
  {
    if (Token::checkPermission($req, 'remove_onu')) {
      $output = shell_exec("python python/huawei/huawei_remove.py '$olt->ip:$olt->port' '$olt->username' '$olt->password' '$req->pos'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function reboot($req, $olt)
  {
    if (Token::checkPermission($req, 'reboot_onu')) {
      $output = shell_exec("python python/huawei/huawei_reboot.py '$olt->ip:$olt->port' '$olt->username' '$olt->password' '$req->pos'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function sp($req, $olt)
  {
    if (Token::checkPermission($req, 'remove_onu')) {
      $output = shell_exec("python python/huawei/huawei_sp.py '$olt->ip:$olt->port' '$olt->username' '$olt->password' '$req->pon' '$req->pos'");
      $arr = explode("\n", $output);
      unset($arr[count($arr) - 1]);
      $res = [];
      foreach ($arr as $sp) {
        array_push($res, [
          'index' => explode(' ', $sp)[0],
          'vlan_id' => explode(' ', $sp)[1],
          'status' => explode(' ', $sp)[12],
        ]);
      }
      return response()->json($res, 200);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function rmSp($req, $olt)
  {
    if (Token::checkPermission($req, 'remove_onu')) {
      $output = shell_exec("python python/huawei/huawei_remove_sp.py '$olt->ip:$olt->port' '$olt->username' '$olt->password' '$req->idx'");
      return response($output);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function onuStatus($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/huawei/huawei_onu_status.py '$olt->ip:$olt->port' '$olt->username' '$olt->password' '$req->onu'");
      $res = str_replace("'", '"', $output);
      return response($res);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function onuStatusMany($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/huawei/huawei_onu_status_many.py '$olt->ip:$olt->port' '$olt->username' '$olt->password' '$req->onu'");
      $res = str_replace("'", '"', $output);
      return response($res);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }
}
