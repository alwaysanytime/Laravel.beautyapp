<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DataTables;

class DashboardController extends Controller
{
    //Dashboard page load
    public function getDashboardData(){
        return view('backend.dashboard');
    }
	
	//Get data for Total Projects
    public function getTotalProjects(Request $request){
		$gtext = gtext();
		$timezone = $gtext['timezone'];
		date_default_timezone_set($timezone);
		$currDate = date("Y-m-d");
		
		$userid = $request->input('userid');
		$roleid = $request->input('roleid');
		
		//Admin
		if($roleid == 1){
			$query = "SELECT SUM(a.TotalProject) AS TotalProject, SUM(a.Inprogress) AS Inprogress, SUM(a.Completed) AS Completed, SUM(a.TimeOut) AS TimeOut 
			FROM (
			SELECT COUNT(id) TotalProject, 0 Inprogress, 0 Completed, 0 TimeOut 
			FROM projects
			UNION ALL
			SELECT 0 TotalProject, COUNT(id) Inprogress, 0 Completed, 0 TimeOut 
			FROM projects 
			WHERE status_id = 1 
			AND end_date >= '".$currDate."'
			UNION ALL
			SELECT 0 TotalProject, 0 Inprogress, COUNT(id) Completed, 0 TimeOut 
			FROM projects 
			WHERE status_id = 2
			UNION ALL
			SELECT 0 TotalProject, 0 Inprogress, 0 Completed, COUNT(id) TimeOut 
			FROM projects 
			WHERE status_id = 1 AND end_date < '".$currDate."'
			) a";
		
		//Client
		} elseif($roleid == 2) {
			
			$query = "SELECT SUM(a.TotalProject) AS TotalProject, SUM(a.Inprogress) AS Inprogress, SUM(a.Completed) AS Completed, SUM(a.TimeOut) AS TimeOut 
			FROM (
			SELECT COUNT(id) TotalProject, 0 Inprogress, 0 Completed, 0 TimeOut 
			FROM projects
			WHERE client_id = '".$userid."'
			UNION ALL
			SELECT 0 TotalProject, COUNT(id) Inprogress, 0 Completed, 0 TimeOut 
			FROM projects 
			WHERE status_id = 1 
			AND end_date >= '".$currDate."'
			AND client_id = '".$userid."'
			UNION ALL
			SELECT 0 TotalProject, 0 Inprogress, COUNT(id) Completed, 0 TimeOut 
			FROM projects 
			WHERE status_id = 2
			AND client_id = '".$userid."'
			UNION ALL
			SELECT 0 TotalProject, 0 Inprogress, 0 Completed, COUNT(id) TimeOut 
			FROM projects 
			WHERE status_id = 1 AND end_date < '".$currDate."'
			AND client_id = '".$userid."'
			) a";
		
		//Staff
		}else{
			
			$query = "SELECT SUM(a.TotalProject) AS TotalProject, SUM(a.Inprogress) AS Inprogress, SUM(a.Completed) AS Completed, SUM(a.TimeOut) AS TimeOut 
			FROM (
			SELECT COUNT(projects.id) TotalProject, 0 Inprogress, 0 Completed, 0 TimeOut 
			FROM projects
			INNER JOIN project_staff_maps ON projects.id = project_staff_maps.project_id AND project_staff_maps.staff_id = '".$userid."'
			UNION ALL
			SELECT 0 TotalProject, COUNT(projects.id) Inprogress, 0 Completed, 0 TimeOut 
			FROM projects
			INNER JOIN project_staff_maps ON projects.id = project_staff_maps.project_id AND project_staff_maps.staff_id = '".$userid."'
			WHERE status_id = 1 
			AND end_date >= '".$currDate."'
			UNION ALL
			SELECT 0 TotalProject, 0 Inprogress, COUNT(projects.id) Completed, 0 TimeOut 
			FROM projects
			INNER JOIN project_staff_maps ON projects.id = project_staff_maps.project_id AND project_staff_maps.staff_id = '".$userid."'
			WHERE status_id = 2
			UNION ALL
			SELECT 0 TotalProject, 0 Inprogress, 0 Completed, COUNT(projects.id) TimeOut 
			FROM projects
			INNER JOIN project_staff_maps ON projects.id = project_staff_maps.project_id AND project_staff_maps.staff_id = '".$userid."'
			WHERE status_id = 1 AND end_date < '".$currDate."'
			) a";
		}

		$data = DB::select(DB::raw($query));
		
		$dataList = array('data' => array(), 'backgroundColor' => array('#cec62e', '#f25961', '#45a6af'), 'labels' => array(__('Inprogress'), __('Timeout'), __('Completed')));
		foreach ($data as $row) {
			if (!is_null($row->Inprogress)){
				settype($row->Inprogress, "integer");
			}
			
			if (!is_null($row->Completed)){
				settype($row->Completed, "integer");
			}
			
			if (!is_null($row->TimeOut)){
				settype($row->TimeOut, "integer");
			}
			
			array_push($dataList['data'], $row->Inprogress, $row->TimeOut, $row->Completed);
		}
		
		$dataList['dataDiv']= $data;

		return response()->json($dataList);
	}
	
