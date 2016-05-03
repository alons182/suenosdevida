<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden # {{ $orderId }}</title>
</head>
<body>
<p>La orden # {{ $orderId }} fue solicitada con los siguientes datos:</p>
 <strong>Nombre:</strong> {{ $first_name }} <br />
 <strong>Apellidos:</strong> {{ $last_name }} <br />
 <strong>Identificación:</strong> {{ $ide }} <br />
 <strong>Dirección:</strong> {{ $address }} <br />
 <strong>Teléfono:</strong> {{ $telephone }} <br />
 <strong>Email:</strong> {{ $email }} <br />
 <strong>Numero de deposito:</strong> {{ $transfer_number }} <br />
 <strong>Fecha de deposito:</strong> {{ $transfer_date }} <br />

<p><strong>Orden #: {{ $orderId }}</strong></p>
 <strong>Id de usuario:</strong> {{ $user_id }} <br />
 <strong>Descripción:</strong> {{ $description }} <br />
 <strong>Total:</strong> {{ $total }} <br />

<p><strong>Productos de la tienda {{ $shop_name  }}: </strong></p>
@foreach($products as $product)
 <strong>{{ $product }} </strong> <br />
@endforeach


</body>
</html>