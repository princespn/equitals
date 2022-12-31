<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use DB;
use Response;
use App\Models\FakeWithdraw;
use App\Models\UserInfo;
//use App\Http\Controllers\userapi\AwardRewardController;


class SecuritySendFakeWithdrawMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:fake_withdraw_mail';
    protected $hidden = true;


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fake_withdraw_mail';

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

    	 $usersList=FakeWithdraw::select('tbl_fake_withdraw.srno','tbl_fake_withdraw.withdraw_id','tbl_fake_withdraw.amount','tbl_fake_withdraw.remark','u.email','tbl_fake_withdraw.id','u.user_id','u.fullname','u.mobile')
         ->join('tbl_users as u','u.id','=','tbl_fake_withdraw.id')->where('mail_status','=',0)->get();

         $adminEmail = UserInfo::where('id',1)->pluck('email')->first();

    	 foreach ($usersList as $user) {

            $subject = "Regarding Invalid Withdrawal";
            $pagename = "emails.admin-emails.security_mail";
            $message  = "Dear User,<br>"
                        ."We had detected Invalid withdrawal of amount ".'$ '.$user->amount
                        ." For USER ID - ".$user->user_id ."<br>"
                        ."This withdrawal is Invalid because ".$user->remark;

            $data = array('pagename' => $pagename,'mesage'=>$message,'title'=>"Invalid Withdrawal");
        

            $emails = explode(',', $adminEmail);
            foreach ($emails as $vemail) {                
                $mail =sendMail($data, $vemail, $subject);
            }

             $arr=array('mail_status'=>1);

            FakeWithdraw::where('srno','=',$user->srno)->update($arr);
            echo "Invalid Withdrawal - ".$user->withdraw_id." Mail sent\n";
    	 }
                            
    }
}


                           