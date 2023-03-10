<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_video';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        ''
    ];
    public $timestamps = true; 

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [];

    
}