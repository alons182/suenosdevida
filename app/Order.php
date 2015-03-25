<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Order extends Model {

    use PresentableTrait;

    protected $presenter = 'App\Presenters\OrderPresenter';
    protected $table = 'orders';

    protected $fillable = [
        'user_id','description','total','status'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search)
        {
            $query->where('total', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    public function details()
    {
        return $this->hasMany('App\Order_detail');
    }
    public function users()
    {
        return $this->belongsTo('App\User','user_id');
    }



}