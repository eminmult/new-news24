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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable()->index(); // Название лога (например: 'post', 'author', 'category')
            $table->text('description'); // Описание действия
            $table->string('event'); // Тип события: created, updated, deleted, restored

            // Пользователь, который совершил действие
            $table->foreignId('causer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('causer_type')->nullable(); // Polymorphic type

            // Модель, с которой произошло действие
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->index(['subject_type', 'subject_id']);

            // Детали изменений
            $table->json('properties')->nullable(); // JSON с данными: old_values, new_values, attributes

            // Дополнительная информация
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
