<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;

class NokiaController extends Controller
{

    public static function decide($arg, $get){
        switch ($arg) {
            case 'pon':
                NokiaController::pon($get['id'], $get['pon']);
                break;
            case 'onu':
                NokiaController::onu($get['id'], $get['onu']);
                break;
            case 'cpu':
                NokiaController::cpu($get['id']);
                break;
            case 'mem':
                NokiaController::mem($get['id']);
                break;
            case 'firmware':
                NokiaController::firmware($get['id']);
                break;
            case 'pending':
                NokiaController::pending($get['id']);
                break;
        }
    }

    // Retorna ONUs de uma PON
    public static function pon($id, $cmd){
        // pega informações sobre a olt
        $args = DB::select("select * from olts where id='$id'");
        $args = $args[0];
        // executa script python
        $output = shell_exec("python python/nokia/isam_count.py $args->ip $args->user $args->pass 1/1/$cmd");
        // remove linhas inuteis do resultado
        $arr = explode("\n", $output);
        array_shift($arr);
        unset($arr[count($arr) -1]);
        unset($arr[count($arr) -1]);
        // retorna o resultado em XML
        echo implode("\n", $arr);
    }

    // Retorna o indice de uma ONU
    public static function onu($id, $onu){
        // pega informações sobre a olt
        $args = DB::select("select * from olts where id='$id'");
        $args = $args[0];
        // executa script python
        $output = shell_exec("python python/nokia/isam_search.py $args->ip $args->user $args->pass $onu");
        // remove linhas inuteis do resultado
        $arr = explode("\n", $output);
        array_shift($arr);
        unset($arr[count($arr) -1]);
        unset($arr[count($arr) -1]);
        // retorna o resultado em XML
        $xml = new SimpleXMLElement(implode("\n", $arr));
        $idx = $xml->hierarchy->hierarchy->hierarchy->hierarchy->instance->info;
        echo $idx;
    }

    // Retorna uso de memória
    public static function mem($id){
        // pega informações sobre a olt
        $args = DB::select("select * from olts where id='$id'");
        $args = $args[0];
        // executa script python
        $output = shell_exec("python python/nokia/isam_mem.py $args->ip $args->user $args->pass");
        // remove linhas inuteis do resultado
        $arr = explode("\n", $output);
        array_shift($arr);
        unset($arr[count($arr) -1]);
        unset($arr[count($arr) -1]);
        // atualiza a linha na tabela do banco de dados
        $xml = new SimpleXMLElement(implode("\n", $arr));
        $mem = $xml->hierarchy->hierarchy->hierarchy->instance[0]->info[2];
        DB::update("update olts set last_mem=$mem where id=$id");
        echo  redirect('/dashboard');
    }

    // Retorna uso de CPU
    public static function cpu($id){
        // pega informações sobre a olt
        $args = DB::select("select * from olts where id='$id'");
        $args = $args[0];
        // executa script python
        $output = shell_exec("python python/nokia/isam_cpu.py $args->ip $args->user $args->pass");
        // remove linhas inuteis do resultado
        $arr = explode("\n", $output);
        array_shift($arr);
        unset($arr[count($arr) -1]);
        unset($arr[count($arr) -1]);
        // atualiza a linha na tabela do banco de dados
        $arr = implode("\n", $arr);
        $xml = new SimpleXMLElement($arr);
        $cpu = $xml->hierarchy->hierarchy->hierarchy->instance->info[1];
        DB::update("update olts set last_cpu=$cpu where id=$id");
        echo  redirect('/dashboard');
    }

    // Retorna versão do firmware
    public static function firmware($id){
        // pega informações sobre a olt
        $args = DB::select("select * from olts where id='$id'");
        $args = $args[0];
        // executa script python
        $output = shell_exec("python python/nokia/isam_firmware.py $args->ip $args->user $args->pass");
        // remove linhas inuteis do resultado
        $arr = explode("\n", $output);
        array_shift($arr);
        unset($arr[count($arr) -1]);
        unset($arr[count($arr) -1]);
        // atualiza a linha na tabela do banco de dados
        $arr = implode("\n", $arr);
        $xml = new SimpleXMLElement($arr);
        $firmware = $xml->hierarchy->hierarchy->hierarchy->hierarchy->info[0];
        DB::update("update olts set firmware='$firmware' where id=$id");
        echo  redirect('/dashboard');
    }
    public static function pending($id){
        // pega informações sobre a olt
        $args = DB::table('olts')->where('id', $id)->first();
        // executa script python
        $output = shell_exec("python python/nokia/isam_pending.py $args->ip $args->user $args->pass");
        // remove linhas inuteis do resultado
        $arr = explode("\n", $output);
        array_shift($arr);
        unset($arr[count($arr) -1]);
        unset($arr[count($arr) -1]);
        // retorna o resultado em XML
        echo implode("\n", $arr);
    }
}
