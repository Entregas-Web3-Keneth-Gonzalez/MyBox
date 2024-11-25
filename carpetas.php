<?php
session_start();

if ($_SESSION["autenticado"] != "SI") {
    header("Location: index.php");
    exit(); // Fin del script
}

// Ruta base del usuario
$ruta_base = getenv('HOME_PATH') . '/' . $_SESSION["usuario"];
$ruta = $ruta_base;

// Validar si se proporcionó una ruta específica
if (isset($_GET['ruta'])) {
    $ruta .= '/' . trim($_GET['ruta']);
    // Validar que no salga del directorio base
    if (strpos(realpath($ruta), $ruta_base) !== 0) {
        die("Acceso denegado.");
    }
}

// Función para obtener el ícono basado en el tipo de archivo
function obtenerIcono($nombreArchivo) {
    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    switch ($extension) {
        case 'doc':
        case 'docx':
            return '<i class="fas fa-file-word" style="color:blue;"></i>';
        case 'pdf':
            return '<i class="fas fa-file-pdf" style="color:red;"></i>';
        case 'jpg':
        case 'jpeg':
        case 'png':
            return '<i class="fas fa-file-image" style="color:green;"></i>';
        case '':
            return '<i class="fas fa-folder" style="color:orange;"></i>';
        default:
            return '<i class="fas fa-file" style="color:gray;"></i>';
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <?php include_once('sections/head.inc'); ?>
    <title>Ingreso al Sitio</title>
    <style>
        #form-crear-carpeta {
            display: none;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="container-fluid">
    <header class="row">
        <div class="row">
            <?php include_once('sections/header.inc'); ?>
        </div>
    </header>
    <main class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <strong>Mi Cajón de Archivos</strong>
            </div>
            <div class="panel-body">
                <br>
                <!-- Botón para mostrar/ocultar el formulario de creación de carpetas -->
                <button onclick="toggleForm()">Crear Carpeta</button>
                <br>

                <!-- Formulario para crear carpetas -->
                <form id="form-crear-carpeta" action="codes/crear_carpeta.php" method="post">
                    <input type="hidden" name="ruta_actual" value="<?php echo isset($_GET['ruta']) ? $_GET['ruta'] : ''; ?>">
                    <input type="text" name="nombre_carpeta" placeholder="Nombre de la carpeta" required>
                    <button type="submit">Crear</button>
                </form>

                <script>
                    function toggleForm() {
                        const form = document.getElementById('form-crear-carpeta');
                        form.style.display = form.style.display === 'none' ? 'block' : 'none';
                    }
                </script>

                <br>
                <!-- Botón para agregar archivos -->
                <a href="./agrearchi.php<?php echo isset($_GET['ruta']) ? '?ruta=' . urlencode($_GET['ruta']) : ''; ?>">Agregar archivo</a>
                <br><br>

                <!-- Tabla de contenidos -->
                <table class="table table-striped">
                    <tr>
                        <th>Ícono</th>
                        <th>Nombre</th>
                        <th>Tamaño (MB)</th>
                        <th>Último acceso</th>
                        <th>Archivo</th>
                        <th>Directorio</th>
                        <th>Lectura</th>
                        <th>Escritura</th>
                        <th>Ejecutable</th>
                        <th>Acción</th>
                    </tr>

                    <?php
                    $conta = 0;
                    $directorio = opendir($ruta);

                    // Enlace para navegar hacia atrás (recarga la página)
                    if (isset($_GET['ruta']) && $_GET['ruta'] !== '') {
                        $ruta_anterior = dirname($_GET['ruta']);
                        echo '<tr>';
                        echo '<td><i class="fas fa-folder" style="color: #FFC107;"></i></td>'; // Ícono de carpeta amarillo
                        echo '<td><a href="carpetas.php">.. (Atrás)</a></td>'; // Texto de "Atrás" con recarga
                        echo '<td>-</td>'; // Tamaño
                        echo '<td>-</td>'; // Último acceso
                        echo '<td>-</td>'; // Archivo
                        echo '<td>Sí</td>'; // Directorio (es un directorio)
                        echo '<td>-</td>'; // Lectura
                        echo '<td>-</td>'; // Escritura
                        echo '<td>-</td>'; // Ejecutable
                        echo '<td>-</td>'; // Acción
                        echo '</tr>';
                    }

                    // Listar elementos del directorio
                    while ($elem = readdir($directorio)) {
                        if (($elem != '.') && ($elem != '..')) {
                            $ruta_elem = $ruta . '/' . $elem;
                            $is_dir = is_dir($ruta_elem);
                            $icono = $is_dir ? obtenerIcono('') : obtenerIcono($elem);
                            $tamano = $is_dir ? '-' : number_format(filesize($ruta_elem) / (1024 * 1024), 2) . ' MB';
                            echo '<tr>';
                            echo '<td>' . $icono . '</td>';
                            echo '<td>';
                            if ($is_dir) {
                                echo '<a href="carpetas.php?ruta=' . urlencode((isset($_GET['ruta']) ? $_GET['ruta'] . '/' : '') . $elem) . '">' . $elem . '</a>';
                            } else {
                                echo '<a href="abrarchi.php?arch=' . $elem . '&ruta=' . urlencode(isset($_GET['ruta']) ? $_GET['ruta'] : '') . '">' . $elem . '</a>';
                            }
                            echo '</td>';
                            echo '<td>' . $tamano . '</td>';
                            echo '<td>' . date("d/m/y h:i:s", fileatime($ruta_elem)) . '</td>';
                            echo '<td>' . (!$is_dir ? 'Sí' : 'No') . '</td>';
                            echo '<td>' . ($is_dir ? 'Sí' : 'No') . '</td>';
                            echo '<td>' . (is_readable($ruta_elem) ? 'Sí' : 'No') . '</td>';
                            echo '<td>' . (is_writable($ruta_elem) ? 'Sí' : 'No') . '</td>';
                            echo '<td>' . (is_executable($ruta_elem) ? 'Sí' : 'No') . '</td>';
                            echo '<td>';
                            if ($is_dir) {
                                echo '<a href="codes/borrar_carpeta.php?carpeta=' . urlencode((isset($_GET['ruta']) ? $_GET['ruta'] . '/' : '') . $elem) . '" onclick="return confirm(\'¿Seguro que deseas borrar esta carpeta?\')">Borrar</a>';
                            } else {
                                echo '<a href="./codes/borrarchi.php?archi=' . $elem . '&ruta=' . urlencode(isset($_GET['ruta']) ? $_GET['ruta'] : '') . '" onclick="return confirm(\'¿Seguro que deseas borrar este archivo?\')">Borrar</a>';
                            }
                            echo '</td>';
                            echo '</tr>';
                            $conta++;
                        }
                    }

                    echo '</table>';
                    closedir($directorio);

                    if ($conta == 0) {
                        echo 'La carpeta del usuario se encuentra vacía.';
                    }
                    ?>
            </div>
        </div>
    </main>
    <footer class="row">
        <?php include_once('sections/foot.inc'); ?>
    </footer>
</body>
</html>
