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
    return Role::with(['permissions:id,name', 'olts:id,name'])->orderBy('name')->get();
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
      if ($request->olts != []) {
        $role->olts()->sync($request->olts);
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
    foreach ($rolePermissions as $rolePermission) {
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
      if ($request->olts != []) {
        $role->olts()->sync($request->olts);
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
