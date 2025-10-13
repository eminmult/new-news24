<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DeletedPostsTable
{
    public static function configure(Table $table): Table
    {
        $columns = PostsTableColumns::get();

        // Добавляем колонку deleted_at после published_at
        $publishedAtIndex = null;
        foreach ($columns as $index => $column) {
            if ($column->getName() === 'published_at') {
                $publishedAtIndex = $index;
                break;
            }
        }

        if ($publishedAtIndex !== null) {
            array_splice($columns, $publishedAtIndex + 1, 0, [
                TextColumn::make('deleted_at')
                    ->label(__('posts.table.columns.deleted_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ]);
        }

        return $table
            ->columns($columns)
            ->recordUrl(fn ($record) => route('filament.admin.resources.deleted-posts.edit', ['record' => $record]))
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->iconSize('lg')
                    ->tooltip(__('posts.table.actions.edit'))
                    ->label(''),
                Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->iconSize('lg')
                    ->label('')
                    ->tooltip(__('posts.table.actions.view_on_site'))
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab()
                    ->color('info'),
                Action::make('restore')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->iconSize('lg')
                    ->tooltip(__('posts.table.actions.restore'))
                    ->label('')
                    ->requiresConfirmation()
                    ->modalHeading(__('posts.table.actions.restore_confirm'))
                    ->modalDescription(__('posts.table.actions.restore_description'))
                    ->action(function ($record) {
                        $record->restore();
                        Notification::make()
                            ->success()
                            ->title(__('posts.table.actions.restored_notification_title'))
                            ->send();
                    })
                    ->color('success'),
            ])
            ->toolbarActions([
                //
            ])
            ->defaultSort('published_at', 'desc');
    }
}
