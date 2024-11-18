<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('pages.user-management.index', compact('users'));
    }
    public function create()
    {
        return view('pages.user-management.add');
    }
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:15',
            'company' => 'nullable|string|max:255',
            'role' => 'required|string|in:admin,visitor,approver',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
            ],
        ]);

        // Simpan user baru
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'company' => $validated['company'],
            'role' => $validated['role'],
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $users = User::findOrFail($id);
        return view('pages.user-management.edit', compact('users'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:15',
            'company' => 'nullable|string|max:255',
            'role' => 'required|string|in:admin,visitor,approver',
        ]);

        // Proses update jika validasi berhasil
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'company' => $request->company,
            'role' => $request->role,
        ]);

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }
    public function destroy($id)
    {
        // dd($id);
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User deleted successfully');
    }
}
