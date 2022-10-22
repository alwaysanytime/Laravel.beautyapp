<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class isActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
	{
		
    if (auth()->check()) {
    /* TEPE
			if(auth()->user()->active_id == 2) {
				auth()->logout();
				
				$message = __('You are not active yet. Please contact administrator.');
				
				return redirect()->route('login')->withMessage($message);
			}
*/
    }
    return $next($request);
  }
}
