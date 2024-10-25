<?php

require '../php/db/conn.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['v'])) {
    switch ($_GET['v']) {
        case 'seccion-home':
            include '../views/home.php';
            break;
        case 'curso_secciones':
            $secciones = $db->getAll("seccion_curso", "*", "id_curso =" . $_GET['id_curso'] . " order by orden");
            $secs = [];
            if (count($secciones) > 0) {
                foreach ($secciones as $seccion) {
                    $p = "." . $seccion["ruta"];
                    $fileContent = "";
                    if (file_exists($p))
                        $fileContent = file_get_contents($p);
                    array_push($secs, ["id_curso" => $_GET['id_curso'], "id_seccion" => $seccion["id_seccion"], "nombre" => $seccion["nombre"], "html" => $fileContent]);
                }
            }
            echo json_encode(array("secciones" => $secs));
            break;
        case 'curso_detalle':
            ob_start();
            include '../views/curso_detalles.php';
            $inscritos = $db->getAll("obtener_estudiantes_inscritos(" . $_GET['id_curso'] . ")");
            $secciones = $db->getAll("seccion_curso", "*", "id_curso =" . $_GET['id_curso'] . " order by orden");
            $secs = [];
            if (count($secciones) > 0) {
                foreach ($secciones as $seccion) {
                    $p = "." . $seccion["ruta"];
                    $fileContent = "";
                    if (file_exists($p))
                        $fileContent = file_get_contents($p);
                    array_push($secs, ["id_curso" => $_GET['id_curso'],"id_seccion" => $seccion["id_seccion"], "nombre" => $seccion["nombre"], "html" => $fileContent]);
                }
            }

            // Leer el contenido del archivo en un array



            $html = ob_get_clean();
            echo json_encode(array("html" => $html, "inscritos" => $inscritos, "secciones" => $secs));
            break;
        case 'nueva_inscripcion_data':
            header('Content-Type: application/json');
            ob_start();
            include '../partial/nueva_inscripcion_data.php';
            $html = ob_get_clean();
            echo json_encode(array("error" => false, "html" => $html));
            break;
        case 'seccion-categorias':
            include '../views/categorias.php';
            break;
    }
}
