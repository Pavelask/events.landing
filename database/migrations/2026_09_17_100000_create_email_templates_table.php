<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Системный ключ (registration-confirmation, ticket, ...) либо slug рассылочного шаблона');
            $table->string('name')->nullable()->comment('Человекочитаемое название');
            $table->string('subject')->nullable()->comment('Тема письма (можно с переменными)');
            $table->longText('content')->comment('HTML-тело письма из Tiptap-редактора');
            $table->json('variables')->nullable()->comment('Описание доступных переменных шаблона');
            $table->foreignId('form_template_id')->nullable()->constrained()->nullOnDelete()->comment('Форма-источник переменных (вопросы)');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
