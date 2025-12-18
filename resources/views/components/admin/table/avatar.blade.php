@props(['value', 'data'])

@php
// Extract customer data from value
if (is_array($value)) {
    $name = $value['name'] ?? '';
    $email = $value['email'] ?? '';
    $image = $value['image'] ?? null;
} else {
    $name = $value ?? '';
    $email = '';
    $image = null;
}

$size = 'md';
$sizeClasses = [
    'sm' => 'w-8 h-8 text-xs',
    'md' => 'w-10 h-10 text-sm',
    'lg' => 'w-12 h-12 text-base',
    'xl' => 'w-16 h-16 text-lg'
];

$sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
$initials = strtoupper(substr($name, 0, 2));
@endphp

<div class="user-avatar {{ $sizeClass }}" data-user="{{ $name }}">
    @if($image)
        <img src="{{ $image }}" alt="{{ $name }}" class="avatar-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
    @endif
    <div class="avatar-fallback" style="{{ $image ? 'display: none;' : '' }}">
        {{ $initials }}
    </div>
    @if($email)
        <div class="avatar-tooltip">
            <div class="tooltip-name">{{ $name }}</div>
            <div class="tooltip-email">{{ $email }}</div>
        </div>
    @endif
</div>

<style>
.user-avatar {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
}

.user-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.avatar-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
}

.avatar-tooltip {
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: #1f2937;
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 50;
    margin-bottom: 0.5rem;
}

.avatar-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 4px solid transparent;
    border-top-color: #1f2937;
}

.user-avatar:hover .avatar-tooltip {
    opacity: 1;
    visibility: visible;
}

.tooltip-name {
    font-weight: 600;
    margin-bottom: 0.125rem;
}

.tooltip-email {
    color: #9ca3af;
    font-size: 0.625rem;
}

/* Size variations */
.w-8 { width: 2rem; height: 2rem; }
.w-10 { width: 2.5rem; height: 2.5rem; }
.w-12 { width: 3rem; height: 3rem; }
.w-16 { width: 4rem; height: 4rem; }

.text-xs { font-size: 0.75rem; }
.text-sm { font-size: 0.875rem; }
.text-base { font-size: 1rem; }
.text-lg { font-size: 1.125rem; }
</style>
