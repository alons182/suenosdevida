<?php namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use App\Repositories\OrderRepository;


class OrdersController extends Controller {

    /**
     * @var OrderRepository
     */
    private $orderRepository;


    function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;


    }


    /**
     * Display a listing of the resource.
     * GET /orders
     *
     * @return Response
     */
    public function index()
    {
        $search = Request::all();
        $search['q'] = (isset($search['q'])) ? trim($search['q']) : '';
        $search['status'] = (isset($search['status'])) ? $search['status'] : '';

        $orders = $this->orderRepository->getAll($search);

        return View::make('admin.orders.index')->with([
            'orders'         => $orders,
            'search'         => $search['q'],
            'selectedStatus' => $search['status']
        ]);
    }

    /** form edit for order status
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $order = $this->orderRepository->findById($id);

        return View::make('admin.orders.edit')->withOrder($order);
    }

    /**
     * Update the specified resource in storage.
     * PUT /products/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {

        $input = Request::all();
        //$this->productForm->validate($input);
        $this->orderRepository->update($id, $input);

        Flash::message('Updated Order');

        return Redirect::route('orders');
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
        $this->orderRepository->destroy($id);

        Flash::message('Order Deleted');

        return Redirect::route('orders');
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
        $orders_id = Request::get('chk_order');

        foreach ($orders_id as $id)
        {
            $this->orderRepository->destroy($id);
        }

        Flash::message('Orders Deleted');

        return Redirect::route('orders');

    }


}