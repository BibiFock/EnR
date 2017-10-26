<?php

namespace App\Roof;

use Illuminate\Database\Eloquent\Model;

class SouthOrientation extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name',
    ];

    public function roofs()
    {
        return $this->hasMany('App\Roof', 'south_orientation_id');
    }

}
