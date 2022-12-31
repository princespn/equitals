<?php

namespace App\Http\Controllers\userapi;

use Illuminate\Http\Response as Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\Dashboard;
use App\Models\ProjectSettings;
use Config;
use Auth;

class SendotpController extends Controller {

    public function __construct() {
        $this->linkexpire = Config::get('constants.settings.linkexpire');
        $this->statuscode = Config::get('constants.statuscode');
        $this->authKey = Config::get('constants.settings.authKey');
        $this->senderId = Config::get('constants.settings.senderId');
        $this->OTP_interval = Config::get('constants.settings.OTP_interval');
        $this->sms_username = Config::get('constants.settings.sms_username');
        $this->sms_pwd = Config::get('constants.settings.sms_pwd');
        $this->sms_route = Config::get('constants.settings.sms_route');
        $this->emptyArray = (object) array();
    }

    public function sendotponmail($users, $username, $type=null) {
        
        $checotpstatus = Otp::where([['id', '=', $users->id],])->orderBy('entry_time', 'desc')->first();
        $updateOtpSTatus['otp_status'] = 1;
        Otp::where([['id', '=', $users->id],])->update($updateOtpSTatus);
        // dd($checotpstatus);
        if (!empty($checotpstatus)) {
            $entry_time = $checotpstatus->entry_time;
            $out_time = $checotpstatus->out_time;
            $checkmin = date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime($entry_time)));
            $current_time = date('Y-m-d H:i:s');
        }


        /* if(!empty($checotpstatus) && $entry_time!='' && strtotime($checkmin)>=strtotime($current_time) && $checotpstatus->otp_status!='1'){
          $updateData=array();
          $updateData['otp_status']=0;

          $updateOtpSta=Otp::where('id', $users->id)->update($updateData);

          return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'OTP already sent to your mail id', $this->emptyArray);

          }else{ */


        $pagename = "emails.otpsend";
        $subject = "OTP sent successfully";
        $random = rand(100000, 999999);
        $data = array('pagename' => $pagename, 'otp' => $random, 'username' => $users->user_id);

        $mail = sendMail($data, $username, $subject);

        $otpExpireMit=Config::get('constants.settings.otpExpireMit');

        $mytime_new = \Carbon\Carbon::now()->toDateTimeString();
        $expire_time = \Carbon\Carbon::now()->addMinutes($otpExpireMit)->toDateTimeString();
        
        $insertotp = array();
        $insertotp['id'] = $users->id;
        $insertotp['ip_address'] = trim($_SERVER['REMOTE_ADDR']);
        $insertotp['otp'] = md5($random);
        $insertotp['otp_status'] = 0;
        $insertotp['type'] = 'email';
        $insertotp['otpexpire'] = $expire_time;
        $insertotp['entry_time'] = $mytime_new;
        $sendotp = Otp::insert($insertotp);

        $arrData = array();
        // $arrData['id']   = $users->id;
        $arrData['remember_token'] = $users->remember_token;

        $arrData['mailverification'] = 'TRUE';
        $arrData['google2faauth'] = 'FALSE';
        $arrData['mailotp'] = 'TRUE';
        $arrData['mobileverification'] = 'TRUE';
        $arrData['otpmode'] = 'FALSE';
        //$mask_mobile = maskmobilenumber($users->mobile);
        $mask_email = maskEmail($users->email);
        $arrData['email'] = $mask_email;
        //$arrData['mobile'] = $mask_mobile;

        if($type == null)
        {
            return $random;
        }

        return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'OTP sent successfully to your email Id', $random);

        return $sendotp;

        //}  // end of users
    }

