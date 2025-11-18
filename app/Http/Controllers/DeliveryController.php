<?php

namespace App\Http\Controllers;

use App\User;
use App\Delivery;
use Carbon\Carbon;
use App\Department;
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

    public function history(Request $request)
    {
        // Ambil bulan dari request, default bulan sekarang
        $month = $request->input('month', Carbon::now()->month);
        $year  = $request->input('year', Carbon::now()->year);

        $deliveries = Delivery::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->get();

        $companies = Delivery::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->distinct()
            ->count('company');

        $visitors = Delivery::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->distinct()
            ->count('name');

        return view('pages.visitor.history-delivery', [
            'deliveries' => $deliveries,
            'companies'  => $companies,
            'visitors'   => $visitors,
            'selectedMonth' => $month,
            'selectedYear'  => $year
        ]);
    }


    public function getStat()
    {
        $today = Carbon::today();

        $deliveries = Delivery::whereDate('created_at', $today)->get();
        $companies = Delivery::whereDate('created_at', $today)->distinct()->count('company');
        $visitors = Delivery::whereDate('created_at', $today)->distinct()->count('name');

        return response()->json([
            'companies'  => $companies,
            'visitors'   => $visitors,
            'deliveries' => $deliveries,
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
