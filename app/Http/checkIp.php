<?php

namespace App\Http\Middleware;
use Config;
use App\User;
use Closure;

class checkIp
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
        $statuscode = Config::get('constants.statuscode');
        $userData = User::where('remember_token',$request->input('remember_token'))->where('type','')->first();
    //   dd(1);
        if(!empty($userData)) {
            return $next($request);
        } else {
            return sendresponse($statuscode[403]['code'], $statuscode[403]['status'], 'Invalid User', '');
        }
    }
}
