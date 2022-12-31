<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Policies\BinaryIncomeClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Config;
use DB;
use App\User;
use App\Models\UserInfo;
use App\Models\ProjectSettings;
use App\Models\PayoutHistory;
use App\Models\Product;
use App\Models\Topup;
use App\Models\QualifiedUserList as QualifiedUsers;
use App\Traits\Users;
use App\Models\Dashboard;
use App\Models\Carrybv;
use App\Models\CurrentAmountDetails;

class OptimizedBinaryIncome extends Command
{

    use Users;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:optimized_binary_income';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Binary income on amount';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BinaryIncomeClass $objBinaryIncome){
        parent::__construct();
        $this->objBinaryIncome = $objBinaryIncome;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    /**
     * Execute the console command.
     *
     * @return mixed
     */
  public function handle()
    {
        list($usec, $sec) = explode(" ", microtime());

        $time_start1 = ((float)$usec + (float)$sec);
        $now = \Carbon\Carbon::now();
        $day=date('D', strtotime($now));
        $current_time = \Carbon\Carbon::now()->toDateTimeString();

        $msg = "Binary Cron started at ".$current_time;
        //sendSMS($mobile,$msg);
        echo $msg."\n";

        echo "start ".now()."\n";

        $getUserDetails = UserInfo::select('tbl_users.id','tbl_users.curr_l_bv','tbl_users.curr_r_bv')
       /* ->join('tbl_topup as tt', 'tt.id', '=', 'tbl_users.id')*/
        ->where([['tbl_users.curr_l_bv','>',0],['tbl_users.curr_r_bv','>',0],['tbl_users.status','=',"Active"],['tbl_users.type','!=',"Admin"],['tbl_users.binary_qualified_status','=',1],['tbl_users.topup_status','=',"1"]])
        //->limit(1)
        ->get();
        /*dd($getUserDetails->toArray());*/

        $binary_count = 0;
        if(!empty($getUserDetails))
        {

            $payout_no=PayoutHistory::max('payout_no');
            if(empty($payout_no)){
              $payout_no=1;
            }else{
              $payout_no=$payout_no+1; 
            }

            $insert_carrybv_arr = array();
            $insert_payout_arr = array();
            $update_currbv_arr = array();
            $update_dash_arr = array();
            foreach ($getUserDetails as $key => $value) 
            {
                list($usec, $sec) = explode(" ", microtime());

                $time_start = ((float)$usec + (float)$sec);

                $deduction=array();
                $deduction['netAmount']=$deduction['tdsAmt']=$deduction['amt_pin']=0;

                $id=$getUserDetails[$key]['id'];
                $type = $value->type;
                
                    $left_bv=$getUserDetails[$key]['curr_l_bv']; 
                    $right_bv=$getUserDetails[$key]['curr_r_bv'];
                 
                    $getVal=min($left_bv,$right_bv);
                    $getTotalPair=$getVal;
                    if($getTotalPair!=0)
                    {    
                     
                      $perPair=1;
                      $match_bv=$getVal;
                      $laps_position=1;
                      $before_l_bv=$left_bv;
                      $before_r_bv=$right_bv;

                      $up_left_bv      = round($left_bv-$match_bv, 10);
                      $up_right_bv     = round($right_bv-$match_bv, 10);
            
                      $carry_l_bv=$up_left_bv;
                      $carry_r_bv=$up_right_bv;

                    //******add carry *************/
                        $carrybvArr1= array();
                        $carrybvArr1['user_id']=$id;
                        $carrybvArr1['payout_no']=$payout_no;
                        $carrybvArr1['before_l_bv']=$left_bv;
                        $carrybvArr1['before_r_bv']=$right_bv;
                        $carrybvArr1['match_bv']=$match_bv;  
                        $carrybvArr1['carry_l_bv']=$carry_l_bv;
                        $carrybvArr1['carry_r_bv']=$carry_r_bv;

                        array_push($insert_carrybv_arr,$carrybvArr1);

                        $dateTime= \Carbon\Carbon::now()->toDateTimeString(); 

                      //*******Update CurrentAmountDetails **********/

                    $updateCuurentData = array();
                    $updateCuurentData['curr_l_bv'] = $up_left_bv;
                    $updateCuurentData['curr_r_bv'] = $up_right_bv;
                    $updateCuurentData['id'] = $id;

                    array_push($update_currbv_arr,$updateCuurentData);

                    $netAmount=$deduction['netAmount'];
                    $tdsAmt= $deduction['tdsAmt'];
                    $amt_pin=$deduction['amt_pin'];

                    $laps_amount  = 0;
                    
                     $topup_amt = Topup::join('tbl_product','tbl_product.id','=','tbl_topup.type')
                    ->select('tbl_topup.srno','tbl_topup.duration','tbl_topup.binary_percentage','tbl_topup.total_income','tbl_topup.amount','tbl_topup.binary_capping','tbl_topup.roi_status','tbl_topup.entry_time','tbl_product.capping')
                    ->where('tbl_topup.id',$id)
                    ->orderBy('tbl_topup.type','desc')->first(); 
                    //->get();
              
                    if(!empty($topup_amt))
                    {
                        $topupAmount = $topup_amt->amount; 
                        $binary_per = $topup_amt->binary_percentage;  
                        $capping = $topup_amt->binary_capping;
                        
                        $amount = ($match_bv * $binary_per)/100;
                        $capping_amount  = 0;
                        $remark = "";

                       $check_if_ref_exist = Topup::select('id')->where('roi_status','Active')->where('id',$id)->count('id');

                        if($check_if_ref_exist == 0)
                        {
                           $capping_amount = $amount;
                           $laps_amount =   $amount;
                           $amount      =  0;
                           $remark = "Not Having Active Topup";
                        }
                        elseif($amount > $capping && $capping>0)
                        {  
                           $capping_amount = $capping;
                           $laps_amount =   ($amount - $capping);
                           $amount      =  $capping;
                        }

                    $netAmount=$amount - $amt_pin;	

                    $payoutArr= array();
                    $payoutArr['user_id']= $id;
        	          $payoutArr['amount']= $amount;
        	          $payoutArr['net_amount']= $netAmount;
        	          $payoutArr['tax_amount']= 0;
        	          $payoutArr['amt_pin']= $amt_pin;
        	          $payoutArr['left_bv']= $left_bv;
        	          $payoutArr['right_bv']= $right_bv;
        	          $payoutArr['match_bv']= $match_bv;
        	          $payoutArr['laps_bv']= 0;
        	          $payoutArr['laps_amount']= $laps_amount;
        	          //$payoutArr['product_id'] = $product_id;
        	          $payoutArr['entry_time']= $dateTime;
        	          $payoutArr['left_bv_before']= $before_l_bv;
        	          $payoutArr['right_bv_before']= $before_r_bv;
        	          $payoutArr['left_bv_carry']= $carry_l_bv;
        	          $payoutArr['right_bv_carry']= $carry_r_bv;
        	          $payoutArr['payout_no']=$payout_no;
        	          $payoutArr['capping_amount']=$capping_amount;
            			  $payoutArr['remark']=$remark;
            			  $payoutArr['percentage']=$binary_per;

                      array_push($insert_payout_arr,$payoutArr);

                      $binary_count++;

                      echo " Srno --> ".$binary_count." --> User Id --> ".$id." --> Binary Income --> ".$amount." --> Match --> ".$match_bv." --> Laps --> ".$laps_amount;

                      $updateDashArr = array();
                      $updateDashArr['binary_income'] = 'binary_income + ' . $amount . '';
                      $updateDashArr['working_wallet'] = 'working_wallet + ' . $amount . '';
                      $updateDashArr['id'] = $id;

                      array_push($update_dash_arr,$updateDashArr);

                        list($usec, $sec) = explode(" ", microtime());
                        $time_end = ((float)$usec + (float)$sec);
                           $time = $time_end - $time_start;
                           echo " --> time ".$time."\n";

                          // }//if
                    }
                  }
              }//end foreach
          // bulk operation
     
          // carry bv insert
          $count = 1;
            $array = array_chunk($insert_carrybv_arr,1000);
           // dd($array);
            while($count <= count($array))
            {
              $key = $count-1;
              Carrybv::insert($array[$key]);
              echo $count." insert carry bv array ".count($array[$key])."\n";
              $count ++;
            }

            // payout insert
          $count1 = 1;
          $array1 = array_chunk($insert_payout_arr,1000);
         // dd($array1);
          while($count1 <= count($array1))
          {
            $key1 = $count1-1;
            PayoutHistory::insert($array1[$key1]);
            echo $count1." insert payout array ".count($array1[$key1])."\n";
            $count1 ++;
          }

          $count2 = 1;     
        
          $array2 = array_chunk($update_currbv_arr,1000);
  
          while($count2 <= count($array2))
          {
            $key2 = $count2-1;
            $arrProcess = $array2[$key2];
            $ids = implode(',', array_column($arrProcess, 'id'));
            $rbv_qry = 'curr_r_bv = (CASE id';
            $lbv_qry = 'curr_l_bv = (CASE id';
            foreach ($arrProcess as $key => $val) {
              $rbv_qry = $rbv_qry . " WHEN ".$val['id']." THEN ".$val['curr_r_bv'];
              $lbv_qry = $lbv_qry . " WHEN ".$val['id']." THEN ".$val['curr_l_bv'];
            }
            $rbv_qry = $rbv_qry . " END)";
            $lbv_qry = $lbv_qry . " END)";
            $updt_qry = "UPDATE tbl_users SET ".$rbv_qry." , ".$lbv_qry." WHERE id IN (".$ids.")";
            $updt_user = DB::statement(DB::raw($updt_qry));
            
            echo $count2." update user array ".count($arrProcess)."\n";
            $count2 ++;
          }

          $count3 = 1;     
        
          $array3 = array_chunk($update_dash_arr,1000);
  
          while($count3 <= count($array3))
          {
            $key3 = $count3-1;
            $arrProcess = $array3[$key3];
            $ids = implode(',', array_column($arrProcess, 'id'));
            $binary_income_qry = 'binary_income = (CASE id';
            $working_wallet_qry = 'working_wallet = (CASE id';
            foreach ($arrProcess as $key => $val) {
              $binary_income_qry = $binary_income_qry . " WHEN ".$val['id']." THEN ".$val['binary_income'];
              $working_wallet_qry = $working_wallet_qry . " WHEN ".$val['id']." THEN ".$val['working_wallet'];
            }
            $binary_income_qry = $binary_income_qry . " END)";
            $working_wallet_qry = $working_wallet_qry . " END)";
            $updt_qry = "UPDATE tbl_dashboard SET ".$binary_income_qry." , ".$working_wallet_qry." WHERE id IN (".$ids.")";
            $updt_user = DB::statement(DB::raw($updt_qry));
            
            echo $count3." update dash array ".count($arrProcess)."\n";
            $count3 ++;
          }
       
        }//if
        echo "end ".now()."\n";
        echo "\n Cron run successfully \n" ;

        $current_time = \Carbon\Carbon::now()->toDateTimeString();
        $msg = "Binary Cron end at ".$current_time."\nTotal binary ids : ".$binary_count."\n";
        //sendSMS($mobile,$msg);
        echo $msg;
        list($usec, $sec) = explode(" ", microtime());
        $time_end1 = ((float)$usec + (float)$sec);
           $time = $time_end1 - $time_start1;
           echo "\n Total cron time -> ".$time."\n";


    }//handle function end
}//class end