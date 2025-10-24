<?php

/**
 * Проверяет целостность данных после миграции
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 Проверка целостности данных...\n\n";

// Переключаемся на PostgreSQL
config(['database.default' => 'pgsql']);

$checks = [
    'users' => 'Пользователи',
    'entities' => 'Сущности (компании, группы и т.д.)',
    'categories' => 'Категории',
    'images' => 'Изображения',
    'offers' => 'Акции',
    'appeals' => 'Обращения',
    'regions' => 'Регионы',
    'cities' => 'Города',
];

$totalRecords = 0;

echo "📊 Количество записей:\n";
echo "─────────────────────────────────────\n";

foreach ($checks as $table => $name) {
    try {
        $count = DB::table($table)->count();
        $totalRecords += $count;
        printf("%-30s %10d\n", $name, $count);
    } catch (\Exception $e) {
        echo "❌ {$name}: таблица не найдена\n";
    }
}

echo "─────────────────────────────────────\n";
printf("%-30s %10d\n", "ВСЕГО ЗАПИСЕЙ", $totalRecords);
echo "\n";

// Проверка связей (Foreign Keys)
echo "🔗 Проверка связей:\n";
echo "─────────────────────────────────────\n";

$relationChecks = [
    ['table' => 'entities', 'column' => 'user_id', 'ref_table' => 'users', 'name' => 'Entity → User'],
    ['table' => 'entities', 'column' => 'category_id', 'ref_table' => 'categories', 'name' => 'Entity → Category'],
    ['table' => 'entities', 'column' => 'city_id', 'ref_table' => 'cities', 'name' => 'Entity → City'],
    ['table' => 'entities', 'column' => 'region_id', 'ref_table' => 'regions', 'name' => 'Entity → Region'],
    ['table' => 'images', 'column' => 'entity_id', 'ref_table' => 'entities', 'name' => 'Image → Entity'],
    ['table' => 'offers', 'column' => 'entity_id', 'ref_table' => 'entities', 'name' => 'Offer → Entity'],
];

foreach ($relationChecks as $check) {
    try {
        // Проверяем, есть ли записи с несуществующими foreign keys
        $broken = DB::table($check['table'])
            ->whereNotNull($check['column'])
            ->whereNotExists(function ($query) use ($check) {
                $query->select(DB::raw(1))
                    ->from($check['ref_table'])
                    ->whereColumn($check['ref_table'] . '.id', '=', $check['table'] . '.' . $check['column']);
            })
            ->count();
        
        if ($broken === 0) {
            echo "✅ {$check['name']}: OK\n";
        } else {
            echo "❌ {$check['name']}: найдено {$broken} несвязанных записей\n";
        }
    } catch (\Exception $e) {
        echo "⚠️  {$check['name']}: не удалось проверить\n";
    }
}

echo "\n";
echo "================================\n";
echo "✅ Проверка завершена!\n";
echo "================================\n";

exit(0);
