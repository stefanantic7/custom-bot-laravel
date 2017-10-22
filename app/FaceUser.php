<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaceUser extends Model
{
    public function getMoreRelevant($currentMax, $rule) {
        $conditions = $rule->conditions;
        $trueStatements = json_decode($this->trueStatements);

        foreach ($conditions as $condition) {
            $condition = $condition->text;
            if (in_array($condition, $trueStatements)) {
                $this->conditionsForSuggested++;
            }
        }
        if($this->conditionsForSuggested > $currentMax) {
            $currentMax = $this->conditionsForSuggested;
            $this->suggestedRule = $rule->text;
        }
        $this->conditionsForSuggested = 0;
        return $currentMax;
    }
}
