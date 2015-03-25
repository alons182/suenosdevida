<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Profile extends Model {

    use PresentableTrait;
    protected $presenter = 'App\Presenters\ProfilePresenter';
    protected $table = 'profiles';

    protected $fillable = [
        'first_name','last_name','ide','address','code_zip','telephone','country','province','canton',
        'city','bank','type_account','number_account','skype'
    ];

    public function users()
    {
        return $this->belongsTo('App\User');
    }
}
