<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp;
use URL;
use Validator;
use Exception;
use Config;
use Auth;
use DB;
use Illuminate\Http\Response as Response;
use Google2FA;
use Crypt;
use GuzzleHttp\Client;
use Hash;
use GrahamCampbell\Throttle\Facades\Throttle;


// use model here
use App\Models\ProjectSettings as ProjectSettingModel;
use App\Models\Otp as OtpModel;
use App\Models\Activitynotification as ActivitynotificationModel;
use App\Models\SecureLoginData as SecureLoginDataModel;
use App\Models\Masterpwd as MasterpwdModel;
use App\Models\AddFailedLoginAttempt as AddFailedLoginAttempt;
use App\User as UserModel;
use App\Models\Dashboard as DashboardModel;



class LoginController extends Controller {


	public function __construct() {
		$this->statuscode = Config::get('constants.statuscode');
        $date = \Carbon\Carbon::now();
        $this->today = $date->toDateTimeString();
    }


public function countrylist() {

        //$getCountry = DB::select('call getCountryWhere');
        // $password=md5('Imuons@14');
        // $checkUserLOgin= DB::select('call UserLogin("pranay","'.$password.'")');
        // $getInvoice= DB::select('call getInvoice("pranay")');
       try{
           //   $query = DB::table('tbl_country_new')->select('*');
        $query = DB::table('country_wize_currency')->select('*')->where('status','=',1);
        
        $getCountry = $query->orderBy('name','desc')->get();
            if (empty($getCountry) && count($getCountry) > 0) {
                //Country not found
                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Country not found'; 
                return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                
            } else {
                //Country found
                $arrData = $getCountry;
                $arrStatus   = Response::HTTP_OK;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Country Found'; 
                return sendResponse($arrStatus,$arrCode,$arrMessage,$arrData);
                
            }
        }catch(Exception $e){
                   
           $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
           $arrCode     = Response::$statusTexts[$arrStatus];
           $arrMessage  = 'Something went wrong,Please try again'; 
           return sendResponse($arrStatus,$arrCode,$arrMessage,'');
        }    
    }

