<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class NokiaController extends Controller
{
  public static function pon($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/nokia/isam_count.py '$olt->ip' '$olt->username' '$olt->password' '1/1/$req->pon'");
      $arr = explode("\n", $output);
      array_shift($arr);
      unset($arr[count($arr) - 1]);
      unset($arr[count($arr) - 1]);
      $xml = new SimpleXMLElement(implode("\n", $arr));
      $xml = $xml->hierarchy->hierarchy->hierarchy->hierarchy->hierarchy->instance;
      $res = [];
      $resId = 'res-id';
      $idx = 0;
      foreach ($xml as $onu) {
        $res[$idx] = [
          'pos' => (string) $onu->$resId[1],
          'sn' => (string) $onu->info[0],
          'status' => (string) $onu->info[2] == 'up' ? 'Active' : 'Inactive',
          'signal' => (string) $onu->info[3] == 'invalid' ? '-40.0' : (string) $onu->info[3],
          'description' => (string) $onu->info[5],
        ];
        $idx++;
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
      $output = shell_exec("python python/nokia/isam_search.py '$olt->ip' '$olt->username' '$olt->password' '$req->onu'");
      $arr = explode("\n", $output);
      array_shift($arr);
      unset($arr[count($arr) - 1]);
      unset($arr[count($arr) - 1]);
      $xml = new SimpleXMLElement(implode("\n", $arr));
      $idx = $xml->hierarchy->hierarchy->hierarchy->hierarchy->instance->info;
      echo preg_replace('/1\/1\//', '', $idx, 1);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function add($req, $olt)
  {
    if (Token::checkPermission($req, 'add_onu')) {
      $output = shell_exec("python python/nokia/isam_add.py '$olt->ip' '$olt->username' '$olt->password' '$req->pos/$req->onuPos' '$req->desc' '$req->desc2' '$req->serial' '$req->vlan' '$req->username' '$req->password' '$req->type' '$olt->superuser' '$olt->superpass'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function ssidPass($req, $olt)
  {
    if (Token::checkPermission($req, 'add_onu')) {
      $output = shell_exec("python python/nokia/isam_ssid_pass.py '$olt->ip' '$req->pos' '$req->ssid2' '$req->password2' '$req->ssid5' '$req->password5'  '$olt->superuser' '$olt->superpass'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function pending($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/nokia/isam_pending.py '$olt->ip' '$olt->username' '$olt->password'");
      $arr = explode("\n", $output);
      array_shift($arr);
      unset($arr[count($arr) - 1]);
      unset($arr[count($arr) - 1]);
      $xml = new SimpleXMLElement(implode("\n", $arr));
      $xml = $xml->hierarchy->hierarchy->hierarchy->instance;
      $res = [];
      foreach ($xml as $onu) {
        array_push($res, [
          'pos' => (string) $onu->info[0],
          'sn' => substr_replace((string) $onu->info[1], ':', 4, 0)
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
      $output = shell_exec("python python/nokia/isam_remove.py '$olt->ip' '$olt->username' '$olt->password' '$req->pos'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function reboot($req, $olt)
  {
    if (Token::checkPermission($req, 'reboot_onu')) {
      $output = shell_exec("python python/nokia/isam_reboot.py '$olt->ip' '$olt->username' '$olt->password' '$req->pos'");
      echo $output;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function cpu($req, $olt)
  {
    if (Token::checkPermission($req, 'edit_olts')) {
      $output = shell_exec("python python/nokia/isam_cpu.py '$olt->ip' '$olt->username' '$olt->password'");
      $arr = explode("\n", $output);
      array_shift($arr);
      unset($arr[count($arr) - 1]);
      unset($arr[count($arr) - 1]);
      $arr = implode("\n", $arr);
      $xml = new SimpleXMLElement($arr);
      $cpu = $xml->hierarchy->hierarchy->hierarchy->instance->info[1];
      $olt->cpu = $cpu;
      $olt->save();
      echo $cpu;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function firmware($req, $olt)
  {
    if (Token::checkPermission($req, 'edit_olts')) {
      $output = shell_exec("python python/nokia/isam_firmware.py '$olt->ip' '$olt->username' '$olt->password'");
      $arr = explode("\n", $output);
      array_shift($arr);
      unset($arr[count($arr) - 1]);
      unset($arr[count($arr) - 1]);
      $arr = implode("\n", $arr);
      $xml = new SimpleXMLElement($arr);
      $firmware = $xml->hierarchy->hierarchy->hierarchy->hierarchy->info[0];
      $olt->firmware = $firmware;
      $olt->save();
      echo $firmware;
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function onuStatus($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/nokia/isam_onu_status.py '$olt->ip' '$olt->username' '$olt->password' '$req->onu'");
      $arr = explode("|", $output);
      $res = [
        'pos' =>  $arr[1],
        'sn' => $arr[2],
        'status' => $arr[4],
        'signal' => str_replace("\n", "", $arr[8]),
        'desc' => str_replace("\"", "", $arr[7]),
      ];
      return response()->json($res);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }

  public static function onuStatusMany($req, $olt)
  {
    if (Token::checkPermission($req, 'view_onus')) {
      $output = shell_exec("python python/nokia/isam_onu_status_many.py '$olt->ip' '$olt->username' '$olt->password' '$req->onus'");
      $arr = explode("\n", $output);
      array_pop($arr);
      $res = [];
      foreach ($arr as $onu) {
        $onu = explode("|", $onu);
        array_push($res, [
            'pos' =>  $onu[1],
            'sn' => $onu[2],
            'status' => $onu[4],
            'signal' => str_replace("\n", "", $onu[8]),
            'desc' => str_replace("\"", "", $onu[7]),
        ]);
      }
      return response()->json($res);
    } else {
      return response()->json(['status' => 401, 'message' => 'You have no permission to perform this action'], 401);
    }
  }
}
