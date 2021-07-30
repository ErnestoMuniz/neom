<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function teste($url, $function) {
        switch ($url) {
            case 'nokia':
                NokiaController::decide($function, $_GET);
                break;
            case 'datacom':
                DatacomController::decide($function, $_GET);
                break;
            case 'huawei':
                HuaweiController::decide($function, $_GET);
                break;
        }
    }

    // Retorna dashboard com informações das OLTs
    public function initial(){
        $olts = DB::select("select * from olts order by 'nome'");
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
