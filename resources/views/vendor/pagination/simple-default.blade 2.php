@if ($paginator->hasPages())
    <nav class="pagination-custom">
        {{-- Previous Page --}}
        @if ($paginator->onFirstPage())
            <span class="disabled">« Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}">« Anterior</a>
        @endif

        {{-- Page Numbers --}}
        <span class="pages">
            Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}
        </span>

        {{-- Next Page --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">Siguiente »</a>
        @else
            <span class="disabled">Siguiente »</span>
        @endif
    </nav>
@endif
