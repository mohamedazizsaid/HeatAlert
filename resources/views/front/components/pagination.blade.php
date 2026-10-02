@if ($paginator->hasPages())
    <nav class="d-flex flex-column align-items-center mt-4" aria-label="Pagination Navigation">
        <ul class="pagination pagination-rounded gap-1 mb-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="Précédent">
                    <span class="page-link rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; border-color: #e2e8f0; color: #94a3b8; background: #f8fafc;" aria-hidden="true">
                        <i class="bi bi-chevron-left"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; border-color: #e2e8f0; color: #1e3a5f; background: #fff;" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Précédent">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; border: none; background: transparent; color: #94a3b8;">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 38px; height: 38px; background: #dc2626; border-color: #dc2626; color: #ffffff;">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link rounded-circle d-flex align-items-center justify-content-center fw-semibold" style="width: 38px; height: 38px; border-color: #e2e8f0; color: #1e3a5f; background: #fff;" href="{{ $url }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; border-color: #e2e8f0; color: #1e3a5f; background: #fff;" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Suivant">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="Suivant">
                    <span class="page-link rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; border-color: #e2e8f0; color: #94a3b8; background: #f8fafc;" aria-hidden="true">
                        <i class="bi bi-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>

        <div class="small text-muted text-center" style="font-size: 13px;">
            Affichage de <strong>{{ $paginator->firstItem() }}</strong> à <strong>{{ $paginator->lastItem() }}</strong> sur <strong>{{ $paginator->total() }}</strong> résultats
        </div>
    </nav>
@endif
