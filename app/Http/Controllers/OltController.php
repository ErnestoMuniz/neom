<?php

namespace App\Http\Controllers;

use App\Models\Olt;
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
        return Olt::all();
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
                'pons' => $request->pons
            ]);
            return response()->json(['status' => '200', 'message' => 'Olt created']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on olt creation'], 500);
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
        return Olt::find($olt->id);
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
            $olt->ip = $request->ip;
            $olt->vendor = $request->vendor;
            $olt->slots = $request->slots;
            $olt->pons = $request->pons;
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
