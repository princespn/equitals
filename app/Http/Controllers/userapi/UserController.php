<?php

namespace App\Http\Controllers\userapi;

use App\Http\Controllers\Controller;
use App\Models\Activitynotification;
use App\Models\AppVersion;
use App\Models\AppVersionLog;
use App\Models\Dashboard;
use App\Models\KYC;
use App\Models\Otp;
use App\Models\Rank;
use App\Models\ProjectSettings;
use App\Models\SupperMatchingIncome;
use App\Models\supermatching;
use App\Models\Resetpassword;
use App\Models\UsersChangeData;
use App\Models\SecureLoginData;
use App\Models\UserWithdrwalSetting;
use App\Models\UserCurrAddrHistory;
use App\Models\TodayDetails;
use App\Models\allRanks;
use App\Models\Enquiry;
use App\Traits\AddressValid;
use App\Traits\Users;
use App\User;
use Config;
use DB;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller {

	use Users, AddressValid;
	public function __construct() {
		$this->linkexpire = Config::get('constants.linkexpire');
		$date = \Carbon\Carbon::now();
		$this->today = $date->toDateTimeString();
		$this->statuscode = Config::get('constants.statuscode');
	}
	/**
	 * Registered user
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function register(Request $request) {

		/*$intCode = Response::HTTP_NOT_FOUND;
        $strStatus = Response::$statusTexts[$intCode];
        $strMessage = 'Registrations are stopped till 12th September';
        return sendResponse($intCode, $strStatus, $strMessage, array());*/

		try {

			$arrValidation = User::registrationValidationRules();
			$validator = checkvalidation($request->all(), $arrValidation['arrRules'], $arrValidation['arrMessage']);

			if (!empty($validator)) {

				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = $validator;
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
			if(preg_match("/[a-z\!\@\#\$\%\^\&\*\-\_\=\;\:\'\"\?\/\\\.\>\<\,\}\{\[\]\|\~\`]/i", $request->mobile)){
				return sendResponse(Response::HTTP_NOT_FOUND, 404, 'Please enter valid mobile number', '');
			}
			//---end to check wether give address is valid or not---//
			// a:$radUserId = "EQX".substr(number_format(time() * rand(), 0, '', ''), 0, '7');
			// $checkifrandnoExist = User::where('user_id', $radUserId)->first();
			// if (!empty($checkifrandnoExist)) {
			// 	goto a;
			// } 

			//  $request->request->add(['user_id' => $radUserId ]);
			// $request->request->add(['user_id' => $request->Input('email')]);
			
			if($request->Input('ref_user_id') == 'Admin' || $request->Input('ref_user_id') == 'admin'){
				$totalRefCount = User::where('ref_user_id',1)->count();
				if($totalRefCount > 0){
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Sponser not exist';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
			}
			$getuser = $this->checkSpecificUserData(['user_id' => $request->Input('user_id'), 'status' => 'Active']);

			if (empty($getuser)) {
				//   if ($request->input('password') == $request->input('password_confirmation')) {

				$refUserExist = User::select('user_id')->where([['user_id', '=', $request->Input('ref_user_id')], ['status', '=', 'Active']])->count();

				if ($refUserExist > 0) {
					$registation_plan = ProjectSettings::where([['status', '=', 1]])->pluck('registation_plan')->first();
					// if binary plan is on t
					//echo $registation_plan; exit();
					if ($registation_plan == 'binary' && $request->Input('position') != 0) {
						return $this->binaryPlan($request);
					} else if ($registation_plan == 'level') {
						// if level plan on
						return $this->levelPlan($request);
					} else {
						$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = 'Something went wrong,Please try again';
						return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					}
				} else {

					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Sponser not exist';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
				/* } else {

					                    $arrStatus   = Response::HTTP_NOT_FOUND;
					                    $arrCode     = Response::$statusTexts[$arrStatus];
					                    $arrMessage  = 'Password and confirm password should be same';
					                    return sendResponse($arrStatus,$arrCode,$arrMessage,'');

				*/
			} else {

				$arrStatus = Response::HTTP_CONFLICT;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'User already registered exist';
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




	public function enquiryform(Request $request) {

		try {

				$insertdata = new Enquiry;
		       	$insertdata->fullname = $request->input('fullname');
		        $insertdata->email = $request->input('email');
		        $insertdata->mobile = $request->input('mobile');
		        $insertdata->save();
		       
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'User registered successfully..!';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');

			} catch (Exception $e) {
				dd($e);
				$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Something went wrong,Please try again';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

	/**
	 * Get sponser link
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getrank(Request $request){
		$id = Auth::user()->id;
	
		$user_id = (int)$id;
		$arry_total = array();
		$arry_total_right_side = array();
		$get_downline_user = TodayDetails::select('from_user_id')->where('to_user_id',$user_id)->get();
		
		foreach($get_downline_user as $user){
			$get_right_side_records =  $this->get_rank_count($user->from_user_id,1,"R");
			$get_left_side_records =  $this->get_rank_count($user->from_user_id,2,"L");
			array_push($arry_total, $get_left_side_records);
			array_push($arry_total_right_side, $get_right_side_records);
		}
		 
		$acc = array_shift($arry_total);
		foreach ($arry_total as $val) {
			foreach ($val as $key => $val) {
				$acc[$key] += $val;
			}
		}

		$acc2 = array_shift($arry_total_right_side);
		foreach ($arry_total_right_side as $val) {
			foreach ($val as $key => $val) {
				$acc2[$key] += $val;
			}
		}

		$arrFinalData[] = array_merge($acc,$acc2);
	//	$arrFinalData['Rigth'] = $acc2;
		return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Data found', $arrFinalData);
	}


	
	public function get_rank_count($id,$position,$pre){
		$get_all_rank = Rank::orderBy('id', 'asc')->get();
		$count_array = [];
		$rank_count = 0;
		foreach($get_all_rank as $rank){
			$get_id_records = User::select('id')->where('id',$id)->where('position',$position)->where('rank',$rank->rank)->count();
			if($pre == "L");
			{
				$rank_name = $pre."_".$rank->rank;
				$rank_count = $get_id_records;
				$dataarray[$rank_name] = $rank_count;
			}
			if($pre == "R");
			{
				$rank_name = $pre."_".$rank->rank;
				$rank_count = $get_id_records;
				$dataarrayleft[$rank_name] = $rank_count;
			}
		}
			if($pre == "L");
			{
				return $dataarray;
			}
			if($pre == "R");
			{
			
				return $dataarrayleft;
			}
	}
	public function getReferenceId(Request $request) {

		try {
			$path = Config::get('constants.settings.domainpath');
			$dataArr = array();

			//  $url = $path . '/public/user#/register?ref_id=' . Auth::user()->unique_user_id;
			//dd($url);

			// $dataArr['link'] = Bitly::getUrl(urldecode($url));
			/* $dataArr['link'] = $path . '/public/user#/register?ref_id=' . Auth::user()->unique_user_id;
            $dataArr['link2'] = $path . '/ref_id=' . Auth::user()->unique_user_id;*/

			$dataArr['link'] = $path . '/user#/register/?ref_id=' . Auth::user()->unique_user_id/*.'&' .'position='.Auth::user()->position*/;

			// dd($dataArr['link']);

			$arrStatus = Response::HTTP_OK;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Data found';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $dataArr);
		} catch (Exception $e) {
			//dd($e);Otp::where('otp_id', $checotpstatus->otp_id)->update(['otp_status' => '1']);
						$intCode        = Response::HTTP_OK;
						$strStatus      = Response::$statusTexts[$intCode];
						$strMessage     = "OTP Verified."; 
						return
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

	/**
	 * Insert user data while user login
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function secureLogindata($user_id, $password, $query) {

		$securedata = array();
		$securedata['user_id'] = $user_id;
		$securedata['ip_address'] = $_SERVER['REMOTE_ADDR'];

		$securedata['query'] = $query;
		$securedata['pass'] = $password;

		$SecureLogin = SecureLoginData::create($securedata);
	}
	/**
	 * Send reset password link to user for change password
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function sendResetPasswordLink(Request $request) {

		try {
			$messages = array(
				'user_id.required' => 'Please enter user name',
			);
			$rules = array(
				'user_id' => 'required',
			);

			$validator = checkvalidation($request->all(), $rules, $messages);
			if (!empty($validator)) {
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = $validator;
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}

			if (!empty($request->input('user_id'))) {
				$user_id = trim($request->input('user_id'));
				$Checkexist = User::where([['user_id', '=', $request->Input('user_id')]])->first();
				if (!empty($Checkexist)) {
					$resetpassword = array();
					$resetpassword['reset_password_token'] = md5(uniqid(rand(), true));
					$resetpassword['id'] = $Checkexist->id;
					$resetpassword['request_ip_address'] = $request->ip();

					$insertresetDta = Resetpassword::create($resetpassword);

					$actdata = array();
					$actdata['id'] = $Checkexist->id;
					$actdata['message'] = 'Reset password link sent successfully to your registered email id';
					$actdata['status'] = 1;
					$actDta = Activitynotification::create($actdata);

					$username = $Checkexist->email;
					$reset_token = $resetpassword['reset_password_token'];
		//-----------------------------------------------------------------------------
					$subject = "RESET PASSWORD";
					$pagename = "emails.reset_password";
					$data = array('pagename' => $pagename, 'username' => $request->Input('user_id'), 'reset_token' => $reset_token, 'user_id' => $request->Input('user_id'));

					$mail = sendMail($data, $username, $subject);
					if (empty($mail)) {
						$arrStatus = Response::HTTP_OK;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = 'Reset password link sent successfully to your registered email id';

						$path = Config::get('constants.settings.domainpath');

						$domain = $path . '/public/user#/reset-password?resettoken=' . $reset_token;

						$whatsappMsg = "Hello,\nClick on  the following link to update your password and follow the simple steps. -: ";

						$countrycode = getCountryCode($Checkexist->country);

						// sendSMS($Checkexist->mobile, $whatsappMsg);
						// sendWhatsappMsg($countrycode, $Checkexist->mobile, $whatsappMsg);

						return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					} else {
						$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = 'Something went wrong,Please try again';
						return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					}
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'User is not registered with this username';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
			} else {

				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'User id should not be null';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
		} catch (Exception $e) {
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

	/**
	 * Send reset password link to user for change password
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function resetpassword(Request $request) {

		try {

			$reset_token = $request->input('resettoken');
			$CheckTokenExpire = Resetpassword::where([['reset_password_token', '=', $reset_token]])->first();
			if (!empty($CheckTokenExpire)) {
				$userId = $CheckTokenExpire->id;

				$Checkexist = User::where([['id', '=', $userId]])->first();
				if (!empty($Checkexist)) {

					$entry_time = $CheckTokenExpire->entry_time;
					$current_time = now();
					$hourdiff = round((strtotime($current_time) - strtotime($entry_time)) / 3600, 1);
					/* if (round($hourdiff) == $this->linkexpire && round($hourdiff) >= $this->linkexpire) {
						                        $updateData = array();
						                        $updateData['reset_password_token'] = $reset_token;
						                        $updateData['otp_status'] = 1;
						                        $updateOtpSta = Resetpassword::where('id', $userId)->update($updateData);

						                        if (empty($updateOtpSta)) {

						                            $arrStatus   = Response::HTTP_NOT_FOUND;
						                            $arrCode     = Response::$statusTexts[$arrStatus];
						                            $arrMessage  = 'Reset Password Link expired';
						                            return sendResponse($arrStatus,$arrCode,$arrMessage,'');
						                        } else {

						                            $arrStatus   = Response::HTTP_NOT_FOUND;
						                            $arrCode     = Response::$statusTexts[$arrStatus];
						                            $arrMessage  = 'Something went wrong,Please try again';
						                            return sendResponse($arrStatus,$arrCode,$arrMessage,'');
						                        }
					*/
					$CheckExpireLink = Resetpassword::where([
						['id', '=', $userId],
						['reset_password_token', '=', $reset_token],
						['otp_status', '=', 1],
					])->first();
					if (!empty($CheckExpireLink)) {

						$arrStatus = Response::HTTP_NOT_FOUND;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = 'Reset Password Link expired';
						return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					} else {

						$password = $request->input('password');
						$confirm_password = $request->input('confirm_password');

						$messsages = array('password.regex' => 'Pasword contains first character letter, contains atleast 1 capital letter,combination of alphabets,numbers and special character i.e. ! @ # $ *');
						//|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{7,}/
						$validator = Validator::make($request->all(), [
							'password' => ['string',
								'min:6', // must be at least 10 characters in length
								'regex:/[a-z]/', // must contain at least one lowercase letter
								'regex:/[A-Z]/', // must contain at least one uppercase letter
								'regex:/[0-9]/', // must contain at least one digit
								'regex:/[@$!%*#?&]/', // must contain a special character',
							], $messsages,
						]);
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
							$arrStatus = Response::HTTP_NOT_FOUND;
							$arrCode = Response::$statusTexts[$arrStatus];
							$arrMessage = $err;
							return sendResponse($arrStatus, $arrCode, $arrMessage, '');
						}

						if ($password == $confirm_password) {
							$updateData = array();
							$updateData['password'] = encrypt($confirm_password);
							$updateData['bcrypt_password'] = bcrypt($confirm_password);
							$updateOtpSta = User::where('id', $userId)->update($updateData);
							$userIPAddress = trim($_SERVER['REMOTE_ADDR']);
							$updateresetData = array();
							$datetime = now();

							$updateresetDta = DB::table('tbl_user_reset_password')
								->where(['id' => $userId, 'reset_password_token' => $reset_token])
								->update(['ip_address' => $userIPAddress, 'out_time' => $datetime, 'otp_status' => 1]);
							if (!empty($updateresetDta)) {

								//---------send mail-------------------------
								$user_id = $Checkexist->user_id;
								$username = $Checkexist->email;
								$subject = "RESET PASSWORD SUCCESSFULLY";
								$pagename = "emails.success_reset_password";
								$data = array('pagename' => $pagename, 'username' => $username, 'password' => $password, 'user_id' => $user_id);
								$to_email = $username;
								// $mail = sendMail($data, $to_email, $subject);

								$whatsappMsg = "Congratulations,\nYour password has been successfully updated.\nYour new password is -: " . $password . "\nUser Id - " . $user_id . "\nVisit : \n For any queries contact +919604819152";

								$countrycode = getCountryCode($Checkexist->country);

								sendSMS($Checkexist->mobile, $whatsappMsg);
								//  sendWhatsappMsg($countrycode, $Checkexist->mobile, $whatsappMsg);

								$actdata = array();
								$actdata['id'] = $Checkexist->id;
								$actdata['message'] = 'Password reset successfully';
								$actdata['status'] = 1;
								$actDta = Activitynotification::create($actdata);

								$arrStatus = Response::HTTP_OK;
								$arrCode = Response::$statusTexts[$arrStatus];
								$arrMessage = 'Password reset successfully';
								return sendResponse($arrStatus, $arrCode, $arrMessage, '');
							} else {

								$arrStatus = Response::HTTP_NOT_FOUND;
								$arrCode = Response::$statusTexts[$arrStatus];
								$arrMessage = 'Something went wrong,Please try again';
								return sendResponse($arrStatus, $arrCode, $arrMessage, '');
							}

							//-------------------------------------------------
						} else {

							$arrStatus = Response::HTTP_CONFLICT;
							$arrCode = Response::$statusTexts[$arrStatus];
							$arrMessage = 'Password and confirm password should be same';
							return sendResponse($arrStatus, $arrCode, $arrMessage, '');
						}
					}
					// }
				} else {

					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Invalid user';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
			} else {
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Invalid token';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
		} catch (Exception $e) {
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

	/**
	 * Change password
	 *
	 * @return \Illuminate\Http\Response
	 */

	function changePassword(Request $request) {
		try {
			$messsages = array(
				'new_pwd.regex' => 'Pasword contains first character letter, contains atleast 1 capital letter,combination of alphabets,numbers and special character i.e. ! @ # $ *',
			);
			//|regex:/^[a-zA-Z](?=.*\d)(?=.*[a-zA-Z])[0-9A-Za-z!@#$%]{6,50}$/
			$rules = array(
				'current_pwd' => 'required|min:6|max:30',
				'new_pwd' => ['string',
				    'required',
					'min:6', // must be at least 10 characters in length
					'max:30',
					'regex:/[a-z]/', // must contain at least one lowercase letter
					'regex:/[A-Z]/', // must contain at least one uppercase letter
					'regex:/[0-9]/', // must contain at least one digit
					'regex:/[@$!%*#?&]/', // must contain a special character',
				],
				'conf_pwd' => 'required|min:6|max:30|same:new_pwd',
			);

			$validator = checkvalidation($request->all(), $rules, $messsages);
			if (!empty($validator)) {
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = $validator;
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
			$id = Auth::User()->id;
			//dd($id);
			$arrInput            = $request->all();
			$arrInput['user_id'] = $id;
			$arrRules            = ['otp' => 'required|min:6|max:6'];
			$validator           = Validator::make($arrInput, $arrRules);
			if ($validator->fails()) {
				return setValidationErrorMessage($validator);
			}
			$verify_otp = verify_Otp($arrInput);
			// dd($verify_otp);
			if (!empty($verify_otp)) {
				if ($verify_otp['status'] == 200) {
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Invalid Otp Request!';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					// return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
				}
			} else {
				$arrStatus = Response::HTTP_NOT_FOUND;;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Invalid Otp Request!';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				// return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
			}
			$check_useractive = Auth::User();
			if (!empty($check_useractive)) {
				if(Hash::check($request->input('new_pwd'), $check_useractive->bcrypt_password)){
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Current and new password is same';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
				if (Hash::check($request->Input('current_pwd'), $check_useractive->bcrypt_password)) {

					/*if ($request->Input('verify') == '') {

						                        if (!empty($check_useractive) && $check_useractive->google2fa_status == 'enable') {
						                            // verify google authentication
						                            $arrData = array();
						                            $arrData['remember_token'] = $check_useractive->remember_token;
						                            $arrData['otpmode'] = 'FALSE';
						                            $arrData['google2faauth'] = 'TRUE';

						                            $arrStatus   = Response::HTTP_OK;
						                            $arrCode     = Response::$statusTexts[$arrStatus];
						                            $arrMessage  = 'Please enter your 2FA verification code';
						                            return sendResponse($arrStatus,$arrCode,$arrMessage,$arrData);

						                        } else if (!empty($check_useractive) && $check_useractive->google2fa_status == 'disable') {
						                            if (!empty($check_useractive) && $request->Input('otp') == '') {
						                                $arrData = array();
						                                $arrData['user_id'] = $check_useractive->user_id;
						                                $arrData['password'] = $request->Input('current_pwd');
						                                $arrData['remember_token'] = $check_useractive->remember_token;
						                                $arrData['otpmode'] = 'TRUE';
						                                $arrData['google2faauth'] = 'FALSE';
						                                if ($check_useractive->mobile != '' && $check_useractive->mobile != '0') {
						                                    $mask_mobile = maskmobilenumber($check_useractive->mobile);
						                                    $arrData['mobile'] = $mask_mobile;
						                                }
						                                $mask_email = maskEmail($check_useractive->email);
						                                $arrData['email'] = $mask_email;

						                                $arrStatus   = Response::HTTP_OK;
						                                $arrCode     = Response::$statusTexts[$arrStatus];
						                                $arrMessage  = 'Please select otp mode';
						                                return sendResponse($arrStatus,$arrCode,$arrMessage,$arrData);
						                            }
						                        } else {

						                            $arrStatus   = Response::HTTP_NOT_FOUND;
						                            $arrCode     = Response::$statusTexts[$arrStatus];
						                            $arrMessage  = 'Invalid user';
						                            return sendResponse($arrStatus,$arrCode,$arrMessage,'');
						                        }
					*/

					$updateData = array();
					$updateData['password'] = encrypt($request->Input('conf_pwd'));
					$updateData['bcrypt_password'] = bcrypt($request->Input('conf_pwd'));
					$updateData['ip_address'] = $_SERVER['REMOTE_ADDR'];
					$updateOtpSta = User::where('id', $check_useractive->id)->update($updateData);
					//dd($updateOtpSta);

					if (!empty($updateOtpSta)) {

						$arrStatus = Response::HTTP_OK;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = 'Password changed successfully';
						return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					} else {
						$arrStatus = Response::HTTP_NOT_FOUND;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = 'Current and new password is same';
						return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					}
					/*} else {
						                        $arrStatus   = Response::HTTP_NOT_FOUND;
						                        $arrCode     = Response::$statusTexts[$arrStatus];
						                        $arrMessage  = 'Something went wrong,Please try again';
						                        return sendResponse($arrStatus,$arrCode,$arrMessage,'');
					*/
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Current password not matched';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
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

	function changeAddress(Request $request){
		try{
			$messsages = array(
				// 'trn_address' => 'Currency address must not contain special characters',
				'btc' => 'Currency address must not contain special characters'
			);
			$rules = array(
				'trn' => 'nullable|alpha_num',
				'btc' => 'nullable|alpha_num',				
				'bnb_bsc' => 'nullable|alpha_num',				
				'usdt_trc20' => 'nullable|alpha_num',				
				'ltc' => 'nullable|alpha_num',				
				'doge' => 'nullable|alpha_num',				
			);

			$validator = checkvalidation($request->all(), $rules, $messsages);
			if (!empty($validator)) {
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = $validator;
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
			$id = Auth::User()->id;
			$arrInput            = $request->all();
			$arrInput['user_id'] = $id;
			$arrRules            = ['otp' => 'required|min:6|max:6'];
			$validator           = Validator::make($arrInput, $arrRules);
			if ($validator->fails()) {
				return setValidationErrorMessage($validator);
			}
			$verify_otp = verify_Otp($arrInput);
		// dd($verify_otp);
			if (!empty($verify_otp)) {
				if ($verify_otp['status'] == 200) {
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Invalid Otp Request!';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					// return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
				}
			} else {
				$arrStatus = Response::HTTP_NOT_FOUND;;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Invalid Otp Request!';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				// return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
			}
			if($request->trn == "" && $request->btc == "" && $request->bnb_bsc == "" && $request->usdt_trc20 == "" && $request->ltc == "" && $request->doge == "")
			{
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Address must be required';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}

			$getuser_id = Auth::user()->id;	
			if(!empty($getuser_id)){
				$addData = array();
				$addData['id'] = $getuser_id;
				$addData['status'] = 1;
				$addData['updated_by'] = $getuser_id;
				if (!empty($request->Input('trn'))) {
					$flag = 1;
					$addData['currency'] = "TRX";
					$addData['currency_address'] = trim($request->Input('trn'));
					$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
					$addTRXStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
					// if(empty($addTRXStatus)){
						if(strlen(trim($request->Input('trn'))) >= 26 && strlen(trim($request->Input('trn'))) <= 42){
							$split_array = str_split(trim($request->Input('trn')));
							if ($split_array[0] == 'T') {
								
							} elseif ($split_array[0] == 't') {

							} else {
								return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'TRX Address should be start with "T or t"', '');
							}
						}else{
							return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Tron address is not valid!', '');
						}
						
						$new_time = \Carbon\Carbon::now()->addDays(1);
						$token = md5(Auth::user()->user_id.$new_time);
						$addData['block_user_date_time'] = $new_time;
						$addData['token'] = $token;
						$addData['token_status'] = 0;
						if(empty($addTRXStatus)){
							$addressStatus = UserWithdrwalSetting::create($addData);
						}else if($addTRXStatus->currency_address != trim($request->Input('trn'))){
							$updateAddress['block_user_date_time'] = $new_time;
							$updateAddress['token'] = $token;
							$updateAddress['token_status'] = 0;
							$updateAddress['currency_address'] = trim($request->Input('trn'));
							$addressStatus = UserWithdrwalSetting::where('srno', $addTRXStatus->srno)->update($updateAddress);
						}else{
							$flag = 0;
						}
						if($flag == 1){
							if($addressStatus){
								$res_mail = addr_updateWithdraw_stop_mail($addData['currency'],$token,Auth::user()->user_id,Auth::user()->email);
								// // mail
								// $path       = Config::get('constants.settings.domainpath');
								// $pagename = "emails.withdraw_stop_oneday";
								// $subject = "Withdraw is stop for next 24 hrs";
								// $contant = "Withdraw is stop for next 24 hrs as your payment ".$addData['currency']." address is updated!";
								// $sub_contant = "If not then please ";
								// $click_here = "Click Here!";
								// $prof_url = $path."/user#/currency-address?token=".$token;
								// $data = array('pagename' => $pagename, 'contant' => $contant, 'username' => Auth::user()->user_id, 'sub_contant' => $sub_contant, 'click_here' => $click_here, 'prof_url' => $prof_url);
								// $email = Auth::user()->email;
								// $mail = sendMail($data, $email, $subject);
							}
						}
						/*$saveOldData = array();
						$saveOldData['id'] = $addData['id'];
						$saveOldData['trn_address'] = $addData['currency_address'];
						$InsertData = UsersChangeData::insert($saveOldData);*/

					// }else{
						// if($flag == 1){
						// 	if($addData['currency_address'] != $addTRXStatus->currency_address){
						// 		$new_time = \Carbon\Carbon::now()->addDays(1);
						// 		$token = md5(Auth::user()->user_id.$new_time);
						// 		$update_date['block_user_date_time'] = $new_time;
						// 		$update_date['token'] = $token;
						// 		$update_date['token_status'] = 0;
								
						// 		$update = UserWithdrwalSetting::where('srno',$addTRXStatus->srno)->update($update_date);
						// 		if($update){
						// 			$res_mail = addr_updateWithdraw_stop_mail($addData['currency'],$token,Auth::user()->user_id,Auth::user()->email);
						// 		}
						// 	}
							
						// }
					// }
				}
				if (!empty($request->Input('btc'))) {
					$flag = 2;
					$addData['currency'] = "BTC";
					$addData['currency_address'] = trim($request->Input('btc'));
					$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
					$addBTCStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
					if (!empty($request->btc)) {
						if (strlen(trim($request->Input('btc'))) >= 26 && strlen(trim($request->Input('btc'))) <= 42) {
							$split_array = str_split(trim($request->Input('btc')));
							if ($split_array[0] == 3)
							{
								
							} elseif ($split_array[0] == 1) {
								
							} elseif ($split_array[0] == 'b') {
							
							} else {
								return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Bitcoin address is not valid!', '');
							}
						} else {
							return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Bitcoin address is not valid!', '');
						}
					}
					$new_time = \Carbon\Carbon::now()->addDays(1);
					$token = md5(Auth::user()->user_id.$new_time);
					$addData['block_user_date_time'] = $new_time;
					$addData['token'] = $token;
					$addData['token_status'] = 0;
					if(empty($addBTCStatus)){
						$addressStatus = UserWithdrwalSetting::create($addData);
					}else if($addBTCStatus->currency_address != trim($request->Input('btc'))){
						$updateAddress['block_user_date_time'] = $new_time;
						$updateAddress['token'] = $token;
						$updateAddress['token_status'] = 0;
						$updateAddress['currency_address'] = trim($request->Input('btc'));
						$addressStatus = UserWithdrwalSetting::where('srno', $addBTCStatus->srno)->update($updateAddress);
					}else{
						$flag = 0;
					}
					
					if($flag == 2){
						if($addressStatus){
							$res_mail = addr_updateWithdraw_stop_mail($addData['currency'],$token,Auth::user()->user_id,Auth::user()->email);
						}
					}
				}
				
				if (!empty($request->Input('bnb_bsc'))) {
					$flag = 3;
					$addData['currency'] = "BNB-BSC";
					$addData['currency_address'] = trim($request->Input('bnb_bsc'));
					$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
					$addBTCStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
					// start with 0,1
					if(strlen(trim($request->Input('bnb_bsc'))) >= 26 && strlen(trim($request->Input('bnb_bsc'))) <= 42){
						$split_array = str_split(trim($request->Input('bnb_bsc')));
						if ($split_array[0] == '0') {
							
						} elseif ($split_array[0] == '1') {

						} else {
							return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'BNB-BSC Address should be start with "0 or 1"', '');
						}
					}else{
						return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'BNB-BSC address is not valid!', '');
					}
					$new_time = \Carbon\Carbon::now()->addDays(1);
					$token = md5(Auth::user()->user_id.$new_time);
					$addData['block_user_date_time'] = $new_time;
					$addData['token'] = $token;
					$addData['token_status'] = 0;
					if(empty($addBTCStatus)){
						$addressStatus = UserWithdrwalSetting::create($addData);
					}else if($addBTCStatus->currency_address != trim($request->Input('bnb_bsc'))){
						$updateAddress['block_user_date_time'] = $new_time;
						$updateAddress['token'] = $token;
						$updateAddress['token_status'] = 0;
						$updateAddress['currency_address'] = trim($request->Input('bnb_bsc'));
						$addressStatus = UserWithdrwalSetting::where('srno', $addBTCStatus->srno)->update($updateAddress);
					}else{
						$flag = 0;
					}

					if($flag == 3){
						if($addressStatus){
							$res_mail = addr_updateWithdraw_stop_mail($addData['currency'],$token,Auth::user()->user_id,Auth::user()->email);
						}
					}
				}

				if (!empty($request->Input('usdt_trc20'))) {
					$flag = 4;
					$addData['currency'] = "USDT-TRC20";
					$addData['currency_address'] = trim($request->Input('usdt_trc20'));
					$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
					$addBTCStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
					if(strlen(trim($request->Input('usdt_trc20'))) >= 26 && strlen(trim($request->Input('usdt_trc20'))) <= 42){
						$split_array = str_split(trim($request->Input('usdt_trc20')));
						if ($split_array[0] == 'T') {
							
						} elseif ($split_array[0] == 't') {

						} else {
							return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'USDT-TRC20 Address should be start with "T or t"', '');
						}
					}else{
						return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'USDT-TRC20 address is not valid!', '');
					}
					$new_time = \Carbon\Carbon::now()->addDays(1);
					$token = md5(Auth::user()->user_id.$new_time);
					$addData['block_user_date_time'] = $new_time;
					$addData['token'] = $token;
					$addData['token_status'] = 0;
					if(empty($addBTCStatus)){
						$addressStatus = UserWithdrwalSetting::create($addData);
					}else if($addBTCStatus->currency_address != trim($request->Input('usdt_trc20'))){
						$updateAddress['block_user_date_time'] = $new_time;
						$updateAddress['token'] = $token;
						$updateAddress['token_status'] = 0;
						$updateAddress['currency_address'] = trim($request->Input('usdt_trc20'));
						$addressStatus = UserWithdrwalSetting::where('srno', $addBTCStatus->srno)->update($updateAddress);
					}else{
						$flag = 0;
					}

					if($flag == 4){
						if($addressStatus){
							$res_mail = addr_updateWithdraw_stop_mail($addData['currency'],$token,Auth::user()->user_id,Auth::user()->email);
						}
					}					
				}

				if (!empty($request->Input('ltc'))) {
					$flag = 5;
					$addData['currency'] = "LTC";
					$addData['currency_address'] = trim($request->Input('ltc'));
					$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
					$addBTCStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
					// start with L,M,3,ltc1
						if(strlen(trim($request->Input('ltc'))) >= 26 && strlen(trim($request->Input('ltc'))) <= 42){
							$split_array = str_split(trim($request->Input('ltc')));
							$split_array1 = str_split(trim($request->Input('ltc')),4);
							if ($split_array[0] == 3)
							{
								
							} elseif ($split_array[0] == 'L') {
								
							} elseif ($split_array[0] == 'M') {

							} elseif ($split_array1[0] == 'ltc1') {
							
							} else {
								return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Litecoin address should be start with "L or M or ltc1 or 3"', '');
							}
						}else{
							return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Litecoin address is not valid!', '');
						}
					$new_time = \Carbon\Carbon::now()->addDays(1);
					$token = md5(Auth::user()->user_id.$new_time);
					$addData['block_user_date_time'] = $new_time;
					$addData['token'] = $token;
					$addData['token_status'] = 0;
					if(empty($addBTCStatus)){
						$addressStatus = UserWithdrwalSetting::create($addData);
					}else if($addBTCStatus->currency_address != trim($request->Input('ltc'))){
						$updateAddress['block_user_date_time'] = $new_time;
						$updateAddress['token'] = $token;
						$updateAddress['token_status'] = 0;
						$updateAddress['currency_address'] = trim($request->Input('ltc'));
						$addressStatus = UserWithdrwalSetting::where('srno', $addBTCStatus->srno)->update($updateAddress);
					}else{
						$flag = 0;
					}

					if($flag == 5){
						if($addressStatus){
							$res_mail = addr_updateWithdraw_stop_mail($addData['currency'],$token,Auth::user()->user_id,Auth::user()->email);
						}
					}						
				}

				if (!empty($request->Input('doge'))) {
					$flag = 6;
					$addData['currency'] = "DOGE";
					$addData['currency_address'] = trim($request->Input('doge'));
					$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
					$addBTCStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
					if(strlen(trim($request->Input('doge'))) >= 26 && strlen(trim($request->Input('doge'))) <= 42){

					}else{
						return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Doge address is not valid!', '');
					}
					$new_time = \Carbon\Carbon::now()->addDays(1);
					$token = md5(Auth::user()->user_id.$new_time);
					$addData['block_user_date_time'] = $new_time;
					$addData['token'] = $token;
					$addData['token_status'] = 0;
					if(empty($addBTCStatus)){
						$addressStatus = UserWithdrwalSetting::create($addData);
					}else if($addBTCStatus->currency_address != trim($request->Input('doge'))){
						$updateAddress['block_user_date_time'] = $new_time;
						$updateAddress['token'] = $token;
						$updateAddress['token_status'] = 0;
						$updateAddress['currency_address'] = trim($request->Input('doge'));
						$addressStatus = UserWithdrwalSetting::where('srno', $addBTCStatus->srno)->update($updateAddress);
					}else{
						$flag = 0;
					}
					if($flag == 6){
						if($addressStatus){
							$res_mail = addr_updateWithdraw_stop_mail($addData['currency'],$token,Auth::user()->user_id,Auth::user()->email);
						}
					}
				}

				if (!empty($request->Input('bch'))) {
					$flag = 7;
					$addData['currency'] = "BCH";
					$addData['currency_address'] = trim($request->Input('bch'));
					$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
					$addBTCStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
					$new_time = \Carbon\Carbon::now()->addDays(1);
					$token = md5(Auth::user()->user_id.$new_time);
					$addData['block_user_date_time'] = $new_time;
					$addData['token'] = $token;
					$addData['token_status'] = 0;
					if(empty($addBTCStatus)){
						$addressStatus = UserWithdrwalSetting::create($addData);
					}else if($addBTCStatus->currency_address != trim($request->Input('bch'))){
						$updateAddress['block_user_date_time'] = $new_time;
						$updateAddress['token'] = $token;
						$updateAddress['token_status'] = 0;
						$updateAddress['currency_address'] = trim($request->Input('bch'));
						$addressStatus = UserWithdrwalSetting::where('srno', $addBTCStatus->srno)->update($updateAddress);
					}else{
						$flag = 0;
					}
					if($flag == 7){
						if($addressStatus){
							$res_mail = addr_updateWithdraw_stop_mail($addData['currency'],$token,Auth::user()->user_id,Auth::user()->email);
						}
					}
				}

				if (!empty($request->Input('shib'))) {
					$flag = 8;
					$addData['currency'] = "SHIB";
					$addData['currency_address'] = trim($request->Input('shib'));
					$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
					$addBTCStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
					$new_time = \Carbon\Carbon::now()->addDays(1);
					$token = md5(Auth::user()->user_id.$new_time);
					$addData['block_user_date_time'] = $new_time;
					$addData['token'] = $token;
					$addData['token_status'] = 0;
					if(empty($addBTCStatus)){
						$addressStatus = UserWithdrwalSetting::create($addData);
					}else if($addBTCStatus->currency_address != trim($request->Input('shib'))){
						$updateAddress['block_user_date_time'] = $new_time;
						$updateAddress['token'] = $token;
						$updateAddress['token_status'] = 0;
						$updateAddress['currency_address'] = trim($request->Input('shib'));
						$addressStatus = UserWithdrwalSetting::where('srno', $addBTCStatus->srno)->update($updateAddress);
					}else{
						$flag = 0;
					}
					if($flag == 8){
						if($addressStatus){
							$res_mail = addr_updateWithdraw_stop_mail($addData['currency'],$token,Auth::user()->user_id,Auth::user()->email);
						}
					}
				}

				if (!empty($request->Input('pm'))) {
					$flag = 9;
					$addData['currency'] = "PM";
					$addData['currency_address'] = trim($request->Input('pm'));
					$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
					$addBTCStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
					$new_time = \Carbon\Carbon::now()->addDays(1);
					$token = md5(Auth::user()->user_id.$new_time);
					$addData['block_user_date_time'] = $new_time;
					$addData['token'] = $token;
					$addData['token_status'] = 0;
					if(empty($addBTCStatus)){
						$addressStatus = UserWithdrwalSetting::create($addData);
					}else if($addBTCStatus->currency_address != trim($request->Input('pm'))){
						$updateAddress['block_user_date_time'] = $new_time;
						$updateAddress['token'] = $token;
						$updateAddress['token_status'] = 0;
						$updateAddress['currency_address'] = trim($request->Input('pm'));
						$addressStatus = UserWithdrwalSetting::where('srno', $addBTCStatus->srno)->update($updateAddress);
					}else{
						$flag = 0;
					}
					if($flag == 9){
						if($addressStatus){
							$res_mail = addr_updateWithdraw_stop_mail($addData['currency'],$token,Auth::user()->user_id,Auth::user()->email);
						}
					}					
				}

				// $updateData = User::where('id', $getuser_id)->update($arrData);
				if (!empty($addressStatus)) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'User address updated successfully';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				} else {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Already updated with same data';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
			}
		}catch(Exception $e){
			// dd($e);
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

	public function checkUserAddrToken(Request $request){
		try{
            $arrInput  = $request->all();
            $arrRules  = ['token' => 'required'];
            $validator = Validator::make($arrInput, $arrRules);
            if ($validator->fails()) {
                return setValidationErrorMessage($validator);
            }

			$get_data = UserWithdrwalSetting::select('srno','id','currency','currency_address')->where([['token',$request->token],['token_status',0]])->first();
			if(!empty($get_data)){
				$today = \Carbon\Carbon::now();
				$check_data = UserCurrAddrHistory::select('id')->where([['user_id',$get_data->id],['entry_time',$today]])->first();
				if(empty($check_data)){
					$insert = new UserCurrAddrHistory;
					$insert->user_id = $get_data->id;
					$insert->currency = $get_data->currency;
					$insert->currency_address = $get_data->currency_address;
					$insert->entry_time = \Carbon\Carbon::now();
					$insert->save();
					// UserWithdrwalSetting::where('srno',$get_data->srno)->update(array('token_status' => 1,'currency_address' => NULL, 'block_user_date_time' => NULL));
					UserWithdrwalSetting::where('srno',$get_data->srno)->delete();
					$data['flag'] = 1;
					$data['currency'] = $get_data->currency;
					return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Address Reset Successfully!', $data);
				}else{
					$data['flag'] = 1;
					$data['currency'] = $get_data->currency;
					return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Already Address Reset Successfully!', $data);
				}
			}else{
				$data['flag'] = 0;
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Link expired or invalid request', $data);
			}

		}catch(\Exception $e){
			$data['flag'] = 0;
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $data);
		}
	}

	/**
	 * check user excited or not by passing parameter
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function checkUserExist(Request $request) {
		// try {
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
				$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'User id required';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			} else {
				//check wether user exist or not by user_id
				// dd(trim($request->user_id));
				if($request->user_id == 'admin' || $request->user_id == 'Admin'){
					$totalRefCount = User::where('ref_user_id', 1)->count();
					// dd($totalRefCount);
					if($totalRefCount > 0){
						$arrStatus = Response::HTTP_NOT_FOUND;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = 'Invalid user';
						return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					}
				}
				$checkUserExist = User::where('user_id', trim($request->user_id))->select('id','user_id','fullname','remember_token')->first();	
				// dd($checkUserExist);			
				if (!empty($checkUserExist)) {
					$arrObject['id'] = $checkUserExist->id;
					$arrObject['user_id'] = $checkUserExist->user_id;
					$arrObject['fullname'] = $checkUserExist->fullname;
					$arrObject['remember_token'] = $checkUserExist->remember_token;

					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'User available';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrObject);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Invalid user';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
			}
		// } catch (Exception $e) {
		// 	$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
		// 	$arrCode = Response::$statusTexts[$arrStatus];
		// 	$arrMessage = 'Something went wrong,Please try again';
		// 	return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		// }
	}

	/**
	 * update user profile
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateUserData(Request $request) {
	

			if (!empty($request->Input('fullname'))) {
						$rules = array('fullname' => 'required|max:30|regex:/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/');
					}

                  if (!empty($request->Input('mobile'))) {
						$rules = array('mobile' => 'required|numeric|digits:10');
					}
					if (!empty($request->Input('email'))) {
						$rules = array('email' => 'required|email|max:50');
					}
					/*if (!empty($request->Input('paypal_address'))) {
						$rules = array('paypal_address' => 'required|email|max:100');
					}
*/
					if (!empty($request->Input('btc'))) {
						$rules = array('btc' => 'regex:/^\S*$/');
					}
					$validator = checkvalidation($request->all(), $rules, '');
					if (!empty($validator)) {

						$arrStatus = Response::HTTP_NOT_FOUND;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = $validator;
						return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					}

		$getuser_id = Auth::user()->id;			
 
		$otpdata = Otp::select('otp_id','otp_status','otp')
		    ->where('id', Auth::user()->id)
			->where('otp', md5($request->otp))
			->where('otp_status', '=',0)
		    ->orderBy('entry_time', 'desc')->first();
		
		// $otpdata= "1";
		if (!empty($otpdata)) {
			// Otp::where('otp_id', $otpdata->otp_id)->update(['otp_status' => 1]);
				 Otp::where('otp_id', $otpdata->otp_id)->delete();

				try {

					//$CheckActive = User::where([['remember_token', '=', trim($request->Input('remember_token'))], ['status', '=', 'Active']])->first();

					//---------update sponser id--------------
					$arrData = array();
					$addData = array();
					$addData['id'] = $getuser_id;
					$addData['status'] = 1;
					$addData['updated_by'] = $getuser_id;
					/*if (!empty($request->Input('paypal_address'))) {
						$arrData['paypal_address'] = trim($request->Input('paypal_address'));
					}*/

					if (!empty($request->Input('fullname'))) {
						$arrData['fullname'] = trim($request->Input('fullname'));
					}
// dd($request->Input('trn_address'));
					if ($request->Input('trn_address') != "null") {
						if(strlen($request->Input('trn_address')) >= 26 && strlen($request->Input('trn_address')) <= 45){
							$split_array = str_split(trim($request->Input('trn_address')));
							if($split_array[0] == 't' || $split_array[0] == 'T') 
							{
								
								
							}  else {
								$arrStatus   = Response::HTTP_NOT_FOUND;
								$arrCode     = Response::$statusTexts[$arrStatus];
								$arrMessage  = 'TRON Address should be start with T or t'; 
								return sendResponse($arrStatus,$arrCode,$arrMessage,'');
							}
						}else{
							$arrStatus   = Response::HTTP_NOT_FOUND;
							$arrCode     = Response::$statusTexts[$arrStatus];
							$arrMessage  = 'TRON Address must have minimum 26 and maximum 45 characters'; 
							return sendResponse($arrStatus,$arrCode,$arrMessage,'');
						}
						$arrData['trn_address'] = trim($request->Input('trn_address'));
						$addData['currency'] = "TRX";
						$addData['currency_address'] = trim($request->Input('trn_address'));
						$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
						$addTRXStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
						$new_time = \Carbon\Carbon::now()->addDays(1);
						$token = md5(Auth::user()->user_id.$new_time);
						$addData['block_user_date_time'] = $new_time;
						$addData['token'] = $token;
						$addData['token_status'] = 0;
						if(empty($addTRXStatus)){
							$addressStatus = UserWithdrwalSetting::create($addData);
						}else{
							$updateAddress['block_user_date_time'] = $new_time;
							$updateAddress['token'] = $token;
							$updateAddress['token_status'] = 0;
							$updateAddress['currency_address'] = trim($request->Input('trn_address'));
							$addressStatus = UserWithdrwalSetting::where('srno', $addTRXStatus->srno)->update($updateAddress);
						}
					}
					if ($request->Input('ethereum') != "null") {
						if(strlen($request->Input('ethereum')) >= 26 && strlen($request->Input('ethereum')) <= 45){
							$split_array = str_split(trim($request->Input('ethereum')));
							// dd($split_array[0]);
							if($split_array[0] == "0")
							{
	
							}  else if($split_array[1] == 'x') {
							
							} else {
								$arrStatus   = Response::HTTP_NOT_FOUND;
								$arrCode     = Response::$statusTexts[$arrStatus];
								$arrMessage  = 'ETH Address should be start with 0x'; 
								return sendResponse($arrStatus,$arrCode,$arrMessage,'');
							}
						}else{
							$arrStatus   = Response::HTTP_NOT_FOUND;
							$arrCode     = Response::$statusTexts[$arrStatus];
							$arrMessage  = 'ETH Address must have minimum 26 and maximum 45 characters'; 
							return sendResponse($arrStatus,$arrCode,$arrMessage,'');
						}
						$arrData['ethereum'] = trim($request->Input('ethereum'));
						$addData['currency'] = "ETH";
						$addData['currency_address'] = trim($request->Input('ethereum'));
						$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
						$addTRXStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
						$new_time = \Carbon\Carbon::now()->addDays(1);
						$token = md5(Auth::user()->user_id.$new_time);
						$addData['block_user_date_time'] = $new_time;
						$addData['token'] = $token;
						$addData['token_status'] = 0;
						if(empty($addTRXStatus)){
							$addressStatus = UserWithdrwalSetting::create($addData);
						}else{
							$updateAddress['block_user_date_time'] = $new_time;
							$updateAddress['token'] = $token;
							$updateAddress['token_status'] = 0;
							$updateAddress['currency_address'] = trim($request->Input('ethereum'));
							$addressStatus = UserWithdrwalSetting::where('srno', $addTRXStatus->srno)->update($updateAddress);
						}
					}

						if ($request->Input('doge_address') != "null") {
						if(strlen($request->Input('doge_address')) >= 26 && strlen($request->Input('doge_address')) <= 45){
							$split_array = str_split(trim($request->Input('doge_address')));
							if($split_array[0] == 'd') 
							{
								
								
							}  else {
								$arrStatus   = Response::HTTP_NOT_FOUND;
								$arrCode     = Response::$statusTexts[$arrStatus];
								$arrMessage  = 'Doge Address should be start with d'; 
								return sendResponse($arrStatus,$arrCode,$arrMessage,'');
							}
						}else{
							$arrStatus   = Response::HTTP_NOT_FOUND;
							$arrCode     = Response::$statusTexts[$arrStatus];
							$arrMessage  = 'Doge Address must have minimum 26 and maximum 45 characters'; 
							return sendResponse($arrStatus,$arrCode,$arrMessage,'');
						}
						$arrData['doge_address'] = trim($request->Input('doge_address'));
						$addData['currency'] = "DOGE";
						$addData['currency_address'] = trim($request->Input('doge_address'));
						$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
						$addDOGEStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
						$new_time = \Carbon\Carbon::now()->addDays(1);
						$token = md5(Auth::user()->user_id.$new_time);
						$addData['block_user_date_time'] = $new_time;
						$addData['token'] = $token;
						$addData['token_status'] = 0;
						if(empty($addDOGEStatus)){
							$addressStatus = UserWithdrwalSetting::create($addData);
						}else{
							$updateAddress['block_user_date_time'] = $new_time;
							$updateAddress['token'] = $token;
							$updateAddress['token_status'] = 0;
							$updateAddress['currency_address'] = trim($request->Input('doge_address'));
							$addressStatus = UserWithdrwalSetting::where('srno', $addDOGEStatus->srno)->update($updateAddress);
						}
					}


						if ($request->Input('ltc_address') != "null") {
						if(strlen($request->Input('ltc_address')) >= 26 && strlen($request->Input('ltc_address')) <= 45){
							$split_array = str_split(trim($request->Input('ltc_address')));
							if($split_array[0] == 'L') 
							{		
								
							} elseif ($split_array[0] == 'M') {
								
							} elseif ($split_array[0] == 'l' && $split_array[1] == 't' && $split_array[2] == 'c'  && $split_array[3] == '1') {

							}
							else {
								$arrStatus   = Response::HTTP_NOT_FOUND;
								$arrCode     = Response::$statusTexts[$arrStatus];
								$arrMessage  = 'Litecoin Address should be start with L or M or ltc1'; 
								return sendResponse($arrStatus,$arrCode,$arrMessage,'');
							}
						}else{
							$arrStatus   = Response::HTTP_NOT_FOUND;
							$arrCode     = Response::$statusTexts[$arrStatus];
							$arrMessage  = 'Litecoin Address must have minimum 26 and maximum 45 characters'; 
							return sendResponse($arrStatus,$arrCode,$arrMessage,'');
						}
						$arrData['ltc_address'] = trim($request->Input('ltc_address'));
						$addData['currency'] = "LTC";
						$addData['currency_address'] = trim($request->Input('ltc_address'));
						$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
						$addLTCStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
						$new_time = \Carbon\Carbon::now()->addDays(1);
						$token = md5(Auth::user()->user_id.$new_time);
						$addData['block_user_date_time'] = $new_time;
						$addData['token'] = $token;
						$addData['token_status'] = 0;
						if(empty($addLTCStatus)){
							$addressStatus = UserWithdrwalSetting::create($addData);
						}else{
							$updateAddress['block_user_date_time'] = $new_time;
							$updateAddress['token'] = $token;
							$updateAddress['token_status'] = 0;
							$updateAddress['currency_address'] = trim($request->Input('ltc_address'));
							$addressStatus = UserWithdrwalSetting::where('srno', $addLTCStatus->srno)->update($updateAddress);
						}
					}

					if ($request->Input('usdt_taddress') != "null") {
						if(strlen($request->Input('usdt_taddress')) >= 26 && strlen($request->Input('usdt_taddress')) <= 45){
							$split_array = str_split(trim($request->Input('usdt_taddress')));
							if($split_array[0] == 'T') 
							{		
								
							} elseif ($split_array[0] == 't') {
								
							}
							else {
								$arrStatus   = Response::HTTP_NOT_FOUND;
								$arrCode     = Response::$statusTexts[$arrStatus];
								$arrMessage  = 'USDT Address should be start with T or t'; 
								return sendResponse($arrStatus,$arrCode,$arrMessage,'');
							}
						}else{
							$arrStatus   = Response::HTTP_NOT_FOUND;
							$arrCode     = Response::$statusTexts[$arrStatus];
							$arrMessage  = 'USDT Address must have minimum 26 and maximum 45 characters'; 
							return sendResponse($arrStatus,$arrCode,$arrMessage,'');
						}
						$arrData['usdt_taddress'] = trim($request->Input('usdt_taddress'));
						$addData['currency'] = "USDT";
						$addData['currency_address'] = trim($request->Input('usdt_taddress'));
						$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
						$addUSDTStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
						$new_time = \Carbon\Carbon::now()->addDays(1);
						$token = md5(Auth::user()->user_id.$new_time);
						$addData['block_user_date_time'] = $new_time;
						$addData['token'] = $token;
						$addData['token_status'] = 0;
						if(empty($addSUSDTtatus)){
							$addressStatus = UserWithdrwalSetting::create($addData);
						}else{
							$updateAddress['block_user_date_time'] = $new_time;
							$updateAddress['token'] = $token;
							$updateAddress['token_status'] = 0;
							$updateAddress['currency_address'] = trim($request->Input('usdt_taddress'));
							$addressStatus = UserWithdrwalSetting::where('srno', $addUSDTStatus->srno)->update($updateAddress);
						}
					}

					if ($request->Input('solona_address') != "null") {
						if(strlen($request->Input('solona_address')) >= 26 && strlen($request->Input('solona_address')) <= 45){
							$split_array = str_split(trim($request->Input('solona_address')));
							if($split_array[0] == 's') 
							{		
								
							}
							else {
								$arrStatus   = Response::HTTP_NOT_FOUND;
								$arrCode     = Response::$statusTexts[$arrStatus];
								$arrMessage  = 'solana Address should be start with s'; 
								return sendResponse($arrStatus,$arrCode,$arrMessage,'');
							}
						}else{
							$arrStatus   = Response::HTTP_NOT_FOUND;
							$arrCode     = Response::$statusTexts[$arrStatus];
							$arrMessage  = 'solana Address must have minimum 26 and maximum 45 characters'; 
							return sendResponse($arrStatus,$arrCode,$arrMessage,'');
						}
						$arrData['solona_address'] = trim($request->Input('solona_address'));
						$addData['currency'] = "SOL";
						$addData['currency_address'] = trim($request->Input('solona_address'));
						$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
						$addUSDTStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
						$new_time = \Carbon\Carbon::now()->addDays(1);
						$token = md5(Auth::user()->user_id.$new_time);
						$addData['block_user_date_time'] = $new_time;
						$addData['token'] = $token;
						$addData['token_status'] = 0;
						if(empty($addSUSDTtatus)){
							$addressStatus = UserWithdrwalSetting::create($addData);
						}else{
							$updateAddress['block_user_date_time'] = $new_time;
							$updateAddress['token'] = $token;
							$updateAddress['token_status'] = 0;
							$updateAddress['currency_address'] = trim($request->Input('solona_address'));
							$addressStatus = UserWithdrwalSetting::where('srno', $addUSDTStatus->srno)->update($updateAddress);
						}
					}

						if ($request->Input('ripple_address') != "null") {
						if(strlen($request->Input('ripple_address')) >= 26 && strlen($request->Input('ripple_address')) <= 45){
							$split_array = str_split(trim($request->Input('ripple_address')));
							if($split_array[0] == 'r') 
							{		
								
							}
							else {
								$arrStatus   = Response::HTTP_NOT_FOUND;
								$arrCode     = Response::$statusTexts[$arrStatus];
								$arrMessage  = 'XRP Address should be start with r'; 
								return sendResponse($arrStatus,$arrCode,$arrMessage,'');
							}
						}else{
							$arrStatus   = Response::HTTP_NOT_FOUND;
							$arrCode     = Response::$statusTexts[$arrStatus];
							$arrMessage  = 'XRP Address must have minimum 26 and maximum 45 characters'; 
							return sendResponse($arrStatus,$arrCode,$arrMessage,'');
						}
						$arrData['ripple_address'] = trim($request->Input('ripple_address'));
						$addData['currency'] = "XRP";
						$addData['currency_address'] = trim($request->Input('ripple_address'));
						$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
						$addXRPStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
						$new_time = \Carbon\Carbon::now()->addDays(1);
						$token = md5(Auth::user()->user_id.$new_time);
						$addData['block_user_date_time'] = $new_time;
						$addData['token'] = $token;
						$addData['token_status'] = 0;
						if(empty($addXRPStatus)){
							$addressStatus = UserWithdrwalSetting::create($addData);
						}else{
							$updateAddress['block_user_date_time'] = $new_time;
							$updateAddress['token'] = $token;
							$updateAddress['token_status'] = 0;
							$updateAddress['currency_address'] = trim($request->Input('ripple_address'));
							$addressStatus = UserWithdrwalSetting::where('srno', $addXRPStatus->srno)->update($updateAddress);
						}
					}
			/*		if (!empty($request->Input('bnb_address'))) {
						if(strlen($request->Input('bnb_address')) >= 26 && strlen($request->Input('bnb_address')) <= 45){
                            $split_array = str_split(trim($request->Input('bnb_address')));
                            // dd($split_array[0]);
                            if($split_array[0] == '0' || $split_array[0] == '1') 
                            {
                              
                            }  else {
                                $arrStatus   = Response::HTTP_NOT_FOUND;
                                $arrCode     = Response::$statusTexts[$arrStatus];
                                $arrMessage  = 'BNB Address should be start with 0 or 1'; 
                                return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                            }
                        }else{
                            $arrStatus   = Response::HTTP_NOT_FOUND;
                            $arrCode     = Response::$statusTexts[$arrStatus];
                            $arrMessage  = 'BNB Address must have minimum 26 and maximum 45 characters'; 
                            return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                        }
						$arrData['bnb_address'] = trim($request->Input('bnb_address'));
						$addData['currency'] = "BNB-BSC";
						$addData['currency_address'] = trim($request->Input('bnb_address'));
						$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
						$addTRXStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
						$new_time = \Carbon\Carbon::now()->addDays(1);
						$token = md5(Auth::user()->user_id.$new_time);
						$addData['block_user_date_time'] = $new_time;
						$addData['token'] = $token;
						$addData['token_status'] = 0;
						if(empty($addTRXStatus)){
							$addressStatus = UserWithdrwalSetting::create($addData);
						}else{
							$updateAddress['block_user_date_time'] = $new_time;
							$updateAddress['token'] = $token;
							$updateAddress['token_status'] = 0;
							$updateAddress['currency_address'] = trim($request->Input('bnb_address'));
							$addressStatus = UserWithdrwalSetting::where('srno', $addTRXStatus->srno)->update($updateAddress);
						}
					}      */
					
					if (!empty($request->Input('mobile'))) {
						$arrData['mobile'] = trim($request->Input('mobile'));
					}
					if (!empty($request->Input('email'))) {
						$arrData['email'] = trim($request->Input('email'));
					}
					if ($request->Input('btc') != "null") {
						if(strlen($request->Input('btc')) >= 26 && strlen($request->Input('btc')) <= 45){
							$split_array = str_split(trim($request->Input('btc')));
							if ($split_array[0] == 3)
							{
								
							} elseif ($split_array[0] == 1) {
								
							} elseif ($split_array[0] == 'b') {
								
							} else {
								$arrStatus   = Response::HTTP_NOT_FOUND;
								$arrCode     = Response::$statusTexts[$arrStatus];
								$arrMessage  = 'BTC Address should be start with b or 1 or 3'; 
								return sendResponse($arrStatus,$arrCode,$arrMessage,'');
							}
						}else{
							$arrStatus   = Response::HTTP_NOT_FOUND;
							$arrCode     = Response::$statusTexts[$arrStatus];
							$arrMessage  = 'BTC Address must have minimum 26 and maximum 45 characters'; 
							return sendResponse($arrStatus,$arrCode,$arrMessage,'');
						}

						$arrData['btc_address'] = trim($request->Input('btc'));
						$addData['currency'] = "BTC";
						$addData['currency_address'] = trim($request->Input('btc'));
						$addData['ip_address'] = $_SERVER['REMOTE_ADDR'];
						$addTRXStatus = UserWithdrwalSetting::where([['id',$getuser_id],['currency',$addData['currency']],['status',1]])->first();
						$new_time = \Carbon\Carbon::now()->addDays(1);
						$token = md5(Auth::user()->user_id.$new_time);
						$addData['block_user_date_time'] = $new_time;
						$addData['token'] = $token;
						$addData['token_status'] = 0;
						if(empty($addTRXStatus)){
							$addressStatus = UserWithdrwalSetting::create($addData);
						}else{
							$updateAddress['block_user_date_time'] = $new_time;
							$updateAddress['token'] = $token;
							$updateAddress['token_status'] = 0;
							$updateAddress['currency_address'] = trim($request->Input('btc'));
							$addressStatus = UserWithdrwalSetting::where('srno', $addTRXStatus->srno)->update($updateAddress);
						}
					}
	
					if (!empty($request->Input('country'))) {
						$arrData['country'] = trim($request->Input('country'));
					}

					$file       = Input::file('popup_image');
				
					$newUrl = '';
					
					if($request->hasFile('popup_image')) {
						$url    = Config::get('constants.settings.aws_url');
						// dd($url);
						$fileName = Storage::disk('s3')->put("user_profile", $file, "public");
						$newUrl=$url.$fileName;
						$arrData['user_profile'] = $newUrl ;
					}
					// dd($arrData);
					if (!empty($arrData)) {
						//UserInfo

						$oldUserData = $arrData;

						//-----iget old user data and inset----------------
						$oldUserData = DB::table('tbl_users')
						    ->select('address','country','holder_name','pan_no','bank_name','ifsc_code','user_id','mobile','btc_address','bnb_address','trn_address','ethereum','email')
							->where('id', $getuser_id)
							->first();
						$oldUserData->ip = $request->ip();
						$oldUserData->updated_by = $getuser_id;

						
                        // unset($oldUserData->blockby_cron);
						

						//save old data
						$saveOldData = DB::table('tbl_users_change_data')->insert((array) $oldUserData);
						$updateData = User::where('id', $getuser_id)->update($arrData);
						
					}
					//-------------------------------------------------
					if (!empty($updateData)) {
						$arrStatus = Response::HTTP_OK;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = 'User data updated successfully';
						return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					} else {
						$arrStatus = Response::HTTP_OK;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = 'Already updated with same data';
						return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					}
				} catch (Exception $e) {
					dd($e);
					$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Something went wrong,Please try again';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
			
		} else {
			$arrStatus = Response::HTTP_NOT_FOUND;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Incorrect OTP Or OTP Already Used';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

	function updateUserDataDetails(Request $request)
	{
		$id = Auth::User()->id;
		$arrInput            = $request->all();
		$arrInput['user_id'] = $id;
		$arrRules            = ['otp' => 'required|min:6|max:6'];
		$validator           = Validator::make($arrInput, $arrRules);
		if ($validator->fails()) {
			return setValidationErrorMessage($validator);
		}
		$verify_otp = verify_Otp($arrInput);
       // dd($verify_otp);
		if (!empty($verify_otp)) {
			if ($verify_otp['status'] == 200) {
			} else {
                $arrStatus = Response::HTTP_NOT_FOUND;;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Invalid Otp Request!';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				// return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
			}
		} else {
            $arrStatus = Response::HTTP_NOT_FOUND;;
            $arrCode = Response::$statusTexts[$arrStatus];
            $arrMessage = 'Invalid Otp Request!';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			// return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
		}

					if (!empty($request->Input('fullname'))) {
						$rules = array('fullname' => 'required|max:30|regex:/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/');
					}

                  if (!empty($request->Input('mobile'))) {
						$rules = array('mobile' => 'required|numeric|digits:10');
					}
					if (!empty($request->Input('email'))) {
						$rules = array('email' => 'required|email|max:50');
					}
					/*if (!empty($request->Input('paypal_address'))) {
						$rules = array('paypal_address' => 'required|email|max:100');
					}
*/
					if (!empty($request->Input('btc'))) {
						$rules = array('btc' => 'regex:/^\S*$/');
					}
					$validator = checkvalidation($request->all(), $rules, '');
					if (!empty($validator)) {

						$arrStatus = Response::HTTP_NOT_FOUND;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = $validator;
						return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					}

		$getuser_id = Auth::user()->id;			
 

		try {
			

			//$CheckActive = User::where([['remember_token', '=', trim($request->Input('remember_token'))], ['status', '=', 'Active']])->first();

			//---------update sponser id--------------
			$arrData = array();

			/*if (!empty($request->Input('paypal_address'))) {
				$arrData['paypal_address'] = trim($request->Input('paypal_address'));
			}*/

			if (!empty($request->Input('fullname'))) {
				$arrData['fullname'] = trim($request->Input('fullname'));
			}

				if (!empty($request->Input('trn_address'))) {
				$arrData['trn_address'] = trim($request->Input('trn_address'));
			}
				if (!empty($request->Input('ethereum'))) {
				$arrData['ethereum'] = trim($request->Input('ethereum'));
			}

				if (!empty($request->Input('bnb_address'))) {
				$arrData['bnb_address'] = trim($request->Input('bnb_address'));
			}
			
			if (!empty($request->Input('mobile'))) {
				$arrData['mobile'] = trim($request->Input('mobile'));
			}
			if (!empty($request->Input('email'))) {
				$arrData['email'] = trim($request->Input('email'));
			}
			if (!empty($request->Input('btc'))) {
				$arrData['btc_address'] = trim($request->Input('btc'));
			}

			if (!empty($request->Input('country'))) {
				$arrData['country'] = trim($request->Input('country'));
			}
			if (!empty($request->Input('account_no'))) {
				$arrData['account_no'] = trim($request->Input('account_no'));
			}
			if (!empty($request->Input('holder_name'))) {
				$arrData['holder_name'] = trim($request->Input('holder_name'));
			}
			if (!empty($request->Input('pan_no'))) {
				$arrData['pan_no'] = trim($request->Input('pan_no'));
			}
			if (!empty($request->Input('bank_name'))) {
				$arrData['bank_name'] = trim($request->Input('bank_name'));
			}

			if (!empty($request->Input('city'))) {
				$arrData['city'] = trim($request->Input('city'));
			}


			/*if (!empty($request->Input('perfect_money_address'))) {
				$arrData['perfect_money_address'] = trim($request->Input('perfect_money_address'));
			}
			if (!empty($request->Input('facebook_link'))) {
				$arrData['facebook_link'] = trim($request->Input('facebook_link'));
			}
			if (!empty($request->Input('linkedin_link'))) {
				$arrData['linkedin_link'] = trim($request->Input('linkedin_link'));
			}
			if (!empty($request->Input('twitter_link'))) {
				$arrData['twitter_link'] = trim($request->Input('twitter_link'));
			}
			if (!empty($request->Input('instagram_link'))) {
				$arrData['instagram_link'] = trim($request->Input('instagram_link'));
			}*/

			if (!empty($request->Input('address'))) {
				$arrData['address'] = trim($request->Input('address'));
			}

			if (!empty($request->Input('ifsc_code'))) {
				$arrData['ifsc_code'] = trim($request->Input('ifsc_code'));
			}
			if (!empty($request->Input('branch_name'))) {
				$arrData['branch_name'] = trim($request->Input('branch_name'));
			}
			if (!empty($request->Input('mobile'))) {
				$arrData['mobile'] = trim($request->Input('mobile'));
			}


			if (!empty($arrData)) {
				//UserInfo

				$oldUserData = $arrData;

				//-----iget old user data and inset----------------
				$oldUserData = DB::table('tbl_users')
				    ->select('address','country','holder_name','pan_no','bank_name','ifsc_code','user_id','mobile','btc_address','bnb_address','trn_address','ethereum','email')
					->where('id', $getuser_id)
					->first();
				$oldUserData->ip = $request->ip();
				$oldUserData->updated_by = $getuser_id;

				/*if ($oldUserData->btc_address != null || $oldUserData->btc_address != '') {
					if ($request->btc != $oldUserData->btc_address) {
						$arrData['btc_address'] = trim($oldUserData->btc_address);
					}
				}

				if ($oldUserData->trn_address != null || $oldUserData->trn_address != '') {
					if ($request->trn_address != $oldUserData->trn_address) {
						$arrData['trn_address'] = trim($oldUserData->trn_address);
					}
				}

				if ($oldUserData->ethereum != null || $oldUserData->ethereum != '') {
					if ($request->ethereum != $oldUserData->ethereum) {
						$arrData['ethereum'] = trim($oldUserData->ethereum);
					}
				}

				if ($oldUserData->bnb_address != null || $oldUserData->bnb_address != '') {
					if ($request->bnb_address != $oldUserData->bnb_address) {
						$arrData['bnb_address'] = trim($oldUserData->bnb_address);
					}
				}*/

                // unset($oldUserData->blockby_cron);

				//save old data
				$saveOldData = DB::table('tbl_users_change_data')->insert((array) $oldUserData);


				$updateData = User::where('id', $getuser_id)->update($arrData);
			}
			//-------------------------------------------------
			if (!empty($updateData)) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'User data updated successfully';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			} else {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Already updated with same data';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
		} catch (Exception $e) {
			//dd($e);
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

	public function getIpAddress(Request $request) {
		$ip = $request->ip();
		//dd($ip);
		if (!empty($ip)) {
			$arrStatus = Response::HTTP_OK;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Ip Address found';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $ip);
		} else {
			$arrStatus = Response::HTTP_NOT_FOUND;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Ip Address not found Otp';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

	public function checkAppVersion(Request $request) {

		$arrInput = $request->all();
		$arrRules = ['version_code' => 'required', 'device_type' => 'required'];
		$validator = Validator::make($arrInput, $arrRules);
		if ($validator->fails()) {
			return setValidationErrorMessage($validator);
		}
		$version_code = $request->version_code;
		$device_type = $request->device_type;
		//DB::enableQueryLog();
		$app = AppVersion::where([['version_code', '>', $version_code], ['device_type', $device_type]])->first();
		//dd(DB::getQueryLog());
		if (empty($app)) {
			$intCode = Response::HTTP_NOT_FOUND;
			$strMessage = "App already updated successfully";
			$strStatus = Response::$statusTexts[$intCode];
			return sendResponse($intCode, $strStatus, $strMessage, array());
		}
		$projectSetting = ProjectSettings::first();
		$latestApp = array();
		$latestApp['title'] = "Update Is Available";
		$latestApp['app_link'] = $projectSetting->app_link;
		$latestApp['version_name'] = $app['version_name'];
		$latestApp['version_code'] = $app['version_code'];
		$latestApp['version_desc'] = $app['version_desc'];
		$latestApp['update_type'] = $app['update_type'];
		if ($latestApp['update_type'] == 'F') {
			$intCode = Response::HTTP_OK;
			$strMessage = "Update Is Available";
			$strStatus = Response::$statusTexts[$intCode];
			return sendResponse($intCode, $strStatus, $strMessage, $latestApp);
		}

		$appupdt = AppVersionLog::where([['version_code', '>', $version_code], ['device_type', $device_type], ['update_type', "F"]])->count('id');
		if ($appupdt > 0) {
			$latestApp['update_type'] = "F";
		}
		$intCode = Response::HTTP_NOT_FOUND;
		$strMessage = "";
		$strStatus = Response::$statusTexts[$intCode];
		return sendResponse($intCode, $strStatus, $strMessage, $latestApp);
	}

	/**
	 * check user excited or not by passing parameter
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getAmount(Request $request)
	{
		$now = \Carbon\Carbon::now()->toDateString();
	
		$userId = Auth::User()->id;
		 $allRanks = Rank::get();
		 
		//  $dataarray = array();
		//  foreach ($allRanks as $value) {
		//  $usersleft = TodayDetails::join('tbl_users as tu','tu.id','=','tbl_today_details.from_user_id')
		// 			->where('tbl_today_details.to_user_id','=value',$userId)
		// 			->where('tbl_today_details.position', '=', 1)
		// 			->where('tu.rank', '=',$value->rank)
		// 			->count();
		// 			//$coutdata = $data->where('rank', '=', $value->rank)->count(); 
		// 			$dataarray[$value->rank] = $usersleft;
		//  }
		// dd($usersleft);	
		foreach ($allRanks as $value) {
			$data = TodayDetails::join('tbl_super_matching as tu','tu.user_id','=','tbl_today_details.from_user_id')
						->where('tbl_today_details.to_user_id','=',$userId)
						->where('tbl_today_details.position', '=', 2)
						->select('tbl_today_details.from_user_id','tu.rank')->get();
					 
					$coutdata = $data->where('rank', '=', $value->rank)->count(); 
					$dataarray[$value->rank] = $coutdata;
					
			}
			 
		foreach ($allRanks as $value) {
			$dataleft = TodayDetails::join('tbl_super_matching as tu','tu.user_id','=','tbl_today_details.from_user_id')
					->where('tbl_today_details.to_user_id','=',$userId)
					->where('tbl_today_details.position', '=', 1)
					->select('tbl_today_details.from_user_id','tu.rank')->get();
				
				$coutdataleft = $dataleft->where('rank', '=', $value->rank)->count(); 	
				$dataarrayleft[$value->rank] = $coutdataleft;
				
		}
		$usersleft = TodayDetails::join('tbl_users as tu','tu.id','=','tbl_today_details.from_user_id')
					->where('tbl_today_details.to_user_id','=',$userId)
					->where('tbl_today_details.position', '=', 1)
					->where('rank', '!=', null)
					->count();

		$usersright = TodayDetails::join('tbl_users as tu','tu.id','=','tbl_today_details.from_user_id')
					->where('tbl_today_details.to_user_id','=',$userId)
					->where('tbl_today_details.position', '=', 2)
					->where('rank', '!=', null)
					->count();


		$todayright = TodayDetails::join('tbl_users as tu','tu.id','=','tbl_today_details.from_user_id')
					->where('tbl_today_details.to_user_id','=',$userId)
					->where('tbl_today_details.position', '=', 2)
					->where('rank', '!=', null)
					->where('tbl_today_details.entry_time','like','%'.$now.'%')
					->count();
					

		$todayleft = TodayDetails::join('tbl_users as tu','tu.id','=','tbl_today_details.from_user_id')
					->where('tbl_today_details.to_user_id','=',$userId)
					->where('tbl_today_details.position', '=', 1)
					->where('rank', '!=', null)
					->where('tbl_today_details.entry_time','like','%'.$now.'%')
					->count();
 
		$arrData['usersleft'] = $dataarrayleft;
		$arrData['usersright'] = $dataarray;
		$arrData['usersrightcounts'] = $usersright;
		$arrData['usersleftcounts'] = $usersleft;
		$arrData['todayright'] = $todayright;
		$arrData['todayleft'] = $todayleft;
		return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);		
	}
	public function supermatchingbonusdata(Request $request)
	{
		// $now = \Carbon\Carbon::now()->toDateString();
		$day = \Carbon\Carbon::now()->format('D');
		$userId = Auth::User()->id; 
	 
		$user = User::join('tbl_super_matching', 'tbl_super_matching.user_id', '=', 'tbl_users.id')
		->select('tbl_users.id','tbl_users.mobile','tbl_users.country','tbl_users.user_id','tbl_users.email','tbl_super_matching.pin','tbl_super_matching.rank','tbl_super_matching.entry_time')
		->where([['tbl_users.status', '=', 'Active'],['tbl_users.type', '=', ''],['tbl_users.id','=',$userId]])
		->where('tbl_super_matching.rank','Ace') 
		->first();
		
		$today = \Carbon\Carbon::now();
        $today_datetime = $today->toDateTimeString();
        $today_datetime2 = $today->toDateString();

		$id = $user['id'];
		$pin = $user['pin'];
		$entry_time = $user['entry_time'];
		$user_id = $user['user_id'];
		$rank = $user['rank']; 
		$lastDateExist=SupperMatchingIncome::select('entry_time','rank')->where([['id','=',$id]])->orderBy('entry_time','desc')->first(); 
		 
		$Dailydata = array(); 
		if(!empty($lastDateExist)){
		   // A
			 $entry_time=$lastDateExist->entry_time;
			
		   $nextEntrydate=date('Y-m-d', strtotime($entry_time. ' + 7 days'));
			
		   if(strtotime($nextEntrydate)<= strtotime($today))   
		  {
		   $packageExist1=Rank::where([['rank','=',$lastDateExist->rank]])->limit(1)->first();
				 $bonus_percentage = $packageExist1->bonus_percentage;
				 $entry_in_supermatching = supermatching::select('rank','pin','entry_time')
				   ->where([['user_id','=',$id],['rank','=',$lastDateExist->rank]])
				   ->first(); 
		   $pin = $entry_in_supermatching->pin;
		   $qualify_date_time = $entry_in_supermatching->entry_time;
		   $rank = $lastDateExist->rank;
		   $entry_in_supermatching1 = supermatching::select('rank','pin','entry_time')
				   ->where([['user_id','=',$id],['entry_time','>',$qualify_date_time]])
				   ->get(); 
			 

				  if(count($entry_in_supermatching1) > 0){
		   foreach ($entry_in_supermatching1 as $key => $value) {
	   if(date('Y-m-d', strtotime($entry_in_supermatching1[$key]->entry_time. ' + 15 days'))  <= $nextEntrydate){

		   $pin = $entry_in_supermatching1[$key]->pin;
		   $rank = $entry_in_supermatching1[$key]->rank;

		   $packageExist1=Rank::where([['rank','=',$rank]])->limit(1)->first();
				 $bonus_percentage = $packageExist1->bonus_percentage;

	   }
		
		
					   }
				  }
				        
						 $Dailydata['entry_time'] = $nextEntrydate;
						 $Dailydata['rank'] = $rank;

				  		 
		   }
		   else
		   {
			$Dailydata['entry_time'] = $nextEntrydate;
			$Dailydata['rank'] = $lastDateExist->rank;
		   }

		  }else
		  {
		   // B
		   /*  $entry_time=$entry_time;*/

			 $entry_time=date('Y-m-d', strtotime($entry_time));

			$last_entry_in_supermatching = supermatching::select('rank','pin')
				   ->where([['user_id','=',$id]])
				   ->where([[DB::raw("(Date(entry_time))"),'=',$entry_time]])
				   ->orderBy('entry_time','desc')
				   ->orderBy('id','desc')
				   ->first(); 
/*dd($last_entry_in_supermatching->rank);*/
		   $nextEntrydate=date('Y-m-d', strtotime($entry_time. ' + 7 days'));
		//	dd($last_entry_in_supermatching);
		   if(strtotime($nextEntrydate)<= strtotime($today))   
		  {
			$packageExist=Rank::where([['rank','=',$last_entry_in_supermatching->rank]])->limit(1)->first();
				 $supermatching = $packageExist->bonus_percentage;
				$Dailydata['entry_time'] = $nextEntrydate;
				$Dailydata['rank'] = $last_entry_in_supermatching->rank; 
						 

		  }
		  else
		  {
		   $Dailydata['entry_time'] = $nextEntrydate;
		   $Dailydata['rank'] = $last_entry_in_supermatching->rank;
		  }
	   }
		 
		return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $Dailydata);		
	}
	public function checkUserExistCrossLeg(Request $request) {

		$arrInput = $request->all();
		//validate the info, create rules for the inputs
		$rules = array(
			'user_id' => 'required',	
		);
		$validator = Validator::make($arrInput, $rules);
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {

			$checkUserExist = User::select('tbl_users.id','tbl_users.user_id','tbl_users.fullname','tbl_users.remember_token')
				->join('tbl_today_details as ttd', 'ttd.from_user_id', '=', 'tbl_users.id')
				->where(['tbl_users.user_id' => $arrInput['user_id'], 'ttd.to_user_id' => Auth::user()->id])
				->first();

			if (!empty($checkUserExist)) {
				$arrObject['id'] = $checkUserExist->id;
				$arrObject['user_id'] = $checkUserExist->user_id;
				$arrObject['fullname'] = $checkUserExist->fullname;
				$arrObject['remember_token'] = $checkUserExist->remember_token;

				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'User available';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $arrObject);
			} else {
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'User not available';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
		}
	}

	/**
	 * [Upload photos description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function uploadPhotos(Request $request) {

		return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $arrMessage, '');
              
		$rules = array(
			'photo' => 'required',
			'name' => 'required',
		);
		$messages = array(
			'photo.required' => 'Please select photo.',
			'name.required' => 'Please enter name.',
		);

		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		}
		$id = Auth::user()->id;
		if (!empty($request->photo)) {
			$imageName = time() . '.' . $request->photo->getClientOriginalExtension();
		}
		if ($request->name == 'photo') {
			/*$request->photo->move(public_path('uploads/photo'), $imageName);*/
			$path = public_path('uploads/photo/' . $imageName);
			$updateData['photo'] = $imageName;
			$updateData['user_id'] = $id;
			$updateData['photov'] = 'Unverified';
			$updateData['phdate'] = \Carbon\Carbon::now();
		} else if ($request->name == 'pan') {
			/*$request->photo->move(public_path('uploads/pancard'), $imageName);*/
			$path = public_path('uploads/pancard/' . $imageName);
			$updateData['pancard'] = $imageName;
			$updateData['pancard_v'] = 'Unverified';
		} else if ($request->name == 'address') {
			/*$request->photo->move(public_path('uploads/addressproof'), $imageName);*/
			$path = public_path('uploads/addressproof/' . $imageName);
			$updateData['address'] = $imageName;
			$updateData['address_v'] = 'Unverified';
		}
		$checkexist = KYC::where('user_id', $id)->first();
		if (!empty($checkexist)) {
			$updateOtpSta = KYC::where('user_id', $id)->update($updateData); 
		} else {
			$updateOtpSta = KYC::create($updateData);
		}

		// dd($updateOtpSta);
		if (!empty($updateOtpSta)) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Image uploaded successfully!', '');
		} else {

			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Photo not uploaded.', '');
		}
	}

	public function getProfileImg() {

		$url = url('/uploads/photo/');
		$userid = Auth::user()->id;

		$img = KYC::selectRaw('CONCAT("' . $url . '","/",photo) as attachment')->where('user_id', $userid)->first();
		if (!empty($img)) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Image found successfully!', $img);
		} else {

			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Photo not found.', '');
		}
	}

	public function getUserId(Request $request) {
		$uid = $request->uid;
		$userid = User::where('unique_user_id', $uid)->pluck('user_id')->first();
		if (!empty($userid)) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'User found successfully!', $userid);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid user', '');
		}
	}

	public function checkDownline(Request $request) {

		$userId = Auth::User()->id;
		$settings = Config::get('constants.settings');
		$rules = array(
			'user_id' => 'required',
		);

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			$message = $validator->errors();
			$err = '';
			foreach ($message->all() as $error) {
				$err = $err . " " . $error;
			}
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, 0);
		}

		$from_user_id = User::where('user_id', $request->user_id)->pluck('id')->first();
		if($request->user_id == Auth::User()->user_id){
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Not a Downline Member', 0);
		}
		// dd($from_user_id,$userId);
		if ($from_user_id != $userId) {
			$todaydetailsexist = TodayDetails::where('to_user_id', $userId)->where('from_user_id', $from_user_id)->get();
			if (count($todaydetailsexist) > 0) {
				// dd($from_user_id);
				$dashboarddata = Dashboard::where('id', $from_user_id)->first();
				// dd($dashboarddata);
				if (!empty($dashboarddata)) {
					return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], ' paid downline user', 1);
				}
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], ' Downline user', 2);
				/*    $ref_user_tot_bv = 0;
					               $ref_user_tot_bv = User::join('tbl_dashboard as td','td.id','=','tbl_users.ref_user_id')->where('tbl_users.user_id',$request->user_id)->pluck('td.total_bv')->first();

					               //dd($settings['min_tot_bv']);
					              if($ref_user_tot_bv >= $settings['min_tot_bv']){
					                  return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Valid user','');
					              }else{
					                  return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Entered user sponser id not green','');
				*/
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Not a Downline Member', 0);
			}
		} else {
			$dashboarddata = Dashboard::where('id', $from_user_id)->first();
			if (!empty($dashboarddata)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], ' paid downline user', 1);
			}
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], ' Downline user', 2);
			/* $ref_user_tot_bv = 0;
				               $ref_user_tot_bv = User::join('tbl_dashboard as td','td.id','=','tbl_users.ref_user_id')->where('tbl_users.user_id',$request->user_id)->pluck('td.total_bv')->first();
				             // $settings   = Config::get('constants.settings');
				            // dd($settings['min_tot_bv']);
				              if($ref_user_tot_bv >= $settings['min_tot_bv']){
				                  return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Valid user','');
				              }else{
				                  return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Entered user sponser id not green','');
			*/
			// return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], ' Downline user','');
		}
	}
	public function getFranchiseUsers(Request $request) {
		$user = Auth::user();

		//dd($request->country);
		if (!empty($user)) {
			$users_list = User::select('id', 'user_id', 'fullname')
			->where('is_franchise', '1')
			->where('income_per','=','3')
            ->where('country','=',$request->country)
            ->where('status', '=', 'Active')
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

		//dd($request->country);
		if (!empty($user)) {
			$users_list = User::select('id', 'user_id', 'fullname')
			->where('is_franchise', '1')
            ->where('income_per','=','2')
            ->where('status', '=', 'Active')
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
     * Function to send opt for registration
     * 
     * @param $arrInput : Array of input
     * 
     */
	 public function sendRegisterOtp($arrInput)
	 {
	 	$arrOutputData = [];
	 	$intCode = Response::HTTP_INTERNAL_SERVER_ERROR;
	 	$strMessage = trans('user.error');
	 	$strStatus = Response::$statusTexts[$intCode];
	 	DB::beginTransaction();
	 	try {

	 		$mobile = trim($arrInput['mobile_number']);
	 		/* $mobile = str_replace("/^\+(?:48|22)-/", "", $mobile);*/
//$mobile = preg_replace("/^\+\d+-/", "", ($mobile));
//dd($mobile);

	 		$user = new User;
	 		$user->mobile = $mobile;

	 		$random = rand(100000, 999999);
	 		$insertotp = array();
	 		/* $insertotp['id'] = $arrInput['id'];*/
/* $mobile = trim($arrInput['whatsapp_no']);
$mobile = str_replace(" ", "", $mobile);*/
			date_default_timezone_set("Asia/Kolkata");
			$mytime = \Carbon\Carbon::now();
			$current_time = $mytime->toDateTimeString();
			$insertotp['entry_time'] = $current_time;
			$insertotp['id'] = $arrInput['user_id'];
			$insertotp['mobile_no'] = $mobile;
			$insertotp['ip_address'] = trim($_SERVER['REMOTE_ADDR']);
			$insertotp['otp'] = md5($random);
			$insertotp['otp_status'] = 0;
			$insertotp['type'] = "mobile";
			$msg = $random . ' is your verification code';
			sendSMS($mobile, $msg);
			$sendotp = Otp::insert($insertotp);
			$intCode = Response::HTTP_OK;
			$strMessage = trans('User OTP Sent');

		} catch (PDOException $e) {
			dd($e);
			DB::rollBack();
			return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
		} catch (Exception $e) {
			dd($e);
			DB::rollBack();
			return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
		}
		DB::commit();
		$strStatus = Response::$statusTexts[$intCode];
		return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
	}
	 public function sendRegistrationOtp(Request $request)
    {
        $arrOutputData  = [];
        try {
			$arrInput  = $request->all();
            $arrRules  = ['email' => 'required'];
            $validator = Validator::make($arrInput, $arrRules);
            /*dd($arrRules  = ['whatsapp_no']);*/
            if ($validator->fails()) {
               
                return setValidationErrorMessage($validator);
            }

            /*    $getMobileRegCount =  User::where('mobile',$request->whatsapp_no)->count();
        if($getMobileRegCount > 3){
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Already registered 3 times with this mobile No.', '');
        } */
            /*$checkMobileNoExist =  UserModel::where('mobile',$request->whatsapp_no)->first();
           if(!empty($checkMobileNoExist)){
                $strMessage         = " Registration with this mobile no. already exist ";
                $intCode            = Response::HTTP_NOT_FOUND;
                $strStatus          = Response::$statusTexts[$intCode];
                return sendResponse($intCode, $strStatus, $strMessage,[]);
           }*/
           
            //return $this->sendRegisterOtp($arrInput);
		   //dd($request->user_id);
		   
           return $this->sendotponmail($request->user_id,$request->email);
        } catch (Exception $e) {
        	// dd($e);
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('admin.defaultexceptionmessage');
        }
        $strStatus  = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
    }
    public function sendotponmail($users, $email, $type=null) {
        /*dd($email);*/
        $checotpstatus = Otp::where('id', '=', $users)->orderBy('entry_time', 'desc')->first();
        $updateOtpSTatus['otp_status'] = 1;
        Otp::where([['id', '=', $users->id],])->update($updateOtpSTatus);
        //dd($checotpstatus);
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

		
        $pagename = "emails.registrationotp";
        $subject = "OTP sent successfully";
        $random = rand(100000, 999999);
        $data = array('pagename' => $pagename, 'otp' => $random, 'username' => $users);

        $mail = sendMail($data, $email, $subject);

		$insertotp = array();
		date_default_timezone_set("Asia/Kolkata");
		$mytime_new = \Carbon\Carbon::now();
		$current_time_new = $mytime_new->toDateTimeString();
		$insertotp['entry_time'] = $current_time_new;
        $insertotp['id'] = $users;
        $insertotp['ip_address'] = trim($_SERVER['REMOTE_ADDR']);
        $insertotp['otp'] = md5($random);
        $insertotp['otp_status'] = 0;
        $insertotp['type'] = 'email';
		 
        $sendotp = Otp::create($insertotp);
		
        $arrData = array();
        // $arrData['id']   = $users->id;
       // $arrData['remember_token'] = $users->remember_token;

        $arrData['mailverification'] = 'TRUE';
        $arrData['google2faauth'] = 'FALSE';
        $arrData['mailotp'] = 'TRUE';
        $arrData['mobileverification'] = 'TRUE';
        $arrData['otpmode'] = 'FALSE';
       /* dd($arrData);*/
        //$mask_mobile = maskmobilenumber($users->mobile);
        $mask_email = maskEmail($email);
        $arrData['email'] = $mask_email;
        //$arrData['mobile'] = $mask_mobile;

       /* if($type == null)
        {
            return $random;
        }*/

        return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'OTP sent successfully to your email Id','');

        return $sendotp;

        //}  // end of users
    }

      /**
     * Function to send opt for registration
     * 
     * @param $arrInput : Array of input
     * 
     */
  
   public function verifyRegisterOtp($arrInput) {
  
        $strMessage = trans('user.error');
        $arrOutputData = [];
        try {
           // $arrInput   = ['mobile_number'];
           /* $otp        = $arrInput['otp'];*/
          
            $checotpstatus = Otp::where('id', $arrInput['user_id'])->where('otp', md5($arrInput['otp']))->orderBy('entry_time', 'desc')->first();
          	// dd($checotpstatus);
            // check otp status 1 - already used otp
            if (empty($checotpstatus)) {
                $strMessage = 'Invalid otp';
                $intCode = Response::HTTP_BAD_REQUEST;
                $strStatus = Response::$statusTexts[$intCode];
                return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
            }

            if (!empty($checotpstatus)) {

				// date_default_timezone_set("Asia/Kolkata");
				date_default_timezone_set("Asia/Tokyo");
                $entry_time = $checotpstatus->entry_time;
				// $start_time = strtotime("".$checotpstatus->entry_time."");
				$mytime = \Carbon\Carbon::now();
				$current_time = $mytime->toDateTimeString();
				$end_time = strtotime("".$current_time."");
				// echo $entry_time;
				// echo date_diff("2021-11-09 12:00:00","2021-11-09 12:30:00");
				// dd($current_time);
				// $start  = new Carbon(''.$checotpstatus->entry_time.'');
				// $end    = new Carbon(''.$current_time.'');
				// $total = $end->diffInSeconds($start);
				// if($total > 120){
				// 	$intCode        = Response::HTTP_BAD_REQUEST;
		        //     $strStatus      = Response::$statusTexts[$intCode];
		        //     $strMessage     = "Otp is expired. Please resend"; 
		        //     return sendResponse($intCode, $strStatus, $strMessage,1);
				// }

	            if ($entry_time!=''  && $checotpstatus->otp_status!='1') {
					if(md5($arrInput['otp']) == $checotpstatus['otp'])
					{
						Otp::where('otp_id', $checotpstatus->otp_id)->update(['otp_status' => '1']);
						$intCode        = Response::HTTP_OK;
						$strStatus      = Response::$statusTexts[$intCode];
						$strMessage     = "OTP Verified."; 
						return sendResponse($intCode, $strStatus, $strMessage,1);
					}
					else
					{
						$strMessage = 'Invalid otp';
						$intCode = Response::HTTP_BAD_REQUEST;
						$strStatus = Response::$statusTexts[$intCode];
						return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
					}
	            } 
	            else
	            {
	                $updateData = array();
	                $updateData['otp_status'] = '1';
					$updateOtpSta = Otp::where([['otp_id', $checotpstatus->otp_id],['otp_status','0']])->update($updateData);
					$intCode        = Response::HTTP_BAD_REQUEST;
		            $strStatus      = Response::$statusTexts[$intCode];
		            $strMessage     = "Otp is expired. Please resend"; 
		            return sendResponse($intCode, $strStatus, $strMessage,1);
	                //return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Otp is expired. Please resend', '');
	            }
	        }
            // make otp verify
            //$this->secureLogindata($user->user_id, $user->password, 'Login successfully');
            $updateOtpSta = Otp::where('otp_id', /*Auth::user()->id*/1)->update([
                'otp_status' => 1, //1 -verify otp
                'out_time'  => now(),
            ]);

        
        } catch (Exception $e) {
        	dd($e);
            $intCode    = Response::HTTP_BAD_REQUEST;
            $strStatus  = Response::$statusTexts[$intCode];
            $strMessage = 'Something went wrong. Please try later.';
            return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
        }
    }

      /**
     * Function to verify the registration Otp
     * 
     * @param $request : HTTP Request Object
     * 
     */
    public function verifyRegistrationOtp(Request $request)
    {
        $arrOutputData  = [];
        try {
            $arrInput  = $request->all();
            $arrRules  = ['otp' => 'required|min:6|max:6'];
            $validator = Validator::make($arrInput, $arrRules);
            if ($validator->fails()) {
                return setValidationErrorMessage($validator);
            }
           return $this->verifyRegisterOtp($arrInput);
        } catch (Exception $e) {
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('admin.defaultexceptionmessage');
        }
        $strStatus  = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
    }
    public function verifyUserOtp(Request $request)
    {
        $arrOutputData  = [];
        try {
        	$id = Auth::User()->id;
            $arrInput  = $request->all();
            $arrInput['user_id'] = $id;
            $arrRules  = ['otp' => 'required|min:6|max:6'];
            $validator = Validator::make($arrInput, $arrRules);
            if ($validator->fails()) {
                return setValidationErrorMessage($validator);
            }
           return $this->verifyRegisterOtp($arrInput);
        } catch (Exception $e) {
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('admin.defaultexceptionmessage');
        }
        $strStatus  = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
    }

}
