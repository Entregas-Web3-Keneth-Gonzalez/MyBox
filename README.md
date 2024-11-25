# Lab05 - MyBox Implementation

## Descripción
Este proyecto implementa un sistema de gestión de archivos basado en PHP, que permite a los usuarios autenticados crear, navegar y administrar carpetas y archivos en un entorno controlado. Además, incluye restricciones de acceso y límites de tamaño para los archivos cargados.

---

## Funcionalidades Implementadas

### 1. Crear, Navegar y Borrar Carpetas
- Los usuarios pueden:
  - **Crear carpetas**: Desde la interfaz de `carpetas.php`, se proporciona un formulario para agregar nuevas carpetas.
  - **Navegar entre carpetas**: Se muestra el contenido de las carpetas, y es posible navegar hacia atrás o hacia las subcarpetas con un diseño intuitivo.
  - **Borrar carpetas**: Las carpetas vacías pueden ser eliminadas después de confirmación.

### 2. Subir, Descargar y Borrar Archivos
- **Subida de archivos**: Los usuarios pueden subir archivos desde cualquier carpeta.
- **Descarga de archivos**: Los usuarios pueden descargar archivos con cualquier tipo de extensión.
- **Borrar archivos**: Implementada con validación de confirmación para evitar eliminaciones accidentales.

### 3. Interfaz Gráfica Mejorada
- Se ha actualizado `carpetas.php` con las siguientes características:
  - Iconos para diferenciar entre **carpetas** y **archivos**.
  - Representación del tipo de archivo mediante iconos según la extensión: PDF, imágenes, documentos, etc.
  - Se incluye información adicional en la tabla:
    - Nombre del archivo.
    - Tamaño en MB.
    - Último acceso.
    - Opciones de lectura, escritura y ejecución.

### 4. Visualización Condicional de Archivos
- Modificación en `abrarchi.php`:
  - Los archivos con extensiones `pdf`, `jpg`, y `png` se muestran directamente en el navegador.
  - Las demás extensiones son forzadas a descargarse.

### 5. Configuración de Restricciones de Control
- **Restricción de tamaño**:
  - Los archivos subidos no pueden exceder los 20 MB.
- **Restricción de acceso por IP**:
  - Solo tres direcciones IP del laboratorio tienen acceso al sitio.
- **Documentación de configuración**:
  - Configuración detallada incluida en el archivo `.htaccess`.

---

## Archivos Clave
### Código Fuente
- **carpetas.php**: Página principal para gestionar carpetas y archivos.
- **abrarchi.php**: Permite abrir archivos según su tipo.
- **agrearchi.php**: Permite la subida de archivos desde la interfaz gráfica.
- **borrarchi.php**: Elimina archivos con confirmación.
- **codes/crear_carpeta.php**: Crea nuevas carpetas en las rutas del usuario.
- **codes/borrar_carpeta.php**: Borra carpetas específicas si están vacías.

### Configuración
- **.htaccess**:
  - Restricciones de IP.
  - Límite de tamaño de archivos subidos.
  - Redirección a HTTPS.
  - Páginas personalizadas para errores HTTP.
- **php.ini** (en el servidor):
  - Configuraciones opcionales para soporte del límite de tamaño de archivos:
    ```ini
    upload_max_filesize = 20M
    post_max_size = 20M
    ```

---

## Requerimientos del Servidor
1. **PHP**: Versión 7.4 o superior.
2. **Servidor Web**: Apache con soporte para `.htaccess`.
3. **Base de Datos**: MySQL para autenticación de usuarios.
4. **Extensiones PHP requeridas**:
   - `mysqli`
   - `mime_content_type` para determinar el tipo MIME de los archivos.

---

## Notas Adicionales
- **Pruebas de funcionamiento**:
  - Se realizaron pruebas con diferentes tipos de archivos (PDF, imágenes, documentos) y tamaños para garantizar las restricciones.
- **Mejoras futuras**:
  - Implementar un sistema de roles para usuarios con diferentes niveles de acceso.
  - Mejorar la gestión de errores con mensajes más descriptivos.

---

## Configuración del Proyecto
1. Clona este repositorio en tu servidor local:
   ```bash
   git clone https://github.com/Tecnologias-y-Sistemas-Web-III/mybox.git
