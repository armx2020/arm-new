<?php

namespace App\Models;

use App\Models\Traits\HasCity;
use App\Models\Traits\HasProjects;
use App\Models\Traits\HasRegion;
use App\Models\Traits\HasUser;
use App\Models\Traits\Search;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Offer extends Model
{
    use HasFactory,
        HasCity,
        HasRegion,
        HasProjects,
        HasUser,
        Search;

    protected $fillable = [
        'name',
        'activity',
        'address',
        'image',
        'description',
        'user_id',
        'city_id',
        'region_id',
        'category_id'
    ];

    protected $searchable = [
        'name',
        'description'
    ];

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function primaryImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->where('sort_id', 0);
    }

    public function primaryImageView(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->orderByDesc('id')->where('checked', 1);
    }
}
