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
}
