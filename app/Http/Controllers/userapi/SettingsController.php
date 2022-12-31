<?php

namespace App\Http\Controllers\userapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;
use App\Models\ProjectSettings;
use App\Models\Topup;
use App\Models\WithdrawPending;
use App\User;
use App\Models\StatisticSettings;
use App\Models\TransactionInvoice;
use Config;
use Validator;
use Exception;

class SettingsController extends Controller {
/**
     * Get project coin name     *
     * @return \Illuminate\Http\Response
     */

    function getProjectDetails() {
      try{
            $coin_name = ProjectSettings::where([['status', '=', 1],])->pluck('coin_name')->first();
            if (!empty($coin_name)) {
                $DataArr = array();
                $DataArr['coin_name'] = $coin_name;
                    $arrStatus   = Response::HTTP_OK;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = 'Project coin name get successfully'; 
                    return sendResponse($arrStatus,$arrCode,$arrMessage,$DataArr);
               
            } else {
                    $arrStatus   = Response::HTTP_NOT_FOUND;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = 'Data not found'; 
                    return sendResponse($arrStatus,$arrCode,$arrMessage,'');
            }
        }catch(Exception $e){
           $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
           $arrCode     = Response::$statusTexts[$arrStatus];
           $arrMessage  = 'Something went wrong,Please try again'; 
           return sendResponse($arrStatus,$arrCode,$arrMessage,'');
        }    
    }

    /**
     * Get project details     *
     * @return \Illuminate\Http\Response
     */
    public function getProjectSettingsDetails() {
        
     try{
            $dataname = ProjectSettings::where([['status', '=', 1],])->first();

            if (!empty($dataname)) {

                    $arrStatus   = Response::HTTP_OK;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = 'Project data found successfully'; 
                    return sendResponse($arrStatus,$arrCode,$arrMessage,$dataname);
            } else {
                    $arrStatus   = Response::HTTP_NOT_FOUND;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = 'Data not found'; 
                    return sendResponse($arrStatus,$arrCode,$arrMessage,'');
            }
        }catch(Exception $e){
           $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
           $arrCode     = Response::$statusTexts[$arrStatus];
           $arrMessage  = 'Something went wrong,Please try again'; 
           return sendResponse($arrStatus,$arrCode,$arrMessage,'');
        }    
    }

