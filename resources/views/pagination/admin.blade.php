@if ($paginator->hasPages())
    <nav class="admin-page-nav" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <p class="admin-page-summary">
            {{ __('Showing') }}
            <strong>{{ $paginator->firstItem() }}</strong>
            {{ __('to') }}
            <strong>{{ $paginator->lastItem() }}</strong>
            {{ __('of') }}
            <strong>{{ $paginator->total() }}</strong>
            {{ __('results') }}
        </p>

        <ul class="admin-page-list">
            @if ($paginator->onFirstPage())
                <li><span class="admin-page-link is-disabled" aria-disabled="true"><i class="bi bi-chevron-left" aria-hidden="true"></i> Previous</span></li>
            @else
                <li><a class="admin-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page"><i class="bi bi-chevron-left" aria-hidden="true"></i> Previous</a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="admin-page-link is-disabled" aria-disabled="true">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="admin-page-link is-current" aria-current="page">{{ $page }}</span></li>
                        @else
                            <li><a class="admin-page-link" href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a class="admin-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">Next <i class="bi bi-chevron-right" aria-hidden="true"></i></a></li>
            @else
                <li><span class="admin-page-link is-disabled" aria-disabled="true">Next <i class="bi bi-chevron-right" aria-hidden="true"></i></span></li>
            @endif
        </ul>
    </nav>
@endif
