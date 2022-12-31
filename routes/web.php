<?php


/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
/* header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Credentials: true'); */

// Route::any('generate-pass',function(){
// 	$arr = [];
// 	$str = '';
// 	$arr['md5'] = md5($str);
//  	$arr['bcrypt_pass'] = bcrypt($str);
// 	$arr['encrypt_pass'] = encrypt($str);
// 	dd($arr);
// });
  
Route::get('EqT2FyvKdAL6FW3gfCKszyU9clNc2hs', function () {
    return view('admin');
});
Route::get('user', function () {
    return view('user');
});