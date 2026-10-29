<html>
<head>
<meta charset="UTF-8">
<title>Minicasino</title>
</head>
<body>
 <h1> BIENVENIDO AL CASINO</h1> <br>
  Esta es su <?= $visitas ?>º visita.<br>
 <form method="post">
 Introduzca el dinero con el que va jugar:
 <input name="cantidadini" type="number" min="1">
 <button type="submit"> Entrar </button>
</form>
</body>
</html>