<?php

namespace App\Http\Controllers;

use App\Models\Olt;
use App\Models\Script;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class ScriptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Script::all();
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
            Script::create([
                'name' => $request->name,
                'protocol' => $request->protocol,
                'port' => $request->port,
                'script_user_id' => $request->script_user_id,
                'vendor_id' => $request->vendor_id,
                'steps' => $request->steps
            ]);
            return response()->json(['status' => '200', 'message' => 'Script created']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => $th], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Script  $script
     * @return \Illuminate\Http\Response
     */
    public function show(Script $script)
    {
        return Script::find($script->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Script  $script
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Script $script)
    {
        try {
            $script = Script::find($script->id);
            $script->name = $request->name;
            $script->protocol = $request->protocol;
            $script->port = $request->port;
            $script->script_user_id = $request->script_user_id;
            $script->vendor_id = $request->vendor_id;
            $script->steps = $request->steps;
            $script->save();
            return response()->json(['status' => '200', 'message' => 'Script updated']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on script update'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Script  $script
     * @return \Illuminate\Http\Response
     */
    public function destroy(Script $script)
    {
        try {
            Script::destroy($script->id);
            return response()->json(['status' => '200', 'message' => 'Script deleted']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on script deletion'], 500);
        }
    }

    public function executeScript(Request $request, $id) {
        try {
            // Declare the needed values to pass to python
            $steps = Script::find($id)->steps;
            $protocol = Script::find($id)->protocol;
            $port = Script::find($id)->port;
            $script_user = DB::table('script_users')->where('id', $request->script_user_id)->first();
            $script_user->password = Crypt::decryptString($script_user->password);
            $script_user = json_encode($script_user);
            $olt = json_encode(Olt::find($request->olt_id));
            // Run the python script passing the given values
            return shell_exec("python python/execute_script.py '$olt' '$script_user' '$steps' '$request->variables' '$protocol' '$port'");
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
