<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->morphs('imageable');
            $table->string('path', 255)->nullable();
            $table->integer('sort_id')->index()->default(1);
            $table->boolean('checked')->index()->default(1);
            $table->boolean('is_logo')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
