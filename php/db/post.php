<?php
header('Content-Type: application/json');
if (session_status() == PHP_SESSION_NONE)
    session_start();
//conexion a la base de datos
require './conn.php';
require '../lang.php';
$categorias_carpeta = "./assets/media/img/categorias/";
$usuarios_carpeta = "./assets/media/img/usuarios/";
$curso_carpeta = "./assets/media/img/cursos/";
$secciones_carpeta = "./assets/media/cursos/secciones/";
//verificacion que la llamada sea metodo GET y que se reciba el parametro a = action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['a'])) {
    //casos de a
    switch ($_POST['a']) {
        case 'curso':
            if (
                !isset($_POST["curso_nombre"])
                || !isset($_POST["curso_nombre_corto"])
                || !isset($_POST["curso_fecha_ini"])
                || !isset($_POST["curso_fecha_finalizacion"])
                || !isset($_POST["curso_de_pago"])
                || !isset($_POST["curso_precio"])
                || !isset($_POST["curso_categoria"])
                || !isset($_POST["curso_instructor"])
            ) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => $lan["faltan_datos"])));
            }
            $nombre = trim($_POST["curso_nombre"]);
            $nombre_corto = trim($_POST["curso_nombre_corto"]);
            $fecha_inicio = trim($_POST["curso_fecha_ini"]);
            $fecha_fin = trim($_POST["curso_fecha_finalizacion"]);
            $visible = trim($_POST["curso_visible"]);
            $precio = trim($_POST["curso_precio"]);
            $es_de_pago = trim($_POST["curso_de_pago"]);
            $categoria = trim($_POST["curso_categoria"]);
            $instructor = trim($_POST["curso_instructor"]);
            $imagen = procesarImagen('curso_imagen', 'foto', $curso_carpeta, $lan, [100, 100]);
            $data_i = array();
            $data_i["nombre"] = $nombre;
            $data_i["nombre_corto"] = $nombre_corto;
            $data_i["fecha_inicio"] = $fecha_inicio;
            $data_i["fecha_fin"] = $fecha_fin;
            $data_i["fecha_fin"] = $fecha_fin;
            $data_i["visible"] = $visible;
            $data_i["precio"] = empty($precio) ? 0 : $precio;
            $data_i["id_categoria"] = $categoria;
            $data_i["id_instructor"] = $instructor;
            $data_i["foto"] = $imagen;
            $data_i["es_de_pago"] = $es_de_pago;


            if (isset($_POST['actualizar']))
                $id_curso = $db->update('curso', $data_i, 'id_curso=' . $_POST['actualizar']);
            else
                $id_curso = $db->insert('curso', $data_i, 'id_curso');

            if ($id_curso) {

                if (isset($_POST['actualizar']))
                    echo json_encode(array("error" => false, "msg" =>  str_replace("{{1}}", $nombre, "Se actualizo el curso {{1}}")));
                else
                    echo json_encode(array("error" => false, "msg" =>  str_replace("{{1}}", $nombre, $lan["curso_registrado"])));
            } else {

                if (isset($_POST['actualizar']))
                    echo json_encode(array("error" => true, "msg" =>  str_replace("{{1}}", $nombre, "Ocurrio un erro actualizando el curso {{1}}")));
                else
                    echo json_encode(array("error" => true, "msg" =>  str_replace("{{1}}", $nombre, $lan["errores_sql"]["curso"])));
            }
            break;
        case 'categoria':
            if (!isset($_POST["categoria_nombre"])) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => "faltan datos")));
            }
            $nombre = trim($_POST["categoria_nombre"]);
            $imagen = procesarImagen('categoria_imagen', 'foto', $categorias_carpeta, $lan, [100, 100]);

            if (isset($_POST['actualizar']))
                $id_categoria = $db->update('categoria', ["nombre" => $nombre, "foto" => $imagen], 'id_categoria=' . $_POST['actualizar']);
            else
                $id_categoria = $db->insert('categoria', ["nombre" => $nombre, "foto" => $imagen], 'id_categoria');
            if ($id_categoria)
                if (isset($_POST['actualizar']))
                    echo json_encode(array("error" => false, "msg" =>  str_replace("{{1}}", $nombre, "Se actuactualizo la categoria {{1}}")));
                else
                    echo json_encode(array("error" => false, "msg" =>  str_replace("{{1}}", $nombre, $lan["categoria_registrada"])));
            else
            if (isset($_POST['actualizar']))
                echo json_encode(array("error" => true, "msg" =>  str_replace("{{1}}", $nombre, "ocurrio un error actuactualizando la categoria {{1}}")));
            else
                echo json_encode(array("error" => true, "msg" =>  str_replace("{{1}}", $nombre, $lan["errores_sql"]["categoria"])));
            break;
        case 'instructor':
            if (!isset($_POST["instructor_nombre"])) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => "faltan datos")));
            }
            $data_i = array();
            $nombre = trim($_POST["instructor_nombre"]);
            $apellido = trim($_POST["instructor_apellido"]);
            $correo = trim($_POST["instructor_correo"]);
            $sexo = trim($_POST["instructor_sexo"]);
            $fecha_nacimiento = trim($_POST["instructor_fecha_nacimiento"]);
            $data_i["nombres"] = $nombre;
            $data_i["apellidos"] = $apellido;
            $data_i["correo"] = $correo;
            $data_i["sexo"] = $sexo;
            $data_i["fecha_nacimiento"] = $fecha_nacimiento;

            // [ " "contra" => $pass, "foto" => $imagen,  => , "fecha_nacimiento" => $fecha_nacimiento]
            if (isset($_POST['actualizar'])) {
                if (!empty($_POST['instructor_pass'])) {
                    $pass = password_hash(isset($_POST['instructor_pass']) ? trim($_POST['instructor_pass']) : '', PASSWORD_DEFAULT);
                    $data_i["contra"] = $pass;
                }
            } else {
                $pass = password_hash(isset($_POST['instructor_pass']) ? trim($_POST['instructor_pass']) : '', PASSWORD_DEFAULT);
                $data_i["contra"] = $pass;
                $data_i["rol"] = 3;
            }

            $imagen = procesarImagen('instructor_imagen', 'foto', $usuarios_carpeta, $lan, [100, 100]);
            $data_i["foto"] = $imagen;
            if (isset($_POST['actualizar']))
                $id_usuario = $db->update('usuario', $data_i, "id_usuario=" . $_POST['actualizar']);
            else
                $id_usuario = $db->insert('usuario', $data_i, 'id_usuario');

            if ($id_usuario)
                if (isset($_POST['actualizar']))
                    echo json_encode(array("error" => false, "msg" =>  str_replace("{{1}}", $nombre, "Se actualizo el intructor {{1}}")));
                else
                    echo json_encode(array("error" => false, "msg" =>  str_replace("{{1}}", $nombre, $lan["instructor_registrado"])));
            else
            if (isset($_POST['actualizar']))
                echo json_encode(array("error" => true, "msg" =>  str_replace("{{1}}", $nombre, "Ocurrio un error actualizando {{1}}")));
            else
                echo json_encode(array("error" => true, "msg" =>  str_replace("{{1}}", $nombre, $lan["errores_sql"]["usuario"])));
            break;
        case 'estudiante':
            if (!isset($_POST["estudiante_nombre"])) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => "faltan datos")));
            }
            $data_i = array();
            $nombre = trim($_POST["estudiante_nombre"]);
            $apellido = trim($_POST["estudiante_apellido"]);
            $correo = trim($_POST["estudiante_correo"]);
            $sexo = trim($_POST["estudiante_sexo"]);
            $fecha_nacimiento = trim($_POST["estudiante_fecha_nacimiento"]);
            $data_i["nombres"] = $nombre;
            $data_i["apellidos"] = $apellido;
            $data_i["correo"] = $correo;
            $data_i["sexo"] = $sexo;
            $data_i["fecha_nacimiento"] = $fecha_nacimiento;

            // [ " "contra" => $pass, "foto" => $imagen,  => , "fecha_nacimiento" => $fecha_nacimiento]
            if (isset($_POST['actualizar'])) {
                if (!empty($_POST['estudiante_pass'])) {
                    $pass = password_hash(isset($_POST['estudiante_pass']) ? trim($_POST['estudiante_pass']) : '', PASSWORD_DEFAULT);
                    $data_i["contra"] = $pass;
                }
            } else {
                $pass = password_hash(isset($_POST['estudiante_pass']) ? trim($_POST['estudiante_pass']) : '', PASSWORD_DEFAULT);
                $data_i["contra"] = $pass;
                $data_i["rol"] = 4;
            }

            $imagen = procesarImagen('estudiante_imagen', 'foto', $usuarios_carpeta, $lan, [100, 100]);
            $data_i["foto"] = $imagen;
            if (isset($_POST['actualizar']))
                $id_usuario = $db->update('usuario', $data_i, "id_usuario=" . $_POST['actualizar']);
            else
                $id_usuario = $db->insert('usuario', $data_i, 'id_usuario');

            if ($id_usuario)
                if (isset($_POST['actualizar']))
                    echo json_encode(array("error" => false, "msg" =>  str_replace("{{1}}", $nombre, "Se actualizo el estudiante {{1}}")));
                else
                    echo json_encode(array("error" => false, "msg" =>  str_replace("{{1}}", $nombre, $lan["estudiante_registrado"])));
            else
                if (isset($_POST['actualizar']))
                echo json_encode(array("error" => true, "msg" =>  str_replace("{{1}}", $nombre, "Ocurrio un error actualizando {{1}}")));
            else
                echo json_encode(array("error" => true, "msg" =>  str_replace("{{1}}", $nombre, $lan["errores_sql"]["usuario"])));
            break;
        case 'usuario':
            if (!isset($_POST["usuario_nombre"])) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => "faltan datos")));
            }
            $data_i = array();
            $nombre = trim($_POST["usuario_nombre"]);
            $apellido = trim($_POST["usuario_apellido"]);
            $correo = trim($_POST["usuario_correo"]);
            $sexo = trim($_POST["usuario_sexo"]);
            $fecha_nacimiento = trim($_POST["usuario_fecha_nacimiento"]);
            $data_i["nombres"] = $nombre;
            $data_i["apellidos"] = $apellido;
            $data_i["correo"] = $correo;
            $data_i["sexo"] = $sexo;
            $data_i["fecha_nacimiento"] = $fecha_nacimiento;

            // [ " "contra" => $pass, "foto" => $imagen,  => , "fecha_nacimiento" => $fecha_nacimiento]
            if (isset($_POST['actualizar'])) {
                if (!empty($_POST['usuario_pass'])) {
                    $pass = password_hash(isset($_POST['usuario_pass']) ? trim($_POST['usuario_pass']) : '', PASSWORD_DEFAULT);
                    $data_i["contra"] = $pass;
                }
            } else {
                $pass = password_hash(isset($_POST['usuario_pass']) ? trim($_POST['usuario_pass']) : '', PASSWORD_DEFAULT);
                $data_i["contra"] = $pass;
                $data_i["rol"] = 4;
            }

            $imagen = procesarImagen('usuario_imagen', 'foto', $usuarios_carpeta, $lan, [100, 100]);
            $data_i["foto"] = $imagen;
            if (isset($_POST['actualizar']))
                $id_usuario = $db->update('usuario', $data_i, "id_usuario=" . $_POST['actualizar']);
            else
                $id_usuario = $db->insert('usuario', $data_i, 'id_usuario');

            if ($id_usuario)
                if (isset($_POST['actualizar']))
                    echo json_encode(array("error" => false, "msg" =>  str_replace("{{1}}", $nombre, "Se actualizo el usuario {{1}}")));
                else
                    echo json_encode(array("error" => false, "msg" =>  str_replace("{{1}}", $nombre, "Usuario registrado con exito")));
            else
                    if (isset($_POST['actualizar']))
                echo json_encode(array("error" => true, "msg" =>  str_replace("{{1}}", $nombre, "Ocurrio un error actualizando {{1}}")));
            else
                echo json_encode(array("error" => true, "msg" =>  str_replace("{{1}}", $nombre, $lan["errores_sql"]["usuario"])));
            break;
        case 'curso_fecha_ini':
            if (!isset($_POST["id_curso"])) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => "faltan datos")));
            }
            $rows_modified = $db->update('curso', ["fecha_inicio" => $_POST['fecha']], 'id_curso = ' . $_POST['id_curso']);
            if ($rows_modified == -1) {
                echo json_encode(array("error" => true, "msg" => "Ocurio un errror actualizando la fecha"));
                exit();
            }
            if ($rows_modified == 0) {
                echo json_encode(array("error" => false, "msg" => "No se actualizo la fecha"));
                exit();
            }
            if ($rows_modified > 0) {
                echo json_encode(array("error" => false, "msg" => "Se actualizo la fecha"));
                exit();
            }
            break;
        case 'curso_fecha_fini':
            if (!isset($_POST["id_curso"])) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => "faltan datos")));
            }
            $rows_modified = $db->update('curso', ["fecha_fin" => $_POST['fecha']], 'id_curso = ' . $_POST['id_curso']);
            if ($rows_modified == -1) {
                echo json_encode(array("error" => true, "msg" => "Ocurio un errror actualizando la fecha"));
                exit();
            }
            if ($rows_modified == 0) {
                echo json_encode(array("error" => false, "msg" => "No se actualizo la fecha"));
                exit();
            }
            if ($rows_modified > 0) {
                echo json_encode(array("error" => false, "msg" => "Se actualizo la fecha"));
                exit();
            }
            break;
        case 'activar_desactivar':
            if (!isset($_POST["id_usuario"]) || !isset($_POST["estado"])) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => "faltan datos")));
            }
            $rows_modified = $db->update('usuario', ["activo" => $_POST['estado'], "fecha_modificacion" => "NOW()"], 'id_usuario = ' . $_POST['id_usuario']);
            if ($rows_modified == -1) {
                echo json_encode(array("error" => true, "msg" => "Ocurio un errror actualizando la fecha"));
                exit();
            }
            if ($rows_modified == 0) {
                echo json_encode(array("error" => false, "msg" => "No se actualizo la fecha"));
                exit();
            }
            if ($rows_modified > 0) {
                echo json_encode(array("error" => false, "msg" => "Se actualizo la fecha"));
                exit();
            }
            break;
        case 'bloquear_desbloquear':
            if (!isset($_POST["id_usuario"]) || !isset($_POST["estado"])) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => "faltan datos")));
            }
            $rows_modified = $db->update('usuario', ["bloqueado" => $_POST['estado'], "fecha_modificacion" => "NOW()"], 'id_usuario = ' . $_POST['id_usuario']);
            if ($rows_modified == -1) {
                echo json_encode(array("error" => true, "msg" => "Ocurio un errror actualizando la fecha"));
                exit();
            }
            if ($rows_modified == 0) {
                echo json_encode(array("error" => false, "msg" => "No se actualizo la fecha"));
                exit();
            }
            if ($rows_modified > 0) {
                echo json_encode(array("error" => false, "msg" => "Se actualizo la fecha"));
                exit();
            }
            break;
        case 'eliminar_usuario':
            if (!isset($_POST["id"])) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => "faltan datos")));
            }
            $deleted = $db->del('usuario', 'id_usuario = ' . $_POST['id']);
            if ($deleted) {
                if (is_array($deleted)) {
                    if ($deleted["error"]) {
                        if (strpos($deleted["msg"], "curso_id_usuario_fkey") !== false) {
                            echo json_encode(["error" => true, "msg" => "No se puede eliminar el usuario porque esta asignado en algun curso"]);
                            exit();
                        }
                    }
                } else {
                    echo json_encode(["error" => false, "msg" => "usuario Eliminado"]);
                    exit();
                }
            } else {
                echo json_encode(["error" => false, "msg" => "No se pudo eliminar"]);
                exit();
            }
            break;
        case 'eliminar_categoria':
            if (!isset($_POST["id"])) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => "faltan datos")));
            }
            $deleted = $db->del('categoria', 'id_categoria = ' . $_POST['id']);
            if ($deleted) {
                if (is_array($deleted)) {
                    if ($deleted["error"]) {
                        if (strpos($deleted["msg"], "curso_id_categoria_fkey") !== false) {
                            echo json_encode(["error" => true, "msg" => "No se puede eliminar la categoria porque esta asignada en algun curso"]);
                            exit();
                        }
                    }
                } else {
                    echo json_encode(["error" => false, "msg" => "Categoria Eliminada"]);
                    exit();
                }
            } else {
                echo json_encode(["error" => false, "msg" => "No se pudo eliminar"]);
                exit();
            }
            break;
        case 'eliminar_curso':
            if (!isset($_POST["id"])) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => "faltan datos")));
            }
            $secciones = $db->getAll("seccion_curso", "*", "id_curso =" . $_POST['id'] . " order by orden");

            echo json_encode(array("error" => false));
            $deleted = $db->del('curso', 'id_curso = ' . $_POST['id']);
            if ($deleted) {
                if (is_array($deleted)) {
                    if ($deleted["error"]) {
                        if (strpos($deleted["msg"], "curso_id_curso_fkey") !== false) {
                            echo json_encode(["error" => true, "msg" => "No se puede eliminar el curso"]);
                            exit();
                        }
                    }
                } else {
                    if (count($secciones) > 0) {
                        foreach ($secciones as $seccion) {
                            $p = "../." . $seccion["ruta"];
                            $fileContent = "";
                            if (file_exists($p))
                                unlink($p);
                        }
                    }
                    echo json_encode(["error" => false, "msg" => "Curso Eliminado"]);
                    exit();
                }
            } else {
                echo json_encode(["error" => false, "msg" => "No se pudo eliminar"]);
                exit();
            }
            break;
        case "inscribir":
            if (!isset($_POST["id_usuario"]) || !isset($_POST["id_curso"])) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => "faltan datos")));
            }

            $id_inscripcion = $db->insert('inscripcion', ["id_usuario" => $_POST["id_usuario"], "id_curso" => $_POST["id_curso"]], 'id_inscripcion');
            if ($id_inscripcion)
                echo json_encode(array("error" => false));
            else
                echo json_encode(array("error" => true));
            break;
        case 'crear_seccion':
            if (!isset($_POST["id_curso"])) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => "faltan datos")));
            }
            $contador = 1;
            $nombreArchivo = 'seccion_' . $contador . '.html';
            $lasP = "../." . $secciones_carpeta;
            if (!is_dir($lasP)) {
                if (!mkdir($lasP, 0777, true)) {
                    throw new Exception("No se pudo crear la carpeta:../." . $secciones_carpeta);
                }
            }
            while (file_exists($lasP     . $nombreArchivo)) {
                $contador++;
                $nombreArchivo = 'seccion_' . $contador . '.html';
            }
            file_put_contents($lasP  . $nombreArchivo, $_POST['html']);

            $ultimo = $db->getCol('obtener_ultimo_orden(' . $_POST["id_curso"] . ')');
            $id_seccion = $db->insert('seccion_curso', [
                "nombre" => $_POST["nombre"],
                "ruta" => $secciones_carpeta . $nombreArchivo,
                "id_curso" => $_POST["id_curso"],
                "orden" => $ultimo
            ], "id_seccion", 'id_seccion');
            if ($id_seccion) {
                $secciones = $db->getAll("seccion_curso", "*", "id_curso =" . $_POST['id_curso'] . " order by orden");
                $secs = [];
                if (count($secciones) > 0) {
                    foreach ($secciones as $seccion) {
                        $p = "../." . $seccion["ruta"];
                        $fileContent = "";
                        if (file_exists($p))
                            $fileContent = file_get_contents($p);
                        array_push($secs, ["id_seccion" => $seccion["id_seccion"], "nombre" => $seccion["nombre"], "html" => $fileContent]);
                    }
                }
                echo json_encode(array("error" => false, "secciones" => $secs));
            } else
                echo json_encode(array("error" => false));
            break;
            case 'actualizar_seccion':
                if (!isset($_POST["id_curso"])) {
                    http_response_code(400); // solicitud no valida
                    die(json_encode(array("error" => true, "msg" => "faltan datos")));
                }
              
               
                $seccion = $db->get("seccion_curso", "*", "id_seccion = " . $_POST['id']);
                $p = "../." . $seccion["ruta"];
                file_put_contents($p, $_POST['html']);
    
                $id_seccion = $db->update('seccion_curso', [
                    "nombre" => $_POST["nombre"]
                ], "id_seccion = " .  $_POST['id']);
         
                    echo json_encode(array("error" => false));
               
                break;
        case 'eliminar_seccion':
            if (!isset($_POST["id_curso"])) {
                http_response_code(400); // solicitud no valida
                die(json_encode(array("error" => true, "msg" => "faltan datos")));
            }

            $secciones = $db->get("seccion_curso", "*", "id_seccion =" . $_POST['id'] . " order by orden");
            $eliminado = $db->del("seccion_curso", "id_curso = "  . $_POST['id_curso']);
            if ( $secciones && count($secciones) > 0 && $eliminado) {
                foreach ($secciones as $seccion) {
                    $p = "../." . $seccion["ruta"];
                    $fileContent = "";
                    if (file_exists($p))
                        unlink($p);
                }
            }
            echo json_encode(array("error" => false));
            break;
            case 'nuevo_contacto':
                if (!isset($_POST["id_amigo"])) {
                    http_response_code(400); // solicitud no valida
                    die(json_encode(array("error" => true, "msg" => "faltan datos")));
                }
    
                $esAmigo = $db->get("amigo","id_amigo","id_amigo = " . $_POST["id_amigo"]);
                if($esAmigo){
                    echo json_encode(array("error" => true,"msg"=>"<div></div>Ya esta en su lista de contactos"));
                    exit();
                }
                $nuevo_contacto = $db->insert("amigo",["id_amigo"=>$_POST["id_amigo"],"id_usuario"=>$_SESSION["USUARIO_ID"]]);
                if($nuevo_contacto){
                    echo json_encode(array("error" => false,"msg"=>"<div></div>Se agrego como contacto"));
                    
                }else
                echo json_encode(array("error" => false,"msg"=>"No se pudo agregar contacto"));
                break;
            case 'enviar_mensaje':
                if (!isset($_POST["id_amigo"])) {
                    http_response_code(400); // solicitud no valida
                    die(json_encode(array("error" => true, "msg" => "faltan datos")));
                }
    
                $enviar_mensaje = $db->insert("mensaje",["id_usuario"=>$_SESSION["USUARIO_ID"],"id_destinatario"=>$_POST["id_amigo"],"mensaje"=>$_POST["msg"]],"id_mensaje");
                if($enviar_mensaje){
                    echo json_encode(array("error" => false,"<div></div>Se agrego como contacto"));
                    
                }else
                echo json_encode(array("error" => false,"msg"=>"No se pudo agregar contacto"));
                break;
        default:
            http_response_code(400); // solicitud no valida
            die(json_encode(array("error" => true, "msg" => "parámetro no valido")));
            break;
    }
} else {
    echo json_encode(array("error" => true, "msg" => "Solicitud no valida"));
}


