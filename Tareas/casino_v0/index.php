<?php
session_start();

$msg = "";

//Para las cookies, una vez por sesion de cada usuarito
if (!isset($_SESSION['visitas'])) {
    if (isset($_COOKIE['visitas'])) {
        $visitas = $_COOKIE['visitas'] + 1;
    } else {
        $visitas = 1;
    }

    //se guarda por 30 dias
    setcookie('visitas', $visitas, time() + 60 * 60 * 24 * 30);
    $_SESSION['visitas'] = $visitas;
} else {
    $visitas = $_SESSION['visitas'];
}

//He puesto un if para saber si cuando entre al casino, y no tiene dinero se lo pide
if (!isset($_SESSION['disponible'])) {

    if (isset($_POST['cantidadini']) && $_POST['cantidadini'] > 0) {

        //Se guarda el dinero pedido
        $_SESSION['disponible'] = (int) $_POST['cantidadini'];
    } else {
        //tipico formulario cuando se entra
        require_once "form_bienvenida.php";
        exit();
    }
}

//Cada apuesta que se haga se llamara al procesar apuesta para ver si gana o pierde, puro gambling my G
if (isset($_POST["apostar"])) {
    $cantidad = (int) $_POST["cantidad"];
    $apuesta = $_POST["apuesta"];
    $msg = procesarApuesta($cantidad, $_SESSION['disponible'], $apuesta);
}

$disponible = $_SESSION['disponible'];

//Si se queda pobre o quiere salir de este vicio de la perdición
if (isset($_POST["dejar"]) || $_SESSION["disponible"] == 0) {
    if ($_SESSION["disponible"] == 0) {
        $msg = $msg . "<br>Se ha quedado sin dinero.";
    } else {
        $msg = "";
    }
    require_once "despedida.php";
    session_destroy();
    exit();
}

//Se muestra el formulario para saber si quiere cavar mas su tumba apostando
require_once "form_apuesta.php";
?>


<?php

//Revisa cada apuesta aplicada
function procesarApuesta(int $valorapuesta, int & $saldodisponible, string $apuesta): String {
    $msgresultado = "";

    if ($valorapuesta <= 0) {
        //Por si algun chistoso, quiere poner un 0, ¿Como jugaras chiquitin?
        $msgresultado = "Error: la cantidad debe ser mayor que 0.";

    } elseif ($valorapuesta > $saldodisponible) {
        //El usuario no tiene ese cantidad de dinero :p
        $msgresultado = "Error: no dispone de $valorapuesta euros disponibles.";
    } else {
        //Para comprobar si es PAR o IMPAR, 50/50 para arruinarte la vida xd
        $numero = rand(1, 100);
        if ($numero % 2 == 0) {
            $resultado = "PAR";
        } else {
            $resultado = "IMPAR";
        }

        $msgresultado = "RESULTADO DE LA APUESTA : $resultado <br>";

        if ($resultado == $apuesta) {
            $saldodisponible = $saldodisponible + $valorapuesta;
            $msgresultado = $msgresultado . "GANASTE";
        } else {
            $saldodisponible = $saldodisponible - $valorapuesta;
            $msgresultado = $msgresultado . "PERDISTE";
        }
    }
    return $msgresultado;
}