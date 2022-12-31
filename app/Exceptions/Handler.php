<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Config;

class Handler extends ExceptionHandler {

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
            //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception) {
       // dd($exception);
        if ($this->shouldReport($exception)) {
            $this->sendEmail($exception); // sends an email
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception) {

         dd($exception);
        $this->statuscode =Config::get('constants.statuscode');
      //  return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'],'Please logout and login again session is expired',''); 
        //return parent::render($request, $exception);
    }


    public function sendEmail(Exception $exception) {
        $subject = $exception->getMessage();
        $pagename = "emails.error_notification";
        $data = array('pagename' => $pagename, 'email' => 'test@test.com', 'msg' => $exception->getMessage());
        $email = 'test@test.com';
        /*$mail = sendMail($data, $email, $subject);*/
    }

}
