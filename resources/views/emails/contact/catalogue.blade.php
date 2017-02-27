<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Información del formulario de catálago</title>
</head>
<body>
<strong>Nombre :</strong> {{ $first_name }} <br />
<strong>Apellidos :</strong> {{ $last_name }} <br />
<strong>Dirección :</strong> {{ $address }} <br />
<strong>Teléfono :</strong> {{ $telephone }} <br />
<strong>Email :</strong> {{ $email }} <br />
<strong>Solicitud :</strong><br />
	<p>{{ $comment }} </p>
</body>
</html>