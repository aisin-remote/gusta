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

        $request->validate([
            'purpose-1' => 'required_without_all:purpose-2,purpose-3,purpose-4',
            'purpose-2' => 'required_without_all:purpose-1,purpose-3,purpose-4',
            'purpose-3' => 'required_without_all:purpose-1,purpose-2,purpose-4',
            'purpose-4' => 'required_without_all:purpose-1,purpose-2,purpose-3',

            'name.*'    => 'required|string',
            'id_type.*' => 'required|in:KTP,SIM,ID CARD',
            'cardId.*'  => ['required','string','regex:/^(?:\d{16}|[A-Za-z0-9-]{6,20})$/'],

            'ipk_form'  => [
                'sometimes',
                function ($attribute, $value, $fail) {
                    if (session()->get('category') === 'Contractor' && !$value) {
                        $fail('The IPK form document is required for contractors.');
                    }
                },
                'file','mimes:pdf,jpeg,png,jpg,gif','max:4096'
            ],

            'date'      => 'required|date',
            'time'      => 'required',
            'area_id'   => 'required|exists:areas,id',
            'pic_id'    => 'required|exists:users,id',
            'pic_dept'  => 'required|string',
        ]);

        $names = (array) $request->input('name', []);
        $types = (array) $request->input('id_type', []);
        $cards = (array) $request->input('cardId', []);
        if (count($names) !== count($types) || count($names) !== count($cards)) {
            return back()->withInput()->with('error', 'Jumlah Name, ID Type, dan ID Number tidak selaras.');
        }

        $otherPurpose = strip_tags($request->input('other_purpose'));
        $picDept = strip_tags($request->input('pic_dept'));

        $purposes = [];
        if ($request->has('purpose-1')) $purposes[] = 'Company Visit';
        if ($request->has('purpose-2')) $purposes[] = 'Benchmarking';
        if ($request->has('purpose-3')) $purposes[] = 'Trial';
        if ($request->has('purpose-4')) $purposes[] = $otherPurpose;
        $purpose = implode(', ', $purposes);

        $docName = null;
        if ($request->hasFile('ipk_form')) {
            $doc = $request->file('ipk_form');
            $docName = time() . '-' . $doc->getClientOriginalName();
            $doc->storeAs('public/doc', $docName);
        }

        $qr = Uuid::uuid4()->toString();

        $card = Card::select('id')
            ->where('area_id', $request->area_id)
            ->where('category', session()->get('category'))
            ->first();

        try {
            DB::beginTransaction();

            $appointment = Appointment::create([
                'purpose'      => $purpose,
                'ipk_form'     => $docName,
                'date'         => $request->date,
                'time'         => $request->time,
                'pic_id'       => $request->pic_id,
                'area_id'      => $request->area_id,
                'pic_dept'     => $picDept,
                'pic_approval' => 'approved',
                'qr_code'      => $qr,
                'dh_approval'  => 'approved',
                'user_id'      => auth()->user()->id,
                'card_id'      => $card->id ?? null,
                // 'id_type' dihapus: sudah tidak ada di tabel appointments
            ]);

            Checkin::create([
                'appointment_id' => $appointment->id,
                'status' => 'out',
            ]);

            // Simpan per-guest (id_type ikut index)
            foreach ($names as $i => $guestName) {
                $guestType = strtoupper(strip_tags($types[$i] ?? 'KTP'));
                if (!in_array($guestType, ['KTP','SIM','ID CARD'], true)) {
                    $guestType = 'KTP';
                }

                $idCard = strip_tags($cards[$i] ?? '');
                if ($guestType === 'KTP') {
                    $idCard = preg_replace('/\D/', '', $idCard);
                }

                Guest::create([
                    'appointment_id' => $appointment->id,
                    'name'    => strip_tags($guestName),
                    'photo'   => null,
                    'id_card' => $idCard,
                    'id_type' => $guestType,
                ]);
            }

            ApprovalHistory::create([
                'appointment_id' => $appointment->id,
            ]);

            // WA notif (tetap)
            $date = $request->date . ' at ' . $request->time;
            $token = 'v2n49drKeWNoRDN4jgqcdsR8a6bcochcmk6YphL6vLcCpRZdV1';
            $phone = $pic->phone_number;

            $guestNames = Guest::where('appointment_id', $appointment->id)->pluck('name')->toArray();
            $guestNamesList = '- ' . implode("\n- ", $guestNames);

            $message = sprintf(
                "```----GUEST ALERT-------\n\nCompany : %s\nGuests : \n%s\n\nAgenda  : %s\nDate    : %s\nStatus  : %s\n\nPlease confirm your guest!\nThank You\n\nPlease click the link below:\n%s```",
                $user_company,
                $guestNamesList,
                $purpose,
                $date,
                $appointment->dh_approval,
                'https://gusta.aiia.co.id/approval'
            );

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query([
                    'token'   => $token,
                    'number'  => $phone,
                    'message' => $message
                ]),
            ]);
            $response = curl_exec($curl);
            curl_close($curl);

            DB::commit();

            return redirect()->route('appointment.history')
                ->with('success', 'Your ticket has been successfully created! Please wait for the PIC to approve your ticket or contact the PIC.');
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
        // VALIDASI (samakan dengan create)
        $request->validate([
            'purpose-1' => 'required_without_all:purpose-2,purpose-3,purpose-4',
            'purpose-2' => 'required_without_all:purpose-1,purpose-3,purpose-4',
            'purpose-3' => 'required_without_all:purpose-1,purpose-2,purpose-4',
            'purpose-4' => 'required_without_all:purpose-1,purpose-2,purpose-3',

            'name.*'    => 'required|string',
            'id_type.*' => 'required|in:KTP,SIM,ID CARD',
            // KTP 16 digit ATAU SIM/ID 6–20 alfanumerik + '-'
            'cardId.*'  => ['required','string','regex:/^(?:\d{16}|[A-Za-z0-9-]{6,20})$/'],

            'date'      => 'required|date',
            'time'      => 'required',
            'area_id'   => 'required|exists:areas,id',
            // Konsisten dengan create(): pic_dept string
            'pic_dept'  => 'required|string',
            'pic_id'    => 'required|exists:users,id',
        ]);

        // GUARD: pastikan array sejajar
        $names = (array) $request->input('name', []);
        $types = (array) $request->input('id_type', []);
        $cards = (array) $request->input('cardId', []);
        if (count($names) !== count($types) || count($names) !== count($cards)) {
            return back()->withInput()->with('error', 'Jumlah Name, ID Type, dan ID Number tidak selaras.');
        }

        // Purpose
        $otherPurpose = strip_tags($request->input('other_purpose'));
        $purposes = [];
        if ($request->has('purpose-1')) $purposes[] = 'Company Visit';
        if ($request->has('purpose-2')) $purposes[] = 'Benchmarking';
        if ($request->has('purpose-3')) $purposes[] = 'Trial';
        if ($request->has('purpose-4')) $purposes[] = $otherPurpose;
        $purpose = implode(', ', $purposes);

        $appointment = Appointment::findOrFail($id);
        if ($appointment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        DB::beginTransaction();
        try {
            // UPDATE APPOINTMENT (tanpa id_type)
            $appointment->update([
                'purpose'  => $purpose,
                'date'     => $request->input('date'),
                'time'     => $request->input('time'),
                'area_id'  => $request->input('area_id'),
                'pic_dept' => strip_tags($request->input('pic_dept')),
                'pic_id'   => $request->input('pic_id'),
            ]);

            // RESET & REINSERT GUESTS sesuai urutan form
            Guest::where('appointment_id', $appointment->id)->delete();

            foreach ($names as $i => $guestName) {
                $guestType = strtoupper(strip_tags($types[$i] ?? 'KTP'));
                if (!in_array($guestType, ['KTP','SIM','ID CARD'], true)) {
                    $guestType = 'KTP';
                }

                $idCard = strip_tags($cards[$i] ?? '');
                if ($guestType === 'KTP') {
                    // normalisasi: hanya digit
                    $idCard = preg_replace('/\D/', '', $idCard);
                }

                Guest::create([
                    'appointment_id' => $appointment->id,
                    'name'    => strip_tags($guestName),
                    'photo'   => null, // kalau ingin mempertahankan foto lama, ganti strategi (update by id)
                    'id_card' => $idCard,
                    'id_type' => $guestType,
                ]);
            }

            DB::commit();
            return redirect()->route('appointment.history')->with('success', 'Appointment updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
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

        // Cek user yang login pemilik appointment
        if ($appointment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Perform the delete operation
        $appointment->delete();

        // Redirect back with a success message
        return redirect()->route('appointment.history')->with('success', 'Appointment deleted successfully.');
    }
}