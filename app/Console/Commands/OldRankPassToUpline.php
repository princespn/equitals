<?php

namespAce App\Console\Commands;
use App\Models\TodayDetails;
use App\Models\supermatching;
use Illuminate\Console\Command;
use DB;
class OldRankPassToUpline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:OldRankPassToUpline';

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
                ->select('id','user_id','rank','rank_upline_pass_status')
                ->where('rank_upline_pass_status', '=', 0)
                ->where('rank', '!=', "Ace")
                ->get();
                
         
        foreach ($users as $value) 
        {

            list($usec, $sec) = explode(" ", microtime());

            $time_start = ((float)$usec + (float)$sec);

            $flag=0;



            echo "Rank passing for ".$value->user_id." At ".date('Y-m-d H:i:s')." \n";

          
              


                if ($value->rank_upline_pass_status == 0) {

                    $rank_details= DB::table('tbl_rank')
                    ->select('id','rank','left_ace_req','right_ace_req','left_users_tbl_col','right_users_tbl_col')
                    ->where('rank', '=', $value->rank)
                    ->get();
                  

                    // update this ranks to upline
                    $left_rank_column=$rank_details[0]->left_users_tbl_col;
                    $right_rank_column=$rank_details[0]->right_users_tbl_col;


                $updateLCountArr = array();
                $updateLCountArr["${left_rank_column}"] = DB::raw("${left_rank_column}".' + 1');
               

                DB::table('tbl_today_details as a')
                ->join('tbl_users as b','a.to_user_id', '=','b.id')
                ->where('a.from_user_id','=',$value->user_id)
                ->where('a.position','=',1)
                ->update($updateLCountArr);


                $updateRCountArr = array();
                $updateRCountArr["${right_rank_column}"] = DB::raw("${right_rank_column}".' + 1');
                

                DB::table('tbl_today_details as a')
                ->join('tbl_users as b','a.to_user_id', '=','b.id')
                ->where('a.from_user_id','=',$value->user_id)
                ->where('a.position','=',2)
                ->update($updateRCountArr);



                $updateData = array();
                $updateData['rank_upline_pass_status'] = 1;
                
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