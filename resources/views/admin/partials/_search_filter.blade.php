{{--
  Partial: Barre de recherche avancée + filtres
  Paramètres attendus:
    $searchRoute  : string (route name)
    $placeholder  : string
    $filters      : array de ['name' => '...', 'label' => '...', 'options' => ['value' => 'label', ...]]
    $activeCount  : int (nombre de filtres actifs, optionnel)
--}}
@php
  $searchVal    = request('search', '');
  $filterCount  = collect($filters ?? [])->filter(fn($f) => request()->filled($f['name']))->count();
  $hasSearch    = $searchVal !== '' || $filterCount > 0;
@endphp

<div class="search-filter-bar m-card" style="margin-bottom: 16px;">
  <form action="{{ route($searchRoute) }}" method="GET" id="search-filter-form">
    <div class="search-filter-inner">

      {{-- BARRE DE RECHERCHE --}}
      <div class="search-input-wrap" style="flex: 1; min-width: 200px;">
        <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
        <input
          type="text"
          name="search"
          id="search-input"
          class="search-input"
          value="{{ $searchVal }}"
          placeholder="{{ $placeholder ?? 'Rechercher…' }}"
          autocomplete="off">
        @if($searchVal)
          <button type="button" class="search-clear" onclick="document.getElementById('search-input').value=''; document.getElementById('search-filter-form').submit();" title="Effacer">
            <i class="fa-solid fa-xmark"></i>
          </button>
        @endif
      </div>

      {{-- FILTRES --}}
      @foreach($filters ?? [] as $filter)
        <div class="filter-select-wrap" style="min-width: 145px;">
          <select
            name="{{ $filter['name'] }}"
            class="filter-select {{ request()->filled($filter['name']) ? 'filter-active' : '' }}"
            onchange="document.getElementById('search-filter-form').submit()">
            <option value="">{{ $filter['label'] }}</option>
            @foreach($filter['options'] as $val => $lbl)
              <option value="{{ $val }}" {{ request($filter['name']) == $val ? 'selected' : '' }}>
                {{ $lbl }}
              </option>
            @endforeach
          </select>
          <span class="filter-select-icon"><i class="fa-solid fa-chevron-down"></i></span>
        </div>
      @endforeach

      {{-- BOUTON RESET --}}
      @if($hasSearch)
        <a href="{{ route($searchRoute) }}" class="filter-reset-btn" title="Réinitialiser tous les filtres">
          <i class="fa-solid fa-rotate-left"></i>
          <span class="d-none d-md-inline">Réinitialiser</span>
          @if($filterCount > 0 || $searchVal)
            <span class="filter-badge">{{ $filterCount + ($searchVal ? 1 : 0) }}</span>
          @endif
        </a>
      @endif

    </div>

    {{-- RÉSULTATS COUNT --}}
    @if($hasSearch && isset($resultCount))
      <div class="search-results-meta">
        <i class="fa-solid fa-filter me-1"></i>
        <strong>{{ $resultCount }}</strong> résultat(s) pour
        @if($searchVal)
          « <em>{{ $searchVal }}</em> »
        @endif
        @if($filterCount > 0)
          avec {{ $filterCount }} filtre(s) actif(s)
        @endif
      </div>
    @endif
  </form>
</div>

<style>
.search-filter-bar { padding: 16px 20px !important; }
.search-filter-inner {
  display: flex; flex-wrap: wrap; gap: 10px; align-items: center;
}
.search-input-wrap {
  position: relative; display: flex; align-items: center;
}
.search-icon {
  position: absolute; left: 12px; color: #9ca3af; font-size: .88rem;
  pointer-events: none;
}
.search-input {
  width: 100%; border: 2px solid #e5e7eb; border-radius: 10px;
  padding: .55rem 2.4rem .55rem 2.4rem;
  font-size: .9rem; transition: .2s; color: #1e293b;
  font-family: inherit; background: #f9fafb;
}
.search-input:focus {
  outline: none; border-color: var(--m-primary, #dc2626);
  box-shadow: 0 0 0 3px rgba(220,38,38,.1); background: #fff;
}
.search-clear {
  position: absolute; right: 10px; background: none; border: none;
  color: #9ca3af; cursor: pointer; font-size: .85rem; padding: 2px 4px;
  border-radius: 4px; transition: .2s;
}
.search-clear:hover { color: #dc2626; background: rgba(220,38,38,.08); }
.filter-select-wrap { position: relative; }
.filter-select {
  appearance: none; border: 2px solid #e5e7eb; border-radius: 10px;
  padding: .52rem 2rem .52rem .9rem;
  font-size: .86rem; color: #374151; background: #f9fafb;
  cursor: pointer; transition: .2s; font-family: inherit;
  width: 100%;
}
.filter-select:focus { outline: none; border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220,38,38,.1); }
.filter-select.filter-active {
  border-color: #dc2626; background: rgba(220,38,38,.05); color: #dc2626; font-weight: 600;
}
.filter-select-icon {
  position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
  font-size: .72rem; color: #9ca3af; pointer-events: none;
}
.filter-reset-btn {
  display: inline-flex; align-items: center; gap: 6px;
  border: 2px solid #e5e7eb; border-radius: 10px; color: #6b7280;
  padding: .5rem .9rem; font-size: .86rem; text-decoration: none;
  transition: .2s; white-space: nowrap; background: #fff;
  position: relative;
}
.filter-reset-btn:hover { border-color: #dc2626; color: #dc2626; background: rgba(220,38,38,.05); }
.filter-badge {
  position: absolute; top: -7px; right: -7px;
  background: #dc2626; color: #fff; border-radius: 50%;
  width: 18px; height: 18px; display: flex; align-items: center; justify-content: center;
  font-size: .65rem; font-weight: 700;
}
.search-results-meta {
  margin-top: 10px; padding-top: 10px;
  border-top: 1px solid #f1f5f9;
  font-size: .83rem; color: #64748b;
}
</style>
