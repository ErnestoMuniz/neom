<?php

namespace App\Http\Controllers;

use App\Models\Olt;
use App\Models\Token;
use Illuminate\Http\Request;

class OltController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return Olt::orderBy('name')->get()->makeVisible([
      'username',
      'password',
      'superuser',
      'superpass'
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function publicIndex(Request $request)
  {
    if (Token::internal($request)) {
      $user = Token::getToken($request->header('Token'))->user;
      return Olt::where('enabled', true)->orderBy('name')->find($user->role->olts->pluck('id')->toArray());
    } else if (env('EXTERNAL_AUTH_ENDPOINT') != "") {
      return Olt::where('enabled', true)->orderBy('name')->get()->toArray();
    }
  }

  /**
   * Change the OLT availability.
   *
   * @param  \App\Models\Olt  $olt
   * @return \Illuminate\Http\Response
   */
  public function toggleOlt(Olt $olt)
  {
    try {
      $olt->enabled = !$olt->enabled;
      $olt->save();
      return response()->json(['status' => '200', 'message' => 'Olt updated']);
    } catch (\Throwable $th) {
      return response()->json(['status' => '500', 'message' => 'Error on olt update ' . $th], 500);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    try {
      Olt::create([
        'name' => $request->name,
        'ip' => $request->ip,
        'vendor' => $request->vendor,
        'slots' => $request->slots,
        'pons' => $request->pons,
        'enabled' => (bool) $request->enabled,
        'username' => $request->username,
        'password' => $request->password,
        'unm' => $request->unm,
        'model' => $request->model,
        'port' => $request->port,
        'snmp' => $request->snmp,
        'community' => $request->community,
        'superuser' => $request->superuser,
        'superpass' => $request->superpass
      ]);
      return response()->json(['status' => '200', 'message' => 'Olt created']);
    } catch (\Throwable $th) {
      return response()->json(['status' => '500', 'message' => 'Error on olt creation '], 500);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Olt  $olt
   * @return \Illuminate\Http\Response
   */
  public function show(Olt $olt)
  {
    return Olt::find($olt->id)->makeVisible([
      'username',
      'password',
      'superuser',
      'superpass'
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Olt  $olt
   * @return \Illuminate\Http\Response
   */
  public function publicShow(Request $request, Olt $olt)
  {
    $user = Token::getToken($request->header('Token'))->user;
    $availableOlts = $user->role->olts->pluck('id')->toArray();
    if (in_array($olt->id, $availableOlts)) {
      return Olt::find($olt->id);
    } else {
      return [];
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Olt  $olt
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Olt $olt)
  {
    try {
      $olt = Olt::find($olt->id);
      $olt->name = $request->name;
      $olt->ip = $request->ip;
      $olt->vendor = $request->vendor;
      $olt->slots = $request->slots;
      $olt->pons = $request->pons;
      $olt->enabled = $request->enabled;
      $olt->username = $request->username;
      $olt->password = $request->password;
      $olt->unm = $request->unm;
      $olt->model = $request->model;
      $olt->port = $request->port;
      $olt->snmp = $request->snmp;
      $olt->community = $request->community;
      $olt->superuser = $request->superuser;
      $olt->superpass = $request->superpass;
      $olt->save();
      return response()->json(['status' => '200', 'message' => 'Olt updated']);
    } catch (\Throwable $th) {
      return response()->json(['status' => '500', 'message' => 'Error on olt update'], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Olt  $olt
   * @return \Illuminate\Http\Response
   */
  public function destroy(Olt $olt)
  {
    try {
      Olt::destroy($olt->id);
      return response()->json(['status' => '200', 'message' => 'Olt deleted']);
    } catch (\Throwable $th) {
      return response()->json(['status' => '500', 'message' => 'Error on olt deletion'], 500);
    }
  }
}
