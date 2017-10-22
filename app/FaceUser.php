<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaceUser extends Model
{
    private $max1;
    private $max2;
    private $max3;

    public function getMax1Attribute()
    {
        return $this->max1;
    }
    public function setMax1Attribute($value)
    {
        $this->max1 = $value;
    }
    public function getMax2Attribute()
    {
        return $this->max2;
    }
    public function setMax2Attribute($value)
    {
        $this->max2 = $value;
    }
    public function getMax3Attribute()
    {
        return $this->max3;
    }
    public function setMax3Attribute($value)
    {
        $this->max3 = $value;
    }

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
            $this->suggestedRuleThird = $this->suggestedRuleSecond;
            $this->max3 = $this->max2;

            $this->suggestedRuleSecond = $this->suggestedRule;
            $this->max2 = $this->max1;

            $this->suggestedRule = $rule;
            $this->max1 = $this->conditionsForSuggested;
        }
        $this->conditionsForSuggested = 0;

        $currentStatus = [
            'max1' => $this->max1,
            'max2' => $this->max2,
            'max3' => $this->max3
        ];

        return $currentStatus;
    }
}