//=========================================================
    public function sendotponmobile($users, $username) {

        $checotpstatus = Otp::where([['id', '=', $users->id],])->orderBy('entry_time', 'desc')->first();

        if (!empty($checotpstatus)) {
            $entry_time = $checotpstatus->entry_time;
            $out_time = $checotpstatus->out_time;
            $checkmin = date('Y-m-d H:i:s', strtotime($this->OTP_interval, strtotime($entry_time)));
            $current_time = date('Y-m-d H:i:s');
        }


        if (false/* !empty($checotpstatus) && $entry_time!='' && strtotime($checkmin)>=strtotime($current_time) && $checotpstatus->otp_status!='1' */) {
            $updateData = array();
            $updateData['otp_status'] = 0;

            $updateOtpSta = Otp::where('id', $users->id)->update($updateData);

            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'OTP already sent to your mobile no', $this->emptyArray);
        } else {


            $random = rand(100000, 999999);

            $numbers = urlencode($users->mobile);
            $username = urlencode($this->sms_username);
            $pass = urlencode($this->sms_pwd);
            $route = urlencode($this->sms_route);
            $senderid = urlencode($this->senderId);
            $OTP = $random;
            $msg = '' . $OTP . ' is your verification code ';
            $message = urlencode($msg);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://173.45.76.227/send.aspx?username=" . $username . "&pass=" . $pass . "&route=" . $route . "&senderid=" . $senderid . "&numbers=" . $numbers . "&message=" . $message,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "",
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                // echo "cURL Error #:" . $err;
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Something went wrong,Please try again', $this->emptyArray);
            } else {
                //echo $response;
                $insertotp = array();
                $insertotp['id'] = $users->id;
                $insertotp['ip_address'] = trim($_SERVER['REMOTE_ADDR']);
                $insertotp['otp'] = md5($random);
                $insertotp['otp_status'] = 0;
                $insertotp['type'] = 'mobile';
                $sendotp = Otp::create($insertotp);

                $arrData = array();
                // $arrData['id']   = $users->id;
                $arrData['remember_token'] = $users->remember_token;
                $arrData['mailverification'] = 'TRUE';
                $arrData['google2faauth'] = 'FALSE';
                $arrData['mailotp'] = 'TRUE';
                $arrData['mobileverification'] = 'TRUE';
                $arrData['otpmode'] = 'FALSE';
                $mask_mobile = maskmobilenumber($users->mobile);
                $mask_email = maskEmail($users->email);
                $arrData['email'] = $mask_email;
                $arrData['mobile'] = $mask_mobile;
                return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'OTP sent successfully to your mobile no ', $arrData);

                return $sendotp;
            }
        }  // end of users
    }
    public function sendotpEditUserProfile(Request $request)
    {
        $user = Auth::User();
        // dd($request->type);
        if($request->type == "Withdrawal")
        {         
            $withdraw_status = Auth::User()->withdraw_status;
            if($withdraw_status == 0)
            {
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],"Your withdraw requests stopped please contact with support team", ''); 
            }
            $projectSettings = ProjectSettings::select('withdraw_day','withdraw_start_time','withdraw_status','withdraw_off_msg')->where('status', 1)->first();
            $day = \Carbon\Carbon::now()->format('D');          
            $hrs = \Carbon\Carbon::now()->format('H');
            $date = \Carbon\Carbon::now();
            $currmonth = \Carbon\Carbon::now()->format('m');
            $curryear = \Carbon\Carbon::now()->format("Y");
            $currday = \Carbon\Carbon::now()->format("d");
            $currsecmon =  date("d", strtotime("second monday", mktime(0,0,0,$currmonth,1,$curryear)));
            $currforthmon =  date("d", strtotime("fourth monday", mktime(0,0,0,$currmonth,1,$curryear)));
            $hrs = (int) $hrs;
            $days = array('Mon'=>"Monday",'Tue'=>"Tuesday",'Wed'=>"Wednesday",'Thu'=>"Thursday",'Fri'=>"Friday",'Sat'=>"Saturday",'Sun'=>"Sunday");
            // dd($currday,$currsecmon,$currforthmon);
            if($projectSettings->withdraw_status == "off")
            {
                $msg = 'Thank you for requesting but requests are closed now';
                if($projectSettings->withdraw_off_msg != '' && $projectSettings->withdraw_off_msg != NULL){
                    $msg = $projectSettings->withdraw_off_msg;
                }
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],$msg, ''); 
            } 
           /* if((intval($currday) == intval($currsecmon)) || (intval($currday) == intval($currforthmon)))
            { 

            }else{*/
            //    return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'You can withdraw only on 2nd & 4th '.$days[$projectSettings->withdraw_day]/*.' after '.$projectSettings->withdraw_start_time.' AM'*/, '');                 
           // }
            // if($hrs < $projectSettings->withdraw_start_time){
            //     return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'You can withdraw only on 2nd & 4th '.$days[$projectSettings->withdraw_day].' after '.$projectSettings->withdraw_start_time.' AM', ''); 
            // }
        }
        if($request->type == "balance_transfer")
        {
            $bal = Dashboard::selectRaw('round(working_wallet-working_wallet_withdraw,2) as balance')->where('id',$user->id)->pluck('balance')->first();
            if($bal < 25){
                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Your Working Wallet is having less than 25$. Login from ID which have 25$ and then Try';
                return sendResponse($arrStatus, $arrCode, $arrMessage, ''); 
            }
            /*$projectSettings = ProjectSettings::select('withdraw_day','withdraw_start_time')->where('status', 1)->first();
            $day = \Carbon\Carbon::now()->format('D');          
            $hrs = \Carbon\Carbon::now()->format('H');
            $hrs = (int) $hrs;
            $days = array('Mon'=>"Monday",'Tue'=>"Tuesday",'Wed'=>"Wednesday",'Thu'=>"Thursday",'Fri'=>"Friday",'Sat'=>"Saturday",'Sun'=>"Sunday");
            if($day != $projectSettings->withdraw_day)
            {
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'You can add request only on '.$days[$projectSettings->withdraw_day].' after '.$projectSettings->withdraw_start_time.' AM', ''); 
            } elseif($hrs < $projectSettings->withdraw_start_time){
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'You can add request only on '.$days[$projectSettings->withdraw_day].' after '.$projectSettings->withdraw_start_time.' AM', ''); 
            }*/
        }

        $username = $user->fullname;
        $mail = $user->email;
        //$mobileResponse = $this->sendotponmobile($user,$username);      
        $emailResponse = $this->sendotponmail($user,$mail);

        $whatsappMsg = "Your OTP is -: " . $emailResponse ;
                          
        $countrycode = getCountryCode($user->country);
                
        $mobile = $user->mobile;

        //sendSMS($mobile, $whatsappMsg);
        //sendWhatsappMsg($countrycode, $mobile, $whatsappMsg);

        return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'OTP sent successfully to your email ', '');
    }
}
