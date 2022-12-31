<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\userapi\GenerateRoiController;
use App\User;
use App\Models\DailyBonus;
use App\Models\Activitynotification;
use App\Models\AllTransaction;
use App\Models\Dashboard;
use App\Models\Rank;



class ThreeHoursGenerateROI extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:roi';
    //protected $hidden = true;


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Every three hours roi generation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(GenerateRoiController $generateRoi) {
        parent::__construct();
        $this->generateRoi = $generateRoi;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        //echo "hellooo";

        //echo "hellooo";
        dd("This is old cron please use cron:optimized_roi_static cron");
        $day = \Carbon\Carbon::now()->format('D');
        //dd($day);
         if($day == 'Sun' || $day == 'Sat'){
            // dd('In');
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'ROI is not allowed on this day', ''); 
        }

        $user = User::join('tbl_topup', 'tbl_topup.id', '=', 'tbl_users.id')
                ->select('tbl_topup.amount','tbl_users.rank','tbl_users.id','tbl_users.mobile','tbl_users.country','tbl_users.user_id','tbl_users.email','tbl_topup.pin','tbl_topup.type','tbl_topup.entry_time','tbl_topup.withdraw','tbl_topup.old_status')->where([['tbl_users.status', '=', 'Active'],['tbl_users.type', '=', ''],['tbl_topup.roi_status', '=', 'Active']])
             /*   ->where('tbl_topup.pin', '152134637292858')*/
              //  ->skip(500)->take(500)
               // ->limit(1)
                ->get();

             
         $today = \Carbon\Carbon::now();
        $today_datetime = $today->toDateTimeString();
        $today_datetime2 = $today->toDateString();

        $payoutHistory =DailyBonus::select('entry_time')->whereDate('entry_time', '=', $today_datetime2)->count();

        $msg = 'ROI CRON started at' . $today_datetime . "\n" .
            "Total records:: " . $payoutHistory . "\n";

        //$ss = sendSms('9860163716', $msg);
       /*$ss = sendSms('8669605501', $msg);
      $ss = sendSms('9404737203', $msg);*/
     //  $ss = sendSms('9403832061', $msg);


        $dashArr=array();
        $traArr=array();
        $actArr=array();
        $roiArr= array();
        $finalArr= array();
        if (!empty($user)) {
            foreach ($user as $key => $value) {
                $invest_amount = $user[$key]->amount;
                $id = $user[$key]->id;
                $pin = $user[$key]->pin;
                $type = $user[$key]->type;
                $entry_time = $user[$key]->entry_time;
                $email = $user[$key]->email;
                $user_id = $user[$key]->user_id;
                $country = $user[$key]->country;
                $mobile = $user[$key]->mobile;
                $withdraw = $user[$key]->withdraw;
                $old_status = $user[$key]->old_status;
                $rank = $user[$key]->rank;
                //except package 1 (trial $100)
                //if($value->type != '1'){
                    //dd($invest_amount);
                    $checkUpdate = $this->generateRoi->generateroi($invest_amount, $id, $pin, $type, $entry_time,$email,$user_id,$country,$mobile,$withdraw,$old_status,$rank);
                     //dd($checkUpdate);
                    if($checkUpdate !=404 ){

                        //$dailydata=$checkUpdate['dailydata']['id'];
                          //dd($checkUpdate['dailydata']);
                         $roiArr[]=$checkUpdate['dailydata']; 
                         $dashArr[]=$checkUpdate['updateCoinData'];  
                          $traArr[]=$checkUpdate['trandata'];  
                        $actArr[]=$checkUpdate['actdata'];    
                    }else{

                       //dd($checkUpdate);
                    }
                       //array_push(array, var)
                          // array_push($dashArr,$checkUpdate['updateCoinData']);  
                          // array_push($traArr,$checkUpdate['Trandata']);  
                           //array_push($actArr,$checkUpdate['actdata']);  
                           
                //}

            }

              $count = 1;
                $array = array_chunk($roiArr,1000);
               /* dd($array);*/
                while($count <= count($array))
                {
                  
                  $key = $count-1;
                  DailyBonus::insert($array[$key]);
                  echo $count." count array ".count($array[$key])."\n";
                  $count ++;
                }
             //----------------------------------
                 $count1 = 1;
                $array_traArr = array_chunk($traArr,1000);
                while($count1 <= count($array_traArr))
                {
                  $key = $count1-1;
                 AllTransaction::insert($array_traArr[$key]);
                  echo $count1." count array ".count($array_traArr[$key])."\n";
                  $count1 ++;
                }
                //----------------------------------
                 $count2 = 1;
                $array_actArr = array_chunk($actArr,1000);
                while($count2 <= count($array_actArr))
                {
                  $key = $count2-1;
                  Activitynotification::insert($array_actArr[$key]);
                  echo $count2." count array ".count($array_actArr[$key])."\n";
                  $count2 ++;
                }

                //-----------------
                  //dd($dashArr);
                  foreach ($dashArr as $value) {
                      # code...
                    //Dashboard::where('id', $value['id'])->limit(1)->update($value);
                  }
               

            $this->info('ROI generated successfully');
            $today = \Carbon\Carbon::now();
        $today_datetime = $today->toDateTimeString();
        $today_datetime2 = $today->toDateString();

        $payoutHistory =DailyBonus::select('entry_time')->whereDate('entry_time', '=', $today_datetime2)->count();

        $msg = 'ROI CRON started at' . $today_datetime . "\n" .
            "Total records:: " . $payoutHistory . "\n";

        //$ss = sendSms('9860163716', $msg);
       /* $ss = sendSms('8669605501', $msg);
        $ss = sendSms('9404737203', $msg);*/
      // $ss = sendSms('9403832061', $msg);

        } else {

            $this->info('User is not exist');
        }
    }

}