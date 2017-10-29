<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'zip', 'name',
    ];

    public function roofs()
    {
        return $this->hasMany('App\Roof', 'department_id');
    }
}
