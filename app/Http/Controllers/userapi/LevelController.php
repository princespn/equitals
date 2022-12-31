<?php

namespace App\Http\Controllers\userapi;

use App\Http\Controllers\Controller;
use App\Models\LevelView;
use App\Models\ProjectSettings;
use App\Models\TodayDetails;
use App\Models\Topup;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller {

	public function __construct() {
		$this->statuscode = Config::get('constants.statuscode');
		$this->emptyArray = (object) array();
		$date = \Carbon\Carbon::now();
		$this->today = $date->toDateTimeString();

		$this->settings = Config::get('constants.settings');
		$this->proSettings = ProjectSettings::where('status', 1)->first();
	}

	public function getLevels(Request $request) {

		$userid = Auth::User()->id;
		if (!empty($userid)) {
			$levels = LevelView::DISTINCT()->where(array(['id', '=', $userid]))->orderBy('level', 'asc')->get(['level']);
			$myArray = array();

			if (count($levels) > 0) {
				foreach ($levels as $level) {
					//   if($level->level != 1)
					// {
					$level_id = $level->level;
					$level_name = 'level ' . $level->level;
					$arr = ['level_id' => $level_id, 'level_name' => $level_name];
					array_push($myArray, $arr);
					//   }

				}
				// dd($myArray);
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Data Found';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $myArray);
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
	}

	/**
	 * [show dashboard Levels ]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */

	public function showDashboardLevels(Request $request) {
		$rules = array(
			'limit' => 'required|',
		);

		$validator = checkvalidation($request->all(), $rules, '');
		if (!empty($validator)) {

			$arrStatus = Response::HTTP_NOT_FOUND;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = $validator;
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
		$id = Auth::User()->id;

		if (!empty($id)) {
			$levels = DB::table('tbl_level_view as tlv')
				->join('tbl_users as tu1', 'tu1.id', '=', 'tlv.id')
				->leftjoin('tbl_users as tu2', 'tu2.id', '=', 'tlv.down_id')
				->leftjoin('tbl_dashboard as td', 'td.id', '=', 'tu2.id')
				->where('tlv.id', $id)
				->select('tu1.id', 'tu1.user_id as user_id', 'tu1.fullname as fullname', 'tu2.id as down_id', 'tu2.user_id as down_user_id', 'tu2.fullname as downId_fullname', 'tlv.level', 'tlv.entry_time')
				->orderBy('tlv.entry_time', 'desc')
				->limit($request->Input('limit'))
				->get();

			if (count($levels) > 0) {
				$arrStatus = Response::HTTP_OK;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = 'Leadership income data found successfully';
				return sendResponse($arrStatus, $arrCode, $arrMessage, $levels);

				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Data Found', $levels);
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
	}

	public function direct_list(Request $request) {

		$arrInput = $request->all();
		$data = [];
		$id = Auth::user()->id;

		// @var [collect self and sponser info] /
		$data['id'] = $id;
		// @var [collect child info] /

		$query = User::selectRaw('user_id,fullname,mobile,amount,position,DATE_FORMAT(tbl_users.entry_time,"%Y/%m/%d") as entry_time,(CASE position WHEN 1 THEN "Left" WHEN 2 THEN "Right" ELSE "" END) as position,status')
			->where('ref_user_id', $id);

		$query = $query->orderBy('tbl_users.entry_time', 'desc');

		$arrData = setPaginate1($query, $arrInput['start'], $arrInput['length'], '');

		if (count($query) > 0) {
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
	 * get levels views tree manual filling
	 * id, remembered token
	 * @return
	 */
	public function getLevelsViewTreeManualProductBase(Request $request) {

		$arrInput = $request->all();
		$levelArray = [];

		//cross leg condition
		if (isset($arrInput['id']) && !empty($arrInput['id']) && Auth::user()->user_id != $arrInput['id']) {
			$checkUser = User::select('tbl_users.id')->join('tbl_today_details as ttd', 'ttd.from_user_id', '=', 'tbl_users.id')->where(['tbl_users.user_id' => $arrInput['id'], 'ttd.to_user_id' => Auth::user()->id])->first();

			if (empty($checkUser)) {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User not available', '');
			}
		}

		$objUser = User::leftjoin('tbl_users as tu', 'tu.id', '=', 'tbl_users.ref_user_id')
			->leftjoin('tbl_users as tu1', 'tu1.id', '=', 'tbl_users.virtual_parent_id')
			//->leftjoin('tbl_curr_amt_details as tcad', 'tcad.user_id', '=', 'tbl_users.id')
			->leftjoin('tbl_topup as tt', function($objUser) {
			    $objUser->on('tt.id','=','tbl_users.id')
			    ->whereRaw('tt.srno IN (select MAX(tt1.srno) from tbl_topup as tt1 join tbl_users as u2 on u2.id = tt1.id group by u2.id)');
			})
			->leftjoin('tbl_product as tp', 'tp.id', '=', 'tt.type')
			->selectRaw('tbl_users.id,tbl_users.rank,tbl_users.user_id,tbl_users.fullname,tu1.user_id as virtual_parent_id,tu.user_id as sponsor_id,tu.fullname as sponsor_fullname,tbl_users.l_c_count,tbl_users.r_c_count,COALESCE(tbl_users.l_bv,0) as l_bv,COALESCE(tbl_users.r_bv,0) as r_bv,COALESCE(tbl_users.curr_l_bv,0) as left_bv,COALESCE(tbl_users.curr_r_bv,0) as right_bv,tbl_users.position,"0" as level,(CASE WHEN  tbl_users.status = "Inactive" THEN "' . $this->settings['block_img'] . '" WHEN tbl_users.topup_status = "0" THEN "' . $this->settings['no_topup'] . '" WHEN tbl_users.topup_status = "1" THEN "' . $this->settings['present_img'] . '"  ELSE "" END) as image,(CASE WHEN  tbl_users.status = "Inactive" THEN "block-card" WHEN tbl_users.topup_status = "0" THEN "inactive-card" WHEN tbl_users.topup_status = "1" THEN "active-card"  ELSE "" END) as cardclass,tbl_users.entry_time,tbl_users.amount as selftopup,tbl_users.amount as usertopup,(CASE WHEN tt.type = "1" THEN "Elegant" WHEN tt.type = "2" THEN "Pro" WHEN tt.type = "3" THEN "Premium" WHEN tt.type = "4" THEN "Ultra" WHEN tt.type = "5" THEN "Ace" ELSE "" END) as product_name');

		if (isset($arrInput['id']) && !empty($arrInput['id'])) {
			// dd('amol');
			$objUser = $objUser->where('tbl_users.user_id', $arrInput['id']);

		} else {
			// dd(Auth::user()->user_id);
			$objUser = $objUser->where('tbl_users.user_id', Auth::user()->user_id);
		}
		$objUser = $objUser->first();

		/*$topup_product =Topup::select('product_name')->where('id',$objUser->id)->orderBy('entry_time','desc')->pluck('product_name')->first();

		$arrData[0]['product_name'] = '';
		if (!empty($topup_product)) {
			$arrData[0]['product_name'] = $topup_product;
		}*/
		// dd($checkUser);
		// dd($objUser);
		//dd($topup, $objUser);
		//$topup = DB::table('tbl_topup')->where('id', $objUser->id)->selectRaw('COALESCE (sum(amount),0) as selftopup')->get();
		/*$topup = Topup::where('id', $objUser->id)->selectRaw('COALESCE (sum(amount),0) as selftopup')->get();*/


		/*$objUser->usertopup = $topup[0]->selftopup;*/
		//dd($objUser->id, $objUser->selftopup, $objUser->selftopup11, $topup[0]->selftopup);

		if (isset($objUser) && !empty($objUser)) {

			$levlQr = $this->getLevelsViewTreeDataByIdForProductBase($objUser->id, $objUser->id);
			//dd($levlQr);
			if (!empty($levlQr) && count($levlQr) > 0) {

				foreach ($levlQr as $value1) {
					//dd("hii".$objUser->id,$value1->id);
					$levelArray[$value1->level][$objUser->id][$value1->position] = $value1;
					$levlQrT = $this->getLevelsViewTreeDataByIdForProductBase($objUser->id, $value1->id);
					//  dd($levlQrT);

					if (!empty($levlQrT) && count($levlQrT) > 0) {

						foreach ($levlQrT as $value2) {

							$levelArray[$value2->level][$value1->id][$value2->position] = $value2;
							$levlQrT11 = $this->getLevelsViewTreeDataByIdForProductBase($objUser->id, $value2->id);

							if (!empty($levlQrT11) && count($levlQrT11) > 0) {

								foreach ($levlQrT11 as $value3) {

									$levelArray[$value3->level][$value2->id][$value3->position] = $value3;

									/*$levlQrT12 = $this->getLevelsViewTreeDataByIdForProductBase($objUser->id, $value3->id);
									
									if (!empty($levlQrT12) && count($levlQrT12) > 0){
										foreach ($levlQrT12 as $value4){
											$levelArray[$value4->level][$value3->id][$value4->position] = $value4;
										}

									}*/
								}
							}
						}
					}
				}
			}
		}

		//dd($levelArray);

		//matrix value from project settings
		$matrixValue = explode(":", $this->proSettings->matrix_value)[0];
		//upto level show from project settings
		$levelValue = $this->proSettings->level_show;
		$counter = 1;
		$arrPos = [];
		for ($x = 1; $x <= $matrixValue; $x++) {
			for ($y = 1; $y <= $matrixValue; $y++) {
				$arrPos[$x][$y] = $counter;
				$counter++;
			}
		}
		$arrLevelsTemp = [];
		$counter1 = 1;
		if (!empty($levelArray) && count($levelArray) > 0) {
			foreach ($levelArray as $key => $value) {
				foreach ($value as $key1 => $value1) {
					if ($key >= 3) {
						$objPos = User::select('tus.position as vp_position', 'tbl_users.position as position')->join('tbl_users as tus', 'tus.id', '=', 'tbl_users.virtual_parent_id')
							->where('tbl_users.id', $key1)->first();

						$arrLevelsTemp[$key][$arrPos[$objPos->vp_position][$objPos->position]] = $value1;
					} else {
						$position = ($counter1 == 1) ? 1 : User::where('id', $key1)->pluck('position')->first();
						$arrLevelsTemp[$key][$position] = $value1;
					}
					$counter1++;
				}
			}
		}
		//object created for absent data left
		$arrTemp = [];
		for ($i = 1; $i <= 3; $i++) {
			$x = pow($matrixValue, $i - 1);
			for ($j = 1; $j <= $x; $j++) {
				for ($k = 1; $k <= $matrixValue; $k++) {
					if (isset($arrLevelsTemp[$i][$j][$k]) && !empty($arrLevelsTemp[$i][$j][$k])) {
						$arrTemp[$i][$j][$k] = $arrLevelsTemp[$i][$j][$k];
						
					} else {

						if (isset($arrInput['reqFrom'])) {
							$NotAvailable = "Absent";

						} else {
							$NotAvailable = 0;
						}
						$arrTemp[$i][$j][$k] = (object) array(
							"id" => $NotAvailable,
							"user_id" => $NotAvailable,
							"fullname" => $NotAvailable,
							"sponsor_id" => $NotAvailable,
							"sponsor_fullname" => $NotAvailable,
							"virtual_id" => $NotAvailable,
							"virtual_fullname" => $NotAvailable,
							"l_c_count" => $NotAvailable,
							"r_c_count" => $NotAvailable,
							"l_bv" => $NotAvailable,
							"r_bv" => $NotAvailable,
							"left_bv" => $NotAvailable,
							"right_bv" => $NotAvailable,
							"left_bv_rep" => $NotAvailable,
							"right_bv_rep" => $NotAvailable,
							"position" => $k,
							"virtual_parent_id" => $NotAvailable,
							"level" => $i,
							"image" => $this->settings['absent_img'],
							"cardclass"      => "absent-card",
							"entry_time" => $NotAvailable,
							"selftopup" => $NotAvailable,
							"rank"      => $NotAvailable,
							"product_name"      => $NotAvailable,
							//"userselftopup" => $NotAvailable,
						);
					}
				}
			}
		}
		/*dd($arrTemp);*/
		$arrFinalData = [];
		$count = 0;
		foreach ($arrTemp as $key => $value) {
			$arrFinalData[$count]['level'] = [];
			foreach ($value as $key1 => $value1) {
				foreach ($value1 as $key2 => $value2) {

					/*if ($value2->id != 0 && $value2->id != 'Not Available') {*/

						/*$topup = DB::table('tbl_topup')->where('id', $value2->id)->selectRaw('COALESCE (sum(amount),0) as selftopup')->get();

						$value2->selftopup = $topup[0]->selftopup;*/
						//dd($arrFinalData, $value2);
					/*}*/

					array_push($arrFinalData[$count]['level'], $value2);
				}
			}
			$count++;
		}
		$arrDataTemp = [];
		foreach ($arrFinalData as $key => $value) {
			$arrDataTemp[] = $value;
		}
		if (!empty($objUser)) {
			$arrData['user'] = $objUser;
			$arrData['tree_data'] = $arrDataTemp;

			$getrankDetails = User::select('tbl_users.l_ace', 'tbl_users.r_ace', 'tbl_users.l_herald', 'tbl_users.r_herald', 'tbl_users.l_crusader', 'tbl_users.r_crusader', 'tbl_users.l_guardian', 'tbl_users.r_guardian', 'tbl_users.l_commander', 'tbl_users.r_commander', 'tbl_users.l_valorant', 'tbl_users.r_valorant', 'tbl_users.l_legend', 'tbl_users.r_legend', 'tbl_users.l_relic', 'tbl_users.r_relic', 'tbl_users.l_almighty', 'tbl_users.r_almighty', 'tbl_users.l_conqueror', 'tbl_users.r_conqueror', 'tbl_users.l_titan', 'tbl_users.r_titan', 'tbl_users.l_lmmortal', 'tbl_users.r_immortal')->where([['tbl_users.id', '=', Auth::user()->id]])->first();

			$getrankDetails->total_left_rank_count = $getrankDetails->l_ace + $getrankDetails->l_herald + $getrankDetails->l_crusader + $getrankDetails->l_guardian + $getrankDetails->l_commander + $getrankDetails->l_valorant + $getrankDetails->l_legend + $getrankDetails->l_relic +$getrankDetails->l_almighty + $getrankDetails->l_conqueror + $getrankDetails->l_titan + $getrankDetails->l_lmmortal;
			
			$getrankDetails->total_right_rank_count = $getrankDetails->r_ace + $getrankDetails->r_herald + $getrankDetails->r_crusader + $getrankDetails->r_guardian + $getrankDetails->r_commander + $getrankDetails->r_valorant + $getrankDetails->r_legend + $getrankDetails->r_relic + $getrankDetails->r_almighty + $getrankDetails->r_conqueror + $getrankDetails->r_titan +  $getrankDetails->r_immortal;

			$arrData['user_rankdata'] = $getrankDetails;

			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Record not found', '');
		}
	}

	private function getLevelsViewTreeDataByIdForProductBase($to_user_id, $virtual_parent_id) {
		// dd($to_user_id,$virtual_parent_id);
		$arrData = DB::table('tbl_users')
			->leftjoin('tbl_users as tu', 'tu.id', '=', 'tbl_users.ref_user_id')
			->leftjoin('tbl_users as tu1', 'tu1.id', '=', 'tbl_users.virtual_parent_id')
			->leftjoin('tbl_today_details as ttd', 'ttd.from_user_id', '=', 'tbl_users.id')
			// ->leftjoin('tbl_curr_amt_details as tcad', 'tcad.user_id', '=', 'tbl_users.id')
			->leftjoin('tbl_topup as tt', function($objUser) {
			    $objUser->on('tt.id','=','tbl_users.id')
			    ->whereRaw('tt.srno IN (select MAX(tt1.srno) from tbl_topup as tt1 join tbl_users as u2 on u2.id = tt1.id group by u2.id)');
			})
			->leftjoin('tbl_product as tp', 'tp.id', '=', 'tt.type')
			->selectRaw('tbl_users.id,tbl_users.rank,tbl_users.user_id,tbl_users.fullname,tu1.user_id as virtual_parent_id,tu.user_id as sponsor_id,tu.fullname as sponsor_fullname,tbl_users.l_c_count,tbl_users.r_c_count,COALESCE(tbl_users.l_bv,0) as l_bv,COALESCE(tbl_users.r_bv,0) as r_bv,COALESCE(tbl_users.curr_l_bv,0) as left_bv,COALESCE(tbl_users.curr_r_bv,0) as right_bv,tbl_users.position,ttd.level,(CASE WHEN  tbl_users.status = "Inactive" THEN "' . $this->settings['block_img'] . '" WHEN tbl_users.topup_status = "0" THEN "' . $this->settings['no_topup'] . '" WHEN tbl_users.topup_status = "1" THEN "' . $this->settings['present_img'] . '"  ELSE "" END) as image,(CASE WHEN  tbl_users.status = "Inactive" THEN "block-card" WHEN tbl_users.topup_status = "0" THEN "inactive-card" WHEN tbl_users.topup_status = "1" THEN "active-card"  ELSE "" END) as cardclass,tbl_users.entry_time,tbl_users.amount as selftopup,(CASE WHEN tt.type = "1" THEN "Elegant" WHEN tt.type = "2" THEN "Pro" WHEN tt.type = "3" THEN "Premium" WHEN tt.type = "4" THEN "Ultra" WHEN tt.type = "5" THEN "Ace" ELSE "" END) as product_name')
			->where('tbl_users.virtual_parent_id', $virtual_parent_id)
			->where('ttd.to_user_id', $to_user_id)
			->orderBy('tbl_users.position', 'asc')
			->get();
		// dd($arrData);
		if (isset($arrData) && !empty($arrData)) {

			//$topup = DB::table('tbl_topup')->where('id', $to_user_id)->selectRaw('COALESCE (sum(amount),0) as selftopup')->get();
			//$arrData[0]['userselftopup'] = $topup[0]->selftopup;
			//dd($arrData);
			return $arrData;
		} else {
			return [];
		}

		$arrData = $arrData->first();

		/*$topup_product =Topup::select('product_name')->where('id',$arrData->id)->orderBy('entry_time','desc')->pluck('product_name')->first();

		$arrData[0]['product_name'] = '';
		if (!empty($topup_product)) {
			$arrData[0]['product_name'] = $topup_product;
		}*/
	}

	public function getTeamView(Request $request) {
		$arrInput = $request->all();

	//	if (isset($arrInput['user_id'])) {
		//	$user = User::where('user_id', $arrInput['user_id'])->first();
	//	} else {
			$user = Auth::user();
	//	}

		$array = [
			'left_id' => $user->l_c_count,
			'right_id' => $user->r_c_count,
			'left_bv' => $user->l_bv,
			'right_bv' => $user->r_bv,
		];

		$query = TodayDetails::select('tu.amount','tu.id', 'tu.user_id', 'tu.fullname', 'tu1.user_id as sponser_id', 'tu2.user_id as upline_id', DB::raw('(CASE tbl_today_details.position WHEN "1" THEN "Left" WHEN "2" THEN "Right" ELSE "" END) as position'), DB::raw('DATE_FORMAT(tu.entry_time,"%Y/%m/%d %H:%i:%s") as joining_date'),DB::raw('(CASE tu.topup_status WHEN "1" THEN "Active" WHEN "0" THEN "Inactive" ELSE "" END) as status'), 'tu.l_c_count as left_id', 'tu.r_c_count as right_id', 'tu.l_bv as left_bv', 'tu.r_bv as right_bv', 'tu.pin_number')
			->join('tbl_users as tu', 'tu.id', '=', 'tbl_today_details.from_user_id')
			->join('tbl_users as tu1', 'tu1.id', '=', 'tu.ref_user_id')
			->join('tbl_users as tu2', 'tu2.id', '=', 'tu.virtual_parent_id')
			->where('tbl_today_details.to_user_id', $user->id);

		/*if (isset($arrInput['status'])) {
			$query = $query->where('tbl_withdraw_link.status', $arrInput['status']);
		}*/
		if (isset($arrInput['position'])) {
			$query = $query->where('tbl_today_details.position', $arrInput['position']);
		}
		if (isset($arrInput['status'])) {
			$query = $query->where('tu.topup_status', $arrInput['status']);
		}
		 if (isset($arrInput['user_id'])) {
		     $query  = $query->where('tu.user_id', $arrInput['user_id']);
		 }
		if (isset($arrInput['sponsor_id'])) {
			$query = $query->where('tu1.user_id', $arrInput['sponsor_id']);
		}
		if (isset($arrInput['upline_id'])) {
			$query = $query->where('tu2.user_id', $arrInput['upline_id']);
		}
		// $query->when(request('position') == 'Left', function ($q) {
		//     return $q->where('tbl_today_details.position', '=', '1');
		// });
		// $query->when(request('position') == 'Right', function ($q) {
		//     return $q->where('tbl_today_details.position', '=', '2');
		// });
		if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			$query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_today_details.entry_time,'%Y-%m-%d')"), [date('Y-m-d', strtotime($arrInput['frm_date'])), date('Y-m-d', strtotime($arrInput['to_date']))]);
		}
		if (isset($arrInput['search']['value']) && !empty($arrInput['search']['value'])) {
			//searching loops on fields
			$fields = ['tu.user_id', 'tu.fullname', 'tu1.user_id', 'tu2.user_id', 'tu.l_c_count', 'tu.r_c_count', 'tu.l_bv', 'tu.r_bv', 'tu.pin_number'];
			$search = $arrInput['search']['value'];
			$query = $query->where(function ($query) use ($fields, $search) {
				foreach ($fields as $field) {
					$query->orWhere($field, 'LIKE', '%' . $search . '%');
				}
				$query->orWhereRaw('(CASE tbl_today_details.position WHEN "1" THEN "Left" WHEN "2" THEN "Right" ELSE "" END) LIKE "%' . $search . '%"');
			});
		}
		$query = $query->orderBy('tbl_today_details.today_id', 'desc');
		$totalRecord = $query->count();
		$arrData = setPaginate1($query, $arrInput['start'], $arrInput['length'], '');
		$arrData['user_binary'] = $array;

		if ($arrData['recordsTotal'] > 0) {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record found', $arrData);
		} else {
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Record not found', $arrData);
		}
	}
}
