@extends('layouts.admin')

@section('title', 'Nhật ký tour')

@section('content')
<div class="container">

    <h2 class="fw-bold mb-4">
        <i class="fas fa-book text-primary me-2"></i>
        Nhật ký tour
    </h2>

    <div class="text-end mb-3">
        <a href="{{ route('admin.tour-logs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm nhật ký
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Ngày ghi</th>
                        <th>Tour</th>
                        <th>Khởi hành</th>
                        <th>Người ghi</th>
                        <th>Loại</th>
                        <th>Nội dung</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->log_date->format('d/m/Y') }}</td>

                        <td>{{ $log->departure->tour->title ?? 'N/A' }}</td>

                        <td>
                            {{ $log->departure->departure_date?->format('d/m/Y') ?? '—' }}
                        </td>

                        <td>{{ $log->guide->name ?? 'Hệ thống' }}</td>

                        <td>
                            <span class="badge bg-info">{{ strtoupper($log->type) }}</span>
                        </td>

                        <td class="text-truncate" style="max-width: 250px;">
                            {{ Str::limit($log->content, 50) }}
                        </td>

                        <td class="text-center">
                            <a href="{{ route('admin.tour-logs.show', $log->id) }}"
                                class="btn btn-outline-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('admin.tour-logs.edit', $log->id) }}"
                                class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.tour-logs.destroy', $log->id) }}"
                                  class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Xóa nhật ký này?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $logs->links() }}
    </div>
</div>
@endsection
