<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name',
        // relations
        'contact_id', 'type_id'
    ];

    public function contact()
    {
        return $this->belongsTo('App\Contact', 'contact_id');
    }

    public function type()
    {
        return $this->belongsTo('App\StructureType', 'type_id');
    }

    public function roofs()
    {
        return $this->hasMany('App\Roof', 'structure_id');
    }

    public function ownsRoof()
    {
        return $this->hasMany('App\Roof', 'owner_id');
    }


}
