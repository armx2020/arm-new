<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_messages', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('group_id');
            $table->bigInteger('user_id');
            $table->text('text');
            $table->timestamp('date');
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('telegram_groups');
            $table->foreign('user_id')->references('id')->on('telegram_users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_messages');
    }
};
