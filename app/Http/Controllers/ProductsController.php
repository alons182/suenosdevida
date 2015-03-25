<?php namespace App\Http\Controllers;


use App\Repositories\CategoryRepository;
use App\Repositories\PhotoRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;


class ProductsController extends Controller {


    protected $productRepository;
    protected $categoryRepository;
    protected $photoRepository;

    function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository, PhotoRepository $photoRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->photoRepository = $photoRepository;



    }

    /**
     * Display a listing of the resource.
     * GET /products
     *
     * @param $category
     * @return Response
     */
    public function index($category)
    {
        $search = Request::all();
        $search['subcat'] = (isset($search['subcat'])) ? $search['subcat'] : '';

        if ($search['subcat'] == '')
            $products = $this->productRepository->findByCategory($category);
        else
            $products = $this->productRepository->findByCategory($search['subcat']);

        $subcategories = $this->categoryRepository->getChildren($category);

        return View::make('products.index')->withProducts($products)
            ->withCategory($category)
            ->withSubcategories($subcategories)
            ->withSelected($search['subcat']);
    }

    /**
     * Display a listing of the resource.
     * GET /products
     *
     * @return Response
     */
    public function search()
    {

        $search = array_add(Request::all(), 'published', 1);

        if ($search['q'] == '') return View::make('categories.index');

        $products = $this->productRepository->getall($search);

        return View::make('products.search')->withProducts($products)->withSearch($search['q']);
    }

    /**
     * Display the specified resource.
     * GET /products/{id}
     *
     * @param $category
     * @param $product
     * @internal param int $id
     * @return Response
     */
    public function show($category, $product)
    {
        $product = $this->productRepository->findBySlug($product);
        //$relateds = $this->productRepository->relateds($product);
        $others = [];//$this->productRepository->others($category, $product->id);
        $photos = $this->photoRepository->getPhotos($product->id);

        return View::make('products.show')->withProduct($product)->withOthers($others)->withCategory($category)->withPhotos($photos);//->withRelateds($relateds);
    }


    /**
     * Display a list of categories from the store
     * @return mixed
     */
    public function categories()
    {
        return View::make('categories.index');
    }


}