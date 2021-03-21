<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function pon(){
        $id = $_GET['id'];
        $cmd = $_GET['pon'];
        $args = DB::select("select * from olt where id='$id'");
        $args = $args[0];
        $output = shell_exec("python isam_count.py $args->ip $args->user $args->pass 1/1/$cmd");
        $arr = explode("\n", $output);
        array_shift($arr);
        unset($arr[count($arr) -1]);
        unset($arr[count($arr) -1]);
        echo implode("\n", $arr);
    }

    public function initial(){
        $olts = DB::select('select * from olt');
        return view('dashboard', ['olts'=>$olts]);
    }
}
