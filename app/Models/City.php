<?php

namespace App\Models;

use App\Models\Traits\TranscriptName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory, TranscriptName;

    protected $searchable = [
        'name',
    ];

    protected $fillable = [
        'name',
        'name_ru',
        'name_en',
        'transcription',
        'lat',
        'lon'
    ];

    public $timestamps = false;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function getCoordinatesAttribute()
    {
        if ($this->lat && $this->lon) {
            return [$this->lat, $this->lon];
        }

        return null;
    }
}
