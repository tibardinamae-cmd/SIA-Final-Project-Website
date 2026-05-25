@extends('layouts.app')

@section('header_title', 'Business Intelligence')

@section('content')
<div class="space-y-10 pb-10">
    <!-- Executive Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform"></div>
            <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="m17 5-5-3-5 3"/><path d="m17 19-5 3-5-3"/><path d="M2 12h20"/><path d="m7 7-5 5 5 5"/><path d="m17 7 5 5-5 5"/></svg>
            </div>
            <p class="text-slate-400 text-xs font-black uppercase tracking-[0.2em] mb-1">Net Revenue</p>
            <h3 class="text-3xl font-black text-slate-900 tracking-tighter">₱{{ number_format($stats['total_sales'], 2) }}</h3>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 rounded-full opacity-50 group-hover:scale-150 transition-transform"></div>
            <div class="w-14 h-14 bg-purple-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-purple-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-3 3-3-3"/><path d="M12 3v18"/><path d="m15 6-3-3-3 3"/></svg>
            </div>
            <p class="text-slate-400 text-xs font-black uppercase tracking-[0.2em] mb-1">Order Volume</p>
            <h3 class="text-3xl font-black text-slate-900 tracking-tighter">{{ number_format($stats['total_orders']) }}</h3>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full opacity-50 group-hover:scale-150 transition-transform"></div>
            <div class="w-14 h-14 bg-orange-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-orange-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            </div>
            <p class="text-slate-400 text-xs font-black uppercase tracking-[0.2em] mb-1">Live Inventory</p>
            <h3 class="text-3xl font-black text-slate-900 tracking-tighter">{{ $stats['active_products'] }} <span class="text-sm font-bold text-slate-400 tracking-normal">SKUs</span></h3>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full opacity-50 group-hover:scale-150 transition-transform"></div>
            <div class="w-14 h-14 {{ $stats['low_stock'] > 0 ? 'bg-red-600' : 'bg-green-600' }} text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <p class="text-slate-400 text-xs font-black uppercase tracking-[0.2em] mb-1">Restock Alerts</p>
            <h3 class="text-3xl font-black {{ $stats['low_stock'] > 0 ? 'text-red-600' : 'text-slate-900' }} tracking-tighter">{{ $stats['low_stock'] }} <span class="text-sm font-bold text-slate-400 tracking-normal">Flags</span></h3>
        </div>
    </div>

    <!-- Analytics Chart -->
    <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h4 class="text-2xl font-black text-slate-900 tracking-tight">Revenue Performance</h4>
                <p class="text-slate-400 font-bold text-sm">Aggregated daily sales over the last 7 sessions.</p>
            </div>
            <div class="flex gap-2">
                <button onclick="exportDashboardPDF()" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl transition-all active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h3a2 2 0 0 1 0 4h-3V15z"/><path d="M17 15v4"/></svg>
                    Export PDF
                </button>
            </div>
        </div>
        <div class="h-[400px] w-full">
            <canvas id="salesChart" 
                    data-labels='@json($labels)' 
                    data-sales='@json($salesData)'></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('salesChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const labels = JSON.parse(canvas.getAttribute('data-labels'));
        const salesData = JSON.parse(canvas.getAttribute('data-sales'));

        // Create gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(234, 88, 12, 0.3)');
        gradient.addColorStop(1, 'rgba(234, 88, 12, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Daily Sales',
                    data: salesData,
                    borderColor: '#ea580c',
                    borderWidth: 4,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#ea580c',
                    pointBorderWidth: 3,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#ea580c',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 16,
                        titleFont: { size: 14, weight: '800', family: 'Plus Jakarta Sans' },
                        bodyFont: { size: 14, weight: '600', family: 'Plus Jakarta Sans' },
                        displayColors: false,
                        cornerRadius: 12,
                        callbacks: {
                            label: function(context) {
                                return ' ₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        border: { display: false },
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 12, weight: '700' },
                            callback: function(value) { return '₱' + value.toLocaleString(); }
                        }
                    },
                    x: {
                        border: { display: false },
                        grid: { display: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 12, weight: '700' }
                        }
                    }
                }
            }
        });
    });

    function exportDashboardPDF() {
        const printWindow = window.open('', '_blank');
        
        // Capture live stats from the dashboard cards
        const statsElements = document.querySelectorAll('h3.text-3xl.font-black');
        const stats = Array.from(statsElements).map(h => h.innerText);
        const labels = ["Total Gross Revenue", "Total Order Volume", "Active Menu SKUs", "Inventory Flags"];
        
        const content = `
            <html>
                <head>
                    <title>Executive Summary - Takoyaki Mini House</title>
                    <style>
                        body { font-family: 'Inter', sans-serif; padding: 40px; color: #0f172a; line-height: 1.5; background: #fff; }
                        .header { border-bottom: 3px solid #ea580c; padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
                        .brand h1 { margin: 0; font-size: 24px; font-weight: 800; text-transform: uppercase; }
                        .brand p { margin: 0; color: #ea580c; font-weight: 700; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; }
                        .meta { text-align: right; font-size: 10px; color: #64748b; }
                        
                        .kpi-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 30px; }
                        .kpi-card { background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; }
                        .kpi-card p { margin: 0; font-size: 9px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
                        .kpi-card h2 { margin: 4px 0 0; font-size: 22px; font-weight: 800; color: #0f172a; }
                        
                        .section-head { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #94a3b8; margin: 30px 0 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 5px; }
                        
                        table { width: 100%; border-collapse: collapse; }
                        th { text-align: left; font-size: 10px; color: #64748b; padding: 8px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
                        td { padding: 10px 8px; border-bottom: 1px solid #f1f5f9; font-size: 12px; }
                        
                        .footer { margin-top: 50px; padding-top: 15px; border-top: 1px solid #f1f5f9; text-align: center; font-size: 9px; color: #cbd5e1; }
                        @media print { .no-print { display: none; } }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="brand">
                            <h1>Takoyaki Mini House</h1>
                            <p>Executive Dashboard Summary</p>
                        </div>
                        <div class="meta">
                            <strong>CONFIDENTIAL REPORT</strong><br>
                            Generated on: ${new Date().toLocaleString()}
                        </div>
                    </div>

                    <div class="section-head">Key Performance Indicators</div>
                    <div class="kpi-grid">
                        <div class="kpi-card"><p>${labels[0]}</p><h2>${stats[0]}</h2></div>
                        <div class="kpi-card"><p>${labels[1]}</p><h2>${stats[1]}</h2></div>
                        <div class="kpi-card"><p>${labels[2]}</p><h2>${stats[2]}</h2></div>
                        <div class="kpi-card"><p>${labels[3]}</p><h2>${stats[3]}</h2></div>
                    </div>

                    <div class="section-head">Recent Transactions Log</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Timestamp / Details</th>
                                <th>Total Payable</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${Array.from(document.querySelectorAll('.flex.items-center.gap-4.p-3')).map(el => {
                                const id = el.querySelector('.text-sm.font-bold').innerText;
                                const details = el.querySelector('.text-slate-400').innerText;
                                const total = el.querySelector('.text-sm.font-black').innerText;
                                return `<tr><td>${id}</td><td>${details}</td><td><strong>${total}</strong></td></tr>`;
                            }).join('') || '<tr><td colspan="3" style="text-align:center; padding:20px;">No recent transactions recorded</td></tr>'}
                        </tbody>
                    </table>

                    <div class="footer">
                        Automated Business Report - Generated by Takoyaki Mini House POS Terminal<br>
                        Authorized Access Only
                    </div>

                    <script>
                        window.onload = function() { 
                            window.print(); 
                            setTimeout(() => window.close(), 1000); 
                        }
                    <\/script>
                </body>
            </html>
        `;
        
        printWindow.document.write(content);
        printWindow.document.close();
    }
</script>
@endpush
@endsection
