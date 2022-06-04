<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Team;

use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::all();
        return $this->apiResponse($teams, 'success', 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator  = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
            ]
        );

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $teams = Team::create($request->all());

        if ($teams) {
            return $this->apiResponse($teams, 'The teams Saved', 201);
        }

        return $this->apiResponse(null, 'The teams Not Save', 400);
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
        // dd('update');
        $validator  = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
            ]
        );
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $team = Team::find($id);

        if (!$team) {
            return $this->apiResponse(null, 'The teams Not Found', 404);
        }

        $team->update($request->all());

        if ($team) {
            return $this->apiResponse($team, 'The team Update', 201);
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
        $team = Team::find($id);

        if (!$team) {
            return $this->apiResponse(null, 'The team Not Found', 404);
        }

        $team->delete($id);

        if ($team) {
            return $this->apiResponse(null, 'The team Deleted', 200);
        }
    }
    public function teamsDetails($id)
    {
        // $teams_id = [];
        $top_scorer_player    = [];
        $teamDetails = [];
        $team = Team::find($id);
        // dd($team->players,$team->coaches)  ;
    $teamDetails['team_players'] = $team->players;
    $teamDetails['team_coaches'] = $team->coaches;
        return $this->apiResponse($teamDetails, 'success', 200);
    }
}
