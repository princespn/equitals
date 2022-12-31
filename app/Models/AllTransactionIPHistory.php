<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllTransactionIPHistory extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_all_transactions_ip_history';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        ''
    ];
    public $timestamps = false; 
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}