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
<head>
   <title>Deposit</title>
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
                              
                              <p style="text-align:center;margin: 20px auto;font-size: 16px;font-weight: 700;color:#000;line-height:24px">
                                 Package of '.$Package.' and '.$amount.' has been activated in your account of Username '.$username.'. 
                              </p>
                           
                              
                              <p style="margin-top:30px;margin-bottom:15px;font-weight:600;font-size:16px;text-align: center;color: #000;">Do not share your login details with anyone:</p>

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
   </body>
</html>
';