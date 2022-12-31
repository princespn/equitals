<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use App\Models\Topup;
use App\Models\FakeTopup;
use App\Models\AllTransaction;
use App\Models\Dashboard;
use App\Models\TransactionInvoice;
use App\Models\UserSettingFund;
use App\Models\WalletTransactionLog;
use App\User;

class SecurityCrossVerifyTopup extends Command {
	/**
	 * The name and signature of the console command.  Hacking
	 *
	 * @var string
	 */
	protected $signature = 'cron:cross_verify_topup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Cross verify Top Up ';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {

		$Topup=Topup::select('id','pin','amount','srno','top_up_by','topupfrom')->where('cross_verify_status','=',0)->where('top_up_by','!=',1)->get();
		$userArr = $topArr = $fakeArr = array();

		foreach ($Topup as $top) {
			$topupfrom = $top->topupfrom;

			$totalTopup=Topup::selectRaw("SUM(fund_wallet) as fundTopup, SUM(setting_wallet) as settingTopup, SUM(amount) as totalTopup")->where('top_up_by',$top->top_up_by)->first();

			$totalDeposite = TransactionInvoice::where('id',$top->top_up_by)->where('in_status',1)->sum('price_in_usd');

			$totalFundAdded = WalletTransactionLog::where('from_user_id',1)->where('to_user_id',$top->top_up_by)->where('transaction_type',1)->sum('amount');

			$totalFund = $totalDeposite + $totalFundAdded;

			$totalSettingFund = UserSettingFund::where('user_id',$top->top_up_by)->sum('amount');
			
			if(strpos($topupfrom, '+')){
				if ($totalTopup->fundTopup > $totalFund || $totalTopup->settingTopup > $totalSettingFund) {

					if ($totalTopup->fundTopup > $totalFund) {
						$remark = "Mismatch fund wallet amount";
					}elseif ($totalTopup->settingTopup > $totalSettingFund) {
						$remark = "Mismatch setting fund wallet amount";						
					}
					$user = array();
					$user['block_remark'] = "Invalid Topup";
					$user['id']= $top->id;

					array_push($userArr,$user);

					$fakeTopup = array();
					$fakeTopup['id']= $top->id;
					$fakeTopup['top_up_by']= $top->top_up_by;
					$fakeTopup['amount']= $top->amount;
					$fakeTopup['pin']= $top->pin;
					$fakeTopup['remark']= $remark;
					$fakeTopup['entry_time']= \Carbon\Carbon::now()->toDateTimeString();

					array_push($fakeArr,$fakeTopup);

					$tp = array();
					$tp['pin'] = $top->pin;
					$tp['cross_verify_status'] = 2;

					array_push($topArr,$tp);

				}elseif ($totalTopup->fundTopup <= $totalFund && $totalTopup->settingTopup <= $totalSettingFund) {
					$tp = array();
					$tp['pin'] = $top->pin;
					$tp['cross_verify_status'] = 1;

					array_push($topArr,$tp);
				}
			}else if ($totalTopup->fundTopup > $totalFund ||  $totalFund < 1) {

					$user = array();
					$user['block_remark'] = "Invalid Topup";
					$user['id']= $top->id;

					array_push($userArr,$user);

				$fakeTopup = array();
				$fakeTopup['id']= $top->id;
				$fakeTopup['top_up_by']= $top->top_up_by;
				$fakeTopup['amount']= $top->amount;
				$fakeTopup['pin']= $top->pin;
				$fakeTopup['remark']= "Mismatch fund wallet amount";;
				$fakeTopup['entry_time']= \Carbon\Carbon::now()->toDateTimeString();

				array_push($fakeArr,$fakeTopup);
				$tp = array();
				$tp['pin'] = $top->pin;
				$tp['cross_verify_status'] = 2;

				array_push($topArr,$tp);
			}elseif ($totalTopup->fundTopup <= $totalFund) {
				$tp = array();
				$tp['pin'] = $top->pin;
				$tp['cross_verify_status'] = 1;
				
				array_push($topArr,$tp);
			}

		}
		//	dd($fakeArr,$topArr,$userArr);

		$count = 1;
    $array = array_chunk($fakeArr,1000);
    while($count <= count($array))
    {
      $key = $count-1;
      FakeTopup::insert($array[$key]);
      echo $count." insert count array ".count($array[$key])."\n";
      $count ++;
    }

      	
    $tpCount = 1;
		$topups = array_chunk($topArr,1000);

		while($tpCount <= count($topups))
		{
			$keyx = $tpCount-1;
			$arrProcess = $topups[$keyx];
			$pin = "'".implode("','", array_column($arrProcess, 'pin'))."'";

			$verify_qry = 'cross_verify_status = (CASE pin';

			foreach ($arrProcess as $key => $val){
				$verify_qry = $verify_qry . " WHEN '".$val['pin']."' THEN ".$val['cross_verify_status'];
			}
			$verify_qry = $verify_qry . " END)"; 
			$updt_qry = "UPDATE tbl_topup SET ".$verify_qry." WHERE pin IN (".$pin.")";
			$updt_user = DB::statement(DB::raw($updt_qry));
			echo $tpCount." update topup status array ".count($arrProcess)."\n";
			$tpCount ++;
		}

    $userCount = 1;
		$userchunk = array_chunk($userArr,1000);

		while($userCount <= count($userchunk))
		{
			$keyu = $userCount-1;
			$arrProcess = $userchunk[$keyu];
			$ids = "'".implode("','", array_column($arrProcess, 'id'))."'";

			$block_qry = 'block_remark = (CASE id';

			foreach ($arrProcess as $key => $val){
				$block_qry = $block_qry . " WHEN '".$val['id']."' THEN '".$val['block_remark']."' ";
			}
			$block_qry = $block_qry . " END)"; 
			$updt_qry = "UPDATE tbl_users SET status='Inactive',".$block_qry." WHERE id IN (".$ids.")";
			$updt_user = DB::statement(DB::raw($updt_qry));
			echo $userCount." update topup status array ".count($arrProcess)."\n";
			$userCount ++;
		}
		echo "successfully  run \n";
	}
}