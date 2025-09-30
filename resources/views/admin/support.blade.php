@extends('layouts.admin')

@section('title', 'Hỗ trợ Khách hàng - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Hỗ trợ Khách hàng</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-headset text-primary"></i> Hỗ trợ Khách hàng</h2>
        <p class="text-muted mb-0">Quản lý các yêu cầu hỗ trợ từ khách hàng</p>
    </div>
</div>

<!-- Support Tickets Table -->
<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    Danh sách yêu cầu hỗ trợ ({{ $tickets->total() }} yêu cầu)
                </h6>
            </div>
            <div class="card-body">
                @if($tickets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã ticket</th>
                                    <th>Khách hàng</th>
                                    <th>Tiêu đề</th>
                                    <th>Loại</th>
                                    <th>Mức độ</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th width="150">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td>
                                            <code>#{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</code>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2">
                                                    {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $ticket->user->name }}</div>
                                                    <small class="text-muted">{{ $ticket->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $ticket->subject }}</div>
                                            <small class="text-muted">{{ Str::limit($ticket->description, 50) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($ticket->type === 'technical') bg-info
                                                @elseif($ticket->type === 'billing') bg-warning
                                                @elseif($ticket->type === 'general') bg-primary
                                                @else bg-secondary
                                                @endif">
                                                @switch($ticket->type)
                                                    @case('technical') Kỹ thuật @break
                                                    @case('billing') Thanh toán @break
                                                    @case('general') Chung @break
                                                    @default {{ $ticket->type }} @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($ticket->priority === 'high') bg-danger
                                                @elseif($ticket->priority === 'medium') bg-warning
                                                @elseif($ticket->priority === 'low') bg-success
                                                @else bg-secondary
                                                @endif">
                                                @switch($ticket->priority)
                                                    @case('high') Cao @break
                                                    @case('medium') Trung bình @break
                                                    @case('low') Thấp @break
                                                    @default {{ $ticket->priority }} @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($ticket->status === 'open') bg-warning
                                                @elseif($ticket->status === 'in_progress') bg-info
                                                @elseif($ticket->status === 'resolved') bg-success
                                                @elseif($ticket->status === 'closed') bg-secondary
                                                @else bg-light text-dark
                                                @endif">
                                                @switch($ticket->status)
                                                    @case('open') Mở @break
                                                    @case('in_progress') Đang xử lý @break
                                                    @case('resolved') Đã giải quyết @break
                                                    @case('closed') Đã đóng @break
                                                    @default {{ $ticket->status }} @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $ticket->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button class="btn btn-outline-info" onclick="viewTicket({{ $ticket->id }})" title="Xem">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-primary" onclick="replyTicket({{ $ticket->id }})" title="Trả lời">
                                                    <i class="fas fa-reply"></i>
                                                </button>
                                                <button class="btn btn-outline-success" onclick="resolveTicket({{ $ticket->id }})" title="Giải quyết">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Hiển thị {{ $tickets->firstItem() }} đến {{ $tickets->lastItem() }} 
                            trong tổng số {{ $tickets->total() }} yêu cầu
                        </div>
                        <div>
                            {{ $tickets->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-headset fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có yêu cầu hỗ trợ nào</h5>
                        <p class="text-muted">Khách hàng chưa gửi yêu cầu hỗ trợ nào</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.875rem;
}
</style>
@endsection

@section('scripts')
<script>
function viewTicket(ticketId) {
    // Implement view functionality
    console.log('View ticket:', ticketId);
}

function replyTicket(ticketId) {
    // Implement reply functionality
    console.log('Reply to ticket:', ticketId);
}

function resolveTicket(ticketId) {
    if (confirm('Bạn có chắc chắn muốn đánh dấu ticket này là đã giải quyết?')) {
        // Implement resolve functionality
        console.log('Resolve ticket:', ticketId);
    }
}
</script>
@endsection
