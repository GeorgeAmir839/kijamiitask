<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::apiResource('Leagues', 'API\BannerController')->only('index');
// Route::apiResource('leagues', [App\Http\Controllers\API\LeagueController::class]);

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::post('player/login', 'API\PlayerController@login');
Route::post('player/register', 'API\PlayerController@register');
Route::post('logout', 'API\UserController@logout');

Route::middleware('auth:api')->group(function () {
    Route::get('user', [passportAuthController::class, 'authenticatedUserDetails']);
    Route::apiResource('leagues', 'API\LeagueController');
    Route::get('leagues/details/{id}', 'API\LeagueController@leaguesDetails')->name('leagues.details');

    Route::apiResource('teams', 'API\TeamController');
    Route::get('teams/details/{id}', 'API\TeamController@teamsDetails')->name('teams.details');

    Route::apiResource('contests', 'API\ContestController');
    Route::get('contests/details/{id}', 'API\ContestController@contestsDetails')->name('contests.details');

    Route::apiResource('players', 'API\PlayerController');
    Route::get('players/details/{id}', 'API\PlayerController@playersDetails')->name('players.details');
});
