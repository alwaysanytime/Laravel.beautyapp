<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class UserController extends Controller
{

	//Save data for user
    protected function StaffClientRegister(Request $request){
		$gtext = gtext();
		$mtext = mtext();

		$res = array();

		return

		$name = $request->input('name');
		$email = $request->input('email');
		$password = $request->input('password');
		$StaffClient = $request->input('StaffClient');
		$creation_date = Carbon::now();

		if($StaffClient == 3){
			$validator_array = array(
				'name' => $request->input('name'),
				'email' => $request->input('email'),
				'password' => $request->input('password'),
				'designation' => $request->input('designation')
			);
			$validator = Validator::make($validator_array, [
				'name' => ['required', 'string', 'max:255'],
				'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
				'password' => ['required', 'string'],
				'designation' => ['required', 'string']
			]);
		}else{
			$validator_array = array(
				'name' => $request->input('name'),
				'email' => $request->input('email'),
				'password' => $request->input('password'),
				'country' => $request->input('country_id')
			);
			$validator = Validator::make($validator_array, [
				'name' => ['required', 'string', 'max:255'],
				'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
				'password' => ['required', 'string'],
				'country' => ['required', 'string']
			]);
		}

		$errors = $validator->errors();

		$errorlist = array();
		if($errors->has('name')){
			$errorlist['name'] = $errors->first('name');
		}
		if($errors->has('email')){
			$errorlist['email'] = $errors->first('email');
		}
		if($errors->has('password')){
			$errorlist['password'] = $errors->first('password');
		}

		if($StaffClient == 3){
			$designation = $request->input('designation');
			$country_id = NULL;

			if($errors->has('designation')){
				$errorlist['designation'] = $errors->first('designation');
			}
		}else{
			$designation = NULL;
			$country_id = $request->input('country_id');

			if($errors->has('country')){
				$errorlist['country'] = $errors->first('country');
			}
		}
		/* Tepe */
		/*var_dump($errorlist); */

		$secretkey = $gtext['secretkey'];
		$recaptcha = $gtext['recaptcha'];
		if($recaptcha == 1){
			$captcha = $request->input('g-recaptcha-response');

			$ip = $_SERVER['REMOTE_ADDR'];
			$url = 'https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($secretkey).'&response='.urlencode($captcha).'&remoteip'.$ip;
			$response = file_get_contents($url);
			$responseKeys = json_decode($response, true);
			if($responseKeys["success"] == false) {
				$errorlist['captcha'] = __('The recaptcha field is required');
			}
		}

		if(count($errorlist)>0){
			$res['msgType'] = 'error';
			$res['msg'] = $errorlist;
			return response()->json($res);
		}

		$data = array(
			'name' => $name,
			'email' => $email,
			'password' => Hash::make($password),
			'designation' => $designation,
			'country_id' => $country_id,
/*TEPE			'active_id' => 2,
			'role_id' => $StaffClient,
			'bactive' => base64_encode($password),
			'creation_date' => $creation_date
*/
		);

		$response = User::create($data);
		if($response){

			if($gtext['isnotification'] == 1){
				require 'vendor/autoload.php';
				$mailAdmin = new PHPMailer(true);
				$mailStaff = new PHPMailer(true);

				if($StaffClient == 2){
					$SubjectAwaiting = $mtext['Subject - New client awaiting review'];
					$BodyAwaiting = $mtext['Body - New client awaiting review'];
				}else{
					$SubjectAwaiting = $mtext['Subject - New staff awaiting review'];
					$BodyAwaiting = $mtext['Body - New staff awaiting review'];
				}

				/*Get adminitrator mail*/
				$mailAdmin->setFrom($gtext['fromMailAddress'], $gtext['company_name']);
				$mailAdmin->addAddress($gtext['toMailAddress']);
				$mailAdmin->isHTML(true);
				$mailAdmin->CharSet = "utf-8";
				$mailAdmin->Subject = $SubjectAwaiting.' - '.$name;
				$mailAdmin->Body = "<table style='background-color:#f0f0f0;color:#444;padding:40px 0px;line-height:24px;font-size:16px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
										<tr>
											<td>
												<table style='background-color:#fff;max-width:600px;margin:0 auto;padding:30px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
													<tr><td style='font-size:30px;border-bottom:1px solid #ddd;padding-bottom:15px;font-weight:bold;text-align:center;'>".$gtext['company_name']."</td></tr>
													<tr><td style='padding-top:30px;'><strong>".$name."</strong> ".$BodyAwaiting."</td></tr>
													<tr><td style='padding-top:30px;padding-bottom:50px;'><a href='".$gtext['siteurl']."/login' target='_blank' style='background:".$gtext['theme_color'].";display:block;text-align:center;padding:10px;border-radius:3px;text-decoration:none;color:#fff;'>".__('Login')."</a></td></tr>
													<tr><td style='padding-top:10px;border-top:1px solid #ddd;'>Thank you!</td></tr>
													<tr><td style='padding-top:5px;'><strong>".$gtext['company_name']."</strong></td></tr>
												</table>
											</td>
										</tr>
									</table>";
				$mailAdmin->send();

				/*Get Staff mail*/
				$mailStaff->setFrom($gtext['fromMailAddress'], $gtext['company_name']);
				$mailStaff->addAddress($email, $name);
				$mailStaff->isHTML(true);
				$mailStaff->CharSet = "utf-8";
				$mailStaff->Subject = $mtext['Subject - Your account is pending review'].' - '.$name;
				$mailStaff->Body = "<table style='background-color:#f0f0f0;color:#444;padding:40px 0px;line-height:24px;font-size:16px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
										<tr>
											<td>
												<table style='background-color:#fff;max-width:600px;margin:0 auto;padding:30px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
													<tr><td style='font-size:30px;border-bottom:1px solid #ddd;padding-bottom:15px;font-weight:bold;text-align:center;'>".$gtext['company_name']."</td></tr>
													<tr><td style='font-size:20px;font-weight:bold;padding:30px 0px 5px 0px;'>Hi ".$name."</td></tr>
													<tr><td style='padding-bottom:50px;'>".$mtext['Body - Your account is pending review']."</td></tr>
													<tr><td style='padding-top:10px;border-top:1px solid #ddd;'>Thank you!</td></tr>
													<tr><td style='padding-top:5px;'><strong>".$gtext['company_name']."</strong></td></tr>
												</table>
											</td>
										</tr>
									</table>";
				$mailStaff->send();
			}

			$res['msgType'] = 'success';
			$res['msg'] = __('Thanks! You have signed up successfully. Please contact administrator.');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Oops! You are failed registration. Please reload the page and try again.');
		}

		return response()->json($res);
    }

	//Save data for user
    protected function resetPassword(Request $request){
		$gtext = gtext();

		$username = $request->input('username');

		if($username==''){
			return redirect()->back()->withErrors(['username' => __('The username field is required')]);
		}

		$secretkey = $gtext['secretkey'];
		$recaptcha = $gtext['recaptcha'];
		if($recaptcha == 1){
			$captcha = $request->input('g-recaptcha-response');

			$ip = $_SERVER['REMOTE_ADDR'];
			$url = 'https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($secretkey).'&response='.urlencode($captcha).'&remoteip'.$ip;
			$response = file_get_contents($url);
			$responseKeys = json_decode($response, true);
			if($responseKeys["success"] == false) {
				return redirect()->back()->withErrors(['email' => __('The recaptcha field is required')]);
			}
		}

		//You can add validation login here
		$user = DB::table('users')->where('email', '=', $email)->get();
		$userCount = $user->count();

		//Check if the user exists
		if($userCount < 1) {
			return redirect()->back()->withErrors(['email' => __('We can not find a user with that email address')]);
		}

		//Create Password Reset Token
		DB::table('password_resets')->insert([
			'email' => $email,
			'token' => Str::random(60),
			'created_at' => Carbon::now()
		]);

		$tokenData = DB::table('password_resets')->where('email', $email)->first();

		$sendResetEmail = self::sendResetEmail($email, $tokenData->token);

		if ($sendResetEmail == 1) {
			return redirect()->back()->with('status', __('We have emailed your password reset link!'));
		} else {
			return redirect()->back()->withErrors(['error' => __('Oops! You are failed change password request. Please try again')]);
		}
	}

	public function sendResetEmail($email, $token){
		$gtext = gtext();
		$mtext = mtext();

		//Retrieve the user from the database
		$UserObj = User::where('email', $email)->first();
		$user = $UserObj->toArray();

		//Generate the password reset link.
		$link = $gtext['siteurl'] . '/password/reset/' . $token . '?email=' . urlencode($user['email']);

		if($gtext['isnotification'] == 1){
			try {

				require 'vendor/autoload.php';
				$mail = new PHPMailer(true);

				/*Get mail*/
				$mail->setFrom($gtext['fromMailAddress'], $gtext['company_name']);
				$mail->addAddress($user['email'], $user['name']);
				$mail->isHTML(true);
				$mail->CharSet = "utf-8";
				$mail->Subject = $mtext['Subject - Forgot your password'].' - '.$user['name'];
				$mail->Body = "<table style='background-color:#f0f0f0;color:#444;padding:40px 0px;line-height:24px;font-size:16px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
								<tr>
									<td>
										<table style='background-color:#fff;max-width:600px;margin:0 auto;padding:30px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
											<tr><td style='font-size:30px;border-bottom:1px solid #ddd;padding-bottom:15px;font-weight:bold;text-align:center;'>".$gtext['company_name']."</td></tr>
											<tr><td style='font-size:20px;font-weight:bold;padding:30px 0px 5px 0px;'>Hi ".$user['name']."</td></tr>
											<tr><td>".$mtext['Body - Forgot your password']."</td></tr>
											<tr><td style='padding-top:30px;padding-bottom:50px;'><a href='".$link."' target='_blank' style='background:".$gtext['theme_color'].";display:block;text-align:center;padding:10px;border-radius:3px;text-decoration:none;color:#fff;'>".__('Forgot your password?')."</a></td></tr>
											<tr><td style='padding-top:10px;border-top:1px solid #ddd;'>Thank you!</td></tr>
											<tr><td style='padding-top:5px;'><strong>".$gtext['company_name']."</strong></td></tr>
										</table>
									</td>
								</tr>
							</table>";
				$mail->send();

				return 1;
			} catch (Exception $e) {
				return 0;
			}
		}
	}

	public function resetPasswordUpdate(Request $request){
		$gtext = gtext();

		$email = $request->input('email');
		$password = $request->input('password');
		$token = $request->input('token');

		//Validate input
		$validator = $request->validate([
			'email' => 'required|email|exists:users,email',
			'password' => 'required|confirmed',
			'token' => 'required',
		]);

		$secretkey = $gtext['secretkey'];
		$recaptcha = $gtext['recaptcha'];
		if($recaptcha == 1){
			$captcha = $request->input('g-recaptcha-response');

			$ip = $_SERVER['REMOTE_ADDR'];
			$url = 'https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($secretkey).'&response='.urlencode($captcha).'&remoteip'.$ip;
			$response = file_get_contents($url);
			$responseKeys = json_decode($response, true);
			if($responseKeys["success"] == false) {
				return redirect()->back()->withErrors(['email' => __('The recaptcha field is required')]);
			}
		}

		//Validate the token
		$tokenData = DB::table('password_resets')->where('token', $token)->get();
		$tokenCount = $tokenData->count();

		//Check the token is invalid
		if($tokenCount == 0) {
			return redirect()->back()->withErrors(['email' => __('This password reset token is invalid')]);
		}

		$tokenEmail = $tokenData[0]->email;
		$userData = User::where('email', $tokenEmail)->get();
		$userCount = $userData->count();

		//Redirect the user back if the email is invalid
		if ($userCount == 0){
			return redirect()->back()->withErrors(['email' => __('We can not find a user with that email address')]);
		}else{

			$data = array(
				'password' => Hash::make($password),
				'bactive' => base64_encode($password)
			);

			$response = User::where('email', $tokenEmail)->update($data);

			if($response){
				//Delete the token
				DB::table('password_resets')->where('email', $tokenEmail)->delete();

				return redirect()->back()->with('status', __('Your password changed successfully'));

			}else{
				return redirect()->back()->withErrors(['email' => __('Oops! You are failed change password. Please try again')]);
			}
		}
	}
}
