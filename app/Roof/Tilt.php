<?php

namespace App\Roof;

use Illuminate\Database\Eloquent\Model;

class Tilt extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slope', 'ground_square_area', 'occupancy_rate', 'south_orientation',
        'inverter_location', 'inverter_distance',
        'latitude', 'longitude',
        // relations
        'roof_id', 'type_id'
    ];

    public function roof()
    {
        return $this->belongsTo('App\Roof', 'roof_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Roof\Tilt\Type', 'type_id');
    }

}
