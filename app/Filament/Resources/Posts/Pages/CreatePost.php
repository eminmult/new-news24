<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Models\ActivityLog;
use App\Observers\PostObserver;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function afterCreate(): void
    {
        // Логируем создание поста
        ActivityLog::create([
            'log_name' => 'post',
            'description' => __('activity-logs.descriptions.post_created', [
                'title' => $this->record->title,
            ]),
            'event' => 'created',
            'causer_id' => Auth::id(),
            'causer_type' => 'App\Models\User',
            'subject_type' => get_class($this->record),
            'subject_id' => $this->record->id,
            'properties' => json_encode([
                'attributes' => $this->record->toArray(),
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // ВАЖНО: Очищаем кеш немедленно после создания поста
        $observer = new PostObserver();
        $observer->created($this->record);

        file_put_contents(storage_path('logs/observer-debug.log'), date('Y-m-d H:i:s') . " - FILAMENT POST CREATED: {$this->record->id} - {$this->record->title}\n", FILE_APPEND);
    }
}
