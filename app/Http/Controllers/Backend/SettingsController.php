<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;
use App\Payment_setting;
use App\Mailtext;
use App\Pcode;
use App\Vpcode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    //Settings page load
    public function getSettingsData(){
        return view('backend.settings');
    }
	
	//Get data for Global Setting Data
    public function getGlobalSettingData(Request $request){
		$data = Setting::all();
		return response()->json($data);
	}

	//Save data for global Setting
    public function globalSettingUpdate(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$company_name = $request->input('company_name');
		$company_title = $request->input('company_title');
		$siteurl = $request->input('siteurl');
		$timezone_id = $request->input('timezone_id');
		$theme_color = $request->input('theme_color');
		$favicon = $request->input('favicon');
		$logo = $request->input('logo');

		$validator_array = array(
			'company_name' => $request->input('company_name'),
			'company_title' => $request->input('company_title'),
			'siteurl' => $request->input('siteurl'),
			'timezone_id' => $request->input('timezone_id'),
			'theme_color' => $request->input('theme_color'),
			'favicon' => $request->input('favicon'),
			'logo' => $request->input('logo')
		);

		$validator = Validator::make($validator_array, [
			'company_name' => 'required',
			'company_title' => 'required',
			'siteurl' => 'required',
			'timezone_id' => 'required',
			'theme_color' => 'required',
			'favicon' => 'required',
			'logo' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('company_name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('company_name');
			return response()->json($res);
		}
		
		if($errors->has('company_title')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('company_title');
			return response()->json($res);
		}
		
		if($errors->has('siteurl')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('siteurl');
			return response()->json($res);
		}
		
		if($errors->has('timezone_id')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('timezone_id');
			return response()->json($res);
		}
		
		if($errors->has('theme_color')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('theme_color');
			return response()->json($res);
		}
		
		if($errors->has('favicon')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('favicon');
			return response()->json($res);
		}
		
		if($errors->has('logo')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('logo');
			return response()->json($res);
		}

		$data = array(
			'company_name' => $company_name,
			'company_title' => $company_title,
			'siteurl' => $siteurl,
			'timezone_id' => $timezone_id,
			'theme_color' => $theme_color,
			'favicon' => $favicon,
			'logo' => $logo
		);

		$response = Setting::where('id', $id)->update($data);
		if($response){
			
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}

		return response()->json($res);
    }

	//Save data for Google Recaptcha
    public function GoogleRecaptchaUpdate(Request $request){
		$res = array();
		
		$id = $request->input('setting_id');
		$sitekey = $request->input('sitekey');
		$secretkey = $request->input('secretkey');
		$g_recaptcha = $request->input('recaptcha');
		
		if ($g_recaptcha == 'true' || $g_recaptcha == 'on') {
			$recaptcha = 1;
		}else {
			$recaptcha = 0;
		}
		
		$validator_array = array(
			'sitekey' => $request->input('sitekey'),
			'secretkey' => $request->input('secretkey')
		);

		$validator = Validator::make($validator_array, [
			'sitekey' => 'required',
			'secretkey' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('sitekey')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('sitekey');
			return response()->json($res);
		}
		if($errors->has('secretkey')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('secretkey');
			return response()->json($res);
		}

		$data = array(
			'sitekey' => $sitekey,
			'secretkey' => $secretkey,
			'recaptcha' => $recaptcha
		);

		$response = Setting::where('id', $id)->update($data);
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}

		return response()->json($res);
    }

	//Get data for Mail Setting
    public function getMailSettingData(Request $request){
		$data = Mailtext::all();
		return response()->json($data);
	}	
	
	//Save data for Mail Setting
    public function MailSettingUpdate(Request $request){
		$res = array();
		
		$id = $request->input('setting_id');
		$email = $request->input('email');
		$tomailaddress = $request->input('tomailaddress');
		$mailsubject = $request->input('mailsubject');
		$mailbody = $request->input('mailbody');
		
		$m_isnotification = $request->input('isnotification');
		
		if ($m_isnotification == 'true' || $m_isnotification == 'on') {
			$isnotification = 1;
		}else {
			$isnotification = 0;
		}
		
		$validator_array = array(
			'email' => $request->input('email'),
			'tomailaddress' => $request->input('tomailaddress')
		);

		$validator = Validator::make($validator_array, [
			'email' => 'required',
			'tomailaddress' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('email')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('email');
			return response()->json($res);
		}
		if($errors->has('tomailaddress')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('tomailaddress');
			return response()->json($res);
		}

		$data = array(
			'isnotification' => $isnotification,
			'email' => $email,
			'tomailaddress' => $tomailaddress
		);

		$response = Setting::where('id', $id)->update($data);
		if($response){
			
			foreach ($mailsubject as $key => $value) {
				$subject_value = $mailsubject[$key];
				$body_value = $mailbody[$key];
				
				$data_arr = array(
					'subject_value' => $subject_value,
					'body_value' => $body_value
				);
				
				Mailtext::where('id', $key)->update($data_arr);
			}
			
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}

		return response()->json($res);
    }	
	
	//Save data for Stripe Setting
    public function StripeUpdate(Request $request){
		$res = array();
		
		$id = $request->input('stripe_id');
		$stripe_key = $request->input('stripe_key');
		$stripe_secret = $request->input('stripe_secret');
		$payment_method = 'Stripe';
		$is_enable = $request->input('isenable');
		
		if ($is_enable == 'true' || $is_enable == 'on') {
			$isenable = 1;
		}else {
			$isenable = 0;
		}

		$data = array(
			'publickey' => $stripe_key,
			'secretkey' => $stripe_secret,
			'payment_method' => $payment_method,
			'isenable' => $isenable
		);
		
		if($id ==''){
			$lastinsert_id = Payment_setting::create($data)->id;
			if($lastinsert_id !=''){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
				$res['stripe_id'] = $lastinsert_id;
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
				$res['stripe_id'] = '';
			}
		}else{
			$response = Payment_setting::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
				$res['stripe_id'] = $id;
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
				$res['stripe_id'] = '';
			}
		}

		return response()->json($res);
    }
	
	//Save data for Purchase Code Setting
    public function PurchaseCodeUpdate(Request $request){
		$res = array();
		
		$id = $request->input('pcode_id');
		$pcode = $request->input('pcode');
		
		$validator_array = array(
			'PurchaseCode' => $request->input('pcode')
		);

		$validator = Validator::make($validator_array, [
			'PurchaseCode' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('PurchaseCode')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('PurchaseCode');
			return response()->json($res);
		}
		
		$purchase_code = htmlspecialchars($pcode);
		$verifyRes = verifyPurchase($purchase_code);

		if($verifyRes == 0){
			Vpcode::truncate();
			Vpcode::create(array('bactive' => 0,'resetkey' => 0));
			$res['msgType'] = 'error';
			$res['msg'] = __('Sorry, This is not a valid purchase code.');
			return response()->json($res);
		}
		
		$data = array(
			'pcode' => $pcode
		);

		if($id ==''){
			Pcode::truncate();
			$res_id = Pcode::create($data)->id;
			if($res_id !=''){
				Vpcode::truncate();
				Vpcode::create(array('bactive' => 1,'resetkey' => 5));
				$res['msgType'] = 'success';
				$res['msg'] = __('Theme registered Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Pcode::where('id', $id)->update($data);
			if($response){
				Vpcode::truncate();
				Vpcode::create(array('bactive' => 1,'resetkey' => 5));
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }
	
	//Get data for Pcode Data
    public function getPcodeData(Request $request){
		$data = Pcode::all();
		return response()->json($data);
	}
	
	//Delete data for Pcode
	public function deletePcode(Request $request){
		
		$res = array();

		$id = $request->id;
		
		if($id != ''){
			$response = Pcode::where('id', $id)->delete();	
			if($response){
				Vpcode::truncate();
				Vpcode::create(array('bactive' => 0,'resetkey' => 0));
				$res['msgType'] = 'success';
				$res['msg'] = __('Theme deregister Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data remove failed');
			}
		}
		
		return response()->json($res);
	}	
}
