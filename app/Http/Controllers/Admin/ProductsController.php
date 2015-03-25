<?php namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Laracasts\Flash\Flash;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use Redirect;
use View;


class ProductsController extends Controller {

    protected $productRepository;
    protected $categoryRepository;


    function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {

        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;

    }


    /**
     * Display a listing of the resource.
     * GET /products
     *
     * @return Response
     */
    public function index()
    {
        $search = Request::all();
        $search['q'] = (isset($search['q'])) ? trim($search['q']) : '';
        $search['cat'] = (isset($search['cat'])) ? $search['cat'] : '';
        $search['published'] = (isset($search['published'])) ? $search['published'] : '';
        $categories = $this->categoryRepository->getParents();
        $products = $this->productRepository->getAll($search);

        return View::make('admin.products.index')->with([
            'products'         => $products,
            'search'           => $search['q'],
            'options'          => $categories,
            'categorySelected' => $search['cat'],
            'selectedStatus'   => $search['published']

        ]);

    }

    /**
     * Show the form for creating a new resource.
     * GET /products/create
     *
     * @return Response
     */
    public function create()
    {
        $categories = $this->categoryRepository->getParents();

        return View::make('admin.products.create')->withCategories($categories);
    }

    /**
     * Store a newly created resource in storage.
     * POST /products
     *
     * @param ProductRequest $request
     * @return Response
     */
    public function store(ProductRequest $request)
    {
        $input = $request->all();

        $this->productRepository->store($input);

        Flash::message('Product Created');

        return Redirect::route('products');
    }


    /**
     * Show the form for editing the specified resource.
     * GET /products/{id}/edit
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $product = $this->productRepository->findById($id);
        $categories = $this->categoryRepository->getParents();
        $selectedCategories = $product->categories()->select('categories.id AS id')->lists('id');

        return View::make('admin.products.edit')->withProduct($product)->withCategories($categories)->withSelected($selectedCategories);//->withRelateds($relateds);
    }

    /**
     * Update the specified resource in storage.
     * PUT /products/{id}
     *
     * @param ProductRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(ProductRequest $request, $id)
    {

        $input = $request->all();

        $this->productRepository->update($id, $input);

        Flash::message('Updated Product');

        return Redirect::route('products');
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
        $this->productRepository->update_state($id, 1);

        return Redirect::route('products');
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
        $this->productRepository->update_state($id, 0);

        return Redirect::route('products');
    }

    /**
     * Featured.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function feat($id)
    {
        $this->productRepository->update_feat($id, 1);

        return Redirect::route('products');
    }

    /**
     * un-featured.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function unfeat($id)
    {
        $this->productRepository->update_feat($id, 0);

        return Redirect::route('products');
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /products/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->productRepository->destroy($id);

        Flash::message('Product Deleted');

        return Redirect::route('products');
    }

    /**
     * Remove multiple resource from storage.
     * DELETE /products/{id}
     *
     * @internal param int $chk_product (array of ids)
     * @return Response
     */
    public function destroy_multiple()
    {
        $products_id = Input::get('chk_product');

        foreach ($products_id as $id)
        {
            $this->productRepository->destroy($id);
        }

        Flash::message('Products Deleted');

        return Redirect::route('products');

    }

    /**
     * List of products for the modal view of related products.
     * GET /products/list
     *
     * @param  int  exc_id (exclude current id for editing)
     * @return Response
     */
    public function list_products()
    {
        return $this->productRepository->list_products(input::get('exc_id'), input::get('key'));
    }


}