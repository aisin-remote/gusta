<?php

namespace App\Http\Controllers;

use App\User;
use App\Delivery;
use App\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function index()
    {
        $departments = Department::all();

        return view('pages.visitor.delivery', [
            'departments' => $departments,
        ]);
    }

    public function create(Request $request)
    {
        // Validasi input
        $request->validate([
            'company'     => 'required|string|max:255',
            'name'        => 'required|array',
            'name.*'      => 'required|string|max:255',
            'destination' => 'required|string|in:Office,Unit,Body',
        ]);

        // Loop melalui setiap nama dan simpan data ke database
        foreach ($request->name as $name) {
            Delivery::create([
                'company'     => $request->company,
                'name'        => $name,
                'destination' => $request->destination,
            ]);
        }

        return redirect()->back()->with('success', 'Your delivery request has been successfully created!');
    }

    public function history()
    {
        $today = Carbon::today();

        $deliveries = Delivery::whereDate('created_at', $today)->get();
        $companies = Delivery::whereDate('created_at', $today)->distinct()->count('company');
        $visitors = Delivery::whereDate('created_at', $today)->distinct()->count('name');
        
        return view('pages.visitor.history-delivery', [
            'deliveries' => $deliveries,
            'companies'  => $companies,
            'visitors'   => $visitors,
        ]);
    }

    private function handleFileUpload(Request $request, $fileInputName, $destinationPath)
    {
        if ($request->hasFile($fileInputName)) {
            $file = $request->file($fileInputName);
            $fileName = time() . '-' . $file->getClientOriginalName();
            $file->move(public_path($destinationPath), $fileName);
            return $fileName;
        }
        return '';
    }
}
