<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'slug'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function posts()
    {
    	return $this->belongsToMany('App\Post', 'post_labels');
    }
}
