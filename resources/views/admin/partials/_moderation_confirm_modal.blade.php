{{-- Modal de confirmation des actions de modération --}}
<div class="modal fade" id="moderationConfirmModal" tabindex="-1" aria-labelledby="moderationConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
    <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden;">
      <div class="modal-header border-0 pb-0 pt-4 px-4 position-relative">
        <div class="d-flex align-items-center gap-3">
          <div id="moderationConfirmIcon" style="width:50px;height:50px;border-radius:14px;background:rgba(37,99,235,.12);color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">
            <i class="fa-solid fa-circle-question"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-dark mb-0" id="moderationConfirmModalLabel">Confirmation</h5>
            <span class="text-primary small fw-semibold">Action de modération</span>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body px-4 py-3">
        <p class="mb-0 text-secondary" id="moderationConfirmMessage">Confirmez-vous cette action sur ce signalement ?</p>
      </div>
      <div class="modal-footer border-0 px-4 pb-4 pt-1 d-flex gap-2">
        <button type="button" class="m-btn m-btn--ghost flex-grow-1 text-center py-2" data-bs-dismiss="modal">
          <i class="fa-solid fa-xmark me-2"></i>Annuler
        </button>
        <form id="moderationConfirmForm" method="POST" action="" class="flex-grow-1 m-0">
          @csrf
          @method('PATCH')
          <button type="button" id="moderationConfirmSubmit" class="btn btn-primary w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2 shadow-sm" style="border-radius:8px;">
            <i class="fa-solid fa-check"></i>Confirmer
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('click', function (event) {
    const trigger = event.target.closest('.js-trigger-moderation');
    if (!trigger) return;

    event.preventDefault();

    const modalElement = document.getElementById('moderationConfirmModal');
    const form = document.getElementById('moderationConfirmForm');
    const method = form?.querySelector('input[name="_method"]');
    const title = document.getElementById('moderationConfirmModalLabel');
    const message = document.getElementById('moderationConfirmMessage');
    const submit = document.getElementById('moderationConfirmSubmit');
    const icon = document.getElementById('moderationConfirmIcon');

    if (!modalElement || !form) return;

    form.action = trigger.dataset.action;
    if (method) method.value = trigger.dataset.method || 'PATCH';
    if (title) title.textContent = trigger.dataset.title || 'Confirmation';
    if (message) message.textContent = trigger.dataset.message || 'Confirmez-vous cette action ?';
    if (submit) {
      submit.innerHTML = `<i class="fa-solid ${trigger.dataset.icon || 'fa-check'}"></i>${trigger.dataset.submit || 'Confirmer'}`;
      submit.className = `btn ${trigger.dataset.variant || 'btn-primary'} w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2 shadow-sm`;
      submit.style.borderRadius = '8px';
    }
    if (icon) {
      icon.style.background = trigger.dataset.color === 'danger' ? 'rgba(220,38,38,.12)' : 'rgba(22,163,74,.12)';
      icon.style.color = trigger.dataset.color === 'danger' ? '#dc2626' : '#16a34a';
      icon.innerHTML = `<i class="fa-solid ${trigger.dataset.icon || 'fa-check'}"></i>`;
    }

    bootstrap.Modal.getOrCreateInstance(modalElement).show();
  });

  document.getElementById('moderationConfirmSubmit')?.addEventListener('click', function () {
    const form = document.getElementById('moderationConfirmForm');

    if (form?.action) {
      HTMLFormElement.prototype.submit.call(form);
    }
  });
</script>
@endpush
