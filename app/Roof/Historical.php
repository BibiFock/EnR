<?php

namespace App\Roof;

use Illuminate\Database\Eloquent\Model;

class Historical extends Model
{
    protected $table = 'roof_historicals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'roof_id', 'user_id', 'state',
        'created_at', 'updated_at'
    ];

    public function roof()
    {
        return $this->belongsTo('App\Roof');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function save(array $options = [])
    {
        $lastHist = Historical::where('roof_id', $this->roof_id)
            ->orderBy('id', 'desc')
            ->first();
        if ($this->state == $lastHist->state) {
            // si on sauvegarde la même chose on oublie
            return false;
        }

        return parent::save($options);
    }
}
