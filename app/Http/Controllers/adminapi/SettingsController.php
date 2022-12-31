<?php

namespace App\Http\Controllers\adminapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\adminapi\CommonController;
use App\Models\ProjectSettings;
use App\Models\ReservedAddress;
use App\Models\PerfectMoneyMember;
use App\Models\Currency;
use App\User;
use DB;
use Config;
use Validator;
use Auth;
use Illuminate\Http\Response; 

class SettingsController extends Controller
{
    /**
     * define property variable
     *
     * @return
     */
    public $statuscode,$constants_settings,$commonController;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CommonController $CommonController) {
        $this->statuscode           = Config::get('constants.statuscode');
        $this->constants_settings   = Config::get('constants.settings');
        $this->commonController     = $CommonController;
    }

    /**
     * get settings of project
     *
     * @return void
     */
    public function getProjectSettings() {

		$ProjectSettings = ProjectSettings::where('status',1)->first();

        if(!empty($ProjectSettings)) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Project settings found', $ProjectSettings);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Project settings not found', '');
        }
	}

    public function currencySettings(Request $request) {
        // $url = url('uploads/products');
        $query = Currency::select('tbl_currency.*');
                 // ->leftjoin('tbl_product_variation_details as tpv','tpv.product_id','=','tbl_product_ecommerce.id');
         $arrInput = $request->all();

        $query = $query->orderBy('tbl_currency.entry_time','desc');
        
        $data = $query->get();
        $arrDirectInc = $query->skip($arrInput['start'])->take($arrInput['length'])->get();
        // dd($data);
        $arrData['recordsTotal'] = count($data);
        $arrData['recordsFiltered'] = count($data);
        $arrData['records'] = $arrDirectInc;

        if(count($arrDirectInc)>0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Ecommerce Products found', $arrData);
        } else {
            return sendresponse($this->statuscode[409]['code'], $this->statuscode[409]['status'],'Ecommerce Products not found', '');
        }
    }

    public function currencyAdd(Request $request){
		$arrInput = $request->all();
		$rules = array(
			'currency_name' => 'required',
			'currency_code' => 'required',
			'currency_status' => 'required',
            'withdrwal_status' => 'required',
		);
		$validator = Validator::make($arrInput, $rules);
		if ($validator->fails()) {
			$message = $validator->errors();
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'Input field is required or invalid', $message);
		}

		
		
		$Insertdata = array();
    $Insertdata['currency_name'] = $request->currency_name;
    $Insertdata['currency_code'] = $request->currency_code;
    $Insertdata['currency_status'] = $request->currency_status;
    $Insertdata['withdrwal_status'] = $request->withdrwal_status;    
    $Insertdata['coinpayment_status'] = 0; 
    $Insertdata['node_api_status'] = 0;     
    $Insertdata['entry_time'] = \Carbon\Carbon::now();    

    $insertAdd = Currency::create($Insertdata);

   

          if (!empty($insertAdd)) 
          {
                  	return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'New currency added successfully','');
					} else {
						return sendresponse($this->statuscode[409]['code'], $this->statuscode[409]['status'], 'Error while adding currency', '');
					}

		
	}

	public function getCurrencyDetails(Request $request) {
		$arrInput = $request->all();
		
	
		//get user dashboard data
		$userProfile = Currency::where('id', $arrInput['id'])->first();

		
		$arrFinalData['userProfile'] = $userProfile;
		

		if (count($arrFinalData) > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrFinalData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
		}
	}

    public function updateCurrency(Request $request) {
		$arrInput = $request->all();

		$rules = array(
			'currency_code' => 'required',
			
			'currency_name' => 'required',
			'currency_status' => 'required',
			/*'city' => 'required',
			'address' => 'required',*/
			'withdrwal_status'       => 'required',
			//'btc_address'   => 'required',
		);
		/*$ruleMessages = array(
			'fullname.regex' => 'Special characters not allowed in fullname.',
		);*/
		$validator = Validator::make($arrInput, $rules);
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$message = messageCreator($validator->errors());
			return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
		} else {
			
					$arrUpdate = [
						'currency_name' => $arrInput['currency_name'],
						'currency_code' => $arrInput['currency_code'],
						'status' => $arrInput['currency_status'],
						/*'city' => $arrInput['city'],
						'address' => $arrInput['address'],*/
						'withdrwal_status'       => $arrInput['withdrwal_status'],
						//'status'        => $arrInput['status'],
						//'coinpayment_status' => $arrInput['coinpayment_status'],
						//'node_api_status' => $arrInput['node_api_status'],
						

						/*'paypal_address' => $arrInput['paypal_address'],
						'perfect_money_address' => $arrInput['perfect_money_address'],*/
						//'ethereum'      => $arrInput['ethereum'],
						//'ref_user_id' =>
					];
					//update user with new data
					$updateData = Currency::where('id', $arrInput['id'])->limit(1)->update($arrUpdate);
					//update levels of user
					/*if(!empty($arrInput['ref_user_id']) && !empty($user_id)){
	                        $this->levelController->updateLevelView($arrInput['ref_user_id'],$user_id,1);
*/
					if (!empty($updateData)) {
						return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Currency data updated successfully.', '');
					} else {
						return sendresponse($this->statuscode[409]['code'], $this->statuscode[409]['status'], 'Data already existed with given inputs.', '');
					}
				
		}
	}
    

    /**
     * get common settings of emails
     *
     * @return void
     */
    public function getConstantSettings() {

        $ProjectSettings = $this->constants_settings;

        if(!empty($ProjectSettings)) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Settings found', $ProjectSettings);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Settings not found', '');
        }
    }

    /**
     * get all country without pagination
     *
     * @return void
     */
    public function getCountry() {

        $arrCountry = $this->commonController->getAllCountry();

        if(count($arrCountry) > 0) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found', $arrCountry);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found', '');
        }
    }

    /**
     * get all products list
     *
     * @return void
     */
    public function getProductList() {
        $arrProducts = $this->commonController->getAllProducts();

        if(count($arrProducts)>0) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Records found', $arrProducts);
        } else {        
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Records not found', '');
        }
    }
    public function UpdateWithdrawSetting(Request $request) {
        try{
            $arrUpdt = array();
            $arrUpdt['withdraw_status'] = $request->withdraw_status;
            $arrUpdt['withdraw_start_time'] =(int) $request->withdraw_start_time;
            $arrUpdt['withdraw_day'] = $request->withdraw_day;
            $arrUpdt['withdraw_off_msg'] = $request->withdraw_off_msg;
            $updt = ProjectSettings::where('id',1)->update($arrUpdt);
            if($updt) {
                return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Settings updated', $updt);
            } 
        }catch(Exception $e){
            dd($e);
        }        
    }

    public function getAdminLoginDetails() {

        $arrData['user_id']         = Auth::user()->user_id;
        //$arrData['current_time']    = \Carbon\Carbon::now();
        
        $arrData['current_time']    = (Object)['date'=>\Carbon\Carbon::now()->setTimezone('Asia/Tokyo')->format('Y/m/d H:i:s')];
        $arrData['ip_address']      = $_SERVER['REMOTE_ADDR'];
        $arrData['server_time']     = getTimeZoneByIP($arrData['ip_address']);
        $arrWhere = [['used_status','Unused']];
        $arrData['address_balance'] = ReservedAddress::where($arrWhere)->count();
        
        if(!empty($arrData)) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found', $arrData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found', '');
        }
    }


     /**
     * Check address is valid or not
     * @return \Illuminate\Http\Response
     */
    public function checkAddresses(Request $request) {
        try{

                $rules = array('address' => 'required', 'network_type' => 'required');
                $validator = checkvalidation($request->all(), $rules,'');
                if (!empty($validator)) {
                    $arrStatus   = Response::HTTP_NOT_FOUND;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = $validator; 
                    return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                }
                //--------------Check adress exist with received-------------//
                if (trim($request->input('network_type')) == 'BTC') {
                    $AddTrecived = blockio_address(trim($request->input('address')));
                    if (!empty($AddTrecived) && $AddTrecived['msg'] == 'fail') {
                        $chainTrecived = blockchain_address(trim($request->input('address')));
                        if (!empty($chainTrecived) && $chainTrecived['msg'] == 'failed') {
                            $cyperTrecived = blockcyper_address(trim($request->input('address')));
                            if (!empty($cyperTrecived) && $cyperTrecived['msg'] == 'failed') {
                                $bitapsrecived = blockbitaps_address(trim($request->input('address')));
                                if (!empty($bitapsrecived) && $bitapsrecived['msg'] == 'failed') {

                                    $arrStatus   = Response::HTTP_NOT_FOUND;
                                    $arrCode     = Response::$statusTexts[$arrStatus];
                                    $arrMessage  = 'Bitcoin address is not valid'; 
                                    return sendResponse($arrStatus,$arrCode,$arrMessage,'');

                                } else {
                                    $arrStatus   = Response::HTTP_OK;
                                    $arrCode     = Response::$statusTexts[$arrStatus];
                                    $arrMessage  = 'Bitcoin address is valid'; 
                                    return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                                }
                            } else {
                                    $arrStatus   = Response::HTTP_OK;
                                    $arrCode     = Response::$statusTexts[$arrStatus];
                                    $arrMessage  = 'Bitcoin address is valid'; 
                                    return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                            }
                        } else {
                            $arrStatus   = Response::HTTP_OK;
                            $arrCode     = Response::$statusTexts[$arrStatus];
                            $arrMessage  = 'Bitcoin address is valid'; 
                            return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                        }
                    } else {
                        $arrStatus   = Response::HTTP_OK;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = 'Bitcoin address is valid'; 
                        return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                    }
                } else if (trim($request->input('network_type')) == 'ETH') {
                    $Transaction = ETHConfirmation(trim($request->input('address')));
                    if (!empty($Transaction) && $Transaction['msg'] == 'failed') {

                        $arrStatus   = Response::HTTP_NOT_FOUND;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = 'Ethereum address is not valid'; 
                        return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                    } else {
                        $arrStatus   = Response::HTTP_OK;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = 'Ethereum address is valid'; 
                        return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                    }
                } /*else if(trim($request->input('network_type')) == 'XRP') {
                    $Transaction = XRPConfirmation(trim($request->input('address')));
                    if (!empty($Transaction) && $Transaction['msg'] == 'failed') {
                        $arrStatus   = Response::HTTP_NOT_FOUND;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = 'Ripple address is not valid'; 
                        return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                       
                    } else {
                        $arrStatus   = Response::HTTP_OK
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = 'Ripple address is valid'; 
                        return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                   }
                }*/
           }catch(Exception $e){
               $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
               $arrCode     = Response::$statusTexts[$arrStatus];
               $arrMessage  = 'Something went wrong,Please try again'; 
               return sendResponse($arrStatus,$arrCode,$arrMessage,'');
           }       
    }

     public function addMember(Request $request)
   {
        $addmember = new PerfectMoneyMember();
        $addmember->member = $request->member;
        $addmember->receiver = $request->receiver;
        $addmember->passkey = $request->passkey;
        
        if($addmember->save()){
            $arrStatus = Response::HTTP_OK;
            $arrCode = Response::$statusTexts[$arrStatus];
            $arrMessage = 'Member Added';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }else{
            $arrStatus = Response::HTTP_NOT_FOUND;
            $arrCode = Response::$statusTexts[$arrStatus];
            $arrMessage = 'Something went wrong. Please try agains';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
   }

   public function updateMember(Request $request)
   {
        try{
            $updateMemeber = array();
            $updateMemeber['member'] = $request->member;
            $updateMemeber['receiver'] = $request->receiver;
            $updateMemeber['passkey'] = $request->passkey;
            PerfectMoneyMember::where('memberID',1)->update($updateMemeber);
            $arrStatus = Response::HTTP_OK;
            $arrCode = Response::$statusTexts[$arrStatus];
            $arrMessage = 'Data Updated';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
        catch(Exception $e){
            $arrStatus = Response::HTTP_NOT_FOUND;
            $arrCode = Response::$statusTexts[$arrStatus];
            $arrMessage = 'Something went wrong. Please try agains';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
       
   }







}