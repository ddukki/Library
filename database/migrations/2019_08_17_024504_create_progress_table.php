<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('edition_id');
            $table->unsignedBigInteger('user_id');
            $table->mediumInteger('location_start');
            $table->mediumInteger('location_end');
            $table->timestamp('datetime');
            $table->timestamps();
        });

        Schema::table('progress', function (Blueprint $table) {
            $table->foreign('edition_id')
                    ->references('id')->on('editions')
                    ->onDelete('cascade');
            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('progress', function (Blueprint $table) {
            $table->dropForeign(['edition_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('progress');
    }
}
