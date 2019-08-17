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
            $table->unsignedBigInteger('book_id');
            $table->mediumText('quote');
            $table->mediumInteger('book_location');
            $table->timestamps();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->foreign('book_id')
                    ->references('id')->on('books')
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
