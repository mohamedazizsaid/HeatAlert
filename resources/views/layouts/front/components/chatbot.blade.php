{{-- ══════════════════════════════════════════════════════════════════════
     HEATBOT IA — ASSISTANT INTELLIGENT DE VIGILANCE & CLIMAT (GEMINI FLASH)
     Widget Flottant en bas à droite avec rendu Markdown et Charts interactifs
     Optimisé pour Plein Écran, Écran Standard et Mobile / Tablettes
══════════════════════════════════════════════════════════════════════ --}}

<!-- Inclusion de Chart.js & Marked.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<!-- Styles du Widget Chatbot HeatAlert -->
<style>
  /* ── Conteneur Général Flottant ── */
  #heatbot-floating-container {
    position: fixed;
    bottom: 26px;
    right: 26px;
    z-index: 999999;
    font-family: 'Montserrat', 'Roboto', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  }

  /* ── Bulle de Déclenchement (Launcher) ── */
  .heatbot-launcher-btn {
    width: 66px;
    height: 66px;
    border-radius: 50%;
    border: none;
    outline: none;
    cursor: pointer;
    background: linear-gradient(135deg, #ff4d00 0%, #ea580c 45%, #dc2626 100%);
    box-shadow: 0 12px 30px -4px rgba(220, 38, 38, 0.55), 0 6px 16px -2px rgba(234, 88, 12, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 28px;
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
  }

  .heatbot-launcher-btn:hover {
    transform: scale(1.08) rotate(4deg);
    box-shadow: 0 18px 38px -4px rgba(220, 38, 38, 0.65), 0 8px 20px -2px rgba(234, 88, 12, 0.5);
  }

  .heatbot-launcher-btn:active {
    transform: scale(0.96);
  }

  /* Masquer le launcher quand la fenêtre est ouverte */
  #heatbot-floating-container.is-open .heatbot-launcher-btn {
    opacity: 0;
    pointer-events: none;
    transform: scale(0.6);
  }

  /* Ondulation / Pulsation d'attention */
  .heatbot-pulse-ring {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 2px solid rgba(234, 88, 12, 0.7);
    animation: heatbotPulse 2.8s infinite;
    pointer-events: none;
  }

  @keyframes heatbotPulse {
    0% { transform: scale(1); opacity: 0.9; }
    50% { transform: scale(1.35); opacity: 0; }
    100% { transform: scale(1.5); opacity: 0; }
  }

  /* Badge En Ligne / Statut */
  .heatbot-online-badge {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 16px;
    height: 16px;
    background-color: #22c55e;
    border: 3px solid #ffffff;
    border-radius: 50%;
    box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.3);
  }

  /* Infobulle de salutation initiale (Teaser) */
  .heatbot-teaser-bubble {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 270px;
    background: #ffffff;
    border-radius: 18px;
    padding: 12px 16px;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.16);
    border: 1px solid rgba(226, 232, 240, 0.9);
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    animation: teaserSlideUp 0.5s ease-out;
    transition: all 0.25s ease;
  }

  .heatbot-teaser-bubble:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 36px rgba(15, 23, 42, 0.22);
  }

  @keyframes teaserSlideUp {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .heatbot-teaser-close {
    border: none;
    background: transparent;
    color: #94a3b8;
    cursor: pointer;
    font-size: 14px;
    padding: 0;
    line-height: 1;
    margin-left: auto;
  }

  .heatbot-teaser-close:hover {
    color: #475569;
  }

  /* ── Fenêtre Principale de Chat ── */
  .heatbot-chat-window {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 440px;
    max-width: calc(100vw - 32px);
    height: 640px;
    max-height: calc(100vh - 40px);
    background: #ffffff;
    border-radius: 26px;
    box-shadow: 0 25px 60px -12px rgba(15, 23, 42, 0.28), 0 12px 24px -8px rgba(220, 38, 38, 0.18);
    border: 1px solid rgba(226, 232, 240, 0.95);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transform-origin: bottom right;
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    opacity: 0;
    transform: scale(0.85) translateY(20px);
    pointer-events: none;
    z-index: 999999;
  }

  .heatbot-chat-window.open {
    opacity: 1;
    transform: scale(1) translateY(0);
    pointer-events: auto;
  }

  /* ── Mode Plein Écran (Desktop / Tablette) ── */
  .heatbot-chat-window.fullscreen {
    position: fixed !important;
    top: 24px !important;
    left: 24px !important;
    right: 24px !important;
    bottom: 24px !important;
    width: auto !important;
    height: auto !important;
    max-width: none !important;
    max-height: none !important;
    border-radius: 24px !important;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4) !important;
    z-index: 10000000 !important;
  }

  .heatbot-chat-window.fullscreen .heatbot-messages-area-inner,
  .heatbot-chat-window.fullscreen .heatbot-suggestions-bar-inner,
  .heatbot-chat-window.fullscreen .heatbot-input-area-inner {
    max-width: 920px;
    width: 100%;
    margin: 0 auto;
  }

  .heatbot-chat-window.fullscreen .heatbot-chart-card {
    max-width: 650px;
    margin: 14px auto 6px auto;
  }

  .heatbot-chat-window.fullscreen .heatbot-chart-canvas-wrap {
    min-height: 280px;
    max-height: 320px;
  }

  /* Header */
  .heatbot-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #334155 100%);
    padding: 16px 20px;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 2px solid rgba(234, 88, 12, 0.4);
    position: relative;
    flex-shrink: 0;
  }

  .heatbot-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .heatbot-avatar-frame {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: linear-gradient(135deg, #ff5722 0%, #dc2626 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 6px 14px rgba(220, 38, 38, 0.4);
    position: relative;
    flex-shrink: 0;
  }

  .heatbot-title {
    font-size: 16px;
    font-weight: 700;
    margin: 0;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #ffffff;
  }

  .heatbot-subtitle {
    font-size: 11px;
    color: #94a3b8;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .heatbot-badge-model {
    background: rgba(234, 88, 12, 0.25);
    color: #fb923c;
    border: 1px solid rgba(251, 146, 60, 0.35);
    font-size: 9.5px;
    padding: 1px 7px;
    border-radius: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .heatbot-header-actions {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  /* ── Toast Personnalisé de Confirmation & Notifications ── */
  .heatbot-confirm-toast {
    position: absolute;
    top: 72px;
    left: 14px;
    right: 14px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 12px 14px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.22);
    display: none;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    z-index: 1050;
    animation: toastSlideDown 0.25s ease-out;
  }

  @keyframes toastSlideDown {
    from { opacity: 0; transform: translateY(-12px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .heatbot-confirm-toast-text {
    font-size: 12.5px;
    font-weight: 600;
    color: #1e293b;
    display: flex;
    align-items: center;
  }

  .heatbot-confirm-toast-actions {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
  }

  .heatbot-confirm-btn {
    border: none;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 11.5px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .heatbot-confirm-btn.cancel {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #cbd5e1;
  }
  .heatbot-confirm-btn.cancel:hover {
    background: #e2e8f0;
  }

  .heatbot-confirm-btn.confirm {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: #ffffff;
    box-shadow: 0 3px 8px rgba(220, 38, 38, 0.3);
  }
  .heatbot-confirm-btn.confirm:hover {
    opacity: 0.95;
    transform: scale(1.02);
  }

  /* Toast de notification temporaire */
  .heatbot-notif-toast {
    position: absolute;
    top: 76px;
    left: 50%;
    transform: translateX(-50%);
    background: #0f172a;
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: none;
    align-items: center;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
    z-index: 1060;
    animation: toastFadeIn 0.25s ease-out;
  }

  @keyframes toastFadeIn {
    from { opacity: 0; transform: translate(-50%, -10px); }
    to { opacity: 1; transform: translate(-50%, 0); }
  }


  /* ── Tiroir / Panneau d'Historique des Discussions ── */
  .heatbot-history-panel {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #0f172a;
    color: #ffffff;
    z-index: 1045;
    display: flex;
    flex-direction: column;
    transform: translateX(-100%);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 10px 0 30px rgba(0, 0, 0, 0.4);
  }

  .heatbot-history-panel.open {
    transform: translateX(0);
  }

  .heatbot-history-header {
    padding: 14px 18px;
    background: #1e293b;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
  }

  .heatbot-history-title {
    font-size: 14.5px;
    font-weight: 700;
    display: flex;
    align-items: center;
    color: #f8fafc;
  }

  .heatbot-history-actions-bar {
    padding: 10px 14px;
    background: #0f172a;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
  }

  .heatbot-new-chat-btn {
    flex: 1;
    background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
    color: #ffffff;
    border: none;
    border-radius: 10px;
    padding: 9px 12px;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
  }

  .heatbot-new-chat-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(220, 38, 38, 0.45);
  }

  .heatbot-clear-all-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.25);
    color: #f87171;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
  }

  .heatbot-clear-all-btn:hover {
    background: #dc2626;
    color: #ffffff;
  }

  .heatbot-history-list {
    flex: 1;
    overflow-y: auto;
    padding: 10px 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .heatbot-history-list::-webkit-scrollbar {
    width: 5px;
  }
  .heatbot-history-list::-webkit-scrollbar-thumb {
    background: #334155;
    border-radius: 4px;
  }

  .heatbot-history-item {
    background: #1e293b;
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 12px;
    padding: 10px 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    text-align: left;
  }

  .heatbot-history-item:hover {
    background: #334155;
    border-color: rgba(234, 88, 12, 0.4);
    transform: translateX(2px);
  }

  .heatbot-history-item.active {
    background: rgba(234, 88, 12, 0.18);
    border-color: #ea580c;
  }

  .heatbot-history-item-info {
    flex: 1;
    min-width: 0;
  }

  .heatbot-history-item-title {
    font-size: 12.5px;
    font-weight: 600;
    color: #f1f5f9;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 2px;
  }

  .heatbot-history-item-meta {
    font-size: 10.5px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .heatbot-history-item-badge {
    background: rgba(255, 255, 255, 0.1);
    color: #cbd5e1;
    padding: 1px 6px;
    border-radius: 8px;
    font-size: 9.5px;
  }

  .heatbot-history-delete-btn {
    background: transparent;
    border: none;
    color: #64748b;
    cursor: pointer;
    padding: 6px;
    border-radius: 8px;
    font-size: 13px;
    transition: all 0.2s;
    opacity: 0.7;
    flex-shrink: 0;
  }

  .heatbot-history-item:hover .heatbot-history-delete-btn {
    opacity: 1;
  }

  .heatbot-history-delete-btn:hover {
    color: #ef4444;
    background: rgba(239, 68, 68, 0.15);
  }

  .heatbot-history-empty {
    text-align: center;
    padding: 35px 16px;
    color: #94a3b8;
  }

  .heatbot-history-empty-icon {
    font-size: 34px;
    margin-bottom: 8px;
    opacity: 0.6;
    color: #ea580c;
  }

  .heatbot-icon-btn {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #cbd5e1;
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s ease;
  }

  .heatbot-icon-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
  }

  /* Zone de Messages */
  .heatbot-messages-area {
    flex: 1;
    overflow-y: auto;
    padding: 18px 16px;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    scroll-behavior: smooth;
  }

  .heatbot-messages-area-inner {
    display: flex;
    flex-direction: column;
    gap: 16px;
    width: 100%;
  }

  .heatbot-messages-area::-webkit-scrollbar {
    width: 6px;
  }

  .heatbot-messages-area::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
  }

  /* Bulle de Message Individuelle */
  .heatbot-msg-row {
    display: flex;
    gap: 12px;
    width: 100%;
    animation: msgFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  }

  @keyframes msgFadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .heatbot-msg-row.user {
    justify-content: flex-end;
  }

  .heatbot-msg-row.bot {
    justify-content: flex-start;
  }

  .heatbot-msg-avatar {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
  }

  .heatbot-msg-avatar.bot {
    background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
    color: #ffffff;
    box-shadow: 0 4px 10px rgba(220, 38, 38, 0.25);
  }

  .heatbot-msg-avatar.user {
    background: #334155;
    color: #ffffff;
  }

  .heatbot-msg-body {
    max-width: 86%;
    display: flex;
    flex-direction: column;
  }

  .heatbot-msg-row.user .heatbot-msg-body {
    align-items: flex-end;
  }

  .heatbot-msg-row.bot .heatbot-msg-body {
    align-items: flex-start;
  }

  /* Conteneur de Bulle avec réinitialisation stricte en block vertical */
  .heatbot-msg-bubble {
    display: block !important;
    padding: 14px 18px;
    border-radius: 18px;
    font-size: 13.5px;
    line-height: 1.6;
    word-break: break-word !important;
    overflow-wrap: anywhere !important;
    white-space: normal !important;
    text-align: left !important;
    width: fit-content;
    max-width: 100%;
  }

  .heatbot-msg-row.bot .heatbot-msg-bubble {
    background: #ffffff;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    border-top-left-radius: 4px;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
  }

  .heatbot-msg-row.user .heatbot-msg-bubble {
    background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
    color: #ffffff;
    border-top-right-radius: 4px;
    box-shadow: 0 4px 12px rgba(234, 88, 12, 0.25);
  }

  .heatbot-msg-time {
    font-size: 10.5px;
    color: #94a3b8;
    margin-top: 4px;
    display: block;
  }

  .heatbot-msg-row.user .heatbot-msg-time {
    color: #94a3b8;
    text-align: right;
  }

  /* ── RÈGLES STRICTES DE MISE EN PAGE VERTICALE DU MARKDOWN ── */
  .heatbot-msg-bubble > * {
    display: block !important;
    width: 100% !important;
    margin-bottom: 8px !important;
  }

  .heatbot-msg-bubble > *:last-child {
    margin-bottom: 0 !important;
  }

  .heatbot-msg-bubble h1,
  .heatbot-msg-bubble h2,
  .heatbot-msg-bubble h3,
  .heatbot-msg-bubble h4 {
    display: block !important;
    font-size: 14.5px !important;
    font-weight: 700 !important;
    margin: 10px 0 6px 0 !important;
    color: #0f172a !important;
    line-height: 1.4 !important;
  }

  .heatbot-msg-bubble p {
    display: block !important;
    margin: 0 0 8px 0 !important;
    line-height: 1.6 !important;
  }

  .heatbot-msg-bubble ul,
  .heatbot-msg-bubble ol {
    display: block !important;
    list-style-position: outside !important;
    margin: 6px 0 10px 20px !important;
    padding: 0 !important;
  }

  .heatbot-msg-bubble ul {
    list-style-type: disc !important;
  }

  .heatbot-msg-bubble ol {
    list-style-type: decimal !important;
  }

  .heatbot-msg-bubble li {
    display: list-item !important;
    margin: 0 0 5px 0 !important;
    padding: 0 !important;
    line-height: 1.55 !important;
  }

  .heatbot-msg-bubble strong {
    display: inline !important;
    font-weight: 700 !important;
    color: inherit !important;
  }

  .heatbot-msg-row.bot .heatbot-msg-bubble strong {
    color: #0f172a !important;
  }

  .heatbot-msg-row.user .heatbot-msg-bubble strong {
    color: #ffffff !important;
  }

  .heatbot-msg-bubble a {
    display: inline-block !important;
    color: #ea580c;
    text-decoration: underline;
    font-weight: 600;
  }

  /* Badges numéros d'urgence */
  .heatbot-emergency-badge {
    display: inline-flex !important;
    align-items: center !important;
    padding: 2px 8px !important;
    border-radius: 6px !important;
    font-weight: 700 !important;
    text-decoration: none !important;
    margin: 2px 0 !important;
  }
  .heatbot-emergency-badge.red {
    background: #fee2e2 !important;
    color: #dc2626 !important;
    border: 1px solid #fca5a5 !important;
  }
  .heatbot-emergency-badge.blue {
    background: #dbeafe !important;
    color: #2563eb !important;
    border: 1px solid #93c5fd !important;
  }

  /* Carte Chart Container dans le chat */
  .heatbot-chart-card {
    display: block !important;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 14px;
    margin-top: 12px !important;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
    position: relative;
    width: 100%;
  }

  .heatbot-chart-title {
    font-size: 13px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 12px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
  }

  .heatbot-chart-canvas-wrap {
    position: relative;
    width: 100%;
    min-height: 220px;
    max-height: 250px;
  }

  /* Indicateur de frappe */
  .heatbot-typing {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 10px 14px;
    background: #ffffff;
    border-radius: 16px;
    width: fit-content;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  }

  .heatbot-dot {
    width: 7px;
    height: 7px;
    background-color: #ea580c;
    border-radius: 50%;
    animation: heatbotBounce 1.4s infinite ease-in-out both;
  }

  .heatbot-dot:nth-child(1) { animation-delay: -0.32s; }
  .heatbot-dot:nth-child(2) { animation-delay: -0.16s; }
  .heatbot-dot:nth-child(3) { animation-delay: 0s; }

  @keyframes heatbotBounce {
    0%, 80%, 100% { transform: scale(0); opacity: 0.4; }
    40% { transform: scale(1); opacity: 1; }
  }

  /* Suggestions / Badges d'action rapide */
  .heatbot-suggestions-bar {
    padding: 8px 14px;
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
    flex-shrink: 0;
  }

  .heatbot-suggestions-bar-inner {
    display: flex;
    gap: 6px;
    overflow-x: auto;
    white-space: nowrap;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
  }

  .heatbot-suggestions-bar-inner::-webkit-scrollbar {
    display: none;
  }

  .heatbot-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 13px;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
    flex-shrink: 0;
  }

  .heatbot-chip:hover {
    background: #ea580c;
    color: #ffffff;
    border-color: #ea580c;
    transform: translateY(-1px);
  }

  /* Zone d'Input / Footer */
  .heatbot-input-area {
    padding: 12px 14px;
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    flex-shrink: 0;
  }

  .heatbot-input-area-inner {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    width: 100%;
  }

  .heatbot-textarea {
    flex: 1;
    border: 1px solid #cbd5e1;
    border-radius: 18px;
    padding: 10px 14px;
    font-size: 13.5px;
    font-family: inherit;
    resize: none;
    outline: none;
    max-height: 100px;
    min-height: 42px;
    line-height: 1.4;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    background: #f8fafc;
  }

  .heatbot-textarea:focus {
    border-color: #ea580c;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.15);
  }

  .heatbot-send-btn {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: none;
    outline: none;
    background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    flex-shrink: 0;
    transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
  }

  .heatbot-send-btn:hover:not(:disabled) {
    transform: scale(1.08);
    box-shadow: 0 6px 16px rgba(220, 38, 38, 0.45);
  }

  .heatbot-send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #94a3b8;
    box-shadow: none;
  }

  /* ── RESPONSIVE : PETIT ÉCRAN (SMARTPHONES & TABLETTES < 768px) ── */
  @media (max-width: 768px) {
    #heatbot-floating-container {
      bottom: 16px;
      right: 16px;
    }

    .heatbot-chat-window {
      position: fixed !important;
      top: 0 !important;
      left: 0 !important;
      right: 0 !important;
      bottom: 0 !important;
      width: 100vw !important;
      height: 100% !important;
      max-width: 100vw !important;
      max-height: 100% !important;
      border-radius: 0 !important;
      border: none !important;
      box-shadow: none !important;
      z-index: 10000000 !important;
    }

    /* Masquer le bouton d'agrandissement en plein écran sur smartphone */
    #heatbotFullscreenBtn {
      display: none !important;
    }

    .heatbot-header {
      padding: 12px 14px;
      border-radius: 0;
    }

    .heatbot-title {
      font-size: 15px;
    }

    .heatbot-msg-body {
      max-width: 90%;
    }

    .heatbot-msg-bubble {
      font-size: 13px;
      padding: 12px 14px;
    }

    /* Évite le zoom automatique sur iPhone lors de la saisie */
    .heatbot-textarea {
      font-size: 16px !important;
    }

    .heatbot-chart-canvas-wrap {
      min-height: 190px !important;
      max-height: 220px !important;
    }

    .heatbot-launcher-btn {
      width: 58px;
      height: 58px;
      font-size: 24px;
    }
  }
