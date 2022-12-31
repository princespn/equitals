<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Config;
use App\User;

class AdminMiddleware
{
    /**
     * Handle an incoming request. User must be logged in to do admin check
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   

       // dd(1);
        $statuscode = Config::get('constants.statuscode');
        $userData = User::where('remember_token',$request->input('remember_token'))->where('type','Admin')->first();

        if(count($userData) > 0 && !empty($userData)) {
            return $next($request);
        } else {
            return sendresponse($statuscode[403]['code'], $statuscode[403]['status'], 'Invalid token', '');
        }
    }
}