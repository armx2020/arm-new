<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entity_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 255)->fulltext();
            $table->string('transcription', 255)->nullable();
            $table->boolean('activity')->default(true);
        });

        DB::table('entity_types')->insert([
            'name' => 'Компания',
        ]);
        DB::table('entity_types')->insert([
            'name' => 'Группа',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('entity_types');
    }
};
