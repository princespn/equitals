<?php

namespace App\Http\Controllers\userapi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\userapi\CurrencyConvertorController;
use App\Models\AddressTransaction;
use App\Models\AllTransaction;
use App\Models\DailyBonus;
use App\Models\ResidualIncome;
use App\Models\DirectIncome;
use App\Models\FranchiseIncome;
use App\Models\Invoice;
use App\Models\LeadershipIncome;
use App\Models\MatchingBonusGenerate;
use App\Models\LevelIncome;
use App\Models\LevelIncomeRoi;
use App\Models\LevelView;
use App\Models\PayoutHistory;
use App\Models\Product;
use App\Models\Topup;
use App\Models\TopupRequest;
use App\Models\TransactionInvoice;
use App\Models\AchievedUserMatchingBonus;
use App\Models\UplineIncome;
use App\Models\WithdrawalConfirmed;
use App\Models\WithdrawPending;
use App\Models\SupperMatchingIncome;
use App\Models\supermatching;
use App\Models\collectusd;
use App\Models\UserStructureModel;
use App\Models\Rank;
use App\Models\FundTransfer;
use App\Models\BalanceTransfer;
use App\Models\PurchaseBalanceTransfer;
use App\Models\WorkingToPurchaseTransfer;
use App\Models\DexToPurchaseFundTransfer;
use App\Models\TodayDetails;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


class ReportsController extends Controller {

	public function __construct(CurrencyConvertorController $currency) {
		$this->statuscode = Config::get('constants.statuscode');
		$date = \Carbon\Carbon::now();
		$this->today = $date->toDateTimeString();
		$this->currency = $currency;
	}

	/**
	 * Leadership income report.
	 *
	 * @return void
	 */

