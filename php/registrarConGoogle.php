<?php
include 'conexion.php';
// Initialize the session
session_start();
// Update the following variables
$google_oauth_client_id = '500102623271-l47kobjvjcm5nik482gqnhcqjm3ddu1e.apps.googleusercontent.com';
$google_oauth_client_secret = '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';
$google_oauth_redirect_uri = 'http://margarita-libreria.com/libreria/php/registrarConGoogle.php';
$google_oauth_version = 'v3';


if (isset($_GET['code']) && !empty($_GET['code'])) {
    // Execute cURL request to retrieve the access token
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
        // Execute cURL request to retrieve the user info associated with the Google account
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/' . $google_oauth_version . '/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $response['access_token']]);
        $response = curl_exec($ch);
        curl_close($ch);
        $profile = json_decode($response, true);
        // Make sure the profile data exists
        if (isset($profile['email'])) {

            session_regenerate_id();
            $_SESSION['r_google'] = true;
            $_SESSION['r_correo'] = $profile['email'];
            $_SESSION['r_nombre'] = isset($profile['given_name']) ? ucwords(strtolower(preg_replace('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ]/u', ' ', $profile['given_name']))) : '';
            $_SESSION['r_apellido'] = isset($profile['family_name']) ? ucwords(strtolower(preg_replace('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ]/u', ' ', $profile['family_name']))) : '';
            $_SESSION['r_foto'] = isset($profile['picture']) ? $profile['picture'] : './img/user.png';

            session_write_close();
            echo json_encode($profile);

            $correo = $profile['email'];;
      
            $sql = " SELECT * FROM usuarios WHERE correo='$correo'; ";

            if ($resultado = $conn->query($sql)) {
                if ($resultado = $resultado->fetch_assoc()) {
                    header("Location: ../index.php?k=./view/registro&google=false&alert=existe");
                    exit;
                } 
            } else {
                $conn->close();
                header("Location: ../index.php?k=./view/error");
            }

            //Redirect to profile page
            header("Location: ../index.php?k=./view/registro&google=true");
            exit;
        } else {
            exit('Could not retrieve profile information! Please try again later!');
        }
    } else {
        exit('Invalid access token! Please try again later!');
    }
    // Code goes here...
} else {
    // Define params and redirect to Google Authentication page
    $params = [
        'response_type' => 'code',
        'client_id' => $google_oauth_client_id,
        'redirect_uri' => $google_oauth_redirect_uri,
        'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
        'access_type' => 'offline',
        'prompt' => 'consent'
    ];
    header('Location: https://accounts.google.com/o/oauth2/auth?' . http_build_query($params));
    exit;
}
