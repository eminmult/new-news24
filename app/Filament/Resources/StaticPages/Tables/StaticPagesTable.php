<?php

namespace App\Filament\Resources\StaticPages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StaticPagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('static-pages.table.columns.title'))
                    ->searchable(),
                TextColumn::make('slug')
                    ->label(__('static-pages.table.columns.slug'))
                    ->badge()
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label(__('static-pages.table.columns.is_active'))
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label(__('static-pages.table.columns.updated_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make()
                    ->label(__('static-pages.table.actions.edit')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
