<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Response as Response;
use App\User;
use Illuminate\Http\Request;
use App\Models\Dashboard;
use App\Models\CurrentAmountDetails;
use App\Models\TodayDetails;
use App\Models\LevelView;
use App\Models\Activitynotification;
use App\Models\Promotionals;
use App\Models\PromotionalSocialIncome;
use App\Models\PromotionalType;
use App\Models\QuesAns;
use App\Models\Packages;
use App\Models\Topup;
use App\Models\Rank;
use App\Models\UserStructureModel;
use App\Models\ProjectSettings;
use App\Models\DirectIncome;
use App\Traits\Income;
use Illuminate\Support\Facades\Auth;
use DB;
use bcrypt;
use Config;
use App\Models\supermatching;

trait Users {
   
     /**
     * check user field exist
     *
     * @return \Illuminate\Http\Response
     */
    public function checkSpecificUserData($arrData) {
        $arrSpecificUsers=''; 
        try{
           $arrSpecificUsers = User::select('id','user_id')->where('type','')->where($arrData)->first();
           return $arrSpecificUsers;
        }catch(Exception $e){
             return $arrSpecificUsers;
        }
    }

    /**
     * Level plan
     *
     * @return \Illuminate\Http\Response
     */
     public function levelPlan(Request $request) {

       try{
            $random_token = md5(uniqid(rand(), true));
            $password = $request->input('password');
            $confirm_password = $request->input('password_confirmation');
            $user_id = trim($request->input('user_id'));

            if (!empty($request->input('mobile'))) {
                $mobile = $request->input('mobile');
            } else {
                $mobile = 0;
            }
            $fullname = $request->input('fullname');
            $country = $request->input('country');

            $refid = $request->input('ref_user_id');
            $email = $request->input('email');
            if ($refid != '') {
                $checksponser = User::where([['user_id', '=', $refid], ['status', '=', 'Active']])->first();
                // dd($checksponser);
                if (!empty($checksponser)) {
                    $sponserId = $checksponser->id;
                } else {
                   
                   $arrStatus   = Response::HTTP_NOT_FOUND;
                   $arrCode     = Response::$statusTexts[$arrStatus];
                   $arrMessage  = 'Sponser/Referrer is not exist'; 
                   return sendResponse($arrStatus,$arrCode,$arrMessage,'');

                }
            } else {
                $sponserId = '1';
            }
            $partiallyexist = User::where([['user_id', '=', $request->Input('user_id')], ['verifyaccountstatus', '=', '1'], ['status', '=', 'Inactive']])->first();

            $insertdata = new User;
            $insertdata->ref_user_id = $sponserId;
            $insertdata->email = $email;
            $insertdata->password = encrypt($password);
            $insertdata->remember_token = $random_token;
            $insertdata->user_id = $user_id;
            $insertdata->fullname = $fullname;
            $insertdata->mobile = $mobile;
            $insertdata->country = $country;
            $insertdata->status = 'Active';
            $insertdata->entry_time = $this->today;
            $insertdata->bcrypt_password = bcrypt($password);
            $insertdata->save();
            $insertid = $insertdata->id;
            $dashdata = new Dashboard;
            $dashdata->id = $insertid;
            $dashdata->save();
            //=========insert level view================================
            if ($sponserId != '0') {
                $this->levelView($sponserId, $insertid, 1);
            }

            $subject = "Congratulations! Your registration is successful";
            $pagename = "emails.registration";
            $data = array('pagename' => $pagename, 'email' => $request->input('email'), 'username' => $user_id,'password' => $password);
            $email = $email;
            $mail = sendMail($data, $email, $subject);

                          $whatsappMsg = "Congratulations, you have successfully completed the registration procedure of Dollar Device.\nYour user id is -: " . $insertdata->user_id . "\nPassword -: " . $password . "\nFor any queries contact +919604819152";
                          
                          
              $countrycode = getCountryCode($country);
                
              
              //sendWhatsappMsg($countrycode, $mobile, $whatsappMsg);

              sendSMS($mobile, $whatsappMsg);
        

              $arrStatus   = Response::HTTP_OK;
              $arrCode     = Response::$statusTexts[$arrStatus];
              $arrMessage  = 'User registered successfully'; 
            return sendResponse($arrStatus,$arrCode,$arrMessage,(object) array('userid' => $user_id));
        }catch(Exception $e){

           $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
           $arrCode     = Response::$statusTexts[$arrStatus];
           $arrMessage  = 'Something went wrong,Please try again'; 
           return sendResponse($arrStatus,$arrCode,$arrMessage,'');
        }    
    } 

    
    /**
     * [Binary plan description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
  
    
    public function getUniqueUserId()
    {
        $radUserId = substr(number_format(time() * rand(), 0, '', ''), 0, '7');

        $user_id = 'X'.$radUserId; 

        $RfCount1 = User::select('id')->where([['user_id', '=', $user_id]])->count('id');
        if ($RfCount1 == 0) {
            return $user_id;
        }
        else{
            $this->getUniqueUserId();
        }


    }

    public function binaryPlanRegistrationnew(Request $value,$no_structure,$ibulkentry)
    {

        list($usec, $sec) = explode(" ", microtime());

                            $time_start = ((float)$usec + (float)$sec);

           // $randomid = mt_rand(100000,999999); 


           // $newID = 'DX'.$randomid; 

            $ref_user_id = $value->user_id;
            $user_id = $this->getUniqueUserId();


            /*$amount_topup = $value->amount_topup;*/
         
            $arrInput = $value->all();
           
            if((isset($arrInput['placement_user_id'])) && (!empty($arrInput['placement_user_id']))){
                $placement_user_id = $arrInput['placement_user_id'];
            }
            else {
                $placement_user_id = $ref_user_id;
                $getUserDetails = User::select('id','ref_user_id','virtual_parent_id')->where([['id', '=', $placement_user_id]])->get();
                $getRefDetails = User::select('id','ref_user_id','virtual_parent_id')->where([['id', '=', $ref_user_id]])->get();
            }

            if (count($getUserDetails) > 0 && count($getRefDetails) > 0) {
                
                $flag = 0;
                $place_id = $getUserDetails[0]->id;   // user auto id
                $reference_id = $getRefDetails[0]->id;          // user reference auto id
                if ($place_id == $reference_id) {
                    $flag = 1;
                }
                if((isset($arrInput['placement_user_id'])) && (!empty($arrInput['placement_user_id']))){
                    if ($flag == 0) {
                        $q2 = TodayDetails::select('to_user_id','from_user_id')->where([['to_user_id', '=', $reference_id], ['from_user_id', '=', $place_id]])->get();
                        if (count($q2) > 0) {
                            $flag = 1;
                        }
                    }
                }
                else
                {
                    $flag = 1;
                }
               
               
            } else {
                echo "Sponser not exist";
            }

