@props(['value', 'data'])

@php
// Extract date from value
$date = is_array($value) ? ($value['date'] ?? $value) : $value;
$format = 'd/m/Y';
$showTime = false;
$relative = false;

try {
    $dateObj = is_string($date) ? \Carbon\Carbon::parse($date) : $date;
    $formattedDate = $dateObj->format($format);
    $relativeDate = $dateObj->diffForHumans();
    $isoDate = $dateObj->toISOString();
} catch (Exception $e) {
    $dateObj = now();
    $formattedDate = 'N/A';
    $relativeDate = 'N/A';
    $isoDate = now()->toISOString();
}
@endphp

<div class="date-container" data-date="{{ $isoDate }}" title="{{ $dateObj->format('d/m/Y H:i:s') }}">
    <div class="date-display">
        <i class="fas fa-calendar-alt date-icon"></i>
        <span class="date-text">{{ $formattedDate }}</span>
    </div>
    
    @if($showTime)
        <div class="time-display">
            <i class="fas fa-clock time-icon"></i>
            <span class="time-text">{{ $dateObj->format('H:i') }}</span>
        </div>
    @endif
    
    @if($relative)
        <div class="relative-date">
            {{ $relativeDate }}
        </div>
    @endif
</div>

<style>
.date-container {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    font-size: 0.875rem;
}

.date-display,
.time-display {
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.date-icon,
.time-icon {
    color: #6b7280;
    font-size: 0.75rem;
    width: 12px;
    text-align: center;
}

.date-text {
    color: #374151;
    font-weight: 500;
}

.time-text {
    color: #6b7280;
    font-size: 0.75rem;
}

.relative-date {
    color: #9ca3af;
    font-size: 0.75rem;
    font-style: italic;
}

/* Hover effects */
.date-container:hover .date-text {
    color: #1f2937;
}

.date-container:hover .date-icon,
.date-container:hover .time-icon {
    color: #3b82f6;
}

/* Status indicators based on date */
.date-container[data-date] {
    position: relative;
}

.date-container[data-date]::before {
    content: '';
    position: absolute;
    left: -0.5rem;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 100%;
    border-radius: 2px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

/* Past dates - red indicator */
.date-container[data-date].past::before {
    background: #ef4444;
    opacity: 0.6;
}

/* Today - green indicator */
.date-container[data-date].today::before {
    background: #10b981;
    opacity: 0.6;
}

/* Future dates - blue indicator */
.date-container[data-date].future::before {
    background: #3b82f6;
    opacity: 0.6;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .date-text {
        color: #e5e7eb;
    }
    
    .time-text {
        color: #9ca3af;
    }
    
    .relative-date {
        color: #6b7280;
    }
    
    .date-icon,
    .time-icon {
        color: #9ca3af;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add status classes based on date
    const dateContainers = document.querySelectorAll('.date-container[data-date]');
    
    dateContainers.forEach(container => {
        const dateStr = container.dataset.date;
        const date = new Date(dateStr);
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const dateOnly = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        
        if (dateOnly < today) {
            container.classList.add('past');
        } else if (dateOnly.getTime() === today.getTime()) {
            container.classList.add('today');
        } else {
            container.classList.add('future');
        }
    });
});
</script>
