<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \Firebase\JWT\JWT;
use App\Meeting_invitation;
use App\User;
use App\ZoomSetting;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ZoomMeetingController extends Controller
{
	
	public function generateJWTKey() {
		$zoomSetting = szoom();

		$key = $zoomSetting['zoom_api_key'];
		$secret = $zoomSetting['zoom_api_secret'];
		$token = array(
			"iss" => $key,
			"exp" => time() + 3600
		);
		
		return JWT::encode( $token, $secret );
	}
	
    //Upcoming Meeting page load
    public function getUpcomingMeetingData(){
        return view('backend.upcoming-meeting');
    }
	
    //Live Meeting page load
    public function getLiveMeetingData(){
        return view('backend.live-meeting');
    }
	
    //previous Meeting page load
    public function getPreviousMeetingData(){
        return view('backend.previous-meeting');
    }
	
    //zoom settings page load
    public function getZoomSettingsData(){
        return view('backend.zoom-settings');
    }
	
    //Upcoming Meeting Data load
    public function getUpcomingMeetingDataLoad(Request $request){
		$zoomSetting = szoom();

		$Length = $request->input('length');
		$pagenumber = $request->input('params');
		$page_number = $pagenumber['page_number'];

		$page = $page_number+1;
		$params = 'meetings?page_size='.$Length.'&type=upcoming&page_number='.$page; 

		$curl = curl_init();

		curl_setopt_array($curl, array(

		  CURLOPT_URL => $zoomSetting['apiurl']."/users/me/".$params,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
			"authorization: Bearer ". self::generateJWTKey(),
			"content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return $err;
		} else {
			$data_list = json_decode($response);
			$resultdata = array();
			$recordsTotal = isset($data_list->total_records) ? $data_list->total_records : 0;
			if($recordsTotal>0){
				$DataList = $data_list->meetings;
				$i = 0;
				foreach ($DataList as $key => $row) {
					
					$timezone = $row->timezone;
					date_default_timezone_set($timezone);
					
					$start_datetime = strtotime($row->start_time);
					$start_time = date("d M Y h:i:s A", $start_datetime);

					$resultdata[$i] = array(
						'topic' => esc($row->topic),
						'start_time' => $start_time,
						'timezone' => $timezone,
						'id' => $row->id,
						'duration' => $row->duration,
						'join_url' => $row->join_url
					 );
					
					$i++;
				}
			}
		}
		
		$data['params']['datareturnformat']['recordsTotal'] = $recordsTotal;
		$data['params']['datareturnformat']['recordsFiltered'] = $recordsTotal;
		$data['params']['datareturnformat']['data'] = $resultdata;
		return json_encode($data['params']['datareturnformat']);
    }
	
    //Live Meeting Data load
    public function getLiveMeetingDataLoad(Request $request){
		$zoomSetting = szoom();

		$Length = $request->input('length');
		$pagenumber = $request->input('params');
		$page_number = $pagenumber['page_number'];

		$page = $page_number+1;
		$params = 'meetings?page_size='.$Length.'&type=live&page_number='.$page; 

		$curl = curl_init();

		curl_setopt_array($curl, array(

		  CURLOPT_URL => $zoomSetting['apiurl']."/users/me/".$params,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
			"authorization: Bearer ". self::generateJWTKey(),
			"content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return $err;
		} else {
			$data_list = json_decode($response);
			$resultdata = array();
			$recordsTotal = isset($data_list->total_records) ? $data_list->total_records : 0;
			if($recordsTotal>0){
				$DataList = $data_list->meetings;
				$i = 0;
				foreach ($DataList as $key => $row) {
					
					$timezone = $row->timezone;
					date_default_timezone_set($timezone);
					
					$start_datetime = strtotime($row->start_time);
					$start_time = date("d M Y h:i:s A", $start_datetime);

					$resultdata[$i] = array(
						'topic' => esc($row->topic),
						'start_time' => $start_time,
						'timezone' => $timezone,
						'id' => $row->id,
						'duration' => $row->duration,
						'join_url' => $row->join_url
					 );
					
					$i++;
				}
			}
		}
		
		$data['params']['datareturnformat']['recordsTotal'] = $recordsTotal;
		$data['params']['datareturnformat']['recordsFiltered'] = $recordsTotal;
		$data['params']['datareturnformat']['data'] = $resultdata;
		return json_encode($data['params']['datareturnformat']);
    }
	
    //Previous Meeting Data load
    public function getPreviousMeetingDataLoad(Request $request){
		$zoomSetting = szoom();

		$Length = $request->input('length');
		$pagenumber = $request->input('params');
		$page_number = $pagenumber['page_number'];

		$page = $page_number+1;
		$params = 'meetings?page_size='.$Length.'&type=scheduled&page_number='.$page; 

		$curl = curl_init();

		curl_setopt_array($curl, array(

		  CURLOPT_URL => $zoomSetting['apiurl']."/users/me/".$params,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
			"authorization: Bearer ". self::generateJWTKey(),
			"content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return $err;
		} else {
			$data_list = json_decode($response);
			$resultdata = array();
			$recordsTotal = isset($data_list->total_records) ? $data_list->total_records : 0;
			if($recordsTotal>0){
				$DataList = $data_list->meetings;
				$i = 0;
				foreach ($DataList as $key => $row) {
					
					$timezone = $row->timezone;
					date_default_timezone_set($timezone);
					
					$start_datetime = strtotime($row->start_time);
					$start_time = date("d M Y h:i:s A", $start_datetime);

					$resultdata[$i] = array(
						'topic' => esc($row->topic),
						'start_time' => $start_time,
						'timezone' => $timezone,
						'id' => $row->id,
						'duration' => $row->duration,
						'join_url' => $row->join_url
					 );
					
					$i++;
				}
			}
		}
		
		$data['params']['datareturnformat']['recordsTotal'] = $recordsTotal;
		$data['params']['datareturnformat']['recordsFiltered'] = $recordsTotal;
		$data['params']['datareturnformat']['data'] = $resultdata;
		return json_encode($data['params']['datareturnformat']);
    }
	
	//Create Meeting
    public function CreateMeeting(Request $request) {
		$res = array();
		$zoomSetting = szoom();

		$MeetingId = $request->input('MeetingId');
		$meeting_topic = $request->input('meeting_topic');
		$meeting_date = $request->input('meeting_date');
		$meeting_duration = $request->input('meeting_duration');
		$timezone = $request->input('timezone_id');
		
		if ($request->input('host_video') == 'true' || $request->input('host_video') == 'on') {
			$host_video = $request->input('host_video');
		}else {
			$host_video = '';
		}
		
		if ($request->input('participant_video') == 'true' || $request->input('participant_video') == 'on') {
			$participant_video = $request->input('participant_video');
		}else {
			$participant_video = '';
		}
		
		if ($request->input('enable_join_before_host') == 'true' || $request->input('enable_join_before_host') == 'on') {
			$enable_join_before_host = $request->input('enable_join_before_host');
		}else {
			$enable_join_before_host = '';
		}
		
		if ($request->input('mute_participants_upon_entry') == 'true' || $request->input('mute_participants_upon_entry') == 'on') {
			$mute_participants_upon_entry = $request->input('mute_participants_upon_entry');
		}else {
			$mute_participants_upon_entry = '';
		}

		$createAMeetingArray = array();
		$createAMeetingArray['topic'] = $meeting_topic;
		$createAMeetingArray['type'] = 2; //Scheduled
		$createAMeetingArray['start_time'] = $meeting_date;
		$createAMeetingArray['timezone'] = $timezone;
		$createAMeetingArray['duration'] = $meeting_duration;
		$createAMeetingArray['settings'] = array(
			'join_before_host' => ! empty($enable_join_before_host) ? true : false,
			'host_video'  => ! empty($host_video) ? true : false,
			'participant_video' => ! empty($participant_video) ? true : false,
			'mute_upon_entry' => ! empty($mute_participants_upon_entry) ? true : false,
			'enforce_login' => false,
			'auto_recording' => "none",
			'alternative_hosts' => ""
		);
		
		if($MeetingId ==''){
			$request_url = $zoomSetting['apiurl'].'/users/me/meetings';
			$headers = array(
				"authorization: Bearer ".self::generateJWTKey(),
				'content-type: application/json'
			);

			$postFields = json_encode($createAMeetingArray);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_URL, $request_url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
			$response = curl_exec($ch);
			$err = curl_error($ch);
			curl_close($ch);
			
			if(!$response){
				$res['msgType'] = 'error';
				$res['msg'] = __('New meeting creation failed');
			}else{
				$datalist = json_decode($response);
				if($datalist->id !=''){
					$res['msgType'] = 'success';
					$res['msg'] = __('New meeting created successfully');
				}
			}
			
			return response()->json($res);
			
		}else{
		
			$request_url = $zoomSetting['apiurl'].'/meetings/'.$MeetingId;
			$headers = array(
				"authorization: Bearer ".self::generateJWTKey(),
				'content-type: application/json'
			);

			$postFields = json_encode($createAMeetingArray);
			
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $request_url,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "PATCH",
			  CURLOPT_POSTFIELDS => $postFields,
			  CURLOPT_HTTPHEADER => $headers,
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);
			
			if ($err) {
				$res['msgType'] = 'error';
				$res['msg'] = __('Meeting update failed');
			}else{
				$res['msgType'] = 'success';
				$res['msg'] = __('Meeting updated successfully');
			}
			
			return response()->json($res);
		}
    }
	
	//get Meeting Details
	public function getMeetingDetails(Request $request) {
		$res = array();
		$zoomSetting = szoom();
		
		$MeetingId = $request->input('MeetingId');

		$curl = curl_init();

		curl_setopt_array($curl, array(

		  CURLOPT_URL => $zoomSetting['apiurl']."/meetings/".$MeetingId,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
			"authorization: Bearer ". self::generateJWTKey(),
			"content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return $err;
		} else {
			
			$data_list = json_decode($response);
			$dataList = $data_list->settings;
			
			$resultdata = array();
			
			$timezone = $data_list->timezone;
			date_default_timezone_set($timezone);
			$MeetingDateTime = isset($data_list->start_time) ? $data_list->start_time : $data_list->created_at;
			$start_datetime = strtotime($MeetingDateTime);
			$InvitationTime = date("d M Y h:i:s A", $start_datetime);
			$start_time = date("Y-m-d\TH:i:s", $start_datetime);

			$resultdata['MeetingId'] = $data_list->id;
			$resultdata['topic'] = $data_list->topic;
			$resultdata['InvitationTime'] = $InvitationTime;
			$resultdata['start_time'] = $start_time;
			$resultdata['duration'] = isset($data_list->duration) ? $data_list->duration : '';
			$resultdata['timezone'] = $data_list->timezone;
			$resultdata['join_url'] = $data_list->join_url;
			$resultdata['password'] = $data_list->password;
			$resultdata['host_video'] = $dataList->host_video;
			$resultdata['participant_video'] = $dataList->participant_video;
			$resultdata['join_before_host'] = $dataList->join_before_host;
			$resultdata['mute_upon_entry'] = $dataList->mute_upon_entry;

			return response()->json($resultdata);
		}
    }	
	
	//delete Meeting
    public function deleteMeeting(Request $request) {
		$res = array();
		$zoomSetting = szoom();
		
		$MeetingId = $request->input('MeetingId');

		$curl = curl_init();

		curl_setopt_array($curl, array(

		  CURLOPT_URL => $zoomSetting['apiurl']."/meetings/".$MeetingId,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "DELETE",
		  CURLOPT_HTTPHEADER => array(
			"authorization: Bearer ". self::generateJWTKey(),
			"content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return $err;
		} else {
			
			Meeting_invitation::where('meeting_id', $MeetingId)->delete();

			$res['msgType'] = 'success';
			$res['msg'] = __('Meeting deleted successfully');

			return response()->json($res);
		}
    }
	
	//get Staff Client List
    public function getStaffClientList(Request $request){
		
		$meeting_id = $request->input('meeting_id');
		
		$Data = DB::table('users')
				->select('users.id', 'users.name')
				->whereNotIn('users.id', function($query) use ($request){
					$query->select('meeting_invitations.staff_id')
					->from('meeting_invitations')
					->where('meeting_invitations.meeting_id', $request->meeting_id);
				})
				->orderBy('name', 'asc')
				->get();
		
		return $Data;
	}
	
	//get Meeting Invitation Staff
    public function getMeetingInvitationStaff(Request $request){
		$meeting_id = $request->input('meeting_id');
		
		$data = DB::table('users')
				->join('meeting_invitations', 'users.id', '=', 'meeting_invitations.staff_id')
				->select('users.name', 'users.photo', 'meeting_invitations.id', 'meeting_invitations.meeting_id', 'meeting_invitations.staff_id')
				->where('meeting_invitations.meeting_id', $meeting_id)
				->orderBy('meeting_invitations.id', 'DESC')
				->get();
				
		return response()->json($data);
	}
	
	//insert Meeting Invitation Data
    public function insertMeetingInvitationData(Request $request){
		$gtext = gtext();
		$mtext = mtext();
		$res = array();

		$staff_id = $request->input('StaffClient_id');
		$meeting_id = $request->input('meeting_id');
		$Invitation_Meeting_Topic = $request->input('Invitation_Meeting_Topic');
		$Invitation_Time = $request->input('Invitation_Time');
		$Invitation_Timezone = $request->input('Invitation_Timezone');
		$Invitation_join_url = $request->input('Invitation_join_url');
		$Invitation_password = $request->input('Invitation_password');

		$validator_array = array(
			'Staff_Client' => $request->input('StaffClient_id')
		);
		$validator = Validator::make($validator_array, [
			'Staff_Client' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('Staff_Client')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('Staff_Client');
			return response()->json($res);
		}
		
		$data = array(
			'staff_id' => $staff_id,
			'meeting_id' => $meeting_id
		);

		$meeting_invitation_id = Meeting_invitation::create($data)->id;

		if($meeting_invitation_id !=''){
			
			if($gtext['isnotification'] == 1){

				require 'vendor/autoload.php';
				$mail = new PHPMailer(true);
				
				$StaffObj = User::where('id', $staff_id)->first();
				$StaffArr = $StaffObj->toArray();
				
				//Send mail
				$mail->setFrom($gtext['fromMailAddress'], $gtext['company_name']);
				$mail->addAddress($StaffArr['email']);
				$mail->isHTML(true);
				$mail->CharSet = "utf-8";
				$mail->Subject = $gtext['company_name'].' is inviting you to a scheduled Zoom meeting.';
				$mail->Body = "<table style='background-color:#f0f0f0;color:#444;padding:40px 0px;line-height:24px;font-size:16px;' border='0' cellpadding='0' cellspacing='0' width='100%'>	
						<tr>
							<td>
								<table style='background-color:#fff;max-width:600px;margin:0 auto;padding:30px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
									<tr><td style='font-size:30px;border-bottom:1px solid #ddd;padding-bottom:15px;font-weight:bold;text-align:center;'>".$gtext['company_name']."</td></tr>
									<tr><td style='padding-top:30px;'>Meeting Topic: ".$Invitation_Meeting_Topic."</td></tr>
									<tr><td>Time: ".$Invitation_Time." ".$Invitation_Timezone."</td></tr>
									<tr><td>Join Zoom Meeting: <a href='".$Invitation_join_url."'>".$Invitation_join_url."</a></td></tr>
									<tr><td>Meeting ID: ".$meeting_id."</td></tr>
									<tr><td style='padding-bottom:50px;'>Passcode: ".$Invitation_password."</td></tr>
									<tr><td style='padding-top:10px;border-top:1px solid #ddd;'>Thank you!</td></tr>
									<tr><td style='padding-top:5px;'><strong>".$gtext['company_name']."</strong></td></tr>
								</table>
							</td>
						</tr>
					</table>";
				$mail->send();
			}
			
			$res['msgType'] = 'success';
			$res['msg'] = __('New Data Added Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data insert failed');
		}
		
		return response()->json($res);
    }
	
	//delete Meeting Invitation
	public function deleteMeetingInvitation(Request $request){
		
		$res = array();

		$id = $request->meeting_invitation_id;
		
		if($id != 0){
			$response = Meeting_invitation::where('id', $id)->delete();	
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Removed Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data remove failed');
			}
		}
		
		return response()->json($res);
	}
	
	//Save data for Zoom Settings
    public function SaveZoomSettings(Request $request){
		$res = array();
		
		$id = $request->input('id');
		$zoom_api_key = $request->input('zoom_api_key');
		$zoom_api_secret = $request->input('zoom_api_secret');
		$apiurl = 'https://api.zoom.us/v2';
		
		$validator_array = array(
			'zoom_api_key' => $request->input('zoom_api_key'),
			'zoom_api_secret' => $request->input('zoom_api_secret')
		);

		$validator = Validator::make($validator_array, [
			'zoom_api_key' => 'required',
			'zoom_api_secret' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('zoom_api_key')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('zoom_api_key');
			return response()->json($res);
		}
		
		if($errors->has('zoom_api_secret')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('zoom_api_secret');
			return response()->json($res);
		}

		$data = array(
			'apiurl' => $apiurl,
			'zoom_api_key' => $zoom_api_key,
			'zoom_api_secret' => $zoom_api_secret
		);

		if($id ==''){
			$response = ZoomSetting::create($data)->id;
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
				$res['id'] = $response;
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
				$res['id'] = '';
			}
		}else{
			$response = ZoomSetting::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
				$res['id'] = $id;
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
				$res['id'] = '';
			}
		}
		
		return response()->json($res);
    }
}
