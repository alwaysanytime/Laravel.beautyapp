<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProfileController extends Controller
{
    //profile page load
    public function getProfilePageLoad(){
        return view('backend.profile');
    }
	
	//Get data for Profile
    public function getProfileData(Request $request){
		
		$id = $request->id;
		
		$data = User::where('id', $id)->first();

		$data->bactive = base64_decode($data->bactive);

		return response()->json($data);
	}
	
	//Save data for Profile
    public function saveProfileData(Request $request){
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
		$photo = $request->input('photo');
		
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
			'photo' => $photo,
			'bactive' => base64_encode($password)
		);

		$response = User::where('id', $id)->update($data);
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}
		
		return response()->json($res);
    }	
}
