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
            $table->string('name', 255);
            $table->string('transcription', 255)->nullable();
            $table->boolean('activity')->default(true);
        });

        if (DB::connection()->getDriverName() !== 'sqlite') {
            Schema::table('entity_types', function (Blueprint $table) {
                $table->fullText('name');
            });
        }

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
