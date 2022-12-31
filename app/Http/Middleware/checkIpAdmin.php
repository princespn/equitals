<?php

namespace App\Http\Middleware;
use Config;
use App\User;
use Closure;
use Auth;

class checkIpAdmin
{


  /*  */

  /*eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImM4MTdiOTEwNDliZGQwNzI2NTljMWVkMDdlMjMxMThhNjRkZDJiZGRjMGE3MGNkNTZhOTM0ZDBiZmQ3YmYwOWE5NzZhMDYyZjYzMWM4Y2FjIn0.eyJhdWQiOiI2IiwianRpIjoiYzgxN2I5MTA0OWJkZDA3MjY1OWMxZWQwN2UyMzExOGE2NGRkMmJkZGMwYTcwY2Q1NmE5MzRkMGJmZDdiZjA5YTk3NmEwNjJmNjMxYzhjYWMiLCJpYXQiOjE2NDg0NzcyNDgsIm5iZiI6MTY0ODQ3NzI0OCwiZXhwIjoxNjgwMDEzMjQ4LCJzdWIiOiIxMSIsInNjb3BlcyI6WyIqIl19.Op_x64kuP0Z8hlG3vRpupsC4OA6h_NTEAMDZWJh8HgKoYSsR19AcV4KRq0YdMRusOJ8_qpA4n8Z0qdpCpa3ihzr4Rl8dWwWXO2phCmtvW6_EG3Y46DlQyg1T5iO-afe9b38Vic1k75KtfPRcsdqG_5ammsk-S_yoQsA46Wgr6-UsEYkXiTxrglvS47Ax_YieHm33WzaltWwrqX05hPR5MNBeklQSosgAWG2HQndxjdCoPs0lp9CutWRW0uVeLgp4WfI2s5SPxyhiEwYCamdKvTtCHeLVa_rUWFbiWWANOg2zdcB-MfE7y1GW5ZCDZS1YO148w-Aj0ktl0YN9hpe8O62Oo38b96_beQpU9-Ghr7ucmZwVE2nm_ZLhGp3DTUsxjBYnKD1mVlNiMvtHAz3rb1FwVzmV8hOsQS8oFVRUe2I4ZxoQVK04YyO2Mb97QcbLbDuB_OA6Bfj270eNpl4p2NG5-9_gpIKJ4-PgJvtWMja-lCDPp6yfytoBQ-r39pfgh1_RY3PNQGla7FDisUMOE5hwluznIjl2CFkEf_f6OzKrxLo7KvO7s0KPchs-bOWPTDw4zhCUwgUXHBRMaKxHiXxE2tTncPZ1o7iV7Zux42z51oW-NYDjKJG0i4qtpR1aBVVriz5Sb_j9RBZaHGARTfj-3PdUt_z4TfxMqJW_A_g*/


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
        $uid=Auth::user()->id;
       // dd($uid);    
        $ip_address=Auth::user()->ip_address;
        $user_token=Auth::user()->user_token;

        $userData = User::select('type','status','id','user_id')->where('id',$uid)->where('status','Active')->where('type',['Admin','sub-admin'])->first();

      //   $now_ip=getIpAddrssNew();


      //   $headers=apache_request_headers();


      //  $token=isset($headers['authorization'])?md5($headers['authorization']):md5($headers['Authorization']);

       //dd($token);

       // dd($now_ip==$ip_address && $user_token==$token);

     
        if(!empty($userData)) {
          if ($userData->type =='Admin') {
            return $next($request);
            # code...
          }else if ($userData->type =='sub-admin') {
            return $next($request);
            # code...
          } else {
             return sendresponse($statuscode[403]['code'], $statuscode[403]['status'], 'Invalid User', '');
            # code...
          }
          
           // if( ($now_ip==$ip_address) && ($user_token==$token)){
            // dd(1);
            //return $next($request);

            /*}else{

              return sendresponse($statuscode[403]['code'], $statuscode[403]['status'], 'Already login in other device', '');
            }*/
        } else {
            return sendresponse($statuscode[403]['code'], $statuscode[403]['status'], 'Invalid User', '');
        }
    }
}
