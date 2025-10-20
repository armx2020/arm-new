<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_entity', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('entity_id')->onDelete('cascade');
            $table->unsignedBigInteger('main_category_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_entity');
    }
};
