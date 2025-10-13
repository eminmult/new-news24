<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'log_name',
        'description',
        'event',
        'causer_id',
        'causer_type',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Пользователь, который совершил действие
     */
    public function causer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    /**
     * Модель, с которой произошло действие
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Получить старые значения из properties
     */
    public function getOldValues(): ?array
    {
        return $this->properties['old'] ?? null;
    }

    /**
     * Получить новые значения из properties
     */
    public function getNewValues(): ?array
    {
        return $this->properties['new'] ?? null;
    }

    /**
     * Получить изменённые атрибуты
     */
    public function getChangedAttributes(): array
    {
        $old = $this->getOldValues() ?? [];
        $new = $this->getNewValues() ?? [];

        return array_keys(array_merge($old, $new));
    }

    /**
     * Проверить, был ли изменён конкретный атрибут
     */
    public function hasChanged(string $attribute): bool
    {
        $old = $this->getOldValues();
        $new = $this->getNewValues();

        if (!isset($old[$attribute]) && !isset($new[$attribute])) {
            return false;
        }

        return ($old[$attribute] ?? null) !== ($new[$attribute] ?? null);
    }

    /**
     * Получить человекочитаемое имя модели
     */
    public function getSubjectName(): string
    {
        if (!$this->subject) {
            return $this->subject_type ?? 'Unknown';
        }

        // Пытаемся получить имя модели
        if (method_exists($this->subject, 'getActivityLogName')) {
            return $this->subject->getActivityLogName();
        }

        // Используем стандартные атрибуты
        $nameAttributes = ['title', 'name', 'slug', 'id'];
        foreach ($nameAttributes as $attr) {
            if (isset($this->subject->$attr)) {
                return $this->subject->$attr;
            }
        }

        return class_basename($this->subject_type) . ' #' . $this->subject_id;
    }

    /**
     * Получить изменения изображений из HTML content
     * Возвращает массив с ключами 'added' и 'removed'
     */
    public function getContentImagesChanges(?string $oldHtml, ?string $newHtml): array
    {
        $oldImages = $this->extractImagesFromHtml($oldHtml ?? '');
        $newImages = $this->extractImagesFromHtml($newHtml ?? '');

        $removedImages = array_diff($oldImages, $newImages);
        $addedImages = array_diff($newImages, $oldImages);

        return [
            'removed' => array_values($removedImages),
            'added' => array_values($addedImages),
        ];
    }

    /**
     * Извлечь все src изображений из HTML
     */
    protected function extractImagesFromHtml(string $html): array
    {
        $images = [];

        // Используем DOMDocument для безопасного парсинга HTML
        if (empty($html)) {
            return $images;
        }

        // Простой regex для извлечения src из img тегов
        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $html, $matches);

        if (!empty($matches[1])) {
            $images = $matches[1];
        }

        return $images;
    }

    /**
     * Получить HTML с выделением изменений между старым и новым значением
     */
    public function getDiffHtml(?string $oldValue, ?string $newValue): string
    {
        if (empty($oldValue) && empty($newValue)) {
            return '';
        }

        if (empty($oldValue)) {
            return '<span class="custom-diff-ins">' . e($newValue) . '</span>';
        }

        if (empty($newValue)) {
            return '<span class="custom-diff-del">' . e($oldValue) . '</span>';
        }

        // Простая реализация word-level diff
        $oldWords = preg_split('/(\s+)/', $oldValue, -1, PREG_SPLIT_DELIM_CAPTURE);
        $newWords = preg_split('/(\s+)/', $newValue, -1, PREG_SPLIT_DELIM_CAPTURE);

        $result = '';
        $i = 0;
        $j = 0;

        while ($i < count($oldWords) || $j < count($newWords)) {
            if ($i < count($oldWords) && $j < count($newWords)) {
                if ($oldWords[$i] === $newWords[$j]) {
                    // Слова одинаковые
                    $result .= e($oldWords[$i]);
                    $i++;
                    $j++;
                } else {
                    // Слова разные
                    // Проверяем, есть ли старое слово дальше в новом массиве
                    $foundInNew = false;
                    for ($k = $j; $k < min($j + 5, count($newWords)); $k++) {
                        if ($oldWords[$i] === $newWords[$k]) {
                            $foundInNew = true;
                            break;
                        }
                    }

                    // Проверяем, есть ли новое слово дальше в старом массиве
                    $foundInOld = false;
                    for ($k = $i; $k < min($i + 5, count($oldWords)); $k++) {
                        if ($newWords[$j] === $oldWords[$k]) {
                            $foundInOld = true;
                            break;
                        }
                    }

                    if (!$foundInNew && !trim($oldWords[$i])) {
                        // Это пробел, просто добавляем
                        $result .= $oldWords[$i];
                        $i++;
                    } elseif (!$foundInOld && !trim($newWords[$j])) {
                        // Это пробел, просто добавляем
                        $result .= $newWords[$j];
                        $j++;
                    } elseif ($foundInNew && !$foundInOld) {
                        // Новое слово добавлено
                        if (trim($newWords[$j])) {
                            $result .= '<span class="custom-diff-ins">' . e($newWords[$j]) . '</span>';
                        } else {
                            $result .= $newWords[$j];
                        }
                        $j++;
                    } elseif (!$foundInNew && $foundInOld) {
                        // Старое слово удалено
                        if (trim($oldWords[$i])) {
                            $result .= '<span class="custom-diff-del">' . e($oldWords[$i]) . '</span>';
                        } else {
                            $result .= $oldWords[$i];
                        }
                        $i++;
                    } else {
                        // Замена: удалено и добавлено
                        if (trim($oldWords[$i])) {
                            $result .= '<span class="custom-diff-del">' . e($oldWords[$i]) . '</span>';
                        } else {
                            $result .= $oldWords[$i];
                        }
                        $i++;
                        if (trim($newWords[$j])) {
                            $result .= '<span class="custom-diff-ins">' . e($newWords[$j]) . '</span>';
                        } else {
                            $result .= $newWords[$j];
                        }
                        $j++;
                    }
                }
            } elseif ($i < count($oldWords)) {
                // Остались только старые слова (удалены)
                if (trim($oldWords[$i])) {
                    $result .= '<span class="custom-diff-del">' . e($oldWords[$i]) . '</span>';
                } else {
                    $result .= $oldWords[$i];
                }
                $i++;
            } else {
                // Остались только новые слова (добавлены)
                if (trim($newWords[$j])) {
                    $result .= '<span class="custom-diff-ins">' . e($newWords[$j]) . '</span>';
                } else {
                    $result .= $newWords[$j];
                }
                $j++;
            }
        }

        return $result;
    }
}
