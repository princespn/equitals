<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Config;
use App\Models\Topup;
use App\Models\DailyBonus;
use App\Models\Product;
use App\Dashboard;
use App\Http\Controllers\userapi\GenerateRoiController;
use DB;


class OptimizedRoiStatic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:optimized_roi_static';    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Roi Cron';

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
    
    public function handle()
    {   
       list($usec, $sec) = explode(" ", microtime());

        $time_start1 = ((float)$usec + (float)$sec);
        // dd('end');
        DB::raw('LOCK TABLES `tbl_dashboard` WRITE');
        // DB::raw('LOCK TABLES `tbl_topup` WRITE');
        DB::raw('LOCK TABLES `tbl_dailybonus` WRITE');
        //cron here
     
        // $time_start1 = microtime_float();
        $day = \Carbon\Carbon::now()->format('D');
       
        $current_time = \Carbon\Carbon::now()->toDateTimeString();
        $msg = "ROI Cron started at ".$current_time;
        echo $msg."\n";

        $insert_dailybonus_arr = array();
        
        $update_dash_arr = array();
        $user_id_arr = $user_id_arr1 = $user_id_arr2 = array();
        $pin_arr = array();
        $updateTopupData = [];

        $allTopusDistinctEntryTime = Topup::select(DB::raw("Date(tbl_topup.last_roi_entry_time) as last_roi_entry_time"))
        ->join('tbl_users as tu', 'tu.id', '=', 'tbl_topup.id')
        ->join('tbl_product as tp', 'tp.id', '=', 'tbl_topup.type')
        ->join('tbl_dashboard as td', 'td.id', '=', 'tbl_topup.id')
        ->where('tu.status', 'Active')->where('tu.type', '')
        ->where('tbl_topup.roi_status','Active')->where('tbl_topup.amount','>',0)
        ->where('tbl_topup.total_roi_amount','<',DB::raw('tbl_topup.total_roi_percentage'))
        ->where(DB::raw("TIMESTAMPDIFF(DAY,DATE(tbl_topup.last_roi_entry_time),now())"), '>=', DB::raw('tp.date_diff'))
        ->groupby(DB::raw("Date(tbl_topup.last_roi_entry_time)"))
        //->limit(1)
        ->get();
        
        foreach ($allTopusDistinctEntryTime as $tpdet) 
        {
            
        echo "Last ROI Date -> ".$last_roi_entry_time=$tpdet->last_roi_entry_time;
        echo "\n";
            
        $allTopus = Topup::select('tbl_topup.id','tbl_topup.pin','tbl_topup.roi_stop_status','tbl_topup.type','tbl_topup.amount','tbl_topup.entry_time','tbl_topup.percentage','tbl_topup.total_roi_amount','tbl_topup.total_roi_percentage','tp.date_diff','tbl_topup.amount_roi','tbl_topup.last_roi_entry_time','tu.entry_time as register_date')
        ->join('tbl_users as tu', 'tu.id', '=', 'tbl_topup.id')
        ->join('tbl_product as tp', 'tp.id', '=', 'tbl_topup.type')
        ->join('tbl_dashboard as td', 'td.id', '=', 'tbl_topup.id')
        ->where('tu.status', 'Active')->where('tu.type', '')
        ->where('tbl_topup.roi_status','Active')
        ->where('tbl_topup.total_roi_amount','<',DB::raw('tbl_topup.total_roi_percentage'))
       ->where(DB::raw("TIMESTAMPDIFF(DAY,DATE(tbl_topup.last_roi_entry_time),now())"), '>=', DB::raw('tp.date_diff'))
        ->where(DB::raw("Date(tbl_topup.last_roi_entry_time)"),$last_roi_entry_time)
       // ->limit(100000)
        ->get();
        
            $i=1;
        
            echo "last roi date -> ".$last_roi_entry_time;
            echo "\n";
           
          
            foreach ($allTopus as $tp)
            {   

                $date_diff = $tp->date_diff;
               $getDate = \Carbon\Carbon::now()->toDateString();
               $nextEntrydate=date('Y-m-d', strtotime($last_roi_entry_time. ' + '.$date_diff. 'days'));
               $getDay = \Carbon\Carbon::parse($nextEntrydate)->format('D');

                if($getDay == 'Sat'){
                   $nextEntrydate = date('Y-m-d', strtotime($nextEntrydate. ' + 2 days')); 
                }
                if($getDay == 'Sun'){
                   $nextEntrydate = date('Y-m-d', strtotime($nextEntrydate. ' + 1 day')); 
                }
            
               if(strtotime($nextEntrydate)<= strtotime($getDate))
               {    
                    $on_amount = $tp->amount;
                    $roi_amt_per = $tp->percentage;
                    $roi_amt = custom_round($tp->amount_roi,4);
                    $working_amt = $roi_amt;

                    list($usec, $sec) = explode(" ", microtime());

                    $time_start = ((float)$usec + (float)$sec);

                    if (($tp->total_roi_amount + $roi_amt) > $tp->total_roi_percentage) {
                       $roi_amt = $tp->total_roi_percentage - $tp->total_roi_amount;
                       echo "roi_amt->".$roi_amt."  total_roi_amount->".$tp->total_roi_amount;
                    }
                    /*dd($roi_amt,$tp->total_roi_amount,$tp->total_roi);*/
                   if($tp->roi_stop_status == 1){ 
                        $Dailydata = array();
                        $Dailydata['amount'] = $roi_amt;
                        $Dailydata['id'] = $tp->id;
                        $Dailydata['pin'] = $tp->pin;
                        // $Dailydata['status'] = 'Paid';
                        // $Dailydata['software_perentage'] = 0;
                        $Dailydata['daily_percentage'] = $roi_amt_per;
                        // $Dailydata['software_amount'] = 0;
                        $Dailydata['daily_amount'] = $roi_amt;
                        $Dailydata['entry_time'] = $nextEntrydate;
                        $Dailydata['on_amount'] = $on_amount;

                        $Dailydata['type'] = $tp->type;
                        // $Dailydata['tax_amount'] = 0;
                        array_push($insert_dailybonus_arr,$Dailydata);

                        $updateCoinData = array();
                        $updateCoinData['id'] = $tp->id;
                        $updateCoinData['usd'] = $roi_amt;
                        $updateCoinData['total_profit'] = $roi_amt;
                        $updateCoinData['roi_income'] = $roi_amt;
                        $updateCoinData['requite_wallet'] = $roi_amt;
                        /*$updateCoinData['roi_income_withdraw'] = $working_amt;
                        $updateCoinData['working_wallet'] = $working_amt; */

                        array_push($update_dash_arr,$updateCoinData);
                   }
                    // topup update
                    
                    $updateTopupData[] = array(
                        'total_roi_amount' => DB::raw('total_roi_amount + ' .$roi_amt),
                        'last_roi_entry_time' => $nextEntrydate,
                        'pin' => $tp->pin
                    );
                  
                    $total_roi_amount = $tp->total_roi_amount + $roi_amt;
                    if ($total_roi_amount >= $tp->total_roi_percentage) {
                        array_push($pin_arr, $tp->pin);
                    }
       
       
                    echo " -> srno -> ".$i++." -> id -> ".$tp->id." ->  roi date -> ".$nextEntrydate." -> ";
             

                    list($usec, $sec) = explode(" ", microtime());
                    $time_end = ((float)$usec + (float)$sec);
                    $time = $time_end - $time_start;
                    echo "time ".$time."\n";
                                                
                }
           }

             echo "\n roi date ".$nextEntrydate."\n";
        }
        $count = 1;
        $array = array_chunk($insert_dailybonus_arr,1000);
       // dd($array);
        while($count <= count($array))
        {
          $key = $count-1;
          DailyBonus::insert($array[$key]);
          echo $count." insert count array ".count($array[$key])."\n";
          $count ++;
        }

        /*Update ROI Income array*/
        $count1 = 1;
        $array1 = array_chunk($update_dash_arr,1000);
        while($count1 <= count($array1))
        {
            $key1 = $count1-1;
            $arrProcess = $array1[$key1];
            $mainArr = array();
            foreach ($arrProcess as $k => $v) {
                $mainArr[$v['id']]['id'] = $v['id'];
        
                if (!isset($mainArr[$v['id']]['total_profit']) && !isset($mainArr[$v['id']]['roi_income']) && !isset($mainArr[$v['id']]['requite_wallet']))
                {

                    $mainArr[$v['id']]['roi_income']=$mainArr[$v['id']]['total_profit']=0;
                    $mainArr[$v['id']]['requite_wallet']=0;
                    
                }
                $mainArr[$v['id']]['total_profit'] += $v['total_profit']; 
                $mainArr[$v['id']]['roi_income'] += $v['roi_income']; 
                $mainArr[$v['id']]['requite_wallet'] += $v['requite_wallet']; 
                
                
            }

            $ids = implode(',', array_column($mainArr, 'id'));
            $total_profit_qry = 'total_profit = (CASE id';
            $roi_income_qry = 'roi_income = (CASE id';
            $requite_wallet_qry = 'requite_wallet = (CASE id';

            foreach ($mainArr as $key => $val) {
                $total_profit_qry = $total_profit_qry . " WHEN ".$val['id']." THEN total_profit + ".$val['total_profit'];             
                $requite_wallet_qry = $requite_wallet_qry . " WHEN ".$val['id']." THEN requite_wallet + ".$val['requite_wallet'];            
                $roi_income_qry = $roi_income_qry . " WHEN ".$val['id']." THEN roi_income + ".$val['roi_income'];
                
            }

            $total_profit_qry = $total_profit_qry . " END)";         
            $requite_wallet_qry = $requite_wallet_qry . " END)";
            $roi_income_qry = $roi_income_qry . " END)";
            
            $updt_qry = "UPDATE tbl_dashboard SET ".$total_profit_qry." , ".$roi_income_qry.", ".$requite_wallet_qry." WHERE id IN (".$ids.")";
            $updt_user = DB::statement(DB::raw($updt_qry));

            echo $count1." update from user dash array ".count($mainArr)."\n";
            $count1 ++;
        }

         $stopCount = 1;
              $stopDirect = array_chunk($updateTopupData,1000);

              while($stopCount <= count($stopDirect))
              {
                  $keyx = $stopCount-1;
                  $arrProcess = $stopDirect[$keyx];
                  $pin = "'".implode("','", array_column($arrProcess, 'pin'))."'";

                  $total_roi_amount = 'total_roi_amount = (CASE pin';
                  $last_roi_entry_time = 'last_roi_entry_time = (CASE pin';

                  foreach ($arrProcess as $key => $val){
                     $total_roi_amount = $total_roi_amount . " WHEN '".$val['pin']."' THEN ".$val['total_roi_amount'];
                     $last_roi_entry_time = $last_roi_entry_time . " WHEN '".$val['pin']."' THEN '".$val['last_roi_entry_time']."'";
                  }
                  $total_roi_amount = $total_roi_amount . " END)"; 
                  $last_roi_entry_time = $last_roi_entry_time . " END)"; 
                  $updt_qry = "UPDATE tbl_topup SET ".$total_roi_amount." , ".$last_roi_entry_time." WHERE pin IN (".$pin.")";
                  $updt_user = DB::statement(DB::raw($updt_qry));
                  echo $stopCount." update direct status array ".count($arrProcess)."\n";
                  $stopCount ++;
              }


      $count3 = 1;     
            $array3 = array_chunk($pin_arr,1000);
            while($count3 <= count($array3))
            {
              $key3 = $count3-1;
              Topup::whereIn('pin',$array3[$key3])->update(['roi_status'=>'Inactive']);
              echo $count3." update pin array ".count($array3[$key3])."\n";
              $count3 ++;
            }

    
        $current_time = \Carbon\Carbon::now()->toDateTimeString();
        $msg = "ROI Cron end at ".$current_time."\nTotal records : ".count($insert_dailybonus_arr)."\n";
        
        echo $msg;

        echo "\n";
            list($usec, $sec) = explode(" ", microtime());
                                $time_end1 = ((float)$usec + (float)$sec);
                                   $time = $time_end1 - $time_start1;
                                   echo "after roi income cron ".$time."\n"; 

        

        DB::raw('UNLOCK TABLES');
    
    }
}
