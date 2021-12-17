<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Vendor::all();
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
            Vendor::create([
                'name' => $request->name
            ]);
            return response()->json(['status' => '200', 'message' => 'Vendor created']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on vendor creation'], 500);
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
        return Vendor::find($id);
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
            $vendor = Vendor::find($id);
            $vendor->name = $request->name;
            $vendor->save();
            return response()->json(['status' => '200', 'message' => 'vendor updated']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on vendor update'], 500);
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
            Vendor::destroy($id);
            return response()->json(['status' => '200', 'message' => 'vendor deleted']);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Error on vendor deletion'], 500);
        }
    }
}
