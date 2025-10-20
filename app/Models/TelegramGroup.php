<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramGroup extends Model
{
    protected $fillable = [
        'id',
        'username',
        'title',
        'description'
    ];

    public $incrementing = false;

    public function telegram_messages()
    {
        return $this->hasMany(TelegramMessage::class, 'group_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('activity', 1);
    }
}
