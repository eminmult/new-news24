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
        Schema::table('main_info', function (Blueprint $table) {
            $table->string('about_title')->nullable()->after('meta_keywords');
            $table->string('about_subtitle')->nullable()->after('about_title');
            $table->text('about_intro')->nullable()->after('about_subtitle');
            $table->string('founder_name')->nullable()->after('about_intro');
            $table->string('founder_title')->nullable()->after('founder_name');
            $table->text('founder_description')->nullable()->after('founder_title');
            $table->string('founder_image')->nullable()->after('founder_description');
            $table->text('cooperation_text')->nullable()->after('founder_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_info', function (Blueprint $table) {
            $table->dropColumn([
                'about_title',
                'about_subtitle',
                'about_intro',
                'founder_name',
                'founder_title',
                'founder_description',
                'founder_image',
                'cooperation_text',
            ]);
        });
    }
};
