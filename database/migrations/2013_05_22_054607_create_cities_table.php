<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->index();
            $table->string('name_ru', 255)->index();
            $table->string('name_en', 255);
            $table->string('name_ru_locative', 255);
            $table->string('name_dat', 255)->nullable()->index();
            $table->string('transcription', 255)->index();
            $table->foreignId('region_id')->constrained()->index();
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lon', 11, 6)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
