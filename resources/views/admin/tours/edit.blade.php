@extends('layouts.admin')

@section('title', 'Sửa tour - Admin')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.tours') }}">Quản lý Tours</a></li>
<li class="breadcrumb-item active">Sửa tour</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-edit text-primary"></i> Sửa tour: {{ $tour->title }}</h2>
        <p class="text-muted mb-0">Cập nhật thông tin tour</p>
    </div>
    <a href="{{ route('admin.tours') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.tours.update', $tour) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Thông tin cơ bản --}}
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Tên tour <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       name="title" value="{{ old('title', $tour->title) }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $tour->category_id)==$category->id?'selected':'' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả tour <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" required>{{ old('description', $tour->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Thời lượng (text)</label>
                            <input class="form-control" name="duration" value="{{ old('duration', $tour->duration) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Số ngày</label>
                            <input type="number" min="1" class="form-control" name="duration_days" value="{{ old('duration_days', $tour->duration_days) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Số đêm</label>
                            <input type="number" min="0" class="form-control" name="nights" value="{{ old('nights', $tour->nights) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" min="0" step="1000" class="form-control" name="price" value="{{ old('price', $tour->price) }}" required>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Giá gốc</label>
                            <input type="number" min="0" step="1000" class="form-control" name="original_price" value="{{ old('original_price', $tour->original_price) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Giá KM</label>
                            <input type="number" min="0" step="1000" class="form-control" name="discount_price" value="{{ old('discount_price', $tour->discount_price) }}">
                        </div>
                    </div>

                    {{-- GIÁ THEO ĐỐI TƯỢNG --}}
                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">Giá người lớn</label>
                            <input type="number" min="0" step="1000" class="form-control"
                                   name="price_adult" value="{{ old('price_adult', $tour->price_adult) }}">
                            <div class="form-text">Nếu bỏ trống, giá lịch sẽ fallback về “Giá (VNĐ)”.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Giá trẻ em</label>
                            <input type="number" min="0" step="1000" class="form-control"
                                   name="price_child" value="{{ old('price_child', $tour->price_child) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Giá trẻ nhỏ</label>
                            <input type="number" min="0" step="1000" class="form-control"
                                   name="price_infant" value="{{ old('price_infant', $tour->price_infant) }}">
                        </div>
                    </div>

                    {{-- Chỉ còn availability_status --}}
                    <div class="row g-3 mt-2">
                        <div class="col-md-3">
                            <label class="form-label">Tình trạng chỗ (tổng)</label>
                            <select class="form-select" name="availability_status">
                                @php $av = old('availability_status', $tour->availability_status ?? 'available'); @endphp
                                <option value="available" {{ $av=='available' ? 'selected':'' }}>Còn chỗ</option>
                                <option value="contact"   {{ $av=='contact'   ? 'selected':'' }}>Liên hệ</option>
                                <option value="sold_out"  {{ $av=='sold_out'  ? 'selected':'' }}>Hết chỗ</option>
                            </select>
                        </div>
                    </div>

                    {{-- Tabs bổ sung --}}
                    <ul class="nav nav-tabs mt-4" role="tablist">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t_includes" type="button">Giá bao gồm</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t_excludes" type="button">Giá không bao gồm</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t_surcharges" type="button">Phụ thu</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t_notes" type="button">Lưu ý/Hướng dẫn</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t_cancel" type="button">Hủy/đổi</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t_visa" type="button">Visa</button></li>
                    </ul>
                    <div class="tab-content border-start border-end border-bottom p-3">
                        <div class="tab-pane fade show active" id="t_includes">
                            <textarea class="form-control" name="includes" rows="3">{{ old('includes', $tour->includes) }}</textarea>
                        </div>
                        <div class="tab-pane fade" id="t_excludes">
                            <textarea class="form-control" name="excludes" rows="3">{{ old('excludes', $tour->excludes) }}</textarea>
                        </div>
                        <div class="tab-pane fade" id="t_surcharges">
                            <textarea class="form-control" name="surcharges" rows="3">{{ old('surcharges', $tour->surcharges) }}</textarea>
                        </div>
                        <div class="tab-pane fade" id="t_notes">
                            <textarea class="form-control" name="notes" rows="3">{{ old('notes', $tour->notes) }}</textarea>
                        </div>
                        <div class="tab-pane fade" id="t_cancel">
                            <textarea class="form-control" name="cancellation_policy" rows="3">{{ old('cancellation_policy', $tour->cancellation_policy) }}</textarea>
                        </div>
                        <div class="tab-pane fade" id="t_visa">
                            <textarea class="form-control" name="visa_requirements" rows="3">{{ old('visa_requirements', $tour->visa_requirements) }}</textarea>
                        </div>
                    </div>

                    {{-- Ảnh hiện tại --}}
                    @if($tour->images && $tour->images->count())
                        <div class="mt-4">
                            <h5><i class="fas fa-images"></i> Hình ảnh hiện tại</h5>
                            <div class="row">
                                @foreach($tour->images as $img)
                                    <div class="col-md-3 mb-3">
                                        <img src="{{ $img->image_url }}" class="img-fluid rounded" style="height:150px;object-fit:cover;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Thêm ảnh mới --}}
                    <div class="mt-3">
                        <label class="form-label">Thêm hình ảnh mới</label>
                        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                    </div>

                    {{-- Lịch trình (day_number) --}}
                    <h5 class="mt-4"><i class="fas fa-calendar-alt"></i> Lịch trình</h5>
                    <div id="schedule-container">
                        @forelse($tour->schedules as $s)
                            <div class="schedule-item mb-3 p-3 border rounded">
                                <div class="row g-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Ngày</label>
                                        <input type="number" class="form-control" name="schedule_day_number[]" value="{{ $s->day_number }}" min="1" max="60">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tiêu đề</label>
                                        <input class="form-control" name="schedule_title[]" value="{{ $s->title }}">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Mô tả</label>
                                        <textarea class="form-control" name="schedule_description[]" rows="2">{{ $s->description }}</textarea>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger" onclick="this.closest('.schedule-item').remove()">Xóa</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="schedule-item mb-3 p-3 border rounded">
                                <div class="row g-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Ngày</label>
                                        <input type="number" class="form-control" name="schedule_day_number[]" value="1" min="1" max="60">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tiêu đề</label>
                                        <input class="form-control" name="schedule_title[]">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Mô tả</label>
                                        <textarea class="form-control" name="schedule_description[]" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger" onclick="this.closest('.schedule-item').remove()">Xóa</button>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <button type="button" class="btn btn-outline-primary" onclick="addSchedule()">+ Thêm ngày</button>

                    {{-- Lịch khởi hành theo ngày --}}
                    <h5 class="mt-4"><i class="fas fa-plane-departure"></i> Lịch khởi hành</h5>
                    <div id="departure-container">
                        @forelse($tour->departures as $d)
                            <div class="departure-item mb-3 p-3 border rounded">
                                <div class="row g-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Ngày khởi hành</label>
                                        <input type="date" class="form-control" name="departure_date[]" value="{{ $d->departure_date?->format('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Tổng chỗ</label>
                                        <input type="number" class="form-control" name="seats_total[]" value="{{ $d->seats_total }}" min="1" max="100">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Còn chỗ</label>
                                        <input type="number" class="form-control" name="seats_available[]" value="{{ $d->seats_available }}" min="0" max="100">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Giá (ngày)</label>
                                        <input type="number" class="form-control" name="price_dep[]" value="{{ $d->price }}" step="1000" min="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Trạng thái</label>
                                        <select class="form-select" name="status_dep[]">
                                            <option value="available" {{ $d->status=='available'?'selected':'' }}>Còn chỗ</option>
                                            <option value="contact"   {{ $d->status=='contact'  ?'selected':'' }}>Liên hệ</option>
                                            <option value="sold_out"  {{ $d->status=='sold_out' ?'selected':'' }}>Hết chỗ</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Giá trẻ em</label>
                                        <input type="number" class="form-control" name="child_price[]" value="{{ $d->child_price }}" step="1000" min="0">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Giá trẻ nhỏ</label>
                                        <input type="number" class="form-control" name="infant_price[]" value="{{ $d->infant_price }}" step="1000" min="0">
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-danger" onclick="this.closest('.departure-item').remove()">Xóa lịch</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="departure-item mb-3 p-3 border rounded">
                                <div class="row g-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Ngày khởi hành</label>
                                        <input type="date" class="form-control" name="departure_date[]" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Tổng chỗ</label>
                                        <input type="number" class="form-control" name="seats_total[]" value="20" min="1" max="100">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Còn chỗ</label>
                                        <input type="number" class="form-control" name="seats_available[]" value="20" min="0" max="100">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Giá (ngày)</label>
                                        <input type="number" class="form-control" name="price_dep[]" step="1000" min="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Trạng thái</label>
                                        <select class="form-select" name="status_dep[]">
                                            <option value="available">Còn chỗ</option>
                                            <option value="contact">Liên hệ</option>
                                            <option value="sold_out">Hết chỗ</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Giá trẻ em</label>
                                        <input type="number" class="form-control" name="child_price[]" step="1000" min="0">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Giá trẻ nhỏ</label>
                                        <input type="number" class="form-control" name="infant_price[]" step="1000" min="0">
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-danger" onclick="this.closest('.departure-item').remove()">Xóa lịch</button>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <button type="button" class="btn btn-outline-primary" onclick="addDeparture()">+ Thêm lịch khởi hành</button>

                    {{-- Submit --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.tours') }}" class="btn btn-secondary">Hủy</a>
                        <button class="btn btn-primary">Cập nhật tour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function addSchedule(){
    const c = document.getElementById('schedule-container');
    const i = c.children.length + 1;
    c.insertAdjacentHTML('beforeend', `
    <div class="schedule-item mb-3 p-3 border rounded">
      <div class="row g-2">
        <div class="col-md-2">
          <label class="form-label">Ngày</label>
          <input type="number" class="form-control" name="schedule_day_number[]" value="${i}" min="1" max="60">
        </div>
        <div class="col-md-4">
          <label class="form-label">Tiêu đề</label>
          <input class="form-control" name="schedule_title[]" placeholder="VD: Tham quan ...">
        </div>
        <div class="col-md-5">
          <label class="form-label">Mô tả</label>
          <textarea class="form-control" name="schedule_description[]" rows="2"></textarea>
        </div>
        <div class="col-md-1 d-flex align-items-end">
          <button type="button" class="btn btn-outline-danger" onclick="this.closest('.schedule-item').remove()">Xóa</button>
        </div>
      </div>
    </div>`);
}

function addDeparture(){
    const c = document.getElementById('departure-container');
    c.insertAdjacentHTML('beforeend', `
    <div class="departure-item mb-3 p-3 border rounded">
      <div class="row g-2">
        <div class="col-md-2"><label class="form-label">Ngày khởi hành</label>
            <input type="date" class="form-control" name="departure_date[]" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
        </div>
        <div class="col-md-2"><label class="form-label">Tổng chỗ</label>
            <input type="number" class="form-control" name="seats_total[]" value="20" min="1" max="100">
        </div>
        <div class="col-md-2"><label class="form-label">Còn chỗ</label>
            <input type="number" class="form-control" name="seats_available[]" value="20" min="0" max="100">
        </div>
        <div class="col-md-2"><label class="form-label">Giá (ngày)</label>
            <input type="number" class="form-control" name="price_dep[]" step="1000" min="0">
        </div>
        <div class="col-md-2"><label class="form-label">Trạng thái</label>
            <select class="form-select" name="status_dep[]">
                <option value="available">Còn chỗ</option>
                <option value="contact">Liên hệ</option>
                <option value="sold_out">Hết chỗ</option>
            </select>
        </div>
        <div class="col-md-1"><label class="form-label">Giá trẻ em</label>
            <input type="number" class="form-control" name="child_price[]" step="1000" min="0">
        </div>
        <div class="col-md-1"><label class="form-label">Giá trẻ nhỏ</label>
            <input type="number" class="form-control" name="infant_price[]" step="1000" min="0">
        </div>
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" class="btn btn-outline-danger" onclick="this.closest('.departure-item').remove()">Xóa lịch</button>
        </div>
      </div>
    </div>`);
}
</script>
@endsection
