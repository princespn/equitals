<?php

namespace App\Http\Controllers\userapi;

use App\Http\Controllers\Controller;
use App\Models\FundRequest;
use App\Models\Gallerya;
use App\Models\Invoice;
use App\Models\Otp;
use App\Models\Packages;
use App\Models\ProjectSettings;
use App\Models\Questions;
use App\Models\Resetpassword;
use App\Models\Topup;
use App\Models\WithdrawMode;
use App\User;
use Validator;
use Auth;
use Config;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;

class PackageController extends Controller {

	public function __construct() {
		$this->statuscode = Config::get('constants.statuscode');
	}

	public function sendotponmail($users, $username, $type = null) {

		$checotpstatus = Otp::select('entry_time','out_time')->where([['id', '=', $users->id]])->orderBy('entry_time', 'desc')->first();

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

		*/

		if ($type == 1) {
			$pagename = "emails.forgotpassmail";
			$subject = "OTP sent successfully";
			$random = rand(100000, 999999);
			$data = array('pagename' => $pagename, 'otp' => $random, 'username' => $users->user_id);
		} else {
			$pagename = "emails.otpsend";
			$subject = "OTP sent successfully";
			$random = rand(100000, 999999);
			$data = array('pagename' => $pagename, 'otp' => $random, 'username' => $users->user_id);
		}

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
		//$mask_email = maskEmail($users->email);
		//$arrData['email'] = $mask_email;
		//$arrData['mobile'] = $mask_mobile;

		if ($type == null) {
			return $random;
		}

		return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'OTP sent successfully to your email Id', $random);

		return $sendotp;

