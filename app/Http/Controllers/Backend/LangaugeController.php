<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Language;
use App\Lankeyvalue;
use DataTables;
use File;
use App;

class LangaugeController extends Controller
{
    //languages page load
    public function getLanguagePageLoad(){
        return view('backend.languages');
    }
	
    //languages Keywords page load
    public function getLanguageKeywordsPageLoad(){
        return view('backend.language-keywords');
    }
	
	//Get data for Languages
    public function getLanguagesData(Request $request){
		
		$data = Language::all();
		
		$DataList = DataTables()->of($data)
		->addColumn('serialno', '')
		->addColumn('action', '')
		->make(true);
		
		return $DataList;
	}
	
	//Save data for Languages
    public function saveLanguagesData(Request $request){
		$res = array();
		
		$id = $request->input('RecordId');
		$language_code = $request->input('language_code');
		$old_language_code = $request->input('old_language_code');
		$language_name = $request->input('language_name');
		
		$languageDefault = $request->input('language_default');
        if ($languageDefault == 'true' || $languageDefault == 'on') {
            $language_default = 1;
        } else {
            $language_default = 0;
        }
		
		$validator_array = array(
			'language_code' => $request->input('language_code'),
			'language_name' => $request->input('language_name')
		);
		$rId = $id == '' ? '' : ','.$id;
		$validator = Validator::make($validator_array, [
			'language_code' => 'required|max:30|unique:languages,language_code' . $rId,
			'language_name' => 'required|max:100|unique:languages,language_name' . $rId
		]);

		$errors = $validator->errors();

		if($errors->has('language_code')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('language_code');
			return response()->json($res);
		}
		
		if($errors->has('language_name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('language_name');
			return response()->json($res);
		}

		$data = array(
			'language_code' => $language_code,
			'language_name' => $language_name,
			'language_default' => $language_default
		);
		
		if($language_default == 1){
			DB::update('update languages set language_default = "0"');
		}
		
		if($id ==''){
			$response = Language::create($data);
			if($response){
				self::LanguageKeyValueInsert($language_code);
				
				self::saveJSONFile($language_code);
				
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
				if($language_default == 1){
					locale();
				}
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Language::where('id', $id)->update($data);
			if($response){
				
				DB::update('update lankeyvalues set language_code = "'.$language_code.'" where language_code = ?', [$old_language_code]);
				
				$count = Lankeyvalue::where('language_code','=', $language_code)->count();
				if($count == 0){
					self::LanguageKeyValueInsert($language_code);
					self::saveJSONFile($language_code);
				}
				
				$defaultCount = Language::where('language_default','=','1')->count();
				if($defaultCount == 0){
					DB::update('update languages set language_default = 1 where language_code = ?', ['en']);
				}
				
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
				if($language_default == 1){
					locale();
				}
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}
		
		return response()->json($res);
    }
	
	//Get data for Language by id
    public function getLanguageById(Request $request){

		$id = $request->RecordId;
        $data= Language::where('id', $id)->first();

		return response()->json($data);
	}
	
	//Delete data for Language
	public function deleteLanguage(Request $request){
		
		$res = array();

		$id = $request->RecordId;
		$language_code = $request->language_code;
		
		if($id != ''){
			Lankeyvalue::where('language_code', $language_code)->delete();	
			$response = Language::where('id', $id)->delete();
			if($response){
				
				$locale = session()->get('locale');
				if($locale == $language_code){
					$count = Language::where('language_default','=','1')->count();
					if($count == 0){
						DB::update('update languages set language_default = 1 where language_code = ?', ['en']);
					}
					
					App::setLocale('en');
					session()->put('locale', 'en');
				}
				
				self::deleteJSONFile($language_code);
				
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Removed Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data remove failed');
			}
		}
		
		return response()->json($res);
	}	
	
	//Insert for Langauge key Value
	public function LanguageKeyValueInsert($language_code){
		
		$currentDataTime = Carbon::now();
  
		DB::insert("INSERT INTO lankeyvalues(language_code, language_key, language_value, created_at, updated_at) 
		SELECT '".$language_code."', language_key, language_value, '".$currentDataTime."', '".$currentDataTime."'
		FROM lankeyvalues WHERE language_code = 'en'");
	}
	
	//Get data for Language Keywords
    public function getLanguageKeywordsData(Request $request){
		$language_code = $request->input('language_code');

		$data = Lankeyvalue::where('language_code', $language_code);

		$DataList = DataTables()->of($data)
		->addColumn('serialno', '')
		->addColumn('action', '')
		->make(true);
		
		return $DataList;
	}
	
	//Save data for Language Keywords
    public function saveLanguageKeywordsData(Request $request){
		$res = array();

		$id = $request->input('RecordId');
		$language_code = $request->input('language_code');
		$language_key = $request->input('language_key');
		$language_value = $request->input('language_value');

 		$validator_array = array(
			'language_key' => $request->input('language_key'),
			'language_value' => $request->input('language_value'),
			'language_code' => $request->input('language_code')
		);
		
		$validator = Validator::make($validator_array, [
			'language_key' => 'required',
			'language_value' => 'required',
			'language_code' => 'required'
		]);
		
		$errors = $validator->errors();	
		
		if($errors->has('language_code')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('language_code');
			return response()->json($res);
		}
		
		if($errors->has('language_key')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('language_key');
			return response()->json($res);
		}
		
		if($errors->has('language_value')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('language_value');
			return response()->json($res);
		}

		$data = array(
			'language_code' => $language_code,
			'language_key' => $language_key,
			'language_value' => $language_value
		);
		
		if($id ==''){
			$response = Lankeyvalue::create($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');

				self::saveJSONFile($language_code);
				
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = Lankeyvalue::where('id', $id)->update($data);
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
				self::saveJSONFile($language_code);
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}
		
		return response()->json($res);
    }	
	
	//Get data for Language keywords by id
    public function getLanguageKeywordsById(Request $request){

		$id = $request->RecordId;
        $data= Lankeyvalue::where('id', $id)->first();

		return response()->json($data);
	}
	
	//Delete data for Language Keywords
	public function deleteLanguageKeywords(Request $request){
		
		$res = array();

		$id = $request->RecordId;
		$language_code = $request->language_code;
		
		if($id != ''){
			$response = Lankeyvalue::where('id', $id)->delete();
			if($response){
				
				self::saveJSONFile($language_code);
				
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Removed Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data remove failed');
			}
		}
		
		return response()->json($res);
	}	
	
    private function saveJSONFile($language_code){
        
		if(File::exists(base_path('resources/lang/'.$language_code.'.json'))){
			File::delete(base_path('resources/lang/'.$language_code.'.json'));
        }
		
		$data = array();
		$lanList = Lankeyvalue::where('language_code', $language_code)->get();
		foreach ($lanList as $row){
			$data[$row['language_key']] = $row['language_value'];
		}

        ksort($data);

        $jsonData = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

        file_put_contents(base_path('resources/lang/'.$language_code.'.json'), stripslashes($jsonData));
    }
	
    private function deleteJSONFile($language_code){
        
		if(File::exists(base_path('resources/lang/'.$language_code.'.json'))){
			File::delete(base_path('resources/lang/'.$language_code.'.json'));
        }
    }
	
	//Get data for Language combo
    public function getLanguageCombo(Request $request){
		
        $data  = Language::orderBy('language_name', 'asc')->get();

		return response()->json($data);
	}	
}
