<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
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
use App\ChatMessage;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ClientController extends Controller
{
    //Client page load
    public function getClientPageLoad()
    {
        return view('backend.client');
    }

    //Get data for Client
    public function getClientData(Request $request)
    {
        $search = $request->input('search');

        $data = DB::table('customers')
            ->select('customers.*')
            ->where(function ($query) use ($search) {
                $query->where('firstname', 'LIKE', '%' . $search . '%')
                    ->orWhere('lastname', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('mobile', 'LIKE', '%' . $search . '%')
                    ->orWhere('phone', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('customers.lastname')
            ->limit(100)
            ->get();

        return response()->json($data);
    }

    //Save data for Client
    public function saveClientData(Request $request)
    {
        $res = array();

        $id = $request->input('RecordId');
        $salutation = $request->input('salutation');
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $email = $request->input('email');
        $mobile = $request->input('mobile');
        $phone = $request->input('phone');
        $city = $request->input('city');
        $zipcode = $request->input('zipcode');
        $address1 = $request->input('address1');
        $createdt = Carbon::now();

        $validator_array = array(
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'mobile' => $request->input('mobile'),
            'email' => $request->input('email')
        );

        $validator = Validator::make($validator_array, [
            'firstname' => 'max:50',
            'lastname' => 'max:50',
            'mobile' => 'min:8',
            'email' => 'nullable|max:100'
        ]);

        $errors = $validator->errors();

        if ($errors->has('firstname')) {
            $res['msgType'] = 'error';
            $res['msg'] = $errors->first('firstname');
            return response()->json($res);
        }

        if ($errors->has('lastname')) {
            $res['msgType'] = 'error';
            $res['msg'] = $errors->first('lastname');
            return response()->json($res);
        }

        if ($errors->has('mobile')) {
            $res['msgType'] = 'error';
            $res['msg'] = $errors->first('mobile');
            return response()->json($res);
        }

        if ($errors->has('email')) {
            $res['msgType'] = 'error';
            $res['msg'] = $errors->first('email');
            return response()->json($res);
        }

        if (empty($id)) {
            $last_cust_id = DB::table('customers')->max('id');
            $last_cust_id++;

            $inserted = DB::table('customers')
                ->insert(["id" => $last_cust_id, "salutation" => $salutation, "firstname" => $firstname, "lastname" => $lastname, "email" => $email, "mobile" => $mobile, "phone" => $phone, "zipcode" => $zipcode, "city" => $city, "address1" => $address1, "createdt" => $createdt]);

            if ($inserted) {
								DB::table('refresh_flags')->update(["customers" => 1]);
                $res['msgType'] = 'success';
                $res['msg'] = __('New Data Added Successfully');
            } else {
                $res['msgType'] = 'error';
                $res['msg'] = __('Data insert failed');
            }
        } else {
            $updated = DB::table('customers')
                ->where("id", $id)
                ->update(["salutation" => $salutation, "firstname" => $firstname, "lastname" => $lastname, "email" => $email, "mobile" => $mobile, "phone" => $phone, "zipcode" => $zipcode, "city" => $city, "address1" => $address1, "lastupdate" => $createdt]);

            if ($updated) {
								DB::table('refresh_flags')->update(["customers" => 1]);
                $res['msgType'] = 'success';
                $res['msg'] = __('Data Updated Successfully');
            } else {
                $res['msgType'] = 'error';
                $res['msg'] = __('Data update failed');
            }
        }

        return response()->json($res);

    }

    //Get data for Client by id
    public function getClientById(Request $request)
    {

        $id = $request->id;

        $data = DB::table('customers')
            ->select('customers.*')
            ->where('customers.id', $id)->first();

        return response()->json($data);
    }

}
