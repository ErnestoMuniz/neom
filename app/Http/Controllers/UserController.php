<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
