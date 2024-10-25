<?php
require './db/conn.php';
// upload_file.php
if (session_status() == PHP_SESSION_NONE)
    session_start();
// Verificar si el archivo fue cargado sin errores
if ($_FILES['file_param']['error'] == UPLOAD_ERR_OK) {
    // Definir rutas según el tipo de archivo
    $fileType = $_POST['file_type']; // Obtener el tipo de archivo enviado desde Froala ('image', 'video', 'file')

    // Establecer carpetas según el tipo de archivo
    switch ($fileType) {
        case 'image':
            $uploadFolder = '../assets/media/img/achivos'; // Carpeta para imágenes
            break;
        case 'video':
            $uploadFolder = '../assets/media/video/achivos'; // Carpeta para videos
            break;
        case 'audio':
            $uploadFolder = '../assets/media/audio/achivos'; // Carpeta para videos
            break;
        case 'file':
            $uploadFolder = '../assets/media/achivos'; // Carpeta para archivos generales
            break;
        default:
            $uploadFolder = '../assets/media/achivos'; // Carpeta por defecto
    }

    // Validar que la carpeta existe o crearla
    $uploadDir = './' . $uploadFolder . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Crear la carpeta si no existe
    }

    // Obtener el hash MD5 del archivo
    $fileTmpPath = $_FILES['file_param']['tmp_name'];
    $fileHash = md5_file($fileTmpPath);
    $extension = pathinfo($_FILES['file_param']['name'], PATHINFO_EXTENSION);

    // Inicializar el contador
    $counter = 1;
    $uploadFile = $uploadDir . $fileHash . '-' . $counter . '.' . $extension;

    // Verificar si el archivo ya existe y modificar el nombre si es necesario
    while (file_exists($uploadFile)) {
        $counter++;
        $uploadFile = $uploadDir . $fileHash . '-' . $counter . '.' . $extension;
    }

    // Mover el archivo al directorio especificado
    if (move_uploaded_file($fileTmpPath, $uploadFile)) {
       $db->insert('archivo_curso',["ruta"=>$uploadFile,"id_usuario"=> $_SESSION["USUARIO_ID"],"id_curso"=>$_POST["id_curso"]]);
        echo json_encode([
            'link' => $uploadFile // Retornar el enlace completo para Froala
        ]);
    } else {
        // Error al mover el archivo
        echo json_encode(['error' => 'Error al mover el archivo.']);
    }
} else {
    // Error al cargar el archivo
    echo json_encode(['error' => 'No se recibió archivo o hubo un error en la carga.']);
}
