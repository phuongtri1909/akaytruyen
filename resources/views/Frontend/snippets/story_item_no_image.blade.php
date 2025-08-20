<div class="story-item-no-image wuxia-story-item">
    <div class="story-item-no-image__name d-flex align-items-center">
        <h3 class="me-1 mb-0 d-flex align-items-center wuxia-story-title">
            <svg class="wuxia-arrow" style="width: 10px; margin-right: 5px;" xmlns="http://www.w3.org/2000/svg" height="1em"
                viewBox="0 0 320 512">
                <path
                    d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z" />
            </svg>
            <a href="{{ route('story', ['slug' => $story->slug]) }}"
                class="text-decoration-none text-dark fs-6 hover-title text-one-row story-name wuxia-story-link">{{ $story->name }}</a>
        </h3>
        @if ($story->is_new)
            <span class="badge text-bg-info text-light me-1 wuxia-badge wuxia-badge--new">New</span>
        @endif

        @if ($story->is_full)
            <span class="badge text-bg-success text-light me-1 wuxia-badge wuxia-badge--full">Full</span>
        @endif

        @if ($story->is_hot)
            <span class="badge text-bg-danger text-light wuxia-badge wuxia-badge--hot">Hot</span>
        @endif
    </div>

    <div class="story-item-no-image__categories ms-2 d-none d-lg-block wuxia-categories">
        <p class="mb-0">
            @foreach ($story->categories as $category)
                <a href="{{ route('category', ['slug' => $category->slug]) }}"
                    class="hover-title text-decoration-none text-dark category-name wuxia-category-link">{{ $category->name }}@if (!$loop->last)
                        ,
                    @endif </a>
            @endforeach
        </p>
    </div>


    <div class="story-item-no-image__chapters ms-2 wuxia-chapters">
    @if ($story->chapter_last)
        @php
            $createdAt = \Carbon\Carbon::parse($story->chapter_last->created_at);
            $isNew = $createdAt->diffInHours(now()) < 24;
        @endphp

        <a href="{{ url($story->slug . '/' . $story->chapter_last->slug) }}"
            class="hover-title text-decoration-none text-info wuxia-chapter-link">
            Chương {{ $story->chapter_last->chapter }}
        </a>

        @if ($isNew)
            <span class="badge bg-danger ms-1 wuxia-badge wuxia-badge--new-chapter">NEW</span>
        @endif
    @endif
</div>




    {{-- <div class="story-item-no-image__updated-at ms-2 d-none d-lg-block">
        <p class="text-secondary mb-0">
            @if ($story->updated_at->diffInMinutes(\Carbon\Carbon::now()) > 60)
            {{ $story->updated_at->diffInHours(\Carbon\Carbon::now()) }} giờ trước
            @else
            {{ $story->updated_at->diffInMinutes(\Carbon\Carbon::now()) }} phút trước
            @endif
        </p>
    </div> --}}
</div>

@once
@push('styles')
<style>
/* Wuxia Story Item No Image Theme */
.wuxia-story-item {
    background: linear-gradient(135deg, #fbf6e6 0%, #efe4c9 100%);
    border: 1px solid #caa83b;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 8px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(202, 168, 59, 0.2);
    transition: all 0.3s ease;
}

.wuxia-story-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: wuxiaLightning 3s infinite;
}

.wuxia-story-item::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 20% 20%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255, 215, 0, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

.wuxia-story-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(202, 168, 59, 0.4);
    border-color: #d4af37;
}

.wuxia-story-item:hover::before {
    animation: wuxiaLightning 1s infinite;
}

/* Lightning Animation */
@keyframes wuxiaLightning {
    0%, 90%, 100% { left: -100%; }
    10%, 20% { left: 100%; }
    15% { left: 50%; }
}

/* Story Title */
.wuxia-story-title {
    position: relative;
    z-index: 2;
}

.wuxia-arrow {
    fill: #d4af37;
    filter: drop-shadow(0 0 2px rgba(212, 175, 55, 0.5));
    animation: wuxiaArrowGlow 2s ease-in-out infinite alternate;
}

@keyframes wuxiaArrowGlow {
    0% { filter: drop-shadow(0 0 2px rgba(212, 175, 55, 0.5)); }
    100% { filter: drop-shadow(0 0 6px rgba(212, 175, 55, 0.8)); }
}

