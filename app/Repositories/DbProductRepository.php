<?php namespace App\Repositories;

use App\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Category;

use App\Photo;


class DbProductRepository extends DbRepository implements ProductRepository {

    protected $model;

    function __construct(Product $model)
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
        $data['image'] = (isset($data['image'])) ? $this->storeImage($data['image'], $data['slug'], 'products', null, null, 640, null) : '';

        $product = $this->model->create($data);
        $this->sync_categories($product, $data['categories']);
        $this->sync_photos($product, $data);

        return $product;
    }

    /**
     * Update a product
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $product = $this->model->findOrFail($id);
        $data = $this->prepareData($data);

        $data['image'] = (isset($data['image'])) ? $this->storeImage($data['image'], $data['slug'], 'products', null, null, 640, null) : $product->image;

        $product->fill($data);
        $product->save();
        $this->sync_categories($product, $data['categories']);

        return $product;
    }

    /**
     * Sync the categories of the product
     * @param $product
     * @param $categories
     */
    public function sync_categories($product, $categories)
    {
        $product->categories()->sync($categories);
    }


    /**
     * Save the photos of the product
     * @param $product
     * @param $data
     */
    public function sync_photos($product, $data)
    {
        if (isset($data['new_photo_file']))
        {
            $cant = count($data['new_photo_file']);
            foreach ($data['new_photo_file'] as $photo)
            {
                $filename = $this->storeImage($photo, 'photo_' . $cant --, 'products/' . $product->id, null, null, 50, null);
                $photos = new Photo;
                $photos->url = $filename;
                $photos->url_thumb = 'thumb_' . $filename;
                $product->photos()->save($photos);
            }
        }

    }

    /**
     * Delete a product by ID
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $product = $this->findById($id);
        $image_delete = $product->image;
        $photos_delete = $product->id;
        $product->delete();

        File::delete(dir_photos_path('products') . $image_delete);
        File::delete(dir_photos_path('products') . 'thumb_' . $image_delete);
        File::deleteDirectory(dir_photos_path('products') . $photos_delete);

        return $product;
    }


    /**
     * Find a product by ID
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->model->with('categories')->findOrFail($id);
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
     * Find a product by Category
     * @param $category
     * @return mixed
     */
    public function findByCategory($category)
    {

        $category = Category::searchSlug($category)->firstOrFail();

        $products = $category->products()->with('categories')->where(function ($query) use ($category)
        {
            $query->where('published', '=', 1)
                  ->where('shop_id', '=',$category->shop_id );

        })->paginate($this->limit);


        return $products;
    }

    /**
     * Get all the products for the admin panel
     * @param $search
     * @return mixed
     */
    public function getAll($search)
    {
        if (isset($search['cat']) && ! empty($search['cat']))
        {
            $category = Category::with('products')->findOrFail($search['cat']);
            $products = $category->products();

        } else
        {
            $products = $this->model;
        }

        if (isset($search['q']) && ! empty($search['q']))
        {
            $products = $products->Search($search['q']);
        }

        if (isset($search['published']) && $search['published'] != "")
        {
            $products = $products->where('published', '=', $search['published']);
        }
        if (isset($search['shop']) && $search['shop'] != "")
        {
            $products = $products->where('shop_id', '=', $search['shop']);
        }

        return $products->with('categories')->orderBy('created_at', 'desc')->paginate($this->limit);
    }

    /**
     * Get all the featured products for the store
     * @return mixed
     */
    public function getFeatured()
    {

        $products = $this->model->Featured()->get();
        if($products->count() > 4)
            $products = $products->random(4);
        
        return $products;
    }

    /**
     * get last products for the dashboard page
     * @return mixed
     */
    public function getLasts()
    {
        return $this->model->orderBy('products.created_at', 'desc')
            ->limit(6)->get(['products.id', 'products.name']);
    }

    /**
     * @param $data
     * @return mixed
     */
    private function prepareData($data)
    {
        $data['slug'] = Str::slug($data['shop_id'].'-'.$data['name']);
        $data['sizes'] = existDataArray($data, 'sizes');
        $data['colors'] = existDataArray($data, 'colors');
        $data['related'] = existDataArray($data, 'related');

        return $data;
    }
}