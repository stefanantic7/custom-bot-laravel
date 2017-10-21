<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $user = \App\FaceUser::where('face_id', 'asdf')->first();
    var_dump($user);
    $rule = \App\Rule::all()->first();
    $return = $rule->check($user, "da");

    if($return === true) {
        return 'true';
    }
    else if($return === false) {
        return 'false';
    }
    else if(is_null($return)){
        return 'null';
    }
    return $user;
});

////route for verification
//Route::get("/bot", "MainController@receive")->middleware("verify");
//
////where Facebook sends messages to. No need to attach the middleware to this because the verification is via GET
//Route::post("/bot", "MainController@receive");