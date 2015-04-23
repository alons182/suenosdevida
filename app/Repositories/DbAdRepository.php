<?php namespace App\Repositories;


use App\Ad;
use App\Gain;
use App\Hit;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpSpec\Wrapper\Collaborator;

class DbAdRepository extends DbRepository implements AdRepository {

    protected $model;

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var GainRepository
     */
    private $gainRepository;

    /**
     * @param Ad $model
     * @param GainRepository $gainRepository
     * @param UserRepository $userRepository
     *
     */
    function __construct(Ad $model, GainRepository $gainRepository, UserRepository $userRepository)
    {
        $this->model = $model;
        $this->limit = 15;
        $this->userRepository = $userRepository;
        $this->gainRepository = $gainRepository;
    }

    public function store($data)
    {
        $data = $this->prepareData($data);
        $data['image'] = (isset($data['image'])) ? $this->storeImage($data['image'], $data['name'], 'ads', 1024, null, 200, 200) : '';

        $ad = $this->model->create($data);

        return $ad;
    }

    public function checkAd($ad, $user_id)
    {
        $hit = new Hit;
        $hit->hit_date = Carbon::now();
        $hit->week_of_month = Carbon::now()->weekOfMonth;
        $hit->check = 1;
        $hit->user_id = $user_id;
        $ad->hits()->save($hit);
        $this->generateGainForClick($ad);
        if($this->userRepository->completeAds($user_id))
            $this->userRepository->checkLevel($user_id);

        return $ad;
    }

   public function generateGainForClick($ad = null, $user = null)
    {
        $user = ($user) ? $user : Auth::user();
        $data['month'] = Carbon::now()->month;

        $possible_gain = $this->gainRepository->getPossibleGainsPerAffiliates($data,$user);
        $daysForMonth =  Carbon::now()->daysInMonth;
        $countAdsForView = $daysForMonth * 5;

        $gain_click = $possible_gain / $countAdsForView;

        $gain = new Gain();
        $gain->user_id = $user->id;
        $gain->description = 'Generado por ver la publicidad '. $ad->name;
        $gain->amount = $gain_click;
        $gain->gain_type = 'C';
        $gain->month = Carbon::now()->month;
        $gain->year = Carbon::now()->year;
        $gain->save();

        return $gain;
    }

    /**
     * Find a Ad by ID
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Find a Ad by Slug
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug)
    {
        return $this->model->SearchSlug($slug)->first();
    }
    public function hits_per_week($user_id)
    {
        $hits_per_week = Hit::where(function ($query) use ($user_id)
        {
            $query->where('user_id', '=', $user_id)
                ->where('week_of_month', '=', Carbon::now()->weekOfMonth)
                ->where(\DB::raw('MONTH(hit_date)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(hit_date)'), '=', Carbon::now()->year);
        })->count();



        return $hits_per_week;
    }

    public function getAds($zone, $user_id)
    {
        //dd(Carbon::now()->weekOfMonth);
        $ads = $this->model->with('hits')->where(function ($query) use ($zone)
        {
            $query->where('canton', '=', $zone)
                ->where(\DB::raw('MONTH(publish_date)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(publish_date)'), '=', Carbon::now()->year);
        })->get();

        $adsWithHits = $this->model->whereHas('hits', function ($q) use ($user_id)
        {
            $q->where('user_id', '=', $user_id);
            //->where(\DB::raw('DAY(hit_date)'), '=', Carbon::now()->day)
            // ->where(\DB::raw('MONTH(hit_date)'), '=', Carbon::now()->month)
            //->where(\DB::raw('YEAR(hit_date)'), '=', Carbon::now()->year);

        })->where(function ($query) use ($zone)
        {
            $query->where('canton', '=', $zone)
                ->where(\DB::raw('MONTH(publish_date)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(publish_date)'), '=', Carbon::now()->year);
        })->get();


        $totalAdsCount = $ads->count() - $adsWithHits->count();

        if( $ads->count() > 5)
        {
            for($i = 1;  $i <= (5-$totalAdsCount); $i++ )
            {
                $ad = $this->model->findOrFail($i);

                $ad->hits->last()->check = 0;
                $ad->hits->last()->save();
            }
        }

        return $ads;
    }

    public function getAll($search)
    {

        $ads = $this->model;


        if (isset($search['q']) && ! empty($search['q']))
        {
            $ads = $ads->Search($search['q']);
        }

        if (isset($search['published']) && $search['published'] != "")
        {
            $ads = $ads->where('published', '=', $search['published']);
        }

        return $ads->orderBy('created_at', 'desc')->paginate($this->limit);

        return $this->model->paginate($this->limit);
    }

    public function update($id, $data)
    {
        $ad = $this->model->findOrFail($id);
        $data = $this->prepareData($data);

        $data['image'] = (isset($data['image'])) ? $this->storeImage($data['image'], $data['name'], 'ads', 1024, null, 200, 200) : $ad->image;

        $ad->fill($data);
        $ad->save();

        return $ad;
    }

    public function destroy($id)
    {
        $ad = $this->findById($id);
        $image_delete = $ad->image;
        $ad->delete();

        File::delete(dir_photos_path('ads') . $image_delete);
        File::delete(dir_photos_path('ads') . 'thumb_' . $image_delete);


        return $ad;
    }

    private function prepareData($data)
    {
        $data['slug'] = Str::slug($data['name']);
        $data['publish_date'] = $data['publish_date_submit'];

        return $data;
    }

}