function procesarImagen($identificador, $segundo_identificador, $carpeta, $lan, $w_h = [100, 100])
{
    $imagen = null;
    if (isset($_FILES[$identificador]) && $_FILES[$identificador]['error'] == 0) {
        // Guarda la imagen y verifica si hubo errores
        $guardarImagen = true;
        if (isset($_POST['actualizar'])) {
            if (isset($_POST[$segundo_identificador])) {

                $md5Hash = md5_file($_FILES[$identificador]['tmp_name']);
                $md5Hash2 = md5_file("../." . $_POST[$segundo_identificador]);
                if ($md5Hash == $md5Hash2) {
                    $imagen = $_POST[$segundo_identificador];
                    $guardarImagen = false;
                } else {
                    $fileInfo = pathinfo("../." . $_POST[$segundo_identificador]);
                    $thumbPath = $fileInfo['dirname'] . '/' . $fileInfo['filename'] . '-thumb.' . $fileInfo['extension'];
                    unlink($thumbPath);
                    unlink("../." . $_POST[$segundo_identificador]);
                }
            }
        }
        if ($guardarImagen) {
            $proceso = guardarImagen($carpeta, $_FILES[$identificador], $lan, $w_h[0], $w_h[1]);

            if (!$proceso["error"]) {
                $imagen = $carpeta . $proceso["img"]; // Establece la ruta de la imagen
            } else {
                http_response_code(500); // fallo en el servidor
                echo json_encode($proceso["mensaje"]); // Devuelve el mensaje de error
                die();
            }
        }
    } else {
        if (isset($_POST[$segundo_identificador])) {
            $fileInfo = pathinfo("../." . $_POST[$segundo_identificador]);
            $thumbPath = $fileInfo['dirname'] . '/' . $fileInfo['filename'] . '-thumb.' . $fileInfo['extension'];
            if (file_exists($thumbPath))
                unlink($thumbPath); //aca unlink =  ELiminar
            if (file_exists("../." . $_POST[$segundo_identificador]))
                unlink("../." . $_POST[$segundo_identificador]);
        }
    }
    return $imagen;
}

