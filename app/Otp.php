<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
  
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_user_otp_magic';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    public $timestamps = false; 
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}