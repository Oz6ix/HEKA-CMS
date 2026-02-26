<!-- Alert Modal (Alpine.js based — replaces Bootstrap modal) -->
<div x-data="{ open: false, title: '', content: '' }" 
     x-on:show-popup.window="open = true; title = $event.detail.title || 'Alert'; content = $event.detail.content || ''"
     x-show="open" x-cloak
     class="fixed inset-0 z-[999] flex items-center justify-center"
     style="display: none;">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/50 transition-opacity" @click="open = false"></div>
    
    <!-- Dialog -->
    <div class="relative bg-white rounded-xl shadow-2xl border border-slate-200 w-full max-w-md mx-4 overflow-hidden"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="h-9 w-9 rounded-full flex items-center justify-center" style="background: #fee2e2; color: #dc2626;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 modelTitleClass" x-text="title">Alert</h3>
        </div>
        
        <div class="px-6 py-5" id="popupContent">
            <p class="text-sm text-slate-600" x-text="content"></p>
        </div>
        
        <div class="px-6 py-3 border-t border-slate-100 flex justify-end" style="background: #f8fafc;">
            <button type="button" @click="open = false" 
                class="px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors" style="background: #2563eb;"
                onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                OK
            </button>
        </div>
    </div>
</div>

<script>
// Legacy compatibility: support old jQuery modal calls
$(document).ready(function() {
    // Override Bootstrap modal show
    $.fn.modal = function(action) {
        if (action === 'show') {
            var title = $('.modelTitleClass').text() || 'Alert';
            var content = $('#popupContent').text() || '';
            window.dispatchEvent(new CustomEvent('show-popup', { 
                detail: { title: title, content: content } 
            }));
        }
        return this;
    };
});
</script>