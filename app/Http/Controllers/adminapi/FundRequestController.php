<?php

namespace App\Http\Controllers\adminapi;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Controllers\adminapi\CommonController;
use Illuminate\Support\Facades\Auth;
use App\Models\FundRequest;
use App\Models\FundTransfer;
use App\Models\BalanceTransfer;
use App\Models\TransactionInvoice;
use App\Models\PurchaseBalanceTransfer;
use App\Models\FundDeduction;
use App\Models\RemoveFundRequest;
use App\Models\dx_wallet_fund;
use App\Models\Dashboard;
use App\Models\DexToPurchaseFundTransfer;
use App\Models\WalletTransactionLog;
use App\Models\UserSettingFund;
use App\Models\FakeTopup;
use App\Models\FakeWithdraw;
use App\User;
use DB;
use Config;
use Validator;

class FundRequestController extends Controller
{
    /**
     * define property variable
     *
     * @return
     */
    public $statuscode, $commonController;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CommonController $commonController) {
        $this->statuscode =	Config::get('constants.statuscode');
        $this->commonController = $commonController;
    }

   
    /**
     * get all Franchise records
     *
     * @return \Illuminate\Http\Response
     */
    public function getFundRequest(Request $request) {

        $arrInput = $request->all();
        $url = url('uploads/files');
        $query = FundRequest::select('tbl_fund_request.*','tbl_fund_request.user_id as uid', 'tu.user_id', 'tu.fullname', DB::raw('IF(tbl_fund_request.pay_slip IS NOT NULL,CONCAT("' . $url . '","/",tbl_fund_request.pay_slip),NULL) attachment'))
            ->join('tbl_users as tu', 'tu.id', '=', 'tbl_fund_request.user_id');

        if (isset($arrInput['status'])) {
            $query = $query->where('tbl_fund_request.status', $arrInput['status']);
        }
        if (isset($arrInput['user_id'])) {
            $query = $query->where('tu.user_id', $arrInput['user_id']);
        }
        if (isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d', strtotime($arrInput['frm_date']));
            $arrInput['to_date'] = date('Y-m-d', strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_fund_request.entry_time,'%Y-%m-%d')"), [$arrInput['frm_date'], $arrInput['to_date']]);
        }
        if (!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])) {
            //searching loops on fields
            $fields = getTableColumns('tbl_fund_request');
            $search = $arrInput['search']['value'];
            $query = $query->where(function ($query) use ($fields, $search) {
                foreach ($fields as $field) {
                    $query->orWhere('tbl_fund_request.'.$field, 'LIKE', '%' . $search . '%');
                }
            });
        }
        if (isset($arrInput['status']) && $arrInput['status'] == 'Approve') {
            $query = $query->orderBy('tbl_fund_request.approve_date', 'desc');
        } else if (isset($arrInput['status']) && $arrInput['status'] == 'Reject') {
            $query = $query->orderBy('tbl_fund_request.reject_date', 'desc');
        } else {
            $query = $query->orderBy('tbl_fund_request.entry_time', 'desc');
        }

        $totalRecord = $query->count();
        $arrFundReq = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal'] = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records'] = $arrFundReq;

        if ($arrData['recordsTotal'] > 0) {
                        $intCode            = Response::HTTP_OK;
            $strStatus          = Response::$statusTexts[$intCode];
            return sendresponse($intCode, $strStatus, 'Record found', $arrData);
        } else {
                $intCode            = Response::HTTP_NOT_FOUND;
            $strStatus          = Response::$statusTexts[$intCode];
            return sendresponse($intCode, $strStatus, 'Record not found', '');
        }
    }

    /**
     * Approve Franchise
     *
     * @param franchise id
     *
     * @return \Illuminate\Http\Response
     **/
    public function approveFundRequest(Request $request) {
        //dd($request->id);
        $arrInput = $request->all();
        $rules = array(

            'remark' => 'required',
        );
        $messages = array(
            'remark.required' => 'Please enter remark.',
        );
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $message = $validator->errors();
            $err = '';
            foreach ($message->all() as $error) {
                $err = $err . " " . $error;
            }
            $intCode            = Response::HTTP_NOT_FOUND;
            $strStatus          = Response::$statusTexts[$intCode];
            return sendresponse($intCode, $strStatus, $err, '');
        }

        $fundreq = FundRequest::find($arrInput['id']);
        $userid = $fundreq->user_id;
        $dashboard = Dashboard::where('id', $userid)->first();
        if (empty($dashboard)) {
            $intCode            = Response::HTTP_NOT_FOUND;
            $strStatus          = Response::$statusTexts[$intCode];
            return sendresponse($intCode, $strStatus, 'Record not found', '');
        }
        if (!empty($fundreq)) {

            //$dashboard->usd = $dashboard->usd  + $fundreq->deposit_amt;
           /* $dashboard->top_up_wallet = $dashboard->top_up_wallet + $fundreq->amount;*/
            // $dashboard->working_wallet = $dashboard->working_wallet + $fundreq->amount;
          /*  $dashboard->update();*/
            $fundreq->status = 'Approve';
            $fundreq->admin_remark = $request->remark;
            $fundreq->approve_date = now();
            $fundreq->update();

            // $userupdate = Dashboard::where('id',$userid)->limit(1)->update(['usd'=>'PDC']);
                $intCode            = Response::HTTP_OK;
            $strStatus          = Response::$statusTexts[$intCode];
            return sendresponse($intCode, $strStatus, 'Successfully Approved', '');
        } else {
            $intCode            = Response::HTTP_NOT_FOUND;
            $strStatus          = Response::$statusTexts[$intCode];
            return sendresponse($intCode, $strStatus, 'Record not found', '');
        }
    }

    /**
     * Reject Franchise
     *
     * @param franchise id
     *
     * @return \Illuminate\Http\Response
     **/
    public function rejectFundRequest(Request $request) {
        $arrInput = $request->all();
        $rules = array(

            'remark' => 'required',
        );
        $messages = array(
            'remark.required' => 'Please enter remark.',
        );
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $message = $validator->errors();
            $err = '';
            foreach ($message->all() as $error) {
                $err = $err . " " . $error;
            }
            $intCode            = Response::HTTP_NOT_FOUND;
            $strStatus          = Response::$statusTexts[$intCode];
            return sendresponse($intCode, $strStatus, $err, '');
        }
        $fundreq = FundRequest::find($arrInput['id']);
        if (!empty($fundreq)) {
            $fundreq->status = 'Reject';
            $fundreq->admin_remark = $request->remark;
            $fundreq->reject_date = now();
            $fundreq->update();
            // $userupdate = User::where('id',$userid)->limit(1)->update(['type'=>'PDC']);
            $intCode            = Response::HTTP_OK;
            $strStatus          = Response::$statusTexts[$intCode];
            return sendresponse($intCode, $strStatus, 'Successfully Rejected', '');
        } else {
            $intCode            = Response::HTTP_NOT_FOUND;
            $strStatus          = Response::$statusTexts[$intCode];
            return sendresponse($intCode, $strStatus, 'Record not found', '');
        }
    }


      public function fundRequest(Request $request) {
        $rules = array(
            'user_id' => 'required',
            'amount' => 'required',
        );
        // $messages = array(
        //     'user_id.required' => 'Please enter user id.',
        //     'amount.required' => 'Please enter amount.',
        // );

        $validator = Validator::make($request->all(), $rules);
        $id = Auth::User()->id;
            $arrInput            = $request->all();
            $arrInput['user_id'] = $id;
            $arrRules            = ['otp' => 'required|min:6|max:10'];
            $verify_otp = verify_Otp($arrInput);
            
            if (!empty($verify_otp)) {
                if ($verify_otp['status'] == 200) {
                } else {
                    $arrStatus = Response::HTTP_NOT_FOUND;;
                    $arrCode = Response::$statusTexts[$arrStatus];
                    $arrMessage = 'Invalid or Expired Otp!';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
                }
            } else {
                $arrStatus = Response::HTTP_NOT_FOUND;;
                $arrCode = Response::$statusTexts[$arrStatus];
                $arrMessage = 'Invalid or Expired Otp!';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
            }
        if ($validator->fails()) {
            $message = $validator->errors();
            $err = '';
            foreach ($message->all() as $error) {
                $err = $err . " " . $error;
            }
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, '');
        }

        $userExist = Auth::user();

        if (!empty($userExist)) 
        {
                // $requestExist = FundRequest::where('user_id', $userExist->id)->whereIn('status', ['pending','approve'])->first();
                // if (!empty($requestExist)) {
                //     return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Request already send.', '');
                // } else {
                    $fundRequest=new FundRequest;
                  
                    $fundRequest->user_id=$request->user_id;
                    $fundRequest->amount=$request->amount;
                    $fundRequest->fund_status=$request->fund_status;
                    $fundRequest->admin_remark=$request->remark;
                    $fundRequest->status='Approve';
                    $fundRequest->save();
                    
                    $dash = Dashboard::where('id',$request->user_id)->first();
                    $update = Dashboard::where('id',$request->user_id)->update(['top_up_wallet'=>$dash->top_up_wallet + $request->amount]);

                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Fund Added successfully!', '');
                // }
           
        }
        else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User does not exist', '');
        }
    }

    public function fund_wallet_action(Request $request) {
        $arrInput = $request->all();
        $rules = array(
            'user_id' => 'required',
            'amount' => 'required|numeric|min:20',
            'action' => 'required'
        );
        $messages = array(
            'user_id.required' => 'Please enter user id.',
            'amount.required' => 'Please enter amount.',
            'action.required' => 'Please select action.',
        );

        $validator = Validator::make($arrInput, $rules, $messages);
		$id = Auth::User()->id;
		$arrInput['user_id'] = $id;
			$arrRules            = ['otp' => 'required|min:6|max:10'];
            $verify_otp = verify_Otp($arrInput);

			
            if (!empty($verify_otp)) {
                if ($verify_otp['status'] == 200) {
                } else {
                    $arrStatus = Response::HTTP_NOT_FOUND;;
                    $arrCode = Response::$statusTexts[$arrStatus];
                    $arrMessage = 'Invalid or Expired Otp!';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
                }
            } else {
                $arrStatus = Response::HTTP_NOT_FOUND;;
                $arrCode = Response::$statusTexts[$arrStatus];
                $arrMessage = 'Invalid or Expired Otp!';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
            }
        if ($validator->fails()) {
            $message = $validator->errors();
            $err = '';
            foreach ($message->all() as $error) {
                $err = $err . " " . $error;
            }
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, '');
        }
        if($request->amount < 1)
        {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'The amount field must be 1 or more', '');
        } 
        //dd($request->action, $request->amount, $request->balance);
        if($request->action == 2 && ($request->amount > $request->balance))
        {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Insufficent Balance', '');
        }    
        $userExist = Auth::user();

        if (!empty($userExist)) 
        {
               
                    $fundWalletAction=new WalletTransactionLog  ;
                    $date= \Carbon\Carbon::now();
                    $today= $date->toDateTimeString();
                    $dash = Dashboard::where('id',$request->user_id)->first();

                    if($request->action == 1)
                    {
                        $action = "Added";
                        if(empty($request->remark))
                        {
                        $fundWalletAction->remark="Admin added fund in new fund wallet"; 
                        }
                        else
                        {
                            $fundWalletAction->remark=$request->remark; 
                        }
                        $update = Dashboard::where('id',$request->user_id)->update(['fund_wallet'=>$dash->fund_wallet + $request->amount]);
                    }
                    if($request->action == 2)
                    {
                        $action = "Removed";
                        if(empty($request->remark))
                        {
                        $fundWalletAction->remark="Admin removed fund from new fund wallet"; 
                        }
                        else
                        {
                            $fundWalletAction->remark=$request->remark; 
                        }
                        $update = Dashboard::where('id',$request->user_id)->update(['fund_wallet_withdraw'=>$dash->fund_wallet_withdraw + $request->amount]);
                    }
                    /* 
                     else{
                         return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'invalid action', '');
                     } 
                     */
                   
                    //dd($request->wallet_type);
                    $fundWalletAction->to_user_id=$request->user_id;
                    $fundWalletAction->from_user_id=1;
                    $fundWalletAction->amount=$request->amount;
                    $fundWalletAction->wallet_type=$request->wallet_type; 
                    $fundWalletAction->transaction_type=$request->action; 
                    $fundWalletAction->ip_address=$_SERVER['REMOTE_ADDR']; 
                    $fundWalletAction->entry_time=$today;
                    $fundWalletAction->save();



                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], "Fund ".$action." successfully!", '');
                
           
        }
        else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User does not exist', '');
        }
    }

    public function amountDeduction(Request $request) {
        $rules = array(
            'user_id' => 'required',
            'amount' => 'required',
        );
        $messages = array(
            'user_id.required' => 'Please enter user id.',
            'amount.required' => 'Please enter amount.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $message = $validator->errors();
            $err = '';
            foreach ($message->all() as $error) {
                $err = $err . " " . $error;
            }
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, '');
        }

        $userExist = Auth::user();

        if (!empty($userExist)) 
        {
            if ($request->type == 1) {
                $balance = Dashboard::selectRaw('ROUND(working_wallet - working_wallet_withdraw,2) as balance')->where('id',$request->user_id)->pluck('balance')->first();

                $wallet_name = 'Account Wallet';
                if ($balance >= $request->amount) {
                    $update = Dashboard::where('id',$request->user_id)->update(["working_wallet_withdraw"=>DB::raw('working_wallet_withdraw +'.$request->amount)]);
                }else{
                    return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Insufficent Balance', '');
                }
                
            }else{
                $balance = Dashboard::selectRaw('ROUND(top_up_wallet - top_up_wallet_withdraw,2) as balance')->where('id',$request->user_id)->pluck('balance')->first();
                $wallet_name = 'Purchase Wallet';
                if ($balance >= $request->amount) {
                    $update = Dashboard::where('id',$request->user_id)->update(["top_up_wallet_withdraw"=>DB::raw('top_up_wallet_withdraw +'.$request->amount)]);
            
                }else{
                    return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Insufficent Balance', '');
                }
            }
                


            $fundRequest=new FundDeduction;
          
            $fundRequest->user_id=$request->user_id;
            $fundRequest->amount=$request->amount;
            $fundRequest->wallet_name=$wallet_name;
            $fundRequest->save();
            

            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Amount deduct successfully!', '');
                // }
           
        }
        else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User does not exist', '');
        }
    }


     /**
     * get all Fund records
     *
     * @return \Illuminate\Http\Response
     */
    public function fundReport(Request $request) {
        $arrInput = $request->all();
        $query = FundRequest::select('tbl_fund_request.admin_remark','tbl_fund_request.amount','tbl_fund_request.entry_time','tbl_fund_request.status','tu.user_id as user_id','tu.fullname as fullname')
                                ->join('tbl_users as tu','tu.id','=','tbl_fund_request.user_id')
                                ->where('tbl_fund_request.fund_status','=','0');
        if(isset($arrInput['status']))
        {
            $query=$query->where('tbl_fund_request.status',$request->status);
        }
        if(isset($arrInput['user_id']))
        {
            $query=$query->where('tu.user_id',$request->user_id);
        }
        if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d',strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_fund_request.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
        }
        /*if(!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])){
            
            $fields = getTableColumns('tbl_fund_request');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere('tbl_fund_request.'.$field,'LIKE','%'.$search.'%');
                }
                $query->orWhere('tu.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu.fullname','LIKE','%'.$search.'%');
            });
        }*/

        if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
			$qry = $query;
			$qry = $qry->select('tu.user_id as user_id','tu.fullname as fullname','tbl_fund_request.amount','tbl_fund_request.status','tbl_fund_request.admin_remark','tbl_fund_request.entry_time');
			$records = $qry->get();
			$res = $records->toArray();
			if (count($res) <= 0) {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
			}
			$var = $this->commonController->exportToExcel($res,"AllUsers");
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
		}


        $totalRecord   = $query->count('tbl_fund_request.user_id');
        $query         = $query->orderBy('tbl_fund_request.entry_time','desc');
        // $totalRecord   = $query->count();
        $arrFranchise      = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrFranchise;

        if(count($arrFranchise) > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found',$arrData);
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found','');
        }
    }

     /**
     * get all Fund records
     *
     * @return \Illuminate\Http\Response
     */
    public function SettingfundReport(Request $request) {
        $arrInput = $request->all();
        $query = FundRequest::select('tbl_fund_request.admin_remark','tbl_fund_request.amount','tbl_fund_request.entry_time','tbl_fund_request.status','tu.user_id as user_id','tu.fullname as fullname')
                                ->join('tbl_users as tu','tu.id','=','tbl_fund_request.user_id')
                                ->where('tbl_fund_request.fund_status','=','1');
        if(isset($arrInput['status']))
        {
            $query=$query->where('tbl_fund_request.status',$request->status);
        }
        if(isset($arrInput['user_id']))
        {
           // dd($arrInput['user_id']);
            $query=$query->where('tu.user_id',$request->user_id);
        }
        if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d',strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_fund_request.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
        }

        /*if(!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])){
            
            $fields = getTableColumns('tbl_fund_request');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere('tbl_fund_request.'.$field,'LIKE','%'.$search.'%');
                }
                $query->orWhere('tu.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu.fullname','LIKE','%'.$search.'%');
            });
        }*/ 
        $query         = $query->orderBy('tbl_fund_request.entry_time','desc');
        $totalRecord   = $query->count('tbl_fund_request.id');
        // $totalRecord   = $query->count();
        $arrFranchise      = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrFranchise;

        if(count($arrFranchise) > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found',$arrData);
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found','');
        }
    }

     /**
     * get all Fund records
     *
     * @return \Illuminate\Http\Response
     */
    public function deductionReport(Request $request) {
        $arrInput = $request->all();
        $query = FundDeduction::select('tbl_deduction_stat.*','tu.user_id as user_id','tu.fullname as fullname')->join('tbl_users as tu','tu.id','=','tbl_deduction_stat.user_id');
       /* if(isset($arrInput['status']))
        {
            $query=$query->where('tbl_deduction_stat.status',$request->status);
        }*/
        if(isset($arrInput['user_id']))
        {
            $query=$query->where('tu.user_id',$request->user_id);
        }
        if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d',strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_deduction_stat.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
        }
        if(!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])){
            //searching loops on fields
            $fields = getTableColumns('tbl_deduction_stat');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere('tbl_deduction_stat'.$field,'LIKE','%'.$search.'%');
                }
            });
        }
        $totalRecord   = $query->count('tbl_deduction_stat.srno');
        $query         = $query->orderBy('tbl_deduction_stat.entry_time','desc');
        // $totalRecord   = $query->count();
        $arrFranchise      = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrFranchise;

        if(count($arrFranchise) > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found',$arrData);
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found','');
        }
    }
    public function removefundRequest(Request $request) {

		$rules = array(
            'user_id' => 'required',
            'amount' => 'required',
        );
        $messages = array(
            'user_id.required' => 'Please enter user id.',
            'amount.required' => 'Please enter amount.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $message = $validator->errors();
            $err = '';
            foreach ($message->all() as $error) {
                $err = $err . " " . $error;
            }
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, '');
        }

        $userExist = Auth::user();

        if (!empty($userExist)) 
        {
                // $requestExist = FundRequest::where('user_id', $userExist->id)->whereIn('status', ['pending','approve'])->first();
                // if (!empty($requestExist)) {
                //     return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Request already send.', '');
                // } else {
                    $removefundRequest=new RemoveFundRequest;
                  
                    $removefundRequest->user_id=$request->user_id;
                    $removefundRequest->amount=$request->amount;
                    $removefundRequest->fund_status=$request->fund_status;
                    $removefundRequest->admin_remark=$request->remark;
                    $removefundRequest->status='Approve';
                    $removefundRequest->save();
                    
                    $dash = Dashboard::where('id',$request->user_id)->first();
                    $update = Dashboard::where('id',$request->user_id)->update(['top_up_wallet_withdraw'=>$dash->top_up_wallet_withdraw + $request->amount]);

                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Fund Removed successfully!', '');
                // }
           
        }
        else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User does not exist', '');
        }

    }
    public function remove_dxwallet_fund(Request $request) {

		$rules = array(
            'user_id' => 'required',
            'amount' => 'required',
        );
        $messages = array(
            'user_id.required' => 'Please enter user id.',
            'amount.required' => 'Please enter amount.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $message = $validator->errors();
            $err = '';
            foreach ($message->all() as $error) {
                $err = $err . " " . $error;
            }
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, '');
        }

        $userExist = Auth::user();

        if (!empty($userExist)) 
        {
                // $requestExist = FundRequest::where('user_id', $userExist->id)->whereIn('status', ['pending','approve'])->first();
                // if (!empty($requestExist)) {
                //     return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Request already send.', '');
                // } else {
                    $dxremovefundRequest=new dx_wallet_fund;
                  
                    $dxremovefundRequest->user_id=$request->user_id;
                    $dxremovefundRequest->amount=$request->amount;
                    $dxremovefundRequest->fund_status=$request->fund_status;
                    $dxremovefundRequest->admin_remark=$request->remark;
                    $dxremovefundRequest->status='Approve';
                    $dxremovefundRequest->save();
                    
                    $dash = Dashboard::where('id',$request->user_id)->first();
                    $update = Dashboard::where('id',$request->user_id)->update(['working_wallet_withdraw'=>$dash->working_wallet_withdraw + $request->amount]);

                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Fund Removed successfully!', '');
                // }
           
        }
        else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User does not exist', '');
        }

    }
    public function remove_dxwallet_fundReport(Request $request) {
         
        $arrInput = $request->all();
        $query = dx_wallet_fund::select('tbl_dxwallet_remove_fund.admin_remark','tbl_dxwallet_remove_fund.amount','tbl_dxwallet_remove_fund.entry_time','tbl_dxwallet_remove_fund.status','tu.user_id as user_id','tu.fullname as fullname')
                                ->join('tbl_users as tu','tu.id','=','tbl_dxwallet_remove_fund.user_id')
                                ->where('tbl_dxwallet_remove_fund.fund_status','=','0');
        if(isset($arrInput['status']))
        {
            $query=$query->where('tbl_dxwallet_remove_fund.status',$request->status);
        }
        if(isset($arrInput['user_id']))
        {
            $query=$query->where('tu.user_id',$request->user_id);
        }
        if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d',strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_dxwallet_remove_fund.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
        }
        // if(!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])){
        //     //searching loops on fields
        //     $fields = getTableColumns('tbl_dxwallet_remove_fund');
        //     $search = $arrInput['search']['value'];
        //     $query  = $query->where(function ($query) use ($fields, $search){
        //         foreach($fields as $field){
        //             $query->orWhere('tbl_dxwallet_remove_fund.'.$field,'LIKE','%'.$search.'%');
        //         }
        //         $query->orWhere('tu.user_id','LIKE','%'.$search.'%')
        //         ->orWhere('tu.fullname','LIKE','%'.$search.'%');
        //     });
        // }
        $query         = $query->orderBy('tbl_dxwallet_remove_fund.entry_time','desc');
        $totalRecord   = $query->count('tbl_dxwallet_remove_fund.id');
        $arrFranchise      = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrFranchise;

        if(count($arrFranchise) > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found',$arrData);
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found','');
        }
    }
    	/**
	 * [e fundRequestReport for perticular user ]
	 * @param  Request $request [token alpha-num]
	 * @return [Array]
	 */
	public function removefundReport(Request $request) {
         
        $arrInput = $request->all();
        $query = RemoveFundRequest::select('tbl_remove_fund.admin_remark','tbl_remove_fund.amount','tbl_remove_fund.entry_time','tbl_remove_fund.status','tu.user_id as user_id','tu.fullname as fullname')
                                ->join('tbl_users as tu','tu.id','=','tbl_remove_fund.user_id')
                                ->where('tbl_remove_fund.fund_status','=','0');
        if(isset($arrInput['status']))
        {
            $query=$query->where('tbl_remove_fund.status',$request->status);
        }
        if(isset($arrInput['user_id']))
        {
            $query=$query->where('tu.user_id',$request->user_id);
        }
        if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d',strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_remove_fund.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
        }
        /*if(!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])){
            
            $fields = getTableColumns('tbl_remove_fund');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere('tbl_remove_fund.'.$field,'LIKE','%'.$search.'%');
                }
                $query->orWhere('tu.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu.fullname','LIKE','%'.$search.'%');
            });
        }*/

        if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
			$qry = $query;
			$qry = $qry->select('tu.user_id as user_id','tu.fullname as fullname','tbl_remove_fund.amount','tbl_remove_fund.status','tbl_remove_fund.admin_remark','tbl_remove_fund.entry_time' );
			$records = $qry->get();
			$res = $records->toArray();
			if (count($res) <= 0) {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
			}
			$var = $this->commonController->exportToExcel($res,"AllUsers");
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
		}


        $totalRecord   = $query->count('tbl_remove_fund.user_id');
        $query         = $query->orderBy('tbl_remove_fund.entry_time','desc');
        // $totalRecord   = $query->count('tbl_remove_fund.user_id');
        $arrFranchise      = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrFranchise;

        if(count($arrFranchise) > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found',$arrData);
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found','');
        }
    }
    public function settingremovefundReport(Request $request) {
         
        $arrInput = $request->all();
        $query = RemoveFundRequest::select('tbl_remove_fund.admin_remark','tbl_remove_fund.amount','tbl_remove_fund.entry_time','tbl_remove_fund.status','tu.user_id as user_id','tu.fullname as fullname')
                                ->join('tbl_users as tu','tu.id','=','tbl_remove_fund.user_id')
                                ->where('tbl_remove_fund.fund_status','=','1');
        if(isset($arrInput['status']))
        {
            $query=$query->where('tbl_remove_fund.status',$request->status);
        }
        if(isset($arrInput['user_id']))
        {
            $query=$query->where('tu.user_id',$request->user_id);
        }
        if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d',strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_remove_fund.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
        }

        /*if(!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])){
            
            $fields = getTableColumns('tbl_remove_fund');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere('tbl_remove_fund.'.$field,'LIKE','%'.$search.'%');
                }
                $query->orWhere('tu.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu.fullname','LIKE','%'.$search.'%');
            });
        }*/
       // $totalRecord   = $query->count('tbl_remove_fund.id');

        $query         = $query->orderBy('tbl_remove_fund.entry_time','desc');
        $totalRecord   = $query->count('tbl_remove_fund.id');
        // $totalRecord   = $query->count();
        $arrFranchise      = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrFranchise;

        if(count($arrFranchise) > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found',$arrData);
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found','');
        }
	}
    public function fundTransferReport(Request $request) {
        $arrInput = $request->all();
        $query = FundTransfer::select('tu.user_id','tu1.user_id as from_user_id','tu.fullname','tu1.fullname as from_fullname','tbl_fund_transfer.amount','tbl_fund_transfer.entry_time',DB::raw('CASE WHEN tbl_fund_transfer.wallet_type = 4 THEN "Working Wallet" WHEN tbl_fund_transfer.wallet_type = 6 THEN "Purchase Wallet" WHEN tbl_fund_transfer.wallet_type = 7 THEN "Purchase Wallet" ELSE "" END as wallet_type'))
            ->join('tbl_users as tu','tu.id','=','tbl_fund_transfer.to_user_id')
            ->join('tbl_users as tu1','tu1.id','=','tbl_fund_transfer.from_user_id');
        
        if(isset($arrInput['user_id']))
        {
            $query=$query->where('tu.user_id',$request->user_id);
        }
        if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d',strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_fund_transfer.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
        }
        /*if(!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])){
           
            $fields = getTableColumns('tbl_fund_transfer');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere('tbl_fund_request.'.$field,'LIKE','%'.$search.'%');
                }
                $query->orWhere('tu.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu.fullname','LIKE','%'.$search.'%')->orWhere('tu1.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu1.fullname','LIKE','%'.$search.'%');
            });
        }*/

        if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
			$qry = $query;
			$qry = $qry->select('tu.user_id','tu.fullname','tu1.user_id as from_user_id','tu1.fullname as from_fullname','tbl_fund_transfer.amount','tbl_fund_transfer.entry_time');
			$records = $qry->get();
			$res = $records->toArray();
			if (count($res) <= 0) {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
			}
			$var = $this->commonController->exportToExcel($res,"AllUsers");
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
		}

        $totalRecord   = $query->count('tbl_fund_transfer.to_user_id');
        $query         = $query->orderBy('tbl_fund_transfer.entry_time','desc');
        $arrFranchise      = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrFranchise;

        if(count($arrFranchise) > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found',$arrData);
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found','');
        }
    }
    public function balanceTransferReport(Request $request) {
        $arrInput = $request->all();
        $query = BalanceTransfer::select('tu.user_id','tu.fullname','tbl_balance_transfer.amount','tbl_balance_transfer.transferred_amount','tbl_balance_transfer.status','tbl_balance_transfer.email','tbl_balance_transfer.entry_time')
            ->join('tbl_users as tu','tu.id','=','tbl_balance_transfer.user_id');
        
        if(isset($arrInput['user_id']))
        {
            $query=$query->where('tu.user_id',$request->user_id);
        }
        if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d',strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_balance_transfer.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
        }
        /*if(!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])){
            
            $fields = getTableColumns('tbl_fund_transfer');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere('tbl_balance_transfer.'.$field,'LIKE','%'.$search.'%');
                }
                $query->orWhere('tu.user_id','LIKE','%'.$search.'%')->orWhere('tu.email','LIKE','%'.$search.'%')
                ->orWhere('tu.fullname','LIKE','%'.$search.'%');
            });
        }*/


		if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
			$qry = $query;
			$qry = $qry->select('tu.user_id','tu.fullname','tbl_balance_transfer.amount','tbl_balance_transfer.transferred_amount','tbl_balance_transfer.status','tbl_balance_transfer.email','tbl_balance_transfer.entry_time');
			$records = $qry->get();
			$res = $records->toArray();
			if (count($res) <= 0) {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
			}
			$var = $this->commonController->exportToExcel($res,"AllUsers");
			return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
		}

        $totalRecord   = $query->count('tbl_balance_transfer.user_id');
        $query         = $query->orderBy('tbl_balance_transfer.entry_time','desc');
        $arrFranchise      = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrFranchise;

        if(count($arrFranchise) > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found',$arrData);
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found','');
        }
    }
    public function PurchaseBalanceTransferReport(Request $request) {
        $arrInput = $request->all();
        $query = PurchaseBalanceTransfer::select('tu.user_id','tu.fullname','tbl_purchase_balance_transfer.amount','tbl_purchase_balance_transfer.transferred_amount','tbl_purchase_balance_transfer.status','tbl_purchase_balance_transfer.email','tbl_purchase_balance_transfer.entry_time')
            ->join('tbl_users as tu','tu.id','=','tbl_purchase_balance_transfer.user_id');
        
        if(isset($arrInput['user_id']))
        {
            $query=$query->where('tu.user_id',$request->user_id);
        }
        if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d',strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_purchase_balance_transfer.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
        }
        /*if(!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])){
            
            $fields = getTableColumns('tbl_fund_transfer');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere('tbl_purchase_balance_transfer.'.$field,'LIKE','%'.$search.'%');
                }
                $query->orWhere('tu.user_id','LIKE','%'.$search.'%')->orWhere('tu.email','LIKE','%'.$search.'%')
                ->orWhere('tu.fullname','LIKE','%'.$search.'%');
            });
        }*/


        if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
            $qry = $query;
            $qry = $qry->select('tu.user_id','tu.fullname','tbl_purchase_balance_transfer.amount','tbl_purchase_balance_transfer.transferred_amount','tbl_purchase_balance_transfer.status','tbl_purchase_balance_transfer.email','tbl_purchase_balance_transfer.entry_time');
            $records = $qry->get();
            $res = $records->toArray();
            if (count($res) <= 0) {
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
            }
            $var = $this->commonController->exportToExcel($res,"AllUsers");
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
        }

        $totalRecord   = $query->count('tbl_purchase_balance_transfer.user_id');
        $query         = $query->orderBy('tbl_purchase_balance_transfer.entry_time','desc');
        $arrFranchise      = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrFranchise;

        if(count($arrFranchise) > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found',$arrData);
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found','');
        }
    }
    public function DexToPurchaseTransferReport(Request $request) {
        $arrInput = $request->all();
        $query = DexToPurchaseFundTransfer::select('tu.user_id','tu.fullname as name',DB::raw('ROUND(tbl_dex_to_purchase_transfer.amount,2) as amount'),'tbl_dex_to_purchase_transfer.entry_time',DB::raw('(CASE  WHEN tbl_dex_to_purchase_transfer.from_wallet_type = 1 THEN "Working Wallet" ELSE "Purchase Wallet" END ) as from_wallet_type'),DB::raw('(CASE  WHEN tbl_dex_to_purchase_transfer.to_wallet_type = 1 THEN "Working Wallet" ELSE "Purchase Wallet" END ) as to_wallet_type'))
                ->join('tbl_users as tu','tu.id',"=",'tbl_dex_to_purchase_transfer.to_user_id');
        
        if(isset($arrInput['id']))
        {
            $query=$query->where('tu.user_id',$request->id);
        }
        if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d',strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_dex_to_purchase_transfer.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
        }
        /*if(!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])){
           
            $fields = getTableColumns('tbl_dex_to_purchase_transfer');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere('tbl_fund_request.'.$field,'LIKE','%'.$search.'%');
                }
                $query->orWhere('tu.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu.fullname','LIKE','%'.$search.'%')->orWhere('tu1.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu1.fullname','LIKE','%'.$search.'%');
            });
        }*/

   
        if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
            $qry = $query;
            $qry = $qry->select('tu.user_id','tu.fullname as name',DB::raw('ROUND(tbl_dex_to_purchase_transfer.amount,2) as amount'),'tbl_dex_to_purchase_transfer.entry_time',DB::raw('(CASE  WHEN tbl_dex_to_purchase_transfer.from_wallet_type = 1 THEN "Working Wallet" ELSE "Purchase Wallet" END ) as from_wallet_type'),DB::raw('(CASE  WHEN tbl_dex_to_purchase_transfer.to_wallet_type = 1 THEN "Working Wallet" ELSE "Purchase Wallet" END ) as to_wallet_type'));
            $records = $qry->get();
            $res = $records->toArray();
            if (count($res) <= 0) {
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
            }
            $var = $this->commonController->exportToExcel($res,"AllUsers");
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
        }

        $totalRecord   = $query->count('tbl_dex_to_purchase_transfer.to_user_id');
        $query         = $query->orderBy('tbl_dex_to_purchase_transfer.entry_time','desc');
        $arrFranchise      = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrFranchise;

        if(count($arrFranchise) > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found',$arrData);
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found','');
        }
    }   
    public function WalletTransactionReport(Request $request) {
        $arrInput = $request->all();
        $query = WalletTransactionLog::select(DB::raw('COALESCE(tu.user_id," ") as user_id'),'tu1.user_id as from_user_id',DB::raw('COALESCE(tu.fullname," ") as fullname'),'tu1.fullname as from_fullname','tbl_wallet_transaction_log.amount','tbl_wallet_transaction_log.remark','tbl_wallet_transaction_log.entry_time',DB::raw('CASE WHEN tbl_wallet_transaction_log.wallet_type = 1 THEN "Working Wallet" WHEN tbl_wallet_transaction_log.wallet_type = 2 THEN "Purchase Wallet" WHEN tbl_wallet_transaction_log.wallet_type = 3 THEN "Fund Wallet" WHEN tbl_wallet_transaction_log.wallet_type = 4 THEN "Development Bonus" ELSE "" END as wallet_type'),'tbl_wallet_transaction_log.ip_address')
            ->leftjoin('tbl_users as tu','tu.id','=','tbl_wallet_transaction_log.to_user_id')
            ->leftjoin('tbl_users as tu1','tu1.id','=','tbl_wallet_transaction_log.from_user_id');
        
        if(isset($arrInput['to_user_id']))
        {
            $query=$query->where('tu.user_id',$request->to_user_id);
        }
        if(isset($arrInput['from_user_id']))
        {
            $query=$query->where('tu1.user_id',$request->from_user_id);
        }
        if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d',strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_wallet_transaction_log.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
        }
        /*if(!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])){
           
            $fields = getTableColumns('tbl_wallet_transaction_log');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere('tbl_fund_request.'.$field,'LIKE','%'.$search.'%');
                }
                $query->orWhere('tu.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu.fullname','LIKE','%'.$search.'%')->orWhere('tu1.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu1.fullname','LIKE','%'.$search.'%');
            });
        }*/
          $query= $query->orderBy('tbl_wallet_transaction_log.entry_time','desc');
        if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
            $qry = $query;
            $qry = $qry->select(DB::raw('COALESCE(tu.user_id," ") as user_id'),'tu1.user_id as from_user_id',DB::raw('COALESCE(tu.fullname," ") as fullname'),'tu1.fullname as from_fullname','tbl_wallet_transaction_log.amount',DB::raw('CASE WHEN tbl_wallet_transaction_log.wallet_type = 1 THEN "Working Wallet" WHEN tbl_wallet_transaction_log.wallet_type = 2 THEN "Purchase Wallet" WHEN tbl_wallet_transaction_log.wallet_type = 3 THEN " Fund Wallet" ELSE "" END as wallet_type'),'tbl_wallet_transaction_log.remark','tbl_wallet_transaction_log.ip_address','tbl_wallet_transaction_log.entry_time');
            $records = $qry->get();
            $res = $records->toArray();
            if (count($res) <= 0) {
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
            }
            $var = $this->commonController->exportToExcel($res,"wallet_transaction_report");
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
        }

        $totalRecord   = $query->count('tbl_wallet_transaction_log.to_user_id');
        $query         = $query->orderBy('tbl_wallet_transaction_log.entry_time','desc');
        $arrFranchise      = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrFranchise;

        if(count($arrFranchise) > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found',$arrData);
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found','');
        }
    }
      
    public function fakeTopupReport(Request $request) {
        $arrInput = $request->all();
        $query = FakeTopup::select("tu.user_id as to_user_id",'tu1.user_id as from_user_id','tu.fullname as to_name','tu1.fullname as from_name','tbl_fake_topup.amount','tbl_fake_topup.remark','tbl_fake_topup.entry_time','tbl_fake_topup.amount','tbl_fake_topup.pin')
            ->leftjoin('tbl_users as tu','tu.id','=','tbl_fake_topup.id')
            ->leftjoin('tbl_users as tu1','tu1.id','=','tbl_fake_topup.top_up_by');
        
        if(isset($arrInput['to_user_id']))
        {
            $query=$query->where('tu.user_id',$request->to_user_id);
        }
        if(isset($arrInput['from_user_id']))
        {
            $query=$query->where('tu1.user_id',$request->from_user_id);
        }
        if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d',strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_fake_topup.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
        }
        /*if(!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])){
           
            $fields = getTableColumns('tbl_fake_topup');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere('tbl_fund_request.'.$field,'LIKE','%'.$search.'%');
                }
                $query->orWhere('tu.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu.fullname','LIKE','%'.$search.'%')->orWhere('tu1.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu1.fullname','LIKE','%'.$search.'%');
            });
        }*/

        if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
            $qry = $query;
            $qry = $qry->select(DB::raw('COALESCE(tu.user_id," ") as user_id'),'tu1.user_id as from_user_id',DB::raw('COALESCE(tu.fullname," ") as fullname'),'tu1.fullname as from_fullname','tbl_fake_topup.amount','tbl_fake_topup.pin','tbl_fake_topup.remark','tbl_fake_topup.entry_time');
            $records = $qry->get();
            $res = $records->toArray();
            if (count($res) <= 0) {
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
            }
            $var = $this->commonController->exportToExcel($res,"AllUsers");
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
        }

        $totalRecord   = $query->count('tbl_fake_topup.srno');
        $query         = $query->orderBy('tbl_fake_topup.entry_time','desc');
        $arrFranchise      = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrFranchise;

        if(count($arrFranchise) > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found',$arrData);
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found','');
        }
    }
     
    public function fakeWithdrawReport(Request $request) {
        $arrInput = $request->all();
        $query = FakeWithdraw::select("tu.user_id",'tu.fullname','tbl_fake_withdraw.amount','tbl_fake_withdraw.remark','tbl_fake_withdraw.entry_time',DB::raw('(CASE tbl_fake_withdraw.withdraw_type WHEN 2 THEN "Working" WHEN 3 THEN "Roi" WHEN 4 THEN "Self Working" WHEN  5 THEN "Self Roi" ELSE "" END) as wallet_type'))
            ->leftjoin('tbl_users as tu','tu.id','=','tbl_fake_withdraw.id');
        
        if(isset($arrInput['to_user_id']))
        {
            $query=$query->where('tu.user_id',$request->to_user_id);
        }
        if(isset($arrInput['from_user_id']))
        {
            $query=$query->where('tu1.user_id',$request->from_user_id);
        }
        if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d',strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_fake_withdraw.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
        }
        /*if(!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])){
           
            $fields = getTableColumns('tbl_fake_withdraw');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere('tbl_fund_request.'.$field,'LIKE','%'.$search.'%');
                }
                $query->orWhere('tu.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu.fullname','LIKE','%'.$search.'%')->orWhere('tu1.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu1.fullname','LIKE','%'.$search.'%');
            });
        }*/

        if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
            $qry = $query;
            $qry = $qry->select(DB::raw('COALESCE(tu.user_id," ") as user_id'),DB::raw('COALESCE(tu.fullname," ") as fullname'),'tbl_fake_withdraw.amount','tbl_fake_withdraw.remark',DB::raw('(CASE tbl_fake_withdraw.withdraw_type WHEN 2 THEN "Working" WHEN 3 THEN "Roi" WHEN 4 THEN "Self Working" WHEN  5 THEN "Self Roi" ELSE "" END) as wallet_type'),'tbl_fake_withdraw.entry_time');
            $records = $qry->get();
            $res = $records->toArray();
            if (count($res) <= 0) {
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
            }
            $var = $this->commonController->exportToExcel($res,"AllUsers");
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
        }

        $totalRecord   = $query->count('tbl_fake_withdraw.srno');
        $query         = $query->orderBy('tbl_fake_withdraw.entry_time','desc');
        $arrFranchise      = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrFranchise;

        if(count($arrFranchise) > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found',$arrData);
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found','');
        }
    }
    
    public function getFundInvoiceTransaction(Request $request)
    {
        $rules = array(
            'invoice_id' => 'required',
        );
        $validator = Validator::make($request->all(), $rules, []);
        if ($validator->fails()) {
            $message = $validator->errors();
            $err     = '';
            foreach ($message->all() as $error) {
                if (count($message->all()) > 1) {
                    $err = $err.' '.$error;
                } else {
                    $err = $error;
                }
            }
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, []);
        }
        $checkExist=TransactionInvoice::select('trans_id')->where('invoice_id',$request->invoice_id)->first();
        if (!empty($checkExist)) {
            $txn=array();
            $txn['id'] = $checkExist->trans_id;

            $transData = get_node_trans_status('publicInvoiceStatus', $txn);
            
            if ($transData['data']['status'] == 'OK' && $transData['data']['code'] == 200) {
                $arrData = $transData['data']['data'];
                if ($arrData['paymentStatus'] == "EXPIRED") {
                    $expired = array();
                    $expired['paymentStatus'] = "EXPIRED";
                    TransactionInvoice::where('invoice_id',$request->invoice_id)->update(['in_status'=>2]);                   
                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], "Data found",$expired);
                }elseif ($arrData['paymentStatus'] == "PAID") {
                    $paid = array();
                    $paid['paymentStatus'] = "PAID";
                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], "Data found",$paid);
                }elseif($arrData['paymentStatus'] == "PENDING"){
                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], "Data found",$arrData);
                }
            }else{

                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, []);
            }
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, []);
        }  
    }

     public function addSettingFund(Request $request) {
        $arrInput = $request->all();
        $rules = array(
            'user_id' => 'required',
            'amount' => 'required|numeric|min:10',
            'topup_percentage' => 'required|numeric|min:1|max:100',
        );
        $messages = array(
            'user_id.required' => 'Please enter user id.',
            'amount.required' => 'Please enter amount.',
        );

        $validator = Validator::make($arrInput, $rules, $messages);
		$id = Auth::User()->id;
		$arrInput['user_id'] = $id;
			$arrRules            = ['otp' => 'required|min:6|max:10'];
            $verify_otp = verify_Otp($arrInput);

			
            if (!empty($verify_otp)) {
                if ($verify_otp['status'] == 200) {
                } else {
                    $arrStatus = Response::HTTP_NOT_FOUND;;
                    $arrCode = Response::$statusTexts[$arrStatus];
                    $arrMessage = 'Invalid or Expired Otp!';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
                }
            } else {
                $arrStatus = Response::HTTP_NOT_FOUND;;
                $arrCode = Response::$statusTexts[$arrStatus];
                $arrMessage = 'Invalid or Expired Otp!';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                // return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid Otp Request!', '');
            }
        if ($validator->fails()) {
            $message = $validator->errors();
            $err = '';
            foreach ($message->all() as $error) {
                $err = $err . " " . $error;
            }
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], $err, '');
        }

        $userExist = Auth::user();

        if (!empty($userExist)) 
        {
                $user = User::leftjoin('tbl_dashboard as td','td.id','=','tbl_users.id')->where('tbl_users.id', $request->user_id)->select('tbl_users.id','td.setting_fund_wallet','td.setting_fund_wallet_withdraw')->first();
                if (empty($user)) {
                    return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'User not found','');
                } else {
                    $before = $user->setting_fund_wallet - $user->setting_fund_wallet_withdraw;
                    $after = $before + $request->amount;

                    $fundRequest= array();                  
                    $fundRequest['user_id']=$request->user_id;
                    $fundRequest['amount']=$request->amount;
                    $fundRequest['topup_percentage']=$request->topup_percentage;
                    $fundRequest['before_balance']=$before;
                    $fundRequest['after_balance']=$after;
                    $fundRequest['remark']=$request->remark;
                    $fundRequest['ip_address']=$_SERVER['REMOTE_ADDR']; 
                    $fundRequest['entry_time']=\Carbon\Carbon::now()->toDateTimeString();

                    $insert = UserSettingFund::create($fundRequest);
                   
                    $update = Dashboard::where('id',$request->user_id)->update(['setting_fund_wallet'=>DB::raw('setting_fund_wallet  + ' .$request->amount)]);

                    return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Fund Added successfully!', '');
                }
           
        }
        else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User does not exist', '');
        }
    }
    public function addSettingFundReport(Request $request) {
        $arrInput = $request->all();
        $query = UserSettingFund::select('tbl_user_setting_fund.remark','tbl_user_setting_fund.amount','tbl_user_setting_fund.topup_percentage','tbl_user_setting_fund.entry_time','tbl_user_setting_fund.status','tu.user_id as user_id','tu.fullname as fullname','tbl_user_setting_fund.ip_address')
            ->join('tbl_users as tu','tu.id','=','tbl_user_setting_fund.user_id');
            
        if(isset($arrInput['status']))
        {
            $query=$query->where('tbl_user_setting_fund.status',$request->status);
        }
        if(isset($arrInput['id']))
        {
            $query=$query->where('tu.user_id',$request->id);
        }
        if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
            $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
            $arrInput['to_date']  = date('Y-m-d',strtotime($arrInput['to_date']));
            $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_user_setting_fund.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
        }
        /*if(!empty($arrInput['search']['value']) && isset($arrInput['search']['value'])){
            
            $fields = getTableColumns('tbl_user_setting_fund');
            $search = $arrInput['search']['value'];
            $query  = $query->where(function ($query) use ($fields, $search){
                foreach($fields as $field){
                    $query->orWhere('tbl_user_setting_fund.'.$field,'LIKE','%'.$search.'%');
                }
                $query->orWhere('tu.user_id','LIKE','%'.$search.'%')
                ->orWhere('tu.fullname','LIKE','%'.$search.'%');
            });
        }*/

        if (isset($arrInput['action']) && $arrInput['action'] == 'export') {
            $qry = $query;
            $qry = $qry->select('tu.user_id as user_id','tu.fullname as fullname','tbl_user_setting_fund.amount','tbl_user_setting_fund.status','tbl_user_setting_fund.admin_remark','tbl_user_setting_fund.entry_time','tbl_user_setting_fund.ip_address');
            $records = $qry->get();
            $res = $records->toArray();
            if (count($res) <= 0) {
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', array());
            }
            $var = $this->commonController->exportToExcel($res,"AllUsers");
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Records found', array('data'=>$var));
        }


        $totalRecord   = $query->count('tbl_user_setting_fund.user_id');
        $query         = $query->orderBy('tbl_user_setting_fund.entry_time','desc');
        // $totalRecord   = $query->count();
        $arrFranchise      = $query->skip($arrInput['start'])->take($arrInput['length'])->get();

        $arrData['recordsTotal']    = $totalRecord;
        $arrData['recordsFiltered'] = $totalRecord;
        $arrData['records']         = $arrFranchise;

        if(count($arrFranchise) > 0){
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'],'Record found',$arrData);
        }else{
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'],'Record not found','');
        }
    }
}