            if ($flag == 1) {

                $RfCount1 = User::select('id','user_id','ref_user_id','virtual_parent_id')->where([['user_id', '=', $user_id]])->get();
                if (count($RfCount1) == 0) {

                   // $RefDetails = User::select('id','user_id','ref_user_id','virtual_parent_id')->where([['id', '=', $ref_user_id]])->get();
                    
                   // if (count($RefDetails) > 0) {
                    
                        $virtual_parent_id = $reference_id;
                        /*$position = $position;*/
                        
                        $RfCount2 = User::select('id','user_id','ref_user_id','virtual_parent_id')->where([['id', '=', $placement_user_id]])->get();
                        
                        if (count($RfCount2) > 0) {

                            $random_token = md5(uniqid(rand(), true));

                            if (!empty($value->mobile)) {
                                $mobile = $value->mobile;
                            } else {
                                $mobile = 0;
                            }
                         
                           
                            // find virtual parent id and position
                            // case 1 - starting no ids
                            $findData = DB::table('tbl_users')
                             ->select('id','l_c_count','r_c_count')
                             ->where('id', '=', $place_id)
                             ->get();
                          
                    if($findData[0]->l_c_count == 0 )
                    {
                        $virtual_parent_id=$place_id;
                        $position=1;
                    }
                    else if($findData[0]->r_c_count == 0)
                    {
                        $virtual_parent_id=$place_id;
                        $position=2;
                    }
                    else
                    {
                       // $place_id=2000;
                        // find from today details
                        list($usec, $sec) = explode(" ", microtime());

                        $time_start = ((float)$usec + (float)$sec);

                           /* $isExistUser = DB::table('tbl_today_details')
                                ->select('from_user_id','from_user_id_l_c_count','from_user_id_r_c_count')
                                ->where('to_user_id','=',$place_id)
                                ->where(function($q) {
                                $q->where('from_user_id_l_c_count', '=', 0)
                                ->orWhere('from_user_id_r_c_count', '=', 0);
                                })
                            ->orderBy('level', 'ASC')
                            ->orderBy('position', 'ASC')
                            ->orderBy('entry_time', 'ASC')
                            ->limit('1')
                            ->get();*/

                            /*$isExistUser = TodayDetails::select('from_user_id','from_user_id_l_c_count','from_user_id_r_c_count')
                            ->where([['to_user_id', '=', $place_id]])
                            ->where(function($q) {
                                 $q->where('from_user_id_l_c_count', '=', 0)
                                 ->orWhere('from_user_id_r_c_count', '=', 0);
                                 })
                            ->orderBy('level', 'ASC')
                            ->orderBy('position', 'ASC')
                            ->orderBy('entry_time', 'ASC')
                            ->limit('1')
                            ->get();*/
                            $isExistUser =   User::select('tbl_users.id','tbl_users.l_c_count','tbl_users.r_c_count')
                            ->join('tbl_today_details as b','b.from_user_id', '=','tbl_users.id')
                            ->where('b.to_user_id','=',$place_id)
                            ->where(function($q) {
                                $q->where('tbl_users.l_c_count', '=', 0)
                                ->orWhere('tbl_users.r_c_count', '=', 0);
                            })
                            ->orderBy('b.level', 'ASC')
                            ->orderBy('b.position', 'ASC')
                            ->orderBy('b.entry_time', 'ASC')
                            ->first();

                            list($usec, $sec) = explode(" ", microtime());
                    $time_end = ((float)$usec + (float)$sec);
                       $time = $time_end - $time_start;
                       echo "to find vpid  time ".$time."\n";
                         

                            if($isExistUser->l_c_count == 0 )
                    {
                        $virtual_parent_id=$isExistUser->id;
                        $position=1;
                    }
                    else if($isExistUser->r_c_count == 0 )
                    {
                        $virtual_parent_id=$isExistUser->id;
                        $position=2;
                    }
                    else
                    {
                        dd("no vpid found");
                    }


                    

                    }

                    // if already placed in vpid and position then give error
                    $placeCount = User::select('id')->where([['virtual_parent_id', '=', $virtual_parent_id],['position', '=', $position]])->count('id');
                    if ($placeCount > 0) {
                                            $data3['status'] = '2';
                                            DB::table('tbl_user_structure')
                                            ->where('id','=', $value->id)
                                            ->update($data3);
                                                dd("Already placed at this position(".$position.") and vpid(".$virtual_parent_id.") this structure will not complete there is some error please solve first");
                                       }

                            $packageExist = Packages::first();
                              
                            $pacakgeId = $packageExist->id;
                           
                            $ProductRoi = $packageExist->roi;
                            $ProductDuration = $packageExist->duration;
                            $Productcost = $packageExist->cost;
                            $direct_income = $packageExist->direct_income;
                            $amount = $packageExist->cost;
                            //-----insert user data--------    

                            $ref_user_id = $virtual_parent_id;
                            $position_direct_business = $position;
                            $insertdata = new User;
                            $insertdata->ref_user_id = $virtual_parent_id;

                            $insertdata->email = $value->email;
                            $insertdata->unique_user_id = $user_id;
                          /*  $insertdata->password = $value->password;*/
                            $insertdata->remember_token = $random_token;
                            $insertdata->user_id = $user_id;
                            $insertdata->fullname = $value->fullname;
                            $insertdata->mobile = $mobile;
                            $insertdata->country = $value->country;
                            $insertdata->position = $position;
                            $insertdata->status = 'Active';
                            $insertdata->topup_status = "0";
                            $insertdata->amount = $amount;
                            $insertdata->reg_mail_status = 1;
                            $insertdata->password = $value->password;
                            $insertdata->bcrypt_password = bcrypt($value->password);
                            $insertdata->virtual_parent_id = $virtual_parent_id;
                            /*$insertdata->structure_id = $value->id;*/
                            $insertdata->save();
                            $last = $insertdata->id;

                            // topup functionality

                            /* $packageExist = Packages::where('cost', $amount_topup)->first();*/
                            

                            $random = substr(number_format(time() * rand(), 0, '', ''), 0, '15');
                            $transction_type = 1;/*$value->transaction_type;*/
                            $topupfrom = "";
                            if($transction_type == 1)
                            {
                                $topupfrom = "Purchase wallet (max 50%) + New Fund Wallet";
                            }
                            if($transction_type == 2)
                            {
                                $topupfrom = "New Fund Wallet";
                            }
                            $Topupdata = array();
                            $Topupdata['id'] = $last;
                            $Topupdata['pin'] = $random;
                            $Topupdata['amount'] = $amount;
                            $Topupdata['percentage'] = 0;
                            $Topupdata['type'] = $pacakgeId;
                            $Topupdata['top_up_by'] = $place_id;
                            $Topupdata['usd_rate'] = '0';
                            $Topupdata['topupfrom'] = $topupfrom;
                            $Topupdata['roi_status'] = 'Active';
                            $Topupdata['top_up_type'] = 3;
                            $Topupdata['binary_pass_status'] = '1';
                            $Topupdata['total_usd'] = 0.001;
                           

                            /*$storeId = Topup::insertGetId($Topupdata);*/

                            $dashdata = new Dashboard;
                            /*$dashdata->usd = $Productcost;
                            $dashdata->total_investment = $Productcost;
                            $dashdata->active_investment = $Productcost;*/
                            $dashdata->id = $last;
                            $dashdata->save();
                         
                            $virtual_parent_id1 = $last;
                            $from_user_id_for_today_count = $last;
                            $i = 0;
                            //-------update user count  & binary business -------
                            $loopOn1 = true;
                            $todaydetails_data = array();
                            $left_users = array();
                            $right_users = array();
                            if ($virtual_parent_id1 > 0) {
                                do {
                                    $posDetails = User::select('id','virtual_parent_id','position')->where([['id', '=', $virtual_parent_id1]])->get();
                                    if (count($posDetails) <= 0) {

                                        $loopOn1 = false;
                                    } else {

                                        foreach ($posDetails as $k => $v) {

                                            $virtual_parent_id1 = $posDetails[$k]->virtual_parent_id;
                                            if ($virtual_parent_id1 > 0) {
                                                $position = $posDetails[$k]->position;
                                                if ($last != $virtual_parent_id1) {
                                                    if ($position == 1) {

                                                        array_push($left_users, $virtual_parent_id1);

                                                      
                                                    }
                                                    if ($position == 2) {
                                                        array_push($right_users, $virtual_parent_id1);

                                                       
                                                    }

                                                     $Todaydata = array(); // new TodayDetails;
                                                    $Todaydata['to_user_id'] = $virtual_parent_id1;
                                                    $Todaydata['from_user_id'] = $from_user_id_for_today_count;
                                                    $Todaydata['entry_time'] = date("Y-m-d H:i:s");
                                                    $Todaydata['position'] = $position;
                                                    $Todaydata['level'] = $i + 1;
                                                    array_push($todaydetails_data, $Todaydata);
                                                       $i++;
                                                   

                                                }
                                                $loopOn1 = false;
                                            } else {
                                                $loopOn1 = false;
                                            }
                                        }
                                    }
                                } while ($loopOn1 == true);

                                //exit;
                            }
                            //-------------------- bulk entery 

                           // bulk todays data from today details table

                           $todaysDetails = TodayDetails::select('to_user_id','position','level')->where([['from_user_id', '=', $virtual_parent_id1]])->get();
                                    if (count($todaysDetails) > 0) {

                                        foreach ($todaysDetails as $k => $v) {

                                            $virtual_parent_id1 = $todaysDetails[$k]->to_user_id;
                                            if ($virtual_parent_id1 > 0) {
                                                $position = $todaysDetails[$k]->position;
                                                if ($last != $virtual_parent_id1) {
                                                    if ($position == 1) {

                                                        array_push($left_users, $virtual_parent_id1);

                                                      
                                                    }
                                                    if ($position == 2) {
                                                        array_push($right_users, $virtual_parent_id1);

                                                       
                                                    }

                                                     $Todaydata = array(); // new TodayDetails;
                                                    $Todaydata['to_user_id'] = $virtual_parent_id1;
                                                    $Todaydata['from_user_id'] = $from_user_id_for_today_count;
                                                    $Todaydata['entry_time'] = date("Y-m-d H:i:s");
                                                    $Todaydata['position'] = $position;
                                                    $Todaydata['level'] = $todaysDetails[$k]->level + 1;
                                                    array_push($todaydetails_data, $Todaydata);
                    
                                                }
                                            } 
                                        }
                                    }



                            $count = 1;
                            $array = array_chunk($todaydetails_data, 1000);
                          
                            while ($count <= count($array)) {
                                 $key = $count - 1;
                             
                               TodayDetails::insert($array[$key]);
                                // echo $count." count array ".count($array[$key])."\n";
                                $count++;

                               
                            }

        
                            $updateLCountArr = array();
                            $updateLCountArr['l_c_count'] = DB::raw('l_c_count + 1');
                            /*$updateLCountArr['l_bv'] = DB::raw('l_bv + ' . $amount . '');
                            $updateLCountArr['curr_l_bv'] = DB::raw('curr_l_bv + ' . $amount . '');*/
                           
                            

                            $updateRCountArr = array();
                            $updateRCountArr['r_c_count'] = DB::raw('r_c_count + 1');
                            /*$updateRCountArr['r_bv'] = DB::raw('r_bv + ' . $amount . '');
                            $updateRCountArr['curr_r_bv'] = DB::raw('curr_r_bv + ' . $amount . '');*/

                            // for curr amount details

                           /* $updateLAmountArr = array();
                            $updateLAmountArr['left_bv'] = DB::raw('left_bv + ' . $amount . '');
                            
                         

                            $updateRAmountArr = array();
                            $updateRAmountArr['right_bv'] = DB::raw('right_bv + ' . $amount . '');*/

                            // for today details

                            // $updateLCountArrToday = array();
                            // $updateLCountArrToday['from_user_id_l_c_count'] = DB::raw('from_user_id_l_c_count + 1');
                            

                            // $updateRCountArrToday = array();
                            // $updateRCountArrToday['from_user_id_r_c_count'] = DB::raw('from_user_id_r_c_count + 1');

                            // Update count
                            $count1 = 1;
                            $array1 = array_chunk($left_users, 1000);
                           // dd($updateLCountArr);

                           list($usec, $sec) = explode(" ", microtime());

                           $time_start = ((float)$usec + (float)$sec);

                            while ($count1 <= count($array1)) {
                                //dd($array1);
                                $key1 = $count1 - 1;
                               // dd($array1[$key1],$updateLCountArr);
                              // DB::enableQueryLog();
                                User::whereIn('id', $array1[$key1])->update($updateLCountArr);
                               // dd(DB::getQueryLog());
                               // CurrentAmountDetails::whereIn('user_id', $array1[$key1])->update($updateLAmountArr);
                               // TodayDetails::whereIn('from_user_id', $array1[$key1])->update($updateLCountArrToday);   
                                $count1++;
                            }

                            $count2 = 1;
                            $array2 = array_chunk($right_users, 1000);
                            while ($count2 <= count($array2)) {
                                $key2 = $count2 - 1;
                                User::whereIn('id', $array2[$key2])->update($updateRCountArr);
                               // CurrentAmountDetails::whereIn('user_id', $array2[$key2])->update($updateRAmountArr);
                               // TodayDetails::whereIn('from_user_id', $array2[$key2])->update($updateRCountArrToday);   
                                $count2++;
                            }

                            list($usec, $sec) = explode(" ", microtime());
                            $time_end = ((float)$usec + (float)$sec);
                               $time = $time_end - $time_start;
                               echo "to update count and bv  time ".$time."\n";

                            // update direct business

                            $updateLCountArrDirectBusiness = array();
                            $updateLCountArrDirectBusiness['power_l_bv'] = DB::raw('power_l_bv + ' . $amount . '');
                            
                            

                            $updateRCountArrDirectBusiness = array();
                            $updateRCountArrDirectBusiness['power_r_bv'] = DB::raw('power_r_bv + ' . $amount . '');

                          if($position_direct_business == 1)
                          {
                           // User::where('id', $ref_user_id)->update($updateLCountArrDirectBusiness);

                          }else if($position_direct_business == 2)
                          {
                           // User::where('id', $ref_user_id)->update($updateRCountArrDirectBusiness);

                          }

                           
                            // check rank of vpid 

                            // $this->check_rank_vpid($ref_user_id);

                             // check rank for direct user to give direct income
                             
                             
                            // $this->check_rank($ref_user_id);
                               
                                 // no need to use this function to pass bv 
                                 //    $this->pay_binarybulk($last, $amount);

                               
                            //        $this->pay_directbulk($last, $amount, $direct_income, $random, $ref_user_id, $user_id);

                                 
                                    list($usec, $sec) = explode(" ", microtime());
                                $time_end = ((float)$usec + (float)$sec);
                                   $time = $time_end - $time_start;
                                   echo "after direct income time ".$time."\n";
                             

                   /* $string = $last.s->now();*/
                     $this->info($ibulkentry." Bulk Entry of structure id ".$value->id." Inserted At ".date("Y-m-d H:i:s"));
                   
                        }
                   // }
                }
            }
       
    }

    public function binaryPlanRegistration($value)
    {
        //dd('in if condition binaryPlanRegistration');
        dd($value);
        DB::beginTransaction();
        try {

            $randomid = mt_rand(100000,999999); 
            $newID = 'DX'.$randomid; 

            $ref_user_id = $value->user_id;
            $user_id = $newID;
            $amount_topup = $value->amount_topup;
            

            /*$arrInput = $request->all();
            $uniqueid =  $request->input('user_id');
            $user_id = $request->input('user_id');
            $ref_user_id = $request->input('ref_user_id');
             $country = $request->input('country');*/
            //$placement_user_id = $ref_user_id;
            if((isset($arrInput['placement_user_id'])) && (!empty($arrInput['placement_user_id'])))
                $placement_user_id = $arrInput['placement_user_id'];
            else 
                $placement_user_id = $ref_user_id;
                $getUserDetails = User::where([['id', '=', $placement_user_id]])->get();
                $getRefDetails = User::where([['id', '=', $ref_user_id]])->get();
            
            if (count($getUserDetails) > 0 && count($getRefDetails) > 0) {
                
                $flag = 0;
                $place_id = $getUserDetails[0]->id;   // user auto id
                $reference_id = $getRefDetails[0]->id;          // user reference auto id
                if ($flag == 0) {
                    $q2 = TodayDetails::where([['to_user_id', '=', $reference_id], ['from_user_id', '=', $place_id]])->get();
                    if (count($q2) > 0) {
                        $flag = 1;
                    }
                }
                if ($place_id == $reference_id) {
                    $flag = 1;
                }
            } else {
                echo "Sponser not exist";
            }

            if ($flag == 1) {
                $RfCount1 = User::where([['user_id', '=', $user_id]])->get();
                if (count($RfCount1) == 0) {
                    $RefDetails = User::where([['user_id', '=', $ref_user_id]])->get();
                    if (count($RefDetails) > 0) {
                        $virtual_parent_id = $RefDetails[0]->id;
                        $position = $request->input('position');
                        $RfCount2 = User::where([['user_id', '=', $placement_user_id]])->get();
                        if (count($RfCount2) > 0) {
                            $random_token = md5(uniqid(rand(), true));
                            if (!empty($request->input('mobile'))) {
                                $mobile = $request->input('mobile');
                            } else {
                                $mobile = 0;
                            }
                            /*$pinAmount = Pins::join('tbl_product as tp', 'tp.id', '=', 'tbl_pins.product_id')->select('tp.cost')->where('tbl_pins.id', '=', $pinid)->first();*/
                            //-----insert user data--------   
            $usersleft = DB::table('tbl_users')
                    ->select('id')
                    ->where('ref_user_id', '=', $ref_user_id)
                    ->where('position', '=', 2)
                    ->count();

                    dd($usersleft);
            $usersright = DB::table('tbl_users')
                    ->select('id')
                    ->where('ref_user_id', '=', $ref_user_id)
                    ->where('position', '=', 1)
                    ->count();
                    dd($usersleft);
                    
                            $insertdata = new User;
                            $insertdata->ref_user_id = $virtual_parent_id;
                            $insertdata->email = $request->input('email');
                            $insertdata->unique_user_id = $uniqueid;
                            $insertdata->password = encrypt($request->input('password'));
                            $insertdata->remember_token = $random_token;
                            $insertdata->user_id = trim($request->input('user_id'));
                            $insertdata->fullname = $request->input('fullname');
                            $insertdata->mobile = $mobile;
                            $insertdata->country = $country;
                            $insertdata->position = $request->input('position');
                            //$insertdata->pin_number = $request->input('pin_number');
                            $insertdata->status = 'Active';
                            $insertdata->bcrypt_password = bcrypt($request->input('password'));
                            $insertdata->tr_passwd = bcrypt($request->input('transcation_password'));
                            $insertdata->entry_time = $this->today;
                        
                            $insertdata->btc_address = $request->input('btc_address');
                           
                            if ($request->input('address_type') == 'BTC') {
                                $insertdata->btc_address = $request->input('address');
                            } else if ($request->input('address_type') == 'ETH') {
                                $insertdata->ethereum = $request->input('address');
                            }
                            $insertdata->save();
                            $last = $insertdata->id;
                            //Save Ques Ans
                          $qa = new QuesAns;
                          $qa->user_id = $insertdata->id;
                          $qa->secret_que = $request->secret_question;
                          $qa->secret_ans = $request->secret_ans;
                          $qa->save();

                            $dashdata = new Dashboard;
                            $dashdata->id = $last;
                            $dashdata->save();

                            $packageExist = Packages::where([['id', '=', $request->Input('product_id')]])->first();
                            

                                $pacakgeId = $packageExist->id;
                                $ProductRoi = $packageExist->roi;
                                $ProductDuration = $packageExist->duration;
                                $Productcost = $packageExist->cost;
                                $direct_income = $packageExist->direct_income;
                                $amount = $packageExist->cost;


                            $random = substr(number_format(time() * rand(), 0, '', ''), 0, '15');
                            $Topupdata = array();
                            $Topupdata['id'] = $last;
                            $Topupdata['pin'] = $random;
                            $Topupdata['amount'] = $amount;
                            $Topupdata['percentage'] = 0;
                            $Topupdata['type'] = $pacakgeId;
                            $Topupdata['top_up_by'] = $place_id;
                            $Topupdata['usd_rate'] = '0';
                            $Topupdata['topupfrom'] = 'Purchase Wallet';
                            $Topupdata['roi_status'] = 'Active';
                            $Topupdata['top_up_type'] = 3;
                            $Topupdata['binary_pass_status'] = '1';
                            $Topupdata['total_usd'] = 0.00001;
                            $Topupdata['entry_time'] = $this->today;

                            $storeId = Topup::insertGetId($Topupdata);


                            $users = User::join('tbl_dashboard', 'tbl_dashboard.id', '=', 'tbl_users.id')->where([['tbl_users.id', '=', $last], ['tbl_users.status', '=', 'Active']])->first(); 

                            
                            $updateCoinData = array();
                                $updateCoinData['usd'] = round(($users->usd - $Productcost), 7);
                                
                                $updateCoinData['total_withdraw'] = round(($users->total_withdraw + $Productcost), 7);
                                
                                $updateCoinData['total_investment'] = round(($users->total_investment + $Productcost), 7);
                                $updateCoinData['active_investment'] = round(($users->active_investment + $Productcost), 7);
                                

                                $updateCoinData = Dashboard::where('id', $users->id)->update($updateCoinData);


                            //----update vetual parent id
                            $virtual_parent_id = $RfCount2[0]->id;
                            $loopOn = true;
                            do {
                                $posDetails = User::where([['virtual_parent_id', '=', $virtual_parent_id], ['position', '=', $position]])->get();
                                if (count($posDetails) <= 0) {
                                    if ($last != $virtual_parent_id) {
                                        $loopOn = false;
                                        $updateData = array();
                                        $updateData['virtual_parent_id'] = $virtual_parent_id; //1 -verify otp
                                        $updateOtpSta = User::where('user_id', $user_id)->update($updateData);
                                    }
                                } else {

                                    $virtual_parent_id = $posDetails[0]->id;
                                }
                            } while ($loopOn == true);

                            $virtual_parent_id1 = $last;
                            $from_user_id_for_today_count = $last;
                            $i = 0;
                            //-------update user binary count-------
                            $loopOn1 = true;
                            $todaydetails_data = array();
                            $left_users = array();
                            $right_users = array();
                            if ($virtual_parent_id1 > 0) {
                                do {
                                    $posDetails = User::where([['id', '=', $virtual_parent_id1]])->get();
                                    if (count($posDetails) <= 0) {

                                        $loopOn1 = false;
                                    } else {

                                        foreach ($posDetails as $k => $v) {

                                            $virtual_parent_id1 = $posDetails[$k]->virtual_parent_id;
                                            if ($virtual_parent_id1 > 0) {
                                                $position = $posDetails[$k]->position;
                                                if ($last != $virtual_parent_id1) {
                                                    if ($position == 1) {

                                                        array_push($left_users, $virtual_parent_id1);

                                                       // $updateOtpSta1 = User::where('id', $virtual_parent_id1)->update(array('l_c_count' => DB::raw('l_c_count + 1')));
                                                    }
                                                    if ($position == 2) {
                                                        array_push($right_users, $virtual_parent_id1);

                                                       // $updateOtpSta1 = User::where('id', $virtual_parent_id1)->update(array('r_c_count' => DB::raw('r_c_count + 1')));
                                                    }

                                                     $Todaydata = array(); // new TodayDetails;
                                                    $Todaydata['to_user_id'] = $virtual_parent_id1;
                                                    $Todaydata['from_user_id'] = $from_user_id_for_today_count;
                                                    $Todaydata['entry_time'] = date("Y-m-d H:i:s");
                                                    $Todaydata['position'] = $position;
                                                    $Todaydata['level'] = $i + 1;
                                                    array_push($todaydetails_data, $Todaydata);
                                                       $i++;
                                                    /*$Todaydata = new TodayDetails;
                                                    $Todaydata->to_user_id = $virtual_parent_id1;
                                                    $Todaydata->from_user_id = $from_user_id_for_today_count;
                                                    $Todaydata->entry_time = date("Y-m-d H:i:s");
                                                    $Todaydata->position = $position;
                                                    $Todaydata->level = $i + 1;
                                                    $Todaydata->entry_time = $this->today;
                                                    $Todaydata->save();
                                                    $DataInsert = $Todaydata->id;
                                                    $i++;*/


                                                }
                                            } else {
                                                $loopOn1 = false;
                                            }
                                        }
                                    }
                                } while ($loopOn1 == true);
                                //exit;
                            }
                            //-------------------- bulk entery 


                            $count = 1;
                            $array = array_chunk($todaydetails_data, 1000);
                            //dd($array);
                            while ($count <= count($array)) {
                                $key = $count - 1;
                                TodayDetails::insert($array[$key]);
                                // echo $count." count array ".count($array[$key])."\n";
                                $count++;
                            }

                            $updateLCountArr = array();
                            $updateLCountArr['l_c_count'] = DB::raw('l_c_count + 1');

                            $updateRCountArr = array();
                            $updateRCountArr['r_c_count'] = DB::raw('r_c_count + 1');

                            // Update count
                            $count1 = 1;
                            $array1 = array_chunk($left_users, 1000);

                            while ($count1 <= count($array1)) {
                                //dd($array1);
                                $key1 = $count1 - 1;
                                User::whereIn('id', $array1[$key1])->update($updateLCountArr);
                                // echo $count." count array1 ".count($array1[$key1])."\n";
                                $count1++;
                            }

                            $count2 = 1;
                            $array2 = array_chunk($right_users, 1000);
                            while ($count2 <= count($array2)) {
                                $key2 = $count2 - 1;
                                User::whereIn('id', $array2[$key2])->update($updateRCountArr);
                                // echo $count2." count array ".count($array2[$key2])."\n";
                                $count2++;
                            }



                            //----------level view---------------
                            if ($virtual_parent_id != '0') {

                                $this->levelView($RefDetails[0]->id, $last, 1);
                                //$this->viewlevel->levelView($RefDetails[0]->id, $last, 1);
                            }

                            $updateData = array();
                            $updateData['virtual_parent_id'] = $virtual_parent_id;
                            $updateOtpSta = User::where('user_id', $user_id)->update($updateData);

                            /*$pinData = DB::table('tbl_pins AS pins')
                                    ->select('pins.id', 'pins.pin', 'pins.status', 'pins.used_by', 'pins.used_date', 'prod.cost', 'prod.id AS prod_id', 'prod.bvalue', 'prod.direct_income')
                                    ->where([
                                        ['pins.id', '=', $pinid]
                                    ])
                                    ->leftJoin('tbl_product AS prod', 'prod.id', '=', 'pins.product_id')
                                    ->where('prod.status_product', '=', 'Active')
                                    ->first();

                            $topupObj = new Topup;
                            $topupObj->user_id = $last;
                            $topupObj->pin = trim($pinData->pin);
                            $topupObj->amount = $pinData->cost;
                            $topupObj->bvalue = $pinData->bvalue;
                            $topupObj->type = $pinData->prod_id;
                            $topupObj->status = 'registration';
                            $topupObj->entry_time = $this->today;
                            $topupObj->save();

                            $updateData = array();
                            $updateData['status'] = 'Inactive';
                            $updateData['used_date'] = $this->today;
                            $updateData['used_by'] = $last;
                            $updateData['remark'] = 'Pin request while registered user :' . $last;
                            $updateOtpSta = Pins::where('pin', '=', $pinData->pin)->update($updateData);

                            $this->objBval_pass->add_b_val($last, $pinData->bvalue);
                            
                            $this->pay_direct($last, $pinData->cost, $pinData->direct_income, $pinData->pin);
                            */
                            $subject = "SignUp Complete!";
                            $pagename = "emails.registration";
                            $data = array('pagename' => $pagename,'email' =>$request->input('email'), 'username' =>$request->input('user_id') ,'password'=>$request->password);
                            $email =$request->input('email');
                            $mail = sendMail($data, $email, $subject);
                            if (!empty($request->input('mobile'))) {
                                $sms_msg = 'Congratulations! You have been registered. User Id: '. $request->input('email') . '. Password: '. $request->input('password').'. Regards Arbitude';
                                //sendSMS($request->input('mobile'), $sms_msg);
                           }
                        }
                    }
                }
            }
        } catch (PDOException $e) {
          dd($e);
            DB::rollBack();
        } catch (Exception $e) {
          dd($e);
            DB::rollBack();
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Something went wrong,Please try again', '');
        }
        DB::commit();
          $statuscode = Config::get('constants.statuscode');

        return sendresponse($statuscode[200]['code'], $statuscode[200]['status'], 'User registration and topup done successfully', (object) array('userid' => $user_id, 'sponsor_name' => $request->sponsor_name, 'password' => $request->input('password')));
        
    }

    public function binaryPlan(Request $request, $pinid = NULL) {
        
        DB::beginTransaction();
        try {
            /*dd($request);*/
            $arrInput = $request->all();
            $uniqueid =  $request->input('user_id');
            $user_id = $request->input('user_id');
            $ref_user_id = $request->input('ref_user_id');
            $country = $request->input('country');
            //$placement_user_id = $ref_user_id;
            if((isset($arrInput['placement_user_id'])) && (!empty($arrInput['placement_user_id'])))
                $placement_user_id = $arrInput['placement_user_id'];
            else 
                $placement_user_id = $ref_user_id;
            $getUserDetails = User::select('id')->where([['user_id', '=', $placement_user_id]])->get();
            $getRefDetails = User::select('id')->where([['user_id', '=', $ref_user_id]])->get();
            if (count($getUserDetails) > 0 && count($getRefDetails) > 0) {
                $flag = 0;
                $place_id = $getUserDetails[0]->id;   // user auto id
                $reference_id = $getRefDetails[0]->id;          // user reference auto id
                if ($flag == 0) {
                    $q2 = TodayDetails::select('to_user_id')->where([['to_user_id', '=', $reference_id], ['from_user_id', '=', $place_id]])->count();
                    if ($q2 > 0) {
                        $flag = 1;
                    }
                }
                if ($place_id == $reference_id) {
                    $flag = 1;
                }
            } else {
                return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Sponser/Referrer is not exist', '');
            }

            if ($flag == 1) {
                $RfCount1 = User::select('user_id')->where([['user_id', '=', $user_id]])->count();
                if ($RfCount1 == 0) {
                    $RefDetails = User::select('id')->where([['user_id', '=', $ref_user_id]])->get();
                    if (count($RefDetails) > 0) {
                        $virtual_parent_id = $RefDetails[0]->id;
                        $position = $request->input('position');
                        $RfCount2 = User::select('id')->where([['user_id', '=', $placement_user_id]])->get();
                        if (count($RfCount2) > 0) {
                            $random_token = md5(uniqid(rand(), true));
                            if (!empty($request->input('mobile'))) {
                                $mobile = $request->input('mobile');
                            } else {
                                $mobile = 0;
                            }
                            /*$pinAmount = Pins::join('tbl_product as tp', 'tp.id', '=', 'tbl_pins.product_id')->select('tp.cost')->where('tbl_pins.id', '=', $pinid)->first();*/
                            //-----insert user data--------   
                            $insertdata = new User;
                            $insertdata->ref_user_id = $virtual_parent_id;
                            $insertdata->email = $request->input('email');
                            $insertdata->unique_user_id = $uniqueid;
                            $insertdata->password = encrypt($request->input('password'));
                            $insertdata->remember_token = $random_token;
                            $insertdata->user_id = trim($request->input('user_id'));
                            $insertdata->fullname = $request->input('fullname');
                            $insertdata->mobile = $mobile;
                            $insertdata->temp_pass = $request->password;
                            $insertdata->country = $country;
                            $insertdata->position = $request->input('position');
                            //$insertdata->pin_number = $request->input('pin_number');
                            $insertdata->status = 'Active';
                            $insertdata->bcrypt_password = bcrypt($request->input('password'));
                            $insertdata->tr_passwd = bcrypt($request->input('transcation_password'));
                            $insertdata->entry_time = $this->today;
                          /*  $insertdata->nominee_name = $request->input('nominee_name');
                            $insertdata->relation = $request->input('relation');
                            $insertdata->dob = $request->input('dob');
                            $insertdata->account_no = $request->input('account_no');
                            $insertdata->holder_name = $request->input('holder_name');
                            $insertdata->bank_name = $request->input('bank_name');
                            $insertdata->branch_name = $request->input('branch_name');
                            $insertdata->pan_no = $request->input('pan_no');
                            $insertdata->ifsc_code = $request->input('ifsc_code');
                            $insertdata->country = $request->input('country');
                            $insertdata->state = $request->input('state');
                            $insertdata->address = $request->input('address');
                            $insertdata->city = $request->input('city');*/
                            $insertdata->btc_address = $request->input('btc_address');
                            //$insertdata->amount = $pinAmount->cost;
                           // $insertdata->pincode = $request->input('pincode');

                            if ($request->input('address_type') == 'BTC') {
                                $insertdata->btc_address = $request->input('address');
                            } else if ($request->input('address_type') == 'ETH') {
                                $insertdata->ethereum = $request->input('address');
                            }
                            $insertdata->save();

                            $last = $insertdata->id;
                            //Save Ques Ans
                        /*  $qa = new QuesAns;
                          $qa->user_id = $insertdata->id;
                          $qa->secret_que = $request->secret_question;
                          $qa->secret_ans = $request->secret_ans;
                          $qa->save();*/

                            $dashdata = new Dashboard;
                            $dashdata->id = $last;
                            $dashdata->save();

                            //----update vetual parent id
                            $virtual_parent_id = $RfCount2[0]->id;
                            $loopOn = true;
                            do {
                                $posDetails = User::select('id')->where([['virtual_parent_id', '=', $virtual_parent_id], ['position', '=', $position]])->get();
                                if (count($posDetails) <= 0) {
                                    if ($last != $virtual_parent_id) {
                                        $loopOn = false;
                                        $updateData = array();
                                        $updateData['virtual_parent_id'] = $virtual_parent_id; //1 -verify otp
                                        $updateOtpSta = User::where('user_id', $user_id)->update($updateData);
                                    }
                                } else {

                                    $virtual_parent_id = $posDetails[0]->id;
                                }
                            } while ($loopOn == true);



                            $virtual_parent_id1 = $last;
                            $from_user_id_for_today_count = $last;
                            $i = 0;
                            //-------update user binary count-------
                            $loopOn1 = true;
                            $todaydetails_data = array();
                            $left_users = array();
                            $right_users = array();
                            if ($virtual_parent_id1 > 0) {
                                do {
                                    $posDetails = User::select('id','position','virtual_parent_id')->where([['id', '=', $virtual_parent_id1]])->get();
                                    if (count($posDetails) <= 0) {

                                        $loopOn1 = false;
                                    } else {

                                        foreach ($posDetails as $k => $v) {

                                            $virtual_parent_id1 = $posDetails[$k]->virtual_parent_id;
                                            if ($virtual_parent_id1 > 0) {
                                                $position = $posDetails[$k]->position;
                                                if ($last != $virtual_parent_id1) {
                                                    if ($position == 1) {

                                                        array_push($left_users, $virtual_parent_id1);

                                                       // $updateOtpSta1 = User::where('id', $virtual_parent_id1)->update(array('l_c_count' => DB::raw('l_c_count + 1')));
                                                    }
                                                    if ($position == 2) {
                                                        array_push($right_users, $virtual_parent_id1);

                                                       // $updateOtpSta1 = User::where('id', $virtual_parent_id1)->update(array('r_c_count' => DB::raw('r_c_count + 1')));
                                                    }

                                                    $Todaydata = array(); // new TodayDetails;
                                                    $Todaydata['to_user_id'] = $virtual_parent_id1;
                                                    $Todaydata['from_user_id'] = $from_user_id_for_today_count;
                                                    $Todaydata['entry_time'] = date("Y-m-d H:i:s");
                                                    $Todaydata['position'] = $position;
                                                    $Todaydata['level'] = $i + 1;
                                                    array_push($todaydetails_data, $Todaydata);
                                                       $i++;
                                                    /*$Todaydata = new TodayDetails;
                                                    $Todaydata->to_user_id = $virtual_parent_id1;
                                                    $Todaydata->from_user_id = $from_user_id_for_today_count;
                                                    $Todaydata->entry_time = date("Y-m-d H:i:s");
                                                    $Todaydata->position = $position;
                                                    $Todaydata->level = $i + 1;
                                                    $Todaydata->entry_time = $this->today;
                                                    $Todaydata->save();
                                                    $DataInsert = $Todaydata->id;
                                                    $i++;*/


                                                }
                                            } else {
                                                $loopOn1 = false;
                                            }
                                        }
                                    }
                                } while ($loopOn1 == true);
                                //exit;
                            }
                            //-------------------- bulk entery 


                            $count = 1;
                            $array = array_chunk($todaydetails_data, 1000);
                            //dd($array);
                            while ($count <= count($array)) {
                                $key = $count - 1;
                                TodayDetails::insert($array[$key]);
                                // echo $count." count array ".count($array[$key])."\n";
                                $count++;
                            }

                            $updateLCountArr = array();
                            $updateLCountArr['l_c_count'] = DB::raw('l_c_count + 1');

                            $updateRCountArr = array();
                            $updateRCountArr['r_c_count'] = DB::raw('r_c_count + 1');

                              // for today details

                            //   $updateLCountArrToday = array();
                            //   $updateLCountArrToday['from_user_id_l_c_count'] = DB::raw('from_user_id_l_c_count + 1');
                              
  
                            //   $updateRCountArrToday = array();
                            //   $updateRCountArrToday['from_user_id_r_c_count'] = DB::raw('from_user_id_r_c_count + 1');
  

                            // Update count
                            $count1 = 1;
                            $array1 = array_chunk($left_users, 1000);

                            while ($count1 <= count($array1)) {
                                //dd($array1);
                                $key1 = $count1 - 1;
                                User::whereIn('id', $array1[$key1])->update($updateLCountArr);
                                //TodayDetails::whereIn('from_user_id', $array1[$key1])->update($updateLCountArrToday);   
                        
                                // echo $count." count array1 ".count($array1[$key1])."\n";
                                $count1++;
                            }

                            $count2 = 1;
                            $array2 = array_chunk($right_users, 1000);
                            while ($count2 <= count($array2)) {
                                $key2 = $count2 - 1;
                                User::whereIn('id', $array2[$key2])->update($updateRCountArr);
                                //TodayDetails::whereIn('from_user_id', $array2[$key2])->update($updateRCountArrToday);   
                         
                                // echo $count2." count array ".count($array2[$key2])."\n";
                                $count2++;
                            }



                            //----------level view---------------
                          /*  if ($virtual_parent_id != '0') {

                                $this->levelView($RefDetails[0]->id, $last, 1);
                                //$this->viewlevel->levelView($RefDetails[0]->id, $last, 1);
                            }*/

                            $updateData = array();
                            $updateData['virtual_parent_id'] = $virtual_parent_id;
                            $updateOtpSta = User::where('user_id', $user_id)->update($updateData);

                            /*$pinData = DB::table('tbl_pins AS pins')
                                    ->select('pins.id', 'pins.pin', 'pins.status', 'pins.used_by', 'pins.used_date', 'prod.cost', 'prod.id AS prod_id', 'prod.bvalue', 'prod.direct_income')
                                    ->where([
                                        ['pins.id', '=', $pinid]
                                    ])
                                    ->leftJoin('tbl_product AS prod', 'prod.id', '=', 'pins.product_id')
                                    ->where('prod.status_product', '=', 'Active')
                                    ->first();

                            $topupObj = new Topup;
                            $topupObj->user_id = $last;
                            $topupObj->pin = trim($pinData->pin);
                            $topupObj->amount = $pinData->cost;
                            $topupObj->bvalue = $pinData->bvalue;
                            $topupObj->type = $pinData->prod_id;
                            $topupObj->status = 'registration';
                            $topupObj->entry_time = $this->today;
                            $topupObj->save();

                            $updateData = array();
                            $updateData['status'] = 'Inactive';
                            $updateData['used_date'] = $this->today;
                            $updateData['used_by'] = $last;
                            $updateData['remark'] = 'Pin request while registered user :' . $last;
                            $updateOtpSta = Pins::where('pin', '=', $pinData->pin)->update($updateData);

                            $this->objBval_pass->add_b_val($last, $pinData->bvalue);
                            
                            $this->pay_direct($last, $pinData->cost, $pinData->direct_income, $pinData->pin);
                            */
                            $subject = "SignUp Complete!";
                            $pagename = "emails.registration";
                            $data = array('pagename' => $pagename,'email' =>$request->input('email'), 'username' =>$request->input('user_id') ,'password'=>$request->password);
                            $email =$request->input('email');
                            // $mail = sendMail($data, $email, $subject);
                            if (!empty($request->input('mobile'))) {
                                $sms_msg = 'Congratulations! You have been registered. User Id: '. $request->input('email') . '. Password: '. $request->input('password').'. Regards Arbitude';
                                //sendSMS($request->input('mobile'), $sms_msg);
                           }
                        }
                    }
                }
            }
        } catch (PDOException $e) {
          dd($e);
            DB::rollBack();
        } catch (Exception $e) {
          dd($e);
            DB::rollBack();
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Something went wrong,Please try again', '');
        }
        DB::commit();
          $statuscode = Config::get('constants.statuscode');

        return sendresponse($statuscode[200]['code'], $statuscode[200]['status'], 'User registered successfully', (object) array('userid' => $user_id, 'sponsor_name' => $request->sponsor_name, 'password' => $request->input('password')));
        
    }
   



    /**
     * insert levels view of users
     *
     * @return void
     */
   
    public function levelView($ref_user_id,$user_id,$level_id) {  
       
      $arrOutput=[]; 
      try{
        $finduser = User::where([['id','=',$ref_user_id]])->first();
        $next_ref_user_id = $finduser->ref_user_id;
         if(($ref_user_id > 0))  {
           $regUser = User::where([['id','=',$user_id]])->first();   
           $entryTime = $regUser->entry_time;    
              // insert data in level view table
              $insertdata=new LevelView;
              $insertdata->id=$ref_user_id;
              $insertdata->level=$level_id;
              $insertdata->down_id=$user_id;
              $insertdata->entry_time=$entryTime;
              
              $insertdata->save();
              //=================insert acitvity notification=============================

              $up_user=User::where([['id','=',$ref_user_id]])->pluck('user_id')->first();
              $down_user=User::where([['id','=',$user_id]])->pluck('user_id')->first();

              $actdata=array();
              $actdata['id']=$ref_user_id;
              $actdata['message']='User  '.$up_user.'  has been added to your team as level  '.$level_id;
              $actdata['status']=1;
              $actDta=Activitynotification::create($actdata);
           }
   
         if(($next_ref_user_id > 0)){
            $level_id = $level_id + 1;      
            $this->levelView($next_ref_user_id,$user_id,$level_id);
         } else {
          return 1;
        }

      }catch(Exception $e){
             
            return $arrOutput;
      } 
    }

    public function PerPromotionalIncome($promotionalId){

        try{
            //fetch data who has status = approved and paid status  = unpaid and entry time between from date and to date and user id, promotional type wise
            $arrPromotionals = Promotionals::selectRaw('tbl_promotionals.srno,tbl_promotionals.id,tbl_promotionals.promotional_type_id,tpt.promotional_cost,tpt.require_count,tpt.duration')
                                ->join('tbl_promotional_type as tpt','tpt.srno','=','tbl_promotionals.promotional_type_id')
                                ->where('tbl_promotionals.status','approved')
                                ->where('tbl_promotionals.paid_status','unpaid')
                                ->where('tbl_promotionals.srno',$promotionalId)
                                ->get();
            
            if (!empty($arrPromotionals)) {
                foreach ($arrPromotionals as $value) {
                    //if($value->count >= $value->require_count){
                        //give promotional income user id and promotional type wise
                        $store  = PromotionalSocialIncome::insertGetId([
                            'id'                    => $value->id,
                            'promotional_type_id'   => $value->promotional_type_id,
                            'amount'                => $value->promotional_cost,
                            // 'from_date'             => $from_date,
                            // 'to_date'               => $to_date,
                            'entry_time'            => now(),
                        ]);

                        //update paid status who's given income above from unpaid to paid
                        $update = Promotionals::where('srno',$value->srno)->limit(1)->update([
                            'paid_status' => 'paid'
                        ]);

                        $updateDash = Dashboard::where('id',$value->id)->limit(1)->update([
                            'promotional_income' => DB::raw('promotional_income + '.$value->promotional_cost),
                            'working_wallet' => DB::raw('working_wallet + '.$value->promotional_cost),
                        ]);
                    //}
                }
            }
        } catch(Exception $e) {
            
        }

         /**
     * [Binary plan description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */

    }
     /**
     * Get Binary income income
     * 
     * @return \Illuminate\Http\Response
     */  

    function pay_binarybulk($user_id, $amount) {

        try{

            //echo "$amount";die;
            $virtual_parent_id1 = $user_id;
            $from_user_id_for_today_count = $user_id;

            $loopOn1 = true;

            if ($virtual_parent_id1 > 0) {
                do {

                    $posDetails = User::where('id',$virtual_parent_id1)->get();
                    
                    if (count($posDetails) <= 0) {

                        $loopOn1 = false;
                    } else {

                        foreach ($posDetails as $k => $v) {
                            $virtual_parent_id1 = $posDetails[$k]->virtual_parent_id;
                            if ($virtual_parent_id1 > 0) {
                                $position = $posDetails[$k]->position;
                                if ($user_id != $virtual_parent_id1) {
                                    $userExist = CurrentAmountDetails::where([['user_id', '=', $virtual_parent_id1]])->first();
                                    if ($position == 1) {
                                        $updateOtpSta1 = User::where('id', $virtual_parent_id1)->update(
                                                array('l_bv' => DB::raw('l_bv + ' . $amount . '')));
                                        if (!empty($userExist)) {
                                            $updateLeftBv = CurrentAmountDetails::where('user_id', $virtual_parent_id1)->update(
                                                    array('left_bv' => DB::raw('left_bv + ' . $amount . '')));
                                        }
                                    }
                                    if ($position == 2) {
                                        $updateOtpSta1 = User::where('id', $virtual_parent_id1)->update(
                                                array('r_bv' => DB::raw('r_bv + ' . $amount . '')));

                                        if (!empty($userExist)) {
                                            $updateLeftBv = CurrentAmountDetails::where('user_id', $virtual_parent_id1)->update(
                                                    array('right_bv' => DB::raw('right_bv + ' . $amount . '')));
                                        }
                                    }
                                }
                            } else {
                                $loopOn1 = false;
                            }
                        }
                    }
                } while ($loopOn1 == true);
                //exit;
            }

        }catch(Exception $e){
               
               $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
               $arrCode     = Response::$statusTexts[$arrStatus];
               $arrMessage  = 'Something went wrong,Please try again'; 
            return sendResponse($arrStatus,$arrCode,$arrMessage,$e);
        } 
    }

    /**
     * Get Direct income income
     * 
     * @return \Illuminate\Http\Response
     */  

     public function check_rank_vpid($id)
     {
        try{


            // $ref_user_id = User::select('id')->where([['id', '=', $user_id]])->pluck('ref_user_id')->first();
            
            // $user_name = User::select('id')->where([['id', '=', $user_id]])->pluck('user_id')->first();
             $date = \Carbon\Carbon::now();
            $dateTime = $date->toDateTimeString();
 
 
             $userDetails = User::select('rank','power_l_bv','power_r_bv','type')
                                  ->where([['id', '=', $id]])
                                  ->get();
            
             $check_if_ref_exist = Topup::select('id')->where('id',$id)->count('id');

             if($userDetails[0]->type != "Admin" && $userDetails[0]->rank == null && $userDetails[0]->power_l_bv > 0 && $userDetails[0]->power_r_bv > 0 && $check_if_ref_exist)
             {
             
               $invoice_id = substr(number_format(time() * rand(), 0, '', ''), 0, '15');

                $Insertdata = array();
                $Insertdata['pin'] = $invoice_id;
                $Insertdata['rank'] = 'Ace';
                $Insertdata['user_id'] = $id;
                
                $insertAdd = supermatching::create($Insertdata);
                $data = array();
                $data['rank'] = 'Ace';
                DB::table('tbl_users')
                ->where('id', $id)
                ->update($data); 


                // update this Ace to upline


            //     $updateLCountArr = array();
            //     $updateLCountArr['l_ace'] = DB::raw('l_ace + 1');
            //     $updateLCountArr['l_ace_check_status'] = DB::raw('l_ace_check_status + 1');
                
             

            //     DB::table('tbl_today_details as a')
            //    // ->select('a.from_user_id','a.from_user_id_l_c_count','a.from_user_id_r_c_count')
            //     ->join('tbl_users as b','a.to_user_id', '=','b.id')
            //     ->where('a.from_user_id','=',$id)
            //     ->where('a.position','=',1)
            //     ->update($updateLCountArr);


            //     $updateRCountArr = array();
            //     $updateRCountArr['r_ace'] = DB::raw('r_ace + 1');
            //     $updateRCountArr['r_ace_check_status'] = DB::raw('r_ace_check_status + 1');


            //     DB::table('tbl_today_details as a')
            //    // ->select('a.from_user_id','a.from_user_id_l_c_count','a.from_user_id_r_c_count')
            //     ->join('tbl_users as b','a.to_user_id', '=','b.id')
            //     ->where('a.from_user_id','=',$id)
            //     ->where('a.position','=',2)
            //     ->update($updateRCountArr);

            

                $virtual_parent_id1 = $id;
                //-------update rank -------
                $loopOn1 = true;
                $left_users = array();
                $right_users = array();
                if ($virtual_parent_id1 > 0) {
                    do {
                        $posDetails = User::select('id','virtual_parent_id','position')->where([['id', '=', $virtual_parent_id1]])->get();
                        if (count($posDetails) <= 0) {

                            $loopOn1 = false;
                        } else {

                            foreach ($posDetails as $k => $v) {

                                $virtual_parent_id1 = $posDetails[$k]->virtual_parent_id;
                                if ($virtual_parent_id1 > 0) {
                                    $position = $posDetails[$k]->position;
                                    if ($id != $virtual_parent_id1) {
                                        if ($position == 1) {

                                            array_push($left_users, $virtual_parent_id1);

                                          
                                        }
                                        if ($position == 2) {
                                            array_push($right_users, $virtual_parent_id1);

                                           
                                        }

                                       

                                    }
                                } else {
                                    $loopOn1 = false;
                                }
                            }
                        }
                    } while ($loopOn1 == true);
                    //exit;
                }



                $todaysDetails = TodayDetails::select('to_user_id','position','level')->where([['from_user_id', '=', $virtual_parent_id1]])->get();
                if (count($todaysDetails) > 0) {

                    foreach ($todaysDetails as $k => $v) {

                        $virtual_parent_id1 = $todaysDetails[$k]->to_user_id;
                        if ($virtual_parent_id1 > 0) {
                            $position = $todaysDetails[$k]->position;
                            if ($id != $virtual_parent_id1) {
                                if ($position == 1) {
                                    array_push($left_users, $virtual_parent_id1);
                                } else if ($position == 2) {
                                    array_push($right_users, $virtual_parent_id1);
                                }

                            }
                        } 
                    }
                }

                // bulk update

                $updateLCountArr = array();
                $updateLCountArr['l_ace'] = DB::raw('l_ace + 1');
                $updateLCountArr['l_ace_check_status'] = DB::raw('l_ace_check_status + 1');
                
             

                $updateRCountArr = array();
                $updateRCountArr['r_ace'] = DB::raw('r_ace + 1');
                $updateRCountArr['r_ace_check_status'] = DB::raw('r_ace_check_status + 1');



                // Update count
                $count1 = 1;
                $array1 = array_chunk($left_users, 1000);

                while ($count1 <= count($array1)) {
                    //dd($array1);
                    $key1 = $count1 - 1;
                    User::whereIn('id', $array1[$key1])->update($updateLCountArr);
                       $count1++;
                }

                $count2 = 1;
                $array2 = array_chunk($right_users, 1000);
                while ($count2 <= count($array2)) {
                    $key2 = $count2 - 1;
                    User::whereIn('id', $array2[$key2])->update($updateRCountArr);
                   $count2++;
                }


             }
   
         }catch(Exception $e){
             dd($e);
                
                $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Something went wrong,Please try again'; 
             return sendResponse($arrStatus,$arrCode,$arrMessage,'');
         } 
     }

     public function check_rank($id)
     {
        try{

          /*  echo $id;*/


            $users = DB::table('tbl_users')
                ->select('id','l_ace','r_ace','rank')
                ->where('id', '=', $id)
                ->where('rank', '!=', 'NULL')
                ->where('type', '!=', 'Admin')
                ->orderBy('id', 'ASC')
                ->get();

                


        foreach ($users as $value) 
        {
           
            $usersleft = $value->l_ace;
            $usersright = $value->r_ace;

            // for loop for rank 

            $from_rank_details= DB::table('tbl_rank')
                ->select('id')
                ->where('rank', '=', $value->rank)
                ->orderBy('id', 'ASC')
                ->limit('1')
                ->get();

           $from_rank = $from_rank_details[0]->id;

            $to_rank_details= DB::table('tbl_rank')
                ->select('id')
                ->where('left_ace_req', '<=', $value->l_ace)
                ->where('right_ace_req', '<=', $value->l_ace)
                ->orderBy('id', 'DESC')
                ->limit('1')
                ->get();

                $to_rank = $to_rank_details[0]->id;

                for ($i = $from_rank; $i <= $to_rank; $i++)
                {
                    for ($x = 0; $x <= 10; $x++)
                  $rank_details= DB::table('tbl_rank')
                   ->select('id','rank','left_ace_req','right_ace_req')
                   ->where('id', '=', $i)
                   ->get();

                   if(($usersleft >= $rank_details[0]->left_ace_req) && ($usersright >=$rank_details[0]->right_ace_req))
            {   
             
                $isExist = supermatching::select("user_id")
                ->where("rank", $rank_details[0]->rank)
                ->where('user_id', $value->id)
                ->count('user_id');


                if ($isExist == 0) {
                    $invoice_id = substr(number_format(time() * rand(), 0, '', ''), 0, '15');

                    $Insertdata = array();
                    $Insertdata['pin'] = $invoice_id;
                    $Insertdata['rank'] = $rank_details[0]->rank;
                    $Insertdata['user_id'] = $value->id;
                    
                    $insertAdd = supermatching::create($Insertdata);
                    $data = array();
                    $data['rank'] = $rank_details[0]->rank;
                    DB::table('tbl_users')
                        ->where('id', $value->id)
                        ->update($data);
                }
                
            } 

                }

                $data5['l_ace_check_status'] = 0;
            $data5['r_ace_check_status'] = 0;
            DB::table('tbl_users')
            ->where('id', $value->id)
            ->update($data5);





        }
   
         }catch(Exception $e){
             dd($e);
                
                $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Something went wrong,Please try again'; 
             return sendResponse($arrStatus,$arrCode,$arrMessage,'');
         } 
     }
 


     
     

    
    public function pay_directbulk($user_id, $amount, $direct_income, $invoice_id,$ref_user_id,$user_name) 
    {

        try{
          
                $date = \Carbon\Carbon::now();
                $dateTime = $date->toDateTimeString();

               
                $percentage = $direct_income;
              
                //----------dirct income------------

                $ref_count = User::where('id',$ref_user_id)->where('type','')->select('topup_status')->first();
                
                if(!empty($ref_count))
                {   
                
                    $remark = "Direct income";
                    $laps = $flag = $working_wallet_amount = $topup_wallet_amount = 0;
                    $check_if_ref_exist = Topup::select('id')->where('roi_status','Active')->where('id',$ref_user_id)->count('id');
                    if ($ref_count->topup_status == "0") {
                        $flag = 1;
                        $remark = "Not having topup";
                    }
                     else if ($check_if_ref_exist == 0) {                        
                        $flag = 1;
                        $remark = "Not having active topup";
                    }

                    $topup_amt = Topup::join('tbl_product','tbl_product.id','=','tbl_topup.type')
                                        ->select('tbl_topup.amount','tbl_product.direct_income')
                                        ->where('tbl_topup.id',$ref_user_id)
                                        ->orderBy('tbl_topup.amount','desc')->first();

                    if(!empty($topup_amt))
                    {
                        $direct_per = $topup_amt->direct_income;  
                                             
                        $payable_direct_income = (($amount * $direct_per) / 100);

                        $topup_wallet_amount = round((($payable_direct_income*0)/100),2);
                        $working_wallet_amount = round((($payable_direct_income*100)/100),2);

                        if ($flag == 1) {
                            $laps = $working_wallet_amount;
                            $working_wallet_amount = 0;
                        }
                    }

                    $Directata = array();      // insert in transaction 
                    $Directata['amount'] = $working_wallet_amount;
                    $Directata['toUserId'] = $ref_user_id;
                    $Directata['fromUserId'] = $user_id;
                    $Directata['entry_time'] = $dateTime;
                    $Directata['topup_wallet_amount'] = $topup_wallet_amount;
                    $Directata['working_wallet_amount'] = $working_wallet_amount;
                    $Directata['laps_amount'] = $laps;
                    $Directata['status'] = 'Paid';
                    $Directata['invoice_id'] = $invoice_id;
                    $Directata['remark'] = $remark;
                    $TransactionDta = DirectIncome::create($Directata);

                    $updateDashData = array();
                    $updateDashData['usd'] = DB::raw('usd + ' . round($working_wallet_amount, 2) . '');
                    $updateDashData['total_profit'] = DB::raw('total_profit + ' . round($working_wallet_amount, 2) . '');
                    $updateDashData['direct_income'] = DB::raw('direct_income + ' . round($working_wallet_amount, 2) . '');
                    $updateDashData['direct_income_withdraw'] = DB::raw('direct_income_withdraw + ' . round($working_wallet_amount, 2) . '');
                    /*$updateDashData['working_wallet'] = DB::raw('working_wallet + ' . round($working_wallet_amount, 2) . '');*/
                    $updateDashData['working_wallet'] = DB::raw('working_wallet + ' . round($working_wallet_amount, 2) . '');
                   /* $updateDashData['working_to_topup'] = DB::raw('working_to_topup + ' . round($topup_wallet_amount, 2) . '');*/
                    $updateDashData['top_up_wallet'] = DB::raw('top_up_wallet + ' . round($topup_wallet_amount, 2) . '');
                    $updateOtpSta = Dashboard::where('id', $ref_user_id)->limit(1)->update($updateDashData);

            }
  
        }catch(Exception $e){
            dd($e);
               
               $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
               $arrCode     = Response::$statusTexts[$arrStatus];
               $arrMessage  = 'Something went wrong,Please try again'; 
            return sendResponse($arrStatus,$arrCode,$arrMessage,'');
        } 
    }



    public function binaryPlanBulk(Request $request) {

        DB::beginTransaction();
        try {
            $start2 = microtime(true);

            $arrInput = $request->all();
            $uniqueid =  $request->input('user_id');
            $user_id = $request->input('user_id');
            $ref_user_id = $request->input('ref_user_id');
            $country = $request->input('country');
            //$placement_user_id = $ref_user_id;
            if((isset($arrInput['placement_user_id'])) && (!empty($arrInput['placement_user_id'])))
                $placement_user_id = $arrInput['placement_user_id'];
            else 
                $placement_user_id = $ref_user_id;
            /*dd($request->all());*/
            $getUserDetails = User::select('id')->where('user_id', $placement_user_id)->get();
            $getRefDetails = User::select('id')->where('user_id', $ref_user_id)->get();
            if (count($getUserDetails) > 0 && count($getRefDetails) > 0) {
                $flag = 0;
                $place_id = $getUserDetails[0]->id;   // user auto id
                $reference_id = $getRefDetails[0]->id;          // user reference auto id
                if ($flag == 0) {
                    $q2 = TodayDetails::select('to_user_id')->where([['to_user_id', '=', $reference_id], ['from_user_id', '=', $place_id]])->get();
                    if (count($q2) > 0) {
                        $flag = 1;
                    }
                }
                if ($place_id == $reference_id) {
                    $flag = 1;
                }
            } else {
                return sendresponse(404, "Not found", 'Sponser/Referrer is not exist', '');
            }


            $start3 = microtime(true);
        
            if ($flag == 1) {
                $RfCount1 = User::select('id')->where([['user_id', '=', $user_id]])->get();
                if (count($RfCount1) == 0) {
                    $RefDetails = $getRefDetails;//User::select('id')->where([['user_id', '=', $ref_user_id]])->get();
                    if (count($RefDetails) > 0) {
                        $virtual_parent_id = $RefDetails[0]->id;
                        $position = $request->input('position');
                        $RfCount2 = $getUserDetails;//User::select('id')->where([['user_id', '=', $placement_user_id]])->get();
                        if (count($RfCount2) > 0) {
                            $random_token = md5(uniqid(rand(), true));
                            if (!empty($request->input('mobile'))) {
                                $mobile = $request->input('mobile');
                            } else {
                                $mobile = 0;
                            }
                            $virtual_parent_id = $RfCount2[0]->id;
                            $loopOn = true;
                            $p=0;
                            do {
                                $start14=microtime(true);

                                $posDetails = User::select('id')->where([['virtual_parent_id', '=', $virtual_parent_id], ['position', '=', $position]])->get();
                                if (count($posDetails) <= 0) {
                                    $loopOn = false;                                      
                                } else {
                                    $virtual_parent_id = $posDetails[0]->id;
                                }
                            } while ($loopOn == true);


                            //-----insert user data--------   
                            $insertdata = new User;
                            $insertdata->ref_user_id = $RefDetails[0]->id;
                            $insertdata->email = $request->input('email');
                            $insertdata->unique_user_id = $uniqueid;
                            $insertdata->password = encrypt($request->input('password'));
                            $insertdata->remember_token = $random_token;
                            $insertdata->user_id = trim($request->input('user_id'));
                            $insertdata->fullname = $request->input('fullname');
                            $insertdata->mobile = $mobile;
                            $insertdata->country = $country;
                            $insertdata->position = $request->input('position');
                            $insertdata->temp_pass = $request->input('password');
                            //$insertdata->pin_number = $request->input('pin_number');
                            $insertdata->status = 'Active';
                            $insertdata->bcrypt_password = bcrypt($request->input('password'));
                            $insertdata->tr_passwd = bcrypt($request->input('transcation_password'));
                            $insertdata->entry_time = \Carbon\Carbon::now();
                            $insertdata->virtual_parent_id = $virtual_parent_id;
                          /*  $insertdata->nominee_name = $request->input('nominee_name');
                            $insertdata->relation = $request->input('relation');
                            $insertdata->dob = $request->input('dob');
                            $insertdata->account_no = $request->input('account_no');
                            $insertdata->holder_name = $request->input('holder_name');
                            $insertdata->bank_name = $request->input('bank_name');
                            $insertdata->branch_name = $request->input('branch_name');
                            $insertdata->pan_no = $request->input('pan_no');
                            $insertdata->ifsc_code = $request->input('ifsc_code');
                            $insertdata->country = $request->input('country');
                            $insertdata->state = $request->input('state');
                            $insertdata->address = $request->input('address');
                            $insertdata->city = $request->input('city');*/
                            $insertdata->btc_address = $request->input('btc_address');
                            $insertdata->bnb_address = $request->input('bnb_address');
                            $insertdata->trn_address = $request->input('trn_address');
                            $insertdata->ethereum = $request->input('ethereum');
                            //$insertdata->polkadot_address = $request->input('polkadot_address');
                            //$insertdata->amount = $pinAmount->cost;
                            // $insertdata->pincode = $request->input('pincode');

                          
                            $insertdata->save();
                            $last = $insertdata->id;

                            $dashdata = new Dashboard;
                            $dashdata->id = $last;
                            $dashdata->save();

                            $virtual_parent_id1 = $last;
                            $from_user_id_for_today_count = $last;
                            $i = 0;
                            
                            $loopOn1 = true;
                            $todaydetails_data = array();
                            $left_users = array();
                            $right_users = array();
                            if ($virtual_parent_id1 > 0) {
                                do {
                                    $posDetails = User::select('id','virtual_parent_id','position')->where([['id', '=', $virtual_parent_id1]])->get();
                                    if (count($posDetails) <= 0) {

                                        $loopOn1 = false;
                                    } else {

                                        foreach ($posDetails as $k => $v) {

                                            $virtual_parent_id1 = $posDetails[$k]->virtual_parent_id;
                                            if ($virtual_parent_id1 > 0) {
                                                $position = $posDetails[$k]->position;
                                                if ($last != $virtual_parent_id1) {
                                                    if ($position == 1) {

                                                        array_push($left_users, $virtual_parent_id1);

                                                       // $updateOtpSta1 = User::where('id', $virtual_parent_id1)->update(array('l_c_count' => DB::raw('l_c_count + 1')));
                                                    }
                                                    if ($position == 2) {
                                                        array_push($right_users, $virtual_parent_id1);

                                                       // $updateOtpSta1 = User::where('id', $virtual_parent_id1)->update(array('r_c_count' => DB::raw('r_c_count + 1')));
                                                    }

                                                     $Todaydata = array(); // new TodayDetails;
                                                    $Todaydata['to_user_id'] = $virtual_parent_id1;
                                                    $Todaydata['from_user_id'] = $from_user_id_for_today_count;
                                                    $Todaydata['entry_time'] = date("Y-m-d H:i:s");
                                                    $Todaydata['position'] = $position;
                                                    $Todaydata['level'] = $i + 1;
                                                    array_push($todaydetails_data, $Todaydata);
                                                       $i++;

                                                }
                                            } else {
                                                $loopOn1 = false;
                                            }
                                        }
                                    }
                                } while ($loopOn1 == true);
                                //exit;
                            }
                          
                            $count = 1;
                            $array = array_chunk($todaydetails_data, 1000);
                            //dd($array);
                            while ($count <= count($array)) {
                                $key = $count - 1;
                                TodayDetails::insert($array[$key]);
                                // echo $count." count array ".count($array[$key])."\n";
                                $count++;
                            }

                            $updateLCountArr = array();
                            $updateLCountArr['l_c_count'] = DB::raw('l_c_count + 1');

                            $updateRCountArr = array();
                            $updateRCountArr['r_c_count'] = DB::raw('r_c_count + 1');

                            // Update count
                            $count1 = 1;
                            $array1 = array_chunk($left_users, 1000);

                            while ($count1 <= count($array1)) {
                                //dd($array1);
                                $key1 = $count1 - 1;
                                User::whereIn('id', $array1[$key1])->update($updateLCountArr);
                                // echo $count." count array1 ".count($array1[$key1])."\n";
                                $count1++;
                            }

                            $count2 = 1;
                            $array2 = array_chunk($right_users, 1000);
                            while ($count2 <= count($array2)) {
                                $key2 = $count2 - 1;
                                User::whereIn('id', $array2[$key2])->update($updateRCountArr);
                                // echo $count2." count array ".count($array2[$key2])."\n";
                                $count2++;
                            }

                             //-----insert top up----------- ---
                            $random = substr(number_format(time() * rand(), 0, '', ''), 0, '15');
                            $ProductRoi = 2 ;
                            $ProductDuration = 100;
                            $Topupdata = array();
                            $Topupdata['id'] = $last;
                            $Topupdata['pin'] = $random;
                            $Topupdata['amount'] = 100;
                            $Topupdata['percentage'] = ($ProductRoi * $ProductDuration);
                            $Topupdata['type'] = 1;
                            $Topupdata['top_up_by'] = 1;
                            $Topupdata['roi_status'] = 'Active';
                            $Topupdata['top_up_type'] = 3;
                            $Topupdata['binary_pass_status'] = '1';
                            $Topupdata['entry_time'] = \Carbon\Carbon::now();

                            if ($request->Input('device') != '') {
                                $Topupdata['device'] = $request->Input('device');
                            } else {
                                $Topupdata['device'] = '-';
                            }
                            if ($request->Input('topupfrom') != '') {
                                $Topupdata['topupfrom'] = $request->Input('topupfrom');
                            } else {
                                $Topupdata['topupfrom'] = '-';
                            }

                            // $storeTopup = Topup::create($Topupdata);
                            $storeTopup = array();

                            if (!empty($storeTopup)) {
                                $storeId = $storeTopup->id;
                                //-----check which plan is on in setting--------
                                $plan = ProjectSettings::selectRaw('(CASE level_plan WHEN "on" THEN 1 WHEN "off" THEN 0 ELSE "" END) as level_plan,(CASE binary_plan WHEN "on" THEN 1 WHEN "off" THEN 0 ELSE "" END) as binary_plan ,(CASE direct_plan WHEN "on" THEN 1 WHEN "off" THEN 0 ELSE "" END ) as direct_plan')->first();

                                if (!empty($plan)) {
                                    $level_plan = $plan->level_plan;
                                    $binary_plan = $plan->binary_plan;
                                    $direct_plan = $plan->direct_plan;
                                    $leadership_plan = $plan->leadership_plan;

                                    if ($level_plan == '1') {
                                        $getlevel = $this->pay_level($last, 100, $pacakgeId);
                                    }

                                    if ($binary_plan == '1') {

                                         // update direct business

                                        $updateLCountArrDirectBusiness = array();
                                        $updateLCountArrDirectBusiness['power_l_bv'] = DB::raw('power_l_bv + ' . 100 . '');
                                         
                                        $updateRCountArrDirectBusiness = array();
                                        $updateRCountArrDirectBusiness['power_r_bv'] = DB::raw('power_r_bv + ' . 100 . '');

                                        if($request->position == 1)
                                        {
                                            User::where('id', $RefDetails[0]->id)->update($updateLCountArrDirectBusiness);
                                        }else if($request->position == 2){
                                            User::where('id', $RefDetails[0]->id)->update($updateRCountArrDirectBusiness);
                                        }
                                        
                                        $usertopup = array('amount'=>DB::raw('amount + 100'),'topup_status'=>"1");
                                        User::where('id', $last)->update($usertopup);
                                    
                                        $getlevel = $this->pay_binary($last, 100);
                                    }
                                    $direct_income = 7;
                                    if ($direct_plan == '1' && $direct_income > 0) {
                                        $getlevel = $this->pay_direct($last, 100, $direct_income, $random);
                                    }
                                    if ($leadership_plan == '1') {
                                        $getlevel = $this->pay_leadership($last, 100, 1);
                                    }
                                }

                               // DB::raw('UNLOCK tbl_today_details');
                                //DB::raw('UNLOCK tbl_users');
                            // DB::raw('UNLOCK TABLES');
                           
                            //----------level view---------------
                            if ($virtual_parent_id != '0') {
                               
                                $this->levelView($RefDetails[0]->id, $last, 1,array());
                                //$this->viewlevel->levelView($RefDetails[0]->id, $last, 1);
                            }
                        }
                    }
                }
            }
        }        
        } catch (Exception $e) {
          dd($e);
            DB::rollBack();
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Something went wrong,Please try again', '');
        }
        DB::commit();
          $statuscode = Config::get('constants.statuscode');
        return sendresponse($statuscode[200]['code'], $statuscode[200]['status'], 'User registered successfully', (object) array('userid' => $user_id, 'sponsor_name' => $request->sponsor_name, 'password' => $request->input('password')));
    } 

}
