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

    public function create(\Illuminate\Http\Request $request)
    {
        try {

            // 1) VALIDATION
            $request->validate([
                'purpose-1' => 'required_without_all:purpose-2,purpose-3,purpose-4',
                'purpose-2' => 'required_without_all:purpose-1,purpose-3,purpose-4',
                'purpose-3' => 'required_without_all:purpose-1,purpose-2,purpose-4',
                'purpose-4' => 'required_without_all:purpose-1,purpose-2,purpose-3',

                'name.*'    => 'required|string|max:255',
                'id_type.*' => 'required|in:KTP,SIM,ID CARD',

                'ipk_form'  => [
                    'nullable',
                    function ($attribute, $value, $fail) {
                        if (strtolower((string) session()->get('category')) === 'contractor' && !$value) {
                            $fail('The IPK form document is required for contractors.');
                        }
                    },
                    'file','mimes:pdf,jpeg,png,jpg,gif','max:4096'
                ],

                'date'      => 'required|date_format:Y-m-d',
                'time'      => 'required|date_format:H:i',
                'area_id'   => 'required|exists:areas,id',
                'pic_id'    => 'required|exists:users,id',
                'pic_dept'  => 'required|integer|exists:departments,id',
            ]);

            // 2) GET PIC
            $pic = User::select('phone_number','occupation')->find($request->pic_id);
            $user_company = optional(auth()->user())->company;

            // 3) GUEST NAME & ID TYPE CHECK
            $names = (array) $request->input('name', []);
            $types = (array) $request->input('id_type', []);

            if (count($names) !== count($types)) {
                return back()->withInput()->with('error', 'Jumlah Name dan ID Type tidak selaras.');
            }

            // 4) PURPOSE FIX (NO MORE boolean())
            $otherPurpose = trim(strip_tags($request->input('other_purpose')));
            $purposes = [];

            if ($request->has('purpose-1')) $purposes[] = 'Company Visit';
            if ($request->has('purpose-2')) $purposes[] = 'Benchmarking';
            if ($request->has('purpose-3')) $purposes[] = 'Trial';
            if ($request->has('purpose-4') && $otherPurpose !== '') {
                $purposes[] = $otherPurpose;
            }

            $purpose = implode(', ', $purposes);

            // 5) UPLOAD IPK FILE
            $docName = null;
            if ($request->hasFile('ipk_form')) {
                $doc = $request->file('ipk_form');
                $docName = time() . '-' . preg_replace('/[^\w\-.]+/', '_', $doc->getClientOriginalName());
                $doc->storeAs('public/doc', $docName);
            }

            // 6) CARD QR
            $qr = \Ramsey\Uuid\Uuid::uuid4()->toString();
            $card = Card::select('id')
                ->where('area_id', $request->area_id)
                ->where('category', session()->get('category'))
                ->first();

            // 7) TRANSACTION SAVE
            \DB::beginTransaction();

            $appointment = \App\Models\Appointment::create([
                'purpose'      => $purpose,
                'ipk_form'     => $docName,
                'date'         => $request->date,
                'time'         => $request->time,
                'pic_id'       => $request->pic_id,
                'area_id'      => $request->area_id,
                'pic_dept'     => (string) $request->pic_dept,
                'pic_approval' => 'approved',
                'qr_code'      => $qr,
                'dh_approval'  => 'approved',
                'user_id'      => auth()->id(),
                'card_id'      => $card->id ?? null,
            ]);

            Checkin::create([
                'appointment_id' => $appointment->id,
                'status'         => 'out',
            ]);

            // 8) GUEST SAVE
            foreach ($names as $i => $guestName) {
                $guestType = strtoupper(trim(strip_tags($types[$i] ?? 'KTP')));
                if (!in_array($guestType, ['KTP','SIM','ID CARD'], true)) {
                    $guestType = 'KTP';
                }

                Guest::create([
                    'appointment_id' => $appointment->id,
                    'name'    => trim(strip_tags($guestName)),
                    'photo'   => null,
                    'id_card' => null,
                    'id_type' => $guestType,
                ]);
            }

            ApprovalHistory::create([
                'appointment_id' => $appointment->id,
            ]);

            \DB::commit();

            // 9) SEND WA (NON BLOCKING)
            try {
                $phone = optional($pic)->phone_number;

                if (!empty($phone)) {
                    $guestNames = Guest::where('appointment_id', $appointment->id)->pluck('name')->toArray();

                    $message = sprintf(
                        "```----GUEST ALERT-------\n\nCompany : %s\nGuests : \n- %s\n\nAgenda  : %s\nDate    : %s at %s\nStatus  : %s\n\nPlease confirm your guest!\nThank You\n\nPlease click the link below:\n%s```",
                        (string) $user_company,
                        implode("\n- ", $guestNames),
                        $appointment->purpose,
                        $appointment->date,
                        $appointment->time,
                        $appointment->dh_approval,
                        'https://gusta.aiia.co.id/approval'
                    );

                    $ch = curl_init('https://app.ruangwa.id/api/send_message');
                    curl_setopt_array($ch, [
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => http_build_query([
                            'token'   => 'v2n49drKeWNoRDN4jgqcdsR8a6bcochcmk6YphL6vLcCpRZdV1',
                            'number'  => $phone,
                            'message' => $message
                        ]),
                        CURLOPT_CONNECTTIMEOUT => 5,
                        CURLOPT_TIMEOUT => 8,
                    ]);
                    curl_exec($ch);
                    curl_close($ch);
                }
            } catch (\Throwable $waErr) {
                \Log::warning('WA error', ['msg' => $waErr->getMessage()]);
            }

            return redirect()->route('appointment.history')
                ->with('success', 'Your ticket has been successfully created! Please wait for the PIC to approve your ticket or contact the PIC.');

        } catch (\Throwable $e) {

            if (\DB::transactionLevel() > 0) {
                \DB::rollBack();
            }

            \Log::error('Appointment create failed', [
                'msg'   => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'user'  => auth()->id(),
                'area'  => $request->input('area_id'),
                'pic'   => $request->input('pic_id'),
            ]);

            return back()->withInput()->with('error', 'Gagal membuat appointment: '.$e->getMessage());
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
    public function update(\Illuminate\Http\Request $request, $id)
    {
        // Pastikan record ada & pemiliknya benar
        $appointment = \App\Models\Appointment::findOrFail($id);
        if ($appointment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Validasi (selaras dengan create)
        $request->validate([
            'purpose-1' => 'required_without_all:purpose-2,purpose-3,purpose-4',
            'purpose-2' => 'required_without_all:purpose-1,purpose-3,purpose-4',
            'purpose-3' => 'required_without_all:purpose-1,purpose-2,purpose-4',
            'purpose-4' => 'required_without_all:purpose-1,purpose-2,purpose-3',

            'name'      => 'required|array|min:1',
            'name.*'    => 'required|string|max:255',
            'id_type'   => 'required|array',
            'id_type.*' => 'required|in:KTP,SIM,ID CARD',

            'date'      => 'required|date_format:Y-m-d',
            'time'      => 'required|date_format:H:i',
            'area_id'   => 'required|exists:areas,id',
            'pic_dept'  => 'required|integer|exists:departments,id', // konsisten dgn create()
            'pic_id'    => 'required|exists:users,id',

            // IPK opsional saat edit
            'ipk_form'  => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif|max:4096',
        ]);

        // Array sejajar
        $names = (array) $request->input('name', []);
        $types = (array) $request->input('id_type', []);
        if (count($names) !== count($types)) {
            return back()->withInput()->with('error', 'Jumlah Name dan ID Type tidak selaras.');
        }

        // Build purpose
        $otherPurpose = trim(strip_tags($request->input('other_purpose')));
        $purposes = [];
        if ($request->boolean('purpose-1')) $purposes[] = 'Company Visit';
        if ($request->boolean('purpose-2')) $purposes[] = 'Benchmarking';
        if ($request->boolean('purpose-3')) $purposes[] = 'Trial';
        if ($request->boolean('purpose-4') && $otherPurpose !== '') $purposes[] = $otherPurpose;
        $purpose = implode(', ', $purposes);

        try {
            \DB::beginTransaction();

            // Handle reupload IPK (hapus file lama agar tidak yatim)
            $docName = $appointment->ipk_form;
            if ($request->hasFile('ipk_form')) {
                $doc = $request->file('ipk_form');
                $docName = time() . '-' . preg_replace('/[^\w\-.]+/', '_', $doc->getClientOriginalName());
                $doc->storeAs('public/doc', $docName);

                // hapus lama jika ada
                if (!empty($appointment->ipk_form)) {
                    try {
                        \Storage::disk('public')->delete('doc/'.$appointment->ipk_form);
                    } catch (\Throwable $delEx) {
                        \Log::warning('Delete old IPK failed', ['msg' => $delEx->getMessage()]);
                    }
                }
            }

            // UPDATE APPOINTMENT
            $appointment->update([
                'purpose'  => $purpose,
                'ipk_form' => $docName,
                'date'     => $request->input('date'),
                'time'     => $request->input('time'),
                'area_id'  => $request->input('area_id'),
                'pic_dept' => (string) $request->input('pic_dept'), // simpan id sebagai string bila kolom text
                'pic_id'   => $request->input('pic_id'),
            ]);

            // RESET & REINSERT GUESTS (tanpa id_card)
            Guest::where('appointment_id', $appointment->id)->delete();

            foreach ($names as $i => $guestName) {
                $guestType = strtoupper(trim(strip_tags($types[$i] ?? 'KTP')));
                if (!in_array($guestType, ['KTP','SIM','ID CARD'], true)) {
                    $guestType = 'KTP';
                }

                Guest::create([
                    'appointment_id' => $appointment->id,
                    'name'    => trim(strip_tags($guestName)),
                    'photo'   => null,
                    'id_card' => null,
                    'id_type' => $guestType,
                ]);
            }

            \DB::commit();

            return redirect()->route('appointment.history')->with('success', 'Appointment updated successfully.');
        } catch (\Throwable $e) {
            if (\DB::transactionLevel() > 0) {
                \DB::rollBack();
            }
            \Log::error('Appointment update failed', [
                'msg'  => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'id'   => $appointment->id,
                'user' => auth()->id(),
            ]);
            return back()->withInput()->with('error', 'Gagal mengubah appointment: '.$e->getMessage());
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