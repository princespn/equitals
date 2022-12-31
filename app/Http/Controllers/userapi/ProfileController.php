<?php

namespace App\Http\Controllers\userapi;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Config;
use Exception;
use Illuminate\Http\Request;
use App\Models\collectusd;
use App\Models\Topup;
use App\Models\Currency;
use App\Models\UserWithdrwalSetting;
use Illuminate\Http\Response as Response;
use DB;

class ProfileController extends Controller {

	/**
	 * Get User profile data
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getusddata(Request $request){
		$id = Auth::user()->id;
		$usddata = Topup::select('entry_time')->where('id' , $id)->first();
		$entry_time = $usddata['entry_time'];
		$to = \Carbon\Carbon::now();
		// $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $entry_time);
		$diff = $to->diffInMinutes($entry_time);
		$arrData['minutes']= $diff;
		$dexd = $diff *0.001;
		$dexd_half = $diff *0.0005;

		if(strtotime($entry_time) >= strtotime("2021-08-01 00:00:00"))
		{
			$arrData['usddata'] = $diff*0.00025;
		}else if(strtotime($entry_time) >= strtotime("2021-06-11 00:00:00"))
		{
			$arrData['usddata'] = $dexd_half;
		}
		else{
			$arrData['usddata'] =$dexd;
		}

		$arrStatus = Response::HTTP_OK;
		$arrCode = Response::$statusTexts[$arrStatus];
		$arrMessage = ' data found successfully';
		return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);

	}

	
	public function getprofileinfo(Request $request) {
		try {

			$id = Auth::user()->id;
			//--------------------------------check id exist-----------------------------
			$users = User::leftjoin('tbl_country_new', 'tbl_country_new.iso_code', '=', 'tbl_users.country')->where([['id', '=', $id], ['status', '=', 'Active']])->first();
			//dd(111);
			$totalDirect = User::select('id')->where('ref_user_id', $id)->count();
			if (empty($users)) {
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Invalid user';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			} else {

				$ref_user_id = $users->ref_user_id;
				if ($users->ref_user_id != '') {

					$getsponser = User::where([['id', '=', $users->ref_user_id], ['status', '=', 'Active']])->first();
					 
					//$getsponser = User::where([['id','=',$users->ref_user_id]])->first();
					if (!empty($getsponser)) {
						$arrData['sponser'] = $getsponser->user_id;
						$arrData['sponser_fullname'] = $getsponser->fullname;
						$arrData['sponser_country'] = $getsponser->country;
					} else {

						$arrData['sponser'] = '';
						$arrData['sponser_fullname'] = '';
						$arrData['sponser_country'] = '';
					}
				}
				
				$arrData['user_id'] = $users->user_id;
				$arrData['totalDirect'] = $totalDirect;
				$arrData['fullname'] = $users->fullname;
				$arrData['entry_time'] = $users->entry_time;
				$arrData['email'] = $users->email;
				$arrData['btc_address'] = $users->btc_address;
				$arrData['usdt_taddress'] = $users->usdt_taddress;
				$arrData['doge_address'] = $users->doge_address;
				$arrData['ltc_address'] = $users->ltc_address;
				$arrData['ethereum'] = $users->ethereum;
				$arrData['trn_address'] = $users->trn_address;
				$arrData['solona_address'] = $users->solona_address;
				$arrData['ripple_address'] = $users->ripple_address;
				$arrData['country'] = $users->iso_code;
				$arrData['country'] = $users->country;
				$arrData['mobile'] = $users->mobile;
				$arrData['address'] = $users->address;
				$arrData['ref_user_id'] = $ref_user_id;
				$arrData['account_no'] = $users->account_no;
				$arrData['holder_name'] = $users->holder_name;
				$arrData['pan_no'] = $users->pan_no;
				$arrData['bank_name'] = $users->bank_name;
				$arrData['ifsc_code'] = $users->ifsc_code;
				$arrData['branch_name'] = $users->branch_name;
				$arrData['city'] = $users->city;
				$arrData['code'] = $users->code;
				$arrData['is_franchise'] = $users->is_franchise;
				$arrData['facebook_link'] = $users->facebook_link;
				$arrData['twitter_link'] = $users->twitter_link;
				$arrData['linkedin_link'] = $users->linkedin_link;
				$arrData['instagram_link'] = $users->instagram_link;
				$arrData['perfect_money_address'] = $users->perfect_money_address;
				$arrData['paypal_address'] = $users->paypal_address;
				$arrData['user_profile'] = $users->user_profile;

			    
			   // $arrData['ethereum'] = $users->ethereum;
			  //  $arrData['bnb_address'] = $users->bnb_address;

			    // $arrData['trn_address'] = $users->trn_address;
			    // $arrData['bnb_address'] = $users->bnb_address;


			//    $withdrawal_currency = Currency::where('tbl_currency.withdrwal_status','1')->get();
			//    foreach ($withdrawal_currency as $key) {
			//    	$curr_address = UserWithdrwalSetting::where([['id',$id], ['currency',$key['currency']],['status',1]])->pluck('currency_address')->first();
			//    	if(!empty($curr_address)){
			//    		$arrData[''.str_replace("-","_",strtolower($key['currency'])).'_address'] = $curr_address;
			//    	}else{
			//    		$arrData[''.str_replace("-","_",strtolower($key['currency'])).'_address'] = "";
			//    	}
			//    	// dd($curr_address);
			//    }
				$google2fa = app('pragmarx.google2fa');

				$secret = $google2fa->generateSecretKey();

				/*$QR_Image = $google2fa->getQRCodeInline(
					config('app.name'),
					$users->user_id,
					$secret
				);*/
				// dd($arrData);
				/*$arrData['QR_Image'] = $QR_Image;*/
				$arrData['secret'] = $secret;
				$arrData['google2fa_status'] = $users->google2fa_status;
				$arrData['image'] = $users->image;
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'User profile data found successfully';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
			}
		} catch (Exception $e) {
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

		/**
	 * Get User profile data
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getuserrank(Request $request){
		$id = Auth::user()->id;
		$userrank = User::select('rank')->where('id', $id)->first();

		if(!empty($userrank->rank))
		{
			$arrData['rank'] =$userrank->rank;
		}
		else{
			$arrData['rank'] = 0;
		}
		$arrStatus = Response::HTTP_OK;
		$arrCode = Response::$statusTexts[$arrStatus];
		$arrMessage = ' data found successfully';
		return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);

	}
	public function checkUserCountryAvoided(){	
		$usercountry = Auth::User()->country;
		$country =DB::table('tbl_country_new')->where([['iso_code','=',$usercountry],['avoid_con',1]])->first();
        if($country!= null){

           $arrStatus   = Response::HTTP_OK;
           $arrCode     = Response::$statusTexts[$arrStatus];
           $arrMessage  = '123';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }else{
        	$arrStatus   = Response::HTTP_NOT_FOUND;
        	$arrCode     = Response::$statusTexts[$arrStatus];
        	$arrMessage  = '';
           	return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
	}
}
