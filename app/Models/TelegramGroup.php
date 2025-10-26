<?php

namespace App\Models;

use App\Models\Traits\Search;
use Illuminate\Database\Eloquent\Model;

class TelegramGroup extends Model
{
    use Search;

    protected $fillable = [
        'id',
        'username',
        'title',
        'description'
    ];

    public $incrementing = false;

    protected $searchable = ['title', 'username', 'description'];

    public function telegram_messages()
    {
        return $this->hasMany(TelegramMessage::class, 'group_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('activity', 1);
    }
}
