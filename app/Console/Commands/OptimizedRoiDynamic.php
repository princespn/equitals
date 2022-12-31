<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\userapi\GenerateRoiController;
use App\Models\DailyBonus;
use App\Models\Topup;
use App\Dashboard;
use DB;



class OptimizedRoiDynamic extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:optimized_roi_dynamic';
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

      DB::raw('LOCK TABLES `tbl_dashboard` WRITE');

      DB::raw('LOCK TABLES `tbl_dailybonus` WRITE');

      $day = \Carbon\Carbon::now()->format('D');
        // dd($day);
      if($day == 'Sun' || $day == 'Sat'){
            // dd('In');
        return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'ROI is not allowed on this day', ''); 
      }

      /*$user = User::join('tbl_topup', 'tbl_topup.id', '=', 'tbl_users.id')
      ->select('tbl_topup.amount','tbl_users.rank','tbl_users.id','tbl_users.mobile','tbl_users.country','tbl_users.user_id','tbl_users.email','tbl_topup.pin','tbl_topup.type','tbl_topup.entry_time','tbl_topup.withdraw','tbl_topup.old_status')->where([['tbl_users.status', '=', 'Active'],['tbl_users.type', '=', ''],['tbl_topup.roi_status', '=', 'Active']])
      ->get();*/

      $user = Topup::select('tbl_topup.id','tbl_topup.pin','tbl_topup.type','tbl_topup.amount','tbl_topup.amount_roi','tbl_topup.roi_status','tbl_topup.entry_time')
        ->where('tbl_topup.roi_status','Active')
        ->join('tbl_users as tu', 'tu.id', '=', 'tbl_topup.id')
        ->where('tu.status', 'Active')->where('tu.type', '')
        // ->where('tbl_topup.retopup_status','!=', 1)
        ->get();

      $today = \Carbon\Carbon::now();
      $today_datetime = $today->toDateTimeString();
      $today_datetime2 = $today->toDateString();


      echo $msg = 'ROI CRON started at' . $today_datetime . "\n" ;


      $dashArr=array();
      /*$traArr=array();
      $actArr=array();*/
      $roiArr= array();
      /*$finalArr= array();*/
      $arr_id = array();
      if (!empty($user)) {
        foreach ($user as $key => $val) {
          $invest_amount = $val->amount;
          $id = $val->id;
          $pin = $val->pin;
          $type = $val->type;
          $entry_time = $val->entry_time;
         /* $email = $user[$key]->email;
          $user_id = $user[$key]->user_id;
          $country = $user[$key]->country;
          $mobile = $user[$key]->mobile;
          $withdraw = $user[$key]->withdraw;
          $old_status = $user[$key]->old_status;
          $rank = $user[$key]->rank;*/

          $checkUpdate = $this->generateRoi->generateroidynamic($invest_amount, $id, $pin, $type, $entry_time);

          if($checkUpdate !=404 ){
           
           array_push($roiArr,$checkUpdate['dailydata']);
           array_push($dashArr,$checkUpdate['dashboarddata']);
           array_push($arr_id,$id);
           $arr_id[]=$id;
           /*$traArr[]=$checkUpdate['trandata'];*/
           /*$actArr[]=$checkUpdate['actdata'];*/ 
          }
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
      /* $count1 = 1;
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
      }*/

        //-----------------
        //dd($dashArr);
        /*foreach ($dashArr as $value) {
            # code...
          Dashboard::where('id', $value['id'])->limit(1)->update($value);
        }*/
      /*  $Total_ROI = 0.05;
        
        $updateCoinData = array();
        $updateCoinData['usd'] = DB::raw('usd +'.$Total_ROI);
        $updateCoinData['total_profit'] = DB::raw('total_profit +'. $Total_ROI); 
        $updateCoinData['roi_income'] = DB::raw('roi_income + '. $Total_ROI);
        $updateCoinData['roi_income_withdraw'] = DB::raw('roi_income_withdraw + '. $Total_ROI);
        $updateCoinData['working_wallet'] = DB::raw('working_wallet + '.$Total_ROI);   
        
        $count2 = 1;     
        $array2 = array_chunk($arr_id,1000);
        while($count2 <= count($array2))
        {
          $key2 = $count2-1;
          Dashboard::whereIn('id',$array2[$key2])->update($updateCoinData);
          echo $count2." update count array ".count($array2[$key2])."\n";
          $count2 ++;
        }*/

        $dashCount = 1;     
        
        $dasharray = array_chunk($dashArr,1000);

        while($dashCount <= count($dasharray))
        {
            $dashk = $dashCount-1;
            $arrProcess = $dasharray[$dashk];
            // echo $arrProcess;die();
            $ids = implode(',', array_column($arrProcess, 'id'));
            $total_profit_qry = 'total_profit = (CASE id';
            $roi_income_qry = 'roi_income = (CASE id';
            $working_wallet_qry = 'working_wallet = (CASE id';

            foreach ($arrProcess as $key => $val) {
                $total_profit_qry = $total_profit_qry . " WHEN ".$val['id']." THEN ".$val['total_profit'];
                $roi_income_qry = $roi_income_qry . " WHEN ".$val['id']." THEN ".$val['roi_income'];
                $working_wallet_qry = $working_wallet_qry . " WHEN ".$val['id']." THEN ".$val['working_wallet'];
            }

            $total_profit_qry = $total_profit_qry . " END)";
            $roi_income_qry = $roi_income_qry . " END)";
            $working_wallet_qry = $working_wallet_qry . " END)";
            
            $updt_qry = "UPDATE tbl_dashboard SET ".$working_wallet_qry." , ".$total_profit_qry." , ".$roi_income_qry." WHERE id IN (".$ids.")";
            $updt_user = DB::statement(DB::raw($updt_qry));

            echo $dashCount." update from user dash array ".count($arrProcess)."\n";
            $dashCount ++;
        }

        $this->info('ROI generated successfully');
        $today = \Carbon\Carbon::now();
        $today_datetime = $today->toDateTimeString();
        $today_datetime2 = $today->toDateString();

        /*$payoutHistory =DailyBonus::select('entry_time')->whereDate('entry_time', '=', $today_datetime2)->count();*/

        echo $msg = 'ROI CRON end at' . $today_datetime . "\n" ;

        DB::raw('UNLOCK TABLES');

     } else {

      $this->info('User is not exist');
    }
  }

}