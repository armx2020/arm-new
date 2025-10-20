<?php

namespace App\Console\Commands;

use App\Models\SiteMap;
use App\Services\SiteMapService;
use Illuminate\Console\Command;

class CreateSiteMap extends Command
{
    protected $signature = 'app:create-site-map {--create} {--truncate}';

    protected $description = 'создать карту сайта {--создать таблицу в бд} {--очистить таблицу в бд}';

    public function handle(SiteMapService $siteMapService)
    {
        if($this->option('truncate')) {
            SiteMap::truncate();
        }

        if($this->option('create')) {
            $siteMapService->create();
        }

        $siteMapService->addFile();
    }
}