</style>

<!-- Structure HTML du Widget -->
<div id="heatbot-floating-container">

  <!-- Teaser initial (affiche un message engageant si la bulle est fermée) -->
  <div id="heatbotTeaser" class="heatbot-teaser-bubble" onclick="openHeatBot()">
    <div style="font-size: 22px;">🤖</div>
    <div>
      <div style="font-size: 12.5px; font-weight: 700; color: #0f172a;">Besoin d'aide ou de stats ?</div>
      <div style="font-size: 11px; color: #64748b;">Je surveille feux, météo & fraîcheur en direct.</div>
    </div>
    <button type="button" class="heatbot-teaser-close" onclick="event.stopPropagation(); closeHeatBotTeaser();">✕</button>
  </div>

  <!-- Fenêtre de Chatbot -->
  <div id="heatbotChatWindow" class="heatbot-chat-window" role="dialog" aria-label="Discussion avec HeatBot IA">

    <!-- En-tête -->
    <div class="heatbot-header">
      <div class="heatbot-header-left">
        <div class="heatbot-avatar-frame">
          <i class="bi bi-robot"></i>
        </div>
        <div>
          <h4 class="heatbot-title">
            HeatBot IA
          </h4>
          <p class="heatbot-subtitle">
            <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#22c55e;"></span>
            Interconnecté à la plateforme & NASA FIRMS
          </p>
        </div>
      </div>
      <div class="heatbot-header-actions">

        <button type="button" id="heatbotHistoryToggleBtn" class="heatbot-icon-btn" title="Historique de mes discussions" onclick="toggleHeatBotHistoryPanel()">
          <i class="bi bi-clock-history"></i>
        </button>
        <button type="button" id="heatbotFullscreenBtn" class="heatbot-icon-btn" title="Plein écran" onclick="toggleHeatBotFullscreen()">
          <i id="heatbotFullscreenIcon" class="bi bi-arrows-angle-expand"></i>
        </button>
        <button type="button" class="heatbot-icon-btn" title="Fermer" onclick="closeHeatBot()">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>
    </div>

    <!-- Tiroir / Panneau d'Historique des Discussions -->
    <div id="heatbotHistoryPanel" class="heatbot-history-panel">
      <div class="heatbot-history-header">
        <div class="heatbot-history-title">
          <i class="bi bi-clock-history text-warning me-2"></i>
          <span>Historique de vos discussions</span>
        </div>
        <button type="button" class="heatbot-icon-btn" title="Fermer l'historique" onclick="toggleHeatBotHistoryPanel()">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <div class="heatbot-history-actions-bar">
        <button type="button" class="heatbot-new-chat-btn" onclick="startNewHeatBotConversation()">
          <i class="bi bi-plus-circle-fill me-1"></i> Nouvelle discussion
        </button>
        <button type="button" class="heatbot-clear-all-btn" title="Tout effacer" onclick="askClearAllConversations()">
          <i class="bi bi-trash3"></i>
        </button>
      </div>

      <div id="heatbotHistoryList" class="heatbot-history-list">
        <!-- Rempli dynamiquement par JS -->
      </div>
    </div>

    <!-- Toast / Bandeau de Confirmation Personnalisé (Sans alert/confirm de navigateur) -->
    <div id="heatbotConfirmToast" class="heatbot-confirm-toast">
      <div class="heatbot-confirm-toast-text">
        <i class="bi bi-trash3-fill text-danger me-2"></i>
        <span id="heatbotConfirmToastText">Réinitialiser la discussion ?</span>
      </div>
      <div class="heatbot-confirm-toast-actions">
        <button type="button" class="heatbot-confirm-btn cancel" onclick="cancelResetHeatBot()">Annuler</button>
        <button type="button" class="heatbot-confirm-btn confirm" onclick="confirmResetHeatBot()">Effacer</button>
      </div>
    </div>

    <!-- Toast Éphémère de Succès -->
    <div id="heatbotNotificationToast" class="heatbot-notif-toast">
      <i class="bi bi-check-circle-fill text-success me-2"></i>
      <span id="heatbotNotifText">Conversation réinitialisée ✨</span>
    </div>

    <!-- Zone des Messages -->
    <div id="heatbotMessagesArea" class="heatbot-messages-area">
      <div class="heatbot-messages-area-inner" id="heatbotMessagesInner">
        <!-- Les messages s'insèrent ici par Javascript -->
      </div>
    </div>

    <!-- Barre de suggestions dynamiques -->
    <div class="heatbot-suggestions-bar">
      <div id="heatbotSuggestionsBar" class="heatbot-suggestions-bar-inner">
        <button type="button" class="heatbot-chip" onclick="sendHeatBotSuggestion('📊 Donne-moi les statistiques globales de la plateforme')">
          📊 Statistiques
        </button>
        <button type="button" class="heatbot-chip" onclick="sendHeatBotSuggestion('🔥 Quels sont les feux récents détectés par la NASA ?')">
          🔥 Feux NASA FIRMS
        </button>
        <button type="button" class="heatbot-chip" onclick="sendHeatBotSuggestion('🌤️ Quelles sont les alertes météo en cours ?')">
          🌤️ Alertes Météo
        </button>
        <button type="button" class="heatbot-chip" onclick="sendHeatBotSuggestion('❄️ Où trouver un point fraîcheur ou une salle climatisée ?')">
          ❄️ Points Fraîcheur
        </button>
        <button type="button" class="heatbot-chip" onclick="sendHeatBotSuggestion('💡 Quels sont les conseils prioritaires contre la canicule ?')">
          💡 Conseils Canicule
        </button>
        <button type="button" class="heatbot-chip" onclick="sendHeatBotSuggestion('⚡ Y a-t-il des coupures d\'électricité signalées ?')">
          ⚡ Signalements Coupures
        </button>
      </div>
    </div>

    <!-- Zone de Saisie Utilisateur -->
    <div class="heatbot-input-area">
      <div class="heatbot-input-area-inner">
        <textarea id="heatbotInput" class="heatbot-textarea"
                  placeholder="Posez votre question"
                  rows="1" onkeydown="handleHeatBotKeyDown(event)"></textarea>
        <button type="button" id="heatbotSendBtn" class="heatbot-send-btn" title="Envoyer le message" onclick="submitHeatBotMessage()">
          <i class="bi bi-send-fill"></i>
        </button>
      </div>
    </div>

  </div>

  <!-- Bouton Flottant (Launcher) -->
  <button type="button" id="heatbotLauncherBtn" class="heatbot-launcher-btn" aria-label="Ouvrir HeatBot" onclick="toggleHeatBot()">
    <div class="heatbot-pulse-ring"></div>
    <i id="heatbotLauncherIcon" class="bi bi-chat-dots-fill"></i>
    <div class="heatbot-online-badge"></div>
  </button>

