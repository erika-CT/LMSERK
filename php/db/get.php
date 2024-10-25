<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();
header('Content-Type: application/json');
//conexion a la base de datos
require './conn.php';
//verificacion que la llamada sea metodo GET y que se reciba el parametro a = action
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['a'])) {
    //casos de a
    switch ($_GET['a']) {

            //acciones seccion
        case 'seccion-categorias':
            $categorias = $db->getAll('todas_categorias');  // Asegúrate de que este método devuelva un array
            // Formato esperado por Select2

            echo json_encode($categorias);
            break;
        case 'seccion-cursos':
            $fromAll=true;
        case 'seccion-mis-cursos':
            $rol = 0;
            if (isset($_SESSION["ROL"]) && !isset($fromAll)){
                $rol = $_SESSION["ROL"];
                $user = $_SESSION["USUARIO_ID"];
            }
            switch ($rol) {
                case "1":
                    $cursos = $db->getAll('todos_los_cursos');
                    break;
                case "4":
                    $cursos = $db->getAll('obtener_cursos_inscritos_con_todo(' . $user .')');
                    break;
                default:
                    $cursos = $db->getAll('todos_los_cursos');
                    break;
            }

            echo json_encode($cursos);
            break;
        case 'seccion-instructores':
            $instructores = $db->getAll('todos_instructores');  // Asegúrate de que este método devuelva un array
            // Formato esperado por Select2

            echo json_encode($instructores);
            break;
        case 'seccion-usuarios':
            $usuarios = $db->getAll('todos_usuarios');  // Asegúrate de que este método devuelva un array
            // Formato esperado por Select2

            echo json_encode($usuarios);
            break;
        case 'seccion-estudiantes':
            $estudiantes = $db->getAll('todos_estudiantes');  // Asegúrate de que este método devuelva un array
            // Formato esperado por Select2

            echo json_encode($estudiantes);
            break;


        case 'categorias_select':
            // Obtén las categorías desde la base de datos
            $categorias = $db->getAll('categoria_activas', '*', "nombre ilike '%" . $_GET['q'] . "%'");  // Asegúrate de que este método devuelva un array
            // Formato esperado por Select2
            $resultado = array_map(function ($categoria) {
                return [
                    'id' => $categoria['id_categoria'],  // ID de la categoría
                    'text' => $categoria['nombre']  // Nombre de la categoría
                    ,
                    'img' => $categoria["foto"] ? $categoria["foto"] : '../assets/media/img/site/categoria_placeholder-thumb.png'
                ];
            }, $categorias);
            echo json_encode(array('error' => false, 'data' => $resultado));
            break;
        case 'instructores_select':
            // Obtén las categorías desde la base de datos
            $instructores = $db->getAll('instructores', '*', "nombres ilike '%" . $_GET['q'] . "%' or apellidos ilike '%" . $_GET['q'] . "%'");  // Asegúrate de que este método devuelva un array
            // Formato esperado por Select2
            $resultado = array_map(function ($instructor) {
                return [
                    'id' => $instructor['id_usuario'],  // ID de la categoría
                    'text' => $instructor['nombres']  // Nombre de la categoría
                ];
            }, $instructores);
            echo json_encode(array('error' => false, 'data' => $resultado));
            break;
        case 'estudiantes_select':
            // Obtén las categorías desde la base de datos
            $estudiantes = $db->getAll('obtener_estudiantes_sin_inscribir(' . $_GET['x'] . ')', '*', "nombres ilike '%" . $_GET['q'] . "%'");  // Asegúrate de que este método devuelva un array
            // Formato esperado por Select2
            $resultado = array_map(function ($estudiante) {
                return [
                    'id' => $estudiante['id_usuario'],
                    'text' => $estudiante['nombres'],
                    'img' => $estudiante['foto']
                ];
            }, $estudiantes);
            echo json_encode(array('error' => false, 'data' => $resultado));
            break;

        case 'categorias':
            die(json_encode(array("error" => false, "data" => $db->getAll('categoria'))));
            break;
        case 'categoria':
            $id = $_GET['id'];
            echo json_encode(array("error" => false, "data" => $db->get('categoria', '*', "id_categoria = $id")));
            break;
        case 'instructor':
            $id = $_GET['id'];
            $sent = $db->get('get_instructores', '*', "id_usuario = $id");
            echo json_encode(array("error" => false, "data" => $sent));
            break;
        case 'estudiante':
            $id = $_GET['id'];
            $sent = $db->get('get_estudiante', '*', "id_usuario = $id");
            echo json_encode(array("error" => false, "data" => $sent));
            break;
        case 'usuario':
            $id = $_GET['id'];
            $sent = $db->get('get_usuario', '*', "id_usuario = $id");
            echo json_encode(array("error" => false, "data" => $sent));
            break;
        case 'mensajes':
            $data = $db->getAll('obtener_ultimos_mensajes(' . $_GET["u"] . ')');
            echo json_encode($data);
            break;
        case 'contactos':

            $data = $db->getAll('seccion,');
            echo json_encode($data);
            break;
        case 'seccion_data':

            $seccion = $db->get("seccion_curso", "*", "id_seccion = " . $_GET['u']);
            $p = "../." . $seccion["ruta"];
            $fileContent = "";
            if (file_exists($p))
                $fileContent = file_get_contents($p);
            echo json_encode(array("nombre"=>$seccion["nombre"],"html"=>$fileContent));
            break;
        case 'mensajes_contacto':
            $data = $db->getAll('obtener_mensajes_conversacion(' . $_SESSION["USUARIO_ID"] . ',' . $_GET["u"] . ')');
            echo json_encode($data);
            break;

        case 'estudiantes_select':
            // Obtén las categorías desde la base de datos
            $estudiantes = $db->getAll('instructor_buscar', '*', "nombre ilike '%" . $_GET['q'] . "%'");  // Asegúrate de que este método devuelva un array
            // Formato esperado por Select2
            $resultado = array_map(function ($instructore) {
                return [
                    'id' => $instructore['id_instructor'],  // ID de la categoría
                    'text' => $instructore['nombre']  // Nombre de la categoría
                ];
            }, $estudiantes);
            echo json_encode(array('error' => false, 'data' => $resultado));
            break;
        default:

            die(json_encode(array("error" => true, "msg" => "parámetro no valido")));
    }
} else {
    echo json_encode(array("error" => true, "msg" => "Solicitud no valida"));
}
