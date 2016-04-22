<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shops';

    protected $fillable = [
        'name', 'slug','canton','information', 'details', 'logo', 'image', 'published','responsable_id'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%');

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
    public function products()
    {
        return $this->hasMany('App\Product');
    }
    public function categories()
    {
        return $this->hasMany('App\Category');
    }
    /*public function orders()
    {
        return $this->hasMany('App\Order');
    }*/
    public function responsable()
    {
        return $this->hasOne('App\User','id','responsable_id');
    }

}
