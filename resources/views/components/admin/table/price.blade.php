@props(['value', 'data'])

@php
// Extract price data from value
if (is_array($value)) {
    $price = $value['price'] ?? 0;
    $originalPrice = $value['original_price'] ?? null;
} else {
    $price = $value ?? 0;
    $originalPrice = null;
}

$currency = 'VNĐ';
$size = 'md';

$sizeClasses = [
    'sm' => 'text-sm',
    'md' => 'text-base',
    'lg' => 'text-lg font-semibold',
    'xl' => 'text-xl font-bold'
];

$sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
$hasDiscount = $originalPrice && $originalPrice > $price;
$discountPercent = $hasDiscount ? round((($originalPrice - $price) / $originalPrice) * 100) : 0;
@endphp

<div class="price-container {{ $sizeClass }}" data-price="{{ $price }}">
    @if($hasDiscount)
        <div class="price-with-discount">
            <div class="current-price">
                {{ number_format($price, 0, ',', '.') }} {{ $currency }}
            </div>
            <div class="original-price">
                {{ number_format($originalPrice, 0, ',', '.') }} {{ $currency }}
            </div>
            <div class="discount-badge">
                -{{ $discountPercent }}%
            </div>
        </div>
    @else
        <div class="current-price">
            {{ number_format($price, 0, ',', '.') }} {{ $currency }}
        </div>
    @endif
</div>

<style>
.price-container {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.price-with-discount {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.current-price {
    color: #059669;
    font-weight: 600;
    line-height: 1.2;
}

.original-price {
    color: #6b7280;
    text-decoration: line-through;
    font-size: 0.875em;
    font-weight: 400;
}

.discount-badge {
    position: absolute;
    top: -0.5rem;
    right: -0.5rem;
    background: #ef4444;
    color: white;
    font-size: 0.625rem;
    font-weight: 600;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
    line-height: 1;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Size variations */
.text-sm { font-size: 0.875rem; }
.text-base { font-size: 1rem; }
.text-lg { font-size: 1.125rem; }
.text-xl { font-size: 1.25rem; }

.font-semibold { font-weight: 600; }
.font-bold { font-weight: 700; }

/* Hover effects */
.price-container:hover .current-price {
    color: #047857;
}

.price-container:hover .discount-badge {
    transform: scale(1.05);
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .current-price {
        color: #10b981;
    }
    
    .original-price {
        color: #9ca3af;
    }
    
    .discount-badge {
        background: #dc2626;
    }
}
</style>
