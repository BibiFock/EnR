<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StructureType extends Model
{

    protected $table = 'structure_type';
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
