<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Product extends Model {

    use PresentableTrait;

    protected $presenter = 'App\Presenters\ProductPresenter';
    protected $table = 'products';

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'promo_price', 'discount', 'image', 'sizes', 'colors', 'related', 'published', 'featured','shop_id'
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

    public function setPriceAttribute($price)
    {
        $this->attributes['price'] = (number($price) == "") ? 0 : number($price);
    }

    public function setPromoPriceAttribute($promo_price)
    {
        $this->attributes['promo_price'] = (number($promo_price) == "") ? 0 : number($promo_price);
    }

    public function setDiscountAttribute($discount)
    {
        $this->attributes['discount'] = (number($discount) == "") ? 0 : number($discount);
    }

    public function setSizesAttribute($sizes)
    {
        $this->attributes['sizes'] = json_encode($sizes);
    }

    public function setColorsAttribute($sizes)
    {
        $this->attributes['colors'] = json_encode($sizes);
    }

    public function setRelatedAttribute($related)
    {
        $this->attributes['related'] = json_encode($related);
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function order_detail()
    {
        return $this->hasOne('App\Order_detail');
    }

    public function photos()
    {
        return $this->hasMany('App\Photo');
    }
    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }

}
