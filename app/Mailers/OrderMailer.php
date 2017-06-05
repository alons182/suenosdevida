<?php namespace App\Mailers;


use App\Order;
use App\Product;
use App\Shop;

class OrderMailer extends Mailer{

    protected $listLocalEmail = ['alonso@avotz.com'];
    protected $listProductionEmail = ['tienda@suenosdevidacr.com'];

    public function sendConfirmMessageOrder(Order $order, $data = null)
    {
        $view = 'emails.orders.confirm';
        $subject = 'Orden creada';
        $emailTo =$this->listProductionEmail;
        $emailTo[] = $data['email'];
        $data['orderId'] = $order->id;
        $data += $order->toArray();

        return $this->sendTo($emailTo, $subject, $view, $data);
    }
    public function sendNotificationToShop(Order $order, $shop, $products, $data = null)
    {
        $view = 'emails.orders.confirm';
        $subject = 'Orden creada';
        $emailTo = $this->listProductionEmail;
        $emailTo[] = $shop->responsable->email;

        $data['orderId'] = $order->id;
        $data['shop_name'] = $shop->name;
        $data += $order->toArray();
        $data['products'] = $products;



        return $this->sendTo($emailTo, $subject, $view, $data);
    }

    public function sendNotificationProductAddedToCart(Product $product, Shop $shop, $user)
    {
        $view = 'emails.orders.productAdded';
        $subject = 'Producto Agregado al Carrito';
        $emailTo = $this->listProductionEmail;
        $emailTo[] = $shop->responsable->email;

        $data['product_id'] = $product->id;
        $data['shop_name'] = $shop->name;
        $data['product_name'] = $product->name;
        $data['product_description'] = $product->description;
        $data['name'] = $user['name'];
        $data['email'] = $user['email'];



        return $this->sendTo($emailTo, $subject, $view, $data);
    }
} 