<?php

namespace App\Roof\Tilt;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'tilt_types';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name',
    ];

    public function tilts()
    {
        return $this->hasMany('App\Roof\Tilt', 'type_id');
    }

}
