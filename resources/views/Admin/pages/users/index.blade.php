@extends('Admin.layouts.main')
{{-- @push('content-header')
    @can('them_nguoi_dung')
        <div class="col ms-auto">
            @include('Admin.component.btn-add', ['title'=>'Thêm', 'href'=>route('admin.users.create')])
        </div>
    @endcan
@endpush --}}
@section('content')
    <section class="app-user-list">
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{ number_format($users->total()) }}</h3>
                            <span>Tổng số người dùng</span>
                        </div>
                        <div class="avatar bg-light-success p-50">
                            <span class="avatar-content">
                              <i data-feather="user" class="font-medium-4"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{ number_format($user_inactive) }}</h3>
                            <span>Số người dùng không hoạt động</span>
                        </div>
                        <div class="avatar bg-light-secondary p-50">
                            <span class="avatar-content">
                              <i data-feather="user" class="font-medium-4"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="row" id="table-striped">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row flex-row-reverse">
                            <div class="col">
                                {!! 
                                    \App\Helpers\SearchFormHelper::getForm(
                                        route('admin.users.index'),
                                        'GET',
                                        [
                                            [
                                                "type" => "text",
                                                "name" => "search[id]",
                                                "placeholder" => "ID người dùng",
                                                "defaultValue" => request('search.id'),
                                            ],
                                            [
                                                "type" => "text",
                                                "name" => "search[keyword]",
                                                "placeholder" => "Tìm người dùng",
                                                "defaultValue" => request('search.keyword'),
                                            ],
                                            [
                                                "type" => "selection",
                                                "name" => "search[status]",
                                                "defaultValue" => request('search.status'),
                                                "options" => ['' => '- Trạng thái -'] + \App\Models\User::STATUS_TEXT,
                                            ],
                                            [
                                                "type" => "selection",
                                                "name" => "search[role_id]",
                                                "defaultValue" => request('search.role_id'),
                                                "options" => ['' => '- Vai trò -'] + $roles->pluck("name", "id")->toArray(),
                                            ],
                                        ]
                                    ) 
                                !!}
                            </div>

                            
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tableProducts" class="table table-striped">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>id</th>
                                <th>Avatar</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>IP đăng nhập<br>gần nhất</th>
                                <th>TG đăng nhập<br>gần nhất</th>
                                <th>Số tiền<br>Donate</th>
                                <th>Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $users as $user )
                                <tr>
                                <td><input type="checkbox" class="userCheckbox" value="{{ $user->id }}"></td>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="avatar-wrapper">
                                            @if($user->avatar)
                                                <div class="avatar">
                                                    <img
                                                        src="{{ $user->avatar }}"
                                                        alt="Avatar" width="32" height="32"/>
                                                </div>
                                            @else
                                                <div
                                                    class="avatar bg-light-{{ array_rand(['info'=>'info', 'success'=>'success', 'warning'=>'warning', 'danger'=>'danger']) }}">
                                                    <span class="avatar-content">
                                                        {{ strtoupper(substr( $user->email ?? $user->username, 0, 1 )) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    {{-- <td>
                                        @foreach( $user->domains as $_domain )
                                            <span class="badge rounded-pill badge-light-primary">
                                                {{ $_domain->name }}
                                            </span>
                                        @endforeach
                                    </td> --}}
                                    <td>
                                        @foreach( $user->roles as $_role )
                                            <span
                                                class="badge rounded-pill badge-light-{{ in_array($_role->name, [ \App\Models\User::ROLE_ADMIN ]) ? 'danger' : 'primary' }}">
                                                {{ $_role->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-pill
                                            badge-light-{{ $user->status == \App\Models\User::STATUS_ACTIVE ? 'success' : 'secondary' }}">
                                            {{ \App\Models\User::STATUS_TEXT[$user->status] ?? '' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->ip_address }}</td>
                                    <td>{{ $user->last_login_time ? \Carbon\Carbon::create($user->last_login_time)->format('d-m-Y H:i:s') : '' }}</td>
                                    <td>{{ number_format($user->donate_amount) }} VND</td>
                                    <td>
                                        @can('sua_nguoi_dung')
                                            <a class="btn btn-sm btn-icon"
                                               href="{{ route('admin.users.edit', $user->id) }}">
                                                <i data-feather="edit" class="font-medium-2 text-body"></i>
                                            </a>
                                            
                                        @endcan
                                        @can('xoa_nguoi_dung')
                                            <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-icon btn-danger" onclick="confirmDelete({{ $user->id }})">
                                                    <i data-feather="trash" class="font-medium-2"></i>
                                                </button>
                                            </form>
                                        @endcan


                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <br>
                        @can('xoa_nguoi_dung')
                            <button id="deleteSelected" class="btn btn-sm btn-icon btn-danger">
                                <i data-feather="trash" class="font-medium-2"></i> Xóa đã chọn
                            </button>
                        @endcan

                    </div>

                    <div class="row">
                        <div class="col-sm-12 mt-1 d-flex justify-content-center">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Cột Form Thêm Donate -->
            <div class="col-lg-3 col-sm-6">
                <div class="donate-container h-100 d-flex flex-column p-3 position-relative">
                    <h2 class="text-black text-center border-bottom pb-2"><b>Thêm Donate</b></h2>

                    <!-- Form nhập donate -->
                    <form action="{{ route('donate.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tên người donate</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số tiền (VND)</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Thêm Donate</button>
                    </form>
                </div>
            </div>

            <!-- Cột Danh Sách Donate -->
            <div class="col-lg-6 col-sm-12">
                <div class="donate-container h-100 d-flex flex-column p-3 position-relative">
                    <h4 class="text-center border-bottom pb-2">Danh sách Donate</h4>


                    
                    <div class="table-responsive scrollable-table">
                        <table class="table table-bordered text-center align-middle donate-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên</th>
                                    <th>Số tiền (VND)</th>
                                    <th>Thời gian</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donations as $index => $donation)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $donation->name }}</td>
                                        <td class="text-success fw-bold">{{ number_format($donation->amount, 0) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($donation->donated_at)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <form action="{{ route('donate.destroy', $donation->id) }}" method="POST" onsubmit="return confirmDelete(event, this)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Hiển thị phân trang -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $donations->links() }}
                        </div>
                    </div>
                </div>

                


                

            </div>
            <div class="donate-container h-100 d-flex flex-column p-3 position-relative">
                        <h4 class="text-center border-bottom pb-2">Tổng doanh thu theo tháng</h4>
                        <div class="table-responsive scrollable-table">
                            <table class="table table-bordered text-center align-middle">
                                <thead>
                                    <tr>
                                        <th>Tháng</th>
                                        <th>Năm</th>
                                        <th>Tổng doanh thu (VND)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlyRevenue as $revenue)
                                        <tr>
                                            <td>{{ $revenue->month }}</td>
                                            <td>{{ $revenue->year }}</td>
                                            <td class="text-success fw-bold">
                                                {{ number_format($revenue->total, 0) }} VND
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>



    </div>

    </section>
    <style>.pagination {
    display: flex;
    justify-content: center;
    margin-top: 10px;
}
</style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: "Bạn có chắc chắn?",
            text: "Hành động này không thể hoàn tác!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Xóa ngay!",
            cancelButtonText: "Hủy"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-user-${userId}`).submit();
            }
        });
    }
</script>
<script>
    document.getElementById('selectAll').addEventListener('click', function () {
        let checkboxes = document.querySelectorAll('.userCheckbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('deleteSelected').addEventListener('click', function () {
        let selected = [];
        document.querySelectorAll('.userCheckbox:checked').forEach(cb => selected.push(cb.value));

        if (selected.length === 0) {
            Swal.fire("Chưa chọn người dùng nào!", "", "warning");
            return;
        }

        Swal.fire({
            title: "Bạn có chắc chắn?",
            text: `Bạn sắp xóa ${selected.length} người dùng!`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Xóa ngay!",
            cancelButtonText: "Hủy"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('admin.users.bulkDelete') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ user_ids: selected })
                }).then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire("Đã xóa thành công!", "", "success").then(() => location.reload());
                    } else {
                        Swal.fire("Lỗi khi xóa!", "", "error");
                    }
                });
            }
        });
    });
</script>
@endsection
