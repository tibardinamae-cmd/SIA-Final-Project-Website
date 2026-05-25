@extends('layouts.app')

@section('header_title', 'Category Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Product Categories</h2>
            <p class="text-slate-500 font-medium">Organize your menu items efficiently.</p>
        </div>
        <button onclick="toggleCategoryModal()" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-orange-100 transition-all flex items-center gap-2 active:scale-95 text-xs uppercase tracking-widest">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Category
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 px-6 py-4 rounded-[2rem] flex items-center gap-3 animate-in fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span class="font-bold text-sm uppercase tracking-tight">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-100 text-red-700 px-6 py-4 rounded-[2rem] flex items-center gap-3 animate-in fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span class="font-bold text-sm uppercase tracking-tight">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $category)
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group hover:shadow-xl transition-all relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-slate-50 rounded-full opacity-50 group-hover:scale-150 transition-transform"></div>
            
            <div class="relative z-10">
                <div class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/></svg>
                </div>
                
                <h3 class="text-xl font-black text-slate-900 tracking-tight mb-1">{{ $category->name }}</h3>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">{{ $category->products_count }} Associated Products</p>
                
                <div class="mt-8 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button data-category='@json($category)' onclick='openEditCategoryModal(this)' class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-orange-600 hover:text-white transition-all">Edit</button>
                    <form action="{{ url('categories/' . $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                        @csrf @method('DELETE')
                        <button class="px-4 py-2 bg-slate-100 text-red-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal: New Category -->
<div id="categoryModal" class="hidden fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[3rem] w-full max-w-sm shadow-2xl overflow-hidden border border-slate-100">
        <div class="p-8 border-b flex justify-between items-center bg-slate-50/50">
            <h3 class="text-xl font-black text-slate-900 uppercase tracking-widest">New Category</h3>
            <button onclick="toggleCategoryModal()" class="text-slate-400 hover:text-slate-900 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
        </div>
        <form action="{{ route('categories.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Category Name</label>
                <input type="text" name="name" required placeholder="e.g. Special Takoyaki" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl text-lg font-black outline-none focus:border-orange-500 focus:bg-white transition-all">
            </div>
            <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl active:scale-95 transition-all">Create Category</button>
        </form>
    </div>
</div>

<!-- Modal: Edit Category -->
<div id="editCategoryModal" class="hidden fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[3rem] w-full max-w-sm shadow-2xl overflow-hidden border border-slate-100">
        <div class="p-8 border-b flex justify-between items-center bg-slate-50/50">
            <h3 class="text-xl font-black text-slate-900 uppercase tracking-widest">Edit Category</h3>
            <button onclick="toggleEditCategoryModal()" class="text-slate-400 hover:text-slate-900 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
        </div>
        <form id="editCategoryForm" action="" method="POST" class="p-8 space-y-6">
            @csrf @method('PUT')
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Category Name</label>
                <input type="text" name="name" id="editCategoryName" required class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl text-lg font-black outline-none focus:border-orange-500 focus:bg-white transition-all">
            </div>
            <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl active:scale-95 transition-all">Update Category</button>
        </form>
    </div>
</div>

<script>
    function toggleCategoryModal() {
        document.getElementById('categoryModal').classList.toggle('hidden');
    }
    function toggleEditCategoryModal() {
        document.getElementById('editCategoryModal').classList.toggle('hidden');
    }
    function openEditCategoryModal(button) {
        const category = JSON.parse(button.getAttribute('data-category'));
        document.getElementById('editCategoryForm').action = `/categories/${category.id}`;
        document.getElementById('editCategoryName').value = category.name;
        toggleEditCategoryModal();
    }
</script>
@endsection
