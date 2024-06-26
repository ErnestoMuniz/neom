<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $user = DB::table('users')->where('email', $request->email)->first();
    if ($user == null) {
      return response()->json(['status' => '404', 'message' => 'E-mail does not exist'], 404);
    }
    if (!Hash::check($request->password, $user->password)) {
      return response()->json(['status' => '404', 'message' => 'Incorrect password'], 404);
    }
    try {
      $token = Str::random(32);
      $role = Role::find($user->role_id);
      if (!in_array("multiple_sessions", $role->permissions->pluck('name')->toArray())) {
        DB::table('tokens')->where('user_id', $user->id)->delete();
      }
      Token::create([
        'token' => Hash::make($token),
        'user_id' => $user->id
      ]);
      $user = User::find($user->id);
      return response()->json(['name' => $user->name, 'email' => $user->email, 'id' => $user->id, 'role' => $role->name, 'permissions' => $user->role->permissions->pluck('name'), 'token' => $token], 200);
    } catch (\Throwable $th) {
      return response()->json(['status' => '500', 'message' => 'Error while creating token' . $th], 500);
    }
  }

  public function logout(Request $request)
  {
    try {
      $token = Token::where('token', $request->token)->first();
      Token::destroy($token->id);
      return response()->json(['status' => '200', 'message' => 'Logout success']);
    } catch (\Throwable $th) {
      return response()->json(['status' => '200', 'message' => 'Not logged in'], 404);
    }
  }
}
