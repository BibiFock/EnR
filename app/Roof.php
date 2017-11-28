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
        'purchase_category_id', 'type_id', 'tilt_id'
        // , 'department_id'
    ];

    protected $casts = [
        'erp' => 'boolean',
    ];

    protected $hidden = [
        'department_id'
    ];

    public function __construct($params = [])
    {
        parent::__construct($params);
        $this->initExtrasDatas($params);
    }

    public function fill(array $params)
    {
        parent::fill($params);
        $this->initExtrasDatas($params);
    }

    public function save(array $options = [])
    {
        if ($this->owner) {
            $this->owner->push();
        }

        return parent::save($options);
    }

    public function owner()
    {
        return $this->belongsTo('\App\Structure');
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

    protected function initExtrasDatas($params = [])
    {
        if (!empty($params['owner'])) {
            $this->loadOwner($params['owner']);
        }
    }

    protected function loadOwner($params)
    {
        if (!empty($this->owner)) {
            if (!empty($params['contact'])) {
                $this->owner->contact->fill($params['contact']);
            }
            $this->owner->type_id = $params['type_id'];
        } else {
            $owner = $this->owner()->create([
                'name' => '',
                'type_id' => $params['type_id'],
            ]);

            $this->owner()->associate($owner);

            $contact = $this->owner->contact()->create($params['contact']);

            $this->owner->contact()->associate($contact);
        }
        $this->owner->name = $this->owner->contact->first_name . ' '
            . $this->owner->contact->last_name;
    }
}
