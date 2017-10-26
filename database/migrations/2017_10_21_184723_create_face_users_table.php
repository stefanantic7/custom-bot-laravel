<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaceUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('face_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('face_id')->unique();
            $table->json('trueStatements')->default('[]');
            $table->json('falseStatements')->default('[]');
            $table->string('question')->default('');
//            $table->integer('conditionsForSuggested')->default(0);
//            $table->string('suggestedRule')->nullable();
//            $table->string('suggestedRuleSecond')->nullable();
//            $table->string('suggestedRuleThird')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('face_users');
    }
}
