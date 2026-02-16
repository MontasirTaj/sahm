@if ($paginator->hasPages())
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="{{ __('السابق') }}">
                    <span class="page-link"><i class="mdi mdi-chevron-right"></i> {{ __('السابق') }}</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        aria-label="{{ __('السابق') }}"><i class="mdi mdi-chevron-right"></i> {{ __('السابق') }}</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span
                            class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span
                                    class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link"
                                    href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                        aria-label="{{ __('التالي') }}">{{ __('التالي') }} <i class="mdi mdi-chevron-left"></i></a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="{{ __('التالي') }}">
                    <span class="page-link">{{ __('التالي') }} <i class="mdi mdi-chevron-left"></i></span>
                </li>
            @endif
        </ul>
        <div class="text-center mt-2">
            <small class="text-muted">
                {{ __('الصفحة') }} {{ $paginator->currentPage() }} {{ __('من') }} {{ $paginator->lastPage() }}
                ({{ $paginator->total() }} {{ __('عرض') }})
            </small>
        </div>
    </nav>
@endif
