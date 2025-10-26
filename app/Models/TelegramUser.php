<?php

namespace App\Models;

use App\Models\Traits\Search;
use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    use Search;

    protected $fillable = ['id', 'first_name', 'last_name', 'username'];
    public $incrementing = false;

    protected $searchable = ['first_name', 'last_name', 'username'];
}
