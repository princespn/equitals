<?php

$logo = $message->embed(public_path() . '/user_files/img/logo.png');
$mailicon = $message->embed(public_path() . '/user_files/img/mailicon.png');
$backimg = $message->embed(public_path() . '/user_files/img/background1.png');
$thanks =$message->embed(public_path() . '/user_files/img/thank_you1.png');

$path       = Config::get('constants.settings.domainpath');
$url      = Config::get('constants.settings.domain');
$linkexpire = Config::get('constants.settings.linkexpire');
//$logo       = asset('img/logo_new.png');
// $emaillogo  = asset('img/email.png');
// $facebook   = asset('img/facebook.png');
$projectname = Config::get('constants.settings.projectname');
echo $msg = '<!DOCTYPE HTML>
<html><head>
   <title>Reset password</title>
     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@500&display=swap" rel="stylesheet">
   </head>
   <body style=" padding:10px;  font-family: Poppins, sans-serif;background:#6BB4C8">
         <table width="600" cellspacing="0" cellpadding="0" align="center" style="background:#fff url(' . $backimg . ') no-repeat;background-size: contain;background-position: center bottom;padding: 20px 35px;min-height: 650px;border-radius: 15px;box-shadow:0px 3px 50px #ffffff82">
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
                              <div style="text-align: center;margin: 10px 0;">
                                <img src="' . $mailicon . '" alt="">
                              </div>
                              <h2 style="text-align: center;margin: 10px auto;color:#23D0E1;font-size: 26px;font-weight: 900;line-height: 35px;"> 
                                 <span style="color: #000;">Hello,</span> 
                                 '.$username.'
                              </h2>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              
                              <p style="text-align:center;margin: 20px auto;font-size: 16px;font-weight: 700;color:#000">
                               Did you try to change the Password of your account?
                               
                              </p>
                              <p style="text-align:center;font-size: 20px;word-spacing: 6px;color:#23D0E1;font-family: Josefin Slab, serif;">
                                 click on the following link to update your password and follow the simple steps :

                              </p>
                              <div style="text-align:center;width: fit-content;margin:10px auto;background:linear-gradient(45deg, #00ff88, #2fc0ff)!important;font-size: 15px;color: #fff;line-height: 25px;padding: 15px 25px;box-shadow: inset 5px 5px 6px #FFFFFF7A, 10px 10px 10px #00000029!important;
    border-radius: 7px!important;font-weight:600">
                                Visit : <a href="'.$path.'/public/user#/reset-password?resettoken=' . $reset_token . '" style="background: #000;text-align: center;text-decoration: none;color: #fff;padding: 10px;display: inline-block;border-radius: 6px;">Link Here..</a>
                                 
                              </div>
                              <p style="margin-top:10px;margin-bottom:5px;font-weight:600;font-size:16px;text-align: center;color: #000;">Do not share your login details with anyone:</p>

                            <!--  <p style="text-align:center;color: #fff; font-size: 18px;">
                                 <br><br><br>
                                You can login here: <a href="'.$url.'" target="_blank" style="color: #fff;text-decoration: none;"> '.$url.'</a>
                              </p> -->
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