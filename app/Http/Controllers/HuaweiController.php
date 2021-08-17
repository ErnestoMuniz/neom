<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HuaweiController extends Controller
{

    public static function decide($arg, $get){
        switch ($arg) {
            case 'firmware':
                HuaweiController::firmware($get['id']);
                break;
            case 'cpu':
                HuaweiController::cpu($get['id']);
                break;
            case 'mem':
                HuaweiController::mem($get['id']);
                break;
            case 'pon':
                HuaweiController::pon($get['id'], $get['pon']);
                break;
            case 'pending':
                HuaweiController::pending($get['id']);
                break;
            case 'onu':
                HuaweiController::onu($get['id'], $get['onu']);
                break;
            case 'remove':
                HuaweiController::remove($get['id'], $get['pon'], $get['pos']);
                break;
            case 'reboot':
                HuaweiController::reboot($get['id'], $get['pon'], $get['pos']);
                break;
        }
    }

    public static function firmware($id){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/huawei/huawei_firmware.py $olt->ip $olt->user $olt->pass");
        DB::table('olts')->where('id', $id)->update(['firmware' => $output]);
        echo redirect('/dashboard');
    }

    public static function cpu($id){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/huawei/huawei_cpu.py $olt->ip $olt->user $olt->pass");
        DB::table('olts')->where('id', $id)->update(['last_cpu' => $output]);
        echo  redirect('/dashboard');
    }

    public static function mem($id){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/huawei/huawei_mem.py $olt->ip $olt->user $olt->pass");
        DB::table('olts')->where('id', $id)->update(['last_mem' => $output]);
        echo  redirect('/dashboard');
    }

    public static function pon($id, $cmd){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/huawei/huawei_count.py $olt->ip $olt->user $olt->pass $cmd");
        echo str_replace("'", '"', $output);
    }

    public static function pending($id){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/huawei/huawei_pending.py $olt->ip $olt->user $olt->pass");
        echo str_replace("'", '"', $output);
    }

    public static function onu($id, $cmd){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/huawei/huawei_search.py $olt->ip $olt->user $olt->pass $cmd");
        echo str_replace("'", '"', $output);
    }

    public static function remove($id, $pon, $pos){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/huawei/huawei_remove.py $olt->ip $olt->user $olt->pass $pon $pos");
        echo str_replace("'", '"', $output);
    }

    public static function reboot($id, $pon, $pos){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/huawei/huawei_reboot.py $olt->ip $olt->user $olt->pass $pon $pos");
        echo str_replace("'", '"', $output);
    }
}
