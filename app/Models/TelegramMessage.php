<?php

namespace App\Models;

use App\Models\Traits\Search;
use Illuminate\Database\Eloquent\Model;

class TelegramMessage extends Model
{
    use Search;

    protected $fillable = ['id', 'group_id', 'user_id', 'text', 'date'];
    public $incrementing = false;

    protected $searchable = ['text'];

    public function group()
    {
        return $this->belongsTo(TelegramGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(TelegramUser::class);
    }
}
