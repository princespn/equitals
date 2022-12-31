<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserStructureModel;
use App\Traits\Users;
use App\User;
use App\Models\ProjectSettings;
use DB;
use Illuminate\Http\Request;


class BulkRegistrationCron extends Command
{
    use Users;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:bulk_registration';

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
       $userdata = array(3);//array('7915777','9883643','5424109','6581926','6386823','2139019','2282918','9801898');
      
        foreach ($userdata as $ref_user_id) {
            $user = User::select('id','user_id')->where('id',$ref_user_id)->first();
            if (!empty($user)) {
                $username = ['One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten'];
                $no_structure =  10;//50;
                for ($i = 0; $i<$no_structure;$i++) {
                    $arrInput = new Request();
                    /*$arrInput = array();*/
                    $arrInput['user_id'] = 'X'.rand(1000000,9999999);
                    $arrInput['fullname'] = "Equitals ".($i+1);
                    $arrInput['ref_user_id'] = $user->user_id;
                    $arrInput['position'] = 2;
                    $arrInput['country'] = "IN";
                    $arrInput['mobile'] = "56489063888";
                    $arrInput['email'] = "equitals@test.com ";
                    $arrInput['password'] = "equitals@123";
                    /*$request->request->add($arrInput);*/
                    $checkUpdate =  $this->binaryPlanBulk($arrInput);/*dd($checkUpdate);*/
                           
                }
            }            
        }
    }
}
