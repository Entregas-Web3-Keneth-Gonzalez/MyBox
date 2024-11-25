<?php
session_start();

if ($_SESSION["autenticado"] != "SI") {
    header("Location: ../index.php");
    exit();
}

$ruta = getenv('HOME_PATH') . '/' . $_SESSION["usuario"];
$nombre_carpeta = trim($_POST['nombre_carpeta']);

// Validar nombre de la carpeta (sin caracteres especiales)
if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $nombre_carpeta)) {
    die("Error: Nombre de carpeta inválido.");
}

if (!mkdir($ruta . '/' . $nombre_carpeta, 0755)) {
    echo "Error: No se pudo crear la carpeta.";
} else {
    header("Location: ../carpetas.php");
    exit();
}
?>
