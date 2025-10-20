<?php

namespace App\Models\Traits;

use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasProjects
{
   public function projects(): MorphMany
    {
        return $this->morphMany(Project::class, 'parent');
    }
}