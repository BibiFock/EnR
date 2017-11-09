<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roof extends Model
{

    const PROBABILITIES = array('acquise', 'forte', 'moyenne', 'faible');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'probability', 'square_area', 'power_max', 'power_min',
        'erp', 'building_size', 'perimeter_abf', 'remarks',
        'inverter_location', 'inverter_distance', 'street',
        'zip', 'city', 'latitude', 'longitude',
        // relations
        'owner_id', 'structure_id', 'south_orientation_id',
        'purchase_category_id', 'type_id', 'tilt_id', 'department_id'
    ];

    public function owner()
    {
        return $this->belongsTo('\App\Contact');
    }

    public function quotes()
    {
        return $this->hasMany('\App\Roof\Quote');
    }

    public function structure()
    {
        return $this->belongsTo('\App\Structure');
    }

    public function southOrientation()
    {
        return $this->belongsTo('\App\Roof\SouthOrientation');
    }

    public function purchaseCategory()
    {
        return $this->belongsTo('\App\Roof\PurchaseCategory');
    }

    public function type()
    {
        return $this->belongsTo('\App\Roof\Type');
    }

    public function tilt()
    {
        return $this->belongsTo('\App\Roof\Tilt');
    }

    public function department()
    {
        return $this->belongsTo('\App\Department');
    }

}
