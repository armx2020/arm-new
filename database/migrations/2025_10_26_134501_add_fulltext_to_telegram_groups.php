<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE telegram_groups ADD FULLTEXT INDEX telegram_groups_fulltext (title, username, description)');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE telegram_groups DROP INDEX telegram_groups_fulltext');
        }
    }
};
