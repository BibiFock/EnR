<?php

namespace App\Roof;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'total_off', 'roi_off', 'total', 'roi', 'panel_type',
        'inverter_type', 'guarantee', 'certifications', 'remarks',
        // relations
        'roof_id'
    ];

    public function roof()
    {
        return $this->belongsTo('App\Roof', 'roof_id');
    }

}
