<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    public $trueConditionsCount = 0;
    public function conclusion(){
        return $this->hasOne(Statement::class, 'conclusion_for_rule');
    }

    public function mainConditions(){
        return $this->hasMany(Statement::class, 'main_condition_for_rule');
    }

    public function conditions(){
        return $this->hasMany(Statement::class, 'condition_for_rule');
    }

    public function check($user, $answer){
        $mainConditions = $this->mainConditions;
        $conditions = $this->conditions;
        $conclusion = $this->conclusion->text;
        $question = $user->question;
        $trueStatements = json_decode($user->trueStatements);
        $falseStatements = json_decode($user->falseStatements);


        if(in_array($conclusion, $trueStatements)) {
            return true;
        }

        $broken = false;
        foreach ($mainConditions as $condition) {
            $condition = $condition->text;
            if (in_array($condition, $trueStatements)) {
                continue;
            }
            if (in_array($condition, $falseStatements)) {
                array_push($falseStatements, $conclusion);
                $user->falseStatements = json_encode($falseStatements);
                $user->save();
                $broken = true;
                continue;
            }

            if($condition == $question) {
                if($answer == 'da') {
                    array_push($trueStatements, $condition);
                    $user->trueStatements = json_encode($trueStatements);
                    $user->save();
                    continue;
                }
                if($answer == 'ne') {
                    array_push($falseStatements, $condition);
                    array_push($falseStatements, $conclusion);
                    $user->falseStatements = json_encode($falseStatements);
                    $user->save();
                    $broken = true;
                    continue;
                }
            }
            else {
                $user->question = $condition;
                $user->save();
                return null;
            }
        }
        if(!$broken) {
            foreach ($conditions as $condition) {
                $condition = $condition->text;
                if (in_array($condition, $trueStatements)) {
                    continue;
                }
                if (in_array($condition, $falseStatements)) {
                    array_push($falseStatements, $conclusion);
                    $user->falseStatements = json_encode($falseStatements);
                    $user->save();
                    continue;
                }

                if($condition == $question) {
                    if($answer == 'da') {
                        array_push($trueStatements, $condition);
                        $user->trueStatements = json_encode($trueStatements);
                        $user->save();
                        continue;
                    }
                    if($answer == 'ne') {
                        array_push($falseStatements, $condition);
                        array_push($falseStatements, $conclusion);
                        $user->falseStatements = json_encode($falseStatements);
                        $user->save();
                        continue;
                    }
                }
                else {
                    $user->question = $condition;
                    $user->save();
                    return null;
                }
            }
        }
        if(in_array($conclusion, $falseStatements)){
            return false;
        }
        else {
            array_push($trueStatements, $conclusion);
            $user->trueStatements = json_encode($trueStatements);
            $user->save();
            return true;
        }


    }
}
