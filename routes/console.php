<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('json:import', function () {
    $content = Storage::get('rules.json');
    $rules_json = json_decode($content)->rules;
    foreach ($rules_json as $rule_json){
        $rule = new \App\Rule();
        $conclusion = new \App\Statement();

        $conclusion->text = $rule_json->conclusion;
        $conclusion->conclusion_for_rule = $rule->id;

        foreach ($rule_json->conditions as $condition_json) {
            $condition = new \App\Statement();

            $condition->text = $condition_json;
            $condition->condition_for_rule = $rule->id;

            $condition->save();
        }


        $conclusion->save();
        $rule->save();
    }

})->describe('Import rules from json file');