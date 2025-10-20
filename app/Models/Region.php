<?php

namespace App\Models;

use App\Models\Traits\TranscriptName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory, TranscriptName;

    protected $searchable = [
        'name',
    ];

    protected $fillable = [
        'name',
        'transcription',
        'lat',
        'lon'
    ];

    public $timestamps = false;

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
