<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 255);
            $table->boolean('activity')->default(true)->index();
            $table->string('address', 128)->nullable();
            $table->string('image', 255)->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->default(1)->constrained();
            $table->foreignId('region_id')->default(1)->constrained();
            $table->foreignId('category_id')->nullable()->constrained();
            $table->foreignId('entity_id')->nullable()->constrained();
            $table->text('comment')->nullable();
        });

        DB::statement(
            'ALTER TABLE offers ADD FULLTEXT fulltext_index(name, description)'
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
