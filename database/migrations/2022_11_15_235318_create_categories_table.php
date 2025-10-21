<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 255);
            $table->string('transcription', 255)->nullable();
            $table->integer('sort_id')->index()->nullable();
            $table->boolean('activity')->default(true);
            $table->foreignId('entity_type_id')->nullable()->constrained();
            $table->foreignId('category_id')->nullable()->constrained();
            $table->string('image', 255)->nullable();
            $table->text('meta_1')->nullable();
            $table->text('meta_2')->nullable();
            $table->index('name');
        });

        if (DB::connection()->getDriverName() !== 'sqlite') {
            Schema::table('categories', function (Blueprint $table) {
                $table->fullText('name');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
