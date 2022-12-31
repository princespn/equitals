<?php

namespace App\Http\Controllers\userapi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\userapi\TransactionConfiController;
use App\Models\Activitynotification;
use App\Models\Dashboard;
use App\Models\Invoice;
use App\Models\Topup;
use App\Models\WithdrawMode;
use App\Models\WithdrawPending;
use App\Models\SupperMatchingIncome;
use App\Models\freedomclubincome;
use App\Models\WithdrawalConfirmed;
use App\Models\ProjectSetting;
use App\Models\WithdrawSettings;
use App\Models\UserContestAchievement;
use App\Models\ContestPrizeSetting;
use App\Models\MatchingBonusGenerate;
use App\Models\UserSettingFund;
use App\Models\MatchingBonusIncomeSettings;
use App\Models\AchievedUserMatchingBonus;
use App\Models\News;
use App\User;
use Auth;
use Config;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;

class DashboardController extends Controller {

	public function __construct(TransactionConfiController $transaction) {
		$this->homepageDate = Config::get('constants.settings.homepageDate');
		$date = \Carbon\Carbon::now();
		$this->today = $date->toDateTimeString();
		$this->transaction = $transaction;
	}

	/**
	 * Get USER Dashboard currecy  details
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUserDashboardDetails(Request $request) {

		try {
			$id = Auth::user()->id;
			//dd($id);
			// get Dashboard Details
			$getDetails = User::select('tbl_users.l_ace', 'tbl_users.r_ace', 'tbl_users.l_herald', 'tbl_users.r_herald', 'tbl_users.l_crusader', 'tbl_users.r_crusader', 'tbl_users.l_guardian', 'tbl_users.r_guardian', 'tbl_users.l_commander', 'tbl_users.r_commander', 'tbl_users.l_valorant', 'tbl_users.r_valorant', 'tbl_users.l_legend', 'tbl_users.r_legend', 'tbl_users.l_relic', 'tbl_users.r_relic', 'tbl_users.contest_lbv', 'tbl_users.contest_rbv', 'tbl_users.l_almighty', 'tbl_users.r_almighty', 'tbl_users.l_conqueror', 'tbl_users.r_conqueror', 'tbl_users.l_titan', 'tbl_users.r_titan', 'tbl_users.l_lmmortal', 'tbl_users.r_immortal','tbl_users.is_franchise','tbl_users.user_id','tbl_users.fullname','tbl_users.rank','tbl_users.l_bv','tbl_users.r_bv','tbl_users.btc_address','tbl_dashboard.*')->join('tbl_dashboard', 'tbl_dashboard.id', '=', 'tbl_users.id')->where([['tbl_users.status', '=', 'Active'], ['tbl_users.type', '=', ''], ['tbl_dashboard.id', '=', $id]])->get();
 
			if (!empty($getDetails) && count($getDetails) > 0) {
				
				/*$mode1 = WithdrawMode::where('id', $id)->select('network_type')->orderBy('id', 'desc')->first();

				if (empty($mode1)) {
					$mode = Invoice::where('id', $id)->select('payment_mode')->orderBy('id', 'asc')->first();
					if (!empty($mode)) {
						$type = $mode->payment_mode;
					} else {
						$type = '';
					}
				} else {
					$mode = $mode1;
					$type = $mode->network_type;
				}
				if ($type == '') {
					$type = 'BTC';
				}
				if ($type != '') {
					if ($type == 'BTC') {
						$arrData['btc_address'] = $getDetails[0]->btc_address;
					} else if ($type == 'ETH') {
						$arrData['eth_address'] = $getDetails[0]->ethereum;
					} else {
						$arrData['btc_address'] = '';
						$arrData['eth_address'] = '';
					}
				}*/
				
				/*$supermatching_income=SupperMatchingIncome::where('id', $id)->sum('amount');	*/
				$supermatching_income=custom_round($getDetails[0]->supper_maching_income, 3);	
				/*$freedom_income	= freedomclubincome::where('user_id', $id)->sum('amount');*/
				$freedom_income	= custom_round($getDetails[0]->freedom_club_income, 3);

				$direct_list = User::where('ref_user_id',$id)->count('id');
				$direct_active = User::where([['ref_user_id',$id],['amount','>',0]])->count('id');
				$totalActive = DB::select("SELECT count(id) as total_active FROM `tbl_users` WHERE id IN (SELECT from_user_id FROM `tbl_today_details` WHERE to_user_id = ".$id.") AND status = 'Active' AND amount > 0");
				$totalInactive = DB::select("SELECT count(id) as total_inactive FROM `tbl_users` WHERE id IN (SELECT from_user_id FROM `tbl_today_details` WHERE to_user_id = ".$id.") AND status = 'Active' AND amount = 0");
				
