<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use DB;
use Response;
use App\User;
use App\Models\UserStructureModel;
//use App\Http\Controllers\userapi\AwardRewardController;


class SendStructureMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:send_structure_mail';
    protected $hidden = true;


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send structure mail';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    // public function __construct(AwardRewardController $assignaward)
    public function __construct()
    {
        parent::__construct();
       // $this->assignaward = $assignaward; 
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $userdata = UserStructureModel::select('id','user_id','email','temp_pass','no_structure','fullname')->where('status',1)->where('reg_mail_status',0)->get();
    	 	//dd(count($usersList));
    	 foreach ($userdata as $user) {

            $subject = "User Structure Created!";
            $pagename = "emails.structure_registration";
            $sponsor =User::select('email','user_id')->where('id',$user->user_id)->first();
            $data = array('pagename' => $pagename,'email' =>$sponsor->email,'user_email' =>$user->email,'fullname' =>$user->fullname, 'username' =>$sponsor->user_id ,'password'=>$user->temp_pass,'no_of_id'=>$user->no_structure);

            $email = $sponsor->email;
            $mail = sendMail($data, $email, $subject);

             $arr=array('temp_pass'=>1,'reg_mail_status'=>1);

            UserStructureModel::where('id','=',$user->id)->update($arr);
             //dd(222);

    	 	# code...
    	 }
                            
    }
}


                           