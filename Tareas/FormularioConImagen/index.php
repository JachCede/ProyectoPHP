<?php
//Se muestra el formulario
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    include "captura.html";
    exit;
}

// Cuando la petición es POST, procesamos sus datos.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Método no permitido.");
}






function limpiar($texto) {
    if (!is_string($texto)) {
        return "";
    }
    return htmlspecialchars(trim($texto), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

$nombre = limpiar($_POST["nombre"] ?? "");
$alias = limpiar($_POST["alias"] ?? "");

$edad = filter_var($_POST["edad"] ?? null, FILTER_VALIDATE_INT);
if ($edad === false || $edad === null || $edad < 1) {
    $edad = "No válida";
}

//Que solo se incluyan las armas mencionadas
$armasPermitidas = ["Maza", "Antorcha", "Martillo", "Látigo"];
$armasSeleccionadas = [];

if (isset($_POST["armas"]) && is_array($_POST["armas"])) {
    foreach ($_POST["armas"] as $arma) {
        if (in_array($arma, $armasPermitidas, true) && !in_array($arma, $armasSeleccionadas, true)) {
            $armasSeleccionadas[] = $arma;
        }
    }
}


if (count($armasSeleccionadas) > 0) {
    $armas = implode(", ", $armasSeleccionadas);
} else {
    $armas = "Ninguna";
}

$magia = $_POST["magia"] ?? "";
if ($magia !== "Sí" && $magia !== "No") {
    $magia = "No indicado";
}

//Si la imagen no va que muestre la calavera
$imagenMostrada = "calavera.png";
$mensajeError = "";
$subidaCorrecta = false;

if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] !== UPLOAD_ERR_NO_FILE) {
    $archivo = $_FILES["imagen"];

    if ($archivo["error"] !== UPLOAD_ERR_OK) {
        $mensajeError = "Error al subir la imagen.";
    } else {
        $tamano = $archivo["size"];
        $extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
        $datosImagen = @getimagesize($archivo["tmp_name"]);

        // Validación en el servidor: extensión, contenido PNG y tamaño.
        if ($extension !== "png" || $datosImagen === false || $datosImagen["mime"] !== "image/png") {
            $mensajeError = "Error: el archivo debe ser una imagen PNG.";
        } elseif ($tamano > 10240) {
            $mensajeError = "Error: la imagen supera el tamaño máximo de 10 KB.";
        } else {
            $carpeta = __DIR__ . "/uploads/";
            $nuevoNombre = bin2hex(random_bytes(8)) . ".png";

            if (is_dir($carpeta) && move_uploaded_file($archivo["tmp_name"], $carpeta . $nuevoNombre)) {
                $imagenMostrada = "uploads/" . $nuevoNombre;
                $subidaCorrecta = true;
            } else {
                $mensajeError = "Error al guardar la imagen en la carpeta uploads.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del Jugador</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7fa;
            margin: 0;
            padding: 30px 12px;
        }

        .resultado {
            max-width: 720px;
            margin: 0 auto;
            background-color: #ffff42;
            padding: 30px 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px #dddddd;
        }

        h2 {
            margin: 0 0 30px;
            text-align: center;
        }

        .contenido {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .datos {
            flex: 1;
            min-width: 0;
        }

        .datos p {
            margin: 16px 0;
            overflow-wrap: anywhere;
        }

        .imagen {
            width: 230px;
            text-align: center;
        }

        .imagen img {
            display: block;
            max-width: 100%;
            width: 180px;
            height: 190px;
            object-fit: contain;
            margin: 12px auto;
            border: 1px solid #444;
            background: #ffffff;
        }

        .error {
            font-size: 14px;
            line-height: 1.4;
        }

        .volver {
            margin-top: 22px;
            text-align: center;
        }

        @media (max-width: 520px) {
            .contenido {
                flex-direction: column;
                align-items: stretch;
            }

            .imagen {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="resultado">
        <h2>Datos del Jugador</h2>

        <div class="contenido">
            <div class="datos">
                <p><strong>Nombre:</strong> <?php echo $nombre; ?></p>
                <p><strong>Alias:</strong> <?php echo $alias; ?></p>
                <p><strong>Edad:</strong> <?php echo $edad; ?></p>
                <p><strong>Armas seleccionadas:</strong> <?php echo $armas; ?></p>
                <p><strong>¿Practica artes mágicas?:</strong> <?php echo $magia; ?></p>
            </div>

            <div class="imagen">
                <?php if ($subidaCorrecta) { ?>
                    <p><strong>Imagen subida:</strong></p>
                <?php } elseif ($mensajeError !== "") { ?>
                    <p><strong>No se subió ninguna imagen.</strong></p>
                <?php } ?>

                <img src="<?php echo $imagenMostrada; ?>" alt="Imagen del jugador o calavera">

                <?php if ($mensajeError !== "") { ?>
                    <p class="error"><?php echo $mensajeError; ?></p>
                <?php } ?>
            </div>
        </div>

        <div class="volver">
            <a href="index.php">Volver al formulario</a>
        </div>
    </div>
</body>
</html>
