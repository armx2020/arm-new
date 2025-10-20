<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_map_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 255)->index();
        });

        DB::table('site_map_types')->insert([
            ['name' => 'домашняя (общая)'],
            ['name' => 'домашняя (область)'],
            ['name' => 'домашняя (город)'],
            ['name' => 'тип сущности (общая)'],
            ['name' => 'тип сущности (область)'],
            ['name' => 'тип сущности (город)'],
            ['name' => 'категория (общая)'],
            ['name' => 'категория (область)'],
            ['name' => 'категория (город)'],
            ['name' => 'сущность'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_map_types');
    }
};
