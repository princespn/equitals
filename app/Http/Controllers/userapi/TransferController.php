<?php

namespace App\Http\Controllers\userapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response as Response;
use App\Http\Controllers\userapi\SettingsController;
use App\Http\Controllers\userapi\IcoPhasesController;
use Illuminate\Http\Request;
use App\Models\Dashboard;
use App\Models\AllTransaction;
use App\Models\Activitynotification;
use App\Models\Wallet;
use App\Models\TodayDetails;
use App\Models\FundTransfer;
use App\Models\DexToPurchaseFundTransfer;
use App\Models\BalanceTransfer;
use App\Models\PurchaseBalanceTransfer;
use App\Models\ProjectSettings;
use App\Models\WorkingToPurchaseTransfer;
use App\Models\WithdrawSettings;
use App\User;
use Exception;
use DB;
use Config;
use Validator;
use Auth;

class TransferController extends Controller
{

    public function __construct(SettingsController $projectsetting, IcoPhasesController $IcoPhases)
    {
        $this->projectsettings = $projectsetting;
        $this->IcoPhases = $IcoPhases;
        $this->emptyArray = (object) array();
        $date = \Carbon\Carbon::now();
        $this->today = $date->toDateTimeString();
    }

    /**
     * Get wallet list
     *
     * @return \Illuminate\Http\Response
     */
    public function walletlist()
    {
        try {
            $getWallet = Wallet::where('status', 'Active')->get();
            if (empty($getWallet) && count($getWallet) > 0) {

                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Wallet data not found';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            } else {
                $arrStatus   = Response::HTTP_OK;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Wallet data found';
                return sendResponse($arrStatus, $arrCode, $arrMessage, $getWallet);
            }
        } catch (Exception $e) {

            $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
            $arrCode     = Response::$statusTexts[$arrStatus];
            $arrMessage  = 'Something went wrong,Please try again';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
    }

    /**
     * Transfer income to wallet 
     *
     * @return \Illuminate\Http\Response
     */
    public function transferTowallet(Request $request)
    {
        try {
            $rules = array(

                'level_income_balance' => 'required|',
                'direct_income_balance' => 'required|',
                'roi_balance' => 'required|',
                'binary_income_balance' => 'required|',
                'leadership_income_balance' => 'required|',
                'wallet_id' => 'required|',
            );
            $validator = checkvalidation($request->all(), $rules, '');
            if (!empty($validator)) {
                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = $validator;
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }

            $users = User::join('tbl_dashboard', 'tbl_dashboard.id', '=', 'tbl_users.id')->where([['tbl_users.id', '=', Auth::user()->id], ['tbl_users.status', '=', 'Active']])->first();
            if (!empty($users)) {
                $WalletExist = Wallet::where([['srno', '=', $request->Input('wallet_id')]])->first();
                if (!empty($WalletExist)) {
                    $level_bal = $users->level_income - $users->level_income_withdraw;
                    $roi_bal = $users->roi_income - $users->roi_income_withdraw;
                    $binary_bal = $users->binary_income - $users->binary_income_withdraw;
                    $direct_bal = $users->direct_income - $users->direct_income_withdraw;
                    $leadership_bal = $users->leadership_income - $users->leadership_income_withdraw;

                    $total_blce = $request->input('level_income_balance') + $request->input('roi_balance') + $request->input('binary_income_balance') + $request->input('direct_income_balance') + $request->input('leadership_income_balance');

                    if ($total_blce <= 0) {
                        $arrMessage  = 'Balance should be greater than 0';
                    } else if ($level_bal < $request->input('level_income_balance')) {
                        $arrMessage  = 'You have insufficient level income balance';
                    } else if ($roi_bal < $request->input('roi_balance')) {
                        $arrMessage  = 'You have insufficient roi income balance';
                    } else if ($binary_bal < $request->input('binary_income_balance')) {
                        $arrMessage  = 'You have insufficient binary income balance';
                    } else if ($direct_bal < $request->input('direct_income_balance')) {
                        $arrMessage  = 'You have insufficient direct income balance';
                    } else if ($leadership_bal < $request->input('leadership_income_balance')) {
                        $arrMessage  = 'You have insufficient leadership income balance';
                    }

                    if (!empty($arrMessage)) {

                        $arrStatus   = Response::HTTP_NOT_FOUND;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = $arrMessage;
                        return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    }

                    $updateCoinData = array();
                    $tempData = [];
                    //---------update level income wd
                    if ($request->input('level_income_balance') != 0) {
                        $updateCoinData['level_income_withdraw'] = round(($users->level_income_withdraw + $request->input('level_income_balance')), 7);
                        $tempData['level_income'] = $request->input('level_income_balance');
                    }
                    //---------update roi income wd
                    if ($request->input('roi_balance') != 0) {
                        $updateCoinData['roi_income_withdraw'] = round(($users->roi_income_withdraw + $request->input('roi_balance')), 7);
                        $tempData['roi_income'] = $request->input('roi_balance');
                    }
                    //---------update binary income wd
                    if ($request->input('binary_income_balance') != 0) {
                        $updateCoinData['binary_income_withdraw'] = round(($users->binary_income_withdraw + $request->input('binary_income_balance')), 7);
                        $tempData['binary_income'] = $request->input('binary_income_balance');
                    }
                    //---------update direct income wd
                    if ($request->input('direct_income_balance') != 0) {
                        $updateCoinData['direct_income_withdraw'] = round(($users->direct_income_withdraw + $request->input('direct_income_balance')), 7);
                        $tempData['direct_income'] = $request->input('direct_income_balance');
                    }
                    //---------update leadership income wd
                    if ($request->input('leadership_income_balance') != 0) {
                        $updateCoinData['leadership_income_withdraw'] = round(($users->leadership_income_withdraw + $request->input('leadership_income_balance')), 7);
                        $tempData['leadership_income'] = $request->input('leadership_income_balance');
                    }
                    //---------update total amount transfer

                    $updateCoinData = Dashboard::where('id', $users->id)->limit(1)->update($updateCoinData);

                    $updatWallet = Dashboard::where('id', $users->id)->update(array('' . $WalletExist->setting_name . '' => DB::raw('' . $WalletExist->setting_name . '+ ' . $total_blce . '')));

                    $getCoin = $this->projectsettings->getProjectDetails();


                    $Trandata1 = []; //insert in transaction
                    foreach ($tempData as $key => $value) {
                        $balance = AllTransaction::where('id', '=', $users->id)->orderBy('srno', 'desc')->pluck('balance')->first();

                        array_push($Trandata1, [
                            'id' => $users->id,
                            'network_type' => $getCoin->original["data"]["coin_name"],
                            'refference' => $users->id,
                            'debit' => $value,
                            'type' => $key,
                            'status' => 1,
                            'balance' => $balance - $value,
                            'remarks' => '$' . $value . ' debited from  ' . $key . 'for ' . $WalletExist->name . ' wallet ',
                            'entry_time' => $this->today
                        ]);
                    }

                    $TransactionDta1 = AllTransaction::insert($Trandata1);
                    $balance1 = AllTransaction::where('id', '=', $users->id)->orderBy('srno', 'desc')->pluck('balance')->first();
                    $Trandata = array();      // insert in transaction 
                    $Trandata['id'] = $users->id;
                    $Trandata['network_type'] = $getCoin->original["data"]["coin_name"];
                    $Trandata['refference'] = $users->id;
                    $Trandata['credit'] = $total_blce;
                    $Trandata['balance'] = $balance1 + $total_blce;
                    $Trandata['type'] = $WalletExist->name;
                    $Trandata['status'] = 1;
                    $Trandata['entry_time'] = $this->today;
                    $Trandataww = '';
                    $transkey = '';
                    foreach ($tempData as $key => $value) {
                        $Trandataww .= $key . '  $' . $value . ',';
                        $transkey .= $key . ',';
                    }
                    $Trandata['type'] = $transkey;
                    $Trandata['remarks'] = $Trandataww . ' credited to ' . $WalletExist->name . ' wallet';

                    $TransactionDta = AllTransaction::create($Trandata);

                    $actdata = array();      // insert in transaction 
                    $actdata['id'] = $users->id;
                    $actdata['message'] = 'Wallet ' . $WalletExist->name . ' Credit transaction from  ' . $Trandataww;
                    $actdata['status'] = 1;
                    $actdata['entry_time'] = $this->today;
                    $actDta = Activitynotification::create($actdata);

                    $arrStatus   = Response::HTTP_OK;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = 'Amount transfer to wallet successfully';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                } else {
                    $arrStatus   = Response::HTTP_NOT_FOUND;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = 'Wallet is not exist';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                }
            } else {

                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Invalid user';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }
        } catch (Exception $e) {

            $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
            $arrCode     = Response::$statusTexts[$arrStatus];
            $arrMessage  = 'Something went wrong,Please try again';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
    }

    /**
     * Fund transfer
     *
     * @return \Illuminate\Http\Response
     */
    public function UserToUserTransfer(Request $request)
    {

        try {
            $rules = array(
                'amount' => 'required',
                'to_user_id' => 'required',
            );
            $validator = checkvalidation($request->all(), $rules, '');
            if (!empty($validator)) {
                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = $validator;
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }

            //from user
            $users = User::join('tbl_dashboard', 'tbl_dashboard.id', '=', 'tbl_users.id')->where([['tbl_users.id', '=', Auth::user()->id], ['tbl_users.status', '=', 'Active']])->first();

            // to user
            $transfer_user_id = User::where('user_id',$request->to_user_id)->pluck('id')->first();
            
            /* to check user downline or not */

            $userId = Auth::User()->id;

            $from_user_id = $transfer_user_id;

            if ($from_user_id != $userId) {

               $todaydetailsexist = TodayDetails::where('to_user_id', $userId)->where('from_user_id', $from_user_id)->get();

              /* if (count($todaydetailsexist) == 0) {

                    $arrStatus = Response::HTTP_NOT_FOUND;
                    $arrCode = Response::$statusTexts[$arrStatus];
                    $arrMessage = 'Not a Downline user';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, 0);
               }*/

            }

            /* to check user downline or not */

            $tran_user_id = User::where('user_id',$request->to_user_id)->pluck('user_id')->first();
            
             $transfer_user_id_email = User::where('user_id',$request->to_user_id)->pluck('email')->first();
            $to_user_dashboard = Dashboard::select('top_up_wallet','top_up_wallet_withdraw')->where('id',$transfer_user_id)->first();

            $to_user_topup_bal = $to_user_dashboard->top_up_wallet - $to_user_dashboard->top_up_wallet_withdraw;
            

            if (!empty($users)) {
                if ($users->user_id != $request->Input('to_user_id')) {
                    $trans_bal = $users->top_up_wallet - $users->top_up_wallet_withdraw;

                    if ($request->input('amount') > 0) {
                        if ($trans_bal >= $request->input('amount')) {
                            $to_user_id = User::where([['user_id', '=', $request->input('to_user_id')],])->pluck('id')->first();
                            if (!empty($to_user_id)) {
                                $updateCoinData = array();
                                $tempData = [];
                                //---------update level income wd---------//
                                $updateCoinData['top_up_wallet_withdraw'] = round(($users->top_up_wallet_withdraw + $request->input('amount')), 7);
                                $updateCoinData['total_withdraw'] = round(($users->top_up_wallet_withdraw + $request->input('amount')), 7);
                                $updateCoinData['usd'] = round(($users->usd - $request->input('amount')), 7);
                                $updateCoinData = Dashboard::where('id', $users->id)->limit(1)->update($updateCoinData);

                                $to_userid_transfer_details = Dashboard::where([['id', '=', $to_user_id],])->select('top_up_wallet', 'total_profit', 'usd')->first();

                                $to_userid_transfer_wallet = $to_userid_transfer_details->top_up_wallet;
                                $to_userid_total_profit = $to_userid_transfer_details->total_profit;
                                $to_userid_usd = $to_userid_transfer_details->usd;
                                /* $to_userid_total_profit =User::where([['id','=',$request->input('to_user_id')],])->pluck('total_profit')->first(); */


                                $updateTouserData['top_up_wallet'] = $to_userid_transfer_wallet + $request->input('amount');

                                $updateTouserData['total_profit'] = round(($to_userid_total_profit + $request->input('amount')), 7);
                                $updateTouserData['usd'] = round(($to_userid_usd + $request->input('amount')), 7);
                                $updateCoinData = Dashboard::where('id', $to_user_id)->limit(1)->update($updateTouserData);

                                $getCoin = $this->projectsettings->getProjectDetails();

                                // first entry

                                $balance = AllTransaction::where('id', '=', $users->id)->orderBy('srno', 'desc')->pluck('balance')->first();
                                $Trandata = array();      // insert in transaction 
                                $Trandata['id'] = $users->id;
                                $Trandata['network_type'] = $getCoin->original["data"]["coin_name"];
                                $Trandata['refference'] = $users->id;
                                $Trandata['debit'] = $request->input('amount');
                               
                                $Trandata['balance'] = $balance - $request->input('amount');
                                $Trandata['type'] = 'TRANSFER';
                                $Trandata['status'] = 1;
                                $Trandata['entry_time'] = $this->today;
                                $Trandata['remarks'] = 'Fund transfer to  ' . $request->input('to_user_id');
                                $Trandata['prev_balance'] = $request['topup_wallet_bal']; 
                                $Trandata['final_balance'] = $request['topup_wallet_bal'] - $request->input('amount');

                                $TransactionDta = AllTransaction::create($Trandata);
                                $balance1 = AllTransaction::where('id', '=', $users->id)->orderBy('srno', 'desc')->pluck('balance')->first();
                                $Trandata = array();      // insert in transaction 
                                $Trandata['id'] = $transfer_user_id;
                                $Trandata['network_type'] = $getCoin->original["data"]["coin_name"];
                                $Trandata['refference'] = $to_user_id;
                                $Trandata['credit'] = $request->input('amount');
                                $Trandata['type'] = 'TRANSFER';
                                $Trandata['balance'] = $balance1 + $request->input('amount');
                                $Trandata['status'] = 1;
                                $Trandata['entry_time'] = $this->today;
                                $Trandata['remarks'] = 'Fund Received from ' . $users->user_id;
                                $Trandata['prev_balance'] = $to_user_topup_bal; 
                                $Trandata['final_balance'] = $to_user_topup_bal + $request->input('amount');

                                $TransactionDta = AllTransaction::create($Trandata);

                                $actdata = array();      // insert in transaction 
                                $actdata['id'] = $users->id;
                                $actdata['message'] = '$'.$request->input('amount').' Transfer to '.$users->user_id;
                                //dd($users->user_id);
                                $actdata['status'] = 1;
                                $actdata['entry_time'] = $this->today;

                                $actDta = Activitynotification::create($actdata);

                                    $subject = "Transfer Amount submitted";
                                    $pagename = "emails.transfer_amount";
                                    $data = array('pagename' => $pagename,
                                        'amount' => $request->input('amount'),
                                        'transfer_user_id' => $tran_user_id,
                                        'username' => $users->user_id
                                    );
                                   // dd($data);
                                    $email = $transfer_user_id_email;
                                    //dd($email);
                                    //$mail = sendMail($data, $email, $subject);
                                    $arrStatus   = Response::HTTP_OK;
                                    $arrCode     = Response::$statusTexts[$arrStatus];
                                    $arrMessage  = 'Amount transfer successfully';
                                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                            } else {
                                $arrStatus   = Response::HTTP_NOT_FOUND;
                                $arrCode     = Response::$statusTexts[$arrStatus];
                                $arrMessage  = 'To user not exist';
                                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                            }
                        } else {

                            $arrStatus   = Response::HTTP_NOT_FOUND;
                            $arrCode     = Response::$statusTexts[$arrStatus];
                            $arrMessage  = 'You have insufficient transfer balance';
                            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                        }
                    } else {

                        $arrStatus   = Response::HTTP_NOT_FOUND;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = 'Topup wallet balance should be greater than 0';
                        return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    }
                } else {
                    $arrStatus   = Response::HTTP_NOT_FOUND;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = 'You can not transfer fund to self account';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                }
            } else {

                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Invalid user';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }
        } catch (Exception $e) {
            dd($e);

            $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
            $arrCode     = Response::$statusTexts[$arrStatus];
            $arrMessage  = 'Something went wrong,Please try again';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
    }

    public function FundToFundTransfer(Request $request)
    {
       
        try {
            $rules = array(
                'amount' => 'required|numeric|min:1',
                'to_user_id' => 'required',
                'otp'  => 'required',
            );
            $validator = checkvalidation($request->all(), $rules, '');
            if (!empty($validator)) {
                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = $validator;
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }
            /*----------------------------OTP Verification--------------------------*/
            $id = Auth::User()->id;
            $arrInput            = $request->all();
            $arrInput['user_id'] = $id;
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
            /*-----------------------------------------------------------------------------------*/

            $from_id = Auth::User()->id;
            if (!empty($from_id)) {
                
                $touser_id = User::where('user_id',$request->to_user_id)->pluck('id')->first();
                
                if (!empty($touser_id) && $from_id != $touser_id) {
                    
                    $topup_bal = Dashboard::selectRaw('fund_wallet -fund_wallet_withdraw as balance')->where('id',$from_id)->first();
                    $amount =$request->amount;
                    $remark =$request->remark;

                    if($topup_bal->balance >= $amount ){

                    /*$deduction = WithdrawSettings::where([['income','=','wallet_wallet'],['status', '=', 'Active']])->pluck('deduction')->first();*/
                    $deduction = 0;

                    $per = $amount*$deduction/100;
                    $amount_after_deduct = $amount - $per;                     

                        $updateFromData = array();

                        $updateFromData['fund_wallet_withdraw'] = DB::raw('fund_wallet_withdraw + '.$amount.'');
                        $updateFromData['usd'] = DB::raw('usd - '.$amount.'');
                        $updateqryfrom = Dashboard::where('id', $from_id)->limit(1)->update($updateFromData);


                        $updateTouserData = array();
                        $updateTouserData['fund_wallet'] = DB::raw('fund_wallet +'.$amount_after_deduct.'');
                        $updateqryto = Dashboard::where('id', $touser_id)->limit(1)->update($updateTouserData);


                        $fundData = array();
                        // $fundData['to_user_id'] = $touser_id;
                        // $fundData['from_user_id'] = $from_id;
                        $fundData['to_user_id'] = $id;
                        $fundData['from_user_id'] = $id;
                        $fundData['amount'] = $amount;
                        $fundData['transfer_charge'] = $per;
                        $fundData['net_amount'] = $amount_after_deduct;
                        $fundData['remark'] = $remark;
                        $fundData['wallet_type'] = 9;
                        $fundData['entry_time'] = $this->today;
                        
                        $insFund = FundTransfer::create($fundData);

                        $arrStatus   = Response::HTTP_OK;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = 'Amount transfer successfully';
                        return sendResponse($arrStatus, $arrCode, $arrMessage, '');

                    }else {

                        $arrStatus   = Response::HTTP_NOT_FOUND;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = 'Insufficient balance';
                        return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    }

                } else {

                    $arrStatus   = Response::HTTP_NOT_FOUND;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = 'Invalid user';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                }

            } else {

                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Unaunthenticated User';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }
            
        }catch (Exception $e) {
            dd($e);

            $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
            $arrCode     = Response::$statusTexts[$arrStatus];
            $arrMessage  = 'Something went wrong,Please try again';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
    }
    public function AllIDBalanceTransfer(Request $request)
    {

        try {
            $touser = Auth::User();

            $arrPendings = User::join('tbl_dashboard as td', 'td.id', '=', 'tbl_users.id')->select('tbl_users.fullname as name','tbl_users.user_id','tbl_users.email','tbl_users.id as from_user_id',DB::raw('td.working_wallet - td.working_wallet_withdraw as amount'))
                    ->where('tbl_users.email',$touser->email)->where('tbl_users.id','!=',$touser->id)->where('tbl_users.status','Active')
                    ->whereRaw('(td.working_wallet - td.working_wallet_withdraw) >=20')->get();
            
            /*dd($arrPendings->toArray());*/
           

            if (!empty($arrPendings)) {
                $to_user_id = $touser->id;
                foreach ($arrPendings as $key => $user) {                    
                    $trans_bal = $user->amount;
                    $from_user_id = $user->from_user_id;
                    if ($trans_bal > 20) {
                        $updateCoinData = array();
                        $tempData = [];
                        //---------update level income wd---------//
                        $updateCoinData['working_wallet_withdraw'] = DB::raw('working_wallet_withdraw +'.$trans_bal);
                        $updateCoinData['usd'] = DB::raw('usd -'.$trans_bal);
                        $updateCoinData = Dashboard::where('id', $from_user_id)->limit(1)->update($updateCoinData);


                        $updateCoinData = array();
                        $updateTouserData['working_wallet'] = DB::raw('working_wallet +'.$trans_bal);
                        $updateCoinData = Dashboard::where('id', $to_user_id)->limit(1)->update($updateTouserData);


                        $fundData = array();
                        $fundData['to_user_id'] = $to_user_id;
                        $fundData['from_user_id'] = $from_user_id;
                        $fundData['amount'] = $trans_bal;
                        $fundData['wallet_type'] = 4;
                        $fundData['entry_time'] = $this->today;
                        
                        $insFund = FundTransfer::create($fundData);

                        $balance = AllTransaction::where('id', '=', $from_user_id)->orderBy('srno', 'desc')->pluck('balance')->first();
                        $balance =($balance != null)?$balance:0; 
                        $Trandata = array();      // insert in transaction 
                        $Trandata['id'] = $from_user_id;
                        $Trandata['network_type'] = "";
                        $Trandata['refference'] = $from_user_id;
                        $Trandata['debit'] = $trans_bal;
                       
                        $Trandata['balance'] = $balance - $trans_bal;
                        $Trandata['type'] = 'TRANSFER';
                        $Trandata['status'] = 1;
                        $Trandata['entry_time'] = $this->today;
                        $Trandata['remarks'] = 'Fund transfer to  ' . $touser->user_id;
                        $Trandata['prev_balance'] = $balance; 
                        $Trandata['final_balance'] = $balance - $trans_bal;

                        $TransactionDta = AllTransaction::create($Trandata);
                        $balance1 = AllTransaction::where('id', '=', $to_user_id)->orderBy('srno', 'desc')->pluck('balance')->first();
                        $balance1 =($balance1 != null)?$balance1:0; 
                        $Trandata = array();      // insert in transaction 
                        $Trandata['id'] = $to_user_id;
                        $Trandata['network_type'] = "";
                        $Trandata['refference'] = $to_user_id;
                        $Trandata['credit'] = $trans_bal;
                        $Trandata['type'] = 'TRANSFER';
                        $Trandata['balance'] = $trans_bal;
                        $Trandata['status'] = 1;
                        $Trandata['entry_time'] = $this->today;
                        $Trandata['remarks'] = 'Fund Received from ' . $user->user_id;
                        $Trandata['prev_balance'] = $balance1; 
                        $Trandata['final_balance'] = $balance1 + $trans_bal;

                        $TransactionDta = AllTransaction::create($Trandata);

                        $actdata = array();      // insert in transaction 
                        $actdata['id'] = $touser->id;
                        $actdata['message'] = '$'.$trans_bal.' Transfer to '.$touser->user_id;
                        //dd($users->user_id);
                        $actdata['status'] = 1;
                        $actdata['entry_time'] = $this->today;

                        $actDta = Activitynotification::create($actdata);

                        $subject = "Transfer Amount submitted";
                        $pagename = "emails.transfer_amount";
                        $data = array('pagename' => $pagename,
                            'amount' => $trans_bal,
                            'transfer_user_id' => $user->user_id,
                            'username' => $touser->user_id
                        );
                       // dd($data);
                        $email = $user->email;
                        //dd($email);
                        $mail = sendMail($data, $email, $subject);
                    } else {

                        $arrStatus   = Response::HTTP_NOT_FOUND;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = $user->user_id.' working wallet balance is less than 20';
                        return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    }
                } 
                $arrStatus   = Response::HTTP_OK;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Amount transfer successfully';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');   
            } else {

                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Invalid user';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }
        } catch (Exception $e) {
            dd($e);

            $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
            $arrCode     = Response::$statusTexts[$arrStatus];
            $arrMessage  = 'Something went wrong,Please try again';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
    }
    public function AddPurchaseBalanceTransferRequest(Request $request)
    {
        try{
            $rules = array(
                'amount' => 'required',
            );
            $validator = checkvalidation($request->all(), $rules, '');
            if (!empty($validator)) {
                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = $validator;
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
            }  */         

            $touseremail = Auth::User()->email;
            $touserid = Auth::User()->id;
            if (!empty($touserid)) {
                
                $checkexist = PurchaseBalanceTransfer::where([['email',$touseremail],['status',0]])->first();
                if (!empty($checkexist)) {
                    $arrStatus   = Response::HTTP_NOT_FOUND;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = 'Request already exist for this email id';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                }
                else{
                    $insArr=array();
                    $insArr['user_id'] = $touserid;
                    $insArr['email'] = $touseremail;
                    $insArr['amount'] = $request->amount;
                    $insArr['entry_time'] = $this->today;
                    $ins=PurchaseBalanceTransfer::create($insArr);
                    if (!empty($ins)) {                        
                        $arrStatus   = Response::HTTP_OK;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = 'Request added successfully';
                        return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    }
                }
                
            } else {

                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Invalid user';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }


        }catch (Exception $e) {
            dd($e);

            $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
            $arrCode     = Response::$statusTexts[$arrStatus];
            $arrMessage  = 'Something went wrong,Please try again';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
    }
    public function CheckTransferRequestExist()
    {
        $touser = Auth::User()->email;
        if (!empty($touser)) {
            $checkexist = BalanceTransfer::where([['email',$touser],['status',0]])->first();
            if (!empty($checkexist)) {
                $arrStatus   = Response::HTTP_OK;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Request already exist for this email id';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }
        }
    }


    public function AddBalanceTransferRequest(Request $request)
    {
        try{
            $rules = array(
                'amount' => 'required',
            );
            $validator = checkvalidation($request->all(), $rules, '');
            if (!empty($validator)) {
                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = $validator;
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
            }    */       

            $touseremail = Auth::User()->email;
            $touserid = Auth::User()->id;
            if (!empty($touserid)) {
                $bal = Dashboard::selectRaw('round(working_wallet-working_wallet_withdraw,2) as balance')->where('id',$touserid)->pluck('balance')->first();
                if($bal < 20){
                    $arrStatus   = Response::HTTP_NOT_FOUND;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = 'Your Working Wallet is having less than 20$. Login from ID which have 20$ and then Try';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, ''); 
                }
                $checkexist = BalanceTransfer::where([['email',$touseremail],['status',0]])->first();
                if (!empty($checkexist)) {
                    $arrStatus   = Response::HTTP_NOT_FOUND;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = 'Request already exist for this email id';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                }
                else{
                    $insArr=array();
                    $insArr['user_id'] = $touserid;
                    $insArr['email'] = $touseremail;
                    $insArr['amount'] = $request->amount;
                    $insArr['entry_time'] = $this->today;
                    $ins=BalanceTransfer::create($insArr);
                    if (!empty($ins)) {                        
                        $arrStatus   = Response::HTTP_OK;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = 'Request added successfully';
                        return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    }
                }
                
            } else {

                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Invalid user';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }


        }catch (Exception $e) {
            dd($e);

            $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
            $arrCode     = Response::$statusTexts[$arrStatus];
            $arrMessage  = 'Something went wrong,Please try again';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
    }

    public function CheckPurchaseTransferRequestExist()
    {
        $touser = Auth::User()->email;
        if (!empty($touser)) {
            $checkexist = PurchaseBalanceTransfer::where([['email',$touser],['status',0]])->first();
            if (!empty($checkexist)) {
                $arrStatus   = Response::HTTP_OK;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Request already exist for this email id';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }
        }
    }
    public function PurchaseToPurchaseTransfer(Request $request)
    {
       
        try {
            $rules = array(
                'amount' => 'required|numeric|min:20',
                'to_user_id' => 'required',
                'otp' => 'required',
            );
             $validator = checkvalidation($request->all(), $rules, '');
             $id = Auth::User()->id;
          $arrInput            = $request->all();
          $arrInput['user_id'] = $id;
           $arrRules            = ['otp' => 'required|min:6|max:6'];

         
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
            if (!empty($validator)) {
                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = $validator;
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }
            $from_id = Auth::User()->id;
           // dd($from_id);
            
            if (!empty($from_id)) {
                
                $touser_id = User::where('user_id',$request->to_user_id)->pluck('id')->first();
                 //dd( $touser_id); 
                if($from_id == $touser_id)
                {
                    //dd($from_id, $touser_id);
                        $arrStatus   = Response::HTTP_NOT_FOUND;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = 'You can not do self transfer';
                        return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                }
                if (!empty($touser_id) || $from_id != $touser_id) {

                    $topup_bal = Dashboard::selectRaw('fund_wallet -fund_wallet_withdraw as balance')->where('id',$from_id)->first();
                    $amount =$request->amount;
                    $remark =$request->remark;

                    if($topup_bal->balance >= $amount ){

                    $deduction = WithdrawSettings::where([['income','=','wallet_wallet'],['status', '=', 'Active']])->pluck('deduction')->first();

                    $per = $amount*$deduction/100;
                    $amount_after_deduct = $amount - $per;                     

                        $updateFromData = array();

                        $updateFromData['fund_wallet_withdraw'] = DB::raw('fund_wallet_withdraw + '.$amount.'');
                        $updateFromData['usd'] = DB::raw('usd - '.$amount.'');
                        $updateqryfrom = Dashboard::where('id', $from_id)->limit(1)->update($updateFromData);


                        $updateTouserData = array();
                        $updateTouserData['fund_wallet'] = DB::raw('fund_wallet +'.$amount_after_deduct.'');
                        $updateqryto = Dashboard::where('id', $touser_id)->limit(1)->update($updateTouserData);


                        $fundData = array();
                        $fundData['to_user_id'] = $touser_id;
                        $fundData['from_user_id'] = $from_id;
                        $fundData['amount'] = $amount;
                        $fundData['transfer_charge'] = $per;
                        $fundData['net_amount'] = $amount_after_deduct;
                        $fundData['remark'] = $remark;
                        $fundData['wallet_type'] = 6;
                        $fundData['entry_time'] = $this->today;
                        
                        $insFund = FundTransfer::create($fundData);

                        $arrStatus   = Response::HTTP_OK;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = 'Amount transfer successfully';
                        return sendResponse($arrStatus, $arrCode, $arrMessage, '');

                    }else {

                        $arrStatus   = Response::HTTP_NOT_FOUND;
                        $arrCode     = Response::$statusTexts[$arrStatus];
                        $arrMessage  = 'Insufficient balance';
                        return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    }

                } /*else {

                    $arrStatus   = Response::HTTP_NOT_FOUND;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = 'Invalid user';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                }*/

            } else {

                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Unaunthenticated User';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }
            
        }catch (Exception $e) {
            dd($e);

            $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
            $arrCode     = Response::$statusTexts[$arrStatus];
            $arrMessage  = 'Something went wrong,Please try again';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
    }
        public function WorkingToPurchaseSelfTransfer(Request $request)
    {
       
        try {
            $rules = array(
                'amount' => 'required|numeric|min:25',
                /*'to_user_id' => 'required',*/
            );
            $validator = checkvalidation($request->all(), $rules, '');
            if (!empty($validator)) {
                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = $validator;
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }
            $id = Auth::User()->id;
            if (!empty($id)) {
                    
                $working_bal = Dashboard::selectRaw('working_wallet - working_wallet_withdraw as balance')->where('id',$id)->first();
                $amount =$request->amount;
                if($working_bal->balance >= $amount ){

                    $deduction = WithdrawSettings::where([['income','=','wallet_wallet'],['status', '=', 'Active']])->pluck('deduction')->first();

                    $per = $amount*$deduction/100;
                    $amount_after_deduct = $amount - $per;  

                    $updateCoinData = array();

                    $updateCoinData['working_wallet_withdraw'] = DB::raw('working_wallet_withdraw +'.$amount.'');
                    $updateCoinData['fund_wallet'] = DB::raw('fund_wallet +'.$amount_after_deduct.'');
                    $updateqryto = Dashboard::where('id', $id)->limit(1)->update($updateCoinData);
                    
                    $fundData = array();
                    $fundData['to_user_id'] = $id;
                    $fundData['from_user_id'] = $id;
                    $fundData['amount'] = $amount;
                    $fundData['net_amount'] = $amount_after_deduct;
                    $fundData['status'] = 1;
                    $fundData['from_wallet_type'] = 1;
                    $fundData['transfer_charge'] = $per;
                    $fundData['to_wallet_type'] = 8;
                    $fundData['entry_time'] = $this->today;
                    
                    $insFund = DexToPurchaseFundTransfer::create($fundData);

                    $arrStatus   = Response::HTTP_OK;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = 'Amount transfer successfully';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');

                }else {

                    $arrStatus   = Response::HTTP_NOT_FOUND;
                    $arrCode     = Response::$statusTexts[$arrStatus];
                    $arrMessage  = 'Insufficient balance';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                }

            } else {

                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Unaunthenticated User';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            }
            
        }catch (Exception $e) {
            dd($e);

            $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
            $arrCode     = Response::$statusTexts[$arrStatus];
            $arrMessage  = 'Something went wrong,Please try again';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
    }
}
