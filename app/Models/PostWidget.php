<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostWidget extends Model
{
    protected $fillable = [
        'post_id',
        'type',
        'content',
        'order',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Extract YouTube video ID from URL or return as is if already an ID
     */
    public static function extractYoutubeId($input): string
    {
        // If it's already an ID (11 chars, alphanumeric with - and _)
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
            return $input;
        }

        // Extract from various YouTube URL formats
        $patterns = [
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/v\/([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input, $matches)) {
                return $matches[1];
            }
        }

        return $input; // Return as is if no pattern matches
    }

    /**
     * Extract OK.ru video ID from URL or return as is if already an ID
     */
    public static function extractOkruId($input): string
    {
        // If it's already an ID (numeric)
        if (preg_match('/^\d+$/', $input)) {
            return $input;
        }

        // Extract from OK.ru URL formats
        $patterns = [
            '/ok\.ru\/video\/(\d+)/',
            '/ok\.ru\/videoembed\/(\d+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input, $matches)) {
                return $matches[1];
            }
        }

        return $input; // Return as is if no pattern matches
    }

    /**
     * Extract Instagram post ID from URL or return as is if already an ID
     */
    public static function extractInstagramId($input): string
    {
        // If it's already an ID (alphanumeric, typically 11 chars)
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
            return $input;
        }

        // Extract from Instagram URL formats
        // https://www.instagram.com/p/POST_ID/
        // https://instagram.com/p/POST_ID/
        // https://www.instagram.com/reel/POST_ID/
        $patterns = [
            '/instagram\.com\/p\/([a-zA-Z0-9_-]+)/',
            '/instagram\.com\/reel\/([a-zA-Z0-9_-]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input, $matches)) {
                return $matches[1];
            }
        }

        return $input; // Return as is if no pattern matches
    }

    /**
     * Set content attribute with smart extraction
     */
    public function setContentAttribute($value)
    {
        if ($this->type === 'youtube') {
            $this->attributes['content'] = self::extractYoutubeId($value);
        } elseif ($this->type === 'okru') {
            $this->attributes['content'] = self::extractOkruId($value);
        } else {
            $this->attributes['content'] = $value;
        }
    }
}
