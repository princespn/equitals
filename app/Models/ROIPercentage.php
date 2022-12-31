<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ROIPercentage extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_roi_percentage';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'srno','date','percentage','updated_time','status'
    ];
    public $timestamps = false; 

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}