				// dd($totalActive);
				$topupsum = Topup::where('id', $id)->sum('amount');

				$withdraw_amount = WithdrawalConfirmed::where('id', $id)->sum('amount');
				$total_withdraw_amount = WithdrawPending::where('id', $id)->whereIn('status',array(1,0))->sum('amount');


				$arrData['topup_amount'] = custom_round($topupsum , 7);
				// $arrData['direct_list'] = $totalActive->total_active;
				// $arrData['direct_active'] = $totalInactive->total_inactive;
				$arrData['totalActive'] = (isset($totalActive[0]->total_active) && !empty($totalActive[0]->total_active) ? $totalActive[0]->total_active : 0);
				$arrData['totalInactive'] = (isset($totalInactive[0]->total_inactive) && !empty($totalInactive[0]->total_inactive) ? $totalInactive[0]->total_inactive : 0);
				//$topupsuminves = Topup::where('id', $id)->sum('amount');
				$arrData['supermatching_income'] = custom_round($supermatching_income, 3);
				$arrData['freedom_income'] = custom_round($freedom_income, 3);
				/*$arrData['total_investment_dashboard'] = custom_round($topupsum, 3);*/
				$arrData['total_deposit'] = custom_round($topupsum, 3);
				$arrData['total_left_bv'] = $getDetails[0]->l_bv;
				$arrData['total_right_bv'] = $getDetails[0]->r_bv;
				$arrData['coin'] = $getDetails[0]->coin;
				$arrData['coin_withdrawal'] = $getDetails[0]->coin_withdrawal;
				$arrData['total_coin'] = $getDetails[0]->coin-$getDetails[0]->coin_withdrawal;
				$arrData['btc'] = custom_round($getDetails[0]->btc, 3);
				$arrData['usd'] = custom_round($getDetails[0]->usd, 3);
				$arrData['total_withdrawl_amount'] = custom_round($withdraw_amount, 3);
				$arrData['total_withdraw_amount'] = custom_round($total_withdraw_amount, 3);
				$arrData['is_franchise'] = $getDetails[0]->is_franchise;
				
				// $arrData['total_investment'] = custom_round($getDetails[0]->total_investment, 3);
				$arrData['active_investment'] = custom_round($getDetails[0]->active_investment, 3);
				$arrData['total_withdraw'] = custom_round($getDetails[0]->total_withdraw, 3);
				$arrData['total_profit'] = custom_round($getDetails[0]->total_profit, 3);
				// $arrData['total_investment'] = custom_round($getDetails[0]->total_investment, 3);
				$arrData['active_investment'] = custom_round($getDetails[0]->active_investment, 3);
				$arrData['franchise_income'] = custom_round($getDetails[0]->franchise_income, 3);
				$arrData['direct_income'] = custom_round($getDetails[0]->direct_income, 3);
				$arrData['direct_income_withdraw'] = custom_round($getDetails[0]->direct_income_withdraw, 3);
				$arrData['direct_wallet_balance'] = ($arrData['direct_income'] - $arrData['direct_income_withdraw']);

				$arrData['level_income'] = custom_round($getDetails[0]->level_income, 3);
				$arrData['level_income_roi'] = custom_round($getDetails[0]->level_income_roi, 3);
				$arrData['level_income_withdraw'] = custom_round($getDetails[0]->level_income_withdraw, 3);

				$arrData['level_income_balance'] = ($arrData['level_income'] - $arrData['level_income_withdraw']);

				$arrData['roi_income'] = custom_round($getDetails[0]->roi_income, 3);
				$arrData['roi_income_withdraw'] = custom_round($getDetails[0]->roi_income_withdraw, 3);

				$arrData['roi_income_balance'] = ($arrData['roi_income'] - $arrData['roi_income_withdraw']);

				$arrData['binary_income'] = custom_round($getDetails[0]->binary_income, 3);
				$arrData['binary_income_withdraw'] = custom_round($getDetails[0]->binary_income_withdraw, 3);

				$arrData['matching_bonus_income'] = custom_round($getDetails[0]->matching_bonus_income, 3);
				$arrData['matching_bonus_withdraw'] = custom_round($getDetails[0]->matching_bonus_income_withdraw, 3);

				$arrData['binary_income_balance'] = ($arrData['binary_income'] - $arrData['binary_income_withdraw']);

				$arrData['direct_income_balance'] = ($arrData['direct_income'] - $arrData['direct_income_withdraw']);

				$arrData['top_up_wallet'] = custom_round($getDetails[0]->top_up_wallet, 3);
				$arrData['top_up_wallet_withdraw'] = custom_round($getDetails[0]->top_up_wallet_withdraw, 3);


