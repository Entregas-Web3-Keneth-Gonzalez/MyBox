<?php
session_start();

if ($_SESSION["autenticado"] != "SI") {
    header("Location: index.php");
    exit(); //fin del script
}

// Ruta base del usuario
$ruta_base = getenv('HOME_PATH') . '/' . $_SESSION["usuario"];

// Validar si se proporcionó una ruta específica
if (isset($_GET['ruta'])) {
    $ruta_actual = $ruta_base . '/' . trim($_GET['ruta']);
    // Validar que no salga del directorio base
    if (strpos(realpath($ruta_actual), $ruta_base) !== 0) {
        die("Acceso denegado.");
    }
} else {
    $ruta_actual = $ruta_base;
}

// Archivo a abrir
$archivo = $_GET['arch'];
$ruta_archivo = $ruta_actual . '/' . $archivo;

// Verificar que el archivo exista
if (!is_file($ruta_archivo)) {
    die("Error: El archivo no existe o no es accesible.");
}

// Obtener la extensión del archivo
$extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));

// Procesar archivos según su extensión
if (in_array($extension, ['pdf', 'jpg', 'png'])) {
    // Mostrar archivos [pdf, jpg, png] directamente en el navegador
    $mime = mime_content_type($ruta_archivo);
    header("Content-type: " . $mime);
    readfile($ruta_archivo);
} else {
    // Forzar la descarga para otros tipos de archivo
    header("Content-Disposition: attachment; filename=" . $archivo);
    header("Content-type: application/octet-stream");
    header("Content-length: " . filesize($ruta_archivo));
    readfile($ruta_archivo);
}
?>
