<?php

namespace App\Filament\Resources\Configs;

use App\Filament\Resources\Configs\Pages\CreateConfig;
use App\Filament\Resources\Configs\Pages\EditConfig;
use App\Filament\Resources\Configs\Pages\ListConfigs;
use App\Filament\Resources\Configs\Schemas\ConfigForm;
use App\Filament\Resources\Configs\Tables\ConfigsTable;
use App\Models\Config;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConfigResource extends Resource
{
    protected static ?string $model = Config::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 100;

    public static function getModelLabel(): string
    {
        return __('configs.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('configs.resource.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('configs.resource.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('configs.resource.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return ConfigForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConfigsTable::configure($table);
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
            'index' => ListConfigs::route('/'),
            'create' => CreateConfig::route('/create'),
            'edit' => EditConfig::route('/{record}/edit'),
        ];
    }
}
