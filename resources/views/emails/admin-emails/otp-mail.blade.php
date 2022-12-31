<?php

$logo = $message->embed(public_path() . '/user_files/img/logo.png');
$mailicon = $message->embed(public_path() . '/user_files/img/mailicon.png');
$backimg = $message->embed(public_path() . '/user_files/img/background1.png');
$thanks =$message->embed(public_path() . '/user_files/img/thank_you1.png');


$path       = Config::get('constants.settings.domainpath');
$url       = Config::get('constants.settings.domain');
$linkexpire = Config::get('constants.settings.linkexpire');
//$logo       = asset('img/logo_new.png');
// $emaillogo  = asset('img/email.png');
// $facebook   = asset('img/facebook.png');
$projectname = Config::get('constants.settings.projectname');
echo $msg = '<!DOCTYPE HTML>
<html><head>
   <title>otp Send</title>
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@500&display=swap" rel="stylesheet">
   </head>
   <body style=" padding:10px;  font-family: Poppins, sans-serif;background:#6BB4C8">
         <table width="600" cellspacing="0" cellpadding="0" align="center" style="background:#fff url(' . $backimg . ') no-repeat;background-size: contain;background-position: center bottom;padding: 20px 35px;min-height: 550px;border-radius: 15px;box-shadow:0px 3px 50px #ffffff82">
         <tbody>
            <tr>
               <td>
                  <table width="100%" cellspacing="0" cellpadding="0">
                     <tbody>
                        <tr>
                           <td>
                              <center>
                                 <img src="' . $logo . '" alt="">
                              </center>
                              <div style="text-align: center;margin: 20px 0;">
                                <img src="' . $mailicon . '" alt="">
                              </div>
                             
                           </td>
                        </tr>
                        <tr>
                           <td>
                              
                              <p style="text-align:center;margin: 20px auto;font-size: 16px;font-weight: 700;color:#000">
                                Your one time password is given below.
                               
                              </p>
                              
                              <div style="text-align:center;width: fit-content;margin:20px auto;background:linear-gradient(45deg, #00ff88, #2fc0ff)!important;font-size: 15px;color: #fff;line-height: 25px;padding: 15px 25px;box-shadow: inset 5px 5px 6px #FFFFFF7A, 10px 10px 10px #00000029!important;
    border-radius: 7px!important;">
                                  <span style="font-weight:900;font-size: 24px;">' . $otp . ' </span>
                                 
                              </div>
                             
                             <p style="text-align:center;color: #000; font-size: 18px;">You can login here: <a href="'.$url.'" target="_blank" style="color:#23D0E1;text-decoration: none;font-weight:600"> '.$url.'</a></p>
                             <p style="text-align:center">
                              <img src="' .$thanks. '" style="margin: auto; text-align: center; display: block; width: 250px;">
                              </p>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </body></html>
';