<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Payment;
use App\Project;
use App\User;
use Carbon\Carbon;
use PDF;

class MilestoneController extends Controller
{
    //Milestones page load
    public function getMilestonesPageLoad($id){
		$data['project_id'] = $id;
        return view('backend.milestones', $data);
    }
	
	//Get data for Milestone
    public function getMilestoneData(Request $request){
	
		$project_id = $request->input('project_id');

		$data = DB::table('payments')
				->join('payment_status', 'payments.payment_status_id', '=', 'payment_status.id')
				->select('payments.id', 'payments.payment_method', 'payments.invoice_no', 'payments.title', 'payments.deadline', 'payments.amount', 'payments.project_id', 'payments.payment_status_id', 'payment_status.payment_status')
				->where('payments.project_id', $project_id)
				->get();
		
		for($i=0; $i<count($data); $i++){
			$data[$i]->deadline = Carbon::createFromFormat('Y-m-d H:i:s', $data[$i]->deadline)->format('Y-m-d');
			$data[$i]->amount = number_format($data[$i]->amount);
		}
		
		$DataList = DataTables()->of($data)
		->addColumn('serialno', '')
		->addColumn('action', '')
		->make(true);
		
		return $DataList;
	}	
	
	//Save data for Milestones
    public function saveMilestoneData(Request $request){
		$res = array();
		$getStripeInfo = getStripeInfo();
		$isenable = $getStripeInfo['isenable'];
		$stripe_secret = $getStripeInfo['stripe_secret'];

		$id = $request->input('RecordId');
		$title = $request->input('title');
		$type_amount = $request->input('amount');
		$deadline = $request->input('deadline');
		$payment_status_id = $request->input('payment_status_id');
		$project_id = $request->input('project_id');
		$payment_method = $request->input('payment_method_id');
		$ClientEmail = $request->input('ClientEmail');
		
		$validator_array = array(
			'title' => $request->input('title'),
			'amount' => $request->input('amount'),
			'deadline' => $request->input('deadline'),
			'status' => $request->input('payment_status_id')
		);
		$validator = Validator::make($validator_array, [
			'title' => 'required',
			'amount' => 'required',
			'deadline' => 'required',
			'status' => 'required'
		]);

		$errors = $validator->errors();

		if($errors->has('title')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('title');
			return response()->json($res);
		}
		
		if($errors->has('amount')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('amount');
			return response()->json($res);
		}
		
		if($errors->has('deadline')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('deadline');
			return response()->json($res);
		}
		
		if($errors->has('status')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('status');
			return response()->json($res);
		}
		
		/*==Start of Stripe==*/
		if($payment_method == 'Card'){
			if($isenable == 1){
				// Enter Your Stripe Secret
				\Stripe\Stripe::setApiKey($stripe_secret);
						
				$amount = $type_amount;
				$amount *= 100;
				$amount = (int) $amount;

				$payment_intent = \Stripe\PaymentIntent::create([
					'amount' => $amount,
					'currency' => 'usd',
					'description' => $title,
					'payment_method_types' => ['card']
				]);
				$intent = $payment_intent->client_secret;
			}
		}else{
			$intent = '';
		}
		/*==end of Stripe==*/
		
		if($id ==''){
			
			$Id = Payment::max('id');
			$MaxId = $Id+1;
			$invoice_no = $project_id.$MaxId;
			
			$data = array(
				'invoice_no' => $invoice_no,
				'title' => $title,
				'amount' => $type_amount,
				'deadline' => $deadline,
				'payment_status_id' => $payment_status_id,
				'project_id' => $project_id,
				'payment_method' => $payment_method
			);
			
			$response = Payment::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
				$res['intent'] = $intent;
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
				$res['intent'] = '';
			}
		}else{
			$data = array(
				'title' => $title,
				'amount' => $type_amount,
				'deadline' => $deadline,
				'payment_status_id' => $payment_status_id,
				'project_id' => $project_id,
				'payment_method' => $payment_method
			);
		
			$response = Payment::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
				$res['intent'] = '';
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
				$res['intent'] = '';
			}
		}

		return response()->json($res);
    }
	
	//get Milestone data By Id
    public function getMilestoneById(Request $request){

		$id = $request->id;
        $data = Payment::where('id', $id)->first();
		
		$data->deadline = Carbon::createFromFormat('Y-m-d H:i:s', $data->deadline)->format('Y-m-d');
		
		return response()->json($data);
	}
	
	//Delete data for Milestone
	public function deleteMilestone(Request $request){
		$res = array();
		
		$id = $request->id;

		if($id != ''){
			$response = Payment::where('id', $id)->delete();
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
	
	//Get data for Project Name
    public function getProjectName(Request $request){

		$id = $request->project_id;
        $data= Project::where('id', $id)->first();

		return response()->json($data);
	}
	
	//Get data for Client Name
    public function getClientInfo(Request $request){

		$id = $request->client_id;
        $data= User::where('id', $id)->first();

		return response()->json($data);
	}
	
	//Get data for Invoice
    public function getInvoice(Request $request){
		
		$id = $request->input('id');

		$data = DB::table('payments')
				->join('payment_status', 'payments.payment_status_id', '=', 'payment_status.id')
				->join('projects', 'payments.project_id', '=', 'projects.id')
				->join('users', 'projects.client_id', '=', 'users.id')
				->select('payments.id', 'payments.payment_method', 'projects.project_name', 'users.name', 'users.address', 'payments.invoice_no', 'payments.title', 'payments.deadline', 'payments.amount', 'payment_status.payment_status', 'payments.payment_status_id')
				->where('payments.id', $id)
				->first();
		
		$data->deadline = Carbon::createFromFormat('Y-m-d H:i:s', $data->deadline)->format('Y-m-d');
		$data->amount = number_format($data->amount);
				
		return response()->json($data);
	}
	
    //invoice-pdf page load
    public function getInvoicePdf($id){
		$gtext = gtext();
		
		$data = DB::table('payments')
				->join('payment_status', 'payments.payment_status_id', '=', 'payment_status.id')
				->join('projects', 'payments.project_id', '=', 'projects.id')
				->join('users', 'projects.client_id', '=', 'users.id')
				->select('payments.id', 'payments.payment_method', 'projects.project_name', 'users.name', 'users.address', 'payments.invoice_no', 'payments.title', 'payments.deadline', 'payments.amount', 'payment_status.payment_status', 'payments.payment_status_id')
				->where('payments.id', $id)
				->first();
		
		$data->deadline = Carbon::createFromFormat('Y-m-d H:i:s', $data->deadline)->format('Y-m-d');
		$data->amount = number_format($data->amount);

		if($data->payment_status_id == 1){
			$PaidUnpaid = '<span class="status-paid">'.$data->payment_status.'</span>';
		}else{
			$PaidUnpaid = '<span class="status-unpaid">'.$data->payment_status.'</span>';
		}
		
		//Global Setting
		$company_name = $gtext['company_name'] =='' ? 'TeamWork' : $gtext['company_name'];
		$company_title = $gtext['company_title'] =='' ? 'TeamWork - Project Management System' : $gtext['company_title'];
		$logo = $gtext['logo'] =='' ? public_path('assets/images/logo.png') : public_path('media/'.$gtext['logo']);
		$theme_color = $gtext['theme_color'] =='' ? '#88c136' : $gtext['theme_color'];
		$toMailAddress = $gtext['toMailAddress'] =='' ? 'yourname@gmail.com' : $gtext['toMailAddress'];
		$siteurl = $gtext['siteurl'] =='' ? 'https://www.yourdomain.com' : $gtext['siteurl'];
	
		//set font
		PDF::SetFont('helvetica', '', 10);
		
		//page title
		PDF::SetTitle($data->title);
		
		//add a page
		PDF::AddPage();

		$html ='<style>
		.w-100 {width: 100%;}
		.w-50 {width: 50%;}
		.w-75 {width: 75%;}
		.w-25 {width: 25%;}
		
		table td, table th {
			color: #686868;
			text-decoration: none;
		}
		a {
			color: #686868;
			text-decoration: none;
		}
		table.border td, table.border th {
			border: 1px solid '.$theme_color.';
		}
		table.border-tb td, table.border-tb th {
			border-top: 1px solid '.$theme_color.';
			border-bottom: 1px solid '.$theme_color.';
		}
		table.border-header td {
			border-bottom: 2px solid '.$theme_color.';
		}
		table.border-t td, table.border-t th {
			border-top: 1px solid '.$theme_color.';
		}
		table.border-none td, table.border-none th {
			border: none;
		}
		.company-logo img{
			width: 111px;
			height: 60px;
		}
		td.invoice-name {
			font-size: 30px;
			font-weight: bold;
			text-align: right;
		}
		h3, h4, p.com-address {
			line-height: 10px;
		}
		p {
			line-height: 5px;
		}
		h3 {
			font-size: 16px;
		}
		h4 {
			font-size: 12px;
			margin-bottom: 0px;
			font-weight: 400;
		}
		.status-paid {
			color: #88c136;
			font-weight: bold;
		}
		.status-unpaid{
			color: #f25961;
			font-weight: bold;	
		}	
		</style>
		
		<!--html table -->
		<table class="border-header" width="100%" cellpadding="10" cellspacing="0">
			<tr>
				<td class="w-50"><span class="company-logo"><img src="'.$logo.'"/></span></td>  
				<td class="w-50 invoice-name">Invoice</td>  
			</tr>
		</table>
		<table class="border-none" width="100%" cellpadding="10" cellspacing="0">
			<tr><td class="w-100" align="center"></td></tr>
		</table>
		<table class="border-none" width="100%" cellpadding="6" cellspacing="0">
			<tr>
				<td class="w-50" align="left">
					<h3>Bill From:</h3>
					<h4>'.$data->name.'</h4>
					<p class="com-address">'.$data->address.'</p>
				</td>  
				<td class="w-50" align="right">
					<p><strong>Payment Method</strong>: '.$data->payment_method.'</p>
					<p><strong>Due Date</strong>: '.$data->deadline.'</p>
					<p><strong>Invoice No</strong>: '.$data->invoice_no.'</p>
					<p><strong>Status</strong>: '.$PaidUnpaid.'</p>
				</td>  
			</tr>
		</table>
		<table class="border-none" width="100%" cellpadding="20" cellspacing="0">
			<tr><td class="w-100" align="center"></td></tr>
		</table>
		<table class="border-none" width="100%" cellpadding="6" cellspacing="0">
			<tr>
				<td class="w-50" align="left">
					<h3>To:</h3>
					<h4>'.$company_name.'</h4>
				</td>  
				<td class="w-50" align="right"></td>  
			</tr>
		</table>
		<table class="border-none" width="100%" cellpadding="20" cellspacing="0">
			<tr><td class="w-100" align="center"></td></tr>
		</table>
		<table class="border-none" width="100%" cellpadding="6" cellspacing="0">
			<tr>
				<td colspan="2" class="w-100" align="left">
					<p><strong>Project Name:</strong> '.$data->project_name.'</p>
				</td> 
			</tr>
		</table>
		<table class="border-none" width="100%" cellpadding="20" cellspacing="0">
			<tr><td class="w-100" align="center"></td></tr>
		</table>
		<table class="border-none" width="100%" cellpadding="6" cellspacing="0">
			<tr>
				<td class="w-75" align="left">
					<strong>Milestone</strong>
				</td>  
				<td class="w-25" align="right">
					<strong>Total</strong>
				</td>  
			</tr>
		</table>
		<table class="border-tb" width="100%" cellpadding="6" cellspacing="0">
			<tr>
				<td class="w-75" align="left">
					'.$data->title.' 
				</td>
				<td class="w-25" align="right">
					'. __('Currency').$data->amount.'
				</td>  
			</tr>
		</table>
		<table class="border-none" width="100%" cellpadding="20" cellspacing="0">
			<tr><td class="w-100" align="center"></td></tr>
		</table>
		<table class="border-none" width="100%" cellpadding="6" cellspacing="0">
			<tr>
				<td class="w-50" align="left"></td>  
				<td class="w-50" align="right">
					<strong>Subtotal: '. __('Currency').$data->amount.'</strong>
				</td>  
			</tr>
		</table>
		<table class="border-none" width="100%" cellpadding="70" cellspacing="0">
			<tr><td class="w-100" align="center"></td></tr>
		</table>
		<table class="border-t" width="100%" cellpadding="10" cellspacing="0">
			<tr>
				<td class="w-100" align="center">
					<p>Thank you for your business!</p>
					<p>If you have any questions about this invoice, please contact</p>
					<p>'.$toMailAddress.'</p>
					<p><a href="'.$siteurl.'">'.$siteurl.'</a></p>
				</td>
			</tr>
		</table>';

		//output the HTML content
		PDF::writeHTML($html, true, false, true, false, '');

		//Close and output PDF document
		PDF::Output('invoice-'.$data->invoice_no.'.pdf', 'I');
    }
}
