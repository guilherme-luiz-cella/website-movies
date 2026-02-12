<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->enum('type', ['movie', 'series'])->default('movie')->after('id');
            $table->integer('seasons')->nullable()->after('year');
            $table->integer('episodes')->nullable()->after('seasons');
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn(['type', 'seasons', 'episodes']);
        });
    }
};
