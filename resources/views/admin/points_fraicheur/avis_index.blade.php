@extends('layouts.admin.admin')
@section('title', 'Supervision des Avis')

@section('content')
<div class="page-header">
  <div>
    <nav aria-label="breadcrumb" class="mb-2">
      <a href="{{ route('admin.points_fraicheur.index') }}" class="text-decoration-none text-muted" style="font-size:.85rem;">
        <i class="fa-solid fa-arrow-left me-1"></i>Points de Fraîcheur
      </a>
    </nav>
    <h1><i class="fa-solid fa-star-half-stroke me-2 text-warning"></i>Supervision des Avis Citoyens</h1>
    <p class="subtitle">{{ $avis->total() }} avis citoyen(s) enregistré(s).</p>
  </div>
</div>

{{-- ── BARRE DE RECHERCHE AVANCÉE DYNAMIQUE EN 1 CLIC ── --}}
<div class="m-card mb-4 p-3" style="border-radius:14px;background:#ffffff;box-shadow:0 2px 10px rgba(0,0,0,0.03);">
  <form action="{{ route('admin.avis_points.index') }}" method="GET" id="avis-search-form">
    <div class="row g-2 align-items-center mb-3">
      {{-- Input recherche texte avec bouton cliquable --}}
      <div class="col-lg-6 col-md-12">
        <div class="input-group" style="border-radius:10px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
          <span class="input-group-text bg-white border-end-0 text-muted" style="border-color:#e2e8f0;padding-left:14px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text"
                 name="search"
                 id="avis-live-search"
                 class="form-control border-start-0 border-end-0 py-2 ps-1"
                 style="border-color:#e2e8f0;font-size:14px;"
                 placeholder="Rechercher par citoyen, email, commentaire… (frappe instantanée)"
                 value="{{ request('search') }}"
                 autocomplete="off">
          @if(request('search'))
            <button type="button" class="btn btn-outline-secondary border-start-0 border-end-0 bg-white" onclick="document.getElementById('avis-live-search').value=''; document.getElementById('avis-search-form').submit();" title="Effacer la recherche">
              ✕
            </button>
          @endif
          <button type="submit" class="btn btn-warning px-3 fw-semibold text-dark" style="background:#f59e0b;border-color:#f59e0b;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1" style="vertical-align:-1px;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            Rechercher
          </button>
        </div>
      </div>

      {{-- Sélection du point de fraîcheur --}}
      <div class="col-lg-4 col-md-8">
        <select name="point_fraicheur_id" id="avis-point-select" class="form-select" style="border-color:#e2e8f0;font-size:13.5px;border-radius:10px;" onchange="document.getElementById('avis-search-form').submit();">
          <option value="">— Tous les points de fraîcheur —</option>
          @foreach($points as $pt)
            <option value="{{ $pt->id }}" {{ request('point_fraicheur_id') == $pt->id ? 'selected' : '' }}>
              {{ $pt->nom }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Bouton reset --}}
      <div class="col-lg-2 col-md-4 d-flex justify-content-lg-end">
        @if(request()->anyFilled(['search', 'note', 'point_fraicheur_id']))
          <a href="{{ route('admin.avis_points.index') }}" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1 w-100 justify-content-center" style="border-radius:10px;height:38px;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            Réinitialiser
          </a>
        @endif
      </div>
    </div>

    {{-- Champ caché pour filtre note en 1 clic --}}
    <input type="hidden" name="note" id="avis-note-hidden" value="{{ request('note') }}">

    {{-- ── FILTRES RAPIDES PAR ÉTOILES EN 1 CLIC ── --}}
    <div class="d-flex flex-wrap align-items-center gap-2 pt-2 border-top" style="border-color:#f1f5f9 !important;">
      <span class="text-muted small fw-semibold me-1">Note :</span>
      <button type="button" class="btn btn-sm {{ !request('note') ? 'btn-dark' : 'btn-light border text-dark' }}" onclick="selectNoteFilter('')">
        Toutes les notes
      </button>
      <button type="button" class="btn btn-sm {{ request('note')==='5' ? 'btn-success text-white' : 'btn-light border text-dark' }}" onclick="selectNoteFilter('5')">
        <span style="color:#f59e0b;">★★★★★</span> 5 étoiles
      </button>
      <button type="button" class="btn btn-sm {{ request('note')==='4' ? 'btn-primary text-white' : 'btn-light border text-dark' }}" onclick="selectNoteFilter('4')">
        <span style="color:#f59e0b;">★★★★</span> 4 étoiles
      </button>
      <button type="button" class="btn btn-sm {{ request('note')==='3' ? 'btn-warning text-dark' : 'btn-light border text-dark' }}" onclick="selectNoteFilter('3')">
        <span style="color:#f59e0b;">★★★</span> 3 étoiles
      </button>
      <button type="button" class="btn btn-sm {{ request('note')==='2' ? 'btn-secondary text-white' : 'btn-light border text-dark' }}" onclick="selectNoteFilter('2')">
        <span style="color:#f59e0b;">★★</span> 2 étoiles
      </button>
      <button type="button" class="btn btn-sm {{ request('note')==='1' ? 'btn-danger text-white' : 'btn-light border text-dark' }}" onclick="selectNoteFilter('1')">
        <span style="color:#f59e0b;">★</span> 1 étoile
      </button>

      <div class="ms-auto small text-muted d-none d-md-block" id="avis-results-counter">
        {{ $avis->total() }} avis affiché(s)
      </div>
    </div>
  </form>
