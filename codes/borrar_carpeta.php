<?php
session_start();

if ($_SESSION["autenticado"] != "SI") {
    header("Location: ../index.php");
    exit();
}

$ruta_base = getenv('HOME_PATH') . '/' . $_SESSION["usuario"];
$carpeta = trim($_GET['carpeta']);
$ruta_completa = $ruta_base . '/' . $carpeta;

// Validar que sea una carpeta
if (!is_dir($ruta_completa)) {
    die("Error: No es una carpeta válida.");
}

// Función para eliminar carpetas recursivamente
function eliminar_carpeta($ruta) {
    foreach (scandir($ruta) as $archivo) {
        if ($archivo === '.' || $archivo === '..') continue;
        $ruta_completa = $ruta . '/' . $archivo;
        is_dir($ruta_completa) ? eliminar_carpeta($ruta_completa) : unlink($ruta_completa);
    }
    return rmdir($ruta);
}

if (eliminar_carpeta($ruta_completa)) {
    header("Location: ../carpetas.php");
} else {
    echo "Error: No se pudo borrar la carpeta.";
}
?>
