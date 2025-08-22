<div class="section-stories-full mb-3 mt-3 col-12 col-md-8 col-lg-9">
    <div class="scroll-title-stories">
        <h2 class="ancient-title-stories">
            <span class="title-text-stories">Truyện đã hoàn thành</span>
            <div class="title-decoration-stories">
                <span class="decoration-line-stories left"></span>
                <span class="decoration-line-stories right"></span>
            </div>
        </h2>
    </div>

    <div class="row">
        @foreach ($stories as $story)
            <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                @include('Frontend.snippets.story_item_full', ['story' => $story])
            </div>
        @endforeach
    </div>
</div>

@push('styles')
    <style>
        .title-text-stories {
            color: #000000;
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

        /* Dark theme */
        .dark-theme .title-text-stories {
            color: #f4f1e8 !important;
        }
    </style>
@endpush
