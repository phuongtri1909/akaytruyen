
<div class="row">
    @foreach ($chapters->chunk(ceil($chapters->count() / 2)) as $chunk)
        <div class="col-md-6">
            <ul class="chapter-list text-muted">
                @foreach ($chunk as $chapter)
                    <li class="mt-2">
                        <a href="{{ route('chapter', ['slugStory' => $chapter->story->slug, 'slugChapter' => $chapter->slug]) }}" class="text-muted">
                            <span class="date">
                                <span>{{ $chapter->created_at->format('d') }}</span>
                                <span class="fs-7">{{ $chapter->created_at->format('m') }}</span>
                            </span>

                            <span class="chapter-title ms-2">
                                Chương {{ $chapter->chapter }}: {{ $chapter->name }}
                                @if ($chapter->created_at->isToday())
                                    <span class="new-badge">New</span>
                                @endif
                            </span>
                        </a>
                        <hr class="my-2 opacity-25">
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>


<style>
       .chapter-list li a {
    display: flex
;
    align-items: center;
    text-decoration: none;
}
        .chapter-list .date {
    font-weight: bold;
    border: 1px solid;
    display: flex
;
    border-radius: 4px;
    text-align: center;
    width: 45px;
    height: 51px;
    line-height: 1.2;
    flex-direction: column;
    justify-content: center;
}
.chapter-list {
    list-style: none; /* Xóa dấu chấm trước mỗi <li> */
    padding: 0; /* Loại bỏ khoảng trắng mặc định của danh sách */
}

.chapter-list li {
    border-bottom: none !important; /* Loại bỏ đường kẻ dưới mỗi mục */
    margin-bottom: 8px; /* Tạo khoảng cách giữa các chương */
}

.new-badge {
    color: #ff0000;
    font-weight: bold;
    margin-left: 5px;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    animation: pulse 1s ease-in-out infinite;
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.7; }
    100% { transform: scale(1); opacity: 1; }
}

@media (max-width: 768px) {
    .chapter-list {
        display: block;
    }
}

</style>