/**
 * Crea una miniatura de una imagen manteniendo la relación de aspecto original.
 *
 * Esta función toma una imagen original, su ancho y alto, y genera una miniatura con las dimensiones 
 * especificadas, ajustando el tamaño de manera proporcional para mantener la relación de aspecto.
 * Si las proporciones de la miniatura no coinciden con las de la imagen original, se ajusta el ancho o la altura
 * de la miniatura para evitar la distorsión.
 *
 * @param GdImage $originalImage  La imagen original como un recurso de imagen GD.
 * @param int $originalWidth       El ancho de la imagen original.
 * @param int $originalHeight      La altura de la imagen original.
 * @param int $thumbWidth          El ancho máximo deseado para la miniatura (opcional, por defecto es 100px).
 * @param int $thumbHeight         La altura máxima deseada para la miniatura (opcional, por defecto es 100px).
 *
 * @return GdImage                Retorna un recurso GD que representa la imagen miniatura generada.
 */
function crearMiniatura($originalImage, $originalWidth, $originalHeight, $thumbWidth = 100, $thumbHeight = 100)
{
    // Calcular la relación de aspecto de la imagen original
    $aspectRatio = $originalWidth / $originalHeight;

    // Ajustar las dimensiones de la miniatura manteniendo la relación de aspecto
    if ($thumbWidth / $thumbHeight > $aspectRatio) {
        // Si la miniatura es más ancha de lo necesario, ajusta la altura
        $thumbWidth = $thumbHeight * $aspectRatio;
    } else {
        // Si la miniatura es más alta de lo necesario, ajusta el ancho
        $thumbHeight = $thumbWidth / $aspectRatio;
    }

    // Crear una nueva imagen con las dimensiones ajustadas de la miniatura
    $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);

    // Redimensionar la imagen original a la miniatura
    imagecopyresampled($thumb, $originalImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $originalWidth, $originalHeight);

    return $thumb;
}
/**
 * Maneja la creación de directorios y genera nombres de archivo únicos.
 *
 * Esta función se encarga de verificar si el directorio donde se almacenarán las imágenes existe, 
 * creándolo si es necesario. También garantiza que el archivo tenga un nombre único en el directorio,
 * añadiendo sufijos numéricos si ya existe un archivo con el mismo nombre.
 *
 * @param string $carpeta       El nombre de la carpeta donde se almacenará la imagen.
 * @param string $archivo       El nombre base del archivo (generalmente un hash o identificador único).
 * @param string $fileExtension La extensión del archivo (por ejemplo: 'jpg', 'png', etc.).
 *
 * @return array                Retorna un arreglo con dos elementos:
 *                              1. La ruta completa del directorio donde se almacenará el archivo.
 *                              2. El nombre final del archivo (sin extensión), asegurando que sea único.
 *
 * @throws Exception            Lanza una excepción si no se puede crear el directorio.
 */
