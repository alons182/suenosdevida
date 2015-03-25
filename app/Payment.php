<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;


class Payment extends Model {

    use PresentableTrait;

    protected $presenter = 'App\Presenters\PaymentPresenter';
    protected $table = 'payments';

    protected $fillable = [
        'user_id','bank','transfer_number','transfer_date','amount','possible_gain','gain','payment_type','membership_cost'
    ];

    public function setAmountAttribute($amount)
    {
        $this->attributes['amount'] = (number($amount) == "") ? 0 : number($amount);
    }
    public function setGainAttribute($gain)
    {
        $this->attributes['gain'] = (number($gain) == "") ? 0 : number($gain);
    }

    public function users()
    {
        return $this->belongsTo('App\User','user_id');
    }


}
