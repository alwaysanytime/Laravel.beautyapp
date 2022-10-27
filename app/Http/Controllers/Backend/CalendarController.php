<?php


namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function getCalendarPage()
    {
        $data = DB::table('appointments')
            ->join("appointtype", "appointments.appointtype", "=", "appointtype.id", "left outer")
            ->select('appointments.subject',"appointments.agreementid","appointments.customerid", "appointments.starttime", "appointments.endtime", "appointments.notes", "appointments.therapist", "appointments.price", "appointtype.color","appointtype.fontcolor")
            ->whereBetween("appointments.starttime",  [Carbon::now()->subDays(3),Carbon::now()->addMonths(4)]) // Tek bir hafta goruntusu-> [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()]
            ->where("appointments.deleted","=", 0)
            ->orderBy('appointments.starttime')
            ->get();


        return view("backend.calendar",compact("data"));

    }

    public function showAppointmentsDetail(Request $request)
    {
        if(!is_null($request->agreement_id) || $request->agreement_id !== 0){
            $agreement_id = $request->agreement_id;
        }else{
            $agreement_id = 1;
        }
        $customer_id = $request->customer_id;

        $data_agr = DB::table('agreements')
            // ->join("users", "appointments.lastuser", "=", "users.id", "left outer")// KATEGORI RENKLERI
            ->select("agreements.id","agreements.agreementid","agreements.agreedate","agreements.category","agreements.areas","agreements.notes as agrnotes","agreements.price as agrprice","agreements.therapist as agrtherapist","agreements.payment" ,"agreements.lastuser","agreements.lastupdate")
            ->where("agreements.agreementid","=", $agreement_id)
            ->orWhere("agreements.customerid","=",$customer_id)
            ->get();

        $data_app = DB::table('appointments')
            // ->join("users", "appointments.lastuser", "=", "users.id", "left outer")// KATEGORI RENKLERI
            ->select('appointments.id','appointments.appointid',"appointments.agreementid",'appointments.subject',"appointments.customerid", "appointments.starttime", "appointments.endtime", "appointments.notes", "appointments.therapist","appointments.treatments", "appointments.price", "appointments.paymethod", "appointments.paidamount","appointments.appointtype","appointments.sendsms","appointments.nopayment", "appointments.lastupdate","appointments.lastuser", /*"appointtype.Descr","appointtype.color","appointtype.fontcolor"*/)
            ->where("appointments.customerid","=", $customer_id)
            ->orderBy('appointments.starttime',"DESC")
            ->get();
        $data_users = DB::table('users')
            ->select('users.name','users.id')
            ->where("users.active","=", 1)
            ->orderBy('users.id',"ASC")
            ->get();
        $data_apptypes = DB::table('appointtype')
            ->select('appointtype.id','appointtype.Descr',"appointtype.color","appointtype.fontcolor",)
            ->where("appointtype.active","=", 1)
            ->orderBy('appointtype.id',"ASC")
            ->get();
        $data_areas = DB::table('areas')
            ->select('areas.descr',"areas.groupid",)
            ->where("areas.active","=", 1)
            ->orderBy('areas.id',"ASC")
            ->get();
        return view("backend.calendar-details", compact(["data_app","data_agr","data_apptypes","data_areas","data_users","customer_id"]));
    }
    public function updateAgreement(Request $request){
        
        $id = $request->id;
        $agreementid = $request->agreementid;
        $agreedate = date_create($request->agreedate);
        $agreedate = date_format($agreedate,"Y-m-d");
        $category = $request->category;
        $areas = $request->areas;
        $therapist = $request->therapist;
        $notes = $request->notes;
        $price = $request->price;
        
        
        date_default_timezone_set("Europe/berlin");

        $data = array(
            "agreedate" => $agreedate, 
            "category" => $category,
            'areas' => $areas, 
            "therapist" => $therapist,
            "notes" => $notes,
            "price" => intval($price), 
            "lastupdate" => date("Y-m-d H:i:s"),
        );

        $updatedapp = DB::table('agreements')
        ->where('id', intval($id))
        ->update($data);
        if ($updatedapp) {
            $res['msgType'] = 'success';
            $res['msg'] = __('Data Updated Successfully');
        } else {
            $res['msgType'] = 'error';
            $res['msg'] = __('Data update failed');
        }
        return response()->json($res);
    }
    
    public function updateAppointment(Request $request){

        $id = $request->id;
        $appointmentId = $request->appointmentId;
        $agreementid = $request->agreementid;
        $starttime = date_create($request->starttime);
        $starttime = date_format($starttime,"Y-m-d H:i:s");
        $endtime = date_create($request->endtime);
        $endtime = date_format($endtime,"Y-m-d H:i:s");
        $therapist = $request->therapist;
        $textNote = $request->textNote;
        $treatments = $request->treatments;
        $inputTotalamount = $request->inputTotalamount;
        $inputPaidamount = $request->inputPaidamount;
        $paymethod = $request->paymethod;
        $sendsms = $request->sendsms;
        $nopayment = $request->nopayment;
        
        $apptype = $request->apptype;
        
        date_default_timezone_set("Europe/berlin");

        // $updatedapp = DB::table('agreements')
        // ->where('agreementid', intval($agreementid))
        // ->update([ 
        //             "notes" => $textNote,
        //             "treatments" => $treatments,
        //             "price" => intval($inputTotalamount), 
        //             "paidamount" => intval($inputPaidamount),
        //             "lastupdate" => date("Y-m-d H:i:s"),
        //         ]);
        
        $updatedapp = DB::table('appointments')
        ->where('id', intval($id))
        ->update([ 
                    "agreementid" => intval($agreementid),
                    "starttime" => $starttime, 
                    "endtime" => $endtime,
                    'therapist' => $therapist, 
                    "notes" => $textNote,
                    "treatments" => $treatments,
                    "price" => intval($inputTotalamount), 
                    "paidamount" => intval($inputPaidamount),
                    "paymethod" => intval($paymethod),
                    "sendsms" => intval($sendsms),
                    "nopayment" => intval($nopayment),
                    "lastupdate" => date("Y-m-d H:i:s"),
                ]);
        

        if ($updatedapp) {
            $res['msgType'] = 'success';
            $res['msg'] = __('Data Updated Successfully');
        } else {
            $res['msgType'] = 'error';
            $res['msg'] = __('Data update failed');
        }
        return response()->json($res);
    }
}