function manejarDirectorio($carpeta, $archivo, $fileExtension)
{
    // Definir la ruta del directorio destino


    // Verificar si la carpeta de destino existe, si no, crearla
    if (!is_dir($carpeta)) {
        if (!mkdir($carpeta, 0777, true)) {
            throw new Exception("No se pudo crear la carpeta: $carpeta");
        }
    }

    // Verificar si el archivo ya existe y agregar sufijos si es necesario
    $counter = 1;
    $newArchivo = $archivo;
    while (file_exists($carpeta . $newArchivo . '.' . $fileExtension)) {
        $newArchivo = $archivo . '-' . $counter; // Agregar un sufijo numérico
        $counter++;
    }

    return [$carpeta, $newArchivo];
}
/**
 * Obtiene las funciones de creación y salida de imágenes según su tipo.
 *
 * Esta función devuelve un arreglo que contiene las funciones necesarias para crear y 
 * guardar imágenes en función del tipo de imagen especificado. Se admite JPEG, PNG, GIF y WEBP.
 *
 * @param int $type El tipo de imagen, representado por constantes como IMAGETYPE_JPEG, 
 *                  IMAGETYPE_PNG, IMAGETYPE_GIF, o IMAGETYPE_WEBP.
 *
 * @return array Un arreglo asociativo que contiene:
 *               - 'create': nombre de la función para crear la imagen desde un archivo.
 *               - 'output': nombre de la función para guardar la imagen en un archivo.
 *               - 'ext': la extensión de archivo correspondiente a ese tipo de imagen.
 *
 * @throws Exception Lanza una excepción si el tipo de imagen no es soportado.
 */
