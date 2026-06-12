<div id="chatbotWidget" class="cs-chatbot">
    <button type="button" id="chatbotToggle" class="cs-chatbot-toggle" aria-label="Buka chatbot">
        <span class="cs-logo" aria-hidden="true">
            <svg viewBox="0 0 24 24" role="img">
                <path d="M12 3.5c-4.85 0-8.78 3.5-8.78 7.82 0 2.44 1.26 4.62 3.23 6.05-.14 1.13-.58 2.2-1.27 3.11-.13.16-.13.4 0 .57.13.16.35.24.56.17 1.76-.55 3.16-1.25 4.19-2.12.64.15 1.31.23 2.07.23 4.85 0 8.78-3.5 8.78-7.81S16.85 3.5 12 3.5Zm-3.28 8.34a1.16 1.16 0 1 1 0-2.32 1.16 1.16 0 0 1 0 2.32Zm3.28 0a1.16 1.16 0 1 1 0-2.32 1.16 1.16 0 0 1 0 2.32Zm3.28 0a1.16 1.16 0 1 1 0-2.32 1.16 1.16 0 0 1 0 2.32Z"/>
            </svg>
        </span>
    </button>

    <div id="chatbotPanel" class="cs-chatbot-panel d-none">
        <div class="cs-chatbot-header">
            <div class="cs-chatbot-header-brand">
                <span class="cs-header-logo" aria-hidden="true">
                    <svg viewBox="0 0 24 24" role="img">
                        <path d="M12 3.5c-4.85 0-8.78 3.5-8.78 7.82 0 2.44 1.26 4.62 3.23 6.05-.14 1.13-.58 2.2-1.27 3.11-.13.16-.13.4 0 .57.13.16.35.24.56.17 1.76-.55 3.16-1.25 4.19-2.12.64.15 1.31.23 2.07.23 4.85 0 8.78-3.5 8.78-7.81S16.85 3.5 12 3.5Zm-3.28 8.34a1.16 1.16 0 1 1 0-2.32 1.16 1.16 0 0 1 0 2.32Zm3.28 0a1.16 1.16 0 1 1 0-2.32 1.16 1.16 0 0 1 0 2.32Zm3.28 0a1.16 1.16 0 1 1 0-2.32 1.16 1.16 0 0 1 0 2.32Z"/>
                    </svg>
                </span>
                <strong>Customer Service Bot</strong>
                <div class="small text-secondary">FAQ + Cek Produk</div>
            </div>
            <button type="button" id="chatbotClose" class="cs-chatbot-close">Tutup</button>
        </div>
        <div id="chatbotMessages" class="cs-chatbot-messages"></div>
        <form id="chatbotForm" class="cs-chatbot-form">
            @csrf
            <input type="text" id="chatbotInput" class="form-control" maxlength="500" placeholder="Tanya stok/harga/produk..." required>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>
</div>

