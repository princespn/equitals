<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserStructureModel;
use App\Traits\Users;
use App\User;
use App\Models\ProjectSettings;
use DB;
use Illuminate\Http\Request;


class BulkEntries extends Command
{
    use Users;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:BulkEntries';

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
       /* $check_cron_running = DB::table('tbl_project_settings')
                      ->select('bulk_entry_cron_status')
                      ->where('bulk_entry_cron_status','=', 0)
                      ->count();

                      if($check_cron_running >= 0)
                      {

                         $data['bulk_entry_cron_status'] = '1';
                        DB::table('tbl_project_settings')
                        ->update($data);*/





        /*$userdata = UserStructureModel::select('id','status','user_id','mobile','email','no_structure','password','bcrypt_password','fullname','transaction_type')->where('status',0)->whereIn('transaction_type',array(1,2))->get();
      
        foreach ($userdata as $value) {*/
        /*foreach ($userdata as $ref_user_id) {*/

            $no_structure = 32; //(int)$value->no_structure;
          //dd($no_structure); 

            $total_structure_completed = 0;/*DB::table('tbl_users')
                      ->select('structure_id')
                      ->where('structure_id','=', $value->id)
                      ->count('structure_id');*/

            // dd(1,$total_structure_completed);
          //  $no_structure = $no_structure - $total_structure_completed;    
                for ($i = 0; $i<$no_structure;$i++) {
                    $obj = new Request();
                    $obj['fullname'] = "Equitals ".($i+11);
                    $obj['user_id'] = 13;
                    $obj['email'] = 'equitals@test.com';
                    $obj['country'] = "IN";
                    $obj['mobile'] = "56489063888";
                    $obj['password'] = "equitals@123";
                    /*$request->request->add($obj);*/

                        $checkUpdate =  $this->binaryPlanRegistrationnew($obj,$no_structure,$i);
                        echo $checkUpdate. "\n";   

                        /*$total_structure_completed = DB::table('tbl_users')
                      ->select('structure_id')
                      ->where('structure_id','=', $value->id)
                      ->count('structure_id');

                      if($total_structure_completed >= $value->no_structure)
                      {
                        $data1['status'] = '1';
                         DB::table('tbl_user_structure')
                        ->where('id','=', $value->id)
                        ->update($data1);
                      }
                      $i=$total_structure_completed;*/

            }


            
        /*}*/

      /*  $data2['bulk_entry_cron_status'] = '0';
                        DB::table('tbl_project_settings')
                       ->update($data2);*/

                       echo "Bulk Entries Cron Run Successfully...";

  /*  }
    else{
        echo "Bulk Entries Cron Already Running...";

    }*/
    }
}
