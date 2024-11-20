<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('pages.departments.index', compact('departments'));
    }
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'departments' => 'required|array|min:1', // Pastikan ada minimal satu department yang diinput
            'departments.*' => 'required|string|max:255', // Setiap departemen harus berupa string dengan panjang maksimal 255 karakter
            'codes' => 'required|array|min:1', // Kode harus ada untuk setiap departemen
            'codes.*' => 'required|string|max:255', // Setiap kode harus berupa string dengan panjang maksimal 255 karakter
        ]);

        // Menyimpan data departemen
        foreach ($request->departments as $index => $departmentName) {
            Department::create([
                'name' => $departmentName, // Simpan nama departemen
                'code' => $request->codes[$index], // Simpan kode departemen
            ]);
        }

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Departments successfully added.');
    }
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'code' => 'required|max:10',
            'name' => 'required|max:255',
        ]);

        // Cari departemen berdasarkan ID
        $department = Department::findOrFail($id);

        // Perbarui data
        $department->code = $request->input('code');
        $department->name = $request->input('name');
        $department->save();

        // Redirect atau kembali dengan pesan sukses
        return redirect()->route('department.index')->with('success', 'Department updated successfully!');
    }
    public function destroy($id)
    {
        // dd($id);
        $departments = Department::findOrFail($id);
        $departments->delete();

        return redirect()->route('department.index')->with('success', 'Department deleted successfully');
    }
}
