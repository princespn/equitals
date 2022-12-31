<?php

namespace App\Http\Controllers\userapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\userapi\CurrencyConvertorController;
use Illuminate\Http\Response as Response;
use App\Models\Currency;
use App\Models\Depositaddress;
use App\Models\Packages;
use App\Models\Invoice;
use App\Models\Topup;
use App\Models\ProjectSettings;
use App\Models\ReservedAddress;
use App\Models\TransactionInvoice;
use App\Http\Controllers\userapi\TransactionConfiController;
use App\User;
use DB;
use Config;
use Validator;
use Exception;
use Auth;

//=================generate address===================================
class MakeDepositController extends Controller {

    public function __construct(CurrencyConvertorController $currencyConvertor,TransactionConfiController $confirmTxn) {

        $this->statuscode = Config::get('constants.statuscode');
        $this->emptyArray = (object) array();
        $this->currencyConvertor = $currencyConvertor;
        $date = \Carbon\Carbon::now();
        $this->today = $date->toDateTimeString();
        $this->no_of_confirmation = ProjectSettings::where('status', 1)->pluck('no_of_confirmation')->first();
         $this->confirmTxn = $confirmTxn;
    }
    
     /**
     * Make deposit functionality (Generate address)
     *
     * @return \Illuminate\Http\Response
     */ 

