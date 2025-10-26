@props(['value', 'data'])

@php
// Extract tour data from value
if (is_array($value)) {
    $title = $value['title'] ?? '';
    $description = $value['description'] ?? '';
    $image = $value['image'] ?? null;
} else {
    $title = $value ?? '';
    $description = '';
    $image = null;
}
@endphp

<div class="tour-title-cell">
    <div class="tour-image">
        @if($image)
            <img src="{{ $image }}" alt="{{ $title }}" class="tour-image-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        @endif
        <div class="tour-image-fallback" style="{{ $image ? 'display: none;' : '' }}">
            <i class="fas fa-image"></i>
        </div>
    </div>
    
    <div class="tour-info">
        <div class="tour-name">{{ $title }}</div>
        @if($description)
            <div class="tour-description">{{ $description }}</div>
        @endif
    </div>
</div>

<style>
.tour-title-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 0;
}

.tour-image {
    width: 50px;
    height: 50px;
    border-radius: 0.5rem;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 1.25rem;
    flex-shrink: 0;
    overflow: hidden;
    position: relative;
}

.tour-image-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 0.5rem;
}

.tour-image-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
    color: #9ca3af;
}

.tour-info {
    flex: 1;
    min-width: 0;
}

.tour-name {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.tour-description {
    font-size: 0.75rem;
    color: #6b7280;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Hover effects */
.tour-title-cell:hover .tour-name {
    color: #3b82f6;
}

.tour-title-cell:hover .tour-image {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .tour-image {
        background: #374151;
    }
    
    .tour-image-fallback {
        background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
        color: #9ca3af;
    }
    
    .tour-name {
        color: #e5e7eb;
    }
    
    .tour-description {
        color: #9ca3af;
    }
    
    .tour-title-cell:hover .tour-name {
        color: #60a5fa;
    }
}
</style>
