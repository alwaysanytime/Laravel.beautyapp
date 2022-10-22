<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Attachment;
use App\Comment;
use App\Project_staff_map;
use App\Task_staff_map;
use App\ChatMessage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class StaffController extends Controller
{
    //Staff page load
    public function getStaffPageLoad(){
        return view('backend.staff');
    }
	
	//Get data for Staff
    public function getStaffData(Request $request){
		$search = $request->input('search');

			$data = User::select("*")
				->whereNotIn('users.role', [2])
				->where(function($query) use ($search){
					$query->where('name', 'LIKE', '%'.$search.'%')
						->orWhere('email', 'LIKE', '%'.$search.'%')
						->orWhere('phone', 'LIKE', '%'.$search.'%');
				})
				->orderBy('id', 'DESC')
				->get();

			return response()->json($data);
	}
	
	//Save data for Staff
    public function saveStaffData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$name = $request->input('name');
		$email = $request->input('email');
		$password = $request->input('password');
		$designation = $request->input('designation');
		$phone = $request->input('phone');
		$skype_id = $request->input('skype_id');
		$facebook_id = $request->input('facebook_id');
		$address = $request->input('address');
/*		$active_id = $request->input('active_id');
		$role = $request->input('role');
		$photo = $request->input('photo');
		$creation_date = Carbon::now();
*/		
		
		$validator_array = array(
			'name' => $request->input('name'),
			'email' => $request->input('email'),
			'password' => $request->input('password'),
			'designation' => $request->input('designation')
		);
		$rId = $id == '' ? '' : ','.$id;
		$validator = Validator::make($validator_array, [
			'name' => 'required|max:191',
			'email' => 'required|max:191|unique:users,email' . $rId,
			'password' => 'required|max:191',
			'designation' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('name');
			return response()->json($res);
		}
		
		if($errors->has('email')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('email');
			return response()->json($res);
		}
		
		if($errors->has('password')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('password');
			return response()->json($res);
		}
		
		if($errors->has('designation')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('designation');
			return response()->json($res);
		}

		$data = array(
			'name' => $name,
			'email' => $email,
			'password' => Hash::make($password),
			'designation' => $designation,
			'phone' => $phone,
			'skype_id' => $skype_id,
			'facebook_id' => $facebook_id,
			'address' => $address,
			'active_id' => $active_id,
			'role' => $role,
			'photo' => $photo,
			'bactive' => base64_encode($password),
			'creation_date' => $creation_date
		);

		if($id ==''){
			$response = User::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = User::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}
		
		return response()->json($res);
    }
	
	//Get data for Staff by id
    public function getStaffById(Request $request){

		$id = $request->id;
		
		$data = DB::table('users')
				->where('users.id', $id)->first();
					
		$data->bactive = base64_decode($data->bactive);

		return response()->json($data);
	}
	
	//Delete data for Staff
	public function deleteStaff(Request $request){
		$gtext = gtext();
		$mtext = mtext();
		$res = array();

		$id = $request->id;
		
		$StaffObj = User::where('id', $id)->first();
		$StaffArr = $StaffObj->toArray();
					
		Attachment::where('staff_id', $id)->delete();
		Comment::where('staff_id', $id)->delete();
		Project_staff_map::where('staff_id', $id)->delete();
		Task_staff_map::where('staff_id', $id)->delete();
		ChatMessage::where('user_id', $id)->delete();
		ChatMessage::where('me_id', $id)->delete();
		DB::table('chat_login_status')->where('user_id', '=', $id)->delete();
		
		$response = User::where('id', $id)->delete();	
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Removed Successfully');
			
			if($gtext['isnotification'] == 1){
				
				require 'vendor/autoload.php';
				$mail = new PHPMailer(true);

				//Send mail
				$mail->setFrom($gtext['fromMailAddress'], $gtext['company_name']);
				$mail->addAddress($StaffArr['email'], $StaffArr['name']);
				$mail->isHTML(true);
				$mail->CharSet = "utf-8";
				$mail->Subject = $mtext['Subject - Your account has been deleted'].' - '.$StaffArr['name'];
				$mail->Body = "<table style='background-color:#f0f0f0;color:#444;padding:40px 0px;line-height:24px;font-size:16px;' border='0' cellpadding='0' cellspacing='0' width='100%'>	
									<tr>
										<td>
											<table style='background-color:#fff;max-width:600px;margin:0 auto;padding:30px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
												<tr><td style='font-size:30px;border-bottom:1px solid #ddd;padding-bottom:15px;font-weight:bold;text-align:center;'>".$gtext['company_name']."</td></tr>
												<tr><td style='font-size:20px;font-weight:bold;padding:30px 0px 5px 0px;'>Hi ".$StaffArr['name']."</td></tr>
												<tr><td style='padding-top:5px;padding-bottom:50px;'>".$mtext['Body - Your account has been deleted']."</td></tr>
												<tr><td style='padding-top:10px;border-top:1px solid #ddd;'>Thank you!</td></tr>
												<tr><td style='padding-top:5px;'><strong>".$gtext['company_name']."</strong></td></tr>
											</table>
										</td>
									</tr>
								</table>";
				$mail->send();
			}
			
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data remove failed');
		}
		
		return response()->json($res);
	}
}
