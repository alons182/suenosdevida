<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    protected $table = 'catalogues';

    protected $fillable = [
        'name', 'url','image','shop_id'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('url', 'like', '%' . $search . '%');
        });
    }


    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }
}
