<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roof extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'propability', 'square_area', 'power_max', 'power_min', 'erp',
        'building_size', 'perimeter_abf', 'remarks',
        'inverter_location', 'inverter_distance', 'street',
        'zip', 'city', 'latitude', 'longitude',
        // relations
        'owner_id', 'structure_id', 'south_orientation_id',
        'purchase_category_id', 'type_id', 'tilt_id', 'department_id'
    ];

    public function owner()
    {
        return $this->hasOne('\App\Contact', 'owner_id');
    }

    public function quotes()
    {
        return $this->hasMany('\App\Roof\Quote', 'roof_id');
    }

    public function structure()
    {
        return $this->belongsTo('\App\Structure', 'structure_id');
    }

    public function southOrientation()
    {
        return $this->belongsTo('\App\Roof\SouthOrientation', 'south_orientation_id');
    }

    public function purchaseCategory()
    {
        return $this->belongsTo('\App\Roof\PurchaseCategory', 'purchase_category_id');
    }

    public function type()
    {
        return $this->belongsTo('\App\Roof\Type', 'type_id');
    }

    public function tilt()
    {
        return $this->belongsTo('\App\Roof\Tilt', 'tilt_id');
    }

    public function department()
    {
        return $this->belongsTo('\App\Department', 'department_id');
    }

}
