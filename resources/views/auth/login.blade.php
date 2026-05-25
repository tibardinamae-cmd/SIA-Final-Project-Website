<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Takoyaki Mini House | Professional POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="h-full antialiased selection:bg-orange-600/30">
    <div class="flex h-full">
        <!-- Brand Side -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-orange-600 items-center justify-center p-20 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-500 to-orange-800 opacity-90"></div>
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 2px, transparent 2px); background-size: 32px 32px;"></div>
            
            <div class="relative z-10 text-center">
                <div class="w-24 h-24 bg-white rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-orange-950/40">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"></path><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"></path><path d="M2 7h20"></path></svg>
                </div>
                <h2 class="text-6xl font-black text-white tracking-tight mb-6">Takoyaki<br>Mini House</h2>
                <p class="text-orange-100 text-xl font-medium opacity-80 max-w-sm mx-auto">
                    The next generation POS & Inventory management for small food business.
                </p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="max-w-md w-full">
                <div class="mb-12">
                    <h3 class="text-4xl font-extrabold text-slate-900 tracking-tight">Welcome!</h3>
                    <p class="text-slate-500 mt-4 text-lg font-medium">Please enter your credentials to access the Terminal.</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-8">
                    @csrf
                    @if($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-r-2xl text-red-700 text-sm font-bold flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-slate-700 uppercase tracking-widest">Username</label>
                        <input type="text" name="username" required placeholder="cashier1" 
                            class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-orange-500 focus:bg-white transition-all text-lg font-bold">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-slate-700 uppercase tracking-widest">Password</label>
                        <input type="password" name="password" required placeholder="cashier123" 
                            class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-orange-500 focus:bg-white transition-all text-lg font-bold">
                    </div>

                    <button type="submit" class="w-full bg-slate-900 hover:bg-orange-600 text-white font-black py-5 rounded-2xl shadow-xl hover:shadow-orange-200 transition-all text-xl transform active:scale-95">
                        Log In to System
                    </button>
                </form>

                <div class="mt-12 text-center text-slate-400 text-sm font-medium">
                    &copy; 2026 Takoyaki Mini House System. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