				$arrData['residual_income'] = custom_round($getDetails[0]->residual_income, 4);
				$arrData['residual_income_withdraw'] = custom_round($getDetails[0]->residual_income_withdraw, 3);
				$arrData['residual_income_balance'] = custom_round(($arrData['residual_income'] - $arrData['residual_income_withdraw']),3);

				$arrData['top_up_Wallet_balance'] = custom_round(($arrData['top_up_wallet'] - $arrData['top_up_wallet_withdraw']),3);

				$arrData['fund_wallet'] = custom_round($getDetails[0]->fund_wallet, 3);
				$arrData['fund_wallet_withdraw'] = custom_round($getDetails[0]->fund_wallet_withdraw, 3);
				
				$arrData['fund_Wallet_balance'] = custom_round(($arrData['fund_wallet'] - $arrData['fund_wallet_withdraw']),3);

				$arrData['transfer_wallet'] = custom_round($getDetails[0]->transfer_wallet, 3);
				$arrData['transfer_wallet_withdraw'] = custom_round($getDetails[0]->transfer_wallet_withdraw, 3);

				$arrData['transfer_Wallet_balance'] = custom_round(($arrData['transfer_wallet'] - $arrData['transfer_wallet_withdraw']),3);

				$arrData['working_wallet'] = custom_round($getDetails[0]->working_wallet, 3);

				$arrData['requite_wallet'] = custom_round($getDetails[0]->requite_wallet, 3);

				$arrData['total_earnings'] = ($arrData['working_wallet'] + $arrData['requite_wallet']); 

				$arrData['working_wallet_withdraw'] = custom_round($getDetails[0]->working_wallet_withdraw, 3);

				$work = ($arrData['working_wallet'] - $arrData['working_wallet_withdraw']);

				$arrData['working_Wallet_balancenew'] = custom_round($work, 3);

				$arrData['leadership_income'] = custom_round($getDetails[0]->leadership_income, 3);
				$arrData['leadership_income_withdraw'] = custom_round($getDetails[0]->leadership_income_withdraw, 3);

				$arrData['leadership_Wallet_balance'] = ($arrData['leadership_income'] - $arrData['leadership_income_withdraw']);

				$arrData['level_income_roi'] = custom_round($getDetails[0]->level_income_roi, 3);
				$arrData['level_income_roi_withdraw'] = custom_round($getDetails[0]->level_income_roi_withdraw, 3);

				$arrData['level_income_roi_balance'] = ($arrData['level_income_roi'] - $arrData['level_income_roi_withdraw']);

				$arrData['upline_income'] = custom_round($getDetails[0]->upline_income, 3);
				$arrData['upline_income_withdraw'] = custom_round($getDetails[0]->upline_income_withdraw, 3);

				$arrData['upline_balance'] = ($arrData['upline_income'] - $arrData['upline_income_withdraw']);

				$arrData['award_income'] = custom_round($getDetails[0]->award_income, 3);
				$arrData['award_income_withdraw'] = custom_round($getDetails[0]->award_income_withdraw, 3);

				$arrData['award_balance'] = ($arrData['award_income'] - $arrData['award_income_withdraw']);

				$arrData['promotional_income'] = custom_round($getDetails[0]->promotional_income, 3);
				$arrData['passive_income'] = custom_round($getDetails[0]->passive_income, 3);
				$arrData['passive_income_withdraw'] = custom_round($getDetails[0]->passive_income_withdraw, 3);
				$arrData['passive_income_balance'] = custom_round(($arrData['passive_income']-$arrData['passive_income_withdraw']), 3);
				$rankDetails = AchievedUserMatchingBonus::where([['user_id',$id],['status',0]])->first();
				// dd($rankDetails);
				if(!empty($rankDetails)){
					$nextRankDetails = MatchingBonusIncomeSettings::where([['id','>',$rankDetails->perform_id],['status','Active']])->first();					
					$arrData['current_reward'] = $rankDetails->amount;
				}else{
					$nextRankDetails = MatchingBonusIncomeSettings::where('status','Active')->first();					
					$arrData['current_reward'] = 0;
				}
				if(!empty($nextRankDetails)){
					$arrData['req_lbv'] = $nextRankDetails->left_bv - $getDetails[0]->l_bv;
					$arrData['req_rbv'] = $nextRankDetails->right_bv - $getDetails[0]->r_bv;
					$arrData['req_amt'] = $nextRankDetails->amount;
					if($arrData['req_lbv'] < 0){
						$arrData['req_lbv'] = 0;
					}
					if($arrData['req_rbv'] < 0){
						$arrData['req_rbv'] = 0;
					}
				}else{
					$arrData['req_amt'] = 0;
					$arrData['req_lbv'] = 0;
					$arrData['req_rbv'] = 0;
				}
				// dd($nextRankDetails);
				// dd($rankDetails);

