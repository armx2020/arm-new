<?php

namespace App\Console\Commands;

use App\Models\Region;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class UpdateRegionsCoordinates extends Command
{
    protected $signature = 'regions:update-coordinates {--limit= : Limit the number of entities to process}';

    protected $description = 'Обновление геогрофических координат регионов с помощью Yandex Geocoder API';

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

        Log::info('start-regions');
        $regions = Region::query()->limit($this->limit)
            ->whereNull('lat')
            ->orWhereNull('lon')
            ->get();
        $totalCities = $regions->count();

        $this->info("Starting to update coordinates for {$totalCities} regions...");

        if ($totalCities === 0) {
            $this->info('No cities to update.');
            return;
        }

        $progressBar = $this->output->createProgressBar($totalCities);
        $progressBar->start();

        $updatedCount = 0;
        $failedCount = 0;

        foreach ($regions as $region) {
            try {
                $coordinates = $this->getCoordinates($region->name);
                if ($coordinates) {
                    $region->update([
                        'lat' => $coordinates['lat'],
                        'lon' => $coordinates['lon']
                    ]);
                    $updatedCount++;
                } else {
                    $this->error("Failed to get coordinates for region: {$region->name}");
                    $failedCount++;
                }
            } catch (Exception $e) {
                $this->error("Error processing region ID {$region->id}: " . $e->getMessage());
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

    protected function getCoordinates(string $regionName): ?array
    {
        $response = Http::get($this->apiUrl, [
            'apikey' => $this->apiKey,
            'geocode' => $regionName,
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
