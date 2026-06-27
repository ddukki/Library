<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('editions', function (Blueprint $table) {
            $table->dropForeign(['location_type_id']);
        });

        Schema::rename('location_types', 'extent_types');

        Schema::table('editions', function (Blueprint $table) {
            $table->renameColumn('location_type_id', 'extent_type_id');
            $table->renameColumn('location_size', 'extent');
        });

        Schema::table('editions', function (Blueprint $table) {
            $table->foreign('extent_type_id')
                  ->references('id')->on('extent_types')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('editions', function (Blueprint $table) {
            $table->dropForeign(['extent_type_id']);
            $table->renameColumn('extent_type_id', 'location_type_id');
            $table->renameColumn('extent', 'location_size');
        });

        Schema::rename('extent_types', 'location_types');

        Schema::table('editions', function (Blueprint $table) {
            $table->foreign('location_type_id')
                  ->references('id')->on('location_types')
                  ->onDelete('cascade');
        });
    }
};
