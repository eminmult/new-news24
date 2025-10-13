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
        // First, drop the foreign key constraint from posts table
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
        });

        // Then drop the authors table
        Schema::dropIfExists('authors');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't need to recreate the authors table
        // All data has been migrated to users table
    }
};
