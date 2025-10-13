<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaObserver
{
    /**
     * Handle the Media "created" event (файл загружен).
     */
    public function created(Media $media): void
    {
        // Отслеживаем только аватарки пользователей
        if ($media->collection_name === 'avatar' && $media->model_type === User::class) {
            $this->logAvatarChange($media, 'added');
        }
    }

    /**
     * Handle the Media "deleted" event (файл удален).
     */
    public function deleted(Media $media): void
    {
        // Отслеживаем только аватарки пользователей
        if ($media->collection_name === 'avatar' && $media->model_type === User::class) {
            $this->logAvatarChange($media, 'removed');
        }
    }

    /**
     * Логируем изменение аватарки
     */
    protected function logAvatarChange(Media $media, string $action): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $targetUser = User::find($media->model_id);
        if (!$targetUser) {
            return;
        }

        // Проверяем, был ли только что создан лог для этого пользователя (в течение последних 5 секунд)
        $recentLog = ActivityLog::where('subject_type', User::class)
            ->where('subject_id', $targetUser->id)
            ->where('event', 'updated')
            ->where('causer_id', $user->id)
            ->where('created_at', '>=', now()->subSeconds(5))
            ->orderBy('created_at', 'desc')
            ->first();

        $avatarData = [
            'file_name' => $media->file_name,
            'url' => $media->getUrl(),
        ];

        if ($recentLog) {
            // Обновляем существующий лог
            $properties = $recentLog->properties ?? [];

            if ($action === 'removed') {
                $properties['old']['avatar'] = $avatarData;
            } else {
                $properties['new']['avatar'] = $avatarData;
            }

            // Обновляем описание
            $description = $recentLog->description;
            $includingText = __('activity-logs.descriptions.including_avatar');
            if (strpos($description, $includingText) === false) {
                $description .= $includingText;
            }

            $recentLog->update([
                'properties' => $properties,
                'description' => $description,
            ]);
        } else {
            // Создаем новый лог
            ActivityLog::create([
                'log_name' => 'user',
                'description' => __('activity-logs.descriptions.user_avatar_changed', [
                    'model' => 'User',
                    'name' => $targetUser->name,
                ]),
                'event' => 'updated',
                'causer_id' => $user->id,
                'causer_type' => get_class($user),
                'subject_type' => User::class,
                'subject_id' => $targetUser->id,
                'properties' => [
                    'old' => $action === 'removed' ? ['avatar' => $avatarData] : [],
                    'new' => $action === 'added' ? ['avatar' => $avatarData] : [],
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }
}
