<?php

namespace App\Exports;

use App\Models\Appointment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportTicket implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $current_date = date("Y-m-d");

        $weekly_date = Appointment::select('start_date')->where('frequency','weekly')->get();
        $today_appointments = Appointment::whereIn('frequency',['daily','once','weekly'])
                                ->where('start_date', '<=', $current_date)
                                ->Where('end_date','>=', $current_date);
                                
        $today_appointment = $today_appointments->get();

        return view('exports.appointment', [
            'appointments' => $today_appointment->where('status','approved'),
        ]);
    }
}