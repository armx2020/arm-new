<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 255);
            $table->string('transcription', 255)->nullable();
            $table->foreignId('entity_type_id')->nullable()->constrained();
            $table->boolean('activity')->default(true);
            $table->string('address', 128)->nullable()->index();
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lon', 11, 6)->nullable();
            $table->string('image', 255)->nullable();
            $table->text('description')->nullable();
            $table->integer('fullness')->default(0);
            $table->boolean('top')->default(0);
            $table->string('phone', 36)->nullable();
            $table->text('web')->nullable();
            $table->text('whatsapp')->nullable();
            $table->string('instagram', 255)->nullable();
            $table->string('vkontakte', 255)->nullable();
            $table->string('telegram', 255)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->default(1)->constrained();
            $table->foreignId('region_id')->default(1)->constrained();
            $table->tinyInteger('region_top')->default(0)->nullable();
            $table->tinyInteger('city_top')->default(0)->nullable();
            $table->foreignId('category_id')->nullable()->constrained();
            $table->text('comment')->nullable();
            $table->text('clinic')->nullable();
            $table->string('director', 255)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->text('email', 96)->nullable();
            $table->string('link', 400 )->nullable();
            $table->text('video_url')->nullable();
            $table->text('paymant_link')->nullable();
            $table->boolean('double')->default(false);
            $table->integer('sort_id')->index()->default(0);
            $table->boolean('checked')->index()->default(1);
            $table->unsignedBigInteger('moderator_id')->nullable();
            $table->text('meta_1')->nullable();
            $table->text('meta_2')->nullable();
        });

        DB::statement(
            'ALTER TABLE entities ADD FULLTEXT fulltext_index(name, description, phone)'
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};
