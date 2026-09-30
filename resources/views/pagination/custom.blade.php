@if ($paginator->hasPages())
  <nav class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-slate-500">Menampilkan
    <span class="font-medium text-slate-700">{{ $paginator->firstItem() }}</span>
    -
    <span class="font-medium text-slate-700">{{ $paginator->lastItem() }}</span>
    dari
    <span class="font-medium text-slate-700">{{ $paginator->total() }}</span>
    data</p>

    <div class="flex items-center gap-1">
      @if ($paginator->onFirstPage())
        <span
          class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 px-3 text-sm text-slate-300"
        >
          Sebelumnya
        </span>
      @else
        <a
          href="{{ $paginator->previousPageUrl() }}"
          class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
        >
          Sebelumnya
        </a>
      @endif

      @foreach ($elements as $element)
        @if (is_string($element))
          <span class="inline-flex h-9 w-9 items-center justify-center text-sm text-slate-400"> {{ $element }} </span>
        @endif

        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <span
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-sm font-semibold text-white"
              >
                {{ $page }}
              </span>
            @else
              <a
                href="{{ $url }}"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-600 transition hover:bg-slate-50"
              >
                {{ $page }}
              </a>
            @endif
          @endforeach
        @endif
      @endforeach

      @if ($paginator->hasMorePages())
        <a
          href="{{ $paginator->nextPageUrl() }}"
          class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
        >
          Selanjutnya
        </a>
      @else
        <span
          class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 px-3 text-sm text-slate-300"
        >
          Selanjutnya
        </span>
      @endif
    </div>
  </nav>
@endif
