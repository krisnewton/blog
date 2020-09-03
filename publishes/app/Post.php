<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [

    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function post_labels()
    {
        return $this->hasMany('App\PostLabel');
    }

    public function labels()
    {
        return $this->belongsToMany('App\Label', 'post_labels');
    }

    public function get_labels()
    {
        $labels = $this->labels;
        $output = [];
        foreach ($labels as $label) {
            $output[] = $label->name;
        }
        return implode(', ', $output);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
