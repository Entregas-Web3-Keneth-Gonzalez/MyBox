<?php
session_start();

if ($_SESSION["autenticado"] != "SI") {
    header("Location: index.php");
    exit(); //fin del script
}

// Ruta base del usuario
$ruta_base = getenv('HOME_PATH') . '/' . $_SESSION["usuario"];

// Si se pasa una ruta actual, ajustarla
if (isset($_GET['ruta'])) {
    $ruta_actual = $ruta_base . '/' . trim($_GET['ruta']);
    // Validar que no salga de la carpeta base
    if (strpos(realpath($ruta_actual), $ruta_base) !== 0) {
        die("Acceso denegado.");
    }
} else {
    $ruta_actual = $ruta_base;
}

$Accion_Formulario = $_SERVER['PHP_SELF'];

if ((isset($_POST["OC_Aceptar"])) && ($_POST["OC_Aceptar"] == "frmArchi")) {
    $Sali = $_FILES['txtArchi']['name'];
    $Sali = str_replace(' ', '_', $Sali); // Reemplaza espacios en el nombre del archivo

    // Ruta final del archivo
    $ruta_final = $ruta_actual . '/' . $Sali;

    if (move_uploaded_file($_FILES['txtArchi']['tmp_name'], $ruta_final)) {
        chmod($ruta_final, 0644); // Cambiar permisos del archivo
        header("Location: carpetas.php?ruta=" . urlencode(trim($_GET['ruta'])));
        exit(); // Fin del script
    } else {
        echo "Error al mover el archivo. Consulte a su administrador.";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <?php include_once('sections/head.inc'); ?>
    <title>Agregar archivos</title>
</head>
<body class="container-fluid">
    <header class="row">
        <div class="row">
            <?php include_once('sections/header.inc'); ?>
        </div>
    </header>
    <main class="row">
        <div class="panel panel-primary datos1">
            <div class="panel-heading">
                <strong>Agregar archivo</strong>
            </div>
            <div class="panel-body">
                <form action="<?php echo $Accion_Formulario; ?>?ruta=<?php echo isset($_GET['ruta']) ? urlencode($_GET['ruta']) : ''; ?>" 
                      method="post" enctype="multipart/form-data" name="frmArchi">
                    <fieldset>
                        <label><strong>Archivo</strong></label>
                        <input name="txtArchi" type="file" id="txtArchi" size="60" />
                        <input type="submit" name="Submit" value="Cargar" />
                    </fieldset>
                    <input type="hidden" name="OC_Aceptar" value="frmArchi" />
                </form>
            </div>
        </div>
    </main>

    <footer class="row">
        <?php include_once('sections/foot.inc'); ?>
    </footer>
</body>
</html>
