<?php

namespace App\Models;

use App\Models\Scopes\CheckedScope;
use App\Models\Traits\HasCity;
use App\Models\Traits\HasProjects;
use App\Models\Traits\HasRegion;
use App\Models\Traits\HasUser;
use App\Models\Traits\Search;
use App\Models\Traits\TranscriptName;
use App\Observers\EntityObserver;
use App\Rules\InstagramUrl;
use App\Rules\TelegramUrl;
use App\Rules\VkontakteUrl;
use App\Rules\WebUrl;
use App\Rules\WhatsappUrl;
use App\Rules\VideoUrl;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Storage;

#[ObservedBy([EntityObserver::class])]
class Entity extends Model
{
    use HasFactory,
        HasCity,
        HasRegion,
        HasProjects,
        HasUser,
        TranscriptName,
        Search;

    protected $fillable = [
        'name',
        'transcription',
        'entity_type_id',
        'activity',
        'address',
        'link',
        'email',
        'director',
        'description',
        'phone',
        'web',
        'whatsapp',
        'instagram',
        'vkontakte',
        'telegram',
        'user_id',
        'city_id',
        'region_id',
        'category_id',
        'sort_id',
        'comment',
        'started_at',
        'checked',
        'clinic',
        'paymant_link',
        'director',
        'moderator_id',
        'lat',
        'lon'
    ];

    protected $searchable = [
        'name',
        'phone',
        'description'
    ];

    public function scopeCompanies($query)
    {
        return $query->where('entity_type_id', 1);
    }

    public function scopeGroups($query)
    {
        return $query->where('entity_type_id', 2);
    }

    public function scopePlaces($query)
    {
        return $query->where('entity_type_id', 3);
    }

    public function scopeCommunities($query)
    {
        return $query->where('entity_type_id', 4);
    }

    public function scopeJobs($query)
    {
        return $query->where('entity_type_id', 7);
    }

    public function scopeNearby($query, $lat, $lon, $radius = 5000)
    {
        return \App\Helpers\GeoHelper::nearbyEntities($query, $lat, $lon, $radius);
    }

    public function scopeActive($query)
    {
        return $query->where('activity', 1);
    }

    // Отношения
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(EntityType::class, 'entity_type_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function fields(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function main_field(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_entity', 'entity_id', 'main_entity_id')->withTimestamps();
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }


    public function appeals(): HasMany
    {
        return $this->hasMany(Appeal::class);
    }

    // Чаты пользователя
    public function chats()
    {
        return $this->morphToMany(Chat::class, 'participant', 'chat_participants')
            ->where('type', 'user_to_entity')
            ->with(['participants'])
            ->withTimestamps();
    }

    // Изображения
    public function images($isWithScope = true): MorphMany
    {
        if ($isWithScope) {
            return $this->morphMany(Image::class, 'imageable')->where('is_logo', 0);
        } else {
            return $this->images()->withoutGlobalScope(CheckedScope::class)->where('is_logo', 0);
        }
    }

    public function primaryImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->orderBy('id')->where('is_logo', 0);
    }

    public function primaryImageView(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->orderByDesc('id')->where('checked', 1)->where('is_logo', 0);
    }

    // Логотип
    public function logo()
    {
        return $this->morphOne(Image::class, 'imageable')->where('is_logo', 1);
    }

    public function deleteLogo(): bool
    {
        if ($this->logo) {
            Storage::delete('public/' . $this->logo->path);
            return $this->logo->delete();
        }

        return false;
    }

    // Мутаторы
    public function setVideoUrlAttribute($value)
    {
        $this->attributes['video_url'] = VideoUrl::normalize($value);
    }

    public function setWhatsappAttribute($value)
    {
        $this->attributes['whatsapp'] = WhatsappUrl::normalize($value);
    }

    public function setWebAttribute($value)
    {
        $this->attributes['web'] = WebUrl::normalize($value);
    }

    public function setVkontakteAttribute($value)
    {
        $this->attributes['vkontakte'] = VkontakteUrl::normalize($value);
    }

    public function setInstagramAttribute($value)
    {
        $this->attributes['instagram'] = InstagramUrl::normalize($value);
    }

    public function setTelegramAttribute($value)
    {
        $this->attributes['telegram'] = TelegramUrl::normalize($value);
    }

    public function setLatAttribute($value)
    {
        $this->attributes['lat'] =  $value != 0 ? round((float)$value, 6) : null;
    }

    public function setLonAttribute($value)
    {
        $this->attributes['lon'] = $value != 0 ? round((float)$value, 6) : null;
    }

    // Аксессоры
    public function getWhatsappLinkAttribute($value)
    {
        return $value ? WhatsappUrl::normalize($value) : null;
    }

    public function getWebAttribute($value)
    {
        return $value ? WebUrl::normalize($value) : null;
    }

    public function getVkontakteAttribute($value)
    {
        return $value ? VkontakteUrl::normalize($value) : null;
    }

    public function getInstagramUrlAttribute($value)
    {
        return $value ? InstagramUrl::normalize($value) : null;
    }

    public function getTelegramAttribute($value)
    {
        return $value ? TelegramUrl::normalize($value) : null;
    }

    public function getCoordinatesAttribute()
    {
        if ($this->lat && $this->lon) {
            return [$this->lat, $this->lon];
        }

        return null;
    }

    public function getSimilarEntities($limit = 3)
    {
        return self::query()
            ->where('entity_type_id', $this->entity_type_id)
            ->where('category_id', $this->category_id)
            ->where('activity', 1)
            ->where('id', '!=', $this->id)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
