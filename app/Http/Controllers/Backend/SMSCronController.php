<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SMSCronController extends Controller
{

	public function SendSMS(Request $request){

		// Read Sender and Signature from settings		
		$query = "SELECT smssender, smssignature from settings";
		$data = DB::select(DB::raw($query));
		$smssender = $data[0]->smssender;
		$smssignature = $data[0]->smssignature;

		// Read SMS Text from message catalog		
		$query = "SELECT text from messages WHERE subject = 'SMSREMINDER'";
		$data = DB::select(DB::raw($query));
		$template = $data[0]->text;

		$query = "SELECT a.id, a.appointid, a.customerid, c.mobile, SUBSTR(TIME(starttime), 1, 5) as 'time', a.starttime, a.endtime, c.firstname, c.lastname
			FROM appointments a, customers c
			WHERE DATE(starttime) = DATE_ADD(date(SYSDATE()), INTERVAL 1 day)
			AND a.deleted = 0
			AND a.customerid <> 0
			AND a.smssent = 0
			AND a.customerid = c.id
			AND c.mobile>'' ";

		$data = DB::select(DB::raw($query));
				
		for($i=0; $i<count($data); $i++){
			echo $data[$i]->mobile . ", " . $data[$i]->starttime . ": ";
				
			$smstext = str_replace('%TIME%', $data[$i]->time, $template);
			$smstext = str_replace('%FIRSTNAME%', $data[$i]->firstname, $smstext);
			$smstext = str_replace('%LASTNAME', $data[$i]->lastname, $smstext);
			$smstext = str_replace('%SIGNATURE%', $smssignature, $smstext);
			
			$body = json_encode(['messages' => array(array(
				    	'body' => $smstext,
				    	'to' => $data[$i]->mobile,
				    	'from' => $smssender
			    		))
			    	]);
			echo $body;
			echo "<br>";

			$response = Http::withBasicAuth('info@tepeon.de', '6F2FD221-57C5-C4B5-3620-DF03AE59EEA1')
					->withOptions(["verify" => false])
					->withBody($body, 'application/json')
			 		->post('https://rest.clicksend.com/v3/sms/send');

			$status = json_decode($response);
			$success = $status->data->messages[0]->status == "SUCCESS";
			if($success) {
        $updated = DB::table('appointments')
            ->where("id", "=", $data[$i]->id)
            ->update(["smssent" => 1]);
			
			}
      $inserted = DB::table('sentsms')
          ->insert(["customerid" => $data[$i]->customerid, "sendtime" => now(), "text" => $smstext, "success" => $success]);

		}
		
	}
}