<?php

namespace App\Console\Commands;

use App\Models\Entity;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class UpdateSocialInEntity extends Command
{
    protected $signature = 'update:social-entity';
    protected $description = 'Обновление whatsapp и telegram у сущностей';

    public function handle()
    {
        Entity::chunk(100, function (Collection $entities) {
            foreach ($entities as $entity) {
                if ($entity->phone) {
                    if ($entity->phone == ' ' || $entity->phone == '') {
                        $entity->phone = null;
                    } else {
                        if ($entity->whatsapp == null || $entity->whatsapp == ' ' || $entity->whatsapp == '') {
                            $entity->whatsapp = 'https://api.whatsapp.com/send/?phone=' . $this->normalizePhoneNumber($entity->phone);
                        }

                        if ($entity->telegram == null || $entity->telegram == ' ' || $entity->telegram == '') {
                            $entity->telegram = 'https://t.me/' . $this->normalizePhoneNumber($entity->phone);
                        }

                        $entity->save();
                    }
                }
            };
        });
    }

    function normalizePhoneNumber($phone)
    {
        // Удаляем все нечисловые символы
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Если номер начинается с 8, заменяем на 7
        if (strlen($phone) == 11 && $phone[0] == '8') {
            $phone[0] = '7';
        }

        // Если номер длиннее 11 символов, обрезаем до 11
        if (strlen($phone) > 11) {
            $phone = substr($phone, 0, 11);
        }

        return $phone;
    }
}
