<?php

namespace App\Http\Controllers;

use App\Mail\RecoverPassword;
use App\Models\PasswordsReset;
use App\Models\Role;
use App\Models\User;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $users = User::addSelect([
      'role_name' => Role::select('name')
        ->whereColumn('role_id', 'roles.id')
    ])->orderBy('name')->get();
    return $users;
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request)
  {
    try {
      User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $request->role_id
      ]);
      return response()->json(['status' => '200', 'message' => 'User created']);
    } catch (\Throwable $th) {
      return response()->json(['status' => '500', 'message' => "Error on user creation\nErr: " . $th], 500);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function show(User $user)
  {
    $user = User::addSelect([
      'role_name' => Role::select('name')
        ->whereColumn('role_id', 'roles.id')
    ])->where('users.id', $user->id)->first();
    return $user;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, User $user)
  {
    try {
      $user = User::find($user->id);
      $user->name = $request->name;
      $user->email = $request->email;
      $user->role_id = $request->role_id;
      if ($request->password != '') {
        $user->password = Hash::make($request->password);
      }
      $user->save();
      return response()->json(['status' => '200', 'message' => 'User updated']);
    } catch (\Throwable $th) {
      return response()->json(['status' => '500', 'message' => "Error on user update\nErr: " . $th], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(User $user)
  {
    try {
      User::destroy($user->id);
      return response()->json(['status' => '200', 'message' => 'User deleted']);
    } catch (\Throwable $th) {
      return response()->json(['status' => '500', 'message' => "Error on user deletion\nErr: " . $th], 500);
    }
  }

  public function sendPasswordRecoverMail(Request $request)
  {
    if ($request->email != '') {
      $user = User::where('email', $request->email)->first();
      if ($user != null) {
        $token = Str::random(64);
        PasswordsReset::create([
          'user_id' => $user->id,
          'recover_token' => $token,
          'valid' => true
        ]);
        Mail::to(Str::lower($request->email))->send(new RecoverPassword($token));
        return response()->json(['status' => 200, 'message' => 'E-Mail sent!'], 200);
      }
      return response()->json(['status' => 404, 'message' => 'There is no user with this email'], 404);
    }
    return response()->json(['status' => 400, 'message' => 'Empty email field'], 400);
  }

  public function resetPassword(Request $request)
  {
    $reset = PasswordsReset::where('recover_token', $request->token)->first();
    $dt1 = strtotime($reset->created_at);
    $dt2 = strtotime(date('Y-m-d h:i:s'));
    if ($reset != null && $dt2 - $dt1 < 7200 && $reset->valid) {
      $reset->valid = false;
      $user = User::find($reset->user_id);
      $user->password = Hash::make($request->password);
      $reset->save();
      $user->save();
      return response()->json(['status' => 200, 'message' => 'Password reseted'], 200);
    }
    return response()->json(['status' => 404, 'message' => 'Invalid token'], 404);
  }
}
