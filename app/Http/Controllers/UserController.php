<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:Admin,Cashier',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->back()->with('success', 'User created successfully');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'role' => 'required|in:Admin,Cashier',
            'password' => 'nullable|min:6',
        ]);

        $updateData = [
            'name' => $data['name'],
            'username' => $data['username'],
            'role' => $data['role'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);
        return redirect()->back()->with('success', 'User credentials updated successfully');
    }

    public function destroy(User $user)
    {
        if ($user->id === 1) return back()->with('error', 'Cannot delete main admin');
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
