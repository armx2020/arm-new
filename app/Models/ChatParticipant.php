<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id', 'participant_type', 'participant_id'
    ];

    public function participant()
    {
        return $this->morphTo();
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
