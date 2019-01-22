<?php

namespace app\http\middleware;

use think\Request;

class Auth
{
    public function handle(Request $request, \Closure $next)
    {
    	if(!$request->session('username')){
    		return redirect(url('login/index'));
	    }
    	return $next($request);
    }
}
