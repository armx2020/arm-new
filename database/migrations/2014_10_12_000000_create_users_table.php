<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 32);
            $table->boolean('activity')->default(true);
            $table->string('email', 96)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('phone', 36)->unique();
            $table->string('phone_fore_verification', 36)->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('whatsapp', 36)->nullable()->unique();
            $table->string('instagram', 255)->nullable()->unique();
            $table->string('vkontakte', 255)->nullable()->unique();
            $table->string('telegram', 255)->nullable()->unique();
            $table->string('image', 255)->nullable();
            $table->string('check_id', 255)->nullable();
            $table->foreignId('city_id')->default(1)->constrained();
            $table->foreignId('region_id')->default(1)->constrained();
            $table->softDeletes('deleted_at', 0);
            $table->index('firstname');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
