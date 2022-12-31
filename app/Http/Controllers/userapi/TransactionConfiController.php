<?php

namespace App\Http\Controllers\userapi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\userapi\BlocktransactionController;
use App\Models\Dashboard;
use App\Models\Invoice;
use App\Models\TransactionInvoice;
use Config;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;

class TransactionConfiController extends Controller {

	public function __construct(BlocktransactionController $blockio) {

		$this->statuscode = Config::get('constants.statuscode');
		$this->blockio = $blockio;
		$date = \Carbon\Carbon::now();
		$this->today = $date->toDateTimeString();
	}

	/**
	 *  BTC Transaction confirmation on address
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function transactionconfirmation(Request $request) {
		try {
			$rules = array(
				'address' => 'required|',
			);

			//  echo $request->input('address');
			echo "\n";

			$validator = checkvalidation($request->all(), $rules, '');
			if (!empty($validator)) {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $validator, '');
			}

			//--------------Check adress exist with received-----------------------
			$UserTreceived = Invoice::where([['address', '=', trim($request->input('address'))], ['payment_mode', '=', 'BTC']])->first(); // get address from deposit mst

			if (!empty($UserTreceived)) {
				//===============using blockio=======================================

				$AddTrecived = blockio_address(trim($request->input('address')));

				// echo "blockio";

				echo "\n";

				// print_r($AddTrecived);
				echo "\n";

				if (!empty($AddTrecived) && $AddTrecived['msg'] == 'success' && !empty($AddTrecived['data']['txs'])) {

					$txsArr = $AddTrecived['data']['txs'];

					if (!empty($txsArr)) {

						$retndata = $this->blockio->blockio_transaction($txsArr, $request->input('address'), $UserTreceived->id, $UserTreceived->price_in_usd, $UserTreceived->rec_amt);

						// return response()->json($retndata->original);
					}
				} else if (1) {
					//===============using blockchain=======================================
					$chainTrecived = blockchain_address(trim($request->input('address')));

					//  echo "blockchain";

					echo "\n";

					echo "\n";

					// dd($chainTrecived);
					// $chainTrecived['msg'] = 'failed';

					if ($chainTrecived['msg'] == 'success' && !empty($chainTrecived['data']['hash160'])) {

						$hash160 = $chainTrecived['data']['hash160']; // check hash is not empty
						if (!empty($hash160)) {

							$chaintxsArr = $chainTrecived['data']['txs'];

							// dd($chaintxsArr);

							if (!empty($chaintxsArr)) {

								$retndata = $this->blockio->blockchain_transaction($chaintxsArr, trim($request->input('address')), $UserTreceived->id, $UserTreceived->price_in_usd, $UserTreceived->rec_amt);

								//  return response()->json($retndata->original);
							}
						} // hash is not empty
						// total received is less
					} else if (1) {
						//===============using blockcyper=======================================
						$cyperTrecived = blockcyper_address(trim($request->input('address')));

						//  echo "blockcyper";

						echo "\n";
						// print_r($cyperTrecived);
						echo "\n";

						//  dd($cyperTrecived);

						//echo($request->input('address'));
						if ($cyperTrecived['msg'] == 'success' && !empty($cyperTrecived['data']['txrefs'])) {
							$txrefs = $cyperTrecived['data']['txrefs'];
							if (!empty($txrefs)) {

								$cyperndata = $this->blockio->blockcyper_transaction($txrefs, $request->input('address'), $UserTreceived->id, $UserTreceived->price_in_usd, $UserTreceived->rec_amt);
							}
						} else if (1) {
							//===============using blockbitaps===
							$bitapsrecived = blockbitaps_address(trim($request->input('address')));

							//    echo "blockbitaps";
							//  print_r($bitapsrecived);
							echo "\n";
							//  dd($bitapsrecived);
							echo "\n";
							// dd($bitapsrecived);
							if ($bitapsrecived['msg'] == 'success' && !empty($bitapsrecived['data'])) {

								$bitapsdata = $this->blockio->blockbitaps_transaction($bitapsrecived['data'], $request->input('address'), $UserTreceived->id, $UserTreceived->price_in_usd, $UserTreceived->rec_amt);
							}
						}
						//===========================================================================
					}
				}
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
	 *  BTC Transaction confirmation on address
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function confirmTransaction(Request $request) {
		try {
			$arr = $request->all();

			if($arr['payment_by'] == "coinpayment"){
				$txn=array();
				$txn['txid'] = $arr['txid'];
				$trans_data = get_trans_status('get_tx_info', $txn);

				$trans_status = $trans_data['data'];
				if ($trans_status['error'] == "ok") {
					if ($trans_status['result']['status'] == 1 || $trans_status['result']['status'] == 100) {
						/*$topup = $this->topupcontroller->selectpackages($arr['invoice_id'], $arr['price_in_usd'], $arr['id'],"coinpay");*/

