<div class="section-stories-full mb-3 mt-3 col-12 col-md-8 col-lg-9">
    <div class="ancient-scroll-stories">
        <div class="scroll-header-stories">
            <div class="scroll-title-stories">
                <h2 class="ancient-title-stories">
                    <span class="title-text-stories">Truyện đã hoàn thành</span>
                    <div class="title-decoration-stories">
                        <span class="decoration-line-stories left"></span>
                        <span class="decoration-symbol-stories">🔥</span>
                        <span class="decoration-line-stories right"></span>
                    </div>
                </h2>
            </div>
        </div>
        <div class="scroll-content-stories">
            <div class="section-stories-full__list">
                @foreach ($stories as $story)
                    @include('Frontend.snippets.story_item_full', ['story' => $story])
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .ancient-scroll-stories {
            margin: 20px 0;
            animation: scrollUnfurl 1.5s ease-out;
        }

        @keyframes scrollUnfurl {
            0% {
                transform: scaleY(0.1);
                opacity: 0;
            }

            50% {
                transform: scaleY(0.8);
                opacity: 0.7;
            }

            100% {
                transform: scaleY(1);
                opacity: 1;
            }
        }

        .scroll-header-stories {
            background: linear-gradient(90deg, #2c1810 0%, #4a2c1a 50%, #2c1810 100%);
            margin: 0;
            padding: 20px 25px;
            border-radius: 15px 15px 0 0;
            position: relative;
            border: 3px solid #8b4513;
            border-bottom: none;
        }

        .scroll-header-stories::before {
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

        .ancient-title-stories {
            margin: 0;
            text-align: center;
            position: relative;
        }

        .title-text-stories {
            color: #f4f1e8;
            font-size: 1.6rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            letter-spacing: 3px;
            display: block;
            margin-bottom: 10px;
            font-family: 'Noto Serif', serif;
        }

        .title-decoration-stories {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .decoration-line-stories {
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, #d4af37 50%, transparent 100%);
            flex: 1;
            max-width: 100px;
            border-radius: 2px;
        }

        .decoration-symbol-stories {
            color: #d4af37;
            font-size: 1.5rem;
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
            animation: flameFlicker 2s ease-in-out infinite;
        }

        @keyframes flameFlicker {

            0%,
            100% {
                transform: scale(1) rotate(0deg);
            }

            50% {
                transform: scale(1.1) rotate(5deg);
            }
        }

        .scroll-content-stories {
            background: linear-gradient(135deg, #f8f4e8 0%, #e8dcc0 50%, #d4c4a8 100%);
            border: 3px solid #8b4513;
            border-top: none;
            border-radius: 0 0 15px 15px;
            padding: 25px;
            position: relative;
            overflow: hidden;
        }

        .scroll-content-stories::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .section-stories-full__list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        @media (min-width: 576px) {
            .section-stories-full__list {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 768px) {
            .section-stories-full__list {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (min-width: 992px) {
            .section-stories-full__list {
                grid-template-columns: repeat(6, 1fr);
            }
        }

        @media (min-width: 1200px) {
            .section-stories-full__list {
                grid-template-columns: repeat(8, 1fr);
            }
        }

        /* Dark theme */
        .dark-theme .scroll-content-stories {
            background: linear-gradient(135deg, #2b2b2b 0%, #242424 50%, #1c1c1c 100%);
            border-color: #8b4513;
        }

        .dark-theme .scroll-content-stories::before {
            background:
                radial-gradient(circle at 25% 25%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(212, 175, 55, 0.05) 0%, transparent 50%);
        }
    </style>
@endpush
