<?php
include '../php/db/conn.php'; // solo que esta se conecta a POSTGRESQL
include '../php/lang.php'; // solo que esta se conecta a POSTGRESQL
// Inicializa la sesión
ob_start();
if (session_status() == PHP_SESSION_NONE)
    session_start();
// Variables de configuración de OAuth de Google
$google_oauth_client_id = '500102623271-l47kobjvjcm5nik482gqnhcqjm3ddu1e.apps.googleusercontent.com';
$google_oauth_client_secret = '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';
$google_oauth_redirect_uri = 'http://lmserk.com:8080/php/google-oauth.php';
$google_oauth_version = 'v3';

if (isset($_GET['code']) && !empty($_GET['code'])) {
    // Ejecuta solicitud cURL para obtener el token de acceso
    $params = [
        'code' => $_GET['code'],
        'client_id' => $google_oauth_client_id,
        'client_secret' => $google_oauth_client_secret,
        'redirect_uri' => $google_oauth_redirect_uri,
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, true);

    if (isset($response['access_token']) && !empty($response['access_token'])) {
        // Ejecuta solicitud cURL para obtener la información del usuario
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/' . $google_oauth_version . '/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $response['access_token']]);
        $response = curl_exec($ch);
        curl_close($ch);
        $profile = json_decode($response, true);

        // Verifica que la información del perfil exista
        if (isset($profile['email'])) {
            session_regenerate_id();
            
            $correo = $profile['email'];
            $userData = $db->get('usuario', '*', "correo = '$correo'"); // necesitamos la base de datos aca
            
            if (!$userData) {
                echo json_encode(array("error" => true, "mensaje" => $lan["usuario_no_encontrado"]));
                header("Location: ../login");
                exit(); // Usa exit() en lugar de die() para detener la ejecución
            }

            //hay que probar XDD
            // Verifica si ya existe una sesión activa
            $_SESSION["NOMBRES"] = $userData['nombres'];
            $_SESSION["NOMBRE_CORTO"] = explode(' ', $userData['nombres'])[0];
            $_SESSION["APELLIDOS"] = $userData['apellidos'];
            $_SESSION['LOGUEADO'] = TRUE;
            $_SESSION["CORREO"] = $userData['correo'];
            $_SESSION["ROL"] = $userData['rol'];
            $_SESSION["FOTO"] = $userData['foto']?$userData['foto']:"./assets/media/img/site/user.png";
            $_SESSION["USUARIO_ID"] = $userData['id_usuario'];
            session_write_close();

            // Después de cerrar la sesión, redirige sin ninguna salida previa
            header("Location: ../../index.php");
            exit(); // Usa exit() para detener la ejecución después de redirigir
        } else {
            exit('No se pudo obtener la información del perfil. ¡Inténtalo de nuevo más tarde!');
        }
    } else {
        exit('¡Token de acceso no válido! ¡Inténtalo de nuevo más tarde!');
    }
} else {
    // Define los parámetros y redirige a la página de autenticación de Google
    $params = [
        'response_type' => 'code',
        'client_id' => $google_oauth_client_id,
        'redirect_uri' => $google_oauth_redirect_uri,
        'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
        'access_type' => 'offline',
        'prompt' => 'consent'
    ];
    header('Location: https://accounts.google.com/o/oauth2/auth?' . http_build_query($params));
    exit();
}
