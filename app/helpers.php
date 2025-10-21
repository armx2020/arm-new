<?php

use App\Helpers\StorageHelper;

if (!function_exists('storage_url')) {
    function storage_url(?string $path): string
    {
        return StorageHelper::imageUrl($path);
    }
}
