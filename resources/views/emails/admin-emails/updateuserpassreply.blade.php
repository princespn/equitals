<?php
$logo = $message->embed(public_path() . '/user_files/img/logo.png');
$mailicon = $message->embed(public_path() . '/user_files/img/mailicon.png');
$backimg = $message->embed(public_path() . '/user_files/img/background1.png');
$thanks =$message->embed(public_path() . '/user_files/img/thank_you1.png');

$emaillogo    = $message->embed(public_path() . '/img/email.png');
$facebook     = $message->embed(public_path() . '/img/facebook.png');
$projectname  = Config::get('constants.settings.projectname');
$path         = Config::get('constants.settings.domainpath');
echo  '<!DOCTYPE HTML>
<html><head>
   <title>Enquiry Mail</title>
         <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@500&display=swap" rel="stylesheet">
   </head>
   <body style=" padding:10px;  font-family: Poppins, sans-serif;background:#6BB4C8">
         <table width="600" cellspacing="0" cellpadding="0" align="center" style="background:#fff url(' . $backimg . ') no-repeat;background-size: contain;background-position: center bottom;padding: 20px 35px;min-height: 500px;border-radius: 15px;box-shadow:0px 3px 50px #ffffff82">
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
                              
                              <p style="text-align:center;margin: 20px auto;font-size: 16px;font-weight: 700;color:#000">
                                '.$msg.'
                               
                              </p>
                              
                             
                             
                            <div class="wrapper-footer" style=" padding: 10px; text-align: center;">
                     <h3 style="font-size: 20px;font-weight: 200;margin: 10px;">Stay in touch</h3>
                     <a href="#">
                     <img src=".$facebook." alt="" style="width:30px"> 
                     </a>
                     <div class="copyright">
                        <p style="margin: 0;margin-top: 10px;line-height: 1.6;"> Email send by '.$projectname.'</p>
                        <p style="margin: 0;margin: 0px;line-height: 1.6;"> Copyright © '.date('Y').', All rights reserved.</p>
                     </div>
                  </div>
                             <p style="text-align:center;margin-top:20px">
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
   </body></html>';