<?php


namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardAppointmentController extends Controller
{
    public function getTableAppointments()
    {

        $data = DB::table('appointments')
            ->whereDate("starttime", "=", Carbon::today()->toDateString())
            ->where("deleted", "=", 0)
            ->orderBy('starttime')
            ->get();
            
        $cashAmount = DB::table('appointments')
            ->whereDate("starttime", "=", Carbon::today()->toDateString())
            ->where("deleted", "=", 0)
            ->where("customerid", ">", 0)
            ->where("paymethod", "=", 0)
            ->sum('paidamount');

        $cardAmount = DB::table('appointments')
            ->whereDate("starttime", "=", Carbon::today()->toDateString())
            ->where("deleted", "=", 0)
            ->where("customerid", ">", 0)
            ->where("paymethod", "=", 1)
            ->sum('paidamount');

        $paymentAmount = DB::table('payments')
            ->whereDate("date", "=", Carbon::today()->toDateString())
            ->sum('amount');

				$data->cashAmount = $cashAmount;
				$data->cardAmount = $cardAmount;
				$data->paymentAmount = $paymentAmount;
				
        return view("backend.dashboard-appointments", compact("data"));
    }
}
