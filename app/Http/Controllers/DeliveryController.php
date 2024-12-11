<?php

namespace App\Http\Controllers;

use App\User;
use App\Delivery;
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
        $pic = User::select('occupation')->where('id', $request->pic_id)->first();

        // Validate input
        $request->validate([
            'nama' => 'required',
            'purpose-1' => 'required_without_all:purpose-2,purpose-3,purpose-4',
            'purpose-2' => 'required_without_all:purpose-1,purpose-3,purpose-4',
            'purpose-3' => 'required_without_all:purpose-1,purpose-2,purpose-4',
            'date' => 'required',
            'time' => 'required',
            'jumlahTamu' => 'required',
            'pic_id' => 'required',
            'pic_dept' => 'required',
        ]);

        $purpose = '';
        if ($request->has('purpose-1')) {
            $purpose .= 'Equipment Delivery, ';
        }
        if ($request->has('purpose-2')) {
            $purpose .= 'Sample Delivery, ';
        }
        if ($request->has('purpose-3')) {
            $purpose .= $request->other_purpose . ', ';
        }
        $purpose = rtrim($purpose, ', ');

        // Handle document uploads
        $docName = $this->handleFileUpload($request, 'doc', 'uploads/doc');
        $selfieName = $this->handleFileUpload($request, 'selfie', 'uploads/selfie');

        // Determine PIC approval status
        $pic_approval = ($pic->occupation == 2) ? 'approved' : 'pending';

        try {
            DB::beginTransaction();
            $delivery = Delivery::create([
                'name' => $request->nama,
                'purpose' => $purpose,
                'date' => $request->date,
                'time' => $request->time,
                'guest' => $request->jumlahTamu,
                'pic_id' => $request->pic_id,
                'pic_dept' => $request->pic_dept,
                'doc' => $docName,
                'selfie' => $selfieName,
                'pic_approval' => $pic_approval,
                'dh_approval' => 'pending',
                'user_id' => auth()->user()->id,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('Error creating delivery: ' . $e->getMessage());
        }

        return redirect()->route('delivery.history')->with('success', 'Your delivery request has been successfully created!');
    }

    public function history()
    {
        if (auth()->user()->role === 'visitor') {
            $deliveries = Delivery::latest()->where('user_id', auth()->user()->id)->get();
            $deliveries->load('pic');

            return view('pages.visitor.history', [
                'deliveries' => $deliveries,
            ]);
        }
    }

    public function getPic(Request $request)
    {
        // Get all PICs where dept_id is dept
        return User::select('name', 'id')->where('department_id', $request->dept)->get();
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
