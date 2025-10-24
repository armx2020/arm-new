<?php

/**
 * Конвертирует MySQL dump в PostgreSQL формат
 * Обрабатывает файл построчно для больших дампов
 */

if ($argc < 3) {
    echo "Usage: php convert-mysql-to-postgres.php <input.sql> <output.sql>\n";
    exit(1);
}

$inputFile = $argv[1];
$outputFile = $argv[2];

if (!file_exists($inputFile)) {
    echo "❌ Файл не найден: $inputFile\n";
    exit(1);
}

echo "📖 Читаем MySQL dump построчно...\n";

$input = fopen($inputFile, 'r');
if (!$input) {
    echo "❌ Не удалось открыть файл для чтения\n";
    exit(1);
}

$output = fopen($outputFile, 'w');
if (!$output) {
    echo "❌ Не удалось создать выходной файл\n";
    fclose($input);
    exit(1);
}

echo "🔧 Конвертируем MySQL → PostgreSQL...\n";

// Пишем заголовок
fwrite($output, "-- Converted from MySQL to PostgreSQL\n");
fwrite($output, "-- Generated: " . date('Y-m-d H:i:s') . "\n\n");
fwrite($output, "SET session_replication_role = 'replica';\n\n");

$lineCount = 0;
$processedCount = 0;

while (($line = fgets($input)) !== false) {
    $lineCount++;
    
    // Пропускаем комментарии и пустые строки
    if (preg_match('/^--/', $line) || trim($line) === '') {
        continue;
    }
    
    // 1. Убираем MySQL-специфичные кавычки (backticks)
    $line = str_replace('`', '"', $line);
    
    // 2. Конвертируем булевы значения
    // Паттерны: ,0, -> ,FALSE,  ,1, -> ,TRUE,
    $line = preg_replace('/,\s*0\s*,/', ',FALSE,', $line);
    $line = preg_replace('/,\s*1\s*,/', ',TRUE,', $line);
    $line = preg_replace('/,\s*0\s*\)/', ',FALSE)', $line);
    $line = preg_replace('/,\s*1\s*\)/', ',TRUE)', $line);
    
    // В VALUES в начале: (0, -> (FALSE,   (1, -> (TRUE,
    $line = preg_replace('/\(\s*0\s*,/', '(FALSE,', $line);
    $line = preg_replace('/\(\s*1\s*,/', '(TRUE,', $line);
    
    // 3. Исправляем даты
    $line = preg_replace("/'0000-00-00 00:00:00'/", 'NULL', $line);
    $line = preg_replace("/'0000-00-00'/", 'NULL', $line);
    
    // 4. Исправляем 'NULL' строки в NULL
    $line = preg_replace("/,'NULL',/", ',NULL,', $line);
    $line = preg_replace("/,'NULL'\)/", ',NULL)', $line);
    
    fwrite($output, $line);
    $processedCount++;
    
    // Прогресс каждые 10000 строк
    if ($processedCount % 10000 === 0) {
        echo "  Обработано строк: " . number_format($processedCount) . "\r";
    }
}

// Футер
fwrite($output, "\nSET session_replication_role = 'origin';\n");

fclose($input);
fclose($output);

$sizeKb = round(filesize($outputFile) / 1024, 2);
echo "\n✅ Конвертация завершена!\n";
echo "   Прочитано строк: " . number_format($lineCount) . "\n";
echo "   Обработано строк: " . number_format($processedCount) . "\n";
echo "   Размер выходного файла: {$sizeKb} KB\n";
echo "📄 Файл сохранен: $outputFile\n";

exit(0);
