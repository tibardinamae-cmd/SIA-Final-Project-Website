@extends('layouts.app')

@section('header_title', 'Transaction Terminal')

@section('content')
<style>
    .pos-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .pos-card:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); }
    .btn-active { background-color: #ea580c !important; color: white !important; box-shadow: 0 10px 15px -3px rgba(234, 88, 12, 0.3); }
    .hide-scroll::-webkit-scrollbar { display: none; }
    .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-140px)] -mt-6">
    <!-- 1. Catalog Section -->
    <div class="flex-1 flex flex-col min-h-0 bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
        <!-- Header Actions -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 space-y-4">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="relative w-full md:w-80">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </div>
                    <input type="text" id="productSearch" onkeyup="filterProducts()" placeholder="Search menu..." 
                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-bold text-sm text-slate-700 shadow-sm">
                </div>
                
                <div class="flex gap-2 overflow-x-auto hide-scroll">
                    <button onclick="filterCategory('All', this)" class="cat-btn btn-active px-5 py-2.5 bg-white text-slate-600 border border-slate-200 rounded-xl text-xs font-black uppercase tracking-tight transition-all">All</button>
                    @foreach($categories as $cat)
                        <button onclick="filterCategory('{{ $cat->id }}', this)" class="cat-btn px-5 py-2.5 bg-white text-slate-600 border border-slate-200 rounded-xl text-xs font-black uppercase tracking-tight transition-all">{{ $cat->name }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Product Grid (Compact High-Visibility) -->
        <div id="productGrid" class="flex-1 overflow-y-auto p-4 grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 hide-scroll">
            @foreach($products as $product)
            <button data-name="{{ strtolower($product->name) }}" data-category="{{ $product->category_id }}" data-product='@json($product)' onclick='addToCart(this)' 
                    class="pos-card group bg-white p-3 rounded-2xl border border-slate-100 text-left relative overflow-hidden active:scale-95 shadow-sm">
                <div class="aspect-square rounded-xl overflow-hidden mb-2 bg-slate-50 relative">
                    <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset($product->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @if($product->quantity <= 0)
                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[1px] flex items-center justify-center">
                            <span class="text-white font-black text-[8px] uppercase tracking-widest -rotate-12">Out of Stock</span>
                        </div>
                    @endif
                </div>
                <!-- Price Highlight (Now at top for better visibility) -->
                <div class="flex justify-between items-center mb-1">
                    <span class="text-orange-600 font-black text-sm">₱{{ number_format($product->price) }}</span>
                    <span class="text-[7px] font-black uppercase text-slate-400">S:{{ $product->quantity }}</span>
                </div>
                <h3 class="font-bold text-slate-800 text-[10px] leading-tight line-clamp-1 uppercase tracking-tight">{{ $product->name }}</h3>
            </button>
            @endforeach
        </div>
    </div>

    <!-- 2. Cart Section (Compact) -->
    <div class="w-full lg:w-[22rem] flex flex-col bg-slate-900 rounded-[2rem] shadow-2xl border border-slate-800 overflow-hidden">
        <div class="p-5 border-b border-slate-800 bg-slate-800/30 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-orange-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/></svg>
                </div>
                <h2 class="text-white font-black text-[11px] uppercase tracking-widest">Cart</h2>
            </div>
            <button onclick="clearCart()" class="text-slate-500 hover:text-red-400 transition-colors text-[9px] font-black uppercase tracking-widest">Clear</button>
        </div>

        <div id="cartItems" class="flex-1 overflow-y-auto p-4 space-y-2 hide-scroll">
            <div class="h-full flex flex-col items-center justify-center text-slate-700 space-y-3 opacity-30 scale-75">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                <p class="text-[9px] font-black uppercase tracking-[0.2em]">Select Items</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-6 bg-slate-800/50 border-t border-slate-700/50 space-y-4">
            <div class="space-y-1.5">
                <div class="flex justify-between text-slate-500 text-[9px] font-black uppercase tracking-widest">
                    <span>Qty</span>
                    <span id="itemCount">0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-white font-black text-[10px] uppercase tracking-widest">Total</span>
                    <span id="total" class="text-orange-500 text-2xl font-black tracking-tighter leading-none">₱0.00</span>
                </div>
            </div>
            <button onclick="openCheckoutModal()" id="checkoutBtn" disabled 
                class="w-full bg-orange-600 hover:bg-orange-500 disabled:bg-slate-800 disabled:text-slate-600 text-white font-black py-4 rounded-xl shadow-lg transition-all active:scale-95 text-sm uppercase tracking-widest">
                Checkout
            </button>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- FLOATING SETTLEMENT MODAL -->
<!-- ======================================================== -->
<div id="checkoutModal" onclick="if(event.target === this) closeCheckoutModal()" class="hidden fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-[2rem] w-full max-w-[340px] shadow-2xl overflow-hidden transform scale-100 border border-slate-100 animate-in zoom-in-95 duration-200">
        <div class="p-6 border-b flex justify-between items-center bg-slate-50/50">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Settlement</h3>
            </div>
            <button onclick="closeCheckoutModal()" class="text-slate-400 hover:text-slate-900 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
        </div>
        <div class="p-6 space-y-6">
            <div class="text-center p-6 bg-slate-900 rounded-3xl shadow-inner">
                <p class="text-slate-500 text-[8px] font-black uppercase tracking-widest mb-1">Payable Amount</p>
                <h4 id="modalTotal" class="text-3xl font-black text-white tracking-tighter">₱0.00</h4>
            </div>
            <div class="space-y-2">
                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Cash Received</label>
                <input type="number" id="paymentAmount" onkeyup="calculateChange()" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl text-4xl font-black outline-none focus:border-orange-500 focus:bg-white transition-all text-center">
            </div>
            <div id="changeDisplay" class="hidden p-5 bg-orange-600 rounded-2xl shadow-xl shadow-orange-900/20 flex justify-between items-center transform animate-in slide-in-from-top-4">
                <span class="text-white font-black text-[10px] uppercase tracking-widest">Return Change</span>
                <span id="changeVal" class="text-white font-black text-2xl tracking-tighter">₱0.00</span>
            </div>
        </div>
        <div class="p-6 bg-slate-50 flex gap-4">
            <button onclick="closeCheckoutModal()" class="flex-1 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Hide</button>
            <button onclick="submitTransaction()" id="confirmBtn" disabled class="flex-[2] py-4 bg-slate-900 text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-xl disabled:opacity-20 active:scale-95 transition-all">Confirm Sale</button>
        </div>
    </div>
</div>

<!-- PROFESSIONAL RECEIPT MODAL -->
<div id="receiptModal" class="hidden fixed inset-0 z-[1100] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-md">
    <div class="bg-white rounded-[2.5rem] w-full max-w-sm p-10 text-center shadow-2xl relative border-t-8 border-orange-600 animate-in zoom-in duration-300">
        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
        <h3 class="text-2xl font-black text-slate-900">Success!</h3>
        <p id="receiptId" class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-2"></p>
        <div id="receiptDetails" class="border-y border-dashed border-slate-200 py-6 font-mono text-left text-[11px] my-6 space-y-1"></div>
        <div class="flex flex-col gap-3">
            <button onclick="window.print()" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black shadow-lg">Print Copy</button>
            <button onclick="location.reload()" class="w-full py-4 bg-orange-600 text-white rounded-2xl font-black shadow-lg shadow-orange-100">New Order</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let cart = [];
    let currentTotal = 0;

    function addToCart(button) {
        const product = JSON.parse(button.getAttribute('data-product'));
        if(product.quantity <= 0) return alert('Out of stock!');
        let item = cart.find(i => i.id === product.id);
        if(item) {
            if(item.quantity < product.quantity) item.quantity++;
            else return alert('Insufficient stock!');
        } else {
            cart.push({ id: product.id, name: product.name, price: parseFloat(product.price), image: product.image, quantity: 1, stock: product.quantity });
        }
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cartItems');
        const checkoutBtn = document.getElementById('checkoutBtn');
        if(cart.length === 0) {
            container.innerHTML = `<div class="h-full flex flex-col items-center justify-center text-slate-700 space-y-4 opacity-40"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg><p class="text-[10px] font-black uppercase tracking-[0.3em]">Ready for Order</p></div>`;
            currentTotal = 0; checkoutBtn.disabled = true;
        } else {
            currentTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            container.innerHTML = cart.map(item => `
                <div class="flex items-center gap-3 bg-slate-800/40 p-3 rounded-2xl border border-slate-700/20">
                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-900/50 flex-shrink-0">
                        <img src="${item.image.startsWith('http') ? item.image : '/'+item.image}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-black text-white text-[9px] truncate mb-0.5 uppercase tracking-tighter">${item.name}</h4>
                        <p class="text-orange-500 font-black text-[10px]">₱${(item.price * item.quantity).toLocaleString()}</p>
                    </div>
                    <div class="flex items-center gap-2 bg-slate-900 p-1 rounded-lg">
                        <button onclick="updateQty(${item.id}, -1)" class="w-6 h-6 flex items-center justify-center hover:bg-slate-700 text-white rounded-md text-xs font-black transition-colors">-</button>
                        <span class="text-[9px] font-black w-3 text-center text-white">${item.quantity}</span>
                        <button onclick="updateQty(${item.id}, 1)" class="w-6 h-6 flex items-center justify-center hover:bg-slate-700 text-white rounded-md text-xs font-black transition-colors">+</button>
                    </div>
                </div>
            `).join('');
            checkoutBtn.disabled = false;
        }
        document.getElementById('total').innerText = `₱${currentTotal.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        document.getElementById('itemCount').innerText = `${cart.length} items`;
        document.getElementById('modalTotal').innerText = `₱${currentTotal.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
    }

    function updateQty(id, delta) {
        let item = cart.find(i => i.id === id);
        if(item) {
            item.quantity += delta;
            if(item.quantity <= 0) cart = cart.filter(i => i.id !== id);
            else if(item.quantity > item.stock) item.quantity = item.stock;
        }
        renderCart();
    }

    function openCheckoutModal() { document.getElementById('checkoutModal').classList.remove('hidden'); setTimeout(()=>document.getElementById('paymentAmount').focus(), 100); }
    function closeCheckoutModal() { document.getElementById('checkoutModal').classList.add('hidden'); }

    function calculateChange() {
        const pay = parseFloat(document.getElementById('paymentAmount').value) || 0;
        const confirmBtn = document.getElementById('confirmBtn');
        const display = document.getElementById('changeDisplay');
        if(pay >= currentTotal && currentTotal > 0) {
            document.getElementById('changeVal').innerText = `₱${(pay - currentTotal).toLocaleString(undefined, {minimumFractionDigits: 2})}`;
            display.classList.remove('hidden'); confirmBtn.disabled = false;
        } else { display.classList.add('hidden'); confirmBtn.disabled = true; }
    }

    async function submitTransaction() {
        const pay = parseFloat(document.getElementById('paymentAmount').value);
        const confirmBtn = document.getElementById('confirmBtn');
        confirmBtn.disabled = true; confirmBtn.innerText = 'Processing...';
        try {
            const resp = await fetch("{{ route('pos.store') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ cart, payment: pay })
            });
            const res = await resp.json();
            if(res.success) {
                closeCheckoutModal(); document.getElementById('receiptModal').classList.remove('hidden');
                document.getElementById('receiptId').innerText = `TRANS-ID: #${res.data.id}`;
                let det = `<div class="flex justify-between font-bold border-b pb-2 mb-2 text-slate-900"><span>ITEM</span><span>TOTAL</span></div>`;
                res.data.items.forEach(i => det += `<div class="flex justify-between py-1"><span>${i.name} x${i.qty}</span><span>₱${parseFloat(i.subtotal).toLocaleString()}</span></div>`);
                det += `<div class="border-t mt-4 pt-4 font-black text-slate-900">GRAND TOTAL: ₱${parseFloat(res.data.total).toLocaleString()}</div>`;
                document.getElementById('receiptDetails').innerHTML = det;
            } else { alert(res.message); confirmBtn.disabled = false; confirmBtn.innerText = 'Confirm Sale'; }
        } catch (e) { alert('Sync failed'); confirmBtn.disabled = false; confirmBtn.innerText = 'Confirm Sale'; }
    }

    function filterProducts() {
        const s = document.getElementById('productSearch').value.toLowerCase();
        document.querySelectorAll('.pos-card').forEach(c => c.style.display = c.dataset.name.includes(s) ? 'block' : 'none');
    }

    function filterCategory(cat, btn) {
        document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('btn-active'));
        btn.classList.add('btn-active');
        document.querySelectorAll('.pos-card').forEach(c => c.style.display = (cat === 'All' || c.dataset.category === cat) ? 'block' : 'none');
    }

    function clearCart() { if(confirm('Clear current order?')) { cart = []; renderCart(); } }
</script>
@endpush
@endsection
