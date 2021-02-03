<?php

namespace App\Http\Controllers\API;

use App\Models\Klasemen;
use App\Models\Pertandingan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KlasemenResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PertandinganResource;

class KlasemenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return KlasemenResource::collection(Klasemen::all()->load('team'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 400);
        }

        $klasemen = Klasemen::findOrFail($request->id);

        if ($klasemen) {

            $klasemen->id = $request->id;
            $klasemen->team_id = $request->team_id;
            $klasemen->point = $request->point;
            $klasemen->save();

            return new KlasemenResource($klasemen);
        }

        return response()->json(false, 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
