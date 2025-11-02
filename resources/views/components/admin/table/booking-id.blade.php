@props(['value', 'data'])

@php
// Extract booking data from value
if (is_array($value)) {
    $id = $value['id'] ?? '';
    $type = $value['type'] ?? 'booking';
} else {
    $id = $value ?? '';
    $type = 'booking';
}
@endphp

<div class="booking-id-cell">
    <div class="booking-icon">
        <i class="fas fa-receipt"></i>
    </div>
    <div class="booking-info">
        <div class="booking-number">#{{ $id }}</div>
        <div class="booking-type">{{ ucfirst($type) }}</div>
    </div>
</div>

<style>
.booking-id-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.booking-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.booking-info {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.booking-number {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
    line-height: 1.2;
}

.booking-type {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

/* Hover effects */
.booking-id-cell:hover .booking-icon {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.booking-id-cell:hover .booking-number {
    color: #3b82f6;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .booking-number {
        color: #e5e7eb;
    }
    
    .booking-type {
        color: #9ca3af;
    }
    
    .booking-id-cell:hover .booking-number {
        color: #60a5fa;
    }
}
</style>
