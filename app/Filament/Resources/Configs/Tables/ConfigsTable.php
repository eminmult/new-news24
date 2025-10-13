<?php

namespace App\Filament\Resources\Configs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConfigsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label(__('configs.table.columns.key'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('label')
                    ->label(__('configs.table.columns.label'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('value')
                    ->label(__('configs.table.columns.value'))
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('type')
                    ->label(__('configs.table.columns.type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'url' => 'info',
                        'email' => 'warning',
                        'phone' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('updated_at')
                    ->label(__('configs.table.columns.updated_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
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
            ])
            ->defaultSort('key');
    }
}
