<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with(['media', 'categories', 'category', 'lock.user']);
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('posts.tabs.all')),
            'published' => Tab::make(__('posts.tabs.published'))
                ->modifyQueryUsing(fn (Builder $query) => $query->published()),
            'scheduled' => Tab::make(__('posts.tabs.scheduled'))
                ->modifyQueryUsing(fn (Builder $query) => $query->scheduled()),
            'unpublished' => Tab::make(__('posts.tabs.unpublished'))
                ->modifyQueryUsing(fn (Builder $query) => $query->unpublished()),
        ];
    }
}
