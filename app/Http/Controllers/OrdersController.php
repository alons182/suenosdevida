<?php namespace App\Http\Controllers;




use App\Http\Requests\OrderRequest;
use App\Mailers\OrderMailer;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Swift_RfcComplianceException;


class OrdersController extends Controller {

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var OrderMailer
     */
    private $mailer;

    function __construct(OrderRepository $orderRepository, OrderMailer $mailer)
    {
        $this->orderRepository = $orderRepository;
        $this->mailer = $mailer;
        $this->middleware('auth', ['only'=>['formCheckout','formPostCheckout']]);
    }


    /**
     * Display a listing of the resource.
     * GET /orders
     *
     * @return Response
     */
    public function index()
    {
        $data = Request::all();

        $orders = $this->orderRepository->findAll($data);

        return View::make('orders.index')->with([
            'orders' => $orders

        ]);
    }

    /**
     * Store a newly created resource in storage.
     * POST /orders
     *
     * @return Response
     */
    public function store()
    {
        $data_cart = Request::all();

        $data_form = json_decode(Session::get('data_form'), true);

        if ($data_cart['itemCount'] == 0)
        {
            Flash::message('No hay items en el carrito');

            return Redirect::route('cart_path');
        }

        $order = $this->orderRepository->store($data_cart);

        //$this->mailer->sendConfirmMessageOrder($order, $data_form);
        $shops = [];

        foreach ($order->details as $detail)
        {
            $shops[] = $detail->products->shop;
        }
        $shops = array_unique($shops);

        foreach ($shops as $shop)
        {
            $products = [];

            foreach ($order->details as $detail)
            {
                if($shop->id == $detail->products->shop_id )
                   $products[] = $detail->products->id  . " - ". $detail->products->name;
            }

            try {
                $this->mailer->sendNotificationToShop($order, $shop, $products, $data_form);
            }catch (Swift_RfcComplianceException $e)
            {
                Log::error($e->getMessage());
            }


        }



        //$this->mailer->sendNotificationToShop($order, $data_form);

        Flash::message('Pago realizado con exito - orden ' . $order->id);

        Session::forget('success');
        Session::put('success', '1');

        return Redirect::route('orders.index');
    }

    /**
     * Display the specified resource.
     * GET /orders/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $order = $this->orderRepository->findById($id);

        return View::make('orders.show')->with([
            'order' => $order

        ]);
    }

    public function cart()
    {
        return View::make('orders.cart');
    }

    /**
     * show the form checkout
     * @return mixed
     */
    public function formCheckout()
    {
        return View::make('orders.checkout');
    }

    /**
     * Post form checkout
     * @param OrderRequest $request
     * @return mixed
     */
    public function formPostCheckout(OrderRequest $request)
    {

        $data = array_except($request->all(), array('_token'));

        Session::forget('data_form');
        Session::forget('success');
        Session::put('data_form', json_encode($data));


        return View::make('orders.checkoutFinal')->withData($data);


    }


}