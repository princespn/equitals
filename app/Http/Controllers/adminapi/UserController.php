<?php

namespace App\Http\Controllers\adminapi;

use App\Http\Controllers\adminapi\CommonController;
use App\Http\Controllers\adminapi\LevelController;
use App\Http\Controllers\Controller;
use App\Models\Activitynotification;
use App\Models\AddressTransaction;
use App\Models\AddressTransactionPending;
use App\Models\AllTransaction;
use App\Models\Country;
use App\Models\CurrentAmountDetails;
use App\Models\Dashboard;
use App\Models\Depositaddress;
use App\Models\AddFailedLoginAttempt;
use App\Models\Otp as Otp;
use App\Models\PowerBV;
use App\Models\AddRemoveBusiness;
use App\Models\AddRemoveBusinessUpline;
use App\Models\Representative;
use App\Models\LevelView;
use App\Models\UsersChangeData;
use App\Models\Topup;
use App\Models\Rank;
use App\Models\TodayDetails;
use App\Models\Currency;
use App\Models\UserWithdrwalSetting;
use App\Models\UserBulkUpdate;
use App\Models\UserContestAchievement;
use App\Models\PerfectMoneyMember;

use App\Models\WithdrawPending;
use App\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
	/**
	 * define property variable
	 *
	 * @return
	 */
	public $statuscode, $commonController, $levelController;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(CommonController $commonController, LevelController $levelController) {
		$this->statuscode = Config::get('constants.statuscode');
		$this->OTP_interval = Config::get('constants.settings.OTP_interval');
		$this->sms_username = Config::get('constants.settings.sms_username');
		$this->sms_pwd = Config::get('constants.settings.sms_pwd');
		$this->sms_route = Config::get('constants.settings.sms_route');
		$this->senderId = Config::get('constants.settings.senderId');
		$this->commonController = $commonController;
		$this->levelController = $levelController;
	}

	public function sendotponmail($users, $username,$otp_type=0, $type=null) {
        
        $checotpstatus = Otp::where([['id', '=', $users->id],])->orderBy('entry_time', 'desc')->first();
        $updateOtpSTatus['otp_status'] = 1;
        //Otp::where([['id', '=', $users->id],])->update($updateOtpSTatus);
        // dd($checotpstatus);
        if (!empty($checotpstatus)) {
            $entry_time = $checotpstatus->entry_time;
            $out_time = $checotpstatus->out_time;
            $checkmin = date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime($entry_time)));
            $current_time = date('Y-m-d H:i:s');
        }
        //dd(!empty($checotpstatus),$entry_time != '' , strtotime($checkmin),strtotime($current_time), $checotpstatus->otp_status !='1');

        if(!empty($checotpstatus) && $entry_time!='' && strtotime($checkmin)>=strtotime($current_time) && $checotpstatus->otp_status!='1' && $checotpstatus->otp_type == $otp_type){
          $updateData=array();
          $updateData['otp_status']=0;
          $seconds  = strtotime($checkmin)-strtotime($current_time);

        $mins = floor(($seconds - ((floor($seconds / 3600))*3600)) / 60);
        $secs = floor($seconds % 60);
        if($seconds < 60)
            $time = $secs." seconds";
        else if($seconds < 60*60 )
            $time = $mins." minutes";
          $minutes = round(((strtotime($checkmin)-strtotime($current_time))/60),2);
          $updateOtpSta=Otp::where('otp_id', $checotpstatus->otp_id)->update($updateData);

          return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'OTP already sent to your mail id, please try after '.$time, []);

        }else{ 


        $pagename = "emails.otpsend";
        $subject = "OTP sent successfully";
        $random = rand(1000000000, 9999999999);
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
		$insertotp['otp_type'] = $otp_type;
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
           // return $random;
        }

        return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'OTP sent successfully to your email Id', $random);

        //return $sendotp;

        }  // end of users
    }

	public function sendotpOnMobile(Request $request) {
		try {
			$arrOutputData = [];
			$strStatus = trans('user.error');
			$arrOutputData['mailverification'] = $arrOutputData['google2faauth'] = $arrOutputData['mailotp'] = $arrOutputData['mobileverification'] = $arrOutputData['otpmode'] = 'FALSE';

			$arrInput = $request->all();
			//$baseUrl = URL::to('/');

			$validator = Validator::make($arrInput, [
				'user_id' => 'required',
				'password' => 'required',
			]);
			// check for validation
			if ($validator->fails()) {
				return setValidationErrorMessage($validator);
			}
			// check for the master password
			$arrWhere = [];
			$arrWhere[] = ['user_id', $arrInput['user_id']];

			$userData = User::select('bcrypt_password')
				->where($arrWhere)
				->whereIn('type', ['Admin', 'Subadmin','sub-admin'])
				->first();

			//	dd($userData, $arrInput['user_id']);
			//$master_pwd = MasterpwdModel::where([['password','=',md5($arrInput['password'])]])->first();
			if (empty($userData)) {
				$intCode = Response::HTTP_UNAUTHORIZED;
				$strStatus = Response::$statusTexts[$intCode];
				$strMessage = 'Invalid username';
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			} else if (!Hash::check($request->Input('password'), $userData->bcrypt_password)) {
				$intCode = Response::HTTP_UNAUTHORIZED;
				$strStatus = Response::$statusTexts[$intCode];
				$strMessage = 'Invalid password';
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			} else {
				// check user status
				$arrWhere = [['user_id', $arrInput['user_id']], ['status', 'Active']];
				$userDataActive = User::select('bcrypt_password')->where($arrWhere)->first();
				if (empty($userDataActive)) {
					$intCode = Response::HTTP_UNAUTHORIZED;
					$strStatus = Response::$statusTexts[$intCode];
					$strMessage = 'User is inactive,Please contact to admin';
					return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
				}
				// if master passport matched with input password then replace the password by user password
				/*if(!empty($master_pwd)){
	                    $arrInput['password'] = Crypt::decrypt($userData->encryptpass);
	                    //dd($arrInput);
*/

			}
			$users = User::where('user_id','=',$arrInput['user_id'])->first();
			$username = $users->fullname;
			$checotpstatus = Otp::where([['id', '=', $users->id]])->orderBy('entry_time', 'desc')->first();

			if (!empty($checotpstatus)) {
				$entry_time = $checotpstatus->entry_time;
				$out_time = $checotpstatus->out_time;
				$checkmin = date('Y-m-d H:i:s', strtotime($this->OTP_interval, strtotime($entry_time)));
				$current_time = date('Y-m-d H:i:s');
			}

			if (false/* !empty($checotpstatus) && $entry_time!='' && strtotime($checkmin)>=strtotime($current_time) && $checotpstatus->otp_status!='1' */) {
				$updateData = array();
				$updateData['otp_status'] = 0;

				$updateOtpSta = Otp::where('user_id', $users->id)->update($updateData);

				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'OTP already sent to your mobile no', $this->emptyArray);
			} else {

				$random = rand(100000, 999999);
				$link = Config::get('constants.settings.link');

				$numbers = urlencode($users->mobile);
				$username = urlencode($this->sms_username);
				$pass = urlencode($this->sms_pwd);
				$route = urlencode($this->sms_route);
				$senderid = urlencode($this->senderId);
				$OTP = $random;
				$msg = 'Dear Admin, your page authentication OTP is' . ' ' . $OTP . '\n' . $link;
				$message = urlencode($msg);

				//send otp to mail
				$pagename = "emails.otpsend";
				$subject = "OTP sent successfully";
				$data = array('pagename' => $pagename, 'otp' => $random, 'tomail' => $users->email, 'username' => $users->email);
				// $mail = sendMail($data, $username, $subject);
				//end send otp to mail

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

				/*  if ($err) {
					                                    // echo "cURL Error #:" . $err;
					                                    return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Something went wrong,Please try again', $this->emptyArray);
				*/
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
				// $mask_mobile = maskmobilenumber($users->mobile);
				$mask_email = maskEmail($users->email);
				$arrData['email'] = $mask_email;
				// $arrData['mobile'] = $mask_mobile;

				$intCode = Response::HTTP_OK;
				$strMessage = 'Otp sent successfully to your mobile no.';
				$strStatus = Response::$statusTexts[$intCode];
				return sendResponse($intCode, $strStatus, $strMessage, $arrData);
				// return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'OTP sent successfully to your mobile no. and registered email Id ', $arrData);

				return $sendotp;
				// }

			} // end of users
		} catch (\Exception $e) {
			dd($e);
			$intCode = Response::HTTP_INTERNAL_SERVER_ERROR;
			$strMessage = trans('admin.defaultexceptionmessage');
			$strStatus = Response::$statusTexts[$intCode];
			return sendResponse($intCode, $strStatus, $strMessage, $e);
			// return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Otp Send failed',$e);
			//return true;
		}
	}

	/**
	 * get all user list with reference user id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUsers(Request $request) {
		$arrInput = $request->all();

		$query = User::join('tbl_users as tu2', 'tu2.id', '=', 'tbl_users.ref_user_id')
			->leftjoin('tbl_country_new as cn', 'cn.iso_code', '=', 'tbl_users.country')
			/*->leftjoin('tbl_topup as tp', 'tp.pin', '=', 'tbl_users.id')*/			
			->where('tbl_users.type', '!=', 'Admin');
		/*if(!empty($this->userType->type) && $this->userType->type=='area_admin'){
			            $query = $query->where('tbl_users.area_admin',$this->userType->id);
		*/
		if (isset($arrInput['id'])) {
			$query = $query->where('tbl_users.user_id', $arrInput['id']);
		}
		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_users.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		}
		if (!empty($arrInput['status']) && isset($arrInput['status'])) {
			$query = $query->where('tbl_users.status', $arrInput['status']);
		}
		if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
			//searching loops on fields
			//$fields = getTableColumns('tbl_users');
			$fields = ['tbl_users.user_id', 'tbl_users.fullname', 'tbl_users.email','tbl_users.trn_address', 'tbl_users.status', 'tu2.user_id', 'cn.country', 'tbl_users.mobile', 'tbl_users.google2fa_status', 'tbl_users.btc_address'];
			$search = $arrInput['search']['value'];
			$query = $query->where(function ($query) use ($fields, $search) {
				foreach ($fields as $field) {
					$query->orWhere($field, 'LIKE', '%' . $search . '%');
				}
			});
		}
			$query = $query->orderBy('tbl_users.id', 'desc');

		if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
			$qry = $query;
			$qry = $qry->select('tbl_users.user_id', 'tbl_users.fullname',  'tbl_users.mobile','cn.country','tbl_users.email','tu2.user_id as sponser_id',DB::raw('(CASE tbl_users.position WHEN 1 THEN "Left" WHEN 2 THEN "Right" ELSE "" END) as position'),DB::raw('(CASE tbl_users.withdraw_status WHEN 0 THEN "OFF" WHEN 1 THEN "ON" END ) as withdraw_status'),DB::raw('(CASE tbl_users.auto_withdraw_status WHEN 0 THEN "OFF" WHEN 1 THEN "ON" END ) as auto_withdraw_status'),'tbl_users.entry_time');
			$records = $qry->get();
			$res = $records->toArray();
			if (count($res) <= 0) {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
			}
			$var = $this->commonController->exportToExcel($res,"AllUsers");
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
		}
		$masterPwd = DB::table('tbl_master_password')->pluck('tran_password')->first();
		$url = Config::get('constants.settings.domainpath');

		$query = $query->select('tbl_users.id', 'tbl_users.user_id','tbl_users.paypal_address', 'tbl_users.fullname', 'tbl_users.email', 'tbl_users.entry_time', 'tbl_users.status', 'tu2.user_id as sponser_id', 'cn.country', 'cn.iso_code', 'tbl_users.type', 'tbl_users.mobile', 'tbl_users.auto_withdraw_status', 'tbl_users.withdraw_status', DB::raw('(CASE tbl_users.verifyaccountstatus WHEN 0 THEN "Unverified" WHEN 1 THEN "Verified" ELSE "" END) as verifyaccountstatus'), DB::raw('(CASE tbl_users.mobileverify_status WHEN 0 THEN "Unverified" WHEN 1 THEN "Verified" ELSE "" END) as mobileverify_status'), DB::raw('(CASE tbl_users.position WHEN 1 THEN "Left" WHEN 2 THEN "Right" ELSE "" END) as position'), 'tbl_users.remember_token');
		$totalRecord = $query->count('tbl_users.id');
		$query = $query->orderBy('tbl_users.id', 'desc');
		// $totalRecord = $query->count();
		$arrUserData = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

		$arrData['recordsTotal'] = $totalRecord;
		$arrData['recordsFiltered'] = $totalRecord;
		$arrData['records'] = $arrUserData;

		if ($arrData['recordsTotal'] > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', $arrData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Records not found', '');
		}
	}

	/**
	 * get all franchise user list with reference user id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getNewFranchiseUsers(Request $request) {
		$arrInput = $request->all();

		$query = User::join('tbl_users as tu2', 'tu2.id', '=', 'tbl_users.ref_user_id')
			->leftjoin('tbl_country_new as cn', 'cn.iso_code', '=', 'tbl_users.country')
			->select('tbl_users.id', 'tbl_users.user_id', 'tbl_users.fullname', 'tbl_users.email', 'tbl_users.mobile', 'tbl_users.entry_time', 'tbl_users.status', 'tu2.user_id as sponser_id', 'cn.country', 'cn.iso_code', 'tbl_users.type', 'tbl_users.mobile',/* 'tbl_users.google2fa_status', 'tbl_users.btc_address', 'tbl_users.l_c_count', 'tbl_users.r_c_count', 'tbl_users.l_bv', 'tbl_users.r_bv',*/ DB::raw('(CASE tbl_users.verifyaccountstatus WHEN 0 THEN "Unverified" WHEN 1 THEN "Verified" ELSE "" END) as verifyaccountstatus'), DB::raw('(CASE tbl_users.mobileverify_status WHEN 0 THEN "Unverified" WHEN 1 THEN "Verified" ELSE "" END) as mobileverify_status'), DB::raw('(CASE tbl_users.position WHEN 1 THEN "Left" WHEN 2 THEN "Right" ELSE "" END) as position'), 'tbl_users.remember_token')
			->where('tbl_users.is_franchise', '1');
		/*if(!empty($this->userType->type) && $this->userType->type=='area_admin'){
			            $query = $query->where('tbl_users.area_admin',$this->userType->id);
		*/
		if (isset($arrInput['id'])) {
			$query = $query->where('tbl_users.user_id', $arrInput['id']);
		}
		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_users.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		}
		if (!empty($arrInput['status']) && isset($arrInput['status'])) {
			$query = $query->where('tbl_users.status', $arrInput['status']);
		}
		if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
			//searching loops on fields
			//$fields = getTableColumns('tbl_users');
			$fields = ['tbl_users.user_id', 'tbl_users.fullname', 'tbl_users.email', 'tbl_users.status', 'tu2.user_id', 'cn.country', 'tbl_users.mobile', 'tbl_users.google2fa_status', 'tbl_users.btc_address'];
			$search = $arrInput['search']['value'];
			$query = $query->where(function ($query) use ($fields, $search) {
				foreach ($fields as $field) {
					$query->orWhere($field, 'LIKE', '%' . $search . '%');
				}
			});
		}
		$totalRecord = $query->count('tbl_users.id');
		$query = $query->orderBy('tbl_users.id', 'desc');
		// $totalRecord = $query->count();
		$arrUserData = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

		$arrData['recordsTotal'] = $totalRecord;
		$arrData['recordsFiltered'] = $totalRecord;
		$arrData['records'] = $arrUserData;

		if ($arrData['recordsTotal'] > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', $arrData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Records not found', '');
		}
	}

	public function makeAsFranchise(Request $request){
  $arrInput = $request->all();

    
      try {
 

 $rules = array(
			'user_id' => 'required',
		);
		// run the validation rules on the inputs from the form
		$validator = Validator::make($arrInput, $rules);
		// if the validator fails, redirect back to the form
		$id = Auth::User()->id;
		$arrInput['user_id'] = $id;
			$arrRules            = ['otp' => 'required|min:6|max:6'];
            $verify_otp = verify_Otp($arrInput);

			
            if (!empty($verify_otp)) {
                if ($verify_otp['status'] == 200) {
                } else {
                    $arrStatus = Response::HTTP_NOT_FOUND;;
                    $arrCode = Response::$statusTexts[$arrStatus];
                    $arrMessage = 'Invalid or Expired Otp!';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
                }
            } else {
                $arrStatus = Response::HTTP_NOT_FOUND;;
                $arrCode = Response::$statusTexts[$arrStatus];
                $arrMessage = 'Invalid or Expired Otp!';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
            }
		if ($validator->fails()) {
			$message = $validator->errors();
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User Id required', $message);
		}

    $user=User::select('is_franchise')->where('user_id','=',$arrInput['user_id'])
     ->where('is_franchise','=','1')
     ->first(); 
     //dd($user);
      	 if (!empty($user)) {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'already franchise user', $user);
		} else {
			 $user=User::select('is_franchise','id')
			     ->where('user_id','=',$arrInput['user_id'])
                ->first(); 

                //dd($user->id);

			 User::where('id','=',$user->id)->update(array('is_franchise'=>'3'));

			 //$updateUser = User::where('id', $user->id)->update(['is_franchise' => '1','income_per'=>3]);

			 // /dd($dd,$updateUser,$arrInput['user_id']);
                                       
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'successfully Make franchise', '');
		}
   // dd($user,222);
      	
      } catch (Exception $e) {
       dd($e);
      	return sendresponse($this->statuscode[500]['code'], $this->statuscode[500]['status'], 'Server Error', '');
      	
      }
      

         

	}

	/**
	 * get userDetails
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function userDetails(Request $request) {
		$user = User::select('tbl_users.*', 'tu.user_id as ref_user_id', 'tu.fullname as ref_fullname')->join('tbl_users as tu', 'tu.id', '=', 'tbl_users.ref_user_id')->first();
		if (!empty($user)) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], '', $user);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User not found', '');
		}
	}	
	/**
	 * get userDetails
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function findUser(Request $request) {
		$user = User::select('tbl_users.user_id')
		     ->where('tbl_users.user_id','=',$request->user_id)
		      ->first();
		if (!empty($user)) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], '', $user);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User not found', '');
		}
	}

	/**
	 * get all user list with reference user id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUserDesignation(Request $request) {
		$arrInput = $request->all();

		$query = User::select('id', 'user_id', 'fullname', 'designation')
			->where('type', '')
			->where('status', 'Active');
		if (isset($arrInput['id'])) {
			$query = $query->where('user_id', $arrInput['id']);
		}
		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		}
		if (isset($arrInput['search']['value']) && !empty($arrInput['search']['value'])) {
			//searching loops on fields
			$fields = ['user_id', 'fullname', 'designation'];
			$search = $arrInput['search']['value'];
			$query = $query->where(function ($query) use ($fields, $search) {
				foreach ($fields as $field) {
					$query->orWhere($field, 'LIKE', '%' . $search . '%');
				}
			});
		}
		$totalRecord = $query->count('id');
		$query = $query->orderBy('id', 'desc');
		// $totalRecord = $query->count();
		$arrUserData = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

		$arrData['recordsTotal'] = $totalRecord;
		$arrData['recordsFiltered'] = $totalRecord;
		$arrData['records'] = $arrUserData;

		if ($arrData['recordsTotal'] > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', $arrData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Records not found', '');
		}
	}
	/**
	 * get all user list with reference user id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUserAddresses(Request $request) {
		$arrInput = $request->all();

		$query = User::select('id', 'user_id', 'fullname', 'btc_address', 'entry_time')->where('type', '')->where('status', 'Active');
		if (isset($arrInput['id'])) {
			$query = $query->where('user_id', $arrInput['id']);
		}
		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		}
		// if (isset($arrInput['search']['value']) && !empty($arrInput['search']['value'])) {
		// 	//searching loops on fields
		// 	$fields = ['user_id', 'fullname', 'btc_address', 'ethereum', 'bch_address', 'ltc_address'];
		// 	$search = $arrInput['search']['value'];
		// 	$query = $query->where(function ($query) use ($fields, $search) {
		// 		foreach ($fields as $field) {
		// 			$query->orWhere($field, 'LIKE', '%' . $search . '%');
		// 		}
		// 	});
		// }

		$query = $query->orderBy('id', 'desc');
		$totalRecord = $query->count('id');
		$arrPendings = $query->skip($request->input('start'))->take($request->input('length'))->get();

		$arrData['recordsTotal'] = $totalRecord;
		$arrData['recordsFiltered'] = $totalRecord;
		$arrData['records'] = $arrPendings;
	/*	dd($arrData);*/
		if (!empty($arrPendings) && count($arrPendings) > 0) {
			$arrStatus = Response::HTTP_OK;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Data Found';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
		} else {
			$arrStatus = Response::HTTP_NOT_FOUND;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Data not Found';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}
	/**
	 * get all user list with reference user id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getBlockUsers(Request $request) {
		$arrInput = $request->all();

		$query = User::select('tbl_users.id', 'tbl_users.user_id', 'tbl_users.fullname', 'tbl_users.email', 'tbl_users.mobile', 'tbl_users.btc_address', 'tbl_users.entry_time', 'tu.user_id as sponsor_id', 'tcn.country')
			->join('tbl_users as tu', 'tu.id', '=', 'tbl_users.ref_user_id')
			->leftjoin('tbl_country_new as tcn', 'tcn.iso_code', '=', 'tbl_users.country')
			->where('tbl_users.type', '')
			->where('tbl_users.status', 'Inactive');
		if (isset($arrInput['id'])) {
			$query = $query->where('tbl_users.user_id', $arrInput['id']);
		}
		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_users.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		}
		// if (isset($arrInput['search']['value']) && !empty($arrInput['search']['value'])) {
		// 	//searching loops on fields
		// 	$fields = ['tbl_users.user_id', 'tbl_users.fullname', 'tbl_users.email', 'tbl_users.mobile'];
		// 	$search = $arrInput['search']['value'];
		// 	$query = $query->where(function ($query) use ($fields, $search) {
		// 		foreach ($fields as $field) {
		// 			$query->orWhere($field, 'LIKE', '%' . $search . '%');
		// 		}
		// 	});
		// }
		$query = $query->orderBy('tbl_users.id', 'desc');
		if (isset($arrInput['start']) && isset($arrInput['length'])) {
			$arrData = setPaginate1($query, $arrInput['start'], $arrInput['length']);
		} else {
			$arrData = $query->get();
		}

		// $totalRecord  = $query->count();
		// $arrUserData  = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

		// $arrData['recordsTotal']    = $totalRecord;
		// $arrData['recordsFiltered'] = $totalRecord;
		// $arrData['records']         = $arrUserData;

		// if($arrData['recordsTotal'] > 0){
		if ((isset($arrData['totalRecord']) > 0) || (count($arrData) > 0)) {

			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', $arrData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Records not found', '');
		}
	}




	public function getUnblockUsers(Request $request) {
		$arrInput = $request->all();

		$query = User::select('tbl_users.id', 'tbl_users.user_id', 'tbl_users.fullname', 'tbl_users.email', 'tbl_users.mobile', 'tbl_users.btc_address', 'tbl_users.entry_time', 'tu.user_id as sponsor_id', 'tcn.country','tu.status')
			->join('tbl_users as tu', 'tu.id', '=', 'tbl_users.ref_user_id')
			->leftjoin('tbl_country_new as tcn', 'tcn.iso_code', '=', 'tbl_users.country')
			->where('tbl_users.type', '')
			->where('tbl_users.status', 'Active');
		if (isset($arrInput['id'])) {
			$query = $query->where('tbl_users.user_id', $arrInput['id']);
		}
		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_users.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		}
		if (isset($arrInput['search']['value']) && !empty($arrInput['search']['value'])) {
			//searching loops on fields
			$fields = ['tbl_users.user_id', 'tbl_users.fullname', 'tbl_users.email', 'tbl_users.mobile'];
			$search = $arrInput['search']['value'];
			$query = $query->where(function ($query) use ($fields, $search) {
				foreach ($fields as $field) {
					$query->orWhere($field, 'LIKE', '%' . $search . '%');
				}
			});
		}
		$query = $query->orderBy('tbl_users.id', 'desc');
		if (isset($arrInput['start']) && isset($arrInput['length'])) {
			$arrData = setPaginate1($query, $arrInput['start'], $arrInput['length']);
		} else {
			$arrData = $query->get();
		}

		// $totalRecord  = $query->count();
		// $arrUserData  = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

		// $arrData['recordsTotal']    = $totalRecord;
		// $arrData['recordsFiltered'] = $totalRecord;
		// $arrData['records']         = $arrUserData;

		// if($arrData['recordsTotal'] > 0){
		if ((isset($arrData['totalRecord']) > 0) || (count($arrData) > 0)) {

			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', $arrData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Records not found', '');
		}
	}

	public function getFranchiseUsersReport(Request $request) {
		$arrInput = $request->all();

		$query = User::select('tbl_users.id', 'tbl_users.user_id', 'tbl_users.fullname', 'tbl_users.email', 'tbl_users.mobile', 'tbl_users.btc_address', 'tbl_users.entry_time', 'tu.user_id as sponsor_id', 'tcn.country')
			->join('tbl_users as tu', 'tu.id', '=', 'tbl_users.ref_user_id')
			->leftjoin('tbl_country_new as tcn', 'tcn.iso_code', '=', 'tbl_users.country')
			->where('tbl_users.type', '')
			->where('tbl_users.is_franchise', '1');
		if (isset($arrInput['id'])) {
			$query = $query->where('tbl_users.user_id', $arrInput['id']);
		}
		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_users.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		}
		if (isset($arrInput['search']['value']) && !empty($arrInput['search']['value'])) {
			//searching loops on fields
			$fields = ['tbl_users.user_id', 'tbl_users.fullname', 'tbl_users.email', 'tbl_users.mobile'];
			$search = $arrInput['search']['value'];
			$query = $query->where(function ($query) use ($fields, $search) {
				foreach ($fields as $field) {
					$query->orWhere($field, 'LIKE', '%' . $search . '%');
				}
			});
		}
		$totalRecord = $query->count('tbl_users.id');
		$query = $query->orderBy('tbl_users.id', 'desc');

		// $totalRecord = count($query->get());
		$arrUserData = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

		$arrData['recordsTotal'] = $totalRecord;
		$arrData['recordsFiltered'] = $totalRecord;
		$arrData['records'] = $arrUserData;

		if ($arrData['recordsTotal'] > 0) {

			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', $arrData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Records not found', '');
		}
	}
	/**
	 * update user data with new data by keeping its old logsupdateuserpassword
	 *
	 * @return void
	 */
	public function updateUser(Request $request) {

	try{
		$arrInput = $request->all();
	
	//	dd($arrInput);
		$rules = array(
		//	'id' => 'required',
			'fullname' => 'required|max:30|regex:/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/',
			'email' => 'required|email|max:50',
			'mobile' => 'required|numeric',
			'otp' => 'required',
			/*'city' => 'required',
			'address' => 'required',*/
			// 'country'       => 'required',
			//'btc_address'   => 'required',
		);

		$ruleMessages = array(
			'fullname.regex' => 'Special characters not allowed in fullname.',
		);
		$validator = Validator::make($arrInput, $rules);
		$id = Auth::User()->id;
		$arrInput['user_id'] = $id;
			$arrRules            = ['otp' => 'required|min:10|max:10'];
            $verify_otp = verify_Otp($arrInput);
          //  dd( $verify_otp);

			
            if (!empty($verify_otp)) {
                if ($verify_otp['status'] == 200) {
                } else {
                    $arrStatus = Response::HTTP_NOT_FOUND;;
                    $arrCode = Response::$statusTexts[$arrStatus];
                    $arrMessage = 'Invalid or Expired Otp!';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
                }
            } else {
                $arrStatus = Response::HTTP_NOT_FOUND;;
                $arrCode = Response::$statusTexts[$arrStatus];
                $arrMessage = 'Invalid or Expired Otp!';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
            }

		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {
			//get old data by remember_token
			$oldUserData = User::select('id','user_id','fullname','email','mobile','city','address','country','btc_address','usdt_taddress','ltc_address','doge_address','trn_address','ethereum','solona_address','ripple_address')->where('id', trim($arrInput['id']))->first();
			 // dd($oldUserData);
			
			$withdrawal_currency = Currency::where('tbl_currency.withdrwal_status','1')->get();
			foreach ($withdrawal_currency as $key) 
			{
				$curr_address = UserWithdrwalSetting::where([['id',trim($arrInput['id'])], ['currency',$key['currency']],['status',1]])->pluck('currency_address')->first();
				if(!empty($curr_address)){
					$arrData[''.str_replace("-","_",strtolower($key['currency'])).'_address'] = $curr_address;
				}else{
					$arrData[''.str_replace("-","_",strtolower($key['currency'])).'_address'] = "";
				}
			}
			$btcadd = UserWithdrwalSetting::where('id', $oldUserData->id)->where('currency', 'BTC')->select('currency_address')->first();
			$usdtadd = UserWithdrwalSetting::where('id', $oldUserData->id)->where('currency', 'USDT-TRC20')->select('currency_address')->first();
			$xrpadd = UserWithdrwalSetting::where('id', $oldUserData->id)->where('currency', 'XRP')->select('currency_address')->first();
			$soladd = UserWithdrwalSetting::where('id', $oldUserData->id)->where('currency', 'SOL')->select('currency_address')->first();
			$ltcadd = UserWithdrwalSetting::where('id', $oldUserData->id)->where('currency', 'LTC')->select('currency_address')->first();
			$dogeadd = UserWithdrwalSetting::where('id', $oldUserData->id)->where('currency', 'DOGE')->select('currency_address')->first();
			       //$bnbadd = UserWithdrwalSetting::where('id', $oldUserData->id)->where('currency', 'BNB-BSC')->select('currency_address')->first();
			$trxadd = UserWithdrwalSetting::where('id', $oldUserData->id)->where('currency', 'TRX')->select('currency_address')->first();
			$ethadd = UserWithdrwalSetting::where('id', $oldUserData->id)->where('currency', 'ETH')->select('currency_address')->first();

			if(empty($btcadd))	{
				$arrData['btc_address'] = '';
			}
			else {
				$arrData['btc_address'] = $btcadd->currency_address;
			}
			if(empty($usdtadd))	{
				$arrData['usdt_taddress'] = '';
			}
			else {
				$arrData['usdt_taddress'] = $usdtadd->currency_address;
			}
		       //	if(empty($bnbadd))	{
			       //	$arrData['bnb_address'] = '';
		       //	}
			       //else {
		       //		$arrData['bnb_address'] = $bnbadd->currency_address;
		       //	}

			if(empty($ltcadd))	{
				$arrData['ltc_address'] = '';
			}
			else {
				$arrData['ltc_address'] = $ltcadd->currency_address;
			}
			if(empty($dogeadd))	{
				$arrData['doge_address'] = '';
			}
			else {
				$arrData['doge_address'] = $dogeadd->currency_address;
			}
			if(empty($trxadd))	{
				$arrData['trn_address'] = '';
			}
			else {
				$arrData['trn_address'] = $trxadd->currency_address;
			}
			if(empty($ethadd))	{
				$arrData['ethereum'] = '';
			}
			else {
				$arrData['ethereum'] = $ethadd->currency_address;
			}
			if(empty($soladd))	{
				$arrData['solona_address'] = '';
			}
			else {
				$arrData['solona_address'] = $soladd->currency_address;
			}
			if(empty($xrpadd))	{
				$arrData['ripple_address'] = '';
			}
			else {
				$arrData['ripple_address'] = $xrpadd->currency_address;
			}
			// $withdrawal_currency = Currency::where('tbl_currency.withdrwal_status','1')->get();
			// //dd($withdrawal_currency);
			// foreach ($withdrawal_currency as $key) 
			// {
			// 	$curr_address = UserWithdrwalSetting::where([['id',trim($arrInput['id'])], ['currency',$key['currency']],['status',1]])->pluck('currency_address')->first();
			// //	dd($curr_address);
			// 	if(!empty($curr_address)){
			// 		$arrData[''.str_replace("-","_",strtolower($key['currency'])).'_address'] = $curr_address;
			// 	}else{
			// 		$arrData[''.str_replace("-","_",strtolower($key['currency'])).'_address'] = "";
			// 	}
			// }


			
			if (!empty($oldUserData)) {
				$updated_by = Auth::user()->id;
				$arrInsertLog = [
					'id' => $oldUserData->id,
					'user_id' => $oldUserData->user_id,
					'fullname' => $oldUserData->fullname,
					'email' => $oldUserData->email,
					'mobile' => $oldUserData->mobile,
					'city' => $oldUserData->city,
					'address' => $oldUserData->address,
					'country'       => $oldUserData->country,
					//'status'      => $oldUserData->status,
					// 'bnb_address' => $arrData['bnb_bsc_address'],
					'btc_address' => $arrData['btc_address'],
					'ltc_address' => $arrData['ltc_address'],
					'trn_address' => $arrData['trn_address'],
					'doge_address' => $arrData['doge_address'],
				//	'bnb_address' => $arrData['bnb_address'],
					'usdt_taddress' => $arrData['usdt_taddress'],
					'solona_address' => $arrData['solona_address'],
					'ethereum' => $arrData['ethereum'],
					'ripple_address' => $arrData['ripple_address'],
					// 'usdt_trc20_address' => $arrInput['usdt_trc20_address'],
					// 'ltc_address' => $arrInput['ltc_address'],
					// 'trn_address' => $arrInput['trx_address'],
				//	'doge_address' => $arrInput['doge_address'],
					// 'ethereum' => $oldUserData->ethereum,
					// 'paypal_address' => $oldUserData->paypal_address,
					// 'paypal_address' => $oldUserData->paypal_address,
					// 'perfect_money_address' => $oldUserData->perfect_money_address,
					//'ethereum'    => $oldUserData->ethereum,
					'ip' => $request->ip(),
					'updated_by' => $updated_by,
					'entry_time' => now(),
					//'ref_user_id' =>
				];
				  
					//dd($arrData['usdt_taddress']);
				//save old data
				$saveOldData = UsersChangeData::insertGetId($arrInsertLog);
				if (!empty($saveOldData)) {
					$arrUpdate = [
						'fullname' => $arrInput['fullname'],
						'email' => $arrInput['email'],
						'mobile' => $arrInput['mobile'],
						/*'city' => $arrInput['city'],
						'address' => $arrInput['address'],*/
						'country'       => $arrInput['country'],
						//'status'        => $arrInput['status'],
						// 'btc_address' => $arrInput['btc_address'],
						// 'usdt_trc20_address' => $arrInput['usdt_trc20_address'],
						// 'ltc_address' => $arrInput['ltc_address'],
						// 'trn_address' => $arrInput['trn_address'],
						// 'doge_address' => $arrInput['doge_address'],
						// 'ethereum' => $arrInput['ethereum'],
						// 'bnb_address' => $arrInput['bnb_address'],

						/*'paypal_address' => $arrInput['paypal_address'],
						'perfect_money_address' => $arrInput['perfect_money_address'],*/
						//'ethereum'      => $arrInput['ethereum'],
						//'ref_user_id' =>
					];
					$updateData = User::where('id', $arrInput['id'])->limit(1)->update($arrUpdate);
					//update user with new data
					$addData['id'] = $arrInput['id'];
					$addData['status'] = 1;
					$addData['updated_by'] = Auth::user()->id;
					// if (!empty($request->Input('trn_address'))) {
					// 	$addData['currency'] = "TRX";
					// 	$addData['currency_address'] = trim($request->Input('trn_address'));
					// 	$addTRXStatus = UserWithdrwalSetting::where([['id',$arrInput['id']],['currency',$addData['currency']],['status',1]])->first();
					// 	if(empty($addTRXStatus)){
					// 		if(substr($request->Input('trn_address'), 0,1) == 'T' || substr($request->Input('trn_address'), 0,1) == 't' ){
					// 			if(strlen(trim($request->Input('trn_address'))) >= 26 && strlen(trim($request->Input('trn_address'))) <= 42){

					// 			}else{
					// 				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Tron address is not valid!', '');
					// 			}
					// 		}else{
					// 			$arrStatus = Response::HTTP_NOT_FOUND;
					// 			$arrCode = Response::$statusTexts[$arrStatus];
					// 			$arrMessage = 'TRX Address should be start with "T or t"';
					// 			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					// 		}
					// 		$updateData = UserWithdrwalSetting::create($addData);

					// 		/*$saveOldData = array();
					// 		$saveOldData['id'] = $addData['id'];
					// 		$saveOldData['trn_address'] = $addData['currency_address'];
					// 		$InsertData = UsersChangeData::insert($saveOldData);*/

					// 	}
					// }
					if (!empty($request->Input('btc_address'))) {
						$addData['currency'] = "BTC";
						$addData['currency_address'] = trim($request->Input('btc_address'));
						$addBTCStatus = UserWithdrwalSetting::where([['id',$arrInput['id']],['currency',$addData['currency']],['status',1]])->first();
						if(empty($addBTCStatus)){
							if (strlen(trim($request->Input('btc_address'))) >= 26 && strlen(trim($request->Input('btc_address'))) <= 45) {
								$split_array = str_split(trim($request->Input('btc_address')));
								if ($split_array[0] == '3')
								{
									
								} elseif ($split_array[0] == '1') {
									
								} elseif ($split_array[0] == 'b') {
								
								} else {
									return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'BTC Address should be start with 3 or 1 or b', '');
								}
							} else {
								return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'BTC Address must have minimum 26 and maximum 45 characters', '');
							}
							$updateData = UserWithdrwalSetting::create($addData);

							/*$saveOldData = array();
							$saveOldData['id'] = $addData['id'];
							$saveOldData['btc_address'] = $addData['currency_address'];
							$InsertData = UsersChangeData::insert($saveOldData);*/
						}
					}

					//    dd($request->Input('bnb_address'));
					 
			/*		 	if (!empty($request->Input('bnb_address'))) {
						$addData['currency'] = "BNB-BSC";
						$addData['currency_address'] = trim($request->Input('bnb_address'));
						$addBNBStatus = UserWithdrwalSetting::where([['id',$arrInput['id']],['currency',$addData['currency']],['status',1]])->first();
						if(empty($addBNBStatus)){
							if (strlen(trim($request->Input('bnb_address'))) >= 26 && strlen(trim($request->Input('bnb_address'))) <= 45) {
								$split_array = str_split(trim($request->Input('bnb_address')));
								if ($split_array[0] == '0')
								{
									
								} elseif ($split_array[0] == '1') {
									
								} else {
									return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'BNB-BSC Address should be start with 0 or 1', '');
								}
							} else {
								return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'BNB-BSC Address must have minimum 26 and maximum 45 characters', '');
							}
							$updateData = UserWithdrwalSetting::create($addData);

							/*$saveOldData = array();
							$saveOldData['id'] = $addData['id'];
							$saveOldData['btc_address'] = $addData['currency_address'];
							$InsertData = UsersChangeData::insert($saveOldData);*/
			/*			}
					}      */

					if (!empty($request->Input('solona_address'))) {
						$addData['currency'] = "SOL";
						$addData['currency_address'] = trim($request->Input('solona_address'));
						$addBTCStatus = UserWithdrwalSetting::where([['id',$arrInput['id']],['currency',$addData['currency']],['status',1]])->first();
						if(empty($addBTCStatus)){
							if (strlen(trim($request->Input('solona_address'))) >= 26 && strlen(trim($request->Input('solona_address'))) <= 45) {
								$split_array = str_split(trim($request->Input('solona_address')));
								if ($split_array[0] == 's')
								{
									
								} 
								
								else {
									return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'SOLANA Address should be start with s', '');
								}
							} else {
								return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'SOLANA Address must have minimum 26 and maximum 45 characters', '');
							}
							$updateData = UserWithdrwalSetting::create($addData);

							/*$saveOldData = array();
							$saveOldData['id'] = $addData['id'];
							$saveOldData['btc_address'] = $addData['currency_address'];
							$InsertData = UsersChangeData::insert($saveOldData);*/
						}
					}     
				//	dd($request->Input('usdt_taddress'));
					if (!empty($request->Input('usdt_taddress'))) {
						$addData['currency'] = "USDT";
						$addData['currency_address'] = trim($request->Input('usdt_taddress'));
						$addBTCStatus = UserWithdrwalSetting::where([['id',$arrInput['id']],['currency',$addData['currency']],['status',1]])->first();
						if(empty($addBTCStatus)){
							if (strlen(trim($request->Input('usdt_taddress'))) >= 26 && strlen(trim($request->Input('usdt_taddress'))) <= 45) {
								$split_array = str_split(trim($request->Input('usdt_taddress')));
								if ($split_array[0] == 'T')
								{
									
								} 
								elseif ($split_array[0] == 't') {
									
								 } 
								 else {
									return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'USDT Address should be start with T or t', '');
								}
							} else {
								return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'USDT Address must have minimum 26 and maximum 45 characters', '');
							}
							$updateData = UserWithdrwalSetting::create($addData);

							/*$saveOldData = array();
							$saveOldData['id'] = $addData['id'];
							$saveOldData['btc_address'] = $addData['currency_address'];
							$InsertData = UsersChangeData::insert($saveOldData);*/
						}
					}  
					if (!empty($request->Input('trn_address'))) {
						$addData['currency'] = "TRX";
						$addData['currency_address'] = trim($request->Input('trn_address'));
						$addBTCStatus = UserWithdrwalSetting::where([['id',$arrInput['id']],['currency',$addData['currency']],['status',1]])->first();
						if(empty($addBTCStatus)){
							if (strlen(trim($request->Input('trn_address'))) >= 26 && strlen(trim($request->Input('trn_address'))) <= 45) {
								$split_array = str_split(trim($request->Input('trn_address')));
								if ($split_array[0] == 'T')
								{
									
								} 
								 elseif ($split_array[0] == 't') {
									
								 } 
								 else {
									return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'TRON Address should be start with T or t', '');
								}
							} else {
								return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'TRON Address must have minimum 26 and maximum 45 characters', '');
							}
							$updateData = UserWithdrwalSetting::create($addData);

							/*$saveOldData = array();
							$saveOldData['id'] = $addData['id'];
							$saveOldData['btc_address'] = $addData['currency_address'];
							$InsertData = UsersChangeData::insert($saveOldData);*/
						}
					}
					if (!empty($request->Input('doge_address'))) {
						$addData['currency'] = "DOGE";
						$addData['currency_address'] = trim($request->Input('doge_address'));
						$addBTCStatus = UserWithdrwalSetting::where([['id',$arrInput['id']],['currency',$addData['currency']],['status',1]])->first();
						if(empty($addBTCStatus)){
							if (strlen(trim($request->Input('doge_address'))) >= 26 && strlen(trim($request->Input('doge_address'))) <= 45) {
								$split_array = str_split(trim($request->Input('doge_address')));
								if ($split_array[0] == 'd')
								{
									
								} 
								
								 else {
									return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'DOGE Address should be start with d', '');
								}
							} else {
								return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'DOGE Address must have minimum 26 and maximum 45 characters', '');
							}
							$updateData = UserWithdrwalSetting::create($addData);

							/*$saveOldData = array();
							$saveOldData['id'] = $addData['id'];
							$saveOldData['btc_address'] = $addData['currency_address'];
							$InsertData = UsersChangeData::insert($saveOldData);*/
						}
					}      
					if (!empty($request->Input('ripple_address'))) {
						$addData['currency'] = "XRP";
						$addData['currency_address'] = trim($request->Input('ripple_address'));
						$addBTCStatus = UserWithdrwalSetting::where([['id',$arrInput['id']],['currency',$addData['currency']],['status',1]])->first();
						if(empty($addBTCStatus)){
							if (strlen(trim($request->Input('ripple_address'))) >= 26 && strlen(trim($request->Input('ripple_address'))) <= 45) {
								$split_array = str_split(trim($request->Input('ripple_address')));
								if ($split_array[0] == 'r')
								{
									
								} 
								 else {
									return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'XRP Address should be start with r', '');
								}
							} else {
								return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'XRP Address must have minimum 26 and maximum 45 characters', '');
							}
							$updateData = UserWithdrwalSetting::create($addData);

							/*$saveOldData = array();
							$saveOldData['id'] = $addData['id'];
							$saveOldData['btc_address'] = $addData['currency_address'];
							$InsertData = UsersChangeData::insert($saveOldData);*/
						}
					}      
					if (!empty($request->Input('ltc_address'))) {
						$addData['currency'] = "LTC";
						$addData['currency_address'] = trim($request->Input('ltc_address'));
						$addBTCStatus = UserWithdrwalSetting::where([['id',$arrInput['id']],['currency',$addData['currency']],['status',1]])->first();
						if(empty($addBTCStatus)){
							if (strlen(trim($request->Input('ltc_address'))) >= 26 && strlen(trim($request->Input('ltc_address'))) <= 45) {
								$split_array = str_split(trim($request->Input('ltc_address')));
								if ($split_array[0] == 'L')
								{
									
								}  elseif ($split_array[0] == 'M') {
								
								}

								elseif ($split_array[0] == 'l' && $split_array[1] == 't' && $split_array[2] == 'c'  && $split_array[3] == '1') {

								}

								 else {
									return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Litecoin Address should be start with L or M or ltc1', '');
								}
							} else {
								return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Litecoin Address must have minimum 26 and maximum 45 characters', '');
							}
							$updateData = UserWithdrwalSetting::create($addData);

							/*$saveOldData = array();
							$saveOldData['id'] = $addData['id'];
							$saveOldData['btc_address'] = $addData['currency_address'];
							$InsertData = UsersChangeData::insert($saveOldData);*/
						}
					}      
						if (!empty($request->Input('ethereum'))) {
						$addData['currency'] = "ETH";
						$addData['currency_address'] = trim($request->Input('ethereum'));
						$addBTCStatus = UserWithdrwalSetting::where([['id',$arrInput['id']],['currency',$addData['currency']],['status',1]])->first();
						if(empty($addBTCStatus)){
							if (strlen(trim($request->Input('ethereum'))) >= 26 && strlen(trim($request->Input('ethereum'))) <= 45) {
								$split_array = str_split(trim($request->Input('ethereum')));
								if ($split_array[0] == '0')
								{
									
								}  elseif ($split_array[1] == 'x') {
								
								}

								 else {
									return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Ethereum Address should be start with 0x', '');
								}
							} else {
								return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Ethereum Address must have minimum 26 and maximum 45 characters', '');
							}
							$updateData = UserWithdrwalSetting::create($addData);

							/*$saveOldData = array();
							$saveOldData['id'] = $addData['id'];
							$saveOldData['btc_address'] = $addData['currency_address'];
							$InsertData = UsersChangeData::insert($saveOldData);*/
						}
					}
			/*		if (!empty($request->Input('bnb_bsc_address'))) {
					$addData['currency'] = "BNB-BSC";
					$addData['currency_address'] = trim($request->Input('bnb_bsc_address'));
					$addBTCStatus = UserWithdrwalSetting::where([['id',$arrInput['id']],['currency',$addData['currency']],['status',1]])->first();
					if(empty($addBTCStatus)){
						if(strlen(trim($request->Input('bnb_bsc_address'))) >= 26 && strlen(trim($request->Input('bnb_bsc_address'))) <= 42){

						}else{
							return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'BNB-BSC address is not valid!', '');
						}
						$updateData = UserWithdrwalSetting::create($addData); */

						/*$saveOldData = array();
						$saveOldData['id'] = $addData['id'];
						$saveOldData['bnb_address'] = $addData['currency_address'];
						$InsertData = UsersChangeData::insert($saveOldData);
					}
				}    */
		/*		if (!empty($request->Input('usdt_trc20_address'))) {
					$addData['currency'] = "USDT-TRC20";
					$addData['currency_address'] = trim($request->Input('usdt_trc20_address'));
					$addBTCStatus = UserWithdrwalSetting::where([['id',$arrInput['id']],['currency',$addData['currency']],['status',1]])->first();
					if(empty($addBTCStatus)){
						if(strlen(trim($request->Input('usdt_trc20_address'))) >= 26 && strlen(trim($request->Input('usdt_trc20_address'))) <= 42){

						}else{
							return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'USDT-TRC20 address is not valid!', '');
						}
						$updateData = UserWithdrwalSetting::create($addData);  */
