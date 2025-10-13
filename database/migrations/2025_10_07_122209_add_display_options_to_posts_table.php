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
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('show_on_homepage')->default(true)->after('published_at');
            $table->boolean('show_in_slider')->default(false)->after('show_on_homepage');
            $table->boolean('is_hidden')->default(false)->after('show_in_slider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['show_on_homepage', 'show_in_slider', 'is_hidden']);
        });
    }
};
