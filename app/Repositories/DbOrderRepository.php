<?php namespace App\Repositories;

use App\Order;
use App\Order_detail;
use Illuminate\Support\Facades\Auth;


class DbOrderRepository extends DbRepository implements OrderRepository {

    /**
     * @var Payment
     */
    protected $model;

    function __construct(Order $model)
    {
        $this->model = $model;
        $this->limit = 20;

    }


    /**
     * Save a order
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $data = $this->prepareData($data);

        $order = $this->model->create($data);
        $this->sync_orderDetail($order, $data);

        return $order;

    }

    /**
     * Save the detail of the order
     * @param $order
     * @param $data
     */
    public function sync_orderDetail($order, $data)
    {
        for ($i = 1; $i <= $data['itemCount']; $i ++)
        {
            $detail = new Order_detail;
            $detail->product_id = substr($data[ 'item_options_' . $i ], 9);
            $detail->quantity = $data[ 'item_quantity_' . $i ];
            $order->details()->save($detail);
        }


    }

    /**
     * Find all orders from one User
     * @param $data
     * @return mixed
     */
    public function findAll($data)
    {
        $orders = Auth::user()->orders()->paginate($this->limit);

        return $orders;
    }

    /**
     * Get all the orders for admin panel
     * @param $search
     * @return mixed
     */
    public function getAll($search)
    {

        $orders = $this->model;


        if (isset($search['q']) && ! empty($search['q']))
        {
            $orders = $orders->Search($search['q']);
        }

        if (isset($search['status']) && $search['status'] != "")
        {
            $orders = $orders->where('status', '=', $search['status']);
        }

        return $orders->with('users', 'details')->orderBy('created_at', 'desc')->paginate($this->limit);
    }

    /**
     * Update a order
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $order = $this->model->findOrFail($id);
        $order->fill($data);
        $order->save();

        return $order;
    }

    /**
     * Delete a order
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $order = $this->model->findOrFail($id);

        $order->delete();

        return $order;
    }

    /**
     * Find a order by ID
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->model->with('details')->findOrFail($id);
    }


    /**
     * @param $data
     * @return array
     */
    private function prepareData($data)
    {
        $data = array_add($data, 'user_id', Auth::user()->id);
        $description = "";
        $total = 0;
        for ($i = 1; $i <= $data['itemCount']; $i ++)
        {
            $description .= $data[ 'item_name_' . $i ] . ', ';
            $total += ($data[ 'item_price_' . $i ] * $data[ 'item_quantity_' . $i ]);
        }
        $data = array_add($data, 'description', $description);
        $data = array_add($data, 'total', $total);
        $data = array_add($data, 'status', 'P');

        return $data;
    }

    /**
     * get last orders for the dashboard page
     * @return mixed
     */
    public function getLasts()
    {
        return $this->model->orderBy('orders.created_at', 'desc')
            ->limit(6)->get(['orders.id']);
    }


}