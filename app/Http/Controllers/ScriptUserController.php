<?php

namespace App\Http\Controllers;

use App\Models\ScriptUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ScriptUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ScriptUser::all();
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
            ScriptUser::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Crypt::encryptString($request->password)
            ]);
            return response()->json(['status' => '200', 'message' => 'ScriptUser created']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on script_user creation'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ScriptUser::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $script_user = ScriptUser::find($id);
            $script_user->name = $request->name;
            $script_user->username = $request->username;
            if ($request->password != '') {
                $script_user->password = Crypt::encryptString($request->password);
            }
            $script_user->save();
            return response()->json(['status' => '200', 'message' => 'script_user updated']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on script_user update'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            ScriptUser::destroy($id);
            return response()->json(['status' => '200', 'message' => 'script_user deleted']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on script_user deletion'], 500);
        }
    }
}
