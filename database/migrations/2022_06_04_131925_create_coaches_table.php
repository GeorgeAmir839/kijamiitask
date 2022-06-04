<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coaches', function (Blueprint $table) {
            $table->id();
            $table->string('coache_name');
            $table->string('job_title')->default('head_coache');
            $table->string('number_of_championships')->nullable();
            $table->foreignId('team_id')->constrained()->references('id')->on('teams')
            ->nullable(); 
            $table->foreignId('user_id')->constrained()->references('id')->on('users')
            ->nullable(); 
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
        Schema::dropIfExists('coaches');
    }
}
