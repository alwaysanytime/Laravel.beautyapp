<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class GlobalController extends Controller
{
	
	//user active
	public function userActive(Request $request){
		$res = array();
		$gtext = gtext();
		$mtext = mtext();
		
		$id = $request->input('id');
		$active_id = $request->input('active_id');

		$data = array(
			'active_id' => $active_id
		);

		if($id !=''){
			$response = User::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
				
				if($gtext['isnotification'] == 1){
					
					require 'vendor/autoload.php';
					$mail = new PHPMailer(true);
					
					$StaffObj = User::where('id', $id)->first();
					$StaffArr = $StaffObj->toArray();

					if($active_id == 1){
						//Send mail
						$mail->setFrom($gtext['fromMailAddress'], $gtext['company_name']);
						$mail->addAddress($StaffArr['email'], $StaffArr['name']);
						$mail->isHTML(true);
						$mail->CharSet = "utf-8";
						$mail->Subject = $mtext['Subject - Your account is now active'].' - '.$StaffArr['name'];
						$mail->Body = "<table style='background-color:#f0f0f0;color:#444;padding:40px 0px;line-height:24px;font-size:16px;' border='0' cellpadding='0' cellspacing='0' width='100%'>	
										<tr>
											<td>
												<table style='background-color:#fff;max-width:600px;margin:0 auto;padding:30px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
													<tr><td style='font-size:30px;border-bottom:1px solid #ddd;padding-bottom:15px;font-weight:bold;text-align:center;'>".$gtext['company_name']."</td></tr>
													<tr><td style='font-size:20px;font-weight:bold;padding:30px 0px 5px 0px;'>Hi ".$StaffArr['name']."</td></tr>
													<tr><td>".$mtext['Body - Your account is now active']."</td></tr>
													<tr><td style='padding-top:30px;padding-bottom:50px;'><a href='".$gtext['siteurl']."/login' target='_blank' style='background:".$gtext['theme_color'].";display:block;text-align:center;padding:10px;border-radius:3px;text-decoration:none;color:#fff;'>".__('Login')."</a></td></tr>
													<tr><td style='padding-top:10px;border-top:1px solid #ddd;'>Thank you!</td></tr>
													<tr><td style='padding-top:5px;'><strong>".$gtext['company_name']."</strong></td></tr>
												</table>
											</td>
										</tr>
									</table>";
						$mail->send();
						
					}else if($active_id == 2){
						
						//Send mail
						$mail->setFrom($gtext['fromMailAddress'], $gtext['company_name']);
						$mail->addAddress($StaffArr['email'], $StaffArr['name']);
						$mail->isHTML(true);
						$mail->CharSet = "utf-8";
						$mail->Subject = $mtext['Subject - Your account has been deactivated'].' - '.$StaffArr['name'];
						$mail->Body = "<table style='background-color:#f0f0f0;color:#444;padding:40px 0px;line-height:24px;font-size:16px;' border='0' cellpadding='0' cellspacing='0' width='100%'>	
											<tr>
												<td>
													<table style='background-color:#fff;max-width:600px;margin:0 auto;padding:30px;' border='0' cellpadding='0' cellspacing='0' width='100%'>
														<tr><td style='font-size:30px;border-bottom:1px solid #ddd;padding-bottom:15px;font-weight:bold;text-align:center;'>".$gtext['company_name']."</td></tr>
														<tr><td style='font-size:20px;font-weight:bold;padding:30px 0px 5px 0px;'>Hi ".$StaffArr['name']."</td></tr>
														<tr><td style='padding-top:5px;padding-bottom:50px;'>".$mtext['Body - Your account has been deactivated']."</td></tr>
														<tr><td style='padding-top:10px;border-top:1px solid #ddd;'>Thank you!</td></tr>
														<tr><td style='padding-top:5px;'><strong>".$gtext['company_name']."</strong></td></tr>
													</table>
												</td>
											</tr>
										</table>";
						$mail->send();
					}
				}
				
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}
		
		return response()->json($res);
	}
}
