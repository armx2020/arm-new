<?php

/**
 * Обновляет sequences в PostgreSQL после импорта данных
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🔄 Обновление sequences в PostgreSQL...\n\n";

// Переключаемся на PostgreSQL
config(['database.default' => 'pgsql']);

// Получаем список всех таблиц
$tables = DB::select("
    SELECT tablename 
    FROM pg_tables 
    WHERE schemaname = 'public'
    AND tablename NOT LIKE 'pg_%'
    AND tablename != 'migrations'
");

$updated = 0;
$errors = 0;

foreach ($tables as $table) {
    $tableName = $table->tablename;
    
    // Проверяем, есть ли колонка 'id'
    if (Schema::hasColumn($tableName, 'id')) {
        try {
            // Получаем название sequence
            $sequenceName = "{$tableName}_id_seq";
            
            // Проверяем, существует ли sequence
            $sequenceExists = DB::selectOne("
                SELECT EXISTS (
                    SELECT 1 
                    FROM pg_class 
                    WHERE relname = ? 
                    AND relkind = 'S'
                ) as exists
            ", [$sequenceName]);
            
            if ($sequenceExists->exists) {
                // Получаем максимальный ID
                $maxId = DB::table($tableName)->max('id');
                
                if ($maxId !== null) {
                    // Обновляем sequence
                    DB::statement("SELECT setval('{$sequenceName}', ?)  ", [$maxId]);
                    echo "✅ {$tableName}: sequence обновлен (max ID: {$maxId})\n";
                    $updated++;
                } else {
                    echo "⚠️  {$tableName}: таблица пустая, sequence не обновлен\n";
                }
            }
        } catch (\Exception $e) {
            echo "❌ {$tableName}: {$e->getMessage()}\n";
            $errors++;
        }
    }
}

echo "\n";
echo "================================\n";
echo "📊 Результаты:\n";
echo "  Обновлено: {$updated}\n";
echo "  Ошибок: {$errors}\n";
echo "================================\n";

exit($errors > 0 ? 1 : 0);
