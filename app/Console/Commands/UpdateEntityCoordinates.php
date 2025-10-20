<?php

namespace App\Console\Commands;

use App\Models\Entity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class UpdateEntityCoordinates extends Command
{
    protected $signature = 'entities:update-coordinates {--limit= : Limit the number of entities to process}';

    protected $description = 'Обновление геогрофических координат с помощью Yandex Geocoder API';

    protected $apiUrl = 'https://geocode-maps.yandex.ru/1.x/';

    protected $apiKey;

    protected $limit = 50;

    public function __construct()
    {
        parent::__construct();
        $this->apiKey = config('services.yandex.geocoder_key');
    }

    public function handle()
    {
        if ($this->option('limit')) {
            $this->limit = (int)$this->option('limit');
        }

        Log::info('start-entities');
        $entities = Entity::query()->limit($this->limit)->with('city')
            ->whereNotNull('city_id')
            ->whereNull('lat')
            ->orWhereNull('lon')
            ->get();
        $totalEntities = $entities->count();

        $this->info("Starting to update coordinates for {$totalEntities} entities...");

        if ($totalEntities === 0) {
            $this->info('No entities to update.');
            return;
        }

        $progressBar = $this->output->createProgressBar($totalEntities);
        $progressBar->start();

        $updatedCount = 0;
        $failedCount = 0;



        foreach ($entities as $entity) {

            if ($entity->city_id == 1) continue;

            $address = $entity->city->name;

            if ($entity->address) {
                $address = $entity->city->name . ', ' . $entity->address;
            }
            try {
                $coordinates = $this->getCoordinates($address);
                if ($coordinates) {
                    $entity->update([
                        'lat' => $coordinates['lat'],
                        'lon' => $coordinates['lon']
                    ]);
                    $updatedCount++;
                } else {
                    $this->error("Failed to get coordinates for entity: {$entity->id}");
                    $failedCount++;
                }
            } catch (Exception $e) {
                $this->error("Error processing entity ID {$entity->id}: " . $e->getMessage());
                $failedCount++;
            }

            $progressBar->advance();

            // Пауза чтобы не превысить лимиты API
            sleep(rand(1, 3)); // 1 - 3 секунды
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("Update completed!");
    }

    protected function getCoordinates(string $entityName): ?array
    {
        $response = Http::get($this->apiUrl, [
            'apikey' => $this->apiKey,
            'geocode' => $entityName,
            'format' => 'json',
            'results' => 1,
            'lang' => 'ru_RU',
            'kind' => 'locality' // Ищем именно города
        ]);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['response']['GeoObjectCollection']['featureMember'][0])) {
                $geoObject = $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject'];
                $pos = $geoObject['Point']['pos'];

                list($lon, $lat) = explode(' ', $pos);

                return [
                    'lat' => $lat,
                    'lon' => $lon
                ];
            }
        }

        return null;
    }
}
