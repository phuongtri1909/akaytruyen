@push('styles')
    <style>
        @media (min-width: 768px) {
            .w-md-auto {
                width: auto !important;
            }
        }
    </style>
@endpush

<section id="all-chapter">
    <div class="container">
        <div class="section-title d-flex justify-content-between align-items-center">
            <h5>DANH SÁCH CHƯƠNG</h5>

        </div>


        <div class="d-block d-md-flex align-items-center mb-3">
            <select class="form-select w-100 w-md-auto" id="chapter-range">
                @foreach ($ranges as $range)
                    <option value="{{ $range['start'] }},{{ $range['end'] }}">
                        Chương {{ $range['start'] }} - {{ $range['end'] }}
                    </option>
                @endforeach
            </select>

            <div class="form-check ms-0 ms-md-3 mt-3 mt-md-0">
                <input class="form-check-input" type="checkbox" id="orderToggle">
                <label class="form-check-label" for="orderToggle">
                    Chương cũ lên trước
                </label>
                <div class="form-check ms-3">


                </div>
                <div>
                    <input class="form-check-input" type="checkbox" id="savedChaptersToggle">
                    <label class="form-check-label" for="savedChaptersToggle">
                        Hiển thị chương đã lưu
                    </label>
                </div>
            </div>
        </div>

        <div class="list-chapter">
            <div id="chapters-container">
                @include('components.chapter-items', ['chapters' => $chapters])

            </div>
        </div>


    </div>
</section>

@push('scripts')
    <script>
        $(document).ready(function() {

            function loadChapters() {

                const [start, end] = $('#chapter-range').val().split(',');
                const isOldFirst = $('#orderToggle').is(':checked');

                $.ajax({
                    url: '{{ route('home') }}',
                    data: {
                        start: start,
                        end: end,
                        old_first: isOldFirst
                    },
                    success: function(response) {
                        $('#chapters-container').html(response.html);
                    },
                    error: function() {
                        showToast('Có lỗi xảy ra khi tải dữ liệu', 'error');
                    }
                });
            }


            $('#chapter-range').change(loadChapters);
            $('#orderToggle').change(loadChapters);
        });
        $(document).ready(function() {
            function loadChapters() {
                const [start, end] = $('#chapter-range').val().split(',');
                const isOldFirst = $('#orderToggle').is(':checked');
                const showSaved = $('#savedChaptersToggle').is(':checked');

                if (showSaved) {
                    $.ajax({
                        url: '{{ route('saved.chapters') }}',
                        success: function(response) {
                            $('#chapters-container').html(response.html);
                        },
                        error: function() {
                            showToast('bạn chưa có chương nào để lưu', 'error');
                        }
                    });
                } else {
                    $.ajax({
                        url: '{{ route('home') }}',
                        data: {
                            start: start,
                            end: end,
                            old_first: isOldFirst
                        },
                        success: function(response) {
                            $('#chapters-container').html(response.html);
                        },
                        error: function() {
                            showToast('Có lỗi xảy ra khi tải dữ liệu', 'error');
                        }
                    });
                }
            }

            $('#chapter-range').change(loadChapters);
            $('#orderToggle').change(loadChapters);
            $('#savedChaptersToggle').change(loadChapters);
        });
    </script>
@endpush
