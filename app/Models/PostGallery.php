<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostGallery extends Model
{
    protected $fillable = [
        'post_id',
        'image',
        'caption',
        'order',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
