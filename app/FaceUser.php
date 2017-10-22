<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaceUser extends Model
{
    private $suggestedRule;
    private $conditionsForSuggested = 0;

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
            $this->suggestedRule = $rule;
        }
        $this->suggestedRule = 0;
        return $currentMax;
    }

    public function getSuggestedRule() {
        return $this->suggestedRule;
    }
}
