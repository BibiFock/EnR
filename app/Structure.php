<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{

    const INIT_STRUCTURE_ID = 1;

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

    public static function getAllInitStructure()
    {
        return static::where('type_id', static::INIT_STRUCTURE_ID)->get();
    }

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

    public function save(array $options = [])
    {
        if (!empty($this->contact)) {
            $this->contact->push();
            $this->contact()->associate($this->contact);
        }
        return parent::save($options);
    }

}
