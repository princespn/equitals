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
<html>
   <head>
      <title>'.$title.' Mail</title>
       <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@500&display=swap" rel="stylesheet">
   </head>
   <body style=" padding:30px;  font-family: Poppins, sans-serif;">
         <table width="600" cellspacing="0" cellpadding="0" align="center" style=" padding: 15px 25px; background: url('.$bg.') no-repeat; background-size:cover ; min-height: 650px;">
         <tbody>
            <tr>
               <td>
                  <table width="100%" cellspacing="0" cellpadding="0">
                     <tbody>
                        <tr>
                           <td>
                              <center>
                                 <img src="' . $logo . '" alt="" width="100">
                              </center>
                           <h2 style="text-align: center;margin: 10px auto;color: #47820c;font-size: 35px;font-weight: 900;line-height: 35px;">
                           '.$title.'</h2>
                          <p style="text-align:center;margin: 20px auto;font-size: 14px;font-weight: 700; color: #000;"> '.$mesage.'<br>
                           </p>
                           <p style="text-align:center;color: #000;">
                              To get more details log on to: <a href="'.$path.'" target="_blank" style="color: #3035f4;text-decoration: none;">&nbsp;'.$path.'</a> <br>
                              Thank you.<br>
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