function obtenerFuncionesImagen($type, $lan)
{

    switch ($type) {
        case IMAGETYPE_JPEG:
            return ['create' => 'imagecreatefromjpeg', 'output' => 'imagejpeg', 'ext' => 'jpg'];
        case IMAGETYPE_PNG:
            return ['create' => 'imagecreatefrompng', 'output' => 'imagepng', 'ext' => 'png'];
        case IMAGETYPE_GIF:
            return ['create' => 'imagecreatefromgif', 'output' => 'imagegif', 'ext' => 'gif'];
        case IMAGETYPE_WEBP:
            return ['create' => 'imagecreatefromwebp', 'output' => 'imagewebp', 'ext' => 'webp'];
        case IMAGETYPE_AVIF:
            return ['create' => 'imagecreatefromavif', 'output' => 'imageavif', 'ext' => 'avif'];
        case IMAGETYPE_BMP:
            return ['create' => 'imagecreatefrombmp', 'output' => 'imagebmp', 'ext' => 'bmp'];
        case IMAGETYPE_WBMP:
            return ['create' => 'imagecreatefromwbmp', 'output' => 'imagewbmp', 'ext' => 'wbmp'];
        case IMAGETYPE_XBM:
            return ['create' => 'imagecreatefromxbm', 'output' => 'imagexbm', 'ext' => 'xbm'];
        case IMAGETYPE_TIFF_II:  // TIFF (Intel byte order)
        case IMAGETYPE_TIFF_MM:  // TIFF (Motorola byte order)
            return ['create' => 'imagecreatefromtiff', 'output' => 'imagetiff', 'ext' => 'tiff'];
        case IMAGETYPE_ICO:
            return ['create' => 'imagecreatefromico', 'output' => 'imageico', 'ext' => 'ico']; // Icon file
        case IMAGETYPE_PSD:
            return ['create' => 'imagecreatefrompsd', 'output' => 'imagepsd', 'ext' => 'psd']; // Photoshop file
        default:
            return false;
    }
}
/**
 * Función principal para guardar una imagen y su miniatura.
 *
 * Esta función maneja la subida de una imagen desde un formulario, crea una versión 
 * en miniatura manteniendo la relación de aspecto y guarda ambas en el sistema de archivos.
 *
 * @param string $carpeta La carpeta donde se guardarán las imágenes.
 * @param array $lan Un arreglo que contiene mensajes de error y otros textos relacionados 
 *                   a la localización (localization).
 * @param int $thumbWidth (Opcional) El ancho deseado para la miniatura. Por defecto, 100.
 * @param int $thumbHeight (Opcional) La altura deseada para la miniatura. Por defecto, 100.
 *
 * @return array Un arreglo que contiene:
 *               - 'error': booleano que indica si hubo un error en el proceso.
 *               - 'mensaje': mensaje de error si hubo un problema (si 'error' es true).
 *               - 'img': el nombre del archivo de la miniatura guardada (si 'error' es false).
 *
 * @throws Exception Lanza una excepción si ocurre un error al mover la imagen, crear la miniatura, 
 *                   o si el tipo de imagen no es soportado.
 */
