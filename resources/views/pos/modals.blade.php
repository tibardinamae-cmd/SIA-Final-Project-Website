<!-- Checkout Modal -->
<div id="checkoutModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[3rem] w-full max-w-md shadow-2xl overflow-hidden border border-slate-100">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <h3 class="text-2xl font-black text-slate-900">Settlement</h3>
            <button onclick="toggleCheckoutModal()" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="p-8 space-y-8">
            <div class="text-center p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Total Amount Due</p>
                <h4 id="modalTotal" class="text-5xl font-black text-slate-900 tracking-tighter">₱0.00</h4>
            </div>

            <div class="space-y-3">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">Received Cash (₱)</label>
                <input type="number" id="paymentAmount" onkeyup="calculateChange()" autofocus 
                    class="w-full px-6 py-5 bg-slate-50 border-2 border-slate-100 rounded-2xl text-3xl font-black outline-none focus:border-orange-500 focus:bg-white transition-all">
            </div>

            <div id="changeDisplay" class="hidden p-6 bg-green-50 rounded-2xl border border-green-100 flex justify-between items-center">
                <span class="text-green-700 font-bold uppercase tracking-widest text-xs">Return Change</span>
                <span id="changeVal" class="text-green-700 font-black text-2xl">₱0.00</span>
            </div>
        </div>
        <div class="p-8 bg-slate-50 flex gap-4">
            <button onclick="toggleCheckoutModal()" class="flex-1 py-4 font-bold text-slate-500">Cancel</button>
            <button onclick="submitTransaction()" id="confirmBtn" disabled 
                class="flex-[2] py-4 bg-slate-900 text-white rounded-2xl font-black shadow-xl disabled:opacity-30 transition-all active:scale-95">
                Confirm & Print
            </button>
        </div>
    </div>
</div>

<!-- Professional Receipt Modal -->
<div id="receiptModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-xl">
    <div class="bg-white rounded-[2.5rem] w-full max-w-sm p-10 text-center space-y-6 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-orange-600"></div>
        
        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        
        <div>
            <h3 class="text-2xl font-black text-slate-900">Success!</h3>
            <p id="receiptId" class="text-xs font-bold text-slate-400 mt-2 uppercase tracking-widest"></p>
        </div>

        <div id="receiptDetails" class="border-y border-dashed border-slate-200 py-6 font-mono text-left text-[11px] leading-relaxed space-y-1 text-slate-700">
            <!-- Receipt items injected here -->
        </div>

        <div class="flex flex-col gap-3">
            <button onclick="window.print()" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black shadow-lg flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Print Receipt
            </button>
            <button onclick="location.reload()" class="w-full py-4 bg-orange-600 text-white rounded-2xl font-black shadow-lg shadow-orange-100 transition-all active:scale-95">
                New Transaction
            </button>
        </div>
    </div>
</div>
