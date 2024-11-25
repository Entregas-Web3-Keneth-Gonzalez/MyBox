<?php
session_start();

if ($_SESSION["autenticado"] != "SI") {
    header("Location: ../index.php");
    exit(); //fin del script
}

// Ruta base del usuario
$ruta_base = getenv('HOME_PATH') . '/' . $_SESSION["usuario"];

// Si se pasa una ruta actual, ajustarla
if (isset($_GET['ruta'])) {
    $ruta_actual = $ruta_base . '/' . trim($_GET['ruta']);
    // Validar que no salga del directorio base
    if (strpos(realpath($ruta_actual), $ruta_base) !== 0) {
        die("Acceso denegado.");
    }
} else {
    $ruta_actual = $ruta_base;
}

// Archivo a borrar
$archivo = $_GET['archi'];
$ruta_archivo = $ruta_actual . '/' . $archivo;

// Intentar borrar el archivo
if (is_file($ruta_archivo) && @unlink($ruta_archivo)) {
    // Redirigir a la página anterior
    $Ir_A = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '../carpetas.php';
    echo "<script> location.href='" . $Ir_A . "' </script>";
} else {
    echo "Error: No se pudo borrar el archivo.";
}
?>