	//Get data for Total Tasks Projects
    public function getTotalTasks(Request $request){
		$gtext = gtext();
		$timezone = $gtext['timezone'];
		date_default_timezone_set($timezone);
		// $currDate = date("Y-m-d");
		$currDate = date("Y-m-d H:i:s");
		
		$userid = $request->input('userid');
		$roleid = $request->input('roleid');
		
		//Admin
		if($roleid == 1){
			$query = "SELECT SUM(a.TotalTasks) AS TotalTasks, SUM(a.DoingTasks) AS DoingTasks, SUM(a.TimeoutTasks) AS TimeoutTasks, SUM(a.CompletedTasks) AS CompletedTasks
			FROM (
			SELECT COUNT(tasks.id) AS TotalTasks, 0 DoingTasks, 0 TimeoutTasks, 0 CompletedTasks 
			FROM tasks
			UNION ALL
			SELECT 0 TotalTasks, COUNT(tasks.id) DoingTasks, 0 TimeoutTasks, 0 CompletedTasks
			FROM tasks 
			WHERE complete_task = 0 
			AND task_date > '".$currDate."'
			UNION ALL
			SELECT 0 TotalTasks, 0 DoingTasks, COUNT(tasks.id) TimeoutTasks, 0 CompletedTasks
			FROM tasks 
			WHERE complete_task = 0 
			AND task_date < '".$currDate."'
			UNION ALL
			SELECT 0 TotalTasks, 0 DoingTasks, 0 TimeoutTasks, COUNT(tasks.id) CompletedTasks
			FROM tasks 
			WHERE complete_task = 1
			) a;";
		
		//Client
		} elseif($roleid == 2) {
			
			$query = "SELECT SUM(a.TotalTasks) AS TotalTasks, SUM(a.DoingTasks) AS DoingTasks, SUM(a.TimeoutTasks) AS TimeoutTasks, SUM(a.CompletedTasks) AS CompletedTasks
			FROM (
			SELECT COUNT(a.id) AS TotalTasks, 0 DoingTasks, 0 TimeoutTasks, 0 CompletedTasks 
			FROM tasks a
			INNER JOIN task_groups b ON a.task_group_id = b.id
			INNER JOIN projects c ON b.project_id = c.id AND c.client_id = '".$userid."'
			UNION ALL
			SELECT 0 TotalTasks, COUNT(a.id) DoingTasks, 0 TimeoutTasks, 0 CompletedTasks
			FROM tasks a
			INNER JOIN task_groups b ON a.task_group_id = b.id
			INNER JOIN projects c ON b.project_id = c.id AND c.client_id = '".$userid."'
			WHERE complete_task = 0 
			AND task_date > '".$currDate."'
			UNION ALL
			SELECT 0 TotalTasks, 0 DoingTasks, COUNT(a.id) TimeoutTasks, 0 CompletedTasks
			FROM tasks a
			INNER JOIN task_groups b ON a.task_group_id = b.id
			INNER JOIN projects c ON b.project_id = c.id AND c.client_id = '".$userid."'
			WHERE complete_task = 0 
			AND task_date < '".$currDate."'
			UNION ALL
			SELECT 0 TotalTasks, 0 DoingTasks, 0 TimeoutTasks, COUNT(a.id) CompletedTasks
			FROM tasks a
			INNER JOIN task_groups b ON a.task_group_id = b.id
			INNER JOIN projects c ON b.project_id = c.id AND c.client_id = '".$userid."'
			WHERE complete_task = 1
			) a;";
		
		//Staff
		}else{
			$query = "SELECT SUM(a.TotalTasks) AS TotalTasks, SUM(a.DoingTasks) AS DoingTasks, SUM(a.TimeoutTasks) AS TimeoutTasks, SUM(a.CompletedTasks) AS CompletedTasks
			FROM (
			SELECT COUNT(a.id) AS TotalTasks, 0 DoingTasks, 0 TimeoutTasks, 0 CompletedTasks 
			FROM tasks a
			INNER JOIN task_groups b ON a.task_group_id = b.id
			INNER JOIN project_staff_maps c ON b.project_id = c.project_id AND c.staff_id = '".$userid."'
			UNION ALL
			SELECT 0 TotalTasks, COUNT(a.id) DoingTasks, 0 TimeoutTasks, 0 CompletedTasks
			FROM tasks a
			INNER JOIN task_groups b ON a.task_group_id = b.id
			INNER JOIN project_staff_maps c ON b.project_id = c.project_id AND c.staff_id = '".$userid."'
			WHERE complete_task = 0 
			AND task_date > '".$currDate."'
			UNION ALL
			SELECT 0 TotalTasks, 0 DoingTasks, COUNT(a.id) TimeoutTasks, 0 CompletedTasks
			FROM tasks a
			INNER JOIN task_groups b ON a.task_group_id = b.id
			INNER JOIN project_staff_maps c ON b.project_id = c.project_id AND c.staff_id = '".$userid."'
			WHERE complete_task = 0 
			AND task_date < '".$currDate."'
			UNION ALL
			SELECT 0 TotalTasks, 0 DoingTasks, 0 TimeoutTasks, COUNT(a.id) CompletedTasks
			FROM tasks a
			INNER JOIN task_groups b ON a.task_group_id = b.id
			INNER JOIN project_staff_maps c ON b.project_id = c.project_id AND c.staff_id = '".$userid."'
			WHERE complete_task = 1
			) a;";
		}

		$data = DB::select(DB::raw($query));

		return response()->json($data);
	}
	
