@props([
    'paginator' => null,
    'size' => 'md',
])

@php
$sizeClass = $size === 'sm' ? 'pagination--sm' : '';
@endphp

@if($paginator && $paginator->hasPages())
<nav aria-label="Pagination">
    <ul class="pagination {{ $sizeClass }}" {{ $attributes }}>
        {{-- Previous --}}
        <li class="pagination__item">
            <a href="{{ $paginator->previousPageUrl() ?? '#' }}"
                class="pagination__link pagination__link--prev"
                @if($paginator->onFirstPage())
                aria-disabled="true" tabindex="-1"
                @endif
                :class="{ 'pagination__link--disabled': {{ $paginator->onFirstPage() ? 'true' : 'false' }} }"
                aria-label="Previous page">
                Previous
            </a>
        </li>

        {{-- Pages with ellipsis --}}
        @foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            @if($page === 1 || $page === $paginator->lastPage() || abs($page - $paginator->currentPage()) <= 2)
                @if(isset($prevPage) && $page - $prevPage > 1)
                <li class="pagination__item">
                    <span class="pagination__link pagination__link--ellipsis" aria-hidden="true">...</span>
                </li>
                @endif

                <li class="pagination__item">
                    <a href="{{ $url }}"
                        class="pagination__link"
                        :class="{ 'pagination__link--active': {{ $page === $paginator->currentPage() ? 'true' : 'false' } }"
                        @if($page === $paginator->currentPage())
                        aria-current="page"
                        @endif
                        aria-label="Page {{ $page }}">
                        {{ $page }}
                    </a>
                </li>

                @php $prevPage = $page; @endphp
            @endif
        @endforeach

        {{-- Next --}}
        <li class="pagination__item">
            <a href="{{ $paginator->nextPageUrl() ?? '#' }}"
                class="pagination__link pagination__link--next"
                @if(!$paginator->hasMorePages())
                aria-disabled="true" tabindex="-1"
                @endif
                :class="{ 'pagination__link--disabled': {{ !$paginator->hasMorePages() ? 'true' : 'false' } }"
                aria-label="Next page">
                Next
            </a>
        </li>
    </ul>
</nav>
@endif
