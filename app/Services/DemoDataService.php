<?php

namespace App\Services;

use Illuminate\Support\Collection;

class DemoDataService
{
    /**
     * Получить демо-сущности (компании/места)
     */
    public function getEntities(): Collection
    {
        return collect([
            ['id' => 1, 'name' => 'Ресторан "Арарат"', 'entity_type_id' => 1, 'region_id' => 1, 'activity' => 1],
            ['id' => 2, 'name' => 'Армянская школа №5', 'entity_type_id' => 2, 'region_id' => 2, 'activity' => 1],
            ['id' => 3, 'name' => 'Магазин "Ереван"', 'entity_type_id' => 1, 'region_id' => 1, 'activity' => 1],
            ['id' => 4, 'name' => 'Церковь Святого Григория', 'entity_type_id' => 3, 'region_id' => 3, 'activity' => 1],
            ['id' => 5, 'name' => 'Кафе "Лаваш"', 'entity_type_id' => 1, 'region_id' => 1, 'activity' => 1],
            ['id' => 6, 'name' => 'Культурный центр', 'entity_type_id' => 2, 'region_id' => 2, 'activity' => 1],
            ['id' => 7, 'name' => 'Юридическая фирма', 'entity_type_id' => 4, 'region_id' => 1, 'activity' => 1],
            ['id' => 8, 'name' => 'Медицинский центр', 'entity_type_id' => 4, 'region_id' => 3, 'activity' => 1],
            ['id' => 9, 'name' => 'Школа танцев', 'entity_type_id' => 2, 'region_id' => 2, 'activity' => 1],
            ['id' => 10, 'name' => 'Армянская община', 'entity_type_id' => 5, 'region_id' => 4, 'activity' => 1],
        ]);
    }

    /**
     * Получить демо-регионы
     */
    public function getRegions(): Collection
    {
        return collect([
            ['id' => 1, 'name' => 'Москва', 'activity' => 1],
            ['id' => 2, 'name' => 'Санкт-Петербург', 'activity' => 1],
            ['id' => 3, 'name' => 'Краснодар', 'activity' => 1],
            ['id' => 4, 'name' => 'Сочи', 'activity' => 1],
            ['id' => 5, 'name' => 'Ростов-на-Дону', 'activity' => 1],
            ['id' => 6, 'name' => 'Екатеринбург', 'activity' => 1],
            ['id' => 7, 'name' => 'Новосибирск', 'activity' => 1],
            ['id' => 8, 'name' => 'Казань', 'activity' => 1],
            ['id' => 9, 'name' => 'Нижний Новгород', 'activity' => 1],
            ['id' => 10, 'name' => 'Самара', 'activity' => 1],
        ]);
    }

    /**
     * Получить демо-категории
     */
    public function getCategories(): Collection
    {
        return collect([
            ['id' => 1, 'name' => 'Рестораны и кафе', 'activity' => 1],
            ['id' => 2, 'name' => 'Образование', 'activity' => 1],
            ['id' => 3, 'name' => 'Культура', 'activity' => 1],
            ['id' => 4, 'name' => 'Медицина', 'activity' => 1],
            ['id' => 5, 'name' => 'Услуги', 'activity' => 1],
            ['id' => 6, 'name' => 'Торговля', 'activity' => 1],
            ['id' => 7, 'name' => 'Религия', 'activity' => 1],
            ['id' => 8, 'name' => 'Спорт', 'activity' => 1],
            ['id' => 9, 'name' => 'Развлечения', 'activity' => 1],
            ['id' => 10, 'name' => 'Туризм', 'activity' => 1],
        ]);
    }

    /**
     * Получить демо-Telegram группы
     */
    public function getTelegramGroups(): Collection
    {
        return collect([
            ['id' => 1, 'title' => 'Армяне Москвы', 'username' => 'armenians_moscow', 'description' => 'Сообщество армян в Москве'],
            ['id' => 2, 'title' => 'Армянский бизнес', 'username' => 'arm_business', 'description' => 'Деловое сообщество'],
            ['id' => 3, 'title' => 'Армянская кухня', 'username' => 'armenian_food', 'description' => 'Рецепты и рестораны'],
            ['id' => 4, 'title' => 'Армянская культура', 'username' => 'arm_culture', 'description' => 'Культурные события'],
            ['id' => 5, 'title' => 'Работа для армян', 'username' => 'arm_jobs', 'description' => 'Вакансии и резюме'],
            ['id' => 6, 'title' => 'Армяне СПб', 'username' => 'armenians_spb', 'description' => 'Сообщество в Петербурге'],
        ]);
    }

    /**
     * Получить демо-сообщения Telegram
     */
    public function getTelegramMessages(): Collection
    {
        return collect([
            ['id' => 1, 'group_id' => 1, 'text' => 'Добро пожаловать в наше сообщество!', 'date' => '2025-01-15'],
            ['id' => 2, 'group_id' => 1, 'text' => 'Кто знает хороший армянский ресторан?', 'date' => '2025-01-16'],
            ['id' => 3, 'group_id' => 2, 'text' => 'Ищу партнёров для бизнеса', 'date' => '2025-01-17'],
            ['id' => 4, 'group_id' => 3, 'text' => 'Рецепт настоящего долмы', 'date' => '2025-01-18'],
            ['id' => 5, 'group_id' => 4, 'text' => 'Концерт армянской музыки в эту субботу', 'date' => '2025-01-19'],
            ['id' => 6, 'group_id' => 5, 'text' => 'Вакансия: менеджер по продажам', 'date' => '2025-01-20'],
            ['id' => 7, 'group_id' => 2, 'text' => 'Новые возможности для стартапов', 'date' => '2025-01-21'],
            ['id' => 8, 'group_id' => 3, 'text' => 'Где купить лаваш в Москве?', 'date' => '2025-01-22'],
            ['id' => 9, 'group_id' => 6, 'text' => 'Встреча армянской общины в СПб', 'date' => '2025-01-23'],
            ['id' => 10, 'group_id' => 4, 'text' => 'Выставка армянских художников', 'date' => '2025-01-24'],
        ]);
    }

    /**
     * Получить демо-пользователей
     */
    public function getUsers(): Collection
    {
        return collect([
            ['id' => 1, 'name' => 'Арам Петросян', 'email' => 'aram@example.com'],
            ['id' => 2, 'name' => 'Мария Саркисян', 'email' => 'maria@example.com'],
            ['id' => 3, 'name' => 'Давид Григорян', 'email' => 'david@example.com'],
            ['id' => 4, 'name' => 'Анна Мкртчян', 'email' => 'anna@example.com'],
            ['id' => 5, 'name' => 'Гарик Авакян', 'email' => 'garik@example.com'],
        ]);
    }

    /**
     * Проверить, включен ли DEMO режим
     */
    public function isDemoMode(): bool
    {
        // На продакшене DEMO режим ВСЕГДА выключен
        if (config('app.env') === 'production') {
            return false;
        }
        
        // На Replit: проверяем сессию, если есть
        // По умолчанию TRUE (быстрая загрузка)
        return session()->get('demo_mode', true);
    }
}
