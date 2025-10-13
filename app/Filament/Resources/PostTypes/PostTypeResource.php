<?php

namespace App\Filament\Resources\PostTypes;

use App\Filament\Resources\PostTypes\Pages\CreatePostType;
use App\Filament\Resources\PostTypes\Pages\EditPostType;
use App\Filament\Resources\PostTypes\Pages\ListPostTypes;
use App\Filament\Resources\PostTypes\Schemas\PostTypeForm;
use App\Filament\Resources\PostTypes\Tables\PostTypesTable;
use App\Models\PostType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PostTypeResource extends Resource
{
    protected static ?string $model = PostType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?int $navigationSort = 4;

    protected static bool $shouldRegisterNavigation = true;

    public static function getModelLabel(): string
    {
        return __('post-types.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('post-types.resource.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('post-types.resource.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('post-types.resource.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return PostTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostTypesTable::configure($table);
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
            'index' => ListPostTypes::route('/'),
            'create' => CreatePostType::route('/create'),
            'edit' => EditPostType::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
