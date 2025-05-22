@extends('Admin.layouts.main')
@section('page_title', 'Thêm quyền mới')

@section('content')
    <div class="row">
        <form method="POST" action="{{ route('admin.permissions.store') }}">
            @csrf
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tạo quyền mới</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-1">
                            <label class="form-label" for="name">Tên quyền <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Nhập tên quyền (ví dụ: xem_danh_sach_user)"
                                value="{{ old('name') }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="group">Thuộc menu (nhóm)</label>
                            <input
                                type="text"
                                id="group"
                                name="group"
                                class="form-control @error('group') is-invalid @enderror"
                                placeholder="Nhập tên nhóm (ví dụ: user, post, product...)"
                                value="{{ old('group') }}"
                            >
                            @error('group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                            <i data-feather="arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i data-feather="save"></i> Lưu quyền
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts-custom')
    <script>
        $(window).on('load', function () {
            if (feather) {
                feather.replace({ width: 14, height: 14 });
            }
        });
    </script>
@endpush
