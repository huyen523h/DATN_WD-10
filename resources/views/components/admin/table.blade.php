@props([
    'headers' => [],
    'data' => [],
    'actions' => [],
    'searchable' => false,
    'sortable' => false,
    'filterable' => false,
    'pagination' => null,
    'emptyMessage' => 'Không có dữ liệu',
    'loading' => false,
    'id' => 'admin-table',
])

<div class="modern-table-container" id="{{ $id }}">
    <!-- Table Header with Search and Filters -->
    @if ($searchable || $filterable)
        <div class="table-header-actions">
            <div class="table-controls">
                @if ($searchable)
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="Tìm kiếm..." data-table-search>
                    </div>
                @endif

                @if ($filterable)
                    <div class="filter-dropdown">
                        <button class="filter-btn" data-table-filter-toggle>
                            <i class="fas fa-filter"></i>
                            <span>Bộ lọc</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="filter-panel" data-table-filter-panel>
                            {{ $filters ?? '' }}
                        </div>
                    </div>
                @endif

                <div class="table-actions">
                    <button class="action-btn" data-table-refresh title="Làm mới">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button class="action-btn" data-table-export title="Xuất dữ liệu">
                        <i class="fas fa-download"></i>
                    </button>
                    <div class="view-options">
                        <button class="action-btn active" data-view="table" title="Xem dạng bảng">
                            <i class="fas fa-table"></i>
                        </button>
                        <button class="action-btn" data-view="grid" title="Xem dạng lưới">
                            <i class="fas fa-th"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading State -->
    @if ($loading)
        <div class="table-loading">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            <p>Đang tải dữ liệu...</p>
        </div>
    @endif

    <!-- Table Content -->
    <div class="table-wrapper" data-table-wrapper>
        <div class="table-responsive">
            <table class="modern-table" data-table>
                <thead>
                    <tr>
                        @foreach ($headers as $header)
                            <th class="table-header {{ $header['sortable'] ?? false ? 'sortable' : '' }}"
                                data-column="{{ $header['key'] ?? '' }}">
                                <div class="header-content">
                                    <span class="header-text">{{ $header['label'] ?? $header }}</span>
                                    @if (($header['sortable'] ?? false) && $sortable)
                                        <div class="sort-indicators">
                                            <i class="fas fa-sort-up sort-up"></i>
                                            <i class="fas fa-sort-down sort-down"></i>
                                        </div>
                                    @endif
                                </div>
                            </th>
                        @endforeach

                        @if (!empty($actions))
                            <th class="table-header actions-header">
                                <span class="header-text">Thao tác</span>
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody data-table-body>
                    @forelse($data as $index => $row)
                        <tr class="table-row" data-row-index="{{ $index }}">
                            @foreach ($headers as $header)
                                <td class="table-cell" data-column="{{ $header['key'] ?? '' }}">
                                    @if (isset($header['component']))
                                        <x-dynamic-component :component="$header['component']" :data="$row" :value="data_get($row, $header['key'] ?? '')" />
                                    @else
                                        <div class="cell-content">
                                            @php
                                                $value = data_get($row, $header['key'] ?? $header);
                                            @endphp
                                            @if (is_array($value))
                                                @if (isset($value['title']) && isset($value['description']))
                                                    <div class="simple-title">
                                                        <div class="title-text">{{ $value['title'] }}</div>
                                                        @if (isset($value['description']))
                                                            <div class="description-text">{{ $value['description'] }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    {{ json_encode($value) }}
                                                @endif
                                            @else
                                                {{ $value }}
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            @endforeach

                            @if (!empty($actions))
                                <td class="table-cell actions-cell">
                                    <div class="action-buttons">
                                        @foreach ($actions as $action)
                                            @if ($action['condition'] ?? true)
                                                @php
                                                    $idValue = is_array(data_get($row, 'id'))
                                                        ? data_get($row, 'id.id') ?? data_get($row, 'id')
                                                        : data_get($row, 'id');
                                                @endphp

                                                @if (!empty($action['custom']))
                                                    {{-- Custom route (ví dụ: admin.schedules.index) --}}
                                                    <a href="{{ route($action['route'], data_get($row, $action['param'] ?? 'id')) }}"
                                                        class="action-btn {{ $action['class'] ?? '' }}"
                                                        title="{{ $action['title'] ?? '' }}">
                                                        <i class="{{ $action['icon'] ?? 'fas fa-link' }}"></i>
                                                    </a>
                                                @else
                                                    {{-- Hành động mặc định: xem, sửa, xóa --}}
                                                    <button class="action-btn {{ $action['class'] ?? '' }}"
                                                        data-action="{{ $action['action'] ?? '' }}"
                                                        data-id="{{ $idValue }}"
                                                        title="{{ $action['title'] ?? '' }}">
                                                        <i class="{{ $action['icon'] ?? 'fas fa-ellipsis-h' }}"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="{{ count($headers) + (!empty($actions) ? 1 : 0) }}" class="empty-cell">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <h4>{{ $emptyMessage }}</h4>
                                    <p>Không có dữ liệu để hiển thị</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($pagination)
        <div class="table-pagination">
            <div class="pagination-info">
                <span>Hiển thị {{ $pagination->firstItem() ?? 0 }} - {{ $pagination->lastItem() ?? 0 }}
                    trong tổng số {{ $pagination->total() ?? 0 }} kết quả</span>
            </div>
            <div class="pagination-links">
                {{ $pagination->links() }}
            </div>
        </div>
    @endif
</div>

@push('styles')
    <style>
        /* Modern Table Styles */
        .modern-table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .table-header-actions {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .table-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            background: white;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 0.875rem;
        }

        .filter-dropdown {
            position: relative;
        }

        .filter-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn:hover {
            border-color: #3b82f6;
            background: #f8fafc;
        }

        .filter-panel {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            min-width: 300px;
            z-index: 50;
            display: none;
        }

        .filter-panel.show {
            display: block;
        }

        .table-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: auto;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #6b7280;
        }

        .action-btn:hover {
            background: #f3f4f6;
            border-color: #3b82f6;
            color: #3b82f6;
        }

        .action-btn.active {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .view-options {
            display: flex;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            overflow: hidden;
        }

        .view-options .action-btn {
            border: none;
            border-radius: 0;
            margin: 0;
        }

        .view-options .action-btn:first-child {
            border-right: 1px solid #d1d5db;
        }

        /* Table Loading */
        .table-loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }

        .loading-spinner {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #3b82f6;
        }

        /* Table Styles */
        .table-wrapper {
            overflow-x: auto;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .table-header {
            background: #f9fafb;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-header.sortable {
            cursor: pointer;
            user-select: none;
        }

        .table-header.sortable:hover {
            background: #f3f4f6;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
        }

        .sort-indicators {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .sort-indicators i {
            font-size: 0.75rem;
            color: #9ca3af;
            transition: color 0.3s ease;
        }

        .sort-indicators i.active {
            color: #3b82f6;
        }

        .table-row {
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }

        .table-row:hover {
            background: #f9fafb;
        }

        .table-cell {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
        }

        .cell-content {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .simple-title {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .title-text {
            font-weight: 600;
            color: #1f2937;
            font-size: 0.875rem;
        }

        .description-text {
            font-size: 0.75rem;
            color: #6b7280;
            line-height: 1.4;
        }

        /* Action Buttons */
        .actions-cell {
            text-align: right;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .action-buttons .action-btn {
            width: 32px;
            height: 32px;
            font-size: 0.75rem;
        }

        .action-buttons .action-btn.btn-primary {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .action-buttons .action-btn.btn-warning {
            background: #f59e0b;
            border-color: #f59e0b;
            color: white;
        }

        .action-buttons .action-btn.btn-danger {
            background: #ef4444;
            border-color: #ef4444;
            color: white;
        }

        /* Empty State */
        .empty-cell {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #6b7280;
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }

        .empty-state h4 {
            margin-bottom: 0.5rem;
            color: #374151;
        }

        /* Pagination */
        .table-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .pagination-info {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .pagination-links {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .table-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                min-width: auto;
            }

            .table-actions {
                margin-left: 0;
                justify-content: space-between;
            }

            .table-pagination {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }

            .action-buttons .action-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            .modern-table-container {
                background: #1f2937;
                color: #e5e7eb;
            }

            .table-header-actions {
                background: #111827;
                border-color: #374151;
            }

            .search-input,
            .filter-btn,
            .action-btn {
                background: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .table-header {
                background: #111827;
                color: #e5e7eb;
                border-color: #374151;
            }

            .table-row:hover {
                background: #111827;
            }

            .table-cell {
                border-color: #374151;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableId = '{{ $id }}';
            const tableContainer = document.getElementById(tableId);

            if (!tableContainer) return;

            // Search functionality
            const searchInput = tableContainer.querySelector('[data-table-search]');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        performSearch(this.value);
                    }, 300);
                });
            }
<<<<<<< HEAD
        });
    }
    
    // Sort functionality
    const sortableHeaders = tableContainer.querySelectorAll('.table-header.sortable');
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const column = this.dataset.column;
            const currentSort = this.dataset.sort || 'asc';
            const newSort = currentSort === 'asc' ? 'desc' : 'asc';
            
            // Update sort indicators
            sortableHeaders.forEach(h => {
                h.dataset.sort = '';
                h.querySelectorAll('.sort-indicators i').forEach(i => i.classList.remove('active'));
            });
            
            this.dataset.sort = newSort;
            const indicator = this.querySelector(`.sort-${newSort}`);
            if (indicator) indicator.classList.add('active');
            
            // Perform sort
            performSort(column, newSort);
        });
    });
    
    // View toggle
    const viewButtons = tableContainer.querySelectorAll('[data-view]');
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.dataset.view;
            viewButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Toggle view
            if (view === 'grid') {
                tableContainer.classList.add('grid-view');
            } else {
                tableContainer.classList.remove('grid-view');
            }
        });
    });
    
    // Action buttons - Only handle if no custom handler exists
    // Custom handlers should be defined in the page that uses this component
    const actionButtons = tableContainer.querySelectorAll('[data-action]');
    actionButtons.forEach(btn => {
        // Check if button already has onclick handler or custom listener
        if (!btn.hasAttribute('onclick')) {
            btn.addEventListener('click', function(e) {
                // Only handle if there's no custom handler on the page
                // This allows pages to override default behavior
                const action = this.dataset.action;
                const id = this.dataset.id;
                
                // If custom handlers exist, don't process here
                if (typeof window.handleTableAction === 'function') {
                    return; // Let custom handler process
                }
                
                // Default fallback handlers (only if no custom handler)
                switch(action) {
                    case 'view':
                        window.location.href = `/admin/tours/${id}`;
                        break;
                    case 'edit':
                        window.location.href = `/admin/tours/${id}/edit`;
                        break;
                    case 'delete':
                        if (confirm('Bạn có chắc chắn muốn xóa?')) {
                            // Handle delete
                            if (typeof handleDelete === 'function') {
                                handleDelete(id);
                            }
                        }
                        break;
                }
            });
        }
    });
    
    // Refresh button
    const refreshBtn = tableContainer.querySelector('[data-table-refresh]');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            location.reload();
        });
    }
    
    // Export button
    const exportBtn = tableContainer.querySelector('[data-table-export]');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            // Handle export
            handleExport();
        });
    }
    
    // Helper functions
    function performSearch(query) {
        const rows = tableContainer.querySelectorAll('.table-row');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matches = text.includes(query.toLowerCase());
            row.style.display = matches ? '' : 'none';
        });
    }
    
    function performSort(column, direction) {
        // This would typically make an AJAX request to the server
        // For now, we'll just show a loading state
        showLoading();
        
        // Simulate API call
        setTimeout(() => {
            hideLoading();
            // Reload page with new sort parameters
            const url = new URL(window.location);
            url.searchParams.set('sort', column);
            url.searchParams.set('direction', direction);
            window.location.href = url.toString();
        }, 1000);
    }
    
    function handleDelete(id) {
        showLoading();
        
        // Make DELETE request
        fetch(`/admin/tours/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
=======

            // Filter toggle
            const filterToggle = tableContainer.querySelector('[data-table-filter-toggle]');
            const filterPanel = tableContainer.querySelector('[data-table-filter-panel]');
            if (filterToggle && filterPanel) {
                filterToggle.addEventListener('click', function() {
                    filterPanel.classList.toggle('show');
                });

                // Close filter panel when clicking outside
                document.addEventListener('click', function(e) {
                    if (!filterToggle.contains(e.target) && !filterPanel.contains(e.target)) {
                        filterPanel.classList.remove('show');
                    }
                });
            }

            // Sort functionality
            const sortableHeaders = tableContainer.querySelectorAll('.table-header.sortable');
            sortableHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const column = this.dataset.column;
                    const currentSort = this.dataset.sort || 'asc';
                    const newSort = currentSort === 'asc' ? 'desc' : 'asc';

                    // Update sort indicators
                    sortableHeaders.forEach(h => {
                        h.dataset.sort = '';
                        h.querySelectorAll('.sort-indicators i').forEach(i => i.classList
                            .remove('active'));
                    });

                    this.dataset.sort = newSort;
                    const indicator = this.querySelector(`.sort-${newSort}`);
                    if (indicator) indicator.classList.add('active');

                    // Perform sort
                    performSort(column, newSort);
                });
            });

            // View toggle
            const viewButtons = tableContainer.querySelectorAll('[data-view]');
            viewButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const view = this.dataset.view;
                    viewButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Toggle view
                    if (view === 'grid') {
                        tableContainer.classList.add('grid-view');
                    } else {
                        tableContainer.classList.remove('grid-view');
                    }
                });
            });

            // Action buttons
            const actionButtons = tableContainer.querySelectorAll('.action-btn');
            actionButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const route = this.dataset.route;
                    const action = this.dataset.action;
                    const id = this.dataset.id;
                    //Nếu là custom route (có data-route)
                    if (route) {
                        window.location.href = route;
                        return;
                    }
                    //Nếu là action mặc định
                    switch (action) {
                        case 'view':
                            window.location.href = `/admin/tours/${id}`;
                            break;
                        case 'edit':
                            window.location.href = `/admin/tours/${id}/edit`;
                            break;
                        case 'delete':
                            if (confirm('Bạn có chắc chắn muốn xóa?')) {
                                handleDelete(id);
                            }
                            break;
                    }
                });
            });

            // Refresh button
            const refreshBtn = tableContainer.querySelector('[data-table-refresh]');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    location.reload();
                });
>>>>>>> huyennt51700
            }

            // Export button
            const exportBtn = tableContainer.querySelector('[data-table-export]');
            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    // Handle export
                    handleExport();
                });
            }

            // Helper functions
            function performSearch(query) {
                const rows = tableContainer.querySelectorAll('.table-row');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const matches = text.includes(query.toLowerCase());
                    row.style.display = matches ? '' : 'none';
                });
            }

            function performSort(column, direction) {
                // This would typically make an AJAX request to the server
                // For now, we'll just show a loading state
                showLoading();

                // Simulate API call
                setTimeout(() => {
                    hideLoading();
                    // Reload page with new sort parameters
                    const url = new URL(window.location);
                    url.searchParams.set('sort', column);
                    url.searchParams.set('direction', direction);
                    window.location.href = url.toString();
                }, 1000);
            }

            function handleDelete(id) {
                showLoading();

                // Make DELETE request
                fetch(`/admin/tours/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            // Remove row from table
                            const row = tableContainer.querySelector(`[data-id="${id}"]`).closest('.table-row');
                            row.remove();

                            // Show success message
                            showNotification('Xóa thành công!', 'success');
                        } else {
                            showNotification('Có lỗi xảy ra!', 'error');
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        showNotification('Có lỗi xảy ra!', 'error');
                    });
            }

            function handleExport() {
                // Handle export functionality
                showNotification('Đang xuất dữ liệu...', 'info');

                // Simulate export
                setTimeout(() => {
                    showNotification('Xuất dữ liệu thành công!', 'success');
                }, 2000);
            }

            function showLoading() {
                const loading = tableContainer.querySelector('.table-loading');
                if (loading) {
                    loading.style.display = 'flex';
                }
            }

            function hideLoading() {
                const loading = tableContainer.querySelector('.table-loading');
                if (loading) {
                    loading.style.display = 'none';
                }
            }

            function showNotification(message, type) {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `notification notification-${type}`;
                notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        `;

                // Add to page
                document.body.appendChild(notification);

                // Show notification
                setTimeout(() => notification.classList.add('show'), 100);

                // Remove notification
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }
        });
    </script>
@endpush