     /**
     * Check address is valid or not
     * @return \Illuminate\Http\Response
     */
    public function checkAddresses(Request $request) {
        try{

                $rules = array('address' => 'required|regex:/^\S*$/', 'network_type' => 'required');
                $validator = checkvalidation($request->all(), $rules,'');
                if (!empty($validator)) {
                    $arrStatus   = Response::HTTP_NOT_FOUND;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = $validator; 
                    return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                }
                //--------------Check adress exist with received-------------//
                
                    if (trim($request->input('network_type')) == 'BTC') {
                        
                        if(!empty($request->address)){
                            if(strlen($request->address) >= 26 && strlen($request->address) <= 45){
                                $split_array = str_split(trim($request->address));
                                if ($split_array[0] == 3)
                                {
                                    $arrStatus   = Response::HTTP_OK;
                                    $arrCode     = Response::$statusTexts[$arrStatus];
                                    $arrMessage  = 'BTC address is valid'; 
                                    return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                                } elseif ($split_array[0] == 1) {
                                    $arrStatus   = Response::HTTP_OK;
                                    $arrCode     = Response::$statusTexts[$arrStatus];
                                    $arrMessage  = 'BTC address is valid'; 
                                    return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                                } elseif ($split_array[0] == 'b') {
                                    $arrStatus   = Response::HTTP_OK;
                                    $arrCode     = Response::$statusTexts[$arrStatus];
                                    $arrMessage  = 'BTC address is valid'; 
                                    return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                                } else {
                                    $arrStatus   = Response::HTTP_NOT_FOUND;
                                    $arrCode     = Response::$statusTexts[$arrStatus];
                                    $arrMessage  = 'BTC Address should be start with "b" or "1" or "3"'; 
                                    return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                                }
                            }else{
                                $arrStatus   = Response::HTTP_NOT_FOUND;
                                $arrCode     = Response::$statusTexts[$arrStatus];
                                $arrMessage  = 'BTC Address must have minimum 26 and maximum 45 characters'; 
                                return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                            }
                        }else{
                            $arrStatus   = Response::HTTP_NOT_FOUND;
                            $arrCode     = Response::$statusTexts[$arrStatus];
                            $arrMessage  = 'BTC Address is Required.'; 
                            return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                        }
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
                    
                    if(strlen($request->address) >= 26 && strlen($request->address) <= 45){
                        $split_array = str_split(trim($request->address));
                        // dd($split_array[0]);
                        if($split_array[0] == "0" && $split_array[1] == 'x')
                        {
                            if($split_array[1] == 'x') {
                                $arrStatus   = Response::HTTP_OK;
                                $arrCode     = Response::$statusTexts[$arrStatus];
                                $arrMessage  = 'ETH address is valid'; 
                                return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                            }else{
                                $arrStatus   = Response::HTTP_NOT_FOUND;
                                $arrCode     = Response::$statusTexts[$arrStatus];
                                $arrMessage  = 'ETH Address should be start with 0x'; 
                                return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                            }

                        }  else if($split_array[1] == 'x') {
                            $arrStatus   = Response::HTTP_OK;
                            $arrCode     = Response::$statusTexts[$arrStatus];
                            $arrMessage  = 'ETH address is valid'; 
                            return sendResponse($arrStatus,$arrCode,$arrMessage,'');
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
                } else if (trim($request->input('network_type')) == 'BNB') {
                  
                        if(strlen($request->address) >= 26 && strlen($request->address) <= 45){
                            $split_array = str_split(trim($request->address));
                            // dd($split_array[0]);
                            if($split_array[0] == '0' || $split_array[0] == '1') 
                            {
                                $arrStatus   = Response::HTTP_OK;
                                $arrCode     = Response::$statusTexts[$arrStatus];
                                $arrMessage  = 'BNB address is valid'; 
                                return sendResponse($arrStatus,$arrCode,$arrMessage,'');
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
                    
                }else if (trim($request->input('network_type')) == 'TRX') {
                    if(strlen($request->address) >= 26 && strlen($request->address) <= 45){
                        $split_array = str_split(trim($request->address));
                        if($split_array[0] == 't' || $split_array[0] == 'T') 
                        {
                            $arrStatus   = Response::HTTP_OK;
                            $arrCode     = Response::$statusTexts[$arrStatus];
                            $arrMessage  = 'TRX address is valid'; 
                            return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                            
                        }  else {
                            $arrStatus   = Response::HTTP_NOT_FOUND;
                            $arrCode     = Response::$statusTexts[$arrStatus];
                            $arrMessage  = 'TRX Address should be start with T or t'; 
                            return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                        }
                    }else{
                        $arrStatus   = Response::HTTP_NOT_FOUND;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = 'TRX Address must have minimum 26 and maximum 45 characters'; 
                        return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                    }
                }
           }catch(Exception $e){
               dd($e);
               $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
               $arrCode     = Response::$statusTexts[$arrStatus];
               $arrMessage  = 'Something went wrong,Please try again'; 
               return sendResponse($arrStatus,$arrCode,$arrMessage,'');
           }       
    }


    
    public function getProjectStatistics() {
        try{

            $total_users = User::select('id')->where('id','!=',1)->count('id');
            /*$total_deposite = Topup::selectRaw('COALESCE(SUM(amount),0) as total')->where('top_up_by','!=',1)->pluck('total')->first();*/
            $total_deposite = TransactionInvoice::selectRaw('COALESCE(ROUND(SUM(hash_unit),2),0) as total')->where('in_status',1)->pluck('total')->first();
            $total_withdraw = WithdrawPending::selectRaw('COALESCE(SUM(amount),0) as total')->where('status',1)->pluck('total')->first();

            $settings = StatisticSettings::selectRaw("start_from_date,(accounts_multiplier * ".$total_users.") as total_users ,(deposit_multiplier * ".$total_deposite.") as total_deposit,(withdraw_multiplier * ".$total_withdraw.") as total_withdraw")->where('status',1)->first();

            if (!empty($settings)) {
                $DataArr = $settings->toArray();
                $date = \Carbon\Carbon::parse($settings->start_from_date);
                $now = \Carbon\Carbon::now();
                $diff = $date->diffInDays($now);
                $DataArr['running_day'] = $diff;
                $arrStatus   = Response::HTTP_OK;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Data found'; 
                return sendResponse($arrStatus,$arrCode,$arrMessage,$DataArr);
               
            } else {
                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Data not found'; 
                return sendResponse($arrStatus,$arrCode,$arrMessage,'');
            }
        }catch(Exception $e){
            $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
            $arrCode     = Response::$statusTexts[$arrStatus];
            $arrMessage  = 'Something went wrong,Please try again'; 
            return sendResponse($arrStatus,$arrCode,$arrMessage,'');
        }    
    }

}