				$arrData['server_time'] = \Carbon\Carbon::now()->format('H:i:s');
				$arrData['joining_date'] = $getDetails[0]->entry_time;
				$arrData['user_id'] = $getDetails[0]->user_id;
				$arrData['fullname'] = $getDetails[0]->fullname;

				$arrData['l_ace']=$getDetails[0]->l_ace;
				$arrData['r_ace']=$getDetails[0]->r_ace;
				$arrData['l_herald']=$getDetails[0]->l_herald;
				$arrData['r_herald']=$getDetails[0]->r_herald;
				$arrData['l_crusader']=$getDetails[0]->l_crusader;
				$arrData['r_crusader']=$getDetails[0]->r_crusader;
				$arrData['l_guardian']=$getDetails[0]->l_guardian;
				$arrData['r_guardian']=$getDetails[0]->r_guardian;
				$arrData['l_commander']=$getDetails[0]->l_commander;
				$arrData['r_commander']=$getDetails[0]->r_commander;
				$arrData['l_valorant']=$getDetails[0]->l_valorant;
				$arrData['r_valorant']=$getDetails[0]->r_valorant;
				$arrData['l_legend']=$getDetails[0]->l_legend;
				$arrData['r_legend']=$getDetails[0]->r_legend;
				$arrData['l_relic']=$getDetails[0]->l_relic;
				$arrData['r_relic']=$getDetails[0]->r_relic;
				$arrData['l_almighty']=$getDetails[0]->l_almighty;
				$arrData['r_almighty']=$getDetails[0]->r_almighty;
				$arrData['l_conqueror']=$getDetails[0]->l_conqueror;
				$arrData['r_conqueror']=$getDetails[0]->r_conqueror;
				$arrData['l_titan']=$getDetails[0]->l_titan;
				$arrData['r_titan']=$getDetails[0]->r_titan;
				$arrData['l_lmmortal']=$getDetails[0]->l_lmmortal;
				$arrData['r_immortal']=$getDetails[0]->r_immortal;

				$arrData['total_left_rank_count'] = $getDetails[0]->l_ace + $getDetails[0]->l_herald + $getDetails[0]->l_crusader + $getDetails[0]->l_guardian + $getDetails[0]->l_commander + $getDetails[0]->l_valorant + $getDetails[0]->l_legend + $getDetails[0]->l_relic +$getDetails[0]->l_almighty + $getDetails[0]->l_conqueror + $getDetails[0]->l_titan + $getDetails[0]->l_lmmortal;
				$arrData['total_right_rank_count'] = $getDetails[0]->r_ace + $getDetails[0]->r_herald + $getDetails[0]->r_crusader + $getDetails[0]->r_guardian + $getDetails[0]->r_commander + $getDetails[0]->r_valorant + $getDetails[0]->r_legend + $getDetails[0]->r_relic + $getDetails[0]->r_almighty + $getDetails[0]->r_conqueror + $getDetails[0]->r_titan +  $getDetails[0]->r_immortal;
				
				//dd($getDetails[0]->rank);
				/*if($getDetails[0]->rank == null){
					$rank = "You dont have Rank Yet";
				}
				else{
					$rank = $getDetails[0]->rank;
				}*/
				
				$arrData['rank'] = $getDetails[0]->rank;
				
				$arrData['lid'] = $getDetails[0]->l_c_count;
				$arrData['rid'] = $getDetails[0]->r_c_count;
				$arrData['l_bv'] = $getDetails[0]->l_bv;
				$arrData['r_bv'] = $getDetails[0]->r_bv;
				$arrData['contest_rbv'] = $getDetails[0]->contest_rbv;
				$arrData['contest_lbv'] = $getDetails[0]->contest_lbv;

				$arrData['sent_balance'] = custom_round($getDetails[0]->sent_balance,3);
				$arrData['received_balance'] = custom_round($getDetails[0]->received_balance,3);
				
				$arrData['ip_address'] = $_SERVER['REMOTE_ADDR'];
				
				$current_time = getTimeZoneByIP($arrData['ip_address']);
				$arrData['current_time'] = $current_time;
				$arrData['total_income'] = custom_round(custom_round($getDetails[0]->direct_income, 3) + custom_round($getDetails[0]->level_income, 3) + custom_round($getDetails[0]->roi_income, 3) + custom_round($getDetails[0]->level_income_roi, 3), 3);

				// $arrData['total_income_amt'] = custom_round(custom_round($getDetails[0]->working_wallet, 3) + custom_round($getDetails[0]->requite_wallet, 3);
				
