@php
    $oldValues = $record->getOldValues() ?? [];
    $newValues = $record->getNewValues() ?? [];
    $allKeys = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));
@endphp

<style>
    .changes-content {
        background: #ffffff;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
    }
    .dark .changes-content {
        background: rgba(17, 24, 39, 0.5);
        border-color: rgba(255,255,255,0.1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    .custom-diff-wrapper {
        font-family: inherit;
        font-size: inherit;
        line-height: 1.6;
    }
    .custom-diff-del {
        background-color: #fee2e2;
        color: #991b1b;
        text-decoration: line-through;
        padding: 3px 8px;
        margin: 0 2px;
        border-radius: 4px;
        font-weight: 600;
        display: inline-block;
    }
    .dark .custom-diff-del {
        background-color: rgba(239, 68, 68, 0.25);
        color: #fca5a5;
    }
    .custom-diff-ins {
        background-color: #dcfce7;
        color: #166534;
        padding: 3px 8px;
        margin: 0 2px;
        border-radius: 4px;
        font-weight: 600;
        display: inline-block;
    }
    .dark .custom-diff-ins {
        background-color: rgba(34, 197, 94, 0.25);
        color: #86efac;
    }
    .change-diff-block {
        background: #f9fafb;
        border-left: 4px solid #6b7280;
        padding: 0.875rem;
        border-radius: 0.5rem;
    }
    .dark .change-diff-block {
        background: rgba(107, 114, 128, 0.1);
        border-left-color: #9ca3af;
    }
    .change-diff-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #6b7280;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .dark .change-diff-label {
        color: #9ca3af;
    }
    .change-diff-text {
        font-size: 0.875rem;
        color: #111827;
        word-break: break-word;
        line-height: 1.8;
    }
    .dark .change-diff-text {
        color: #f9fafb;
    }
    .changes-header {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    .dark .changes-header {
        color: #f9fafb;
        border-bottom-color: rgba(255,255,255,0.1);
    }
    .change-item {
        margin-bottom: 1.5rem;
    }
    .change-item:last-child {
        margin-bottom: 0;
    }
    .change-field-name {
        font-size: 0.8125rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        margin-bottom: 0.75rem;
    }
    .dark .change-field-name {
        color: #9ca3af;
    }
    .change-old-value {
        background: #fee2e2;
        border-left: 4px solid #ef4444;
        padding: 0.875rem;
        border-radius: 0.5rem;
        margin-bottom: 0.75rem;
    }
    .dark .change-old-value {
        background: rgba(239, 68, 68, 0.15);
        border-left-color: #ef4444;
    }
    .change-old-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #991b1b;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .dark .change-old-label {
        color: #fca5a5;
    }
    .change-old-text {
        font-size: 0.875rem;
        color: #7f1d1d;
        word-break: break-word;
    }
    .dark .change-old-text {
        color: #fecaca;
    }
    .change-new-value {
        background: #dcfce7;
        border-left: 4px solid #22c55e;
        padding: 0.875rem;
        border-radius: 0.5rem;
    }
    .dark .change-new-value {
        background: rgba(34, 197, 94, 0.15);
        border-left-color: #22c55e;
    }
    .change-new-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #166534;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .dark .change-new-label {
        color: #86efac;
    }
    .change-new-text {
        font-size: 0.875rem;
        color: #14532d;
        word-break: break-word;
    }
    .dark .change-new-text {
        color: #bbf7d0;
    }
    .change-image {
        max-width: 200px;
        max-height: 128px;
        border-radius: 0.5rem;
        margin-top: 0.5rem;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .dark .change-image {
        border-color: rgba(255,255,255,0.2);
    }
    .content-images-header {
        font-size: 0.75rem;
        font-weight: 700;
        color: #6b7280;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
    }
    .dark .content-images-header {
        color: #9ca3af;
    }
</style>

@if(empty($allKeys))
    <div style="font-size: 0.875rem; color: #6b7280;">
        {{ __('activity-logs.no_changes') }}
    </div>
@else
    <div class="changes-content">
        <div class="changes-header">
            <svg style="width: 1.25rem; height: 1.25rem; display: inline-block; margin-right: 0.5rem; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
            </svg>
            {{ __('activity-logs.view.changes') }}
        </div>

        @foreach($allKeys as $key)
            @php
                $oldValue = $oldValues[$key] ?? null;
                $newValue = $newValues[$key] ?? null;
                $hasChanged = $oldValue !== $newValue;

                // Преобразуем author_id в имя автора
                if ($key === 'author_id') {
                    if (!is_null($oldValue) && is_numeric($oldValue)) {
                        $oldAuthor = \App\Models\User::find($oldValue);
                        $oldValue = $oldAuthor ? $oldAuthor->name : "ID: $oldValue";
                    }
                    if (!is_null($newValue) && is_numeric($newValue)) {
                        $newAuthor = \App\Models\User::find($newValue);
                        $newValue = $newAuthor ? $newAuthor->name : "ID: $newValue";
                    }
                }
            @endphp

            @if($hasChanged)
                <div class="change-item">
                    <div class="change-field-name">
                        @php
                            $displayName = __('activity-logs.fields.' . $key);
                            if ($displayName === 'activity-logs.fields.' . $key) {
                                // Если перевод не найден, используем форматированный ключ
                                $displayName = ucfirst(str_replace('_', ' ', $key));
                            }
                        @endphp
                        {{ $displayName }}
                    </div>

                    @php
                        // Проверяем, можем ли мы использовать inline diff
                        $canUseDiff = !is_null($oldValue) && !is_null($newValue)
                            && is_string($oldValue) && is_string($newValue)
                            && $key !== 'gallery'
                            && !is_array($oldValue) && !is_array($newValue)
                            && strlen($oldValue) < 10000 && strlen($newValue) < 10000;

                        // Для content (HTML) используем diff на очищенном тексте
                        $oldValueForDiff = $oldValue;
                        $newValueForDiff = $newValue;
                        if ($key === 'content' && $canUseDiff) {
                            $oldValueForDiff = strip_tags($oldValue);
                            $newValueForDiff = strip_tags($newValue);
                        }
                    @endphp

                    @if($canUseDiff)
                        {{-- Используем inline diff для текстовых полей --}}
                        <div class="change-diff-block">
                            <div class="change-diff-label">
                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                                <span>{{ __('activity-logs.view.changes_label') }}</span>
                            </div>
                            <div class="change-diff-text">
                                {!! $record->getDiffHtml($oldValueForDiff, $newValueForDiff) !!}
                            </div>
                        </div>

                        {{-- Для content также показываем изменения изображений --}}
                        @if($key === 'content')
                            @php
                                $imageChanges = $record->getContentImagesChanges($oldValue, $newValue);
                                $hasImageChanges = !empty($imageChanges['removed']) || !empty($imageChanges['added']);
                            @endphp

                            @if($hasImageChanges)
                                <div style="margin-top: 1rem;">
                                    <div class="content-images-header">
                                        {{ __('activity-logs.view.content_images_changes') }}
                                    </div>

                                    @if(!empty($imageChanges['removed']))
                                        <div class="change-old-value" style="margin-bottom: 0.75rem;">
                                            <div class="change-old-label">
                                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                <span>{{ __('activity-logs.view.deleted_images') }}</span>
                                            </div>
                                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.75rem; margin-top: 0.5rem;">
                                                @foreach($imageChanges['removed'] as $imageSrc)
                                                    <div style="position: relative;">
                                                        <img src="{{ $imageSrc }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 0.5rem; border: 3px solid #ef4444;" alt="Deleted image">
                                                        <div style="position: absolute; top: 0.25rem; right: 0.25rem; background: #ef4444; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700;">{{ mb_strtoupper(__('activity-logs.events.deleted')) }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if(!empty($imageChanges['added']))
                                        <div class="change-new-value">
                                            <div class="change-new-label">
                                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span>{{ __('activity-logs.view.added_images') }}</span>
                                            </div>
                                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.75rem; margin-top: 0.5rem;">
                                                @foreach($imageChanges['added'] as $imageSrc)
                                                    <div style="position: relative;">
                                                        <img src="{{ $imageSrc }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 0.5rem; border: 3px solid #22c55e;" alt="Added image">
                                                        <div style="position: absolute; top: 0.25rem; right: 0.25rem; background: #22c55e; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700;">{{ mb_strtoupper(__('activity-logs.events.created')) }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif
                    @else
                        {{-- Показываем старое и новое значение раздельно --}}
                        @if(!is_null($oldValue))
                            <div class="change-old-value">
                                <div class="change-old-label">
                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span>{{ __('activity-logs.view.was') }}</span>
                                </div>
                                <div class="change-old-text">
                                    @if($key === 'gallery' && is_array($oldValue))
                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.75rem; margin-top: 0.5rem;">
                                        @foreach($oldValue as $imageData)
                                            @php
                                                $imageName = is_array($imageData) ? ($imageData['file_name'] ?? '') : $imageData;
                                                $imageUrl = is_array($imageData) ? ($imageData['url'] ?? "/storage/{$imageName}") : "/storage/{$imageName}";
                                            @endphp
                                            <div style="position: relative;">
                                                <img src="{{ $imageUrl }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 0.5rem; border: 3px solid #ef4444;" alt="Deleted image">
                                                <div style="position: absolute; top: 0.25rem; right: 0.25rem; background: #ef4444; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700;">{{ mb_strtoupper(__('activity-logs.events.deleted')) }}</div>
                                                <div style="font-size: 0.625rem; margin-top: 0.25rem; word-break: break-all;">{{ $imageName }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($key === 'avatar' && is_array($oldValue))
                                    @php
                                        $avatarUrl = is_array($oldValue) ? ($oldValue['url'] ?? '') : $oldValue;
                                    @endphp
                                    <div style="margin-top: 0.5rem;">
                                        <img src="{{ $avatarUrl }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 4px solid #ef4444;" alt="Old avatar">
                                    </div>
                                @elseif($key === 'widgets' && is_array($oldValue))
                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.75rem; margin-top: 0.5rem;">
                                        @foreach($oldValue as $widgetData)
                                            @php
                                                $widgetType = $widgetData['type'] ?? 'unknown';
                                                $widgetContent = $widgetData['content'] ?? '';
                                                $widgetUrl = '';
                                                if ($widgetType === 'youtube') {
                                                    $widgetUrl = 'https://www.youtube.com/watch?v=' . $widgetContent;
                                                } elseif ($widgetType === 'instagram') {
                                                    $widgetUrl = 'https://www.instagram.com/p/' . $widgetContent . '/';
                                                } elseif ($widgetType === 'okru') {
                                                    $widgetUrl = 'https://ok.ru/video/' . $widgetContent;
                                                }
                                            @endphp
                                            <div>
                                                <div style="position: relative; background: rgba(0,0,0,0.05); border-radius: 0.5rem; overflow: hidden; border: 3px solid #ef4444;">
                                                    @if($widgetType === 'youtube')
                                                        <img src="https://img.youtube.com/vi/{{ $widgetContent }}/maxresdefault.jpg" style="width: 100%; height: 150px; object-fit: cover;" alt="YouTube">
                                                        <div style="position: absolute; bottom: 0.5rem; left: 0.5rem; background: rgba(255,0,0,0.9); color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700;">▶ YouTube</div>
                                                    @elseif($widgetType === 'instagram')
                                                        <div style="width: 100%; height: 150px; display: flex; align-items: center; justify-content: center; background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);">
                                                            <svg style="width: 4rem; height: 4rem; color: white;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                                        </div>
                                                        <div style="position: absolute; bottom: 0.5rem; left: 0.5rem; background: rgba(0,0,0,0.7); color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700;">Instagram</div>
                                                    @elseif($widgetType === 'okru')
                                                        <div style="width: 100%; height: 150px; display: flex; align-items: center; justify-content: center; background: #ee8208;">
                                                            <svg style="width: 4rem; height: 4rem; color: white;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.6 0 12 0zm0 2.5c1.9 0 3.5 1.6 3.5 3.5s-1.6 3.5-3.5 3.5-3.5-1.6-3.5-3.5 1.6-3.5 3.5-3.5zm0 16.5c-2.8 0-5.3-1.5-6.7-3.7l2.5-1.4c1 1.6 2.8 2.6 4.7 2.6s3.7-1 4.7-2.6l2.5 1.4c-1.4 2.2-3.9 3.7-7.2 3.7z"/></svg>
                                                        </div>
                                                        <div style="position: absolute; bottom: 0.5rem; left: 0.5rem; background: rgba(0,0,0,0.7); color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700;">OK.ru</div>
                                                    @else
                                                        <div style="width: 100%; height: 150px; display: flex; align-items: center; justify-content: center; background: #6b7280; color: white; font-size: 0.875rem;">{{ strtoupper($widgetType) }}</div>
                                                    @endif
                                                    <div style="position: absolute; top: 0.25rem; right: 0.25rem; background: #ef4444; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700;">{{ mb_strtoupper(__('activity-logs.events.deleted')) }}</div>
                                                </div>
                                                @if($widgetUrl)
                                                    <a href="{{ $widgetUrl }}" target="_blank" rel="noopener noreferrer" style="display: block; margin-top: 0.5rem; font-size: 0.75rem; color: #3b82f6; text-decoration: none; word-break: break-all;">
                                                        <svg style="width: 0.875rem; height: 0.875rem; display: inline-block; vertical-align: middle; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                        </svg>
                                                        {{ $widgetUrl }}
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif(is_array($oldValue) || is_object($oldValue))
                                    <pre style="font-size: 0.8125rem; white-space: pre-wrap; font-family: monospace; margin: 0;">{{ json_encode($oldValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @elseif(is_bool($oldValue))
                                    {{ $oldValue ? __('activity-logs.yes') : __('activity-logs.no') }}
                                @elseif(is_string($oldValue) && filter_var($oldValue, FILTER_VALIDATE_URL) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $oldValue))
                                    <img src="{{ $oldValue }}" class="change-image" alt="Old image">
                                    <div style="font-size: 0.75rem; margin-top: 0.5rem; opacity: 0.7;">{{ $oldValue }}</div>
                                @else
                                    {{ $oldValue }}
                                @endif
                            </div>
                        </div>
                    @endif

                    @if(!is_null($newValue))
                        <div class="change-new-value">
                            <div class="change-new-label">
                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __('activity-logs.view.became') }}</span>
                            </div>
                            <div class="change-new-text">
                                @if($key === 'gallery' && is_array($newValue))
                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.75rem; margin-top: 0.5rem;">
                                        @foreach($newValue as $imageData)
                                            @php
                                                $imageName = is_array($imageData) ? ($imageData['file_name'] ?? '') : $imageData;
                                                $imageUrl = is_array($imageData) ? ($imageData['url'] ?? "/storage/{$imageName}") : "/storage/{$imageName}";
                                            @endphp
                                            <div style="position: relative;">
                                                <img src="{{ $imageUrl }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 0.5rem; border: 3px solid #22c55e;" alt="Added image">
                                                <div style="position: absolute; top: 0.25rem; right: 0.25rem; background: #22c55e; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700;">{{ mb_strtoupper(__('activity-logs.events.created')) }}</div>
                                                <div style="font-size: 0.625rem; margin-top: 0.25rem; word-break: break-all;">{{ $imageName }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($key === 'avatar' && is_array($newValue))
                                    @php
                                        $avatarUrl = is_array($newValue) ? ($newValue['url'] ?? '') : $newValue;
                                    @endphp
                                    <div style="margin-top: 0.5rem;">
                                        <img src="{{ $avatarUrl }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 4px solid #22c55e;" alt="New avatar">
                                    </div>
                                @elseif($key === 'widgets' && is_array($newValue))
                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.75rem; margin-top: 0.5rem;">
                                        @foreach($newValue as $widgetData)
                                            @php
                                                $widgetType = $widgetData['type'] ?? 'unknown';
                                                $widgetContent = $widgetData['content'] ?? '';
                                                $widgetUrl = '';
                                                if ($widgetType === 'youtube') {
                                                    $widgetUrl = 'https://www.youtube.com/watch?v=' . $widgetContent;
                                                } elseif ($widgetType === 'instagram') {
                                                    $widgetUrl = 'https://www.instagram.com/p/' . $widgetContent . '/';
                                                } elseif ($widgetType === 'okru') {
                                                    $widgetUrl = 'https://ok.ru/video/' . $widgetContent;
                                                }
                                            @endphp
                                            <div>
                                                <div style="position: relative; background: rgba(0,0,0,0.05); border-radius: 0.5rem; overflow: hidden; border: 3px solid #22c55e;">
                                                    @if($widgetType === 'youtube')
                                                        <img src="https://img.youtube.com/vi/{{ $widgetContent }}/maxresdefault.jpg" style="width: 100%; height: 150px; object-fit: cover;" alt="YouTube">
                                                        <div style="position: absolute; bottom: 0.5rem; left: 0.5rem; background: rgba(255,0,0,0.9); color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700;">▶ YouTube</div>
                                                    @elseif($widgetType === 'instagram')
                                                        <div style="width: 100%; height: 150px; display: flex; align-items: center; justify-content: center; background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);">
                                                            <svg style="width: 4rem; height: 4rem; color: white;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                                        </div>
                                                        <div style="position: absolute; bottom: 0.5rem; left: 0.5rem; background: rgba(0,0,0,0.7); color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700;">Instagram</div>
                                                    @elseif($widgetType === 'okru')
                                                        <div style="width: 100%; height: 150px; display: flex; align-items: center; justify-content: center; background: #ee8208;">
                                                            <svg style="width: 4rem; height: 4rem; color: white;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.6 0 12 0zm0 2.5c1.9 0 3.5 1.6 3.5 3.5s-1.6 3.5-3.5 3.5-3.5-1.6-3.5-3.5 1.6-3.5 3.5-3.5zm0 16.5c-2.8 0-5.3-1.5-6.7-3.7l2.5-1.4c1 1.6 2.8 2.6 4.7 2.6s3.7-1 4.7-2.6l2.5 1.4c-1.4 2.2-3.9 3.7-7.2 3.7z"/></svg>
                                                        </div>
                                                        <div style="position: absolute; bottom: 0.5rem; left: 0.5rem; background: rgba(0,0,0,0.7); color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700;">OK.ru</div>
                                                    @else
                                                        <div style="width: 100%; height: 150px; display: flex; align-items: center; justify-content: center; background: #6b7280; color: white; font-size: 0.875rem;">{{ strtoupper($widgetType) }}</div>
                                                    @endif
                                                    <div style="position: absolute; top: 0.25rem; right: 0.25rem; background: #22c55e; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700;">{{ mb_strtoupper(__('activity-logs.events.created')) }}</div>
                                                </div>
                                                @if($widgetUrl)
                                                    <a href="{{ $widgetUrl }}" target="_blank" rel="noopener noreferrer" style="display: block; margin-top: 0.5rem; font-size: 0.75rem; color: #3b82f6; text-decoration: none; word-break: break-all;">
                                                        <svg style="width: 0.875rem; height: 0.875rem; display: inline-block; vertical-align: middle; margin-right: 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                        </svg>
                                                        {{ $widgetUrl }}
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif(is_array($newValue) || is_object($newValue))
                                    <pre style="font-size: 0.8125rem; white-space: pre-wrap; font-family: monospace; margin: 0;">{{ json_encode($newValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @elseif(is_bool($newValue))
                                    {{ $newValue ? __('activity-logs.yes') : __('activity-logs.no') }}
                                @elseif(is_string($newValue) && filter_var($newValue, FILTER_VALIDATE_URL) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $newValue))
                                    <img src="{{ $newValue }}" class="change-image" alt="New image">
                                    <div style="font-size: 0.75rem; margin-top: 0.5rem; opacity: 0.7;">{{ $newValue }}</div>
                                @else
                                    {{ $newValue }}
                                @endif
                            </div>
                        </div>
                    @endif
                    @endif
                </div>
            @endif
        @endforeach
    </div>
@endif
