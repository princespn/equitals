<?php

namespAce App\Console\Commands;
use App\Models\TodayDetails;
use App\Models\supermatching;
use Illuminate\Console\Command;
use DB;
class OldContestRankPassToUpline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:OldContestRankPassToUpline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        

                      
               
        $users = DB::table('tbl_super_matching')
                ->select('id','user_id','rank','contest_rank_upline_pass_status')
                ->where('contest_rank_upline_pass_status', '=', 0)
                ->where('rank', "Commander")
                ->whereBetween("entry_time", ["2021-07-01 00:00:00", "2021-08-31 23:59:59"])
                ->get();
                
         
        foreach ($users as $value) 
        {

            list($usec, $sec) = explode(" ", microtime());

            $time_start = ((float)$usec + (float)$sec);

            $flag=0;



            echo "Rank passing for ".$value->user_id." At ".date('Y-m-d H:i:s')." \n";

          
              


                if ($value->contest_rank_upline_pass_status == 0) {

                    /*$rank_details= DB::table('tbl_rank')
                    ->select('id','rank','left_ace_req','right_ace_req','left_users_tbl_col','right_users_tbl_col')
                    ->where('rank', '=', $value->rank)
                    ->first();
                  

                    // update this ranks to upline
                    $left_rank_column=$rank_details->left_users_tbl_col;
                    $right_rank_column=$rank_details->right_users_tbl_col;*/


                    $updateLCountArr = array();
                    $updateLCountArr["contest_lbv"] = DB::raw("contest_lbv".' + 1');
                   

                    $qry1 = DB::table('tbl_users as a')
                    ->join('tbl_today_details as b','b.to_user_id', '=','a.id')
                    ->where('b.from_user_id','=',$value->user_id)
                    ->where('b.position','=',1)
                    ->update($updateLCountArr);


                    $updateRCountArr = array();
                    $updateRCountArr["contest_rbv"] = DB::raw("contest_rbv".' + 1');
                    

                    $qry2 = DB::table('tbl_users as a')
                    ->join('tbl_today_details as b','b.to_user_id', '=','a.id')
                    ->where('b.from_user_id','=',$value->user_id)
                    ->where('b.position','=',2)
                    ->update($updateRCountArr);



                    $updateData = array();
                    $updateData['contest_rank_upline_pass_status'] = 1;
                    
                    supermatching::where('id', $value->id)->update($updateData);
      

                }
            

            

            list($usec, $sec) = explode(" ", microtime());
            $time_end = ((float)$usec + (float)$sec);
               $time = $time_end - $time_start;
               echo "after rank pass time ".$time."\n";
        
        }
        echo "Old Rank Pass to Upline Cron Run Succesfully.....";


        
    }
}