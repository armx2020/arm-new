<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculateDoubles extends Command
{
    protected $signature = 'app:calculate-doubles';

    protected $description = 'Calculate doubles for all entities';

    protected $fields = [
        'name'      => 'Название',
        'phone'     => 'Телефон',
        'address'   => 'Адрес',
        'email'     => 'Почта',
        'web'       => 'Сайт',
        'vkontakte' => 'Вконтакте',
        'whatsapp'  => 'Whatsapp',
        'telegram'  => 'Telegram',
        'instagram' => 'Instagram',
    ];

    public function handle()
    {
        DB::table('entities')->update(['double' => 0]);
        $this->info('Сброшено значение double для всех записей.');

        foreach ($this->fields as $field => $displayName) {
            $dupValues = DB::table('entities')
                ->select($field, DB::raw('COUNT(*) as cnt'))
                ->whereNotNull($field)
                ->where($field, '<>', '')
                ->groupBy($field)
                ->havingRaw('COUNT(*) > 1')
                ->pluck($field);

            if ($dupValues->count() > 0) {
                DB::table('entities')
                    ->whereIn($field, $dupValues)
                    ->update(['double' => 1]);
                $this->info("Для поля '{$field}' найдены дубли. Обновлено значение double.");
            }
        }

        $this->info('Обновление колонки double завершено.');
        return 0;
    }
}
