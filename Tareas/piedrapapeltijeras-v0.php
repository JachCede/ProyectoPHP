<?php
// JUEGO DE PIEDRA, PAPEL Y TIJERAS VERSION INCOMPLETA
//-------------------------------
// Caracteres asociadas a las logos 
// PIEDRA (Puño derecho)
// PIEDRA2 (Puño Izquierdo / jugador 2 ) 
define ('PIEDRA',   "&#x1F91C;");
define ('PIEDRA2',  "&#x1F91B;");
define ('TIJERAS',  "&#x1F596;");
define ('PAPEL',    "&#x1F91A;" );

// Tabla de mensajes en función del ganador
$tmsg = [
          "¡Empate !",
          " Ha ganado el jugador 1",
          " Ha ganado el jugador 2"
        ];


/**
 *  Calcula el ganador 
 *  Parámetros: Dos valores PIEDRA, PAPEL O TIJERA
 *  Resultado: 0 (Empate),1 (1 Gana jugador 1), 2 (Gana jugador 2)   
 *  
 */

function calcularGanador (String $valor1, String $valor2): int{
    // COMPLETAR
    //Si los dos tienen el mismo valor, da 0 que es el empate
    if ($valor1 == $valor2) { 
    return 0;
}

 if( ($valor1 == PIEDRA && $valor2 == TIJERAS) || 
     ($valor1 == TIJERAS && $valor2 == PAPEL) ||
     ($valor1 == PAPEL && $valor2 == PIEDRA)){
     return 1;
     }
     //Por logica si no ha empatado, ni gano el player 1, gana el 2 retornando asi en 2
     return 2;
}





/**
 *  Obtiene un valor aleatorio PIEDRA, PAPEL O TIJERAS
 * @return string
 */
function obtenerFicha (): string {
   // COMPLETAR
   $numero = rand(1,3); //de 1 a 3 porque son piedra, papel, tijera
   
   //uno para cada uno para que pueda devolver
   if($numero == 1){
  return PIEDRA;
   }
   if($numero == 2){
   return PAPEL;
  }
  return TIJERAS;
}

//Por lo que entiendo parte del juego donde se empieza, se calcula y da el mensaje
$jugador1 = obtenerFicha();
$jugador2 = obtenerFicha();
$pos = calcularGanador($jugador1,$jugador2);
$mensaje =  $tmsg[$pos]; 


$jugador2 = ($jugador2 == PIEDRA) ?PIEDRA2:$jugador2;

?>

<html>
<head>
<title>Online PHP Script Execution</title>
</head>
<body>
<h1>¡Piedra, papel, tijera!</h1>

    <p>Actualice la página para mostrar otra partida.</p>

    <table>
      <tr>
        <th>Jugador 1</th>
        <th>Jugador 2</th>
      </tr>
      <tr>
        <td><span style="font-size: 7rem"><?= $jugador1; ?></span></td>
        <td><span style="font-size: 7rem"><?= $jugador2; ?></span></td>
      </tr>
      <tr>
        <th colspan="2"><?= $mensaje ?></th>
      </tr>
    </table>
</body>
</html>