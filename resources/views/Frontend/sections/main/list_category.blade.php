@if (isset($categories) && count($categories))
<div class="section-list-category ancient-scroll-container">
    <div class="scroll-paper">
        <div class="scroll-header">
            <div class="scroll-title">
                <h2 class="ancient-title">
                    <span class="title-text">Thể loại truyện</span>
                    <div class="title-decoration">
                        <span class="decoration-line left"></span>
                        <span class="decoration-symbol">⚔</span>
                        <span class="decoration-line right"></span>
                    </div>
                </h2>
            </div>
        </div>
        <div class="scroll-content">
            <ul class="category-list-ancient">
                @foreach ($categories as $category)
                <li class="category-item-ancient">
                    <a href="{{ route('category', ['slug' => $category->slug]) }}" class="category-link-ancient">
                        <span class="link-text">{{ $category->name }}</span>
                        <span class="link-decoration">❖</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<style>
.ancient-scroll-container {
    margin: 20px 0;
    padding: 15px;
}

.scroll-paper {
    background: linear-gradient(135deg, #f4f1e8 0%, #e8e0c9 50%, #d4c4a8 100%);
    border: 3px solid #8b4513;
    border-radius: 15px;
    box-shadow:
        0 0 0 2px #d4c4a8,
        0 0 0 4px #8b4513,
        0 8px 16px rgba(139, 69, 19, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.4);
    position: relative;
    overflow: hidden;
}

.scroll-paper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

.scroll-header {
    background: linear-gradient(90deg, #8b4513 0%, #a0522d 50%, #8b4513 100%);
    margin: -3px -3px 0 -3px;
    padding: 15px 20px;
    border-radius: 12px 12px 0 0;
    position: relative;
}

.scroll-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.1) 50%, transparent 100%);
    border-radius: 12px 12px 0 0;
}

.ancient-title {
    margin: 0;
    text-align: center;
    position: relative;
}

.title-text {
    color: #f4f1e8;
    font-size: 1.5rem;
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    letter-spacing: 2px;
    display: block;
    margin-bottom: 8px;
}

.title-decoration {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.decoration-line {
    height: 2px;
    background: linear-gradient(90deg, transparent 0%, #f4f1e8 50%, transparent 100%);
    flex: 1;
    max-width: 80px;
}

.decoration-symbol {
    color: #f4f1e8;
    font-size: 1.2rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}

.scroll-content {
    padding: 20px;
    background:
        radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 70% 70%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
}

.category-list-ancient {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}

.category-item-ancient {
    position: relative;
}

.category-link-ancient {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: linear-gradient(135deg, rgba(139, 69, 19, 0.1) 0%, rgba(160, 82, 45, 0.1) 100%);
    border: 1px solid rgba(139, 69, 19, 0.3);
    border-radius: 8px;
    text-decoration: none;
    color: #4a4a4a;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.category-link-ancient::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.3) 50%, transparent 100%);
    transition: left 0.5s ease;
}

.category-link-ancient:hover::before {
    left: 100%;
}

.category-link-ancient:hover {
    background: linear-gradient(135deg, rgba(139, 69, 19, 0.2) 0%, rgba(160, 82, 45, 0.2) 100%);
    border-color: rgba(139, 69, 19, 0.6);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(139, 69, 19, 0.2);
    color: #8b4513;
}

.link-text {
    font-size: 0.95rem;
    letter-spacing: 0.5px;
}

.link-decoration {
    color: #8b4513;
    font-size: 0.8rem;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.category-link-ancient:hover .link-decoration {
    opacity: 1;
    transform: scale(1.1);
}

@media (max-width: 768px) {
    .category-list-ancient {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 8px;
    }

    .category-link-ancient {
        padding: 10px 12px;
    }

    .title-text {
        font-size: 1.3rem;
    }
}
</style>
@endif
<br>
