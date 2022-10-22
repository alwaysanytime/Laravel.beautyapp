<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Task_group;
use App\Task;
use App\Comment;
use App\Attachment;
use App\Task_staff_map;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class TaskBoardController extends Controller
{
    //Task Board page load
    public function getTaskBoardPageLoad($id){
		$data['project_id'] = $id;
        return view('backend.task-board', $data);
    }
	
	//Get data for Project Info
    public function getProjectInfo(Request $request){
		
		$project_id = $request->input('project_id');
		$userid = $request->input('userid');

		$data = DB::table('project_staff_maps')
				->join('projects', 'project_staff_maps.project_id', '=', 'projects.id')
				->select('projects.project_name', 'project_staff_maps.bActive')
				->where('project_staff_maps.project_id', $project_id)
				->where('project_staff_maps.staff_id', $userid)
				->get();
		
		return response()->json($data);
	}
	
	//Get data for Task Board
    public function getTaskBoardData(Request $request){
	
		$project_id = $request->input('project_id');

		$data = DB::table('task_groups')
				->where('task_groups.project_id', $project_id)
				->orderBy('bOrder', 'asc')
				->get();

		for($i=0; $i<count($data); $i++){
			$task_group_id = $data[$i]->id;
			$data[$i]->TaskList = self::getTaskList($task_group_id, $project_id);
		}
		
		return response()->json($data);
	}
	
    public function getTaskList($task_group_id, $project_id) {

		$gtext = gtext();
		$timezone = $gtext['timezone'];

		$data = Task::where('task_group_id', $task_group_id)
				->orderBy('bOrder', 'asc')
				->get();

		$TaskList = '';
		foreach ($data as $row) {
			
			date_default_timezone_set($timezone);
			$tdate = strtotime($row->task_date);
			$task_date = date("M d", $tdate);
			
			date_default_timezone_set($timezone);
			$olddate = strtotime($row->task_date);
			$oldDateTime = date("YmdHis", $olddate);
			
			date_default_timezone_set($timezone);
			$currDateTime = date("YmdHis");
		
			if($row->complete_task == 0){
				if($currDateTime < $oldDateTime){
					$task_datetime = '<div class="datetime dobgcolor"><a onclick="onLoadTaskDataForStatus('.$row->id.');" href="javascript:void(0);" title="'.__('This task is incomplete').'"><i class="fa fa-clock-o"></i>'.$task_date.'</a></div>';
				}else if($currDateTime > $oldDateTime){
					$task_datetime = '<div class="datetime expbgcolor"><a onclick="onLoadTaskDataForStatus('.$row->id.');" href="javascript:void(0);" title="'.__('This task is timeout').'"><i class="fa fa-clock-o"></i>'.$task_date.'</a></div>';
				}
			}else{
				$task_datetime = '<div class="datetime combgcolor"><a onclick="onLoadTaskDataForStatus('.$row->id.');" href="javascript:void(0);" title="'.__('This task is complete').'"><i class="fa fa-clock-o"></i>'.$task_date.'</a></div>';
			}
			
			$TaskList .= '<li id="'.$row->id.'">
						<p id="task_name_'.$row->id.'">'.$row->task_name.'</p>
						<ul class="task-action">
							<li class="task-edit"><a onclick="onCommentsAttachment('.$row->id.','.$task_group_id.');" href="javascript:void(0);" title="'.__('Comments').'"><i class="fa fa-comments-o"></i></a></li>
							<li class="task-edit"><a onclick="onLoadTaskDataForStatus('.$row->id.');" href="javascript:void(0);" title="'.__('Change Task Status').'"><i class="fa fa-clock-o"></i></a></li>
							<li class="task-edit"><a onclick="onMoveTask('.$row->id.','.$task_group_id.');" href="javascript:void(0);" title="'.__('Move').'"><i class="fa fa-exchange"></i></a></li>
							<li class="task-edit"><a onclick="onTaskEditData('.$row->id.');" href="javascript:void(0);" title="'.__('Edit').'"><i class="fa fa-pencil"></i></a></li>
							<li class="task-delete"><a onclick="onTaskDelete('.$row->id.');" href="javascript:void(0)" title="'.__('Delete').'"><i class="fa fa-trash-o"></i></a></li>
						</ul>
						'.$task_datetime.'
						<ul class="staff-image">
							'.self::getStaffPhoto($row->id, $project_id).'
						</ul>
					</li>';
		}
		
		return $TaskList;
    }
	
   public function getStaffPhoto($id, $project_id) {

		$data = DB::table('task_staff_maps')
				->join('users', 'task_staff_maps.staff_id', '=', 'users.id')
				->join('project_staff_maps', 'users.id', '=', 'project_staff_maps.staff_id')
				->select('users.name', 'users.photo')
				->where('project_staff_maps.bActive', 1)
				->where('project_staff_maps.project_id', $project_id)
				->where('task_staff_maps.task_id', $id)
				->orderBy('task_staff_maps.id', 'asc')
				->get();
				
		$StaffPhoto = '';
		if(count($data)>0){
			$StaffPhoto .= '<li class="staff-plus"><a onclick="onInviteTask('.$id.');" href="javascript:void(0);" title="'.__('Add Staff').'"><i class="fa fa-user-plus"></i></a></li>';
		}else{
			$StaffPhoto .= '<li class="staff-plus mr-0"><a onclick="onInviteTask('.$id.');" href="javascript:void(0);" title="'.__('Add Staff').'"><i class="fa fa-user-plus"></i></a></li>';
		}

		$index = 1;
		$pCount = 0;
		foreach ($data as $row) {
			if($index>5){
				$pCount++;
			}else{
				if($row->photo !=''){
					$StaffPhoto .= '<li><img title="'.$row->name.'" src="'.asset('public/media/'.$row->photo).'"></li>';
				}else{
					$StaffPhoto .= '<li><img title="'.$row->name.'" src="'.asset('public/assets/images/default.png').'"></li>';
				}
			}
			$index++;
		}
		if($pCount>0){
			$StaffPhoto .= '<li class="count">'.$pCount.'+</li>';
		}
		return $StaffPhoto;
    }
	
	//Save data for Task Board
    public function saveTaskBoardData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$task_group_name = $request->input('task_group_name');
		$project_id = $request->input('project_id');

		$validator_array = array(
			'task_group_name' => $request->input('task_group_name'),
			'project_id' => $request->input('project_id')
		);
		$validator = Validator::make($validator_array, [
			'task_group_name' => 'required',
			'project_id' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('task_group_name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('task_group_name');
			return response()->json($res);
		}
		
		if($errors->has('project_id')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('project_id');
			return response()->json($res);
		}

		if($id ==''){
			
			$MaxId = Task_group::where('project_id', $project_id)->max('bOrder');
			$bOrder = $MaxId+1;
			
			$data = array(
				'task_group_name' => $task_group_name,
				'project_id' => $project_id,
				'bOrder' => $bOrder
			);
			
			$response = Task_group::create($data)->id;
			
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$data = array(
				'task_group_name' => $task_group_name,
				'project_id' => $project_id
			);
			$response = Task_group::where('id', $id)->update($data);
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
	
	//Get data for Task Board by id
    public function getTaskBoardById(Request $request){

		$id = $request->RecordId;

		$data = Task_group::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for TaskBoard
	public function deleteTaskBoard(Request $request){
		
		$res = array();

		$id = $request->RecordId;
		
		if($id != 0){
			$response = Task_group::where('id', $id)->delete();	
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
	
	//Save data for Task
    public function saveTaskData(Request $request){
		$gtext = gtext();
		$timezone = $gtext['timezone'];
		date_default_timezone_set($timezone);
		$comments_date = date("Y-m-d h:i:s");
		
		$res = array();
		
		$id = $request->input('task_id');
		$task_name = $request->input('task_name');
		$description = $request->input('description');
		$task_date = $request->input('task_date');
		$task_group_id = $request->input('task_group_id');
		$task_group_name = $request->input('task_group_name');
		$userid = $request->input('userid');
		$project_id = $request->input('project_id');

		$validator_array = array(
			'task_name' => $request->input('task_name'),
			'task_date' => $request->input('task_date')
		);
		$validator = Validator::make($validator_array, [
			'task_name' => 'required',
			'task_date' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('task_name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('task_name');
			return response()->json($res);
		}
		
		if($errors->has('task_date')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('task_date');
			return response()->json($res);
		}

		if($id ==''){
			
			$MaxId = Task::where('task_group_id', $task_group_id)->max('bOrder');
			$bOrder = $MaxId+1;

			$data = array(
				'task_name' => $task_name,
				'description' => $description,
				'task_group_id' => $task_group_id,
				'project_id' => $project_id,
				'task_date' => $task_date,
				'bOrder' => $bOrder
			);
			
			$task_id = Task::create($data)->id;
			
			if($task_id !=''){
				
				$comment = "Added this task to ".$task_group_name;
				
				$data = array(
					'comment' => $comment,
					'comments_date' => $comments_date,
					'task_id' => $task_id,
					'staff_id' => $userid,
					'project_id' => $project_id,
					'battach' => 0
				);

				Comment::create($data);
		
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$data = array(
				'task_name' => $task_name,
				'description' => $description,
				'task_group_id' => $task_group_id,
				'project_id' => $project_id,
				'task_date' => $task_date
			);
			$response = Task::where('id', $id)->update($data);
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
	
	//Get data for Task by id
    public function getTaskById(Request $request){

		$id = $request->RecordId;

		$data = Task::where('id', $id)->first();
		
		return response()->json($data);
	}
	
	//Delete data for Task
	public function deleteTask(Request $request){
		
		$res = array();

		$id = $request->RecordId;
		
		if($id != ''){

			Attachment::where('task_id', $id)->delete();
			Comment::where('task_id', $id)->delete();
			Task_staff_map::where('task_id', $id)->delete();
			
			$response = Task::where('id', $id)->delete();	
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
	
	//Save data for Task StaffPhoto
    public function updateTaskStatusData(Request $request){
		$gtext = gtext();
		$timezone = $gtext['timezone'];
		date_default_timezone_set($timezone);
		$comments_date = date("Y-m-d h:i:s");
		
		$res = array();
		
		$task_id = $request->input('status_task_id');
		$task_group_id = $request->input('task_group_id');
		$project_id = $request->input('project_id');
		$task_date = $request->input('status_task_date');
		$complete_task = $request->input('status_complete_task');
		
		$task_group_name = $request->input('task_group_name');
		$userid = $request->input('userid');

		$validator_array = array(
			'task_date' => $request->input('status_task_date')
		);
		$validator = Validator::make($validator_array, [
			'task_date' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('task_date')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('task_date');
			return response()->json($res);
		}

		if($task_id !=''){
			
			$data = array(
				'task_group_id' => $task_group_id,
				'task_date' => $task_date,
				'complete_task' => $complete_task
			);
			$response = Task::where('id', $task_id)->update($data);
			if($response){
				
				if($complete_task == 1){
					
					$comment = "This task is complete in ".$task_group_name;
					$commentData = array(
						'comment' => $comment,
						'comments_date' => $comments_date,
						'task_id' => $task_id,
						'staff_id' => $userid,
						'project_id' => $project_id,
						'battach' => 0
					);
					
					Comment::create($commentData);
					
				}else{			
					$comment = "Change status this task to ".$task_group_name;
					$commentData = array(
						'comment' => $comment,
						'comments_date' => $comments_date,
						'task_id' => $task_id,
						'staff_id' => $userid,
						'project_id' => $project_id,
						'battach' => 0
					);
					Comment::create($commentData);
				}
				
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}
		
		return response()->json($res);
    }
	
	//Save data for Task Move
    public function updateTaskMove(Request $request){
		$gtext = gtext();
		$timezone = $gtext['timezone'];
		date_default_timezone_set($timezone);
		$comments_date = date("Y-m-d h:i:s");
		
		$res = array();
		
		$task_id = $request->input('Move_task_id');
		$task_group_id = $request->input('move_task_group_id');
		$task_group_name = $request->input('task_group_name');
		$userid = $request->input('userid');
		$project_id = $request->input('project_id');

		if($task_id !=''){
			
			$MaxId = Task::where('task_group_id', $task_group_id)->max('bOrder');
			$bOrder = $MaxId+1;

			$data = array(
				'task_group_id' => $task_group_id,
				'bOrder' => $bOrder
			);
			
			$response = Task::where('id', $task_id)->update($data);
			
			if($response){

				$dataObj = Task_group::where('id', $task_group_id)->first();
				$dataArr = $dataObj->toArray();
				$move_task_group_name = $dataArr['task_group_name'];
				
				$comment = "Moved this task from ".$task_group_name." to ".$move_task_group_name;
				
				$commentData = array(
					'comment' => $comment,
					'comments_date' => $comments_date,
					'task_id' => $task_id,
					'staff_id' => $userid,
					'project_id' => $project_id,
					'battach' => 0
				);
				
				Comment::create($commentData);
					
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}
		
		return response()->json($res);
    }
	
	//Get data for Invite Task
    public function getInviteTaskData(Request $request){
	
		$project_id = $request->input('project_id');
		$task_id = $request->input('task_id');

		$data = DB::table('task_staff_maps')
				->join('users', 'task_staff_maps.staff_id', '=', 'users.id')
				->join('project_staff_maps', 'users.id', '=', 'project_staff_maps.staff_id')
				->select('task_staff_maps.id', 'users.name', 'users.photo', 'task_staff_maps.task_id')
				->where('project_staff_maps.bActive', 1)
				->where('project_staff_maps.project_id', $project_id)
				->where('task_staff_maps.task_id', $task_id)
				->whereNotIn('users.role', [2])
				->groupBy('task_staff_maps.id', 'users.name', 'users.photo', 'task_staff_maps.task_id')
				->orderBy('users.name', 'asc')
				->get();
		return response()->json($data);
	}
	
	//Get data for Staff List combo
    public function getInviteStaff(Request $request){
		
		$project_id = $request->input('project_id');
		$task_id = $request->input('task_id');
		
		$Data = DB::table('users')
				->join('project_staff_maps', 'users.id', '=', 'project_staff_maps.staff_id')
				->select('users.id', 'users.name')
				->where('project_staff_maps.bActive', 1)
				->whereNotIn('users.role', [2])
				->where('project_staff_maps.project_id', $project_id)
				->whereNotIn('users.id', function($query) use ($request){
					$query->select('task_staff_maps.staff_id')
					->from('task_staff_maps')
					->where('task_staff_maps.task_id', $request->task_id);
				})
				->groupBy('users.id', 'users.name')
				->orderBy('name', 'asc')
				->get();
					
		return $Data;
	}
	
	//Save data for Invite Task
    public function insertInviteTaskData(Request $request){
		$gtext = gtext();
		$mtext = mtext();
		
		$res = array();
		
		$staff_id = $request->input('staff_id');
		$task_id = $request->input('invite_task_id');
		$project_id = $request->input('project_id');

		$validator_array = array(
			'staff' => $request->input('staff_id')
		);
		$validator = Validator::make($validator_array, [
			'staff' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('staff')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('staff');
			return response()->json($res);
		}
		
		$data = array(
			'staff_id' => $staff_id,
			'task_id' => $task_id,
			'project_id' => $project_id
		);
		
		$task_staff_id = Task_staff_map::create($data)->id;
		
		if($task_staff_id !=''){
			
			if($gtext['isnotification'] == 1){

				require 'vendor/autoload.php';
				$mail = new PHPMailer(true);
				
				$StaffObj = User::where('id', $staff_id)->first();
				$StaffArr = $StaffObj->toArray();
				
				$taskObj = Task::where('id', $task_id)->first();
				$taskArr = $taskObj->toArray();

				//Send mail
				$mail->setFrom($gtext['fromMailAddress'], $gtext['company_name']);
				$mail->addAddress($StaffArr['email']);
				$mail->isHTML(true);
				$mail->CharSet = "utf-8";
				$mail->Subject = $mtext['Subject - Assigned you in a task'].' - '.$taskArr['task_name'];
				$mail->Body = "<table style='background-color:#f0f0f0;color:#444;padding:40px 0px;line-height:24px;font-size:16px;' border='0' cellpadding='0' cellspacing='0' width='100%'>	
							<tr>
								<td>
									<table style='background-color:#fff;max-width:600px;margin:0 auto;padding:30px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
										<tr><td style='font-size:30px;border-bottom:1px solid #ddd;padding-bottom:15px;font-weight:bold;text-align:center;'>".$gtext['company_name']."</td></tr>
										<tr><td style='font-size:20px;font-weight:bold;padding:30px 0px 5px 0px;'>Hi ".$StaffArr['name']."</td></tr>
										<tr><td>Task name: ".$taskArr['task_name']."</td></tr>
										<tr><td style='padding-bottom:40px;'>".$mtext['Body - Assigned you in a task']."</td></tr>
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
	
	//Delete data for Invite Task
	public function deleteInviteTask(Request $request){
		$gtext = gtext();
		$mtext = mtext();
		
		$res = array();

		$id = $request->RecordId;
		
		if($id != ''){
			
			if($gtext['isnotification'] == 1){

				require 'vendor/autoload.php';
				$mail = new PHPMailer(true);
	
				$Obj = DB::table('task_staff_maps')
						->join('users', 'task_staff_maps.staff_id', '=', 'users.id')
						->join('tasks', 'task_staff_maps.task_id', '=', 'tasks.id')
						->select('tasks.task_name', 'users.name', 'users.email')
						->where('task_staff_maps.id', $id)
						->get();
				$aRows = $Obj->toArray();
			}

			$response = Task_staff_map::where('id', $id)->delete();	
			if($response){
				
				if($gtext['isnotification'] == 1){
					
					//Send mail
					$mail->setFrom($gtext['fromMailAddress'], $gtext['company_name']);
					$mail->addAddress($aRows[0]->email);
					$mail->isHTML(true);
					$mail->CharSet = "utf-8";
					$mail->Subject = $mtext['Subject - Removed you in a task'].' - '.$aRows[0]->task_name;
					$mail->Body = "<table style='background-color:#f0f0f0;color:#444;padding:40px 0px;line-height:24px;font-size:16px;' border='0' cellpadding='0' cellspacing='0' width='100%'>	
									<tr>
										<td>
											<table style='background-color:#fff;max-width:600px;margin:0 auto;padding:30px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
												<tr><td style='font-size:30px;border-bottom:1px solid #ddd;padding-bottom:15px;font-weight:bold;text-align:center;'>".$gtext['company_name']."</td></tr>
												<tr><td style='font-size:20px;font-weight:bold;padding:30px 0px 5px 0px;'>Hi ".$aRows[0]->name."</td></tr>
												<tr><td style='padding-top:5px;'>Task name: ".$aRows[0]->task_name."</td></tr>
												<tr><td style='padding-bottom:50px;'>".$mtext['Body - Removed you in a task']."</td></tr>
												<tr><td style='padding-top:10px;border-top:1px solid #ddd;'>Thank you!</td></tr>
												<tr><td style='padding-top:5px;'><strong>".$gtext['company_name']."</strong></td></tr>
											</table>
										</td>
									</tr>
								</table>";
					$mail->send();
				}
				
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Removed Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data remove failed');
			}
		}
		
		return response()->json($res);
	}
	
	//Get data for Active Staff in Projects
    public function getActiveStaffinProjects(Request $request){
		
		$project_id = $request->input('project_id');

		$data = DB::table('users')
				->join('project_staff_maps', 'users.id', '=', 'project_staff_maps.staff_id')
				->select('users.id', 'users.name', 'users.email',  'users.photo', 'project_staff_maps.project_id', 'project_staff_maps.staff_id', 'project_staff_maps.id', 'project_staff_maps.bActive')
				->where('project_staff_maps.bActive', 1)
				->where('project_staff_maps.project_id', $project_id)
				->orderBy('users.name', 'asc')
				->get();
				
		return response()->json($data);
	}
	
	//Get data for Invite Projects
    public function getInviteProjectsData(Request $request){
		$project_id = $request->input('project_id');

		$data = DB::table('users')
				->join('project_staff_maps', 'users.id', '=', 'project_staff_maps.staff_id')
				->select('users.id', 'users.role', 'users.name', 'users.email',  'users.photo', 'project_staff_maps.project_id', 'project_staff_maps.staff_id', 'project_staff_maps.id', 'project_staff_maps.bActive')
				->where('project_staff_maps.project_id', $project_id)
				->orderBy('users.name', 'asc')
				->get();
				
		return response()->json($data);
	}
	
	//Get data for Staff by Project
    public function getStaffbyProject(Request $request){

		$project_id = $request->input('project_id');

		$data = DB::table('users')
				->select('users.id', 'users.role', 'users.name')
				->where('users.active_id', 1)
				->whereNotIn('users.id', function($query) use ($request){
					$query->select('project_staff_maps.staff_id')
					->from('project_staff_maps')
					->where('project_staff_maps.project_id', $request->project_id);
				})
				->orderBy('users.name', 'asc')
				->get();
				
		return response()->json($data);
	}
	
	//Save data for Task list Sortable
    public function onTasklistSortable(Request $request){

		$res = array();
		
		$tasklist = json_decode($request->input('tasklistObject'));
		$incri = 0;
		foreach ($tasklist as $key => $task_id) {
			$data = array('bOrder' => $key);
			Task::where('id', $task_id)->update($data);
			$incri++;
		}

		if($incri>0){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}

		return response()->json($res);
    }
	
	//Save data for Task Group Sortable
    public function onTaskGroupSortable(Request $request){

		$res = array();
		
		$TaskGrouplist = json_decode($request->input('TaskGroupObject'));
		$incri = 0;
		foreach ($TaskGrouplist as $key => $task_group_id) {
			$data = array('bOrder' => $key);
			Task_group::where('id', $task_group_id)->update($data);
			$incri++;
		}

		if($incri>0){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}

		return response()->json($res);
    }
	
	//Get data for Comments Data
    public function getCommentsData(Request $request){
		$gtext = gtext();
		$timezone = $gtext['timezone'];
		
		$task_id = $request->input('task_id');

		$data = DB::table('comments')
				->join('users', 'comments.staff_id', '=', 'users.id')
				->select('comments.id', 'comments.comment', 'comments.attachment', 'comments.comments_date', 'comments.task_id', 'comments.staff_id', 'comments.battach', 'comments.editable', 'users.name', 'users.photo')
				->where('comments.task_id', $task_id)
				->orderBy('comments.id', 'DESC')
				->get();
				
			for($i=0; $i<count($data); $i++){
				$comments_id = $data[$i]->id;

				date_default_timezone_set($timezone);
				$date = strtotime($data[$i]->comments_date);
				$commentsDateTime = date("d M Y h:i A", $date);
				
				$data[$i]->comments_date = $commentsDateTime;
				$data[$i]->attachment = self::getCommentAttachs($task_id, $comments_id);
			}
				
		return response()->json($data);
	}
	
  public function getCommentAttachs($task_id, $comments_id) {
		$gtext = gtext();
		$timezone = $gtext['timezone'];
		
		$data = DB::table('attachments')
				->join('users', 'attachments.staff_id', '=', 'users.id')
				->select('attachments.id', 'attachments.attach_title', 'attachments.attachment', 'attachments.attach_date', 'attachments.comments_id', 'attachments.task_id', 'attachments.staff_id', 'users.name')
				->where('attachments.comments_id', $comments_id)
				->orderBy('attachments.id', 'DESC')
				->get();

		$Attachs = '';
		foreach ($data as $row) {
			
			$Filetype = strtolower(pathinfo($row->attachment, PATHINFO_EXTENSION));

			date_default_timezone_set($timezone);
			$date = strtotime($row->attach_date);
			$attach_date = date("d M Y, h:i A", $date);
				
			if($row->attachment !=''){
				$Attachs .= '<li>
					<div class="row display-none" id="attach_box_'.$row->id.'">
						<div class="col-lg-12">
							<div class="form-group edit-textarea">
								<textarea name="attach_title" id="attach_title_txt_'.$row->id.'" class="form-control"></textarea>
							</div>
							<a onclick="onAttachTitleSave('.$task_id.', '.$row->id.');" href="javascript:void(0);" class="btn green-btn mr-10">'.__('Save').'</a>
							<a onClick="onAttachTitleBoxsh('.$row->id.')" href="javascript:void(0);" class="btn danger-btn">'.__('Cancel').'</a>
						</div>
					</div>
					<a class="att-icon" id="att_icon_'.$row->id.'" download href="'.asset('public/media/'.$row->attachment).'">'.$Filetype.'</a>
					<div class="attach-info">
						<a download id="geTattach_titleTxt_'.$row->id.'" href="'.asset('public/media/'.$row->attachment).'">'.$row->attach_title.'</a>
					</div>
					<span class="text-muted" id="text_muted_att_'.$row->id.'">
						<small class="text-muted">'.$row->name.' on '.$attach_date.'</small>
					</span>
					<ul class="attach-control" id="attach_control_'.$row->id.'">
						<li class="att-edit"><a onclick="onAttachTitleEdit('.$row->id.');" href="javascript:void(0);" title="'.__('Edit').'"><i class="fa fa-pencil"></i></a></li>
						<li class="att-delete"><a onclick="onAttachDelete('.$task_id.', '.$row->id.');" href="javascript:void(0);" title="'.__('Delete').'"><i class="fa fa-trash-o"></i></a></li>
					</ul>
				</li>';
			}
		}
		
		return $Attachs;
    }

	//Save data for Comments
    public function insertUpdateComments(Request $request){
		$gtext = gtext();
		$timezone = $gtext['timezone'];
		
		date_default_timezone_set($timezone);
		$comments_date = date("Y-m-d h:i:s");
		
		date_default_timezone_set($timezone);
		$attach_date = date("Y-m-d h:i:s");

		$res = array();
		
		$id = $request->input('comments_id');
		$userid = $request->input('userid');
		$task_id = $request->input('task_id');
		$project_id = $request->input('project_id');
		$comment = $request->input('comments');
		$attach_str = $request->input('attachment-files');

		$validator_array = array(
			'comment' => $request->input('comments')
		);
		$validator = Validator::make($validator_array, [
			'comment' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('comment')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('comment');
			return response()->json($res);
		}
		
		if($attach_str != ''){
			$battach = 1;
		}else{
			$battach = 0;
		}
		
		if($id ==''){
			$data = array(
				'comment' => $comment,
				'comments_date' => $comments_date,
				'task_id' => $task_id,
				'staff_id' => $userid,
				'project_id' => $project_id,
				'battach' => $battach,
				'editable' => 1
			);

			$response = Comment::create($data)->id;

			if($response){

				if($attach_str != ''){
					$attach_list = explode("|",$attach_str);
					foreach ($attach_list as $key => $attachment) {

						$last_insetid = $response;
						$last_data_arr = array(
							'attach_title' => $attachment,
							'attachment' => $attachment,
							'attach_date' => $attach_date,
							'comments_id' => $last_insetid,
							'task_id' => $task_id,
							'staff_id' => $userid,
							'project_id' => $project_id
						);

						Attachment::create($last_data_arr);
					}
				}
				
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$data = array(
				'task_group_name' => $task_group_name,
				'project_id' => $project_id
			);
			$response = Comment::where('id', $id)->update($data);
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

	//update Comments
    public function updateComments(Request $request){
		$res = array();
		
		$id = $request->input('comments_id');
		$comment = $request->input('comment');

		$validator_array = array(
			'comment' => $request->input('comment')
		);
		$validator = Validator::make($validator_array, [
			'comment' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('comment')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('comment');
			return response()->json($res);
		}

		$data = array(
			'comment' => $comment
		);
		
		$response = Comment::where('id', $id)->update($data);
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}

		return response()->json($res);
    }
	
	//Delete data for Comment
	public function deleteComment(Request $request){
		
		$res = array();

		$id = $request->comments_id;
		
		if($id != 0){
			
			Attachment::where('comments_id', $id)->delete();
			
			$response = Comment::where('id', $id)->delete();	
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
	
	//Save data for Comments
    public function addAttachment(Request $request){
		$gtext = gtext();
		$timezone = $gtext['timezone'];
		date_default_timezone_set($timezone);
		$attach_date = date("Y-m-d h:i:s");
		
		$res = array();

		$comments_id = $request->input('comments_id');
		$userid = $request->input('userid');
		$task_id = $request->input('task_id');
		$project_id = $request->input('project_id');
		$attach_str = $request->input('attachment-files');

		$attach_list = explode("|",$attach_str);
		foreach ($attach_list as $key => $attachment) {

			$data = array(
				'attach_title' => $attachment,
				'attachment' => $attachment,
				'attach_date' => $attach_date,
				'comments_id' => $comments_id,
				'task_id' => $task_id,
				'staff_id' => $userid,
				'project_id' => $project_id
			);
			
			Attachment::create($data);
		}

		$response = Comment::where('id', $comments_id)->update(array('battach' => 1));
		
		if($response){

			$res['msgType'] = 'success';
			$res['msg'] = __('New Data Added Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data insert failed');
		}

		return response()->json($res);
    }
	
	//update Attach Title
    public function updateAttachTitle(Request $request){
		$res = array();
		
		$attachment_id = $request->input('attachment_id');
		$attach_title = $request->input('attach_title');

		$validator_array = array(
			'attachment_title' => $request->input('attach_title')
		);
		$validator = Validator::make($validator_array, [
			'attachment_title' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('attachment_title')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('attachment_title');
			return response()->json($res);
		}

		$data = array(
			'attach_title' => $attach_title
		);
		
		$response = Attachment::where('id', $attachment_id)->update($data);
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Updated Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data update failed');
		}

		return response()->json($res);
    }
	
	//Delete data for Attach
	public function deleteAttach(Request $request){
		
		$res = array();

		$id = $request->attachment_id;

		$response = Attachment::where('id', $id)->delete();	
		if($response){
			$res['msgType'] = 'success';
			$res['msg'] = __('Data Removed Successfully');
		}else{
			$res['msgType'] = 'error';
			$res['msg'] = __('Data remove failed');
		}

		return response()->json($res);
	}	
}

