<?php

use App\Setting;
use App\Mailtext;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\ChatMessage;
use App\Payment_setting;
use App\Pcode;
use App\Vpcode;

function gtext(){
	
	locale();

	$datalist = Setting::where('bactive', 1)->get();
	
	$id = '';
	foreach ($datalist as $row){
		$id = $row->id;
	}

	$data = array();
	
	if($id != ''){
		foreach ($datalist as $row) {
			$data['setting_id'] = $row->id;
			$data['bactive'] = $row->bactive;
			$data['company_name'] = $row->company_name;
			$data['company_title'] = $row->company_title;
			$data['logo'] = $row->logo;
			$data['favicon'] = $row->favicon;
			$data['fromMailAddress'] = $row->email;
			$data['toMailAddress'] = $row->tomailaddress;
			$data['timezone_id'] = $row->timezone_id;
			$data['timezone'] = $row->timezone_id;
			$data['theme_color'] = $row->theme_color;
			$data['recaptcha'] = $row->recaptcha;
			$data['sitekey'] = $row->sitekey;
			$data['secretkey'] = $row->secretkey;
			$data['isnotification'] = $row->isnotification;
			$data['siteurl'] = $row->siteurl;
		}
	}else{

		$data['setting_id'] = '';
		$data['bactive'] = '';
		$data['company_name'] = 'TeamWork';
		$data['company_title'] = 'TeamWork - Project Management System';
		$data['logo'] = '';
		$data['favicon'] = '';
		$data['fromMailAddress'] = '';
		$data['toMailAddress'] = '';
		$data['timezone_id'] = '';
		$data['timezone'] = '';
		$data['theme_color'] = '#45a6af';
		$data['recaptcha'] = '';
		$data['sitekey'] = '';
		$data['secretkey'] = '';
		$data['isnotification'] = '';
		$data['siteurl'] = '';
	}
	return $data;
}

function mtext(){

	$datalist = Mailtext::all();
	
	$data = array();
	foreach ($datalist as $row) {
		$data[$row->subject_key] = $row->subject_value;
		$data[$row->body_key] = $row->body_value;
	}
	
	return $data;
}

function szoom(){

	$datalist = DB::table('zoom_setting')->get();
	$id = '';
	foreach ($datalist as $row){
		$id = $row->id;
	}

	$data = array();
	if($id != ''){
		foreach ($datalist as $row) {
			$data['id'] = $row->id;
			$data['apiurl'] = $row->apiurl;
			$data['zoom_api_key'] = $row->zoom_api_key;
			$data['zoom_api_secret'] = $row->zoom_api_secret;
		}
	}else{
		$data['id'] = '';
		$data['apiurl'] = '';
		$data['zoom_api_key'] = '';
		$data['zoom_api_secret'] = '';
	}
	return $data;
}

function getStripeInfo(){
	
	$datalist = Payment_setting::where('payment_method', 'Stripe')->get();
	$id = '';
	foreach ($datalist as $row){
		$id = $row->id;
	}

	$data = array();
	if($id != ''){
		foreach ($datalist as $row) {
			$data['stripe_id'] = $row->id;
			$data['stripe_key'] = $row->publickey;
			$data['stripe_secret'] = $row->secretkey;
			$data['payment_method'] = $row->payment_method;
			$data['isenable'] = $row->isenable;
		}
	}else{
		$data['stripe_id'] = '';
		$data['stripe_key'] = '';
		$data['stripe_secret'] = '';
		$data['payment_method'] = '';
		$data['isenable'] = '';
	}
	return $data;
}

function vipc(){
	
	$datalist = Vpcode::all();
	$id = '';
	foreach ($datalist as $row){
		$id = $row->id;
	}

	$data = array();
	if($id != ''){
		foreach ($datalist as $row) {
			$data['bkey'] = $row->resetkey;
		}
	}else{
		$data['bkey'] = 0;
	}
	return $data;
}

function getPcode(){
	
	$datalist = Pcode::all();
	$id = '';
	foreach ($datalist as $row){
		$id = $row->id;
	}

	$data = array();
	if($id != ''){
		foreach ($datalist as $row) {
			$data['pcode_id'] = $row->id;
			$data['pcode'] = $row->pcode;
		}
	}else{
		$data['pcode_id'] = '';
		$data['pcode'] = '';
	}
	return $data;
}

function getPurchaseData( $code ) {
	
	$header   = array();
	$header[] = 'Content-length: 0';
	$header[] = 'Content-type: application/json; charset=utf-8';
	$header[] = 'Authorization: bearer LkIHSQR0WsV9MADhIhiLPg4XmYqcu2TQ';
	$verify_url = 'https://api.envato.com/v3/market/author/sale/';
	$ch_verify = curl_init( $verify_url . '?code=' . $code );
	curl_setopt( $ch_verify, CURLOPT_HTTPHEADER, $header );
	curl_setopt( $ch_verify, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch_verify, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch_verify, CURLOPT_CONNECTTIMEOUT, 5 );
	curl_setopt( $ch_verify, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

	$cinit_verify_data = curl_exec( $ch_verify );
	curl_close( $ch_verify );

	if ($cinit_verify_data != ""){
		return json_decode($cinit_verify_data);  
	}else{
		return false;
	}
}

function verifyPurchase($code) {
	$verify_obj = getPurchaseData($code);
	if ((false === $verify_obj) || !is_object($verify_obj) || isset($verify_obj->error) || !isset($verify_obj->sold_at)){
		return 0;
	}else{
		return 1;
	}
}

function loginStatus(){

	$gtext = gtext();
	$timezone = $gtext['timezone'];
	date_default_timezone_set($timezone);
	$login_datetime = date("Y-m-d H:i:s");
	
	$user = auth()->user();
	
	$user_id = $user->id;
	
	$aRow = DB::table('chat_login_status')->where('user_id', $user_id)->count();
	$data = array(
		'user_id' => $user_id,
		'login_datetime' => $login_datetime,
		'is_active' => 1,
		'created_at' => $login_datetime,
		'updated_at' => $login_datetime
	);
	
	if($aRow == 0){
		$response = DB::table('chat_login_status')->insert($data);
	}else{
		$response = DB::table('chat_login_status')->where('user_id', $user_id)->update($data);
	}
	
	return $response;
}

//Get data for Language locale
function locale(){
	$data = Language::where('language_default', 1)->get();
	$language_code = '';
	foreach ($data as $row){
		$language_code = $row['language_code'];
	}
	if($language_code != ''){
		$locale = $language_code;
	}else{
		$locale = 'en';
	}
	
	$session_language_code = session()->get('locale');
	if($session_language_code != $locale){
		App::setLocale($locale);
		session()->put('locale', $locale);
	}
}

function msgCount($me_id) {
	$data = ChatMessage::where('user_id', $me_id)->where('is_seen', 0)->get()->count();
	
	if($data>0){
		return '<span title="'.__('New message').'" class="msg_count">'.$data.'</span>';
	}else{
		return '';
	}
}

function esc($string){
	$string = (string) $string;

	if ( 0 === strlen($string) ) {
		return '';
	}
	
	$string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	
	return $string;
}

function str_slug($str) {

	$str_slug = Str::slug($str, "-");
	
	return $str_slug;
}