function guardarImagen($carpeta, $file, $lan, $thumbWidth = 100, $thumbHeight = 100)
{
    $imgTemp = $file['tmp_name'];
    $Imagen = $file['name'];

    // Obtener el hash MD5
    $md5Hash = md5_file($imgTemp);
    $fileExtension = pathinfo($Imagen, PATHINFO_EXTENSION);

    // Obtener el tipo de la imagen con getimagesize()
    list($width, $height, $type) = getimagesize($imgTemp);

    // Obtener funciones de imagen basadas en el tipo (no en la extensión)
    $funcionesImagen = obtenerFuncionesImagen($type, $lan);
    if (!$funcionesImagen) {
        return array("error" => true, "mensaje" => str_replace("{{1}}", $lan["validaciones"]["imagenes_validas"], $lan["validaciones"]["imagenes_permitidas"]));
    }

    // Manejar la ruta del archivo y nombre único
    list($ruta, $newImagen) = manejarDirectorio("../../" . $carpeta, $md5Hash, $fileExtension);

    // Mover el archivo
    if (!move_uploaded_file($imgTemp, $ruta . $newImagen . '.' . $fileExtension)) {
        return json_encode(array("error" => true, "mensaje" => $lan["errores_sql"]["imagen"]));
    }

    $originalImage = $funcionesImagen['create']($ruta . $newImagen . '.' . $fileExtension);
    if (!$originalImage) {
        return json_encode(array("error" => true, "mensaje" => $lan['errores_php']["thumb"]));
    }

    // Crear miniatura
    $thumb = crearMiniatura($originalImage, $width, $height, $thumbWidth, $thumbHeight);

    // Guardar miniatura
    $thumbPath = $ruta . $newImagen . '-thumb.' . $fileExtension;
    $funcionesImagen['output']($thumb, $thumbPath);

    // Liberar recursos
    imagedestroy($originalImage);
    imagedestroy($thumb);

    return array("error" => false, "img" => $newImagen . '-thumb.' . $fileExtension);
}
