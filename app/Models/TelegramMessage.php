<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramMessage extends Model
{
    protected $fillable = ['id', 'group_id', 'user_id', 'text', 'date'];
    public $incrementing = false;

    public function group()
    {
        return $this->belongsTo(TelegramGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(TelegramUser::class);
    }
}
