<?php

namespace App\Models;

use App\Models\Traits\HasUser;
use App\Models\Traits\Search;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Appeal extends Model
{
    use HasFactory, HasUser, Search;

    protected $searchable = [
        'name', 'phone', 'message'
    ];

    protected $fillable = [
        'id',
        'name',
        'phone',
        'message',
        'activity',
        'entity_id',
        'user_id'
    ];

    public function scopeActive($query)
    {
        return $query->where('activity', 1);
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
