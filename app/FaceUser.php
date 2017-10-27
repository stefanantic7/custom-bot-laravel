<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaceUser extends Model
{
    private $max1=0;
    private $max2=0;
    private $max3=0;


    private $suggestedRule;
    private $suggestedRuleSecond;
    private $suggestedRuleThird;

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

    public function getSuggestedRuleAttribute()
    {
        return $this->suggestedRule;
    }
    public function setSuggestedRuleAttribute($value)
    {
        $this->suggestedRule = $value;
    }

    public function getSuggestedRuleSecondAttribute()
    {
        return $this->suggestedRuleSecond;
    }
    public function setSuggestedRuleSecondAttribute($value)
    {
        $this->suggestedRuleSecond = $value;
    }

    public function getSuggestedRuleThirdAttribute()
    {
        return $this->suggestedRuleThird;
    }
    public function setSuggestedRuleThirdAttribute($value)
    {
        $this->suggestedRuleThird = $value;
    }

    public function getMoreRelevant($currentMax, $rule) {
        $conditions = array_merge($rule->conditions, $rule->mainConditions);
        $trueStatements = json_decode($this->trueStatements);
        $counter = 0;
        foreach ($conditions as $condition) {
            $condition = $condition->text;
            if (in_array($condition, $trueStatements)) {
                $counter++;
//                $this->conditionsForSuggested++;
            }
        }
        if($counter > $currentMax) {
            $this->suggestedRuleThird = $this->suggestedRuleSecond;
            $this->max3 = $this->max2;

            $this->suggestedRuleSecond = $this->suggestedRule;
            $this->max2 = $this->max1;

            $this->suggestedRule = $rule;
            $this->max1 = $counter;
        }
        else if($counter > $this->max2) {
            $this->suggestedRuleThird = $this->suggestedRuleSecond;
            $this->max3 = $this->max2;

            $this->suggestedRuleSecond = $rule;
            $this->max2 = $counter;
        }
        else if($counter > $this->max3) {
            $this->suggestedRuleThird = $rule;
            $this->max3 = $counter;
        }
//        $this->conditionsForSuggested = 0;

        $currentStatus = [
            'max1' => $this->max1,
            'max2' => $this->max2,
            'max3' => $this->max3
        ];

        return $currentStatus;
    }
}
