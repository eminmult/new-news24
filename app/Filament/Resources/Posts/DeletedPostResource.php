<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages\DeletedPosts\ListDeletedPosts;
use App\Filament\Resources\Posts\Schemas\PostForm;
use App\Filament\Resources\Posts\Tables\DeletedPostsTable;
use App\Models\Post;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DeletedPostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrash;

    protected static ?string $slug = 'deleted-posts';

    public static function getModelLabel(): string
    {
        return __('posts.deleted_resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('posts.deleted_resource.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('posts.deleted_resource.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('posts.deleted_resource.navigation_group');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->onlyTrashed();
    }

    public static function form(Schema $schema): Schema
    {
        return PostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeletedPostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeletedPosts::route('/'),
            'edit' => \App\Filament\Resources\Posts\Pages\DeletedPosts\EditDeletedPost::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
