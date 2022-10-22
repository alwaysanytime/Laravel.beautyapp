<?php


namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function getCalendarPage()
    {
        $data = DB::table('appointments')
            ->join("appointtype", "appointments.appointtype", "=", "appointtype.id", "left outer")
            ->select('appointments.subject', "appointments.starttime", "appointments.endtime", "appointments.notes", "appointments.therapist", "appointments.price", "appointtype.color","appointtype.fontcolor")
            ->where('deleted', '=', '0')
            ->whereBetween("appointments.starttime",  [Carbon::now()->subDays(3),Carbon::now()->addMonths(4)])
            ->orderBy('appointments.starttime')
            ->get();


        return view("backend.calendar",compact("data"));

    }

    public function showAppointments()
    {
       //
    }
}