</div>

<!-- Script Principal du Chatbot HeatAlert -->
<script>
  // Configuration
  const HEATBOT_ROUTES = {
    ask: "{{ route('chatbot.ask') }}",
    stats: "{{ route('chatbot.stats') }}",
    conversations: "{{ route('chatbot.conversations') }}",
    newConversation: "{{ route('chatbot.conversations.new') }}",
    showConversationBase: "{{ url('/chatbot/conversations') }}",
    deleteConversationBase: "{{ url('/chatbot/conversations') }}",
    clearConversations: "{{ route('chatbot.conversations.clear') }}",
    csrf: "{{ csrf_token() }}",
  };

  const CURRENT_USER_NAME = @json(auth()->user()?->prenom ?? auth()->user()?->nom ?? auth()->user()?->name ?? null);

  // État local du Chatbot
  let heatbotState = {
    isOpen: false,
    isFullscreen: false,
    isThinking: false,
    historyPanelOpen: false,
    activeConversationId: null,
    activeConversationTitle: null,
    history: [],
    chartInstances: {},
  };

  // ── Initialisation au chargement de la page ──
  document.addEventListener('DOMContentLoaded', () => {
    loadHeatBotSession();

    // Si aucune conversation n'a démarré, afficher le message de bienvenue pro
    if (heatbotState.history.length === 0) {
      appendHeatBotWelcomeMessage();
    } else {
      renderAllStoredMessages();
    }

    // Afficher le teaser après 4 secondes si la fenêtre n'a pas été ouverte
    setTimeout(() => {
      const teaserDismissed = sessionStorage.getItem('heatbot_teaser_dismissed');
      if (!teaserDismissed && !heatbotState.isOpen) {
        const teaser = document.getElementById('heatbotTeaser');
        if (teaser) teaser.style.display = 'flex';
      }
    }, 4000);
  });

  // ── Ouvrir / Fermer le Chatbot ──
  function toggleHeatBot() {
    if (heatbotState.isOpen) {
      closeHeatBot();
    } else {
      openHeatBot();
    }
  }

  function openHeatBot() {
    heatbotState.isOpen = true;
    const container = document.getElementById('heatbot-floating-container');
    const windowEl = document.getElementById('heatbotChatWindow');
    const iconEl = document.getElementById('heatbotLauncherIcon');

    container.classList.add('is-open');
    windowEl.classList.add('open');
    iconEl.className = 'bi bi-x-lg';
    closeHeatBotTeaser();

    // Auto-focus sur le champ input
    setTimeout(() => {
      const input = document.getElementById('heatbotInput');
      if (input) input.focus();
      scrollHeatBotToBottom();
      resizeAllCharts();
    }, 250);
  }

  function closeHeatBot() {
    heatbotState.isOpen = false;
    closeHeatBotHistoryPanel();
    const container = document.getElementById('heatbot-floating-container');
    const windowEl = document.getElementById('heatbotChatWindow');
    const iconEl = document.getElementById('heatbotLauncherIcon');

    container.classList.remove('is-open');
    windowEl.classList.remove('open');
    iconEl.className = 'bi bi-chat-dots-fill';

    if (heatbotState.isFullscreen) {
      toggleHeatBotFullscreen();
    }
  }

  function closeHeatBotTeaser() {
    const teaser = document.getElementById('heatbotTeaser');
    if (teaser) teaser.style.display = 'none';
    sessionStorage.setItem('heatbot_teaser_dismissed', 'true');
  }

  function toggleHeatBotFullscreen() {
    heatbotState.isFullscreen = !heatbotState.isFullscreen;
    const windowEl = document.getElementById('heatbotChatWindow');
    const icon = document.getElementById('heatbotFullscreenIcon');

    if (heatbotState.isFullscreen) {
      windowEl.classList.add('fullscreen');
      icon.className = 'bi bi-arrows-angle-contract';
      icon.setAttribute('title', 'Réduire');
    } else {
      windowEl.classList.remove('fullscreen');
      icon.className = 'bi bi-arrows-angle-expand';
      icon.setAttribute('title', 'Plein écran');
    }

    setTimeout(() => {
      resizeAllCharts();
      scrollHeatBotToBottom();
    }, 300);
  }

  function resizeAllCharts() {
    Object.values(heatbotState.chartInstances).forEach(chart => {
      try { chart.resize(); } catch(e){}
    });
  }

  // ── Gestion du Toast de confirmation personnalisé ──
  let confirmToastCallback = null;
  function showConfirmToast(messageText, onConfirm) {
    const toast = document.getElementById('heatbotConfirmToast');
    const textEl = document.getElementById('heatbotConfirmToastText');
    if (textEl) textEl.innerText = messageText;
    confirmToastCallback = onConfirm;
    if (toast) toast.style.display = 'flex';
  }

  function cancelResetHeatBot() {
    confirmToastCallback = null;
    const toast = document.getElementById('heatbotConfirmToast');
    if (toast) toast.style.display = 'none';
  }

  function confirmResetHeatBot() {
    if (confirmToastCallback) {
      confirmToastCallback();
      cancelResetHeatBot();
    } else {
      cancelResetHeatBot();
      startNewHeatBotConversation();
    }
  }

  function resetHeatBotHistory() {
    showConfirmToast("Effacer cette discussion ?", () => {
      startNewHeatBotConversation();
      showHeatBotNotification("Discussion réinitialisée ✨");
    });
  }

  function showHeatBotNotification(msg) {
    const notif = document.getElementById('heatbotNotificationToast');
    const textEl = document.getElementById('heatbotNotifText');
    if (!notif) return;
    if (textEl) textEl.innerText = msg;
    notif.style.display = 'flex';
    setTimeout(() => {
      notif.style.display = 'none';
    }, 2400);
  }

  // ── Tiroir & Historique des Discussions ──
  function toggleHeatBotHistoryPanel() {
    heatbotState.historyPanelOpen = !heatbotState.historyPanelOpen;
    const panel = document.getElementById('heatbotHistoryPanel');
    if (!panel) return;

    if (heatbotState.historyPanelOpen) {
      panel.classList.add('open');
      fetchHeatBotConversations();
    } else {
      panel.classList.remove('open');
    }
  }

  function closeHeatBotHistoryPanel() {
    heatbotState.historyPanelOpen = false;
    const panel = document.getElementById('heatbotHistoryPanel');
    if (panel) panel.classList.remove('open');
  }

  async function fetchHeatBotConversations() {
    const listEl = document.getElementById('heatbotHistoryList');
    if (!listEl) return;

    listEl.innerHTML = `
      <div style="text-align:center; padding:35px 15px; color:#94a3b8; font-size:12px;">
        <span class="spinner-border spinner-border-sm text-warning me-2" role="status"></span>
        Chargement de vos discussions...
      </div>
    `;

    try {
      const res = await fetch(HEATBOT_ROUTES.conversations, {
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': HEATBOT_ROUTES.csrf,
        }
      });
      const data = await res.json();

      if (data.success && Array.isArray(data.conversations) && data.conversations.length > 0) {
        renderHistoryList(data.conversations);
      } else {
        listEl.innerHTML = `
          <div class="heatbot-history-empty">
            <div class="heatbot-history-empty-icon"><i class="bi bi-chat-square-text"></i></div>
            <div style="font-weight:600; color:#f1f5f9; font-size:13px; margin-bottom:4px;">Aucune discussion</div>
            <div style="font-size:11.5px; color:#94a3b8;">Vos échanges avec HeatBot apparaîtront ici automatiquement.</div>
          </div>
        `;
      }
    } catch (e) {
      console.error('Error fetching conversations:', e);
      listEl.innerHTML = `
        <div style="text-align:center; padding:25px; color:#ef4444; font-size:12px;">
          Impossible de charger l'historique des discussions.
        </div>
      `;
    }
  }

  function renderHistoryList(conversations) {
    const listEl = document.getElementById('heatbotHistoryList');
    if (!listEl) return;
    listEl.innerHTML = '';

    conversations.forEach(conv => {
      const item = document.createElement('div');
      const isActive = heatbotState.activeConversationId === conv.id;
      item.className = 'heatbot-history-item' + (isActive ? ' active' : '');
      item.onclick = () => selectHeatBotConversation(conv.id);

      item.innerHTML = `
        <div class="heatbot-history-item-info">
          <div class="heatbot-history-item-title" title="${escapeHtml(conv.title)}">
            <i class="bi bi-chat-left-text-fill text-warning me-1" style="font-size:10px;"></i>
            ${escapeHtml(conv.title)}
          </div>
          <div class="heatbot-history-item-meta">
            <span><i class="bi bi-clock me-1"></i>${escapeHtml(conv.updated_at || conv.date)}</span>
            <span class="heatbot-history-item-badge">${conv.messages_count} msg</span>
          </div>
        </div>
        <button type="button" class="heatbot-history-delete-btn" title="Supprimer cette discussion" onclick="deleteHeatBotConversation(${conv.id}, event)">
          <i class="bi bi-trash3"></i>
        </button>
      `;

      listEl.appendChild(item);
    });
  }

  async function selectHeatBotConversation(convId) {
    closeHeatBotHistoryPanel();
    const messagesArea = document.getElementById('heatbotMessagesInner');
    messagesArea.innerHTML = `
      <div style="text-align:center; padding:35px 15px; color:#64748b; font-size:13px;">
        <span class="spinner-border spinner-border-sm text-danger me-2" role="status"></span>
        Chargement de la discussion...
      </div>
    `;

    try {
      const res = await fetch(`${HEATBOT_ROUTES.showConversationBase}/${convId}`, {
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': HEATBOT_ROUTES.csrf,
        }
      });
      const data = await res.json();

      if (data.success && data.messages) {
        heatbotState.activeConversationId = convId;
        heatbotState.activeConversationTitle = data.conversation.title;
        heatbotState.history = [];

        messagesArea.innerHTML = '';
        data.messages.forEach(msg => {
          if (msg.role === 'user') {
            appendUserMessageToDom(msg.content, msg.time || '');
            heatbotState.history.push({ role: 'user', content: msg.content, time: msg.time });
          } else {
            const formatted = renderMarkdownToHtml(msg.content);
            appendBotMessageToDom(formatted, msg.chart, msg.time || '', false);
            heatbotState.history.push({ role: 'bot', content: msg.content, chart: msg.chart, time: msg.time });
          }
        });

        saveHeatBotSession();
        scrollHeatBotToBottom();
        showHeatBotNotification(`Discussion "${escapeHtml(data.conversation.title)}" chargée ✨`);
      } else {
        showHeatBotNotification("Impossible de charger la discussion");
        appendHeatBotWelcomeMessage();
      }
    } catch (e) {
      console.error('Error loading conversation messages:', e);
      messagesArea.innerHTML = '';
      appendHeatBotWelcomeMessage();
      showHeatBotNotification("Erreur de communication avec le serveur");
    }
  }

  function startNewHeatBotConversation() {
    closeHeatBotHistoryPanel();
    heatbotState.activeConversationId = null;
    heatbotState.activeConversationTitle = null;
    heatbotState.history = [];
    sessionStorage.removeItem('heatbot_active_conv_id');
    sessionStorage.removeItem('heatbot_history_v1');

    const messagesArea = document.getElementById('heatbotMessagesInner');
    if (messagesArea) messagesArea.innerHTML = '';
    appendHeatBotWelcomeMessage();

    // Réinitialiser les suggestions par défaut
    updateSuggestionsBar([
      '📊 Donne-moi les statistiques globales de la plateforme',
      '🔥 Quels sont les feux récents détectés par la NASA ?',
      '🌤️ Quelles sont les alertes météo en cours ?',
      '❄️ Où trouver un point fraîcheur ou une salle climatisée ?',
      '💡 Quels sont les conseils prioritaires contre la canicule ?',
      '⚡ Y a-t-il des coupures d\'électricité signalées ?',
    ]);

    showHeatBotNotification("Nouvelle discussion commencée ✨");
    const input = document.getElementById('heatbotInput');
    if (input) input.focus();
  }

  function deleteHeatBotConversation(convId, event) {
    if (event) event.stopPropagation();

    showConfirmToast("Supprimer cette discussion ?", async () => {
      try {
        const res = await fetch(`${HEATBOT_ROUTES.deleteConversationBase}/${convId}`, {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': HEATBOT_ROUTES.csrf,
          }
        });
        const data = await res.json();
        if (data.success) {
          showHeatBotNotification("Discussion supprimée 🗑️");
          if (heatbotState.activeConversationId === convId) {
            startNewHeatBotConversation();
          }
          fetchHeatBotConversations();
        }
      } catch (e) {
        console.error('Error deleting conversation:', e);
      }
    });
  }

  function askClearAllConversations() {
    showConfirmToast("Supprimer TOUT votre historique ?", async () => {
      try {
        const res = await fetch(HEATBOT_ROUTES.clearConversations, {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': HEATBOT_ROUTES.csrf,
          }
        });
        const data = await res.json();
        if (data.success) {
          showHeatBotNotification("Historique effacé 🗑️");
          startNewHeatBotConversation();
          fetchHeatBotConversations();
        }
      } catch (e) {
        console.error('Error clearing conversations:', e);
      }
    });
  }

  // ── Message de bienvenue ──
  function appendHeatBotWelcomeMessage() {
    const greeting = CURRENT_USER_NAME ? `Bonjour **${escapeHtml(CURRENT_USER_NAME)}** !` : `Bonjour !`;
    const welcomeHtml = `
      <div style="font-weight:700; color:#ea580c; margin-bottom:6px;">
        <i class="bi bi-shield-check me-1"></i>${greeting} Je suis HeatBot.
      </div>
      <p style="margin-bottom:8px;">
        Votre conseiller intelligent pour la veille canicule, météo et sécurité civile. J'analyse en temps réel :
      </p>
      <ul style="margin:6px 0 10px 20px; padding:0; font-size:13px;">
        <li>📊 <strong>Statistiques & Graphiques</strong> de la plateforme HeatAlert</li>
        <li>🔥 <strong>Détection des feux</strong> via satellite NASA FIRMS</li>
        <li>🌤️ <strong>Météo mondiale & nationale</strong> (Open-Meteo)</li>
        <li>❄️ <strong>Points Fraîcheur</strong> et refuges climatisés</li>
        <li>💡 <strong>Conseils de santé</strong> & gestes qui sauvent</li>
        <li>⚡ <strong>Signalements & Coupures</strong> d'électricité</li>
      </ul>
      <p style="font-size:12px; color:#64748b; margin:0;">
        💡 <em>Cliquez sur une suggestion ci-dessous ou posez-moi votre question directement !</em>
      </p>
    `;
    appendBotMessageToDom(welcomeHtml, null, getCurrentTime(), false);
  }

  // ── Gestion de la saisie utilisateur ──
  function handleHeatBotKeyDown(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      submitHeatBotMessage();
    }
  }

  function sendHeatBotSuggestion(text) {
    const input = document.getElementById('heatbotInput');
    input.value = text;
    submitHeatBotMessage();
  }

  // ── Envoi du message au serveur ──
  async function submitHeatBotMessage() {
    const input = document.getElementById('heatbotInput');
    const sendBtn = document.getElementById('heatbotSendBtn');
    const message = input.value.trim();

    if (!message || heatbotState.isThinking) return;

    // Ajout message utilisateur
    const userTime = getCurrentTime();
    appendUserMessageToDom(message, userTime);
    heatbotState.history.push({ role: 'user', content: message, time: userTime });
    saveHeatBotSession();

    input.value = '';
    input.style.height = 'auto';
    heatbotState.isThinking = true;
    sendBtn.disabled = true;

    // Afficher l'indicateur de frappe
    const typingRow = showTypingIndicator();

    try {
      const response = await fetch(HEATBOT_ROUTES.ask, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': HEATBOT_ROUTES.csrf,
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          message: message,
          conversation_id: heatbotState.activeConversationId,
          history: heatbotState.history.slice(-8).map(h => ({
            role: h.role,
            text: h.content,
          })),
        }),
      });

      const data = await response.json();
      removeTypingIndicator(typingRow);

      if (data.success) {
        if (data.conversation_id) {
          heatbotState.activeConversationId = data.conversation_id;
          heatbotState.activeConversationTitle = data.conversation_title;
        }

        const botTime = data.timestamp || getCurrentTime();
        const formattedHtml = renderMarkdownToHtml(data.reply);
        appendBotMessageToDom(formattedHtml, data.chart, botTime, true);

        heatbotState.history.push({
          role: 'bot',
          content: data.reply,
          chart: data.chart,
          time: botTime,
        });
        saveHeatBotSession();

        // Mettre à jour les suggestions rapides
        if (data.suggestions && data.suggestions.length) {
          updateSuggestionsBar(data.suggestions);
        }
      } else {
        appendBotMessageToDom("Désolé, une anomalie temporaire est survenue. Veuillez réessayer dans quelques instants.", null, getCurrentTime(), true);
      }
    } catch (err) {
      console.error('HeatBot Error:', err);
      removeTypingIndicator(typingRow);
      appendBotMessageToDom("⚠️ Impossible de joindre le serveur. Vérifiez votre connexion internet.", null, getCurrentTime(), true);
    } finally {
      heatbotState.isThinking = false;
      sendBtn.disabled = false;
      input.focus();
    }
  }

  // ── Affichage des messages dans le DOM ──
  function appendUserMessageToDom(text, time) {
    const messagesArea = document.getElementById('heatbotMessagesInner');
    const row = document.createElement('div');
    row.className = 'heatbot-msg-row user';
    row.innerHTML = `
      <div class="heatbot-msg-body">
        <div class="heatbot-msg-bubble">${escapeHtml(text)}</div>
        <span class="heatbot-msg-time">${time}</span>
      </div>
      <div class="heatbot-msg-avatar user"><i class="bi bi-person-fill"></i></div>
    `;
    messagesArea.appendChild(row);
    scrollHeatBotToBottom();
  }

  function appendBotMessageToDom(htmlContent, chartData, time, animate) {
    const messagesArea = document.getElementById('heatbotMessagesInner');
    const row = document.createElement('div');
    row.className = 'heatbot-msg-row bot';

    const chartCanvasId = 'heatbot_chart_' + Date.now() + '_' + Math.floor(Math.random() * 1000);

    let chartHtml = '';
    if (chartData && typeof chartData === 'object') {
      chartHtml = `
        <div class="heatbot-chart-card">
          <div class="heatbot-chart-title">
            <i class="bi bi-bar-chart-fill text-danger me-1"></i>
            ${escapeHtml(chartData.title || 'Visualisation')}
          </div>
          <div class="heatbot-chart-canvas-wrap">
            <canvas id="${chartCanvasId}"></canvas>
          </div>
        </div>
      `;
    }

    row.innerHTML = `
      <div class="heatbot-msg-avatar bot"><i class="bi bi-robot"></i></div>
      <div class="heatbot-msg-body">
        <div class="heatbot-msg-bubble">
          ${htmlContent}
          ${chartHtml}
        </div>
        <span class="heatbot-msg-time">${time}</span>
      </div>
    `;

    messagesArea.appendChild(row);
    scrollHeatBotToBottom();

    // Rendu du Chart.js si applicable
    if (chartData && typeof chartData === 'object') {
      setTimeout(() => {
        renderChartOnCanvas(chartCanvasId, chartData);
      }, 100);
    }
  }

  // ── Instanciation graphique Chart.js ──
  function renderChartOnCanvas(canvasId, config) {
    const canvas = document.getElementById(canvasId);
    if (!canvas || typeof Chart === 'undefined') return;

    try {
      const ctx = canvas.getContext('2d');
      const chartType = config.type || 'doughnut';

      // Palette de repli si absente
      const fallbackColors = ['#dc2626', '#ea580c', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899'];
      if (config.datasets) {
        config.datasets.forEach((ds, idx) => {
          if (!ds.backgroundColor) {
            ds.backgroundColor = fallbackColors;
          }
          if (chartType === 'line' && !ds.borderColor) {
            ds.borderColor = '#ea580c';
            ds.backgroundColor = 'rgba(234, 88, 12, 0.15)';
            ds.tension = 0.35;
          }
        });
      }

      const newChart = new Chart(ctx, {
        type: chartType,
        data: {
          labels: config.labels || [],
          datasets: config.datasets || [],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: chartType === 'doughnut' || chartType === 'pie' ? 'bottom' : 'top',
              labels: {
                boxWidth: 12,
                font: { size: 11, family: 'Montserrat, sans-serif' },
                color: '#334155',
              },
            },
            tooltip: {
              backgroundColor: '#0f172a',
              titleFont: { size: 12, weight: 'bold' },
              bodyFont: { size: 11.5 },
              padding: 10,
              cornerRadius: 8,
            },
          },
          scales: chartType === 'doughnut' || chartType === 'pie' ? {} : {
            y: {
              beginAtZero: true,
              ticks: { font: { size: 10.5 }, color: '#64748b' },
              grid: { color: 'rgba(226, 232, 240, 0.6)' },
            },
            x: {
              ticks: { font: { size: 10 }, color: '#64748b' },
              grid: { display: false },
            },
          },
        },
      });

      heatbotState.chartInstances[canvasId] = newChart;
    } catch (e) {
      console.error('Error creating HeatBot chart:', e);
    }
  }

  // ── Indicateur de Frappe ──
  function showTypingIndicator() {
    const messagesArea = document.getElementById('heatbotMessagesInner');
    const row = document.createElement('div');
    row.className = 'heatbot-msg-row bot';
    row.id = 'heatbotTypingIndicator';
    row.innerHTML = `
      <div class="heatbot-msg-avatar bot"><i class="bi bi-robot"></i></div>
      <div class="heatbot-typing">
        <span class="heatbot-dot"></span>
        <span class="heatbot-dot"></span>
        <span class="heatbot-dot"></span>
        <span style="font-size:11.5px; color:#64748b; margin-left:4px;">HeatBot analyse...</span>
      </div>
    `;
    messagesArea.appendChild(row);
    scrollHeatBotToBottom();
    return row;
  }

  function removeTypingIndicator(row) {
    if (row && row.parentNode) {
      row.parentNode.removeChild(row);
    } else {
      const el = document.getElementById('heatbotTypingIndicator');
      if (el) el.remove();
    }
  }

  // ── Mise à jour des suggestions ──
  function updateSuggestionsBar(suggestions) {
    const bar = document.getElementById('heatbotSuggestionsBar');
    if (!bar) return;
    bar.innerHTML = '';
    suggestions.forEach(sug => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'heatbot-chip';
      btn.innerText = sug;
      btn.onclick = () => sendHeatBotSuggestion(sug);
      bar.appendChild(btn);
    });
  }

  // ── Rendu Markdown Professionnel via Marked.js ──
  function renderMarkdownToHtml(md) {
    if (!md) return '';
    let html = '';

    if (typeof marked !== 'undefined') {
      try {
        marked.setOptions({
          gfm: true,
          breaks: true,
        });
        html = marked.parse(md);
      } catch (e) {
        html = fallbackSimpleMarkdown(md);
      }
    } else {
      html = fallbackSimpleMarkdown(md);
    }

    // Mise en valeur des numéros d'urgence cliquables
    html = html.replace(/\b(198)\b/g, '<a href="tel:198" class="heatbot-emergency-badge red"><i class="bi bi-telephone-fill me-1"></i>198 (Protection Civile)</a>');
    html = html.replace(/\b(190)\b/g, '<a href="tel:190" class="heatbot-emergency-badge blue"><i class="bi bi-telephone-fill me-1"></i>190 (SAMU)</a>');

    return html;
  }

  function fallbackSimpleMarkdown(text) {
    let clean = escapeHtml(text);
    // Titres
    clean = clean.replace(/^### (.*$)/gim, '<h3>$1</h3>');
    clean = clean.replace(/^## (.*$)/gim, '<h2>$1</h2>');
    clean = clean.replace(/^# (.*$)/gim, '<h1>$1</h1>');
    // Gras / Italique
    clean = clean.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    clean = clean.replace(/\*(.*?)\*/g, '<em>$1</em>');
    // Listes à puces
    clean = clean.replace(/^\- (.*$)/gim, '<li>$1</li>');
    clean = clean.replace(/(<li>.*<\/li>)/gms, '<ul>$1</ul>');
    // Sauts de ligne
    clean = clean.replace(/\n\n/g, '<p></p>');
    clean = clean.replace(/\n/g, '<br>');
    return clean;
  }

  function escapeHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.innerText = str;
    return div.innerHTML;
  }

  function getCurrentTime() {
    const now = new Date();
    return String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
  }

  function scrollHeatBotToBottom() {
    const area = document.getElementById('heatbotMessagesArea');
    if (area) {
      area.scrollTop = area.scrollHeight;
    }
  }

  // ── Session Storage (Persistance pendant la navigation) ──
  function saveHeatBotSession() {
    try {
      sessionStorage.setItem('heatbot_history_v1', JSON.stringify(heatbotState.history));
      if (heatbotState.activeConversationId) {
        sessionStorage.setItem('heatbot_active_conv_id', String(heatbotState.activeConversationId));
      } else {
        sessionStorage.removeItem('heatbot_active_conv_id');
      }
    } catch(e){}
  }

  function loadHeatBotSession() {
    try {
      const stored = sessionStorage.getItem('heatbot_history_v1');
      if (stored) {
        heatbotState.history = JSON.parse(stored);
      }
      const activeId = sessionStorage.getItem('heatbot_active_conv_id');
      if (activeId) {
        heatbotState.activeConversationId = parseInt(activeId, 10);
      }
    } catch(e) {
      heatbotState.history = [];
    }
  }

  function renderAllStoredMessages() {
    const messagesArea = document.getElementById('heatbotMessagesInner');
    messagesArea.innerHTML = '';
    heatbotState.history.forEach(item => {
      if (item.role === 'user') {
        appendUserMessageToDom(item.content, item.time || '');
      } else {
        const formatted = renderMarkdownToHtml(item.content);
        appendBotMessageToDom(formatted, item.chart, item.time || '', false);
      }
    });
  }
</script>
