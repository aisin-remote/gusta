<?php

namespace App\Http\Controllers;

use App\User;
use App\Guest;
use App\Checkin;
use Carbon\Carbon;
use App\CardStatus;
use App\FacilityDetail;
use App\ApprovalHistory;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{

    public function index()
    {
        $userId = auth()->user()->id;
        $userDept = auth()->user()->department_id;
        $occupation = User::select('occupation')->where('id', $userId)->first();
        $role = auth()->user()->role;

        $appointments = Appointment::latest();
        //load facitily detail
        $appointments->with('facility_detail', 'guests');


        if ($role == 'superadmin') {
            // Jika role "superadmin", ambil semua appointment dengan dh_approval "pending"
            $appointments->where('dh_approval', 'pending');
        } else {
            if ($occupation->occupation == 2) {
                // Jika occupation adalah 2, filter berdasarkan dept dan status approval
                $appointments->where('pic_approval', 'approved')
                    ->where('dh_approval', 'pending')
                    ->where('pic_dept', $userDept);
            } else {
                // Jika occupation bukan 2, filter berdasarkan pic_id (userId)
                $appointments->where('pic_approval', 'pending')
                    ->where('dh_approval', 'pending')
                    ->where('pic_id', $userId);
            }
        }
        $appointments = $appointments->get();
        //if appointment date is tomorrow then add new key  facility_eligble false,if the day after tomorrow or later then true
        foreach ($appointments as $key => $appointment) {
            $date = Carbon::parse($appointment['date']);

            $now = Carbon::now();
            $now->setTime(0, 0, 0);
            //add 2 day
            $now->addDays(2);

            if ($date >= $now) {
                $appointments[$key]['facility_eligble'] = true;
            } else {
                $appointments[$key]['facility_eligble'] = false;
            }
        }
        // dd($appointments);

        return view('pages.admin.index', [
            'appointments' => $appointments,
            'occupation' => $occupation
        ]);
    }


    public function history()
    {
        $userId = auth()->user()->id;
        $userDept = auth()->user()->department_id;
        $user = User::select('occupation')->where('id', $userId)->first();
        $role = auth()->user()->role;

        // Query dasar untuk appointments
        $appointments = Appointment::with('guests', 'pic', 'facility_detail')->latest();

        // Logika berdasarkan role
        if ($role == 'superadmin') {
            // Jika superadmin, ambil semua history yang approved atau rejected
            $appointments->whereIn('dh_approval', ['approved', 'rejected']);
        } else {
            // Jika occupation adalah 2, filter berdasarkan dept dan status approval
            if ($user->occupation == 2) {
                $appointments->where('pic_approval', 'approved')
                    ->where('pic_dept', $userDept);
            } else {
                // Jika occupation bukan 2, filter berdasarkan pic_id (userId)
                $appointments->where('pic_id', $userId);
            }
        }

        // Ambil semua data yang sudah difilter
        $appointments = $appointments->get();

        return view('pages.admin.history', [
            'appointments' => $appointments,
            'user' => $user,
        ]);
    }



    public function ticketApproval(Request $request, Appointment $ticket)
    {
        $user = User::select('occupation')->where('id', $ticket->pic_id)->first();

        // if the pic of the ticket is spv down and the auth user is spv (the pic itself) then show the facility button modal
        // then only update the pic_appproval field
        if ($user->occupation == 1 && auth()->user()->occupation == 1) {
            Appointment::where('id', $ticket->id)->update([
                'pic_approval' => 'approved'
            ]);

            FacilityDetail::create([
                'snack_kering' => $request->get('dry-food-quantity'),
                'snack_basah' => $request->get('wet-food-quantity'),
                'makan_siang' => $request->get('lunch-quantity'),
                'permen' => $request->get('candy-quantity'),
                'kopi' => $request->get('coffee-quantity'),
                'teh' => $request->get('tea-quantity'),
                'soft_drink' => $request->get('soft-drink-quantity'),
                'air_mineral' => $request->get('mineral-water-quantity'),
                'helm' => $request->get('helm-quantity'),
                'handuk' => $request->get('handuk-quantity'),
                'speaker' => $request->get('speaker-quantity'),
                'speaker_wireless' => $request->get('speaker-wireless-quantity'),
                'mobil' => $request->get('mobil-quantity'),
                'motor' => $request->get('motor-quantity'),
                'mini_bus' => $request->get('mini-bus-quantity'),
                'bus' => $request->get('bus-quantity'),
                'other' => $request->get('other-value'),
                'appointment_id' => $ticket->id
            ]);

            // create or update approval history
            ApprovalHistory::create([
                'signed_by' => auth()->user()->id,
                'appointment_id' =>  $ticket->id,
                'status' => 'PIC approved'
            ]);
            // if the pic of ticket is spv down (1) and the auth user is manager up, then show the approval button modal and only update the dh_approval because the pic_approval already approved, because the ticket is appear when already approved by the pic , when the ticket not approved yet by the pic, the ticket should not appear in the list
        } elseif ($user->occupation == 1 && auth()->user()->occupation == 2) {
            Appointment::where('id', $ticket->id)->update([
                'dh_approval' => 'approved'
            ]);

            // create or update approval history
            ApprovalHistory::where('appointment_id', $ticket->id)->update([
                'signed_by' => auth()->user()->id,
                'status' => 'Dept Head approved'
            ]);
            // if the pic of the ticket is manager up and the auth user is manager (the pic itself) then show the facility button modal
            // and update both pic and dh approval
        } elseif ($user->occupation == 2 && auth()->user()->occupation == 2) {
            Appointment::where('id', $ticket->id)->update([
                'pic_approval' => 'approved',
                'dh_approval' => 'approved'
            ]);

            FacilityDetail::create([
                'snack_kering' => $request->get('dry-food-quantity'),
                'snack_basah' => $request->get('wet-food-quantity'),
                'makan_siang' => $request->get('lunch-quantity'),
                'permen' => $request->get('candy-quantity'),
                'kopi' => $request->get('coffee-quantity'),
                'teh' => $request->get('tea-quantity'),
                'soft_drink' => $request->get('soft-drink-quantity'),
                'air_mineral' => $request->get('mineral-water-quantity'),
                'helm' => $request->get('helm-quantity'),
                'handuk' => $request->get('handuk-quantity'),
                'speaker' => $request->get('speaker-quantity'),
                'speaker_wireless' => $request->get('speaker-wireless-quantity'),
                'mobil' => $request->get('mobil-quantity'),
                'motor' => $request->get('motor-quantity'),
                'mini_bus' => $request->get('mini-bus-quantity'),
                'bus' => $request->get('bus-quantity'),
                'other' => $request->get('other-value'),
                'appointment_id' => $ticket->id
            ]);

            ApprovalHistory::create([
                'signed_by' => auth()->user()->id,
                'appointment_id' =>  $ticket->id,
                'status' => 'Dept Head approved'
            ]);
        }


        return redirect()->back()->with('approved', 'Ticket has been approved!');
    }

    public function ticketRejection(Request $request, Appointment $ticket)
    {
        // create approval history
        $user = User::select('occupation')->where('id', $ticket->pic_id)->first();

        // if the pic of the ticket is spv down and the auth user is spv (the pic itself)
        // then only update the pic_appproval field
        if ($user->occupation == 1 && auth()->user()->occupation == 1) {
            ApprovalHistory::create([
                'signed_by' => auth()->user()->id,
                'appointment_id' =>  $ticket->id,
                'note' => $request->note,
                'status' => 'rejected'
            ]);

            // update appointment status, if the pic reject then dept head automatically reject
            Appointment::where('id', $ticket->id)->update([
                'pic_approval' => 'rejected',
                'dh_approval' => 'rejected'
            ]);
        }
        // if the pic of ticket is spv down (1) and the auth user is manager up, then show the approval button modal and only update the dh_approval because the pic_approval already approved, because the ticket is appear when already approved by the pic , when the ticket not approved yet by the pic, the ticket should not appear in the list
        elseif ($user->occupation == 1 && auth()->user()->occupation == 2) {
            ApprovalHistory::where('appointment_id', $ticket->id)->update([
                'signed_by' => auth()->user()->id,
                'note' => $request->note,
                'status' => 'rejected'
            ]);

            // update appointment status, if the pic reject then dept head automatically reject
            Appointment::where('id', $ticket->id)->update([
                'dh_approval' => 'rejected'
            ]);
            // if the pic of the ticket is manager up and the auth user is manager (the pic itself) then show the facility button modal
            // and update both pic and dh approval
        } elseif ($user->occupation == 2 && auth()->user()->occupation == 2) {
            ApprovalHistory::where('appointment_id', $ticket->id)->update([
                'signed_by' => auth()->user()->id,
                'note' => $request->note,
                'status' => 'rejected'
            ]);

            // update appointment status
            Appointment::where('id', $ticket->id)->update([
                'dh_approval' => 'rejected'
            ]);
        }

        return redirect()->back()->with('reject', 'Ticket has been rejected!');
    }

    public function facilityDone(FacilityDetail $facility)
    {

        FacilityDetail::where('id', $facility->id)->update([
            'status' => 'done'
        ]);

        return redirect()->back()->with('selesai', 'Kebutuhan untuk tiket-' . $facility->appointment_id . ' telah siap!');
    }

    public function facilityHistory()
    {
        $appointment = DB::table('facility_details')
            ->join('appointments', 'facility_details.appointment_id', '=', 'appointments.id')
            ->join('users', 'appointments.pic_id', '=', 'users.id')
            ->select('appointments.id', 'users.name', 'appointments.purpose', 'appointments.date', 'facility_details.status')
            ->where('pic_approval', 'approved')
            ->where('dh_approval', 'approved')
            ->get();

        // dd($appointment);   
        return view('pages.admin.facility-history', [
            'appointments' => $appointment
        ]);
    }

    public function qrScanView()
    {
        return view('pages.admin.qrcode', [
            'appointments' => [],
        ]);
    }

    public function qrScan(Request $request)
    {
        $qrId = $request->qr_code;
        $status = null;
        $appointments = Appointment::with('card')->where('qr_code', $qrId)->first();

        if (!$appointments || empty($appointments) || $appointments == null) {
            return redirect()->back()->with('error', 'Invalid QR Code!');
        }

        $checkin_status = Checkin::where('appointment_id', $appointments->id)->first();

        $now = Carbon::now();
        // update checkin status
        //$appointments->time - 10 minutes

        $appointments10minutes = Carbon::parse($appointments->time)->subMinutes(10);

        if ($appointments->date >= $now->format('Y-m-d') && $appointments->time >= $now->format('H:i:s')) {
            //not allow if it's not 10 minutes before the appointment

            if ($appointments->date != $now->format('Y-m-d') && $appointments10minutes >= $now->format('H:i:s')) {
                return redirect()->back()->with('error', 'Not yet time to visit!');
            } else {
                if ($appointments !== null || $checkin_status !== null) {
                    if ($checkin_status->status === 'out') {
                        $status = "sukses_in";
                        $checkin_status->update([
                            'status' => 'in',
                            'checkin_at' => Carbon::now(),
                        ]);
                    } elseif ($checkin_status->status === 'in') {
                        $status = 'sukses_out';
                        $checkin_status->update([
                            'status' => 'out',
                            'checkout_at' => Carbon::now(),
                        ]);
                    }
                }
            }
        } else {
            return redirect()->back()->with('error', 'Ticket Expired!');
        }

        $guests = Guest::where('appointment_id', $appointments->id)->get(); // Fetch guests associated with the appointment

        $cardDetails = [];
        foreach ($guests as $guest) {
            $cardDetails[] = [
                'card_id' => $appointments->card->id,
                'guest_id' => $guest->id,
                'guest_name' => $guest->name, // You can adjust this based on your Guest model fields
                'guest_photo' => asset('uploads/doc/' . $guest->photo ?? 'default.jpg'), // You can adjust this based on your Guest model fields
                'guest_id_card' => $guest->id_card, // You can adjust this based on your Guest model fields
                'card_title' => $appointments->card->title ?? 'No Title', // Fetching the card title associated with the appointment
                'card_image_url' => asset('uploads/cards/' . $appointments->card->card ?? 'default.jpg'), // You can modify to handle default card images
            ];
        }

        return view('pages.admin.scan-card', [
            'status' => 'success',
            'checkin_status' => $status,
            'details' => $cardDetails
        ]);
    }

    public function cardScan(Request $request)
    {
        $card_id = $request->card_id;
        $guest_id = $request->guest_id;
        $serial = $request->serial;
        $current_status = $request->current_status;

        // cek card status
        $card = CardStatus::where('card_id', $card_id)
            ->where('serial', $serial)
            ->first();

        if (!$card) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kartu tidak terdftar!'
            ]);
        }

        if ($card->status == 'used' && $card->guest_id !== null && $current_status == 'sukses_in') {
            return response()->json([
                'status' => 'error',
                'message' => 'Kartu sudah digunakan!'
            ]);
        }

        if ($card->status == 'ready' && $card->guest_id == null && $current_status == 'sukses_out') {
            return response()->json([
                'status' => 'error',
                'message' => 'Salah Kartu!'
            ]);
        }

        try {
            DB::beginTransaction();

            // update status
            if ($card->status == 'used' && $card->guest_id !== null && $current_status == 'sukses_out') {
                CardStatus::where('card_id', $card_id)
                    ->where('serial', $serial)
                    ->update([
                        'guest_id' => null,
                        'status' => 'ready'
                    ]);
            } elseif ($card->status == 'ready' && $card->guest_id == null && $current_status == 'sukses_in') {
                CardStatus::where('card_id', $card_id)
                    ->where('serial', $serial)
                    ->update([
                        'guest_id' => $guest_id,
                        'status' => 'used'
                    ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Kartu berhasil discan, silahkan masuk!'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
}
