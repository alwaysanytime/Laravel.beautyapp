<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){

		if(Auth::check()) {
			if(auth()->user()->role == 1){
				return $next($request);
			}else{
				return redirect('backend/notfound')->with('error', __('You do not have permission to access this page') );
			}
		}else{
			return redirect('/login');
		}
	}
}
