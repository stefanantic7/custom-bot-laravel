<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    public function conclusion(){
        return $this->hasOne(Statement::class, 'conclusion_for_rule');
    }

    public function conditions(){
        return $this->hasMany(Statement::class, 'condition_for_rule');
    }

    public function check($user, $answer){
        $conditions = $this->conditions;
        $conclusion = $this->conclusion->text;
        $question = $user->question;
        $trueStatements = json_decode($user->trueStatements);
        $falseStatements = json_decode($user->falseStatements);

        if(in_array($conclusion, $trueStatements)) {
            return true;
        }
        if(in_array($conclusion, $falseStatements)) {
            return false;
        }

        foreach ($conditions as $condition) {
            $condition = $condition->text;
            if (in_array($condition, $trueStatements)) {
                continue;
            }
            if (in_array($condition, $falseStatements)) {
                return false;
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
                    $user->falseStatements = json_encode($falseStatements);
                    $user->save();
                    return false;
                }
            }
            else {
                $user->question = $condition;
                $user->save();
                return null;
            }
        }
        array_push($trueStatements, $conclusion);
        $user->trueStatements = json_encode($trueStatements);
        $user->save();
        return true;
    }
}
