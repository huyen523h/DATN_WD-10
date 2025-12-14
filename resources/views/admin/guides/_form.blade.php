@php
    $isEdit = $guide->exists;
    $selectedCategories = old('category_ids', $guide->categories->pluck('id')->toArray() ?? []);

    $certifications = old('certifications', $guide->certifications ?? []);
    if (empty($certifications)) {
        $certifications = [''];
    }

    $languages = old('languages', $guide->languages->map(fn ($item) => $item->only(['language', 'proficiency', 'certification_code', 'certified_at']))->toArray() ?? []);
    if (empty($languages)) {
        $languages = [
            ['language' => '', 'proficiency' => 'basic', 'certification_code' => '', 'certified_at' => ''],
        ];
    }

    $documents = old('documents', $guide->documents->map(fn ($item) => $item->only(['type', 'name', 'issued_by', 'issued_at', 'expires_at', 'status', 'notes']))->toArray() ?? []);
    if (empty($documents)) {
        $documents = [
            ['type' => '', 'name' => '', 'issued_by' => '', 'issued_at' => '', 'expires_at' => '', 'status' => 'valid', 'notes' => ''],
        ];
    }

    $healthRecords = old('health_records', $guide->healthRecords->map(fn ($item) => $item->only(['check_date', 'status', 'doctor_name', 'hospital', 'notes']))->toArray() ?? []);
    if (empty($healthRecords)) {
        $healthRecords = [
            ['check_date' => '', 'status' => '', 'doctor_name' => '', 'hospital' => '', 'notes' => ''],
        ];
    }
    $currentUserId = old('user_id', $guide->user_id ?? null);
    $guideUsersList = $guideUsers ?? collect();
