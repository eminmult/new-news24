<style>
    .activity-timeline {
        position: relative;
    }
    .activity-item {
        position: relative;
        margin-bottom: 2rem;
    }
    .activity-content {
        background: #ffffff;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
    }
    .dark .activity-content {
        background: rgba(17, 24, 39, 0.5);
        border-color: rgba(255,255,255,0.1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    .dark .activity-header {
        border-bottom-color: rgba(255,255,255,0.1);
    }
    .activity-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.25rem;
    }
    .dark .activity-title {
        color: #f9fafb;
    }
    .activity-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .dark .activity-subtitle {
        color: #9ca3af;
    }
    .activity-time {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8125rem;
        color: #6b7280;
        white-space: nowrap;
    }
    .dark .activity-time {
        color: #9ca3af;
    }
    .activity-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    .activity-meta-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        background: #f9fafb;
        border-radius: 0.5rem;
    }
    .dark .activity-meta-item {
        background: rgba(255,255,255,0.05);
    }
    .activity-meta-icon {
        width: 1.25rem;
        height: 1.25rem;
        color: #6b7280;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }
    .dark .activity-meta-icon {
        color: #9ca3af;
    }
    .activity-meta-content {
        flex: 1;
        min-width: 0;
    }
    .activity-meta-label {
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }
    .dark .activity-meta-label {
        color: #9ca3af;
    }
    .activity-meta-value {
        font-size: 0.875rem;
        color: #111827;
        word-break: break-word;
    }
    .dark .activity-meta-value {
        color: #f9fafb;
    }
    .activity-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 600;
    }
    .activity-badge-created {
        background: #dcfce7;
        color: #166534;
    }
    .dark .activity-badge-created {
        background: rgba(34, 197, 94, 0.2);
        color: #86efac;
    }
    .activity-badge-updated {
        background: #fef3c7;
        color: #92400e;
    }
    .dark .activity-badge-updated {
        background: rgba(251, 191, 36, 0.2);
        color: #fcd34d;
    }
    .activity-badge-deleted {
        background: #fee2e2;
        color: #991b1b;
    }
    .dark .activity-badge-deleted {
        background: rgba(239, 68, 68, 0.2);
        color: #fca5a5;
    }
    .activity-badge-restored {
        background: #dbeafe;
        color: #1e40af;
    }
    .dark .activity-badge-restored {
        background: rgba(59, 130, 246, 0.2);
        color: #93c5fd;
    }
</style>

<div class="activity-timeline">
    <div class="activity-item">
        @php
            $eventColors = [
                'created' => ['bg' => '#22c55e', 'class' => 'activity-badge-created'],
                'updated' => ['bg' => '#f59e0b', 'class' => 'activity-badge-updated'],
                'deleted' => ['bg' => '#ef4444', 'class' => 'activity-badge-deleted'],
                'restored' => ['bg' => '#3b82f6', 'class' => 'activity-badge-restored'],
            ];
            $eventColor = $eventColors[$record->event] ?? ['bg' => '#6b7280', 'class' => 'activity-badge-updated'];
        @endphp

        <div class="activity-content">
            <div class="activity-header">
                <div style="flex: 1;">
                    <div class="activity-title">{{ $record->description }}</div>
                    <div class="activity-subtitle">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>{{ $record->causer?->name ?? __('activity-logs.system') }}</span>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                    <span class="activity-badge {{ $eventColor['class'] }}">
                        {{ __('activity-logs.events.' . $record->event) }}
                    </span>
                    <div class="activity-time">
                        <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $record->created_at?->format('d.m.Y H:i:s') }}
                    </div>
                    <div style="font-size: 0.75rem; color: #9ca3af;">
                        {{ $record->created_at?->diffForHumans() }}
                    </div>
                </div>
            </div>

            <div class="activity-meta-grid">
                <div class="activity-meta-item">
                    <svg class="activity-meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                    <div class="activity-meta-content">
                        <div class="activity-meta-label">{{ __('activity-logs.table.columns.section') }}</div>
                        <div class="activity-meta-value">{{ $record->log_name ? __('activity-logs.sections.' . $record->log_name) : '-' }}</div>
                    </div>
                </div>

                <div class="activity-meta-item">
                    <svg class="activity-meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <div class="activity-meta-content">
                        <div class="activity-meta-label">{{ __('activity-logs.table.columns.model') }}</div>
                        <div class="activity-meta-value">{{ $record->subject_type ? __('activity-logs.models.' . class_basename($record->subject_type)) : '-' }}</div>
                    </div>
                </div>

                <div class="activity-meta-item">
                    <svg class="activity-meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                    </svg>
                    <div class="activity-meta-content">
                        <div class="activity-meta-label">{{ __('activity-logs.table.columns.subject_id') }}</div>
                        <div class="activity-meta-value" style="font-family: monospace; font-weight: 600;">{{ $record->subject_id ?? '-' }}</div>
                    </div>
                </div>

                <div class="activity-meta-item">
                    <svg class="activity-meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                    <div class="activity-meta-content">
                        <div class="activity-meta-label">{{ __('activity-logs.table.columns.ip_address') }}</div>
                        <div class="activity-meta-value" style="font-family: monospace;">{{ $record->ip_address ?? '-' }}</div>
                    </div>
                </div>

                @if($record->user_agent)
                <div class="activity-meta-item" style="grid-column: 1 / -1;">
                    <svg class="activity-meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <div class="activity-meta-content">
                        <div class="activity-meta-label">{{ __('activity-logs.table.columns.user_agent') }}</div>
                        <div class="activity-meta-value" style="font-family: monospace; font-size: 0.8125rem; opacity: 0.8;">{{ $record->user_agent }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
