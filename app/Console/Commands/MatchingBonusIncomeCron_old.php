<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Controllers\userapi\GenerateRoiController;
// use App\Models\DailyBonus;
// use App\Models\Topup;
use App\Models\MatchingBonusIncomeSettings;
use App\Models\AchievedUserMatchingBonus;
use App\Models\MatchingBonusGenerate;
use App\Models\UserInfo;
use DB;



class MatchingBonusIncomeCron_old extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:matching_bonus_old';
    //protected $hidden = true;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Performance bonus';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(/* GenerateRoiController $generateRoi */)
    {
        parent::__construct();
        // $this->generateRoi = $generateRoi;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        list($usec, $sec) = explode(" ", microtime());

        $time_start1 = ((float)$usec + (float)$sec);
        // dd('end');
        DB::raw('LOCK TABLES `tbl_dashboard` WRITE');
        DB::raw('LOCK TABLES `tbl_achieved_user_perform_bonus` WRITE');
        //cron here
        $current_time = \Carbon\Carbon::now()->toDateTimeString();
        $msg = "Performance Bonus Cron started at " . $current_time;
        echo $msg . "\n";


        $get_records = AchievedUserMatchingBonus::select('id', 'user_id','bonus_name', 'amount', 'perform_id', 'bonus_receiv_count', 'entry_time')/*->where('status', '=', 0)*/->get();

        $updateAchieved = array();
        $updateDash = array();
        $insert = array();
        $gaming_total = array();
      
        // dd($insert);
        foreach ($get_records as $list) {
            $userId = $list->user_id;            
            $packageExist = MatchingBonusIncomeSettings::where('id', $list->perform_id)->limit(1)->first();

            if (empty($packageExist)) {
                echo "\n Invalid Package for user id : " . $userId . " \n";
                continue;
            }
            $entry_time = $list->entry_time;
            $lastDateExist = MatchingBonusGenerate::select('entry_time','count_week')->where('user_id', $userId)->orderBy('id', 'desc')->first();
            
            if (!empty($lastDateExist)) {
                $entry_time = $lastDateExist->entry_time;
                $count_week = ($lastDateExist->count_week + 1);
            } else {

                $count_week = 1;
                // $entry_time = \Carbon\Carbon::now();
                // $nextEntrydate = \Carbon\Carbon::now()->format('Y-m-d');
            }

            $date = \Carbon\Carbon::parse($entry_time);
            $now = \Carbon\Carbon::now();
            $diff = $date->diffInDays($now);
            $getDate = $now->toDateString();

            $nextEntrydate = date('Y-m-d', strtotime($entry_time . ' + ' . $packageExist->date_diff . ' days'));

            $DailyBounsExist = MatchingBonusGenerate::select('entry_time')->where('user_id','=',$userId)->where(DB::raw("(DATE_FORMAT(entry_time,'%Y-%m-%d'))"), '=', $nextEntrydate)->first();
            
            if(!empty($DailyBounsExist))
            {
                $nextEntrydate=date('Y-m-d', strtotime($entry_time. ' + 7 days'));

                  if(strtotime($nextEntrydate)<= strtotime($today))   
                  {
                    $packageExist1=AchievedUserMatchingBonus::where([['perform_id','=',$DailyBounsExist->ach_perf_bonus_id]])->limit(1)->first();
                    $bonus_percentage = $packageExist1->bonus_percentage;
                    $entry_in_supermatching = AchievedUserMatchingBonus::select('id','user_id','bonus_name','perform_id','amount','entry_time')
                    ->where([['user_id','=',$userId],['perform_id','=',$DailyBounsExist->ach_perf_bonus_id]])
                    ->first(); 
                   /* $pin = $entry_in_supermatching->perform_id;*/
                    $qualify_date_time = $entry_in_supermatching->entry_time;
                    $bonus_name = $DailyBounsExist->bonus_name;

                    $entry_in_supermatching1 = AchievedUserMatchingBonus::select('id','user_id','bonus_name','perform_id','amount','entry_time')
                    ->where([['user_id','=',$userId],['entry_time','>',$qualify_date_time]])
                    ->get(); 

                    if(count($entry_in_supermatching1) > 0){
                        foreach ($entry_in_supermatching1 as $key => $value) {
                            if(date('Y-m-d', strtotime($entry_in_supermatching1[$key]->entry_time. ' + 15 days'))  <= $nextEntrydate){

                               /* $pin = $entry_in_supermatching1[$key]->pin;*/
                                $bonus_name = $entry_in_supermatching1[$key]->bonus_name;
                                $perform_id = $entry_in_supermatching1[$key]->perform_id;

                                 $packageExist1 = MatchingBonusIncomeSettings::where('id', $list->perform_id)->limit(1)->first();

                                $bonus_percentage = $packageExist1->amount;

                            }
                        }
                    }

                $amount = $list->amount;
                $performanceWallet = $amount;
               
                $insert[] = array(
                    'user_id' => $userId, 
                    'ach_perf_bonus_id' => $list->id, 
                    'bonus_name' => $list->bonus_name, 
                    'amount' => $amount, 
                    'performance_wallet' => $performanceWallet, 
                    'count_week' => $count_week, 
                    'entry_time' => $nextEntrydate
                );
                $updateAchiPerfor_id = array();
                $updateAchiPerfor_id['ach_perf_bonus_id'] = $list->id;
                array_push($updateAchieved, $updateAchiPerfor_id);


                $updateDashboard = array();
                $updateDashboard['id'] = $userId;
                $updateDashboard['matching_bonus_income'] = $performanceWallet;
                $updateDashboard['matching_bonus_income_withdraw'] = $performanceWallet;
                $updateDashboard['working_wallet'] = $performanceWallet;
                array_push($updateDash, $updateDashboard);

                }    
            }elseif (empty($DailyBounsExist) && $diff >= $packageExist->date_diff && strtotime($nextEntrydate) <= strtotime($getDate)) {

                $amount = $list->amount;
                $performanceWallet = $amount;
               
                $insert[] = array(
                    'user_id' => $userId, 
                    'ach_perf_bonus_id' => $list->id, 
                    'bonus_name' => $list->bonus_name, 
                    'amount' => $amount, 
                    'performance_wallet' => $performanceWallet, 
                    'count_week' => $count_week, 
                    'entry_time' => $nextEntrydate
                );
                $updateAchiPerfor_id = array();
                $updateAchiPerfor_id['ach_perf_bonus_id'] = $list->id;
                array_push($updateAchieved, $updateAchiPerfor_id);


                $updateDashboard = array();
                $updateDashboard['id'] = $userId;
                $updateDashboard['matching_bonus_income'] = $performanceWallet;
                $updateDashboard['matching_bonus_income_withdraw'] = $performanceWallet;
                $updateDashboard['working_wallet'] = $performanceWallet;
                array_push($updateDash, $updateDashboard);
            }
        }
        if (isset($insert) && !empty($insert)) {
            MatchingBonusGenerate::insert($insert);
        }

        // update dashboard
        $dashCount = 1;
        $dasharray = array_chunk($updateDash, 1000);

        while ($dashCount <= count($dasharray)) {
            $dashk = $dashCount - 1;
            $arrProcess = $dasharray[$dashk];
            $mainArr = array();
            foreach ($arrProcess as $k => $v) {
                $mainArr[$v['id']]['id'] = $v['id'];
                if (!isset($mainArr[$v['id']]['matching_bonus_income']) && !isset($mainArr[$v['id']]['matching_bonus_income_withdraw']) && !isset($mainArr[$v['id']]['working_wallet'])) {
                   
                    $mainArr[$v['id']]['matching_bonus_income'] = 0;
                    $mainArr[$v['id']]['matching_bonus_income_withdraw'] = 0;
                    $mainArr[$v['id']]['working_wallet'] = 0;
                }
               
                $mainArr[$v['id']]['matching_bonus_income'] += $v['matching_bonus_income'];
                $mainArr[$v['id']]['matching_bonus_income_withdraw'] += $v['matching_bonus_income_withdraw'];
                $mainArr[$v['id']]['working_wallet'] += $v['working_wallet'];
            }

            $ids = implode(',', array_column($mainArr, 'id'));

           
            $matching_bonus_income_qry = 'matching_bonus_income = (CASE id';
            $matching_bonus_income_withdraw_qry = 'matching_bonus_income_withdraw = (CASE id';
            $working_wallet_qry = 'working_wallet = (CASE id';

            foreach ($mainArr as $key => $val) {
               
                $matching_bonus_income_qry = $matching_bonus_income_qry . " WHEN " . $val['id'] . " THEN matching_bonus_income + " . $val['matching_bonus_income'];
                $matching_bonus_income_withdraw_qry = $matching_bonus_income_withdraw_qry . " WHEN " . $val['id'] . " THEN matching_bonus_income_withdraw + " . $val['matching_bonus_income_withdraw'];
                $working_wallet_qry = $working_wallet_qry . " WHEN " . $val['id'] . " THEN working_wallet + " . $val['working_wallet'];
            }

            $matching_bonus_income_qry = $matching_bonus_income_qry . " END)";
            $matching_bonus_income_withdraw_qry = $matching_bonus_income_withdraw_qry . " END)";
            $working_wallet_qry = $working_wallet_qry . " END)";

            echo  $updt_qry = "UPDATE tbl_dashboard SET  " . $matching_bonus_income_qry ." , " . $matching_bonus_income_withdraw_qry ." , " . $working_wallet_qry . "  WHERE id IN (" . $ids . ")";
          
            $updt_user = DB::statement(DB::raw($updt_qry));

            echo "\n" . $dashCount . " update from user dash array " . count($mainArr) . "\n";
            $dashCount++;
        }

        // Update Count in AchievedUserMatchingBonus tbl
        $updateRecord = array();
        $updateRecord['bonus_receiv_count'] = DB::raw('bonus_receiv_count + 1');

        $count2 = 1;
        $array2 = array_chunk($updateAchieved, 1000);
        while ($count2 <= count($array2)) {
            $key2 = $count2 - 1;
            AchievedUserMatchingBonus::whereIn('id', $array2[$key2])->update($updateRecord);
            echo $count2 . " update count array " . count($array2[$key2]) . "\n";
            $count2++;
        }

       
        $current_time = \Carbon\Carbon::now()->toDateTimeString();
        // $msg = "Dividend Income Cron end at ".$current_time."\nTotal records : ".count($update_dashboard)."\n";
        $msg = "Performance Bonus Cron end at " . $current_time;

        echo $msg;

        echo "\n";
        list($usec, $sec) = explode(" ", microtime());
        $time_end1 = ((float)$usec + (float)$sec);
        $time = $time_end1 - $time_start1;
        echo "after Performance Bonus Cron cron " . $time . "\n";


        DB::raw('UNLOCK TABLES');
    }
}
