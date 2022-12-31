<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use DB;
use Response;
use App\User;
//use App\Http\Controllers\userapi\AwardRewardController;


class SignUpMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:sign_up_mail';
    protected $hidden = true;


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sign_up_mail';

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

    	 $usersList=User::select('email','id','user_id','mobile','temp_pass')->where('reg_mail_status','=',0)->get();

    	 	//dd(count($usersList));
    	 foreach ($usersList as $user) {

            $subject = "Registration Complete!";
            $pagename = "emails.registration";
            $data = array('pagename' => $pagename,'email' =>$user->email, 'username' =>$user->user_id ,'password'=>$user->temp_pass);
            $email =$user->email;
            $mail = sendMail($data, $email, $subject);

             $arr=array('temp_pass'=>1,'reg_mail_status'=>1);

            User::where('id','=',$user->id)->update($arr);
             //dd(222);

    	 	# code...
    	 }
                            
    }
}


                           