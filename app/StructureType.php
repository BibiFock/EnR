<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StructureType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name',
    ];

    public function structures()
    {
        return $this->hasMany('App\Structure', 'type_id');
    }
}
