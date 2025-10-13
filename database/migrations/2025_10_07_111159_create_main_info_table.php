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
        Schema::create('main_info', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->string('site_url')->nullable();
            $table->text('site_title')->nullable();
            $table->text('site_description')->nullable();
            $table->text('address')->nullable();
            $table->json('emails')->nullable();
            $table->json('phones')->nullable();
            $table->string('fax')->nullable();
            $table->string('location')->nullable(); // Google Maps link
            $table->json('reklam_phones')->nullable();
            $table->json('reklam_emails')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_info');
    }
};