				$topup_time = Topup::join('tbl_product','tbl_product.id','=','tbl_topup.type')
				->select('tbl_topup.duration','tbl_topup.entry_time','tbl_topup.total_roi_count','tbl_product.date_diff')->where('tbl_topup.id', $id)->orderBy('tbl_topup.entry_time', 'desc')->first();
				if (!empty($topup_time)) {

					$topup_entry_time = $topup_time->entry_time;
					$topup_diff = $topup_time->date_diff;

					$retop_date =date('Y-m-d H:i:s', strtotime($topup_entry_time . " + " . $topup_diff . " days"));
					$now = \Carbon\Carbon::now();
															
					$date1 = \Carbon\Carbon::parse($retop_date);
					$day_diff = $now->diff($date1)->format("%a");
					
					$arrData['retopup_days'] = $day_diff;

					$arrData['show_popup'] = 0;
					if($arrData['retopup_days'] == 1)
					{
						$arrData['show_popup'] = 1;
					}else
					{
						$arrData['show_popup'] = 0;
					}

				}
				/*$topupdata = Topup::where('id', $id)->orderBy('entry_time', 'desc')->select('amount')->first();
				if (!empty($topupdata)) {
					$arrData['last_investment'] = $topupdata->amount;
				} else {
					$arrData['last_investment'] = 0;
				}*/


				$path = Config::get('constants.settings.domainpath');
				$dataArr = array();

				$arrData['link'] = $path . '/user#/register/?ref_id=' . Auth::user()->unique_user_id/*.'&' .'position='.Auth::user()->position*/;

				/*$withPending = WithdrawPending::where('id', $id)->orderBy('entry_time', 'desc')->where('withdraw_type', 3)->first();
				if (!empty($withPending)) {
					$arrData['last_roi_withdraw'] = $withPending->amount;
					$arrData['pending_roi_withdraw'] = WithdrawPending::where('id', $id)->where('status', 0)->where('withdraw_type', 3)->sum('amount');
					$arrData['confirmed_roi_withdraw'] = WithdrawPending::where('id', $id)->where('status', 1)->where('withdraw_type', 3)->sum('amount');
				} else {
					$arrData['last_roi_withdraw'] = 0;
					$arrData['pending_roi_withdraw'] = 0;
					$arrData['confirmed_roi_withdraw'] = 0;

				}*/

				/*$withConfirm = WithdrawPending::where('id', $id)->orderBy('entry_time', 'desc')->where('withdraw_type', 2)->first();
				if (!empty($withConfirm)) {
					$arrData['last_working_withdraw'] = $withConfirm->amount;
					$arrData['pending_working_withdraw'] = WithdrawPending::where('id', $id)->where('status', 0)->where('withdraw_type', 2)->sum('amount');
					$arrData['confirmed_working_withdraw'] = WithdrawPending::where('id', $id)->where('status', 1)->where('withdraw_type', 2)->sum('amount');
				} else {
					$arrData['last_working_withdraw'] = 0;
					$arrData['pending_working_withdraw'] = 0;
					$arrData['confirmed_working_withdraw'] = 0;
				}*/

				//$arrData['last_roi_withdraw'] = WithdrawPending::where('id',$id)->orderBy('entry_time','desc')->where('withdraw_type',3)->first()->pluck('amount');

				// $arrData['last_working_withdraw'] = WithdrawPending::where('id',$id)->orderBy('entry_time','desc')->where('withdraw_type',2)->first()->pluck('amount');

