@extends('Admin.layouts.main')

@section('content')
    <section class="">
        <div class="row">

        </div>

        <div class="row" id="table-striped">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if (session()->has('success'))
                            <div class="alert alert-success p-1">
                                {{ session()->get('success') }}
                            </div>
                        @endif
                        <form id="form-crawl" class="form-validate" method="post" autocomplete="off"
                            action="{{ route('admin.display.update') }}">
                            @csrf
                            <div class="row mb-1">
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="col-12 mb-1">
                                        {!! FormUi::text('title', 'Title site', $errors, $setting, []) !!}
                                    </div>
                                    <div class="col-12 mb-1">
                                        {!! FormUi::text('description', 'Description site', $errors, $setting, []) !!}
                                    </div>
                                    <div class="col-12 mb-1">
                                    {!! FormUi::checkbox('index', 'Index', '', $errors, $setting) !!}
                                    </div>
                                    <div class="col-12 mb-1">
                                        {!! FormUi::textarea('header_script', 'Header script', $errors, $setting, []) !!}
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="col-12 mb-1">
                                        {!! FormUi::textarea('body_script', 'Body script', $errors, $setting, []) !!}
                                    </div>
                                    <div class="col-12 mb-1">
                                        {!! FormUi::textarea('footer_script', 'Footer script', $errors, $setting, []) !!}
                                    </div>
                                </div>
                            </div>



                            <div class="">
                                <button type="submit" class="btn btn-success me-1">
                                    Cập nhật
                                </button>
                                {{-- <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-1">
                                <i data-feather='rotate-ccw'></i>
                                Quay lại
                            </a> --}}
                            </div>
                        </form>
                    </div>
                    <div class="mb-3">
                        <h5>Editor Loads for Current Billing Cycle</h5>
                        <p><strong>Usage history:</strong> <span id="editor_loads">Đang tải...</span></p>
                    </div>
                    <div class="container" style="text-allgin:center;">
                            <h3>Cấu hình TinyMCE</h3>
                            <form action="{{ route('settings.update') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="tinymce_api_key" class="form-label">TinyMCE API Key</label><br>
                                    <small>Lấy key vui lòng truy cập <a class="dashed-border" href="https://www.tiny.cloud/my-account/integrate/#html" target="_blank" rel="noopener noreferrer">
                        Click vào trong này
                    </a> để lấy api key rồi add vào bên dưới nhé</small><br>
                                    <input type="text" name="tinymce_api_key" id="tinymce_api_key" class="form-control" value="{{ $tinymce_api_key }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Lưu</button>
                            </form>
                    </div>
                    <!-- <div style="width: 300px; height: 300px; margin: auto;">
    <canvas id="tinymceUsageChart"></canvas>
</div> -->
    <style>
.dashed-border {
    border: 2px dashed #007bff; /* Màu xanh */
    padding: 5px;
    display: inline-block;
    text-decoration: none;
    color: #007bff;
}
.dashed-border:hover {
    border-color: #0056b3; /* Màu xanh đậm hơn khi hover */
}
</style>
                    {{-- <div class="table-responsive">
                    <table id="tableProducts" class="table table-striped">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>IP đăng nhập<br>gần nhất</th>
                            <th>TG đăng nhập<br>gần nhất</th>
                            <th>Tác vụ</th>
                        </tr>
                        </thead>
                        <tbody>
                     
                        </tbody>
                    </table>
                </div> --}}

                    <div class="row">
                        <div class="col-sm-12 mt-1 d-flex justify-content-center">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
    // document.addEventListener("DOMContentLoaded", function () {
    //     fetch("{{ route('settings.getTinyMCEUsage') }}")
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.error) {
    //                 document.getElementById("editor_loads").innerText = "Lỗi: " + data.error;
    //             } else {
    //                 document.getElementById("editor_loads").innerText = `${data.editor_loads} / ${data.limit}`;
    //             }
    //         })
    //         .catch(error => {
    //             document.getElementById("editor_loads").innerText = "Không thể tải dữ liệu";
    //         });
    // });

    document.addEventListener("DOMContentLoaded", function () {
        fetch("{{ route('settings.trackTinyMCEUsage') }}", { method: "POST", headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" } })
            .then(response => response.json())
            .then(data => {
                document.getElementById("editor_loads").innerText = `${data.tinymce_usage} / 1,000`;
            })
            .catch(error => {
                document.getElementById("editor_loads").innerText = "Không thể tải dữ liệu";
            });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetch("{{ route('settings.getTinyMCEUsage') }}")
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }

                const ctx = document.getElementById('tinymceUsageChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Đã sử dụng', 'Còn lại'],
                        datasets: [{
                            data: [data.editor_loads, data.limit - data.editor_loads],
                            backgroundColor: ['#ff6384', '#36a2eb'],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            });
    });
</script>
@endsection
