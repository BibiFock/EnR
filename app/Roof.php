<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Roof\Tilt;
use App\Roof\Historical;

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
        'street', 'zip', 'city',
        // relations
        'owner_id', 'structure_id',
        'purchase_category_id'
    ];

    protected $casts = [
        'erp' => 'boolean',
        'perimeter_abf' => 'boolean'
    ];


    protected $toLoad = [
        'owner', 'structure', 'purchaseCategory', 'tilts'
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
            $this->owner()->associate($this->owner);
        }

        $output = parent::save($options);

        // on sauvegarde tout
        $ids = $this->tilts->map(function ($item) {
            $item->roof_id = $this->id;
            $item->save();
            return $item->id;
        });

        // on dégage les toitures absentes
        $tilts = Tilt::where('roof_id', $this->id)
            ->whereNotIn('id', $ids->toArray())
            ->get()
            ->each(function ($tilt) {
                $tilt->delete();
            });

        return $output;
    }

    public function saveState($userId)
    {
        $hist = new Historical([
            'roof_id' => $this->id,
            'state' => $this->getCurrentState(),
            'user_id' => $userId
        ]);

        $hist->save();
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

    public function purchaseCategory()
    {
        return $this->belongsTo('\App\Roof\PurchaseCategory');
    }

    public function states()
    {
        return $this->hasMany('\App\Roof\Historical');
    }

    public function tilts()
    {
        return $this->hasMany('\App\Roof\Tilt');
    }

    public function getCurrentState()
    {
        return $this->loadMissing($this->toLoad);
    }

    protected function initExtrasDatas($params = [])
    {
        if (!empty($params['owner'])) {
            $this->loadOwner($params['owner']);
        }

        if (!empty($params['tilts'])) {
            $this->loadTilts($params['tilts']);
        }
    }

    protected function loadTilts($params)
    {
        $nbTilts = $this->tilts->count();
        foreach ($params as $k => $param) {
            if (!empty($this->tilts->get($k))) {
                $this->tilts->get($k)->fill($param);
                continue;
            }
            $tilt = new Tilt();
            $tilt->fill($param);
            $this->tilts->push($tilt);
        }

        if ($nbTilts <= count($params)) {
            return true;
        }

        $this->tilts->splice(count($params), $nbTilts - count($params));
    }

    protected function loadOwner($params)
    {
        if (empty($params['contact']) && empty($params['type_id'])) {
            return true;
        }

        if (empty($params['contact']['first_name'])
            && empty($params['contact']['last_name'])
        ) {
            return true;
        }

        if (!empty($this->owner)) {
            if (!empty($params['contact'])) {
                $this->owner->contact->fill($params['contact']);
            }
            $this->owner->type_id = $params['type_id'];
        } else {
            $owner = $this->owner()->make([
                'name' => '',
                'type_id' => $params['type_id'],
            ]);

            $this->owner()->associate($owner);

            $contact = $this->owner->contact()->make($params['contact']);

            $this->owner->contact()->associate($contact);
        }
        $this->owner->name = $this->owner->contact->first_name . ' '
            . $this->owner->contact->last_name;
    }
}
