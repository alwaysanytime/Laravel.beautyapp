<?php


namespace App\Http\Controllers\Backend;

use Auth;
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
        $userId = Auth::id();
        
        $data_user = DB::table('users')
            ->select('id','name','username','role')
            ->where("id","=", $userId)
            ->get();

        $data_agr = DB::table('agreements')
            ->select("id","agreementid","agreedate","category","areas","notes as agrnotes","price as agrprice","therapist as agrtherapist","payment" ,"lastuser","lastupdate")
            ->where("agreementid","=", $agreement_id)
            ->orWhere("customerid","=",$customer_id)
            ->get();

        $data_app = DB::table('appointments')
            // ->join("users", "appointments.lastuser", "=", "users.id", "left outer")// KATEGORI RENKLERI
            ->select('id','appointid',"agreementid",'subject',"customerid", "starttime", "endtime", "notes", "therapist","treatments", "price", "paymethod", "paidamount","appointtype","sendsms","nopayment","deleted","deletiontype", "lastupdate","lastuser", /*"appointtype.Descr","appointtype.color","appointtype.fontcolor"*/)
            ->where("customerid","=", $customer_id)
            ->orderBy('starttime',"DESC")
            ->get();
        $data_users = DB::table('users')
            ->select('users.name','users.id')
            ->where("users.active","=", 1)
            ->orderBy('users.id',"ASC")
            ->get();
        $data_customer = DB::table('customers')
            ->select('firstname','lastname')
            ->where("id","=", $customer_id)
            ->get();
        $data_apptypes = DB::table('appointtype')
            ->select('id','Descr',"color","fontcolor",)
            ->where("active","=", 1)
            ->orderBy('id',"ASC")
            ->get();
        $data_deltypes = DB::table('deletiontype')
            ->select('id','Descr',)
            ->where("active","=", 1)
            ->orderBy('id',"ASC")
            ->get();
        $data_areas = DB::table('areas')
            ->select('descr',"groupid",)
            ->where("active","=", 1)
            ->orderBy('id',"ASC")
            ->get();
        return view("backend.calendar-details", compact(["data_user","data_app","data_agr","data_apptypes","data_areas","data_deltypes", "data_users","data_customer","customer_id"]));
    }
    public function updateAgreement(Request $request){

        $type = $request->type;
        if($type == "update") {
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
        } else if($type =="delete") {
            $id = $request->id;
            $deletiontype = $request->deletiontype;
            date_default_timezone_set("Europe/berlin");

            $data = array(
                "deleted" => 1, 
                "deletiontype" => $deletiontype,
                "lastupdate" => date("Y-m-d H:i:s"),
            );
        }

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
        
        $type = $request->type;
        if($type == "update") {
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
            $data = array(
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
            );

        } else if($type == "delete") {
            $id = $request->id;
            $notes = $request->notes;
            $deletiontype = $request->deletiontype;
            date_default_timezone_set("Europe/berlin");

            $data = array(
                "deleted" => 1, 
                "notes" => $notes,
                "deletiontype" => $deletiontype,
                "lastupdate" => date("Y-m-d H:i:s"),
            );
        }
        
        $updatedapp = DB::table('appointments')
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
    public function deleteAppointment(Request $request){
        $id = $request->id;
        $deletedapp = DB::table('appointments')
        ->where('id', intval($id))
        ->delete();
        if ($deletedapp) {
            $res['msgType'] = 'success';
            $res['msg'] = __('Data Removed Successfully');
        } else {
            $res['msgType'] = 'error';
            $res['msg'] = __('Data remove failed');
        }
        return response()->json($res);
    }
}
