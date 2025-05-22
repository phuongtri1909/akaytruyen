
<div class="section-list-category bg-light p-2 rounded card-custom">
    <div class="head-title-global mb-2">
        <div class="col-12 col-md-12 head-title-global__left">
            <h2 class="mb-0 border-bottom border-secondary pb-1">
                <span href="#" class="d-block text-decoration-none text-dark fs-4" title="Truyện đang đọc">Live Chat Cộng Đồng</span>
            </h2>
            @if (!Auth()->check() || (Auth()->check() && Auth()->user()->ban_comment == false))
                @include('Frontend.sections.components.comment', ['pinnedComments' => $pinnedComments, 'regularComments' => $regularComments])
            @else
                <div class="text-center py-5">
                    <i class="fas fa-sad-tear fa-4x text-muted mb-3 animate__animated animate__shakeX"></i>
                    <h5 class="text-danger">Bạn đã bị cấm bình luận!</h5>
                </div>
            @endif
        </div>
    </div>
</div>
