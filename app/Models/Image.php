<?php

namespace App\Models;

use App\Models\Scopes\CheckedScope;
use App\Models\Scopes\SortAscScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;

#[ScopedBy([SortAscScope::class, CheckedScope::class])]

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path', 'sort_id', 'checked', 'is_logo'
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeMain($query)
    {
        return $query->where('sort_id', 0);
    }

    public function scopeLogo($query)
    {
        return $query->where('is_logo', 1);
    }

}
