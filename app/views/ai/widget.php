<?php
/**
 * PolyMee - AI Assistant Floating Widget
 * Trợ lý ảo thông minh tương tác ngôn ngữ tự nhiên và tự động Function Calling
 */
declare(strict_types=1);
?>

<!-- Floating Trigger Button (Bottom Right) -->
<div id="aiWidgetLauncher" class="fixed bottom-6 right-6 z-50">
    <button type="button" onclick="toggleAiWidget()" class="group relative flex items-center gap-3 px-4 py-3 rounded-full bg-gradient-to-r from-fpoly-primary via-orange-500 to-amber-500 text-white font-bold text-sm shadow-2xl shadow-orange-500/40 hover:scale-105 active:scale-95 transition-all">
        <!-- Ping Glow -->
        <span class="absolute -top-1 -right-1 flex h-4 w-4">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-400 text-[9px] text-slate-900 font-extrabold items-center justify-center">AI</span>
        </span>
        
        <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center">
            <i class="fa-solid fa-wand-magic-sparkles text-sm group-hover:rotate-12 transition-transform"></i>
        </div>
        <span class="tracking-wide">Trợ Lý PolyMee</span>
    </button>
</div>

<!-- Floating AI Chat Window -->
<div id="aiChatDialog" class="hidden fixed bottom-6 right-6 z-50 w-full sm:w-96 max-w-[calc(100vw-2rem)] h-[580px] bg-white rounded-3xl shadow-2xl border border-slate-200/90 flex flex-col overflow-hidden animate-in slide-in-from-bottom-5 duration-200">
    
    <!-- Chat Header -->
    <div class="bg-[#FAFAFC] p-4 text-[#1D1D1F] flex items-center justify-between border-b border-[#E5E5EA]">
        <div class="flex items-center gap-3">
            <div class="relative">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-fpoly-primary to-orange-400 flex items-center justify-center text-white font-bold shadow-xs">
                    <i class="fa-solid fa-wand-magic-sparkles text-base"></i>
                </div>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-white absolute -bottom-0.5 -right-0.5"></span>
            </div>
            <div>
                <div class="flex items-center gap-1.5">
                    <h3 class="font-extrabold text-sm text-[#1D1D1F]">PolyMee Assistant</h3>
                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-orange-500/30 text-orange-300 border border-orange-500/40">AI 4.0</span>
                </div>
                <p class="text-[11px] text-[#86868B] flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span>Sẵn sàng hỗ trợ điều phối CTV</span>
                </p>
            </div>
        </div>

        <button type="button" onclick="toggleAiWidget()" class="text-[#86868B] hover:text-[#1D1D1F] p-1 rounded-lg hover:bg-black/5 transition">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- Quick Action Pills Bar -->
    <div class="bg-slate-100/90 border-b border-slate-200/80 px-3 py-2 flex items-center gap-1.5 overflow-x-auto text-[11px] no-scrollbar">
        <button type="button" onclick="sendQuickPrompt('Tôi cần làm gì hôm nay?')" class="flex-shrink-0 px-2.5 py-1 rounded-full bg-white hover:bg-orange-50 text-slate-700 hover:text-orange-600 border border-slate-200 font-medium transition shadow-xs flex items-center gap-1">
            <i class="fa-solid fa-clipboard-check text-orange-500"></i>
            <span>Lịch trực hôm nay?</span>
        </button>
        <button type="button" onclick="sendQuickPrompt('Hướng dẫn điểm danh Dynamic QR')" class="flex-shrink-0 px-2.5 py-1 rounded-full bg-white hover:bg-orange-50 text-slate-700 hover:text-orange-600 border border-slate-200 font-medium transition shadow-xs flex items-center gap-1">
            <i class="fa-solid fa-qrcode text-blue-500"></i>
            <span>Điểm danh QR?</span>
        </button>
        <button type="button" onclick="sendQuickPrompt('https://drive.google.com/drive/folders/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs')" class="flex-shrink-0 px-2.5 py-1 rounded-full bg-white hover:bg-orange-50 text-slate-700 hover:text-orange-600 border border-slate-200 font-medium transition shadow-xs flex items-center gap-1">
            <i class="fa-brands fa-google-drive text-emerald-600"></i>
            <span>Demo nộp link Drive</span>
        </button>
    </div>

    <!-- Messages Container -->
    <div id="aiChatMessages" class="flex-grow p-4 overflow-y-auto space-y-3.5 bg-slate-50/50 text-xs">
        
        <!-- Welcome Message from Assistant -->
        <div class="flex items-start gap-2.5">
            <div class="w-7 h-7 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0 font-bold text-xs mt-0.5">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
            </div>
            <div class="bg-white p-3.5 rounded-2xl rounded-tl-sm border border-slate-200/80 shadow-xs max-w-[85%] text-slate-800 space-y-2">
                <p>Xin chào <strong><?= htmlspecialchars($currentUser['full_name']) ?></strong>! Tôi là Trợ lý AI PolyMee.</p>
                <p class="text-slate-600">Tôi có thể giúp bạn:</p>
                <ul class="list-disc list-inside space-y-1 text-slate-600 pl-1">
                    <li>Tra cứu nhiệm vụ và lịch trực trong ngày.</li>
                    <li>Tự động nhận diện và nộp link Google Drive nghiệm thu minh chứng media.</li>
                    <li>Hướng dẫn sử dụng Dynamic QR 30s để điểm danh.</li>
                </ul>
            </div>
        </div>

    </div>

    <!-- Typing Loader Indicator (Hidden by default) -->
    <div id="aiTypingIndicator" class="hidden px-4 py-2 bg-slate-50/50 flex items-center gap-2 text-slate-400 text-xs">
        <div class="w-6 h-6 rounded-lg bg-orange-100 text-orange-500 flex items-center justify-center text-[10px]">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>
        <div class="flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-bounce"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-bounce [animation-delay:0.2s]"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-bounce [animation-delay:0.4s]"></span>
            <span class="text-[11px] text-slate-500 ml-1">PolyMee đang suy nghĩ...</span>
        </div>
    </div>

    <!-- Input Footer -->
    <div class="p-3 bg-white border-t border-slate-200">
        <form id="aiChatForm" onsubmit="handleAiChatSubmit(event)" class="flex items-center gap-2">
            <input 
                type="text" 
                id="aiUserInput" 
                placeholder="Nhập câu hỏi hoặc dán link Drive..." 
                autocomplete="off"
                class="flex-grow px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition"
            >
            <button 
                type="submit" 
                id="btnSendAi"
                class="w-9 h-9 rounded-xl bg-fpoly-primary hover:bg-fpoly-hover text-white flex items-center justify-center flex-shrink-0 transition shadow-sm shadow-orange-500/20"
            >
                <i class="fa-solid fa-paper-plane text-xs"></i>
            </button>
        </form>
    </div>

</div>
