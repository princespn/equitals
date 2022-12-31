<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use App\Models\WithdrawPending;
use App\Models\Topup;
use App\Models\FakeWithdraw;
use App\Models\AllTransaction;
use App\Models\Dashboard;
use App\Models\TransactionInvoice;
use App\Models\UserSettingFund;
use App\Models\WalletTransactionLog;
use App\Models\DailyBonus;
use App\Models\DirectIncome;
use App\Models\PayoutHistory;
use App\Models\MatchingBonusGenerate;
use App\User;

class SecurityCrossVerifyWithdraw extends Command {
	/**
	 * The name and signature of the console command.  Hacking
	 *
	 * @var string
	 */
	protected $signature = 'cron:cross_verify_withdraw';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Cross verify Withdraw ';

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

		$allWithdraw = WithdrawPending::select('id','sr_no','amount as net_amount','withdraw_type','deduction',DB::raw('amount+deduction as amount'))->where('cross_verify_status','=',0)->where('status','=',0)->get();
		$userArr = $withdrawArr = $fakeArr = array();

		foreach ($allWithdraw as $withdraw) {

			$totalWithdraw=WithdrawPending::where('withdraw_type',$withdraw->withdraw_type)->where('id',$withdraw->id)->whereIn('status',[1,0])->sum(DB::raw("amount + deduction"));

			if ($withdraw->withdraw_type == 3) {
				$totalRoi = DailyBonus::where('id',$withdraw->id)->sum('amount');
				if ($totalWithdraw > $totalRoi) {
					$remark = "Mismatch total ROI income";						
					
					$user = array();
					$user['block_remark'] = "Invalid ROI Withdrawal";
					$user['id']= $withdraw->id;

					array_push($userArr,$user);

					$fakeTopup = array();
					$fakeTopup['id']= $withdraw->id;
					$fakeTopup['withdraw_type']= $withdraw->withdraw_type;
					$fakeTopup['amount']= $withdraw->amount;
					$fakeTopup['withdraw_id']= $withdraw->sr_no;
					$fakeTopup['remark']= $remark;
					$fakeTopup['entry_time']= \Carbon\Carbon::now()->toDateTimeString();

					array_push($fakeArr,$fakeTopup);

					$tp = array();
					$tp['sr_no'] = $withdraw->sr_no;
					$tp['cross_verify_status'] = 2;

					array_push($withdrawArr,$tp);
				}else{
					$tp = array();
					$tp['sr_no'] = $withdraw->sr_no;
					$tp['cross_verify_status'] = 1;
					
					array_push($withdrawArr,$tp);
				}
			}elseif ($withdraw->withdraw_type == 2) {
				$totalBinary = PayoutHistory::where('user_id',$withdraw->id)->sum('amount');
				$totalDirect = DirectIncome::where('toUserId',$withdraw->id)->sum('amount');
				$totalBonus = MatchingBonusGenerate::where('user_id',$withdraw->id)->sum('amount');

				$totalWorking = round(($totalBinary + $totalDirect + $totalBonus),2);
				if ($totalWithdraw > $totalWorking) {
					$remark = "Mismatch total working income";						
					
					$user = array();
					$user['block_remark'] = "Invalid Working Withdrawal";
					$user['id']= $withdraw->id;

					array_push($userArr,$user);

					$fakeTopup = array();
					$fakeTopup['id']= $withdraw->id;
					$fakeTopup['withdraw_type']= $withdraw->withdraw_type;
					$fakeTopup['amount']= $withdraw->amount;
					$fakeTopup['withdraw_id']= $withdraw->sr_no;
					$fakeTopup['remark']= $remark;
					$fakeTopup['entry_time']= \Carbon\Carbon::now()->toDateTimeString();

					array_push($fakeArr,$fakeTopup);

					$tp = array();
					$tp['sr_no'] = $withdraw->sr_no;
					$tp['cross_verify_status'] = 2;

					array_push($withdrawArr,$tp);
				}else{
					$tp = array();
					$tp['sr_no'] = $withdraw->sr_no;
					$tp['cross_verify_status'] = 1;
					
					array_push($withdrawArr,$tp);
				}
			}
		}

		$count = 1;
    $array = array_chunk($fakeArr,1000);
    while($count <= count($array))
    {
      $key = $count-1;
      FakeWithdraw::insert($array[$key]);
      echo $count." insert count array ".count($array[$key])."\n";
      $count ++;
    }

  	
    $wCount = 1;
		$withdrawals = array_chunk($withdrawArr,1000);

		while($wCount <= count($withdrawals))
		{
			$keyx = $wCount-1;
			$arrProcess = $withdrawals[$keyx];
			$pin = "'".implode("','", array_column($arrProcess, 'sr_no'))."'";

			$verify_qry = 'cross_verify_status = (CASE sr_no';

			foreach ($arrProcess as $key => $val){
				$verify_qry = $verify_qry . " WHEN '".$val['sr_no']."' THEN ".$val['cross_verify_status'];
			}
			$verify_qry = $verify_qry . " END)"; 
			$updt_qry = "UPDATE tbl_withdrwal_pending SET ".$verify_qry." WHERE sr_no IN (".$pin.")";
			$updt_user = DB::statement(DB::raw($updt_qry));
			echo $wCount." update withdraw status array ".count($arrProcess)."\n";
			$wCount ++;
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