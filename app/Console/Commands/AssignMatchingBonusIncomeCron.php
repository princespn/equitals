<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Controllers\userapi\GenerateRoiController;
// use App\Models\DailyBonus;
// use App\Models\Topup;
use App\Models\MatchingBonusIncomeSettings;
use App\Models\AchievedUserMatchingBonus;
use App\Models\UserInfo;
use DB;



class AssignMatchingBonusIncomeCron extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:assign_matching_bonus';
    //protected $hidden = true;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign matching bonus on l_bv and r_bv';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(/* GenerateRoiController $generateRoi */)
    {
        parent::__construct();
        // $this->generateRoi = $generateRoi;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $users = UserInfo::select('id', 'l_bv', 'r_bv')->where([['l_bv', '>=', 1000],['r_bv','>=',1000],['status','=','Active'],['type','=',''],['topup_status','=','1']])->get();

        $ranks = MatchingBonusIncomeSettings::where('status','Active')->get();
        $insert_rank = array();
        $updateUserData = array();
        foreach ($users as $user) {
            foreach ($ranks as $rank) {
                if (($user->l_bv >= $rank->left_bv) && ($user->r_bv >= $rank->right_bv)) {
                    $checkRank = AchievedUserMatchingBonus::select('id')->where('user_id', $user->id)->where('perform_id', $rank->id)->count('id');
                    if ($checkRank == 0) {
                        $rankdata = array();
                        $rankdata['user_id'] = $user->id;
                        $rankdata['perform_id'] = $rank->id;
                        $rankdata['bonus_name'] = $rank->bonus_name;
                        $rankdata['amount'] = $rank->amount;
                        $rankdata['entry_time'] = \Carbon\Carbon::now()->format("Y-m-d H:i:s");

                        /*array_push($insert_rank,$rankdata);*/
                        AchievedUserMatchingBonus::insert($rankdata);
                        $updateOldRank = AchievedUserMatchingBonus::where('user_id', $user->id)->where('perform_id', '<', $rank->id)->update(['status' => 1]);

                        $updateUserData[$user->id]['rank'] = $rank->bonus_name;
                        $updateUserData[$user->id]['id'] = $user->id;
                    }
                }
            }
        }

        /*$count1 = 1;
        $array1 = array_chunk($insert_rank,1000);
        while($count1 <= count($array1))
        {
            $key1 = $count1-1;
            AchievedUserRank::insert($array1[$key1]);
            echo $count1." insert achieved rank array ".count($array1[$key1])."\n";
            $count1 ++;
        } */

       /* $count2 = 1;

        $array2 = array_chunk($updateUserData, 1000);
        while ($count2 <= count($array2)) {
            $key2 = $count2 - 1;
            $arrProcess = $array2[$key2];
            $ids = implode(',', array_column($arrProcess, 'id'));
            $rank_qry = 'rank = (CASE id';
            foreach ($arrProcess as $key => $val) {
                $rank_qry = $rank_qry . " WHEN " . $val['id'] . " THEN '" . $val['rank'] . "'";
            }
            $rank_qry = $rank_qry . " END)";
            $updt_qry = "UPDATE tbl_users SET " . $rank_qry . " WHERE id IN (" . $ids . ")";
            $updt_user = DB::statement(DB::raw($updt_qry));

            echo $count2 . " update rank of user array " . count($arrProcess) . "\n";
            $count2++;
        }*/
    }
}
