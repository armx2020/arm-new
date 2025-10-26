<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE telegram_users ADD FULLTEXT INDEX telegram_users_fulltext (first_name, last_name, username)');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE telegram_users DROP INDEX telegram_users_fulltext');
        }
    }
};
