<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

class TranscriptService
{
    private $converter = array(
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'e',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'y',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sch',
        'ь' => '',
        'ы' => 'y',
        'ъ' => '',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
    );

    private function getTranslit($value)
    {
        if ($value == 'Россия') {
            $value = 'russia';
        } elseif ($value == 'не выбрано') {
            $value = 'no-selected';
        } else {
            $value = mb_strtolower($value);
            $value = strtr($value, $this->converter);
            $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
            $value = mb_ereg_replace('[-]+', '-', $value);
            $value = trim($value, '-');
        }

        return $value;
    }

    public function translitName($table)
    {
        $table->chunk(100, function (Collection $collections) {
            foreach ($collections as $entity) {

                $transcription = $this->getTranslit($entity->name);

                $entity->update([
                    'transcription' => $transcription
                ]);
            }
        });
    }
}
