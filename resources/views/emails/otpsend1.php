<?php

$logo = $message->embed(public_path() . '/user_files/img/logo.png');
$mailicon = $message->embed(public_path() . '/user_files/img/mailicon.png');
$backimg = $message->embed(public_path() . '/user_files/img/background1.png');
$thanks =$message->embed(public_path() . '/user_files/img/thank_you1.png');

$path       = Config::get('constants.settings.domainpath');
$linkexpire = Config::get('constants.settings.linkexpire');
//$logo       = asset('img/logo_new.png');
// $emaillogo  = asset('img/email.png');
// $facebook   = asset('img/facebook.png');

$projectname = Config::get('constants.settings.projectname');
echo $msg = '<!DOCTYPE HTML>

<head>
  <title>Bitcoin Mail</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
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
                              <p style="text-align:center;margin: 20px auto;font-size: 16px;font-weight: 700;color:#000">
                                Did you try to change the Bitcoin Address in your account?
                               
                              </p>
                              <p style="text-align:center;font-size: 20px;word-spacing: 6px;color:#23D0E1;font-family: Josefin Slab, serif;">
                                 If YES, use the below code to proceed.
                              </p>
                              <div style="text-align:center;width: fit-content;margin:10px auto;background:linear-gradient(45deg, #00ff88, #2fc0ff)!important;font-size: 15px;color: #fff;line-height: 25px;padding: 15px 25px;box-shadow: inset 5px 5px 6px #FFFFFF7A, 10px 10px 10px #00000029!important;
    border-radius: 7px!important;">
                                  <span style="font-weight:900;font-size: 24px;">' . $otp . ' </span>
                                 
                              </div>
                              <p style="margin-top:10px;margin-bottom:5px;font-weight:600;font-size:16px;text-align: center;color: #000;">If Not, just ignore this mail.</p>

                            <!--  <p style="text-If Not, just ignore this mail.align:center;color: #fff; font-size: 18px;">
                                 <br><br><br>
                                You can login here: <a href="'.$url.'" target="_blank" style="color: #fff;text-decoration: none;"> '.$url.'</a>
                              </p> -->
                             <p style="text-align:center;color: #000; font-size: 18px;">You can login here: <a href="https://www.equitals.com" target="_blank" style="color:#23D0E1;text-decoration: none;font-weight:600">www.equitals.com</a></p>
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
   </body>

</html>

';

