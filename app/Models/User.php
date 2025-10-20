<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\HasCity;
use App\Models\Traits\HasProjects;
use App\Models\Traits\HasRegion;
use App\Models\Traits\Search;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens,
        HasRoles,
        HasFactory,
        Notifiable,
        HasCity,
        HasRegion,
        HasProjects,
        Search;


    protected $searchable = [
        'firstname',
        'email',
        'phone'
    ];

    public function entities(): HasMany
    {
        return $this->hasMany(Entity::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    // Чаты пользователя
    public function chats()
    {
        return $this->morphToMany(Chat::class, 'participant', 'chat_participants')
            ->with(['participants'])
            ->withTimestamps();
    }

    public function chats_to_user()
    {
        return $this->morphToMany(Chat::class, 'participant', 'chat_participants')
            ->where('type', 'user_to_user')
            ->with(['participants'])
            ->withTimestamps();
    }

    public function chats_to_entity()
    {
        return $this->morphToMany(Chat::class, 'participant', 'chat_participants')
            ->where('type', 'user_to_entity')
            ->with(['participants'])
            ->withTimestamps();
    }

    public function unreadMessagesCount()
    {
        return $this->chats()
            ->withCount(['messages as unread_messages' => function ($query) {
                $query->where('user_id', '!=', $this->id)
                    ->where('is_read', false);
            }])
            ->get()
            ->sum('unread_messages');
    }

    public function updateLastActivity()
    {
        $this->last_active_at = now();
        $this->save();
    }

    public function isOnline()
    {
        return $this->last_active_at && $this->last_active_at->gt(now()->subMinutes(10));
    }

    public function whenVisited()
    {
        if ($this->isOnline()) {
            return 'онлайн';
        }

        if ($this->last_active_at && $this->last_active_at->gt(now()->subMinutes(700))) {
            return 'был(а) сегодня';
        }

        if ($this->last_active_at && $this->last_active_at->gt(now()->subMinutes(50000))) {
            return 'был(а) в течение недели';
        }

        return 'был(а) давно';
    }

    protected $fillable = [
        'firstname',
        'phone',
        'email',
        'password',
        'last_active_at',
        'activity',
        'city_id',
        'region_id',
        'phone_verified_at',
        'phone_fore_verification',
        'check_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_active_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function hasVerifiedPhone()
    {
        return !is_null($this->phone_verified_at);
    }
}
