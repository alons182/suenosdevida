<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;


class Gain extends Model {

    use PresentableTrait;


    protected $table = 'gains';

    protected $fillable = [
        'user_id','description','amount','gain_type', 'month','year', 'level'
    ];

    public function setAmountAttribute($amount)
    {
        $this->attributes['amount'] = (number($amount) == "") ? 0 : number($amount);
    }


    public function users()
    {
        return $this->belongsTo('App\User','user_id');
    }


}
