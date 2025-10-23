<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\StorageHelper;

Route::get('/test-s3', function () {
    $testImages = [
        'uploaded/00SymyHs1Wznie8otllRsASIXZiqwXOQYYiDrHay.jpg',
        'uploaded/05ZhMW28lpselRhOfH19e2xtTvqK6txpWHo4IFrj.jpg',
        'users/2fliO3UOjJnpeV6ohwFnePNB6nPZaF5oz9opt6He.png',
    ];
    
    $results = [];
    foreach ($testImages as $image) {
        $url = StorageHelper::imageUrl($image);
        $results[] = [
            'path' => $image,
            'url' => $url,
        ];
    }
    
    return view('test-s3-simple', ['results' => $results]);
});
