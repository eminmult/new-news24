<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Models\ActivityLog;
use App\Models\PostLock;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected array $originalGallery = [];

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            \Filament\Actions\Action::make('cancel')
                ->label(__('posts.table.actions.cancel'))
                ->color('gray')
                ->action(function () {
                    // Снимаем блокировку перед уходом
                    PostLock::where('post_id', $this->record->id)
                        ->where('user_id', Auth::id())
                        ->delete();

                    return redirect()->route('filament.admin.resources.posts.index');
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

                redirect()->route('filament.admin.resources.posts.index');
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
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Сохраняем текущее состояние галереи при загрузке формы в сессию
        // Сохраняем как file_name => url для правильного отображения
        $originalGallery = $this->record->getMedia('post-gallery')->mapWithKeys(function($media) {
            return [$media->file_name => $media->getUrl()];
        })->toArray();

        session()->put('post_' . $this->record->id . '_original_gallery', $originalGallery);

        // Сохраняем текущие категории
        $originalCategories = $this->record->categories()->pluck('categories.id')->toArray();
        session()->put('post_' . $this->record->id . '_original_categories', $originalCategories);

        // Сохраняем текущие типы
        $originalTypes = $this->record->types()->pluck('post_types.id')->toArray();
        session()->put('post_' . $this->record->id . '_original_types', $originalTypes);

        // Сохраняем текущие виджеты
        $originalWidgets = $this->record->widgets()->get()->map(function($widget) {
            return [
                'id' => $widget->id,
                'type' => $widget->type,
                'content' => $widget->content,
                'order' => $widget->order,
            ];
        })->toArray();
        session()->put('post_' . $this->record->id . '_original_widgets', $originalWidgets);

        return $data;
    }

    protected function afterSave(): void
    {
        // Получаем оригинальную галерею из сессии (file_name => url)
        $originalGallery = session()->get('post_' . $this->record->id . '_original_gallery', []);

        // Проверяем галерею после сохранения (file_name => url)
        $currentGallery = $this->record->fresh()->getMedia('post-gallery')->mapWithKeys(function($media) {
            return [$media->file_name => $media->getUrl()];
        })->toArray();

        // Находим изменения (сравниваем ключи - file_name)
        $originalKeys = array_keys($originalGallery);
        $currentKeys = array_keys($currentGallery);

        $addedKeys = array_diff($currentKeys, $originalKeys);
        $removedKeys = array_diff($originalKeys, $currentKeys);

        if (!empty($addedKeys) || !empty($removedKeys)) {
            // Собираем данные для лога
            $added = [];
            foreach ($addedKeys as $key) {
                $added[$key] = $currentGallery[$key];
            }

            $removed = [];
            foreach ($removedKeys as $key) {
                $removed[$key] = $originalGallery[$key];
            }

            $this->trackGalleryChanges($added, $removed, $this->record);
        }

        // Проверяем изменения категорий
        $originalCategories = session()->get('post_' . $this->record->id . '_original_categories', []);
        $currentCategories = $this->record->fresh()->categories()->pluck('categories.id')->toArray();

        sort($originalCategories);
        sort($currentCategories);

        if ($originalCategories !== $currentCategories) {
            $this->trackCategoryChanges($originalCategories, $currentCategories, $this->record);
        }

        // Проверяем изменения типов
        $originalTypes = session()->get('post_' . $this->record->id . '_original_types', []);
        $currentTypes = $this->record->fresh()->types()->pluck('post_types.id')->toArray();

        sort($originalTypes);
        sort($currentTypes);

        if ($originalTypes !== $currentTypes) {
            $this->trackTypeChanges($originalTypes, $currentTypes, $this->record);
        }

        // Проверяем изменения виджетов
        $originalWidgets = session()->get('post_' . $this->record->id . '_original_widgets', []);
        $currentWidgets = $this->record->fresh()->widgets()->get()->map(function($widget) {
            return [
                'id' => $widget->id,
                'type' => $widget->type,
                'content' => $widget->content,
                'order' => $widget->order,
            ];
        })->toArray();

        if ($this->hasWidgetChanges($originalWidgets, $currentWidgets)) {
            $this->trackWidgetChanges($originalWidgets, $currentWidgets, $this->record);
        }

        // Удаляем из сессии
        session()->forget('post_' . $this->record->id . '_original_gallery');
        session()->forget('post_' . $this->record->id . '_original_categories');
        session()->forget('post_' . $this->record->id . '_original_types');
        session()->forget('post_' . $this->record->id . '_original_widgets');

        // Снимаем блокировку после сохранения
        PostLock::where('post_id', $this->record->id)
            ->where('user_id', Auth::id())
            ->delete();
    }

    protected function trackGalleryChanges(array $added, array $removed, $record): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        if (empty($added) && empty($removed)) {
            return; // Нет изменений в галерее
        }

        // $added и $removed теперь в формате ['file_name' => 'url', ...]
        // Преобразуем в формат для отображения
        $addedData = [];
        foreach ($added as $fileName => $url) {
            $addedData[] = [
                'file_name' => $fileName,
                'url' => $url
            ];
        }

        $removedData = [];
        foreach ($removed as $fileName => $url) {
            $removedData[] = [
                'file_name' => $fileName,
                'url' => $url
            ];
        }

        // Проверяем, был ли только что создан лог для этого поста (в течение последних 10 секунд)
        $recentLog = ActivityLog::where('subject_type', get_class($record))
            ->where('subject_id', $record->getKey())
            ->where('event', 'updated')
            ->where('causer_id', $user->id)
            ->where('created_at', '>=', now()->subSeconds(10))
            ->orderBy('created_at', 'desc')
            ->first();

        if ($recentLog) {
            // Обновляем существующий лог, добавляя изменения галереи
            $properties = $recentLog->properties ?? [];

            // Добавляем изменения галереи к существующим изменениям
            if (!empty($removedData)) {
                $properties['old']['gallery'] = $removedData;
            }
            if (!empty($addedData)) {
                $properties['new']['gallery'] = $addedData;
            }

            // Обновляем описание, добавляя информацию о галерее
            $description = $recentLog->description;
            $includingText = __('activity-logs.descriptions.including_gallery');
            if (strpos($description, $includingText) === false) {
                $description .= $includingText;
            }

            $recentLog->update([
                'properties' => $properties,
                'description' => $description,
            ]);
        } else {
            // Создаем новый лог только для изменений галереи
            ActivityLog::create([
                'log_name' => 'post',
                'description' => __('activity-logs.descriptions.post_gallery_changed', [
                    'model' => 'Post',
                    'name' => $record->title ?? $record->id,
                ]),
                'event' => 'updated',
                'causer_id' => $user->id,
                'causer_type' => get_class($user),
                'subject_type' => get_class($record),
                'subject_id' => $record->getKey(),
                'properties' => [
                    'old' => !empty($removedData) ? ['gallery' => $removedData] : [],
                    'new' => !empty($addedData) ? ['gallery' => $addedData] : [],
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }

    protected function trackCategoryChanges(array $originalCategoryIds, array $currentCategoryIds, $record): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        if ($originalCategoryIds === $currentCategoryIds) {
            return; // Нет изменений
        }

        // Получаем имена категорий
        $allCategoryIds = array_unique(array_merge($originalCategoryIds, $currentCategoryIds));
        $categories = \App\Models\Category::whereIn('id', $allCategoryIds)->pluck('name', 'id');

        $oldCategories = [];
        foreach ($originalCategoryIds as $id) {
            $oldCategories[] = $categories[$id] ?? "ID: $id";
        }

        $newCategories = [];
        foreach ($currentCategoryIds as $id) {
            $newCategories[] = $categories[$id] ?? "ID: $id";
        }

        // Проверяем, был ли только что создан лог для этого поста (в течение последних 10 секунд)
        $recentLog = ActivityLog::where('subject_type', get_class($record))
            ->where('subject_id', $record->getKey())
            ->where('event', 'updated')
            ->where('causer_id', $user->id)
            ->where('created_at', '>=', now()->subSeconds(10))
            ->orderBy('created_at', 'desc')
            ->first();

        if ($recentLog) {
            // Обновляем существующий лог, добавляя изменения категорий
            $properties = $recentLog->properties ?? [];

            $properties['old']['categories'] = implode(', ', $oldCategories);
            $properties['new']['categories'] = implode(', ', $newCategories);

            // Обновляем описание, добавляя информацию о категориях
            $description = $recentLog->description;
            $includingText = __('activity-logs.descriptions.including_categories');
            if (strpos($description, $includingText) === false) {
                $description .= $includingText;
            }

            $recentLog->update([
                'properties' => $properties,
                'description' => $description,
            ]);
        } else {
            // Создаем новый лог только для изменений категорий
            ActivityLog::create([
                'log_name' => 'post',
                'description' => __('activity-logs.descriptions.post_categories_changed', [
                    'model' => 'Post',
                    'name' => $record->title ?? $record->id,
                ]),
                'event' => 'updated',
                'causer_id' => $user->id,
                'causer_type' => get_class($user),
                'subject_type' => get_class($record),
                'subject_id' => $record->getKey(),
                'properties' => [
                    'old' => ['categories' => implode(', ', $oldCategories)],
                    'new' => ['categories' => implode(', ', $newCategories)],
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }

    protected function trackTypeChanges(array $originalTypeIds, array $currentTypeIds, $record): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        if ($originalTypeIds === $currentTypeIds) {
            return; // Нет изменений
        }

        // Получаем названия типов
        $allTypeIds = array_unique(array_merge($originalTypeIds, $currentTypeIds));
        $types = \App\Models\PostType::whereIn('id', $allTypeIds)->pluck('name', 'id');

        $oldTypes = [];
        foreach ($originalTypeIds as $id) {
            $oldTypes[] = $types[$id] ?? "ID: $id";
        }

        $newTypes = [];
        foreach ($currentTypeIds as $id) {
            $newTypes[] = $types[$id] ?? "ID: $id";
        }

        // Проверяем, был ли только что создан лог для этого поста (в течение последних 10 секунд)
        $recentLog = ActivityLog::where('subject_type', get_class($record))
            ->where('subject_id', $record->getKey())
            ->where('event', 'updated')
            ->where('causer_id', $user->id)
            ->where('created_at', '>=', now()->subSeconds(10))
            ->orderBy('created_at', 'desc')
            ->first();

        if ($recentLog) {
            // Обновляем существующий лог, добавляя изменения типов
            $properties = $recentLog->properties ?? [];

            $properties['old']['types'] = implode(', ', $oldTypes);
            $properties['new']['types'] = implode(', ', $newTypes);

            // Обновляем описание, добавляя информацию о типах
            $description = $recentLog->description;
            $includingText = __('activity-logs.descriptions.including_types');
            if (strpos($description, $includingText) === false) {
                $description .= $includingText;
            }

            $recentLog->update([
                'properties' => $properties,
                'description' => $description,
            ]);
        } else {
            // Создаем новый лог только для изменений типов
            ActivityLog::create([
                'log_name' => 'post',
                'description' => __('activity-logs.descriptions.post_types_changed', [
                    'model' => 'Post',
                    'name' => $record->title ?? $record->id,
                ]),
                'event' => 'updated',
                'causer_id' => $user->id,
                'causer_type' => get_class($user),
                'subject_type' => get_class($record),
                'subject_id' => $record->getKey(),
                'properties' => [
                    'old' => ['types' => implode(', ', $oldTypes)],
                    'new' => ['types' => implode(', ', $newTypes)],
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }

    protected function hasWidgetChanges(array $originalWidgets, array $currentWidgets): bool
    {
        // Сравниваем по ID и содержимому
        $originalIds = collect($originalWidgets)->pluck('id')->sort()->values()->toArray();
        $currentIds = collect($currentWidgets)->pluck('id')->sort()->values()->toArray();

        if ($originalIds !== $currentIds) {
            return true; // Разное количество или разные ID
        }

        // Проверяем содержимое каждого виджета
        $originalByContent = collect($originalWidgets)->map(function($w) {
            return $w['type'] . '|' . $w['content'];
        })->sort()->values()->toArray();

        $currentByContent = collect($currentWidgets)->map(function($w) {
            return $w['type'] . '|' . $w['content'];
        })->sort()->values()->toArray();

        return $originalByContent !== $currentByContent;
    }

    protected function trackWidgetChanges(array $originalWidgets, array $currentWidgets, $record): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        // Находим добавленные и удаленные виджеты
        $originalIds = collect($originalWidgets)->pluck('id')->toArray();
        $currentIds = collect($currentWidgets)->pluck('id')->toArray();

        $removedIds = array_diff($originalIds, $currentIds);
        $addedIds = array_diff($currentIds, $originalIds);

        $removedWidgets = collect($originalWidgets)->whereIn('id', $removedIds)->values()->toArray();
        $addedWidgets = collect($currentWidgets)->whereIn('id', $addedIds)->values()->toArray();

        if (empty($removedWidgets) && empty($addedWidgets)) {
            return; // Нет изменений
        }

        // Подготавливаем данные для лога
        $removedData = [];
        foreach ($removedWidgets as $widget) {
            $removedData[] = [
                'type' => $widget['type'],
                'content' => $widget['content'],
            ];
        }

        $addedData = [];
        foreach ($addedWidgets as $widget) {
            $addedData[] = [
                'type' => $widget['type'],
                'content' => $widget['content'],
            ];
        }

        // Проверяем, был ли только что создан лог для этого поста (в течение последних 10 секунд)
        $recentLog = ActivityLog::where('subject_type', get_class($record))
            ->where('subject_id', $record->getKey())
            ->where('event', 'updated')
            ->where('causer_id', $user->id)
            ->where('created_at', '>=', now()->subSeconds(10))
            ->orderBy('created_at', 'desc')
            ->first();

        if ($recentLog) {
            // Обновляем существующий лог, добавляя изменения виджетов
            $properties = $recentLog->properties ?? [];

            if (!empty($removedData)) {
                $properties['old']['widgets'] = $removedData;
            }
            if (!empty($addedData)) {
                $properties['new']['widgets'] = $addedData;
            }

            // Обновляем описание, добавляя информацию о виджетах
            $description = $recentLog->description;
            $includingText = __('activity-logs.descriptions.including_widgets');
            if (strpos($description, $includingText) === false) {
                $description .= $includingText;
            }

            $recentLog->update([
                'properties' => $properties,
                'description' => $description,
            ]);
        } else {
            // Создаем новый лог только для изменений виджетов
            ActivityLog::create([
                'log_name' => 'post',
                'description' => __('activity-logs.descriptions.post_widgets_changed', [
                    'model' => 'Post',
                    'name' => $record->title ?? $record->id,
                ]),
                'event' => 'updated',
                'causer_id' => $user->id,
                'causer_type' => get_class($user),
                'subject_type' => get_class($record),
                'subject_id' => $record->getKey(),
                'properties' => [
                    'old' => !empty($removedData) ? ['widgets' => $removedData] : [],
                    'new' => !empty($addedData) ? ['widgets' => $addedData] : [],
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
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
