<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Department;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('has_department')
            ->orderBy('updated_at', 'DESC')
            ->get();
        return view('pages.user-management.index', compact('users'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('pages.user-management.add', compact('departments'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:15',
            'departments' => 'required|exists:departments,id',
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

        // Tentukan occupation berdasarkan role
        $occupation = null;
        if ($validated['role'] === 'approver') {
            $occupation = 2;
        } elseif ($validated['role'] === 'admin') {
            $occupation = 3;
        }

        // Simpan user baru
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'department_id' => $validated['departments'],
            'company' => $validated['company'],
            'role' => $validated['role'],
            'password' => bcrypt($validated['password']),
            'email_verified_at' => Carbon::now(),
            'occupation' => $occupation,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User created successfully.');
    }


    public function edit($id)
    {
        $users = User::findOrFail($id);
        $departments = Department::all();
        return view('pages.user-management.edit', compact('users', 'departments'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:15',
            'company' => 'nullable|string|max:255',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
            ],
        ]);

        // Temukan user berdasarkan ID
        $user = User::findOrFail($id);

        $validated['departments'] = $request->departments ? $request->departments : null;
        $validated['role'] = $request->role ? $request->role : 'visitor';

        // Tentukan occupation berdasarkan role
        $occupation = null;
        if ($validated['role'] === 'approver') {
            $occupation = 2;
        } elseif ($validated['role'] === 'admin') {
            $occupation = 3;
        }else{
            $occupation = 1;
        }

        // Update data user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'department_id' => $validated['departments'],
            'company' => $validated['company'],
            'role' => $validated['role'],
            'occupation' => $occupation,
            'password' => $validated['password'] ? bcrypt($validated['password']) : $user->password, // Jika password diisi, update; jika tidak, gunakan password lama
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        // dd($id);
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'User deleted successfully');
    }
}
