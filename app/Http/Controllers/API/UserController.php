<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    use ApiResponseTrait;
    /**
     * handle user registration request
     */
    public function register(Request $request)
    {
        // dd($request);
        // $this->validate(
        //     request(),
        //     [
        //         'name' => 'required',
        //         'email' => 'required|email|unique:users',
        //         'password' => 'required|min:8',
        //     ]
        // );
        $rules = array(
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',

        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => $validator->errors()->first()
            ];
        }
        // dd($request);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => 'user',

        ]);
        // dd($user);
        $token = $user->createToken('API Token')->accessToken;
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
    public function authenticatedUserDetails()
    {
        //returns details
        return response()->json(['authenticated-user' => auth()->user()], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