	//Get data for Staff Status
    public function getStaffStatus(Request $request){

		$query = "SELECT SUM(a.TotalActiveStaff) AS TotalActiveStaff, SUM(a.TotalInactiveStaff) AS TotalInactiveStaff
		FROM (SELECT COUNT(id) TotalActiveStaff, 0 TotalInactiveStaff 
		FROM users 
		WHERE /*active_id = 1*/ AND role NOT IN(2)
		UNION ALL
		SELECT 0 TotalActiveStaff, COUNT(id) TotalInactiveStaff 
		FROM users 
		WHERE /*active_id = 2 AND*/ role NOT IN(2)) a;";

		$data = DB::select(DB::raw($query));
		$dataList = array('data' => array(), 'backgroundColor' => array('#45a6af', '#f25961'), 'labels' => array( __('Active'), __('Inactive')));
		foreach ($data as $row) {
			if (!is_null($row->TotalActiveStaff)){
				settype($row->TotalActiveStaff, "integer");
			}
			
			if (!is_null($row->TotalInactiveStaff)){
				settype($row->TotalInactiveStaff, "integer");
			}
			
			array_push($dataList['data'], $row->TotalActiveStaff, $row->TotalInactiveStaff);
		}
		return response()->json($dataList);
	}
	
	//Get data for Project List
    public function getProjectList(Request $request){
		$gtext = gtext();
		$timezone = $gtext['timezone'];
		date_default_timezone_set($timezone);
		$currDate = date("Y-m-d");
		
		$userid = $request->input('userid');
		$roleid = $request->input('roleid');
		
		//Admin
		if($roleid == 1){
			$query = "SELECT 
				a.id,
				a.project_name,
				b.name client_name,
				b.photo client_photo,
				c.name createby_name,
				c.photo createby_photo,
				d.status_name,
				a.start_date,
				a.end_date,
				a.status_id,
				a.createby,
				a.creation_date
			FROM projects a
			INNER JOIN users b ON a.client_id = b.id
			INNER JOIN users c ON a.createby = c.id
			INNER JOIN pstatus d ON a.status_id = d.id";
		
		//Client
		} elseif($roleid == 2) {
			$query = "SELECT 
				a.id,
				a.project_name,
				b.name client_name,
				b.photo client_photo,
				c.name createby_name,
				c.photo createby_photo,
				d.status_name,
				a.start_date,
				a.end_date,
				a.status_id,
				a.createby,
				a.creation_date
			FROM projects a
			INNER JOIN users b ON a.client_id = b.id
			INNER JOIN users c ON a.createby = c.id
			INNER JOIN pstatus d ON a.status_id = d.id
			WHERE a.client_id = '".$userid."'";
		
		//Staff
		}else{
			$query = "SELECT 
				a.id,
				a.project_name,
				b.name client_name,
				b.photo client_photo,
				c.name createby_name,
				c.photo createby_photo,
				d.status_name,
				a.start_date,
				a.end_date,
				a.status_id,
				a.createby,
				a.creation_date
			FROM projects a
			INNER JOIN users b ON a.client_id = b.id
			INNER JOIN users c ON a.createby = c.id
			INNER JOIN pstatus d ON a.status_id = d.id
			INNER JOIN project_staff_maps e ON a.id = e.project_id AND e.staff_id = '".$userid."'";
		}
		$data = DB::select(DB::raw($query));
		
		for($i=0; $i<count($data); $i++){
			$Photos = self::getStaffPhoto($data[$i]->id);

			if($data[$i]->status_id ==2){
				$pstatus = $data[$i]->status_name;
				$statusClass = 'completed';
			}else{
				if($currDate > $data[$i]->end_date){
					$pstatus = 'Timeout';
					$statusClass = 'expirydate';
				}else{
					$pstatus = $data[$i]->status_name;
					$statusClass = 'inprogress';
				}
			}
			
			$data[$i]->Photos = '<ul class="facelist">'.$Photos.'</ul>';
			$data[$i]->status_name = $pstatus;
			$data[$i]->statusClass = $statusClass;
		}
		
		$DataList = DataTables()->of($data)
		->rawColumns(['Photos'])
		->make(true);
		
		return $DataList;
	}
	
	//Get data for Photo
	public function getStaffPhoto($project_id) {
		$data = DB::table('users')
				->join('project_staff_maps', 'users.id', '=', 'project_staff_maps.staff_id')
				->select('users.name', 'users.photo')
				->where('project_staff_maps.project_id', $project_id)
				->where('project_staff_maps.bActive', 1)
				->whereNotIn('users.role', [2])
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
			$photo .= '<li>'.$pCount.'+</li>';
		}
		return $photo;
    }
	
	//Get data for Client List
    public function getDashboardClientList(Request $request){

		$query = "SELECT 
			a.name, 
			a.email, 
			a.phone, 
			a.skype_id, 
			a.facebook_id, 
			a.photo, 
			b.country_name
		FROM users a
		INNER JOIN countries b ON a.country_id = b.id
		WHERE a.role = 2";
		$data = DB::select(DB::raw($query));
		
		$DataList = DataTables()->of($data)->make(true);
		
		return $DataList;
	}
}
