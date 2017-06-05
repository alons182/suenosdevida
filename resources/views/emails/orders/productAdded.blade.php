<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Producto # {{ $product_id }}</title>
</head>
<body>
<p>El producto # {{ $product_id }} fue agregado al carrito por:</p>
 <strong>Nombre:</strong> {{ $name }} <br />
 <strong>Email:</strong> {{ $email }} <br />

<p><strong>Producto #: {{ $product_id }}</strong></p>
 <strong>Nombre:</strong> {{ $product_name }} <br />
 <strong>Descripción:</strong> {{ $product_description }} <br />

<p><strong>Producto de la tienda {{ $shop_name  }} </strong></p>



</body>
</html>