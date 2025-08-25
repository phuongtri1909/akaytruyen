@extends('Admin.layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-heart text-danger"></i>
                        Quản lý Donation - {{ $story->name }}
                    </h4>
                    <p class="card-text">Quản lý danh sách người đã donate cho truyện này</p>
                </div>
                <div class="card-body">
                    <!-- Form thêm donation mới -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-plus text-success"></i> Thêm Donation Mới
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form id="addDonationForm">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="name" class="form-label">Tên người donate <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name" name="name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="amount" class="form-label">Số tiền (VND) <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="amount" name="amount" min="1000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="donated_at" class="form-label">Ngày donate</label>
                                                    <input type="datetime-local" class="form-control" id="donated_at" name="donated_at">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-outline-primary">
                                                    Thêm Donation
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng danh sách donation -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên người donate</th>
                                    <th>Số tiền (VND)</th>
                                    <th>Ngày donate</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="donationsTableBody">
                                @foreach($donations as $index => $donation)
                                    <tr data-id="{{ $donation->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $donation->name }}</td>
                                        <td class="text-success fw-bold">{{ number_format($donation->amount, 0) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($donation->donated_at)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-warning edit-donation" data-id="{{ $donation->id }}">
                                                Sửa
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-donation" data-id="{{ $donation->id }}">
                                                Xóa
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $donations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Donation -->
    <div class="modal fade" id="editDonationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa Donation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editDonationForm">
                        <input type="hidden" id="edit_donation_id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Tên người donate <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_amount" class="form-label">Số tiền (VND) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_amount" name="amount" min="1000" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_donated_at" class="form-label">Ngày donate</label>
                            <input type="datetime-local" class="form-control" id="edit_donated_at" name="donated_at">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-outline-primary" id="saveEditDonation">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-custom')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const storyId = {{ $story->id }};
    const routes = {
        store: '{{ route("admin.donations.store", $story->id) }}',
        update: '{{ route("admin.donations.update", ":id") }}',
        destroy: '{{ route("admin.donations.destroy", ":id") }}'
    };

    // Thêm donation mới
    $('#addDonationForm').on('submit', function(e) {
        e.preventDefault();

        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();

        // Disable button và hiển thị loading
        submitBtn.prop('disabled', true).text('Đang thêm...');

        const formData = new FormData(this);

        fetch(routes.store, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire('Thành công!', data.message, 'success');
                location.reload();
            } else {
                Swal.fire('Lỗi!', data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Lỗi!', 'Có lỗi xảy ra khi thêm donation.', 'error');
        })
        .finally(() => {
            // Re-enable button
            submitBtn.prop('disabled', false).text(originalText);
        });
    });

    // Sửa donation (sử dụng event delegation)
    $(document).on('click', '.edit-donation', function() {
        const donationId = $(this).data('id');
        const row = $(this).closest('tr');

        $('#edit_donation_id').val(donationId);
        $('#edit_name').val(row.find('td:eq(1)').text());
        $('#edit_amount').val(row.find('td:eq(2)').text().replace(/[^\d]/g, ''));

        // Format datetime for input
        const donatedAt = row.find('td:eq(3)').text(); // Format: dd/mm/yyyy HH:mm
        const parts = donatedAt.split(' ');
        const datePart = parts[0].split('/'); // [dd, mm, yyyy]
        const timePart = parts[1] || '00:00'; // HH:mm

        // Tạo date string theo format yyyy-mm-ddTHH:mm
        const formattedDate = `${datePart[2]}-${datePart[1].padStart(2, '0')}-${datePart[0].padStart(2, '0')}T${timePart}`;
        $('#edit_donated_at').val(formattedDate);

        $('#editDonationModal').modal('show');
    });

    // Lưu thay đổi donation
    $('#saveEditDonation').on('click', function() {
        const saveBtn = $(this);
        const originalText = saveBtn.text();

        // Disable button và hiển thị loading
        saveBtn.prop('disabled', true).text('Đang lưu...');

        const donationId = $('#edit_donation_id').val();
        const formData = new FormData($('#editDonationForm')[0]);

        fetch(routes.update.replace(':id', donationId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire('Thành công!', data.message, 'success');
                $('#editDonationModal').modal('hide');
                location.reload();
            } else {
                Swal.fire('Lỗi!', data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Lỗi!', 'Có lỗi xảy ra khi cập nhật donation.', 'error');
        })
        .finally(() => {
            // Re-enable button
            saveBtn.prop('disabled', false).text(originalText);
        });
    });

    // Xóa donation (sử dụng event delegation)
    $(document).on('click', '.delete-donation', function() {
        const donationId = $(this).data('id');

        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: 'Hành động này không thể hoàn tác!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa ngay!',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(routes.destroy.replace(':id', donationId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'DELETE'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire('Thành công!', data.message, 'success');
                        location.reload();
                    } else {
                        Swal.fire('Lỗi!', data.message || 'Có lỗi xảy ra', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Lỗi!', 'Có lỗi xảy ra khi xóa donation.', 'error');
                });
            }
        });
    });
});
</script>
@endpush
