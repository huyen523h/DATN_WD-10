@extends('layouts.admin')

@section('title', 'Quản lý Tours - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Quản lý Tours</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-map-marked-alt text-primary"></i> Quản lý Tours</h2>
        <p class="text-muted mb-0">Quản lý tất cả các tour du lịch trong hệ thống</p>
    </div>
    <a href="{{ route('admin.tours.create') }}" class="btn btn-admin-primary">
        <i class="fas fa-plus"></i> Thêm tour mới
    </a>
</div>

{{-- Search & Filter --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.tours') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Tên tour, mô tả, danh mục...">
                    </div>

                    <div class="col-md-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (string)request('category_id') === (string)$category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="availability_status" class="form-label">Trạng thái</label>
                        @php $av = request('availability_status'); @endphp
                        <select class="form-select" id="availability_status" name="availability_status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="available" {{ $av==='available' ? 'selected' : '' }}>Còn chỗ</option>
                            <option value="contact"   {{ $av==='contact'   ? 'selected' : '' }}>Liên hệ</option>
                            <option value="sold_out"  {{ $av==='sold_out'  ? 'selected' : '' }}>Hết chỗ</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('admin.tours') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Tours Table --}}
<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Danh sách tours ({{ $tours->total() }} tours)
                </h6>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                        <i class="fas fa-check-square"></i> Chọn tất cả
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="bulkDelete()">
                        <i class="fas fa-trash"></i> Xóa đã chọn
                    </button>
                </div>
            </div>

            <div class="card-body">
                @if($tours->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    </th>
                                    <th>Hình ảnh</th>
                                    <th>Tên tour</th>
                                    <th>Danh mục</th>
                                    <th>Giá</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                    <th>Đặt tour</th>
                                    <th>Ngày tạo</th>
                                    <th width="150">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tours as $tour)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="tour-checkbox" value="{{ $tour->id }}">
                                        </td>

                                        {{-- Hình: luôn lấy ảnh đầu tiên theo quan hệ đã SORT + thêm cache-busting bằng updated_at --}}
                                        <td>
                                            @if($tour->images->count() > 0)
                                                {{-- CHANGED: thêm ?v=updated_at để tránh cache --}}
                                                <img src="{{ $tour->images->first()->image_url }}?v={{ $tour->updated_at?->timestamp }}"
                                                     alt="{{ $tour->title }}"
                                                     class="tour-thumbnail">
                                            @else
                                                <div class="tour-thumbnail bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="fw-bold">{{ $tour->title }}</div>
                                            <small class="text-muted">{{ \Illuminate\Support\Str::limit($tour->description, 60) }}</small>
                                        </td>

                                        <td>
                                            @if($tour->category)
                                                <span class="badge bg-light text-dark">{{ $tour->category->name }}</span>
                                            @else
                                                <span class="text-muted">Chưa phân loại</span>
                                            @endif
                                        </td>

                                        <td class="fw-bold text-success">
                                            {{ number_format($tour->price, 0, ',', '.') }}đ
                                        </td>

                                        <td>
                                            <i class="fas fa-clock text-muted"></i>
                                            {{ $tour->duration_days ? $tour->duration_days.' ngày' : ($tour->duration ?? '—') }}
                                        </td>

                                        {{-- Trạng thái (availability_status) --}}
                                        @php
                                            $map = [
                                                'available' => ['Còn chỗ', 'badge bg-success'],
                                                'contact'   => ['Liên hệ',  'badge bg-warning text-dark'],
                                                'sold_out'  => ['Hết chỗ',  'badge bg-secondary'],
                                            ];
                                            [$label,$cls] = $map[$tour->availability_status] ?? ['—','badge bg-light text-dark'];
                                        @endphp
                                        <td><span class="{{ $cls }}">{{ $label }}</span></td>

                                        <td><span class="badge bg-info">{{ $tour->bookings->count() }}</span></td>

                                        <td>
                                            <small class="text-muted">{{ optional($tour->created_at)->format('d/m/Y') }}</small>
                                        </td>

                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('tours.show', $tour) }}"
                                                   class="btn btn-outline-info" title="Xem">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.tours.edit', $tour) }}"
                                                   class="btn btn-outline-primary" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                {{-- Nút xóa: data-id để JS lấy id --}}
                                                <button type="button"
                                                        class="btn btn-outline-danger"
                                                        data-id="{{ $tour->id }}"
                                                        onclick="openDeleteModal(this)"
                                                        title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Hiển thị {{ $tours->firstItem() }} đến {{ $tours->lastItem() }}
                            trong tổng số {{ $tours->total() }} tours
                        </div>
                        <div>
                            {{ $tours->withQueryString()->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-map-marked-alt fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có tour nào</h5>
                        <p class="text-muted">Hãy thêm tour đầu tiên để bắt đầu</p>
                        <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm tour mới
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa tour này không?
                {{-- <div class="text-danger small mt-2">Hành động này không thể hoàn tác!</div> --}}
                <div id="deleteSpinner" class="mt-3 d-none">
                    <i class="fas fa-spinner fa-spin me-2"></i> Đang xóa...
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Xóa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.tour-thumbnail{width:60px;height:40px;object-fit:cover;border-radius:4px}
.tour-checkbox{transform:scale(1.2)}
.btn-group-sm .btn{padding:.25rem .5rem;font-size:.75rem}
.table th{border-top:none;font-weight:600;color:#6B7280;font-size:.875rem;text-transform:uppercase;letter-spacing:.05em}
.table td{vertical-align:middle}
.card-header{background:#F9FAFB;border-bottom:1px solid #E5E7EB}
</style>
@endsection

@section('scripts')
<script>
let deletingId = null;
let deleteModal = null;

// Lấy CSRF token từ meta trong layout
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Mở modal xóa
function openDeleteModal(btnEl){
    deletingId = btnEl.getAttribute('data-id');
    if(!deleteModal){
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    }
    document.getElementById('deleteSpinner').classList.add('d-none');
    document.getElementById('confirmDelete').disabled = false;
    deleteModal.show();
}

// Chọn / bỏ chọn tất cả
function selectAll(){
  const cbs=[...document.querySelectorAll('.tour-checkbox')];
  const allChecked=cbs.every(cb=>cb.checked);
  cbs.forEach(cb=>cb.checked=!allChecked);
  document.getElementById('selectAll').checked=!allChecked;
}
function toggleSelectAll(){
  const m=document.getElementById('selectAll');
  document.querySelectorAll('.tour-checkbox').forEach(cb=>cb.checked=m.checked);
}

// Xóa hàng loạt (placeholder)
function bulkDelete(){
  const ids=[...document.querySelectorAll('.tour-checkbox:checked')].map(cb=>cb.value);
  if(ids.length===0){ alert('Vui lòng chọn ít nhất một tour để xóa'); return; }
  alert('Bạn đã chọn ' + ids.length + ' tour. (Chưa gắn API xóa hàng loạt)');
}

// Gửi request xóa (AJAX + method spoofing)
document.getElementById('confirmDelete').addEventListener('click', async function(){
    if(!deletingId) return;

    const spinner = document.getElementById('deleteSpinner');
    this.disabled = true;
    spinner.classList.remove('d-none');

    const url = "{{ route('admin.tours.destroy', ':id') }}".replace(':id', deletingId);

    try{
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({_method: 'DELETE'})
        });

        if(!res.ok){
            let msg='Xóa thất bại';
            try{ const data = await res.json(); msg = data.message || msg; }catch(e){}
            throw new Error(msg + ' (HTTP ' + res.status + ')');
        }

        // Nếu controller trả JSON {ok:true}
        try{
            const data = await res.json();
            if(data.ok){ location.reload(); return; }
        }catch(e){
            // Nếu controller redirect HTML -> cứ reload
            location.reload();
        }
    }catch(err){
        console.error(err);
        alert('Không thể xóa tour. Vui lòng xem Console/log để biết chi tiết.');
        this.disabled = false;
        spinner.classList.add('d-none');
    }
});
</script>
@endsection
