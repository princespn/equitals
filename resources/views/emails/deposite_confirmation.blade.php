<?php

$logo = $message->embed(public_path() . '/user_files/img/logo.png');
$congrats = $message->embed(public_path() . '/user_files/img/Congratulations1.png');
$backimg = $message->embed(public_path() . '/user_files/img/background1.png');
$thanks =$message->embed(public_path() . '/user_files/img/thank_you1.png');

//$logo = $message->embed(public_path() . '/img/logo.png');
//$emaillogo = $message->embed(public_path() . '/img/email.png');
//$facebook = $message->embed(public_path() . '/img/facebook.png');
$project_name = Config::get('constants.settings.projectname');
$path = Config::get('constants.settings.domainpath');
$url = Config::get('constants.settings.domain');
echo $msg = '<html>
  <head>
    <meta charset="utf-8">
    <title>Confirmation Mail </title>
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
                                <img src="' . $congrats . '" alt="">
                              </div>
                              <h2 style="text-align: center;margin: 10px auto;color:#23D0E1;font-size: 26px;font-weight: 900;line-height: 35px;"> 
                                 Password Reset Successfully
                              </h2>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              
                              <p style="text-align:center;margin: 20px auto;font-size: 16px;font-weight: 700;color:#000;line-height:24px">
                                Thank you for using password recovery option. .
                              </p>
                           
                              
                              <p style="margin-top:30px;margin-bottom:15px;font-weight:600;font-size:16px;text-align: center;color: #000;"> You password reset successfully.</p>

                          <p style="margin-top:20px;margin-bottom:15px;font-weight:600;font-size:16px;color: #000;line-height:24px">
                                 Regards<br>
                                 Team '.$projectname.'<br>
                                 <a href='.$url.'>'.$url.'</a>
                              </p>
                               <p style="margin-top:30px;margin-bottom:15px;font-weight:600;font-size:16px;text-align: center;color: #000;line-height:24px">  Greetings on your successful confirmation of your deposit '.$amount.' with order ID '.$order_id.'</p>
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