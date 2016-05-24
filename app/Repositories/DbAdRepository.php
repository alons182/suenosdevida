<?php namespace App\Repositories;


use App\Ad;
use App\GalleryAd;
use App\Hit;
use App\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


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
        $data['company_logo'] = (isset($data['company_logo'])) ? $this->storeImage($data['company_logo'], 'logo-'.$data['company_name'], 'ads', 400, null, 200, 200) : '';

        $ad = $this->model->create($data);
        $this->sync_photos($ad, $data);
        return $ad;
    }
    /**
     * Save the photos of the product
     * @param $ad
     * @param $data
     */
    public function sync_photos($ad, $data)
    {
        if (isset($data['new_photo_file']))
        {
            $cant = count($data['new_photo_file']);
            foreach ($data['new_photo_file'] as $photo)
            {
                $filename = $this->storeImage($photo, 'photo_' . $cant --, 'ads/' . $ad->id, null, null, 300, null);
                $photos = new GalleryAd;
                $photos->url = $filename;
                $photos->url_thumb = 'thumb_' . $filename;
                $ad->gallery()->save($photos);
            }
        }

    }

    public function checkAd($ad, $user_id)
    {
        $hit = new Hit;
        $hit->hit_date = Carbon::now();
        $hit->week_of_month = Carbon::now()->weekOfMonth;
        $hit->check = 1;
        $hit->user_id = $user_id;
        $ad->hits()->save($hit);

        $task = new Task();
        $task->user_id = $user_id;
        $task->ad_id = $ad->id;
        $task->save();

        $this->checkCompleteAllAds($user_id);

        return $ad;
    }

    public function checkCompleteAllAds($user_id)
    {
        $zone = Auth::user()->profiles->canton;

        $adsWithHitsIds = $this->model->whereHas('hits', function ($q) use ($user_id)
        {
            $q->where('user_id', '=', $user_id);

        })->where(function ($query) use ($zone)
        {
            $query->where('canton', '=', $zone)
                ->where(\DB::raw('MONTH(publish_date)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(publish_date)'), '=', Carbon::now()->year);
        })->get()->lists('id')->all();

        $adsWithoutHits = $this->model->with(['hits' => function ($query) use ($user_id) {
            $query->where('user_id', '=', $user_id);

        }])->where(function ($query) use ($zone, $adsWithHitsIds)
        {
            $query->whereNotIn('id', $adsWithHitsIds)
                ->where('canton', '=', $zone)
                ->where(\DB::raw('MONTH(publish_date)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(publish_date)'), '=', Carbon::now()->year);
        })->get();

        $ads = $this->model->with(['hits' => function ($query) use ($user_id) {
            $query->where('user_id', '=', $user_id)
                  ->where(\DB::raw('DAY(hit_date)'), '<>', Carbon::now()->day );
        }])->where(function ($query) use ($zone)
        {
            $query->where('canton', '=', $zone)
                ->where(\DB::raw('MONTH(publish_date)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(publish_date)'), '=', Carbon::now()->year);
        })->get();

        if($adsWithoutHits->count() == 0)
        {

            foreach($ads as $ad)
            {
                if($ad->hits->count() > 0)
                {
                   foreach($ad->hits as $hit)
                    {
                        if($hit->user_id == $user_id)
                            $hit->delete();
                    }
                }


            }
        }
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

    public function hits_per_day($user_id)
    {
        $hits_per_day = Task::where(function ($query) use ($user_id)
        {
            $query->where('user_id', '=', $user_id)
                ->where(\DB::raw('DAY(created_at)'), '=', Carbon::now()->day)
                ->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
        })->count();


        return $hits_per_day;
    }
    public function hits_per_week($user_id)
    {
        $hits_per_week = [];
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $tasks = Task::where(function ($query) use ($user_id)
        {
            $query->where('user_id', '=', $user_id)
                 ->where(\DB::raw('MONTH(created_at)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(created_at)'), '=', Carbon::now()->year);
        })->get();

        foreach($tasks as $task)
        {
            //if($task->created_at->weekOfMonth == Carbon::now()->weekOfMonth) $hits_per_week[] = $task;
            if($task->created_at->between($startOfWeek, $endOfWeek)) $hits_per_week[] = $task;
        }
       /* $tasksOfWeek = array_filter($tasks->toArray(),function($task){

            return $task->created_at == Carbon::now()->weekOfMonth;
        });*/

        //dd(count($hits_per_week));
        return count($hits_per_week);
    }

    public function getAds($zone, $user_id)
    {


        $adsTotal = $this->model->with(['hits' => function ($query) use ($user_id) {
            $query->where('user_id', '=', $user_id);

        }])->where(function ($query) use ($zone)
        {
            $query->where('all_country', '=', 1)
                ->orWhere('canton', '=', $zone)
                ->where(\DB::raw('MONTH(publish_date)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(publish_date)'), '=', Carbon::now()->year);
        })->get();

        $adsWithHitsToday = $this->model->whereHas('hits', function ($q) use ($user_id)
        {
            $q->where('user_id', '=', $user_id)
                ->where(\DB::raw('day(hit_date)'), '=', Carbon::now()->day)
                ->where(\DB::raw('MONTH(hit_date)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(hit_date)'), '=', Carbon::now()->year);
        })->where(function ($query) use ($zone)
        {
            $query->where('canton', '=', $zone)
                ->where(\DB::raw('MONTH(publish_date)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(publish_date)'), '=', Carbon::now()->year);
        })->get();


        if($adsTotal->count() <= 5 && $adsWithHitsToday->count() == 0)
        {

            foreach($adsTotal as $ad)
            {
                if($ad->hits->count() > 0)
                {
                    foreach($ad->hits as $hit)
                    {
                        if($hit->user_id == $user_id)
                            $hit->delete();
                    }
                }


            }
        }

        $ads = $this->model->with(['hits' => function ($query) use ($user_id) {
            $query->where('user_id', '=', $user_id);

        }])->where(function ($query) use ($zone)
        {
            $query->where('all_country', '=', 1)
                ->orWhere('canton', '=', $zone)
                ->where(\DB::raw('MONTH(publish_date)'), '=', Carbon::now()->month)
                ->where(\DB::raw('YEAR(publish_date)'), '=', Carbon::now()->year);
        })->orderBy(\DB::raw('RAND()'))->get();


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

    }

    public function update($id, $data)
    {
        $ad = $this->model->findOrFail($id);
        $data = $this->prepareData($data);

        $data['image'] = (isset($data['image'])) ? $this->storeImage($data['image'], $data['name'], 'ads', 1024, null, 200, 200) : $ad->image;
        $data['company_logo'] = (isset($data['company_logo'])) ? $this->storeImage($data['company_logo'], 'logo-'.$data['company_name'], 'ads', 400, null, 200, 200) : $ad->company_logo;

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