<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use SimpleXMLElement;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Retorna ONUs de uma PON
    public function pon(){
        // cria variaveis
        $id = $_GET['id'];
        $cmd = $_GET['pon'];
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

    // Retorna uso de memória
    public function mem(){
        // cria variaveis
        $id = $_GET['id'];
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
        return redirect()->route('dashboard');
    }

    // Retorna dashboard com informações das OLTs
    public function initial(){
        $olts = DB::select('select * from olts');
        return view('dashboard', ['olts'=>$olts]);
    }

    // Retorna navigate com informações da OLT
    public function navigate(){
        $olt = $_GET['olt'];
        $olt = DB::select("select * from olts where id=$olt");
        return view('navigate', ['olt'=>$olt[0]]);
    }

    // Retorna a lista de usuários e grupos
    public function users(){
        if(Auth::user()->hasRole('admin')){
            $users = DB::select('select * from users');
            $roles = array();
            foreach ($users as $key => $user){
                $user->role = DB::select("select * from model_has_roles where model_id=$user->id")[0]->role_id;
                $users[$key] = $user;
            }
            return view("users", ['users'=>$users]);
        } else {
            return back();
        }
    }

    // Retorna a lsita de olts
    public function olts(){
        if(Auth::user()->hasRole('n2')){
            $olts = DB::select('select * from olts');
            return view("olts", ['olts'=>$olts]);
        } else {
            return back();
        }
    }
}
