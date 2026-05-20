@if ($paginator->hasPages())
    <div class="pagination-container justify-between flex-wrap items-center flex mt-8">
        <p>
            {!! __('Showing') !!}
            @if ($paginator->firstItem())
                <span>{{ $paginator->firstItem() }}</span> {!! __('to') !!} <span>{{ $paginator->lastItem() }}</span>
            @else
                <span>{{ $paginator->count() }}</span>
            @endif
            {!! __('of') !!}
            <span>{{ $paginator->total() }}</span> {!! __('results') !!}
        </p>

        <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span aria-hidden="true">&lsaquo;</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}">
                    &lsaquo;
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true">
                        <span>{{ $element }}</span>
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page">
                                <span>{{ $page }}</span>
                            </span>
                        @else
                            <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">
                    &rsaquo;
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span aria-hidden="true">&rsaquo;</span>
                </span>
            @endif
        </nav>
    </div>
@endif
