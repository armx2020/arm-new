<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name_ru', 255);
            $table->string('name_en', 255);
            $table->text('value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
