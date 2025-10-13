<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(PostsTableColumns::get())
            ->defaultSort('published_at', 'desc')
            ->recordUrl(fn ($record) => route('filament.admin.resources.posts.edit', ['record' => $record]))
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
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->iconSize('lg')
                    ->tooltip(__('posts.table.actions.delete'))
                    ->label('')
                    ->requiresConfirmation()
                    ->modalHeading(__('posts.table.actions.delete_confirm'))
                    ->modalDescription(__('posts.table.actions.delete_description'))
                    ->action(function ($record) {
                        $record->delete();
                        Notification::make()
                            ->success()
                            ->title(__('posts.table.actions.deleted_notification_title'))
                            ->body(__('posts.table.actions.deleted_notification_body'))
                            ->send();
                    })
                    ->color('danger'),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
