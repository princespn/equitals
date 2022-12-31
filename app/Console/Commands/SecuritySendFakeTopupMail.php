<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use DB;
use Response;
use App\Models\FakeTopup;
use App\Models\UserInfo;
//use App\Http\Controllers\userapi\AwardRewardController;


class SecuritySendFakeTopupMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:fake_topup_mail';
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

    	 $usersList=FakeTopup::select('tbl_fake_topup.srno','tbl_fake_topup.amount','tbl_fake_topup.pin','tbl_fake_topup.remark','u.email','tbl_fake_topup.id','u.user_id','u.fullname','u.mobile','u2.user_id as from_user_id')
         ->join('tbl_users as u','u.id','=','tbl_fake_topup.id')->join('tbl_users as u2','u2.id','=','tbl_fake_topup.top_up_by')
         ->where('mail_status','=',0)->get();

        $adminEmail = UserInfo::where('id',1)->pluck('email')->first();

    	 foreach ($usersList as $user) {

            $subject = "Regarding Invalid Topup";
            $pagename = "emails.admin-emails.security_mail";
            $message  = "Dear User,<br>"
                        ."We had detected Invalid topup of amount ".'$ '.$user->amount
                        ." For USER ID - ".$user->user_id ." done by ".$user->from_user_id."<br>"
                        ."This topup is Invalid because ".$user->remark;

            $data = array('pagename' => $pagename,'mesage'=>$message,'title'=>"Invalid Topup");
        

            $emails = explode(',', $adminEmail);
            foreach ($emails as $vemail) {                
                $mail =sendMail($data, $vemail, $subject);
            }

             $arr=array('mail_status'=>1);

            FakeTopup::where('srno','=',$user->srno)->update($arr);
            echo "Invalid Topup - ".$user->pin." Mail sent\n";
    	 }
                            
    }
}


                           