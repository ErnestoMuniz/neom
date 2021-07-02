<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatacomController extends Controller
{

    public static function decide($arg, $get){
        switch ($arg) {
            case 'cpu':
                DatacomController::cpu($get['id']);
                break;
            case 'mem':
                DatacomController::mem($get['id']);
                break;
            case 'firmware':
                DatacomController::firmware($get['id']);
                break;
            case 'pon':
                DatacomController::pon($get['id'], $get['pon']);
                break;
            case 'onu':
                DatacomController::onu($get['id'], $get['onu']);
                break;
            case 'pending':
                DatacomController::pending($get['id']);
                break;
        }
    }

    public static function cpu($id){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/datacom/datacom_cpu.py $olt->ip $olt->user $olt->pass");
        $output = json_decode($output, true);
        $cpu = $output['data']['dmos-base:status']['system']['dmos-system-monitor:cpu']['chassis'][0]['slot'][0]['load']['five-seconds']['active'];
        DB::table('olts')->where('id', $id)->update(['last_cpu' => $cpu]);
        echo  redirect('/dashboard');
    }

    public static function mem($id){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/datacom/datacom_mem.py $olt->ip $olt->user $olt->pass");
        $output = json_decode($output, true);
        $total = $output['data']['dmos-base:status']['system']['dmos-system-monitor:memory']['chassis'][0]['slot'][0]['five-seconds']['total'];
        $used = $output['data']['dmos-base:status']['system']['dmos-system-monitor:memory']['chassis'][0]['slot'][0]['five-seconds']['used'];
        $res = intval($used / $total * 100);
        DB::table('olts')->where('id', $id)->update(['last_mem' => $res]);
        echo  redirect('/dashboard');
    }

    public static function firmware($id){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/datacom/datacom_firmware.py $olt->ip $olt->user $olt->pass");
        $output = json_decode($output, true);
        $firmware = $output['data']['dmos-base:status']['firmware']['dmos-sw-update:firmware'][0]['version'];
        DB::table('olts')->where('id', $id)->update(['firmware' => $firmware]);
        echo  redirect('/dashboard');
    }

    public static function pon($id, $cmd){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/datacom/datacom_count.py $olt->ip $olt->user $olt->pass $cmd");
        echo $output;
    }

    public static function onu($id, $cmd){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/datacom/datacom_search.py $olt->ip $olt->user $olt->pass $cmd");
        $output = explode(' ', $output);
        echo $output[0] . '/' . $output[5];
    }
}
