<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//     protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = 'backend/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // var_dump(Hash::make('admin'));die;
    }

	public function logout(Request $request) {
		$gtext = gtext();
		$timezone = $gtext['timezone'];
		date_default_timezone_set($timezone);
		$login_datetime = date("Y-m-d H:i:s");
		$user = auth()->user();

		$user_id = $user->id;
/*
		$aRow = DB::table('chat_login_status')->where('user_id', $user_id)->count();
		$data = array(
			'user_id' => $user_id,
			'login_datetime' => $login_datetime,
			'is_active' => 0,
			'created_at' => $login_datetime,
			'updated_at' => $login_datetime
		);

		if($aRow == 0){
			DB::table('chat_login_status')->insert($data);
		}else{
			DB::table('chat_login_status')->where('user_id', $user_id)->update($data);
		}
*/
		Auth::logout();
		return redirect('/');
	}
}
