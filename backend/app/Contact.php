<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'first_name', 'last_name', 'phone', 'email'
    ];

    public function structures()
    {
        return $this->hasMany('App\Structure', 'contact_id');
    }

}