		//}  // end of users
	}

	public function getRoiPer() {
		$packages = Packages::select('roi', 'package_type')->where([['status', '=', 'Active'], ['user_show_status', '=', 'Active']])->orderBy('id', 'asc')->groupBy('roi')->get();
		if (count($packages) > 0) {
			$arrStatus = Response::HTTP_OK;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Roi Percentages found successfully';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $packages);

		} else {
			$arrStatus = Response::HTTP_NOT_FOUND;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Data not found';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

	/**
	 * Get all packages
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getpackage(Request $request) {
		try {

			$extra = ProjectSettings::where('status', '=', 1)->pluck('extra')->first();

			$USDtoINR = ProjectSettings::where('status', '=', 1)->pluck('USD-to-INR')->first();

			//$CheckFirstTopupExist=Topup::where('id','=',$users->id)->first();
			$CheckFirstTopupExist = Topup::where('id', '=', Auth::user()->id)->orderBy('srno', 'desc')->max('amount');
			
			$packages = Packages::where([['status', '=', 'Active'], ['user_show_status', '=', 'Active']])->orderBy('id', 'asc')->get(); 
			// dd($packages);
			/*if (!empty($CheckFirstTopupExist)) {
	            $packages = Packages::where('cost', '>=', $CheckFirstTopupExist)->orderBy('cost', 'asc')->get();
	            if (!empty($extra) && count($packages) > 0) {

	                foreach ($packages as $pa) {
	                    $pa->extra = $extra;
	                }
	            } else {
	                foreach ($packages as $pa) {
	                    $pa->extra = 0;
	                }
	            }
	        } else {
	            $packages = Packages::orderBy('id', 'asc')->get();
	            foreach ($packages as $pa) {
	                $pa->extra = 0;
	        }
			*/

			$id = Auth::user()->id;
			$country = Auth::user()->country;
			$countrycode = getCountryCode($country);

			$checkTopup = Topup::where('id', $id)->select('id')->first();
			if (!empty($checkTopup)) {

				$mode1 = WithdrawMode::where('id', $id)->select('network_type')->orderBy('id', 'desc')->first();

				if (empty($mode1)) {
					$mode = Invoice::where('id', $id)->select('payment_mode')->where('in_status', 1)->orderBy('id', 'asc')->first();

					if (!empty($mode)) {
						$type = $mode->payment_mode;
					} else {
						$mode2 = FundRequest::where('user_id', $id)->select('user_id')->where('status', 'Approve')->orderBy('id', 'asc')->first();

						if (!empty($mode2)) {
							//$type = 'INR';
							//$mode = '6';
							$type = "BTC";
						} else {
							$type = "BTC";
						}
					}
				} else {
					$mode = $mode1;
					$type = $mode->network_type;
				}
			} else {
				$type = "BOTH";
			}

			$packages[0]['convert'] = $USDtoINR;
			$packages[0]['type'] = $type;
			$packages[0]['countryCode'] = $countrycode;

			if (!empty($packages) && count($packages) > 0) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Packages found successfully';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $packages);

			} else {
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Invalid user';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}

		} catch (Exception $e) {

			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

	public function getpackage1(Request $request) {
		try {

			$extra = ProjectSettings::where('status', '=', 1)->pluck('extra')->first();

			$USDtoINR = ProjectSettings::where('status', '=', 1)->pluck('USD-to-INR')->first();

			//$CheckFirstTopupExist=Topup::where('id','=',$users->id)->first();
			$CheckFirstTopupExist = Topup::where('id', '=', Auth::user()->id)->orderBy('srno', 'desc')->max('amount');
			$packages = DB::table('tbl_product1')->where([['status', '=', 'Active'], ['user_show_status', '=', 'Active']])->orderBy('id', 'asc')->get();

			if (!empty($packages) && count($packages) > 0) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Packages found successfully';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $packages);

			} else {
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Invalid user';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}

		} catch (Exception $e) {
			dd($e);
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

	public function getPackageFront(Request $request) {
		try {

			$extra = ProjectSettings::where('status', '=', 1)->pluck('extra')->first();
			$USD = ProjectSettings::where('status', '=', 1)->pluck('USD-to-INR')->first();
			//$CheckFirstTopupExist=Topup::where('id','=',$users->id)->first();
			/* $CheckFirstTopupExist = Topup::where('id', '=', Auth::user()->id)->orderBy('srno', 'desc')->max('amount');*/
			$packages = Packages::where([['status', '=', 'Active'], ['user_show_status', '=', 'Active']])->orderBy('id', 'asc')->get();

			$html = '';
			if (!empty($packages) && count($packages) > 0) {

				foreach ($packages as $pkey => $pvalue) {
					$html .= "<option value='" . $pvalue['roi'] . "' data-package='" . $pvalue['name'] . "' data-duration='" . $pvalue['duration'] . "'>" . $pvalue['roi'] . " % Daily Return With $" . /*$pvalue['name']*/$pvalue['min_hash'] . " - $" . $pvalue['max_hash'] . "</option>";
				}

				/*echo "<pre>"; print_r($html); exit();*/
				return $html;
			} else {
				return NULL;
			}

		} catch (Exception $e) {

			return NULL;
		}
	}

	public function getPackageFrontRupee(Request $request) {
		try {

			$extra = ProjectSettings::where('status', '=', 1)->pluck('extra')->first();
			$USD = ProjectSettings::where('status', '=', 1)->pluck('USD-to-INR')->first();
			//$CheckFirstTopupExist=Topup::where('id','=',$users->id)->first();
			/* $CheckFirstTopupExist = Topup::where('id', '=', Auth::user()->id)->orderBy('srno', 'desc')->max('amount');*/
			$packages = Packages::where([['status', '=', 'Active'], ['user_show_status', '=', 'Active']])->orderBy('id', 'asc')->get();

			$html = '';
			if (!empty($packages) && count($packages) > 0) {

				foreach ($packages as $pkey => $pvalue) {
					$html .= "<option value='" . $pvalue['roi'] . "' data-package='" . $pvalue['name_rupee'] . "' data-duration='" . $pvalue['duration'] . "'>" . $pvalue['roi'] . " % Daily Return With " . /*$pvalue['name']*/"Rs." . ($pvalue['min_hash'] * $USD) . "- " . "Rs." . ($pvalue['max_hash'] * $USD) . "" . "</option>";
				}

				/*echo "<pre>"; print_r($html); exit();*/
				return $html;
			} else {
				return NULL;
			}

		} catch (Exception $e) {

			return NULL;
		}
	}

	public function getImageFront(Request $request) {
		try {

			$url = url('uploads/gallery');
			/* $query  = Gallerya::selectRaw('*,(CASE WHEN attachment IS NOT NULL THEN CONCAT("'.$url.'","/",attachment) ELSE "" END) as attachment')->where('gid',$request->gid);*/

			$query = Gallerya::join('tbl_gallery as tg', 'tg.id', '=', 'tbl_gallerya.gid')
				->select('tg.name', DB::raw('CASE WHEN tbl_gallerya.attachment IS NOT NULL THEN CONCAT("' . $url . '","/",tbl_gallerya.attachment) ELSE "" END as attachment'));
			$query = $query->get();

			//echo "<pre>"; print_r($query); exit();
			/*     $html = "";
	              foreach($query as $key => $value)
	              {
	                $html .= '<div class="col-lg-3 col-md-4 col-sm-6" data-toggle="modal" data-target="#modal">';
	                $html .= '<a href="#lightbox" data-slide-to="1">';
	                $html .= '<img src='.$value['attachment'].' class="img-thumbnail pb-0">';
	                 $html .= '<p class="bg-dark text-white text-center">';
	                 $html .= '.'$value['name']'.';
	                 $html .= "</p></a></div>";
*/

			if (!empty($query) && count($query) > 0) {

				/*echo "<pre>"; print_r($html); exit();*/
				return $query;
			} else {
				return NULL;
			}

		} catch (Exception $e) {
			dd($e);
			return NULL;
		}
	}

	public function getQuestions() {
		$ques = Questions::all();

		if (!empty($ques)) {
			$arrStatus = Response::HTTP_OK;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Questions found successfully';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $ques);

		} else {
			$arrStatus = Response::HTTP_NOT_FOUND;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Questions not found';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}

	}

	public function generateRandomNo() {
		$rand = mt_rand(100000, 999999);
		$arrStatus = Response::HTTP_OK;
		$arrCode = Response::$statusTexts[$arrStatus];
		$arrMessage = 'Questions found successfully';
		return sendResponse($arrStatus, $arrCode, $arrMessage, $rand);

	}

	public function sendotpEditUserProfile(Request $request) {
		$user = User::select('remember_token','email','id','user_id','fullname','mobile')->where('user_id', $request->user_id)->first();
		//$username = $user->fullname;
		$mail = $user->email;
		//$mobileResponse = $this->sendotponmobile($user,$username);
		$emailResponse = $this->sendotponmail($user, $mail, $type = 1);

		// $whatsappMsg = "Your OTP is -: " . $emailResponse ;

		// $countrycode = getCountryCode($user->country);

		// $mobile = $user->mobile;

		//sendSMS($mobile, $whatsappMsg);
		//sendWhatsappMsg($countrycode, $mobile, $whatsappMsg);

		return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'OTP sent successfully to your email ', '');
	}

	/**
	 * update user profile
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateUserData_old(Request $request) {
		$getuser = User::where('user_id', $request->user_id)->first();

		$otpdata = Otp::where('id', $getuser->id)->where('otp', md5($request->otp))->orderBy('entry_time', 'desc')->first();
		if (!empty($otpdata)) {

			$entry_time   = $otpdata->entry_time;
			$checkmin     = date('Y-m-d H:i:s', strtotime('+3 minutes', strtotime($entry_time)));
			$current_time = date('Y-m-d H:i:s');

			if($current_time >= $checkmin && $otpdata->otp_status == 0){

				$updateData               = array();
				 $updateData['otp_status'] = '1';
				 $updateOtpSta             = Otp::where([['otp_id', $otpdata->otp_id], ['otp_status', '0']])->update($updateData);

				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Invalid or Expired Otp';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
			/*dd($checkmin,$current_time);*/

			if ($otpdata->otp_status == 'Active') {
	
				
					//$resetpassword['reset_password_token'] = md5(uniqid(rand(), true));
					$resetpassword = array();
					$resetpassword['reset_password_token'] = md5(uniqid(rand(), true));
					$resetpassword['id'] = $otpdata->id;
					$resetpassword['request_ip_address'] = $request->ip();

					$insertresetDta = Resetpassword::create($resetpassword);

					$path = Config::get('constants.settings.domainpath');

					$domain = $path . '/user#/reset-password?resettoken=' . $resetpassword['reset_password_token'];

					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Please change Your password';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $domain);
				
			}else {
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Otp Already Used';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
		} 
		else {
			$arrStatus = Response::HTTP_NOT_FOUND;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Incorrect Otp';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}

	}

	public function updateUserData(Request $request) {

		$getuser = User::where('user_id', $request->user_id)->first();

		$otpdata = Otp::where('id', $getuser->id)->where('otp', md5($request->otp))->orderBy('entry_time', 'desc')->first();
		if (!empty($otpdata)) {

		$id = $getuser->id;
		$arrInput            = $request->all();
		$arrInput['user_id'] =		$id;
		$arrRules            = ['otp' => 'required|min:6|max:6'];
		$validator           = Validator::make($arrInput, $arrRules);
		if ($validator->fails()) {
			return setValidationErrorMessage($validator);
		}
		$verify_otp = verify_Otp($arrInput);

		if (!empty($verify_otp)) {
			if ($verify_otp['status'] == 200) {

			} else {
                $arrStatus = Response::HTTP_NOT_FOUND;;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Invalid or expired Otp!';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				// return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
			}
		} else {
	            $arrStatus = Response::HTTP_NOT_FOUND;;
	            $arrCode = Response::$statusTexts[$arrStatus];
	            $arrMessage = 'Invalid or expired Otp!';
	            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				// return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
		}	


				//$resetpassword['reset_password_token'] = md5(uniqid(rand(), true));
					$resetpassword = array();
					$resetpassword['reset_password_token'] = md5(uniqid(rand(), true));
					$resetpassword['id'] = $otpdata->id;
					$resetpassword['request_ip_address'] = $request->ip();

					$insertresetDta = Resetpassword::create($resetpassword);

					$path = Config::get('constants.settings.domainpath_vue');

					$domain = $path . '/reset?resettoken=' . $resetpassword['reset_password_token'];

					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Please change Your password';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $domain);

		}else {
			$arrStatus = Response::HTTP_NOT_FOUND;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Incorrect Otp';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}
}
