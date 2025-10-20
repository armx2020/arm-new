<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_maps', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('url', 255)->index();
            $table->foreignId('site_map_type_id')->nullable()->constrained();
            $table->string('name', 255)->index()->nullable();
            $table->string('title', 255)->index()->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('quantity_entity')->unsigned()->nullable()->default(0);
            $table->foreignId('region_id')->nullable()->constrained();
            $table->foreignId('city_id')->nullable()->constrained();
            $table->foreignId('category_id')->nullable()->constrained();
            $table->foreignId('entity_type_id')->nullable()->constrained();
            $table->foreignId('entity_id')->nullable()->onDelete('cascade');
            $table->boolean('index')->default(false);
            $table->text('meta_1')->nullable();
            $table->text('meta_2')->nullable();
            $table->text('meta_3')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_maps');
    }
};
