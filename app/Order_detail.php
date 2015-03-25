<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model {

    protected $table = 'orders_details';

    protected $fillable = [
        'order_id','product_id','quantity'
    ];

    public function orders()
    {
        return $this->belongsTo('App\Order','order_id');
    }
    public function products()
    {
        return $this->belongsTo('App\Product','product_id');
    }
}