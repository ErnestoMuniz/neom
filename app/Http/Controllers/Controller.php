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

    // Retorna ONUs de uma PON
    public function pon(){
        // cria variaveis
        $id = $_GET['id'];
        $cmd = $_GET['pon'];
        // pega informações sobre a olt
        $args = DB::select("select * from olt where id='$id'");
        $args = $args[0];
        // executa script python
        $output = shell_exec("python python/isam_count.py $args->ip $args->user $args->pass 1/1/$cmd");
        // remove linhas inuteis do resultado
        $arr = explode("\n", $output);
        array_shift($arr);
        unset($arr[count($arr) -1]);
        unset($arr[count($arr) -1]);
        // retorna o resultado em XML
        echo implode("\n", $arr);
    }

    // Retorna dashboard com infomções das OLTs
    public function initial(){
        $olts = DB::select('select * from olts');
        return view('dashboard', ['olts'=>$olts]);
    }
}
