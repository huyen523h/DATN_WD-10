@props(['value', 'data'])

@php
// Extract guests data from value
if (is_array($value)) {
    $adults = $value['adults'] ?? 0;
    $children = $value['children'] ?? 0;
    $infants = $value['infants'] ?? 0;
} else {
    $adults = 0;
    $children = 0;
    $infants = 0;
}

$total = $adults + $children + $infants;
@endphp

<div class="guests-cell">
    <div class="guests-summary">
        <div class="total-guests">
            <i class="fas fa-users"></i>
            <span>{{ $total }} khách</span>
        </div>
    </div>
    
    <div class="guests-breakdown">
        @if($adults > 0)
        <div class="guest-item adults">
            <i class="fas fa-user"></i>
            <span>{{ $adults }} người lớn</span>
        </div>
        @endif
        
        @if($children > 0)
        <div class="guest-item children">
            <i class="fas fa-child"></i>
            <span>{{ $children }} trẻ em</span>
        </div>
        @endif
        
        @if($infants > 0)
        <div class="guest-item infants">
            <i class="fas fa-baby"></i>
            <span>{{ $infants }} em bé</span>
        </div>
        @endif
    </div>
</div>

<style>
.guests-cell {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.guests-summary {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.total-guests {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.total-guests i {
    color: #3b82f6;
    font-size: 0.875rem;
}

.guests-breakdown {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.guest-item {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.75rem;
    color: #6b7280;
}

.guest-item i {
    width: 12px;
    text-align: center;
    font-size: 0.625rem;
}

.guest-item.adults i {
    color: #3b82f6;
}

.guest-item.children i {
    color: #10b981;
}

.guest-item.infants i {
    color: #f59e0b;
}

/* Hover effects */
.guests-cell:hover .total-guests {
    color: #3b82f6;
}

.guests-cell:hover .total-guests i {
    transform: scale(1.1);
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .total-guests {
        color: #e5e7eb;
    }
    
    .guest-item {
        color: #9ca3af;
    }
    
    .guests-cell:hover .total-guests {
        color: #60a5fa;
    }
}
</style>
