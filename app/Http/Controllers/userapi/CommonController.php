<?php

namespace App\Http\Controllers\userapi;


use App\Http\Controllers\Controller;
use Illuminate\Http\Response as Response;
use App\Models\Country;
use App\Models\allRanks;
use App\Models\ProjectSettings;
use App\User;
use Config;
use Exception;

class CommonController extends Controller {

   /**
     * Get country details
     *
     * @return \Illuminate\Http\Response
     */
    public function getCountry() {

        //$getCountry = DB::select('call getCountryWhere');
        // $password=md5('Imuons@14');
        // $checkUserLOgin= DB::select('call UserLogin("pranay","'.$password.'")');
        // $getInvoice= DB::select('call getInvoice("pranay")');
       try{
            $getCountry = Country::orderBy('country_id', 'asc')->get();
            if (empty($getCountry) && count($getCountry) > 0) {
                //Country not found
                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Country not found'; 
                return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                
            } else {
                //Country found
                $arrData = $getCountry;
                $arrStatus   = Response::HTTP_OK;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Country Found'; 
                return sendResponse($arrStatus,$arrCode,$arrMessage,$arrData);
                
            }
        }catch(Exception $e){
                   
           $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
           $arrCode     = Response::$statusTexts[$arrStatus];
           $arrMessage  = 'Something went wrong,Please try again'; 
           return sendResponse($arrStatus,$arrCode,$arrMessage,'');
        }    
    }

    public function getrank() {


        //$getCountry = DB::select('call getCountryWhere');
        // $password=md5('Imuons@14');
        // $checkUserLOgin= DB::select('call UserLogin("pranay","'.$password.'")');
        // $getInvoice= DB::select('call getInvoice("pranay")');
       try{
            $getrank = allRanks::orderBy('id', 'asc')->get();

            if (empty($getrank) && count($getrank) > 0) {
                //Country not found
                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Rank not found'; 
                return sendResponse($arrStatus,$arrCode,$arrMessage,'');
                
            } else {
                //Country found
                $arrData = $getrank;
                $arrStatus   = Response::HTTP_OK;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Rank Found'; 
                return sendResponse($arrStatus,$arrCode,$arrMessage,$arrData);
                
            }
        }catch(Exception $e){
                   
           $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
           $arrCode     = Response::$statusTexts[$arrStatus];
           $arrMessage  = 'Something went wrong,Please try again'; 
           return sendResponse($arrStatus,$arrCode,$arrMessage,'');
        }    
    }    
     /**
     * Get country details BY user
     *
     * @return \Illuminate\Http\Response
     */

    public function getUserCountry() {
     try{
            $getCountry = User::join('tbl_country_new as cn', 'tbl_users.country', '=', 'cn.iso_code')->select('tbl_users.fullname', 'cn.country')->orderBy('tbl_users.id', 'desc')->limit(10)->get();

            if (!empty($getCountry) && count($getCountry) > 0) {

                $arrData = $getCountry;
                $arrStatus   = Response::HTTP_OK;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Data not found'; 
                return sendResponse($arrStatus,$arrCode,$arrMessage,$arrData);

            } else {
               
                $arrStatus   = Response::HTTP_NOT_FOUND;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Data not found'; 
                return sendResponse($arrStatus,$arrCode,$arrMessage,'');

            }
        }catch(Exception $e){
                   
           $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
           $arrCode     = Response::$statusTexts[$arrStatus];
           $arrMessage  = 'Something went wrong,Please try again'; 
           return sendResponse($arrStatus,$arrCode,$arrMessage,'');
        }     
    }

    public function getProjectSettings() {

        $ProjectSettings = ProjectSettings::where('status',1)->first();

        if(!empty($ProjectSettings)) {
                 
                $arrStatus   = Response::HTTP_OK;
                $arrCode     = Response::$statusTexts[$arrStatus];
                $arrMessage  = 'Data  found'; 
                return sendResponse($arrStatus,$arrCode,$arrMessage,$ProjectSettings);

        } else {
              $arrStatus   = Response::HTTP_INTERNAL_SERVER_ERROR;
              $arrCode     = Response::$statusTexts[$arrStatus];
              $arrMessage  = 'Data not found'; 
              return sendResponse($arrStatus,$arrCode,$arrMessage,'');
        }
    }

}
