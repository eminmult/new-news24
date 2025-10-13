<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogObserver
{
    /**
     * Атрибуты, которые следует игнорировать при логировании
     */
    protected array $ignoredAttributes = [
        'updated_at',
        'created_at',
        'deleted_at',
        'remember_token',
    ];

    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        $this->logActivity($model, 'created', [
            'new' => $this->getRelevantAttributes($model),
        ]);
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        $changes = $model->getChanges();
        $original = $model->getOriginal();

        // Убираем игнорируемые атрибуты
        $changes = array_diff_key($changes, array_flip($this->ignoredAttributes));

        if (empty($changes)) {
            return; // Нет изменений для логирования
        }

        $old = [];
        $new = [];

        foreach ($changes as $key => $value) {
            $old[$key] = $original[$key] ?? null;
            $new[$key] = $value;
        }

        $this->logActivity($model, 'updated', [
            'old' => $old,
            'new' => $new,
        ]);
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $this->logActivity($model, 'deleted', [
            'old' => $this->getRelevantAttributes($model),
        ]);
    }

    /**
     * Handle the Model "restored" event.
     */
    public function restored(Model $model): void
    {
        $this->logActivity($model, 'restored', [
            'new' => $this->getRelevantAttributes($model),
        ]);
    }

    /**
     * Логировать активность
     */
    protected function logActivity(Model $model, string $event, array $properties): void
    {
        $user = Auth::user();

        // Пропускаем логирование, если нет авторизованного пользователя
        if (!$user) {
            return;
        }

        ActivityLog::create([
            'log_name' => $this->getLogName($model),
            'description' => $this->getDescription($model, $event),
            'event' => $event,
            'causer_id' => $user->id,
            'causer_type' => get_class($user),
            'subject_type' => get_class($model),
            'subject_id' => $model->getKey(),
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Получить название лога на основе модели
     */
    protected function getLogName(Model $model): string
    {
        return strtolower(class_basename($model));
    }

    /**
     * Получить описание действия
     */
    protected function getDescription(Model $model, string $event): string
    {
        $modelName = class_basename($model);
        $modelId = $model->getKey();

        // Пытаемся получить читаемое имя модели
        $name = null;
        if (method_exists($model, 'getActivityLogName')) {
            $name = $model->getActivityLogName();
        } elseif (isset($model->title)) {
            $name = $model->title;
        } elseif (isset($model->name)) {
            $name = $model->name;
        }

        // Используем переводы вместо хардкода
        if ($name) {
            return __('activity-logs.descriptions.model_' . $event, [
                'model' => $modelName,
                'name' => $name,
            ]);
        }

        return __('activity-logs.descriptions.model_' . $event . '_id', [
            'model' => $modelName,
            'id' => $modelId,
        ]);
    }

    /**
     * Получить релевантные атрибуты модели (без игнорируемых)
     */
    protected function getRelevantAttributes(Model $model): array
    {
        $attributes = $model->getAttributes();
        return array_diff_key($attributes, array_flip($this->ignoredAttributes));
    }
}
