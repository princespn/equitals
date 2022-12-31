<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Config;
use App\Models\Topup;
use App\Models\DailyBonus;
use App\Models\Product;
use App\Models\ResidualIncome;
use App\Dashboard;
use App\Http\Controllers\userapi\GenerateRoiController;
use DB;


class OptimizedResidualIncome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:optimized_residual_income';    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Residual income Cron';

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
       
      /*  if($day == 'Sun' || $day == 'Sat'){
            // dd('In');
           return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'ROI is not allowed on this day', ''); 
        }
       */
        $current_time = \Carbon\Carbon::now()->toDateTimeString();
        $msg = "ROI Cron started at ".$current_time;
        echo $msg."\n";

        $insert_dailybonus_arr = array();
        
        $update_dash_arr = array();
        $user_id_arr = $user_id_arr1 = $user_id_arr2 = array();
        $pin_arr = array();
        $updateTopupData = [];

        /*$allTopusDistinctEntryTime = DailyBonus::select('tbl_dailybonus.residual_status')
        ->leftjoin('tbl_product as tp', 'tp.id', '=', 'tbl_dailybonus.type')
        ->where('tbl_dailybonus.residual_status', 0)->where('tbl_dailybonus.total_residual_count', 0)
        ->get();

        foreach ($allTopusDistinctEntryTime as $tpdet) 
        {*/
        
        $allTopus = DailyBonus::select('tbl_dailybonus.id','tbl_dailybonus.pin','tbl_dailybonus.type','tbl_dailybonus.amount','tbl_dailybonus.residual_status','tp.residual_income_per')
        ->leftjoin('tbl_product as tp', 'tp.id', '=', 'tbl_dailybonus.type')
        ->where('tbl_dailybonus.residual_status', 0)->where('tbl_dailybonus.total_residual_count', 0)
        ->get();

            $i=1;
          
            foreach ($allTopus as $tp)
            {   

                $date_diff = $tp->date_diff;
               $getDate = \Carbon\Carbon::now()->toDateString();
              
                    $on_amount = $tp->amount;
                     $type = $tp->type;
                    $residual_per = $tp->residual_income_per;
                    $pin = $tp->pin;
                    $resiual_amt = ($on_amount * $residual_per)/100;
                    
                    list($usec, $sec) = explode(" ", microtime());

                    $time_start = ((float)$usec + (float)$sec);

                        $Dailydata = array();
                        $Dailydata['id'] = $tp->id;
                        $Dailydata['amount'] = $resiual_amt;
                        $Dailydata['pin'] = $pin;
                        $Dailydata['residual_percentage'] = $residual_per;
                        $Dailydata['type'] = $type;
                        $Dailydata['on_amount'] = $on_amount;
                        $Dailydata['entry_time'] = \Carbon\Carbon::now()->toDateTimeString();
                       
                        array_push($insert_dailybonus_arr,$Dailydata);

                        $updateCoinData = array();
                        $updateCoinData['id'] = $tp->id;
                        $updateCoinData['residual_income'] = $resiual_amt;
                        // $updateCoinData['residual_income_withdraw'] = $resiual_amt;
                        $updateCoinData['requite_wallet'] = $resiual_amt;
                       

                        array_push($update_dash_arr,$updateCoinData);
                   
                    // topup update
                    
                    $updateTopupData[] = array(
                        'total_residual_count' => DB::raw('total_residual_count + 1'),
                        'pin' => $tp->pin
                    );
                  
                    $total_residual_count = $tp->total_residual_count + 1;
                    if ($total_residual_count == 1) {
                        array_push($pin_arr, $tp->pin);
                    }
       
       
                    echo " -> srno -> ".$i++." -> id -> ".$tp->id;
             

                    list($usec, $sec) = explode(" ", microtime());
                    $time_end = ((float)$usec + (float)$sec);
                    $time = $time_end - $time_start;
                    echo "time ".$time."\n";
                                                
            }

       
        $count = 1;
        $array = array_chunk($insert_dailybonus_arr,1000);
       // dd($array);
        while($count <= count($array))
        {
          $key = $count-1;
          ResidualIncome::insert($array[$key]);
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
        
                if (!isset($mainArr[$v['id']]['residual_income']) && !isset($mainArr[$v['id']]['residual_income_withdraw']) && !isset($mainArr[$v['id']]['requite_wallet']) /*&& !isset($mainArr[$v['id']]['residual_income_withdraw'])*/) 
                {

                    $mainArr[$v['id']]['residual_income']=$mainArr[$v['id']]['residual_income_withdraw']=0;
                    $mainArr[$v['id']]['requite_wallet']=0;
                    
                }
                $mainArr[$v['id']]['residual_income'] += $v['residual_income']; 
                // $mainArr[$v['id']]['residual_income_withdraw'] += $v['residual_income_withdraw']; 
                 $mainArr[$v['id']]['requite_wallet'] += $v['requite_wallet']; 
                
            }

            $ids = implode(',', array_column($mainArr, 'id'));
            $residual_income_qry = 'residual_income = (CASE id';
            $residual_income_withdraw_qry = 'residual_income_withdraw = (CASE id';
            $requite_wallet_qry = 'requite_wallet = (CASE id';


            foreach ($mainArr as $key => $val) {
                $residual_income_qry = $residual_income_qry . " WHEN ".$val['id']." THEN residual_income + ".$val['residual_income'];             
                $residual_income_withdraw_qry = $residual_income_withdraw_qry . " WHEN ".$val['id']." THEN residual_income_withdraw + ".$val['residual_income_withdraw'];
                $requite_wallet_qry = $requite_wallet_qry . " WHEN ".$val['id']." THEN requite_wallet + ".$val['requite_wallet'];
                
            }

            $residual_income_qry = $residual_income_qry . " END)";         
            $residual_income_withdraw_qry = $residual_income_withdraw_qry . " END)";
            $requite_wallet_qry = $requite_wallet_qry . " END)";
            
            $updt_qry = "UPDATE tbl_dashboard SET ".$residual_income_qry." ,".$residual_income_withdraw_qry." , ".$requite_wallet_qry." WHERE id IN (".$ids.")";
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

              $total_residual_count = 'total_residual_count = (CASE pin';
              

              foreach ($arrProcess as $key => $val){
                 $total_residual_count = $total_residual_count . " WHEN '".$val['pin']."' THEN ".$val['total_residual_count'];
                 
              }
              $total_residual_count = $total_residual_count . " END)"; 
              
              $updt_qry = "UPDATE tbl_dailybonus SET ".$total_residual_count." WHERE pin IN (".$pin.")";
              $updt_user = DB::statement(DB::raw($updt_qry));
              echo $stopCount." update direct status array ".count($arrProcess)."\n";
              $stopCount ++;
          }

            $count3 = 1;     
            $array3 = array_chunk($pin_arr,1000);
            while($count3 <= count($array3))
            {
              $key3 = $count3-1;
              DailyBonus::whereIn('pin',$array3[$key3])->update(['residual_status'=> 1]);
              echo $count3." update pin array ".count($array3[$key3])."\n";
              $count3 ++;
            }

    
        $current_time = \Carbon\Carbon::now()->toDateTimeString();
        $msg = "Residual Cron end at ".$current_time."\nTotal records : ".count($insert_dailybonus_arr)."\n";
        
        echo $msg;

        echo "\n";
            list($usec, $sec) = explode(" ", microtime());
                                $time_end1 = ((float)$usec + (float)$sec);
                                   $time = $time_end1 - $time_start1;
                                   echo "after roi income cron ".$time."\n"; 

        DB::raw('UNLOCK TABLES');
    
    }
}
