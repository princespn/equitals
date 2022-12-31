<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use DB; 
use App\Models\TheDailyReport;
use App\Models\WithdrawalConfirmed;
use App\Models\TransactionInvoice;

class DailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:dailyreport';

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
        //
        echo 'Daily report  CRON started at '. \Carbon\Carbon::now()->toDateTimeString() ."\n";
         
        $all_users = DB::table('tbl_users')->select('entry_time')->orderby('entry_time','asc')->where('type','=','')->first();

        $nextEntrydate  = date('Y-m-d',strtotime($all_users->entry_time));

        $exists = TheDailyReport::select('entry_time')->orderBy('entry_time','desc')->first();

        if (!empty($exists)) {
            $nextEntrydate  = date('Y-m-d',strtotime($exists->entry_time . "+1 days"));
        }
    
        $today = \Carbon\Carbon::now()->format('Y-m-d');
        
        $todaydetails_data = array();

        list($usec, $sec) = explode(" ", microtime());

        $time_start1 = ((float)$usec + (float)$sec);

        while ($today > $nextEntrydate) {

           /* DB::enableQueryLog();*/
            $totalWithdraw = WithdrawalConfirmed::where('status',1)->whereBetween('entry_time',[$nextEntrydate." 00:00:00",$nextEntrydate." 23:59:59"])->sum(DB::raw('amount + deduction'));
            /*dd(DB::getQueryLog());*/

            $totalDeposite = TransactionInvoice::where('in_status',1)->whereBetween('entry_time',[$nextEntrydate." 00:00:00",$nextEntrydate." 23:59:59"])->sum('price_in_usd');     

            $entryExists = TheDailyReport::select('entry_time')->where('entry_time',$nextEntrydate)->first();     
         
             if(empty($entryExists))
             {
                $DailyInsert = array();
                $DailyInsert['total_withdraw'] = $totalWithdraw;
                $DailyInsert['total_deposit'] = $totalDeposite;
                $DailyInsert['entry_time'] = $nextEntrydate;
                $insertdata = TheDailyReport::insert($DailyInsert);
             } 

            $nextEntrydate = date('Y-m-d',strtotime($nextEntrydate. "+1 days"));
        }
      

        echo 'Daily Report  CRON end at '. \Carbon\Carbon::now()->toDateTimeString() ."\n";

       /*  list($usec, $sec) = explode(" ", microtime());
                                      $time_end = ((float)$usec + (float)$sec);
                                         $time = $time_end- $time_start;
                                         echo "time  ".$time."\n";*/
    }
}
