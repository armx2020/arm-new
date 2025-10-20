<?php

namespace App\Models\Traits;

use App\Models\City;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


trait HasCity
{
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}