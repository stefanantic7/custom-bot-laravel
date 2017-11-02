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

    $suggestions = [];

    $suggestion = [
        'weight' => 0.955555*100,
        'suggestion' => 'nesto'
    ];

    $suggestions[] = $suggestion;

    $suggestion = [
        'weight' => 0.944444*100,
        'suggestion' => 'nestoo'
    ];

    $suggestions[] = $suggestion;

    $suggestion = [
        'weight' => 1.00*100,
        'suggestion' => 'nestoo'
    ];

    $suggestions[] = $suggestion;

    usort($suggestions, function($a, $b) {
        return $b['weight'] - $a['weight'];
    });

    foreach ($suggestions as $index=>$suggestion) {
        var_dump($index.' '.$suggestion['weight']);
    }

//    return $suggestions;

    $rule = \App\Rule::with(['mainConditions', 'conditions', 'conclusion'])->first();
    dd($rule->mainConditions->merge($rule->conditions));
});