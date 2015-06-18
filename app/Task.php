<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

    protected $table = 'tasks';

    protected $fillable = [
        'ad_id', 'user_id'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function Ad(){
        return $this->belongsTo('App\Ad');
    }

}
