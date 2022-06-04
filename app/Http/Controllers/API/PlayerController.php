<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\player;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class playerController extends Controller
{
    use ApiResponseTrait;
    public function register(Request $request)
    {
        // dd($request);
        $rules = array(
            'player_name' => 'required',
            'number_of_goals' => 'required',
            'player_number' => 'required',
            'email' => 'required|email|unique:users',
            'team_id' => 'required',
            'password' => 'required|min:8',

        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => $validator->errors()->first()
            ];
        }
      
        $user = User::create([
            'name' => $request->player_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => 'player',
        ]);
        $token = $user->createToken('API Token')->accessToken;
        $request->merge([
            'user_id' => $user->id,
        ]);
        $players = player::create( $request->only(['player_name', 'player_number','number_of_goals','team_id','user_id']));
        //return the access token we generated in the above step
        return response()->json([
            'status' => (bool) $user,
            'user'   => $user,
            'message' => $user ? 'success register!' : 'an error has occurred',
            'token' => $token
        ], 201);
        // return response()->json(['token' => $access_token_example], 200);
    }

    /**
     * login user to our application
     */
    public function login(Request $request)
    {
        // Validator::extend('without_spaces', function($attr, $value){
        //     return preg_match('/^\S*$/u', $value);
        // });
        $rules = array(
            'email' => 'required|email',
            // 'password' => 'required|min:8',

        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => $validator->errors()->first()
            ];
        }
        $login_credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (auth()->attempt($login_credentials)) {
            //generate the token for the user
            if(auth()->user()->user_type != 'player'){
                return response()->json(['error' => 'Not allawed For You'], 401);
            }

            $token = auth()->user()->createToken('API Token')->accessToken;            //now return this token on success login attempt
            return response()->json([
                'status' => (bool)auth()->user(),
                'user'   => auth()->user(),
                'message' => 'success login!',
                'token' => $token
            ], 201);
        } else {
            //wrong login credentials, return, user not authorised to our system, return error code 401
            return response()->json(['error' => 'UnAuthorised Access'], 401);
        }
    }

    /**
     * This method returns authenticated user details
     */
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->delete();
        $response = ["massage" => "you have success logout "];
        return response($response, 200);
    }
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
                'player_name' => 'required',
                'team_id' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
            ]
        );
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
        $user = new User;
        $user->name = $request->player_name;
        $user->email = $request->email;
        $user->user_type = "player";
        $user->password = Hash::make($request->password);$user->email_verified_at = date('Y-m-d H:m:s');
        $user->email_verified_at = date('Y-m-d H:m:s');
        $user->save();
        $request->merge([
            'user_id' => $user->id,
        ]);
       
        $players = player::create( $request->only(['player_name', 'player_number','number_of_goals','team_id','user_id']));

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
