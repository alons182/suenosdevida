<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Hit extends Model {

    protected $table = 'hits';

    protected $fillable = [
        'ad_id', 'hit_date', 'user_id','week_of_month','check'
    ];

    public function ad(){
        return $this->belongsTo('App\Ad');
    }

}