    /**
	 * Function to verify the otp
	 *
	 */
	public function checkOtpAdminLogin(Request $request) {
		$strMessage = trans('user.error');
		$arrOutputData = [];
		try {
			$arrInput = $request->all();
			$otp = trim($arrInput['otp']);
			//$user 	= Auth::user();

			$user= UserModel::where('user_id','=',$arrInput['user_id'])->first();

			if(empty($user)){
                
                $strMessage = 'Check user ID';
				$intCode = Response::HTTP_BAD_REQUEST;
				$strStatus = Response::$statusTexts[$intCode];
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			}else{
             $id = $user->id;

			}
             
			
			
			/*$checotpstatus = OtpModel::where([
			['id','=',$id],*/

			/*['otp','=',md5($otp)]])->orderBy('otp_id', 'desc')->first();*/
			$arrOtpWhere = [[[['id', '=', $id], ['otp', '=', md5($otp)]], ['ip_address', $_SERVER['REMOTE_ADDR']]]];
			$checotpstatus = OtpModel::where($arrOtpWhere)->orderBy('otp_id', 'desc')->first();

			// check otp status 1 - already used otp
			if (empty($checotpstatus)) {

				
				$strMessage = 'Invalid otp for token';
				$intCode = Response::HTTP_BAD_REQUEST;
				$strStatus = Response::$statusTexts[$intCode];
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			}
			if ($checotpstatus->otp_status == 1) {
				// otp already veriied
				$strMessage = trans('user.otpverified');
				$intCode = Response::HTTP_BAD_REQUEST;
				$strStatus = Response::$statusTexts[$intCode];
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			}

			// make otp verify
			//secureLogindata($user->user_id,$user->password,'Login successfully');
			$updateData = array();
			$updateData['otp_status'] = 1; //1 -verify otp
			$updateData['out_time'] = date('Y-m-d H:i:s');
			$updateOtpSta = OtpModel::where('id', $id)->update($updateData);
			if (!empty($updateOtpSta)) {
				// ==============activity notification==========
				$date = \Carbon\Carbon::now();
				$today = $date->toDateTimeString();
				$actdata = array();
				$actdata['id'] = $id;
				$actdata['message'] = 'Login successfully with IP address ( ' . $request->ip() . ' ) at time (' . $today . ' ) ';
				$actdata['status'] = 1;
				$actDta = ActivitynotificationModel::create($actdata);

			} // end of else
			$intCode = Response::HTTP_OK;
			$strStatus = Response::$statusTexts[$intCode];
			$strMessage = "Otp Verified.Login successfully";
			return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
		} catch (Exception $e) {
			//return ['response' => $e->getMessage()];
			$intCode = Response::HTTP_BAD_REQUEST;
			$strStatus = Response::$statusTexts[$intCode];
			return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
		}
	}


/**
     * Function to authenticate user and login
     *l
     * @return \Illuminate\Http\Response 
     */
    public function login(Request $request){  

    	//dd($request->password);
       // Auth::logoutOtherDevices($request->password);
    	
		$arrOutputData = [];
        $strStatus = trans('user.error');
        $arrOutputData['mailverification'] = $arrOutputData['google2faauth'] = $arrOutputData['mailotp'] = $arrOutputData['mobileverification'] = $arrOutputData['otpmode'] = 'FALSE';
        
        try {

            $arrInput = $request->all();
            $baseUrl = URL::to('/');
            $validator = Validator::make($arrInput, [
                        'user_id' => 'required',
                        'password' => 'required'
            ]);
            // check for validation
            if ($validator->fails()) {
                return setValidationErrorMessage($validator);
            }

           /* $date                       = \Carbon\Carbon::now();
			$today                      = $date->toDateTimeString();
			$updateData                 = array();
			$updateData['user_id']      = $arrInput['user_id'];
			$updateData['ip_address']   = $_SERVER['REMOTE_ADDR'];
			$updateData['attempted_at'] = $today ;
			$updateData['message']      = 'For 3 Wrong Attemps, Lockout For 1 min';

			$oldDate = date("Y-m-d H:i:s", strtotime($today) - (5 * 60));
			$hrDate = date("Y-m-d H:i:s", strtotime($today) - (60 * 60));

			
			$countlogin = AddFailedLoginAttempt::where([['user_id', '=', $arrInput['user_id']],['ip_address','=',$_SERVER['REMOTE_ADDR']],['attempted_at','>=',$hrDate]])->count('ip_address');
			
			if($countlogin >= 3 )
			{
				$hrcountlogin = AddFailedLoginAttempt::where([['user_id', '=', $arrInput['user_id']],['ip_address','=',$_SERVER['REMOTE_ADDR']],['attempted_at','>=',$hrDate]])->count('ip_address');
				if($countlogin == 3 )
				{
				    $intCode    = Response::HTTP_BAD_REQUEST;
					$strStatus  = Response::$statusTexts[$intCode];
					$strMessage ='For  Wrong Attemps, Lockout For 1 hour';
					$updateData['time'] = 3600 * 1000 ;
					return sendResponse($intCode, $strStatus, $strMessage,$updateData);
				}else{  

				     $secTime    = $hrcountlogin;
					 $time       = intval($secTime)*3600;
					 $t1         = gmdate("H",$time);
					 $intCode    = Response::HTTP_BAD_REQUEST;
					 $strStatus  = Response::$statusTexts[$intCode];
					 $strMessage ='For  Wrong Attemps, Lockout For '. $t1 . ' hours'; 
					 $updateData['time'] = $time * 1000 ;

				 	return sendResponse($intCode, $strStatus, $strMessage,$updateData);     
				}
			}*/

			$userData = UserModel::select('bcrypt_password','password')->where([['user_id', '=', $arrInput['user_id']], ['type','=','']])->first();

           
            if (empty($userData)) {
                $intCode = Response::HTTP_UNAUTHORIZED;
                $strStatus = Response::$statusTexts[$intCode];
                $strMessage = 'Invalid username';
                return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
            } else {
            	$flag = 0;
                $master_pwd = MasterpwdModel::where([['password','=',md5($arrInput['password'])]])->first();

                if(!empty($master_pwd)){

                   // dd($userData->password);

                        $arrInput['password'] = decrypt($userData->password);
                        $flag = 1;
                    }
                // }else{
                //         $intCode = Response::HTTP_NOT_FOUND;
                //         $strStatus = Response::$statusTexts[$intCode];
                //         $strMessage = 'Logins are stopped till 12th September';
                //         return sendResponse($intCode, $strStatus, $strMessage, array());
                // }
                if (!Hash::check($request->Input('password'), $userData->bcrypt_password) && $flag == 0) {

                	/*else if (!Hash::check($request->Input('password'), $userData->bcrypt_password)) { */
			
					$getCurrentUserLoginIp = getIPAddress();

					$GetDetails = UserModel::where('user_id',$arrInput['user_id'])
								->select('ip_address','invalid_login_attempt','ublock_ip_address_time')->first();
								// dd($checkipaddress);
					$ip_address = $GetDetails->ip_address;
					$getCurrentUserLoginIp = getIPAddress();
						//  dd($getCurrentUserLoginIp);
					if($ip_address == null || $ip_address == '')
					{
						$UpdateIpAddressForFirstTime = UserModel::where('user_id',$arrInput['user_id'])->update(['ip_address' => $getCurrentUserLoginIp]);
						$ip_address = $getCurrentUserLoginIp;
						$updateData1 = array();
						$updateData1['invalid_login_attempt'] =0; 
						$updateData1['ublock_ip_address_time'] = null;
						$updt_touser1 = UserModel::where('user_id',$arrInput['user_id'])->update($updateData1);
					}
					 if($ip_address != $getCurrentUserLoginIp)
					{
						$UpdateIpAddressForFirstTime = UserModel::where('user_id',$arrInput['user_id'])->update(['ip_address' => $getCurrentUserLoginIp]);
						$ip_address = $getCurrentUserLoginIp;
						$updateData2 = array();
						$updateData2['invalid_login_attempt'] =0; 
						$updateData2['ublock_ip_address_time'] = null;
						$updt_touser1 = UserModel::where('user_id',$arrInput['user_id'])->update($updateData2);
					}
					$expire_time = \Carbon\Carbon::now();
					if($GetDetails->ublock_ip_address_time != null && $expire_time < $GetDetails->ublock_ip_address_time)
					{
						 		
								$message = "Login Restricted Till ".$GetDetails->ublock_ip_address_time;

								$intCode 	= Response::HTTP_UNAUTHORIZED;
								$strStatus	= Response::$statusTexts[$intCode];
								$strMessage = $message;
								return sendResponse($intCode, $strStatus, $strMessage,'');	
					}
					
							$updateDataNew = array();
							$updateDataNew['invalid_login_attempt'] = DB::raw('invalid_login_attempt + 1'); 
							$message = "Invalid Password";
							if($GetDetails->invalid_login_attempt >= 2 )
							{
								$temp_var = $GetDetails->invalid_login_attempt + 1;
								switch ($temp_var) {
									case 3:
										$expire_time = \Carbon\Carbon::now()->addHour(1)->toDateTimeString();
										$message = "Invalid Password Attempt For Multiple Times,Login Restricted Till ".$expire_time;
										$updateDataNew['ublock_ip_address_time'] = $expire_time; 
										break;
									case 6:
										$expire_time = \Carbon\Carbon::now()->addHour(2)->toDateTimeString();
										$message = "Invalid Password Attempt For Multiple Times,Login Restricted Till ".$expire_time;
										$updateDataNew['ublock_ip_address_time'] = $expire_time; 
										break;
									case 9:
										$expire_time = \Carbon\Carbon::now()->addHour(3)->toDateTimeString();
										$message = "Invalid Password Attempt For Multiple Times,Login Restricted Till ".$expire_time;
										$updateDataNew['ublock_ip_address_time'] = $expire_time; 
										break;
									case 12:
										$expire_time = \Carbon\Carbon::now()->addHour(4)->toDateTimeString();
										$message = "Invalid Password Attempt For Multiple Times,Login Restricted Till ".$expire_time;
										$updateDataNew['ublock_ip_address_time'] = $expire_time; 
										break;
									case 15:
										$expire_time = \Carbon\Carbon::now()->addHour(5)->toDateTimeString();
										$message = "Invalid Password Attempt For Multiple Times,Login Restricted Till ".$expire_time;
										$updateDataNew['ublock_ip_address_time'] = $expire_time; 
										break;
									case 18:
										$expire_time = \Carbon\Carbon::now()->addHour(6)->toDateTimeString();
										$message = "Invalid Password Attempt For Multiple Times,Login Restricted Till ".$expire_time;
										$updateDataNew['ublock_ip_address_time'] = $expire_time; 
										break;
									default:
									// $expire_time = \Carbon\Carbon::now()->addHour(1)->toDateTimeString();
									// $updateDataNew['invalid_login_attempt'] = DB::raw('invalid_login_attempt + 1'); 
									 
								}
								$getUsersCount = AddFailedLoginAttempt::where([['user_id','=',$request->user_id],['status',0]])->count();

								if($getUsersCount > 0){
									$updateStatus = array();
									$updateStatus['status'] = 2; 
									$updt_status = AddFailedLoginAttempt::where('user_id',$request->user_id)->update($updateStatus);
								}

								$DataLogin = array();
								$DataLogin['user_id'] = $request->user_id;
								$DataLogin['ip_address'] = $getCurrentUserLoginIp;
								$DataLogin['login_count'] = $GetDetails->invalid_login_attempt;
								$DataLogin['remark'] = $message;
								$DataLogin['status'] = 0;
								$insertData = AddFailedLoginAttempt::create($DataLogin);
								// $updateDataNew['ublock_ip_address_time'] = $expire_time;
									
							}	

							$updt_touser = UserModel::where('user_id',$request->user_id)->update($updateDataNew);
							

								$intCode 	= Response::HTTP_UNAUTHORIZED;
								$strStatus	= Response::$statusTexts[$intCode];
								$strMessage = $message;
								return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
							
			/*}*/
                       /* $intCode = Response::HTTP_UNAUTHORIZED;
                        $strStatus = Response::$statusTexts[$intCode];
                        $strMessage = 'Invalid password';
                        $actDta     = AddFailedLoginAttempt::create($updateData);
                        return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);*/
                }
                
                // check user status
                $arrWhere = [['user_id', $arrInput['user_id']], ['status', 'Active']];
                $userDataActive = UserModel::select('bcrypt_password')->where($arrWhere)->first();
                if(empty($userDataActive)) {
                    $intCode = Response::HTTP_UNAUTHORIZED;
                    $strStatus = Response::$statusTexts[$intCode];
                    $strMessage = 'User is inactive,Please contact to admin';
                    return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
                }
            }


            $user_exists= UserModel::select('ublock_ip_address_time')->where('user_id',$arrInput['user_id'])->where('type','')->first();
			if(!empty($user_exists))
				{
					 
					if($user_exists->ublock_ip_address_time != null)
					{
							$expire_time = \Carbon\Carbon::now()->toDateTimeString();
							if($expire_time > $user_exists->ublock_ip_address_time)
							{
								$updateData=array(); 
								$updateData['ublock_ip_address_time'] = null;
								$updateData['invalid_login_attempt'] = 0;
								$updateData=UserModel::where('user_id',$arrInput['user_id'])->update($updateData);
							}
							else
							{
								return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Login Restricted Till'.$user_exists->ublock_ip_address_time,'');
							}
					}
				}
          
            $http = new Client();
			$response = $http->post($baseUrl . '/oauth/token', [
				'form_params' => [
					'grant_type' => 'password',
					'client_id' => "6",
					'client_secret' => "e4s9XtT5RlyXqNAdZYK5xgnSs6xh5NX76NviQ2TH",
					/* 'client_id' => env('CLIENT_ID'),
					'client_secret' => env('CLIENT_SECRETE'), */
					'username' => $arrInput['user_id'],
					'password' => $arrInput['password'],
					'scope' => '*',
					//'code' => $request->code
				],
			]);
			$intCode = Response::HTTP_OK;
			$strMessage = "Login successful.";
			$strStatus = Response::$statusTexts[$intCode];
			//print_r($response); die;
			$passportResponse = json_decode((string) $response->getBody());
			//dd($passportResponse);
			$client = new GuzzleHttp\Client;
			$userRequest = $client->request('GET', $baseUrl.'/api/user', [
			'headers' => [
			'Accept' => 'application/json',
			'Authorization' => 'Bearer '.$passportResponse->access_token,
			],
			]);
			$userData = json_decode((string) $userRequest->getBody());
			//dd($user);
			$strTok = $passportResponse->access_token;
			$arrOutputData['access_token'] = $strTok;
			//check for master password
			//check for master password
			$user = $userData;

            if (!empty($user)) {
                $arrOutputData['mobileverification'] = 'TRUE';
                $arrOutputData['mailverification'] = 'TRUE';
                $arrOutputData['google2faauth'] = 'FALSE';
                $arrOutputData['mailotp'] = 'FALSE';
                $arrOutputData['otpmode'] = 'FALSE';
                $arrOutputData['master_pwd'] = 'FALSE';
                $date = \Carbon\Carbon::now();
                $today = $date->toDateTimeString();
               /*  $actdata = array();
                $actdata['id'] = $user->id;
                $actdata['message'] = 'Login successfully with IP address ( ' . $request->ip() . ' ) at time (' . $today . ' ) ';
                $actdata['status'] = 1;
                $actDta = ActivitynotificationModel::create($actdata); */
                if (!empty($master_pwd)) {
                    $arrOutputData['user_id'] = $user->user_id;
                    $arrOutputData['password'] = $arrInput['password'];
                    $arrOutputData['master_pwd'] = 'TRUE';
                } else {
					$projectSetting = ProjectSettingModel::first();
                    //dd($projectSetting->otp_status);
                    if (!empty($projectSetting) && ($projectSetting->otp_status == 'on')) {
						// if google 2 fa is enable then dont issue OTP
						//dd($user->google2fa_status);
                         if($user->google2fa_status=='enable') {

                          $arrOutputData['google2faauth']   		= 'TRUE';
                          } else { 
                        // issue token
                       
                        $otpMode = '';
                        if ($user->type != 'Admin') {
                            if (isset($arrInput['otp']) && $arrInput['otp'] == 'mail') {
                                $otpMode = 'email';
                            }
                            if (isset($arrInput['otp']) && $arrInput['otp'] == 'mobile') {
                                $otpMode = 'email';
                            }
                        } else {
                                                       
                            $otpMode = 'mobile';
                            $arrOutputData['google2faauth'] = 'TRUE';
                        }

                        if ($otpMode != '') {

                            $arrOutputData = $this->sendOtp($user, $otpMode);
                            $strMessage = "Login successful.";
                        }
                        	}
                    }
                }
                 

                
                $ip_address=getIpAddrssNew();
                $user_token='Bearer '.$passportResponse->access_token;
                UserModel::where('user_id',$user->user_id)->update(array('user_token' => md5($user_token),'ip_address'=>$ip_address));


                $arrOutputData['access_token'] = $strTok;

            }

            return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
        } catch (Exception $e) {
            dd($e);
            $arrOutputData = [];
            $strMessage = "The user credentials were incorrect";
            $intCode = Response::HTTP_UNAUTHORIZED;
            $strStatus = Response::$statusTexts[$intCode];
            return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
        }
    }
    /**
     * Function for Logout
     */ 
    public function logout(Request $request){
    	$strStatus 		= trans('user.error');
    	$arrOutputData    = [];
    	try {
    		if (Auth::check()) {
		       Auth::user()->AauthAcessToken()->delete();
		    }
    		//$request->user()->token()->revoke();
    		$intCode 		= Response::HTTP_OK;
			$strStatus		= Response::$statusTexts[$intCode];
			$strMessage 	= "Logout successfully"; 
			return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		} catch (Exception $e) {
    		$intCode 		= Response::HTTP_BAD_REQUEST;
        	$strMessage		= Response::$statusTexts[$intCode];
        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    	}
    }
    /**
     * Function Only User
     */ 
    public function getOnlyUser(Request $request){
        $user = Auth::user();
        if (!empty($user)) {
            $users_list = User::select('id', 'user_id', 'fullname');

             $users_list = $users_list->skip(0)->take(20)->get();

            if (!empty($users_list)) {
                return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Data found', $users_list);
            } else {
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', '');
            }
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User Unaunthenticated', '');
        }
    }
    /**
     * Function to verify the otp
     * 
     */ 
	public function checkOtp(Request $request) {
		$strMessage    = trans('user.error');
    	$arrOutputData    = [];
    	try {
			$arrInput = $request->all();
			$otp 	=trim($arrInput['otp']);
			$user 	= Auth::user();

			$id 	= $user->id;
			$checotpstatus = OtpModel::where([
			['id','=',$id],
			['otp','=',md5($otp)]])->orderBy('otp_id', 'desc')->first();
			// check otp status 1 - already used otp
			if(empty($checotpstatus)){
				$strMessage = 'Invalid otp for token';
				$intCode 	= Response::HTTP_BAD_REQUEST;
	        	$strStatus	= Response::$statusTexts[$intCode];
	        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);

			}
			if($checotpstatus->otp_status == 1){
				// otp already veriied
				$strMessage 	= 'Otp already verified';
				$intCode 		= Response::HTTP_BAD_REQUEST;
	        	$strStatus		= Response::$statusTexts[$intCode];
	        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
			}

			// make otp verify
			secureLogindata($user->user_id,$user->password,'Login successfully');
			$updateData=array();
			$updateData['otp_status']=1; //1 -verify otp
			$updateData['out_time']=date('Y-m-d H:i:s');
			$updateOtpSta =  OtpModel::where('id', $id)->update($updateData);
			if(!empty($updateOtpSta)) {
				// ==============activity notification==========
				$date  = \Carbon\Carbon::now();
				$today = $date->toDateTimeString();
				$actdata = array();     
				$actdata['id'] = $id;
				$actdata['message'] = 'Login successfully with IP address ( '.$request->ip().' ) at time ('.$today.' ) ';
				$actdata['status']=1;
				$actdata['entry_time'] = $this->today;
				$actDta=ActivitynotificationModel::create($actdata);

			} // end of else
			$intCode    = Response::HTTP_OK;
			$strStatus	= Response::$statusTexts[$intCode];
			$strMessage  	= "Otp Verified.Login successfully"; 
			return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		} catch (Exception $e) {
			//return ['response' => $e->getMessage()];
    		$intCode 		= Response::HTTP_BAD_REQUEST;
        	$strStatus		= Response::$statusTexts[$intCode];
        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    	}
	} 

	
	/**
	 * Function to verify the mobile no
	 * 
	 * @param $request : HTTP Request object
	 * 
	 */ 
	public function verifyMobile(Request $request)  { 
		$intCode 		= Response::HTTP_BAD_REQUEST;
        $strStatus 		= Response::$statusTexts[$intCode];
		try {
			$arrOutputData = [];
			$arrInput = $request->all();
			$user = Auth::user();
			$validator 		= Validator::make($arrInput, [
	            'otp'	 	=> 'required',
	        ]);
			## check for validation
	        if($validator->fails()){
	        	$strMessage	= trans('user.error');
	        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
	        }
	        if($user->status == 'Active' || $user->mobileverify_status == '1'){
	        	$strMessage		= "Mobile already verified";
        		return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
			} 

			// user in inactive then verify mobile

			$otp = $arrInput['otp']; 
			if($otp != $user->mobile_otp) { 
		    	secureLogindata($user->user_id,$user->password,'Invalid verification code',$user->mobile);
		        $strMessage = 'Invalid verification code';
		        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		    }        
            $arrOutputData['mobileverification']   	= 'FALSE';
			$arrOutputData['mailverification']   	= 'FALSE';
			$arrOutputData['google2faauth']  		= 'FALSE';
			$arrOutputData['mailotp']   			= 'FALSE';
			$user->status 	= 'Active';
			$user->mobileverify_status = '1';
			$user->save();
			secureLogindata($user->user_id,$user->password,'Mobile verified successfully');        
			$strMessage = "Mobile verified successfully";
			$intCode    = Response::HTTP_OK;
			$strStatus	= Response::$statusTexts[$intCode];
		} catch (Exception $e) {
			$intCode 		= Response::HTTP_INTERNAL_SERVER_ERROR;
        	$strStatus 		= Response::$statusTexts[$intCode];
        	$strMessage		= trans('user.error');
    	}
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
	} 

	/**
	 * Function to send the OTP
	 * 
	 * @param $user 	: User object
	 * 
	 * @param $otpMode 	: OTP Mode(mobile/mail)
	 */ 
	public function sendOtp($users, $otpMode) {
		$arrOutputData  = [];
		$arrOutputData['mailverification'] = $arrOutputData['mobileverification'] = 'FALSE';
		$arrOutputData['google2faauth'] = $arrOutputData['mailotp'] =  $arrOutputData['otpmode'] = 'FALSE';
		DB::beginTransaction();
		try {
			$otpInterval 	= Config::get('constants.settings.OTP_interval');

			$checotpstatus = OtpModel::where([['id','=',$users->id],])->orderBy('entry_time', 'desc')->first();
			if(!empty($checotpstatus)){
	            $entry_time=$checotpstatus->entry_time;
	            $out_time=$checotpstatus->out_time;
	            $checkmin=date('Y-m-d H:i:s',strtotime($otpInterval,strtotime($entry_time)));
	            $current_time=date('Y-m-d H:i:s');
	        }

	        if(false/*!empty($checotpstatus) && $entry_time!='' && strtotime($checkmin)>=strtotime($current_time) && $checotpstatus->otp_status!='1'*/){
	            $updateData=array();
	            $updateData['otp_status']=0;

	            $updateOtpSta 	= OtpModel::where('id', $users->id)->update($updateData);
	           	$intCode       	= Response::HTTP_BAD_REQUEST;
				$strStatus     	= Response::$statusTexts[$intCode];
				$strMessage 	= 'OTP already sent to your mobile no';
	           	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
			}else{
				$random 	= rand(100000,999999); 
				// send otp as 123456 if webservice hit from local
				if($_SERVER['REMOTE_ADDR'] == '192.168.21.173')
					$random = "123456";
				$insertotp 					= array();
	            $insertotp['id'] 			= $users->id;
	            $insertotp['ip_address']	= trim($_SERVER['REMOTE_ADDR']);
	            $insertotp['otp'] 			= md5($random);
	            $insertotp['otp_status']	= 0;
	            $insertotp['type'] 			= $otpMode;
	            if($otpMode == 'mobile') {
	            	$msg 		= $random.' is your verification code';
	            	sendMessage($users, $msg);
	            }
	            else if($otpMode == 'mail' || $otpMode == 'email') {
	            	$arrEmailData['email'] 		= $users->email;
	            	$arrEmailData['subject'] 	= 'Otp';
	            	$arrEmailData['otp']		=$random;
	            	$arrEmailData['template']	= 'email.otp';
	            	$arrEmailData['fullname']	= $users->fullname;
	            	//dD($arrEmailData);
	            	sendEmail($arrEmailData);
	            } else if($otpMode == 'both') {
	            	// send email and message from here
	            	$msg 		= $random.' is your verification code';
	            	sendMessage($users, $msg);
	            	$arrEmailData['email'] 		= $users->email;
	            	$arrEmailData['subject'] 	= 'Otp';
	            	$arrEmailData['otp']		= $random;
	            	$arrEmailData['template']	= 'email.otp';
	            	$arrEmailData['fullname']	= $users->fullname;
	            	sendEmail($arrEmailData);
	            }

				$sendotp  = OtpModel::create($insertotp); 
				$arrOutputData = array();
	            // $arrOutputData['id']   = $users->id;
	            $arrOutputData['mailverification']  	= 'FALSE';
	            $arrOutputData['google2faauth']   		= 'FALSE';
	            $arrOutputData['mailotp']   			=  'TRUE';
	            $arrOutputData['mobileverification']   	= 'FALSE';
	            //$arrOutputData['otpmode']   = ($otpMode == 'mobile') ? 'TRUE' :'FALSE';
	            $arrOutputData['otpmode']   			= $otpMode;
	            $arrOutputData['master_pwd']   			= 'FALSE';
	            // for now overrite with false;
	           // $arrOutputData['otpmode']   = 'FALSE';
	            $mask_mobile 	=	maskMobileNumber($users->mobile); 
	            $mask_email 	=	maskEmail($users->email); 
	            $arrOutputData['email'] = $mask_email;
	            $arrOutputData['mobile'] = $mask_mobile;
	            //$arrOutputData['otp']	=	$random;
			}
		}
		catch (PDOException $e) {
			DB::rollBack();
		} catch (Exception $e) {
			DB::rollBack();
		}
		DB::commit();
    	return $arrOutputData;
	}

	/**
	 * Function to verify the mobile no
	 * 
	 * @param $request : HTTP Request object
	 * 
	 */ 
	public function verifyEmail(Request $request)  { 
		$intCode 		= Response::HTTP_BAD_REQUEST;
        $strStatus 		= Response::$statusTexts[$intCode];
		try {
			$arrOutputData = [];
			$arrInput = $request->all();
			$user = Auth::user();
			$validator 		= Validator::make($arrInput, [
	            'verifytoken'	 	=> 'required',
	        ]);
			## check for validation
	        if($validator->fails()){
	        	$strMessage	= trans('user.error');
	        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
	        }
	        if($user->status == 'Active' || $user->verifyaccountstatus == '1'){
	        	$strMessage		= "Email already verified";
        		return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
			} 

			// user in inactive then verify mobile

			$verifytoken = $arrInput['verifytoken']; 
			if($verifytoken != $user->verifytoken) { 
		    	secureLogindata($user->user_id,$user->password,'Invalid token',$user->mobile);
		        $strMessage = 'Invalid token';
		        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		    }        
            $arrOutputData['mobileverification']   	= 'FALSE';
			$arrOutputData['mailverification']   	= 'FALSE';
			$arrOutputData['google2faauth']  		= 'FALSE';
			$arrOutputData['mailotp']   			= 'FALSE';
			$user->status 	= 'Active';
			$user->verifyaccountstatus = '1';
			$use->status  = 'Active';
			$user->save();
			$dashdata = new DashboardModel;
            $dashdata->id = $user->id; 
            $dashdata->save();

			secureLogindata($user->user_id,$user->password,'Email verified successfully');        
			$strMessage = "Congratulations your email id verified successfully";
			$intCode    = Response::HTTP_OK;
			$strStatus	= Response::$statusTexts[$intCode];
		} catch (Exception $e) {
			$intCode 		= Response::HTTP_INTERNAL_SERVER_ERROR;
        	$strStatus 		= Response::$statusTexts[$intCode];
        	$strMessage		= trans('user.error');
    	}
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
	} 

	/**
	 * Function to resend the otp
	 * 
	 * @param $request : HTTP Request
	 */ 
	public function resendOtp(Request $request){
		$arrOutputData = [];
		try {
			$arrOutputData['mobileverification']= 'FALSE';
			$arrOutputData['mailverification']  = 'FALSE';
			$arrOutputData['google2faauth']   	= 'FALSE';
			$arrOutputData['mailotp']   		= 'FALSE';
			$arrOutputData['otpmode']   		= 'FALSE';
			$arrOutputData['master_pwd']   		= 'FALSE';
			$strMessage = "Error in resending otp";
			$arrInput = $request->all();
			$projectSetting = ProjectSettingModel::first();
			$user = Auth::user();
			if(!empty($projectSetting) && ($projectSetting->otp_status == 'on')) {
				// if google 2 fa is enable then dont issue OTP
				if($user->google2fa_status=='enable') {
					$arrOutputData['google2faauth']   		= 'TRUE';
				} else {
					// issue token
					$otpMode = ''; 
					if($user->type != 'Admin') {
						if(isset($arrInput['otp']) && $arrInput['otp'] == 'mail') {
							$otpMode =  'email';
						}
						if(isset($arrInput['otp']) && $arrInput['otp'] == 'mobile') {
							$otpMode =  'email';
						}
					} else {
						$otpMode = 'mobile';
					}
					if($otpMode != '') {
						$arrOutputData  = $this->sendOtp($user,$otpMode);
						$strMessage = "Otp resent";
					}
				}
			}
			$intCode    = Response::HTTP_OK;
			$strStatus	= Response::$statusTexts[$intCode];
			return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		} catch (Exception $e) {
			$strMessage = "Something went wrong";
			$intCode 	= Response::HTTP_UNAUTHORIZED;
        	$strStatus	= Response::$statusTexts[$intCode];
        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        }
	}
	/*public function testMasterPassword(Request $request){
		$arrInput  = $request->all();
		$arrWhere  = [['user_id', $arrInput['user_id'],['bcrypt_password', bcrypt($arrInput['password'])]]];
		$user = UserModel::where($arrWhere)->first();
		$token = $this->issueToken();
		dd($token);
	}
	public function passEnc(Request $request) {
		$arrInput = $request->all();
		//$arrRespose  = md5Encoder($arrInput['password']);
		$encrypted = Crypt::encrypt($arrInput['password']);
		dd($encrypted);
	}
	public function passDecrypt(Request $request) {
		$arrInput = $request->all();
		//$arrRespose  = md5Decoder($arrInput['password']);
		$decrypted = Crypt::decrypt($arrInput['password']);
		dd($decrypted);
	}*/
}