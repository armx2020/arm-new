<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Используем существующий регион (Россия id=1)
        // Не создаем города - слишком много обязательных полей
        
        // Добавляем демо-пользователя БЕЗ города (используем только регион)
        // Сначала добавляем простой город с минимумом полей
        $cityId = DB::table('cities')->insertGetId([
            'name' => 'Москва',
            'name_ru' => 'Москва',
            'name_en' => 'Moscow',
            'name_ru_locative' => 'Москве',
            'name_dat' => 'Москве',
            'transcription' => 'moscow',
            'region_id' => 1,
            'lat' => 55.7558,
            'lon' => 37.6173,
        ]);

        // Добавляем демо-пользователя
        $userId = DB::table('users')->insertGetId([
            'firstname' => 'Демо Пользователь',
            'email' => 'demo@vsearmyne.ru',
            'phone' => '+79991234567',
            'password' => Hash::make('password'),
            'activity' => true,
            'email_verified_at' => now(),
            'region_id' => 1,
            'city_id' => $cityId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Добавляем демо-сущности (компании, группы, места)
        $entities = [
            [
                'name' => 'Ресторан "Арарат"',
                'entity_type_id' => 3,
                'description' => 'Армянский ресторан с традиционной кухней',
                'region_id' => 1,
                'city_id' => $cityId,
                'user_id' => $userId,
                'activity' => true,
                'lat' => 55.7558,
                'lon' => 37.6173,
            ],
            [
                'name' => 'Армянская община Москвы',
                'entity_type_id' => 6,
                'description' => 'Крупнейшая армянская община в Москве',
                'region_id' => 1,
                'city_id' => $cityId,
                'user_id' => $userId,
                'activity' => true,
                'lat' => 55.7600,
                'lon' => 37.6200,
            ],
            [
                'name' => 'Кафе "Ереван"',
                'entity_type_id' => 3,
                'description' => 'Уютное армянское кафе',
                'region_id' => 1,
                'city_id' => $cityId,
                'user_id' => $userId,
                'activity' => true,
                'lat' => 55.7500,
                'lon' => 37.6100,
            ],
            [
                'name' => 'Армянская воскресная школа',
                'entity_type_id' => 4,
                'description' => 'Школа армянского языка и культуры',
                'region_id' => 1,
                'city_id' => $cityId,
                'user_id' => $userId,
                'activity' => true,
                'lat' => 55.7650,
                'lon' => 37.6250,
            ],
            [
                'name' => 'Магазин "Армения"',
                'entity_type_id' => 3,
                'description' => 'Армянские продукты и сувениры',
                'region_id' => 1,
                'city_id' => $cityId,
                'user_id' => $userId,
                'activity' => true,
                'lat' => 55.7450,
                'lon' => 37.6050,
            ],
        ];

        foreach ($entities as $entity) {
            DB::table('entities')->insert(array_merge($entity, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✅ Демо-данные успешно добавлены!');
        $this->command->info('📊 Добавлено:');
        $this->command->info('  - 1 город (Москва)');
        $this->command->info('  - 1 пользователь (demo@vsearmyne.ru / password)');
        $this->command->info('  - 5 сущностей (рестораны, магазины, группы)');
    }
}
