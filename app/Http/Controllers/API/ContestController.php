<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\contest;

use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiResponseTrait;
use App\Models\Player;
use App\Models\Referee;
use Illuminate\Support\Facades\Validator;

class contestController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contests = contest::all();
        return $this->apiResponse($contests, 'success', 200);
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
                'name' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $contests = contest::create($request->all());

        if ($contests) {
            return $this->apiResponse($contests, 'The contests Saved', 201);
        }

        return $this->apiResponse(null, 'The contests Not Save', 400);
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

        $contest = contest::find($id);

        if (!$contest) {
            return $this->apiResponse(null, 'The contests Not Found', 404);
        }

        $contest->update($request->all());

        if ($contest) {
            return $this->apiResponse($contest, 'The contest Update', 201);
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
        $contest = contest::find($id);

        if (!$contest) {
            return $this->apiResponse(null, 'The contest Not Found', 404);
        }

        $contest->delete($id);

        if ($contest) {
            return $this->apiResponse(null, 'The contest Deleted', 200);
        }
    }
    public function contestsDetails($id)
    {
        // dd($id);
        $contestDetails = [];
        $contest = contest::find($id);
        // $explode_id = array_map('intval', explode(',', $contest->goal_scorers));
        $explode_id = json_decode($contest->goal_scorers, true);
        // dd($contest->goal_scorers,$explode_id);
        // dd($contest->goal_scorers);
        $scoring_players = Player::whereIn('id', $explode_id)->get();
        $referee = Referee::find($contest->referee_id);
        // dd($players_names );
        // dd($contest->players,$contest->coaches)  ;
    $contestDetails['current_score'] = $contest->number_of_goals;
    $contestDetails['scoring_players'] = $scoring_players;
    $contestDetails['Referee'] = $referee;

        return $this->apiResponse($contestDetails, 'success', 200);
    }
}
