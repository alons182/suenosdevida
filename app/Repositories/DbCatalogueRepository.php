<?php namespace App\Repositories;

use App\Catalogue;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;



class DbCatalogueRepository extends DbRepository implements CatalogueRepository {

    protected $model;

    function __construct(Catalogue $model)
    {
        $this->model = $model;
        $this->limit = 10;
    }


    /**
     * Save a category
     * @param $data
     * @return static
     */
    public function store($data)
    {
        $data = $this->prepareData($data);
        $data['image'] = (isset($data['image'])) ? $this->storeImage($data['image'], $data['slug'], 'catalogues', null, null, 640, null) : '';

        return $this->model->create($data);
    }

    /**
     * Update a category
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function update($id, $data)
    {
        $catalogue = $this->model->findOrFail($id);
        $data = $this->prepareData($data);
        $data['image'] = (isset($data['image'])) ? $this->storeImage($data['image'], $data['slug'], 'catalogues', null, null, 640, null) : $catalogue->image;

        $catalogue->fill($data);
        $catalogue->save();

        return $catalogue;
    }

    /**
     * Find a category by ID
     * @param $id
     * @return \Illuminate\Support\Collection|static
     */
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Delete a category by ID
     * @param $id
     * @return \Illuminate\Support\Collection|DbCategoryRepository|static
     */
    public function destroy($id)
    {
        $catalogue = $this->findById($id);
       // $image_delete = $catalogue->image;
        $catalogue->delete();

       // File::delete(dir_photos_path('catalogues') . $image_delete);
        //File::delete(dir_photos_path('catalogues') . 'thumb_' . $image_delete);

        return $catalogue;
    }



    /**
     * get all categories from admin control
     * @param $search
     * @return mixed
     */
    public function getAll($search)
    {
        if (isset($search['q']) && ! empty($search['q']))
        {
            $catalogues = $this->model->Search($search['q']);
        } else
        {
            $catalogues = $this->model;
        }

       
        if (isset($search['shop_id']) && $search['shop_id'] != "")
        {
            $catalogues = $catalogues->where('shop_id', '=', $search['shop_id']);
           

        }

        return $catalogues->orderBy('created_at')->paginate($this->limit);
    }

    


   

    /**
     * @param $data
     * @return mixed
     */
    private function prepareData($data)
    {
        // if (! $data['parent_id'])
        // {
        //     $data = array_except($data, array('parent_id'));
        // }
        // if($data['parent_id']== 'root')
        //     $data['parent_id'] = NULL;

         $data['slug'] = Str::slug($data['shop_id'].'-'.$data['name']);

        return $data;
    }

}