				/*$login_time = Activitynotification::where('id', $getDetails[0]->id)->orderBy('srno', 'desc')->pluck('entry_time')->first();
				$arrData['login_time'] = $login_time;*/

				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);

			} else {

				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data not found';
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

	/**
	 * Get PerfectMoney
	 **/
	public function getPerfectMoneyCred() {
		try {
			$user = Auth::user();
			// get Dashboard Details

			if (!empty($user)) {
				$getDetails = Config::get('constants.perfectmoney_credential');
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $getDetails);

			}
		} catch (Exception $e) {
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}
	/**
	 * Get PerfectMoney
	 **/
	public function getManualPayInfo() {
		try {dd('Not in use');
			$user = DB::table('tbl_manual_pay')->where('status', 1)->get();

			if (!empty($user)) {
				$getDetails = $user;
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $getDetails);

			}
		} catch (Exception $e) {
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

	/************* WEBSITE ADMIN AREA TO PUT PERFECT MONEY CREDENTIALS ********/
	// PM = PERFECT MONEY
	/*************************************************************************/
	//Check Valid PerfectMoney Account
	/*  public function getNewPerfectmoney(){
	        try{

	            $pm_member_id = "1000589";
	            $pm_phrase = "U78bD6Fd5P5pOTopbziIyaCUI";
	            $pm_usd_account = "U22384420";
	            $account_id="U17876799";
	            $pmamount="1";
	            $payment_id="PAYMENT ID GENERATED BY WEBSITE";

	            $f=fopen('https://perfectmoney.is/acct/acc_name.asp?AccountID='.$pm_member_id.'&PassPhrase='.$pm_phrase.'&Account='.$account_id, 'rb');
	            if($f===false)
	            {
	              echo 'Invalid url parameter';
	            }
	            $out="";
	            while(!feof($f)) $out.=fgets($f);
	            fclose($f);
	            $error = explode(":",$out);
	            if($error[0] == "ERROR")
	            {
	               echo "Perfect Money Account ID not Valid.";
	            }

	        }catch(Exception $e){
	            dd($e);

	        }
*/

	/**
	 * Get Working Balance
	 **/
	public function getWorkingBalance() {
		try {
			$id = Auth::user()->id;
			// get Dashboard Details
			$getDetails = Dashboard::where('id', $id)->select('working_wallet', 'working_wallet_withdraw')->first();
			$bal = custom_round(($getDetails->working_wallet - $getDetails->working_wallet_withdraw),3);
			if ($bal > 0) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);

			}else{
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data not found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);
			}
		} catch (Exception $e) {
			$bal = 0;
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);
		}
	}

	/**
	 * Get Working Balance
	 **/
	public function getFundWalletBalance() {
		try {
			$id = Auth::user()->id;
			// get Dashboard Details
			$getDetails = Dashboard::where('id', $id)->select('fund_wallet', 'fund_wallet_withdraw')->first();
			$bal = custom_round(($getDetails->fund_wallet - $getDetails->fund_wallet_withdraw),3);
			if ($bal > 0) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);

			}else{
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data not found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);
			}
		} catch (Exception $e) {
			$bal = 0;
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);
		}
	}
	//getRoiBalance
	public function getRoiBalance() {

		try {
			$id = Auth::user()->id;
			// get Dashboard Details
			$getDetails = Dashboard::where('id', $id)->select('roi_income','roi_income_withdraw')->first();
			$bal = custom_round(($getDetails->roi_income - $getDetails->roi_income_withdraw),3);

			if ($bal > 0) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);

			}else{
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data not found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);
			}
		} catch (Exception $e) {
			$bal = 0;
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);
		}
	}

	//getRequiteBalance
	public function getRequiteBalance() {

		try {
			$id = Auth::user()->id;
			// get Dashboard Details
			$getDetails = Dashboard::where('id', $id)->select('requite_wallet','requite_wallet_withdraw')->first();
			$bal = custom_round(($getDetails->requite_wallet - $getDetails->requite_wallet_withdraw),3);

			if ($bal > 0) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);

			}else{
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data not found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);
			}
		} catch (Exception $e) {
			$bal = 0;
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);
		}
	}

	public function getWorkingWithdrawalDeduction() {
		try {
			// $id = Auth::user()->id;
			// get Dashboard Details
			$deduction = WithdrawSettings::where('income','=', 'wallet_withdrawal')->pluck('deduction')->first();
			if (!empty($deduction)) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $deduction);

			}else{
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data not found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $deduction);
			}
		} catch (Exception $e) {
			$deduction = 0;
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $deduction);
		}
	}

	public function getRoiWithdrawalDeduction() {
		try {
			// $id = Auth::user()->id;
			// get Dashboard Details
			$deduction = WithdrawSettings::where('income','=', 'roi_balance')->pluck('deduction')->first();
			if (!empty($deduction)) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $deduction);

			}else{
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data not found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $deduction);
			}
		} catch (Exception $e) {
			$deduction = 0;
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $deduction);
		}
	}
	/**
	 * Get Working Balance
	 **/
	public function getTopupBalance() {
		try {
			$id = Auth::user()->id;
			// get Dashboard Details
            $users = User::where([['id', '=', $id], ['status', '=', 'Active']])->first();
			$bal['user_id'] = $users->user_id;
			$getDetails = Dashboard::where('id', $id)->select('top_up_wallet', 'top_up_wallet_withdraw','fund_wallet','fund_wallet_withdraw')->first();
			// dd($getDetails);
			if (!empty($getDetails)) {
				$bal['purchase_wallet'] = custom_round($getDetails->top_up_wallet - $getDetails->top_up_wallet_withdraw,3);
				$bal['fund_wallet'] = custom_round($getDetails->fund_wallet - $getDetails->fund_wallet_withdraw,3);
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);

			}
		} catch (Exception $e) {
			$bal = 0;
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);
		}
	}
	public function getWalletBalance(Request $request) {
		// dd($request->all());
		try {
			$rules = array(
				'wallet_type' => 'required',
			);
			$validator = checkvalidation($request->all(), $rules, '');
			if (!empty($validator)) {
				return sendresponse(Response::HTTP_NOT_FOUND, Response::$statusTexts[404] , $validator,[]);
			}
			$id = Auth::user()->id;
			$wallet_type = $request->wallet_type;
			$balance = 0;
			if ($wallet_type == "working_wallet") {
				$balance =  Dashboard::where('id', $id)->selectRaw('round(working_wallet-working_wallet_withdraw,2) as balance')->pluck('balance')->first();
			}elseif ($wallet_type == "top_up_wallet") {
				$balance =  Dashboard::where('id', $id)->selectRaw('round(top_up_wallet-top_up_wallet_withdraw,2) as balance')->pluck('balance')->first();
			}elseif ($wallet_type == "fund_wallet") {
				$balance =  Dashboard::where('id', $id)->selectRaw('round(fund_wallet-fund_wallet_withdraw,2) as balance')->pluck('balance')->first();
			}elseif ($wallet_type == "passive_income") {
				$balance =  Dashboard::where('id', $id)->selectRaw('round(passive_income-passive_income_withdraw,2) as balance')->pluck('balance')->first();
			}elseif ($wallet_type == "passive_income_withdraw") {
				$bal =  Dashboard::where('id', $id)->selectRaw('round(passive_income-passive_income_withdraw,2) as balance')->pluck('balance')->first();
				/*$checksunday = date("Y-m-d");
				$getlastsun = ProjectSettings::select('fourth_sunday_date')->pluck('fourth_sunday_date')->first();
				$fourthsunday = date("Y-m-d", strtotime("" . ' fourth sunday'));
				if ($checksunday == $fourthsunday) {
					$checkWexist = WithdrawPending::where('id',$id)->whereBetween(DB::raw("DATE_FORMAT(tbl_withdrwal_pending.entry_time,'%Y-%m-%d')"), [date("Y-m-01"), $fourthsunday])
						->count('sr_no');
					if ($checkWexist > 0) {
						$balance = $bal / 10;
					} else {
						$balance = $bal / 20;
					}
					
				} else {
					$balance = $bal / 20;
				}*/

				$balance = ($bal * 2.5)/100;
				
			}
			
			if ($balance > 0) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $balance);

			}else{				
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data not found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $balance);
			}
		} catch (Exception $e) {dd($e);
			$bal = 0;
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);
		}
	}
	/**
	 * show homepage data
	 * @param  Request $request [description]
	 * @return [type]           [Json Array]
	 */
	public function homepageUserdata() {
		try {
			$date = \Carbon\Carbon::parse($this->homepageDate);
			$now = \Carbon\Carbon::now();

			$getDetails = User::join('tbl_dashboard', 'tbl_dashboard.id', '=', 'tbl_users.id')->selectRaw('sum(active_investment) as Total_deposit,sum(total_withdraw) as Total_withdraw,count(*) as Total_users')->where([['tbl_users.type', '=', '']])->get();
			if (!empty($getDetails)) {
				$diff = $date->diffInDays($now);

				$arr = array();
				$arr['Datediff'] = $diff;
				$arr['Total_deposit'] = $getDetails[0]->Total_deposit;
				$arr['Total_withdraw'] = $getDetails[0]->Total_withdraw;
				$arr['Total_users'] = $getDetails[0]->Total_users;

				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found successfully';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $arr);

			} else {

				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data not found';
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
     * [getNews description]
     * @param  Request $request [frm_date:   to_date:  start:  length:]
     * @return [type]           [description]
     */
    public function getNews(Request $request) {
        $query = News::select('sub','text','entry_time')->where('status', 'Active')->orderBy('entry_time', 'desc')->get();
        if (!empty($query)) {
        	    $arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found successfully';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $query);
           
        } else {
                $arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data not found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
    }

/**
	 * Get USER Dashboard currecy  details
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUsernavigationDetails(Request $request) {

		try {
			$id = Auth::user()->id;
			// get Dashboard Details
			$getDetails = User::select('tbl_users.user_id','tbl_users.fullname','tbl_users.rank')
			
	       ->where([['tbl_users.status', '=', 'Active'], ['tbl_users.type', '=', ''],['tbl_users.id', '=', $id]])
	       ->get();
         $proSetting=ProjectSetting::select('ico_status','ico_admin_error_msg')->first();

			if (!empty($getDetails) && count($getDetails) > 0) {

				$direct_list = User::where('ref_user_id',$id)->count();			
				$arrData['server_time'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
				$arrData['user_id'] = $getDetails[0]->user_id;
				$arrData['fullname'] = $getDetails[0]->fullname;
									
				$arrData['rank'] = $getDetails[0]->rank;
							
				$arrData['ip_address'] = $_SERVER['REMOTE_ADDR'];
				
				$current_time = getTimeZoneByIP($arrData['ip_address']);
				$arrData['current_time'] = $current_time;
				$arrData['ico_status'] = $proSetting->ico_status;
				$arrData['ico_admin_error_msg'] = $proSetting->ico_admin_error_msg;
				$path = Config::get('constants.settings.domainpath');
				$dataArr = array();

				$arrData['link'] = $path . '/user#/register/?ref_id=' . Auth::user()->unique_user_id/*.'&' .'position='.Auth::user()->position*/;
					// /	  dd($proSetting->ico_status,$arrData);		
			$dataArr = array();
						
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);

			} else {

				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data not found';
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

	public function getIcoStatus(){

		try {

			$arrData=ProjectSetting::select('ico_status','ico_admin_error_msg')->first();

			$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
			
		} catch (Exception $e) {
				$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

	public function getUserContestData(){

		try {
			$id = Auth::user()->id;

			$arrData=ContestPrizeSetting::select('uca.id','tbl_contest_setttings.required_left_commanders','tbl_contest_setttings.required_right_commanders','tbl_contest_setttings.contest_prize','uca.claim_status')
					->leftjoin('tbl_user_contest_achievment as uca', function ($arrData) use ($id) {
			            $arrData->on('uca.contest_id','=','tbl_contest_setttings.id')
			                 ->where('uca.user_id',$id);
			        })
					->get();

			$arrStatus = Response::HTTP_OK;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Data found';
			return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
			
		} catch (Exception $e) {
			dd($e);
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

	public function claimPrize(Request $request){

		try {

			$rules = array(
				'id' => 'required',
			);
			$validator = checkvalidation($request->all(), $rules, '');
			if (!empty($validator)) {
				return sendresponse(Response::HTTP_NOT_FOUND, Response::$statusTexts[404] , $validator,[]);
			}
			$user_id = Auth::user()->id;
			$updt=UserContestAchievement::where('user_id',$user_id)->where('id',$request->id)->update(['claim_status'=>1]);
			UserContestAchievement::where('user_id',$user_id)->where('id','!=',$request->id)->update(['claim_status'=>3]);
			if($updt){
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Contest prize claimed successfully';
				return sendResponse($arrStatus, $arrCode, $arrMessage, []);
			}else{
				$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Something went wrong,Please try again';
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

	public function getSettingBalance()
    {
        try {
            $id = Auth::user()->id;
            $fund = UserSettingFund::where('user_id',$id)->orderBy('entry_time','desc')->first();
            if (!empty($fund)) {
                // get Dashboard Details
                $getDetails = Dashboard::where('id', $id)->select(/*'top_up_wallet', 'top_up_wallet_withdraw', */'fund_wallet', 'fund_wallet_withdraw', 'setting_fund_wallet', 'setting_fund_wallet_withdraw')->first();
                if (!empty($getDetails)) {
                    /*$bal['purchase_wallet'] = custom_round($getDetails->top_up_wallet - $getDetails->top_up_wallet_withdraw, 2);*/
                    /*$bal['fund_wallet'] = custom_round($getDetails->fund_wallet - $getDetails->fund_wallet_withdraw, 2);*/
                    $bal['setting_wallet'] = (float)((int)(($getDetails->setting_fund_wallet - $getDetails->setting_fund_wallet_withdraw) * pow(10, 3)) / pow(10, 3));
                    $bal['show_wallet'] = 1;
                    $bal['topup_percentage'] = $fund->topup_percentage;
                    $arrStatus = Response::HTTP_OK;
                    $arrCode = Response::$statusTexts[$arrStatus];
                    $arrMessage = 'Data found';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);
                }else{
                    $arrStatus = Response::HTTP_NOT_FOUND;
                    $arrCode = Response::$statusTexts[$arrStatus];
                    $arrMessage = 'User not found';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, []);
                }
            }else{
                $arrStatus = Response::HTTP_NOT_FOUND;
                $arrCode = Response::$statusTexts[$arrStatus];
                $arrMessage = 'Data not found';
                return sendResponse($arrStatus, $arrCode, $arrMessage, []);
            }
        } catch (Exception $e) {dd($e);
            $bal = 0;
            $arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            $arrCode = Response::$statusTexts[$arrStatus];
            $arrMessage = 'Something went wrong,Please try again';
            return sendResponse($arrStatus, $arrCode, $arrMessage, $bal);
        }
    }

}
