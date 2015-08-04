<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model {

    protected $table = 'ads';

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'video', 'province', 'canton','email','all_country', 'published', 'featured','publish_date'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    public function scopeSearchSlug($query, $search)
    {
        return $query->where(function ($query) use ($search)
        {
            $query->where('slug', '=', $search)
                ->where('published', '=', 1);
        });
    }

    public function scopeFeatured($query)
    {
        return $query->where(function ($query)
        {
            $query->where('featured', '=', 1)
                ->where('published', '=', 1);
        });
    }

    public function hits()
    {
       return $this->hasMany('App\Hit');
    }
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

}
