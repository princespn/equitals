<?php

namespace App\Console\Commands;

use App\Http\Controllers\userapi\TransactionConfiController;
use App\Models\ProjectSetting as ProjectSettingModel;
use App\Models\TransactionInvoice;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class ConfirmTransactionCron extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'cron:confirm_transaction';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deposit usd to address';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(TransactionConfiController $confirmTxn) {
		parent::__construct();
		$this->confirmTxn = $confirmTxn;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		//echo "hellooo";

		$projectSettings = ProjectSettingModel::where('status', 1)->first();
		if ($projectSettings->confirm_transaction_status == 0) {

			ProjectSettingModel::where('status', 1)->update(array('confirm_transaction_status' => 0));
			$UserInvoice = TransactionInvoice::where([['in_status', '=', 0], ['top_up_status', '=', 0]])->orderBy('entry_time', 'ASC')->get();

			
			// 'address','id','invoice_id','price_in_usd','payment_mode','trans_id','srno'
			if (!empty($UserInvoice)) {
				foreach ($UserInvoice as $k => $v) {
					$address = $v->address;
					$id = $v->id;
					$invoice_id = $v->invoice_id;
					$price_in_usd = $v->price_in_usd;
					$currency_price = $v->currency_price;
					$payment_mode = $v->payment_mode;
					$payment_by = $v->product_url;
					$trans_id = $v->trans_id;
					$srno = $UserInvoice[$k]->srno;
					$req1 = new request();
					if ($trans_id) {
						$req1['txid'] = $trans_id;
						$req1['id'] = $id;
						$req1['srno'] = $srno;
						$req1['invoice_id'] = $invoice_id;
						$req1['price_in_usd'] = $price_in_usd;
						$req1['currency_price'] = $currency_price;
						$req1['payment_mode'] = $payment_mode;
						$req1['payment_by'] = $payment_by;
						$this->confirmTxn->confirmTransaction($req1);
					}
				}
			
				ProjectSettingModel::where('status', 1)->update(array('confirm_transaction_status' => 0));

				echo "run successfully ";
				$this->info('Usd Deposit to address is done');
			} else {

				$this->info('Something went wrong');
			}
		} else {

			echo " already running ";

		}

	}

}
