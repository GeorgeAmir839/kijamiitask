<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefereeIdColumnToContestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contests', function (Blueprint $table) {
            // $table->foreignId('referee_id')->constrained()->references('id')->on('contesrs')
            // ->nullable()->change();
            $table->unsignedBigInteger('referee_id')->nullable()->after('name');
            $table->foreign('referee_id')->references('id')->on('referees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contests', function (Blueprint $table) {
            $table->dropForeign('contests_referee_id_foreign');
            $table->dropColumn('referee_id');
        });
    }
}
