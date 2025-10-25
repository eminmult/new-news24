<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes, Searchable;
    protected $fillable = [
        'title',
        'slug',
        'old_url',   // Старый URL из DLE для редиректов
        'content',
        'excerpt',   // Краткое описание
        'meta_title',
        'meta_description',
        'meta_keywords',
        'featured_image',
        'category_id',
        'author_id',
        'views',
        'read_time',
        'is_featured',
        'is_published',
        'published_at',
        'show_on_homepage',
        'show_in_slider',
        'show_in_video_section',
        'show_in_types_block',
        'show_in_important_today',
        'is_hidden',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'show_on_homepage' => 'boolean',
        'show_in_slider' => 'boolean',
        'show_in_video_section' => 'boolean',
        'show_in_types_block' => 'boolean',
        'show_in_important_today' => 'boolean',
        'is_hidden' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
            ->withPivot('order')
            ->orderByPivot('order');
    }

    public function getMainCategoryAttribute()
    {
        // Используем ->categories (без скобок) чтобы использовать eager loaded relationship
        // Это избежит N+1 проблему при генерации sitemap
        return $this->categories->first() ?? $this->category;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(PostGallery::class);
    }

    public function widgets(): HasMany
    {
        return $this->hasMany(PostWidget::class)->orderBy('order');
    }

    public function lock(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PostLock::class);
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(PostType::class, 'post_post_type');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '<=', now())
                    ->where('is_published', true);
    }

    public function scopeScheduled($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '>', now());
    }

    public function scopeUnpublished($query)
    {
        return $query->where('is_published', false);
    }

    public function getIsScheduledAttribute(): bool
    {
        return $this->published_at && $this->published_at->isFuture();
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('post-gallery')
            ->useDisk('public')
            ->useFallbackUrl('/images/placeholder.jpg');

        $this->addMediaCollection('post-content-images')
            ->useDisk('public');
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        // Маленькие превью для карточек - 450x300px (пропорции 3:2) - ОПТИМИЗИРОВАНО
        $this->addMediaConversion('thumb')
            ->format('webp')
            ->fit(\Spatie\Image\Enums\Fit::Crop, 450, 300)
            ->quality(70)
            ->performOnCollections('post-gallery', 'post-content-images')
            ->nonQueued();

        // Средние превью - 700x467px (пропорции 3:2) - ОПТИМИЗИРОВАНО
        $this->addMediaConversion('medium')
            ->format('webp')
            ->fit(\Spatie\Image\Enums\Fit::Crop, 700, 467)
            ->quality(72)
            ->performOnCollections('post-gallery', 'post-content-images')
            ->nonQueued();

        // Большие превью для слайдеров - 1200x800px (пропорции 3:2) - ОПТИМИЗИРОВАНО
        $this->addMediaConversion('large')
            ->format('webp')
            ->fit(\Spatie\Image\Enums\Fit::Crop, 1200, 800)
            ->quality(75)
            ->performOnCollections('post-gallery', 'post-content-images')
            ->nonQueued();

        // Большая версия в WebP - максимум 1000px (оригинальные пропорции) - ОПТИМИЗИРОВАНО
        $this->addMediaConversion('webp')
            ->format('webp')
            ->fit(\Spatie\Image\Enums\Fit::Max, 1000, 1000)
            ->quality(73)
            ->performOnCollections('post-gallery', 'post-content-images')
            ->nonQueued();
    }

    public function getFeaturedImageAttribute()
    {
        // Берем первое фото из галереи
        $firstMedia = $this->getFirstMedia('post-gallery');
        return $firstMedia ? $firstMedia->getFullUrl() : null;
    }

    public function getFeaturedImageThumbAttribute()
    {
        // Берем первое фото из галереи
        $firstMedia = $this->getFirstMedia('post-gallery');
        return $firstMedia ? $firstMedia->getUrl('thumb') : null;
    }

    public function getFeaturedImageMediumAttribute()
    {
        // Берем первое фото из галереи
        $firstMedia = $this->getFirstMedia('post-gallery');
        return $firstMedia ? $firstMedia->getUrl('medium') : null;
    }

    public function getFeaturedImageLargeAttribute()
    {
        // Берем первое фото из галереи
        $firstMedia = $this->getFirstMedia('post-gallery');
        if (!$firstMedia) {
            return null;
        }

        // Проверяем существует ли large конверсия
        try {
            $largePath = $firstMedia->getPath('large');
            if (file_exists($largePath)) {
                return $firstMedia->getUrl('large');
            }
        } catch (\Exception $e) {
            // Конверсия large не существует
        }

        // Fallback на medium если large не существует
        return $firstMedia->getUrl('medium');
    }

    public function getFeaturedImageWebpAttribute()
    {
        // Берем первое фото из галереи
        $firstMedia = $this->getFirstMedia('post-gallery');
        return $firstMedia ? $firstMedia->getUrl('webp') : null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    public function getMetaDescriptionAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Получаем текст из content, удаляя HTML теги
        $text = strip_tags($this->content);

        // Умное обрезание до 200 символов по границе предложения
        if (mb_strlen($text) <= 200) {
            return $text;
        }

        // Ищем конец предложения в пределах 200 символов
        $excerpt = mb_substr($text, 0, 200);
        $lastPeriod = mb_strrpos($excerpt, '.');
        $lastExclamation = mb_strrpos($excerpt, '!');
        $lastQuestion = mb_strrpos($excerpt, '?');

        $lastSentenceEnd = max($lastPeriod, $lastExclamation, $lastQuestion);

        if ($lastSentenceEnd !== false && $lastSentenceEnd > 100) {
            return mb_substr($text, 0, $lastSentenceEnd + 1);
        }

        // Если не нашли конец предложения, обрезаем по последнему пробелу
        $lastSpace = mb_strrpos($excerpt, ' ');
        if ($lastSpace !== false) {
            return mb_substr($text, 0, $lastSpace) . '...';
        }

        return $excerpt . '...';
    }

    public function getMetaKeywordsAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $description = $this->getMetaDescriptionAttribute(null);

        // Удаляем знаки препинания и приводим к нижнему регистру
        $text = mb_strtolower($description);
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);

        // Разбиваем на слова
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        // Фильтруем короткие слова (меньше 4 символов)
        $words = array_filter($words, function($word) {
            return mb_strlen($word) >= 4;
        });

        // Подсчитываем частоту слов
        $wordCount = array_count_values($words);

        // Фильтруем слова, которые встречаются больше одного раза
        $repeatedWords = array_filter($wordCount, function($count) {
            return $count > 1;
        });

        // Сортируем по частоте
        arsort($repeatedWords);

        // Берем топ слова
        $keywords = array_keys($repeatedWords);

        // Если мало повторяющихся слов, добавляем самые длинные уникальные
        if (count($keywords) < 5) {
            $uniqueWords = array_diff($words, $keywords);
            usort($uniqueWords, function($a, $b) {
                return mb_strlen($b) - mb_strlen($a);
            });
            $keywords = array_merge($keywords, array_slice($uniqueWords, 0, 5 - count($keywords)));
        }

        // Возвращаем минимум 5 слов
        return implode(', ', array_slice($keywords, 0, max(5, count($keywords))));
    }

    public function getUrlAttribute()
    {
        $category = $this->main_category;
        $frontendUrl = config('app.frontend_url', config('app.url'));

        if ($category) {
            return $frontendUrl . '/' . $category->slug . '/' . $this->slug;
        }
        // Fallback если нет категории
        return $frontendUrl;
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        // Загружаем связи если не загружены
        $this->loadMissing(['category', 'categories', 'tags', 'author']);

        $normalizer = \App\Services\AzerbaijaniSearchNormalizer::class;

        return [
            'id' => $this->id,

            // Оригинальные данные
            'title' => $this->title,
            'content' => strip_tags($this->content),
            'excerpt' => $this->excerpt,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'meta_title' => $this->meta_title,

            // Латинизированные версии для лучшего поиска (ə → a)
            'title_latin' => $normalizer::toLatinVariant($this->title),
            'content_latin' => $normalizer::toLatinVariant(strip_tags($this->content)),
            'excerpt_latin' => $normalizer::toLatinVariant($this->excerpt ?? ''),
            'meta_description_latin' => $normalizer::toLatinVariant($this->meta_description ?? ''),

            // Альтернативные латинизированные версии (ə → e) для тех кто пишет "e"
            'title_latin_e' => $normalizer::toLatinVariantWithE($this->title),
            'content_latin_e' => $normalizer::toLatinVariantWithE(strip_tags($this->content)),

            // Категории для поиска и фильтрации
            'category_id' => $this->category_id,
            'category_name' => $this->category?->name,
            'category_name_latin' => $normalizer::toLatinVariant($this->category?->name ?? ''),
            'category_slug' => $this->category?->slug,
            'categories_names' => $this->categories->pluck('name')->join(', '),
            'categories_names_latin' => $normalizer::toLatinVariant($this->categories->pluck('name')->join(', ')),

            // Теги
            'tags' => $this->tags->pluck('name')->join(', '),
            'tags_latin' => $normalizer::toLatinVariant($this->tags->pluck('name')->join(', ')),

            // Автор
            'author_name' => $this->author?->name,
            'author_name_latin' => $normalizer::toLatinVariant($this->author?->name ?? ''),

            // Статус и метрики
            'status' => $this->is_published ? 'published' : 'draft',
            'published_at' => $this->published_at?->timestamp,
            'views' => $this->views,
            'is_featured' => $this->is_featured,
        ];
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        // Индексируем только опубликованные посты
        return $this->is_published && $this->published_at && $this->published_at->isPast();
    }

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'posts';
    }

}
