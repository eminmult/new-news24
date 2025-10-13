<?php

namespace App\Filament\Resources\PostTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PostTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('icon')
                    ->label(__('post-types.table.columns.icon'))
                    ->searchable(),
                TextColumn::make('name')
                    ->label(__('post-types.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('post-types.table.columns.slug'))
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('color')
                    ->label(__('post-types.table.columns.color'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state)
                    ->color(fn (string $state): string => match(true) {
                        str_starts_with($state, '#') => 'gray',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->label(__('post-types.table.columns.is_active'))
                    ->boolean(),
                TextColumn::make('order')
                    ->label(__('post-types.table.columns.order'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('posts_count')
                    ->label(__('post-types.table.columns.posts_count'))
                    ->counts('posts')
                    ->sortable(),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
