<?php

namespace App\Models;

use App\Models\Scopes\SortDescScope;
use App\Models\Traits\Search;
use App\Models\Traits\TranscriptName;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy([SortDescScope::class])]
class Category extends Model
{
    use HasFactory, Search, TranscriptName;

    protected $searchable = [
        'name'
    ];

    protected $fillable = [
        'id',
        'name',
        'activity',
        'transcription',
        'entity_type_id',
        'category_id',
        'sort_id'
    ];

    public function scopeMain($query)
    {
        return $query->where('category_id', null);
    }

    public function scopeActive($query)
    {
        return $query->where('activity', 1);
    }

    public function scopeCompanies($query)
    {
        return $query->where('entity_type_id', 1);
    }

    public function scopeGroups($query)
    {
        return $query->where('entity_type_id', 2);
    }

    public function scopePlaces($query)
    {
        return $query->where('entity_type_id', 3);
    }

    public function scopeCommunities($query)
    {
        return $query->where('entity_type_id', 4);
    }

    public function scopeJobs($query)
    {
        return $query->where('entity_type_id', 7);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function childrenCategories()
    {
        return $this->hasMany(Category::class)->with('categories');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(EntityType::class, 'entity_type_id');
    }
}