						$check = TransactionInvoice::where('srno', '=', $arr['srno'])
							->where('in_status', '=', 0)
							->where('top_up_status', '=', 0)
							->get();

						if (count($check) == 1) {

							$dash['fund_wallet'] = DB::RAW('fund_wallet + ' . $arr['price_in_usd']);
							Dashboard::where('id', $arr['id'])->update($dash);

							$updateOtpSta = TransactionInvoice::where([['srno', '=', $arr['srno']]])->update(array('in_status' => 1, 'top_up_status' => 1, 'top_up_date' => $this->today));
						}

					} elseif ($trans_status['result']['status'] == -1) {

						$updateOtpSta = TransactionInvoice::where([['srno', '=', $arr['srno']]])->update(array('in_status' => 2));
					}
				}
			}elseif($arr['payment_by'] == "node_api"){
				$txn=array();
				$txn['id'] = $arr['txid'];

				$trans_data = get_node_trans_status('publicInvoiceStatus', $txn);
				
				$trans_status = $trans_data['data'];
				
				if ($trans_status['status'] == "OK") {
					if ($trans_status['data']['paymentStatus'] == 'PAID') /*||$trans_status['data']['paymentStatus'] == 'Pending' || $trans_status['message']=='Transaction already has been done '*/
					{

						$check = TransactionInvoice::where('srno', '=', $arr['srno'])
							->where('in_status', '=', 0)
							->where('top_up_status', '=', 0)
							->get();

						if (count($check) == 1) {

							/*$dash['top_up_wallet'] = DB::RAW('top_up_wallet + ' . $arr['price_in_usd']);
							Dashboard::where('id', $arr['id'])->update($dash);*/

						   $dash['fund_wallet'] = DB::RAW('fund_wallet + ' . $arr['price_in_usd']);
							Dashboard::where('id', $arr['id'])->update($dash);

							$updateOtpSta = TransactionInvoice::where([['srno', '=', $arr['srno']]])->update(array('in_status' => 1, 'top_up_status' => 1, 'top_up_date' => $this->today));
						}

					} elseif ($trans_status['data']['paymentStatus'] == 'EXPIRED' || $trans_status['data']['paymentStatus'] == 'REJECTED') {

						$updateOtpSta = TransactionInvoice::where([['srno', '=', $arr['srno']]])->update(array('in_status' => 2));
					}
				}				
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
	 * ETH Transaction confirmation on address
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function ETHtransaction(Request $request) {

		try {
			$rules = array(
				'address' => 'required|',
			);

			$validator = checkvalidation($request->all(), $rules, '');
			if (!empty($validator)) {

				$arrStatus = Response::HTTP_NOT_FOUND;
				$arrCode = Response::$statusTexts[$arrStatus];
				$arrMessage = $validator;
				return sendResponse($arrStatus, $arrCode, $arrMessage, '');
			}

			//--------------Check adress exist with received-----------------------
			$UserTreceived = Invoice::where([['address', '=', trim($request->input('address'))], ['payment_mode', '=', 'ETH']])->first(); // get address from deposit mst

			if (!empty($UserTreceived)) {
				//===============using blockio=======================================

				$Transaction = ETHConfirmation(trim($request->input('address')));

				if (!empty($Transaction) && $Transaction['msg'] == 'success') {

					$txsArr = $Transaction['data'];

					if (!empty($txsArr)) {

						$retndata = $this->blockio->etherscanio_transaction($txsArr, $request->input('address'), $UserTreceived->id, $UserTreceived->price_in_usd, $UserTreceived->rec_amt);
					}
				}
			}

		} catch (Exception $e) {

			$arrStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
			$arrCode = Response::$statusTexts[$arrStatus];
			$arrMessage = 'Something went wrong,Please try again';
			return sendResponse($arrStatus, $arrCode, $arrMessage, '');
		}
	}

}
