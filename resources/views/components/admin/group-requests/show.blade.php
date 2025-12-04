@extends('layouts.admin')

@section('title', 'Chi tiết Yêu cầu #' . $request->id)

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Chi tiết Yêu cầu #{{ $request->id }}</h1>
        <a href="{{ route('admin.group-requests.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin Khách hàng & Nhu cầu</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Họ tên</th>
                            <td>{{ $request->name }}</td>
                        </tr>
                        <tr>
                            <th>Số điện thoại</th>
                            <td><a href="tel:{{ $request->phone }}">{{ $request->phone }}</a></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $request->email }}</td>
                        </tr>
                        <tr>
                            <th>Tên tổ chức</th>
                            <td>{{ $request->organization ?? 'Không có' }}</td>
                        </tr>
                        <tr>
                            <th>Điểm đến</th>
                            <td>{{ $request->destination }}</td>
                        </tr>
                        <tr>
                            <th>Ngày đi / Thời gian</th>
                            <td>{{ $request->departure_date->format('d/m/Y') }} ({{ $request->duration }})</td>
                        </tr>
                        <tr>
                            <th>Số lượng khách</th>
                            <td>{{ $request->adults }} Lớn - {{ $request->children }} Trẻ em - {{ $request->infants }} Bé</td>
                        </tr>
                        <tr>
                            <th>Ngân sách dự kiến</th>
                            <td>{{ $request->budget }}</td>
                        </tr>
                        <tr>
                            <th>Dịch vụ yêu cầu</th>
                            <td>
                                @if($request->services)
                                    @foreach($request->services as $srv)
                                        <span class="badge bg-info me-1">{{ $srv }}</span>
                                    @endforeach
                                @else
                                    Không có
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ghi chú của khách</th>
                            <td>{{ $request->note }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-warning">
                    <h6 class="m-0 font-weight-bold text-white">Xử lý Yêu cầu (Dành cho Sale)</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.group-requests.update', $request->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái xử lý</label>
                            <<select name="status" class="form-select">
    @if($request->status == 'contracted')
        {{-- Nếu đã chốt thì chỉ hiện trạng thái đã chốt (khóa không cho sửa) --}}
        <option value="contracted" selected>🟢 Đã chốt / Cọc</option>
    @else
        {{-- Nếu chưa chốt thì hiện đủ --}}
        <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>🟡 Mới</option>
        <option value="contacted" {{ $request->status == 'contacted' ? 'selected' : '' }}>🔵 Đang tư vấn</option>
        <option value="cancelled" {{ $request->status == 'cancelled' ? 'selected' : '' }}>🔴 Hủy / Trượt</option>
    @endif
</select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú nội bộ (Admin Note)</label>
                            <textarea name="admin_notes" class="form-control" rows="6" placeholder="Ghi lại lịch sử gọi điện, giá đã báo, lý do khách hủy...">{{ $request->admin_notes }}</textarea>
                            <small class="text-muted">Chỉ Admin/Staff mới thấy ghi chú này.</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Cập nhật tiến độ
                        </button>
                    </form>
                </div>
            </div>

            </div>
         </div>
            
@if($request->status == 'contacted')
    {{-- TRƯỜNG HỢP 1: Đang tư vấn -> Hiện nút tạo booking --}}
    <div class="card shadow mb-4 border-success">
        <div class="card-header py-3 bg-success text-white">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-file-invoice-dollar"></i> Chốt Đơn & Tạo Booking</h6>
        </div>
        <div class="card-body">
            <p class="small mb-3">Khách đã đồng ý giá? Hãy tạo đơn hàng ngay để khách thanh toán.</p>
            <button type="button" class="btn btn-success w-100 py-2" data-bs-toggle="modal" data-bs-target="#createBookingModal">
                <i class="fas fa-check-circle me-1"></i> Tạo Booking Ngay
            </button>
        </div>
    </div>

@elseif($request->status == 'contracted')
    {{-- TRƯỜNG HỢP 2: Đã chốt -> Hiện thông báo thành công (Không hiện nút tạo nữa) --}}
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> Đã tạo Booking thành công. <br>
        <a href="{{ route('admin.bookings') }}" class="fw-bold text-success">Xem danh sách đơn hàng</a>
    </div>

@elseif($request->status == 'cancelled')
    {{-- TRƯỜNG HỢP 3: Đã hủy -> Hiện thông báo hủy --}}
    <div class="alert alert-danger">
        <i class="fas fa-times-circle"></i> Yêu cầu này đã bị hủy.
    </div>

@else
    {{-- TRƯỜNG HỢP 4: Mới (Pending) -> Nhắc nhở tư vấn --}}
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-circle"></i> Vui lòng liên hệ khách và chuyển trạng thái sang <strong>"Đang tư vấn"</strong> trước khi tạo đơn.
    </div>
@endif
        
    </div> </div> <div class="modal fade" id="createBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.group-requests.convert', $request->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Xác nhận tạo Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle"></i> Hệ thống sẽ tự động tạo tài khoản cho khách (nếu chưa có) và tạo đơn hàng để thanh toán.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên Tour (Hiển thị trong đơn hàng) *</label>
                        <input type="text" name="tour_name" class="form-control" required 
                               value="Tour đoàn: {{ $request->destination }} - {{ $request->name }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ngày khởi hành chốt *</label>
                        <input type="date" name="departure_date" class="form-control" 
                               value="{{ $request->departure_date->format('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-success">Tổng giá trị hợp đồng (VNĐ) *</label>
                        <input type="number" name="final_price" class="form-control form-control-lg border-success" required placeholder="VD: 50000000">
                        <div class="form-text">Nhập tổng số tiền khách phải trả (sau khi đã thương lượng).</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Xác nhận tạo</button>
                </div>
            </form>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@endsection