.wuxia-story-link {
    color: #4c380b !important;
    font-weight: 600;
    text-shadow: 0 1px 2px rgba(255, 255, 255, 0.5);
    transition: all 0.3s ease;
    position: relative;
}

.wuxia-story-link:hover {
    color: #d4af37 !important;
    text-shadow: 0 0 8px rgba(212, 175, 55, 0.6);
}

.wuxia-story-link::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #d4af37, #ffd700);
    transition: width 0.3s ease;
}

.wuxia-story-link:hover::after {
    width: 100%;
}

/* Badges */
.wuxia-badge {
    border-radius: 12px !important;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.7em;
    padding: 4px 8px;
    position: relative;
    overflow: hidden;
    z-index: 2;
}

.wuxia-badge::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: wuxiaBadgeShine 2s infinite;
}

@keyframes wuxiaBadgeShine {
    0% { left: -100%; }
    50% { left: 100%; }
    100% { left: 100%; }
}

.wuxia-badge--new {
    background: linear-gradient(135deg, #17a2b8, #138496) !important;
    border: 1px solid #0f6674;
    box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
}

.wuxia-badge--full {
    background: linear-gradient(135deg, #28a745, #1e7e34) !important;
    border: 1px solid #155724;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
}

.wuxia-badge--hot {
    background: linear-gradient(135deg, #dc3545, #c82333) !important;
    border: 1px solid #a71e2a;
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
    animation: wuxiaHotPulse 1.5s ease-in-out infinite;
}

@keyframes wuxiaHotPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.wuxia-badge--new-chapter {
    background: linear-gradient(135deg, #dc3545, #c82333) !important;
    border: 1px solid #a71e2a;
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
    animation: wuxiaNewChapter 1s ease-in-out infinite;
}

@keyframes wuxiaNewChapter {
    0%, 100% { transform: scale(1) rotate(0deg); }
    25% { transform: scale(1.1) rotate(-2deg); }
    75% { transform: scale(1.1) rotate(2deg); }
}

/* Categories */
.wuxia-categories {
    position: relative;
    z-index: 2;
}

.wuxia-category-link {
    color: #6c757d !important;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
}

.wuxia-category-link:hover {
    color: #d4af37 !important;
    text-shadow: 0 0 4px rgba(212, 175, 55, 0.4);
}

.wuxia-category-link::before {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 0;
    height: 1px;
    background: #d4af37;
    transition: width 0.3s ease;
}

.wuxia-category-link:hover::before {
    width: 100%;
}

/* Chapters */
.wuxia-chapters {
    position: relative;
    z-index: 2;
}

.wuxia-chapter-link {
    color: #17a2b8 !important;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    padding: 2px 6px;
    border-radius: 4px;
}

.wuxia-chapter-link:hover {
    color: #d4af37 !important;
    background: rgba(212, 175, 55, 0.1);
    text-shadow: 0 0 4px rgba(212, 175, 55, 0.4);
}

.wuxia-chapter-link::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border: 1px solid transparent;
    border-radius: 4px;
    background: linear-gradient(45deg, transparent, rgba(212, 175, 55, 0.2), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.wuxia-chapter-link:hover::after {
    opacity: 1;
}

/* Dark Theme Support */
.dark-theme .wuxia-story-item {
    background: linear-gradient(135deg, #2c2a26 0%, #24221f 100%);
    border-color: rgba(212, 175, 55, 0.4);
    color: #fff;
}

.dark-theme .wuxia-story-link {
    color: #fff !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
}

.dark-theme .wuxia-story-link:hover {
    color: #ffd700 !important;
    text-shadow: 0 0 8px rgba(255, 215, 0, 0.6);
}

.dark-theme .wuxia-category-link {
    color: #adb5bd !important;
}

.dark-theme .wuxia-category-link:hover {
    color: #ffd700 !important;
    text-shadow: 0 0 4px rgba(255, 215, 0, 0.4);
}

.dark-theme .wuxia-chapter-link {
    color: #6fcf97 !important;
}

.dark-theme .wuxia-chapter-link:hover {
    color: #ffd700 !important;
    background: rgba(255, 215, 0, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .wuxia-story-item {
        padding: 10px 12px;
        margin-bottom: 6px;
    }

    .wuxia-story-link {
        font-size: 0.9em;
    }

    .wuxia-badge {
        font-size: 0.6em;
        padding: 3px 6px;
    }
}
</style>
@endpush
@endonce
