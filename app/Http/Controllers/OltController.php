<?php

namespace App\Http\Controllers;

use App\Models\Olt;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class OltController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Nova Olt
    public function newOlt(Request $request){
        if (Auth::user()->hasRole('n2')){
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            $olt = Olt::factory()->create([
                'nome' => $request->nome,
                'ip' => $request->ip,
                'user' => $request->user,
                'pass' => $request->pass,
                'slot' => $request->slots,
                'pon' => $request->pons,
                'vendor' => $request->vendor,
            ]);
            return back();
        } else {
            return back();
        }
    }

    // Editar Olt
    public function editOlt(Request $request){
        if (Auth::user()->hasRole('admin')){
            if ($request->pass != ''){
                DB::update("update olts set nome='$request->nome', ip='$request->ip', user='$request->user', pass='$request->pass', slot=$request->slots, pon=$request->pons, vendor='$request->vendor' where id=$request->id");
            } else {
                DB::update("update olts set nome='$request->nome', ip='$request->ip', user='$request->user', slot=$request->slots, pon=$request->pons, vendor='$request->vendor' where id=$request->id");
            }
            return back();
        } else {
            return back();
        }
    }

    // Remove uma OLT
    public function removeOlt(Request $request){
        if (Auth::user()->hasRole('n2')){
            $id = $_GET['id'];
            DB::delete("delete from olts where id=$id");

            return back();
        } else {
            return back();
        }
    }
}
