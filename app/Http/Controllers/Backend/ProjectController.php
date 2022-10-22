<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Project;
use App\User;
use App\Project_staff_map;
use App\Task_group;
use App\Attachment;
use App\Comment;
use App\Task_staff_map;
use App\Task;
use App\Payment;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ProjectController extends Controller
{
    //Project page load
    public function getProjectPageLoad(){
        return view('backend.project');
    }
	
	//Get data for Project
    public function getProjectData(Request $request){
		$gtext = gtext();
		$timezone = $gtext['timezone'];
		date_default_timezone_set($timezone);
		$currDate = date("Y-m-d");
		
		$search = $request->input('search');
		$userid = $request->input('userid');
		$role = $request->input('role');
		
		//Admin
		if($role == 1){
			$data = DB::table('projects')
				->join('pstatus', 'projects.status_id', '=', 'pstatus.id')
				->select('projects.*', 'pstatus.status_name')
				->where(function($query) use ($search){
					$query->where('project_name', 'LIKE', '%'.$search.'%')
						->orWhere('start_date', 'LIKE', '%'.$search.'%')
						->orWhere('end_date', 'LIKE', '%'.$search.'%')
						->orWhere('description', 'LIKE', '%'.$search.'%');
				})
				->orderBy('projects.id', 'DESC')
				->get();
		
		//Client			
		} elseif($role == 2) {
		
			$data = DB::table('projects')
				->join('pstatus', 'projects.status_id', '=', 'pstatus.id')
				->select('projects.*', 'pstatus.status_name')
				->where('projects.client_id', $userid)
				->where(function($query) use ($search){
					$query->where('projects.project_name', 'LIKE', '%'.$search.'%')
						->orWhere('projects.start_date', 'LIKE', '%'.$search.'%')
						->orWhere('projects.end_date', 'LIKE', '%'.$search.'%')
						->orWhere('projects.description', 'LIKE', '%'.$search.'%');
				})
				->orderBy('projects.id', 'DESC')
				->get();
		
		//Staff
		}else{
			$data = DB::table('projects')
				->join('pstatus', 'projects.status_id', '=', 'pstatus.id')
				->join('project_staff_maps', 'projects.id', '=', 'project_staff_maps.project_id')
				->select('projects.*', 'pstatus.status_name')
				->where('project_staff_maps.staff_id', $userid)
				->where(function($query) use ($search){
					$query->where('project_name', 'LIKE', '%'.$search.'%')
						->orWhere('start_date', 'LIKE', '%'.$search.'%')
						->orWhere('end_date', 'LIKE', '%'.$search.'%')
						->orWhere('description', 'LIKE', '%'.$search.'%');
				})
				->orderBy('projects.id', 'DESC')
				->get();
		}
		
		$pstatus = '';
		for($i=0; $i<count($data); $i++){
			$project_id = $data[$i]->id;

			if($data[$i]->status_id == 2){
				$pstatus = '<span class="pstatus completed pull-right">'.$data[$i]->status_name.'</span>';
			}else{
				if($currDate > $data[$i]->end_date){
					$pstatus = '<span class="pstatus expirydate pull-right">Timeout</span>';
				}else{
					$pstatus = '<span class="pstatus inprogress pull-right">'.$data[$i]->status_name.'</span>';
				}
			}
			
			$data[$i]->status_name = $pstatus;
			$data[$i]->Photo = self::getStaffNamePhoto($project_id);
		}
		
		return response()->json($data);
	}
	
	//Get data for Photo
    public function getStaffNamePhoto($id){

		$data = DB::table('users')
				->join('project_staff_maps', 'users.id', '=', 'project_staff_maps.staff_id')
				->select('users.*')
				->where('project_staff_maps.project_id', $id)
				->where('project_staff_maps.bActive', 1)
				->get();
		
		$photo = '';
		$index = 1;
		$pCount = 0;
		foreach ($data as $row) {
			
			if($index>5){
				$pCount++;
			}else{
				if($row->photo !=''){
					$photo .= '<li><img title="'.$row->name.'" src="'.asset('public/media/'.$row->photo).'"></li>';
				}else{
					$photo .= '<li><img title="'.$row->name.'" src="'.asset('public/assets/images/default.png').'"></li>';
				}
			}
			$index++;
		}
		if($pCount>0){
			$photo .= '<li class="count">'.$pCount.'+</li>';
		}
		
		return $photo;
	}
	
	//Save data for Project
    public function saveProjectData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$project_name = $request->input('project_name');
		$start_date = $request->input('start_date');
		$end_date = $request->input('end_date');
		$budget = $request->input('budget');
		$client_id = $request->input('client_id');
		$status_id = $request->input('status_id');
		$description = $request->input('description');
		$userid = $request->input('userid');
		$creation_date = Carbon::now();
		
		$validator_array = array(
			'project_name' => $request->input('project_name'),
			'start_date' => $request->input('start_date'),
			'end_date' => $request->input('end_date'),
			'budget' => $request->input('budget'),
			'client' => $request->input('client_id'),
			'status' => $request->input('status_id')
		);
		$validator = Validator::make($validator_array, [
			'project_name' => 'required',
			'start_date' => 'required',
			'end_date' => 'required',
			'budget' => 'required',
			'client' => 'required',
			'status' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('project_name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('project_name');
			return response()->json($res);
		}
		
		if($errors->has('start_date')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('start_date');
			return response()->json($res);
		}

		if($errors->has('end_date')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('end_date');
			return response()->json($res);
		}

		if($errors->has('budget')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('budget');
			return response()->json($res);
		}

		if($errors->has('client')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('client');
			return response()->json($res);
		}

		if($errors->has('status')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('status');
			return response()->json($res);
		}

		$data = array(
			'project_name' => $project_name,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'budget' => $budget,
			'client_id' => $client_id,
			'status_id' => $status_id,
			'description' => $description,
			'createby' => $userid,
			'creation_date' => $creation_date
		);

		if($id ==''){
			$project_id = Project::create($data)->id;
			if($project_id !=''){
				if($userid == $client_id){
					Project_staff_map::create(array('project_id' => $project_id,'staff_id' => $userid,'bActive' => 1));
				}else{
					Project_staff_map::create(array('project_id' => $project_id,'staff_id' => $userid,'bActive' => 1));
					Project_staff_map::create(array('project_id' => $project_id,'staff_id' => $client_id,'bActive' => 1));
				}
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Project::where('id', $id)->update($data);
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

	//Get data for Project by id
    public function getProjectById(Request $request){

		$id = $request->id;

		$data = DB::table('projects')
					->join('pstatus', 'projects.status_id', '=', 'pstatus.id')
					->join('users', 'projects.client_id', '=', 'users.id')
					->select('projects.*', 'pstatus.status_name')
					->where('projects.id', $id)->first();
		
		$data->budget = $data->budget;
		$data->budget_number = number_format($data->budget);
		
		return response()->json($data);
	}
	
	//Delete data for Project
	public function deleteProject(Request $request){
		$gtext = gtext();
		$mtext = mtext();
		$res = array();

		$userid = $request->userid;
		$id = $request->id;
		
		if($gtext['isnotification'] == 1){

			$query = "SELECT a.project_name, b.name, b.email
			FROM projects a 
			INNER JOIN users b ON a.createby = b.id
			WHERE a.id = '".$id."';";
			$aRows = DB::select(DB::raw($query));
			if(!empty($aRows)){
				$isCreateby = 1;
			}else{
				$isCreateby = 0;
			}
			$Obj = User::where('id', $userid)->first();
			$isUser = $Obj->toArray();
		}

		Attachment::where('project_id', $id)->delete();
		Comment::where('project_id', $id)->delete();
		Task_staff_map::where('project_id', $id)->delete();
		Task::where('project_id', $id)->delete();
		Task_group::where('project_id', $id)->delete();
		Project_staff_map::where('project_id', $id)->delete();
		Payment::where('project_id', $id)->delete();
		
		$response = Project::where('id', $id)->delete();
		if($response){
			
			if($gtext['isnotification'] == 1){
				
				if($isCreateby == 1){
					require 'vendor/autoload.php';
					$mail = new PHPMailer(true);

					//Send mail
					$mail->setFrom($gtext['fromMailAddress'], $gtext['company_name']);
					$mail->addAddress($aRows[0]->email);
					$mail->isHTML(true);
					$mail->CharSet = "utf-8";
					$mail->Subject = $mtext['Subject - Your project has been deleted permanently'].' - '.$isUser['name'];
					$mail->Body = "<table style='background-color:#f0f0f0;color:#444;padding:40px 0px;line-height:24px;font-size:16px;' border='0' cellpadding='0' cellspacing='0' width='100%'>	
										<tr>
											<td>
												<table style='background-color:#fff;max-width:600px;margin:0 auto;padding:30px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
													<tr><td style='font-size:30px;border-bottom:1px solid #ddd;padding-bottom:15px;font-weight:bold;text-align:center;'>".$gtext['company_name']."</td></tr>
													<tr><td style='font-size:20px;font-weight:bold;padding:30px 0px 5px 0px;'>Hi ".$aRows[0]->name."</td></tr>
													<tr><td>Project name: ".$aRows[0]->project_name."</td></tr>
													<tr><td style='padding-bottom:40px;'>".$mtext['Body - Your project has been deleted permanently']."</td></tr>
													<tr><td style='padding-bottom:50px;'><a href='".$gtext['siteurl']."/login' style='background:".$gtext['theme_color'].";display:block;text-align:center;padding:10px;border-radius:3px;text-decoration:none;color:#fff;'>".__('Login')."</a></td></tr>
													<tr><td style='padding-top:10px;border-top:1px solid #ddd;'>Thank you!</td></tr>
													<tr><td style='padding-top:5px;'><strong>".$gtext['company_name']."</strong></td></tr>
												</table>
											</td>
										</tr>
									</table>";
					$mail->send();
				}
			}
			
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Removed Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data remove failed');
		}

		return response()->json($res);
	}
	
	//Get data for Invited Staff
	public function getInvitedStaff(Request $request){

		$id = $request->id;

		$data = DB::table('users')
					->join('project_staff_maps', 'users.id', '=', 'project_staff_maps.staff_id')
					->select('users.*', 'project_staff_maps.bActive', 'project_staff_maps.project_id', 'project_staff_maps.id as project_staff_id')
					->where('project_staff_maps.project_id', $id)
					->orderBy('name', 'asc')
					->get();
					
		return response()->json($data);
	}
	
	//Get data for Staff List combo
    public function getStaffList(Request $request){

		$Data = DB::table('users')
					->where('active_id', 1)
					->whereNotIn('users.id', function($query) use ($request){
						$query->select('project_staff_maps.staff_id')
						->from('project_staff_maps')
						->where('project_staff_maps.project_id', $request->project_id);
					})
					->orderBy('name', 'asc')
					->get();
					
		return $Data;
	}
	
	//Save data for Invite
    public function saveInviteData(Request $request){
		$gtext = gtext();
		$mtext = mtext();
		
		$res = array();
		
		$staff_id = $request->input('staff_id');
		$project_id = $request->input('project_id');
		$userid = $request->input('userid');

		$validator_array = array(
			'staff' => $request->input('staff_id'),
			'project' => $request->input('project_id')
		);
		$validator = Validator::make($validator_array, [
			'staff' => 'required',
			'project' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('staff')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('staff');
			return response()->json($res);
		}
		
		if($errors->has('project')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('project');
			return response()->json($res);
		}

		$data = array(
			'staff_id' => $staff_id,
			'project_id' => $project_id
		);

		$response = Project_staff_map::create($data);
		if($response){
			
			if($gtext['isnotification'] == 1){

				require 'vendor/autoload.php';
				$mail = new PHPMailer(true);
				
				$projectObj = Project::where('id', $project_id)->first();
				$projectArr = $projectObj->toArray();
				
				$StaffObj = User::where('id', $staff_id)->first();
				$StaffArr = $StaffObj->toArray();
				
				$UserObj = User::where('id', $userid)->first();
				$UserArr = $UserObj->toArray();

				//Send mail
				$mail->setFrom($gtext['fromMailAddress'], $gtext['company_name']);
				$mail->addAddress($StaffArr['email']);
				$mail->isHTML(true);
				$mail->CharSet = "utf-8";
				$mail->Subject = $UserArr['name'].' '.$mtext['Subject - invited you to join'].' - '.$projectArr['project_name'];
				$mail->Body = "<table style='background-color:#f0f0f0;color:#444;padding:40px 0px;line-height:24px;font-size:16px;' border='0' cellpadding='0' cellspacing='0' width='100%'>	
									<tr>
										<td>
											<table style='background-color:#fff;max-width:600px;margin:0 auto;padding:30px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
												<tr><td style='font-size:30px;border-bottom:1px solid #ddd;padding-bottom:15px;font-weight:bold;text-align:center;'>".$gtext['company_name']."</td></tr>
												<tr><td style='font-size:20px;font-weight:bold;padding:30px 0px 5px 0px;'>Hi ".$StaffArr['name']."</td></tr>
												<tr><td>Project name: ".$projectArr['project_name']."</td></tr>
												<tr><td style='padding-bottom:40px;'>".$mtext['Body - invited you to join']."</td></tr>
												<tr><td style='padding-bottom:50px;'><a href='".$gtext['siteurl']."/login' style='background:".$gtext['theme_color'].";display:block;text-align:center;padding:10px;border-radius:3px;text-decoration:none;color:#fff;'>".__('Login')."</a></td></tr>
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
	
	//Save data for Invite Active Inactive
    public function InviteActiveInactive(Request $request){
		$gtext = gtext();
		$mtext = mtext();
		
		$res = array();
		
		$id = $request->input('id');
		$bActive = $request->input('bActive');

		$data = array(
			'bActive' => $bActive
		);

		$response = Project_staff_map::where('id', $id)->update($data);
		if($response){

			if($gtext['isnotification'] == 1){
				if($bActive == 0){
					
					require 'vendor/autoload.php';
					$mail = new PHPMailer(true);
					
					$obj = DB::table('project_staff_maps')
					->join('projects', 'project_staff_maps.project_id', '=', 'projects.id')
					->join('users', 'project_staff_maps.staff_id', '=', 'users.id')
					->select('users.email', 'users.name', 'projects.project_name')
					->where('project_staff_maps.id', $id)->first();

					//Get Staff mail
					$mail->setFrom($gtext['fromMailAddress'], $gtext['company_name']);
					$mail->addAddress($obj->email, $obj->name);
					$mail->isHTML(true);
					$mail->CharSet = "utf-8";
					$mail->Subject = $mtext['Subject - Removed you from the project'].' - '.$obj->project_name;
					$mail->Body = "<table style='background-color:#f0f0f0;color:#444;padding:40px 0px;line-height:24px;font-size:16px;' border='0' cellpadding='0' cellspacing='0' width='100%'>	
											<tr>
												<td>
													<table style='background-color:#fff;max-width:600px;margin:0 auto;padding:30px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
														<tr><td style='font-size:30px;border-bottom:1px solid #ddd;padding-bottom:15px;font-weight:bold;text-align:center;'>".$gtext['company_name']."</td></tr>
														<tr><td style='font-size:20px;font-weight:bold;padding:30px 0px 5px 0px;'>Hi ".$obj->name."</td></tr>
														<tr><td style='padding-top:5px;padding-bottom:50px;'>".$mtext['Body - Removed you from the project']." - ".$obj->project_name."</td></tr>
														<tr><td style='padding-top:10px;border-top:1px solid #ddd;'>Thank you!</td></tr>
														<tr><td style='padding-top:5px;'><strong>".$gtext['company_name']."</strong></td></tr>
													</table>
												</td>
											</tr>
										</table>";
					$mail->send();
				}
			}
				
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data insert failed');
		}
		
		return response()->json($res);
    }
	
	//Delete data for Invite Project
	public function deleteInviteProject(Request $request){
		$res = array();

		$id = $request->id;
		
		if($id != 0){
			$response = Project_staff_map::where('id', $id)->delete();	
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
}
