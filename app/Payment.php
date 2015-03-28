<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;


class Payment extends Model {

    use PresentableTrait;

    protected $presenter = 'App\Presenters\PaymentPresenter';
    protected $table = 'payments';

    protected $fillable = [
        'user_id', 'bank', 'transfer_number', 'transfer_date', 'amount', 'possible_gain', 'gain', 'payment_type', 'membership_cost', 'click_cost'
    ];

    public function setAmountAttribute($amount)
    {
        $this->attributes['amount'] = (number($amount) == "") ? 0 : number($amount);
    }

    public function setGainAttribute($gain)
    {
        $this->attributes['gain'] = (number($gain) == "") ? 0 : number($gain);
    }

    /**
     * @param $amount
     * @param $user_id
     * @param null $description
     * @return mixed
     * @internal param $membership_cost
     * @internal param null $gain
     */
    public function generateGain($amount, $user_id, $description = null)
    {

        $gain = new Gain();
        $gain->user_id = $user_id;
        $gain->description = $description;
        $gain->amount = $amount;
        $gain->month = Carbon::now()->month;
        $gain->year = Carbon::now()->year;

        return $gain->save();
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }


}
