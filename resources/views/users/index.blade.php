@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">User Management</h2>
            <p class="text-slate-500">Manage system administrators and cashiers.</p>
        </div>
        <button onclick="toggleUserModal()" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-6 rounded-xl shadow-lg transition-all">
            Add User
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($users as $user)
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 relative group">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center {{ $user->role === 'Admin' ? 'bg-orange-100 text-orange-600' : 'bg-slate-100 text-slate-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-slate-900 truncate">{{ $user->name }}</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs font-medium text-slate-400">@ {{ $user->username }}</span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase {{ $user->role === 'Admin' ? 'bg-orange-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                        {{ $user->role }}
                    </span>
                </div>
            </div>
            
            <div class="absolute top-4 right-4 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <!-- Edit Button -->
                <button data-user='@json($user)' onclick='openEditUserModal(this)' class="p-2 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>

                @if($user->id !== 1)
                <!-- Delete Button -->
                <form action="{{ url('users/' . $user->id) }}" method="POST" onsubmit="return confirm('Delete this user account?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Add User Modal -->
<div id="userModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-xl font-bold text-slate-900">Add New User</h3>
        </div>
        <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                <input type="text" name="name" required class="w-full px-4 py-2 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                <input type="text" name="username" required class="w-full px-4 py-2 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Role</label>
                <select name="role" class="w-full px-4 py-2 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="Cashier">Cashier</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <div class="mt-8 flex gap-3 pt-4">
                <button type="button" onclick="toggleUserModal()" class="flex-1 py-3 px-4 rounded-xl font-bold text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-[2] py-3 px-4 bg-orange-600 hover:bg-orange-700 text-white rounded-xl font-bold shadow-lg shadow-orange-100 transition-all">
                    Save User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden border border-slate-100">
        <div class="p-8 border-b flex justify-between items-center bg-slate-50/50">
            <h3 class="text-xl font-black text-slate-900 uppercase tracking-widest">Modify Member</h3>
            <button onclick="toggleEditUserModal()" class="text-slate-400 hover:text-slate-900 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="editUserForm" action="" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                    <input type="text" name="name" id="editUserName" required class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-orange-500 focus:bg-white transition-all font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Username</label>
                    <input type="text" name="username" id="editUserUsername" required class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-orange-500 focus:bg-white transition-all font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-orange-600">New Password (Leave blank to keep same)</label>
                    <input type="password" name="password" placeholder="••••••••" class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-orange-500 focus:bg-white transition-all font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">System Role</label>
                    <select name="role" id="editUserRole" class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-orange-500 focus:bg-white transition-all font-bold appearance-none">
                        <option value="Cashier">Cashier</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
            </div>
            <div class="pt-6 flex gap-4">
                <button type="button" onclick="toggleEditUserModal()" class="flex-1 py-4 font-bold text-slate-400 uppercase text-xs tracking-widest">Cancel</button>
                <button type="submit" class="flex-[2] py-4 bg-slate-900 text-white rounded-2xl font-black shadow-xl hover:bg-orange-600 transition-all uppercase text-xs tracking-widest active:scale-95">Update Staff</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleUserModal() {
        document.getElementById('userModal').classList.toggle('hidden');
    }

    function toggleEditUserModal() {
        document.getElementById('editUserModal').classList.toggle('hidden');
    }

    function openEditUserModal(button) {
        const user = JSON.parse(button.getAttribute('data-user'));
        const form = document.getElementById('editUserForm');
        form.action = `/users/${user.id}`;
        
        document.getElementById('editUserName').value = user.name;
        document.getElementById('editUserUsername').value = user.username;
        document.getElementById('editUserRole').value = user.role;
        
        toggleEditUserModal();
    }
</script>
@endsection
