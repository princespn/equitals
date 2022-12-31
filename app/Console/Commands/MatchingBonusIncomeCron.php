<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\userapi\GenerateRoiController;
use App\User;
use App\Models\UserInfo;
use App\Models\DailyBonus;
use App\Models\MatchingBonusGenerate;
use App\Models\Activitynotification;
use App\Models\AllTransaction;
use App\Models\Dashboard;
use App\Models\Topup;
use App\Models\AchievedUserMatchingBonus;
use DB;
use App\Models\MatchingBonusIncomeSettings;

class MatchingBonusIncomeCron extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:matching_bonus';
    //protected $hidden = true;


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Matching Bonus';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(/*GenerateRoiController $generatesuppermatchingincome*/) {
        parent::__construct();
        /*$this->generatesuppermatchingincome = $generatesuppermatchingincome;*/
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        //echo "hellooo";

        //echo "hellooo";
        $day = \Carbon\Carbon::now()->format('D');
        /*/dd($day);*/DB::enableQueryLog();

        $user = UserInfo::select('tbl_users.id','tbl_users.mobile','tbl_users.country','tbl_users.user_id','tbl_users.email','tbl_users.entry_time as register_date','tbl_achieved_user_matching_bonus.perform_id','tbl_achieved_user_matching_bonus.bonus_name','tbl_achieved_user_matching_bonus.entry_time')
        ->join('tbl_achieved_user_matching_bonus', 'tbl_achieved_user_matching_bonus.user_id', '=', 'tbl_users.id')
        /*->join('tbl_topup as tt', 'tt.id','=','tbl_users.id')*/
        ->where([['tbl_users.status', '=', 'Active'],['tbl_users.type', '=', '']])
        ->where('tbl_achieved_user_matching_bonus.bonus_name','RANK 1')
        ->get();
     
        $today = \Carbon\Carbon::now();
        $today_datetime = $today->toDateTimeString();
       echo $today_datetime2 = $today->toDateString();

        /*$payoutHistory =MatchingBonusGenerate::select('entry_time')->whereDate('entry_time', '=', $today_datetime2)->count();

        $msg = 'Matching Bonus CRON started at' . $today_datetime . "\n" .
        "Total records:: " . $payoutHistory . "\n";*/

        if (!empty($user)) {
            foreach ($user as $key => $value) {
                $id = $user[$key]->id;
                $pin = $user[$key]->perform_id;
                $type = $user[$key]->type;
                $entry_time = $user[$key]->entry_time;
                $email = $user[$key]->email;
                $user_id = $user[$key]->user_id;
                $country = $user[$key]->country;
                $mobile = $user[$key]->mobile;
                $rank = $user[$key]->bonus_name; 
                /*$register_date = UserInfo::select('entry_time')->where('id',$id)->pluck('entry_time')->first();
                $datex = \Carbon\Carbon::parse($register_date);
                $nowx = \Carbon\Carbon::now();
                $diffx = $datex->diffInDays($nowx);*/
             
                echo "For User Id ".$id." pin ".$pin." \n";
                
                $lastDateExist=MatchingBonusGenerate::select('entry_time','bonus_name','count_week')->where([['user_id','=',$id]])->orderBy('entry_time','desc')->first(); 

                if(!empty($lastDateExist)){
                    // A
                  $entry_time=$lastDateExist->entry_time;
                  $count_week = ($lastDateExist->count_week + 1);

                  $nextEntrydate=date('Y-m-d', strtotime($entry_time. ' + 7 days'));

                  if(strtotime($nextEntrydate)<= strtotime($today))   
                  { 
                    $packageExist1=MatchingBonusIncomeSettings::where([['bonus_name','=',$lastDateExist->bonus_name]])->limit(1)->first();
                    $bonus_percentage = $packageExist1->amount;
                    $entry_in_matching = AchievedUserMatchingBonus::select('bonus_name','perform_id','entry_time')
                    ->where([['user_id','=',$id],['bonus_name','=',$lastDateExist->bonus_name]])
                    ->first(); 


                    $pin = $entry_in_matching->perform_id;
                    $qualify_date_time = $entry_in_matching->entry_time;
                    $rank = $lastDateExist->bonus_name;
                    $entry_in_matching1 = AchievedUserMatchingBonus::select('bonus_name','perform_id','entry_time','amount')
                    ->where([['user_id','=',$id],['entry_time','>',$qualify_date_time]])
                    ->get(); 
                    $next_matching = array();
                    $incomeEntryTime = null;
                    foreach ($entry_in_matching1 as $key => $val) {
                        if (count($next_matching) < 1) {
                            $next_matching[] = $val;
                        }
                        if (!isset($incomeEntryTime)) {
                            $incomeEntryTime = $val->entry_time;
                        }elseif (date('Y-m-d',strtotime($incomeEntryTime)) == date('Y-m-d',strtotime($val->entry_time))) {
                            $next_matching[] = $val;
                        }
                        $incomeEntryTime = $val->entry_time;
                        $pinMatching1  = $val->perform_id;
                    }
                    /*dd($next_matching);*/
                    /*$countMatching1 = MatchingBonusGenerate::where('ach_perf_bonus_id','=',$pinMatching1)->where('user_id','=',$id)->count('ach_perf_bonus_id');*/
                   
                    if(count($next_matching) > 0){
                        $countLastRank = MatchingBonusGenerate::where('ach_perf_bonus_id','=',$pin)->where('user_id','=',$id)->count('ach_perf_bonus_id');
                        foreach ($next_matching as $key => $value) {
                            if(date('Y-m-d', strtotime($next_matching[$key]->entry_time. ' + 15 days'))  <= $nextEntrydate || ($countLastRank >= 12 && date('Y-m-d', strtotime($next_matching[$key]->entry_time. ' + 15 days'))  <= $today_datetime2)){
                        
                                $pin = $next_matching[$key]->perform_id;
                                $rank = $next_matching[$key]->bonus_name;
                                $bonus_percentage = $next_matching[$key]->amount;
                                if($countLastRank >= 12 && date('Y-m-d', strtotime($next_matching[$key]->entry_time. ' + 15 days'))  <= $today_datetime2){
                                    $nextEntrydate = date('Y-m-d', strtotime($next_matching[$key]->entry_time. ' + 15 days'));
                                }
                            }
                        }
                    }/*dd($pin,$rank,$bonus_percentage,$nextEntrydate);*/

                    $duration=MatchingBonusIncomeSettings::where('id','=',$pin)->limit(1)->pluck('duration')->first(); 
                    $countRank = MatchingBonusGenerate::where('ach_perf_bonus_id','=',$pin)->where('user_id','=',$id)->count('ach_perf_bonus_id');
                
                    if ($countRank < $duration) {
                        $supermatching = $bonus_percentage;
                        echo "For User Id ".$id." count ".$countRank." duration ".$duration." pin ".$pin." \n";

                        $check_if_ref_exist = Topup::select('id')->where('roi_status','Active')->where('id',$id)->count('id');

                        $remark = "";
                        $laps_amount = 0;
                        if($check_if_ref_exist == 0)
                        {
        
                           $laps_amount =   $supermatching;
                           $supermatching      =  0;
                           $remark = "Not having active topup";
                        }

                        $finalarr=array(); 
                        $Dailydata = array();
                        $Dailydata['amount'] = $supermatching;
                        $Dailydata['user_id'] = $id;
                        $Dailydata['ach_perf_bonus_id'] = $pin;
                        $Dailydata['performance_wallet'] = $supermatching;
                        $Dailydata['count_week'] = $count_week;
                        $Dailydata['laps_amount'] = $laps_amount;
                        $Dailydata['remark'] = $remark;
                        $Dailydata['entry_time'] = $nextEntrydate;
                        $Dailydata['bonus_name'] = $rank;
                        $Dailydata = MatchingBonusGenerate::create($Dailydata);



                        $updateCoinData = array();
                        $updateCoinData['matching_bonus_income'] = DB::raw('matching_bonus_income +'.$supermatching);
                        $updateCoinData['matching_bonus_income_withdraw'] = DB::raw('matching_bonus_income_withdraw + '. $supermatching);
                        $updateCoinData['working_wallet'] = DB::raw('working_wallet + '. $supermatching);
                        
                       
                        $updateCoinData = Dashboard::where('id', $id)->limit(1)->update($updateCoinData);
                    }else
                    { 
                     
                        echo "duration completed \n";
                    }

                }

            }else
            {
               
                // B
                /*  $entry_time=$entry_time;*/

                $entry_time=date('Y-m-d', strtotime($entry_time));
                $count_week = 1;

                $last_entry_in_supermatching = AchievedUserMatchingBonus::select('bonus_name','perform_id')
                ->where([['user_id','=',$id]])
                ->where([[DB::raw("(Date(entry_time))"),'=',$entry_time]])
                ->orderBy('entry_time','desc')
                ->orderBy('id','desc')
                ->first(); 
                /*dd($last_entry_in_supermatching->rank);*/
                $nextEntrydate=date('Y-m-d', strtotime($entry_time. ' + 7 days'));

                if(strtotime($nextEntrydate)<= strtotime($today)){
                    $packageExist=MatchingBonusIncomeSettings::where([['bonus_name','=',$last_entry_in_supermatching->bonus_name]])->limit(1)->first();
                    $supermatching = $packageExist->amount;

                    $check_if_ref_exist = Topup::select('id')->where('roi_status','Active')->where('id',$id)->count('id');

                    $remark = "";
                    $laps_amount = 0;
                    if($check_if_ref_exist == 0)
                    {
    
                       $laps_amount =   $supermatching;
                       $supermatching      =  0;
                       $remark = "Not having active topup";
                    }

                    $finalarr=array(); 
                    $Dailydata = array();
                    $Dailydata['amount'] = $supermatching;
                    $Dailydata['user_id'] = $id;
                    $Dailydata['ach_perf_bonus_id'] = $last_entry_in_supermatching->perform_id;
                    $Dailydata['performance_wallet'] = $supermatching;
                    $Dailydata['count_week'] = $count_week;
                    $Dailydata['laps_amount'] = $laps_amount;
                    $Dailydata['remark'] = $remark;
                    $Dailydata['entry_time'] = $nextEntrydate;
                    $Dailydata['bonus_name'] = $last_entry_in_supermatching->bonus_name;
                    $Dailydata = MatchingBonusGenerate::create($Dailydata);

                    $updateCoinData = array();
                   $updateCoinData['matching_bonus_income'] = DB::raw('matching_bonus_income +'.$supermatching);
                    $updateCoinData['matching_bonus_income_withdraw'] = DB::raw('matching_bonus_income_withdraw + '. $supermatching);
                    $updateCoinData['working_wallet'] = DB::raw('working_wallet + '. $supermatching);
                   

                    $updateCoinData = Dashboard::where('id', $id)->limit(1)->update($updateCoinData);
                }
            }
        }
        $this->info(' Matching Bonus generated successfully');
        } else {

            $this->info('User is not exist');
        }

}

}
