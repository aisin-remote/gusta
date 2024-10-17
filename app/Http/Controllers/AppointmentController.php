<?php

namespace App\Http\Controllers;

use App\Room;
use App\User;
use App\Checkin;
use App\Department;
use App\RoomDetail;
use App\Models\Appointment;
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
        // $rooms = Room::where('status','available')->get();

        return view('pages.visitor.index',[
            'departments' => $departments,
            // 'rooms' =>  $rooms
        ]);
    }

    public function create(Request $request)
    {

        $pic = User::select('occupation')->where('id',$request->pic_id)->first();

        $request->validate([
            'nama' => 'required',
            'purpose-1' => 'required_without_all:purpose-2,purpose-3,purpose-4',
            'purpose-2' => 'required_without_all:purpose-1,purpose-3,purpose-4',
            'purpose-3' => 'required_without_all:purpose-1,purpose-2,purpose-4',
            'purpose-4' => 'required_without_all:purpose-1,purpose-2,purpose-3',
            'date' => 'required',
            'time' => 'required',
            'jumlahTamu' => 'required',
            'room' => '',
            'pic_id' => 'required',
            'pic_dept' => 'required',
        ]);

        $purpose = '';
        if($request->has('purpose-1')) {
            $purpose .= 'Company Visit, ';
        }
        if($request->has('purpose-2')) {
            $purpose .= 'Benchmarking, ';
        }
        if($request->has('purpose-3')) {
            $purpose .= 'Trial ';
        }
        if($request->has('purpose-4')) {
            $purpose .= $request->other_purpose;
        }
        $purpose = rtrim($purpose, ', ');

        if($request->has('doc')){
            $doc = $request->file('doc');
            $docName = time() . '-' . $doc->getClientOriginalName();
            $doc->move(public_path('uploads/doc'), $docName);
        } else {
            $docName = '';
        }

        if($request->has('selfie')){
            $doc2 = $request->file('selfie');
            $doc2Name = time() . '-' . $doc2->getClientOriginalName();
            $doc2->move(public_path('uploads/selfie'), $doc2Name);
        } else {
            $doc2Name = '';
        }

        // if pic == dept head (2), only 1 step approval
        if($pic->occupation == 2){
            $pic_approval = 'approved';
        }else{
            $pic_approval = 'pending';
        }
        try{
        DB::beginTransaction();
        $appointment = Appointment::create([
            'name' => $request->nama,
            'purpose' => $purpose,
            'date' => $request->date,
            'time' => $request->time,
            'guest' => $request->jumlahTamu,
            'pic_id' => $request->pic_id,
            'pic_dept' => $request->pic_dept,
            'doc' => $docName,
            'selfie' => $doc2Name,
            'pic_approval' => $pic_approval,
            'dh_approval' => 'pending',
            'user_id' => auth()->user()->id
        ]);

        // create checkin data immedietly
        Checkin::create([
            'appointment_id' => $appointment->id,
            'status' => 'out',
        ]);

        // create rooms detail data immedietly
        RoomDetail::create([
            'appointment_id' => $appointment->id,
            'room_id' => $request->room,
            'booking_date' => $request->date,
            'booking_time' => $request->time
        ]);
        DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollback();
        }

        return redirect()->route('appointment.history')->with('success', 'Your ticket has been successfully created! Please wait for the PIC to approve your ticket or Contact the PIC');
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
