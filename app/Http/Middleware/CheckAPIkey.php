<?php

namespace App\Http\Middleware;

use Closure;

class CheckAPIkey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roleName)
    {  
     

      if($roleName=="12345")
      { 
         return $next($request);

      }else{
       
        echo "Not autneticate";

      }
   
    }
}
