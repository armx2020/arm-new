<?php

namespace App\Models;

use App\Models\Traits\HasCity;
use App\Models\Traits\HasRegion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteMap extends Model
{
    use HasFactory,
        HasCity,
        HasRegion;

    protected $fillable = [
        'url',
        'site_map_type_id',
        'name',
        'title',
        'description',
        'quantity_entity',
        'region_id',
        'city_id',
        'entity_type_id',
        'category_id',
        'entity_id',
        'index'
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(SiteMapType::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }
}
