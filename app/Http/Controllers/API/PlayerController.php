<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\player;
use App\Models\Team;


use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class playerController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = player::all();
        return $this->apiResponse($players, 'success', 200);
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

        $players = player::create($request->all());

        if ($players) {
            return $this->apiResponse($players, 'The players Saved', 201);
        }

        return $this->apiResponse(null, 'The players Not Save', 400);
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

        $player = player::find($id);

        if (!$player) {
            return $this->apiResponse(null, 'The players Not Found', 404);
        }

        $player->update($request->all());

        if ($player) {
            return $this->apiResponse($player, 'The player Update', 201);
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
        $player = player::find($id);

        if (!$player) {
            return $this->apiResponse(null, 'The player Not Found', 404);
        }

        $player->delete($id);

        if ($player) {
            return $this->apiResponse(null, 'The player Deleted', 200);
        }
    }
    public function playersDetails($id)
    {
        // dd($id);
        // $players_id = [];
        $top_scorer_player    = [];
        $playerDetails = [];
        $player = player::find($id);
        $team = Team::find($player->team_id);

    $playerDetails['current_team'] = $team ;
    $playerDetails['number_of_goals'] = $player->number_of_goals;
    
    return $this->apiResponse($playerDetails, 'success', 200);
    }
}
