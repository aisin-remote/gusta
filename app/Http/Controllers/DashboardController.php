<?php

namespace App\Http\Controllers;

use App\Card;
use App\User;
use App\Checkin;
use App\CardStatus;
use App\FacilityDetail;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user_role = auth()->user()->role;
        $user_id = auth()->user()->id;
        $user_dept = auth()->user()->department_id;
        $current_date = date("Y-m-d");

        $appointments = Appointment::where('pic_approval', 'approved')->where('dh_approval', 'approved');
        $today_appointments = Appointment::where('date', $current_date);

        $today_appointment = $today_appointments->get();
        $visitor_inside = Checkin::with('appointment')
            ->join('appointments', 'appointments.id', '=', 'checkin.appointment_id')
            ->join('guests', 'guests.appointment_id', '=', 'appointments.id')
            ->where('date', $current_date)
            ->where('checkin.status', 'in')
            ->count();

        $today_visitor = $today_appointment->sum('guest');
        $current_time = date("H:i:s");

        $appt = Appointment::where('pic_approval', 'approved')->where('dh_approval', 'approved')
            ->get();
        foreach ($appt as $key => $a) {


            $faciliti = DB::table('facility_details')
                ->select('snack_kering', 'snack_basah', 'makan_siang', 'permen', 'kopi', 'teh', 'soft_drink', 'air_mineral', 'helm', 'handuk', 'speaker', 'speaker_wireless', 'mobil', 'motor', 'mini_bus', 'bus','other')
                ->leftJoin('appointments', 'appointments.id', '=', 'facility_details.appointment_id')
                //where appointment date and time more than current date, it's a date
                ->where(function($query) use ($current_date, $current_time) {
                    $query->where('appointments.date', '>', $current_date)
                          ->orWhere(function($query) use ($current_date, $current_time) {
                                $query->where('appointments.date', '=', $current_date)
                                      ->where('appointments.time', '>', $current_time);
                          });
                })
                ->where('status', 'pending')
                ->where('appointment_id', $appt[$key]->id)
                ->first();
            if($faciliti == null)
            {
                $faciliti = [];
            }



            //merge into $appt['facility']
            $appt[$key]->facility = $faciliti;
        }


        if ($user_role === 'admin') {
            $appointmentFacility =$appointments
            ->select('appointments.*', 'facility_details.status as facility_status')
            ->leftJoin('facility_details', 'appointments.id', '=', 'facility_details.appointment_id')
            ->where('date', $current_date)
            ->where('pic_approval', 'approved')
            ->where('dh_approval', 'approved');

            return view('dashboard', [
                'appointments' => $appointmentFacility->get(),
                'total_appointment' => $appointments->get()->count(),
                'today_visitor' => $today_visitor,
                'today_appointment' => $appointmentFacility->get()->count(),
                'visitor_inside' => $visitor_inside,
            ]);
        } elseif ($user_role === 'approver') {
            $appointmentFacility = $appointments
            ->select('appointments.*', 'facility_details.status as facility_status')
            ->leftJoin('facility_details', 'appointments.id', '=', 'facility_details.appointment_id')
            ->where('appointments.date', $current_date)
            ->where('pic_approval', 'approved')
            ->where('dh_approval', 'approved')
            ->where('pic_dept', $user_dept);

            return view('dashboard', [
                // showing appointment by department
                'appointments' => $appointmentFacility->get(),
                'total_appointment' => $appointments->get()->count(),
                'today_visitor' => $today_visitor,
                'today_appointment' => $appointmentFacility->get()->count(),
                'visitor_inside' => $visitor_inside,
            ]);
        } elseif ($user_role === 'visitor') {
            $appointmentFacility = $appointments
            ->select('appointments.*', 'facility_details.status as facility_status')
            ->leftJoin('facility_details', 'appointments.id', '=', 'facility_details.appointment_id')
            ->where('date', $current_date)
            ->where('pic_approval', 'approved')
            ->where('dh_approval', 'approved')
            ->where('user_id', $user_id);
            return view('dashboard', [
                'appointments' =>$appointmentFacility->get(),
                'total_appointment' => $appointments->get()->count(),
                'today_visitor' => $today_visitor,
                'today_appointment' => $appointmentFacility->get()->count(),
                'visitor_inside' => $visitor_inside,
            ]);
        } else {
            return view('dashboard', [
                'facilities' => $appt
            ]);
        }
    }

    public function card()
    {
        $cards = Card::all();
        $totalCards = CardStatus::selectRaw('card_id, COUNT(card_id) as total')
                            ->groupBy('card_id')
                            ->get();
                            
        $availableCards = CardStatus::selectRaw('card_id, COUNT(card_id) as total')
                            ->where('status', 'ready')
                            ->groupBy('card_id')
                            ->get();

        return view('pages.admin.dashboard-card', [
            'cards' => $cards,
            'totalCards' => $totalCards,
            'availableCards' => $availableCards,
        ]);
    }

    public function show($id)
    {
        $card = Card::with('card_status.guest')->findOrFail($id);
        return view('pages.admin.card-detail', compact('card'));
    }
}
