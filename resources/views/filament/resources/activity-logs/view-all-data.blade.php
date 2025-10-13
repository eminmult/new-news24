@php
    $properties = $record->properties ?? [];
    $data = $properties['new'] ?? $properties['old'] ?? [];
    $isDeleted = $record->event === 'deleted';
    $isCreated = $record->event === 'created';

    // Определяем, какие данные показываем
    $displayData = [];
    if ($isDeleted && isset($properties['old'])) {
        $displayData = $properties['old'];
    } elseif ($isCreated && isset($properties['new'])) {
        $displayData = $properties['new'];
    } else {
        $displayData = $data;
    }
@endphp

<style>
    .data-content {
        background: #ffffff;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
    }
    .dark .data-content {
        background: rgba(17, 24, 39, 0.5);
        border-color: rgba(255,255,255,0.1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    .data-header {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    .dark .data-header {
        color: #f9fafb;
        border-bottom-color: rgba(255,255,255,0.1);
    }
    .data-item {
        margin-bottom: 1.5rem;
    }
    .data-item:last-child {
        margin-bottom: 0;
    }
    .data-field-name {
        font-size: 0.8125rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        margin-bottom: 0.75rem;
    }
    .dark .data-field-name {
        color: #9ca3af;
    }
    .data-value-block {
        background: #fee2e2;
        border-left: 4px solid #ef4444;
        padding: 0.875rem;
        border-radius: 0.5rem;
    }
    .dark .data-value-block {
        background: rgba(239, 68, 68, 0.15);
        border-left-color: #ef4444;
    }
    .data-value-block.created {
        background: #dcfce7;
        border-left-color: #22c55e;
    }
    .dark .data-value-block.created {
        background: rgba(34, 197, 94, 0.15);
        border-left-color: #22c55e;
    }
    .data-value-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #991b1b;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .dark .data-value-label {
        color: #fca5a5;
    }
    .data-value-label.created {
        color: #166534;
    }
    .dark .data-value-label.created {
        color: #86efac;
    }
    .data-value-text {
        font-size: 0.875rem;
        color: #7f1d1d;
        word-break: break-word;
    }
    .dark .data-value-text {
        color: #fecaca;
    }
    .data-value-text.created {
        color: #14532d;
    }
    .dark .data-value-text.created {
        color: #bbf7d0;
    }
</style>

@if(empty($displayData))
    <div style="font-size: 0.875rem; color: #6b7280;">
        {{ __('activity-logs.no_data') }}
    </div>
@else
    <div class="data-content">
        <div class="data-header">
            <svg style="width: 1.25rem; height: 1.25rem; display: inline-block; margin-right: 0.5rem; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if($isDeleted)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                @elseif($isCreated)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                @endif
            </svg>
            @if($isDeleted)
                {{ __('activity-logs.view.deleted_data') }}
            @elseif($isCreated)
                {{ __('activity-logs.view.created_data') }}
            @else
                {{ __('activity-logs.view.all_data') }}
            @endif
        </div>

        @foreach($displayData as $key => $value)
            <div class="data-item">
                <div class="data-field-name">
                    @php
                        $displayName = __('activity-logs.fields.' . $key);
                        if ($displayName === 'activity-logs.fields.' . $key) {
                            // Если перевод не найден, используем форматированный ключ
                            $displayName = ucfirst(str_replace('_', ' ', $key));
                        }
                    @endphp
                    {{ $displayName }}
                </div>

                <div class="data-value-block {{ $isCreated ? 'created' : '' }}">
                    <div class="data-value-label {{ $isCreated ? 'created' : '' }}">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($isDeleted)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            @elseif($isCreated)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            @endif
                        </svg>
                        <span>{{ $isDeleted ? __('activity-logs.view.deleted') : ($isCreated ? __('activity-logs.view.created') : __('activity-logs.view.value')) }}</span>
                    </div>
                    <div class="data-value-text {{ $isCreated ? 'created' : '' }}">
                        @if($key === 'gallery' && is_array($value))
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.75rem; margin-top: 0.5rem;">
                                @foreach($value as $imageData)
                                    @php
                                        $imageName = is_array($imageData) ? ($imageData['file_name'] ?? '') : $imageData;
                                        $imageUrl = is_array($imageData) ? ($imageData['url'] ?? "/storage/{$imageName}") : "/storage/{$imageName}";
                                    @endphp
                                    <div style="position: relative;">
                                        <img src="{{ $imageUrl }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 0.5rem; border: 3px solid {{ $isDeleted ? '#ef4444' : '#22c55e' }};" alt="Image">
                                        <div style="position: absolute; top: 0.25rem; right: 0.25rem; background: {{ $isDeleted ? '#ef4444' : '#22c55e' }}; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700;">{{ $isDeleted ? mb_strtoupper(__('activity-logs.events.deleted')) : mb_strtoupper(__('activity-logs.events.created')) }}</div>
                                        <div style="font-size: 0.625rem; margin-top: 0.25rem; word-break: break-all;">{{ $imageName }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($key === 'avatar' && is_array($value))
                            @php
                                $avatarUrl = is_array($value) ? ($value['url'] ?? '') : $value;
                            @endphp
                            <div style="margin-top: 0.5rem;">
                                <img src="{{ $avatarUrl }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 4px solid {{ $isDeleted ? '#ef4444' : '#22c55e' }};" alt="Avatar">
                            </div>
                        @elseif($key === 'widgets' && is_array($value))
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.75rem; margin-top: 0.5rem;">
                                @foreach($value as $widgetData)
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
                                        <div style="position: relative; background: rgba(0,0,0,0.05); border-radius: 0.5rem; overflow: hidden; border: 3px solid {{ $isDeleted ? '#ef4444' : '#22c55e' }};">
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
                                            <div style="position: absolute; top: 0.25rem; right: 0.25rem; background: {{ $isDeleted ? '#ef4444' : '#22c55e' }}; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700;">{{ $isDeleted ? mb_strtoupper(__('activity-logs.events.deleted')) : mb_strtoupper(__('activity-logs.events.created')) }}</div>
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
                        @elseif(is_array($value) || is_object($value))
                            <pre style="font-size: 0.8125rem; white-space: pre-wrap; font-family: monospace; margin: 0;">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        @elseif(is_bool($value))
                            {{ $value ? __('activity-logs.yes') : __('activity-logs.no') }}
                        @elseif(is_string($value) && filter_var($value, FILTER_VALIDATE_URL) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $value))
                            <img src="{{ $value }}" style="max-width: 200px; max-height: 128px; border-radius: 0.5rem; margin-top: 0.5rem; border: 1px solid rgba(0,0,0,0.1);" alt="Image">
                            <div style="font-size: 0.75rem; margin-top: 0.5rem; opacity: 0.7;">{{ $value }}</div>
                        @elseif(is_null($value))
                            <span style="opacity: 0.5; font-style: italic;">{{ __('activity-logs.empty') }}</span>
                        @else
                            {{ $value }}
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
