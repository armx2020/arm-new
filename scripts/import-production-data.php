<?php

/**
 * Импорт данных из production MySQL dump в PostgreSQL
 * Обрабатывает файл построчно для экономии памяти
 */

if ($argc < 2) {
    echo "Usage: php import-production-data.php <input.sql>\n";
    exit(1);
}

$inputFile = $argv[1];

if (!file_exists($inputFile)) {
    echo "❌ Файл не найден: $inputFile\n";
    exit(1);
}

echo "📖 Обрабатываю файл: $inputFile\n";
echo "🔧 Конвертирую MySQL → PostgreSQL...\n\n";

$input = fopen($inputFile, 'r');
if (!$input) {
    echo "❌ Не удалось открыть файл\n";
    exit(1);
}

// Подключаемся к PostgreSQL напрямую
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Переключаемся на PostgreSQL
config(['database.default' => 'pgsql']);

$lineCount = 0;
$insertCount = 0;
$skipCount = 0;
$currentInsert = '';
$inMultilineInsert = false;

echo "📦 Импортирую данные...\n";

while (($line = fgets($input)) !== false) {
    $lineCount++;
    
    $trimmed = trim($line);
    
    // Пропускаем комментарии
    if (preg_match('/^--/', $trimmed) || $trimmed === '') {
        continue;
    }
    
    // Пропускаем MySQL-специфичные команды
    if (preg_match('/^\/\*!/', $trimmed) ||
        preg_match('/^SET /', $trimmed) ||
        preg_match('/^START TRANSACTION/', $trimmed) ||
        preg_match('/^COMMIT/', $trimmed) ||
        preg_match('/^CREATE DATABASE/', $trimmed) ||
        preg_match('/^USE /', $trimmed)) {
        $skipCount++;
        continue;
    }
    
    // Заменяем backticks на кавычки
    $line = str_replace('`', '"', $line);
    
    // Исправляем даты
    $line = preg_replace("/'0000-00-00 00:00:00'/", 'NULL', $line);
    $line = preg_replace("/'0000-00-00'/", 'NULL', $line);
    
    // Если это начало INSERT команды
    if (preg_match('/^INSERT INTO/', $trimmed)) {
        $currentInsert = $line;
        $inMultilineInsert = !preg_match('/;\s*$/', $trimmed);
        
        // Если INSERT на одной строке - выполняем сразу
        if (!$inMultilineInsert) {
            try {
                DB::statement($currentInsert);
                $insertCount++;
                if ($insertCount % 100 === 0) {
                    echo "  Импортировано записей: $insertCount\r";
                }
            } catch (\Exception $e) {
                // Игнорируем ошибки дубликатов
                if (!str_contains($e->getMessage(), 'duplicate key')) {
                    echo "\n⚠️  Ошибка на строке $lineCount: " . substr($e->getMessage(), 0, 100) . "\n";
                }
            }
            $currentInsert = '';
        }
    } elseif ($inMultilineInsert) {
        // Продолжаем многострочный INSERT
        $currentInsert .= $line;
        
        // Если строка заканчивается точкой с запятой - выполняем
        if (preg_match('/;\s*$/', $trimmed)) {
            try {
                DB::statement($currentInsert);
                $insertCount++;
                if ($insertCount % 100 === 0) {
                    echo "  Импортировано записей: $insertCount\r";
                }
            } catch (\Exception $e) {
                if (!str_contains($e->getMessage(), 'duplicate key')) {
                    echo "\n⚠️  Ошибка на строке $lineCount: " . substr($e->getMessage(), 0, 100) . "\n";
                }
            }
            $currentInsert = '';
            $inMultilineInsert = false;
        }
    }
}

fclose($input);

echo "\n\n";
echo "================================\n";
echo "✅ Импорт завершен!\n";
echo "================================\n";
echo "  Прочитано строк: " . number_format($lineCount) . "\n";
echo "  Импортировано INSERT: " . number_format($insertCount) . "\n";
echo "  Пропущено команд: " . number_format($skipCount) . "\n";
echo "================================\n";

exit(0);
