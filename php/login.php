<?php
include './db/conn.php';
include './lang.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['correo'])) {
        die();
    }

    if (!isset($_POST['contra'])) {
        die();
    }

    $correo = $_POST['correo'];
    $contra = $_POST['contra'];
    $userData = $db->getAll('usuario', '*', "correo = '$correo'");
    if (!$userData) {
        echo json_encode(array("error"=>true,"mensaje"=>"Correo o contraseña invalida"));
        die();
    }

    $userData = $userData[0];
    if (password_verify(
        $contra, //contraseña que el usuario envia
        $userData['contra'] //contraseña encriptada en la base de datos
    )) {
        //verificando que no exista una sesion activa
        if (session_status() == PHP_SESSION_NONE)
            session_start();
            $_SESSION["NOMBRES"] = $userData['nombres'];
            $_SESSION["NOMBRE_CORTO"] = explode(' ', $userData['nombres'])[0];
            $_SESSION["APELLIDOS"] = $userData['apellidos'];
            $_SESSION['LOGUEADO'] = TRUE;
            $_SESSION["CORREO"] = $userData['correo'];
            $_SESSION["ROL"] = $userData['rol'];
            $_SESSION["FOTO"] = $userData['foto']?$userData['foto']:"./assets/media/img/site/user.png";
            $_SESSION["USUARIO_ID"] = $userData['id_usuario'];
        session_write_close();
        echo json_encode(array('error' => false));
        exit();
    } else {

        if (session_status() == PHP_SESSION_NONE)
            session_start();
        session_unset();
        session_write_close();
        echo json_encode(array('error' => true, "mensaje" => $lan["nologin"]));
        exit();
    }
}
