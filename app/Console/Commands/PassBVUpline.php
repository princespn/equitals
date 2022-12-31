<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Config;
use App\Models\Topup;
use App\Models\DailyBonus;
use App\Models\Product;
use App\Models\CurrentAmountDetails;
use App\Models\AddRemoveBusinessUpline;
use App\Dashboard;
use App\User;
use App\Http\Controllers\userapi\GenerateRoiController;
use DB;
use App\Traits\Income;

class PassBVUpline extends Command
{
  
  use Income;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:pass_bv_upline';    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'pass_bv_upline';

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
      
        

      $records = AddRemoveBusinessUpline::select('tbl_add_remove_business_upline.srno','tbl_add_remove_business_upline.user_id','tbl_add_remove_business_upline.position','tbl_add_remove_business_upline.power_bv','tbl_add_remove_business_upline.type','tu.l_bv','tu.r_bv','tu.curr_l_bv','tu.curr_r_bv')
      ->where('cron_status',"0")
      ->leftjoin('tbl_users as tu',"tu.id",'=',"tbl_add_remove_business_upline.user_id")
      ->get();

      foreach ($records as $value) 
      {
   
        $userArr = array();

        $before_lbv = $value->l_bv;
        $before_rbv = $value->r_bv;
        $before_curr_lbv = $value->curr_l_bv;
        $before_curr_rbv = $value->curr_r_bv;
        $position = $value->position;
        $powerbv = $value->power_bv;
        $new_lbv = 0;
        $new_rbv = 0;
        $new_curr_lbv = 0;
        $new_curr_rbv = 0;

        if ($position == 1) {
          if ($value->type == "2") {
            $new_lbv = $before_lbv - $powerbv;
            $new_rbv = $before_rbv;
            $new_curr_lbv = $before_curr_lbv - $powerbv;
            $new_curr_rbv = $before_curr_rbv;
            $userArr['manual_power_lbv'] = DB::raw('manual_power_lbv -' . $powerbv);
          } else {
            $new_lbv = $before_lbv + $powerbv;
            $new_rbv = $before_rbv;
            $new_curr_lbv = $before_curr_lbv + $powerbv;
            $new_curr_rbv = $before_curr_rbv;
            $userArr['manual_power_lbv'] = DB::raw('manual_power_lbv +' . $powerbv);
          }
        } elseif ($position == 2) {
          if ($value->type == "2") {
            $new_lbv = $before_lbv;
            $new_rbv = $before_rbv - $powerbv;
            $new_curr_lbv = $before_curr_lbv;
            $new_curr_rbv = $before_curr_rbv - $powerbv;
            $userArr['manual_power_rbv'] = DB::raw('manual_power_rbv -' . $powerbv);
          } else {
            $new_lbv = $before_lbv;
            $new_rbv = $before_rbv + $powerbv;
            $new_curr_rbv = $before_curr_rbv + $powerbv;
            $new_curr_lbv = $before_curr_lbv;
            $userArr['manual_power_rbv'] = DB::raw('manual_power_rbv +' . $powerbv);
          }
        }
        $userArr['l_bv'] = $new_lbv;
        $userArr['r_bv'] = $new_rbv;
        $userArr['curr_l_bv'] = $new_curr_lbv;
        $userArr['curr_r_bv'] = $new_curr_rbv;
        $updtuser = User::where('id',$value->user_id)->update($userArr);

        if ($value->type == "2") {
          $getlevel = $this->pay_binary_remove($value->user_id, $value->power_bv);          
        }elseif($value->type == "1"){
          $getlevel = $this->pay_binary($value->user_id, $value->power_bv);
        }

        $power = array();
        $power['before_lbv'] = $before_lbv;
        $power['before_rbv'] = $before_rbv;
        $power['after_lbv'] = $new_lbv;
        $power['after_rbv'] = $new_rbv;
        $power['before_curr_lbv'] = $before_curr_rbv;
        $power['before_curr_rbv'] = $before_curr_lbv;
        $power['after_curr_lbv'] = $new_curr_lbv;
        $power['after_curr_rbv'] = $new_curr_rbv;
        $power['cron_status'] = "1";

        AddRemoveBusinessUpline::where('srno','=',$value->srno)->update($power);
        echo "UserID => ".$value->user_id." srno". $value->srno."\n";
        echo "BV". $value->power_bv."\n";
      }
       echo "run success \n";
  }
}