<style>
    .cs-chatbot {
        position: fixed;
        right: 1.2rem;
        bottom: 1.2rem;
        z-index: 3600;
    }

    .cs-chatbot-toggle {
        width: 58px;
        height: 58px;
        border-radius: 50% !important;
        border: 1px solid rgba(251, 146, 60, 0.7);
        background: linear-gradient(135deg, #f97316, #fb923c);
        color: #101828;
        box-shadow: 0 12px 24px rgba(249, 115, 22, 0.35);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        padding: 0;
        overflow: hidden;
        line-height: 1;
    }

    .cs-chatbot-toggle:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 28px rgba(249, 115, 22, 0.4);
    }

    .cs-logo {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(17, 24, 39, 0.28);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
    }

    .cs-logo svg {
        width: 19px;
        height: 19px;
        fill: #111827;
    }

    .cs-chatbot-panel {
        width: min(360px, calc(100vw - 2rem));
        height: 480px;
        background:
            radial-gradient(circle at top right, rgba(249, 115, 22, 0.16), transparent 42%),
            rgba(18, 23, 33, 0.98);
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 30px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.38);
        margin-bottom: 0.7rem;
    }

    .cs-chatbot-header {
        padding: 0.75rem 0.95rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cs-chatbot-header-brand {
        display: grid;
        grid-template-columns: auto 1fr;
        grid-template-rows: auto auto;
        column-gap: 0.6rem;
        align-items: center;
    }

    .cs-chatbot-header-brand strong {
        grid-column: 2;
        line-height: 1.1;
    }

    .cs-chatbot-header-brand .small {
        grid-column: 2;
    }

    .cs-header-logo {
        width: 34px;
        height: 34px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.2), rgba(34, 197, 94, 0.2));
        border: 1px solid rgba(148, 163, 184, 0.2);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        grid-row: 1 / span 2;
    }

    .cs-header-logo svg {
        width: 18px;
        height: 18px;
        fill: #fbbf24;
    }

    .cs-chatbot-messages {
        flex: 1;
        padding: 0.8rem;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .cs-bubble {
        padding: 0.58rem 0.7rem;
        border-radius: 17px;
        white-space: pre-wrap;
        line-height: 1.35;
        font-size: 0.9rem;
        max-width: 92%;
    }

    .cs-bubble-user {
        align-self: flex-end;
        background: rgba(251, 146, 60, 0.18);
        border: 1px solid rgba(251, 146, 60, 0.35);
        border-bottom-right-radius: 7px;
    }

    .cs-bubble-bot {
        align-self: flex-start;
        background: rgba(51, 65, 85, 0.45);
        border: 1px solid rgba(148, 163, 184, 0.24);
        border-bottom-left-radius: 7px;
    }

    .cs-chatbot-form {
        border-top: 1px solid rgba(148, 163, 184, 0.2);
        padding: 0.7rem;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 0.55rem;
    }

    .cs-chatbot-close {
        border: 1px solid rgba(148, 163, 184, 0.35);
        color: #dbe5f2;
        background: rgba(51, 65, 85, 0.35);
        border-radius: 999px;
        padding: 0.3rem 0.7rem;
        font-size: 0.75rem;
        line-height: 1.2;
    }

    .cs-chatbot-close:hover {
        border-color: rgba(251, 146, 60, 0.6);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
    }
</style>

<script>
    (function () {
        const toggle = document.getElementById('chatbotToggle');
        const panel = document.getElementById('chatbotPanel');
        const closeBtn = document.getElementById('chatbotClose');
        const form = document.getElementById('chatbotForm');
        const input = document.getElementById('chatbotInput');
        const messages = document.getElementById('chatbotMessages');
        const csrfToken = form?.querySelector('input[name="_token"]')?.value || '';

        if (!toggle || !panel || !form || !input || !messages) {
            return;
        }

        const addBubble = (text, role) => {
            const bubble = document.createElement('div');
            bubble.className = `cs-bubble ${role === 'user' ? 'cs-bubble-user' : 'cs-bubble-bot'}`;
            bubble.textContent = text;
            messages.appendChild(bubble);
            messages.scrollTop = messages.scrollHeight;
        };

        addBubble('Halo, saya CS Bot. Saya bisa bantu FAQ, cek stok, harga, dan daftar produk.', 'bot');

        const openPanel = () => {
            panel.classList.remove('d-none');
            input.focus();
        };

        const closePanel = () => panel.classList.add('d-none');

        toggle.addEventListener('click', () => {
            if (panel.classList.contains('d-none')) {
                openPanel();
            } else {
                closePanel();
            }
        });

        closeBtn?.addEventListener('click', closePanel);

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            addBubble(message, 'user');
            input.value = '';
            input.disabled = true;

            try {
                const res = await fetch('{{ route('chatbot.ask') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message }),
                });

                const data = await res.json();
                const answer = data?.data?.answer || 'Maaf, respons bot sedang tidak tersedia.';
                addBubble(answer, 'bot');
            } catch (error) {
                addBubble('Maaf, terjadi gangguan koneksi. Coba lagi sebentar.', 'bot');
            } finally {
                input.disabled = false;
                input.focus();
            }
        });
    })();
</script>
