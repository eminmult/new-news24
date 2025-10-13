<?php

namespace App\Filament\Resources\PostTypes\Pages;

use App\Filament\Resources\PostTypes\PostTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePostType extends CreateRecord
{
    protected static string $resource = PostTypeResource::class;
}
