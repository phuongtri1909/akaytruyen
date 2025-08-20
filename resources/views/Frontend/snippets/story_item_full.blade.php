<div class="story-item-full text-center story-scroll-card">
    <div class="story-card-container">
        <div class="story-card-image-wrapper">
            <a href="{{ route('story', ['slug' => $story->slug]) }}" class="d-block story-item-full__image">
                <img src="{{ asset($story->image) }}" alt="{{ $story->name }}" class="img-fluid w-100 story-image" width="150" height="230" loading='lazy'>
                <div class="image-overlay">
                    <span class="overlay-text">Xem chi tiết</span>
                </div>
            </a>
        </div>
        <div class="story-card-content">
            <h3 class="fs-6 story-item-full__name fw-bold text-center mb-2">
                <a href="{{ route('story', ['slug' => $story->slug]) }}" class="text-decoration-none text-one-row story-name">
                    {{ $story->name }}
                </a>
            </h3>
            <div class="story-badge-container">
                <span class="story-item-full__badge badge">Full - {{ $story->chapter_last ? $story->chapter_last->chapter : 0 }} chương</span>
            </div>
        </div>
    </div>
</div>

<style>
.story-scroll-card {
    position: relative;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    transform-origin: center bottom;
}

.story-scroll-card:hover {
    transform: translateY(-8px) scale(1.02);
    z-index: 10;
}

.story-card-container {
    background: linear-gradient(135deg, #f8f4e8 0%, #e8dcc0 50%, #d4c4a8 100%);
    border: 2px solid #8b4513;
    border-radius: 12px;
    box-shadow:
        0 4px 8px rgba(139, 69, 19, 0.2),
        0 0 0 1px rgba(212, 175, 55, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.4);
    overflow: hidden;
    position: relative;
    transition: all 0.3s ease;
}

.story-scroll-card:hover .story-card-container {
    box-shadow:
        0 12px 24px rgba(139, 69, 19, 0.4),
        0 0 0 2px rgba(212, 175, 55, 0.5),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
    border-color: #d4af37;
}

.story-card-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.2) 0%, transparent 50%),
        radial-gradient(circle at 70% 70%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    pointer-events: none;
    z-index: 1;
}

.story-card-image-wrapper {
    position: relative;
    overflow: hidden;
    border-bottom: 2px dashed rgba(139, 69, 19, 0.3);
}

.story-item-full__image {
    position: relative;
    display: block;
    transition: all 0.3s ease;
}

.story-image {
    transition: transform 0.3s ease;
    filter: brightness(0.9) contrast(1.1);
}

.story-scroll-card:hover .story-image {
    transform: scale(1.05);
    filter: brightness(1) contrast(1.2);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(139, 69, 19, 0.8) 0%, rgba(212, 175, 55, 0.6) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 2;
}

.story-scroll-card:hover .image-overlay {
    opacity: 1;
}

.overlay-text {
    color: #f4f1e8;
    font-weight: bold;
    font-size: 0.9rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
    letter-spacing: 1px;
    transform: translateY(10px);
    transition: transform 0.3s ease;
}

.story-scroll-card:hover .overlay-text {
    transform: translateY(0);
}

.story-card-content {
    padding: 12px 8px 8px;
    position: relative;
    z-index: 2;
}

.story-item-full__name {
    margin-bottom: 8px;
    line-height: 1.3;
}

.story-name {
    color: #2c1810;
    font-family: 'Noto Serif', serif;
    font-weight: 600;
    letter-spacing: 0.3px;
    transition: color 0.3s ease;
    display: block;
    padding: 4px 0;
}

.story-scroll-card:hover .story-name {
    color: #8b4513;
    text-shadow: 0 1px 2px rgba(139, 69, 19, 0.2);
}

.story-badge-container {
    display: flex;
    justify-content: center;
    margin-top: 6px;
}

.story-item-full__badge {
    background: linear-gradient(135deg, #d4af37 0%, #b8860b 100%) !important;
    color: #2c1810 !important;
    font-weight: 600;
    font-size: 0.75rem;
    padding: 6px 12px;
    border-radius: 15px;
    border: 1px solid rgba(139, 69, 19, 0.3);
    box-shadow: 0 2px 4px rgba(139, 69, 19, 0.2);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.story-item-full__badge::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.3) 50%, transparent 100%);
    transition: left 0.5s ease;
}

.story-scroll-card:hover .story-item-full__badge::before {
    left: 100%;
}

.story-scroll-card:hover .story-item-full__badge {
    background: linear-gradient(135deg, #ffd700 0%, #d4af37 100%) !important;
    box-shadow: 0 4px 8px rgba(139, 69, 19, 0.3);
    transform: scale(1.05);
}

/* Dark theme */
.dark-theme .story-card-container {
    background: linear-gradient(135deg, #2b2b2b 0%, #242424 50%, #1c1c1c 100%);
    border-color: #8b4513;
}

.dark-theme .story-card-container::before {
    background:
        radial-gradient(circle at 30% 30%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 70% 70%, rgba(212, 175, 55, 0.05) 0%, transparent 50%);
}

.dark-theme .story-name {
    color: #f4f1e8;
}

.dark-theme .story-scroll-card:hover .story-name {
    color: #d4af37;
}

.dark-theme .story-item-full__badge {
    background: linear-gradient(135deg, #d4af37 0%, #b8860b 100%) !important;
    color: #2c1810 !important;
}

/* Animation for card entrance */
.story-scroll-card {
    animation: cardEntrance 0.6s ease-out;
    animation-fill-mode: both;
}

.story-scroll-card:nth-child(1) { animation-delay: 0.1s; }
.story-scroll-card:nth-child(2) { animation-delay: 0.2s; }
.story-scroll-card:nth-child(3) { animation-delay: 0.3s; }
.story-scroll-card:nth-child(4) { animation-delay: 0.4s; }
.story-scroll-card:nth-child(5) { animation-delay: 0.5s; }
.story-scroll-card:nth-child(6) { animation-delay: 0.6s; }
.story-scroll-card:nth-child(7) { animation-delay: 0.7s; }
.story-scroll-card:nth-child(8) { animation-delay: 0.8s; }

@keyframes cardEntrance {
    0% {
        opacity: 0;
        transform: translateY(30px) scale(0.8);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@media (max-width: 768px) {
    .story-card-content {
        padding: 10px 6px 6px;
    }

    .story-name {
        font-size: 0.85rem;
    }

    .story-item-full__badge {
        font-size: 0.7rem;
        padding: 4px 8px;
    }
}
</style>
