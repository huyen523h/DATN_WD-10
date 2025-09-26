@extends('admin.layout')

@section('content')
<div class="container" style="max-width: 860px;">
    <h1 class="h4 mb-3">Cấu hình hệ thống</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label class="form-label">Tên website</label>
                    <input class="form-control" value="Tour365">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email liên hệ</label>
                    <input class="form-control" value="support@tour365.vn">
                </div>
                <div class="mb-3">
                    <label class="form-label">Hotline</label>
                    <input class="form-control" value="1900 1234">
                </div>
                <button type="button" class="btn btn-primary">Lưu</button>
            </form>
        </div>
    </div>
</div>
@endsection