/*
						$saveOldData = array();
						$saveOldData['id'] = $addData['id'];
						$saveOldData['usdt_trc20_address'] = $addData['currency_address'];
						$InsertData = UsersChangeData::insert($saveOldData);
					}
				}  */

				// if (!empty($request->Input('ltc_address'))) {
				// 	$addData['currency'] = "LTC";
				// 	$addData['currency_address'] = trim($request->Input('ltc_address'));
				// 	$addBTCStatus = UserWithdrwalSetting::where([['id',$arrInput['id']],['currency',$addData['currency']],['status',1]])->first();
				// 	if(empty($addBTCStatus)){
				// 		if(strlen(trim($request->Input('ltc_address'))) >= 26 && strlen(trim($request->Input('ltc_address'))) <= 35){

				// 		}else{
				// 			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Litecoin address is not valid!', '');
				// 		}
				// 		$updateData = UserWithdrwalSetting::create($addData);

				// 		/*$saveOldData = array();
				// 		$saveOldData['id'] = $addData['id'];
				// 		$saveOldData['ltc_address'] = $addData['currency_address'];
				// 		$InsertData = UsersChangeData::insert($saveOldData);*/
				// 	}
				// }

				// if (!empty($request->Input('doge_address'))) {
				// 	$addData['currency'] = "DOGE";
				// 	$addData['currency_address'] = trim($request->Input('doge_address'));
				// 	$addBTCStatus = UserWithdrwalSetting::where([['id',$arrInput['id']],['currency',$addData['currency']],['status',1]])->first();
				// 	if(empty($addBTCStatus)){
				// 		if(strlen(trim($request->Input('doge_address'))) >= 26 && strlen(trim($request->Input('doge_address'))) <= 34){

				// 		}else{
				// 			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Doge address is not valid!', '');
				// 		}
				// 		$updateData = UserWithdrwalSetting::create($addData);

				// 		/*$saveOldData = array();
				// 		$saveOldData['id'] = $addData['id'];
				// 		$saveOldData['doge_address'] = $addData['currency_address'];
				// 		$InsertData = UsersChangeData::insert($saveOldData);*/
				// 	}
				// }
					//update levels of user
					/*if(!empty($arrInput['ref_user_id']) && !empty($user_id)){
	                        $this->levelController->updateLevelView($arrInput['ref_user_id'],$user_id,1);
*/
					if (!empty($updateData)) {
						return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'User data updated successfully.', '');
					} else {
						return sendresponse($this->statuscode[409]['code'], $this->statuscode[409]['status'], 'Data already existed with given inputs.', '');
					}
				} else {
					return sendresponse($this->statuscode[409]['code'], $this->statuscode[409]['status'], 'Something went wrong. Please try later.', '');
				}
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User not found', '');
			}
		}
		}catch(Exception $e){
			dd($e);
		}
	}
	/**
	 * get all country list from commonController
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getAllCountry(Request $request) {
		$arrInput = $request->all();

		$query = Country::query();
		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		}
		if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
			//searching loops on fields
			$fields = getTableColumns('tbl_country_new');
			$search = $arrInput['search']['value'];
			$query = $query->where(function ($query) use ($fields, $search) {
				foreach ($fields as $field) {
					$query->orWhere($field, 'LIKE', '%' . $search . '%');
				}
			});
		}
		$totalRecord = $query->count('country_id');
		$query = $query->orderBy('country_id', 'desc');
		// $totalRecord = $query->count();
		$arrCountryData = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

		$arrData['recordsTotal'] = $totalRecord;
		$arrData['recordsFiltered'] = $totalRecord;
		$arrData['records'] = $arrCountryData;

		if ($arrData['recordsTotal'] > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', $arrData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Records not found', '');
		}
	}

	/**
	 * get specific user data by post parameter
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getSpecificUserData(Request $request) {
		$arrInput = $request->all();
		//get user data by post data
		$arrUserData = $this->commonController->getSpecificUserData($arrInput);

		if (count($arrUserData) > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'User found', $arrUserData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User not found', '');
		}
	}

	/**
	 * check user excited or not by passing parameter
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function checkUserExist(Request $request) {
		
		try {
            $arrInput = $request->all();

		//validate the info, create rules for the inputs
		$rules = array(
			'user_id' => 'required',
		);
		// run the validation rules on the inputs from the form
		$validator = Validator::make($arrInput, $rules);
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$message = $validator->errors();
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Input credentials is invalid or required', $message);
		} else {
			//check wether user exist or not by user_id
			$checkUserExist = $this->commonController->getSpecificUserData(['user_id' => $arrInput['user_id']]);

				 
			  if (!empty($checkUserExist)) {
			   if($checkUserExist->type != 'sub-admin' || $checkUserExist->type != 'sub-admin' )
			   	{

                  	$dash = Dashboard::where('id', $checkUserExist->id)->first();
					  //dd($dash);
					  if(!empty($dash))
					
                    // $bvcount = CurrentAmountDetails::select('user_id','left_bv','right_bv')->where('user_id', $checkUserExist->id)->first();
					// if(!empty($bvcount))
					{
						$arrObject['balance'] = $dash->top_up_wallet - $dash->top_up_wallet_withdraw;
						$arrObject['fund_wallet_balance'] = $dash->fund_wallet - $dash->fund_wallet_withdraw;
						$arrObject['acc_wallet'] = $dash->working_wallet - $dash->working_wallet_withdraw;
						$arrObject['id'] = $checkUserExist->id;
						$arrObject['remember_token'] = $checkUserExist->remember_token;
						$arrObject['fullname'] = $checkUserExist->fullname;
						$arrObject['username'] = $checkUserExist->fullname;
						$arrObject['email'] = $checkUserExist->email;
						$arrObject['power_lbv'] = $checkUserExist->power_l_bv;
						$arrObject['power_rbv'] = $checkUserExist->power_r_bv;
						$arrObject['l_bv'] = $checkUserExist->curr_l_bv;
						$arrObject['r_bv'] = $checkUserExist->curr_r_bv;
						$arrObject['coin_balance'] = $dash->coin- $dash->coin_withdrawal;

					}
					// }else
					// {
					// 	$arrObject['balance'] = $dash->top_up_wallet - $dash->top_up_wallet_withdraw;
					// 	$arrObject['fund_wallet_balance'] = $dash->fund_wallet - $dash->fund_wallet_withdraw;
					// 	$arrObject['user_id'] = $arrInput['user_id'];
					// 	$arrObject['id'] = $checkUserExist->id;
					// 	$arrObject['fullname'] = $checkUserExist->fullname;
					// 	$arrObject['username'] = $checkUserExist->fullname;
					// 	$arrObject['email'] = $checkUserExist->email;
					// 	$arrObject['l_bv'] = 0;
					// 	$arrObject['r_bv'] = 0;
					// }
				 }
				/* else
				 {*/

					$arrObject['user_id'] = $arrInput['user_id'];
					$arrObject['id'] = $checkUserExist->id;
					$arrObject['fullname'] = $checkUserExist->fullname;
					$arrObject['username'] = $checkUserExist->fullname;
					$arrObject['l_business'] = $checkUserExist->l_bv;
					$arrObject['r_business'] = $checkUserExist->r_bv;
               /*  } */
			
			// /dd($arrInput['user_id'], $dash->coin, $dash->coin_withdraw);

				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'User available', $arrObject);
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User not available', '');
			}
		}


			
		} catch (Exception $e) {

			dd($e);
			
		}
		
	}


	/**
	 * get user updated data log
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUserUpdatedLog(Request $request) {
		$arrInput = $request->all();

		//get user data by post data
		// $query = DB::table('tbl_users_change_data as tbucd')
		// 	->join('tbl_users as tbu', 'tbu.id', '=', 'tbucd.updated_by')
		// 	->join('tbl_users as tbu1', 'tbu1.id', '=', 'tbucd.id')
		// 	->join('tbl_users as tbu2', 'tbu2.id', '=', 'tbu1.ref_user_id')
		// 	->leftjoin('tbl_country_new as cn', 'cn.iso_code', '=', 'tbucd.country')
		// 	->selectRaw('tbu1.id,tbu1.user_id,tbu2.user_id as sponser_id,tbu2.fullname as sponser,tbucd.fullname,tbucd.mobile,tbucd.email,cn.country,tbu1.btc_address as new_btc_address,tbu1.bnb_address as new_bnb_address,tbu1.trn_address as new_trn_address,tbu1.ethereum as new_ethereum,tbucd.btc_address as old_btc_address,tbucd.bnb_address as old_bnb_address,tbucd.trn_address as old_trn_address,tbucd.ethereum as old_ethereum,tbucd.ip,tbu.user_id as updated_by,tbucd.created_at,tbucd.entry_time')
		// 	->where('tbucd.type', '');
		// $query = DB::table('tbl_users_change_data as tbucd')
		// 	->join('tbl_users as tbu', 'tbu.id', '=', 'tbucd.updated_by')
		// 	->join('tbl_users as tbu1', 'tbu1.id', '=', 'tbucd.id')
		// 	->join('tbl_users as tbu2', 'tbu2.id', '=', 'tbu1.ref_user_id')
		// 	->leftjoin('tbl_country_new as cn', 'cn.iso_code', '=', 'tbucd.country')
		// 	->selectRaw('tbu1.id,tbu1.user_id,tbu2.user_id as sponser_id,tbu2.fullname as sponser,tbucd.fullname,tbucd.mobile,tbucd.email,cn.country,tbu1.btc_address as new_btc_address,tbu1.bnb_address as new_bnb_address,tbu1.trn_address as new_trn_address,tbu1.ethereum as new_ethereum,tbucd.btc_address as old_btc_address,tbucd.bnb_address as old_bnb_address,tbucd.trn_address as old_trn_address,tbucd.ethereum as old_ethereum,tbucd.ip,tbu.user_id as updated_by,tbucd.created_at,tbucd.entry_time')
		// 	->where('tbucd.type', '');
		// 	if (isset($arrInput['id'])) {
		// 		$query = $query->where('tbucd.id', $arrInput['id']);
		// 	}
		// 	if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
		// 		$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
		// 		$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
		// 		$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbucd.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		// 	}
		// if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
		// 	//searching loops on fields
		// 	$fields = ['tbu1.user_id', 'tbu2.user_id', 'tbu2.fullname', 'tbucd.fullname', 'tbucd.mobile', 'tbucd.email', 'cn.country', 'tbucd.btc_address', 'tbucd.ethereum', 'tbucd.ip', 'tbu.user_id'];
		// 	$search = $arrInput['search']['value'];
		// 	$query = $query->where(function ($query) use ($fields, $search) {
		// 		foreach ($fields as $field) {
		// 			$query->orWhere($field, 'LIKE', '%' . $search . '%');
		// 		}
		// 	});
		// }

		$query = UsersChangeData::join('tbl_users as tbu', 'tbu.id', '=', 'tbl_users_change_data.updated_by')
			->join('tbl_users as tbu1', 'tbu1.id', '=', 'tbl_users_change_data.id')
			->join('tbl_users as tbu2', 'tbu2.id', '=', 'tbu1.ref_user_id')
			->leftjoin('tbl_country_new as cn', 'cn.iso_code', '=', 'tbl_users_change_data.country')
			->selectRaw('tbu1.id,tbu1.user_id,tbu2.user_id as sponser_id,tbu2.fullname as sponser,tbl_users_change_data.fullname,tbl_users_change_data.mobile,tbl_users_change_data.email,cn.country,tbl_users_change_data.btc_address,tbl_users_change_data.usdt_taddress,tbl_users_change_data.solona_address,tbl_users_change_data.ripple_address,tbl_users_change_data.ethereum,tbl_users_change_data.ltc_address,tbl_users_change_data.doge_address,tbl_users_change_data.trn_address,tbl_users_change_data.ip,tbu.user_id as updated_by,tbl_users_change_data.created_at,tbl_users_change_data.entry_time, tbl_users_change_data.ethereum')
			->where('tbl_users_change_data.type', '');
		if (isset($arrInput['id'])) {
			$query = $query->where('tbl_users_change_data.user_id', $arrInput['id']);
		}
		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_users_change_data.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		}
		if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
			//searching loops on fields
			$fields = ['tbu1.user_id', 'tbu2.user_id', 'tbu2.fullname', 'tbl_users_change_data.fullname', 'tbl_users_change_data.mobile', 'tbl_users_change_data.email', 'cn.country', 'tbl_users_change_data.trn_address', 'tbl_users_change_data.ethereum', 'tbl_users_change_data.ip', 'tbu.user_id'];
			$search = $arrInput['search']['value'];
			$query = $query->where(function ($query) use ($fields, $search) {
				foreach ($fields as $field) {
					$query->orWhere($field, 'LIKE', '%' . $search . '%');
				}
			});
		}
		$query = $query->orderBy('tbl_users_change_data.sr_no', 'desc');

		if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
			$qry = $query;
			$qry = $qry->select('tbu1.user_id','tbu1.fullname','tbl_users_change_data.mobile','tbl_users_change_data.btc_address','tbl_users_change_data.usdt_taddress','tbl_users_change_data.trn_address','tbl_users_change_data.ltc_address','tbl_users_change_data.doge_address','tbl_users_change_data.ripple_address','tbl_users_change_data.ethereum','tbl_users_change_data.solona_address','tbu.user_id as updated_by');


			// $qry = $qry->selectRaw('tbu1.user_id', 'tbu1.fullname','tbl_users_change_data.mobile', 'tbl_users_change_data.trn_address as tron address','tbl_users_change_data.email','cn.country','tbl_users_change_data.btc_address','tbl_users_change_data.trn_address as new_trn_address','tbl_users_change_data.ip','tbu.user_id as updated_by','tbl_users_change_data.created_at','tbl_users_change_data.entry_time','tbl_users_change_data.trn_address', 'tbl_users_change_data.bnb_address', 'tbl_users_change_data.btc_address as old_btc_address', 'tbl_users_change_data.btc_address as new_btc_address', 'tbl_users_change_data.bnb_address as old_bnb_address', 'tbl_users_change_data.bnb_address as new_bnb_address', 'tbl_users_change_data.ethereum as old_ethereum','tbl_users_change_data.ethereum as new_ethereum', 'tbl_users_change_data.trn_address as old_trn_address');
			$records = $qry->get();
			$res = $records->toArray();
			if (count($res) <= 0) {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
			}
			$var = $this->commonController->exportToExcel($res,"AllUsers");
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
		}
		$query = $query->whereRaw('tbl_users_change_data.sr_no IN (select MAX(sr_no) FROM tbl_users_change_data GROUP BY id)');
		$query = $query->orderBy('tbl_users_change_data.sr_no', 'desc');

		if (isset($arrInput['start']) && isset($arrInput['length'])) {
			$arrData = setPaginate1($query, $arrInput['start'], $arrInput['length']);
		} else {
			$arrData = $query->get();
		}

		// $query = $query->whereRaw('tbucd.sr_no IN (select MAX(sr_no) FROM tbl_users_change_data GROUP BY id)');
		// $query = $query->orderBy('tbucd.sr_no', 'desc');

		// if (isset($arrInput['start']) && isset($arrInput['length'])) {
		// 	$arrData = setPaginate1($query, $arrInput['start'], $arrInput['length']);
		// } else {
		// 	$arrData = $query->get();
		// }

		/*$totalRecord    = $query->get()->count();
			         $arrUserlogData = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

			         $arrData['recordsTotal']    = $totalRecord;
			         $arrData['recordsFiltered'] = $totalRecord;
		*/

		if ($arrData['recordsTotal'] > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', $arrData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Records not found', '');
		}
	}

	/**
	 * get user password details
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUserPassword(Request $request) {
		$arrInput = $request->all();

		$rules = array('user_id' => 'required');
		$validator = Validator::make($arrInput, $rules);

		if ($validator->fails()) {
			$message = $validator->errors();
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'Input credentials is invalid or required', $message);
		} else {
			$objPasswordData = $this->commonController->getSpecificUserData(['user_id' => $arrInput['user_id']]);

			if (count($objPasswordData) > 0) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'User available', $objPasswordData);
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User not available', '');
			}
		}
	}

	/**
	 * update user password with new password
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateUserPassword(Request $request) {
		$arrInput = $request->all();
		// validate the info, create rules for the inputs
		$rules = array(
			'id' => 'required',
			'password' => ['string',
				'min:6', // must be at least 10 characters in length
				'regex:/[a-z]/', // must contain at least one lowercase letter
				'regex:/[A-Z]/', // must contain at least one uppercase letter
				'regex:/[0-9]/', // must contain at least one digit
				'regex:/[@$!%*#?&]/'], // regex:/^[a-zA-Z](?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{6,50}$/|min:6|max:50
			'confirm_password' => 'required|same:password',
			'otp'=>'required',
		);

		// $ruleMessages = array(
		//     'password.regex' => 'Pasword contains first character letter, contains atleast 1 capital letter,combination of alphabets,numbers and special character i.e. ! @ # $ *'
		// );
		// run the validation rules on the inputs form the form
		
		$validator = Validator::make($arrInput, $rules);
		$id = Auth::User()->id;
		$arrInput['user_id'] = $id;
			$arrRules            = ['otp' => 'required|min:6|max:10'];
            $verify_otp = verify_Otp($arrInput);

			
            if (!empty($verify_otp)) {
                if ($verify_otp['status'] == 200) {
                } else {
                    $arrStatus = Response::HTTP_NOT_FOUND;;
                    $arrCode = Response::$statusTexts[$arrStatus];
                    $arrMessage = 'Invalid or Expired Otp!';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
                }
            } else {
                $arrStatus = Response::HTTP_NOT_FOUND;;
                $arrCode = Response::$statusTexts[$arrStatus];
                $arrMessage = 'Invalid or Expired Otp!';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
            }
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {
			$arrUpdate = [
				'password' => Crypt::encrypt($arrInput['password']),
				'bcrypt_password' => bcrypt($arrInput['password']),
			];
			$updatePass = User::where('id', $arrInput['id'])->update($arrUpdate);

			if (!empty($updatePass)) {
				$arrSendMail = [
					'to_mail' => $this->commonController->getSpecificUserData(['id' => $arrInput['id']])->email,
					'pagename' => 'emails.admin-emails.updateuserpassreply',
					'msg' => 'Password has been updated by Administrator. Please contact for any query',
					'subject' => 'Password update alert',
				];
				sendMail($arrSendMail, $arrSendMail['to_mail'], $arrSendMail['subject']);
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Password updated successfully', '');
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data already existed with given inputs', '');
			}
		}
	}

	/**
	 * get user profile details
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUserProfileDetails(Request $request) {
		$arrInput = $request->all();
		//get user about data (personal data)
		$userProfile = DB::table('tbl_users as tu1')
			->leftjoin('tbl_users as tu2', 'tu2.id', '=', 'tu1.ref_user_id')
			->leftjoin('tbl_country_new as cn', 'cn.iso_code', '=', 'tu1.country')
			->where([['tu1.type', '=', ''], ['tu1.id', '=', $arrInput['id']]])
			->select('tu1.*', 'tu2.user_id as sponser_id', 'cn.country', 'cn.iso_code')
			->first();
		//get user data by post data
		$getUserLogs = DB::table('tbl_users_change_data as tbucd')
			->selectRaw('tbucd.id,tbucd.fullname,tbucd.mobile,tbucd.btc_address,tbucd.bnb_address,tbucd.trn_address,tbucd.ethereum,tbucd.ref_user_id as sponser_id,tbucd.ip,tu1.fullname as updated_by,tbucd.created_at,tbucd.entry_time,cn.country,tu2.user_id as sponser')
			->leftjoin('tbl_users as tu1', 'tu1.id', '=', 'tbucd.updated_by')
			->leftjoin('tbl_country_new as cn', 'cn.iso_code', '=', 'tbucd.country')
			->leftjoin('tbl_users as tu2', 'tu2.id', '=', 'tbucd.ref_user_id')
			->orderBy('tbucd.entry_time', 'desc')
			->get();

		 $withdrawal_currency = Currency::where('tbl_currency.status','1')->get();
	   foreach ($withdrawal_currency as $key) {
	   	$curr_address = UserWithdrwalSetting::where([['id',$arrInput['id']], ['currency',$key['currency']],['status',1]])->pluck('currency_address')->first();
	   	if(!empty($curr_address)){
	   		$arrFinalData[''.str_replace("-","_",strtolower($key['currency'])).'_address'] = $curr_address;
	   	}else{
	   		$arrFinalData[''.str_replace("-","_",strtolower($key['currency'])).'_address'] = "";
	   	}
	   	// dd($curr_address);
	   }

		//get user activity notifications
		$userActivityNotify = Activitynotification::where([['id', $arrInput['id']], ['status', 1]])->orderBy('entry_time', 'desc')->get();

		//get user deposite addresses
		$userDepositeAddr = Depositaddress::where([['id', $arrInput['id']], ['status', 1]])->orderBy('entry_time', 'desc')->get();

		//get user deposite addresses Transactions
		$userDepositeAddrTransConfrm = AddressTransaction::where('id', $arrInput['id'])->orderBy('entry_time', 'desc')->get();

		//get user deposite addresses Transactions pending
		$userDepositeAddrTransPend = AddressTransactionPending::where('id', $arrInput['id'])->orderBy('entry_time', 'desc')->get();

		//get user all Transactions
		$coin_name = $this->commonController->getProjectSettings()->coin_name;
		$userAllTransCoin = AllTransaction::where([['id', '=', $arrInput['id']], ['status', '=', 1], ['network_type', '=', $coin_name]])->orderBy('entry_time', 'desc')->get();

		//get user all Transactions
		$userAllTransBTC = AllTransaction::where([['id', '=', $arrInput['id']], ['status', '=', 1], ['network_type', '=', 'BTC']])->orderBy('entry_time', 'desc')->get();

		//get user all Transactions
		$userAllTransUSD = AllTransaction::where([['id', '=', $arrInput['id']], ['status', '=', 1], ['network_type', '=', 'USD']])->orderBy('entry_time', 'desc')->get();

		//get user dashboard data
		$userDashboard = Dashboard::where('id', $arrInput['id'])->first();

		$arrFinalData['userProfile'] = $userProfile;
		$arrFinalData['userDashboard'] = $userDashboard;
		$arrFinalData['userLogs'] = $getUserLogs;
		$arrFinalData['userActivityNotifi'] = $userActivityNotify;
		$arrFinalData['depositeAddr'] = $userDepositeAddr;
		$arrFinalData['depositeAddrTransConfrm'] = $userDepositeAddrTransConfrm;
		$arrFinalData['depositeAddrTransPend'] = $userDepositeAddrTransPend;
		$arrFinalData['allTransactionCoin'] = $userAllTransCoin;
		$arrFinalData['allTransactionBTC'] = $userAllTransBTC;
		$arrFinalData['allTransactionUSD'] = $userAllTransUSD;

		if (count($arrFinalData) > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrFinalData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
		}
	}

	/**
	 * get admin profile details
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getAdminProfileDetails(Request $request) {

		$objAdminDetails = User::select('user_id', 'fullname', 'email', 'mobile', 'gender', 'tcn.country')
			->leftjoin('tbl_country_new as tcn', 'tcn.iso_code', '=', 'tbl_users.country')
			->where('remember_token', $request->remember_token)
			->where('type', 'Admin')
			->first();

		if (!empty($objAdminDetails)) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $objAdminDetails);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
		}
	}

	/**
	 * get 2fa status of users
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update2faUserStatus(Request $request) {
		$arrInput = $request->all();

		// validate the info, create rules for the inputs
		$rules = array('id' => 'required', '2fa_status' => 'required');
		$validator = Validator::make($arrInput, $rules);
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$message = $validator->errors();
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'Input field is required or invalid', $message);
		} else {
			$arrUpdateData = [
				'google2fa_status' => $arrInput['2fa_status'] == 'true' ? 'enable' : 'disable',
			];
			$update = User::where('id', $arrInput['id'])->update($arrUpdateData);

			if (!empty($update)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record updated succesfully', '');
			} else {
				return sendresponse($this->statuscode[409]['code'], $this->statuscode[409]['status'], 'Error occured while updating status', '');
			}
		}
	}

	/**
	 * [ip_track description]  Admin API Service
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function ipTrack(Request $request) {
		$rules = array(
			'user_id' => 'required',
		);
		$messsages = array(
			'user_id.required' => 'Please enter user Id.',
		);

		$validator = Validator::make($request->all(), $rules, $messsages);
		if ($validator->fails()) {
			$message = $validator->errors();
			$err = '';
			foreach ($message->all() as $error) {
				if (count($message->all()) > 1) {
					$err = $err . ' ' . $error;
				} else {
					$err = $error;
				}
			}
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, '');
		}

		$user_id = trim($request->get("user_id"));
		$isExistUser = User::Select("id")->Where("id", $user_id)->first();
		if (!is_null($isExistUser)) {
			$query = IpTrack::select('users.user_id As user', 'users.id As user_id', 'ip_tack.hostname', 'ip_tack.ipaddress', 'ip_tack.rec_date', 'ip_tack.forward', 'ip_tack.status')
				->leftJoin('users', 'users.id', '=', 'ip_tack.user_id')
				->where('ip_tack.user_id', $user_id);

			$data = setPaginate($query, $request->start, $request->length);
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Successful!', $data);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[401]['status'], 'User Id does not exist.', '');
		}
	}

	/**
	 * [ip_track description]  Admin API Service
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function ip_track(Request $request) {
		$rules = array(
			'user_id' => 'required',
		);
		$messsages = array(
			'user_id.required' => 'Please enter user Id.',
		);

		$validator = Validator::make($request->all(), $rules, $messsages);
		if ($validator->fails()) {
			$message = $validator->errors();
			$err = '';
			foreach ($message->all() as $error) {
				if (count($message->all()) > 1) {
					$err = $err . ' ' . $error;
				} else {
					$err = $error;
				}
			}
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, '');
		}

		$user_id = trim($request->get("user_id"));
		$isExistUser = User::Select("id")->Where("id", $user_id)->first();
		if (!is_null($isExistUser)) {
			$query = IpTrack::select('users.user_id As user', 'users.id As user_id', 'ip_tack.hostname', 'ip_tack.ipaddress', 'ip_tack.rec_date', 'ip_tack.forward', 'ip_tack.status')
				->leftJoin('tbl_users.users', 'users.id', '=', 'ip_tack.user_id')
				->where('ip_tack.user_id', $user_id);

			$data = setPaginate($query, $request->start, $request->length);
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Successful!', $data);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[401]['status'], 'User Id does not exist.', '');
		}
	}
	/**
	 * to store representative data
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function storeRepresentative(Request $request) {
		$rules = array(
			'user_name' => 'required',
			'name' => 'required',
			'email' => 'required',
			'mobile' => 'required',
		);
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {
			$arrInsert = [
				'user_name' => ($request->user_name) ? $request->user_name : '',
				'mobile' => ($request->mobile) ? $request->mobile : '',
				'name' => ($request->name) ? $request->name : '',
				'email' => ($request->email) ? $request->email : '',
				'country' => ($request->country) ? $request->country : '',
				'language' => ($request->language) ? $request->language : '',
				'facebook_id' => ($request->facebook_id) ? $request->facebook_id : '',
				'sky_d' => ($request->sky_d) ? $request->sky_d : '',
				'twitter_id' => ($request->twitter_id) ? $request->twitter_id : '',
				'telegram_id' => ($request->telegram_id) ? $request->telegram_id : '',
				'instagram_id' => ($request->instagram_id) ? $request->instagram_id : '',
				'admin_status' => 'Approved',
				'entry_time' => now(),
				'approved_time' => now(),
				'status' => '0',
			];
			$storeId = Representative::insertGetId($arrInsert);

			if (!empty($storeId)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Representative added successfully', '');
			} else {
				return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'Error occured while adding record', '');
			}
		}
	}
	/**
	 * to update representative data
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function updateRepresentative(Request $request) {
		$rules = array(
			'id' => 'required',
			'user_name' => 'required',
			'mobile' => 'required',
			'name' => 'required',
			'email' => 'required',
		);
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {
			$arrUpdate = [
				'user_name' => ($request->user_name) ? $request->user_name : '',
				'mobile' => ($request->mobile) ? $request->mobile : '',
				'name' => ($request->name) ? $request->name : '',
				'email' => ($request->email) ? $request->email : '',
				'country' => ($request->country) ? $request->country : '',
				'language' => ($request->language) ? $request->language : '',
				'facebook_id' => ($request->facebook_id) ? $request->facebook_id : '',
				'sky_d' => ($request->sky_d) ? $request->sky_d : '',
				'twitter_id' => ($request->twitter_id) ? $request->twitter_id : '',
				'telegram_id' => ($request->telegram_id) ? $request->telegram_id : '',
				'instagram_id' => ($request->instagram_id) ? $request->instagram_id : '',
				/*'admin_status'      => 'Approved',
					                'entry_time'        => now(),
					                'approved_time'     => now(),
				*/
			];
			$update = Representative::where('id', trim($request->id))->update($arrUpdate);

			if (!empty($update)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Representative updated successfully', '');
			} else {
				return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'Error occured while updating record', '');
			}
		}
	}
	/**
	 * to delete representative data
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function deleteRepresentative(Request $request) {
		$rules = array(
			'id' => 'required',
		);
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {
			$arrUpdate = [
				'status' => '1',
			];
			$update = Representative::where('id', trim($request->id))->update($arrUpdate);

			if (!empty($update)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Representative deleted successfully', '');
			} else {
				return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'Error occured while deleteing record', '');
			}
		}
	}
	/**
	 * get representative report
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showRepresentative(Request $request) {
		$arrInput = $request->all();

		//get user data by post data
		$query = Representative::leftjoin('tbl_country_new as cn', 'cn.iso_code', '=', 'tbl_representative.country')
			->select('tbl_representative.*', 'cn.country', 'cn.iso_code')
			->where('tbl_representative.status', '0');

		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_representative.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		}
		if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
			//searching loops on fields
			$fields = getTableColumns('tbl_representative');
			$search = $arrInput['search']['value'];
			$query = $query->where(function ($query) use ($fields, $search) {
				foreach ($fields as $field) {
					$query->orWhere('tbl_representative.' . $field, 'LIKE', '%' . $search . '%');
				}
				$query->orWhere('cn.country', 'LIKE', '%' . $search . '%');
			});
		}
		$totalRecord = $query->count('tbl_representative.id');
		$query = $query->orderBy('tbl_representative.id', 'desc');
		// $totalRecord = $query->count();
		$arrRepresentative = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

		$arrData['recordsTotal'] = $totalRecord;
		$arrData['recordsFiltered'] = $totalRecord;
		$arrData['records'] = $arrRepresentative;

		if ($arrData['recordsTotal'] > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', $arrData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Records not found', '');
		}
	}
	/**
	 * get user dashboard details
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getActiveInactiveUsers(Request $request) {
		$arrInput = $request->all();

		$query = Dashboard::join('tbl_users as tu', 'tu.id', '=', 'tbl_dashboard.id')
			->select('tbl_dashboard.srno', 'tbl_dashboard.total_investment', 'tu.user_id', 'tu.fullname', 'tu.mobile', 'tu.email');
		if (isset($arrInput['id'])) {
			$query = $query->where('tu.user_id', $arrInput['id']);
		}
		if (isset($arrInput['status'])) {
			if ($arrInput['status'] == 'Active') {
				$query = $query->where('tbl_dashboard.total_investment', '>', 0);
			} else if ($arrInput['status'] == 'Inactive') {
				$query = $query->where('tbl_dashboard.total_investment', '=', 0);
			}
		}
		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_dashboard.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		}
		if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
			//searching loops on fields
			$fields = ['tbl_dashboard.total_investment', 'tu.user_id', 'tu.fullname', 'tu.mobile', 'tu.email'];
			$search = $arrInput['search']['value'];
			$query = $query->where(function ($query) use ($fields, $search) {
				foreach ($fields as $field) {
					$query->orWhere($field, 'LIKE', '%' . $search . '%');
				}
			});
		}
		$totalRecord = $query->count('tbl_dashboard.id');
		$query = $query->orderBy('tbl_dashboard.entry_time', 'desc');
		// $totalRecord = $query->count();
		$arrDashboard = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

		$arrData['recordsTotal'] = $totalRecord;
		$arrData['recordsFiltered'] = $totalRecord;
		$arrData['records'] = $arrDashboard;

		if (count($arrDashboard) > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
		}
	}

	/**
	 * [userProfile as per userid]
	 * @param  Request $request [user_id]
	 * @return [Array]           [User Data]
	 */
	public function userProfile(Request $request) {
		$arrInput = $request->all();

		$rules = array(
			'id' => 'required',
		);
		$validator = Validator::make($arrInput, $rules);
		//if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		}
		$getuser = User::select('tbl_users.*', 'cn.iso_code', 'tu.user_id as ref_user_id', 'tu.fullname as sponser_id', DB::raw('(CASE tbl_users.position WHEN 1 THEN "Left" WHEN 2 THEN "Right" ELSE "" END) as position'), 'cn.country', DB::raw('DATE_FORMAT(tbl_users.entry_time,"%Y/%m/%d %H:%i:%s") as entry_time'))
			->join('tbl_users as tu', 'tu.id', '=', 'tbl_users.ref_user_id')
			->leftjoin('tbl_country_new as cn', 'cn.iso_code', '=', 'tbl_users.country')
			->where('tbl_users.id', $arrInput['id'])
			->first();
		
		if (!empty($getuser)) {
			$arrData['id'] = $getuser->id;
			$arrData['user_id'] = $getuser->user_id;
			$arrData['fullname'] = $getuser->fullname;
			$arrData['sponser_id'] = $getuser->sponser_id;
			$arrData['entry_time'] = $getuser->entry_time;
			$arrData['email'] = $getuser->email;
			$arrData['position'] = $getuser->position;
			$arrData['country'] = $getuser->country;
			// $arrData['btc_address'] = $getuser->btc_address;
			$arrData['ethereum'] = $getuser->ethereum;
			$arrData['iso_code'] = $getuser->iso_code;
			$arrData['mobile'] = $getuser->mobile;
			$arrData['address'] = $getuser->address;
			$arrData['ref_user_id'] = $getuser->ref_user_id;
			$arrData['account_no'] = $getuser->account_no;
			$arrData['holder_name'] = $getuser->holder_name;
			$arrData['pan_no'] = $getuser->pan_no;
			$arrData['bank_name'] = $getuser->bank_name;
			$arrData['ifsc_code'] = $getuser->ifsc_code;
			$arrData['branch_name'] = $getuser->branch_name;
			$arrData['city'] = $getuser->city;
			$arrData['code'] = $getuser->code;
			$arrData['is_franchise'] = $getuser->is_franchise;
			$arrData['facebook_link'] = $getuser->facebook_link;
			$arrData['twitter_link'] = $getuser->twitter_link;
			$arrData['linkedin_link'] = $getuser->linkedin_link;
			$arrData['instagram_link'] = $getuser->instagram_link;
			$arrData['perfect_money_address'] = $getuser->perfect_money_address;
			$arrData['paypal_address'] = $getuser->paypal_address;
			// $arrData['trn_address'] = $getuser->trn_address;
			//$arrData['ethereum'] = $getuser->ethereum;
			// $arrData['bnb_address'] = $getuser->bnb_address;
			$withdrawal_currency = Currency::where('tbl_currency.status','1')->get();
			// dd($withdrawal_currency);
			foreach ($withdrawal_currency as $key) {
				$curr_address = UserWithdrwalSetting::where([['id',$arrInput['id']], ['currency',$key['currency']],['status',1]])->pluck('currency_address')->first();
				if(!empty($curr_address)){
					$arrData[''.str_replace("-","_",strtolower($key['currency'])).'_address'] = $curr_address;
				}else{
					$arrData[''.str_replace("-","_",strtolower($key['currency'])).'_address'] = "";
				}
			}

			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
		}
	} // fn userProfile

	/**
	 * Block User
	 *
	 * @return void
	 */
	public function blockUser(Request $request) {
		$id = Auth::user()->id;
		$arrInput = $request->all();
		$rules = array(
			'id' => 'required',
			'status' => 'required',
		);
		$validator = Validator::make($arrInput, $rules);
		//if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {

			/** @var [ins into Change History table] */
			if ($arrInput['status'] == 'Active') {
				$do = 'block';
				$status = 'Inactive';
				$msg = 'User  blocked successfully';
			} else {
				$do = 'unblock';
				$status = 'Active';
				$msg = 'User  unblocked successfully';
				
			}

			$block = User::where('id', $arrInput['id'])->update(['status' => $status]);
			if (!empty($block)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], $msg, '');
			} else {
				return sendresponse($this->statuscode[409]['code'], $this->statuscode[409]['status'], 'Error occured while blocking user', '');
			}
		}
	}
	public function changeWithdrawStatus(Request $request) {
		$id = Auth::user()->id;
		$arrInput = $request->all();
		$rules = array(
			'id' => 'required',
		);
		$validator = Validator::make($arrInput, $rules);
		//if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {

			/** @var [ins into Change History table] */
			$user = User::select('auto_withdraw_status')->where('id', $arrInput['id'])->first();
			if (empty($user)) {
				return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], "User Not Found", '');				
			}
			if ($user->auto_withdraw_status == 1) {
				$status = 0;
				$msg = 'Auto withdraw status updated';
			} else {
				$status = 1;
				$msg = 'Auto withdraw status updated';
			}

			$block = User::where('id', $arrInput['id'])->update(['auto_withdraw_status' => $status]);
			if (!empty($block)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], $msg, '');
			} else {
				return sendresponse($this->statuscode[409]['code'], $this->statuscode[409]['status'], 'Something went wrong', '');
			}
		}
	}


	public function changeUserWithdrawStatus(Request $request) {
		$id = Auth::user()->id;
		$arrInput = $request->all();
		$rules = array(
			'id' => 'required',
		);
		$validator = Validator::make($arrInput, $rules);
		//if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {

			/** @var [ins into Change History table] */
			$user = User::select('withdraw_status')->where('id', $arrInput['id'])->first();
			if (empty($user)) {
				return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], "User Not Found", '');				
			}
			if ($user->withdraw_status == 1) {
				$status = 0;
				$msg = 'Withdraw status updated';
			} else {
				$status = 1;
				$msg = 'Withdraw status updated';
			}

			$block = User::where('id', $arrInput['id'])->update(['withdraw_status' => $status]);
			if (!empty($block)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], $msg, '');
			} else {
				return sendresponse($this->statuscode[409]['code'], $this->statuscode[409]['status'], 'Something went wrong', '');
			}
		}
	}



	public function changeUserBlockStatus(Request $request) {
		$id = Auth::user()->id;
		$arrInput = $request->all();
		$rules = array(
			'id' => 'required',
			'status' => 'required',
		);
		
			/** @var [ins into Change History table] */
			$user = AddFailedLoginAttempt::select('status')->where('user_id', $arrInput['id'])->where('status', $arrInput['status'])->first();
			if (empty($user)) {
				return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], "User Not Found", '')	;			
			}
			
			$msg = "";
			if ($user->status == 0) {
				$status = 1;
				$msg = 'Unblock by Admin';
			
				$updateStatus = array();
				$updateStatus['status'] = 1;
				$updateStatus['remark'] =  $msg;
				$unblock = AddFailedLoginAttempt::where('user_id',$arrInput['id'])->where('status',$arrInput['status'])->update($updateStatus);

				$updateUserStatus = array();
				$updateUserStatus['invalid_login_attempt'] = 0;
				$updateUserStatus['ublock_ip_address_time'] =  null;
				$unblock = User::where('user_id',$arrInput['id'])->update($updateUserStatus);
			}
			if (!empty($unblock)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], $msg, '');
			} else {
				return sendresponse($this->statuscode[409]['code'], $this->statuscode[409]['status'], 'Error occured while unblocking user', '');
			}
		}




	/**
	 * Block User
	 *
	 * @return void
	 */
	public function verifyUser(Request $request) {
		$arrInput = $request->all();

		$rules = array(
			'id' => 'required',
			'verify' => 'required',
		);
		$validator = Validator::make($arrInput, $rules);
		//if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {

			/** @var [ins into Change History table] */
			if ($arrInput['verify'] == 0) {
				$do = 'verify';
				$status = 1;
				$msg = 'User  verifyed successfully';
			} else {
				$do = 'unverify';
				$status = 0;
				$msg = 'User  unverify';
			}

			$block = WithdrawPending::where('sr_no', $arrInput['id'])->update(['verify' => $status]);
			if (!empty($block)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], $msg, '');
			} else {
				return sendresponse($this->statuscode[409]['code'], $this->statuscode[409]['status'], 'Error occured while blocking user', '');
			}
		}
	}

	/**
	 ** Add Power
	 ** @param Request obj
	 **
	 ** @return json
	 **/
	public function addPower(Request $request) {

		$arrInput = $request->all();

		$rules = array('id' => 'required', 'position' => 'required', 'power_bv' => 'required|integer|min:10');
		$validator = Validator::make($arrInput, $rules);

		  $id = Auth::user()->id;			
 
		$otpdata = Otp::select('otp_id','otp_status','otp')
		    ->where('id', Auth::user()->id)
			->where('otp', md5($request->otp))
			->where('otp_status', '=',0)
		    ->orderBy('entry_time', 'desc')->first();
		//dd($otpdata->otp_id);
		if (!empty($otpdata)) {
			 Otp::where('otp_id', $otpdata->otp_id)->delete();

		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {
			$user = User::where('id', $arrInput['id'])->first();
			$before_lbv = 0;
			$before_rbv = 0;
			if (!empty($user)) {

			/*	if ($user->business_l_bv > 0 && $request->position == 2) {
					return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'Cannot add power to right', '');
				} else if ($user->business_r_bv > 0 && $request->position == 1) {
					return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'Cannot add power to left', '');
				}*/

				$before_lbv = $user->l_bv;
				$before_rbv = $user->r_bv;
				$before_curr_lbv = $user->curr_l_bv;
				$before_curr_rbv = $user->curr_r_bv;
				$before_power_lbv = $user->power_l_bv;
				$before_power_rbv = $user->power_r_bv;
				$position = $arrInput['position'];
				$powerbv = $arrInput['power_bv'];
				$new_lbv = 0;
				$new_rbv = 0;

				if ($position == 1) {
					if ($arrInput['type'] == 3 || $arrInput['type'] == 4) {
						if ($before_rbv >= $powerbv && $before_power_rbv >= $powerbv) {
							$new_lbv = $before_lbv - $powerbv;
							$new_rbv = $before_rbv;
							$new_curr_lbv = $before_curr_lbv - $powerbv;
							$new_curr_rbv = $before_curr_rbv;
							$new_power_lbv = $before_power_lbv - $powerbv;
							$new_power_rbv = $before_power_rbv;
						} else {
							return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'User dont have too much power to remove ', '');
						}
					} else {
						$new_lbv = $before_lbv + $powerbv;
						$new_rbv = $before_rbv;
						$new_curr_lbv = $before_curr_lbv + $powerbv;
						$new_curr_rbv = $before_curr_rbv;
						$new_power_lbv = $before_power_lbv + $powerbv;
						$new_power_rbv = $before_power_rbv;
					}
				} elseif ($position == 2) {
					if ($arrInput['type'] == 3 || $arrInput['type'] == 4) {
						if ($before_rbv >= $powerbv && $before_power_rbv >= $powerbv) {
							$new_lbv = $before_lbv;
							$new_rbv = $before_rbv - $powerbv;
							$new_curr_lbv = $before_curr_lbv;
							$new_curr_rbv = $before_curr_rbv - $powerbv;
							$new_power_lbv = $before_power_lbv;
							$new_power_rbv = $before_power_rbv - $powerbv;
						} else {
							return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'User dont have too much power to remove ', '');
						}
					} else {
						$new_lbv = $before_lbv;
						$new_rbv = $before_rbv + $powerbv;
						$new_curr_lbv = $before_curr_lbv;
						$new_curr_rbv = $before_curr_rbv + $powerbv;
						$new_power_rbv = $before_power_rbv + $powerbv;
						$new_power_lbv = $before_power_lbv;
					}
				}

				$user->l_bv = $new_lbv;
				$user->r_bv = $new_rbv;
				$user->curr_l_bv = $new_curr_lbv;
				$user->curr_r_bv = $new_curr_rbv;
				$user->power_l_bv = $new_power_lbv;
				$user->power_r_bv = $new_power_rbv;
				$user->business_l_bv = $new_power_lbv;
				$user->business_r_bv = $new_power_rbv;
				$user->save();

				//current details update in exist
				$curr_amt_details = CurrentAmountDetails::where('user_id', $arrInput['id'])->first();
				$before_curr_lbv = 0;
				$before_curr_rbv = 0;
				$new_curr_lbv = 0;
				$new_curr_rbv = 0;
				if (!empty($curr_amt_details)) {
					$before_curr_lbv = $curr_amt_details->left_bv;
					$before_curr_rbv = $curr_amt_details->right_bv;
					$position = $arrInput['position'];
					$powerbv = $arrInput['power_bv'];
					$new_curr_lbv = 0;
					$new_curr_rbv = 0;

					if ($position == 1) {
						if ($arrInput['type'] == 3 || $arrInput['type'] == 4) {
							$new_curr_lbv = $before_curr_lbv - $powerbv;
							$new_curr_rbv = $before_curr_rbv;
						} else {
							$new_curr_lbv = $before_curr_lbv + $powerbv;
							$new_curr_rbv = $before_curr_rbv;
						}
					} elseif ($position == 2) {
						if ($arrInput['type'] == 3 || $arrInput['type'] == 4) {
							$new_curr_lbv = $before_curr_lbv;
							$new_curr_rbv = $before_curr_rbv - $powerbv;
						} else {
							$new_curr_lbv = $before_curr_lbv;
							$new_curr_rbv = $before_curr_rbv + $powerbv;
						}
					}

					$curr_amt_details->left_bv = $new_curr_lbv;
					$curr_amt_details->right_bv = $new_curr_rbv;
					$curr_amt_details->save();
				}

				//Insert inn power Bv Table
				$power = new AddRemoveBusiness;
				$power->user_id = $arrInput['id'];
				$power->position = $arrInput['position'];
				$power->power_bv = $powerbv;
				$power->type = $arrInput['type'];
				$power->before_lbv = $before_lbv;
				$power->before_rbv = $before_rbv;
				$power->after_lbv = $new_lbv;
				$power->after_rbv = $new_rbv;
				$power->before_curr_lbv = $before_curr_lbv;
				$power->before_curr_rbv = $before_curr_rbv;
				$power->after_curr_lbv = $new_curr_lbv;
				$power->after_curr_rbv = $new_curr_rbv;
				$power->entry_time = \Carbon\Carbon::now();
				$power->save();
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Power will update after 10 min', '');
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User not available', '');
			}
		}
		 } else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Incorrect OTP Or OTP Already Used', '');
			}
	}

	/**
	 ** Power Report
	 **
	 ** @param request
	 **
	 ** @return Json
	 **/
	public function powerReport(Request $request) {

		$arrInput = $request->all();

		$userExist = User::where('id', '=', Auth::user()->id)->whereIn("type", ["admin"])->first();

		if (!empty($userExist)) {
			$query = AddRemoveBusiness::join('tbl_users as tu', 'tu.id', '=', 'tbl_add_remove_business.user_id')
				->select('tbl_add_remove_business.*', 'tu.user_id as user', 'tu.fullname', DB::raw('DATE_FORMAT(tbl_add_remove_business.entry_time,"%Y/%m/%d") as entry_time'));

			if (isset($arrInput['user_id'])) {
				$query = $query->where('tu.user_id', $arrInput['user_id']);
			}
			if (isset($arrInput['amount'])) {
				$query = $query->where('tbl_add_remove_business.amount', $arrInput['amount']);
			}
			if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
				$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_add_remove_business.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
			}
			if (isset($arrInput['search']['value']) && !empty($arrInput['search']['value'])) {
				//searching loops on fields
				$fields = getTableColumns('tbl_add_remove_business');
				$search = $arrInput['search']['value'];
				$query = $query->where(function ($query) use ($fields, $search) {
					foreach ($fields as $field) {
						$query->orWhere('tbl_add_remove_business.' . $field, 'LIKE', '%' . $search . '%');
					}
					$query->orWhere('tu.user_id', 'LIKE', '%' . $search . '%')
						->orWhere('tu.fullname', 'LIKE', '%' . $search . '%');
				});
			}
             $query = $query->orderBy('tbl_add_remove_business.srno', 'desc');
			if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
			$qry = $query;
				$qry = $qry->select('tu.user_id as user id', 'tu.fullname', 'tbl_add_remove_business.power_bv',DB::raw('CASE tbl_add_remove_business.position WHEN 2 THEN "Right" WHEN 1 THEN "Left" END as position')  , 'tbl_add_remove_business.before_lbv' , 'tbl_add_remove_business.before_rbv','tbl_add_remove_business.before_curr_lbv' , 'tbl_add_remove_business.before_curr_rbv' , 'tbl_add_remove_business.after_curr_lbv' , 'tbl_add_remove_business.after_curr_rbv',  DB::raw('DATE_FORMAT(tbl_add_remove_business.entry_time,"%Y/%m/%d") as entry_time'));
			$records = $qry->get();
			$res = $records->toArray();
			if (count($res) <= 0) {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
			}
			$var = $this->commonController->exportToExcel($res,"power-report");
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
		}
			$totalRecord = $query->count('tbl_add_remove_business.srno');
			$query = $query->orderBy('tbl_add_remove_business.entry_time', 'desc');
			// $totalRecord = $query->count();
			$powerbv = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

			$arrData['recordsTotal'] = $totalRecord;
			$arrData['recordsFiltered'] = $totalRecord;
			$arrData['records'] = $powerbv;

			if (count($powerbv) > 0) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
			}
		} else {
			return sendresponse($this->statuscode[401]['code'], $this->statuscode[401]['status'], 'Unauthorised User.', '');
		}
	}

	public function getFranchiseUsers(Request $request) {
		$user = Auth::user();
		if (!empty($user)) {
			$users_list = User::select('id', 'user_id', 'fullname')
			->where('is_franchise', '1')
			->where('country','=',$request->country)
			->where('income_per', '=','3')
			->get();
			if (!empty($users_list)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Data found', $users_list);
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', '');
			}
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User Unaunthenticated', '');
		}
	}
	public function getMasterFranchiseUsers(Request $request) {
		$user = Auth::user();
		if (!empty($user)) {
			$users_list = User::select('id', 'user_id', 'fullname')
			->where('is_franchise', '1')
			->where('income_per','=','2')
			->get();
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
     * show downline users reports
     *
     * @return \Illuminate\Http\Response
     */
    public function getDownlineUsers(Request $request){

        $query = LevelView::
            join('tbl_transaction_invoices as tri','tri.id','=','tbl_level_view.down_id')
            ->join('tbl_users','tbl_level_view.down_id','=','tbl_users.id')
            ->selectRaw('tbl_users.id,tbl_users.user_id,tbl_users.fullname,tri.invoice_id,tri.status_url,tri.entry_time,tri.payment_mode,tri.address,tri.hash_unit');



        if(isset($request->user_id)){
        	$data=User::where('tbl_users.user_id', $request->user_id)->first();
      if(empty($data)){

      	 return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'User not found', '');

      }
    	$id=$data->id;
            $query = $query->where('tbl_level_view.id', $id);
        }else{

        	 $query = $query->where('tbl_level_view.id', '=','1');
        }
       $query = $query->where('tri.in_status', '=','1');

        if(isset($request->frm_date) && isset($request->to_date)){
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_users.entry_time,'%Y-%m-%d')"),[date('Y-m-d',strtotime($request->frm_date)), date('Y-m-d',strtotime($request->to_date))]);
        }
        // if(isset($request->search['value']) && !empty($request->search['value'])){
        //     //searching loops on fields
        //     $fields = ['tbl_users.user_id','tbl_users.fullname','tri.invoice_id','tri.status_url','tri.entry_time','tri.payment_mode','tri.address','tri.hash_unit'];
        //     $search = $request->search['value'];
        //     $query  = $query->where(function ($query) use ($fields, $search){
        //         foreach($fields as $field){
        //             $query->orWhere($field,'LIKE','%'.$search.'%');
        //         }
        //     });
        // }
		
        $query        = $query->orderBy('tbl_level_view.level','asc');
		$totalRecord  = $query->count('tri.srno');
        // $totalRecord  = $query->count();
        $arrUserData  = $query->skip($request->start)->take($request->length)->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrUserData;

        if($arrData['recordsTotal'] > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Records found', $arrData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Records not found', '');
        }
    }

     /**
     * show downline users reports
     *
     * @return \Illuminate\Http\Response
     */
    public function businessSetting(Request $request){

        $query = DB::table('tbl_business_setting')->join('tbl_users','tbl_business_setting.user_id','=','tbl_users.id')
            ->selectRaw('tbl_users.id,tbl_users.user_id,tbl_users.fullname,tbl_business_setting.amount,tbl_business_setting.remark,tbl_business_setting.entry_time');

 
        $business_setting=0;  
        if(isset($request->user_id)){
        	$data=User::where('tbl_users.user_id', $request->user_id)->first();
		      if(empty($data)){

		      	 return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'User not found', '');

		      }
    	$id=$data->id;

            $query = $query->where('tbl_business_setting.user_id', $id);
        }else{

        	$query = $query->where('tbl_business_setting.user_id', '=','1');
        
        }
      // $query = $query->where('tri.in_status', '=','1');
     // dd($query->toSql());
        if(isset($request->frm_date) && isset($request->to_date)){
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_business_setting.entry_time,'%Y-%m-%d')"),[date('Y-m-d',strtotime($request->frm_date)), date('Y-m-d',strtotime($request->to_date))]);
        }
        if(isset($request->search['value']) && !empty($request->search['value'])){
            //searching loops on fields
            $fields = ['tbl_users.user_id','tbl_users.fullname'];
            $search = $request->search['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere($field,'LIKE','%'.$search.'%');
                }
            });
        }
		$totalRecord  = $query->count('tbl_business_setting.id');
        $query        = $query->orderBy('tbl_business_setting.id','desc');
        //dd($query->sum('hash_unit'));
        // $totalRecord  = $query->count();
       
     
        $arrUserData  = $query->skip($request->start)->take($request->length)->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrUserData;
        
        //$arrData['records']         = $arrUserData;

        if($arrData['recordsTotal'] > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Records found', $arrData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Records not found', '');
        }
    }     /**
     * show downline users reports
     *
     * @return \Illuminate\Http\Response
     */
    public function findDownlineUsersBusiness(Request $request){

        $query = TodayDetails::join('tbl_transaction_invoices as tri','tri.id','=','tbl_today_details.from_user_id')
            ->join('tbl_users','tbl_today_details.from_user_id','=','tbl_users.id')
            ->selectRaw('tbl_users.id,tbl_users.user_id,tbl_users.fullname,tri.invoice_id,tri.status_url,tri.entry_time,tri.payment_mode,tri.address,tri.hash_unit');

 
        $business_setting=0;  
        if(isset($request->user_id)){
        	$data=User::where('tbl_users.user_id', $request->user_id)->first();
		      if(empty($data)){

		      	 return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'User not found', '');

		      }
    	$id=$data->id;

    	 $business_setting=DB::table('tbl_business_setting')->where('user_id','=',$id)->sum('amount');


            $query = $query->where('tbl_today_details.to_user_id', $id);
        }else{

        	$query = $query->where('tbl_today_details.today_id', '=','1');
        
        }
      $query = $query->where('tri.in_status', '=','1');
     //dd($request->frm_date);
        if(isset($request->frm_date) && isset($request->to_date)){
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tri.entry_time,'%Y-%m-%d')"),[date('Y-m-d',strtotime($request->frm_date)), date('Y-m-d',strtotime($request->to_date))]);
        }
        if(isset($request->search['value']) && !empty($request->search['value'])){
            //searching loops on fields
            $fields = ['tbl_users.user_id','tbl_users.fullname','tri.invoice_id','tri.status_url','tri.entry_time','tri.payment_mode','tri.address','tri.hash_unit'];
            $search = $request->search['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere($field,'LIKE','%'.$search.'%');
                }
            });
        }
		$totalRecord  = $query->count('tri.srno');
        $query        = $query->orderBy('tbl_today_details.level','asc');
        //dd($query->sum('hash_unit'));
        // $totalRecord  = $query->count();
       
     
        $arrUserData  = $query->skip($request->start)->take($request->length)->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrUserData;
        $arrData['total_business']  = $query->sum('hash_unit');
        $arrData['business_setting']  = $business_setting;
        //$arrData['records']         = $arrUserData;

        if($arrData['recordsTotal'] > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Records found', $arrData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Records not found', '');
        }
    }

  public function saveBusinessSetting(Request $request){

  	$arrInput = $request->all();

		$rules = array('user_id' => 'required', 'amount' => 'required');
		$validator = Validator::make($arrInput, $rules);

		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {

            $user=User::select('id')->where('user_id','=',$arrInput['user_id'])->first();


            $arrInput['user_id']=$user->id;

          //  dd($arrInput);
          
           if(!empty($user)){

           	DB::table('tbl_business_setting')->insert($arrInput);

			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'successfully updated', '');
           }else{

           return sendresponse($this->statuscode[400]['code'], $this->statuscode[400]['status'],'User Not Found', '');

           }

			



		}



  }


    public function useridUpdate(Request $request){
    	$id = Auth::user();
    	$arrInput = $request->all();
    	    

		$query = DB::table('tbl_user_update')->select('id', 'old_user_id', 'new_user_id','status' , 'entry_time');


		if (isset($arrInput['id'])) {
			$query = $query->where('id', $arrInput['id']);
		}
		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		}
		// if (isset($arrInput['search']['value']) && !empty($arrInput['search']['value'])) {
		// 	//searching loops on fields
		// 	$fields = ['id', 'old_user_id', 'new_user_id','status'];
		// 	$search = $arrInput['search']['value'];
		// 	$query = $query->where(function ($query) use ($fields, $search) {
		// 		foreach ($fields as $field) {
		// 			$query->orWhere($field, 'LIKE', '%' . $search . '%');
		// 		}
		// 	});
		// }
		
		$query = $query->orderBy('id', 'desc');
		$totalRecord = $query->count('id');
		// $totalRecord = $query->count();
		$arrDirectInc = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

		$arrData['recordsTotal'] = $totalRecord;
		$arrData['recordsFiltered'] = $totalRecord;
		$arrData['records'] = $arrDirectInc;

		if ($arrData['recordsTotal'] > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
		}

		

    }


    public function updateUserid(Request $request){
    	$id = Auth::user();
    	$arrData = $request->all();
    	$insert = DB::table('tbl_user_update')->insert($arrData);
    	$new_userid = $arrData['new_user_id'];
    	// dd($new_userid);
    	$arrUpdate = ['user_id' => $new_userid];

    	$updateUser = User::where('id', $request->id)->update($arrUpdate);
    	// dd($updateUser);
    	if (!empty($updateUser)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Userid Updated successfully', '');
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'userid not updated', '');
			}
    }

    /**
	 ** Add Power
	 ** @param Request obj
	 **
	 ** @return json
	 **/
	public function addBussiness(Request $request) {

		$arrInput = $request->all();

		$rules = array('id' => 'required', 'position' => 'required', 'power_bv' => 'required|integer|min:1');
		$validator = Validator::make($arrInput, $rules);
		$id = Auth::User()->id;
		$arrInput['user_id'] = $id;
			$arrRules            = ['otp' => 'required|min:6|max:10'];
            $verify_otp = verify_Otp($arrInput);

			
            if (!empty($verify_otp)) {
                if ($verify_otp['status'] == 200) {
                } else {
                    $arrStatus = Response::HTTP_NOT_FOUND;;
                    $arrCode = Response::$statusTexts[$arrStatus];
                    $arrMessage = 'Invalid or Expired Otp!';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
                }
            } else {
                $arrStatus = Response::HTTP_NOT_FOUND;;
                $arrCode = Response::$statusTexts[$arrStatus];
                $arrMessage = 'Invalid or Expired Otp!';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
            }
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {
			$user = User::where('id', $arrInput['id'])->first();
			$before_lbv = 0;
			$before_rbv = 0;
			if (!empty($user)) {

			/*	if ($user->business_l_bv > 0 && $request->position == 2) {
					return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'Cannot add power to right', '');
				} else if ($user->business_r_bv > 0 && $request->position == 1) {
					return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'Cannot add power to left', '');
				}*/

				$before_lbv = $user->l_bv;
				$before_rbv = $user->r_bv;
				$before_power_lbv = $user->manual_power_lbv;
				$before_power_rbv = $user->manual_power_rbv;
				$before_curr_l_bv = $user->curr_l_bv;
				$before_curr_r_bv = $user->curr_r_bv;
				$position = $arrInput['position'];
				$powerbv = $arrInput['power_bv'];
				$new_lbv = 0;
				$new_rbv = 0;
				$new_curr_lbv = 0;
				$new_curr_rbv = 0;

				if ($position == 1) {
					if ($arrInput['type'] == 3 || $arrInput['type'] == 4) {
						/*if ($before_rbv >= $powerbv && $before_power_rbv >= $powerbv) {*/
							$new_lbv = $before_lbv - $powerbv;
							$new_rbv = $before_rbv;
							$new_curr_lbv = $before_curr_l_bv - $powerbv;
							$new_curr_rbv = $before_curr_r_bv;
							$new_power_lbv = $before_power_lbv - $powerbv;
							$new_power_rbv = $before_power_rbv;
						/*} else {
							return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'User dont have too much Business to remove ', '');
						}*/
					} else {
						$new_lbv = $before_lbv + $powerbv;
						$new_rbv = $before_rbv;
						$new_curr_lbv = $before_curr_l_bv + $powerbv;
						$new_curr_rbv = $before_curr_r_bv;
						$new_power_lbv = $before_power_lbv + $powerbv;
						$new_power_rbv = $before_power_rbv;
					}
				} elseif ($position == 2) {
					if ($arrInput['type'] == 3 || $arrInput['type'] == 4) {
					/*	if ($before_rbv >= $powerbv && $before_power_rbv >= $powerbv) {*/
							$new_lbv = $before_lbv;
							$new_rbv = $before_rbv - $powerbv;
							$new_curr_rbv = $before_curr_r_bv - $powerbv;
							$new_curr_lbv = $before_curr_l_bv;
							$new_power_lbv = $before_power_lbv;
							$new_power_rbv = $before_power_rbv - $powerbv;
					/*	} else {
							return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'User dont have too much Business to remove ', '');
						}*/
					} else {
						$new_lbv = $before_lbv;
						$new_rbv = $before_rbv + $powerbv;
						$new_curr_rbv = $before_curr_r_bv + $powerbv;
						$new_curr_lbv = $before_curr_l_bv;
						$new_power_rbv = $before_power_rbv + $powerbv;
						$new_power_lbv = $before_power_lbv;
					}
				}
				$user->l_bv = $new_lbv;
				$user->r_bv = $new_rbv;
				$user->curr_l_bv = $new_curr_lbv;
				$user->curr_r_bv = $new_curr_rbv;
				$user->manual_power_lbv = $new_power_lbv;
				$user->manual_power_rbv = $new_power_rbv;
				/*$user->business_l_bv = $new_power_lbv;
				$user->business_r_bv = $new_power_rbv;*/
				$user->save();

				//current details update in exist
				/*$curr_amt_details = CurrentAmountDetails::where('user_id', $arrInput['id'])->first();
				$before_curr_lbv = 0;
				$before_curr_rbv = 0;
				$new_curr_lbv = 0;
				$new_curr_rbv = 0;
				if (!empty($curr_amt_details)) {
					$before_curr_lbv = $curr_amt_details->left_bv;
					$before_curr_rbv = $curr_amt_details->right_bv;
					$position = $arrInput['position'];
					$powerbv = $arrInput['power_bv'];
					$new_curr_lbv = 0;
					$new_curr_rbv = 0;

					if ($position == 1) {
						if ($arrInput['type'] == 3 || $arrInput['type'] == 4) {
							$new_curr_lbv = $before_curr_lbv - $powerbv;
							$new_curr_rbv = $before_curr_rbv;
						} else {
							$new_curr_lbv = $before_curr_lbv + $powerbv;
							$new_curr_rbv = $before_curr_rbv;
						}
					} elseif ($position == 2) {
						if ($arrInput['type'] == 3 || $arrInput['type'] == 4) {
							$new_curr_lbv = $before_curr_lbv;
							$new_curr_rbv = $before_curr_rbv - $powerbv;
						} else {
							$new_curr_lbv = $before_curr_lbv;
							$new_curr_rbv = $before_curr_rbv + $powerbv;
						}
					}

					$curr_amt_details->left_bv = $new_curr_lbv;
					$curr_amt_details->right_bv = $new_curr_rbv;
					$curr_amt_details->save();
				}*/

				//Insert inn power Bv Table
				$power = new AddRemoveBusiness;
				$power->user_id = $arrInput['id'];
				$power->position = $arrInput['position'];
				/*$power->remark = $arrInput['remark'];*/
				$power->power_bv = $powerbv;
				$power->type = $arrInput['type'];
				$power->before_lbv = $before_lbv;
				$power->before_rbv = $before_rbv;
				$power->after_lbv = $new_lbv;
				$power->after_rbv = $new_rbv;
				$power->before_curr_lbv = $before_curr_l_bv;
				$power->before_curr_rbv = $before_curr_r_bv;
				$power->after_curr_lbv = $new_curr_lbv;
				$power->after_curr_rbv = $new_curr_rbv;
				$power->entry_time = \Carbon\Carbon::now();
				$power->save();
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Bussiness Added Successfully', '');
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User not available', '');
			}
		}

	}

	public function addBussinessbv(Request $request) {
		$arrInput = $request->all();//dd($arrInput);
		$rules = array('id' => 'required', 'power_bv' => 'required|integer|min:10', 'position' => 'required', 'type' => 'required');
		$validator = Validator::make($arrInput, $rules);
		$id = Auth::User()->id;
		$arrInput['user_id'] = $id;
			$arrRules            = ['otp' => 'required|min:6|max:10'];
            $verify_otp = verify_Otp($arrInput);

			
            if (!empty($verify_otp)) {
                if ($verify_otp['status'] == 200) {
                } else {
                    $arrStatus = Response::HTTP_NOT_FOUND;;
                    $arrCode = Response::$statusTexts[$arrStatus];
                    $arrMessage = 'Invalid or Expired Otp!';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
                }
            } else {
                $arrStatus = Response::HTTP_NOT_FOUND;;
                $arrCode = Response::$statusTexts[$arrStatus];
                $arrMessage = 'Invalid or Expired Otp!';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
            }

		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {
		/*$getlevel = $this->pay_binary($request->id, $request->power_bv);*/
		$power = array();
		$power['user_id'] = $arrInput['id'];
		$power['position'] = (int)$arrInput['position'];
		$power['remark'] = $arrInput['remark'];
		$power['power_bv'] = $request->power_bv;
		$power['type'] = $arrInput['type'];
		$power['before_lbv'] = 0;
		$power['before_rbv'] = 0;
		$power['after_lbv'] = 0;
		$power['after_rbv'] = 0;
		$power['before_curr_lbv'] = 0;
		$power['before_curr_rbv'] = 0;
		$power['after_curr_lbv'] = 0;
		$power['after_curr_rbv'] = 0;
		$power['entry_time'] = \Carbon\Carbon::now();
		$last_entry = AddRemoveBusinessUpline::insert($power);
		return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Bussiness will update after 10 min', '');
		}
	}


	/**
	 ** Power Report
	 **
	 ** @param request
	 **
	 ** @return Json
	 **/
	public function buinessReport(Request $request) {

		$arrInput = $request->all();

		$userExist = User::where('id', '=', Auth::user()->id)->whereIn("type", ["admin"])->first();

		if (!empty($userExist)) {
			$query = AddRemoveBusiness::join('tbl_users as tu', 'tu.id', '=', 'tbl_add_remove_business.user_id')
			->select('tbl_add_remove_business.power_bv','tbl_add_remove_business.position',
			'tbl_add_remove_business.before_lbv','tbl_add_remove_business.before_rbv',
			'tbl_add_remove_business.after_lbv','tbl_add_remove_business.after_rbv', 
			'tbl_add_remove_business.before_curr_lbv','tbl_add_remove_business.before_curr_rbv', 
			'tbl_add_remove_business.after_curr_lbv','tbl_add_remove_business.after_curr_rbv', 'tbl_add_remove_business.remark','tbl_add_remove_business.type',

			'tu.user_id as user', 'tu.fullname', DB::raw('DATE_FORMAT(tbl_add_remove_business.entry_time,"%Y/%m/%d") as entry_time'));

				/*->select('tbl_add_remove_business.*', 'tu.user_id as user', 'tu.fullname', DB::raw('DATE_FORMAT(tbl_add_remove_business.entry_time,"%Y/%m/%d") as entry_time'));*/

			if (isset($arrInput['user_id'])) {
				$query = $query->where('tu.user_id', $arrInput['user_id']);
			}
			if (isset($arrInput['amount'])) {
				$query = $query->where('tbl_add_remove_business.amount', $arrInput['amount']);
			}
			if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
				$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_add_remove_business.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
			}
			if (isset($arrInput['search']['value']) && !empty($arrInput['search']['value'])) {
				//searching loops on fields
				$fields = getTableColumns('tbl_add_remove_business');
				$search = $arrInput['search']['value'];
				$query = $query->where(function ($query) use ($fields, $search) {
					foreach ($fields as $field) {
						$query->orWhere('tbl_add_remove_business.' . $field, 'LIKE', '%' . $search . '%');
					}
					$query->orWhere('tu.user_id', 'LIKE', '%' . $search . '%')
						->orWhere('tu.fullname', 'LIKE', '%' . $search . '%');
				});
			}


			if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
				$qry = $query;
				$qry = $qry->select('tbl_add_remove_business.power_bv','tbl_add_remove_business.position',
			'tbl_add_remove_business.before_lbv','tbl_add_remove_business.before_rbv',
			'tbl_add_remove_business.after_lbv','tbl_add_remove_business.after_rbv', 
			'tbl_add_remove_business.before_curr_lbv','tbl_add_remove_business.before_curr_rbv', 
			'tbl_add_remove_business.after_curr_lbv','tbl_add_remove_business.after_curr_rbv', 'tbl_add_remove_business.remark','tbl_add_remove_business.type',

			'tu.user_id as user', 'tu.fullname', DB::raw('DATE_FORMAT(tbl_add_remove_business.entry_time,"%Y/%m/%d") as entry_time'));
				// $qry = $qry->select('tu.user_id as user', 'tu.fullname', 'tbl_add_remove_business.power_bv','tbl_add_remove_business.position' , 'tbl_add_remove_business.before_lbv' , 'tbl_add_remove_business.before_rbv' , 'tbl_add_remove_business.after_lbv' , 'tbl_add_remove_business.after_rbv' , 'tbl_add_remove_business.before_curr_lbv' , 'tbl_add_remove_business.before_curr_rbv' , 'tbl_add_remove_business.after_curr_lbv' , 'tbl_add_remove_business.after_curr_rbv' , 'tbl_add_remove_business.remark' , 'tbl_add_remove_business.cron_status' ,  DB::raw('DATE_FORMAT(tbl_add_remove_business.entry_time,"%Y/%m/%d") as entry_time'));
				$records = $qry->get();
				$res = $records->toArray();
				if (count($res) <= 0) {
					return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
				}
				$var = $this->commonController->exportToExcel($res,"AllUsers");
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
			}

			$totalRecord = $query->count('tbl_add_remove_business.user_id');
			$query = $query->orderBy('tbl_add_remove_business.entry_time', 'desc');
			// $totalRecord = $query->count();
			$powerbv = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

			$arrData['recordsTotal'] = $totalRecord;
			$arrData['recordsFiltered'] = $totalRecord;
			$arrData['records'] = $powerbv;

			if (count($powerbv) > 0) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
			}
		} else {
			return sendresponse($this->statuscode[401]['code'], $this->statuscode[401]['status'], 'Unauthorised User.', '');
		}
	}



	public function buinessUplineReport(Request $request) {

		$arrInput = $request->all();

		$userExist = User::where('id', '=', Auth::user()->id)->whereIn("type", ["admin"])->first();

		if (!empty($userExist)) {
			$query = AddRemoveBusinessUpline::join('tbl_users as tu', 'tu.id', '=', 'tbl_add_remove_business_upline.user_id')
			->select('tbl_add_remove_business_upline.power_bv','tbl_add_remove_business_upline.position',
			'tbl_add_remove_business_upline.before_lbv','tbl_add_remove_business_upline.before_rbv',
			'tbl_add_remove_business_upline.after_lbv','tbl_add_remove_business_upline.after_rbv', 
			'tbl_add_remove_business_upline.before_curr_lbv','tbl_add_remove_business_upline.before_curr_rbv', 
			'tbl_add_remove_business_upline.after_curr_lbv','tbl_add_remove_business_upline.after_curr_rbv', 'tbl_add_remove_business_upline.remark','tbl_add_remove_business_upline.type',

			'tu.user_id as user', 'tu.fullname', DB::raw('DATE_FORMAT(tbl_add_remove_business_upline.entry_time,"%Y/%m/%d") as entry_time'));

				/*->select('tbl_add_remove_business_upline.*', 'tu.user_id as user', 'tu.fullname', DB::raw('DATE_FORMAT(tbl_add_remove_business_upline.entry_time,"%Y/%m/%d") as entry_time'));*/

			if (isset($arrInput['user_id'])) {
				$query = $query->where('tu.user_id', $arrInput['user_id']);
			}
			if (isset($arrInput['amount'])) {
				$query = $query->where('tbl_add_remove_business_upline.amount', $arrInput['amount']);
			}
			if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
				$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_add_remove_business_upline.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
			}
			if (isset($arrInput['search']['value']) && !empty($arrInput['search']['value'])) {
				//searching loops on fields
				$fields = getTableColumns('tbl_add_remove_business_upline');
				$search = $arrInput['search']['value'];
				$query = $query->where(function ($query) use ($fields, $search) {
					foreach ($fields as $field) {
						$query->orWhere('tbl_add_remove_business_upline.' . $field, 'LIKE', '%' . $search . '%');
					}
					$query->orWhere('tu.user_id', 'LIKE', '%' . $search . '%')
						->orWhere('tu.fullname', 'LIKE', '%' . $search . '%');
				});
			}


			if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
				$qry = $query;
				$qry = $qry->select('tbl_add_remove_business_upline.power_bv',DB::raw('CASE tbl_add_remove_business.position WHEN 2 THEN "Right" WHEN 1 THEN "Left" END as position'),
			'tbl_add_remove_business_upline.before_lbv','tbl_add_remove_business_upline.before_rbv',
			'tbl_add_remove_business_upline.after_lbv','tbl_add_remove_business_upline.after_rbv', 
			'tbl_add_remove_business_upline.before_curr_lbv','tbl_add_remove_business_upline.before_curr_rbv', 
			'tbl_add_remove_business_upline.after_curr_lbv','tbl_add_remove_business_upline.after_curr_rbv', 'tbl_add_remove_business_upline.remark',
			DB::raw('CASE tbl_add_remove_business_upline.position WHEN 2 THEN "Remove Bussiness Amount" WHEN 1 THEN "Add Business Amount" END as position'),

			'tu.user_id as user', 'tu.fullname', DB::raw('DATE_FORMAT(tbl_add_remove_business_upline.entry_time,"asc") as entry_time'));
			
				$records = $qry->get();
				$res = $records->toArray();
				if (count($res) <= 0) {
					return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
				}
				$var = $this->commonController->exportToExcel($res,"AllUsers");
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
			}

			$totalRecord = $query->count('tbl_add_remove_business_upline.user_id');
			$query = $query->orderBy('tbl_add_remove_business_upline.entry_time', 'desc');
			// $totalRecord = $query->count();
			$powerbv = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

			$arrData['recordsTotal'] = $totalRecord;
			$arrData['recordsFiltered'] = $totalRecord;
			$arrData['records'] = $powerbv;

			if (count($powerbv) > 0) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
			}
		} else {
			return sendresponse($this->statuscode[401]['code'], $this->statuscode[401]['status'], 'Unauthorised User.', '');
		}
	}


	/**
	 ** Power Report
	 **
	 ** @param request
	 **
	 ** @return Json
	 **/
	public function uplineBusinessReport(Request $request) {

		$arrInput = $request->all();

		$userExist = User::where('id', '=', Auth::user()->id)->whereIn("type", ["admin"])->first();

		if (!empty($userExist)) {
			$query = AddRemoveBusinessUpline::join('tbl_users as tu', 'tu.id', '=', 'tbl_add_remove_business_upline.user_id')
				->select('tbl_add_remove_business_upline.*', 'tu.user_id as user', 'tu.fullname', DB::raw('DATE_FORMAT(tbl_add_remove_business_upline.entry_time,"%Y/%m/%d") as entry_time'));

			if (isset($arrInput['user_id'])) {
				$query = $query->where('tu.user_id', $arrInput['user_id']);
			}
			if (isset($arrInput['amount'])) {
				$query = $query->where('tbl_add_remove_business_upline.amount', $arrInput['amount']);
			}
			if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
				$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_add_remove_business_upline.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
			}
			if (isset($arrInput['search']['value']) && !empty($arrInput['search']['value'])) {
				//searching loops on fields
				$fields = getTableColumns('tbl_add_remove_business_upline');
				$search = $arrInput['search']['value'];
				$query = $query->where(function ($query) use ($fields, $search) {
					foreach ($fields as $field) {
						$query->orWhere('tbl_add_remove_business_upline.' . $field, 'LIKE', '%' . $search . '%');
					}
					$query->orWhere('tu.user_id', 'LIKE', '%' . $search . '%')
						->orWhere('tu.fullname', 'LIKE', '%' . $search . '%');
				});
			}
		$query = $query->orderBy('tbl_add_remove_business_upline.entry_time', 'desc');
			if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
				$qry = $query;
				// $qry = $qry->select('tbl_add_remove_business_upline.user_id', 'tu.user_id as user', 'tu.fullname','tbl_add_remove_business_upline.*', DB::raw('DATE_FORMAT(tbl_add_remove_business_upline.entry_time,"%Y/%m/%d") as entry_time'));
				$qry = $qry->select('tu.user_id as user id','tu.fullname','tbl_add_remove_business_upline.power_bv',DB::raw('CASE tbl_add_remove_business_upline.position WHEN 2 THEN "Right" WHEN 1 THEN "Left" END as position'),
			'tbl_add_remove_business_upline.before_lbv','tbl_add_remove_business_upline.before_rbv',
			'tbl_add_remove_business_upline.after_lbv','tbl_add_remove_business_upline.after_rbv', 
			'tbl_add_remove_business_upline.before_curr_lbv','tbl_add_remove_business_upline.before_curr_rbv', 
			'tbl_add_remove_business_upline.after_curr_lbv','tbl_add_remove_business_upline.after_curr_rbv', 'tbl_add_remove_business_upline.remark',
			DB::raw('CASE tbl_add_remove_business_upline.type WHEN 2 THEN "Remove Bussiness Amount" WHEN 1 THEN "Add Business Amount" END as type'),DB::raw('DATE_FORMAT(tbl_add_remove_business_upline.entry_time, "%Y/%m/%d") as entry_time'));
				$records = $qry->get();
				$res = $records->toArray();
				if (count($res) <= 0) {
					return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
				}
				$var = $this->commonController->exportToExcel($res,"AllUsers");
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
			}

			$totalRecord = $query->count('tbl_add_remove_business_upline.user_id');
			$query = $query->orderBy('tbl_add_remove_business_upline.entry_time', 'desc');
			// $totalRecord = $query->count();
			$powerbv = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

			$arrData['recordsTotal'] = $totalRecord;
			$arrData['recordsFiltered'] = $totalRecord;
			$arrData['records'] = $powerbv;

			if (count($powerbv) > 0) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
			}
		} else {
			return sendresponse($this->statuscode[401]['code'], $this->statuscode[401]['status'], 'Unauthorised User.', '');
		}
	}



	public function removepowerbv(Request $request) {
		$arrInput = $request->all();
		$rules = array('id' => 'required', 'power_bv' => 'required|integer|min:10', 'position' => 'required', 'type' => 'required');
		$validator = Validator::make($arrInput, $rules);

		   $id = Auth::user()->id;			
 
		$otpdata = Otp::select('otp_id','otp_status','otp')
		    ->where('id', Auth::user()->id)
			->where('otp', md5($request->otp))
			->where('otp_status', '=',0)
		    ->orderBy('entry_time', 'desc')->first();
		//dd($otpdata->otp_id);
		if (!empty($otpdata)) {
			 Otp::where('otp_id', $otpdata->otp_id)->delete();

		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {
		/*$getlevel = $this->pay_binary_remove($request->id, $request->power_bv);*/
		$power = array();
		$power['user_id'] = $arrInput['id'];
		$power['position'] = (int) $arrInput['position'];
		$power['remark'] = $arrInput['remark'];
		$power['power_bv'] = $request->power_bv;
		$power['type'] = $arrInput['type'];
		$power['before_lbv'] = 0;
		$power['before_rbv'] = 0;
		$power['after_lbv'] = 0;
		$power['after_rbv'] = 0;
		$power['before_curr_lbv'] = 0;
		$power['before_curr_rbv'] = 0;
		$power['after_curr_lbv'] = 0;
		$power['after_curr_rbv'] = 0;
		$power['entry_status'] = "Remove";
		$power['entry_time'] = \Carbon\Carbon::now();
		$last_entry = AddRemoveBusinessUpline::insert($power);
		return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Bussiness will update after 10 min', '');

	}
	} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Incorrect OTP Or OTP Already Used', '');
			}
}



	/**
	 ** getSummaryByUserid
	 **
	 ** @param request
	 **
	 ** @return Json
	 **/
	public function getSummaryByUserid(Request $request) {

		$id = Auth::user()->id;
		$arrInput = $request->all();
		$rules = array('user_id' => 'required');
		$validator = Validator::make($arrInput, $rules);

		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} 
		$user = User::selectRaw('id,l_bv,r_bv,l_c_count,r_c_count')->where('user_id', '=', $request->user_id)->first();

		if (!empty($user)) {
			
			$topup_count = Topup::join('tbl_today_details as ttd','ttd.from_user_id','=','tbl_topup.id')->where('ttd.to_user_id',$user->id)->count('tbl_topup.id');

			/*$topup_amount = Topup::join('tbl_today_details as ttd','ttd.from_user_id','=','tbl_topup.id')->where('ttd.to_user_id',$user->id)->sum('tbl_topup.amount');*/
			$topup_amount = $user->l_bv + $user->r_bv;
			$total_register =  $user->l_c_count + $user->r_c_count;
			$confirm_withdraw = WithdrawPending::join('tbl_today_details as ttd','ttd.from_user_id','=','tbl_withdrwal_pending.id')->where('ttd.to_user_id',$user->id)->where('tbl_withdrwal_pending.status','1')->sum('tbl_withdrwal_pending.amount');
			$total_dex_wallet = Dashboard::join('tbl_today_details as ttd','ttd.from_user_id','=','tbl_dashboard.id')->where('ttd.to_user_id',$user->id)->sum(DB::raw('ROUND(tbl_dashboard.working_wallet - tbl_dashboard.working_wallet_withdraw,2)'));
			$total_purchase_wallet = Dashboard::join('tbl_today_details as ttd','ttd.from_user_id','=','tbl_dashboard.id')->where('ttd.to_user_id',$user->id)->sum(DB::raw('ROUND(tbl_dashboard.top_up_wallet - tbl_dashboard.top_up_wallet_withdraw,2)'));
			
			$arrData = array();
			$arrData['downline_topup_count']=$topup_count;
      $arrData['total_register']=$total_register;
      $arrData['total_confirm_withdraw']=$confirm_withdraw;
      $arrData['total_dex_wallet']=$total_dex_wallet;
      $arrData['total_purchase_wallet']=$total_purchase_wallet;
			

			if (!empty($arrData)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
			}
		} else {
			return sendresponse($this->statuscode[401]['code'], $this->statuscode[401]['status'], 'User not found.', '');
		}
	}


	public function getRankByUserid(Request $request) {
	
        $id = Auth::user()->id;
		
        $arrInput = $request->all();
        $rules = array('user_id' => 'required' , 'rank' => 'required');
        $validator = Validator::make($arrInput, $rules);

        if ($validator->fails()) {
            $message = messageCreator($validator->errors());
            return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
        } 

		$ranks = Rank::select('rank')->get();
		if (!empty($arrInput['rank']) && isset($arrInput['rank'])) {
			$ranks = $ranks->where('rank', $arrInput['rank']);
			$totalrankrecords = array();
			foreach($ranks as $rank)
			{
				$rankname = strtolower($rank->rank);
				if($rankname == 'immortal'){
					$l_position = "l_lmmortal";
					$r_position = "r_immortal";
				}else{
					$l_position = "l_".$rankname;
					$r_position = "r_".$rankname;
				}
				$userdata = User::select($l_position." as left_count",$r_position." as right_count")->where('user_id', '=', $request->user_id)->first()->toArray();
				$userdata['rank'] = $rank->rank;
				array_push($totalrankrecords, $userdata);
			}
			
		}

		$arrData['recordsTotal'] = count($totalrankrecords);
		$arrData['recordsFiltered'] = count($totalrankrecords);
		$arrData['records'] = $totalrankrecords;

		if ($arrData['recordsTotal'] > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
		}
         
    }



	public function AddRankPower(Request $request) {

		$id = Auth::user()->id;
		$arrInput = $request->all();
		$rules = array('id' => 'required' , 'position'=>'required' , 'power' => 'required|integer' , 'rank'=>'required');
		$validator = Validator::make($arrInput, $rules);

		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} 
		$rank = $request->position."_".strtolower($request->rank);
		$user = User::select('user_id','l_ace_check_status','r_ace_check_status',$rank)->where('id', '=', $request->id)->first();
		
		if (!empty($user)) {
			
			$arrUpdate = array();
			$arrUpdate[$rank] = DB::raw($rank." + ".$request->power);
			
			// if($request->type == "add"){
			// 	$arrUpdate[$rank] = DB::raw($rank." + ".$request->power);
			// }elseif($request->type == "remove"){
			// 	$arrUpdate[$rank] = DB::raw($rank." - ".$request->power);
			// }
			
			$pos="";
			if($request->position =='l'){
				$pos = '1';
			}
			else{
				$pos = '2';
			}

			$res = $user->toArray();

			$before_power = $res[$rank];
			$power = $request->power;
			$after_power = $res[$rank] + $power;

			if($request->position == 'l')
			{
				$ace_check_status = $res['l_ace_check_status'] + 1;
				$updateData = array('l_ace_check_status' => $ace_check_status);
			}

			if($request->position =='r'){
				$ace_check_status = $res['r_ace_check_status'] + 1;
				$updateData = array('r_ace_check_status' => $ace_check_status);
			}
			// $after_bv = $res[$rank] - $power_bv;

			// if($request->type == 'add')
            // {
            //     $after_bv = $res[$rank] + $power_bv;
            // }
            // if($request->type == 'remove')
            // {
            //     $after_bv = $res[$rank] - $power_bv;
            // }

			$updateData[$rank] = $after_power;
			$userupdate =User::where('id', $request->id)->update($updateData);
			$current_time = date('Y-m-d H:i:s');
			$arrInsert = array(
				'user_id'=> $request->id,
				'position'=>$pos,
				'before_bv'=>$res[$rank],
				'after_bv'=>$after_power,
				'bussiness_bv'=>$request->bussiness,
				'entry_time'=>$current_time,
			);
		
						
			$addbussiness = DB::table('tbl_add_remove_rank_business')->insert($arrInsert);

			if (!empty($arrInput)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Bussiness added Successfully!', $arrInput);
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
			}
		} else {
			return sendresponse($this->statuscode[401]['code'], $this->statuscode[401]['status'], 'User not found.', '');
		}
	}

	public function AddRankPowerUpline(Request $request) {

		$id = Auth::user()->id;
		$arrInput = $request->all();
		$rules = array('id' => 'required' , 'position'=>'required' , 'power' => 'required|integer' , 'rank'=>'required');
		$validator = Validator::make($arrInput, $rules);

		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} 

		$ranks = '';
		if($arrInput['rank'] == 'Noble'){
			$ranks = 'Ace';
		}

		if($arrInput['rank'] == 'Eques'){
			$ranks = 'Herald';
		}

		if($arrInput['rank'] == 'Baron'){
			$ranks = 'Guardian';
		}

		if($arrInput['rank'] == 'Comes'){
			$ranks = 'Crusader';
		}

		if($arrInput['rank'] == 'Earl'){
			$ranks = 'Commander';
		}

		if($arrInput['rank'] == 'Marchio'){
			$ranks = 'Valorant';
		}

		if($arrInput['rank'] == 'Prorex'){
			$ranks = 'Legend';
		}

		if($arrInput['rank'] == 'Knight'){
			$ranks = 'Relic';
		}

		if($arrInput['rank'] == 'Archidux'){
			$ranks = 'Almighty';
		}

		if($arrInput['rank'] == 'Magnus'){
			$ranks = 'Conqueror';
		}

		if($arrInput['rank'] == 'Rexus'){
			$ranks = 'Titan';
		}
		$rank = $request->position."_".strtolower($ranks);
		$up_rank_l = "l_".strtolower($ranks);
		$up_rank_r = "r_".strtolower($ranks);
		
		if($arrInput['rank'] == 'Imperator'){
			if($request->position == 'l'){
				$rank = 'l_lmmortal';				
			}else{
				$rank = 'r_immortal';
			}
			$up_rank_l = "l_lmmortal";
			$up_rank_r = "r_immortal";			
		}


		// dd($rank);
		$user = User::select('user_id','l_ace_check_status','r_ace_check_status',$rank)->where('id', '=', $request->id)->first();
		
		if (!empty($user)) {
			
			$arrUpdate = array();
			$arrUpdate[$rank] = DB::raw($rank." + ".$request->power);
			
			// if($request->type == "add"){
			// 	$arrUpdate[$rank] = DB::raw($rank." + ".$request->power);
			// }elseif($request->type == "remove"){
			// 	$arrUpdate[$rank] = DB::raw($rank." - ".$request->power);
			// }
			
			$pos="";
			if($request->position =='l'){
				$pos = '1';
			}
			else{
				$pos = '2';
			}

			$res = $user->toArray();

			$before_power = $res[$rank];
			$power = $request->power;
			$after_power = $res[$rank] + $power;

			if($request->position == 'l')
			{
				$ace_check_status = $res['l_ace_check_status'] + 1;
				$updateData = array('l_ace_check_status' => $ace_check_status);
			}

			if($request->position =='r'){
				$ace_check_status = $res['r_ace_check_status'] + 1;
				$updateData = array('r_ace_check_status' => $ace_check_status);
			}
			
      $updateLCountArr = array();
      $updateLCountArr[$up_rank_l] = DB::raw($up_rank_l.' + '.$request->power);
     

      DB::table('tbl_today_details as a')
      ->join('tbl_users as b','a.to_user_id', '=','b.id')
      ->where('a.from_user_id','=',$request->id)
      ->where('a.position','=',1)
      ->update($updateLCountArr);


      $updateRCountArr = array();
      $updateRCountArr[$up_rank_r] = DB::raw($up_rank_r.' + '.$request->power);
      

      DB::table('tbl_today_details as a')
      ->join('tbl_users as b','a.to_user_id', '=','b.id')
      ->where('a.from_user_id','=',$request->id)
      ->where('a.position','=',2)
      ->update($updateRCountArr);

			$updateData[$rank] = $after_power;
			$userupdate =User::where('id', $request->id)->update($updateData);
			$current_time = date('Y-m-d H:i:s');
			$arrInsert = array(
				'user_id'=> $request->id,
				'position'=>$pos,
				/*'type'=>ucfirst($request->type),*/
				'rank'=>$request->rank,
				'before_bv'=>$res[$rank],
				'power_bv'=>$request->power,
				'after_bv'=>$after_power,
				'entry_time'=>$current_time,
			);
		
						
			$addpower = DB::table('tbl_add_remove_rank_business_upline')->insert($arrInsert);

			if (!empty($arrInput)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Rank Power added Successfully!', $arrInput);
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
			}
		} else {
			return sendresponse($this->statuscode[401]['code'], $this->statuscode[401]['status'], 'User not found.', '');
		}
	}

	/*public function AddBussinessUpline(Request $request) {

		$arrInput = $request->all();

		$rules = array('id' => 'required', 'position' => 'required', 'bussiness' => 'required|integer');
		$validator = Validator::make($arrInput, $rules);

		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {
			$user = User::where('id', $arrInput['id'])->first();
			$before_lbv = 0;
			$before_rbv = 0;
		}
		if (!empty($user)) {

			  $before_lbv = $user->l_bv;
				$before_rbv = $user->r_bv;
				$before_power_lbv = $user->power_l_bv;
				$before_power_rbv = $user->power_r_bv;
				$before_curr_l_bv = $user->curr_l_bv;
				$before_curr_r_bv = $user->curr_r_bv;
				$position = $arrInput['position'];
				$powerbv = $arrInput['bussiness'];
				$new_lbv = 0;
				$new_rbv = 0;
				$new_curr_lbv = 0;
				$new_curr_rbv = 0;

			$pos="";
			if($request->position =='l'){
				$pos = '1';
				$new_lbv = $before_lbv - $powerbv;
				$new_rbv = $before_rbv;
				$new_curr_lbv = $before_curr_l_bv - $powerbv;
				$new_curr_rbv = $before_curr_r_bv;
				$new_power_lbv = $before_power_lbv - $powerbv;
				$new_power_rbv = $before_power_rbv;
			}
			else{
				$pos = '2';
				$new_lbv = $before_lbv;
				$new_rbv = $before_rbv - $powerbv;
				$new_curr_rbv = $before_curr_r_bv - $powerbv;
				$new_curr_lbv = $before_curr_l_bv;
				$new_power_lbv = $before_power_lbv;
				$new_power_rbv = $before_power_rbv - $powerbv;
			}

			$res = $user->toArray();
			$bussiness = $request->bussiness;

			$before_power = $powerbv;

			$after_power = $powerbv + $bussiness;
		
      $updateLCountArr = array();
      $updateLCountArr[$before_lbv] = DB::raw($before_lbv.' + '.$request->bussiness);
     
      DB::table('tbl_today_details as a')
      ->join('tbl_users as b','a.to_user_id', '=','b.id')
      ->where('a.from_user_id','=',$request->id)
      ->where('a.position','=',1)
      ->update($updateLCountArr);

      $updateRCountArr = array();
      $updateRCountArr[$before_rbv] = DB::raw($before_rbv.' + '.$request->bussiness);
      
      DB::table('tbl_today_details as a')
      ->join('tbl_users as b','a.to_user_id', '=','b.id')
      ->where('a.from_user_id','=',$request->id)
      ->where('a.position','=',2)
      ->update($updateRCountArr);

			$updateData[$powerbv] = $after_power;
			$userupdate =User::where('id', $request->id)->update($updateData);
			$current_time = date('Y-m-d H:i:s');
			$arrInsert = array(
				'user_id'=> $request->id,
				'position'=>$pos,
				'before_bv'=>$res[$powerbv],
				'bussiness_bv'=>$request->power,
				'after_bv'=>$after_power,
				'entry_time'=>$current_time,
			);
					
			$addpower = DB::table('tbl_add_remove_business_upline')->insert($arrInsert);

			if (!empty($arrInput)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Rank Power added Successfully!', $arrInput);
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
			}
		} else {
			return sendresponse($this->statuscode[401]['code'], $this->statuscode[401]['status'], 'User not found.', '');
		}
	}

}*/

	public function addBussinessUpline(Request $request) {

		$arrInput = $request->all();

		$rules = array('id' => 'required', 'position' => 'required', 'bussiness' => 'required|integer');
		$validator = Validator::make($arrInput, $rules);

		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {
			$user = User::where('id', $arrInput['id'])->first();
			$before_lbv = 0;
			$before_rbv = 0;
			if (!empty($user)) {

			/*	if ($user->business_l_bv > 0 && $request->position == 2) {
					return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'Cannot add power to right', '');
				} else if ($user->business_r_bv > 0 && $request->position == 1) {
					return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'Cannot add power to left', '');
				}*/

				$before_lbv = $user->l_bv;
				$before_rbv = $user->r_bv;
				$before_power_lbv = $user->power_l_bv;
				$before_power_rbv = $user->power_r_bv;
				$before_curr_l_bv = $user->curr_l_bv;
				$before_curr_r_bv = $user->curr_r_bv;
				$position = $arrInput['position'];
				$powerbv = $arrInput['bussiness'];
				$new_lbv = 0;
				$new_rbv = 0;
				$new_curr_lbv = 0;
				$new_curr_rbv = 0;

				if ($position == 1) {
					if ($arrInput['type'] == 3 || $arrInput['type'] == 4) {
						/*if ($before_rbv >= $powerbv && $before_power_rbv >= $powerbv) {*/
							$new_lbv = $before_lbv - $powerbv;
							$new_rbv = $before_rbv;
							$new_curr_lbv = $before_curr_l_bv - $powerbv;
							$new_curr_rbv = $before_curr_r_bv;
							$new_power_lbv = $before_power_lbv - $powerbv;
							$new_power_rbv = $before_power_rbv;
						/*} else {
							return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'User dont have too much Business to remove ', '');
						}*/
					} else {
						$new_lbv = $before_lbv + $powerbv;
						$new_rbv = $before_rbv;
						$new_curr_lbv = $before_curr_l_bv + $powerbv;
						$new_curr_rbv = $before_curr_r_bv;
						$new_power_lbv = $before_power_lbv + $powerbv;
						$new_power_rbv = $before_power_rbv;
					}
				} elseif ($position == 2) {
					if ($arrInput['type'] == 3 || $arrInput['type'] == 4) {
					/*	if ($before_rbv >= $powerbv && $before_power_rbv >= $powerbv) {*/
							$new_lbv = $before_lbv;
							$new_rbv = $before_rbv - $powerbv;
							$new_curr_rbv = $before_curr_r_bv - $powerbv;
							$new_curr_lbv = $before_curr_l_bv;
							$new_power_lbv = $before_power_lbv;
							$new_power_rbv = $before_power_rbv - $powerbv;
					/*	} else {
							return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'User dont have too much Business to remove ', '');
						}*/
					} else {
						$new_lbv = $before_lbv;
						$new_rbv = $before_rbv + $powerbv;
						$new_curr_rbv = $before_curr_r_bv + $powerbv;
						$new_curr_lbv = $before_curr_l_bv;
						$new_power_rbv = $before_power_rbv + $powerbv;
						$new_power_lbv = $before_power_lbv;
					}
				}

				$user->l_bv = $new_lbv;
				$user->r_bv = $new_rbv;
				$user->curr_l_bv = $new_curr_lbv;
				$user->curr_r_bv = $new_curr_rbv;
				/*$user->power_l_bv = $new_power_lbv;
				$user->power_r_bv = $new_power_rbv;
				$user->business_l_bv = $new_power_lbv;
				$user->business_r_bv = $new_power_rbv;*/
				$user->save();

	      $updateLCountArr = array();
	      $updateLCountArr["l_bv"] = DB::raw('l_bv + '.$request->bussiness);
	      $updateLCountArr["curr_l_bv"] = DB::raw('curr_l_bv + '.$request->bussiness);
	     
	      DB::table('tbl_today_details as a')
	      ->join('tbl_users as b','a.to_user_id', '=','b.id')
	      ->where('a.from_user_id','=',$request->id)
	      ->where('a.position','=',1)
	      ->update($updateLCountArr);

	      $updateRCountArr = array();
	      $updateRCountArr["r_bv"] = DB::raw('r_bv + '.$request->bussiness);
	      $updateRCountArr["curr_r_bv"] = DB::raw('curr_r_bv + '.$request->bussiness);
	      
	      DB::table('tbl_today_details as a')
	      ->join('tbl_users as b','a.to_user_id', '=','b.id')
	      ->where('a.from_user_id','=',$request->id)
	      ->where('a.position','=',2)
	      ->update($updateRCountArr);

				//Insert inn power Bv Table
				$power = array();
				$power['user_id'] = $arrInput['id'];
				$power['position'] = $arrInput['position'];
				$power['remark'] = $arrInput['remark'];
				$power['power_bv'] = $powerbv;
				$power['type'] = $arrInput['type'];
				$power['before_lbv'] = $before_lbv;
				$power['before_rbv'] = $before_rbv;
				$power['after_lbv'] = $new_lbv;
				$power['after_rbv'] = $new_rbv;
				$power['before_curr_lbv'] = $before_curr_l_bv;
				$power['before_curr_rbv'] = $before_curr_r_bv;
				$power['cron_status'] = "1";
				$power['after_curr_lbv'] = $new_curr_lbv;
				$power['after_curr_rbv'] = $new_curr_rbv;
				$power['entry_time'] = \Carbon\Carbon::now();
				DB::table('tbl_add_remove_business_upline')->insert($power);
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Bussiness added successfully', '');
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User not available', '');
			}
		}

	}

	public function getRankCount(Request $request) {

		$id = Auth::user()->id;
		$arrInput = $request->all();
		$col_name = $request->rank_name;
		$query = User::select(DB::raw($col_name))->where('user_id' , $request->user_id)->first();
		$arr['rank_name'] = $query[$col_name];
			if (!empty($arr)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Rank Power added Successfully!', $arr);
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
			}
		
	}


	public function updateBulkUsers(Request $request) {
		$arrInput = $request->all();

		$rules = array(
			'user_ids' => 'required',
			'fullname' => 'nullable|required_if:email,null|required_if:mobile,null|required_if:password,null|regex:/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/',
			'email' => 'nullable|required_if:fullname,null|required_if:mobile,null|required_if:password,null|required_if:country,null|email',
			'mobile'=>'nullable|required_if:email,null|required_if:fullname,null|required_if:password,null|required_if:country,null|numeric',
			'password'=>'nullable|required_if:email,null|required_if:mobile,null|required_if:fullname,null|required_if:country,null|min:6',
			'country'=>'nullable|required_if:email,null|required_if:mobile,null|required_if:fullname,null|required_if:password,null'
		);
		$ruleMessages = array(
			'fullname.regex' => 'Special characters not allowed in fullname.',
		);
		$validator = Validator::make($arrInput, $rules);
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {
			$oldUsercount = User::whereIn('user_id', explode(',',$request->user_ids))->count('id');
			if ($oldUsercount > 0) {
				$updated_by = Auth::user()->id;

				$arrUpdate = $arrInsert = array();
				if (isset($arrInput['fullname']) && $arrInput['fullname'] != '') {
					$arrUpdate['fullname'] = $arrInsert['fullname'] = $arrInput['fullname'];
				}
				if (isset($arrInput['email']) && $arrInput['email'] != '') {
					$arrUpdate['email'] = $arrInsert['email'] = $arrInput['email'];
				}
				if (isset($arrInput['mobile']) && $arrInput['mobile'] != '') {
					$arrUpdate['mobile'] = $arrInsert['mobile'] = $arrInput['mobile'];
				}
				if (isset($arrInput['country']) && $arrInput['country'] != '') {
					$arrUpdate['country'] = $arrInsert['country'] = $arrInput['country'];
				}
				if (isset($arrInput['password']) && $arrInput['password'] != '') {
					$arrUpdate['password'] = Crypt::encrypt($arrInput['password']);
					$arrUpdate['bcrypt_password'] = bcrypt($arrInput['password']);
					$arrInsert['password'] = md5($arrInput['password']);
				}

				$arrInsert['updated_by'] = $updated_by;
				$arrInsert['user_ids'] = $request->user_ids;
				$arrInsert['entry_time'] = \Carbon\Carbon::now();

				$userupdate =User::whereIn('user_id', explode(',',$request->user_ids))->update($arrUpdate);


				if (!empty($userupdate)) {
					$insertlog = UserBulkUpdate::insert($arrInsert);
					return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'User data updated successfully.', '');
				} else {
					return sendresponse($this->statuscode[409]['code'], $this->statuscode[409]['status'], 'Data already existed with given inputs.', '');
				}
			} else {
				return sendresponse($this->statuscode[409]['code'], $this->statuscode[409]['status'], 'User not found', '');
			}
		}
	}


	public function BulkEditReport(Request $request) {

		$arrInput = $request->all();

		$userExist = User::where('id', '=', Auth::user()->id)->whereIn("type", ["admin"])->first();

		if (!empty($userExist)) {
			$query = DB::table('tbl_user_bulk_update as bu')->select('bu.user_ids','bu.email','bu.mobile','bu.fullname', 'cn.country', DB::raw('DATE_FORMAT(bu.entry_time,"%Y/%m/%d") as entry_time'))->leftjoin('tbl_country_new as cn', 'cn.iso_code', '=', 'bu.country');

			if (isset($arrInput['user_id'])) {
				$query = $query->where('user_ids','LIKE', "%".$arrInput['user_id']."%");
			}
			if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
				$query = $query->whereBetween(DB::raw("DATE_FORMAT(bu.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
			}
			if (isset($arrInput['search']['value']) && !empty($arrInput['search']['value'])) {
				//searching loops on fields
				$fields = getTableColumns('tbl_user_bulk_update');
				$search = $arrInput['search']['value'];
				$query = $query->where(function ($query) use ($fields, $search) {
					foreach ($fields as $field) {
						$query->orWhere($field, 'LIKE', '%' . $search . '%');
					}
				});
			}


			if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
				$qry = $query;
				$records = $qry->get();
				$res = $records->toArray();
				if (count($res) <= 0) {
					return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
				}
				$var = $this->commonController->exportToExcel($res,"AllUsers");
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
			}

			$totalRecord = $query->count('bu.id');
			$query = $query->orderBy('bu.entry_time', 'desc');
			// $totalRecord = $query->count();
			$powerbv = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

			$arrData['recordsTotal'] = $totalRecord;
			$arrData['recordsFiltered'] = $totalRecord;
			$arrData['records'] = $powerbv;

			if (count($powerbv) > 0) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
			}
		} else {
			return sendresponse($this->statuscode[401]['code'], $this->statuscode[401]['status'], 'Unauthorised User.', '');
		}
	}


  public function getContestachievementreport(Request $request)
  {
    //$Checkexist = Auth::User();
    $arrInput = $request->all();

    
    $userdata = UserContestAchievement::select('tu.user_id','tu.fullname','cs.contest_prize',DB::raw('(CASE tbl_user_contest_achievment.claim_status WHEN 0 THEN "Not claimed" WHEN 1 THEN "Claimed" WHEN 3 THEN "Claimed Other" ELSE "Rejected" END) as claim_status'),'tbl_user_contest_achievment.entry_time')
                    ->join('tbl_users as tu', 'tu.id' ,'=','tbl_user_contest_achievment.user_id')
                    ->join('tbl_contest_setttings as cs', 'cs.id', '=', 'tbl_user_contest_achievment.contest_id');
    

    if (isset($arrInput['user_id'])) {
            $userdata = $userdata->where('tu.user_id', $arrInput['id']);
    }

 
    if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
        $arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
        $arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
        $userdata = $userdata->whereBetween(DB::raw("DATE_FORMAT(tbl_user_contest_achievment.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
    }

    $userdata = $userdata->orderBy('tbl_user_contest_achievment.entry_time', 'desc');
    $totalRecord = $userdata->count('tbl_user_contest_achievment.id');
    
    $arrPendings = $userdata->skip($request->input('start'))->take($request->input('length'))->get();

    $arrData['recordsTotal'] = $totalRecord;
    $arrData['recordsFiltered'] = $totalRecord;
    $arrData['records'] = $arrPendings;
  

    if ($arrData['recordsTotal'] > 0) {
    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', $arrData);
    } else {
        return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Records not found', '');
    }
             
  }

  public function PMMemberDetails(Request $request) {
		$user = PerfectMoneyMember::select('memberID','member','receiver','passkey')->first();
		if (!empty($user)) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], '', $user);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User not found', '');
		}
	}
	public function sendOtp(Request $request)
	{
	   // $checotpstatus = Otp::where('id', '=', $users)->orderBy('entry_time', 'desc')->first();
	   // //dd($checotpstatus);
	   // if (!empty($checotpstatus)) {
	   // 	$entry_time = $checotpstatus->entry_time;
	   // 	$out_time = $checotpstatus->out_time;
	   // 	$checkmin = date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime($entry_time)));
	   // 	$current_time = date('Y-m-d H:i:s');
	   // }
   
		   $user_exists=DB::table('tbl_users')
		   ->select('id','email')
		   ->where('user_id',$request->user_id)
		   ->first();
		   
		   if(!empty($user_exists))
		   {
			   $pagename = "emails.admin-emails.otp-mail";
			   $subject = "OTP sent successfully";
			   $random = rand(100000, 999999);
			   $data = array('pagename' => $pagename, 'otp' => $random, 'username' => $request->user_id);
   
			   //$mail = sendMail($data, $user_exists->email, $subject);
   
			   if(!empty($user_exists->email)){
			   $email = explode(',',trim($user_exists->email));
			   }else{
				   dd("Please Set Senders Email Id!!");
			   }
				   
			   $mail = sendMail($data, $email, $subject);
   
			   $insertotp = array();
			  ///date_default_timezone_set("Asia/Kolkata");

			   $otpExpireMit=Config::get('constants.settings.otpExpireMit');
			   $mytime_new = \Carbon\Carbon::now();
			   $expire_time = \Carbon\Carbon::now()->addMinutes($otpExpireMit)->toDateTimeString();
 
			   $current_time_new = $mytime_new->toDateTimeString();
			   $insertotp['entry_time'] = $current_time_new;
			   $insertotp['id'] = $user_exists->id;
			   $insertotp['ip_address'] = trim($_SERVER['REMOTE_ADDR']);
			   $insertotp['otp'] = md5($random);
			   $insertotp['otp_status'] = 0;
			   $insertotp['otpexpire'] = $expire_time;
			   $insertotp['type'] = 'email';
			   
			   $sendotp = Otp::create($insertotp);
			   
			   return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'OTP sent successfully to your email Id','');
		   }
		   else
		   {
   
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid User','');
		   }
	}



	public function sendTopupOtp(Request $request)
	{	
		 $user = Auth::User();
		 
	  $mail = explode(',',$user->email);
	  
        //$mobileResponse = $this->sendotponmobile($user,$username);      
      return $emailResponse = $this->sendotponmail($user,$mail,$request->otp_type);//dd($emailResponse);

      $whatsappMsg = "Your OTP is -: " . $emailResponse ;
                        
      $countrycode = getCountryCode($user->country);
              
      $mobile = $user->mobile;

      //sendSMS($mobile, $whatsappMsg);
      //sendWhatsappMsg($countrycode, $mobile, $whatsappMsg);

      return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'OTP sent successfully to your email', '');
	}
	public function sendOtpWithdrawMail(Request $request) {
        try{ 	
			$admin = Auth::user(); 
			if(empty($admin->email)){
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Please set email id', '');
			}
			$checotpstatus = Otp::where([['id', '=', $admin->id],])->orderBy('otp_id', 'desc')->first();
		 
			// $users, $username, $type=null
			if (!empty($checotpstatus)) {
				$entry_time = $checotpstatus->entry_time;
				$out_time = $checotpstatus->out_time;
				$checkmin = date('Y-m-d H:i:s', strtotime('+2 hours', strtotime($entry_time)));
				$current_time = date('Y-m-d H:i:s');
			}
			
			//$temp_data = Template::where('title', '=', 'Otp')->first();
			//$project_set_data = ProjectSetting::select('icon_image','domain_name')->first();

			$pagename = "emails.admin-emails.otp-mail";
			$subject = "OTP";//$temp_data->subject;
			$content = '';//$temp_data->content;
			$domain_name = '';//$project_set_data->domain_name;
			// $subject = "OTP sent successfully";
			$random = rand(1000000000, 9999999999);
			//$data = array('pagename' => $pagename, 'otp' => $random, 'username' => $admin->user_id,'content'=>$content,'domain_name' =>$domain_name);
             $data = array('pagename' => $pagename, 'otp' => $random, 'username' => $admin->user_id);
			if(!empty($admin->email)){
				$email = explode(',',trim($admin->email));
			}else{
				dd("Please Set Senders Email Id!!");
			}

			//dd($data, $email, $subject);

			$mail = sendMail($data, $email, $subject);

		//	$admin_expire_hr = Config::get('constants.settings.adminOtpExpireHr');
			$admin_expire_min = Config::get('constants.settings.otpExpireMit');
			$insertotp = array();
			$insertotp['id'] = $admin->id;
			$insertotp['ip_address'] = trim($_SERVER['REMOTE_ADDR']);
			$insertotp['otp'] = md5($random);
			$insertotp['otp_status'] = 0;
			$insertotp['type'] = 'email';
			// $insertotp['wallet_type'] =$request->type;
			$insertotp['otpexpire'] = \Carbon\Carbon::now()->addMinutes($admin_expire_min)->format('Y-m-d H:i:s');
			$insertotp['entry_time'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
		//	dd($insertotp['otpexpire'], $insertotp['entry_time']);
			if($request->type==1){

			$insertotp['remark'] = 'admin withdraw';
		    }else if($request->type==2){
				
			$insertotp['remark'] = 'admin topup';
			}else if($request->type==3){
				
			$insertotp['remark'] = 'admin edit profile';
			}else if($request->type==4){
				
				$insertotp['remark'] = 'admin fund';
			}else if($request->type==5){
				
				$insertotp['remark'] = 'admin remove fund';
			}
			else if($request->type==6){
				
				$insertotp['remark'] = 'admin power';
			}
			else if($request->type==7)
			{
				
				$insertotp['remark'] = 'admin_verify_withdraw';
			}else if($request->type==8)
			{
				
				$insertotp['remark'] = 'admin_change_password';
			}
			else if($request->type==9)
			{
				
				$insertotp['remark'] = 'add rank';
			}
			else if($request->type==10)
			{
				
				$insertotp['remark'] = 'add rank power';
			}else
			{

			$insertotp['remark'] = '';

			}
			$sendotp = Otp::create($insertotp);
			//dd($mail , $sendotp);
			if($sendotp){
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'OTP sent successfully to your email Id', '');
			}else{
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Please Try again!', '');
			}

			//}  // end of users
		}catch(\Exception $e){
			dd($e);
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Something went wrong,Please try again!!', '');
		}
        
    }	

}