    public function new_address(Request $request) {

         return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Stop by admin', []);
 
        $messsages = array('currency_code.regex' => 'Currency code should be in capital letter',);
        $rules = array(
            //'remember_token' => 'required|',
            'currency_code' => 'required',
            'product_id' => 'required',
            'hash_unit'=> 'required',
        );
        //dd($request->Input('hash_unit'));
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
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, $this->emptyArray);
        }
          // $id = User::where([['remember_token', '=', $request->input('remember_token')], ['status', '=', 'Active']])->pluck('id')->first();
        $id = Auth::user()->id;
          if (!empty($id)) {
                //User is not registered with this email id 
                $packageExist = DB::table('tbl_product')->select('min_hash','max_hash','name','currency_code','cost')->where([['id', '=',$request->input('product_id')],['status', '=', 'Active'],['user_show_status', '=', 'Active']] )->first();
               
                if(!empty($packageExist)) {

                  if($request->Input('hash_unit')>=$packageExist->min_hash && $request->Input('hash_unit')<=$packageExist->max_hash){
                    
                      if ($request->Input('hash_unit') % 1 != 0){
                          return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Deposit amount should be multiple of 1.', $this->emptyArray);
                      }

                      $currency_code=$request->input('currency_code');
                      $currency_name=$packageExist->name;
                      //echo new_address1$id; exit();
                      $CheckuserAddressExist = Invoice::where([['id', '=', $id], ['payment_mode', '=', $currency_code], ['plan_id', '=', $request->Input('product_id')],['hash_unit', '=', $request->Input('hash_unit')], ['price_unit', '=', $request->Input('hash_unit')],['in_status', '=', 0]])->first();

                       //dd($CheckuserAddressExist);

                        if (!empty($CheckuserAddressExist)) {

                            $arrData = array();
                            $arrData['address'] = $CheckuserAddressExist->address;
                            $arrData['name'] = $currency_name;
                            $arrData['network_type'] = $currency_code;
                            $arrData['price_in_usd'] = $CheckuserAddressExist->price_in_usd;
                            $arrData['price_in_currency'] = $CheckuserAddressExist->currency_price;
                            $arrData['received_amount'] = $CheckuserAddressExist->rec_amt;
                            $arrData['invoice_id']['invoice_id'] = $CheckuserAddressExist->invoice_id;
                            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Address found', $arrData);
                        } else {
                           $getSystemAddress=ReservedAddress::select('srno','address')->where('used_status','Unused')->OrderBy('srno','ASC')->first();

                           

                          if(!empty($getSystemAddress)){
                              $result = $this->insertaddress($getSystemAddress->address, $id, $currency_code, 'system', $request);
                              return $result;  
                          }else{

                            $getaddress=getnew_address($id);

                            if($getaddress['msg']=='success'){
                                $result=$this->insertaddress($getaddress['address'],$id,$currency_code,'blockchain-local',$request);
                                return $result;
                            }else if($getaddress['msg']=='failed'){
                                $coin_address = getCoinbaseCurrency_address($currency_code);
                                if ($coin_address['msg'] == 'success') {
                                  $result = $this->insertaddress($coin_address['address'], $id, $currency_code, 'coinbase', $request);
                                        return $result;
                                }else if ($coin_address['msg'] == 'failed') {   
                                  $req = array('currency' => $currency_code);
                                  // function coinpayments_api_call call  to generate address which is in helper/message.php   
                                  $req_data = coinpayments_api_call('get_callback_address', $req);
                                  if ($req_data['msg'] == 'success') {
                                      $result = $this->insertaddress($req_data['address'], $id, $currency_code, 'coinpayment', $request);
                                      return $result;
                                  } else {
                                      return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Something went wrong,Please try again', $this->emptyArray);
                                  }
                                } else {

                                    return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Something went wrong,Please try again', $this->emptyArray);
                                }
                            }   
                      }// else of address exist
                    }
                 }else{
                  return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Please enter hash rate min '.$packageExist->min_hash.' and max '.$packageExist->max_hash, $this->emptyArray);
                 } 
                }else{
                  return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid package', $this->emptyArray);
                }  
          }else{
             return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid user', $this->emptyArray);
          }      
       
    }


    public function new_address1(){
     

          $checkexist = Invoice::where('id',Auth::user()->id)->first();
          
          if(empty($checkexist)){

            $getSystemAddress=ReservedAddress::select('srno','address')->where('used_status','Unused')->OrderBy('srno','ASC')->first();

            if(!empty($getSystemAddress)){
            a:
            $random = substr(number_format(time() * rand(), 0, '', ''), 0, '10');

            $checkInvoiceExist=Invoice::where('invoice_id',$random)->first();
            if(!empty($checkInvoiceExist)){
              goto a;
             }



            $Invoicedata['hash_rate'] = 0;
            $Invoicedata['hash_unit'] = 0;
            $Invoicedata['invoice_id'] = $random;
            $Invoicedata['price_unit'] = 0;;
            $Invoicedata['price_in_usd'] = 0;;
            $Invoicedata['id'] = Auth::user()->id;
            $Invoicedata['currency_price'] = 0;
            $Invoicedata['payment_mode'] = 'BTC';
            $Invoicedata['address'] = $getSystemAddress->address;
            $Invoicedata['plan_id'] = 0;
            $Invoicedata['entry_time'] = \Carbon\Carbon::now();
            $Invoicedata['product_url'] = 'System';

            $insertAdd = Invoice::create($Invoicedata);


            ReservedAddress::where('address',$getSystemAddress->address)->update(['used_status'=>'Used']);
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Address created successfully', $getSystemAddress->address);
          
          }else{
           return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'No reserved address found', $this->emptyArray);
          }
        }else{
          $addr = $checkexist->address;
          return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Address created successfully', $addr);
        }
       // }else{
       //     return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid user', $this->emptyArray);
       // }
    }


     /**
     * Insert address
     *
     * @return \Illuminate\Http\Response
     */ 
     function insertaddress($getaddress, $id, $currency_code, $getMethodname, Request $request) {

        $random = substr(number_format(time() * rand(), 0, '', ''), 0, '15');
        $packageExist = Packages::select('cost','name','hash_rate')->where([['id', '=',$request->Input('product_id')],['status', '=', 'Active'],['user_show_status', '=', 'Active']] )->first();
        /*$CheckFirstTopupExist = Topup::where('id', '=', $id)->first();

        if (empty($CheckFirstTopupExist)) {
            $extra = ProjectSettings::where('status', '=', 1)->pluck('extra')->first();
            $costextra = $packageExist->cost + $extra;
        } else {*/
            $costextra = $request->input('hash_unit');//*$request->input('hash_unit');
        //}


        if ($currency_code == 'BCH') {
            $currencyValue = currency_convert('BCH', $costextra);
        }

        $request->request->add(['usd' => $costextra]);
        $getvalue = $this->currencyConvertor->currenyConverter($request);

        if (!empty($getvalue->original['data'])) {
            if (array_key_exists($currency_code, $getvalue->original['data'])) {
                $currencyValue = $getvalue->original['data'][$currency_code];
            }
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Something went wrong,Please try again', $this->emptyArray);
        }

        // $random = substr(number_format(time() * rand(), 0, '', ''), 0, '10');
              if($getMethodname=='system'){
                $chekAdressExist=ReservedAddress::where('address',$getaddress)->first();
                ReservedAddress::where('address',$getaddress)->update(array('used_status' => 'Used','used_time'=>now(),'user_ip'=>\Request::ip()));
                $random=$chekAdressExist->invoice_id;
              }
              $checkInvoiceExist=Invoice::where('invoice_id',$random)->first();
              if(!empty($checkInvoiceExist)){
                $random = substr(number_format(time() * rand(), 0, '', ''), 0, '15');
              }

              
       $Invoicedata = array();
        $Invoicedata['invoice_id'] = $random;
        
        $Invoicedata['hash_rate'] = $packageExist->hash_rate;
        $Invoicedata['hash_unit'] = $request->input('hash_unit');
        $Invoicedata['price_unit'] = $request->input('hash_unit');
        $Invoicedata['price_in_usd'] = $request->input('hash_unit');
        $Invoicedata['id'] = $id;
        $Invoicedata['currency_price'] = round($currencyValue, 7);
        $Invoicedata['payment_mode'] = $currency_code;
        $Invoicedata['address'] = $getaddress;
        $Invoicedata['plan_id'] = $request->Input('product_id');
        $Invoicedata['entry_time'] = $this->today;
        $Invoicedata['product_url'] = $getMethodname;



        $insertAdd = Invoice::create($Invoicedata);
        if (!empty($insertAdd)) {
            $arrData['address'] = $getaddress;
            $arrData['name'] = $packageExist->name;

            $request->request->add(['usd' => $costextra]);
            $valuewithExtra = $this->currencyConvertor->currenyConverter($request);

            if (!empty($valuewithExtra->original['data'])) {

                if (array_key_exists($currency_code, $valuewithExtra->original['data'])) {
                    $currencyValuwithextra = $valuewithExtra->original['data'][$currency_code];
                } else {
                    $currencyValuwithextra = 0;
                }
            } else {
                $currencyValuwithextra = 0;
            }


            $arrData['network_type'] = $currency_code;
            $arrData['price_in_usd'] = $request->input('hash_unit');
            $arrData['price_in_currency'] = $currencyValuwithextra;
            $arrData['received_amount'] = 0;
             $arrData['invoice_id']['invoice_id'] = $random;


            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Address found', $arrData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Address not found', $this->emptyArray);
        }
    }




  public function insertcoinaddress($getaddress, $id, $currency_code, $getMethodname, Request $request,$req_data=array()) {

    $random = substr(number_format(time() * rand(), 0, '', ''), 0, '15');
    $packageExist = Packages::select('cost','name','hash_rate')->where([['id', '=',1],['status', '=', 'Active'],['user_show_status', '=', 'Active']] )->first();

    $costextra = $request->input('hash_unit');

    /*$currencyValue = currency_convert($currency_code, $costextra);

    $request->request->add(['usd' => $costextra]);
    $getvalue = $this->currencyConvertor->currenyConverter($request);

    if (!empty($getvalue->original['data'])) {
      if (array_key_exists($currency_code, $getvalue->original['data'])) {
        $currencyValue = $getvalue->original['data'][$currency_code];
      }
    } else {
      return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Something went wrong,Please try again', $this->emptyArray);
    }*/

    

    $Invoicedata = array();
    $Invoicedata['invoice_id'] = substr(number_format(time() * rand(), 0, '', ''), 0, '15');
    $Invoicedata['hash_rate'] = $packageExist->hash_rate;
    $Invoicedata['hash_unit'] = $request->input('hash_unit');
    $Invoicedata['price_unit'] = $packageExist->cost;
    $Invoicedata['price_in_usd'] = $costextra;
    $Invoicedata['id'] = $id;
    $Invoicedata['currency_price'] = round($req_data['amount'], 7);
    $Invoicedata['payment_mode'] = $currency_code;
    $Invoicedata['address'] = $getaddress;
    $Invoicedata['plan_id'] = $request->Input('product_id');
    $Invoicedata['entry_time'] = $this->today;
    $Invoicedata['product_url'] = $getMethodname;
    $Invoicedata['trans_id'] = $req_data['txn_id'];
    $Invoicedata['status_url'] = $req_data['checkout_url'];
    $Invoicedata['ip_address'] = $_SERVER['REMOTE_ADDR'];

    $insertAdd = TransactionInvoice::create($Invoicedata);


    if (!empty($insertAdd)) {
      $arrData['address'] = $getaddress;
      $arrData['invoice_id'] = $Invoicedata['invoice_id'];
      $arrData['name'] = $packageExist->name;

      $request->request->add(['usd' => $costextra]);
      /*$valuewithExtra = $this->currencyConvertor->currenyConverter($request);

      if (!empty($valuewithExtra->original['data'])) {

        if (array_key_exists($currency_code, $valuewithExtra->original['data'])) {
          $currencyValuwithextra = $valuewithExtra->original['data'][$currency_code];
        } else {
          $currencyValuwithextra = 0;
        }
      } else {
        $currencyValuwithextra = 0;
      }*/

    $arrData['payment_by']        = $getMethodname;  
    $arrData['network_type'] = $currency_code;
    $arrData['price_in_usd'] = $costextra;
    $arrData['price_in_currency'] = $req_data['amount'];
    $arrData['received_amount'] = 0;
    $arrData['exists'] = 0;
    $arrData['status_url'] = $req_data['checkout_url'];
    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Address found', $arrData);
  } else {
    return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Address not found', $this->emptyArray);
  }
}

 /*   function insertaddress($getaddress, $id, $currency_code, $getMethodname, Request $request){
       try{
              $random = substr(number_format(time() * rand(), 0, '', ''), 0, '15');
              $packageExist = Packages::where([['id', '=', $request->Input('product_id')]])->first();
              $CheckFirstTopupExist = Topup::where('id', '=', $id)->first();

              if (empty($CheckFirstTopupExist)) {
                  $extra = ProjectSettings::where('status', '=', 1)->pluck('extra')->first();
                  $costextra = $packageExist->cost + $extra;
              } else {
                  $costextra = $packageExist->cost;
              }

              if ($currency_code == 'BCH') {
                  $currencyValue = currency_convert('BCH', $costextra);
              }

              $request->request->add(['usd' => $costextra]);
              $getvalue = $this->currencyConvertor->currenyConverter($request);

              if (!empty($getvalue->original['data'])) {
                  if (array_key_exists($currency_code, $getvalue->original['data'])) {
                      $currencyValue = $getvalue->original['data'][$currency_code];
                  }
              } else {
                   $arrStatus   = Response::HTTP_NOT_FOUND;
                   $arrCode     = Response::$statusTexts[$arrStatus];
                   $arrMessage  = 'Something went wrong,Please try again'; 
                   return sendResponse($arrStatus,$arrCode,$arrMessage,'');
              }

              $Invoicedata = array();
              $Invoicedata['invoice_id'] = $random;
              $Invoicedata['price_in_usd'] = $costextra;
              $Invoicedata['id'] = $id;
              $Invoicedata['currency_price'] = round($currencyValue, 7);
              $Invoicedata['payment_mode'] = $currency_code;
              $Invoicedata['address'] = $getaddress;
              $Invoicedata['plan_id'] = $request->Input('product_id');
              $Invoicedata['entry_time'] = $this->today;
              $Invoicedata['product_url'] = $getMethodname;

              $insertAdd = Invoice::create($Invoicedata);
              if (!empty($insertAdd)) {
                  $arrData['address'] = $getaddress;
                  if ($currency_code == 'BTC') {
                      $arrData['name'] = 'Bitcoin';
                  } else if ($currency_code == 'ETH') {
                      $arrData['name'] = 'Ethereum';
                  } else if ($currency_code == 'BCH') {
                      $arrData['name'] = 'Bitcoin hash';
                  }


                  $request->request->add(['usd' => $costextra]);
                  $valuewithExtra = $this->currencyConvertor->currenyConverter($request);

                  if (!empty($valuewithExtra->original['data'])) {

                      if (array_key_exists($currency_code, $valuewithExtra->original['data'])) {
                          $currencyValuwithextra = $valuewithExtra->original['data'][$currency_code];
                      } else {
                          $currencyValuwithextra = 0;
                      }
                  } else {
                      $currencyValuwithextra = 0;
                  }


                  $arrData['network_type'] = $currency_code;
                  $arrData['price_in_usd'] = $costextra;
                  $arrData['price_in_currency'] = $currencyValuwithextra;
                  $arrData['received_amount'] = 0;
                  
                  $arrStatus   = Response::HTTP_OK;
                  $arrCode     = Response::$statusTexts[$arrStatus];
                  $arrMessage  = 'Address found'; 
                  return sendResponse($arrStatus,$arrCode,$arrMessage,$arrData);
                  
              } else {
                   $arrStatus   = Response::HTTP_NOT_FOUND;
                   $arrCode     = Response::$statusTexts[$arrStatus];
                   $arrMessage  = 'Address not found'; 
                   return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                 
              }
        }catch(Exception $e){
                   
             $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
             $arrCode     = Response::$statusTexts[$arrStatus];
             $arrMessage  = 'Something went wrong,Please try again'; 
             return sendResponse($arrStatus,$arrCode,$arrMessage,'');
        }  

    }*/

      public function fetchAddressBalance(Request $request){
      $arrInput = $request->all();
      $data=[];
      $rules = array('invoice_id'  => 'required');
      // run the validation rules on the inputs from the form
      $validator = Validator::make($arrInput, $rules);
      // if the validator fails, redirect back to the form
      if ($validator->fails()) {
          $message = $validator->errors();
            $arrStatus   = Response::HTTP_NOT_FOUND;
        $arrCode     = Response::$statusTexts[$arrStatus];
        $strMessage  = 'Address is Required'; 
        return sendResponse($arrStatus,$arrCode,$strMessage,'');
      }
      //->where('in_status','0')
      $invoice = Invoice::where('invoice_id', $arrInput['invoice_id'])->first();
      if(empty($invoice)){
            $arrStatus   = Response::HTTP_NOT_FOUND;
        $arrCode     = Response::$statusTexts[$arrStatus];
        $strMessage  = 'Invalid Order Id '; 
        return sendResponse($arrStatus,$arrCode,$strMessage,'');
      }
      $strAddress = $invoice->address;
      //dd()
      $url = "https://blockchain.info/balance?active=".$strAddress;
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL, $url);
      $response = curl_exec($ch);  
      $response = json_decode($response, true);
      // dd($response);
      $arrResp = [];

      if((isset($response[$strAddress])) && (!empty($response[$strAddress]))){
      
        $arrResp = $response[$strAddress];

        $arrResp['total_received'] = ($arrResp['total_received'] > 0 ) ? ($arrResp['total_received']/ 100000000) : 0;
        $arrStatus   = Response::HTTP_OK;
        $arrCode     = Response::$statusTexts[$arrStatus];
        $strMessage  = 'Data found successfully'; 
        $user = Auth::user();
        
        if($arrResp['total_received'] >= $invoice->currency_price){
            $status = 1;
            $data['status']=1;

          $whatsappMsg = "<span class='text-success'>Order Amount: ".$invoice->currency_price." BTC.\nPayment received ".$arrResp['total_received']." BTC against your order id .Order will be confirmed after 2 confirmations.\n </span>";
        } else {
            //$status = 2; 

            $data['status']=2;
          $whatsappMsg = "<span class='text-danger'>Order Amount: ".$invoice->currency_price." BTC.\nPayment received ".$arrResp['total_received']." BTC against your order id .Please deposit remaining amount.\n</span>";
        }

           $data['rec'] = $arrResp['total_received'];
     //   Invoice::where('invoice_id',$request->invoice_id)->limit(1)->update(['rec_amt'=>$arrResp['total_received']]);
      /*  $countrycode =getCountryCode($user->country_whatsapp);
        $mobile = $user->whatsapp_no;
        sendWhatsappMsg($countrycode,$mobile,$whatsappMsg); */

        return sendResponse($arrStatus,$arrCode,$whatsappMsg,$data);
      }else {
        $arrStatus   = Response::HTTP_NOT_FOUND;
        $arrCode     = Response::$statusTexts[$arrStatus];
        $strMessage  = 'Data not found'; 
        return sendResponse($arrStatus,$arrCode,$strMessage,'');
      }
    }


    public function ConfirmDeposit(Request $request){
         $UserInvoice = Invoice::orderBy('entry_time','desc')
        ->where('invoice_id',$request->invoice_id)
        ->first();
        

          if($UserInvoice->in_status == 0){

            $req1=new request();
            $req1['address'] = $UserInvoice->address;
            $req1['functcall'] = 1;
            $res = $this->confirmTxn->transactionconfirmation($req1);
            //dd($res);
            $arr['ret'] = 0;
            $arrStatus   = Response::HTTP_OK;
            $arrCode     = Response::$statusTexts[$arrStatus];
            $strMessage  = 'status'; 
            return sendResponse($arrStatus,$arrCode,$strMessage,$arr);
          }else {
            $arr['ret'] = 1;
            $arrStatus   = Response::HTTP_OK;
            $arrCode     = Response::$statusTexts[$arrStatus];
            $strMessage  = 'status'; 
            return sendResponse($arrStatus,$arrCode,$strMessage,$arr);
          }
    }



         /**
     * Make deposit functionality (Generate address)
     *
     * @return \Illuminate\Http\Response
     */ 

    // public function create_transaction(Request $request) {

 
    //     /*$intCode = Response::HTTP_NOT_FOUND;
    //     $strStatus = Response::$statusTexts[$intCode];
    //     $strMessage = 'Adding Funds are stopped till 12th September';
    //     return sendResponse($intCode, $strStatus, $strMessage, array());*/

    //     $messsages = array('currency_code.regex' => 'Currency code should be in capital letter',
    //                         "hash_unit.required" => "Amount is required",
    //                        "hash_unit.min" => "Amount must be equal or greater than 20");
    //     $rules = array(
    //         //'remember_token' => 'required|',
    //         'currency_code' => 'required',
    //         //'product_id' => 'required',
    //         'hash_unit'=> 'required|numeric|min:20',
    //     );
    //     $validator = Validator::make($request->all(), $rules, $messsages);
    //     if ($validator->fails()) {
    //         $message = $validator->errors();
    //         $err = '';
    //         foreach ($message->all() as $error) {
    //             if (count($message->all()) > 1) {
    //                 $err = $err . ' ' . $error;
    //             } else {
    //                 $err = $error;
    //             }
    //         }
    //         return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, $this->emptyArray);
    //     }
    //     if($request->hash_unit <= 0)
    //     {
    //       return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], "Amount Must be greater than zero","");
    //     }
    //       // $id = User::where([['remember_token', '=', $request->input('remember_token')], ['status', '=', 'Active']])->pluck('id')->first();
    //     $user= Auth::user();
    //     $id = $user->id;
    //     if (!empty($id)) {
    //         //User is not registered with this email id 
    //         $packageExist = Packages::select('min_hash','max_hash','name','currency_code','cost')->where([['id', '=',1],['status', '=', 'Active'],['user_show_status', '=', 'Active']] )->first();
           
    //         if(!empty($packageExist)) {

    //           /*if($request->hash_unit >= $packageExist->min_hash && $request->hash_unit <= $packageExist->max_hash){*/
                
    //               if ($request->Input('hash_unit') % 1 != 0){
    //                   return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Deposit amount should be multiple of 1.', $this->emptyArray);
    //               }

    //               $currency_code=$request->input('currency_code');
    //               $currency_name=$packageExist->name;
    //               //echo $id; exit();
    //               $CheckuserAddressExist = TransactionInvoice::where([['id', '=', $id], ['payment_mode', '=', $currency_code], ['plan_id', '=', $request->Input('product_id')],['hash_unit', '=', $request->Input('hash_unit')], ['price_unit', '=', $packageExist->cost],['in_status', '=', 0]])->first();

    //             if (!empty($CheckuserAddressExist)) {
    //                 $arrData = array();
    //                 $arrData['payment_by']        = $CheckuserAddressExist->product_url;
    //                 $arrData['invoice_id'] = $CheckuserAddressExist->invoice_id;
    //                 $arrData['address'] = $CheckuserAddressExist->address;
    //                 $arrData['name'] = $currency_name;
    //                 $arrData['exists'] = 1;
    //                 $arrData['network_type'] = $currency_code;
    //                 $arrData['price_in_usd'] = $CheckuserAddressExist->price_in_usd;
    //                 $arrData['price_in_currency'] = $CheckuserAddressExist->currency_price;
    //                 $arrData['received_amount'] = $CheckuserAddressExist->rec_amt;
    //                 $arrData['status_url'] = $CheckuserAddressExist->status_url;
    //                 return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Address found', $arrData);
    //             } else {
    //                 $payment_by = '';
    //                 $currency = Currency::select('coinpayment_status','node_api_status','node_currency_code')->where('currency_code',$currency_code)->first();
                     
    //                 if($currency->coinpayment_status == 1){
    //                     $payment_by = 'coinpayment';
    //                     $req = array(
    //                       'amount' => $request->hash_unit,
    //                       'currency1' => 'USD',
    //                       'currency2' => $currency_code,
    //                       'buyer_email'=>$user->email,
    //                     );
    //                     // function coinpayments_api_call call  to generate address which is in helper/message.php   
    //                     // dd(122);
    //                     $req_data = coinpayments_api_call('create_transaction', $req);
    //                 }elseif ($currency->node_api_status == 1){
    //                     $payment_by = 'node_api';
    //                     $currency_code = $currency->node_currency_code;
    //                     $req = array(
    //                       'amount' => (int)$request->hash_unit,
    //                       'currency' => $currency_code,
    //                       'email' => $user->email
    //                       /*'cryptoAmount' =>false*/
    //                     );
    //                     // dd('133');
    //                     $req_data = node_api_call('createInvoice', $req);


    //                 }
    //                 if ($req_data['msg'] == 'success') {
    //                     $result = $this->insertcoinaddress($req_data['address'], $id, $currency_code, $payment_by, $request,$req_data["data"]);
    //                     return $result;
    //                 } else {
    //                     return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Something went wrong,Please try again', $this->emptyArray);
    //                 }
    //             } 
    //             /*} else {
    //                     return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Amount must be between '.$packageExist->min_hash.' and '.$packageExist->max_hash, $this->emptyArray);
    //                 }*/
    //         }
    //     }else{
    //           return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid package', $this->emptyArray);
    //     }    
    // }


         public function create_transaction(Request $request) {

           $arrInput = $request->all();
          // dd($arrInput);
        /*$intCode = Response::HTTP_NOT_FOUND;
        $strStatus = Response::$statusTexts[$intCode];
        $strMessage = 'Adding Funds are stopped till 12th September';
        return sendResponse($intCode, $strStatus, $strMessage, array());*/

        $messsages = array('currency_code.regex' => 'Currency code should be in capital letter',
                            "hash_unit.required" => "Amount is required",
                           "hash_unit.min" => "Amount must be equal or greater than 20");
        $rules = array(
            //'remember_token' => 'required|',
            'currency_code' => 'required',
            //'product_id' => 'required',
            'hash_unit'=> 'required|numeric|min:20',
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
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, $this->emptyArray);
        }
        if($request->hash_unit <= 0)
        {
          return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], "Amount Must be greater than zero","");
        }
          // $id = User::where([['remember_token', '=', $request->input('remember_token')], ['status', '=', 'Active']])->pluck('id')->first();
        $user= Auth::user();
        $id = $user->id;
        if (!empty($id)) {
            //User is not registered with this email id 
            $packageExist = Packages::select('min_hash','max_hash','name','currency_code','cost')->where([['id', '=',1],['status', '=', 'Active'],['user_show_status', '=', 'Active']] )->first();
           
            if(!empty($packageExist)) {

              /*if($request->hash_unit >= $packageExist->min_hash && $request->hash_unit <= $packageExist->max_hash){*/
                
                  if ($request->Input('hash_unit') % 1 != 0){
                      return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Deposit amount should be multiple of 1.', $this->emptyArray);
                  }

                  $currency_code=$request->input('currency_code');
                  $currency_name=$packageExist->name;
                  //echo $id; exit();
                  $CheckuserAddressExist = TransactionInvoice::where([['id', '=', $id], ['payment_mode', '=', $currency_code], ['plan_id', '=', $request->Input('product_id')],['hash_unit', '=', $request->Input('hash_unit')], ['price_unit', '=', $packageExist->cost],['in_status', '=', 0]])->first();

                if (!empty($CheckuserAddressExist)) {
                    $arrData = array();
                    $arrData['payment_by']        = $CheckuserAddressExist->product_url;
                    $arrData['invoice_id'] = $CheckuserAddressExist->invoice_id;
                    $arrData['address'] = $CheckuserAddressExist->address;
                    $arrData['name'] = $currency_name;
                    $arrData['exists'] = 1;
                    $arrData['network_type'] = $currency_code;
                    $arrData['price_in_usd'] = $CheckuserAddressExist->price_in_usd;
                    $arrData['price_in_currency'] = $CheckuserAddressExist->currency_price;
                    $arrData['received_amount'] = $CheckuserAddressExist->rec_amt;
                    $arrData['status_url'] = $CheckuserAddressExist->status_url;
                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Address found', $arrData);
                } else {
                    $payment_by = '';
                    $currency = Currency::select('coinpayment_status','node_api_status','node_currency_code')->where('currency_code',$currency_code)->first();
                     
                    if($currency->coinpayment_status == 1){
                        $payment_by = 'coinpayment';
                        $req = array(
                          'amount' => $request->hash_unit,
                          'currency1' => 'USD',
                          'currency2' => $currency_code,
                          'buyer_email'=>$user->email,
                        );
                        // function coinpayments_api_call call  to generate address which is in helper/message.php   
                        // dd(122);
                        $req_data = coinpayments_api_call('create_transaction', $req);
                    }elseif ($currency->node_api_status == 1){
                        $payment_by = 'node_api';
                        $currency_code = $currency->node_currency_code;
                        $req = array(
                          'amount' => (int)$request->hash_unit,
                          'currency' => $currency_code,
                          'email' => $user->email
                          /*'cryptoAmount' =>false*/
                        );
                        // dd('133');
                        $req_data = node_api_call('createInvoice', $req);

                    }
                    if ($req_data['msg'] == 'success') {
                        $result = $this->insertcoinaddress($req_data['address'], $id, $currency_code, $payment_by, $request,$req_data["data"]);
                        return $result;
                    } else {
                        return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Something went wrong,Please try again', $this->emptyArray);
                    }
                } 
                /*} else {
                        return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Amount must be between '.$packageExist->min_hash.' and '.$packageExist->max_hash, $this->emptyArray);
                    }*/
            }
        }else{
              return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid package', $this->emptyArray);
        }    
    }



     public function getFundInvoiceTransaction(Request $request){        

        $rules = array(
            'invoice_id' => 'required',
        );
        $validator = Validator::make($request->all(), $rules, []);
        if ($validator->fails()) {
            $message = $validator->errors();
            $err     = '';
            foreach ($message->all() as $error) {
                if (count($message->all()) > 1) {
                    $err = $err.' '.$error;
                } else {
                    $err = $error;
                }
            }
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, $this->emptyArray);
        }
        $checkExist=TransactionInvoice::select('trans_id')->where('invoice_id',$request->invoice_id)->first();
        if (!empty($checkExist)) {
            $txn=array();
            $txn['id'] = $checkExist->trans_id;

            $transData = get_node_trans_status('publicInvoiceStatus', $txn);
            
            if ($transData['data']['status'] == 'OK' && $transData['data']['code'] == 200) {
                $arrData = $transData['data']['data'];
                if ($arrData['paymentStatus'] == "EXPIRED") {
                    $expired = array();
                    $expired['paymentStatus'] = "EXPIRED";
                    TransactionInvoice::where('invoice_id',$request->invoice_id)->update(['in_status'=>2]);
                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], "Data found",$expired);
                }elseif ($arrData['paymentStatus'] == "PAID") {
                    $paid = array();
                    $paid['paymentStatus'] = "PAID";
                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], "Data found",$paid);
                }elseif($arrData['paymentStatus'] == "PENDING"){
                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], "Data found",$arrData);
                }
            }else{

                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, $this->emptyArray);
            }
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, $this->emptyArray);
        }

    }

     public function getRefundData(Request $request){

        $rules = array(
            'transaction_id' => 'required',
        );
        $validator = Validator::make($request->all(), $rules, []);
        if ($validator->fails()) {
            $message = $validator->errors();
            $err     = '';
            foreach ($message->all() as $error) {
                if (count($message->all()) > 1) {
                    $err = $err.' '.$error;
                } else {
                    $err = $error;
                }
            }
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, $this->emptyArray);
        }
        $transact = explode('-', $request->transaction_id);
        $checkExist=TransactionInvoice::select('trans_id','invoice_id','id','refund_status')->where('trans_id',$transact[0])->first();
        $invoice_type ="Fund";

        if (!empty($checkExist) || $checkExist != null) {
            $txn=array();
            $txn['transactionId'] = $request->transaction_id;

            $transData = get_node_refund_data('getRefundInfo', $txn);
            if(empty($transData) || $transData == null || @$transData['msg'] == "failed"){

                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], "Transaction Not found", $this->emptyArray);
            }
            if ($transData['data']['status'] == 'OK' && $transData['data']['code'] == 200) {
                $arrData = $transData['data']['data'];
                $status = (isset($arrData['status']))?$arrData['status']:((isset($arrData['refundStatus']))?$arrData['refundStatus']:'');
                if ($status == "EXPIRED") {
                    $expired = array();
                    $expired['status'] = "EXPIRED";
                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], "Data found",$expired);
                }elseif ($status == "PAID") {
                    $paid = array();
                    $paid['status'] = "PAID";
                    $arrData['status'] = $status;
                    if ($checkExist->refund_status == 0) {

                        $insertRefund = array();
                        $insertRefund['user_id'] = $checkExist->id;
                        $insertRefund['payment_id'] = $checkExist->trans_id;
                        $insertRefund['invoice_id'] = $checkExist->invoice_id;
                        $insertRefund['amount'] = $arrData['amount'];
                        $insertRefund['address'] = $arrData['address'];
                        $insertRefund['currency'] = $arrData['currency'];
                        $insertRefund['invoice_type'] = $invoice_type;
                        $insertRefund['entry_time'] = \Carbon\Carbon::now()->toDateTimeString();
                        $insert = DB::table("tbl_invoice_refund")->insert($insertRefund);

                        $updtTp =TransactionInvoice::where('trans_id',$transact[0])->update(['refund_status'=>1]);
                    }   
                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], "Data found",$arrData);
                }elseif($status == "PENDING"){
                    $arrData['status'] = $status;
                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], "Data found",$arrData);
                }else{
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], "Transaction not found", $this->emptyArray);
                }
            }else{

                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], "Transaction not found", $this->emptyArray);
            }
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], "Transaction not found", $this->emptyArray);
        }

    }

    public function setRefundData(Request $request){        

        $rules = array(
            'address' => 'required',
            'currency' => 'required',
            'transaction_id' => 'required'
        );
        $validator = Validator::make($request->all(), $rules, []);
        if ($validator->fails()) {
            $message = $validator->errors();
            $err     = '';
            foreach ($message->all() as $error) {
                if (count($message->all()) > 1) {
                    $err = $err.' '.$error;
                } else {
                    $err = $error;
                }
            }
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, $this->emptyArray);
        }
        $addr = array();
        $addr['address'] = $request->address;
        $addr['currency'] = $request->currency;
        $addrData = validate_Address('addressValidate', $addr);

         if(empty($addrData) || $addrData == null || @$addrData['msg'] == "failed"){
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], "Please enter valid ".$request->currency." address", $this->emptyArray);
        }
        $transact = explode('-', $request->transaction_id);
        $checkExist=TransactionInvoice::select('trans_id','invoice_id','id')->where('trans_id',$transact[0])->first();
        $invoice_type = "Fund";
        if (!empty($checkExist) || $checkExist != null) {
            $txn=array();
            $txn['transactionId'] = $request->transaction_id;
            $txn['toAddress'] = $request->address;

            $transData = set_node_refund_data('setRefund', $txn);
            if(empty($transData) || $transData == null || @$transData['msg'] == "failed"){
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], "Transaction Not found", $this->emptyArray);
            }
            if ($transData['data']['status'] == 'OK' && $transData['data']['code'] == 200) {
                $arrData = $transData['data']['data'];
                $status = (isset($arrData['status']))?$arrData['status']:((isset($arrData['refundStatus']))?$arrData['refundStatus']:'');
                if ($status == "EXPIRED") {
                    $expired = array();
                    $expired['status'] = "EXPIRED";
                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], "Data found",$expired);
                }elseif ($status == "PAID") {
                    $paid = array();
                    $paid['status'] = "PAID";
                    $arrData['status'] = $status;

                    $insertRefund = array();
                    $insertRefund['user_id'] = $checkExist->id;
                    $insertRefund['payment_id'] = $checkExist->trans_id;
                    $insertRefund['invoice_id'] = $checkExist->invoice_id;
                    $insertRefund['amount'] = $arrData['amount'];
                    $insertRefund['address'] = $arrData['address'];
                    $insertRefund['currency'] = $arrData['currency'];
                    $insertRefund['invoice_type'] = $invoice_type;
                    $insertRefund['entry_time'] = \Carbon\Carbon::now()->toDateTimeString();
                    $insert = DB::table("tbl_invoice_refund")->insert($insertRefund);

                    $updtTp =TransactionInvoice::where('trans_id',$transact[0])->update(['refund_status'=>1]);

                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], "Data found",$arrData);
                }elseif($status == "PENDING"){
                    $arrData['status'] = $status;
                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], "Data found",$arrData);
                }else{
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], "Transaction not found", $this->emptyArray);
                }
            }else{

                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], "Transaction not found", $this->emptyArray);
            }
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], "Transaction not found", $this->emptyArray);
        }

    }




  

}
