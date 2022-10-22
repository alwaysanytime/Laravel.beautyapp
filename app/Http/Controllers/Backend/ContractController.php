<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Attachment;
use App\Comment;
use App\Task_staff_map;
use App\Task;
use App\Task_group;
use App\Project_staff_map;
use App\Payment;
use App\Project;
//use App\ChatMessage;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Http\Controllers\Backend\PdfController;


class ContractController extends Controller
{
    use PdfController;

    public function getContractPageLoad()
    {

        $data = DB::table('customers')
            ->select('customers.*')
            ->orderBy('customers.lastname')
            ->get();

        $therapist_data = DB::table('therapists')
            ->select("id", 'name')
            ->get();

        return view("backend.contract", compact("data", "therapist_data"));
    }

    public function getNewContract(Request $request)
    {

        $this->jsignaturetoPdf($_POST["signature"]);

        $saved_array = $this->saveNewContracttoDB($request);

        /****************KONTROL ET SIL DEVAM*****/
//        echo "<pre>";
//        return $request;
//        exit();
        /***************************+*/

        // PDF KAYIT ISLEMI BURADA, SONRA BUTON ILE GOSTERMEK ICIN DIGER SAYFAYA GEC
        $pdf_file = $this->createPdfTemplate($saved_array);

        $str = explode("/", $pdf_file);
        $pdf_file_local[0] = $str[sizeof($str) - 2];
        $pdf_file_local[1] = $str[sizeof($str) - 1];

        if ($saved_array[0]) {
            $msg["success"] = "Vereinbarung erfolgreich gespeichert.";
        } else {
            $msg["error"] = "Fehler beim Speichern";
        }

        return view("backend.contract-pdf", compact("msg", "pdf_file_local"));
    }

    public function saveNewContracttoDB($request)
    {

        $last_agr_id = DB::table('agreements')->max('agreementid'); // dbdeki son contract ID
        $last_agr_id++;

        $customer_fullname = DB::table('customers')
            ->select("id", 'firstname', "lastname", "birthdate", "address1", "zipcode", "city", "mobile", "phone", "email")
            ->where("id", $request->id)
            ->first();

        $contract_type = 5; // Laserhaarentfernung icin id. Daha sonra ayarlanabilir digerleride
        $type = DB::table('contracttype')
            ->select('descr')
            ->where("id", $contract_type)
            ->first();

        $i = 0;
        foreach ($request->service as $service) {
            $query = DB::table('areas')
                ->select('descr')
                ->where("id", (int)$service)
                ->first();

            $areas[$i] = json_decode(json_encode($query), true);

            $i++;
        }

        $input_date = $request->date;
        $date = strtotime($input_date);
        $input_date = date("d.m.Y", $date);
        $date_right = date('d.m.Y', $date);
        $date_db = date('Y.m.d', $date);
        

        $all_areas = "";
        $all_services_arr = array();
        foreach ($areas as $area) {
            $all_areas .= $area["descr"] . " ";
            array_push($all_services_arr, $area["descr"]);
        }

        if (isset($request->payment_method)) {
            if ($request->payment_method == "BAR") {
                $payment = ucfirst(strtolower($request->payment_method)) . "/ EC";
            } else {
                $payment = $request->payment_method;
            }
        }else{
            $payment = "Bar/ EC";
        }

        $price = floatval($request->price);

        if ($request->note == null) {
            $request->note = "";
        }

        $therapist = DB::table('therapists')
            ->select('name')
            ->where("id", (int)$request->therapist)
            ->first();

        $filename = $last_agr_id . ".pdf"; // BURASI ESKI DBYE GORE OYLESINE YAPILDI. SONRADAN GEREKIRSE GERCEK FILENAME YOLU ATANABILIR.

        try {
            $is_inserted = DB::table('agreements')
            ->insert(["customerid" => $request->id, "agreementid" => $last_agr_id, "agreedate" => $date_db, "category" => $type->descr, "areas" => $all_areas, "price" => $price,
                "payment" => $payment, "notes" => $request->note, "therapist" => $therapist->name /*"filename" => $filename*/]);
						// Set Update to automatically refresh Agreements
						DB::table('refresh_flags')->update(["agreements" => 1]);
        }catch (QueryException $e)
        {
             echo $e->getMessage();
        }
        
        $is_inserted = 1;
        $saved_array = [$is_inserted, $customer_fullname, $all_services_arr, $input_date, $last_agr_id, $payment, $price, $request->options, $therapist->name, $request->note];
        return $saved_array;

    }

}
