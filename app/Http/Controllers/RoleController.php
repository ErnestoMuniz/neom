<?php

namespace App\Http\Controllers;

use App\Models\Permission;
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
        $roles = Role::all();
        $res = [];
        $cnt = 0;
        foreach($roles as $role){
            $rolePermissions = Permission::join('role_has_permissions', 'permission_id', 'id')->where('role_id', $role->id)->get();
            $permissionsName = [];
            $count = 0;
            foreach ($rolePermissions as $rolePermission){
                $permissionsName[$count] = [
                    "id" => $rolePermission->id,
                    "name" => $rolePermission->name
                ];
                $count++;
            }
            $json = [
                "id" => $role->id,
                "name" => $role->name,
                "permissions" => $permissionsName
            ];
            $res[$cnt] = $json;
            $cnt++;
        }
        return $res;
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
            $role = Role::create([
                'name' => $request->name
            ]);
            if ($request->permissions != []) {
                $role->permissions()->sync($request->permissions);
            }
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
        $role = Role::find($id);
        $rolePermissions = Permission::join('role_has_permissions', 'permission_id', 'id')->where('role_id', $id)->get();
        $permissionsName = [];
        $count = 0;
        foreach ($rolePermissions as $rolePermission){
            $permissionsName[$count] = [
                "id" => $rolePermission->id,
                "name" => $rolePermission->name
            ];
            $count++;
        }
        $json = [
            "id" => $role->id,
            "name" => $role->name,
            "permissions" => $permissionsName
        ];
        return $json;
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
            if ($request->permissions != []) {
                $role->permissions()->sync($request->permissions);
            }
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
