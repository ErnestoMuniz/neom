<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HuaweiController extends Controller
{
    public static function firmware($id){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/huawei/huawei_firmware.py $olt->ip $olt->user $olt->pass");
        DB::table('olts')->where('id', $id)->update(['firmware' => $output]);
    }

    public static function cpu($id){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/huawei/huawei_cpu.py $olt->ip $olt->user $olt->pass");
        DB::table('olts')->where('id', $id)->update(['last_cpu' => $output]);
    }

    public static function mem($id){
        $olt = DB::table('olts')->where('id', $id)->first();
        $output = shell_exec("python python/huawei/huawei_mem.py $olt->ip $olt->user $olt->pass");
        DB::table('olts')->where('id', $id)->update(['last_mem' => $output]);
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
}
