<?php

namespace App\Models;

use App\Models\Traits\HasCity;
use App\Models\Traits\HasRegion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use HasFactory,
        HasCity,
        HasRegion;

    protected $fillable = [
        'url',
        'site_type_id',
        'title',
        'description',
        'quantity_entity',
        'index'
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(SiteType::class, 'site_type_id');
    }
}
