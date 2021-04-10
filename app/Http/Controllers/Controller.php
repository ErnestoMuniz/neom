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
            return redirect()->route('dashboard');
        }
    }

    // Cria novo usuário
    public function newUser(Request $request){
        if (Auth::user()->hasRole('admin')){
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            $user = User::factory()->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            if ($request->group == 'admin'){
                $user->assignRole('admin', 'n2', 'n1');
            } elseif ($request->group == 'n2') {
                $user->assignRole('n2', 'n1');
            } else {
                $user->assignRole('n1');
            }
            return redirect()->route('users');
        } else {
            return redirect()->route('dashboard');
        }
    }

    // Edita um usuário
    public function editUser(Request $request){
        if (Auth::user()->hasRole('admin')){
            if ($request->password != ''){
                $password = Hash::make($request->password);
                DB::update("update users set name='$request->name', email='$request->email', password='$password' where id=$request->id");
            } else {
                DB::update("update users set name='$request->name', email='$request->email' where id=$request->id");
            }
            DB::delete("delete from model_has_roles where model_id=$request->id");
            if ($request->group == 'admin'){
                $user = User::find($request->id);
                $user->syncRoles(['n1', 'n2', 'admin']);
            } elseif ($request->group == 'n2'){
                $user = User::find($request->id);
                $user->syncRoles(['n1', 'n2']);
            } else {
                $user = User::find($request->id);
                $user->syncRoles(['n1']);
            }
            return redirect()->route('users');
        } else {
            return redirect()->route('dashboard');
        }

    }

    // Remove um usuário
    public function removeUser(Request $request){
        if (Auth::user()->hasRole('admin')){
            $user = User::find($request->id);
            $user->syncRoles([]);
            $id = $_GET['id'];
            DB::delete("delete from users where id=$id");

            return redirect()->route('users');
        } else {
            return redirect()->route('dashboard');
        }
    }
}
