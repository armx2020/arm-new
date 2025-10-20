<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appeals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 32);
            $table->string('phone', 36);
            $table->text('message')->nullable();
            $table->boolean('activity')->default(true);
            $table->foreignId('entity_id')->nullable()->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
        });

        DB::statement(
            'ALTER TABLE appeals ADD FULLTEXT fulltext_index(`name`, message, phone)'
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('appeals');
    }
};
