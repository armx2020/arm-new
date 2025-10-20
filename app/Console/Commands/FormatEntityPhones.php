<?php

namespace App\Console\Commands;

use App\Models\Entity;
use Illuminate\Console\Command;

class FormatEntityPhones extends Command
{

    protected $signature = 'app:format-entity-phones';

    protected $description = 'Format all entity phones to +7 (XXX) XXX-XX-XX format';

    public function handle()
    {
        $countEntitiesWithPhone = Entity::whereNotNull('phone')->count();

        $countEntitiesWithPhone;
        $updated = 0;

        $this->info("Starting phone formatting for {$countEntitiesWithPhone} entities...");

        Entity::whereNotNull('phone')->chunk(100, function ($entities) use (&$updated) {
            foreach ($entities as $entity) {
                $originalPhone = $entity->phone;
                $formattedPhone = $this->formatPhone($originalPhone);

                if ($formattedPhone !== $originalPhone) {
                    $entity->phone = $formattedPhone;
                    $entity->save();
                    $updated++;
                    $this->line("Updated: {$originalPhone} → {$formattedPhone}");
                }
            }
        });

        $this->info("Completed! {$updated} of {$countEntitiesWithPhone} phones were updated.");
        return 0;
    }

    protected function formatPhone(string $phone): string
    {
        // Удаляем все нецифровые символы
        $digits = preg_replace('/[^0-9]/', '', $phone);

        // Если номер пустой - возвращаем как есть
        if (empty($digits)) {
            return $phone;
        }

        // Обрезаем номер до 11 цифр (если длиннее)
        if (strlen($digits) > 11) {
            $digits = substr($digits, 0, 11);
        }

        // Если номер короче 11 цифр - не форматируем
        if (strlen($digits) < 11) {
            return $phone;
        }

        // Если номер начинается с 8, заменяем на 7
        if ($digits[0] === '8') {
            $digits = '7' . substr($digits, 1);
        }

        // Если номер начинается не с 7 - не форматируем
        if ($digits[0] !== '7') {
            return $phone;
        }

        // Форматируем номер
        $code = substr($digits, 1, 3);
        $part1 = substr($digits, 4, 3);
        $part2 = substr($digits, 7, 2);
        $part3 = substr($digits, 9, 2);

        return "+7 ({$code}) {$part1}-{$part2}-{$part3}";
    }
}
