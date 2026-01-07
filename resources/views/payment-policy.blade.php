@extends('layouts.app')

@section('title', 'Chính sách & Điều khoản Tour Đoàn - Tour365')

@section('content')
<!-- Hero Section -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold mb-3">Chính sách & Điều khoản Tour Đoàn</h1>
                <p class="lead">Thông tin chi tiết về chính sách thanh toán và điều khoản đặt tour</p>
            </div>
        </div>
    </div>
</section>

<!-- Policy Content Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- 1. Chính sách trẻ em (dạng văn bản) -->
                <div class="mb-4">
                    <h2 class="h4 fw-bold mb-3">1. Chính sách trẻ em</h2>
                    <ul class="mb-0">
                        <li>
                            <strong>Trẻ em dưới 5 tuổi:</strong>
                            được miễn phí dịch vụ tour cơ bản, sử dụng chung giường và chỗ ngồi với người lớn đi kèm
                            (trừ khi có quy định khác trong từng chương trình cụ thể).
                        </li>
                        <li>
                            <strong>Trẻ em từ 5 đến dưới 12 tuổi:</strong>
                            thông thường áp dụng mức giá khoảng <strong>50% – 70% giá người lớn</strong>, tùy theo chính sách
                            của từng tour và từng thời điểm.
                        </li>
                        <li>
                            <strong>Trẻ em từ đủ 12 tuổi trở lên:</strong>
                            được tính giá như một khách người lớn.
                        </li>
                        <li>
                            Chính sách cụ thể về giá, dịch vụ bao gồm/không bao gồm cho trẻ em sẽ được nêu rõ trong
                            mô tả chi tiết của từng chương trình tour hoặc trong hợp đồng (nếu có).
                        </li>
                    </ul>
                </div>

                <!-- 2. Chính sách hoàn hủy (dạng văn bản) -->
                <div class="mb-4">
                    <h2 class="h4 fw-bold mb-3">2. Chính sách hoàn hủy</h2>
                    <p class="mb-2">
                        Khách hàng có nhu cầu hủy tour cần thông báo cho công ty bằng văn bản hoặc các kênh liên lạc
                        chính thức. Tùy thuộc vào thời điểm hủy so với ngày khởi hành, các điều kiện phạt/hoàn tiền được
                        áp dụng như sau (có thể thay đổi theo từng chương trình tour cụ thể):
                    </p>
                    <ul class="mb-0">
                        <li>
                            <strong>Hủy trước ngày khởi hành từ 15 ngày trở lên:</strong>
                            có thể được hoàn lại tối đa đến <strong>100% số tiền đã thanh toán</strong>, sau khi trừ các chi phí
                            phát sinh thực tế (nếu có).
                        </li>
                        <li>
                            <strong>Hủy trước ngày khởi hành từ 7 đến dưới 15 ngày:</strong>
                            có thể áp dụng mức phạt khoảng <strong>50% giá trị tiền đã thanh toán</strong> hoặc theo tỷ lệ ghi rõ
                            trong chương trình/ hợp đồng.
                        </li>
                        <li>
                            <strong>Hủy trước ngày khởi hành dưới 7 ngày hoặc hủy/không tham gia vào ngày khởi hành:</strong>
                            thông thường <strong>không được hoàn tiền</strong>, trừ trường hợp hai bên có thỏa thuận khác bằng văn bản.
                        </li>
                        <li>
                            Các chi phí đã thanh toán cho bên thứ ba (hãng hàng không, khách sạn, đối tác dịch vụ…) và
                            không thể hoàn lại sẽ được thông báo rõ cho khách hàng và được trừ vào số tiền hoàn (nếu có).
                        </li>
                    </ul>
                </div>

                <!-- 3. Chính sách khởi hành & hủy tour do thiếu khách (dạng văn bản) -->
                <div class="mb-4">
                    <h2 class="h4 fw-bold mb-3">3. Chính sách khởi hành &amp; hủy tour do thiếu khách</h2>

                    <p class="mb-2">
                        3.1. <strong>Điều kiện khởi hành tour</strong>
                    </p>
                    <p class="mb-2">
                        Tour chỉ được khởi hành khi đạt đủ <strong>số lượng khách tối thiểu</strong> theo quy định của công ty
                        cho từng chương trình tour cụ thể.
                    </p>
                    <p class="mb-3">
                        Số lượng khách tối thiểu sẽ được thông báo cụ thể trong chương trình tour, báo giá hoặc tại thời điểm
                        khách hàng đặt tour, và có thể thay đổi tùy theo mùa, tuyến điểm và hình thức tổ chức.
                    </p>

                    <p class="mb-2">
                        3.2. <strong>Trường hợp không đủ khách khởi hành</strong>
                    </p>
                    <p class="mb-2">
                        Trong trường hợp đến thời hạn cận ngày khởi hành mà không đủ số lượng khách tối thiểu, công ty có quyền
                        điều chỉnh hoặc hủy tour và sẽ thông báo cho khách hàng. Các phương án xử lý có thể bao gồm:
                    </p>
                    <ul class="mb-3">
                        <li>
                            <strong>Dời ngày khởi hành</strong> sang thời điểm khác phù hợp và có sự xác nhận lại của khách hàng;
                        </li>
                        <li>
                            <strong>Gộp đoàn</strong> với tour khác có cùng hoặc tương đương hành trình (nếu có và khách hàng đồng ý);
                        </li>
                        <li>
                            <strong>Hủy tour</strong> và áp dụng chính sách hoàn tiền theo quy định tại Mục 3.3 dưới đây.
                        </li>
                    </ul>

                    <p class="mb-2">
                        3.3. <strong>Chính sách hoàn tiền khi hủy tour do thiếu khách</strong>
                    </p>
                    <p class="mb-2">
                        Khi phải hủy tour do không đủ khách, công ty sẽ thông báo cho khách hàng trước ngày khởi hành
                        tối thiểu <strong>03 (ba) ngày làm việc</strong> hoặc trong thời hạn phù hợp với từng chương trình tour cụ thể.
                    </p>
                    <p class="mb-2">
                        Khách hàng được lựa chọn một trong các phương án sau:
                    </p>
                    <ul class="mb-3">
                        <li>
                            <strong>Hoàn trả 100% số tiền đã thanh toán</strong> cho chương trình tour (không bao gồm
                            các chi phí phát sinh ngoài chương trình do khách hàng tự đặt, nếu các chi phí này không thể hoàn lại
                            từ bên cung cấp dịch vụ); hoặc
                        </li>
                        <li>
                            <strong>Chuyển sang tour khác</strong> có giá trị tương đương hoặc cao hơn; trường hợp giá tour mới
                            cao hơn, khách hàng thanh toán phần chênh lệch theo thỏa thuận.
                        </li>
                    </ul>
                    <p class="mb-3">
                        Việc hoàn tiền (nếu có) sẽ được thực hiện trong vòng <strong>03 – 07 ngày làm việc</strong> kể từ ngày
                        công ty xác nhận hủy tour do thiếu khách, bằng hình thức chuyển khoản hoặc phương thức khác phù hợp
                        theo thỏa thuận với khách hàng.
                    </p>

                    <p class="mb-2">
                        3.4. <strong>Trách nhiệm và giới hạn bồi thường</strong>
                    </p>
                    <p class="mb-2">
                        Trong trường hợp tour bị hủy do thiếu khách, công ty không chịu trách nhiệm đối với các chi phí
                        phát sinh ngoài chương trình tour mà khách hàng tự đặt trước, bao gồm nhưng không giới hạn: vé máy bay
                        tự túc, vé tàu/xe, phí bảo hiểm cá nhân, chi phí lưu trú hoặc các chi phí cá nhân khác,
                        nếu các khoản này không thể hoàn lại từ bên cung cấp dịch vụ.
                    </p>
                    <p class="mb-3">
                        Công ty không có nghĩa vụ bồi thường thêm bất kỳ khoản nào khác ngoài số tiền đã hoàn trả cho khách hàng
                        theo chính sách tại Mục 3.3, trừ khi có thỏa thuận khác bằng văn bản giữa hai bên.
                    </p>

                    <p class="mb-2">
                        3.5. <strong>Điều khoản chung</strong>
                    </p>
                    <p class="mb-2">
                        Việc khách hàng đặt tour và thanh toán được xem là khách hàng đã đọc, hiểu rõ và đồng ý với các điều khoản
                        liên quan đến điều kiện khởi hành và hủy tour do thiếu khách nêu tại Mục 3 này.
                    </p>
                    <p class="mb-0">
                        Công ty có quyền điều chỉnh, cập nhật chính sách khi cần thiết và sẽ công khai trên website hoặc các kênh
                        thông tin chính thức khác. Trong trường hợp có Hợp đồng Du lịch riêng, các điều khoản trong hợp đồng (nếu khác)
                        sẽ được ưu tiên áp dụng.
                    </p>
                </div>

                <!-- Lưu ý quan trọng -->
                <div class="alert alert-info border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle fa-2x me-3 mt-1"></i>
                        <div>
                            <h5 class="alert-heading fw-bold mb-2">Lưu ý quan trọng</h5>
                            <p class="mb-0">
                                Đây là chính sách tham khảo. Điều khoản chi tiết sẽ được quy định cụ thể trong 
                                <strong>Hợp đồng Du lịch</strong> khi hai bên ký kết.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-question-circle text-primary me-2"></i>
                            Thông tin bổ sung
                        </h3>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Thời gian tính từ ngày khởi hành của tour
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Mọi thay đổi về chính sách sẽ được thông báo trước khi đặt tour
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Vui lòng đọc kỹ hợp đồng trước khi ký kết
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check text-success me-2"></i>
                                Liên hệ hotline để được tư vấn chi tiết về từng tour cụ thể
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="text-center mt-5">
                    <a href="{{ route('tours.index') }}" class="btn btn-primary btn-lg me-2">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        Xem các tour hiện có
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-envelope me-2"></i>
                        Liên hệ tư vấn
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.policy-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(14, 165, 233, 0.1);
    border-radius: 12px;
}

.policy-item {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.policy-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.card {
    border-radius: 16px;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-3px);
}

.alert {
    border-radius: 16px;
}

@media (max-width: 768px) {
    .policy-icon {
        width: 50px;
        height: 50px;
    }
    
    .policy-icon i {
        font-size: 1.5rem !important;
    }
}
</style>
@endsection

