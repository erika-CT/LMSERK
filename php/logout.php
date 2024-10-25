<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
   
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        session_unset();
        session_write_close();
        echo json_encode(array('error'=>false,"msg"=>"Salio de su cuenta"));
        exit();
    }else{
        echo json_encode(array('error'=>true,"msg"=>"No se pudo cerra la sesion"));
        exit();
}

?>
