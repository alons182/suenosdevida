<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GalleryAd extends Model {

    protected $table = 'galleryAds';

    protected $fillable = [

        'ad_id', 'url', 'url_thumb'

    ];


    public function ads()
    {
        return $this->belongsTo('App\Ad');
    }
}
