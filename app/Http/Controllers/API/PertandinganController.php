<?php

namespace App\Http\Controllers\API;

use App\Models\Pertandingan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PertandinganResource;
use App\Models\Klasemen;

class PertandinganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PertandinganResource::collection(Pertandingan::all()->load('homeTeam')->load('awayTeam'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'homeTeamId' => 'required',
            'awayTeamId' => 'required',
            'homeTeamScore' => 'required',
            'awayTeamScore' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 400);
        }

        $homeTeamPoint = $awayTeamPoint = 0;

        if ($request->homeTeamScore > $request->awayTeamScore) {
            $homeTeamPoint = 3;
        } else if ($request->homeTeamScore === $request->awayTeamScore) {
            $homeTeamPoint = $awayTeamPoint = 1;
        } else {
            $awayTeamPoint = 3;
        }

        $klasemenHomeTeam = Klasemen::where('team_id', $request->homeTeamId)->firstOrFail();

        if ($klasemenHomeTeam) {

            $klasemenHomeTeam->team_id = $request->homeTeamId;
            $klasemenHomeTeam->point += $homeTeamPoint;
            $klasemenHomeTeam->save();
        }

        $klasemenAwayTeam = Klasemen::where('team_id', $request->awayTeamId)->firstOrFail();

        if ($klasemenAwayTeam) {

            $klasemenAwayTeam->team_id = $request->awayTeamId;
            $klasemenAwayTeam->point += $awayTeamPoint;
            $klasemenAwayTeam->save();
        }

        return new PertandinganResource(Pertandingan::create([
            'home_team_id' => $request->homeTeamId,
            'away_team_id' => $request->awayTeamId,
            'home_team_score' => $request->homeTeamScore,
            'away_team_score' => $request->awayTeamScore,
        ]));
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
        //
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
