<?php
session_start();

$msg = "";

// ---------------------------------------------------------
// VISITAS (cookie). Solo se cuenta una vez por cada sesión nueva
// ---------------------------------------------------------
if (!isset($_SESSION['visitas'])) {
    if (isset($_COOKIE['visitas'])) {
        $visitas = $_COOKIE['visitas'] + 1;
    } else {
        $visitas = 1;
    }
    // La cookie dura un mes (30 días)
    setcookie('visitas', $visitas, time() + 60 * 60 * 24 * 30);
    $_SESSION['visitas'] = $visitas;
} else {
    $visitas = $_SESSION['visitas'];
}

// ---------------------------------------------------------
// Entra en el casino: si no hay dinero, se lo pedimos
// ---------------------------------------------------------
if (!isset($_SESSION['disponible'])) {

    if (isset($_POST['cantidadini']) && $_POST['cantidadini'] > 0) {
        // Ya ha escrito el dinero inicial: lo guardo en la sesión
        $_SESSION['disponible'] = (int) $_POST['cantidadini'];
    } else {
        // Muestro el formulario de bienvenida
        require_once "form_bienvenida.php";
        // No continuo después de mostrar los formularios
        exit();
    }
}

// ---------------------------------------------------------
// Si realiza una apuesta, llama a procesarApuesta
// ---------------------------------------------------------
if (isset($_POST["apostar"])) {
    $cantidad = (int) $_POST["cantidad"];
    $apuesta = $_POST["apuesta"];
    $msg = procesarApuesta($cantidad, $_SESSION['disponible'], $apuesta);
}

$disponible = $_SESSION['disponible'];

// ---------------------------------------------------------
// Si abandona o ya no le queda dinero
// ---------------------------------------------------------
if (isset($_POST["dejar"]) || $_SESSION["disponible"] == 0) {
    if ($_SESSION["disponible"] == 0) {
        $msg = $msg . "<br>Se ha quedado sin dinero.";
    } else {
        $msg = "";
    }
    require_once "despedida.php";
    // Cierro la sesión para que la próxima vez cuente como nueva visita
    session_destroy();
    exit();
}

// Muestro el formulario si puede seguir apostando
require_once "form_apuesta.php";
?>


<?php

/**
 *  FUNCIONES AUXILIARES
 */

function procesarApuesta(int $valorapuesta, int & $saldodisponible, string $apuesta): String {
    $msgresultado = "";

    if ($valorapuesta <= 0) {
        // Cantidad no válida
        $msgresultado = "Error: la cantidad debe ser mayor que 0.";
    } elseif ($valorapuesta > $saldodisponible) {
        // No tiene tanto dinero
        $msgresultado = "Error: no dispone de $valorapuesta euros disponibles.";
    } else {
        // Número aleatorio: si es par sale PAR, si no IMPAR
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