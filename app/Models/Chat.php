<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_message_at',
        'last_message',
        'type'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function participants()
    {
        return $this->hasMany(ChatParticipant::class);
    }

    public function participantsWithData()
    {
        return $this->hasMany(ChatParticipant::class)
            ->with('participant'); // Загружаем связанные модели
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Пользователи чата
    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_participants')
            ->using(ChatParticipant::class)
            ->withTimestamps();
    }

    // Сущности чата
    public function entities()
    {
        return $this->morphedByMany(Entity::class, 'participant', 'chat_participants')
            ->using(ChatParticipant::class);
    }

    public function otherParticipant()
    {
        return $this->participants
            ->where('participant_id', '!=', auth()->id())
            ->pluck('participant')
            ->First();
    }

    public function otherUserParticipant()
    {
        return $this->participants
            ->where('participant_type', 'App\Models\User')
            ->where('participant_id', '!=', auth()->id())
            ->pluck('participant')
            ->First();
    }

    public function otherParticipantRelatenshion()
    {
        return $this->participants
            ->where('participant_id', '!=', auth()->id())
            ->pluck('participant');
    }

    public function unreadMessagesCountForAuthUser()
    {
        return $this->messages()
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->count();
    }
}
