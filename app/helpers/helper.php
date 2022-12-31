<?php

use App\Models\Country;
use App\Models\Names;
use App\Models\WhiteListIpAddress;
use App\Models\UserApiHitDetails;
use App\Models\Otp as OtpModel;
use App\Models\ProjectSetting as ProjectSettingModel;
use App\User;
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Enum\CurrencyCode;
use Coinbase\Wallet\Resource\Address;
use Coinbase\Wallet\Resource\Transaction;
use Coinbase\Wallet\Value\Money;
use Illuminate\Http\Response;
use Symfony\Component\CssSelector\Parser\Handler\WhitespaceHandler;

/**
 *type array convert into array messages to string messages
 *
 */
function messageCreator($messages) {
	$err = '';
	$msgCount = count($messages->all());
	foreach ($messages->all() as $error) {
		if ($msgCount > 1) {
			$err = $err . ' ' . $error . ',';
		} else {
			$err = $error;
		}
	}
	return $err;
}

function getIpAddrss(){

	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    	$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function whiteListIpAddress($type=0,$uid,$seconds=30){

	$ip_add = getIpAddrss();
	$today = \Carbon\Carbon::now()->format("Y-m-d H:i:s");
	$arr = array('uid' => $uid,'type' => $type,'ip_add' => $ip_add,'entry_time' => $today);
	$res = UserApiHitDetails::insert($arr);

	$hits_array = array();
	$new_date_time = \Carbon\Carbon::now()->subSeconds($seconds)->format('Y-m-d H:i:s');
	$get_user_hits = UserApiHitDetails::select('id','uid', 'ip_add','entry_time')->where([['type',$type],['uid',$uid],['status','Active'],['ip_add',$ip_add]])->limit(5)->orderBy('id','desc')->get();
	$flag = 1;
	$hr = 1;
	if(!empty($get_user_hits) && count($get_user_hits) >= 5){
		foreach($get_user_hits as $key => $v){
			if($today <= $v['entry_time'] || $v['entry_time'] >= $new_date_time){
				if($flag >= 5){
					$insert_array = array();
					$insert_array['uid'] = $v['uid'];
					$insert_array['ip_add'] = $v['ip_add'];
					$remark = 'working fine';
					if($type == 1){
						//type 1 is for topup
						$insert_array['topup_status'] = 1;
						$insert_array['topup_expire'] = \Carbon\Carbon::now()->addHour($hr);
					}else if($type == 2){
						//type 2 is for withdraw
						$insert_array['withdraw_status'] = 1;
						$insert_array['withdraw_expire'] = \Carbon\Carbon::now()->addHour($hr);
					}else if($type == 3){
						//type 3 is for transfer
						$insert_array['transfer_status'] = 1;
						$insert_array['transfer_expire'] = \Carbon\Carbon::now()->addHour($hr);
					}else{
						$remark = 'type invalid';
					}
					$insert_array['remark'] = $remark;
					$insert_array['entry_time'] = \Carbon\Carbon::now();
					$check_user_hits = WhiteListIpAddress::select('id')->where([['uid',$v['uid']],['ip_add',$v['ip_add']]])->first();
					if(!empty($check_user_hits)){
						$update = array();
						if($type == 1){
							//type 1 is for topup
							$update['topup_status'] = 1;
							$update['topup_expire'] = \Carbon\Carbon::now()->addHour($hr);
						}else if($type == 2){
							//type 2 is for withdraw
							$update['withdraw_status'] = 1;
							$update['withdraw_expire'] = \Carbon\Carbon::now()->addHour($hr);
						}else if($type == 3){
							//type 3 is for transfer
							$update['transfer_status'] = 1;
							$update['transfer_expire'] = \Carbon\Carbon::now()->addHour($hr);
						}
						$update['ip_add'] = $ip_add;
						$update['remark'] = $remark;
						$result = WhiteListIpAddress::where('id',$check_user_hits->id)->update($update);
						break;
						// if($result){
						// 	// return true;
						// 	break;
						// }else{
						// 	// return false;
						// 	break;
						// }
					}else{
						$new_insert = WhiteListIpAddress::insert($insert_array);
						break;
						// if($new_insert){
						// 	// return true;
						// 	break;
						// }else{
						// 	// return false;
						// 	break;
						// }
					}
					
				}
				$flag = $flag + 1;
			}
		}
		$user_array = implode(',',array_column($get_user_hits->toArray(),'id'));
		$make_array = explode(',',$user_array);
		$get_res = UserApiHitDetails::where([['uid',$uid],['type',$type],['ip_add',$ip_add]])->whereNotIn('id',$make_array)->delete();
		if($get_res){
			return true;
		}else{
			return false;
		}

	}
	return '';

}

function setFlightAipToken(){


     
$curl = curl_init();




$apiUrl= Config::get('constants.settings.flight_api');
$client_id= Config::get('constants.settings.flight_client_id');
$client_secret= Config::get('constants.settings.flight_client_secret');
$grant_type= Config::get('constants.settings.flight_grant_type');

curl_setopt_array($curl, array(
  CURLOPT_URL => $apiUrl."/v1/security/oauth2/token",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "client_id=".$client_id."&client_secret=".$client_secret."&grant_type=".$grant_type,
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/x-www-form-urlencoded",
    "postman-token: 482dc4d7-1a8d-f259-7fb1-bbc31636740b"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
   $res=json_decode($response);


 /* if($res->access_token !=''){

   

  }*/
  ProjectSettingModel::where('id','=',1)->update(array('flight_api_token'=>$res->access_token));
 
  // dd(3,$res->access_token);
}


}

function getIpAddrssNew(){

	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
 return $ip;
}

/**
 * Function to verify the otp
 *
 * @param $otp
 */
function verifyOtp($intputotp) {
	$id = Auth::User()->id;
	$otp = OtpModel::where([
		['id', '=', $id],
		['otp', '=', md5($intputotp)]])->orderBy('otp_id', 'desc')->first();
	if (empty($otp)) {
		$intCode = 400; // bad request
		return $intCode;
	}
	if ($otp->otp_status == '1') {
		$intCode = 404; // already verified
	} else {
		// check otp matched or not
		$updateData = array();
		$updateData['otp_status'] = 1; //1 -verify otp
		$updateData['out_time'] = date('Y-m-d H:i:s');
		$updateOtpSta = OtpModel::where('id', $id)->update($updateData);
		if (!empty($updateOtpSta)) {
			$intCode = 200; //ok
		} else {
			$intCode = 500; // wrong
		}
	}
	return $intCode;
}

/**
 * get time zone by using ip address
 *
 * @return \Illuminate\Http\Response
 */
function getTimeZoneByIP($ip_address = null) {

	/*$url = "https://timezoneapi.io/api/ip/$ip_address";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$getdata = curl_exec($ch);
	$data = json_decode($getdata, true);
	dd($data);
	if ($data['meta']['code'] == '200') {
		//echo "City: " . $data['data']['city'] . "<br>";
		$date = $data['data']['datetime']['date_time'];
		$old_date_timestamp = strtotime($date);*/
		return $new_date = date('Y-m-d H:i:s');

	/*} else {
		return false;
	}*/
}

/*
 *get all columns from table
 */
function getTableColumns($table) {
	return DB::getSchemaBuilder()->getColumnListing(trim($table));
}

/*
 *send json response after each request
 */
function sendresponse($code, $status, $message, $arrData) {

	$output['code'] = $code;
	$output['status'] = $status;
	$output['message'] = $message;
	if (empty($arrData)) {
		$arrData = (object) array();
	}
	$output['data'] = $arrData;
	return response()->json($output);
}

/*
 *Check validation after each request
 */
function checkvalidation($request, $rules, $messsages) {

	$validator = Validator::make($request, $rules);
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
	} else {
		$err = '';
	}
	return $err;
}

/*
 *Send mail
 */
function sendMail($data, $to_mail, $getsubject) {

	$projectSettings = ProjectSettingModel::where('status', 1)->first();
	//dd(1222, $projectSettings->mail_status == 'on');

	if ($projectSettings->mail_status == 'on') {
		// dd($to_mail);
		$succss = false;
		try {
			$displaypage = $data['pagename'];
			// dd($displaypage);
			$succss = Mail::send($displaypage, $data, function ($message) use ($to_mail, $getsubject) {
				$from_mail = Config::get('constants.settings.from_email');
				$to_email = $to_mail;
				$project_name = Config::get('constants.settings.projectname');
				$message->from($from_mail, $project_name);
				$message->to($to_email)->subject($project_name . " | " . $getsubject);
			});
			// dd($succss);
		} catch (\Exception $e) {
			dd($e);
			return $succss;
		}
	}

	// dd($to_mail);

	//dd($succss);
	return true;
}

function sendCoinbase_btc($cmd = '', $req = array()) {

	//dd($req['address']);

	$bitcoin_credential = Config::get('constants.bitcoin_credential');
	$coin_apiKey = $bitcoin_credential['sender_coin_apiKey'];
	$coin_apiSecret = $bitcoin_credential['sender_coin_apiSecret'];
	if (!empty($coin_apiKey) && !empty($coin_apiSecret)) {
		$configuration = Configuration::apiKey($coin_apiKey, $coin_apiSecret);
		$client = Client::create($configuration);
		$account = $client->getPrimaryAccount();
		// $address = new Address();
		// $client->createAccountAddress($account, $address);
		// $client->refreshAccount($account);
		$transaction = Transaction::send([
			'toBitcoinAddress' => $req['address'],
			'amount' => new Money($req[
				'amount'], CurrencyCode::USD),
			'description' => $req['note'],
			//'fee'              => '0.0001' // only required for transactions under BTC0.0001
		]);
		// $transaction->setToBitcoinAddress($address->getAddress());

		$client->createAccountTransaction($account, $transaction);

		$client->refreshAccount($account);

		$transactionId = $transaction->getId();
		$transactionStatus = $transaction->getStatus();
		$transactionHash = $transaction->getNetwork();

		if ($transactionId != "" && !empty($coin_apiKey) && !empty($coin_apiSecret)) {

			$arr = array();
			// $arr['address'] = $address->getAddress();
			$arr['msg'] = 'success';
			$arr['transactionId'] = $transactionId;
			return $arr;
		} else {

			$arr = array();
			//$arr['address'] = '';
			$arr['msg'] = 'failed';
			return $arr;
		}
	} else {

		$arr = array();
		// $arr['address'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
}

/*
 *Send enquiry mail
 */
function sendEnquiryMail($data, $to_mail, $getsubject, $imageName) {

	try {
		$displaypage = $data['pagename'];
		$succss = Mail::send($displaypage, $data, function ($message) use ($to_mail, $getsubject, $imageName) {
			$from_mail = Config::get('constants.settings.from_email');
			$to_email = $to_mail;
			$project_name = Config::get('constants.settings.projectname');
			$message->from($from_mail, $project_name);
			$message->to($to_mail)->subject($project_name . " | " . $getsubject);

			if (!empty($imageName)) {
				$sample = public_path() . '/attachment/' . $imageName;
				$message->attach($sample);
			}
		});
	} catch (\Exception $e) {

		return $succss;
	}
	return $succss;
}

/*
 * Mask mobile numbetr
 */

function maskmobilenumber($number) {

	$masked = substr($number, 0, 2) . str_repeat("*", strlen($number) - 4) . substr($number, -2);
	return $masked;
}

/*
 * Mask email address
 */

function maskEmail($email) {

	$masked = preg_replace('/(?:^|.@).\K|.\.[^@]*$(*SKIP)(*F)|.(?=.*?\.)/', '*', $email);
	return $masked;
}

/*
 * Generate address
 */
function getnew_address($label = null) {

	$bitcoin_credential = Config::get('constants.bitcoin_credential');
	$api_code = $bitcoin_credential['api_code'];
	$guid = $bitcoin_credential['guid'];
	$main_password = $bitcoin_credential['main_password'];
	$url = $bitcoin_credential['url'];
	if (!empty($api_code) && !empty($guid) && !empty($main_password) && !empty($url)) {
		$query = "/merchant/$guid/new_address?password=$main_password&label=$label";
		$url .= $query;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$transaction = curl_exec($ch);
		$transaction = json_decode($transaction, true);
		if (!empty($transaction) && empty($transaction['error']) && !empty($api_code) && !empty($guid) && !empty($main_password) && !empty($url)) {

			$arr = array();
			$arr['address'] = $transaction['address'];
			$arr['msg'] = 'success';
			return $arr;
		} else {

			$arr = array();
			$arr['address'] = '';
			$arr['msg'] = 'failed';
			return $arr;
		}
	} else {

		$arr = array();
		$arr['address'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
}
/*
 * Generate new address using blockchain
 */

function getBlockchain_address() {

	$bitcoin_credential = Config::get('constants.bitcoin_credential');

	$key = $bitcoin_credential['block_key'];
	$xpub = $bitcoin_credential['xpub'];
	$path = Config::get('constants.settings.domainpath');
	$gap_limit = 1000;
	$callback_url = urlencode('' . $path . '/public/api/receive_callback');
	if (!empty($key) && !empty($xpub) && !empty($callback_url) && !empty($path)) {
		$url = "https://api.blockchain.info/v2/receive?xpub=$xpub&callback=$callback_url&key=$key&gap_limit=$gap_limit";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$transaction = curl_exec($ch);
		$transaction = json_decode($transaction, true);

		if (!empty($transaction) && empty($transaction['error']) && !empty($key) && !empty($xpub) && !empty($callback_url) && !empty($path)) {

			$arr = array();
			$arr['address'] = $transaction['address'];
			$arr['msg'] = 'success';
			return $arr;
		} else {

			$arr = array();
			$arr['address'] = '';
			$arr['msg'] = 'failed';
			return $arr;
		}
	} else {

		$arr = array();
		$arr['address'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
}

/*
 * Generate new address using coinbase
 */
/*function getCoinbase_address() {

$bitcoin_credential = Config::get('constants.bitcoin_credential');
$coin_apiKey = $bitcoin_credential['coin_apiKey'];
$coin_apiSecret = $bitcoin_credential['coin_apiSecret'];
if (!empty($coin_apiKey) && !empty($coin_apiSecret)) {
$configuration = Configuration::apiKey($coin_apiKey, $coin_apiSecret);
$client = Client::create($configuration);
$account = $client->getPrimaryAccount();
$address = new Address();
$client->createAccountAddress($account, $address);
$client->refreshAccount($account);
$transaction = Transaction::send();
$transaction->setToBitcoinAddress($address->getAddress());
if (!empty($address->getAddress()) && !empty($coin_apiKey) && !empty($coin_apiSecret)) {

$arr = array();
$arr['address'] = $address->getAddress();
$arr['msg'] = 'success';
return $arr;
} else {

$arr = array();
$arr['address'] = '';
$arr['msg'] = 'failed';
return $arr;
}
} else {

$arr = array();
$arr['address'] = '';
$arr['msg'] = 'failed';
return $arr;
}
 */

/*
 * Generate new address using coinbase
 */
function getCoinbase_address($cmd = '', $req = array()) {

	//dd($req['address']);

	$bitcoin_credential = Config::get('constants.bitcoin_credential');
	$coin_apiKey = $bitcoin_credential['sender_coin_apiKey'];
	$coin_apiSecret = $bitcoin_credential['sender_coin_apiSecret'];
	if (!empty($coin_apiKey) && !empty($coin_apiSecret)) {
		$configuration = Configuration::apiKey($coin_apiKey, $coin_apiSecret);
		$client = Client::create($configuration);
		$account = $client->getPrimaryAccount();
		// $address = new Address();
		// $client->createAccountAddress($account, $address);
		// $client->refreshAccount($account);
		$transaction = Transaction::send([
			'toBitcoinAddress' => $req['address'],
			'amount' => new Money($req[
				'amount'], CurrencyCode::USD),
			'description' => $req['note'],
			//'fee'              => '0.0001' // only required for transactions under BTC0.0001
		]);
		// $transaction->setToBitcoinAddress($address->getAddress());

		$client->createAccountTransaction($account, $transaction);

		$client->refreshAccount($account);

		$transactionId = $transaction->getId();
		$transactionStatus = $transaction->getStatus();
		$transactionHash = $transaction->getNetwork();

		if ($transactionId != "" && !empty($coin_apiKey) && !empty($coin_apiSecret)) {

			$arr = array();
			// $arr['address'] = $address->getAddress();
			$arr['msg'] = 'success';
			$arr['transactionId'] = $transactionId;
			return $arr;
		} else {

			$arr = array();
			//$arr['address'] = '';
			$arr['msg'] = 'failed';
			return $arr;
		}
	} else {

		$arr = array();
		// $arr['address'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
}

/*
 * Generate new address using coinbase
 */

function getCoinbaseCurrency_address($Currency) {

	$bitcoin_credential = Config::get('constants.bitcoin_credential');
	$coin_apiKey = $bitcoin_credential['coin_apiKey'];
	$coin_apiSecret = $bitcoin_credential['coin_apiSecret'];
	if (!empty($coin_apiKey) && !empty($coin_apiSecret)) {

		$configuration = Configuration::apiKey($coin_apiKey, $coin_apiSecret);
		$client = Client::create($configuration);
		$account = $client->getAccounts();

		foreach ($account as $k => $v) {

			$getCurreny[] = $account[$k]->getcurrency();
			$acount_id[$account[$k]->getcurrency()] = $account[$k]->getid();
			if (in_array($Currency, $getCurreny)) {
				$getCurAcntId = $acount_id[$Currency];
			}
		}
		$account1 = $client->getAccount($getCurAcntId);
		$address = new Address();
		$client->createAccountAddress($account1, $address);
		$client->refreshAccount($account1);

		if (!empty($address->getAddress()) && !empty($coin_apiKey) && !empty($coin_apiSecret)) {

			$arr = array();
			$arr['address'] = $address->getAddress();
			$arr['msg'] = 'success';
			return $arr;
		} else {

			$arr = array();
			$arr['address'] = '';
			$arr['msg'] = 'failed';
			return $arr;
		}
	} else {

		$arr = array();
		$arr['address'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
}

// currency address change from profile send mail for withdraw stop 24hrs

function addr_updateWithdraw_stop_mail($curr_type,$token,$user_id,$user_email){
	// mail
	$path       = Config::get('constants.settings.domainpath');
	$pagename = "emails.withdraw_stop_oneday";
	$subject = "Withdraw is stop for next 24 hrs";
	$contant = "Withdraw is stop for next 24 hrs as your payment ".$curr_type." address is updated!";
	$sub_contant = "If not then please ";
	$click_here = "Click Here!";
	$prof_url = $path."/user#/currency-address?token=".$token;
	$data = array('pagename' => $pagename, 'contant' => $contant, 'username' => $user_id, 'sub_contant' => $sub_contant, 'click_here' => $click_here, 'prof_url' => $prof_url);
	$email = $user_email;
	$mail = sendMail($data, $email, $subject);
}

/*
 * Coinbase get transaction hash by api id
 */

function getCoinbaseTransactionHash($Currency, $transactionId = '') {
	$bitcoin_credential = Config::get('constants.bitcoin_credential');
	$coin_apiKey = $bitcoin_credential['sender_coin_apiKey'];
	$coin_apiSecret = $bitcoin_credential['sender_coin_apiSecret'];
	$arr = [];
	try {
		if (!empty($coin_apiKey) && !empty($coin_apiSecret)) {

			$configuration = Configuration::apiKey($coin_apiKey, $coin_apiSecret);
			$client = Client::create($configuration);
			$account = $client->getAccounts();

			foreach ($account as $k => $v) {

				$getCurreny[] = $account[$k]->getcurrency();
				$acount_id[$account[$k]->getcurrency()] = $account[$k]->getid();
				if (in_array($Currency, $getCurreny)) {
					$getCurAcntId = $acount_id[$Currency];
				}
			}
			$account1 = $client->getAccount($getCurAcntId);
			if ($transactionId != '') {
				$transaction = $client->getAccountTransaction($account1, $transactionId);
				$arr['status'] = "Success";
				$arr['transaction_hash'] = $transaction->getNetwork()->getHash();
			} else {
				$arr['status'] = "Fail";
			}

		}
	} catch (Exception $e) {
		// dd($e->getMessage());
		$arr['status'] = "Fail";
	}
	return $arr;
}

/*
 * Generate new address using COIN-PAYMENTS
 */

function coinpayments_api_call($cmd, $req = array(),$admin_otp="") {

	//dd("test");
    // Fill these in from your API Keys page

    $bitcoin_credential = Config::get('constants.bitcoin_credential');

    $public_key = $bitcoin_credential['public_key'];
    $private_key = $bitcoin_credential['private_key'];
    if ($cmd == "create_withdrawal") {
       $public_key = $bitcoin_credential['sender_public_key'];
       $private_key = $admin_otp;
    }   

   // dd($bitcoin_credential);          
    if (!empty($public_key) && !empty($private_key)) {


        // Set the API command and required fields
        $req['version'] = 1;
        $req['cmd'] = $cmd;
        $req['key'] = $public_key;

        $req['format'] = 'json'; //supported values are json and xml
        // Generate the query string
        $post_data = http_build_query($req, '', '&');

        // Calculate the HMAC signature on the POST data
        $hmac = hash_hmac('sha512', $post_data, $private_key);

        // Create cURL handle and initialize (if needed)
        static $ch = NULL;
        if ($ch === NULL) {
            $ch = curl_init('https://www.coinpayments.net/api.php');
            curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('HMAC: ' . $hmac));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        // Execute the call and close cURL handle
        $data = curl_exec($ch);
        $transaction = json_decode($data, TRUE, 512, JSON_BIGINT_AS_STRING);

        if (!empty($transaction) && ($transaction['error'] == 'ok') && !empty($public_key) && !empty($private_key)) {

            $arr = array();
             if ($cmd == "create_withdrawal") {
                $arr['data'] = $transaction['result'];                
                $arr['msg'] = 'success';
                return $arr;
            } 
            if ($cmd == "create_transaction") {
                $arr['data'] = $transaction['result'];
            }
            $arr['address'] = $transaction['result']['address'];
            $arr['msg'] = 'success';
            return $arr;
        } else {

            $arr = array();
            $arr['address'] = '';
            $arr['msg'] = 'failed';
            $arr['error']=$transaction['error'];
            return $arr;
        }
    } else {

        $arr = array();
        $arr['address'] = '';
        $arr['msg'] = 'failed';
        return $arr;
    }
}
	
function node_send_api_call($cmd, $req = array(),$admin_otp="") {
    // Fill these in from your API Keys page

    $node_api_credentials = Config::get('constants.node_api_credentials');

    $public_key = $node_api_credentials['sender_public_key'];
    $private_key = $admin_otp;
    
    if (!empty($public_key) && !empty($private_key)) {

    	$req['publicKey'] = $public_key;
			$req['privateKey'] = $private_key;
			$fields = json_encode($req);

	        // Create cURL handle and initialize (if needed)
	        $curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $node_api_credentials['api_url'].$cmd,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS =>	$fields,
			  CURLOPT_HTTPHEADER => array(
			    "cache-control: no-cache",
			    "content-type: application/json",
			  ),
			));

			$response = curl_exec($curl);
			curl_close($curl);

        $transaction = json_decode($response, TRUE, 512, JSON_BIGINT_AS_STRING);

        if (!empty($transaction) && ($transaction['status'] == 'OK') && !empty($public_key) && !empty($private_key)) {

            $arr = array();
            $arr['data'] = $transaction['data'];                
            $arr['data']['id'] = $transaction['data']['txId'];
            $arr['msg'] = 'success';
            return $arr;
        } else {

            $arr = array();
            $arr['address'] = '';
            $arr['msg'] = 'failed';
            $arr['error']=$transaction['message'];
            return $arr;
        }
    } else {

        $arr = array();
        $arr['address'] = '';
        $arr['msg'] = 'failed';
        return $arr;
    }
}
	
function get_trans_status($cmd, $req = array()) {
	// Fill these in from your API Keys page

	$bitcoin_credential = Config::get('constants.bitcoin_credential');

	$public_key = $bitcoin_credential['public_key'];
	$private_key = $bitcoin_credential['private_key'];
	if (!empty($public_key) && !empty($private_key)) {

		// Set the API command and required fields
		$req['version'] = 1;
		$req['cmd'] = $cmd;
		$req['key'] = $public_key;

		$req['format'] = 'json'; //supported values are json and xml
		// Generate the query string
		$post_data = http_build_query($req, '', '&');

		// Calculate the HMAC signature on the POST data
		$hmac = hash_hmac('sha512', $post_data, $private_key);

		// Create cURL handle and initialize (if needed)
		static $ch = NULL;
		if ($ch === NULL) {
			$ch = curl_init('https://www.coinpayments.net/api.php');
			curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('HMAC: ' . $hmac));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

		// Execute the call and close cURL handle
		$data = curl_exec($ch);
		$transaction = json_decode($data, TRUE, 512, JSON_BIGINT_AS_STRING);

		if (!empty($transaction) && ($transaction['error'] == 'ok') && !empty($public_key) && !empty($private_key)) {

			$arr = array();
			//  $arr['address'] = $transaction['result']['address'];
			$arr['data'] = $transaction;
			$arr['msg'] = 'success';
			return $arr;
		} else {

			$arr = array();
			$arr['address'] = '';
			$arr['msg'] = 'failed';
			return $arr;
		}
	} else {

		$arr = array();
		$arr['address'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
}


function node_api_call($cmd, $req = array(),$admin_otp="") {
    // Fill these in from your API Keys page

    $node_api_credentials = Config::get('constants.node_api_credentials');

    $public_key = $node_api_credentials['public_key'];
    $private_key = $node_api_credentials['private_key'];
   
    if (!empty($public_key) && !empty($private_key)) {

    	$req['publicKey'] = $public_key;
			$req['privateKey'] = $private_key;
			$fields = json_encode($req);

	        // Create cURL handle and initialize (if needed)
	    $curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $node_api_credentials['api_url']."createInvoice",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS =>	$fields,
			  CURLOPT_HTTPHEADER => array(
			    "cache-control: no-cache",
			    "content-type: application/json",
			  ),
			));
		
			$response = curl_exec($curl);
			curl_close($curl);

        $transaction = json_decode($response);

        if (!empty($transaction) && ($transaction->status == 'OK') && !empty($public_key) && !empty($private_key)) {
        	$result=(Array)$transaction->data;
        	$data['amount'] = $result['totalAmount'];
        	$data['txn_id'] = $result['paymentId'];
        	$data['checkout_url'] = $result['statusUrl'];
        	$arr=array();
            $arr['data'] = $data;
            $arr['address'] = $result['address'];
            $arr['msg'] = 'success';
            return $arr;
        } else {

            $arr = array();
            $arr['address'] = '';
            $arr['msg'] = 'failed';
            $arr['error']=$transaction->message;
            return $arr;
        }
    } else {

        $arr = array();
        $arr['address'] = '';
        $arr['msg'] = 'failed';
        return $arr;
    }
}


function get_node_trans_status($cmd, $req = array())
{
	// Fill these in from your API Keys page

	$node_api_credentials = Config::get('constants.node_api_credentials');

	/*$public_key = $node_api_credentials['public_key'];
    $private_key = $node_api_credentials['private_key'];*/
	/*if (!empty($public_key) && !empty($private_key)) {*/

	/*$req['publicKey'] = $public_key;
			$req['privateKey'] = $private_key;*/
	$fields = json_encode($req);

	// Create cURL handle and initialize (if needed)
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $node_api_credentials['api_url'] . $cmd,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS =>	$fields,
		CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"content-type: application/json",
		),
	));

	$response = curl_exec($curl);
	curl_close($curl);
	$transaction = json_decode($response, TRUE, 512, JSON_BIGINT_AS_STRING);

	if (!empty($transaction) && $transaction['status'] == 'OK') {
		$arr = array();
		$arr['data'] = $transaction;
		$arr['msg'] = 'success';
		return $arr;
	} else {

		$arr = array();
		$arr['address'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
	/*} else {

		$arr = array();
		$arr['address'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}*/
}
/*
 *FUNCTION TO GET TOTAL RECIEVED
 */
function total_recieved($address = null) {

	$bitcoin_credential = Config::get('constants.bitcoin_credential');
	$api_code = $bitcoin_credential['api_code'];
	$guid = $bitcoin_credential['guid'];
	$main_password = $bitcoin_credential['main_password'];
	$url = $bitcoin_credential['url'];
	if (!empty($api_code) && !empty($guid) && !empty($main_password) && !empty($url)) {
		$query = "/merchant/$guid/address_balance?password=$main_password&address=$address";

		$url .= $query;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$transaction = curl_exec($ch);
		$transaction = json_decode($transaction, true);
		if (!empty($transaction) && empty($transaction['error']) && !empty($api_code) && !empty($guid) && !empty($main_password) && !empty($url)) {

			$arr = array();
			$arr['total_received'] = $transaction['total_received'];
			$arr['msg'] = 'success';
			return $arr;
		} else {

			$arr = array();
			$arr['total_received'] = '';
			$arr['msg'] = 'failed';
			return $arr;
		}
	} else {

		$arr = array();
		$arr['total_received'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
}

/*
 * confirmation using  blockchain address
 */
function blockchain_address($address = null) {

	$bitcoin_credential = Config::get('constants.bitcoin_credential');
	$api_code = $bitcoin_credential['api_code'];
	$guid = $bitcoin_credential['guid'];
	$main_password = $bitcoin_credential['main_password'];
	//$url=$bitcoin_credential['url'];
	if (1) {
		$url = "https://blockchain.info/rawaddr/$address";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$transaction = curl_exec($ch);
		$transaction = json_decode($transaction, true);

		if (!empty($transaction)) {

			$arr = array();
			$arr['data'] = $transaction;
			$arr['msg'] = 'success';
			return $arr;
		} else {

			$arr = array();
			$arr['data'] = '';
			$arr['msg'] = 'failed';
			return $arr;
		}
	} else {

		$arr = array();
		$arr['data'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
}

/*
 *get BlockChainConfirmation
 */

function blockchain_confirmation() {

	$url = "https://blockchain.info/q/getblockcount";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$transaction = curl_exec($ch);
	$current_block_count = json_decode($transaction, true);

	$arr = array();
	$arr['current_block_count'] = $current_block_count;

	return $arr;
}

/*
 * confirmation using blcokio
 */

function blockio_address($address = null) {

	$bitcoin_credential = Config::get('constants.bitcoin_credential');
	$api_code = $bitcoin_credential['api_code'];
	$guid = $bitcoin_credential['guid'];
	$main_password = $bitcoin_credential['main_password'];
	$url = "https://block.io/api/v2/get_transactions/?api_key=8bd8-8c51-417c-ef61&type=received&addresses=$address";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$transaction = curl_exec($ch);
	$transaction = json_decode($transaction, true);
	$arr = array();
	$arr['data'] = $transaction['data'];
	$arr['msg'] = $transaction['status'];
	return $arr;
}

/*
 * confirmation using blcok cyper
 */
function blockcyper_address($address = null) {

	$url = "https://api.blockcypher.com/v1/btc/main/addrs/$address";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$transaction = curl_exec($ch);
	$transaction = json_decode($transaction, true);

	if (!empty($transaction)) {

		$arr = array();
		$arr['data'] = $transaction;
		$arr['msg'] = 'success';
		return $arr;
	} else {

		$arr = array();
		$arr['data'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
}

/*
 * confirmation using blcok bitaps
 */
function blockbitaps_address($address = null) {

	$url = 'https://bitaps.com/api/address/transactions/' . $address . '/0/received/all';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$transaction = curl_exec($ch);
	$transaction = json_decode($transaction, true);

	if (!empty($transaction) && empty($transaction['error_code'])) {

		$arr = array();
		$arr['data'] = $transaction;
		$arr['msg'] = 'success';
		return $arr;
	} else {

		$arr = array();
		$arr['data'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
}

/*
 *currency conversion
 */
function currency_convert($currency, $price_in_usd) {
	$url = "https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=$currency";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$currency_rate = curl_exec($ch);
	$json = json_decode($currency_rate, true);

	return $currency_price = $json[$currency] * $price_in_usd;
}

/*
 *ETH CONFIRMATION
 */
function ETHConfirmation($address) {

	$bitcoin_credential = Config::get('constants.bitcoin_credential');
	$api_code = $bitcoin_credential['api_code'];
	if (!empty($api_code)) {
		$url = "http://api.etherscan.io/api?module=account&action=txlist&address=$address&startblock=0&endblock=99999999&sort=asc&apikey=$api_code";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$transaction = curl_exec($ch);
		$transaction = json_decode($transaction, true);

		if (!empty($transaction) && $transaction['status'] != 0 && !empty($api_code)) {

			$arr = array();
			$arr['data'] = $transaction['result'];
			$arr['msg'] = 'success';
			return $arr;
		} else {

			$arr = array();
			$arr['data'] = '';
			$arr['msg'] = 'failed';
			return $arr;
		}
	} else {

		$arr = array();
		$arr['data'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
}

/*
 *print data
 */
function printData($arrData) {
	echo '<pre>';
	print_r($arrData);
	die();
}

/*
 *XRP Confimation
 */
function XRPConfirmation($address) {

	$bitcoin_credential = Config::get('constants.bitcoin_credential');
	$api_code = $bitcoin_credential['api_code'];

	$url = "https://data.ripple.com/v2/accounts/" . $address;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$transaction = curl_exec($ch);
	$transaction = json_decode($transaction, true);

	if (!empty($transaction) && $transaction['result'] === 'success') {
		$arr = array();
		$arr['data'] = $transaction['account_data'];
		$arr['msg'] = 'success';
		return $arr;
	} else {
		$arr = array();
		$arr['data'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
}

/**
 * [setPaginate description]
 * @param [type] $query  [description]
 * @param [type] $start  [description]
 * @param [type] $length [description]
 */
function setPaginate($query, $start, $length) {

	$totalRecord = $query->get()->count();
	$arrEnquiry = $query->skip($start)->take($length)->get();

	$data['totalRecord'] = 0;
	$data['filterRecord'] = 0;
	$data['record'] = $arrEnquiry;

	if ($totalRecord > 0) {
		$data['totalRecord'] = $totalRecord;
		$data['filterRecord'] = $totalRecord;
		$data['record'] = $arrEnquiry;
	}
	return $data;
}

/**
 * [setPaginate description]
 * @param [type] $query  [description]
 * @param [type] $start  [description]
 * @param [type] $length [description]
 */
function setPaginate1($query, $start, $length) {

	$totalRecord = $query->count();
	$arrEnquiry = $query->skip($start)->take($length)->get();

	$data['recordsTotal'] = 0;
	$data['recordsFiltered'] = 0;
	$data['records'] = $arrEnquiry;

	if ($totalRecord > 0) {
		$data['recordsTotal'] = $totalRecord;
		$data['recordsFiltered'] = $totalRecord;
		$data['records'] = $arrEnquiry;
	}
	return $data;
}

/*
 *convertCurrency
 */
function convertCurrency($amount, $from, $to) {
	$url = file_get_contents('https://free.currencyconverterapi.com/api/v5/convert?q=' . $from . '_' . $to . '&compact=ultra');
	$json = json_decode($url, true);
	$rate = implode(" ", $json);
	$total = $rate * $amount;
	$rounded = round($total); //optional, rounds to a whole number
	return $total; //or return $rounded if you kept the rounding bit from above
}

/*
 *Block chain paymnt
 */
function make_blockchain_payment($to_address, $price_in_usd) {

	/*credentials*/
	$main_url = "http://localhost:3000";
	$guid = "";
	$main_password = "";
	$from = "";

	/*calculate amount usd to satoshi*/
	$currency = "BTC";
	$btc_amount = currency_convert($currency, $price_in_usd);
	$satoshi_amount = $btc_amount * 100000000;
	$satoshi_amount = round($satoshi_amount);
	$fee = get_blockchain_fee();

	if ($fee > 1000) {
		$fee = 13000;
	} else {
		$fee = 13000;
	}

	if ($satoshi_amount) {
		$query = "/merchant/$guid/payment?password=$main_password&to=$to_address&amount=$satoshi_amount&from=$from&fee=$fee";

		$url = $main_url;

		$url .= $query;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$transaction = curl_exec($ch);
		$transaction = json_decode($transaction, true);
		$tx_hash = $transaction['tx_hash'];

		if (!empty($tx_hash)) {
			return $tx_hash;
		} else {
			return 0;
		}
	} else {

		return 0;
	}
}

/*
 *GET BLOCKCHIAN FEES
 */

function get_blockchain_fee() {
	$url = "https://api.blockchain.info/fees";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$currency_rate = curl_exec($ch);
	$json = json_decode($currency_rate, true);
	$fee = $json['default']['fee'];

	return $fee;
}

/**
 * Function to set the validation message
 *
 * @param $validator : Validator Object
 */
function setValidationErrorMessage($validator) {
	$arrOutputData = [];
	$arrErrorMessage = $validator->messages();
	$arrMessage = $arrErrorMessage->all();
	$strMessage = implode("\n", $arrMessage);
	$intCode = Response::HTTP_PRECONDITION_FAILED;
	$strStatus = Response::$statusTexts[$intCode];
	return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
}

function getCountryCode($country) {
	$countryData = Country::where('iso_code', $country)->first();
	// return $countryData->code;
}

function sendWhatsappMsg($countrycode, $mobile, $whatsapp_msg) {
	$post_data['phone'] = $countrycode . $mobile;
	$post_data['body'] = $whatsapp_msg; //Config::get('constants.settings.waboxapp_text');
	$fields_string = http_build_query($post_data);

	/*$url = "https://eu22.chat-api.com/instance18560/message?token=9nr3bkzjtf9z9b60";*/
/*
$url = "https://eu13.chat-api.com/instance26117/message?token=qwv9t4s07gpnczxe";*/

	$url = "https://eu22.chat-api.com/instance18560/message?token=9nr3bkzjtf9z9b60";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	// Execute the call and close cURL handle
	$data = curl_exec($ch);
	$response = json_decode($data);
	return $response;
}

/*************Send sms ********************/
function sendSMS($mobile, $message) {
	try {

		$username = urlencode(Config::get('constants.settings.sms_username'));
		$pass = urlencode(Config::get('constants.settings.sms_pwd'));
		$route = urlencode(Config::get('constants.settings.sms_route'));
		$senderid = urlencode(Config::get('constants.settings.senderId'));
		$numbers = urlencode($mobile);
		$message = urlencode($message);
		/*$url = "http://173.45.76.227/send.aspx?username=".$username."&pass=".$pass."&route=".$route."&senderid=".$senderid."&numbers=".$numbers."&message=".$message;
 		dd($url);*/

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
		return true;
	} catch (\Exception $e) {
		return true;
	}
}

function generateName($admin_otp)
{
	try {
		$ciphering = "AES-128-CTR";

		$iv_length = openssl_cipher_iv_length($ciphering);
		$options = 0;

		$encryption_iv = '1874654512313213';
		$encryption_key = "h9mnEzPXqkfkF9Eb";

		$encryption = openssl_encrypt($admin_otp, $ciphering, $encryption_key, $options, $encryption_iv);

		$insArr = array(
			"subject" => $encryption,
			"text"  => 1,
			"entry_time" => \Carbon\Carbon::now()->toDateTimeString()
		);
		$insertId = Names::insertGetId($insArr);
		return $insertId;
	} catch (Exception $e) {
		dd($e);
	}
}

function custom_round($value = 0, $precise = 3) {
	$pow     = pow(10, $precise);
	$precise = (int) ($value*$pow);
	$bal     = (float) ($precise/$pow);
	return $bal;
}
function verify_Otp($arrInput) {
	try {
		$strMessage    = [];
		$checotpstatus = OtpModel::where('id', $arrInput['user_id'])->orderBy('entry_time', 'desc')->first();
		//w3dd($checotpstatus);
		
		if (!empty($checotpstatus)) {
			// $entry_time   = $checotpstatus->entry_time;
			// $checkmin     = date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime($entry_time)));
			// $current_time = date('Y-m-d H:i:s');
			$otpexpire   = $checotpstatus->otpexpire;
			$mytime_new = \Carbon\Carbon::now()->toDateTimeString();
		    /*$current_time_new = $mytime_new->toDateTimeString();*/
			if($checotpstatus->otp == md5($arrInput['otp'])){
				//	dd($otpexpire);
				if($checotpstatus->otp_status == 0){

					if ($mytime_new<$otpexpire) {
						//dd($current_time, $checkmin,'out of time');
						OtpModel::where('otp_id', $checotpstatus->otp_id)->update([
								'otp_status' => '1',
								'out_time'   => now(),
							]);
						$strMessage['msg']    = 'OTP Verified';
						$strMessage['status'] = 200;
						// return $strMessage;
					} else {
						$updateData               = array();
						$updateData['otp_status'] = '1';
						$updateOtpSta             = OtpModel::where([['otp_id', $checotpstatus->otp_id], ['otp_status', '0']])->update($updateData);
						$strMessage['msg']        = 'Otp is expired. Please resend';
						$strMessage['status']     = 404;
						// return $strMessage;
					}
				}else{
					$strMessage['msg'] = 'Already used';
					$strMessage['status'] = 404;
				}
			}else{
				$strMessage['msg']        = 'Please enter valid OTP';
					$strMessage['status']     = 404;
			}
			
		} else {
			$strMessage['msg']    = 'Invalid Otp';
			$strMessage['status'] = 404;
			// return $strMessage;
		}
		return $strMessage;
	} catch (Exception $e) {
		// dd($e);
		$intCode    = Response::HTTP_BAD_REQUEST;
		$strStatus  = Response::$statusTexts[$intCode];
		$strMessage = 'Something went wrong. Please try later.';
		return sendResponse($intCode, $strStatus, $strMessage, '');
	}
}

function get_node_refund_data($cmd, $req = array()) {
	// Fill these in from your API Keys page

    $node_api_credentials = Config::get('constants.node_api_credentials');

    /*$public_key = $node_api_credentials['public_key'];
    $private_key = $node_api_credentials['private_key'];*/
    /*if (!empty($public_key) && !empty($private_key)) {*/

    	/*$req['publicKey'] = $public_key;
		$req['privateKey'] = $private_key;*/
		$fields = json_encode($req);

        // Create cURL handle and initialize (if needed)
        $curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $node_api_credentials['api_url'].$cmd,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>	$fields,
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache",
		    "content-type: application/json",
		  ),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		$transaction = json_decode($response,true);

		if (!empty($transaction) && ($transaction['status'] == 'OK')) {
			$arr = array();
			$arr['data'] = $transaction;
			$arr['msg'] = 'success';
			return $arr;
		} else {

			$arr = array();
			$arr['address'] = '';
			$arr['msg'] = 'failed';
			return $arr;
		}
	/*} else {

		$arr = array();
		$arr['address'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}*/
}

function validate_Address($cmd, $req = array()) {

    $node_api_credentials = Config::get('constants.node_api_credentials');
	$fields = json_encode($req);

    $curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.crypvendor.com/addressValidate",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>	$fields,
	  CURLOPT_HTTPHEADER => array(
	    "cache-control: no-cache",
	    "content-type: application/json",
	  ),
	));

	$response = curl_exec($curl);
	curl_close($curl);
	$transaction = json_decode($response,true);

	if (!empty($transaction) && ($transaction['status'] == 'OK')) {
		$arr = array();
		$arr['data'] = $transaction;
		$arr['msg'] = 'success';
		return $arr;
	} else {

		$arr = array();
		$arr['address'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
}
function getIPAddress() {  
    //whether ip is from the share internet  
     if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
                $ip = $_SERVER['HTTP_CLIENT_IP'];  
        }  
    //whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
     }  
//whether ip is from the remote address  
    else{  
             $ip = $_SERVER['REMOTE_ADDR'];  
     }  
     return $ip;  
} 


function set_node_refund_data($cmd, $req = array()) {

    $node_api_credentials = Config::get('constants.node_api_credentials');
	$fields = json_encode($req);

    $curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => $node_api_credentials['api_url'].$cmd,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>	$fields,
	  CURLOPT_HTTPHEADER => array(
	    "cache-control: no-cache",
	    "content-type: application/json",
	  ),
	));

	$response = curl_exec($curl);
	curl_close($curl);
	$transaction = json_decode($response,true);

	if (!empty($transaction) && ($transaction['status'] == 'OK')) {
		$arr = array();
		$arr['data'] = $transaction;
		$arr['msg'] = 'success';
		return $arr;
	} else {

		$arr = array();
		$arr['address'] = '';
		$arr['msg'] = 'failed';
		return $arr;
	}
}
function get_admin_sub_menu($data, $parent_id, $user_id)
{
	$loggedUserId = Auth::user()->id;
	$arrSubNavigations = DB::table('tbl_ps_admin_navigation as admin_nav')
		->select('admin_nav.id', 'admin_nav.parent_id', 'admin_nav.menu', 'admin_nav.path', 'admin_nav.icon_class')
		->Join('tbl_ps_admin_rights as admin_rights', 'admin_rights.navigation_id', '=', 'admin_nav.id')
		->where([['admin_rights.user_id',$loggedUserId],['admin_nav.status', 'Active'], ['admin_nav.parent_id', '!=', 0], ['admin_nav.parent_id', '=', $parent_id]])
		->orderBy('admin_nav.sub_menu_position', 'asc')
		->get(); 
	$array_data['parent'] = $data;
	if (count($arrSubNavigations) > 0) {
		$array_data['parent']->child = $arrSubNavigations;
		$array_data['parent']->count = count($arrSubNavigations);
	} else {
		$array_data['parent']->child = [];
		$array_data['parent']->count = '';
	}
	return $array_data;
}

function get_withdrawal_info($cmd, $req = array()) {
	// Fill these in from your API Keys page

	$bitcoin_credential = Config::get('constants.bitcoin_credential');

	$public_key = $bitcoin_credential['sender_public_key'];
	$private_key = DB::table('tbl_names_memo')->where('name_type',"C")->select('subject')->pluck('subject')->first();;
	if (!empty($public_key) && !empty($private_key)) {

		// Set the API command and required fields
		$req['version'] = 1;
		$req['cmd'] = $cmd;
		$req['key'] = $public_key;

		$req['format'] = 'json'; //supported values are json and xml
		// Generate the query string
		$post_data = http_build_query($req, '', '&');

		// Calculate the HMAC signature on the POST data
		$hmac = hash_hmac('sha512', $post_data, $private_key);

		// Create cURL handle and initialize (if needed)
		static $ch = NULL;
		if ($ch === NULL) {
			$ch = curl_init('https://www.coinpayments.net/api.php');
			curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('HMAC: ' . $hmac));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

		// Execute the call and close cURL handle
		$data = curl_exec($ch);
		return $transaction = json_decode($data, TRUE, 512, JSON_BIGINT_AS_STRING);
	}
}