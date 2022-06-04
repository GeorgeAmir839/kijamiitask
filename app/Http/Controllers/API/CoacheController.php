<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\coache;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class coacheController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coaches = coache::all();
        return $this->apiResponse($coaches, 'success', 200);
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
                'coache_name' => 'required',
                'team_id' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
            ]
        );
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
        $user = new User;
        $user->name = $request->coache_name;
        $user->email = $request->email;
        $user->user_type = "coache";
        $user->password = Hash::make($request->password);$user->email_verified_at = date('Y-m-d H:m:s');
        $user->email_verified_at = date('Y-m-d H:m:s');
        $user->save();
        $request->merge([
            'user_id' => $user->id,
        ]);
       
        $coaches = coache::create( $request->only(['coache_name', 'coache_number','number_of_goals','team_id','user_id']));

        if ($coaches) {
            return $this->apiResponse($coaches, 'The coaches Saved', 201);
        }

        return $this->apiResponse(null, 'The coaches Not Save', 400);
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

        $coache = coache::find($id);

        if (!$coache) {
            return $this->apiResponse(null, 'The coaches Not Found', 404);
        }

        $coache->update($request->all());

        if ($coache) {
            return $this->apiResponse($coache, 'The coache Update', 201);
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
        $coache = coache::find($id);

        if (!$coache) {
            return $this->apiResponse(null, 'The coache Not Found', 404);
        }

        $coache->delete($id);

        if ($coache) {
            return $this->apiResponse(null, 'The coache Deleted', 200);
        }
    }
   
}
