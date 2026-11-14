/**
 * PolyMee - Main Application JavaScript Bundle
 * Handles Ajax shift registration, Interval Collision modal, Dynamic AI chat, and UI helpers
 */

// 1. Dropdown Toggle Helper
function toggleDropdown(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
window.addEventListener('click', function(e) {
    const demoBtn = document.getElementById('userSwitcherDropdown');
    const demoMenu = document.getElementById('demoUserMenu');
    if (demoBtn && demoMenu && !demoBtn.contains(e.target)) {
        demoMenu.classList.add('hidden');
    }
});

// 2. AI Assistant Floating Widget Controls
function toggleAiWidget() {
    const dialog = document.getElementById('aiChatDialog');
    if (!dialog) return;
    
    dialog.classList.toggle('hidden');
    if (!dialog.classList.contains('hidden')) {
        const input = document.getElementById('aiUserInput');
        if (input) input.focus();
        scrollChatToBottom();
    }
}

function scrollChatToBottom() {
    const container = document.getElementById('aiChatMessages');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
}

function sendQuickPrompt(promptText) {
    const dialog = document.getElementById('aiChatDialog');
    if (dialog && dialog.classList.contains('hidden')) {
        toggleAiWidget();
    }
    const input = document.getElementById('aiUserInput');
    if (input) {
        input.value = promptText;
        const form = document.getElementById('aiChatForm');
        if (form) form.dispatchEvent(new Event('submit', { cancelable: true }));
    }
}

// 3. AI Chat Submission Handler
async function handleAiChatSubmit(e) {
    e.preventDefault();
    const input = document.getElementById('aiUserInput');
    const btnSend = document.getElementById('btnSendAi');
    const typingIndicator = document.getElementById('aiTypingIndicator');
    const msg = input ? input.value.trim() : '';

    if (!msg) return;

    // Append User Message Bubble
    appendChatMessage('user', msg);
    input.value = '';
    if (btnSend) btnSend.disabled = true;

    // Show Typing Indicator
    if (typingIndicator) typingIndicator.classList.remove('hidden');
    scrollChatToBottom();

    try {
        const response = await fetch('/aiAssistant/ask', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ message: msg })
        });

        const data = await response.json();

        if (typingIndicator) typingIndicator.classList.add('hidden');
        if (btnSend) btnSend.disabled = false;

        if (data.success && data.reply) {
            appendChatMessage('bot', data.reply);
        } else {
            appendChatMessage('bot', data.message || 'Xin lỗi, tôi gặp sự cố khi xử lý câu hỏi của bạn. Vui lòng thử lại!');
        }
    } catch (err) {
        if (typingIndicator) typingIndicator.classList.add('hidden');
        if (btnSend) btnSend.disabled = false;
        appendChatMessage('bot', '⚠️ Không thể kết nối đến máy chủ AI. Vui lòng kiểm tra lại đường truyền mạng.');
        console.error('Lỗi AI Chat:', err);
    }
}

// Append Chat Bubble to DOM
function appendChatMessage(sender, text) {
    const container = document.getElementById('aiChatMessages');
    if (!container) return;

    const div = document.createElement('div');
    div.className = 'animate-fade-in ' + (sender === 'user' ? 'flex justify-end' : 'flex items-start gap-2.5');

    if (sender === 'user') {
        div.innerHTML = `
            <div class="bg-[#1D1D1F] text-white px-3.5 py-2.5 rounded-2xl rounded-tr-sm max-w-[85%] shadow-xs break-words">
                ${escapeHtml(text)}
            </div>
        `;
    } else {
        const parsedHtml = formatMarkdown(text);
        div.innerHTML = `
            <div class="w-7 h-7 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0 font-bold text-xs mt-0.5">
                <i class="fa-solid fa-robot"></i>
            </div>
            <div class="bg-white p-3.5 rounded-2xl rounded-tl-sm border border-slate-200/80 shadow-xs max-w-[85%] text-slate-800 ai-bubble-content break-words leading-relaxed">
                ${parsedHtml}
            </div>
        `;
    }

    container.appendChild(div);
    scrollChatToBottom();
}