</div>

<section class="m-card">
  <div class="table-responsive">
    <table class="m-table" id="avis-table">
      <thead>
        <tr>
          <th>Point de Fraîcheur</th>
          <th>Citoyen</th>
          <th style="text-align:center;">Note</th>
          <th>Commentaire</th>
          <th style="text-align:center;">Date</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody id="avis-table-body">
        @forelse($avis as $av)
        <tr class="avis-table-row" data-point="{{ strtolower($av->pointFraicheur?->nom ?? '') }}" data-user="{{ strtolower(($av->user?->prenom ?? '') . ' ' . ($av->user?->nom ?? '') . ' ' . ($av->user?->email ?? '')) }}" data-comment="{{ strtolower($av->commentaire ?? '') }}" data-note="{{ $av->note }}">
          <td>
            <a href="{{ route('admin.points_fraicheur.show', $av->point_fraicheur_id) }}" class="text-decoration-none fw-bold text-dark">
              {{ $av->pointFraicheur?->nom ?? '—' }}
            </a>
          </td>
          <td>
            <strong>{{ $av->user?->prenom ?? '' }} {{ $av->user?->nom ?? '—' }}</strong>
            <br><small class="text-muted">{{ $av->user?->email ?? '' }}</small>
          </td>
          <td style="text-align:center;">
            <span style="color:#f59e0b;">
              @for($s = 1; $s <= 5; $s++)
                <i class="fa-{{ $s <= $av->note ? 'solid' : 'regular' }} fa-star" style="font-size:13px;"></i>
              @endfor
              <span class="ms-1 text-dark fw-bold" style="font-size:.8rem;">({{ $av->note }}/5)</span>
            </span>
          </td>
          <td>
            <span class="avis-comment-text">{{ $av->commentaire ? Str::limit($av->commentaire, 80) : '<em class="text-muted">Aucun commentaire</em>' }}</span>
          </td>
          <td style="text-align:center;font-size:.82rem;">{{ $av->date_avis?->format('d/m/Y') ?? '—' }}</td>
          <td style="text-align:center;">
            {{-- Bouton Modifier l'avis (Modération) --}}
            <button type="button" class="m-btn m-btn--ghost text-primary" style="height:28px;padding:0 10px;font-size:12px;"
                    title="Modifier cet avis"
                    onclick="openEditAvisModal({{ $av->id }}, {{ $av->note }}, '{{ addslashes($av->commentaire ?? '') }}', '{{ addslashes($av->pointFraicheur?->nom ?? 'Point') }}', '{{ addslashes(($av->user?->prenom ?? '') . ' ' . ($av->user?->nom ?? '')) }}')">
              <i class="fa-solid fa-pen-to-square"></i>
            </button>

            {{-- Bouton Supprimer l'avis --}}
            <form method="POST" action="{{ route('admin.avis_points.destroy', $av) }}" style="display:inline;">
              @csrf @method('DELETE')
              <button type="submit" class="m-btn m-btn--ghost text-danger" style="height:28px;padding:0 10px;font-size:12px;"
                      onclick="return confirm('Supprimer cet avis définitivement ?')" title="Supprimer">
                <i class="fa-solid fa-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr id="avis-no-records">
          <td colspan="6" class="text-center py-4 text-muted">
            <i class="fa-solid fa-comment-slash fa-2x mb-2 d-block text-secondary"></i>Aucun avis trouvé.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- ── PAGINATION AVEC ICÔNES CHEVRON SVG ET ÉTAT DÉSACTIVÉ GARANTI ── --}}
  @if($avis->hasPages())
  <div class="d-flex flex-column align-items-center mt-3 pb-3 border-top pt-3" style="gap:8px;">
    <div class="text-muted" style="font-size:12.5px;">
      Affichage de <strong>{{ $avis->firstItem() }}</strong> à <strong>{{ $avis->lastItem() }}</strong> sur <strong>{{ $avis->total() }}</strong> avis
    </div>
    <div class="d-flex justify-content-center align-items-center" style="gap:6px;">
      {{-- Bouton Page Précédente --}}
      @if($avis->onFirstPage())
        <span class="m-btn m-btn--ghost disabled" style="height:32px;padding:0 12px;font-size:13px;opacity:0.35;cursor:not-allowed;" title="Page précédente">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M15 18l-6-6 6-6"/></svg>
        </span>
      @else
        <a href="{{ $avis->previousPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;" title="Page précédente">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M15 18l-6-6 6-6"/></svg>
        </a>
      @endif

      {{-- Numéros de page --}}
      @foreach($avis->getUrlRange(max(1, $avis->currentPage()-2), min($avis->lastPage(), $avis->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}" class="m-btn {{ $page == $avis->currentPage() ? 'm-btn--primary' : 'm-btn--ghost' }}" style="height:32px;padding:0 12px;font-size:13px;min-width:34px;text-align:center;">
          {{ $page }}
        </a>
      @endforeach

      {{-- Bouton Page Suivante --}}
      @if($avis->hasMorePages())
        <a href="{{ $avis->nextPageUrl() }}" class="m-btn m-btn--ghost" style="height:32px;padding:0 12px;font-size:13px;" title="Page suivante">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M9 18l6-6-6-6"/></svg>
        </a>
      @else
        <span class="m-btn m-btn--ghost disabled" style="height:32px;padding:0 12px;font-size:13px;opacity:0.35;cursor:not-allowed;" title="Page suivante">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-1px;"><path d="M9 18l6-6-6-6"/></svg>
        </span>
      @endif
    </div>
  </div>
  @endif
</section>

{{-- ── MODAL D'ÉDITION DE MODÉRATION D'AVIS (AVEC CONTRÔLE DE SAISIE CLIENT ET SERVEUR) ── --}}
<div class="modal fade" id="modalEditAvis" tabindex="-1" aria-labelledby="modalEditAvisLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 10px 30px rgba(0,0,0,0.15);">
      <div class="modal-header border-0 pb-0">
        <div>
          <h5 class="modal-title fw-bold" id="modalEditAvisLabel">
            <i class="fa-solid fa-pen-to-square text-warning me-2"></i>Modérer l'avis
          </h5>
          <small class="text-muted" id="modalEditAvisSubtitle">Édition de l'évaluation</small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="form-edit-avis" method="POST" action="" novalidate>
        @csrf
        @method('PUT')
        <div class="modal-body pt-3">
          {{-- Alerte d'erreur client --}}
          <div id="modal-avis-alert" class="alert alert-danger d-none py-2 px-3 small rounded-3">
            <i class="fa-solid fa-triangle-exclamation me-1"></i><span></span>
          </div>

          {{-- Note par étoiles --}}
          <div class="mb-3">
            <label class="form-label fw-semibold">Note attribuée <span class="text-danger">*</span></label>
            <div class="d-flex align-items-center gap-2 star-picker-wrap">
              <input type="hidden" name="note" id="edit-avis-note-val" value="5" required>
              <div class="d-flex gap-1" id="star-picker" style="cursor:pointer;font-size:24px;color:#d1d5db;">
                <span class="star-btn" data-val="1">★</span>
                <span class="star-btn" data-val="2">★</span>
                <span class="star-btn" data-val="3">★</span>
                <span class="star-btn" data-val="4">★</span>
                <span class="star-btn" data-val="5">★</span>
              </div>
              <span class="badge bg-warning text-dark fw-bold px-2 py-1" id="star-score-badge">5/5</span>
            </div>
            <div id="note-error-msg" class="text-danger small mt-1 d-none">
              <i class="fa-solid fa-circle-exclamation me-1"></i>Veuillez sélectionner une note de 1 à 5 étoiles.
            </div>
          </div>

          {{-- Commentaire --}}
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label for="edit-avis-commentaire" class="form-label fw-semibold mb-0">Commentaire</label>
              <small class="text-muted" id="modal-char-counter" style="font-size:11px;">0 / 1000</small>
            </div>
            <textarea name="commentaire" id="edit-avis-commentaire" class="form-control" rows="4" maxlength="1000" placeholder="Commentaire du citoyen…" style="border-radius:10px;font-size:13.5px;"></textarea>
            <div id="commentaire-error-msg" class="text-danger small mt-1 d-none">
              <i class="fa-solid fa-circle-exclamation me-1"></i>Le commentaire doit comporter au moins 3 caractères s'il est renseigné.
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-warning fw-semibold px-4 text-dark" style="border-radius:10px;">
            <i class="fa-solid fa-check me-1"></i>Enregistrer
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
// ─── Sélection note rapide en 1 clic ──────────────────────────────────────────
function selectNoteFilter(val) {
  document.getElementById('avis-note-hidden').value = val;
  document.getElementById('avis-search-form').submit();
}

// ─── Live search instantané dans le tableau ────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('avis-live-search');
  const rows = document.querySelectorAll('.avis-table-row');
  const counterEl = document.getElementById('avis-results-counter');
  const tbody = document.getElementById('avis-table-body');

  if (searchInput && rows.length > 0) {
    searchInput.addEventListener('input', function() {
      const q = this.value.trim().toLowerCase();
      let count = 0;

      rows.forEach(r => {
        const pt = r.getAttribute('data-point') || '';
        const user = r.getAttribute('data-user') || '';
        const comm = r.getAttribute('data-comment') || '';
        const match = pt.includes(q) || user.includes(q) || comm.includes(q);

        if (match) {
          r.style.display = '';
          count++;
        } else {
          r.style.display = 'none';
        }
      });

      if (counterEl) {
        counterEl.textContent = `${count} avis affiché(s)`;
      }

      let emptyRow = document.getElementById('avis-live-empty-row');
      if (count === 0) {
        if (!emptyRow) {
          emptyRow = document.createElement('tr');
          emptyRow.id = 'avis-live-empty-row';
          emptyRow.innerHTML = `<td colspan="6" class="text-center py-4 text-muted"><i class="fa-solid fa-comment-slash fa-2x mb-2 d-block text-secondary"></i>Aucun avis ne correspond à « <em>${q}</em> ».</td>`;
          tbody.appendChild(emptyRow);
        } else {
          emptyRow.style.display = '';
          emptyRow.querySelector('em').textContent = q;
        }
      } else if (emptyRow) {
        emptyRow.style.display = 'none';
      }
    });
  }

  // ─── Star picker pour le Modal d'édition ─────────────────────────────────────
  const starBtns = document.querySelectorAll('#star-picker .star-btn');
  const noteHidden = document.getElementById('edit-avis-note-val');
  const scoreBadge = document.getElementById('star-score-badge');

  function renderStars(val) {
    starBtns.forEach(b => {
      const bVal = parseInt(b.getAttribute('data-val'));
      b.style.color = bVal <= val ? '#f59e0b' : '#d1d5db';
    });
    if (scoreBadge) scoreBadge.textContent = `${val}/5`;
  }

  starBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const val = parseInt(this.getAttribute('data-val'));
      noteHidden.value = val;
      renderStars(val);
      document.getElementById('note-error-msg')?.classList.add('d-none');
    });

    btn.addEventListener('mouseenter', function() {
      const val = parseInt(this.getAttribute('data-val'));
      renderStars(val);
    });
  });

  document.getElementById('star-picker')?.addEventListener('mouseleave', function() {
    renderStars(parseInt(noteHidden.value || 5));
  });

  // ─── Contrôle de saisie sur le modal de modération avis ───────────────────────
  const editForm = document.getElementById('form-edit-avis');
  const commTextarea = document.getElementById('edit-avis-commentaire');
  const charCounter = document.getElementById('modal-char-counter');
  const commErrorMsg = document.getElementById('commentaire-error-msg');
  const modalAlert = document.getElementById('modal-avis-alert');

  commTextarea?.addEventListener('input', function() {
    const len = this.value.length;
    if (charCounter) charCounter.textContent = `${len} / 1000`;
    const trimmed = this.value.trim();

    if (trimmed.length > 0 && trimmed.length < 3) {
      commErrorMsg?.classList.remove('d-none');
      this.classList.add('is-invalid');
    } else {
      commErrorMsg?.classList.add('d-none');
      this.classList.remove('is-invalid');
      if (trimmed.length >= 3) this.classList.add('is-valid');
      else this.classList.remove('is-valid');
    }
  });

  editForm?.addEventListener('submit', function(e) {
    const note = parseInt(noteHidden.value);
    const comm = commTextarea?.value?.trim() || '';
    let hasErr = false;

    if (isNaN(note) || note < 1 || note > 5) {
      document.getElementById('note-error-msg')?.classList.remove('d-none');
      hasErr = true;
    }

    if (comm.length > 0 && comm.length < 3) {
      commErrorMsg?.classList.remove('d-none');
      commTextarea.classList.add('is-invalid');
      hasErr = true;
    }

    if (hasErr) {
      e.preventDefault();
      if (modalAlert) {
        modalAlert.classList.remove('d-none');
        modalAlert.querySelector('span').textContent = 'Veuillez corriger les erreurs avant d\'enregistrer.';
      }
    } else {
      if (modalAlert) modalAlert.classList.add('d-none');
    }
  });
});

