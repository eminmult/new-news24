<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\ManageCategories;
use App\Models\Category;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    public static function getModelLabel(): string
    {
        return __('categories.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('categories.resource.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('categories.resource.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('categories.resource.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('categories.fields.name'))
                    ->required(),
                TextInput::make('slug')
                    ->label(__('categories.fields.slug'))
                    ->required(),
                TextInput::make('color')
                    ->label(__('categories.fields.color'))
                    ->required()
                    ->default('#fc0067'),
                Textarea::make('description')
                    ->label(__('categories.fields.description'))
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label(__('categories.fields.is_active'))
                    ->required(),
                Toggle::make('show_in_menu')
                    ->label(__('categories.fields.show_in_menu'))
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('categories.table.columns.name'))
                    ->searchable(),
                TextColumn::make('slug')
                    ->label(__('categories.table.columns.slug'))
                    ->searchable(),
                TextColumn::make('color')
                    ->label(__('categories.table.columns.color'))
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label(__('categories.table.columns.is_active'))
                    ->boolean(),
                IconColumn::make('show_in_menu')
                    ->label(__('categories.table.columns.show_in_menu'))
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(__('categories.table.columns.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('categories.table.columns.updated_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label(__('categories.table.actions.edit')),
                DeleteAction::make()
                    ->label(__('categories.table.actions.delete')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order')
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCategories::route('/'),
        ];
    }
}
