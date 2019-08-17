<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('edition_id');
            $table->unsignedBigInteger('user_id');
            $table->mediumText('quote');
            $table->mediumInteger('location');
            $table->timestamps();
        });

        Schema::table('quotes', function (Blueprint $table) {
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
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
        });

        Schema::dropIfExists('quotes');
    }
}
