<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support enum changes, so we just rely on application validation
        // The status column will accept: pending, watching, watched
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No changes needed for rollback
    }
};
