<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AchievedUserMatchingBonus extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_achieved_user_matching_bonus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        ''
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    
    /**
     * false timestamp created_at and updated_at in table
     *
     * @var array
     */
    public $timestamps = false;
}