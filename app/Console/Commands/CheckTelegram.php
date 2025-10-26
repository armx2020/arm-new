<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TelegramGroup;
use App\Models\TelegramUser;
use App\Models\TelegramMessage;
use Illuminate\Support\Facades\Schema;

class CheckTelegram extends Command
{
    protected $signature = 'telegram:check';
    protected $description = 'Проверка статуса парсинга Telegram';

    public function handle()
    {
        $this->info('🔍 Проверка системы парсинга Telegram...');
        $this->newLine();

        try {
            if (!Schema::hasTable('telegram_groups')) {
                $this->error('❌ Таблица telegram_groups не существует');
                $this->warn('Запустите: php artisan migrate');
                return 1;
            }

            $this->info('✅ Таблица telegram_groups существует');
            
            $groupsCount = TelegramGroup::count();
            $this->line("📊 Групп в БД: {$groupsCount}");

            if ($groupsCount > 0) {
                $this->newLine();
                $this->info('📋 Список групп:');
                $groups = TelegramGroup::all();
                
                foreach ($groups as $group) {
                    $activity = isset($group->activity) && $group->activity ? '🟢' : '🔴';
                    $this->line("  {$activity} @{$group->username} - {$group->title}");
                    $this->line("     ID: {$group->id}, Добавлена: {$group->created_at}");
                }
            } else {
                $this->warn('⚠️  Нет добавленных групп для парсинга');
            }

            $this->newLine();

            if (Schema::hasTable('telegram_messages')) {
                $messagesCount = TelegramMessage::count();
                $this->line("💬 Сообщений в БД: {$messagesCount}");

                if ($messagesCount > 0) {
                    $latest = TelegramMessage::latest('date')->first();
                    $this->line("📅 Последнее сообщение: {$latest->date}");
                    $this->line("📝 Текст: " . substr($latest->text, 0, 50) . '...');
                }
            } else {
                $this->warn('⚠️  Таблица telegram_messages не существует');
            }

            $this->newLine();

            if (Schema::hasTable('telegram_users')) {
                $usersCount = TelegramUser::count();
                $this->line("👥 Пользователей в БД: {$usersCount}");
            } else {
                $this->warn('⚠️  Таблица telegram_users не существует');
            }

            $this->newLine();

            $apiId = env('TELEGRAM_API_ID');
            $apiHash = env('TELEGRAM_API_HASH');

            if (empty($apiId) || empty($apiHash)) {
                $this->error('❌ TELEGRAM_API_ID и TELEGRAM_API_HASH не настроены в .env');
            } else {
                $this->info('✅ API ключи настроены');
            }

            $sessionPath = storage_path('app/telegram/session.madeline');
            if (file_exists($sessionPath)) {
                $size = filesize($sessionPath);
                $this->info("✅ Сессия авторизована (файл: " . number_format($size) . " bytes)");
            } else {
                $this->warn('⚠️  Сессия не создана (нужна первая авторизация)');
            }

            $this->newLine();
            $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            
            if ($groupsCount > 0 && $messagesCount > 0) {
                $this->info('🎉 Парсинг работает! Данные обновляются.');
            } elseif ($groupsCount > 0) {
                $this->warn('⚠️  Группы добавлены, но сообщений нет. Запустите: php artisan parse-telegram');
            } else {
                $this->warn('⚠️  Система настроена, но группы не добавлены.');
                $this->line('    Добавьте группы в /admin/telegram_group');
            }

        } catch (\Exception $e) {
            $this->error('Ошибка: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
