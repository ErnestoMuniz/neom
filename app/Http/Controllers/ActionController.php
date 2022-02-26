<?php

namespace App\Http\Controllers;

use App\Models\Olt;
use Illuminate\Http\Request;

class ActionController extends Controller
{
  public function bash($cmd)
  {
    return shell_exec(escapeshellcmd($cmd));
  }

  public function router(Request $req, Olt $olt, $cmd)
  {
    switch ($olt->vendor) {
      case 'Fiberhome':
        return FiberhomeController::$cmd($req, $olt);
        break;
      case 'Nokia':
        return NokiaController::$cmd($req, $olt);
        break;
      case 'Huawei':
        return HuaweiController::$cmd($req, $olt);
        break;
      case 'Datacom':
        return DatacomController::$cmd($req, $olt);
        break;
    }
  }

  public function pon(Request $req, Olt $olt)
  {
    $output = $this->bash("python python/$olt->vendor/pon.py '$olt->ip' '$olt->username' '$olt->password' ");
    return response($output);
  }
}
