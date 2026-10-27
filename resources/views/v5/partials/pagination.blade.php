@if ($productos->hasPages())
  <nav class="v5-pagination" aria-label="Páginas del catálogo">
    @if ($productos->onFirstPage())
      <span class="v5-pagination__edge">Anterior</span>
    @else
      <a href="{{ $productos->previousPageUrl() }}">Anterior</a>
    @endif
    <span>Página {{ $productos->currentPage() }} de {{ $productos->lastPage() }}</span>
    @if ($productos->hasMorePages())
      <a href="{{ $productos->nextPageUrl() }}">Siguiente</a>
    @else
      <span class="v5-pagination__edge">Siguiente</span>
    @endif
  </nav>
@endif
