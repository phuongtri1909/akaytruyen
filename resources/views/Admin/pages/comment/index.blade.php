@extends('Admin.layouts.main')
@push('content-header')
    @can('xem_comment_data')
        <div class="col ms-auto">
            {{-- @include('Admin.component.btn-add', ['title'=>'Thêm', 'href'=>route('admin.crawl.create')]) --}}
        </div>
    @endcan
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Danh sách bình luận</h5>
                        </div>
                    </div>
                </div><br>
                <div class="card-body px-0 pt-0 pb-2">


                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Thành viên
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Bình luận
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Ngày tạo
                                    </th>

                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($comments as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <p class="text-xs font-weight-bold mb-0">
                                                @if ($item->user)
                                                    <a
                                                        href="{{ route('admin.users.edit', $item->user->id) }}">{{ $item->user->name }}</a>
                                                @else
                                                    Khách hàng không tồn tại
                                                @endif
                                            </p>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->comment }}</p>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->created_at }}</p>
                                        </td>

                                        <td class="text-center">
                                            @can('xoa_nguoi_dung')
                                                <form action="{{ route('delete.comments', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa bình luận này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                                </form>
                                            @endcan

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <x-pagination :paginator="$comments" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).on('submit', '.delete-form', function(e) {
            e.preventDefault();
            let form = $(this);

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        showToast(response.message, 'success'); // Hiển thị thông báo thành công
                        form.closest('tr').fadeOut(300, function() {
                            $(this).remove();
                        }); // Xóa hàng chứa bình luận
                    } else {
                        showToast(response.message || 'Không thể xóa bình luận', 'error');
                    }
                },
                error: function(xhr) {
                    showToast('Có lỗi xảy ra khi xóa bình luận', 'error');
                }
            });
        });

        // Hàm hiển thị thông báo góc màn hình
        function showToast(message, type = 'info') {
            const bgColor = type === 'success' ? 'green' : 'red';
            const toast = $(`
        <div class="toast-message" style="
            position: fixed; bottom: 20px; right: 20px;
            background: ${bgColor}; color: white;
            padding: 10px 15px; border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 9999;
        ">
            ${message}
        </div>
    `);
            $('body').append(toast);
            setTimeout(() => toast.fadeOut(500, function() {
                $(this).remove();
            }), 3000);
        }
    </script>
@endpush
