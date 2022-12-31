<?php


$logo = $message->embed(public_path() . '/user_files/img/logo.png');
$mailicon = $message->embed(public_path() . '/user_files/img/mailicon.png');
$backimg = $message->embed(public_path() . '/user_files/img/background1.png');
$thanks =$message->embed(public_path() . '/user_files/img/thank_you1.png');

$emaillogo = $message->embed(public_path() . '/img/email.png');
$facebook = $message->embed(public_path() . '/img/facebook.png');
//$logo="http://sk.uploads.im/t/xp0bI.png";
//$emaillogo="http://sk.uploads.im/t/DMtVy.png";
//$facebook="http://sk.uploads.im/t/gmFsb.png";
$path = Config::get('constants.settings.domainpath');
$projectname = Config::get('constants.settings.projectname');
echo $msg = '<!DOCTYPE HTML>
<html>
   <head>
      <title>Enquiry Mail</title>
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
                              
                           </td>
                        </tr>
                        <tr>
                           <td>
                              
                             
                              <div style="text-align:center;width: fit-content;margin:10px auto;background:linear-gradient(45deg, #00ff88, #2fc0ff)!important;font-size: 15px;color: #fff;line-height: 25px;padding: 15px 25px;box-shadow: inset 5px 5px 6px #FFFFFF7A, 10px 10px 10px #00000029!important;
    border-radius: 7px!important;">
                                  Fullname :' . $fullname . ' <br>
                                 Email: ' . $email . ' <br>
                                 Message: ' . $msg . ' <br>
                                 
                              </div>
                              <div class="wrapper-footer" style="background: #fff0; padding: 10px; text-align: center;">
                              <h3 style=" font-size: 20px; color: #6f6f6f; font-weight: 200; margin: 10px;">Stay in touch</h3>
                              <a href="#"><img src=' . $facebook . ' alt="" style="width:30px"></a>
                              <div class="copyright">
                                <p style=" color: #8e8e8e; margin: 0; margin-top: 10px; line-height: 1.6;"> Email send by ' . $projectname . '/p>
                                <p style=" color: #8e8e8e; margin: 0; margin: 0px; line-height: 1.6;"> Copyright &copy; ' . date('Y') . ', All rights reserved.</p>
                              </div>
                            </div>
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
