<div>
    @auth
        <div class="border p-3 mb-2 rounded bg-light">
            <div class="d-flex align-items-center mb-3">
                @include('Frontend.components.user-avatar', ['user' => auth()->user()])

                @if (!auth()->user()->ban_comment)
                    <textarea wire:model.lazy="content" class="form-control ms-2" placeholder="Nội dung bình luận..." rows="2"
                        maxlength="1000"></textarea>
                    <button wire:click="postComment" class="btn btn-primary ms-2" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="postComment">Gửi</span>
                        <span wire:loading wire:target="postComment">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                @else
                    <p class="text-danger ms-2">Bạn đã bị cấm bình luận.</p>
                @endif
            </div>
        </div>
    @endauth

    {{-- Comment List Container với Scroll Detection --}}
    <div class="comment-list" id="comment-container" style="max-height: 600px; overflow-y: auto;">
        @forelse($comments as $comment)
            @if ($comment->user)
                @include('Frontend.components.single-comment', ['comment' => $comment])
            @endif
        @empty
            <div class="text-center p-4">
                <p class="text-muted">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
            </div>
        @endforelse

        {{-- Loading indicator khi scroll --}}
        <div wire:loading wire:target="loadMoreComments" class="text-center p-3">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Đang tải thêm...</span>
            </div>
        </div>

        {{-- Scroll sentinel --}}
        @if ($hasMoreComments)
            <div id="scroll-sentinel" class="text-center p-2">
                <small class="text-muted">Cuộn xuống để xem thêm bình luận...</small>
            </div>
        @else
            <div class="text-center p-2">
                <small class="text-muted">Đã hiển thị hết bình luận</small>
            </div>
        @endif
    </div>
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/comment-styles.css') }}">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const commentContainer = document.getElementById('comment-container');
                const scrollSentinel = document.getElementById('scroll-sentinel');
                let isLoading = false;

                // Intersection Observer để detect khi scroll sentinel xuất hiện
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && !isLoading) {
                            isLoading = true;
                            // Gọi Livewire method để load thêm comments
                            @this.call('loadMoreOnScroll').then(() => {
                                isLoading = false;
                            });
                        }
                    });
                }, {
                    root: commentContainer,
                    rootMargin: '100px',
                    threshold: 0.1
                });

                // Bắt đầu observe scroll sentinel
                if (scrollSentinel) {
                    observer.observe(scrollSentinel);
                }

                // Update observer khi Livewire re-render
                document.addEventListener('livewire:updated', function() {
                    const newScrollSentinel = document.getElementById('scroll-sentinel');
                    if (newScrollSentinel) {
                        observer.observe(newScrollSentinel);
                    }
                });
            });

            function confirmDelete(id) {
                Swal.fire({
                    title: 'Bạn có chắc muốn xóa?',
                    text: "Hành động này không thể hoàn tác!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy',
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('deleteComment', id);
                    }
                });
            }

            // Event listeners cho Livewire v3
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('deleteSuccess', () => {
                    Swal.fire({
                        title: 'Đã xóa!',
                        text: 'Bình luận đã được xóa.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                    });
                });
            });
        </script>
    @endpush
@endonce
