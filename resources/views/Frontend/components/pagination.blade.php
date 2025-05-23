@props(['paginator'])

@if ($paginator->hasPages())
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination pagination-sm flex-wrap justify-content-center gap-1">
            {{-- First Page Link --}}
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url(1) }}" aria-label="First">
                    <span aria-hidden="true">&laquo;&laquo;</span>
                </a>
            </li>

            {{-- Previous Page Link --}}
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            {{-- Numbered pages --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $delta = 2; // Number of pages to show before and after current page
            @endphp

            @for ($i = 1; $i <= $lastPage; $i++)
                @if ($i == 1 || $i == $lastPage || ($i >= $currentPage - $delta && $i <= $currentPage + $delta))
                    <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @elseif ($i == $currentPage - $delta - 1 || $i == $currentPage + $delta + 1)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif
            @endfor

            {{-- Next Page Link --}}
            <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>

            {{-- Last Page Link --}}
            <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($lastPage) }}" aria-label="Last">
                    <span aria-hidden="true">&raquo;&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
@endif