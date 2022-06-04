<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\League;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiResponseTrait;
use App\Models\Contest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class LeagueController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $leagues = League::all();
        return $this->apiResponse($leagues, 'success', 200);
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
                'country' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $leagues = League::create($request->all());

        if ($leagues) {
            return $this->apiResponse($leagues, 'The leagues Saved', 201);
        }

        return $this->apiResponse(null, 'The leagues Not Save', 400);
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
                'country' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $league = League::find($id);

        if (!$league) {
            return $this->apiResponse(null, 'The leagues Not Found', 404);
        }

        $league->update($request->all());

        if ($league) {
            return $this->apiResponse($league, 'The league Update', 201);
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
        $league = League::find($id);

        if (!$league) {
            return $this->apiResponse(null, 'The league Not Found', 404);
        }

        $league->delete($id);

        if ($league) {
            return $this->apiResponse(null, 'The league Deleted', 200);
        }
    }
    public function leaguesDetails($id)
    {
        // $teams_id = [];
        $top_scorer_player    = [];
        $leagueDetails = [];
        $league = League::find($id);
        $teams_id = $league->teams->pluck('id')->toArray();
        $result = Contest::where(function ($query) use ($teams_id) {
            $query->whereIn('first_team', $teams_id)
                ->orWhereIn('second_team',  $teams_id);
        })
        ->sum('number_of_goals');
        foreach ($league->teams as $team) {
            foreach ($team->players as $player) {
                $top_scorer_player[] = $player['number_of_goals'];
            }
        }
        $maxs = array_keys($top_scorer_player, max($top_scorer_player));
        // dd($maxs);
        $leagueDetails['number_of_goals'] = $result;
        $leagueDetails['top-scorer-player'] = $top_scorer_player[$maxs[0]];
        return $this->apiResponse($leagueDetails, 'success', 200);
    }
}
