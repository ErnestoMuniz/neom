<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class ZteController extends Controller
{
  public static function pon($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/zte/zte_count.py '$olt->ip' '$olt->username' '$olt->password' '$olt->superpass' '$req->pon'");
      $arr = explode("\n", $output);
      array_pop($arr);
      $res = [];
      foreach ($arr as $onu) {
        array_push($res, [
          'pos' => explode(' ', $onu)[0],
          'sn' => explode(' ', $onu)[3],
          'status' => explode(' ', $onu)[1] == 'working' ? 'Active' : 'Inactive',
          'signal' => explode(' ', $onu)[2],
        ]);
      }
      return response()->json($res, 200);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function position($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/nokia/isam_position.py '$olt->ip' '$olt->username' '$olt->password' '1/1/$req->pon'");
      return response($output);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function onu($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/zte/zte_search.py '$olt->ip' '$olt->username' '$olt->password' '$olt->superpass' '$req->onu'");
      return response($output);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function add($req, $olt)
  {
    if (Token::checkPermission($req, 'add_onu')) {
      $output = shell_exec("python python/nokia/isam_add.py '$olt->ip' '$olt->username' '$olt->password' '$req->pos/$req->onuPos' '$req->desc' '$req->desc2' '$req->serial' '$req->vlan' '$req->username' '$req->password' '$req->type'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function ssidPass($req, $olt)
  {
    if (Token::checkPermission($req, 'add_onu')) {
      $output = shell_exec("python python/nokia/isam_ssid_pass.py '$olt->ip' '$req->pos' '$req->ssid2' '$req->password2' '$req->ssid5' '$req->password5'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function pending($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/zte/zte_pending.py '$olt->ip' '$olt->username' '$olt->password' '$olt->superpass'");
      $arr = explode("\n", $output);
      array_pop($arr);
      $res = [];
      foreach ($arr as $onu) {
        $onu = explode(' ', $onu);
        array_push($res, [
          'pos' => $onu[0],
          'sn' => $onu[2]
        ]);
      }
      return response()->json($res, 200);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function remove($req, $olt)
  {
    if (Token::checkPermission($req, 'remove_onu')) {
      $output = shell_exec("python python/zte/zte_remove.py '$olt->ip' '$olt->username' '$olt->password' '$olt->superpass' '$req->pos'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function reboot($req, $olt)
  {
    if (Token::checkPermission($req, 'reboot_onu')) {
      $output = shell_exec("python python/zte/zte_reboot.py '$olt->ip' '$olt->username' '$olt->password' '$olt->superpass' '$req->pos'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function cpu($req, $olt)
  {
    if (Token::checkPermission($req, 'edit_olts')) {
      $output = shell_exec("python python/zte/zte_cpu.py '$olt->ip' '$olt->username' '$olt->password' '$olt->superpass'");
      $arr = explode("\n", $output);
      array_pop($arr);
      $olt->cpu = $arr[0];
      $olt->save();
      return response($arr[0]);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function firmware($req, $olt)
  {
    if (Token::checkPermission($req, 'edit_olts')) {
      $output = shell_exec("python python/zte/zte_firmware.py '$olt->ip' '$olt->username' '$olt->password' '$olt->superpass'");
      $arr = explode("\n", $output);
      array_pop($arr);
      $olt->firmware = $arr[0];
      $olt->save();
      return response($arr[0]);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function onuStatus($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/zte/zte_onu_status.py '$olt->ip' '$olt->username' '$olt->password' '$olt->superpass' '$req->onu'");
      $arr = explode(" ", str_replace("\n", '', $output));
      $res = [
        'pos' =>  $arr[0],
        'sn' => $arr[1],
        'status' => $arr[2] == 'working' ? 'Active' : 'Inactive',
        'signal' => $arr[3],
      ];
      return response()->json($res);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function onuStatusMany($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/zte/zte_onu_status_many.py '$olt->ip' '$olt->username' '$olt->password' '$olt->superpass' '$req->onus'");
      $arr = explode("\n", $output);
      array_pop($arr);
      $res = [];
      foreach ($arr as $onu) {
        $onu = explode(" ", $onu);
        array_push($res, [
            'pos' =>  $onu[0],
            'sn' => $onu[1],
            'status' => $onu[2] == 'working' ? 'Active' : 'Inactive',
            'signal' => $onu[3],
        ]);
      }
      return response()->json($res);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }
}
