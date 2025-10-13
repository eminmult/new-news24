<?php

namespace App\Filament\Resources\Posts\Pages\DeletedPosts;

use App\Filament\Resources\Posts\DeletedPostResource;
use App\Models\PostLock;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditDeletedPost extends EditRecord
{
    protected static string $resource = DeletedPostResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            Action::make('cancel')
                ->label(__('posts.table.actions.cancel'))
                ->color('gray')
                ->action(function () {
                    // Снимаем блокировку перед уходом
                    PostLock::where('post_id', $this->record->id)
                        ->where('user_id', Auth::id())
                        ->delete();

                    return redirect()->route('filament.admin.resources.posts.deleted-posts.index');
                }),
        ];
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);

        // Очищаем устаревшие блокировки
        PostLock::cleanupStale();

        // Проверяем, не заблокирован ли пост другим пользователем
        $existingLock = PostLock::where('post_id', $this->record->id)->first();

        if ($existingLock && $existingLock->user_id !== Auth::id()) {
            if ($existingLock->isActive()) {
                Notification::make()
                    ->danger()
                    ->title(__('posts.table.actions.post_being_edited'))
                    ->body(__('posts.table.actions.post_being_edited_by', ['user' => $existingLock->user->name]))
                    ->persistent()
                    ->send();

                redirect()->route('filament.admin.resources.posts.deleted-posts.index');
                return;
            } else {
                // Блокировка устарела, удаляем
                $existingLock->delete();
            }
        }

        // Создаем или обновляем блокировку для текущего пользователя
        PostLock::updateOrCreate(
            ['post_id' => $this->record->id],
            [
                'user_id' => Auth::id(),
                'locked_at' => now(),
                'last_heartbeat' => now(),
            ]
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('restore')
                ->label(__('posts.table.actions.restore'))
                ->icon('heroicon-o-arrow-uturn-left')
                ->requiresConfirmation()
                ->modalHeading(__('posts.table.actions.restore_confirm'))
                ->modalDescription(__('posts.table.actions.restore_description'))
                ->action(function () {
                    $this->record->restore();
                    Notification::make()
                        ->success()
                        ->title(__('posts.table.actions.restored_notification_title'))
                        ->send();
                    return redirect()->route('filament.admin.resources.posts.index');
                })
                ->color('success'),
        ];
    }

    protected function afterSave(): void
    {
        // Снимаем блокировку после сохранения
        PostLock::where('post_id', $this->record->id)
            ->where('user_id', Auth::id())
            ->delete();
    }

    public function beforeLeave(): void
    {
        // Снимаем блокировку при выходе со страницы
        PostLock::where('post_id', $this->record->id)
            ->where('user_id', Auth::id())
            ->delete();
    }

    protected function getViewData(): array
    {
        return array_merge(parent::getViewData(), [
            'postId' => $this->record->id,
        ]);
    }
}
