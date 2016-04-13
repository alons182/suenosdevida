<?php namespace App\Repositories;

use App\Shop;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;



class DbShopRepository extends DbRepository implements ShopRepository {

    protected $model;

    function __construct(Shop $model)
    {
        $this->model = $model;
        $this->limit = 10;
    }

    /**
     * Save a product
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $data = $this->prepareData($data);

        $data['image'] = (isset($data['image'])) ? $this->storeImage($data['image'], $data['name'], 'shops', null, null, 640, null) : '';
        $data['logo'] = (isset($data['logo'])) ? $this->storeImage($data['logo'], 'logo-'.$data['name'], 'shops', null, null, 640, null) : '';

        $shop = $this->model->create($data);

        return $shop;
    }

    /**
     * Update a product
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $shop = $this->model->findOrFail($id);
        $data = $this->prepareData($data);

        $data['image'] = (isset($data['image'])) ? $this->storeImage($data['image'], $data['name'], 'shops', null, null, 640, null) : $shop->image;
        $data['logo'] = (isset($data['logo'])) ? $this->storeImage($data['logo'], 'logo-'.$data['name'], 'shops', null, null, 640, null) : $shop->logo;

        $shop->fill($data);
        $shop->save();

        return $shop;
    }




    /**
     * Delete a product by ID
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $shop = $this->findById($id);
        $image_delete = $shop->image;
        $logo_delete = $shop->logo;

        $shop->delete();

        File::delete(dir_photos_path('shops') . $image_delete);
        File::delete(dir_photos_path('shops') . 'thumb_' . $image_delete);
        File::delete(dir_photos_path('shops') . $logo_delete);
        File::delete(dir_photos_path('shops') . 'thumb_' . $logo_delete);


        return $shop;
    }


    /**
     * Find a product by ID
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Find a product by Slug
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug)
    {
        return $this->model->SearchSlug($slug)->first();
    }

    /**
     * Find a shop by canton
     * @param $canton
     * @return mixed
     */
    public function findByCanton($canton)
    {

        $shops = $this->model->where(function ($query) use ($canton) {
            $query->where('canton', '=', $canton)
                ->where('published', '=', 1);

        })->paginate($this->limit);



        return $shops;
    }

    /**
     * Get all the shops for the admin panel
     * @param $search
     * @return mixed
     */
    public function getAll($search)
    {

        $shops = $this->model;


        if (isset($search['q']) && ! empty($search['q']))
        {
            $shops = $shops->Search($search['q']);
        }

        if (isset($search['published']) && $search['published'] != "")
        {
            $shops = $shops->where('published', '=', $search['published']);
        }
        if (isset($search['canton']) && $search['canton'] != "")
        {
            $shops = $shops->where('canton', '=', $search['canton']);
        }

        return $shops->orderBy('created_at', 'desc')->paginate($this->limit);
    }

    public function list_shops($value = null, $search = null)
    {


        $shops = $this->model->where('published', '=', 1)->get();

        return $shops;
    }

    public function getCantonesWithShops()
    {
        /*$cantones = [
            [
                "name_id" => 'liberia',
                "title"=> 'Liberia'
            ],
            [
                "name_id"=> 'nicoya',
                "title"=> 'Nicoya'
            ],
            [
                "name_id"=> 'santa_cruz',
                "title"=> 'Santa Cruz'
            ],
            [
                "name_id"=> 'bagaces',
                "title"=> 'Bagaces'
            ],
            [
                "name_id"=> 'carrillo',
                "title"=> 'Carrillo'
            ],
            [
                "name_id"=> 'canas',
                "title"=> 'Cañas'
            ],
            [
                "name_id"=> 'abangares',
                "title"=> 'Abangares'
            ],
            [
                "name_id"=> 'tilaran',
                "title"=> 'Tilarán'
            ],
            [
                "name_id"=> 'nandayure',
                "title"=> 'Nandayure'
            ],
            [
                "name_id"=> 'la_cruz',
                "title"=> 'La Cruz'
            ],
            [
                "name_id"=> 'hojancha',
                "title"=> 'Hojancha'
            ]

        ];*/
        $shops = $this->model->where('published', '=',1)->get();
        $cantones = [];
        foreach($shops as $shop)
        {
            $cantones [] = $shop->canton;
        }
        $cantones = array_unique($cantones);

       return $cantones;
    }



    /**
     * @param $data
     * @return mixed
     */
    private function prepareData($data)
    {
        $data['slug'] = Str::slug($data['name']);

        return $data;
    }
}