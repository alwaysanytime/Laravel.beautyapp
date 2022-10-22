<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComboController extends Controller
{
	//Get data for Country List combo
    public function getCountryList(){
		
		$Data = DB::table('countries')->get();
		
		return $Data;
	}
	
	//Get data for User Active List combo
    public function getUserActivesList(){
		
		$Data = DB::table('user_actives')->get();
		
		return $Data;
	}
	
	//Get data for Timezone List combo
    public function getTimezoneList(){
		
		$Data = DB::table('timezones')->orderBy('timezone', 'asc')->get();
		
		return $Data;
	}
	
	//Get data for Month List combo
    public function getMonthList(){
		
		$Data = DB::table('months')->get();
		
		return $Data;
	}
	
	//Get data for Year List combo
    public function getYearList(){
		
		$Data = DB::table('years')->get();
		
		return $Data;
	}
	
	//Get data for Language List combo
    public function getLanguageList(){
		
		$Data = DB::table('languages')->get();
		
		return $Data;
	}
	
	//Get data for Payment Status List combo
    public function getPaymentStatusList(){
		
		$Data = DB::table('payment_status')->get();
		
		return $Data;
	}
	
	//Get data for User Roles List combo
    public function getUserRolesList(){
		
		$Data = DB::table('user_roles')
			->whereNotIn('id', [2])
			->get();
		
		return $Data;
	}
	
	//Get data for Client List combo
    public function getClientList(){
		
		$Data = DB::table('users')
			->where('role', 2)
			->get();
		
		return $Data;
	}

	//Get data for Status List combo
    public function getStatusList(){
		
		$Data = DB::table('pstatus')->get();
		
		return $Data;
	}
	
	//Get data for payment method List combo
    public function getPaymentMethodList(){
		
		$Data = DB::table('payment_method')->get();
		
		return $Data;
	}

	//Get data for Task Group combo
    public function getTaskGroup(Request $request){
		$project_id = $request->input('project_id');

		$Data = DB::table('task_groups')
				->where('task_groups.project_id', $project_id)
				->orderBy('bOrder', 'asc')
				->get();
		
		return $Data;
	}
}
