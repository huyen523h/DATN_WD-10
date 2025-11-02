@props(['value', 'data'])

@php
// Extract status from value
$status = is_array($value) ? ($value['status'] ?? $value) : $value;
$type = 'default';

$statusConfig = [
    'active' => ['class' => 'badge-success', 'icon' => 'fas fa-check-circle', 'text' => 'Hoạt động'],
    'inactive' => ['class' => 'badge-secondary', 'icon' => 'fas fa-pause-circle', 'text' => 'Không hoạt động'],
    'draft' => ['class' => 'badge-warning', 'icon' => 'fas fa-edit', 'text' => 'Bản nháp'],
    'pending' => ['class' => 'badge-warning', 'icon' => 'fas fa-clock', 'text' => 'Chờ xử lý'],
    'confirmed' => ['class' => 'badge-success', 'icon' => 'fas fa-check', 'text' => 'Đã xác nhận'],
    'cancelled' => ['class' => 'badge-danger', 'icon' => 'fas fa-times', 'text' => 'Đã hủy'],
    'completed' => ['class' => 'badge-info', 'icon' => 'fas fa-flag-checkered', 'text' => 'Hoàn thành'],
    'paid' => ['class' => 'badge-success', 'icon' => 'fas fa-credit-card', 'text' => 'Đã thanh toán'],
    'unpaid' => ['class' => 'badge-danger', 'icon' => 'fas fa-exclamation-triangle', 'text' => 'Chưa thanh toán'],
];

$config = $statusConfig[$status] ?? ['class' => 'badge-secondary', 'icon' => 'fas fa-circle', 'text' => ucfirst($status)];
@endphp

<span class="status-badge {{ $config['class'] }}" data-status="{{ $status }}">
    <i class="{{ $config['icon'] }}"></i>
    <span>{{ $config['text'] }}</span>
</span>

<style>
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
    transition: all 0.3s ease;
}

.status-badge i {
    font-size: 0.625rem;
}

.badge-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.badge-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.badge-info {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}

.badge-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.status-badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
