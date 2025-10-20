<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class UpdateCountriesCoordinates extends Command
{
    protected $signature = 'countries:update-coordinates {--limit= : Limit the number of entities to process}';

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

        Log::info('start-countries');
        $countries = Country::query()->limit($this->limit)
            ->whereNull('lat')
            ->orWhereNull('lon')
            ->get();
        $totalCities = $countries->count();

        $this->info("Starting to update coordinates for {$totalCities} cities...");

        if ($totalCities === 0) {
            $this->info('No cities to update.');
            return;
        }

        $progressBar = $this->output->createProgressBar($totalCities);
        $progressBar->start();

        $updatedCount = 0;
        $failedCount = 0;

        foreach ($countries as $country) {
            try {
                $coordinates = $this->getCoordinates($country->name_ru);
                if ($coordinates) {
                    $country->update([
                        'lat' => $coordinates['lat'],
                        'lon' => $coordinates['lon']
                    ]);
                    $updatedCount++;
                } else {
                    $this->error("Failed to get coordinates for country: {$country->name_ru}");
                    $failedCount++;
                }
            } catch (Exception $e) {
                $this->error("Error processing country ID {$country->id}: " . $e->getMessage());
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

    protected function getCoordinates(string $countryName): ?array
    {
        $response = Http::get($this->apiUrl, [
            'apikey' => $this->apiKey,
            'geocode' => $countryName,
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
