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
use App\ApprovalHistory;
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
        $pic = User::select('phone_number','occupation')->where('id', $request->pic_id)->first();
        $user_company = auth()->user()->company;
        $user_name = auth()->user()->name;

        $request->validate([
            'purpose-1' => 'required_without_all:purpose-2,purpose-3,purpose-4',
            'purpose-2' => 'required_without_all:purpose-1,purpose-3,purpose-4',
            'purpose-3' => 'required_without_all:purpose-1,purpose-2,purpose-4',
            'purpose-4' => 'required_without_all:purpose-1,purpose-2,purpose-3',
            'name.*' => 'required|string', // Validate all guest names
            'cardId.*' => 'required|string|min:16|max:16', // Validate all card IDs
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

            // insert approval history data
            ApprovalHistory::create([
                'appointment_id' => $appointment->id,
            ]);

            $date = $request->date . ' at ' . $request->time;

            // send Wa notif to pic
            $token = 'v2n49drKeWNoRDN4jgqcdsR8a6bcochcmk6YphL6vLcCpRZdV1';
            $phone = $pic->phone_number;
            // Pass array elements as additional arguments to sprintf
            $message = sprintf(
                "```----GUEST ALERT-------\n\nCompany : %s\nName    : %s\nAgenda  : %s\nDate    : %s\nStatus  : %s\n\nPlease confirm your guest!\nThank You```",
                $user_company,              // Assuming $user_company holds the company name
                $user_name,              // Assuming $user_company holds the company name
                $purpose,                   // The agenda of the guest visit
                $date,                          // The date of the visit
                $appointment->dh_approval   // The current status (e.g., 'pending')
            );
        
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                // Use http_build_query to properly encode the POST fields
                CURLOPT_POSTFIELDS => http_build_query([
                    'token' => $token,
                    'number' => $phone,
                    'message' => $message
                ]),
            ]);
            $response = curl_exec($curl);
            curl_close($curl); // Don't forget to close the cURL session once done

            DB::commit();
            
            return redirect()->route('appointment.history')->with('success', 'Your ticket has been successfully created! Please wait for the PIC to approve your ticket or contact the PIC.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('appointment.history')->with('error', $e->getMessage());
        }
    }
    
    public function edit($id)
    {
        $company = session()->get('company'); 
        $appointment = Appointment::findOrFail($id);
        $areas = Area::all();  // Assuming Area model exists
        $pics = User::where('company', $company)->where('role', 'approver')->get(); // Assuming User model exists
        $departments = Department::all();  // Assuming Department model exists

        return view('pages.visitor.edit', compact('appointment', 'areas', 'departments', 'pics'));
    }

    public function show($id)
    {
        $appointment = Appointment::with(['user', 'guests', 'pic', 'approval_history'])->findOrFail($id);
    
        $rejectedHistory = ApprovalHistory::where('appointment_id', $id)->first();
        
        return response()->json([
            'purpose' => $appointment->purpose,
            'formatted_date' => $appointment->date,
            'formatted_time' => $appointment->time,
            'guests' => $appointment->guests,
            'user' => $appointment->user,
            'pic' => $appointment->pic,
            // Include rejection reasons only if they exist
            'rejection_reason' => $rejectedHistory, // This will be a single reason or null,
        ]);
    }

    // Update the appointment
    public function update(Request $request, $id)
    {
        $request->validate([
            'purpose-1' => 'required_without_all:purpose-2,purpose-3,purpose-4',
            'purpose-2' => 'required_without_all:purpose-1,purpose-3,purpose-4',
            'purpose-3' => 'required_without_all:purpose-1,purpose-2,purpose-4',
            'purpose-4' => 'required_without_all:purpose-1,purpose-2,purpose-3',
            'name.*' => 'required|string',
            'cardId.*' => 'required|digits:16',
            'date' => 'required|date',
            'time' => 'required',
            'area_id' => 'required|exists:areas,id',
            'pic_dept' => 'required|exists:departments,id',
            'pic_id' => 'required|exists:users,id',
            // Additional validations as needed
        ]);
        
        // Build the purpose string
        $purposes = [];
        if ($request->has('purpose-1')) $purposes[] = 'Company Visit';
        if ($request->has('purpose-2')) $purposes[] = 'Benchmarking';
        if ($request->has('purpose-3')) $purposes[] = 'Trial';
        if ($request->has('purpose-4')) $purposes[] = $request->other_purpose;
        $purpose = implode(', ', $purposes);

        $appointment = Appointment::findOrFail($id);
        $appointment->update([
            'purpose' => $purpose,
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'area_id' => $request->input('area_id'),
            'pic_dept' => $request->input('pic_dept'),
            'pic_id' => $request->input('pic_id'),
            // Additional fields as required
        ]);

        foreach ($request->name as $index => $guestName) {
            Guest::where('appointment_id', $appointment->id)->update([
                'name' => $guestName,
                'id_card' => $request->cardId[$index],
            ]);
        }

        return redirect()->route('appointment.history')->with('success', 'Appointment updated successfully.');
    }

    public function history(\App\Models\Appointment $appointment)
    {
        if(auth()->user()->role === 'visitor')
        {
            $appointments = Appointment::where('user_id', auth()->user()->id)->get();
            //get the pic name laravel 5
            $appointments->load('pic')->toArray();

            return view('pages.visitor.history',[
                'appointments' => $appointments,
            ]);
        }

    }

    public function getPic(Request $request)
    {
        $company = $request->company == 'aisin' ? 'AIIA' : $request->company;
        // get all pic where dept_id is dept
        $pic = User::select('name','id')->where('department_id', $request->dept)->where('company', $company)->get();

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

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Perform the delete operation
        $appointment->delete();

        // Redirect back with a success message
        return redirect()->route('appointment.history')->with('success', 'Appointment deleted successfully.');
    }
}