// ─── Fonction d'ouverture du modal de modération ──────────────────────────────
function openEditAvisModal(id, note, commentaire, pointNom, userName) {
  const form = document.getElementById('form-edit-avis');
  form.action = `/admin/avis-points/${id}`;
  document.getElementById('modalEditAvisSubtitle').textContent = `Avis de ${userName} sur « ${pointNom} »`;
  document.getElementById('edit-avis-note-val').value = note;
  document.getElementById('edit-avis-commentaire').value = commentaire;
  document.getElementById('modal-char-counter').textContent = `${commentaire.length} / 1000`;

  // Render initial stars
  const starBtns = document.querySelectorAll('#star-picker .star-btn');
  starBtns.forEach(b => {
    const bVal = parseInt(b.getAttribute('data-val'));
    b.style.color = bVal <= note ? '#f59e0b' : '#d1d5db';
  });
  const scoreBadge = document.getElementById('star-score-badge');
  if (scoreBadge) scoreBadge.textContent = `${note}/5`;

  // Clear previous errors
  document.getElementById('modal-avis-alert')?.classList.add('d-none');
  document.getElementById('note-error-msg')?.classList.add('d-none');
  document.getElementById('commentaire-error-msg')?.classList.add('d-none');
  document.getElementById('edit-avis-commentaire')?.classList.remove('is-invalid', 'is-valid');

  const modalEl = document.getElementById('modalEditAvis');
  const modal = new bootstrap.Modal(modalEl);
  modal.show();
}
</script>
@endpush
@endsection
