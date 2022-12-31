<?php

namespace App\Http\Controllers\adminapi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\adminapi\CommonController;
use Illuminate\Support\Facades\Auth;
use App\Models\Currencyrate;
use App\Models\Currency;
use App\Models\Dashboard;
use App\Models\Enquiry;
use App\Models\UsersChangeData;
use App\Models\AddressTransaction;
use App\Models\AddressTransactionPending;
use App\Models\TodaySummary;
use App\Models\AddFunds;
use App\Models\Topup;
use App\Models\Invoice;
use App\Models\DirectIncome;
use App\Models\BinaryIncome;
use App\Models\LevelIncome;
use App\Models\DailyBouns;
use App\Models\AllTransaction;
use App\Models\ReplyEnquiryReport;
use App\Models\WithdrawalConfirmed;
use App\Models\WithdrawPending;
use App\Models\LeadershipIncome;
use App\Models\PayoutHistory;
use App\Models\UplineIncome;
use App\Models\LevelIncomeRoi;
use App\Models\AwardWinner;
use App\Models\PromotionalSocialIncome;
use App\Models\SupperMatchingIncome;
use App\Models\freedomclubincome;
use App\Models\AddFailedLoginAttempt;
use App\User;
use DB;
use Config;
use Validator;

class DashboardController extends Controller
{
    /**
     * define property variable
     *
     * @return
     */
    public $statuscode, $commonController;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CommonController $commonController)
    {
        $this->statuscode =    Config::get('constants.statuscode');
        $this->commonController = $commonController;
    }

    /**
     * get summary report for dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function getDashboardSummary(Request $request)
    {

        if (isset($request->frm_date) && isset($request->to_date)) {
            //input request from user
            $frm_date   = date('Y-m-d', strtotime($request->frm_date));
            $to_date    = date('Y-m-d', strtotime($request->to_date));
            //total active user
            $totalUnblockUser = User::where([['status', '=', 'Active'], ['type', '=', '']])
                ->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $totalUnblockUser = $totalUnblockUser->where('id', $request->id);
            }
            $totalUnblockUser = $totalUnblockUser->count();
            //total Inactive user
            $totalBlockUser = User::where([['status', '=', 'Inactive'], ['type', '=', '']])
                ->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $totalBlockUser = $totalBlockUser->where('id', $request->id);
            }
            $totalBlockUser = $totalBlockUser->count();

            //total topup count
            $totalTopup = Topup::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $totalTopup = $totalTopup->where('id', $request->id);
            }
            $totalTopup = $totalTopup->sum('amount');

            //total paid user
            $totalPaidUser = Dashboard::sum('top_up_wallet')->get();
            $totalPaidUser = $totalPaidUser->count();

            //total unpaid user
            $totalUnPaidUser = Dashboard::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date])
                ->where('total_investment', '=', 0);
            if (isset($request->id) && !empty($request->id)) {
                $totalUnPaidUser = $totalUnPaidUser->where('id', $request->id);
            }
            $totalUnPaidUser = $totalUnPaidUser->count();

            //total direct topup count(deposite BTC)
            $directTopupCount = Topup::where('top_up_type', 0)
                ->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $directTopupCount = $directTopupCount->where('id', $request->id);
            }
            $directTopupCount = $directTopupCount->sum('amount');

            //total free topup count
            $freeTopupCount = Topup::where('top_up_type', 1)
                ->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $freeTopupCount = $freeTopupCount->where('id', $request->id);
            }
            $freeTopupCount = $freeTopupCount->sum('amount');

            //total admin topup count
            $adminTopupCount = Topup::where('top_up_type', 2)
                ->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $adminTopupCount = $adminTopupCount->where('id', $request->id);
            }
            $adminTopupCount = $adminTopupCount->sum('amount');
            //total self topup count
            $selfTopupCount = Topup::where('top_up_type', 3)
                ->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $selfTopupCount = $selfTopupCount->where('id', $request->id);
            }
            $selfTopupCount = $selfTopupCount->sum('amount');

            //total withdrawal confirmed
            $withdrawConfirmed = WithdrawalConfirmed::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $withdrawConfirmed = $withdrawConfirmed->where('id', $request->id);
            }
            $withdrawConfirmed = $withdrawConfirmed->sum('amount');

            //total withdrawal pending
            $withdrawPending = WithdrawPending::where('status', 0)
                ->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $withdrawPending = $withdrawPending->where('id', $request->id);
            }
            $withdrawPending = $withdrawPending->sum('amount');


            /*//total coinpayment
            $totalCoinpayment = Invoice::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"),[$frm_date, $to_date])->where('product_url','coinpayment')->where('in_status',1)->sum('price_in_usd');
            //total blockio or block chain
            $totalBlockChain = Invoice::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"),[$frm_date, $to_date])->whereIn('product_url',['blockchain-local','blockchain-online'])->where('in_status',1)->sum('price_in_usd');
            //total coinbase
            $totalCoinbase = Invoice::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"),[$frm_date, $to_date])->where('product_url','coinbase')->where('in_status',1)->sum('price_in_usd');*/
            //total direct income
            $directIncomeCount = DirectIncome::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $directIncomeCount = $directIncomeCount->where('toUserId', $request->id);
            }
            $directIncomeCount = $directIncomeCount->sum('amount');


            $binaryIncomeCount = BinaryIncome::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $binaryIncomeCount = $binaryIncomeCount->where('user_id', $request->id);
            }
            $binaryIncomeCount = $binaryIncomeCount->sum('amount');


            //total level income
            $levelIncomeCount = LevelIncome::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $levelIncomeCount = $levelIncomeCount->where('toUserId', $request->id);
            }
            $levelIncomeCount = $levelIncomeCount->sum('amount');
            //total ROI income
            $roiIncomeCount = DailyBouns::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $roiIncomeCount = $roiIncomeCount->where('id', $request->id);
            }
            $roiIncomeCount = $roiIncomeCount->sum('amount');

            $uplineIncome = UplineIncome::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $uplineIncome = $uplineIncome->where('toUserId', $request->id);
            }
            $uplineIncome = $uplineIncome->sum('amount');

            $LevelIncomeRoi = LevelIncomeRoi::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $LevelIncomeRoi = $LevelIncomeRoi->where('toUserId', $request->id);
            }
            $LevelIncomeRoi = $LevelIncomeRoi->sum('amount');

            $promotionalIncome = PromotionalSocialIncome::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $promotionalIncome = $promotionalIncome->where('id', $request->id);
            }
            $promotionalIncome = $promotionalIncome->sum('amount');

            $AwardWinner = AwardWinner::join('tbl_awards_list as tal', 'tal.award_id', 'tbl_award_winner.award_id')->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$frm_date, $to_date]);
            if (isset($request->id) && !empty($request->id)) {
                $AwardWinner = $AwardWinner->where('user_id', $request->id);
            }

            $AwardWinner = $AwardWinner->sum('tal.award');
            $yetToWithdraw = Dashboard::selectRaw("ROUND(SUM(working_wallet-working_wallet_withdraw),4) as working_balance")->where(DB::raw('working_wallet-working_wallet_withdraw'),">=",20)->pluck('working_balance')->first();
        } else {
            //total active user
            $totalUnblockUser = User::where([['status', '=', 'Active'], ['type', '=', '']]);
            if (isset($request->id) && !empty($request->id)) {
                $totalUnblockUser = $totalUnblockUser->where('id', $request->id);
            }
            $totalUnblockUser = $totalUnblockUser->count();
            //total Inactive user
            $totalBlockUser = User::where([['status', '=', 'Inactive'], ['type', '=', '']]);
            if (isset($request->id) && !empty($request->id)) {
                $totalBlockUser = $totalBlockUser->where('id', $request->id);
            }
            $totalBlockUser = $totalBlockUser->count();

            //total topup count
            $totalTopup = Topup::query();
            if (isset($request->id) && !empty($request->id)) {
                $totalTopup = $totalTopup->where('id', $request->id);
            }
            $totalTopup = $totalTopup->sum('amount');

            //total paid user
            $totalPaidUser = Dashboard::where('total_investment', '>', 0);
            if (isset($request->id) && !empty($request->id)) {
                $totalPaidUser = $totalPaidUser->where('id', $request->id);
            }
            $totalPaidUser = $totalPaidUser->count();

            //total unpaid user
            $totalUnPaidUser = Dashboard::where('total_investment', '=', 0);
            if (isset($request->id) && !empty($request->id)) {
                $totalUnPaidUser = $totalUnPaidUser->where('id', $request->id);
            }
            $totalUnPaidUser = $totalUnPaidUser->count();

            //total direct topup count(deposite BTC)
            $directTopupCount = Topup::where('top_up_type', 0);
            if (isset($request->id) && !empty($request->id)) {
                $directTopupCount = $directTopupCount->where('id', $request->id);
            }
            $directTopupCount = $directTopupCount->sum('amount');

            //total free topup count
            $freeTopupCount = Topup::where('top_up_type', 1);
            if (isset($request->id) && !empty($request->id)) {
                $freeTopupCount = $freeTopupCount->where('id', $request->id);
            }
            $freeTopupCount = $freeTopupCount->sum('amount');

            //total admin topup count
            $adminTopupCount = Topup::where('top_up_type', 2);
            if (isset($request->id) && !empty($request->id)) {
                $adminTopupCount = $adminTopupCount->where('id', $request->id);
            }
            $adminTopupCount = $adminTopupCount->sum('amount');
            //total self topup count
            $selfTopupCount = Topup::where('top_up_type', 3);
            if (isset($request->id) && !empty($request->id)) {
                $selfTopupCount = $selfTopupCount->where('id', $request->id);
            }
            $selfTopupCount = $selfTopupCount->sum('amount');

            //add funt 

             $addFund = Invoice::where('in_status', 1);
            if (isset($request->id) && !empty($request->id)) {
                $addFund = $addFund->where('id', $request->id);
            }
            $addFund = $addFund->sum('price_in_usd');


            //total withdrawal confirmed
            $withdrawConfirmed = WithdrawalConfirmed::query();
            if (isset($request->id) && !empty($request->id)) {
                $withdrawConfirmed = $withdrawConfirmed->where('id', $request->id);
            }
            $withdrawConfirmed = $withdrawConfirmed->sum('amount');


            //total withdrawal confirmed
            $withdrawConfirmedDeduction = WithdrawalConfirmed::query();
            if (isset($request->id) && !empty($request->id)) {
                $withdrawConfirmedDeduction = $withdrawConfirmedDeduction->where('id', $request->id);
            }
            $withdrawConfirmedDeduction = $withdrawConfirmedDeduction->sum(DB::raw('amount + deduction'));

            //total withdrawal pending
           
             $withdrawPending = WithdrawPending::join('tbl_users as tu', 'tu.id', '=','tbl_withdrwal_pending.id');

            if (isset($request->id) && !empty($request->id)) {
                $withdrawPending = $withdrawPending->where('tbl_withdrwal_pending.id', $request->id);
            }

            $withdrawPending = $withdrawPending->where('tbl_withdrwal_pending.status', 0)->where('tu.status','Active')->sum(DB::raw('tbl_withdrwal_pending.amount + tbl_withdrwal_pending.deduction'));


            /*//total coinpayment
            $totalCoinpayment = Invoice::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"),[$frm_date, $to_date])->where('product_url','coinpayment')->where('in_status',1)->sum('price_in_usd');
            //total blockio or block chain
            $totalBlockChain = Invoice::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"),[$frm_date, $to_date])->whereIn('product_url',['blockchain-local','blockchain-online'])->where('in_status',1)->sum('price_in_usd');
            //total coinbase
            $totalCoinbase = Invoice::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"),[$frm_date, $to_date])->where('product_url','coinbase')->where('in_status',1)->sum('price_in_usd');*/
            //total direct income
            $directIncomeCount = DirectIncome::query();
            if (isset($request->id) && !empty($request->id)) {
                $directIncomeCount = $directIncomeCount->where('toUserId', $request->id);
            }
            $directIncomeCount = $directIncomeCount->sum('amount');

            $binaryIncomeCount = PayoutHistory::query();
            if (isset($request->id) && !empty($request->id)) {
                $binaryIncomeCount = $binaryIncomeCount->where('user_id', $request->id);
            }
            $binaryIncomeCount = $binaryIncomeCount->sum('amount');

            //total binary income
            /*$binaryIncomeCount = BinaryIncome::whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"),[$frm_date, $to_date])->sum('amount');*/
            //total level income
            $levelIncomeCount = LevelIncome::query();
            if (isset($request->id) && !empty($request->id)) {
                $levelIncomeCount = $levelIncomeCount->where('toUserId', $request->id);
            }
            $levelIncomeCount = $levelIncomeCount->sum('amount');
            //total ROI income
            $roiIncomeCount = DailyBouns::query();
            if (isset($request->id) && !empty($request->id)) {
                $roiIncomeCount = $roiIncomeCount->where('id', $request->id);
            }
            $roiIncomeCount = $roiIncomeCount->sum('amount');

            $uplineIncome = UplineIncome::query();
            if (isset($request->id) && !empty($request->id)) {
                $uplineIncome = $uplineIncome->where('toUserId', $request->id);
            }
            $uplineIncome = $uplineIncome->sum('amount');

            $LevelIncomeRoi = LevelIncomeRoi::query();
            if (isset($request->id) && !empty($request->id)) {
                $LevelIncomeRoi = $LevelIncomeRoi->where('toUserId', $request->id);
            }
            $LevelIncomeRoi = $LevelIncomeRoi->sum('amount');

            $AwardWinner = AwardWinner::join('tbl_awards_list as tal', 'tal.award_id', 'tbl_award_winner.award_id');
            if (isset($request->id) && !empty($request->id)) {
                $AwardWinner = $AwardWinner->where('user_id', $request->id);
            }

            $AwardWinner = $AwardWinner->sum('tal.award');

            $promotionalIncome = PromotionalSocialIncome::query();
            if (isset($request->id) && !empty($request->id)) {
                $promotionalIncome = $promotionalIncome->where('id', $request->id);
            }
            $promotionalIncome = $promotionalIncome->sum('amount');


             //Total account wallet balance
            $totalworkingwall = Dashboard::sum('working_wallet');
            $totalWithdrawwal = Dashboard::sum('working_wallet_withdraw');
            $totalAccountWalletBal = $totalworkingwall - $totalWithdrawwal;
            //dd($totalAccountWalletBal);


            //Total purchase wallet balance
            $topupWallet = Dashboard::sum('fund_wallet');
            $topupWalletWithdraw = Dashboard::sum('fund_wallet_withdraw');
            $totalPurchaseWalletBal = $topupWallet - $topupWalletWithdraw;

            //Upcoming Withdraw amount
            $yetToWithdraw = Dashboard::selectRaw("ROUND(SUM(working_wallet-working_wallet_withdraw),4) as working_balance")->where(DB::raw('working_wallet-working_wallet_withdraw'),">=",20)->pluck('working_balance')->first();
        }


        $id=Auth::user()->id;

       $isAssignDash= DB::table('tbl_ps_admin_rights')->where('user_id','=',$id)->where('parent_id','=',1)->where('navigation_id','=',3)->first();
       if(!empty($isAssignDash)){

        $arrSummaryData['isAssignDash'] = True;
       }else{

        $arrSummaryData['isAssignDash'] = false;

       }
       $supermatching_income=SupperMatchingIncome::sum('amount'); 
        $freedom_income = freedomclubincome::sum('amount');

        
        $arrSummaryData['supermatching_income']              = $supermatching_income;
        $arrSummaryData['freedom_income']              = $freedom_income;
        //build array of summary data
        $arrSummaryData['total_users']              = $totalUnblockUser + $totalBlockUser;
        $arrSummaryData['total_unblock_users']      = $totalUnblockUser;
        $arrSummaryData['total_block_users']        = $totalBlockUser;
        $arrSummaryData['total_account_wallet_bal']        = number_format($totalAccountWalletBal, 2, '.', '');
        $arrSummaryData['total_purchase_wallet_bal']        = number_format($totalPurchaseWalletBal, 2, '.', '');

        $arrSummaryData['total_investment']         = number_format($totalTopup, 2, '.', '');

        $arrSummaryData['total_active_user']        = $totalPaidUser;
        $arrSummaryData['total_inactive_user']      = $totalUnPaidUser;

        $arrSummaryData['total_deposite_admin']     = number_format($adminTopupCount, 2, '.', '');
        $arrSummaryData['total_deposite_btc']       = number_format($directTopupCount, 2, '.', '');
        $arrSummaryData['total_deposite_wallet']    = number_format($selfTopupCount, 2, '.', '');

        $arrSummaryData['add_fund_btc']    = number_format($addFund, 2, '.', '');


        $arrSummaryData['total_withdraw']           = number_format($withdrawConfirmed + $withdrawPending, 2, '.', '');
        $arrSummaryData['total_withdraw_confirm']   = number_format($withdrawConfirmed, 2, '.', '');
        $arrSummaryData['total_withdraw_confirmd_deduction']   = number_format($withdrawConfirmedDeduction, 2, '.', '');
        $arrSummaryData['total_withdraw_pending']   = number_format($withdrawPending, 2, '.', '');

        /*$arrSummaryData['total_admin_topup']      = $adminTopupCount;
        $arrSummaryData['total_coinpayment']        = $totalCoinpayment;
        $arrSummaryData['total_blockio']            = $totalBlockChain;
        $arrSummaryData['total_coinbase']           = $totalCoinbase;*/
        $arrSummaryData['total_income']             = number_format($directIncomeCount + $binaryIncomeCount + $levelIncomeCount + $roiIncomeCount + $LevelIncomeRoi, 2, '.', '');
        $arrSummaryData['total_direct_income']      = number_format($directIncomeCount, 2, '.', '');
        $arrSummaryData['total_binary_income']    = number_format($binaryIncomeCount, 2);
        $arrSummaryData['total_level_income']       = number_format($levelIncomeCount, 2, '.', '');
        $arrSummaryData['total_roi_income']         = number_format($roiIncomeCount, 2, '.', '');
        $arrSummaryData['total_upline_income']      = number_format($uplineIncome, 2, '.', '');
        $arrSummaryData['total_level_roi_income']   = number_format($LevelIncomeRoi, 2, '.', '');
        $arrSummaryData['total_promotional_income'] = number_format($promotionalIncome, 2, '.', '');
        $arrSummaryData['total_award']         = number_format($AwardWinner, 2, '.', '');
        $arrSummaryData['total_dex_wallet_bal']         = number_format($yetToWithdraw, 2, '.', '');
        $arrSummaryData['total_balance']            = number_format($arrSummaryData['total_investment'] - $arrSummaryData['total_withdraw'], 2, '.', '');

        if (!empty($arrSummaryData)) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Dashboard summary data found', $arrSummaryData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Dashboard summary data not found', '');
        }
    }

    /**
     * get getAccountWalletgetAccountWallet for dashboard
     *
     * @return \Illuminate\Http\Response
     */
    // public function getAccountWallet(Request $request)
    // {

    //     $arrInput = $request->all();

    //     $query = Dashboard::select('tbl_dashboard.top_up_wallet','tbl_dashboard.top_up_wallet_withdraw','tbl_dashboard.working_wallet','tbl_dashboard.working_wallet_withdraw','tbl_dashboard.entry_time','tu.user_id','tu.fullname')
    //                         ->join('tbl_users as tu', 'tu.id', 'tbl_dashboard.id')
    //                         ->where(DB::raw('tbl_dashboard.top_up_wallet + tbl_dashboard.top_up_wallet_withdraw'),'>','0')
    //                         ->orwhere(DB::raw('tbl_dashboard.working_wallet + tbl_dashboard.working_wallet_withdraw'),'>','0');

            
    //      //Total account wallet balance
    //         // $totalworkingwall = Dashboard::sum('working_wallet');
    //         // $totalWithdrawwal = Dashboard::sum('working_wallet_withdraw');
    //         // $totalAccountWalletBal = $totalworkingwall - $totalWithdrawwal;
    //         //dd($totalAccountWalletBal);


    //         //Total purchase wallet balance
    //         $topupWallet = Dashboard::sum('top_up_wallet');
    //         $topupWalletWithdraw = Dashboard::sum('top_up_wallet_withdraw');
    //         $totalPurchaseWalletBal = $topupWallet - $topupWalletWithdraw;

    //         if (isset($arrInput['user_id'])) {
    //             $query = $query->where('tu.user_id', $arrInput['user_id']);
    //         }

    //     if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
    //         $arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
    //         $arrInput['to_date']  = date('Y-m-d', strtotime($arrInput['to_date']));
    //         $query  = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_dashboard.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
    //     }
    //     if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
            
    //         $fields = ['tu.user_id','tbl_dashboard.direct_income','tbl_dashboard.binary_income','tbl_dashboard.roi_income','tbl_dashboard.franchise_income','tbl_dashboard.working_wallet'];

    //         //getTableColumns('tbl_dashboard');
    //         $search = $arrInput['search']['value'];
    //         $query  = $query->where(function ($query) use ($fields, $search) {
    //             foreach ($fields as $field) {
    //                 $query->orWhere($field, 'LIKE', '%' . $search . '%');
    //             }
    //         });
    //     }

    //     if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
	// 		$qry = $query;
	// 		$qry = $qry->select('tu.user_id','tu.fullname','tbl_dashboard.top_up_wallet','tbl_dashboard.top_up_wallet_withdraw','tbl_dashboard.working_wallet','tbl_dashboard.working_wallet_withdraw','tbl_dashboard.entry_time');
	// 		$records = $qry->get();
	// 		$res = $records->toArray();
	// 		if (count($res) <= 0) {
	// 			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
	// 		}
	// 		$var = $this->commonController->exportToExcel($res,"AllUsers");
	// 		return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
	// 	}


    //     $totalRecord  = $query->count('tbl_dashboard.id');
    //     $query        = $query->orderBy('tbl_dashboard.srno', 'desc');
    //      $totalworkingwall = $query->sum('working_wallet');
    //      $totalWithdrawwal = $query->sum('working_wallet_withdraw');
    //      $totalAccountWalletBal = $totalworkingwall - $totalWithdrawwal;

    //       $topupWallet = $query->sum('top_up_wallet');
    //         $topupWalletWithdraw = $query->sum('top_up_wallet_withdraw');
    //         $totalPurchaseWalletBal = $topupWallet - $topupWalletWithdraw;


    //     // $totalRecord  = $query->count();
    //     $arrWallet  = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

    //     $arrData['recordsTotal']    = $totalRecord;
    //     $arrData['recordsFiltered'] = $totalRecord;
    //     $arrData['records']         = $arrWallet;
    //     $arrData['total']  = number_format($totalAccountWalletBal, 2, '.', '');
    //     $arrData['balance'] = number_format($totalworkingwall, 2, '.', '');
    //     $arrData['topup_bal'] = number_format($totalPurchaseWalletBal, 2, '.', '');

    //     if (count($arrWallet) > 0) {
    //         return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'record found', $arrData);
    //     } else {
    //         return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'record not found', '');
    //     }
    // }
     public function getAccountWallet(Request $request)
    {

        $arrInput = $request->all();
        // dd($arrInput);
        $query = Dashboard::select('tbl_dashboard.fund_wallet','tbl_dashboard.fund_wallet_withdraw','tbl_dashboard.working_wallet','tbl_dashboard.working_wallet_withdraw','tbl_dashboard.entry_time','tu.user_id','tu.fullname')
                            ->join('tbl_users as tu', 'tu.id', 'tbl_dashboard.id')
                            ->where(function($q) {
                                $q->where(DB::raw('tbl_dashboard.fund_wallet + tbl_dashboard.fund_wallet_withdraw'),'>','0')
                                ->orwhere(DB::raw('tbl_dashboard.working_wallet + tbl_dashboard.working_wallet_withdraw'),'>','0');
                                });
                            


         //Total account wallet balance
            // $totalworkingwall = Dashboard::sum('working_wallet');
            // $totalWithdrawwal = Dashboard::sum('working_wallet_withdraw');
            // $totalAccountWalletBal = $totalworkingwall - $totalWithdrawwal;
            //dd($totalAccountWalletBal);


            //Total purchase wallet balance
            $topupWallet = Dashboard::sum('top_up_wallet');
            $topupWalletWithdraw = Dashboard::sum('top_up_wallet_withdraw');
            $totalPurchaseWalletBal = $topupWallet - $topupWalletWithdraw;

            if (isset($arrInput['user_id'])) {
                $query = $query->where('tu.user_id', $arrInput['user_id']);
            }
          //  dd(date('Y-m-d', strtotime($arrInput['frm_date'])));
           // dd( $arrInput['frm_date']);
        // if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
        //     $arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
        //     $arrInput['to_date']  = date('Y-m-d', strtotime($arrInput['to_date']));
        //     $query  = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_dashboard.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]); 
        // }

        if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d', strtotime($arrInput['to_date']));
            $query  = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_dashboard.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]); 
        }
        /*if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
            
            $fields = ['tu.user_id','tbl_dashboard.direct_income','tbl_dashboard.binary_income','tbl_dashboard.roi_income','tbl_dashboard.franchise_income','tbl_dashboard.working_wallet'];

            //getTableColumns('tbl_dashboard');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search) {
                foreach ($fields as $field) {
                    $query->orWhere($field, 'LIKE', '%' . $search . '%');
                }
            });
        }*/
         $query = $query->orderBy('tbl_dashboard.srno', 'desc');
        if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
			$qry = $query;
			$qry = $qry->select('tu.user_id','tu.fullname',DB::raw('tbl_dashboard.fund_wallet - tbl_dashboard.fund_wallet_withdraw as purchase_wallet_balance'),'tbl_dashboard.working_wallet as total income',DB::raw('tbl_dashboard.working_wallet - tbl_dashboard.working_wallet_withdraw as account_wallet_balance'),'tbl_dashboard.entry_time');
			$records = $qry->get();
			$res = $records->toArray();
			if (count($res) <= 0) {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
			}
			$var = $this->commonController->exportToExcel($res,"AllUsers");
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
		}


        $totalRecord  = $query->count('tbl_dashboard.id');
        $query        = $query->orderBy('tbl_dashboard.srno', 'desc');
         $totalworkingwall = $query->sum('working_wallet');
         $totalWithdrawwal = $query->sum('working_wallet_withdraw');
         $totalAccountWalletBal = $totalworkingwall - $totalWithdrawwal;

          $topupWallet = $query->sum('fund_wallet');
            $topupWalletWithdraw = $query->sum('fund_wallet_withdraw');
            $totalPurchaseWalletBal = $topupWallet - $topupWalletWithdraw;


        // $totalRecord  = $query->count();
        $arrWallet  = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrWallet;
        $arrData['total']  = number_format($totalAccountWalletBal, 2, '.', '');
        $arrData['balance'] = number_format($totalworkingwall, 2, '.', '');
        $arrData['topup_bal'] = number_format($totalPurchaseWalletBal, 2, '.', '');

        if (count($arrWallet) > 0) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'record found', $arrData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'record not found', '');
        }
    }
    public function getDexAccountWallet(Request $request)
    {

        $arrInput = $request->all();

        $query = Dashboard::join('tbl_users as tu', 'tu.id', 'tbl_dashboard.id')->where(DB::raw('tbl_dashboard.working_wallet - tbl_dashboard.working_wallet_withdraw'),'>=',20);

        if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d', strtotime($arrInput['to_date']));
            $query  = $query->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
        }
        if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
            //searching loops on fields


            
            $fields = ['tu.user_id',DB::raw('tbl_dashboard.working_wallet-tbl_dashboard.working_wallet_withdraw')];

            //getTableColumns('tbl_dashboard');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search) {
                foreach ($fields as $field) {
                    $query->orWhere($field, 'LIKE', '%' . $search . '%');
                }
            });
        }

        if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
            $qry = $query;
            $qry = $qry->selectRaw('tbl_dashboard.entry_time,tu.user_id,tu.fullname,ROUND(tbl_dashboard.working_wallet-tbl_dashboard.working_wallet_withdraw,2) as dex_wallet_balance');
            $records = $qry->get();
            $res = $records->toArray();
            if (count($res) <= 0) {
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
            }
            $var = $this->commonController->exportToExcel($res,"AllUsers");
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
        }
        
        $query = $query->selectRaw('tbl_dashboard.entry_time,tu.user_id,tu.fullname,ROUND(tbl_dashboard.working_wallet-tbl_dashboard.working_wallet_withdraw,2) as dex_wallet_balance');

        $totalRecord  = $query->count('tbl_dashboard.id');
        $query        = $query->orderBy('tbl_dashboard.srno', 'desc');
        $totalAccountWalletBal = $query->sum(DB::raw('working_wallet-working_wallet_withdraw'));

        $arrWallet  = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrWallet;
        $arrData['total']  = number_format($totalAccountWalletBal, 2, '.', '');

        if (count($arrWallet) > 0) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'record found', $arrData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'record not found', '');
        }
    }

    /**
     * get summary report for graphical views
     *
     * @return \Illuminate\Http\Response
     */
    public function getGraphicalSummary(Request $request)
    {
        $arrInput = $request->all();
        // validate the info, create rules for the inputs
        $rules = array(
            'frm_date'  => 'required',
            'to_date'   => 'required'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($arrInput, $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            $message = $validator->errors();
            return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'Input field is required or invalid', $message);
        } else {
            //input request from user
            $frm_date   = date('Y-m-d', strtotime($request->Input('frm_date')));
            $to_date    = date('Y-m-d', strtotime($request->Input('to_date')));

            //total registration summary count between two dates
            $registrationCount = User::selectRaw("DATE_FORMAT(entry_time,'%d-%m-%Y') as entry_time, COUNT(*) as total")
                ->whereBetween(DB::raw("(DATE_FORMAT(entry_time,'%Y-%m-%d'))"), [$frm_date, $to_date])
                ->groupBy(DB::raw("DATE_FORMAT(entry_time,'%d-%m-%Y')"))
                ->orderBy(DB::raw("DATE_FORMAT(entry_time,'%d-%m-%Y')"), 'asc')
                ->get();

            //total deposite transaction summary count between two dates
            $transCount = AddressTransaction::selectRaw("DATE_FORMAT(entry_time,'%d-%m-%Y') as entry_time, COUNT(*) as total")
                ->whereBetween(DB::raw("(DATE_FORMAT(entry_time,'%Y-%m-%d'))"), [$frm_date, $to_date])
                ->groupBy(DB::raw("DATE_FORMAT(entry_time,'%d-%m-%Y')"))
                ->orderBy(DB::raw("DATE_FORMAT(entry_time,'%d-%m-%Y')"), 'asc')
                ->get();

            //total deposite transaction pending summary count between two dates
            $transPendingCount = AddressTransactionPending::selectRaw("DATE_FORMAT(entry_time,'%d-%m-%Y') as entry_time, COUNT(*) as total")
                ->whereBetween(DB::raw("(DATE_FORMAT(entry_time,'%Y-%m-%d'))"), [$frm_date, $to_date])
                ->groupBy(DB::raw("DATE_FORMAT(entry_time,'%d-%m-%Y')"))
                ->orderBy(DB::raw("DATE_FORMAT(entry_time,'%d-%m-%Y')"), 'asc')
                ->get();

            $todayIcoPhaseSummy = TodaySummary::selectRaw('ANY_VALUE(DATE_FORMAT(date,"%d-%m-%Y")) as date,SUM(today_supply) as today_supply,SUM(today_sold) as today_sold,SUM(today_available) as today_available')
                ->whereBetween(DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']])
                ->groupBy(DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"))
                ->orderBy(DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"), 'desc')
                ->get();

            //build array of summary data
            $arrSummaryData['registrations'] = $registrationCount;
            $arrSummaryData['receivedtrans'] = $transCount;
            $arrSummaryData['receivedtranspending'] = $transPendingCount;
            $arrSummaryData['todayIcoPhaseSummy'] = $todayIcoPhaseSummy;

            if (!empty($arrSummaryData)) {
                return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Graphical Summary data found', $arrSummaryData);
            } else {
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Graphical Summary data not found', '');
            }
        }
    }

    /**
     * store curreny rate
     *
     * @return \Illuminate\Http\Response
     */
    public function storeCurrencyRate(Request $request)
    {
        $arrInput   = $request->all();

        $rules = array(
            'coin'      => 'required',
            'usd'       => 'required',
            'btc'       => 'required',
            'remark'    => 'required',
            'transaction_fee'  => 'required',
            'bonus'     => 'required',
            'min_coin'  => 'required'
        );
        $validator = Validator::make($arrInput, $rules);
        if ($validator->fails()) {
            $message = $validator->errors();
            return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], 'Input field is required or invalid', $message);
        } else {
            $arrInsert = [
                'coin'      => $arrInput['coin'],
                'usd'       => $arrInput['usd'],
                'btc'       => $arrInput['btc'],
                'remark'    => $arrInput['remark'],
                'transaction_fee' => $arrInput['transaction_fee'],
                'bonus'     => $arrInput['bonus'],
                'min_coin'  => $arrInput['min_coin'],
                'entry_time' => now(),
            ];
            $storeId    = Currencyrate::insertGetId($arrInsert);
            $arrUpdateData = ['status' => '0'];
            //active only last record and inactive all record
            Currencyrate::whereNotIn('srno', [$storeId])->update($arrUpdateData);
            if (!empty($storeId)) {
                return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Currency rate added successfully', '');
            } else {
                return sendresponse($this->statuscode[409]['code'], $this->statuscode[409]['status'], 'Error while adding currency rate', '');
            }
        }
    }

    /**
     * get all records of currency
     *
     * @return \Illuminate\Http\Response
     */
    public function getCurrency(Request $request)
    {
        $arrInput = $request->all();

        $query = Currency::query();
        if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d', strtotime($arrInput['to_date']));
            $query  = $query->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
        }
        if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
            //searching loops on fields
            $fields = getTableColumns('tbl_currency');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search) {
                foreach ($fields as $field) {
                    $query->orWhere($field, 'LIKE', '%' . $search . '%');
                }
            });
        }
        $totalRecord  = $query->count('id');
        $query        = $query->orderBy('id', 'desc');
        // $totalRecord  = $query->count();
        $arrCurrency  = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrCurrency;

        if (count($arrCurrency) > 0) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Currency record found', $arrData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Currency record not found', '');
        }
    }

    /**
     * get all records of currency rate
     *
     * @return \Illuminate\Http\Response
     */
    public function getCurrencyRate(Request $request)
    {
        $arrInput = $request->all();

        $query  = Currencyrate::query();
        if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d', strtotime($arrInput['to_date']));
            $query  = $query->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
        }
        if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
            //searching loops on fields
            $fields = getTableColumns('tbl_currency_rate');
            $search = $arrInput['search']['value'];
            $query = $query->where(function ($query) use ($fields, $search) {
                foreach ($fields as $field) {
                    $query->orWhere($field, 'LIKE', '%' . $search . '%');
                }
            });
        }
        $totalRecord     = $query->count('srno');
        $query           = $query->orderBy('srno', 'desc');
        // $totalRecord     = $query->count();
        $arrCurrencyRate = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrCurrencyRate;

        if (count($arrCurrencyRate) > 0) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Currency rate record found', $arrData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Currency rate record not found', '');
        }
    }

    public function getDashboardReport(Request $request)
    {
        $arrInput = $request->all();

        $query = Dashboard::join('tbl_users as tu', 'tu.id', '=', 'tbl_dashboard.id')
            ->select('tbl_dashboard.*', 'tu.user_id', 'tu.fullname');
        if (isset($arrInput['id'])) {
            $query = $query->where('tu.user_id', $arrInput['id']);
        }
        if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d', strtotime($arrInput['to_date']));
            $query  = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_dashboard.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
        }
        if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
            //searching loops on fields
            $fields = getTableColumns('tbl_dashboard');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search) {
                foreach ($fields as $field) {
                    $query->orWhere('tbl_dashboard.' . $field, 'LIKE', '%' . $search . '%');
                }
                $query->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
            });
        }
        $totalRecord  = $query->count('tbl_dashboard.srno');
        $query        = $query->orderBy('tbl_dashboard.srno', 'desc');
        // $totalRecord  = $query->count();
        $arrDashboard = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        foreach ($arrDashboard as $key => $value) {
            $level1Sum = ($value->direct_income - $value->direct_income_withdraw) +
                ($value->level_income - $value->level_income_withdraw) +
                ($value->roi_income - $value->roi_income_withdraw) +
                ($value->binary_income - $value->binary_income_withdraw) +
                ($value->top_up_wallet - $value->top_up_wallet_withdraw) +
                ($value->transfer_wallet - $value->transfer_wallet_withdraw) +
                ($value->working_wallet - $value->working_wallet_withdraw);

            $level2Sum = ($value->total_profit - $value->total_withdraw);
            if ($level1Sum == $value->usd) {
                $value->level_1 = 'True';
            } else {
                $value->level_1 = 'False';
            }
            if ($level2Sum == $value->usd) {
                $value->level_2 = 'True';
            } else {
                $value->level_2 = 'False';
            }
        }

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrDashboard;

        if (count($arrDashboard) > 0) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
        }
    }

    public function getSecurityDashboardSummary(Request $request)
    {
        $remainingtp = Topup::where('top_up_by','!=',1)->where('cross_verify_status',0)->count('srno');
        $validtp = Topup::where('top_up_by','!=',1)->where('cross_verify_status',1)->count('srno');
        $invalidtp = Topup::where('top_up_by','!=',1)->where('cross_verify_status',2)->count('srno');
        $checkedtp = $validtp + $invalidtp;

        $remainingwithdraw = WithdrawPending::where('cross_verify_status',0)->count('sr_no');
        $validwithdraw = WithdrawPending::where('cross_verify_status',1)->count('sr_no');
        $invalidwithdraw = WithdrawPending::where('cross_verify_status',2)->count('sr_no');
        $checkedwithdraw = $validwithdraw + $invalidwithdraw;

        $invalid_login = AddFailedLoginAttempt::count('id');

        $arrDashboard = array();

        $arrDashboard['remaining_topup'] = $remainingtp;
        $arrDashboard['valid_topup'] = $validtp;
        $arrDashboard['invalid_topup'] = $invalidtp;
        $arrDashboard['checked_topup'] = $checkedtp;

        $arrDashboard['remaining_withdraw'] = $remainingwithdraw;
        $arrDashboard['valid_withdraw'] = $validwithdraw;
        $arrDashboard['invalid_withdraw'] = $invalidwithdraw;
        $arrDashboard['checked_withdraw'] = $checkedwithdraw;

        $arrDashboard['invalid_login'] = $invalid_login;
        
        if (count($arrDashboard) > 0) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrDashboard);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
        }
    }

    public function getTopupsForSecurity(Request $request) {
        $arrInput = $request->all();

        $query = Topup::join('tbl_users as tu', 'tu.id', '=', 'tbl_topup.id')
            ->join('tbl_product as tp', 'tp.id', '=', 'tbl_topup.type')
            ->join('tbl_users as tu1', 'tu1.id', '=', 'tbl_topup.top_up_by')
            ->where('tbl_topup.top_up_by','!=',1);

        if (isset($arrInput['user_id'])) {
            $query = $query->where('tu.user_id', $arrInput['user_id']);
        }
        if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
            $arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_topup.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
        }

        $totalRecord = $query->count('tbl_topup.id');
        $query = $query->orderBy('tbl_topup.srno', 'desc');

        $totalRecord = $query->count();
        $query = $query->selectRaw('tbl_topup.srno,tbl_topup.topupfrom,tbl_topup.product_name,tbl_topup.ip_address,tu.user_id,tu.fullname,tbl_topup.pin,tbl_topup.amount,tbl_topup.roi_status,tbl_topup.roi_stop_status,tbl_topup.cross_verify_status,tp.name,tbl_topup.entry_time,tu1.user_id as top_up_by');
        $arrTopup = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal'] = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records'] = $arrTopup;

        if ($arrData['recordsTotal'] > 0) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
        }
    }

    public function getWithdrawalsForSecurity(Request $request) {
        $arrInput = $request->all();

        $query = WithdrawPending::join('tbl_users as tu', 'tu.id', '=', 'tbl_withdrwal_pending.id')
        ->leftjoin('tbl_country_new as cn','tu.country','=','cn.iso_code')
            ->select('tbl_withdrwal_pending.amount','tbl_withdrwal_pending.deduction','tbl_withdrwal_pending.to_address','tbl_withdrwal_pending.cross_verify_status','tbl_withdrwal_pending.entry_time','tbl_withdrwal_pending.network_type','tu.user_id', 'tu.fullname', DB::raw('(CASE tbl_withdrwal_pending.withdraw_type WHEN 2 THEN "Working" WHEN 3 THEN "Roi" WHEN 4 THEN "Self Working" WHEN  5 THEN "Self Roi" ELSE "" END) as wallet_type'),'cn.country')
            ->where('tbl_withdrwal_pending.status', 0);

        if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
            $arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_withdrwal_pending.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
        }
        if (isset($arrInput['id'])) {
            $query = $query->where('tu.user_id', $arrInput['id']);
        }
        if (isset($arrInput['country'])) {
            $query = $query->where('tu.country', $arrInput['country']);
        }
        if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
            //searching loops on fields
            $fields = getTableColumns('tbl_withdrwal_pending');
            $search = $arrInput['search']['value'];
            $query = $query->where(function ($query) use ($fields, $search) {
                foreach ($fields as $field) {
                    $query->orWhere('tbl_withdrwal_pending.' . $field, 'LIKE', '%' . $search . '%');
                }
                $query->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
            });
        }
        $totalRecord = $query->count('tbl_withdrwal_pending.id');
        $query = $query->orderBy('tbl_withdrwal_pending.sr_no', 'desc');

        $arrPendings = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal'] = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records'] = $arrPendings;

        if ($arrData['recordsTotal'] > 0) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
        }
    }

    public function getchecktopuptime(Request $request) {
        $arrInput = $request->all();

        $entry_time = Topup::select('entry_time')->orderBy('srno','asc')->pluck('entry_time')->first();

        $date = \Carbon\Carbon::parse($entry_time);
        $now = \Carbon\Carbon::now();        
        $timeW = date('H:i',strtotime($entry_time));
        $timenow = $now->toDateString() . $timeW;

        $startPoint = 0;
        if (@$arrInput['tCondition'] == 1) {
           $startPoint = $request->position;
        }else{
            $startPoint = $request->bckPosition;
        }
        $diff = $date->diffInHours($timenow);
        $usdData = array();
        for ($i=$startPoint; $i <= ($startPoint+9); $i++) {

            if ($diff > $startPoint) {
                $records = array();
                $records['t_time'] = date('H:i',strtotime($timenow.' - '.$i.' hours'));
                $records['t_date'] = date('d-m-Y',strtotime($timenow.' - '.$i.' hours'));
                array_push($usdData,$records);
                $diff = $date->diffInHours(date("Y-m-d H:i",strtotime($timenow.' - '.$i.' hours')));
            }
           
        }

        $arrData['records'] = $usdData;
        $arrData['recordsTotal'] = count($usdData);

        if ($arrData['recordsTotal'] > 0) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
        }
    }

    public function getcheckwithdrawtime(Request $request) {
        $arrInput = $request->all();

        $entry_time = WithdrawPending::select('entry_time')->orderBy('sr_no','asc')->pluck('entry_time')->first();

        $date = \Carbon\Carbon::parse($entry_time);
        $now = \Carbon\Carbon::now();
        $timeW = date('H:i',strtotime($entry_time));
        $timenow = $now->toDateString() . $timeW;

        $startPoint = 0;
        if (@$arrInput['wCondition'] == 1) {
           $startPoint = $request->position;
        }else{
            $startPoint = $request->bckPosition;
        }
        $diff = $date->diffInHours($timenow);
        $usdData = array();
        for ($i=$startPoint; $i <= ($startPoint+9); $i++) {

            if ($diff > $startPoint) {
                $records = array();
                $diff = $date->diffInHours(date("Y-m-d H:i",strtotime($timenow.' - '.$i.' hours')));
                $datediff = date("Y-m-d H:i",strtotime($timenow.' - '.$i.' hours'));
                if ($datediff < $timenow) {
                    $records['w_time'] = date('H:i ',strtotime($timenow.' - '.$i.' hours'));
                    $records['w_date'] = date('d-m-Y',strtotime($timenow.' - '.$i.' hours'));
                    array_push($usdData,$records);
                }
            }
           
        }

        $arrData['records'] = $usdData;
        $arrData['recordsTotal'] = count($usdData);

        if ($arrData['recordsTotal'] > 0) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
        }
    }

}
