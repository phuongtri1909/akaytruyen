@extends('Admin.layouts.main')
@section('page_title')
    Quản lý thông tin donate - {{ $story->name }}
@endsection

@section('content')
    <section class="app-user-list">
        <div class="row" id="table-striped">
            <div class="col-12">
                <div class="content-card">
                    <div class="card-top">
                        <h4 class="card-title">Quản lý thông tin donate cho truyện: {{ $story->name }}</h4>
                    </div>
                    <div class="card-content">
                        @if (session()->has('success'))
                            <div class="alert alert-success p-1">
                                {{ session()->get('success') }}
                            </div>
                        @endif

                        <!-- Form thêm donate mới -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="content-card">
                                    <div class="card-top">
                                        <h5>Thêm thông tin donate mới</h5>
                                    </div>
                                    <div class="card-content">
                                        <form id="addDonateForm" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="bank_name">Tên ngân hàng/dịch vụ <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="bank_name" name="bank_name" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="donate_info">Thông tin donate (STK, gmail, etc.)</label>
                                                        <input type="text" class="form-control" id="donate_info" name="donate_info">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="image">Hình ảnh QR code</label>
                                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-outline-primary">Thêm donate</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Danh sách donate -->
                        <div class="row">
                            <div class="col-12">
                                <div class="content-card">
                                    <div class="card-top">
                                        <h5>Danh sách thông tin donate</h5>
                                    </div>
                                    <div class="card-content">
                                        @if($story->donates->count() > 0)
                                            <div class="row">
                                                @foreach($story->donates as $donate)
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-8">
                                                                        <h6 class="card-title">{{ $donate->bank_name }}</h6>
                                                                        @if($donate->donate_info)
                                                                            <p class="card-text">{{ $donate->donate_info }}</p>
                                                                        @endif
                                                                    </div>
                                                                                                                                         <div class="col-md-4">
                                                                         @if($donate->image)
                                                                             <img src="{{ Storage::url($donate->image) }}" alt="QR Code" class="img-fluid" style="max-width: 100px;">
                                                                         @endif
                                                                     </div>
                                                                </div>
                                                                <div class="mt-2">
                                                                    <button class="btn btn-sm btn-outline-warning edit-donate"
                                                                            data-donate-id="{{ $donate->id }}"
                                                                            data-bank-name="{{ $donate->bank_name }}"
                                                                            data-donate-info="{{ $donate->donate_info }}">
                                                                        <i class="fas fa-edit"></i> Sửa
                                                                    </button>
                                                                    <button class="btn btn-sm btn-outline-danger delete-donate"
                                                                            data-donate-id="{{ $donate->id }}">
                                                                        <i class="fas fa-trash"></i> Xóa
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted">Chưa có thông tin donate nào.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Edit Donate -->
    <div class="modal fade" id="editDonateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa thông tin donate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editDonateForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="edit_donate_id" name="donate_id">
                        <div class="form-group mb-3">
                            <label for="edit_bank_name">Tên ngân hàng/dịch vụ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_bank_name" name="bank_name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_donate_info">Thông tin donate (STK, gmail, etc.)</label>
                            <input type="text" class="form-control" id="edit_donate_info" name="donate_info">
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_image">Hình ảnh QR code</label>
                            <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-outline-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
<script>
// Route helpers
const routes = {
    donateUpdate: (id) => `/admin/donate/${id}`,
    donateDelete: (id) => `/admin/donate/${id}`
};
$(document).ready(function() {
    // Thêm donate mới
    $('#addDonateForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('{{ route("admin.donate.store", $story->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.AkayTruyen.csrfToken,
            },
            body: formData
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.message || 'Có lỗi xảy ra',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Có lỗi xảy ra khi thêm donate',
                showConfirmButton: false,
                timer: 2000
            });
        });
    });

    // Mở modal edit
    $('.edit-donate').on('click', function() {
        const donateId = $(this).data('donate-id');
        const bankName = $(this).data('bank-name');
        const donateInfo = $(this).data('donate-info');

        $('#edit_donate_id').val(donateId);
        $('#edit_bank_name').val(bankName);
        $('#edit_donate_info').val(donateInfo);

        $('#editDonateModal').modal('show');
    });

    // Cập nhật donate
    $('#editDonateForm').on('submit', function(e) {
        e.preventDefault();

        const donateId = $('#edit_donate_id').val();
        const formData = new FormData(this);
        const url = routes.donateUpdate(donateId);

        console.log('Update URL:', url);
        console.log('Donate ID:', donateId);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.AkayTruyen.csrfToken,
                'X-HTTP-Method-Override': 'PUT',
            },
            body: formData
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.message || 'Có lỗi xảy ra',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Có lỗi xảy ra khi cập nhật donate',
                showConfirmButton: false,
                timer: 2000
            });
        });
    });

    // Xóa donate
    $('.delete-donate').on('click', function() {
        const donateId = $(this).data('donate-id');

        Swal.fire({
            text: 'Bạn có muốn xóa thông tin donate này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Có',
            cancelButtonText: 'Không',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ms-1'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                fetch(routes.donateDelete(donateId), {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.AkayTruyen.csrfToken,
                    }
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: data.message || 'Có lỗi xảy ra',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Có lỗi xảy ra khi xóa donate',
                        showConfirmButton: false,
                        timer: 2000
                    });
                });
            }
        });
    });
});
</script>
@endpush
