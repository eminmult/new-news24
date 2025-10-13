<?php

namespace App\Filament\Resources\Tags;

use App\Filament\Resources\Tags\Pages\ManageTags;
use App\Models\Tag;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function getModelLabel(): string
    {
        return __('tags.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('tags.resource.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('tags.resource.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('tags.resource.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('tags.fields.name'))
                    ->required(),
                TextInput::make('slug')
                    ->label(__('tags.fields.slug'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('tags.table.columns.name'))
                    ->searchable(),
                TextColumn::make('slug')
                    ->label(__('tags.table.columns.slug'))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('tags.table.columns.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('tags.table.columns.updated_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label(__('tags.table.actions.edit')),
                DeleteAction::make()
                    ->label(__('tags.table.actions.delete')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTags::route('/'),
        ];
    }
}
