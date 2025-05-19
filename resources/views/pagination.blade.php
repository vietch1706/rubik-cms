@if ($paginator->hasPages())
    <nav class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 border border-latte-surface1 rounded-lg text-latte-subtext0 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-1 border border-latte-surface1 rounded-lg text-latte-subtext0 hover:bg-latte-surface0">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-3 py-1 text-latte-subtext0">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1 bg-latte-blue text-white rounded-lg">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-1 border border-latte-surface1 rounded-lg text-latte-text hover:bg-latte-surface0">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-1 border border-latte-surface1 rounded-lg text-latte-subtext0 hover:bg-latte-surface0">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
            @else
                <span class="px-3 py-1 border border-latte-surface1 rounded-lg text-latte-subtext0 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </span>
            @endif
        </div>
        <div class="text-sm text-latte-subtext0">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </div>
    </nav>
@endif
