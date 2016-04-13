<?php namespace App\Http\Controllers\Admin;



use App\Category;
use App\Http\Requests\ShopRequest;
use App\Photo;
use App\Product;
use App\Repositories\ShopRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Input;

class ShopsController extends Controller
{
    protected $shopRepository;


    function __construct(ShopRepository $shopRepository)
    {

        $this->shopRepository = $shopRepository;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->all();

        $search['q'] = (isset($search['q'])) ? trim($search['q']) : '';
        $search['published'] = (isset($search['published'])) ? $search['published'] : '';

        $shops = $this->shopRepository->getAll($search);

        return View::make('admin.shops.index')->with([
            'shops'         => $shops,
            'search'           => $search['q'],
            'selectedStatus'   => $search['published']

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return View::make('admin.shops.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShopRequest $request)
    {
        $input = $request->all();

        $this->shopRepository->store($input);

        Flash::message('Shop Created');

        return Redirect::route('store.admin.shops.index');
    }

    /**
     * Reply products form other shop.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function reply(Request $request)
    {
        $input = $request->all();

        $categoriesToReply = Category::where('shop_id','=',$input['shopToReply'])->get();
        $productsToReply = Product::where('shop_id','=',$input['shopToReply'])->get();

        foreach($categoriesToReply as $catReply)
        {
            $newCategory = new Category;
            $newCategory->name = $catReply->name;
            $newCategory->slug =  Str::slug($input['currentShop'].'-'.$catReply->name);
            $newCategory->description = $catReply->description;
            $newCategory->published = $catReply->published;
            $newCategory->featured = $catReply->featured;
            $newCategory->shop_id = $input['currentShop'];


            if($catReply->image) {
                $oldPath = dir_photos_path('categories') . $catReply->image; // publc/images/1.jpg
                $fileExtension = \File::extension($oldPath);
                $newName = $newCategory->slug.'.' . $fileExtension;
                $newPathWithName = dir_photos_path('categories') . $newName;

                if (! \File::copy($oldPath, $newPathWithName)) {
                    log('error copiando la imagen de la categoria');
                }
                if (! \File::copy(dir_photos_path('categories') . 'thumb_'.$catReply->image, dir_photos_path('categories') .'thumb_'.$newName)) {
                    log('error copiando la imagen en miniatura de la categoria');
                }
                $newCategory->image = $newName;
            }

            $newCategory->save();
        }

        foreach($productsToReply as $prodReply)
        {
            $newProduct = new Product;
            $newProduct->name = $prodReply->name;
            $newProduct->slug =  Str::slug($input['currentShop'].'-'.$prodReply->name);
            $newProduct->description = $prodReply->description;
            $newProduct->price = $prodReply->price;
            $newProduct->promo_price = $prodReply->promo_price;
            $newProduct->discount = $prodReply->discount;
            $newProduct->sizes = (count($prodReply->present()->sizes)) ? $prodReply->present()->sizes : [];
            $newProduct->colors = (count($prodReply->present()->colors)) ? $prodReply->present()->colors : [];
            $newProduct->related = (count($prodReply->present()->related)) ? $prodReply->present()->related : [];
            $newProduct->published = $prodReply->published;
            $newProduct->featured = $prodReply->featured;
            $newProduct->shop_id = $input['currentShop'];


            if($prodReply->image) {
                $oldPath = dir_photos_path('products') . $prodReply->image; // publc/images/1.jpg
                $fileExtension = \File::extension($oldPath);
                $newName = $newProduct->slug.'.' . $fileExtension;
                $newPathWithName = dir_photos_path('products') . $newName;

                if (! \File::copy($oldPath, $newPathWithName)) {
                    log('error copiando la imagen del producto');
                }
                if (! \File::copy(dir_photos_path('products') . 'thumb_'.$prodReply->image, dir_photos_path('products') .'thumb_'.$newName)) {
                    log('error copiando la imagen en miniatura del producto');
                }
                $newProduct->image = $newName;
            }

            $newProduct->save();

            if($prodReply->photos)
            {
                foreach ($prodReply->photos as $photo)
                {
                    $newPhotos = new Photo;
                    $newPhotos->url = $photo->url;
                    $newPhotos->url_thumb = $photo->url_thumb;
                    $newProduct->photos()->save($newPhotos);
                }

                $sourceDir = dir_photos_path('products'). $prodReply->id;
                $destinationDir = dir_photos_path('products'). $newProduct->id;
                if (! \File::copyDirectory($sourceDir, $destinationDir)) log('error copiando la galeria del producto');

            }


        }


        Flash::message('Shop Replicada');

        return Redirect::route('store.admin.shops.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shop = $this->shopRepository->findById($id);

        return View::make('admin.shops.edit')->withShop($shop);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShopRequest $request, $id)
    {

        $input = $request->all();

        $this->shopRepository->update($id, $input);

        Flash::message('Updated Shop');

        return Redirect::route('store.admin.shops.index');
    }

    /**
     * published a Product.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function pub($id)
    {
        $this->shopRepository->update_state($id, 1);

        return Redirect::route('store.admin.shops.index');
    }

    /**
     * Unpublished a Product.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function unpub($id)
    {
        $this->shopRepository->update_state($id, 0);

        return Redirect::route('store.admin.shops.index');
    }

    /**
     * @return mixed
     */
    public function list_shops()
    {

        return $this->shopRepository->list_shops();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->shopRepository->destroy($id);

        Flash::message('Shop Deleted');

        return Redirect::route('store.admin.shops.index');
    }
}
