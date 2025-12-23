@extends('layouts.app')

@section('title', 'Đặt Tour Đoàn / Thiết Kế Tour Riêng')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h2 class="mb-0"><i class="fas fa-users me-2"></i> ĐẶT TOUR ĐOÀN / THIẾT KẾ RIÊNG</h2>
                    <p class="mb-0 mt-2 opacity-75">Tận hưởng kỳ nghỉ theo cách riêng của bạn</p>
                </div>
                <div class="card-body p-5">
                    
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('group-tour.store') }}" method="POST">
                        @csrf
                        
                        <h5 class="text-primary mb-3 border-bottom pb-2">
                            <i class="fas fa-user-circle me-2"></i>1. Thông tin liên hệ
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="Nhập họ tên người liên hệ">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" required placeholder="Để Sale liên hệ tư vấn">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required placeholder="Nhận báo giá qua email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tên công ty / Tổ chức</label>
                                <input type="text" name="organization" class="form-control" placeholder="VD: Công ty FPT, Lớp 12A...">
                            </div>
                        </div>

                        <h5 class="text-primary mb-3 border-bottom pb-2">
                            <i class="fas fa-map-marked-alt me-2"></i>2. Thông tin chuyến đi
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Điểm đến mong muốn <span class="text-danger">*</span></label>
                                <input type="text" name="destination" class="form-control" required placeholder="VD: Phú Quốc, Đà Lạt...">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Ngày khởi hành dự kiến <span class="text-danger">*</span></label>
                                <input type="date" name="departure_date" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Thời gian</label>
                                <select name="duration" class="form-select">
                                    <option value="2N1Đ">2 Ngày 1 Đêm</option>
                                    <option value="3N2Đ" selected>3 Ngày 2 Đêm</option>
                                    <option value="4N3Đ">4 Ngày 3 Đêm</option>
                                    <option value="Khác">Khác (Ghi chú thêm)</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Người lớn (>12t)</label>
                                <input type="number" name="adults" class="form-control" value="10" min="1">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Trẻ em (5-11t)</label>
                                <input type="number" name="children" class="form-control" value="0" min="0">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Em bé (dưới 5t) </label>
                                <input type="number" name="infants" class="form-control" value="0" min="0">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Ngân sách / người</label>
                                <select name="budget" class="form-select">
                                    <option value="<3tr">Dưới 3 triệu</option>
                                    <option value="3-5tr" selected>3 - 5 triệu</option>
                                    <option value="5-10tr">5 - 10 triệu</option>
                                    <option value=">10tr">Trên 10 triệu</option>
                                </select>
                            </div>
                        </div>

                        <h5 class="text-primary mb-3 border-bottom pb-2">
                            <i class="fas fa-concierge-bell me-2"></i>3. Dịch vụ & Yêu cầu khác
                        </h5>
                        <div class="mb-3">
                            <label class="form-label fw-bold d-block">Dịch vụ yêu cầu thêm:</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="services[]" value="Teambuilding" id="srv1">
                                <label class="form-check-label" for="srv1">Tổ chức Teambuilding</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="services[]" value="Gala Dinner" id="srv2">
                                <label class="form-check-label" for="srv2">Tổ chức Gala Dinner</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="services[]" value="Phòng hội nghị" id="srv3">
                                <label class="form-check-label" for="srv3">Thuê phòng hội nghị</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="services[]" value="Xe riêng" id="srv4">
                                <label class="form-check-label" for="srv4">Xe riêng đưa đón</label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Ghi chú thêm</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Ví dụ: Đoàn có người ăn chay, cần hướng dẫn viên biết tiếng Anh..."></textarea>
                        </div>

                        <div class="alert alert-light border mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" required id="agreePolicy">
                                <label class="form-check-label" for="agreePolicy">
                                    Bằng việc gửi yêu cầu, quý khách đồng ý với 
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#policyModal" class="text-decoration-underline fw-bold">Chính sách đặt tour đoàn</a> 
                                    của chúng tôi.
                                </label>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i> GỬI YÊU CẦU BÁO GIÁ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="policyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Chính sách & Điều khoản Tour Đoàn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
    <h6 class="fw-bold text-primary"><i class="fas fa-users-cog me-2"></i>1. Quy tắc hành khách</h6>
    <div class="alert alert-info bg-light border-0 small">
        <ul class="mb-0 ps-3">
            <li class="mb-2">
                Mỗi người lớn có thể đi kèm tối đa <strong>2 trẻ em (2-11 tuổi)</strong> và <strong>1 em bé (< 2 tuổi)</strong>.
            </li>
            <li class="mb-2">
                Trẻ em/em bé <strong>bắt buộc</strong> phải đi cùng ít nhất 1 người lớn.
            </li>
            <li>
                <strong>Người lớn:</strong> Là hành khách trên 11 tuổi.
            </li>
        </ul>
    </div>


    <h6 class="fw-bold text-primary mt-3"><i class="fas fa-undo-alt me-2"></i>2. Chính sách hoàn hủy</h6>
    <ul class="small ps-3 text-secondary">
        <li class="mb-1">Hủy trước <strong>15 ngày</strong>: Hoàn 100% tiền cọc.</li>
        <li class="mb-1">Hủy trước <strong>7 ngày</strong>: Phạt 50% tiền cọc.</li>
        <li>Hủy sau <strong>7 ngày</strong>: Không hoàn tiền cọc.</li>
    </ul>

    <div class="mt-4 pt-3 border-top">
        <p class="fst-italic text-muted small mb-0">
            <i class="fas fa-info-circle me-1"></i> 
            Lưu ý: Đây là chính sách tham khảo chung. Điều khoản chi tiết chính xác sẽ được quy định cụ thể trong Hợp đồng Du lịch khi hai bên ký kết.
        </p>
    </div>
</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endsection