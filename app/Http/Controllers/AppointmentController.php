<?php

namespace App\Http\Controllers;

use App\Area;
use App\Card;
use App\Room;
use App\User;
use App\Guest;
use App\Checkin;
use App\Department;
use App\RoomDetail;
use Ramsey\Uuid\Uuid;
use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ExportTicket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AppointmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $areas = Area::all();

        return view('pages.visitor.index',[
            'departments' => $departments,
            'areas' =>  $areas
        ]);
    }

    public function create(Request $request)
    {
        $pic = User::select('occupation')->where('id', $request->pic_id)->first();

        $request->validate([
            'purpose-1' => 'required_without_all:purpose-2,purpose-3,purpose-4',
            'purpose-2' => 'required_without_all:purpose-1,purpose-3,purpose-4',
            'purpose-3' => 'required_without_all:purpose-1,purpose-2,purpose-4',
            'purpose-4' => 'required_without_all:purpose-1,purpose-2,purpose-3',
            'name.*' => 'required|string', // Validate all guest names
            'cardId.*' => 'required|string', // Validate all card IDs
            'date' => 'required|date', // Ensure valid date format
            'time' => 'required', // Ensure time is provided
            'area_id' => 'required|exists:areas,id', // Ensure area exists
            'pic_id' => 'required|exists:users,id', // Ensure PIC exists
            'pic_dept' => 'required|string',
        ]);

        // Build the purpose string
        $purposes = [];
        if ($request->has('purpose-1')) $purposes[] = 'Company Visit';
        if ($request->has('purpose-2')) $purposes[] = 'Benchmarking';
        if ($request->has('purpose-3')) $purposes[] = 'Trial';
        if ($request->has('purpose-4')) $purposes[] = $request->other_purpose;
        $purpose = implode(', ', $purposes);

        if($request->has('ipk_form')){
            $doc = $request->file('ipk_form');
            $docName = time() . '-' . $doc->getClientOriginalName();
            $doc->move(public_path('uploads/doc'), $docName);
        } else {
            $docName = null;
        }

        // Determine approval status based on occupation
        $pic_approval = ($pic->occupation == 2) ? 'approved' : 'pending';

        // Generate a 15-character random string for barcode_id
        $qr = Uuid::uuid4()->toString(); ; // This generates a secure random string

        // get cards_id
        $card = Card::select('id')
                        ->where('area_id', $request->area_id)
                        ->where('category', session()->get('category'))
                        ->first();
                        
        try {
            DB::beginTransaction();

            // Create the appointment record
            $appointment = Appointment::create([
                'purpose' => $purpose,
                'ipk_form' => $docName,
                'date' => $request->date,
                'time' => $request->time,
                'pic_id' => $request->pic_id,
                'area_id' => $request->area_id,
                'pic_dept' => $request->pic_dept,
                'pic_approval' => $pic_approval,
                'qr_code' => $qr,
                'dh_approval' => 'pending',
                'user_id' => auth()->user()->id,
                'card_id' => $card->id
            ]);

            // Create check-in data immediately
            Checkin::create([
                'appointment_id' => $appointment->id,
                'status' => 'out', // Initial status
            ]);

            // Insert guest data
            foreach ($request->name as $index => $guestName) {
                Guest::create([
                    'appointment_id' => $appointment->id,
                    'name' => $guestName,
                    'id_card' => $request->cardId[$index],
                ]);
            }

            DB::commit();
            
            return redirect()->route('appointment.history')->with('success', 'Your ticket has been successfully created! Please wait for the PIC to approve your ticket or contact the PIC.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('appointment.history')->with('error', $e->getMessage());
        }
    }

    public function history(\App\Models\Appointment $appointment)
    {
        if(auth()->user()->role === 'visitor')
        {
            $appointments = Appointment::latest()->where('user_id', auth()->user()->id)->get();
            //get the pic name laravel 5
            $appointments->load('pic')->toArray();

            return view('pages.visitor.history',[
                'appointments' => $appointments,
            ]);
        }

    }

    public function getPic(Request $request)
    {
        // get all pic where dept_id is dept
        $pic = User::select('name','id')->where('department_id', $request->dept)->get();

        return $pic;
    }

    public function getRoom(Request $request)
    {
        $date = $request->date;

        // get booked rooms
        $roomBooked = Room::whereNotIn('id', function($query) use ($date) {
            $query->select('room_id')
                ->from('room_details')
                ->where('booking_date', $date);
        })->get();

        return $roomBooked;
    }

    public function export()
    {
        // export ticket
        return Excel::download(new ExportTicket, 'appointment.xlsx');
    }
}
