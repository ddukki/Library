<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('editions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('book_id');
            $table->string('name');
            $table->unsignedBigInteger('location_type_id');
            $table->integer('location_size');
            $table->timestamps();
        });

        Schema::create('location_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->timestamp('birth_date')->nullable();
            $table->timestamp('death_date')->nullable();
            $table->timestamps();
        });

        Schema::create('book_authors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('author_id');
            $table->timestamps();
        });

        Schema::create('shelves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::create('edition_shelves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shelf_id');
            $table->unsignedBigInteger('edition_id');
            $table->timestamps();
        });

        // Create foreign keys
        Schema::table('book_authors', function (Blueprint $table) {
            $table->foreign('book_id')
                    ->references('id')->on('books')
                    ->onDelete('cascade');
            $table->foreign('author_id')
                    ->references('id')->on('authors')
                    ->onDelete('cascade');
        });

        Schema::table('edition_shelves', function (Blueprint $table) {
            $table->foreign('edition_id')
                    ->references('id')->on('editions')
                    ->onDelete('cascade');
            $table->foreign('shelf_id')
                    ->references('id')->on('shelves')
                    ->onDelete('cascade');
        });

        Schema::table('shelves', function (Blueprint $table) {
            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
        });

        Schema::table('editions', function (Blueprint $table) {
            $table->foreign('book_id')
                    ->references('id')->on('books')
                    ->onDelete('cascade');
            $table->foreign('location_type_id')
                    ->references('id')->on('location_types')
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
        Schema::table('editions', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->dropForeign(['location_type_id']);
        });

        Schema::table('book_authors', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->dropForeign(['author_id']);
        });

        Schema::table('edition_shelves', function (Blueprint $table) {
            $table->dropForeign(['edition_id']);
            $table->dropForeign(['shelf_id']);
        });

        Schema::table('shelves', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('books');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('shelves');
        Schema::dropIfExists('location_types');
        Schema::dropIfExists('book_authors');
        Schema::dropIfExists('edition_shelves');
        Schema::dropIfExists('editions');
    }
}
