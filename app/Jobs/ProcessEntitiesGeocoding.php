<?php

namespace App\Jobs;

use App\Models\Entity;
use App\Services\YandexGeocoderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessEntitiesGeocoding implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Entity $entity)
    {
        //
    }

    public function handle(YandexGeocoderService $geocoder): void
    {
        $coordinates = $geocoder->geocode($this->entity->city->name, ', ', $this->entity->address, false);

        if ($coordinates) {
            $this->entity->update([
                'lat' => $coordinates['lat'],
                'lon' => $coordinates['lon']
            ]);
        } else {
            Log::warning("ProcessEntitiesGeocoding: No coordinates for entity {$this->entity->id}");
            $this->release(86400); // Откладываем на 24 часа (86400 секунд)
            return;
        }
    }
}
