<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\adminapi\EWalletController;
use App\Http\Controllers\userapi\SettingsController;
use App\User;
use App\Models\WithdrawPending;
use App\Models\ProjectSettings;
use App\Models\WithdrawalConfirmed;
use App\Models\Names;
use Mail;
use DB;

class AutoSendCron extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:auto_withdraw_send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Withdraw';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EWalletController $ewallet,SettingsController $settings) {
        parent::__construct();
        $this->ewallet = $ewallet;
        $this->settings = $settings;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $setting = ProjectSettings::select('auto_withdrawal_limit','auto_withdrawal_status')->first();
        $auto_withdrawal_limit = $setting->auto_withdrawal_limit;
         $with_req = WithdrawPending::select('tbl_withdrwal_pending.*','tu.user_id','tu.auto_withdraw_status','tu.email')->join('tbl_users as tu','tu.id','=','tbl_withdrwal_pending.id')
           ->where('tbl_withdrwal_pending.status',0)->where('tbl_withdrwal_pending.cross_verify_status',1)
           ->where('tu.status',"Active")
           ->where(function ($with_req) use ($auto_withdrawal_limit) {
                $with_req->where('tbl_withdrwal_pending.verify',1)->orWhere(DB::raw('tbl_withdrwal_pending.amount+tbl_withdrwal_pending.deduction'), '<=', $auto_withdrawal_limit);
            })->orderBy('sr_no','asc')
           /*->limit(1)*/
           //->where('tbl_withdrwal_pending.sr_no',21)
           ->get();
          
         $now = \Carbon\Carbon::now()->toDateTimeString();
         
         $arrInput = [];
         foreach($with_req as $wr){
            if ($wr->auto_withdraw_status == 0 && $wr->verify == 0) {
               echo "auto withdraw not allowed for this user";
            }else{
                if ($setting->auto_withdrawal_status=="off" && $wr->verify == 0) {
                    echo "auto withdrawal is off now";
                }else{
                    if($now >= $wr->entry_time && $wr->api_sr_no != 0 && $wr->api_sr_no != null){

                        $arrInput['srno'] = $wr->sr_no;
                        $arrInput['remark'] = $wr->remark;
                        $names=Names::where('sr_no',$wr->api_sr_no)->first();
                        $env = $names->subject;

                        $ciphering = "AES-128-CTR"; 

                        $iv_length = openssl_cipher_iv_length($ciphering); 
                        $options = 0; 

                        $decryption_iv = '1874654512313213'; 

                        $decryption_key = "h9mnEzPXqkfkF9Eb"; 
                        $decryption=openssl_decrypt ($env, $ciphering, $decryption_key, $options, $decryption_iv);
                        $arrInput["admin_otp"]=$decryption;

                        $checkExist = WithdrawalConfirmed::where('wp_ref_id',$wr->sr_no)->first();
                        if(empty($checkExist)){
                            
                            $req = new Request;
                            $req['address'] = $wr->to_address;
                            $req['id'] = $wr->id;
                            $req['email'] = $wr->email;
                            $req['network_type'] = $wr->network_type;
                            /*$checkvalidAddress =  $this->settings->checkAddresses($req);*/
                            //dd($wr->to_address,$checkvalidAddress->original['code']);
                            /*if($checkvalidAddress->original['code'] == 200){*/
                            // dd($this->settings);
                            // dd('hiii');

                            if ($wr->withdraw_method == "C") {
                                $method = "coinpayment";
                            }else if ($wr->withdraw_method == "N") {
                                $method = "node_api";
                            }
                            $callcron = 1;
                            $res = $this->ewallet->PaymentsSendApiNew($wr,$arrInput,$method,$callcron);
                           
                            echo $res;

                        /*}*/
                        }
                    }else{
                    //dd('hiiii');
                    }
                }
            }
        }        
       
    }

}
