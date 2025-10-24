<?php

/**
 * Конвертирует MySQL dump в PostgreSQL формат
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

echo "📖 Читаем MySQL dump...\n";
$content = file_get_contents($inputFile);

if ($content === false) {
    echo "❌ Не удалось прочитать файл\n";
    exit(1);
}

echo "🔧 Конвертируем в PostgreSQL формат...\n";

// 1. Убираем MySQL-специфичные кавычки (backticks)
$content = str_replace('`', '"', $content);

// 2. Конвертируем булевы значения
$content = preg_replace('/,0,/', ',FALSE,', $content);
$content = preg_replace('/,1,/', ',TRUE,', $content);
$content = preg_replace('/,0\)/', ',FALSE)', $content);
$content = preg_replace('/,1\)/', ',TRUE)', $content);

// 3. Конвертируем NULL в правильный формат
$content = str_replace("'NULL'", 'NULL', $content);

// 4. Исправляем даты
$content = preg_replace("/'0000-00-00 00:00:00'/", 'NULL', $content);
$content = preg_replace("/'0000-00-00'/", 'NULL', $content);

// 5. Убираем лишние пробелы
$content = preg_replace('/\s+/', ' ', $content);

// 6. Добавляем заголовок
$header = "-- Converted from MySQL to PostgreSQL\n";
$header .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
$header .= "SET session_replication_role = 'replica';\n\n";

// 7. Добавляем футер
$footer = "\nSET session_replication_role = 'origin';\n";

$content = $header . $content . $footer;

echo "💾 Сохраняем результат...\n";
$result = file_put_contents($outputFile, $content);

if ($result === false) {
    echo "❌ Ошибка сохранения файла\n";
    exit(1);
}

$sizeKb = round(filesize($outputFile) / 1024, 2);
echo "✅ Конвертация завершена! Размер: {$sizeKb} KB\n";
echo "📄 Файл сохранен: $outputFile\n";

exit(0);
