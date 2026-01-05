@if ($paginator->hasPages())
  <nav class="events-pager" role="navigation" aria-label="Pagination">

    {{-- Prev --}}
    @if ($paginator->onFirstPage())
      <span class="events-pager__item is-disabled" aria-disabled="true">‹ Prev</span>
    @else
      <a class="events-pager__item" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹ Prev</a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
      @if (is_string($element))
        <span class="events-pager__item is-disabled">{{ $element }}</span>
      @endif

      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span class="events-pager__item is-active" aria-current="page">{{ $page }}</span>
          @else
            <a class="events-pager__item" href="{{ $url }}">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
      <a class="events-pager__item" href="{{ $paginator->nextPageUrl() }}" rel="next">Next ›</a>
    @else
      <span class="events-pager__item is-disabled" aria-disabled="true">Next ›</span>
    @endif

  </nav>
@endif
