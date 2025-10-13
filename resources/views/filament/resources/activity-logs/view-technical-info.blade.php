<style>
    .activity-log-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .activity-log-value {
        font-size: 0.875rem;
        color: #111827;
        padding: 0.75rem;
        background: #f9fafb;
        border-radius: 0.5rem;
        border-left: 3px solid #e5e7eb;
    }
    .dark .activity-log-label {
        color: #9ca3af;
    }
    .dark .activity-log-value {
        color: #f9fafb;
        background: rgba(255, 255, 255, 0.05);
        border-left-color: rgba(255, 255, 255, 0.1);
    }
    .activity-log-icon {
        width: 1rem;
        height: 1rem;
        opacity: 0.7;
    }
</style>

<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
    <div>
        <div class="activity-log-label">
            <svg class="activity-log-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
            </svg>
            {{ __('activity-logs.table.columns.ip_address') }}
        </div>
        <div class="activity-log-value" style="border-left-color: #14b8a6; font-family: monospace;">
            {{ $record->ip_address ?? '-' }}
        </div>
    </div>

    <div>
        <div class="activity-log-label">
            <svg class="activity-log-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
            </svg>
            {{ __('activity-logs.table.columns.subject_id') }}
        </div>
        <div class="activity-log-value" style="border-left-color: #a855f7; font-family: monospace; font-weight: 600;">
            {{ $record->subject_id ?? '-' }}
        </div>
    </div>

    <div style="grid-column: span 2;">
        <div class="activity-log-label">
            <svg class="activity-log-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            {{ __('activity-logs.table.columns.user_agent') }}
        </div>
        <div class="activity-log-value" style="border-left-color: #f97316; word-break: break-all; font-family: monospace; font-size: 0.8125rem;">
            {{ $record->user_agent ?? '-' }}
        </div>
    </div>
</div>
