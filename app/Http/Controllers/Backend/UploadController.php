<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UploadController extends Controller
{

	public function FileUpload(Request $request){

		$destinationPath = public_path('media');
		$dateTime = date('dmYHis');

		$file = $request->file('FileName');

		//Display File Name
		$FileName = $dateTime.'-'.$file->getClientOriginalName();
//		$FileName = $file->getClientOriginalName();

		//get file extension
		$FileExt = $file->getClientOriginalExtension();

		//Convert uppercase to lowercase
		$Filetype = Str::lower($FileExt);

		//Display File Real Path
		$FileRealPath = $file->getRealPath();

		//Display File Size
		$FileSize = $file->getSize();

		//Display File Mime Type
		$FileMimeType = $file->getMimeType();

		if (file_exists(public_path('media/'.$FileName))) {
			unlink(public_path('media/'.$FileName));
		}

		$msgList = array();

		//The file Check extension
		if (($Filetype == 'jpg') || ($Filetype == 'jpeg') || ($Filetype == 'png') || ($Filetype == 'gif') || ($Filetype == 'PNG') || ($Filetype == 'JPG') || ($Filetype == 'JPEG') || ($Filetype == 'ico')) {
			if($file->move($destinationPath, $FileName)) {

				$msgList["msgType"] = 'success';
				$msgList['msg'] = __('The file uploaded Successfully');
				$msgList["FileName"] = $FileName;

			} else {
				$msgList["msgType"] = 'error';
				$msgList['msg'] = __('Sorry, there was an error uploading your file');
				$msgList["FileName"] = '';
			}
		} else {
			$msgList["msgType"] = 'error';
			$msgList['msg'] = __('Sorry only you can upload jpg, png and gif file type');
			$msgList["FileName"] = '';
		}

		return response()->json($msgList);
	}

	public function attachmentUpload(Request $request){

		$destinationPath = public_path('media');
		$dateTime = date('dmYHis');
		$msgList = array();

        if($request->hasfile('FileName')){
			$FilesName = '';
			$f = 0;

            foreach($request->file('FileName') as $file){

				$FileName = $dateTime.'-'.$file->getClientOriginalName();

				if (file_exists(public_path('media/'.$FileName))) {
					unlink(public_path('media/'.$FileName));
				}

				if($file->move($destinationPath, $FileName)) {
					if ($f++)
						$FilesName .= '|';

					$FilesName .= $FileName;
				}
            }

			$msgList["msgType"] = 'success';
			$msgList['msg'] = __('The file uploaded Successfully');
			$msgList["FileName"] = $FilesName;
        }else {
			$msgList["msgType"] = 'error';
			$msgList['msg'] = __('Sorry, there was an error uploading your file');
			$msgList["FileName"] = '';
		}

		return response()->json($msgList);
	}
}
