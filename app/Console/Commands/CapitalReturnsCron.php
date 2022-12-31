<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Config;
use App\Models\Topup;
use App\Models\DailyBonus;
use App\Models\Product;
use App\Models\CapitalRetruns;
use App\Dashboard;
use App\Http\Controllers\userapi\GenerateRoiController;
use DB;


class CapitalReturnsCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:capital_returns_cron';    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Capital returns Cron';

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
        $msg = "Capital Cron started at ".$current_time;
        echo $msg."\n";

        $insert_dailybonus_arr = array();
        
        $update_dash_arr = array();
        $pin_arr = array();
        $updateTopupData = [];

        $allTopus = Topup::select('tbl_topup.roi_status','tbl_topup.pin','tbl_topup.amount','tbl_topup.id')
        ->where('tbl_topup.roi_status','Inactive')->where('tbl_topup.capital_returns_status',0)
        ->whereIn('tbl_topup.type',array(1,2))
        ->get();
        
            $i=1;
        
            foreach ($allTopus as $tp)
            {   

                $date_diff = $tp->date_diff;
               $getDate = \Carbon\Carbon::now()->toDateString();
              
 
                    $amount = $tp->amount;
                    
                    list($usec, $sec) = explode(" ", microtime());

                    $time_start = ((float)$usec + (float)$sec);

                        $Dailydata = array();
                        $Dailydata['amount'] = $amount;
                        $Dailydata['id'] = $tp->id;
                        $Dailydata['pin'] = $tp->pin;
                        
                        array_push($insert_dailybonus_arr,$Dailydata);

                        $updateCoinData = array();
                        $updateCoinData['id'] = $tp->id;
                        $updateCoinData['capital_returns'] = $amount;
                        $updateCoinData['fund_wallet'] = $amount;
                        
                        array_push($update_dash_arr,$updateCoinData);
                  
                
                    
                    if ($tp->capital_returns_status == 0) {
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
          CapitalRetruns::insert($array[$key]);
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
        
                if (!isset($mainArr[$v['id']]['capital_returns']) && !isset($mainArr[$v['id']]['fund_wallet']))
                {

                    $mainArr[$v['id']]['capital_returns']=$mainArr[$v['id']]['fund_wallet']=0;
                   
                }
                $mainArr[$v['id']]['capital_returns'] += $v['capital_returns']; 
                $mainArr[$v['id']]['fund_wallet'] += $v['fund_wallet']; 
               
            }

            $ids = implode(',', array_column($mainArr, 'id'));
            $capital_returns_qry = 'capital_returns = (CASE id';
            $fund_wallet_qry = 'fund_wallet = (CASE id';
            
            foreach ($mainArr as $key => $val) {
                $capital_returns_qry = $capital_returns_qry . " WHEN ".$val['id']." THEN capital_returns + ".$val['capital_returns'];             
                $fund_wallet_qry = $fund_wallet_qry . " WHEN ".$val['id']." THEN fund_wallet + ".$val['fund_wallet'];            
                
            }

            $capital_returns_qry = $capital_returns_qry . " END)";         
            $fund_wallet_qry = $fund_wallet_qry . " END)";
            
            $updt_qry = "UPDATE tbl_dashboard SET ".$capital_returns_qry." , ".$fund_wallet_qry." WHERE id IN (".$ids.")";
            $updt_user = DB::statement(DB::raw($updt_qry));

            echo $count1." update from user dash array ".count($mainArr)."\n";
            $count1 ++;
        }

       $count3 = 1;     
        $array3 = array_chunk($pin_arr,1000);
        while($count3 <= count($array3))
        {
          $key3 = $count3-1;
          Topup::whereIn('pin',$array3[$key3])->update(['capital_returns_status'=> 1 ]);
          echo $count3." update pin array ".count($array3[$key3])."\n";
          $count3 ++;
        }

    
        $current_time = \Carbon\Carbon::now()->toDateTimeString();
        $msg = "Capital Cron end at ".$current_time."\nTotal records : ".count($insert_dailybonus_arr)."\n";
        
        echo $msg;

        echo "\n";
            list($usec, $sec) = explode(" ", microtime());
                                $time_end1 = ((float)$usec + (float)$sec);
                                   $time = $time_end1 - $time_start1;
                                   echo "after capital income cron ".$time."\n"; 

        

        DB::raw('UNLOCK TABLES');
    
    }
}
