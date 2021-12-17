<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Permission::all();
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
            Permission::create([
                'name' => $request->name
            ]);
            return response()->json(['status' => '200', 'message' => 'Permission created']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on permission creation'], 500);
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
        return Permission::find($id);
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
            $permission = permission::find($id);
            $permission->name = $request->name;
            $permission->save();
            return response()->json(['status' => '200', 'message' => 'permission updated']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on permission update'], 500);
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
            Permission::destroy($id);
            return response()->json(['status' => '200', 'message' => 'permission deleted']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on permission deletion'], 500);
        }
    }
}
