<?php
namespace App\Console\Commands;

use App\Models\BinaryIncome;
use App\Policies\BinaryIncomeClass;
use App\User;
use Illuminate\Console\Command;

class DailyBinaryIncome extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'daily:binary_income';
	//  protected $hidden = true;

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(BinaryIncomeClass $objBinaryIncome) {
		parent::__construct();
		$this->objBinaryIncome = $objBinaryIncome;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {

		/* $day = \Carbon\Carbon::now()->format('D');

			            if($day == 'Sat' || $day == 'Sun'){

			             return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Withdraw is not allowed  on Weekends', '');
		*/

		/*	$isExistUser = User::select("id", "user_id As name")->where('l_bv', '>', 0)->where('r_bv', '>', 0)
			->take(3)->get();*/

		$isExistUser = User::select("id", "user_id As name")->where('l_bv', '>', 0)->where('r_bv', '>', 0)
			->get();

		//	dd($isExistUser);

		$today = \Carbon\Carbon::now();
		$today_datetime = $today->toDateTimeString();
		$today_datetime2 = $today->toDateString();

		$payoutHistory = BinaryIncome::select('entry_time')->whereDate('entry_time', '=', $today_datetime2)->count();

		$msg = 'Binary CRON started at' . $today_datetime . "\n" .
			"Total records:: " . $payoutHistory . "\n";

		//$ss = sendSms('9860163716', $msg);
		/*$ss = sendSms('8669605501', $msg);
		$ss = sendSms('9404737203', $msg);
		$ss = sendSms('9403832061', $msg);*/

		if (!is_null($isExistUser)) {
			foreach ($isExistUser as $user) {
				$user_id = $user->id;
				// $user_id = 16635;
				//  $isUsrQualifed = $this->objBinaryIncome->chkQualifiedBinaryIncome($user_id);
				//  if($isUsrQualifed !== false ) {
				$insBinaryIncome = $this->objBinaryIncome->insertBinaryIncome($user_id);
				//  }
			} // foreach
		} // isExist

		$today = \Carbon\Carbon::now();
		$today_datetime = $today->toDateTimeString();

		$today_datetime2 = $today->toDateString();

		$payoutHistory = BinaryIncome::select('entry_time')->whereDate('entry_time', '=', $today_datetime2)->count();

		$msg2 = 'Binary CRON end at' . $today_datetime . "\n" .
			"Total records:: " . $payoutHistory . "\n";

		//$ss = sendSms('9860163716', $msg2);
		/*$ss = sendSms('8669605501', $msg2);
		$ss = sendSms('9404737203', $msg2);
		$ss = sendSms('9403832061', $msg2);*/

	}
}
