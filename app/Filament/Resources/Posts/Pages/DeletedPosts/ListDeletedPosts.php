<?php

namespace App\Filament\Resources\Posts\Pages\DeletedPosts;

use App\Filament\Resources\Posts\DeletedPostResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListDeletedPosts extends ListRecords
{
    protected static string $resource = DeletedPostResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with(['media', 'categories', 'category', 'lock.user']);
    }
}
