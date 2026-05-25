@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Sales History & Analytics</h2>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Comprehensive performance tracking</p>
        </div>
        <button onclick="exportToPDF()" class="bg-slate-900 hover:bg-slate-800 text-white font-black py-3 px-8 rounded-2xl shadow-xl flex items-center gap-2 transition-all active:scale-95 text-xs uppercase tracking-widest">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h3a2 2 0 0 1 0 4h-3V15z"/><path d="M17 15v4"/></svg>
            Print History
        </button>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Bar Chart: Revenue Trend -->
        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Revenue Trend (14 Days)</h4>
            <div class="h-64">
                <canvas id="historyBarChart" data-labels='@json($dailyLabels)' data-values='@json($dailySales)'></canvas>
            </div>
        </div>

        <!-- Pie Chart: Category Mix -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Product Mix</h4>
            <div class="h-64">
                <canvas id="historyPieChart" data-labels='@json($pieLabels)' data-values='@json($pieData)'></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Order ID</th>
                        <th class="px-6 py-4 font-semibold">Cashier</th>
                        <th class="px-6 py-4 font-semibold">Items</th>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold">Total Amount</th>
                        <th class="px-6 py-4 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $t)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-900">#{{ $t->id }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $t->cashier->name }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-slate-500">
                                {{ $t->details->count() }} items
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-sm">
                            {{ $t->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">
                            ₱{{ number_format($t->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            @php
                                $transactionData = [
                                    'id' => $t->id,
                                    'date' => $t->created_at->format('M d, Y h:i A'),
                                    'cashier' => $t->cashier->name,
                                    'total' => $t->total_amount,
                                    'payment' => $t->payment,
                                    'change' => $t->change,
                                    'items' => $t->details->map(function($d){ 
                                        return [
                                            'name' => $d->product->name, 
                                            'qty' => $d->quantity, 
                                            'subtotal' => $d->subtotal
                                        ]; 
                                    })
                                ];
                            @endphp
                            <button data-transaction='@json($transactionData)'
                                    onclick='viewReceipt(this)' 
                                    class="p-2 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition-all"
                                    title="View Receipt">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                            No transactions found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-orange-600 p-6 rounded-2xl text-white shadow-lg shadow-orange-100">
            <p class="opacity-80 text-sm font-medium">Total Revenue</p>
            <h3 class="text-2xl font-bold mt-1">₱{{ number_format($transactions->sum('total_amount'), 2) }}</h3>
        </div>
        
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-slate-500 text-sm font-medium">Average Order</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1">
                ₱{{ $transactions->count() > 0 ? number_format($transactions->avg('total_amount'), 2) : '0.00' }}
            </h3>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-slate-500 text-sm font-medium">Total Transactions</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $transactions->count() }}</h3>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receiptModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md">
    <div class="bg-white rounded-2xl w-full max-w-sm p-8 text-center space-y-4 shadow-2xl">
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        <div>
            <h3 class="text-xl font-bold text-slate-900">Transaction Receipt</h3>
            <p id="receiptId" class="text-xs text-slate-500 mt-1"></p>
        </div>
        <div id="receiptDetails" class="border-y border-dashed border-slate-200 py-4 font-mono text-left text-xs space-y-1">
            <!-- Receipt items injected here -->
        </div>
        <div class="flex gap-2 pt-2">
            <button onclick="closeReceiptModal()" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold transition-colors">Close</button>
            <button onclick="window.print()" class="flex-1 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-xl font-bold shadow-lg shadow-orange-100 transition-all flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Print
            </button>
        </div>
    </div>
</div>

<script>
    function viewReceipt(button) {
        const data = JSON.parse(button.getAttribute('data-transaction'));
        document.getElementById('receiptModal').classList.remove('hidden');
        document.getElementById('receiptId').innerText = `ID: #${data.id} | ${data.date}`;
        
        let detailsHtml = `
            <div class="flex justify-between font-bold border-b border-slate-100 pb-1 mb-1">
                <span>ITEM</span><span>QTY</span><span>TOTAL</span>
            </div>
        `;
        
        data.items.forEach(item => {
            detailsHtml += `
                <div class="flex justify-between py-0.5">
                    <span class="truncate pr-2">${item.name}</span>
                    <span>x${item.qty}</span>
                    <span>₱${parseFloat(item.subtotal).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                </div>
            `;
        });
        
        detailsHtml += `
            <div class="border-t border-slate-100 mt-2 pt-2 space-y-1">
                <div class="flex justify-between font-bold text-sm text-slate-900">
                    <span>TOTAL</span>
                    <span>₱${parseFloat(data.total).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                </div>
                <div class="flex justify-between text-[10px] text-slate-500">
                    <span>PAYMENT</span>
                    <span>₱${parseFloat(data.payment).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                </div>
                <div class="flex justify-between text-[10px] text-green-600 font-bold">
                    <span>CHANGE</span>
                    <span>₱${parseFloat(data.change).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                </div>
            </div>
            <div class="mt-4 pt-2 border-t border-slate-50 text-[10px] text-center text-slate-400">
                Cashier: ${data.cashier}
            </div>
        `;
        
        document.getElementById('receiptDetails').innerHTML = detailsHtml;
    }

    function closeReceiptModal() {
        document.getElementById('receiptModal').classList.add('hidden');
    }

    function exportToPDF() {
        // Create a new window with a print-friendly version of the table
        const printWindow = window.open('', '_blank');
        const content = `
            <html>
                <head>
                    <title>Sales Report - Takoyaki Mini House</title>
                    <style>
                        body { font-family: sans-serif; padding: 40px; color: #1e293b; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th { background: #f8fafc; text-align: left; padding: 12px; border-bottom: 2px solid #e2e8f0; font-size: 12px; text-transform: uppercase; }
                        td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
                        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 4px solid #ea580c; padding-bottom: 20px; }
                        .total-box { margin-top: 30px; background: #0f172a; color: white; padding: 20px; border-radius: 10px; text-align: right; }
                        .footer { margin-top: 50px; font-size: 10px; color: #94a3b8; text-align: center; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div>
                            <h1 style="margin:0; color:#0f172a;">Sales Summary Report</h1>
                            <p style="margin:5px 0 0; color:#64748b;">Takoyaki Mini House | Official Document</p>
                        </div>
                        <div style="text-align:right">
                            <p style="margin:0; font-weight:bold;">Date Generated</p>
                            <p style="margin:0;">${new Date().toLocaleDateString()}</p>
                        </div>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Cashier</th>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${Array.from(document.querySelectorAll('tbody tr')).map(row => {
                                const cells = row.querySelectorAll('td');
                                if (cells.length < 5) return '';
                                return `
                                    <tr>
                                        <td>${cells[0].innerText}</td>
                                        <td>${cells[1].innerText}</td>
                                        <td>${cells[3].innerText}</td>
                                        <td style="font-weight:bold;">${cells[4].innerText}</td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>

                    <div class="total-box">
                        <p style="margin:0; font-size:12px; opacity:0.7;">TOTAL GROSS REVENUE</p>
                        <h2 style="margin:5px 0 0; font-size:32px;">${document.querySelector('h3.text-2xl.font-bold').innerText}</h2>
                    </div>

                    <div class="footer">
                        &copy; 2026 Takoyaki Mini House POS System. This is a computer-generated report.
                    </div>

                    <script>
                        window.onload = function() { window.print(); window.close(); }
                    <\/script>
                </body>
            </html>
        `;
        printWindow.document.write(content);
        printWindow.document.close();
    }

    // --- Analytics Charts ---
    document.addEventListener('DOMContentLoaded', function() {
        const barCanvas = document.getElementById('historyBarChart');
        const pieCanvas = document.getElementById('historyPieChart');

        if (barCanvas) {
            const labels = JSON.parse(barCanvas.getAttribute('data-labels'));
            const values = JSON.parse(barCanvas.getAttribute('data-values'));
            new Chart(barCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Daily Revenue',
                        data: values,
                        backgroundColor: '#ea580c',
                        borderRadius: 8,
                        barThickness: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, border: { display: false }, grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { weight: 'bold', size: 10 } } },
                        x: { border: { display: false }, grid: { display: false }, ticks: { color: '#94a3b8', font: { weight: 'bold', size: 10 } } }
                    }
                }
            });
        }

        if (pieCanvas) {
            const labels = JSON.parse(pieCanvas.getAttribute('data-labels'));
            const values = JSON.parse(pieCanvas.getAttribute('data-values'));
            new Chart(pieCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: ['#ea580c', '#0f172a', '#94a3b8', '#38bdf8', '#fbbf24'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: { size: 11, weight: 'bold', family: 'Plus Jakarta Sans' },
                                color: '#64748b'
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
