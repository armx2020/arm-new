<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE telegram_messages ADD FULLTEXT INDEX telegram_messages_text_fulltext (text)');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE telegram_messages DROP INDEX telegram_messages_text_fulltext');
        }
    }
};
