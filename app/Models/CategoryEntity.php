<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryEntity extends Model
{
    use HasFactory;
    
    protected $table = 'category_entity';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function mainCategory()
    {
        return $this->belongsTo(Category::class);
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }
}
