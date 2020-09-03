<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [

    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function posts()
    {
    	return $this->hasMany('App\Post');
    }

    public function scopeReal($query)
    {
    	return $query->where('id', '<>', 1);
    }
}
