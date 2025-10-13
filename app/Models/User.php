<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements FilamentUser, HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, InteractsWithMedia;

    const ROLE_ADMIN = 'admin';
    const ROLE_EDITOR = 'editor';
    const ROLE_AUTHOR = 'author';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'role',
        'is_active',
        'avatar',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Only active users can access admin panel
        return $this->is_active === true;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is editor
     */
    public function isEditor(): bool
    {
        return $this->role === self::ROLE_EDITOR;
    }

    /**
     * Check if user is author
     */
    public function isAuthor(): bool
    {
        return $this->role === self::ROLE_AUTHOR;
    }

    /**
     * Check if user has at least editor role
     */
    public function canManageContent(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_EDITOR]);
    }

    /**
     * Get all available roles
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN => __('roles.admin'),
            self::ROLE_EDITOR => __('roles.editor'),
            self::ROLE_AUTHOR => __('roles.author'),
        ];
    }

    /**
     * Get posts relationship
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    /**
     * Get route key name for URL binding
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->useDisk('public')
            ->singleFile()
            ->useFallbackUrl('/images/default-avatar.png');
    }

    /**
     * Register media conversions
     */
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        // Миниатюра 150x150 в WebP для фронта
        $this->addMediaConversion('thumb')
            ->format('webp')
            ->fit(\Spatie\Image\Enums\Fit::Crop, 150, 150)
            ->quality(90)
            ->performOnCollections('avatar')
            ->nonQueued();
    }

    /**
     * Get avatar URL attribute
     */
    public function getAvatarUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('avatar');
        return $media ? $media->getUrl() : null;
    }

    /**
     * Get avatar thumb attribute
     */
    public function getAvatarThumbAttribute(): ?string
    {
        $media = $this->getFirstMedia('avatar');
        return $media ? $media->getUrl('thumb') : asset('images/default-avatar.png');
    }

    /**
     * Scope to get only active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only inactive users
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}
