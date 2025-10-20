<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphTo;


trait HasParent
{
    public function parent(): MorphTo
    {
        return $this->morphTo();
    }
}