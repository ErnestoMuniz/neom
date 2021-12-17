<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Role::all();
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
            Role::create([
                'name' => $request->name
            ]);
            return response()->json(['status' => '200', 'message' => 'Role created']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on role creation'], 500);
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
        return Role::find($id);
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
            $role = Role::find($id);
            $role->name = $request->name;
            $role->save();
            return response()->json(['status' => '200', 'message' => 'role updated']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on role update'], 500);
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
            Role::destroy($id);
            return response()->json(['status' => '200', 'message' => 'role deleted']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on role deletion'], 500);
        }
    }
}