@endphp

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Vui lòng kiểm tra lại!</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $formAction }}" method="POST">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Thông tin cơ bản</span>
            <span class="badge bg-primary">{{ $isEdit ? 'Chỉnh sửa' : 'Tạo mới' }}</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Mã HDV *</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $guide->code) }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Họ tên *</label>
                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $guide->full_name ?? '') }}" required placeholder="Nhập họ tên hướng dẫn viên">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', optional($guide->date_of_birth)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Giới tính</label>
                    <select name="gender" class="form-select">
                        <option value="">-- Chọn --</option>
                        @foreach (['male' => 'Nam', 'female' => 'Nữ', 'other' => 'Khác'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('gender', $guide->gender) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ảnh đại diện (URL)</label>
                    <input type="text" name="avatar_url" class="form-control" value="{{ old('avatar_url', $guide->avatar_url) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $guide->phone) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email * <small class="text-muted">(Dùng để tạo tài khoản đăng nhập)</small></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $guide->email) }}" {{ $isEdit ? '' : 'required' }}>
                    @if(!$isEdit)
                        <small class="text-muted">Email này sẽ được dùng để tạo tài khoản đăng nhập cho HDV</small>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ngôn ngữ chính</label>
                    <input type="text" name="primary_language" class="form-control" value="{{ old('primary_language', $guide->primary_language) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $guide->address) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Liên hệ khẩn cấp</label>
                    <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $guide->emergency_contact_name) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">SĐT liên hệ khẩn</label>
                    <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $guide->emergency_contact_phone) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Số năm kinh nghiệm</label>
                    <input type="number" min="0" name="experience_years" class="form-control" value="{{ old('experience_years', $guide->experience_years ?? 0) }}" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Điểm đánh giá</label>
                    <input type="number" step="0.1" min="0" max="5" name="rating_average" class="form-control" value="{{ old('rating_average', $guide->rating_average) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Số lượt đánh giá</label>
                    <input type="number" min="0" name="rating_count" class="form-control" value="{{ old('rating_count', $guide->rating_count) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tình trạng sức khoẻ</label>
                    <input type="text" name="health_status" class="form-control" value="{{ old('health_status', $guide->health_status) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ngày khám sức khoẻ gần nhất</label>
                    <input type="date" name="last_medical_check_at" class="form-control" value="{{ old('last_medical_check_at', optional($guide->last_medical_check_at)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select" required>
                        @php
                            $currentStatus = old('status', $guide->status ?? 'active');
                        @endphp
                        @foreach (['active' => 'Đang hoạt động', 'inactive' => 'Ngưng hoạt động', 'on_leave' => 'Tạm nghỉ'] as $value => $label)
                            <option value="{{ $value }}" @selected($currentStatus === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Tuyến chuyên môn</label>
                    <input type="text" name="specialty_routes" class="form-control" value="{{ old('specialty_routes', $guide->specialty_routes) }}"
                        placeholder="Ví dụ: Hà Nội - Sa Pa, Phú Quốc - Nam Du...">
                </div>
                <div class="col-12">
                    <label class="form-label">Tiểu sử / ghi chú</label>
                    <textarea name="biography" rows="3" class="form-control">{{ old('biography', $guide->biography) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Tài khoản đăng nhập (HDV)</div>
        <div class="card-body">
            <div class="row g-3">
                @if ($guide->user)
                    <div class="col-12">
                        <div class="alert alert-info py-2 mb-2">
                            Tài khoản hiện tại: <strong>{{ $guide->user->email }}</strong>
                        </div>
                    </div>
                @endif

                <div class="col-md-6">
                    <label class="form-label">Chọn tài khoản hướng dẫn viên đã có</label>
                    <select name="user_id" class="form-select">
                        <option value="">-- Không gán / giữ nguyên --</option>
                        @foreach ($guideUsersList as $user)
                            <option value="{{ $user->id }}" @selected($currentUserId == $user->id)>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">
                        Chọn user có role <code>guide</code> nếu đã tạo tài khoản trước đó.
                    </small>
                </div>

                <div class="col-md-6">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="create_user_account" value="1"
                            id="createUserAccountCheckbox" @checked(old('create_user_account'))>
                        <label class="form-check-label" for="createUserAccountCheckbox">
                            Tạo mới tài khoản đăng nhập cho HDV này
                        </label>
                    </div>
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label">Email đăng nhập</label>
                            <input type="email" name="user_email" class="form-control"
                                   value="{{ old('user_email', $guide->user->email ?? $guide->email) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mật khẩu ban đầu</label>
                            <input type="password" name="user_password" class="form-control"
                                   placeholder="{{ $isEdit ? 'Để trống nếu không đổi mật khẩu' : '' }}">
                        </div>
                    </div>
                    <small class="text-muted d-block mt-1">
                        Nếu tick tạo tài khoản mới, hệ thống sẽ tạo (hoặc dùng lại) user theo email trên và gán role <code>guide</code>.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Nhóm HDV</div>
        <div class="card-body">
            <div class="row">
                @foreach ($categories as $category)
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="category_ids[]" value="{{ $category->id }}" id="cat-{{ $category->id }}"
                                @checked(in_array($category->id, $selectedCategories))>
                            <label class="form-check-label" for="cat-{{ $category->id }}">
                                {{ $category->name }}
                                <small class="text-muted d-block">{{ $category->type }}</small>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Chứng chỉ chuyên môn</span>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-row data-target="#certificationRows" data-template="#certificationTemplate">
                <i class="fas fa-plus"></i> Thêm chứng chỉ
            </button>
        </div>
        <div class="card-body" id="certificationRows">
            @foreach ($certifications as $index => $certificate)
                <div class="row g-3 align-items-end repeat-row mb-3">
                    <div class="col-md-10">
                        <label class="form-label">Tên chứng chỉ</label>
                        <input type="text" name="certifications[]" class="form-control" value="{{ $certificate }}">
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-outline-danger w-100" data-remove-row>Xoá</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Ngôn ngữ sử dụng</span>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-row data-target="#languageRows" data-template="#languageTemplate">
                <i class="fas fa-plus"></i> Thêm ngôn ngữ
            </button>
        </div>
        <div class="card-body" id="languageRows">
            @foreach ($languages as $index => $language)
                <div class="border rounded p-3 mb-3 repeat-row">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Ngôn ngữ</label>
                            <input type="text" name="languages[{{ $loop->index }}][language]" class="form-control" value="{{ $language['language'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Trình độ</label>
                            <select name="languages[{{ $loop->index }}][proficiency]" class="form-select">
                                @foreach (['basic' => 'Cơ bản', 'intermediate' => 'Khá', 'advanced' => 'Tốt', 'native' => 'Bản ngữ'] as $value => $label)
                                    <option value="{{ $value }}" @selected(($language['proficiency'] ?? '') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Mã chứng chỉ</label>
                            <input type="text" name="languages[{{ $loop->index }}][certification_code]" class="form-control" value="{{ $language['certification_code'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Ngày cấp</label>
                            <input type="date" name="languages[{{ $loop->index }}][certified_at]" class="form-control" value="{{ $language['certified_at'] ?? '' }}">
                        </div>
                    </div>
                    <div class="text-end mt-2">
                        <button type="button" class="btn btn-outline-danger btn-sm" data-remove-row>Xoá</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Tài liệu & giấy tờ</span>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-row data-target="#documentRows" data-template="#documentTemplate">
                <i class="fas fa-plus"></i> Thêm tài liệu
            </button>
        </div>
        <div class="card-body" id="documentRows">
            @foreach ($documents as $index => $document)
                <div class="border rounded p-3 mb-3 repeat-row">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Loại</label>
                            <input type="text" name="documents[{{ $loop->index }}][type]" class="form-control" value="{{ $document['type'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tên tài liệu</label>
                            <input type="text" name="documents[{{ $loop->index }}][name]" class="form-control" value="{{ $document['name'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Đơn vị cấp</label>
                            <input type="text" name="documents[{{ $loop->index }}][issued_by]" class="form-control" value="{{ $document['issued_by'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="documents[{{ $loop->index }}][status]" class="form-select">
                                @foreach (['valid' => 'Còn hiệu lực', 'expired' => 'Hết hạn', 'revoked' => 'Thu hồi', 'pending' => 'Đang xử lý'] as $value => $label)
                                    <option value="{{ $value }}" @selected(($document['status'] ?? '') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ngày cấp</label>
                            <input type="date" name="documents[{{ $loop->index }}][issued_at]" class="form-control" value="{{ $document['issued_at'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ngày hết hạn</label>
                            <input type="date" name="documents[{{ $loop->index }}][expires_at]" class="form-control" value="{{ $document['expires_at'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ghi chú</label>
                            <input type="text" name="documents[{{ $loop->index }}][notes]" class="form-control" value="{{ $document['notes'] ?? '' }}">
                        </div>
                    </div>
                    <div class="text-end mt-2">
                        <button type="button" class="btn btn-outline-danger btn-sm" data-remove-row>Xoá</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div> --}}

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Lịch sử sức khoẻ</span>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-row data-target="#healthRows" data-template="#healthTemplate">
                <i class="fas fa-plus"></i> Thêm lần khám
            </button>
        </div>
        <div class="card-body" id="healthRows">
            @foreach ($healthRecords as $index => $record)
                <div class="border rounded p-3 mb-3 repeat-row">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Ngày khám</label>
                            <input type="date" name="health_records[{{ $loop->index }}][check_date]" class="form-control" value="{{ $record['check_date'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kết luận</label>
                            <input type="text" name="health_records[{{ $loop->index }}][status]" class="form-control" value="{{ $record['status'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Bác sĩ</label>
                            <input type="text" name="health_records[{{ $loop->index }}][doctor_name]" class="form-control" value="{{ $record['doctor_name'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cơ sở y tế</label>
                            <input type="text" name="health_records[{{ $loop->index }}][hospital]" class="form-control" value="{{ $record['hospital'] ?? '' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="health_records[{{ $loop->index }}][notes]" rows="2" class="form-control">{{ $record['notes'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="text-end mt-2">
                        <button type="button" class="btn btn-outline-danger btn-sm" data-remove-row>Xoá</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="text-end">
        <a href="{{ route('admin.guides.index') }}" class="btn btn-outline-secondary me-2">Huỷ</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> {{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}
        </button>
    </div>
</form>

{{-- <template id="certificationTemplate">
    <div class="row g-3 align-items-end repeat-row mb-3">
        <div class="col-md-10">
            <label class="form-label">Tên chứng chỉ</label>
            <input type="text" name="certifications[]" class="form-control">
        </div>
        <div class="col-md-2 text-end">
            <button type="button" class="btn btn-outline-danger w-100" data-remove-row>Xoá</button>
        </div>
    </div>
</template> --}}

{{-- <template id="languageTemplate">
    <div class="border rounded p-3 mb-3 repeat-row">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Ngôn ngữ</label>
                <input type="text" name="languages[__INDEX__][language]" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Trình độ</label>
                <select name="languages[__INDEX__][proficiency]" class="form-select">
                    <option value="basic">Cơ bản</option>
                    <option value="intermediate">Khá</option>
                    <option value="advanced">Tốt</option>
                    <option value="native">Bản ngữ</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Mã chứng chỉ</label>
                <input type="text" name="languages[__INDEX__][certification_code]" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Ngày cấp</label>
                <input type="date" name="languages[__INDEX__][certified_at]" class="form-control">
            </div>
        </div>
        <div class="text-end mt-2">
            <button type="button" class="btn btn-outline-danger btn-sm" data-remove-row>Xoá</button>
        </div>
    </div>
</template> --}}

{{-- <template id="documentTemplate">
    <div class="border rounded p-3 mb-3 repeat-row">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Loại</label>
                <input type="text" name="documents[__INDEX__][type]" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tên tài liệu</label>
                <input type="text" name="documents[__INDEX__][name]" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Đơn vị cấp</label>
                <input type="text" name="documents[__INDEX__][issued_by]" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select name="documents[__INDEX__][status]" class="form-select">
                    <option value="valid">Còn hiệu lực</option>
                    <option value="expired">Hết hạn</option>
                    <option value="revoked">Thu hồi</option>
                    <option value="pending">Đang xử lý</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Ngày cấp</label>
                <input type="date" name="documents[__INDEX__][issued_at]" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Ngày hết hạn</label>
                <input type="date" name="documents[__INDEX__][expires_at]" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Ghi chú</label>
                <input type="text" name="documents[__INDEX__][notes]" class="form-control">
            </div>
        </div>
        <div class="text-end mt-2">
            <button type="button" class="btn btn-outline-danger btn-sm" data-remove-row>Xoá</button>
        </div>
    </div>
</template> --}}

{{-- <template id="healthTemplate">
    <div class="border rounded p-3 mb-3 repeat-row">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Ngày khám</label>
                <input type="date" name="health_records[__INDEX__][check_date]" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kết luận</label>
                <input type="text" name="health_records[__INDEX__][status]" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Bác sĩ</label>
                <input type="text" name="health_records[__INDEX__][doctor_name]" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Cơ sở y tế</label>
                <input type="text" name="health_records[__INDEX__][hospital]" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">Ghi chú</label>
                <textarea name="health_records[__INDEX__][notes]" rows="2" class="form-control"></textarea>
            </div>
        </div>
        <div class="text-end mt-2">
            <button type="button" class="btn btn-outline-danger btn-sm" data-remove-row>Xoá</button>
        </div>
    </div>
</template> --}}

