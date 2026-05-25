@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900">Inventory Management</h2>
            <p class="text-slate-500 font-medium">Monitor and manage your Takoyaki stock.</p>
        </div>
        <button onclick="toggleModal()" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-orange-100 transition-all flex items-center gap-2 active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Add Product
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-2xl flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-green-500"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-2xl space-y-1">
            <p class="font-black text-xs uppercase tracking-widest mb-2">Error Saving Product:</p>
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase font-black tracking-widest">
                        <th class="px-8 py-5">Product Info</th>
                        <th class="px-8 py-5">Category</th>
                        <th class="px-8 py-5">Price</th>
                        <th class="px-8 py-5">Quantity</th>
                        <th class="px-8 py-5">Expiration</th>
                        <th class="px-8 py-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($products as $p)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-100 shadow-sm flex-shrink-0">
                                    <img src="{{ str_starts_with($p->image, 'http') ? $p->image : asset($p->image) }}" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 truncate">{{ $p->name }}</h4>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">ID: #PROD-{{ $p->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black uppercase">{{ $p->category->name ?? 'Uncategorized' }}</span>
                        </td>
                        <td class="px-8 py-5 font-bold text-slate-900">₱{{ number_format($p->price, 2) }}</td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                <span class="font-black {{ $p->quantity < 10 ? 'text-red-600' : 'text-slate-900' }}">{{ $p->quantity }}</span>
                                @if($p->quantity < 10)
                                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-5 text-slate-500 text-sm font-medium">{{ $p->expiration_date }}</td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end items-center gap-3">
                                <!-- Edit Button -->
                                <button data-product='@json($p)'
                                        onclick="openEditModal(this)" 
                                        class="p-2 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition-all"
                                        title="Edit Product">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                </button>

                                <!-- Delete Form -->
                                <form action="{{ url('inventory/' . $p->id) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete {{ $p->name }}?')"
                                      style="display: inline-block;">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all"
                                            title="Delete Product">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mb-4"><path d="m7.5 4.27 9 5.15"></path><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path><path d="m3.27 6.96 8.73 5.04 8.73-5.04"></path><path d="M12 22.08V12"></path></svg>
                                <p class="font-bold">No products found in inventory.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div id="productModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] w-full max-w-2xl shadow-2xl overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <h3 class="text-2xl font-black text-slate-900">Add New Product</h3>
            <button onclick="toggleModal()" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Side: Image Upload -->
                <div class="space-y-4">
                    <label class="block text-sm font-bold text-slate-700">Product Image</label>
                    <div id="imagePreviewContainer" class="relative aspect-square w-full rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center overflow-hidden group">
                        <img id="imagePreview" src="" class="hidden w-full h-full object-cover">
                        <div id="uploadPlaceholder" class="text-center p-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-slate-300 mb-2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect><circle cx="9" cy="9" r="2"></circle><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path></svg>
                            <p class="text-xs font-bold text-slate-400">Click to upload product image</p>
                        </div>
                        <input type="file" name="image" id="imageInput" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(this)">
                    </div>
                </div>

                <!-- Right Side: Details -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Product Name</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-medium">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Price (₱)</label>
                            <input type="number" name="price" required class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Quantity</label>
                            <input type="number" name="quantity" required class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Category</label>
                        <select name="category_id" required class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-medium">
                            @if($categories->isEmpty())
                                <option value="" disabled selected>Please add a category first!</option>
                            @else
                                <option value="" disabled selected>Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @if($categories->isEmpty())
                            <p class="mt-2 text-[10px] text-red-500 font-bold uppercase tracking-widest">
                                <a href="{{ route('categories.index') }}" class="underline">Click here to add a category</a>
                            </p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Expiration Date</label>
                        <input type="date" name="expiration_date" required class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-medium">
                    </div>
                </div>
            </div>

            <div class="mt-10 flex gap-4">
                <button type="button" onclick="toggleModal()" class="flex-1 py-4 font-bold text-slate-500 hover:bg-slate-50 rounded-2xl transition-colors">Cancel</button>
                <button type="submit" class="flex-[2] py-4 bg-orange-600 hover:bg-orange-700 text-white font-black rounded-2xl shadow-xl shadow-orange-100 transition-all active:scale-95">Save Product to Inventory</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] w-full max-w-2xl shadow-2xl overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <h3 class="text-2xl font-black text-slate-900">Edit Product</h3>
            <button onclick="toggleEditModal()" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="editForm" action="" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <label class="block text-sm font-bold text-slate-700">Product Image</label>
                    <div class="relative aspect-square w-full rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center overflow-hidden group">
                        <img id="editImagePreview" src="" class="w-full h-full object-cover">
                        <input type="file" name="image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewEditImage(this)">
                    </div>
                    <p class="text-[10px] text-center text-slate-400 font-bold">CLICK IMAGE TO CHANGE</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Product Name</label>
                        <input type="text" name="name" id="editName" required class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-medium">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Price (₱)</label>
                            <input type="number" name="price" id="editPrice" required class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Quantity</label>
                            <input type="number" name="quantity" id="editQuantity" required class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Category</label>
                        <select name="category_id" id="editCategory" required class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-medium">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Expiration Date</label>
                        <input type="date" name="expiration_date" id="editExpiration" required class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-medium">
                    </div>
                </div>
            </div>

            <div class="mt-10 flex gap-4">
                <button type="button" onclick="toggleEditModal()" class="flex-1 py-4 font-bold text-slate-500 hover:bg-slate-50 rounded-2xl transition-colors">Cancel</button>
                <button type="submit" class="flex-[2] py-4 bg-orange-600 hover:bg-orange-700 text-white font-black rounded-2xl shadow-xl transition-all active:scale-95">Update Product Info</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal() {
        document.getElementById('productModal').classList.toggle('hidden');
    }

    function toggleEditModal() {
        document.getElementById('editModal').classList.toggle('hidden');
    }

    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewEditImage(input) {
        const preview = document.getElementById('editImagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openEditModal(button) {
        const product = JSON.parse(button.getAttribute('data-product'));
        const form = document.getElementById('editForm');
        form.action = `/inventory/${product.id}`;
        
        document.getElementById('editName').value = product.name;
        document.getElementById('editPrice').value = product.price;
        document.getElementById('editQuantity').value = product.quantity;
        document.getElementById('editCategory').value = product.category_id;
        document.getElementById('editExpiration').value = product.expiration_date;
        
        // Handle image URL correctly
        const imgSrc = product.image.startsWith('http') ? product.image : `/` + product.image;
        document.getElementById('editImagePreview').src = imgSrc;
        
        toggleEditModal();
    }
</script>
@endsection
