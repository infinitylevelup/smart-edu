@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center my-3" role="navigation" aria-label="صفحه‌بندی">
        <ul class="pagination pagination-sm gap-1 flex-wrap align-items-center">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link rounded-pill px-3">قبلی</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link rounded-pill px-3" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        قبلی
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link rounded-pill px-3">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link rounded-pill px-3 fw-bold">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link rounded-pill px-3"
                                    href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link rounded-pill px-3" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        بعدی
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link rounded-pill px-3">بعدی</span>
                </li>
            @endif

        </ul>
    </nav>

    {{-- Persian info line --}}
    <div class="text-center small text-muted mt-1">
        نمایش {{ $paginator->firstItem() }} تا {{ $paginator->lastItem() }}
        از {{ $paginator->total() }} آزمون
        — صفحه {{ $paginator->currentPage() }} از {{ $paginator->lastPage() }}
    </div>
@endif
