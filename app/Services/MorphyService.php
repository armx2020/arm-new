<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use morphos\Russian\GeographicalNamesInflection;


class MorphyService
{
    public function setDative($region, $case)
    {
        $region->chunk(50, function (Collection $regions) use ($case) {
            foreach ($regions as $region) {
                if ($region->name !== 'не выбрано') {
                    $region->update([
                        $region->name_dat =  'в ' . GeographicalNamesInflection::getCase($region->name, $case)
                    ]);
                } else {
                    $region->update([
                        $region->name_dat =  'не выбрано'
                    ]);
                }
            }
        });
    }
}