	public function LeadershipIncomeReport(Request $request) {

		try {

			$userId = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($userId)) {
				$Incomedata = LeadershipIncome::join('tbl_users as tu', 'tu.id', '=', 'tbl_leadership_income.toUserId')
					->join('tbl_users as tu1', 'tu1.id', '=', 'tbl_leadership_income.fromUserId')
					->join('tbl_product as tp', 'tp.id', '=', 'tbl_leadership_income.type')
					->select('tbl_leadership_income.*', 'tu.user_id as to_user_id', 'tu1.user_id as from_user_id', 'tp.name as plan_name', DB::raw('(CASE  WHEN tbl_leadership_income.status = 1 THEN "Paid" END ) as status'))
					->where([['tbl_leadership_income.fromUserId', '=', $userId], ['tbl_leadership_income.status', '=', 1]])
					->orderBy('tbl_leadership_income.entry_time', 'desc');

				if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_leadership_income');
					$search = $request->input('search')['value'];
					$Incomedata = $Incomedata->where(function ($Incomedata) use ($fields, $search) {
						foreach ($fields as $field) {
							$Incomedata->orWhere('tbl_leadership_income.' . $field, 'LIKE', '%' . $search . '%');
						}
						$Incomedata->orWhere('tu.user_id', 'LIKE', '%' . $search . '%')
							->orWhere('tu1.user_id', 'LIKE', '%' . $search . '%')
							->orWhere('tp.name', 'LIKE', '%' . $search . '%')
							->orWhere(DB::raw('(CASE  WHEN tbl_leadership_income.status = 1 THEN "Paid" END )'), 'LIKE', '%' . $search . '%');
					});
				}
				$totalRecord = $Incomedata->count('tbl_leadership_income.id');
				$arrPendings = $Incomedata->skip($request->input('start'))->take($request->input('length'))->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				if (!empty($arrPendings) && count($arrPendings) != '0') {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Leadership income data found successfully';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Leadership income data not found';
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

	/**
	 * Level income report.
	 *
	 * @return void
	 */
	public function LevelIncomeReport(Request $request) {
		try {
			$userId = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($userId)) {
				$Incomedata = LevelIncome::join('tbl_users as tu', 'tu.id', '=', 'tbl_level_income.toUserId')
					->join('tbl_users as tu1', 'tu1.id', '=', 'tbl_level_income.fromUserId')
					->join('tbl_product as tp', 'tp.id', '=', 'tbl_level_income.type')
					->select('tbl_level_income.*', 'tu.user_id as to_user_id', 'tu1.user_id as from_user_id', 'tu.fullname as to_fullname', 'tu1.fullname as from_fullname', 'tp.name as plan_name', DB::raw('(CASE  WHEN tbl_level_income.status = 1 THEN "Paid" END ) as status'))
					->where([['tbl_level_income.toUserId', '=', $userId], ['tbl_level_income.status', '=', 1]])
					->orderBy('tbl_level_income.entry_time', 'desc');

				if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_level_income');
					$search = $request->input('search')['value'];
					$Incomedata = $Incomedata->where(function ($Incomedata) use ($fields, $search) {
						foreach ($fields as $field) {
							$Incomedata->orWhere('tbl_level_income.' . $field, 'LIKE', '%' . $search . '%');
						}
						$Incomedata->orWhere('tu.user_id', 'LIKE', '%' . $search . '%')
							->orWhere('tu1.user_id', 'LIKE', '%' . $search . '%')
							->orWhere('tp.name', 'LIKE', '%' . $search . '%')
							->orWhere(DB::raw('(CASE  WHEN tbl_level_income.status = 1 THEN "Paid" END )'), 'LIKE', '%' . $search . '%');
					});
				}

				$totalRecord = $Incomedata->count('tbl_level_income.id');
				$arrPendings = $Incomedata->skip($request->input('start'))->take($request->input('length'))->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) != '0') {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Level income data found successfully';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Level income data not found';
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

	/**
	 * Level income report.
	 *
	 * @return void
	 */
	public function LevelIncomeRoiReport(Request $request) {

		try {
			$userId = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($userId)) {
				$Incomedata = LevelIncomeRoi::join('tbl_users as tu', 'tu.id', '=', 'tbl_level_income_roi.toUserId')->join('tbl_users as tu1', 'tu1.id', '=', 'tbl_level_income_roi.fromUserId')
					->join('tbl_product as tp', 'tp.id', '=', 'tbl_level_income_roi.type')
					->select('tbl_level_income_roi.*', 'tu.user_id as to_user_id', 'tu1.user_id as from_user_id', 'tu.fullname as to_fullname', 'tu1.fullname as from_fullname', 'tp.name as plan_name', DB::raw('(CASE  WHEN tbl_level_income_roi.status = 1 THEN "Paid" END ) as status'))
					->where([['tbl_level_income_roi.toUserId', '=', $userId], ['tbl_level_income_roi.status', '=', 1]])
					->orderBy('tbl_level_income_roi.entry_time', 'desc');

				if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_level_income_roi');
					$search = $request->input('search')['value'];
					$Incomedata = $Incomedata->where(function ($Incomedata) use ($fields, $search) {
						foreach ($fields as $field) {
							$Incomedata->orWhere('tbl_level_income_roi.' . $field, 'LIKE', '%' . $search . '%');
						}
						$Incomedata->orWhere('tu.user_id', 'LIKE', '%' . $search . '%')
							->orWhere('tu1.user_id', 'LIKE', '%' . $search . '%')
							->orWhere('tp.name', 'LIKE', '%' . $search . '%')
							->orWhere(DB::raw('(CASE  WHEN tbl_level_income_roi.status = 1 THEN "Paid" END )'), 'LIKE', '%' . $search . '%');
					});
				}

				$totalRecord = $Incomedata->count('tbl_level_income_roi.id');
				$arrPendings = $Incomedata->skip($request->input('start'))->take($request->input('length'))->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) != '0') {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Level income roi data found successfully';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Level income roi data not found';
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
	public function fundtofundTransferReport(Request $request){
		try {
			$arrInput = $request->all();
			// dd($arrInput);
			$Checkexist = Auth::User()->id; // check use is active or not
			if (!empty($Checkexist)) {

				$fundReport = FundTransfer::select('tu1.user_id','tu1.fullname as name','tu2.user_id as to_user_id','tu2.fullname as toname',DB::raw('ROUND(tbl_fund_transfer.amount,2) as amount'),DB::raw('ROUND(tbl_fund_transfer.net_amount,2) as net_amount'),DB::raw('ROUND(tbl_fund_transfer.transfer_charge,2) as transfer_charge'),'tbl_fund_transfer.remark','tbl_fund_transfer.entry_time'/*,DB::raw('ROUND(td.working_wallet - td.working_wallet_withdraw , 2) as balance')*/)
					->leftjoin('tbl_users as tu1','tu1.id',"=",'tbl_fund_transfer.from_user_id')
					->leftjoin('tbl_users as tu2','tu2.id',"=",'tbl_fund_transfer.to_user_id')
					->leftjoin('tbl_dashboard as td','td.id',"=",'tbl_fund_transfer.from_user_id')
					 ->where('tbl_fund_transfer.from_user_id','=',$Checkexist);

				/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					$search = $request->input('search')['value'];					
					$fundReport->orWhere('tu.name', 'LIKE', '%' . $search . '%')->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
				}*/

				// if (isset($arrInput['frmuser_id'])) {
				//     $fundReport  = $fundReport->where('tu1.user_id', $arrInput['frmuser_id']);
				// }else{
				// 	$fundReport  = $fundReport->where('tbl_fund_transfer.from_user_id',$Checkexist);					
				// }

				// if (isset($arrInput['touser_id'])) {
				//     $fundReport  = $fundReport->where('tu2.user_id', $arrInput['touser_id']);
				// }else{
				// 	$fundReport  = $fundReport->orwhere('tbl_fund_transfer.to_user_id',$Checkexist);					
				// }

				// if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
				// 	// dd(date('Y-m-d', strtotime($arrInput['to_date'])));
				// 	$fundReport = $fundReport->whereBetween(DB::raw("DATE_FORMAT(tbl_fund_transfer.entry_time,'%Y-%m-%d')"), ['2021-10-05', date('Y-m-d', strtotime($arrInput['to_date']))]);
				// }
				//	dd(date('Y-m-d', strtotime($arrInput['to_date'])));
				// if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			 //    $arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			 //    $arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			 //    $fundReport = $fundReport->whereBetween(DB::raw("DATE_FORMAT(tbl_fund_transfer.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
	   //       	}

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$dateArr  = array(date('Y-m-d', strtotime($arrInput['frm_date']))." 00:00:00",date('Y-m-d', strtotime($arrInput['to_date']))." 23:59:59");

					$fundReport = $fundReport->whereBetween("tbl_fund_transfer.entry_time", $dateArr);
				}
	         	//dd($fundReport);

				$totalRecord = $fundReport->count('tbl_fund_transfer.to_user_id');
				$fundReport = $fundReport->where('tbl_fund_transfer.wallet_type',9)->orderBy('tbl_fund_transfer.entry_time','desc');			
				$arrPendings = $fundReport->skip($request->input('start'))->take($request->input('length'))->get();
					//	dd($arrPendings);		
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				// dd($arrPendings);
				if (count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				}
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
	/**
	 * Level income report.
	 *
	 * @return void
	 */
	public function UplineIncomeReport(Request $request) {

		try {
			$userId = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($userId)) {
				$Incomedata = UplineIncome::join('tbl_users as tu', 'tu.id', '=', 'tbl_upline_income.toUserId')
					->join('tbl_users as tu1', 'tu1.id', '=', 'tbl_upline_income.fromUserId')
					->join('tbl_product as tp', 'tp.id', '=', 'tbl_upline_income.type')
					->select('tbl_upline_income.*', 'tu.user_id as to_user_id', 'tu1.user_id as from_user_id', 'tu.fullname as to_fullname', 'tu1.fullname as from_fullname', 'tp.name as plan_name', DB::raw('(CASE  WHEN tbl_upline_income.status = 1 THEN "Paid" END ) as status'))
					->where([['tbl_upline_income.toUserId', '=', $userId], ['tbl_upline_income.status', '=', 1]])
					->orderBy('tbl_upline_income.entry_time', 'desc');

				if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_upline_income');
					$search = $request->input('search')['value'];
					$Incomedata = $Incomedata->where(function ($Incomedata) use ($fields, $search) {
						foreach ($fields as $field) {
							$Incomedata->orWhere('tbl_upline_income.' . $field, 'LIKE', '%' . $search . '%');
						}
						$Incomedata->orWhere('tu.user_id', 'LIKE', '%' . $search . '%')
							->orWhere('tu1.user_id', 'LIKE', '%' . $search . '%')
							->orWhere('tp.name', 'LIKE', '%' . $search . '%')
							->orWhere(DB::raw('(CASE  WHEN tbl_upline_income.status = 1 THEN "Paid" END )'), 'LIKE', '%' . $search . '%');
					});
				}

				$totalRecord = $Incomedata->count('tbl_upline_income.id');
				$arrPendings = $Incomedata->skip($request->input('start'))->take($request->input('length'))->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) != '0') {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Upline income data found successfully';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Upline income data not found';
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

	/**
	 * Level view report.
	 *
	 * @return void
	 */
	public function displayLevelView(Request $request) {
		try {
			// check token is valid
			$Userid = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($Userid)) {
				if (empty($request->input('level_id'))) {
					$user_level_id = 1;
				} else {

					$user_level_id = $request->input('level_id');

					/* if($user_level_id == 3)
						                        {
						                            $user_level_id = 2;
					*/
				}
				$levelview = LevelView::where([['id', $Userid], ['level', $user_level_id]])->get();
				//dd($levelview);
				$result = [];

				if (count($levelview) > 0) {
					foreach ($levelview as $view) {
						array_push($result, $view->id);
					}
					$levels = DB::table('tbl_level_view as tlv')
						->join('tbl_users as tu1', 'tu1.id', '=', 'tlv.id')
						->join('tbl_users as tu2', 'tu2.id', '=', 'tlv.down_id')
						->join('tbl_users as tu3', 'tu3.id', '=', 'tu2.ref_user_id')
						->leftjoin('tbl_country_new as cn', 'cn.iso_code', '=', 'tu2.country')
						->join('tbl_dashboard as td', 'td.id', '=', 'tu2.id')
						->whereIn('tlv.id', $result)
						->where('tlv.level', $user_level_id)
						->select('tu1.id', 'tu2.id as down_id', 'tu1.user_id as user_id', 'tu2.user_id as down_user_id', 'tu2.fullname as down_fullname', 'tu3.user_id as sponser_id', 'cn.country', 'tu2.fullname', 'tu2.entry_time', 'tu2.status', 'td.coin', 'td.btc', 'td.total_investment', 'td.total_withdraw', 'td.total_profit', 'tlv.level', DB::raw('(CASE  WHEN td.total_investment > 0 THEN "Active" ELSE "Inactive" END ) as status'))
						->orderBy('tlv.entry_time', 'desc');

					if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
						//searching loops on fields
						$fields = getTableColumns('tbl_level_view');
						$search = $request->input('search')['value'];
						$levels = $levels->where(function ($levels) use ($fields, $search) {
							foreach ($fields as $field) {
								$levels->orWhere('tlv.' . $field, 'LIKE', '%' . $search . '%');
							}
							$levels->orWhere('tu1.user_id', 'LIKE', '%' . $search . '%')
								->orWhere('tu2.user_id', 'LIKE', '%' . $search . '%')
								->orWhere('cn.country', 'LIKE', '%' . $search . '%')
								->orWhere('td.total_investment', 'LIKE', '%' . $search . '%')
								->orWhere('tu1.fullname', 'LIKE', '%' . $search . '%')
								->orWhere('tu2.fullname', 'LIKE', '%' . $search . '%')
								->orWhere(DB::raw('(CASE  WHEN td.total_investment> 0 THEN "Active" ELSE "Inactive" END  )'), 'LIKE', '%' . $search . '%');
						});
					}

					$totalRecord = $levels->count();
					$arrPendings = $levels->skip($request->input('start'))->take($request->input('length'))->get();

					$arrData['recordsTotal'] = $totalRecord;
					$arrData['recordsFiltered'] = $totalRecord;
					$arrData['records'] = $arrPendings;

					if (count($arrPendings) > 0) {

						$arrStatus = Response::HTTP_OK;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = 'Record found';
						return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
					} else {
						$arrStatus = Response::HTTP_NOT_FOUND;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = 'Record not found';
						return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					}
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Record not found';
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

	/**
	 * Top up report.
	 *
	 * @return void
	 */
	public function getTopupReport(Request $request) {

		try {
			$arrInput = $request->all();
			$Checkexist = Auth::User()->id; // check use is active or not
			// ini_set('memory_limit', '-1');
			if (!empty($Checkexist)) {

				/*$topupReport = Topup::join('tbl_product as tp', 'tp.id', '=', 'tbl_topup.type')
					                    ->join('tbl_users as tu', 'tu.id', '=', 'tbl_topup.top_up_by')
					                    ->select('tbl_topup.srno as srno','tbl_topup.usd_rate as usd_rate','tbl_topup.payment_type as payment_type','tbl_topup.withdraw as withdraw','tbl_topup.pin as invoice_id','tp.name as plan_name','tp.name_rupee as rupee_plan_name','tp.date_diff as date_diff','tp.duration as duration','tbl_topup.amount as top_amount','tbl_topup.percentage as percentage','tu.user_id as user_id','tbl_topup.pin as pin','tbl_topup.entry_time as entry_time',DB::raw('(CASE tbl_topup.top_up_type WHEN 0 THEN "BTC" WHEN 1 THEN "Free"  WHEN 2 THEN "Admin" WHEN 3 THEN "Self" ELSE "" END) as deposit_type'),DB::raw('(CASE  WHEN tbl_topup.roi_status = "Active" THEN "Confirmed" ELSE "Inactive" END ) as status'))
					                    ->where('tbl_topup.id', '=', $Checkexist->id)
					                    ->groupBy('tbl_topup.pin')
				*/
				$topupReport = Topup::join('tbl_product as tp', 'tp.id', '=', 'tbl_topup.type')/*->leftjoin('tbl_users as tu', 'tu.id', '=', 'tbl_topup.franchise_id')*/
					->select('tbl_topup.srno','tbl_topup.product_name','tbl_topup.pin','tbl_topup.amount','tbl_topup.entry_time', 'tp.name',DB::raw('(CASE  WHEN tbl_topup.topupfrom = "Admin Topup" THEN "Topup by Admin" ELSE tbl_topup.topupfrom END ) as topupfrom'))
					->where('tbl_topup.id', '=', $Checkexist)
					->orderBy('tbl_topup.entry_time', 'desc');

				if (isset($arrInput['deposit_id'])) {
				    $topupReport  = $topupReport->where('tbl_topup.pin', $arrInput['deposit_id']);
				}

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$topupReport = $topupReport->whereBetween(DB::raw("DATE_FORMAT(tbl_topup.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
				}

				/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					
					$fields = getTableColumns('tbl_topup');
					$search = $request->input('search')['value'];
					$topupReport = $topupReport->where(function ($topupReport) use ($fields, $search) {
						foreach ($fields as $field) {
							$topupReport->orWhere('tbl_topup.' . $field, 'LIKE', '%' . $search . '%');
						}
						$topupReport->orWhere('tp.name', 'LIKE', '%' . $search . '%')
							->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
					});
				}*/
				$totalRecord = $topupReport->count('tbl_topup.srno');
				$arrPendings = $topupReport->skip($request->input('start'))->take($request->input('length'))->get();

				//150 days 1 % daily
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				//$arrData['Totalrecords1']   = count($arrPendings);
				if (count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
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
	/**
	 *Downline Top up report.
	 *
	 * @return void
	 */
	public function getDownlineTopupReport(Request $request) {

		try {
			$arrInput = $request->all();
			$Checkexist = Auth::User()->id; // check use is active or not
			// ini_set('memory_limit', '-1');
			if (!empty($Checkexist)) {

				/*$topupReport = Topup::join('tbl_product as tp', 'tp.id', '=', 'tbl_topup.type')
					                    ->join('tbl_users as tu', 'tu.id', '=', 'tbl_topup.top_up_by')
					                    ->select('tbl_topup.srno as srno','tbl_topup.usd_rate as usd_rate','tbl_topup.payment_type as payment_type','tbl_topup.withdraw as withdraw','tbl_topup.pin as invoice_id','tp.name as plan_name','tp.name_rupee as rupee_plan_name','tp.date_diff as date_diff','tp.duration as duration','tbl_topup.amount as top_amount','tbl_topup.percentage as percentage','tu.user_id as user_id','tbl_topup.pin as pin','tbl_topup.entry_time as entry_time',DB::raw('(CASE tbl_topup.top_up_type WHEN 0 THEN "BTC" WHEN 1 THEN "Free"  WHEN 2 THEN "Admin" WHEN 3 THEN "Self" ELSE "" END) as deposit_type'),DB::raw('(CASE  WHEN tbl_topup.roi_status = "Active" THEN "Confirmed" ELSE "Inactive" END ) as status'))
					                    ->where('tbl_topup.id', '=', $Checkexist->id)
					                    ->groupBy('tbl_topup.pin')
				*/
				$topupReport = Topup::join('tbl_product as tp', 'tp.id', '=', 'tbl_topup.type')/*->leftjoin('tbl_users as tu', 'tu.id', '=', 'tbl_topup.franchise_id')*/->leftjoin('tbl_users as tu2', 'tu2.id', '=', 'tbl_topup.id')
					->select('tbl_topup.srno','tbl_topup.topupfrom','tbl_topup.pin','tbl_topup.amount','tbl_topup.entry_time', 'tp.package_name','tu2.user_id')
					->where('tbl_topup.top_up_by', '=', $Checkexist)
					->where('tbl_topup.id', '!=', $Checkexist)
					->orderBy('tbl_topup.entry_time', 'desc');

				/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					
					$fields = getTableColumns('tbl_topup');
					$search = $request->input('search')['value'];
					$topupReport = $topupReport->where(function ($topupReport) use ($fields, $search) {
						foreach ($fields as $field) {
							$topupReport->orWhere('tbl_topup.' . $field, 'LIKE', '%' . $search . '%');
						}
						$topupReport->orWhere('tp.name', 'LIKE', '%' . $search . '%')
							->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
					});
				}*/

				if (isset($arrInput['deposit_id'])) {
				    $topupReport  = $topupReport->where('tbl_topup.pin', $arrInput['deposit_id']);
				}

				if (isset($arrInput['user_id'])) {
				    $topupReport  = $topupReport->where('tu2.user_id', $arrInput['user_id']);
				}

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$topupReport = $topupReport->whereBetween(DB::raw("DATE_FORMAT(tbl_topup.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
				}

				$totalRecord = $topupReport->count('tbl_topup.srno');
				$arrPendings = $topupReport->skip($request->input('start'))->take($request->input('length'))->get();

				//150 days 1 % daily
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				//$arrData['Totalrecords1']   = count($arrPendings);
				if (count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				}
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

	/**
	 * Self Top up report.
	 *
	 * @return void
	 */
	public function selfTopupReport(Request $request) {

		try {
			$Checkexist = Auth::User()->id; // check use is active or not
			// ini_set('memory_limit', '-1');
			if (!empty($Checkexist)) {

				$topupReport = Topup::join('tbl_product as tp', 'tp.id', '=', 'tbl_topup.type')
					->join('tbl_users as tu', 'tu.id', '=', 'tbl_topup.top_up_by')

					->selectRaw('ANY_VALUE(tbl_topup.srno) as srno,ANY_VALUE(tbl_topup.pin) as invoice_id,ANY_VALUE(tp.name) as plan_name,ANY_VALUE(tbl_topup.amount) as top_amount,ANY_VALUE(tu.user_id) as user_id,ANY_VALUE(tbl_topup.pin)as pin,ANY_VALUE(tbl_topup.entry_time) as entry_time')
					->where([['tbl_topup.id', '=', $Checkexist], ['tbl_topup.top_up_type', '=', 3]])
					->groupBy('tbl_topup.pin')
					->orderBy('tbl_topup.entry_time', 'desc');

				if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_topup');
					$search = $request->input('search')['value'];
					$topupReport = $topupReport->where(function ($topupReport) use ($fields, $search) {
						foreach ($fields as $field) {
							$topupReport->orWhere('tbl_topup.' . $field, 'LIKE', '%' . $search . '%');
						}
						$topupReport->orWhere('tp.name', 'LIKE', '%' . $search . '%')
							->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
					});
				}
				$totalRecord = $topupReport->count();
				$arrPendings = $topupReport->skip($request->input('start'))->take($request->input('length'))->get();

				//150 days 1 % daily
				$arrData['recordsTotal'] = count($arrPendings);
				$arrData['recordsFiltered'] = count($arrPendings);
				$arrData['records'] = $arrPendings;
				//$arrData['Totalrecords1']   = count($arrPendings);
				if (count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {

					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
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

	public function manualTopupReport(Request $request) {

		try {
			$Checkexist = Auth::User()->id; // check use is active or not
			// ini_set('memory_limit', '-1');
			if (!empty($Checkexist)) {
				//$url = url('uploads/files');

				$url = config::get('constants.settings.aws_url');
				$topupReport = TopupRequest::
					//join('tbl_product as tp', 'tp.id', '=', 'tbl_topup_request.type')
					join('tbl_users as tu', 'tu.id', '=', 'tbl_topup_request.top_up_by')
				// ANY_VALUE(tp.name) as plan_name
					->selectRaw('ANY_VALUE(tbl_topup_request.srno) as srno,ANY_VALUE(tbl_topup_request.pin) as invoice_id,ANY_VALUE(tbl_topup_request.amount) as top_amount,ANY_VALUE(tu.user_id) as user_id,ANY_VALUE(tbl_topup_request.pin)as pin,ANY_VALUE(tbl_topup_request.entry_time) as entry_time,admin_status,IF(tbl_topup_request.attachment IS NOT NULL,CONCAT("' . $url . '",tbl_topup_request.attachment),NULL) attachment')
					->where([['tbl_topup_request.id', '=', $Checkexist], ['tbl_topup_request.top_up_type', '=', 3]])
					->groupBy('tbl_topup_request.pin')
					->orderBy('tbl_topup_request.entry_time', 'desc');

				if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_topup_request');
					$search = $request->input('search')['value'];
					$topupReport = $topupReport->where(function ($topupReport) use ($fields, $search) {
						foreach ($fields as $field) {
							$topupReport->orWhere('tbl_topup_request.' . $field, 'LIKE', '%' . $search . '%');
						}
						$topupReport->orWhere('tp.name', 'LIKE', '%' . $search . '%')
							->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
					});
				}
				$totalRecord = $topupReport->count();
				$arrPendings = $topupReport->skip($request->input('start'))->take($request->input('length'))->get();

				//150 days 1 % daily
				$arrData['recordsTotal'] = count($arrPendings);
				$arrData['recordsFiltered'] = count($arrPendings);
				$arrData['records'] = $arrPendings;
				//$arrData['Totalrecords1']   = count($arrPendings);
				if (count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {

					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
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



  
	/**
	 * Withdraw pending report.
	 *
	 * @return void
	 */
	public function withdrawPendingReports(Request $request) {

		try {
			$arrInput = $request->all();
			$UserExistid = Auth::User()->id;
			// ini_set('memory_limit', '-1');

			if (!empty($UserExistid)) {

				$query = WithdrawPending::join('tbl_users as tu', 'tu.id', '=', 'tbl_withdrwal_pending.id')->select('tbl_withdrwal_pending.*', 'tu.user_id')->where([['tbl_withdrwal_pending.id', '=', $UserExistid], ['tbl_withdrwal_pending.status', '=', 0]])
					->orderBy('tbl_withdrwal_pending.entry_time', 'desc');

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
				$totalRecord = $query->count('tbl_withdrwal_pending.sr_no');
				$query = $query->orderBy('tbl_withdrwal_pending.entry_time', 'desc');
				// $totalRecord = $query->get()->count();
				$arrDirectInc = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrDirectInc;

				if (!empty($arrDirectInc) && count($arrDirectInc) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Withdraw pending data found successfully';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Withdraw pending data not found';
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

	/**
	 * Withdraw confirm report.
	 *
	 * @return void
	 */
	public function withdrawConfirmReports(Request $request) {

		try {
			$arrInput = $request->all();
			$UserExistid = Auth::User()->id;
			// ini_set('memory_limit', '-1');

			if (!empty($UserExistid)) {

				$query = WithdrawalConfirmed::join('tbl_users as tu', 'tu.id', '=', 'tbl_withdrwal_confirmed.id')->select('tbl_withdrwal_confirmed.*', 'tu.user_id')->where([['tbl_withdrwal_confirmed.id', '=', $UserExistid], ['tbl_withdrwal_confirmed.status', '=', 1]])
					->orderBy('tbl_withdrwal_confirmed.entry_time', 'desc');

				if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_withdrwal_confirmed');
					$search = $arrInput['search']['value'];
					$query = $query->where(function ($query) use ($fields, $search) {
						foreach ($fields as $field) {
							$query->orWhere('tbl_withdrwal_confirmed.' . $field, 'LIKE', '%' . $search . '%');
						}
						$query->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
					});
				}
				$totalRecord = $query->count('tbl_withdrwal_confirmed.sr_no');
				$query = $query->orderBy('tbl_withdrwal_confirmed.entry_time', 'desc');
				// $totalRecord = $query->get()->count();
				$arrDirectInc = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrDirectInc;

				if (!empty($arrDirectInc) && count($arrDirectInc) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Withdraw confirmed data found successfully';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {

					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Withdraw confirmed data not found';
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

	/**
	 *  Roi and working pending withdraw report
	 *
	 * @return void
	 */
	public function allwithdrawPendingReports(Request $request) {

		try {
			$rules = array(

				'withdraw_type' => 'required', // 2-working,3-roi,4-self working,5-self roi
			);

			$validator = checkvalidation($request->all(), $rules, '');
			if (!empty($validator)) {
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = $validator;
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
			$arrInput = $request->all();
			$UserExistid = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($UserExistid)) {

				$query = WithdrawPending::join('tbl_users as tu', 'tu.id', '=', 'tbl_withdrwal_pending.id')->select('tbl_withdrwal_pending.*', 'tu.user_id')->where([['tbl_withdrwal_pending.id', '=', $UserExistid], ['tbl_withdrwal_pending.status', '=', 0], ['tbl_withdrwal_pending.withdraw_type', '=', $request->input('withdraw_type')]])
					->orderBy('tbl_withdrwal_pending.entry_time', 'desc');

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
				$totalRecord = $query->count('tbl_withdrwal_pending.sr_no');
				$query = $query->orderBy('tbl_withdrwal_pending.entry_time', 'desc');
				// $totalRecord = $query->get()->count();
				$arrDirectInc = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrDirectInc;

				if (!empty($arrDirectInc) && count($arrDirectInc) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data found successfully';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
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

	/**
	 *  Roi and working confirm withdraw report
	 *
	 * @return void
	 */
	public function allwithdrawConfirmReports(Request $request) {

		try {
			$rules = array(

				'withdraw_type' => 'required', // 2-working,3-roi,4-self working,5-self roi
			);

			$validator = checkvalidation($request->all(), $rules, '');
			if (!empty($validator)) {
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = $validator;
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
			$arrInput = $request->all();
			// ini_set('memory_limit', '-1');
			$UserExistid = Auth::User()->id;
			if (!empty($UserExistid)) {

				$query = WithdrawalConfirmed::join('tbl_users as tu', 'tu.id', '=', 'tbl_withdrwal_confirmed.id')->select('tbl_withdrwal_confirmed.*', 'tu.user_id')->where([['tbl_withdrwal_confirmed.id', '=', $UserExistid], ['tbl_withdrwal_confirmed.status', '=', 1], ['tbl_withdrwal_confirmed.withdraw_type', '=', $request->input('withdraw_type')]])
					->orderBy('tbl_withdrwal_confirmed.entry_time', 'desc');

				if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_withdrwal_confirmed');
					$search = $arrInput['search']['value'];
					$query = $query->where(function ($query) use ($fields, $search) {
						foreach ($fields as $field) {
							$query->orWhere('tbl_withdrwal_confirmed.' . $field, 'LIKE', '%' . $search . '%');
						}
						$query->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
					});
				}
				$totalRecord = $querycount('tbl_withdrwal_confirmed.sr_no');
				$query = $query->orderBy('tbl_withdrwal_confirmed.entry_time', 'desc');
				// $totalRecord = $query->get()->count();
				$arrDirectInc = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrDirectInc;

				if (!empty($arrDirectInc) && count($arrDirectInc) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
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

	/**
	 *  Pending Deposit report
	 *
	 * @return void
	 */
	public function pendingDeposit(Request $request) {

		try {
			$id = Auth::user()->id;
			// ini_set('memory_limit', '-1');

			$pendingReport = Invoice::join('tbl_product as tp', 'tp.id', '=', 'tbl_invoices.plan_id')
				->select('tbl_invoices.srno', 'tbl_invoices.invoice_id', 'tbl_invoices.id', 'tbl_invoices.price_in_usd', 'tbl_invoices.currency_price', 'tbl_invoices.payment_mode', 'tbl_invoices.product_url', 'tbl_invoices.address', 'tbl_invoices.entry_time', 'tbl_invoices.plan_id', 'tbl_invoices.in_status', 'tbl_invoices.remark', 'tbl_invoices.trans_hash', 'tbl_invoices.rec_amt', 'tbl_invoices.top_up_status', 'tbl_invoices.top_up_date', 'tp.name as plan_name', 'tp.name_rupee as rupee_plan_name')
				->where([['tbl_invoices.id', '=', $id], ['tbl_invoices.in_status', '=', 0]])
				->orderBy('tbl_invoices.entry_time', 'desc');

			if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
				//searching loops on fields
				$fields = getTableColumns('tbl_invoices');
				$search = $request->input('search')['value'];
				$pendingReport = $pendingReport->where(function ($pendingReport) use ($fields, $search) {
					foreach ($fields as $field) {
						$pendingReport->orWhere('tbl_invoices.' . $field, 'LIKE', '%' . $search . '%');
					}
					$pendingReport->orWhere('tp.name', 'LIKE', '%' . $search . '%');
				});
			}
			$totalRecord = $pendingReport->count('tbl_invoices.srno');
			$arrPendings = $pendingReport->skip($request->input('start'))->take($request->input('length'))->get();

			$arrData['recordsTotal'] = $totalRecord;
			$arrData['recordsFiltered'] = $totalRecord;
			$arrData['records'] = $arrPendings;

			if (!empty($arrPendings) && count($arrPendings) > 0) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Pending data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
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
	 *  Pending Deposit report
	 *
	 * @return void
	 */
	public function pendingAddDeposit(Request $request) {
		try {
			$arrInput = $request->all();
			$id = Auth::user()->id;
			// ini_set('memory_limit', '-1');
			$arrInput = $request->all();
			$pendingReport = TransactionInvoice::select('srno', 'invoice_id', 'price_in_usd', 'payment_mode', 'address', 'entry_time', 'status_url','product_url',DB::raw('(CASE tbl_transaction_invoices.in_status WHEN 0 THEN "Pending"  END) as in_status'))
				->where('id', $id)->whereIn('in_status', [0])
				/*->where([['id', '=', $id], ['in_status', '=', 2]])*/
				->orderBy('entry_time', 'desc');

			/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
				
				$fields = getTableColumns('tbl_transaction_invoices');
				$search = $request->input('search')['value'];
				$pendingReport = $pendingReport->where(function ($pendingReport) use ($fields, $search) {
					foreach ($fields as $field) {
						$pendingReport->orWhere('tbl_transaction_invoices.' . $field, 'LIKE', '%' . $search . '%');
					}
					$pendingReport->orWhere('tp.name', 'LIKE', '%' . $search . '%');
				});
			}*/

			if (isset($arrInput['deposit_id'])) {
				    $pendingReport  = $pendingReport->where('invoice_id', $arrInput['deposit_id']);
			}

			if (isset($arrInput['status'])) {
				    $pendingReport  = $pendingReport->where('in_status', $arrInput['status']);
			}

			if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
				$pendingReport = $pendingReport->whereBetween(DB::raw("DATE_FORMAT(tbl_transaction_invoices.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
			}

			$totalRecord = $pendingReport->count('id');
			$arrPendings = $pendingReport->skip($request->input('start'))->take($request->input('length'))->get();

			$arrData['recordsTotal'] = $totalRecord;
			$arrData['recordsFiltered'] = $totalRecord;
			$arrData['records'] = $arrPendings;

			if (!empty($arrPendings) && count($arrPendings) > 0) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Pending data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
			} else {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data not found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
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
	 *  Expired Deposit report
	 *
	 * @return void
	 */
	public function expiredsAddDeposit(Request $request) {
		try {
			$id = Auth::user()->id;
			// ini_set('memory_limit', '-1');
			$arrInput = $request->all();
			$pendingReport = TransactionInvoice::leftjoin('tbl_product as tp', 'tp.id', '=', 'tbl_transaction_invoices.plan_id')
				->select('tbl_transaction_invoices.srno', 'tbl_transaction_invoices.invoice_id', 'tbl_transaction_invoices.id', 'tbl_transaction_invoices.price_in_usd', 'tbl_transaction_invoices.currency_price', 'tbl_transaction_invoices.payment_mode', 'tbl_transaction_invoices.product_url', 'tbl_transaction_invoices.address', 'tbl_transaction_invoices.entry_time', 'tbl_transaction_invoices.plan_id', 'tbl_transaction_invoices.in_status', 'tbl_transaction_invoices.remark', 'tbl_transaction_invoices.trans_hash', 'tbl_transaction_invoices.rec_amt', 'tbl_transaction_invoices.top_up_status', 'tbl_transaction_invoices.status_url', 'tbl_transaction_invoices.top_up_date', 'tp.name as plan_name', 'tp.name as rupee_plan_name')
				->where([['tbl_transaction_invoices.id', '=', $id], ['tbl_transaction_invoices.in_status', '=', 2]])
				->orderBy('tbl_transaction_invoices.entry_time', 'desc');

			if (isset($arrInput['deposit_id'])) {
				    $pendingReport  = $pendingReport->where('invoice_id', $arrInput['deposit_id']);
			}

			if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
				$pendingReport = $pendingReport->whereBetween(DB::raw("DATE_FORMAT(tbl_transaction_invoices.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
			}

			if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
				//searching loops on fields
				$fields = getTableColumns('tbl_transaction_invoices');
				$search = $request->input('search')['value'];
				$pendingReport = $pendingReport->where(function ($pendingReport) use ($fields, $search) {
					foreach ($fields as $field) {
						$pendingReport->orWhere('tbl_transaction_invoices.' . $field, 'LIKE', '%' . $search . '%');
					}
					$pendingReport->orWhere('tp.name', 'LIKE', '%' . $search . '%');
				});
			}
			$totalRecord = $pendingReport->count('tbl_transaction_invoices.srno');
			$arrPendings = $pendingReport->skip($request->input('start'))->take($request->input('length'))->get();

			$arrData['recordsTotal'] = $totalRecord;
			$arrData['recordsFiltered'] = $totalRecord;
			$arrData['records'] = $arrPendings;

			if (!empty($arrPendings) && count($arrPendings) > 0) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Pending data found';
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
	 *  Confirm Deposit report
	 *
	 * @return void
	 */
	public function confirmAddDeposit(Request $request) {
		try {
			$arrInput = $request->all();
			$id = Auth::user()->id;
			// ini_set('memory_limit', '-1');
			$arrInput = $request->all();
			$pendingReport = TransactionInvoice::select('srno', 'invoice_id', 'price_in_usd', 'payment_mode', 'address', 'entry_time', 'status_url')
				->where([['id', '=', $id], ['in_status', '=', 1]])
				->orderBy('entry_time', 'desc');

			/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
				
				$fields = getTableColumns('tbl_transaction_invoices');
				$search = $request->input('search')['value'];
				$pendingReport = $pendingReport->where(function ($pendingReport) use ($fields, $search) {
					foreach ($fields as $field) {
						$pendingReport->orWhere('tbl_transaction_invoices.' . $field, 'LIKE', '%' . $search . '%');
					}
					$pendingReport->orWhere('tp.name', 'LIKE', '%' . $search . '%');
				});
			}*/

			if (isset($arrInput['deposit_id'])) {
				    $pendingReport  = $pendingReport->where('invoice_id', $arrInput['deposit_id']);
			}

			if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
				$pendingReport = $pendingReport->whereBetween(DB::raw("DATE_FORMAT(tbl_transaction_invoices.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
			}

			$totalRecord = $pendingReport->count('id');
			$arrPendings = $pendingReport->skip($request->input('start'))->take($request->input('length'))->get();

			$arrData['recordsTotal'] = $totalRecord;
			$arrData['recordsFiltered'] = $totalRecord;
			$arrData['records'] = $arrPendings;

			if (!empty($arrPendings) && count($arrPendings) > 0) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Confirm data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
			} else {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data not found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
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
	 *  Confirm DEposit report
	 *
	 * @return void
	 */
	public function confirmedDeposit(Request $request) {

		try {
			$id = Auth::user()->id;
			// ini_set('memory_limit', '-1');
			$pendingReport = AddressTransaction::where([['id', '=', $id], ['status', '=', 'confirmed']])->get();
			if (!empty($pendingReport) && count($pendingReport) > 0) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Confirmed transaction data found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $pendingReport);
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
	 * All Transaction report
	 *
	 * @return void
	 */
	public function getAllTransaction(Request $request) {

		try {
			$myArray = [];
			$userexist = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if ($userexist) {

				$transaction = AllTransaction::join('tbl_users as tu', 'tu.id', '=', 'tbl_all_transaction.id')->select('tbl_all_transaction.srno', 'tbl_all_transaction.id', 'tbl_all_transaction.network_type', 'tbl_all_transaction.credit', 'tbl_all_transaction.debit', 'tbl_all_transaction.balance', 'tbl_all_transaction.refference', 'tbl_all_transaction.transaction_date', 'tbl_all_transaction.type', 'tbl_all_transaction.status', 'tbl_all_transaction.remarks', 'tbl_all_transaction.entry_time', 'tu.user_id')->orderBy('tbl_all_transaction.transaction_date', 'DESC')->where('tbl_all_transaction.id', $userexist);

				if ($request->network_type != '') {
					$transaction = $transaction->where('tbl_all_transaction.network_type', $request->network_type);
				}

				if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_all_transaction');
					$search = $request->input('search')['value'];
					$transaction = $transaction->where(function ($transaction) use ($fields, $search) {
						foreach ($fields as $field) {
							$transaction->orWhere('tbl_all_transaction.' . $field, 'LIKE', '%' . $search . '%');
						}
						$transaction->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
					});
				}

				$totalRecord = $transaction->count('tbl_all_transaction.srno');
				$arrPendings = $transaction->skip($request->input('start'))->take($request->input('length'))->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				//$transaction=$transaction->get();
				if (!empty($arrPendings) && (count($arrPendings) > 0)) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {

					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'NO Data Found';
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

	/**
	 * Withdrawal report
	 *
	 * @return void
	 */

	public function WithdrawalIncomeReport(Request $request) {
		try {
			// ini_set('memory_limit', '-1');
			$arrInput = $request->all();
			$UserExistid = Auth::User()->id;

			if (!empty($UserExistid)) {

				$query = WithdrawPending::select('tbl_withdrwal_pending.amount','tbl_withdrwal_pending.id','tbl_withdrwal_pending.deduction','tbl_withdrwal_pending.status','tbl_withdrwal_pending.to_address','tbl_withdrwal_pending.withdraw_type','tbl_withdrwal_pending.entry_time','tbl_withdrwal_pending.remark','tbl_withdrwal_pending.on_amount')
					->join('tbl_users as tu', 'tu.id', '=', 'tbl_withdrwal_pending.id')
					->select('tbl_withdrwal_pending.*', 'tu.user_id')
					->where([['tbl_withdrwal_pending.id', '=', $UserExistid]])
					->where([['tbl_withdrwal_pending.withdraw_type', '!=', 7]])
					->orderBy('tbl_withdrwal_pending.entry_time', 'desc');

					
					if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
					$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
					$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_withdrwal_pending.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
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
				$totalRecord = $query->count('tbl_withdrwal_pending.sr_no');
				$query = $query->orderBy('tbl_withdrwal_pending.entry_time', 'desc');
				// $totalRecord = $query->get()->count();
				$arrDirectInc = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrDirectInc;

				if (!empty($arrDirectInc) && count($arrDirectInc) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Withdraw pending data found successfully';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Withdraw pending data not found';
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

	/**
	 * Roi report
	 *
	 * @return void
	 */

	public function ROIIncomeReport(Request $request) {

		try {
			// ini_set('memory_limit', '-1');
			$arrInput = $request->all();
			$userExistId = Auth::User()->id;
			if (!empty($userExistId)) {
				// $DailuBonus = DailyBonus::join('tbl_product as tp', 'tbl_dailybonus.type', '=', 'tp.id')->join('tbl_topup as tt', 'tt.pin', '=', 'tbl_dailybonus.pin')
				// ->selectRaw('tbl_dailybonus.sr_no,tbl_dailybonus.pin,tbl_dailybonus.amount,daily_percentage,tp.name,tp.name_rupee,tbl_dailybonus.entry_time,tp.duration,tbl_dailybonus.status,tt.amount as on_amount')
				// ->where('tbl_dailybonus.id', '=', $userExistId->id);

				$DailuBonus = DailyBonus::join('tbl_product as tp', 'tbl_dailybonus.type', '=', 'tp.id')
								/*->join('tbl_topup as tt', 'tt.pin', '=', 'tbl_dailybonus.pin')*/
								->select('tbl_dailybonus.sr_no','tbl_dailybonus.pin','tbl_dailybonus.amount','tbl_dailybonus.on_amount','tp.name','tbl_dailybonus.entry_time','tbl_dailybonus.status')
								->where('tbl_dailybonus.id', '=', $userExistId);

				// $DailuBonus = DailyBonus::select('tbl_dailybonus.amount' , 'tbl_topup.pin' , 'tbl_dailybonus.entry_time' ,'tbl_dailybonus.status' , 'tbl_topup.amount as name')
				// 						->join('tbl_topup' , 'tbl_dailybonus.pin' , '=' , 'tbl_topup.pin')
				// 						->where('tbl_dailybonus.id' , '=' , $userExistId->id);
				
				// dd($DailuBonus);

				if (isset($arrInput['deposit_id'])) {
				    $DailuBonus  = $DailuBonus->where('tbl_dailybonus.pin', $arrInput['deposit_id']);
				}

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$DailuBonus = $DailuBonus->whereBetween(DB::raw("DATE_FORMAT(tbl_dailybonus.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
				}

				/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					
					$fields = getTableColumns('tbl_dailybonus');
					$search = $request->input('search')['value'];
					$DailuBonus = $DailuBonus->where(function ($DailuBonus) use ($fields, $search) {
						foreach ($fields as $field) {
							$DailuBonus->orWhere('tbl_dailybonus.' . $field, 'LIKE', '%' . $search . '%');
						}
						$DailuBonus->orWhere('tp.name', 'LIKE', '%' . $search . '%');
					});
				}*/

				$totalRecord = $DailuBonus->count('tbl_dailybonus.id');
				$DailuBonus = $DailuBonus->orderBy('tbl_dailybonus.entry_time', 'desc');
				// $totalRecord = $DailuBonus->count();
				$arrPendings = $DailuBonus->skip($request->input('start'))->take($request->input('length'))->get();
				// dd($arrPendings);
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
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

	public function ResidualIncomeReport(Request $request) {

		try {
			// ini_set('memory_limit', '-1');
			$arrInput = $request->all();
			$userExistId = Auth::User()->id;
			if (!empty($userExistId)) {
				
				$DailuBonus = ResidualIncome::join('tbl_product as tp', 'tbl_residual_income.type', '=', 'tp.id')
								->select('tbl_residual_income.sr_no','tbl_residual_income.pin','tbl_residual_income.amount','tbl_residual_income.residual_percentage','tbl_residual_income.on_amount','tp.package_name','tbl_residual_income.entry_time')
								->where('tbl_residual_income.id', '=', $userExistId);

				
				if (isset($arrInput['deposit_id'])) {
				    $DailuBonus  = $DailuBonus->where('tbl_residual_income.pin', $arrInput['deposit_id']);
				}

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$DailuBonus = $DailuBonus->whereBetween(DB::raw("DATE_FORMAT(tbl_residual_income.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
				}

				
				$totalRecord = $DailuBonus->count('tbl_residual_income.id');
				$DailuBonus = $DailuBonus->orderBy('tbl_residual_income.entry_time', 'desc');
				// $totalRecord = $DailuBonus->count();
				$arrPendings = $DailuBonus->skip($request->input('start'))->take($request->input('length'))->get();
				// dd($arrPendings);
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
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

	/**
	 * Binary report
	 *
	 * @return void
	 */

	public function binaryIncomeReport(Request $request) {

		try {
			$userExistId = Auth::User()->id;
			$arrInput = $request->all();
			// ini_set('memory_limit', '-1');
			if (!empty($userExistId)) {
				// $binreport = PayoutHistory::orderBy('tbl_payout_history.entry_time', 'desc')
				$binreport = PayoutHistory::select('id' , 'amount' , 'laps_amount' , 'left_bv' , 'right_bv' , 'percentage' , 'rank' , 'entry_time')
					->where('user_id', $userExistId);

				/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					
					$fields = getTableColumns('tbl_dailybonus');
					$search = $request->input('search')['value'];
					$binreport = $binreport->where(function ($binreport) use ($fields, $search) {
						foreach ($fields as $field) {
							$binreport->orWhere('tbl_dailybonus.' . $field, 'LIKE', '%' . $search . '%');
						}
						$binreport->orWhere('tp.name', 'LIKE', '%' . $search . '%');
					});
				}*/

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$binreport = $binreport->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
				}

				
				$totalRecord = $binreport->count('id');
				$binreport = $binreport->orderBy('entry_time', 'desc');
				$arrPendings = $binreport->skip($request->input('start'))->take($request->input('length'))->get();
				// dd($arrPendings);
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				}
			} else {

				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Invalid user';
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

	/**
	 * Matching Bonus report
	 *
	 * @return void
	 */

	public function matchingBonusIncomeReport(Request $request) {

		try {
			$userExistId = Auth::User()->id;
			$arrInput = $request->all();
			// ini_set('memory_limit', '-1');
			if (!empty($userExistId)) {
				
				
					$binreport = MatchingBonusGenerate::join('tbl_users as u', 'tbl_matching_bonus_generate.user_id', '=', 'u.id')
					/*->leftjoin('tbl_achieved_user_matching_bonus as tp', 'tp.perform_id', '=', 'tbl_matching_bonus_generate.ach_perf_bonus_id')*/
					->select('tbl_matching_bonus_generate.id', 'u.user_id','u.fullname','tbl_matching_bonus_generate.amount','tbl_matching_bonus_generate.count_week','tbl_matching_bonus_generate.bonus_name','tbl_matching_bonus_generate.entry_time','tbl_matching_bonus_generate.remark')
					->where('tbl_matching_bonus_generate.user_id', $userExistId);

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$binreport = $binreport->whereBetween(DB::raw("DATE_FORMAT(tbl_matching_bonus_generate.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
				}

				$totalRecord = $binreport->count('tbl_matching_bonus_generate.id');
				$binreport = $binreport->orderBy('tbl_matching_bonus_generate.entry_time', 'desc');
				$arrPendings = $binreport->skip($request->input('start'))->take($request->input('length'))->get();
				/*dd($arrPendings);*/
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				}
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


	public function rankMatchingBonusIncomeReport(Request $request) {

		try {
			$userExistId = Auth::User()->id;
			$arrInput = $request->all();
			// ini_set('memory_limit', '-1');
			if (!empty($userExistId)) {
				
				
					$binreport = AchievedUserMatchingBonus::join('tbl_users as u', 'tbl_achieved_user_matching_bonus.user_id', '=', 'u.id')
					->select('u.user_id','tbl_achieved_user_matching_bonus.bonus_name','tbl_achieved_user_matching_bonus.entry_time')
					->where('tbl_achieved_user_matching_bonus.user_id', $userExistId);

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$binreport = $binreport->whereBetween(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
				}

				$totalRecord = $binreport->count('tbl_achieved_user_matching_bonus.id');
				$binreport = $binreport->orderBy('tbl_achieved_user_matching_bonus.entry_time', 'desc');
				$arrPendings = $binreport->skip($request->input('start'))->take($request->input('length'))->get();
				/*dd($arrPendings);*/
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				}
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

	/**
	 * Add Fund report
	 *
	 * @return void
	 */

	public function addFundReport(Request $request) {

		

		try {
			$userExistId = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($userExistId)) {
				$fundreport = AddressTransaction::join('tbl_users as tu', 'tu.id', '=', 'tbl_deposit_address_transaction.id')
					->select('tu.user_id', 'tbl_deposit_address_transaction.*')
					->where('tbl_deposit_address_transaction.id', $userExistId)
					->orderBy('tbl_deposit_address_transaction.entry_time', 'desc');

				if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_users');
					$search = $request->input('search')['value'];
					$fundreport = $fundreport->where(function ($fundreport) use ($fields, $search) {
						foreach ($fields as $field) {
							$fundreport->orWhere('tbl_users.' . $field, 'LIKE', '%' . $search . '%');
						}
						$fundreport->orWhere('tp.name', 'LIKE', '%' . $search . '%');
					});
				}

				$totalRecord = $fundreport->count();
				$arrPendings = $fundreport->skip($request->input('start'))->take($request->input('length'))->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
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

	/**
	 * Pending Fund report
	 *
	 * @return void
	 */

	public function pendingFundReport(Request $request) {

		try {
			$userExistId = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($userExistId)) {
				$fundreport = Invoice::join('tbl_users as tu', 'tu.id', '=', 'tbl_invoices.id')
					->select('tu.user_id', 'tbl_invoices.*')
					->where('tbl_invoices.payment_mode', '=', 'BTC')
					->where('tbl_invoices.in_status', '=', 0)
					->where('tbl_invoices.id', '=', $userExistId)
					->orderBy('tbl_invoices.entry_time', 'desc');

				if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_users');
					$search = $request->input('search')['value'];
					$fundreport = $fundreport->where(function ($fundreport) use ($fields, $search) {
						foreach ($fields as $field) {
							$fundreport->orWhere('tbl_users.' . $field, 'LIKE', '%' . $search . '%');
						}
						$fundreport->orWhere('tp.name', 'LIKE', '%' . $search . '%');
					});
				}

				$totalRecord = $fundreport->count();
				$arrPendings = $fundreport->skip($request->input('start'))->take($request->input('length'))->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
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

	public function ExpiredFundReport(Request $request) {

		try {
			$userExistId = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($userExistId)) {
				$fundreport = Invoice::join('tbl_users as tu', 'tu.id', '=', 'tbl_invoices.id')
					->select('tu.user_id', 'tbl_invoices.*')
					->where('tbl_invoices.payment_mode', '=', 'BTC')
					->where('tbl_invoices.in_status', '=', 2)
					->where('tbl_invoices.id', '=', $userExistId)
					->orderBy('tbl_invoices.entry_time', 'desc');

				if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_users');
					$search = $request->input('search')['value'];
					$fundreport = $fundreport->where(function ($fundreport) use ($fields, $search) {
						foreach ($fields as $field) {
							$fundreport->orWhere('tbl_users.' . $field, 'LIKE', '%' . $search . '%');
						}
						$fundreport->orWhere('tp.name', 'LIKE', '%' . $search . '%');
					});
				}

				$totalRecord = $fundreport->count();
				$arrPendings = $fundreport->skip($request->input('start'))->take($request->input('length'))->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
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
	public function confirmFundReport(Request $request) {

		try {
			$userExistId = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($userExistId)) {
				$fundreport = Invoice::join('tbl_users as tu', 'tu.id', '=', 'tbl_invoices.id')
					->select('tu.user_id', 'tbl_invoices.payment_mode','tbl_invoices.in_status','tbl_invoices.id','tbl_invoices.top_up_date')
					->where('tbl_invoices.payment_mode', '=', 'BTC')
					->where('tbl_invoices.in_status', '=', 1)
					->where('tbl_invoices.id', '=', $userExistId)
					->orderBy('tbl_invoices.top_up_date', 'desc');

				if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_users');
					$search = $request->input('search')['value'];
					$fundreport = $fundreport->where(function ($fundreport) use ($fields, $search) {
						foreach ($fields as $field) {
							$fundreport->orWhere('tbl_users.' . $field, 'LIKE', '%' . $search . '%');
						}
						$fundreport->orWhere('tp.name', 'LIKE', '%' . $search . '%');
					});
				}

				$totalRecord = $fundreport->count();
				$arrPendings = $fundreport->skip($request->input('start'))->take($request->input('length'))->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
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
	public function perfectMoneyFundReport(Request $request) {

		try {
			$userExistId = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($userExistId)) {
				$fundreport = Invoice::join('tbl_users as tu', 'tu.id', '=', 'tbl_invoices.id')
					->select('tu.user_id', 'tbl_invoices.*')
					->where('tbl_invoices.payment_mode', '=', 'PM')
					->where('tbl_invoices.id', '=', $userExistId)
					->orderBy('tbl_invoices.top_up_date', 'desc');

				if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_users');
					$search = $request->input('search')['value'];
					$fundreport = $fundreport->where(function ($fundreport) use ($fields, $search) {
						foreach ($fields as $field) {
							$fundreport->orWhere('tbl_users.' . $field, 'LIKE', '%' . $search . '%');
						}
						$fundreport->orWhere('tp.name', 'LIKE', '%' . $search . '%');
					});
				}

				$totalRecord = $fundreport->count();
				$arrPendings = $fundreport->skip($request->input('start'))->take($request->input('length'))->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
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

	/**
	 * Fund Transfer report
	 *
	 * @return void
	 */
	public function fundTransferReport(Request $request) {

		try {
			$myArray = [];
			$userexist = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if ($userexist) {

				$transaction = AllTransaction::join('tbl_users as tu', 'tu.id', '=', 'tbl_all_transaction.id')->select('tbl_all_transaction.srno', 'tbl_all_transaction.id', 'tbl_all_transaction.network_type', 'tbl_all_transaction.prev_balance', 'tbl_all_transaction.final_balance', 'tbl_all_transaction.credit', 'tbl_all_transaction.debit', 'tbl_all_transaction.balance', 'tbl_all_transaction.refference', 'tbl_all_transaction.transaction_date', 'tbl_all_transaction.type', 'tbl_all_transaction.status', 'tbl_all_transaction.remarks', 'tbl_all_transaction.entry_time', 'tu.user_id')->orderBy('tbl_all_transaction.srno', 'DESC')->where([['tbl_all_transaction.refference', $userexist], ['tbl_all_transaction.type', 'transfer']]);

				if ($request->network_type != '') {
					$transaction = $transaction->where('tbl_all_transaction.network_type', $request->network_type);
				}

				if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_all_transaction');
					$search = $request->input('search')['value'];
					$transaction = $transaction->where(function ($transaction) use ($fields, $search) {
						foreach ($fields as $field) {
							$transaction->orWhere('tbl_all_transaction.' . $field, 'LIKE', '%' . $search . '%');
						}
						$transaction->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
					});
				}

				$totalRecord = $transaction->count();
				$arrPendings = $transaction->skip($request->input('start'))->take($request->input('length'))->get();
				if (!empty($arrPendings) && (count($arrPendings) > 0)) {
					foreach ($arrPendings as $key => $v) {
						if ($arrPendings[$key]->credit != 0) {
							$v->fund_status = 'Credit';
						} else {
							$v->fund_status = 'Debit';
						}
					}
				}

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				//$transaction=$transaction->get();
				if (!empty($arrPendings) && (count($arrPendings) > 0)) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
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

	/**
	 * Income grph report
	 *
	 * @return void
	 */
	public function investmentGraphReport(Request $request) {

		try {
			$userId = Auth::User()->id;
			$total_investment = Topup::where('id',$userId)->sum('amount');
			$last_investment = Topup::where('id',$userId)->orderBy('entry_time','DESC')->first();
			$total_day = Product::where('id',$last_investment->type)->pluck('date_diff')->first();
			// dd($total_day);
			$mainArray = array();
			if (!empty($userId)) {
				$arrData = $arrData1 = [];
				for ($i = 1; $i <= $total_day; $i++) {

					$Todate = date('Y-m-d', strtotime("+$i day", strtotime(''.$last_investment->entry_time.'')));

					$directData = DirectIncome::selectRaw('ANY_VALUE(entry_time) as entry_time,ANY_VALUE(sum(amount)) as amount')->where('tbl_directincome.toUserId', '=', $userId)
						->where(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), '<=', $Todate)
						->groupBy(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"))->first();

					$binaryData = PayoutHistory::selectRaw('ANY_VALUE(created_at) as entry_time,ANY_VALUE(sum(amount)) as amount')->where('tbl_payout_history.user_id', '=', $userId)
						->where(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"), '<=', $Todate)
						->groupBy(DB::raw("DATE_FORMAT(entry_time,'%Y-%m-%d')"))->first();

					$roiData = DailyBonus::join('tbl_product as tp', 'tbl_dailybonus.type', '=', 'tp.id')->selectRaw('ANY_VALUE(tbl_dailybonus.entry_time) as entry_time,ANY_VALUE(sum(amount)) as amount')->where('tbl_dailybonus.id', '=', $userId)
						->where(DB::raw("DATE_FORMAT(tbl_dailybonus.entry_time,'%Y-%m-%d')"), '<=', $Todate)
						->orderBy(DB::raw("DATE_FORMAT(tbl_dailybonus.entry_time,'%Y-%m-%d')"))->first();

					$date = date("d/m", strtotime($Todate));

					$arrData['date'] = $date;
					$arrData['total_return'] = custom_round((isset($directData->amount) && !empty($directData->amount) ? $directData->amount : 0) + (isset($binaryData->amount) && !empty($binaryData->amount) ? $binaryData->amount : 0) + (isset($roiData->amount) && !empty($roiData->amount) ? $roiData->amount : 0), 2);
					$arrData['total_investment'] = $total_investment;
					array_push($mainArray, $arrData);
				}
				if (!empty($arrData)) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data found successfully';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $mainArray);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
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

	/**
	 * [getDirectIncome description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function DirectIncomeReport(Request $request) {
		try {
			$Checkexist = Auth::User()->id;
			
			// check use is active or not
			if (!empty($Checkexist)) {
				ini_set('memory_limit', '-1');
				$arrInput = $request->all();
				
				// $query = DirectIncome::join('tbl_users as tu', 'tu.id', '=', 'tbl_directincome.fromUserId')
				// 					->join('tbl_users as tu1', 'tu1.id', '=', 'tbl_directincome.toUserId')
				// 					->select('tbl_directincome.*', 'tu.user_id as from_user_id', 'tu1.user_id as to_user_id', 'tu.fullname as from_fullname', 'tu1.fullname as to_fullname')
				// 					->where('tbl_directincome.toUserId', '=', $Checkexist->id);
				// print_r($query);
				// exit();

				// $query = DirectIncome::join('tbl_users as tu', 'tu.id', '=', 'tbl_directincome.fromUserId')
				// 					   ->join('tbl_users as tu1', 'tu1.id', '=', 'tbl_directincome.toUserId')
				// 					   ->select('tu1.user_id as to_user_id',  'tu1.fullname as to_fullname' , 'tbl_directincome.amount' , 'tbl_directincome.entry_time' , 'tbl_directincome.status' , 'tbl_directincome.laps_amount' , 'tbl_directincome.toUserId' , 'tu.user_id as from_user_id', 'tu.fullname as from_fullname')
				// 					   ->where('tbl_directincome.toUserId', '=', $Checkexist->id);


				$query = DirectIncome::join('tbl_users as tu', 'tu.id', '=', 'tbl_directincome.fromUserId')
									   ->select('tbl_directincome.amount' ,'tbl_directincome.topup_wallet_amount as purchase_wallet_amount','tbl_directincome.working_wallet_amount' , 'tbl_directincome.entry_time' , 'tbl_directincome.status' , 'tbl_directincome.laps_amount','tbl_directincome.remark', 'tu.user_id as from_user_id')
									   ->where('tbl_directincome.toUserId', '=', $Checkexist);

				if (isset($arrInput['user_id'])) {
				    $query  = $query->where('tu.user_id', $arrInput['user_id']);
				}

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
					$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
					$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_directincome.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
				}
				// if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
				 
				// 	$fields = getTableColumns('tbl_directincome');
				// 	$search = $arrInput['search']['value'];
				// 	$query = $query->where(function ($query) use ($fields, $search) {
				// 		foreach ($fields as $field) {
				// 			$query->orWhere('tbl_directincome.' . $field, 'LIKE', '%' . $search . '%');
				// 		}
				// 		$query->orWhere('tu.user_id', 'LIKE', '%' . $search . '%')
				// 			->orWhere('tu1.user_id', 'LIKE', '%' . $search . '%');
				// 	});
				// }

			
				$query = $query->orderBy('tbl_directincome.entry_time', 'desc');
				$totalRecord = $query->count('tbl_directincome.id');
				// $totalRecord = $query->get()->count();
				$arrDirectInc = $query->skip($arrInput['start'])->take($arrInput['length'])->get();
				// dd($arrDirectInc);
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrDirectInc;

				if (count($arrDirectInc) > 0) {

					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Record found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Record not found';
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
	public function franchiseIncomeReport(Request $request) {
		try {
			$Checkexist = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			// check use is active or not
			if (!empty($Checkexist)) {
				$arrInput = $request->all();

				$query = FranchiseIncome::join('tbl_users as tu', 'tu.id', '=', 'tbl_franchise_income.from_user_id')->join('tbl_users as tu1', 'tu1.id', '=', 'tbl_franchise_income.to_user_id')->select('tbl_franchise_income.*', 'tu.user_id as from_user_id', 'tu1.user_id as to_user_id', 'tu.fullname as from_fullname', 'tu1.fullname as to_fullname')->where('tbl_franchise_income.to_user_id', '=', $Checkexist);
				// print_r($query);
				// exit();

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
					$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
					$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_franchise_income.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
				}
				if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
					//searching loops on fields
					$fields = getTableColumns('tbl_franchise_income');
					$search = $arrInput['search']['value'];
					$query = $query->where(function ($query) use ($fields, $search) {
						foreach ($fields as $field) {
							$query->orWhere('tbl_franchise_income.' . $field, 'LIKE', '%' . $search . '%');
						}
						$query->orWhere('tu.user_id', 'LIKE', '%' . $search . '%')
							->orWhere('tu1.user_id', 'LIKE', '%' . $search . '%');
					});
				}
				$totalRecord = $query->get()->count('tbl_franchise_income.id');
				$query = $query->orderBy('tbl_franchise_income.entry_time', 'desc');
				// $totalRecord = $query->get()->count();
				$arrDirectInc = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrDirectInc;

				if (count($arrDirectInc) > 0) {

					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Record found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Record not found';
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

	/**
	 * [direct_list description]
	 * @param  Request $request [user_id =>  integer]
	 * @param  Request $request [token => alpha-numeric]
	 * @param  Request $request [start => integer (0)]
	 * @param  Request $request [length => integer (50)]
	 * @return [Array]          [user-details, sponser details, child details]
	 */
	public function direct_list(Request $request) {
		// ini_set('memory_limit', '-1');
		$arrInput = $request->all();
		$data = [];
		$userData = Auth::user()->id;

		// @var [collect self and sponser info] /
		$data['id'] = $userData;
		// @var [collect child info] / selftopup total_investment

		$query = User::selectRaw('tbl_users.user_id,tbl_users.fullname,tbl_users.email,tbl_users.mobile,tbl_users.amount as topup_amount,tbl_users.position,tbl_users.entry_time,(CASE tbl_users.position WHEN 1 THEN "Left" WHEN 2 THEN "Right" ELSE "" END) as position ,status')
			/*->join('tbl_dashboard as td', 'td.id', '=', 'tbl_users.id')*/
			->where('tbl_users.ref_user_id', $userData);

	    if (isset($arrInput['user_id'])) {
		    $query  = $query->where('tbl_users.user_id', $arrInput['user_id']);
		}
		if (isset($arrInput['position'])) {
			$query = $query->where('tbl_users.position', $arrInput['position']);
		}

		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_users.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		}

	/*	if (isset($arrInput['position'])) {
			if($arrInput['position'] == 1){
				$query  = $query->where('tbl_users.amount','>',0);
			}
			if($arrInput['position'] == 2){
		    	$query  = $query->where('tbl_users.amount','=<',0);
			}
		}   */

		/*if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
			
			$fields = ['user_id', 'fullname', 'mobile', 'email', 'amount', 'position', 'entry_time'];
			$search = $arrInput['search']['value'];
			$query = $query->where(function ($query) use ($fields, $search) {
				foreach ($fields as $field) {
					$query->orWhere('tbl_users.' . $field, 'LIKE', '%' . $search . '%');
				}
			});
		}*/
		$query = $query->orderBy('tbl_users.entry_time', 'desc');
		$query = $query->groupBy('tbl_users.id');
		$totalRecord = $query->get()->count('tbl_users.id');
		$arrDirectInc = $query->skip($request->start)->take($request->length)->get();

		$data['recordsTotal'] = $totalRecord;
		$data['recordsFiltered'] = $totalRecord;
		$data['records'] = $arrDirectInc;

		if ($data['recordsTotal'] > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Data found Successful!', $data);
		} else {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Data not found', $data);
		}
	}

	public function direct_list_dash(Request $request) {
		// ini_set('memory_limit', '-1');
		$arrInput = $request->all();
		$data = [];
		$userData = Auth::user()->id;

		// @var [collect self and sponser info] /
		$data['id'] = $userData;
		// @var [collect child info] / selftopup total_investment

		$query = User::selectRaw('tbl_users.user_id,tbl_users.fullname,tbl_users.email,tbl_users.mobile,tbl_users.amount as topup_amount,tbl_users.position,tbl_users.entry_time,(CASE tbl_users.position WHEN 1 THEN "Left" WHEN 2 THEN "Right" ELSE "" END) as position ,status')
			/*->join('tbl_dashboard as td', 'td.id', '=', 'tbl_users.id')*/
			->where('tbl_users.ref_user_id', $userData);

	    if (isset($arrInput['user_id'])) {
		    $query  = $query->where('tbl_users.user_id', $arrInput['user_id']);
		}

		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
			$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_users.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
		}
		/*if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
			
			$fields = ['user_id', 'fullname', 'mobile', 'email', 'amount', 'position', 'entry_time'];
			$search = $arrInput['search']['value'];
			$query = $query->where(function ($query) use ($fields, $search) {
				foreach ($fields as $field) {
					$query->orWhere('tbl_users.' . $field, 'LIKE', '%' . $search . '%');
				}
			});
		}*/
		$query = $query->orderBy('tbl_users.entry_time', 'desc');
		$query = $query->groupBy('tbl_users.id');
		$totalRecord = $query->get()->count('tbl_users.id');
		$arrDirectInc = $query->get();

		$data['recordsTotal'] = $totalRecord;
		$data['recordsFiltered'] = $totalRecord;
		$data['records'] = $arrDirectInc;

		if ($data['recordsTotal'] > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Data found Successful!', $data);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', '');
		}
	}

	/**
	 * Roi report
	 *
	 * @return void
	 */

	public function freedomclubbonus(Request $request)
	{
		try {
			$userExistId = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($userExistId)) {
				$DailuBonus = User::select('tbl_users.user_id','tbl_users.fullname','td.entry_time','td.amount','td.rank')
				->join('tbl_freedom_club_income as td', 'td.user_id', '=', 'tbl_users.id')->where('tbl_users.id', '=', $userExistId);
				
				// if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
				 
				// 	$fields = getTableColumns('tbl_freedom_club_income');
				// 	$search = $request->input('search')['value'];
				// 	$DailuBonus = $DailuBonus->where(function ($DailuBonus) use ($fields, $search) {
				// 		foreach ($fields as $field) {
				// 			$DailuBonus->orWhere('tbl_freedom_club_income.' . $field, 'LIKE', '%' . $search . '%');
				// 		}
				// 		$DailuBonus->orWhere('tp.name', 'LIKE', '%' . $search . '%');
				// 	});
				// }
				
				$DailuBonus = $DailuBonus->orderBy('tbl_users.id', 'desc');
				$totalRecord = $DailuBonus->count('td.id');
				// $totalRecord = $DailuBonus->count();
				$arrPendings = $DailuBonus->skip($request->input('start'))->take($request->input('length'))->get();
				// dd($arrPendings);
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
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

	public function suppermatchingbonus(Request $request) {

		try {
			$userExistId = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($userExistId)) {
				$DailuBonus = User::select('tbl_users.user_id','sm.rank','tbl_users.fullname','sm.entry_time','sm.pin','sm.sr_no','sm.amount')
				->join('tbl_supper_matching_bonus_income as sm', 'sm.id', '=', 'tbl_users.id')->where('tbl_users.id', '=', $userExistId);

				// if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					 
				// 	$fields = getTableColumns('tbl_supper_matching_bonus_income');
				// 	$search = $request->input('search')['value'];
				// 	$DailuBonus = $DailuBonus->where(function ($DailuBonus) use ($fields, $search) {
				// 		foreach ($fields as $field) {
				// 			$DailuBonus->orWhere('sm.' . $field, 'LIKE', '%' . $search . '%');
				// 		}
						
				// 		$DailuBonus->orWhere('tbl_users.user_id', 'LIKE', '%' . $search . '%');
				// 		$DailuBonus->orWhere('tbl_users.entry_time', 'LIKE', '%' . $search . '%');
				// 		$DailuBonus->orWhere('tbl_users.fullname', 'LIKE', '%' . $search . '%');

				// 	});
				// }
				
				$DailuBonus = $DailuBonus->orderBy('sm.sr_no', 'desc');
				$totalRecord = $DailuBonus->count('sm.sr_no');
				// $totalRecord = $DailuBonus->count();
				$arrPendings = $DailuBonus->skip($request->input('start'))->take($request->input('length'))->get();
				// dd($arrPendings);
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;

				if (!empty($arrPendings) && count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
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

/**
	 * [getRankIncome description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
		public function Structurereport(Request $request){
			$Checkexist = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			// $userdata = UserStructureModel::select(DB::raw("count(tu.id) as no_structure"),'tbl_user_structure.status','tbl_user_structure.amount_topup','tu.user_id','tbl_user_structure.entry_time')
			// 			->leftJoin('tbl_users as tu', 'tu.id', '=', 'tbl_user_structure.user_id')
			// 			->where('tbl_user_structure.user_id', '=', $Checkexist->id);

			$userdata = UserStructureModel::select('tbl_user_structure.id','tu2.user_id',DB::raw("count(tu.structure_id) as no_structure_from_users"),'tbl_user_structure.no_structure','tbl_user_structure.amount_topup','tbl_user_structure.status','tbl_user_structure.entry_time')
						->leftJoin('tbl_users as tu', 'tbl_user_structure.id', '=', 'tu.structure_id')
						->leftJoin('tbl_users as tu2', 'tbl_user_structure.user_id', '=', 'tu2.id')
						->where('tbl_user_structure.user_id', '=', $Checkexist)
						->orderBy('tbl_user_structure.entry_time', 'desc');
			 //->get();dd($userdata);
			$qry = $userdata;
			//$totalRecord = $qry->get()->count('tbl_user_structure.id');
			$userdata = $userdata->groupBy('tbl_user_structure.id');
			$totalRecord = $userdata->get()->count();
			$arrPendings = $userdata->skip($request->input('start'))->take($request->input('length'))->get();

			$arrData['recordsTotal'] = $totalRecord;
			$arrData['recordsFiltered'] = $totalRecord;
			$arrData['records'] = $arrPendings;
			/*	dd($arrData);*/
			if (!empty($arrPendings) && count($arrPendings) > 0) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data Found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
			} else {
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data not Found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
		}
		public function RegistrationStructurereport(Request $request){
			$Checkexist = Auth::User()->id;
			// $userdata = User::select('tbl_user_structure.no_structure','tbl_user_structure.amount_topup','tu.user_id','tbl_user_structure.entry_time')
			// 			->join('tbl_users as tu', 'tu.id', '=', 'tbl_user_structure.user_id')
			// 			->where('tbl_user_structure.user_id', '=', $Checkexist->id);
		//	dd($Checkexist->id);
			$userdata = User::select('tbl_users.id','tbl_users.user_id','tbl_users.ref_user_id','tp.amount','tbl_users.entry_time')
						->join('tbl_topup as tp', 'tp.id', '=', 'tbl_users.id')
						->where('tbl_users.structure_id', '=', $Checkexist);

			
				$userdata = $userdata->orderBy('tp.id', 'desc');
				$totalRecord = $userdata->count();
				$arrPendings = $userdata->skip($request->input('start'))->take($request->input('length'))->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
			/*	dd($arrData);*/
				if (!empty($arrPendings) && count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
		}
		public function getallrank(Request $request){
			$Checkexist = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			$allRanks = Rank::get();
					$dataarray = array();
					foreach ($allRanks as $value) {
						$data = supermatching::select('rank','entry_time')
								->where([['user_id','=',$Checkexist],['rank','=',$value->rank]]) 
								->first();
								 
							if(empty($data)) 
							{
									$rank_name = $value->rank;
									$entry_time = null;
									$data_rank_null = array('rank'=>$rank_name,'entry_time'=>null);
									array_push($dataarray,$data_rank_null);
							}
							else
							{
								array_push($dataarray,$data);
							}
						
														
					} 
				//	dd($dataarray);
				// $dataarray = DB::table('tbl_rank')
				// 						->leftjoin('tbl_super_matching','tbl_rank.rank','=','tbl_super_matching.rank')
				// 						->select('tbl_rank.rank','tbl_super_matching.entry_time as entry_time')
				// 						->where('tbl_super_matching.user_id','=',$Checkexist->id)
				// 						->groupBy('tbl_rank.id')
				// 						->get();
				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
						$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
						$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
						$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_rank.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
					}
					if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
						 
						$fields = getTableColumns('tbl_rank');
						$search = $arrInput['search']['value'];
						$query = $query->where(function ($query) use ($fields, $search) {
							foreach ($fields as $field) {
								$query->orWhere('tbl_rank.' . $field, 'LIKE', '%' . $search . '%');
							}
							$query->orWhere('tbl_rank.rank', 'LIKE', '%' . $search . '%')
								->orWhere('tu1.user_id', 'LIKE', '%' . $search . '%');
						});
					}
		/*	$userdata = $userdata->orderBy('tbl_rank.id', 'desc');*/
				$totalRecord = count($dataarray);
				/*$arrPendings = $userdata->skip($request->input('start'))->take($request->input('length'))->get();*/

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $dataarray;
			/*	dd($arrData);*/
				if (count($dataarray) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
		}
	/*public function getallrank(Request $request) {
		try {
			$Checkexist = Auth::User();

			// check use is active or not
			if (!empty($Checkexist)) {
				$arrInput = $request->all();

				//$query = Rank::select('tbl_rank.rank');
				$allRanks = Rank::get();

			$dataarray = [];
					foreach ($allRanks as $value) {
					$data = supermatching::join('tbl_users as tu','tu.id','=','tbl_super_matching.user_id')
								->where('tbl_super_matching.user_id','=',$Checkexist->id) 
								->select('tbl_super_matching.rank','tbl_super_matching.entry_time')->get();
							 
							$dataarray[$value->rank] = $data;
							
					}
				//dd($data);
				// if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
				// 	$arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
				// 	$arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
				// 	$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_rank.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
				// }
				// if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
				// 	//searching loops on fields
				// 	$fields = getTableColumns('tbl_rank');
				// 	$search = $arrInput['search']['value'];
				// 	$query = $query->where(function ($query) use ($fields, $search) {
				// 		foreach ($fields as $field) {
				// 			$query->orWhere('tbl_rank.' . $field, 'LIKE', '%' . $search . '%');
				// 		}
				// 		$query->orWhere('tbl_rank.rank', 'LIKE', '%' . $search . '%');
				// 			/*->orWhere('tu1.user_id', 'LIKE', '%' . $search . '%');
				// 	});
				// }


				$query = $query;->orderBy('tbl_rank.rank', 'ASC');
				$totalRecord = count($dataarray);
				$arrDirectInc = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $dataarray;

				if (count($dataarray) > 0) {

					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Record found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Record not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
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
	}*/
	public function getearnreport(Request $request) {

		try {
			date_default_timezone_set('Asia/Tokyo');
			$userExistId = Auth::User()->id;
			$date = \Carbon\Carbon::now();
		 
			$usddata = Topup::select('entry_time')->where('id' , $userExistId)->first();
			if (!empty($userExistId) ) {
				if (strtotime($usddata->entry_time) < strtotime("2021-08-31 00:00:00")) {
					$DailuBonus = User::select('tbl_users.user_id','tbl_users.fullname','tp.total_usd','tp.amount','tp.entry_time',DB::raw('now() as server_time_in_minute'),DB::raw('tp.entry_time as topup_time_in_minut'))
					->join('tbl_topup as tp', 'tp.id', '=', 'tbl_users.id')->where('tbl_users.id', '=', $userExistId);

					// if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					 
					// 	$fields = getTableColumns('tbl_topup');
					// 	$search = $request->input('search')['value'];
					// 	$DailuBonus = $DailuBonus->where(function ($DailuBonus) use ($fields, $search) {
					// 		foreach ($fields as $field) {
					// 			$DailuBonus->orWhere('tbl_topup.' . $field, 'LIKE', '%' . $search . '%');
					// 		}
					// 		$DailuBonus->orWhere('tp.name', 'LIKE', '%' . $search . '%');
					// 	});
					// }
					
					$DailuBonus = $DailuBonus->orderBy('tbl_users.id', 'desc');
					$totalRecord = $DailuBonus->count('tbl_users.id');
					// $totalRecord = $DailuBonus->count();
					$arrPendings = $DailuBonus->skip($request->input('start'))->take($request->input('length'))->get();
					$arrPendings[0]['server_time_in_minute'] = $date->toDateTimeString();
					$arrData['recordsTotal'] = $totalRecord;
					$arrData['recordsFiltered'] = $totalRecord;
					$arrData['records'] = $arrPendings;
					
					if (!empty($arrPendings) && count($arrPendings) > 0) {
						$arrStatus = Response::HTTP_OK;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = 'Data Found';
						return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
					} else {
						$arrStatus = Response::HTTP_NOT_FOUND;
						$arrCode = Response::$statusTexts[$arrStatus];
						$arrMessage = 'Data not Found';
						return sendResponse($arrStatus, $arrCode, $arrMessage, '');
					}
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not Found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
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

	
	public function AllIdBalanceReport(Request $request) {

		try {
			$Checkexist = Auth::User(); // check use is active or not
			if (!empty($Checkexist)) {
				
				$topupReport = DB::table('tbl_users as tu')->join('tbl_dashboard as td', 'td.id', '=', 'tu.id')->select('tu.fullname as name','tu.user_id',DB::raw('ROUND(td.working_wallet - td.working_wallet_withdraw , 2) as amount'))
					->where('tu.email',$Checkexist->email)->where('tu.id','!=',$Checkexist->id)
					->where('tu.status','Active')
					->where(DB::raw('ROUND(td.working_wallet - td.working_wallet_withdraw , 2)') ,'>',0);
				if (isset($request->user_id)){
					$topupReport = $topupReport->where('tu.user_id',$request->input('user_id'));
				}

				/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					$search = $request->input('search')['value'];
					$topupReport = $topupReport->orWhere('tu.fullname', 'LIKE', '%' . $search . '%')->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
				}*/
				$totalRecord = $topupReport->count();
				$query = $topupReport;//DB::enableQueryLog();
				$arrPendings = $topupReport->skip($request->input('start'))->take($request->input('length'))->get();//dd(DB::getQueryLog());
				$totalAmount = User::join('tbl_dashboard as td', 'td.id', '=', 'tbl_users.id')
								->selectRaw('ROUND((sum(td.working_wallet - td.working_wallet_withdraw)),2) as total_amount')
								->where('tbl_users.email',$Checkexist->email)->where('tbl_users.id','!=',$Checkexist->id)
								->where(DB::raw('ROUND(td.working_wallet - td.working_wallet_withdraw , 2)'), '>', 0)
								->where('tbl_users.status','Active')
								->groupBy('tbl_users.email')->pluck('total_amount')->first();
				$withdrawAmount = User::join('tbl_dashboard as td', 'td.id', '=', 'tbl_users.id')
								->selectRaw('ROUND(sum(td.working_wallet - td.working_wallet_withdraw),2) as total_amount')
								->where('tbl_users.email',$Checkexist->email)->where('tbl_users.id','!=',$Checkexist->id)
								->where(DB::raw('ROUND((td.working_wallet - td.working_wallet_withdraw),2)'), '>=', 20)
								->where('tbl_users.status','Active')
								->groupBy('tbl_users.email')->pluck('total_amount')->first();
				
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				$arrData['totalAmount']   = round($totalAmount,2);
				$arrData['withdrawAmount']   = round($withdrawAmount,2);
				if (count($arrPendings) > 0) {
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

	public function UserBalanceTransferReceiveReport(Request $request) {

		try {
			$arrInput = $request->all();
			$Checkexist = Auth::User(); // check use is active or not
			if (!empty($Checkexist)) {

				$fundReport = FundTransfer::select('tu1.user_id','tu1.fullname as name','tu2.user_id as to_user_id','tu2.fullname as toname','tbl_fund_transfer.email',DB::raw('ROUND(tbl_fund_transfer.amount,2) as amount'),'tbl_fund_transfer.entry_time',DB::raw('ROUND(td.working_wallet - td.working_wallet_withdraw , 2) as balance'))
					->join('tbl_users as tu1','tu1.id',"=",'tbl_fund_transfer.from_user_id')
					->join('tbl_users as tu2','tu2.id',"=",'tbl_fund_transfer.to_user_id')
					->join('tbl_dashboard as td','td.id',"=",'tbl_fund_transfer.from_user_id')
					->where('tu1.email',$Checkexist->email)
					->where('tbl_fund_transfer.wallet_type',4);

				/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					$search = $request->input('search')['value'];					
					$fundReport->orWhere('tu.name', 'LIKE', '%' . $search . '%')->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
				}*/

				if (isset($arrInput['frmuser_id'])) {
				    $fundReport  = $fundReport->where('tu1.user_id', $arrInput['frmuser_id']);
				}

				if (isset($arrInput['touser_id'])) {
				    $fundReport  = $fundReport->where('tu2.user_id', $arrInput['touser_id']);
				}

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$fundReport = $fundReport->whereBetween(DB::raw("DATE_FORMAT(tbl_fund_transfer.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
				}

				$totalRecord = $fundReport->count('tbl_fund_transfer.id');
				$fundReport = $fundReport->orderBy('tbl_fund_transfer.entry_time','desc');
				$arrPendings = $fundReport->skip($request->input('start'))->take($request->input('length'))->get();
								
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				if (count($arrPendings) > 0) {
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

	public function WorkingBalanceTransferReceiveReport(Request $request) {

		try {
			$Checkexist = Auth::User()->id; // check use is active or not
			if (!empty($Checkexist)) {

				$fundReport = FundTransfer::select('tu.user_id','tu.fullname as name',DB::raw('ROUND(tbl_fund_transfer.amount,2) as amount'),'tbl_fund_transfer.entry_time');

				if (!empty($request->type) && isset($request->type) && $request->type == 'transfer') {
					$fundReport = $fundReport->join('tbl_users as tu','tu.id',"=",'tbl_fund_transfer.to_user_id')->where('tbl_fund_transfer.from_user_id',$Checkexist);
				}else{
					$fundReport = $fundReport->join('tbl_users as tu','tu.id',"=",'tbl_fund_transfer.from_user_id')->where('tbl_fund_transfer.to_user_id',$Checkexist);
				}
				if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					$search = $request->input('search')['value'];					
					$fundReport->orWhere('tu.name', 'LIKE', '%' . $search . '%')->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
				}
				$totalRecord = $fundReport->count();
				$fundReport = $fundReport->orderBy('tbl_fund_transfer.entry_time','desc');			
				$arrPendings = $fundReport->skip($request->input('start'))->take($request->input('length'))->get();
								
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				if (count($arrPendings) > 0) {
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

	public function WorkingToPurchaseReport(Request $request) {

		try {
			$arrInput = $request->all();
			$Checkexist = Auth::User()->id; // check use is active or not
			// ini_set('memory_limit', '-1');
			if (!empty($Checkexist)) {
				$topupReport = DB::table('tbl_working_to_purchase_transfer as ds')
					->join('tbl_users as b', 'b.id','=','ds.id') 
					->where('ds.id', $Checkexist)
					->select('ds.id', 'b.user_id','b.fullname', 'ds.balance', 'ds.purchase_wallet_amount', 'ds.working_wallet_amount','ds.total_income_without_roi', 'ds.old_total_income_without_roi', DB::raw("DATE_FORMAT(ds.entry_time,'%Y-%m-%d') as entry_time"))
					->orderBy('ds.entry_time', 'desc');


				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$topupReport = $topupReport->whereBetween(DB::raw("DATE_FORMAT(tbl_topup.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
				}
				$totalRecord = $topupReport->count('ds.sr_no');
				$arrPendings = $topupReport->skip($request->input('start'))->take($request->input('length'))->get();

				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				if (count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
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

	public function PendingTransferBalanceRequest(Request $request) {

		try {
			$arrInput = $request->all();
			$Checkexist = Auth::User(); // check use is active or not
			if (!empty($Checkexist)) {

				$fundReport = BalanceTransfer::select('tu.user_id','tu.fullname as name',DB::raw('ROUND(tbl_balance_transfer.amount,2) as amount'),'tbl_balance_transfer.email','tbl_balance_transfer.entry_time')
				->join('tbl_users as tu','tu.id',"=",'tbl_balance_transfer.user_id')
				->where('tbl_balance_transfer.status',0)->where('tbl_balance_transfer.email',$Checkexist->email);

				/*if (!empty($request->type) && isset($request->type) && $request->type == 'transfer') {
					$fundReport = $fundReport->join('tbl_users as tu','tu.id',"=",'tbl_fund_transfer.to_user_id')->where('tbl_fund_transfer.from_user_id',$Checkexist->id);
				}else{
					$fundReport = $fundReport->join('tbl_users as tu','tu.id',"=",'tbl_fund_transfer.from_user_id')->where('tbl_fund_transfer.to_user_id',$Checkexist->id);
				}*/
				/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					$search = $request->input('search')['value'];					
					$fundReport->orWhere('tu.name', 'LIKE', '%' . $search . '%')->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
				}*/

				if (isset($arrInput['user_id'])) {
				    $fundReport  = $fundReport->where('tu.user_id', $arrInput['user_id']);
				}

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$fundReport = $fundReport->whereBetween(DB::raw("DATE_FORMAT(tbl_balance_transfer.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
				}

				$totalRecord = $fundReport->count('tbl_balance_transfer.user_id');
				$query = $fundReport;			
				$arrPendings = $fundReport->skip($request->input('start'))->take($request->input('length'))->get();
								
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				if (count($arrPendings) > 0) {
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

	public function PurchaseBalanceTransferReceiveReport(Request $request) {

		try {
			$arrInput = $request->all();
			// dd($arrInput);
			$Checkexist = Auth::User()->id; // check use is active or not
			if (!empty($Checkexist)) {

				$fundReport = FundTransfer::select('tu1.user_id','tu1.fullname as name','tu2.user_id as to_user_id','tu2.fullname as toname',DB::raw('ROUND(tbl_fund_transfer.amount,2) as amount'),DB::raw('ROUND(tbl_fund_transfer.net_amount,2) as net_amount'),DB::raw('ROUND(tbl_fund_transfer.transfer_charge,2) as transfer_charge'),'tbl_fund_transfer.remark','tbl_fund_transfer.entry_time'/*,DB::raw('ROUND(td.working_wallet - td.working_wallet_withdraw , 2) as balance')*/)
					->leftjoin('tbl_users as tu1','tu1.id',"=",'tbl_fund_transfer.from_user_id')
					->leftjoin('tbl_users as tu2','tu2.id',"=",'tbl_fund_transfer.to_user_id')
					->leftjoin('tbl_dashboard as td','td.id',"=",'tbl_fund_transfer.from_user_id');
					// ->where('tbl_fund_transfer.from_user_id','=',$Checkexist);

				/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					$search = $request->input('search')['value'];					
					$fundReport->orWhere('tu.name', 'LIKE', '%' . $search . '%')->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
				}*/

				if (isset($arrInput['frmuser_id'])) {
				    $fundReport  = $fundReport->where('tu1.user_id', $arrInput['frmuser_id']);
				}else{
					$fundReport  = $fundReport->where('tbl_fund_transfer.from_user_id',$Checkexist);					
				}

				if (isset($arrInput['touser_id'])) {
				    $fundReport  = $fundReport->where('tu2.user_id', $arrInput['touser_id']);
				}else{
					$fundReport  = $fundReport->orwhere('tbl_fund_transfer.to_user_id',$Checkexist);					
				}

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					// dd(date('Y-m-d', strtotime($arrInput['to_date'])));
					$fundReport = $fundReport->whereBetween(DB::raw("DATE_FORMAT(tbl_fund_transfer.entry_time,'%Y-%m-%d')"), ['2021-10-05', date('Y-m-d', strtotime($arrInput['to_date']))]);
				}

				$totalRecord = $fundReport->count('tbl_fund_transfer.to_user_id');
				$fundReport = $fundReport->where('tbl_fund_transfer.wallet_type',6)->orderBy('tbl_fund_transfer.entry_time','desc');			
				$arrPendings = $fundReport->skip($request->input('start'))->take($request->input('length'))->get();
								
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				// dd($arrPendings);
				if (count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				}
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

	public function AllIdPurchaseBalanceReport(Request $request) {

		try {
			$Checkexist = Auth::User(); // check use is active or not
			if (!empty($Checkexist)) {
				
				$topupReport = DB::table('tbl_users as tu')->join('tbl_dashboard as td', 'td.id', '=', 'tu.id')->select('tu.fullname as name','tu.user_id',DB::raw('ROUND(td.top_up_wallet - td.top_up_wallet_withdraw , 2) as amount'))
					->where('tu.email',$Checkexist->email)->where('tu.id','!=',$Checkexist->id)
					->where('tu.status','Active')
					->where(DB::raw('round(td.top_up_wallet - td.top_up_wallet_withdraw,2)') ,'>',0);
				if (isset($request->user_id)){
					$topupReport = $topupReport->where('tu.user_id',$request->input('user_id'));
				}

				/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					$search = $request->input('search')['value'];
					$topupReport = $topupReport->orWhere('tu.fullname', 'LIKE', '%' . $search . '%')->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
				}*/
				$totalRecord = $topupReport->count();
				$query = $topupReport;/*DB::enableQueryLog();*/
				$arrPendings = $topupReport->skip($request->input('start'))->take($request->input('length'))->get();//dd(DB::getQueryLog());
				$totalAmount = User::join('tbl_dashboard as td', 'td.id', '=', 'tbl_users.id')
								->selectRaw('sum(td.top_up_wallet - td.top_up_wallet_withdraw) as total_amount')
								->where('tbl_users.email',$Checkexist->email)->where('tbl_users.id','!=',$Checkexist->id)
								->where(DB::raw('round(td.top_up_wallet - td.top_up_wallet_withdraw,2)'), '>', 0)
								->where('tbl_users.status','Active')
								->groupBy('tbl_users.email')->pluck('total_amount')->first();
				$withdrawAmount = User::join('tbl_dashboard as td', 'td.id', '=', 'tbl_users.id')
								->selectRaw('sum(td.top_up_wallet - td.top_up_wallet_withdraw) as total_amount')
								->where('tbl_users.email',$Checkexist->email)->where('tbl_users.id','!=',$Checkexist->id)
								->where(DB::raw('td.top_up_wallet - td.top_up_wallet_withdraw'), '>=', 1)
								->where('tbl_users.status','Active')
								->groupBy('tbl_users.email')->pluck('total_amount')->first();
				
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				$arrData['totalAmount']   = round($totalAmount,2);
				$arrData['withdrawAmount']   = round($withdrawAmount,2);
				if (count($arrPendings) > 0) {
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
	
	public function UserPurchaseTransferReceiveReport(Request $request) {

		try {
			$arrInput = $request->all();
			$Checkexist = Auth::User(); // check use is active or not
			if (!empty($Checkexist)) {

				$fundReport = FundTransfer::select('tu1.user_id','tu1.fullname as name','tu2.user_id as to_user_id','tu2.fullname as toname',DB::raw('ROUND(tbl_fund_transfer.amount,2) as amount'),'tbl_fund_transfer.entry_time',DB::raw('ROUND(td.top_up_wallet - td.top_up_wallet_withdraw , 2) as balance'))
					->join('tbl_users as tu1','tu1.id',"=",'tbl_fund_transfer.from_user_id')
					->join('tbl_users as tu2','tu2.id',"=",'tbl_fund_transfer.to_user_id')
					->join('tbl_dashboard as td','td.id',"=",'tbl_fund_transfer.from_user_id')
					->where('tu1.email',$Checkexist->email)
					->where('tbl_fund_transfer.wallet_type',7);

				/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					$search = $request->input('search')['value'];					
					$fundReport->orWhere('tu.name', 'LIKE', '%' . $search . '%')->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
				}*/

				if (isset($arrInput['frmuser_id'])) {
				    $fundReport  = $fundReport->where('tu1.user_id', $arrInput['frmuser_id']);
				}

				if (isset($arrInput['touser_id'])) {
				    $fundReport  = $fundReport->where('tu2.user_id', $arrInput['touser_id']);
				}

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$fundReport = $fundReport->whereBetween(DB::raw("DATE_FORMAT(tbl_fund_transfer.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
				}

				$totalRecord = $fundReport->count('tbl_fund_transfer.to_user_id');
				$fundReport = $fundReport->orderBy('tbl_fund_transfer.entry_time','desc');			
				$arrPendings = $fundReport->skip($request->input('start'))->take($request->input('length'))->get();
								
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				if (count($arrPendings) > 0) {
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

	public function PendingPurchaseTransferBalanceRequest(Request $request) {

		try {
			$arrInput = $request->all();
			$Checkexist = Auth::User(); // check use is active or not
			if (!empty($Checkexist)) {

				$fundReport = PurchaseBalanceTransfer::select('tu.user_id','tu.fullname as name',DB::raw('ROUND(tbl_purchase_balance_transfer.amount,2) as amount'),'tbl_purchase_balance_transfer.email','tbl_purchase_balance_transfer.entry_time')
				->join('tbl_users as tu','tu.id',"=",'tbl_purchase_balance_transfer.user_id')
				->where('tbl_purchase_balance_transfer.status',0)->where('tbl_purchase_balance_transfer.email',$Checkexist->email);

				/*if (!empty($request->type) && isset($request->type) && $request->type == 'transfer') {
					$fundReport = $fundReport->join('tbl_users as tu','tu.id',"=",'tbl_fund_transfer.to_user_id')->where('tbl_fund_transfer.from_user_id',$Checkexist->id);
				}else{
					$fundReport = $fundReport->join('tbl_users as tu','tu.id',"=",'tbl_fund_transfer.from_user_id')->where('tbl_fund_transfer.to_user_id',$Checkexist->id);
				}*/
				/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					$search = $request->input('search')['value'];					
					$fundReport->orWhere('tu.name', 'LIKE', '%' . $search . '%')->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
				}*/

				if (isset($arrInput['user_id'])) {
				    $fundReport  = $fundReport->where('tu.user_id', $arrInput['user_id']);
				}

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$fundReport = $fundReport->whereBetween(DB::raw("DATE_FORMAT(tbl_purchase_balance_transfer.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
				}

				$totalRecord = $fundReport->count('tbl_purchase_balance_transfer.user_id');
				$query = $fundReport;			
				$arrPendings = $fundReport->skip($request->input('start'))->take($request->input('length'))->get();
								
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				if (count($arrPendings) > 0) {
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
	public function DexToPurchaseTransferReport(Request $request){

		try {
			$arrInput = $request->all();
			$id = Auth::User()->id; // check use is active or not
			if (!empty($id)) {

				$fundReport = DexToPurchaseFundTransfer::select('tu.user_id','tu.fullname as name',DB::raw('ROUND(tbl_dex_to_purchase_transfer.amount,2) as amount'),DB::raw('ROUND(tbl_dex_to_purchase_transfer.net_amount,2) as net_amount'),DB::raw('ROUND(tbl_dex_to_purchase_transfer.transfer_charge,2) as transfer_charge'),'tbl_dex_to_purchase_transfer.entry_time',DB::raw('(CASE  WHEN tbl_dex_to_purchase_transfer.from_wallet_type = 1 THEN "Working Wallet" ELSE "Purchase Wallet" END ) as from_wallet_type'),DB::raw('(CASE  WHEN tbl_dex_to_purchase_transfer.to_wallet_type = 1 THEN "Working Wallet" ELSE "Purchase Wallet" END ) as to_wallet_type'))
				->join('tbl_users as tu','tu.id',"=",'tbl_dex_to_purchase_transfer.to_user_id')
				->where('tbl_dex_to_purchase_transfer.to_user_id',$id);

				/*if (!empty($request->type) && isset($request->type) && $request->type == 'transfer') {
					$fundReport = $fundReport->join('tbl_users as tu','tu.id',"=",'tbl_fund_transfer.to_user_id')->where('tbl_fund_transfer.from_user_id',$Checkexist->id);
				}else{
					$fundReport = $fundReport->join('tbl_users as tu','tu.id',"=",'tbl_fund_transfer.from_user_id')->where('tbl_fund_transfer.to_user_id',$Checkexist->id);
				}*/
				/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					$search = $request->input('search')['value'];					
					$fundReport->orWhere('tu.name', 'LIKE', '%' . $search . '%')->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
				}*/

				if (isset($arrInput['user_id'])) {
				    $fundReport  = $fundReport->where('tu.user_id', $arrInput['user_id']);
				}

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$fundReport = $fundReport->whereBetween(DB::raw("DATE_FORMAT(tbl_dex_to_purchase_transfer.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
				}

				$totalRecord = $fundReport->count('tbl_dex_to_purchase_transfer.id');
				$fundReport = $fundReport->orderBy('tbl_dex_to_purchase_transfer.entry_time','desc');			
				$arrPendings = $fundReport->skip($request->input('start'))->take($request->input('length'))->get();
								
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				if (count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				}
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

	public function downlineBuyTokenReport(Request $request) {

		try {
			$arrInput = $request->all();
			$userId = Auth::User()->id; // check use is active or not
			if (!empty($userId)) {

				$query = TodayDetails::select('tr.srno','ur.user_id','ph.name','tr.coin','tr.entry_time')
				->leftjoin('tbl_all_transaction as tr','tbl_today_details.from_user_id','=','tr.id')
				->leftjoin('tbl_users as ur','ur.id','=','tr.id')
				->leftjoin('tbl_phases as ph','tr.phases_id','=','ph.srno')
				->where([['tbl_today_details.to_user_id',$userId],['tr.type','Buy Coin']]);
				/*if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
					
					$fields = getTableColumns('tbl_topup');
					$search = $request->input('search')['value'];
					$topupReport = $topupReport->where(function ($topupReport) use ($fields, $search) {
						foreach ($fields as $field) {
							$topupReport->orWhere('tbl_topup.' . $field, 'LIKE', '%' . $search . '%');
						}
						$topupReport->orWhere('tp.name', 'LIKE', '%' . $search . '%')
							->orWhere('tu.user_id', 'LIKE', '%' . $search . '%');
					});
				}*/

				if (isset($arrInput['user_id'])) {
				    $query  = $query->where('ur.user_id', $arrInput['user_id']);
				}

				if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
					$query = $query->whereBetween(DB::raw("DATE_FORMAT(tr.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
				}

				$totalRecord = $query->count('tr.srno');
				$arrPendings = $query->skip($request->input('start'))->take($request->input('length'))->get();

				//150 days 1 % daily
				$arrData['recordsTotal'] = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records'] = $arrPendings;
				//$arrData['Totalrecords1']   = count($arrPendings);
				if (count($arrPendings) > 0) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
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


  public function incomeGraphReportvue(Request $request) 
	{

		try {
			$userId = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			$mainArray = array();
			if (!empty($userId)) {
				$currmonth = \Carbon\Carbon::now()->format('m');
				// dd($currmonth);
				$arrData = $arrData1 = [];
				for ($i = 0; $i <= ($currmonth-1); $i++) {

					$Todate = date('Y-m', strtotime("-$i month"));

					// $leveldata = LevelIncome::selectRaw('ANY_VALUE(entry_time) as entry_time,ANY_VALUE(sum(amount)) as amount')->where([['tbl_level_income.toUserId', '=', $userId], ['tbl_level_income.status', '=', 1]])
					// 	->where(DB::raw("DATE_FORMAT(entry_time,'%Y-%m')"), '=', $Todate)
					// 	->groupBy(DB::raw("DATE_FORMAT(entry_time,'%Y-%m')"))->first();

					$directData = DirectIncome::selectRaw('ANY_VALUE(entry_time) as entry_time,ANY_VALUE(sum(amount)) as amount')->where('tbl_directincome.toUserId', '=', $userId)
						->where(DB::raw("DATE_FORMAT(entry_time,'%Y-%m')"), '=', $Todate)
						->groupBy(DB::raw("DATE_FORMAT(entry_time,'%Y-%m')"))->first();

					$binaryData = PayoutHistory::selectRaw('ANY_VALUE(created_at) as entry_time,ANY_VALUE(sum(amount)) as amount')->where('tbl_payout_history.user_id', '=', $userId)
						->where(DB::raw("DATE_FORMAT(entry_time,'%Y-%m')"), '=', $Todate)
						->groupBy(DB::raw("DATE_FORMAT(entry_time,'%Y-%m')"))->first();

					$residualData = ResidualIncome::selectRaw('ANY_VALUE(entry_time) as entry_time,ANY_VALUE(sum(amount)) as amount')->where('tbl_residual_income.id', '=', $userId)
						->where(DB::raw("DATE_FORMAT(entry_time,'%Y-%m')"), '=', $Todate)
						->groupBy(DB::raw("DATE_FORMAT(entry_time,'%Y-%m')"))->first();

					$roiData = DailyBonus::join('tbl_product as tp', 'tbl_dailybonus.type', '=', 'tp.id')->selectRaw('ANY_VALUE(tbl_dailybonus.entry_time) as entry_time,ANY_VALUE(sum(amount)) as amount')->where('tbl_dailybonus.id', '=', $userId)
						->where(DB::raw("DATE_FORMAT(tbl_dailybonus.entry_time,'%Y-%m')"), '=', $Todate)
						->orderBy(DB::raw("DATE_FORMAT(tbl_dailybonus.entry_time,'%Y-%m')"))->first();

					$date = date("d/m/Y", strtotime($Todate));

					$arrData['date'] = $date;

					// $arrData['level'] = round((isset($leveldata->amount) && !empty($leveldata->amount) ? $leveldata->amount : 0),2);
					$arrData['direct'] = round((isset($directData->amount) && !empty($directData->amount) ? $directData->amount : 0),2);
					$arrData['binary'] = round((isset($binaryData->amount) && !empty($binaryData->amount) ? $binaryData->amount : 0),2);
					$arrData['residual'] = round((isset($residualData->amount) && !empty($residualData->amount) ? $residualData->amount : 0),2);
					$arrData['roi'] = round((isset($roiData->amount) && !empty($roiData->amount) ? $roiData->amount : 0),2);
					array_push($mainArray, $arrData);
				}
				if (!empty($arrData)) {
					$arrStatus = Response::HTTP_OK;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data found successfully';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $mainArray);
				} else {
					$arrStatus = Response::HTTP_NOT_FOUND;
					$arrCode = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
			} else {
				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Invalid user';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
		} catch (Exception $e) {
			// dd($e);
			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

   public function getReferralDirects(Request $request) {

		try {

			$userId = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($userId)) {
				// dd($userId);
				$Refdata = User::Leftjoin('tbl_topup as tp', 'tbl_users.id', '=', 'tp.id')
				    ->join('tbl_users as tu' , 'tu.id', '=' ,'tbl_users.ref_user_id')
					->select('tbl_users.user_id','tu.user_id as sponser_id','tbl_users.fullname','tbl_users.email','tp.id','tp.type','tp.roi_status','tp.product_name','tp.amount','tp.entry_time')
					->where([['tbl_users.ref_user_id', '=', $userId]])
					->orderBy('tp.entry_time', 'desc')->limit(5)->get();
				//  dd($Refdata);
				// if (!empty($request->input('search')['value']) && isset($request->input('search')['value'])) {
				// 	//searching loops on fields
				// 	$fields     = getTableColumns('tbl_users');
				// 	$search     = $request->input('search')['value'];
				// 	$Refdata = $Refdata->where(function ($Refdata) use ($fields, $search) {
				// 			foreach ($fields as $field) {
				// 				$Refdata->orWhere('tbl_users.'.$field, 'LIKE', '%'.$search.'%');
				// 			}
				// 			$Refdata->orWhere('tu.user_id', 'LIKE', '%'.$search.'%')
				// 			->orWhere('tu1.user_id', 'LIKE', '%'.$search.'%')
				// 				->orWhere('tp.name', 'LIKE', '%'.$search.'%')
				// 			->orWhere(DB::raw('(CASE  WHEN tbl_users.status = 1 THEN "Paid" END )'), 'LIKE', '%'.$search.'%');
				// 		});
				// }
				$totalRecord = $Refdata->count('tp.id');
				// $arrPendings = $Refdata->skip($request->input('start'))->take($request->input('length'))->get();

				$arrData['recordsTotal']    = $totalRecord;
				$arrData['recordsFiltered'] = $totalRecord;
				$arrData['records']         = $Refdata;
				if (!empty($Refdata) && count($Refdata) != '0') {
					$arrStatus  = Response::HTTP_OK;
					$arrCode    = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Referral data found successfully';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus  = Response::HTTP_NOT_FOUND;
					$arrCode    = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Referral data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
			} else {
				$arrStatus  = Response::HTTP_NOT_FOUND;
				$arrCode    = Response::$statusTexts[$arrStatus];
				$arrMessage = 'User Id not found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
		} catch (Exception $e) {
			dd($e);
			$arrStatus  = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode    = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}


	public function getReferralTopup(Request $request) {

		try {

			$userId = Auth::User()->id;
			// ini_set('memory_limit', '-1');
			if (!empty($userId)) {
				// dd($userId);
				$RefActivedata = User::where([['tbl_users.ref_user_id', '=', $userId]])
								->where([['tbl_users.topup_status', '=', '1']])
					            ->count();
				$RefINActivedata = User::where([['tbl_users.ref_user_id', '=', $userId]])
				->where([['tbl_users.topup_status', '=', '0']])
				->count();					
				$arrData['activedata'] = $RefActivedata;			
				$arrData['Inactivedata'] = $RefINActivedata;
				if (!empty($arrData)) {
					$arrStatus  = Response::HTTP_OK;
					$arrCode    = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Referral data Status found successfully';
					return sendResponse($arrStatus, $arrCode, $arrMessage, $arrData);
				} else {
					$arrStatus  = Response::HTTP_NOT_FOUND;
					$arrCode    = Response::$statusTexts[$arrStatus];
					$arrMessage = 'Referral data not found';
					return sendResponse($arrStatus, $arrCode, $arrMessage, '');
				}
			} else {
				$arrStatus  = Response::HTTP_NOT_FOUND;
				$arrCode    = Response::$statusTexts[$arrStatus];
				$arrMessage = 'User Id not found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}
		} catch (Exception $e) {
			dd($e);
			$arrStatus  = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode    = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

}