// Markdown Formatter (Bold, Links, Bullet Points, Line Breaks)
function formatMarkdown(text) {
    if (!text) return '';
    let html = escapeHtml(text);

    // Bold: **text**
    html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

    // Italic: *text*
    html = html.replace(/\*(.*?)\*/g, '<em>$1</em>');

    // Markdown Links: [title](url)
    html = html.replace(/\[(.*?)\]\((https?:\/\/[^\s]+)\)/g, '<a href="$2" target="_blank" rel="noopener noreferrer">$1 <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></a>');

    // Raw URLs that weren't captured by markdown
    html = html.replace(/(^|[^">])(https?:\/\/[^\s<]+)/g, '$1<a href="$2" target="_blank" rel="noopener noreferrer">$2</a>');

    // Line breaks to <br> or paragraphs
    html = html.replace(/\n\n/g, '<br><br>').replace(/\n/g, '<br>');

    return html;
}

function escapeHtml(str) {
    const div = document.createElement('div');
    div.innerText = str;
    return div.innerHTML;
}

// 4. Ajax Shift Registration with Interval Collision Detection
async function registerShift(shiftId, shiftName) {
    const btn = document.getElementById(`btnRegister-${shiftId}`);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-1"></i>Đang kiểm tra...`;
    }

    try {
        const formData = new FormData();
        formData.append('shift_id', shiftId);

        const response = await fetch('/shift/register', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        const data = await response.json();

        if (response.status === 409 || data.is_conflict) {
            // Hiển thị Modal Cảnh Báo Xung Đột Lịch Trực (Interval Collision)
            showConflictModal(data.message || 'Phát hiện xung đột lịch trực đã đăng ký!');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-user-plus mr-1"></i>Đăng Ký Ca`;
            }
            return;
        }

        if (data.success) {
            // Thành công: Cập nhật giao diện thẻ ca trực ngay lập tức
            if (btn) {
                btn.className = 'px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold cursor-default shadow-sm opacity-90 flex items-center gap-1.5';
                btn.innerHTML = `<i class="fa-solid fa-check"></i><span>Đã Tham Gia</span>`;
            }

            // Tăng số lượng đã duyệt
            const countEl = document.getElementById(`shiftCount-${shiftId}`);
            if (countEl) {
                const current = parseInt(countEl.textContent || '0', 10);
                countEl.textContent = current + 1;
            }

            // Cập nhật viền thẻ
            const card = document.getElementById(`shiftCard-${shiftId}`);
            if (card) {
                card.classList.remove('border-slate-200', 'bg-white');
                card.classList.add('border-emerald-300', 'bg-emerald-50/30');
            }

            showToast('success', data.message || 'Đăng ký ca trực thành công!');
        } else {
            showToast('error', data.message || 'Đăng ký không thành công.');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-user-plus mr-1"></i>Đăng Ký Ca`;
            }
        }
    } catch (err) {
        console.error('Lỗi khi đăng ký ca trực:', err);
        showToast('error', 'Có lỗi kết nối xảy ra. Vui lòng thử lại!');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = `<i class="fa-solid fa-user-plus mr-1"></i>Đăng Ký Ca`;
        }
    }
}

// Conflict Modal Helpers
function showConflictModal(message) {
    const modal = document.getElementById('conflictModal');
    const msgEl = document.getElementById('conflictMessage');
    if (msgEl) msgEl.textContent = message;
    if (modal) modal.classList.remove('hidden');
}

function closeConflictModal() {
    const modal = document.getElementById('conflictModal');
    if (modal) modal.classList.add('hidden');
}

// 5. Google Drive Modal Handlers
let currentAssignmentIdForDrive = null;

function openDriveSubmitModal(assignmentId, shiftTitle) {
    currentAssignmentIdForDrive = assignmentId;
    const modal = document.getElementById('driveSubmitModal');
    const titleEl = document.getElementById('modalShiftTitle');
    if (titleEl) titleEl.textContent = shiftTitle;
    if (modal) modal.classList.remove('hidden');
}

function closeDriveModal() {
    const modal = document.getElementById('driveSubmitModal');
    if (modal) modal.classList.add('hidden');
    currentAssignmentIdForDrive = null;
}

async function submitDriveLinkAjax() {
    const urlInput = document.getElementById('driveUrlInput');
    const notesInput = document.getElementById('driveNotesInput');
    const driveUrl = urlInput ? urlInput.value.trim() : '';
    const notes = notesInput ? notesInput.value.trim() : '';

    if (!driveUrl || !driveUrl.includes('drive.google.com')) {
        alert('Vui lòng nhập đúng liên kết Google Drive hợp lệ (https://drive.google.com/...)');
        return;
    }

    closeDriveModal();

    // Tự động chuyển qua AI chatbox để xử lý submission
    toggleAiWidget();
    sendQuickPrompt(driveUrl + (notes ? ' - Ghi chú: ' + notes : ''));
}

// 6. Dynamic Toast Notification Helper
function showToast(type, message) {
    const isSuccess = (type === 'success');
    const div = document.createElement('div');
    div.className = `fixed top-20 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-xl text-xs font-semibold animate-in fade-in slide-in-from-top-4 duration-200 ${
        isSuccess ? 'bg-emerald-600 text-white shadow-emerald-900/20' : 'bg-rose-600 text-white shadow-rose-900/20'
    }`;

    div.innerHTML = `
        <i class="fa-solid ${isSuccess ? 'fa-circle-check' : 'fa-circle-exclamation'} text-sm"></i>
        <span>${escapeHtml(message)}</span>
    `;

    document.body.appendChild(div);

    setTimeout(() => {
        div.classList.add('opacity-0', 'transition-opacity', 'duration-300');
        setTimeout(() => div.remove(), 300);
    }, 3500);
}
