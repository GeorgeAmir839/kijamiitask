<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('start_contest');
            $table->string('first_team')->nullable();
            $table->string('second_team')->nullable();
            $table->string('winner_team')->nullable();
            $table->string('loser_team')->nullable();
            $table->integer('number_of_goals')->nullable();
            $table->string('goal_scorers')->nullable();
            $table->foreignId('league_id')->constrained()->references('id')->on('leagues')
            ->nullable();
            // $table->foreignId('team_id')->constrained()->references('id')->on('teams')->nullable();

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
        Schema::dropIfExists('contests');
    }
}
