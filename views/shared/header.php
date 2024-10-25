  <?php function generarColorPastelAleatorio($opacity = 1) //esta funcion genera colores aleatorios tipo pastel
  {
    // Generar valores RGB aleatorios
    $r = rand(0, 127); // 0-127 para un tono más oscuro
    $g = rand(0, 127);
    $b = rand(0, 127);

    // Convertir a hexadecimal
    $rHex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
    $gHex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
    $bHex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

    // Asegurarse de que la opacidad esté en el rango de 0 a 1
    $opacity = max(0, min(1, $opacity));

    // Convertir la opacidad a formato hexadecimal (00 a FF)
    $opacityHex = str_pad(dechex(round($opacity * 255)), 2, '0', STR_PAD_LEFT);

    // Retornar el color en formato hexadecimal ARGB
    return "#{$rHex}{$gHex}{$bHex}{$opacityHex}"; // Color en formato hex ARGB
  }
  $nombres = "";
  $apellidos = "";
  $correo = "";

  $foto = "./assets/media/img/site/user.png";
  $nombre_apellido = "Invitado";
  if ($logueado) {
    $nombre_apellido = $_SESSION['NOMBRE_CORTO'];;
    $nombres = $_SESSION['NOMBRES'];
    $apellidos = $_SESSION['APELLIDOS'];
    $correo = $_SESSION['CORREO'];
    $rol = $_SESSION['ROL'];
    $foto = $_SESSION['FOTO'];
  }
  ?>
  <style>
    <?php if ($rol != 1) echo ".ocultar{display:none!important;}";
    else echo ".noocultar{display:none!important;}" ?><?php if ($logueado) echo ".ocultar.logueado{display:unset!important;}.mostrar{display:none!important;}" ?>
  </style>
  <nav id="erika-navbar" class="border-gray-200 fixed w-full top-0 z-[1]">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <a href="./" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="./assets/media/img/site/logo.png" class="h-8" alt="Flowbite Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Erika</span>
      </a>
      <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">

        <div class="text-center">
          <?php if ($logueado) { ?>
            <button data-overlayed-toogle="#erika-mensajes" class="text-white rounded-lg text-sm mr-2 px-5 py-2.5 "
              type="button">
              <?php include './php/svg/mensajes.svg' ?>
            </button>
            <button id="erika-notificaciones" class="mr-10 relative inline-flex items-center text-sm font-medium text-center  hover:text-gray-900 focus:outline-none dark:hover:text-white text-gray-400" type="button">

              <?php include './php/svg/notificacion.svg' ?>

              <div class="absolute block w-3 h-3 bg-red-500 border-2 border-white rounded-full -top-0.5 start-2.5 dark:border-gray-900"></div>
            </button>
          <?php } ?>
        </div>
        <button type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
          <span class="sr-only">Open user menu</span>
          <img class="w-8 h-8 rounded-full" src="<?= $foto ?>" alt="user photo">
        </button>
        <!-- Dropdown menu -->
        <div class="backdrop-blur-lg dark:bg-[#000]/50 bg-[#fff]/50 z-50 hidden my-4 text-base list-none  divide-y divide-gray-100 rounded-lg shadow dark:divide-gray-600" id="user-dropdown">
          <div class="px-4 py-3">
            <span class="block text-sm  dark:text-white"><?= $nombre_apellido ?></span>
            <span class="block text-sm   truncate text-gray-400"><?= $correo ?></span>
          </div>
          <ul class="py-2" aria-labelledby="user-menu-button">
            <?php if ($logueado && $rol == 1) { ?>
              <li>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:dark:bg-[#000]/50 hover:bg-[#fff]/50 backdrop-blur-lg dark:text-gray-200 dark:hover:text-white"><?= $lan["administracion"] ?></a>
              </li>
            <?php } ?>
            <?php if ($logueado) { ?>

              <li>
                <a href="#" class="btn-cerrar-sesion block px-4 py-2 text-sm text-gray-700 hover:dark:bg-[#000]/50 hover:bg-[#fff]/50 backdrop-blur-lg dark:text-gray-200 dark:hover:text-white"><?= $lan["cerrar_sesion"] ?></a>
              </li>
            <?php } else { ?>
              <li>
                <!-- aca es donde se le permite al usuario iniciar sesion -->
                <a href="./login" class="block px-4 py-2 text-sm text-gray-700 hover:dark:bg-[#000]/50 hover:bg-[#fff]/50 backdrop-blur-lg dark:text-gray-200 dark:hover:text-white"><?= $lan["iniciar_sesion"] ?></a>
              </li>
            <?php } ?>
          </ul>
        </div>

      </div>

    </div>
  </nav>






  <!-- notificaciones -->
  <div id="dropdownNotification"
    class="z-20 transform tranlate-x-[1rem] mr-4 hidden w-full max-w-sm dark:bg-[#000]/50 bg-[#fff]/50 backdrop-blur-lg divide-y divide-gray-100 rounded-lg shadow  dark:divide-gray-700">
    <div class="block px-4 py-2 font-medium text-center text-gray-700 rounded-t-lg  dark:bg-[#000]/10 bg-[#fff]/10 dark:text-white">
      Notifications
    </div>
    <div class="divide-y divide-gray-100 dark:divide-gray-700">
      <a href="#" class="flex px-4 py-3 hover:dark:bg-[#000]/20 hover:bg-[#fff]/20">
        <div class="flex-shrink-0">
          <img class="rounded-full w-11 h-11" src="./assets/media/img/site/user.png" alt="Jese image">
          <div class="absolute flex items-center justify-center w-5 h-5 ms-6 -mt-5 bg-blue-600 border border-white rounded-full dark:border-gray-800">
            <svg class="w-2 h-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
              <path d="M1 18h16a1 1 0 0 0 1-1v-6h-4.439a.99.99 0 0 0-.908.6 3.978 3.978 0 0 1-7.306 0 .99.99 0 0 0-.908-.6H0v6a1 1 0 0 0 1 1Z" />
              <path d="M4.439 9a2.99 2.99 0 0 1 2.742 1.8 1.977 1.977 0 0 0 3.638 0A2.99 2.99 0 0 1 13.561 9H17.8L15.977.783A1 1 0 0 0 15 0H3a1 1 0 0 0-.977.783L.2 9h4.239Z" />
            </svg>
          </div>
        </div>
        <div class="w-full ps-3">
          <div class=" text-sm mb-1.5 text-gray-400">New message from <span class="font-semibold  dark:text-white">Jese Leos</span>: "Hey, what's up? All set for the presentation?"</div>
          <div class="text-xs text-blue-600 dark:text-blue-500">a few moments ago</div>
        </div>
      </a>
      <a href="#" class="flex px-4 py-3 hover:dark:bg-[#000]/20 hover:bg-[#fff]/20">
        <div class="flex-shrink-0">
          <img class="rounded-full w-11 h-11" src="./assets/media/img/site/user.png" alt="Joseph image">
          <div class="absolute flex items-center justify-center w-5 h-5 ms-6 -mt-5 bg-gray-900 border border-white rounded-full dark:border-gray-800">
            <svg class="w-2 h-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
              <path d="M6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Zm11-3h-2V5a1 1 0 0 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 0 0 2 0V9h2a1 1 0 1 0 0-2Z" />
            </svg>
          </div>
        </div>
        <div class="w-full ps-3">
          <div class=" text-sm mb-1.5 text-gray-400"><span class="font-semibold  dark:text-white">Joseph Mcfall</span> and <span class="font-medium  dark:text-white">5 others</span> started following you.</div>
          <div class="text-xs text-blue-600 dark:text-blue-500">10 minutes ago</div>
        </div>
      </a>
      <a href="#" class="flex px-4 py-3 hover:dark:bg-[#000]/20 hover:bg-[#fff]/20">
        <div class="flex-shrink-0">
          <img class="rounded-full w-11 h-11" src="./assets/media/img/site/user.png" alt="Bonnie image">
          <div class="absolute flex items-center justify-center w-5 h-5 ms-6 -mt-5 bg-red-600 border border-white rounded-full dark:border-gray-800">
            <svg class="w-2 h-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
              <path d="M17.947 2.053a5.209 5.209 0 0 0-3.793-1.53A6.414 6.414 0 0 0 10 2.311 6.482 6.482 0 0 0 5.824.5a5.2 5.2 0 0 0-3.8 1.521c-1.915 1.916-2.315 5.392.625 8.333l7 7a.5.5 0 0 0 .708 0l7-7a6.6 6.6 0 0 0 2.123-4.508 5.179 5.179 0 0 0-1.533-3.793Z" />
            </svg>
          </div>
        </div>
        <div class="w-full ps-3">
          <div class=" text-sm mb-1.5 text-gray-400"><span class="font-semibold  dark:text-white">Bonnie Green</span> and <span class="font-medium  dark:text-white">141 others</span> love your story. See it and view more stories.</div>
          <div class="text-xs text-blue-600 dark:text-blue-500">44 minutes ago</div>
        </div>
      </a>
      <a href="#" class="flex px-4 py-3 hover:dark:bg-[#000]/20 hover:bg-[#fff]/20">
        <div class="flex-shrink-0">
          <img class="rounded-full w-11 h-11" src="./assets/media/img/site/user.png" alt="Leslie image">
          <div class="absolute flex items-center justify-center w-5 h-5 ms-6 -mt-5 bg-green-400 border border-white rounded-full dark:border-gray-800">
            <svg class="w-2 h-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
              <path d="M18 0H2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h2v4a1 1 0 0 0 1.707.707L10.414 13H18a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5 4h2a1 1 0 1 1 0 2h-2a1 1 0 1 1 0-2ZM5 4h5a1 1 0 1 1 0 2H5a1 1 0 0 1 0-2Zm2 5H5a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Zm9 0h-6a1 1 0 0 1 0-2h6a1 1 0 1 1 0 2Z" />
            </svg>
          </div>
        </div>
        <div class="w-full ps-3">
          <div class=" text-sm mb-1.5 text-gray-400"><span class="font-semibold  dark:text-white">Leslie Livingston</span> mentioned you in a comment: <span class="font-medium text-blue-500" href="#">@bonnie.green</span> what do you say?</div>
          <div class="text-xs text-blue-600 dark:text-blue-500">1 hour ago</div>
        </div>
      </a>
      <a href="#" class="flex px-4 py-3 hover:dark:bg-[#000]/10 hover:bg-[#fff]/10">
        <div class="flex-shrink-0">
          <img class="rounded-full w-11 h-11" src="./assets/media/img/site/user.png" alt="Robert image">
          <div class="absolute flex items-center justify-center w-5 h-5 ms-6 -mt-5 bg-purple-500 border border-white rounded-full dark:border-gray-800">
            <svg class="w-2 h-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
              <path d="M11 0H2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm8.585 1.189a.994.994 0 0 0-.9-.138l-2.965.983a1 1 0 0 0-.685.949v8a1 1 0 0 0 .675.946l2.965 1.02a1.013 1.013 0 0 0 1.032-.242A1 1 0 0 0 20 12V2a1 1 0 0 0-.415-.811Z" />
            </svg>
          </div>
        </div>
        <div class="w-full ps-3">
          <div class=" text-sm mb-1.5 text-gray-400"><span class="font-semibold  dark:text-white">Robert Brown</span> posted a new video: Glassmorphism - learn how to implement the new design trend.</div>
          <div class="text-xs text-blue-600 dark:text-blue-500">3 hours ago</div>
        </div>
      </a>
    </div>
    <a href="#" class="block py-2 text-sm font-medium text-center  rounded-b-lg dark:text-white dark:bg-[#000]/10 bg-[#fff]/10 hover:dark:bg-[#000]/50 hover:bg-[#fff]/50">
      <div class="inline-flex items-center ">
        <svg class="w-4 h-4 me-2  text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
          <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
        </svg>
        View all
      </div>
    </a>
  </div>




  <div id="menulateraladmin" class="menu">
    <?php if ($rol == 1) { ?>
      <div class="con-tabs animado" style="--delay:0.7s;">
        <div class="tabs glass-bg rounded rounded-lg">
          <input type="radio" id="radio-0" name="tabs" hidden checked />
          <input type="radio" id="radio-1" name="tabs" <?php if ($pagina == "/home") {
                                                          echo " checked";
                                                        } ?> />
          <label data-last="seccion-home"
            data-seccion="seccion-home"
            class="tab flex relative"
            for="radio-1">
            <span class="ml-1 p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-[9f3ab7]">
              <?php include './php/svg/tablero.svg' ?>
            </span>
            <span class="ml-3 text-white leyenda">Inicio</span>
            <span class="notification mr-2 hidden small"></span>
          </label>


          <input type="radio" id="radio-2" name="tabs" <?php if ($pagina == "/cursos") {
                                                          echo " checked";
                                                        } ?> />
          <label data-last="seccion-cursos"
            data-seccion="seccion-cursos"
            class="tab flex relative"
            for="radio-2">
            <span class="ml-1 p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-[9f3ab7]">
              <?php include './php/svg/cursos.svg' ?>
            </span>
            <span class="ml-3 text-white leyenda">Cursos</span>


            <?php
            $curso = json_encode($db->getCol("curso", "count(*)"));
            $expand = $curso > 999 ? "expand" : "";
            ?>
            <span class="curso_counter notification mr-2 small <?= $expand ?>"><?= $curso ?></span>
          </label>

          <input type="radio" id="radio-3" name="tabs" <?php if ($pagina == "/categorias") {
                                                          echo " checked";
                                                        } ?> />




          <label data-last="seccion-categorias"
            data-seccion="seccion-categorias"
            class="tab flex relative"
            for="radio-3">
            <span class="ml-1 p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-[9f3ab7]">
              <?php include './php/svg/categoria.svg' ?>
            </span>
            <span class="ml-3 text-white leyenda Capitalize"><?= $lan['categorias'] ?></span>

            <?php
            $categoria = json_encode($db->getCol("categoria", "count(*)"));
            $expand = $categoria > 999 ? "expand" : "";
            ?>
            <span class="categoria_counter notification mr-2 small <?= $expand ?>"><?= $categoria ?></span>
          </label>



          <input type="radio" id="radio-4" name="tabs" <?php if ($pagina == "/estudiantes") {
                                                          echo " checked";
                                                        } ?> />


          <label data-last="seccion-estudiantes"
            data-seccion="seccion-estudiantes"
            class="tab flex relative"
            for="radio-4">
            <span class="ml-1 p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-[9f3ab7]">
              <?php include './php/svg/estudiantes.svg' ?>
            </span>
            <span class="ml-3 text-white leyenda">Estudiantes</span>
            <?php $estudiantes = json_encode($db->getCol("todos_estudiantes", "count(*)"));
            $expand = $estudiantes > 999 ? "expand" : "";
            ?>
            <span class="estudiante_counter notification mr-2 small <?= $expand ?>"><?= $estudiantes ?>
            </span>
          </label>



          <input type="radio" id="radio-5" name="tabs" <?php if ($pagina == "/instructores") {
                                                          echo " checked";
                                                        } ?> />
          <label data-last="seccion-instructores" data-seccion="seccion-instructores" class="tab flex relative" for="radio-5">

            <span class="ml-1 flex w-auto flex items-center p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] w-min group
        border cursor-pointer border-transparent hover:border-[9f3ab7]">
              <?php include './php/svg/instructores.svg' ?>
            </span>
            <span class="ml-3 text-white leyenda">Instructores</span>

            <?php
            $instructor = json_encode($db->getCol("todos_instructores", "count(*)"));
            $expand = $instructor > 999 ? "expand" : "";
            ?>
            <span class="instructor_counter notification mr-2 small <?= $expand ?>"><?= $instructor ?></span>
          </label>
          <input type="radio" id="radio-6" name="tabs" <?php if ($pagina == "/usuarios") {
                                                          echo " checked";
                                                        } ?> />
          <label data-last="seccion-usuarios" data-seccion="seccion-usuarios" class="tab flex relative" for="radio-6">

            <span class="ml-1 flex w-auto flex items-center p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] w-min group
        border cursor-pointer border-transparent hover:border-[9f3ab7]">
              <?php include './php/svg/usuarios.svg' ?>
            </span>
            <span class="ml-3 text-white leyenda"><?= $lan['usuarios'] ?></span>

            <?php
            $usuario = json_encode($db->getCol("usuario", "count(*)"));
            $expand = $usuario > 999 ? "expand" : "";
            ?>
            <span class="usuario_counter notification mr-2 small <?= $expand ?>"><?= $usuario ?></span>
          </label>
          <input type="radio" id="radio-7" name="tabs" <?php if ($pagina == "/ajustes") {
                                                          echo " checked";
                                                        } ?> />
          <label data-last="seccion-ajustes" data-seccion="seccion-ajustes" class="tab" for="radio-7">

            <span class="ml-1 flex w-auto flex items-center p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] w-min group
        border cursor-pointer border-transparent hover:border-[9f3ab7]">
              <?php include './php/svg/ajustes.svg' ?>
            </span>
            <span class="ml-3 text-white leyenda">Ajustes</span>

          </label>

          <span class="glider"></span>
          <span class="glider-hover"></span>



          <label
            class="menu-izquierdo-collapser flex relative h-[2rem]">
            <span class="offmenu absolute p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-[9f3ab7]">
              <?php include './php/svg/collapse-off.svg' ?>
            </span>
            <span class="onmenu hidden absolute p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-[9f3ab7]">
              <?php include './php/svg/collapse-on.svg' ?>
            </span>
          </label>

        </div>

      </div>
    <?php } ?>
    <div id="datelateral" class="mostrar animado" style="width: 100%; scale: 0.89;--delay: 10s"></div>
    <div class="con-tabs animado  <?php if ($rol == 1) echo 'hidden' ?>  <?php if (!$logueado || $rol == 1) echo " ocultar" ?>" style="--delay:0.7s;">
      <div class="tabs glass-bg rounded rounded-lg">
        <input type="radio" id="radio-0" name="tabs" hidden checked />
        <input type="radio" id="radio-1" name="tabs" <?php if ($pagina == "/home") {
                                                        echo " checked";
                                                      } ?> />
        <label data-last="seccion-home"
          data-seccion="seccion-home"
          class="tab flex relative"
          for="radio-1">
          <span class="ml-1 p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-[9f3ab7]">
            <?php include './php/svg/tablero.svg' ?>
          </span>
          <span class="ml-3 text-white leyenda">Inicio</span>
          <span class="notification mr-2 hidden small"></span>
        </label>
        <input type="radio" id="radio-2" name="tabs" <?php if ($pagina == "/mis-cursos") {
                                                        echo " checked";
                                                      } ?> />
        <label data-last="seccion-home"
          data-seccion="seccion-mis-cursos"
          class="tab flex relative"
          for="radio-2">
          <span class="ml-1 p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-[9f3ab7]">
            <?php include './php/svg/tablero.svg' ?>
          </span>
          <span class="ml-3 text-white leyenda">Mis Cursos</span>
          <span class="notification mr-2 hidden small"></span>
        </label>

        <input type="radio" id="radio-3" name="tabs" <?php if ($pagina == "/mis-tareas") {
                                                        echo " checked";
                                                      } ?> />

        <label data-last="seccion-home"
          data-seccion="seccion-mis-tareas"
          class="tab flex relative"
          for="radio-3">
          <span class="ml-1 p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-[9f3ab7]">
            <?php include './php/svg/tablero.svg' ?>
          </span>
          <span class="ml-3 text-white leyenda">Mis Tareas</span>
          <span class="notification mr-2 hidden small"></span>
        </label>


        <input type="radio" id="radio-4" name="tabs" <?php if ($pagina == "/mis-archivos") {
                                                        echo " checked";
                                                      } ?> />

        <label data-last="seccion-home"
          data-seccion="seccion-mis-archivos"
          class="tab flex relative"
          for="radio-4">
          <span class="ml-1 p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-[9f3ab7]">
            <?php include './php/svg/tablero.svg' ?>
          </span>
          <span class="ml-3 text-white leyenda">Mis Archivos</span>
          <span class="notification mr-2 hidden small"></span>
        </label>



        <input type="radio" id="radio-5" name="tabs" <?php if ($pagina == "/cursos") {
                                                        echo " checked";
                                                      } ?> />

        <label data-last="seccion-home"
          data-seccion="seccion-cursos"
          class="tab flex relative"
          for="radio-5">
          <span class="ml-1 p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-[9f3ab7]">
            <?php include './php/svg/tablero.svg' ?>
          </span>
          <span class="ml-3 text-white leyenda">Ver todos los cursos</span>
          <span class="notification mr-2 hidden small"></span>
        </label>





        <span class="glider"></span>
        <span class="glider-hover"></span>
        <label
          class="menu-izquierdo-collapser flex relative h-[2rem]">
          <span class="offmenu absolute p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-[9f3ab7]">
            <?php include './php/svg/collapse-off.svg' ?>
          </span>
          <span class="onmenu hidden absolute p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-[9f3ab7]">
            <?php include './php/svg/collapse-on.svg' ?>
          </span>
        </label>
      </div>
    </div>
  </div>



  <div class="overlay" data-overlayed="#erika-mensajes" data-overlayed-toogle="#erika-mensajes" style="opacity: 0; pointer-events: none;"></div>
  <div drawer id="erika-mensajes" style="transform: translateX(100%); opacity: 0; pointer-events: none;"
    class="fixed top-0 right-0 z-[101] max-[2rem] min-[2rem] h-screen p-4 overflow-y-auto transition-transform 
  dark:bg-[#000]/40 bg-[#fff]/40 backdrop-blur-lg"
    tabindex="-1">

    <h5 class="text-base font-semibold  uppercase text-gray-400"><?= $lan['mensajeria'] ?></h5>

    <div style="--delay:0.3s;" class="animado glass-bg p-2 account-profile  mb-2  w-full flex justify-start  items-center">
      <img class="big lazod lazy-img" src="<?= $foto ?>" data-src="<?php echo str_replace("-thumb", "", $foto) ?>" alt="">

      <div class="flex flex-col items-start justify-center ml-5 p-2">
        <div class="text-left text-white"><?php if(isset($_SESSION["USUARIO_ID"])){ echo $_SESSION["NOMBRES"] . " " . $_SESSION["APELLIDOS"];}?></div>
      </div>
    </div>
    <div class="tabs horizontal">

      <input type="radio" name="c2" id="radio-mensaje-1" name="tabs" checked />
      <label class="tab" for="radio-mensaje-1" tabindex="1">Mensajes</label>

      <input type="radio" name="c2" id="radio-mensaje-2" name="tabs" />
      <label class="tab" for="radio-mensaje-2" tabindex="2">
        Contactos
      </label>

      <span class="glider horizontal horizontal-ouline"></span>
      <span class="glider-hover horizontal"></span>
    </div>
    <!-- Close Button -->
    <button type="button"
      data-overlayed-toogle="#erika-mensajes"
      class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-[#374151]/20 dark:hover:text-white">
      <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
      </svg>
      <span class="sr-only">Close menu</span>
    </button>

    <!-- Navigation Content -->
    <div class="py-4 relative" style="
    height: Calc(100% - 14rem);
">
      <span id="atras_chat" style="position: absolute; top: 0px; left: 0px; cursor: pointer; opacity: 0; transform: translateX(100%); pointer-events: none; display: none;">
        <div class="flex"><?php include_once './php/svg/atras.svg' ?>
          <span class="ml-5">Nombre del detinatario <span class="ml-2 status inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300" tabindex="-1">
              <span class="w-2 h-2 me-1 bg-red-500 rounded-full" tabindex="-1" id="erk_erk_2"></span>
              Desconectado
            </span></span>
        </div>
      </span>
      <ul class="space-y-2">
        <li>
          <div id="li_list_chats" class="animado flex justify-center chat-item-container" style="--delay:0.5s;">
            <ul role="list" class="px-2 max-w-sm divide-y divide-gray-200 dark:divide-gray-700" id="mensajesListErk">
            </ul>
          </div>
          <div id="li_list_contactos" class="animado flex justify-center chat-item-container" style="--delay:0.5s;">

            <ul role="list" class="px-2 max-w-sm divide-y divide-gray-200 dark:divide-gray-700" id="contactosListErk" tabindex="-1">

            </ul>
          </div>
        </li>

        <li id="li_enviar_mensaje" style="position: absolute; width: 100%; bottom: 0px; left: 0px; transform: translateY(0%) translateX(-100%); opacity: 0; pointer-events: none; display: none;">
          
          
          <ul id="listado_de_mensajes" role="list" class="max-w-sm divide-y divide-gray-200 dark:divide-gray-700"style="
    width: 100%;
    max-width: 100%;
    padding:12px;
    overflow-y: auto;
    max-height: 28rem;
">

            
          </ul>
          <form id="enviar-mensaje-textor" style="height: auto;width: 94%;min-height: unset;margin: 0 auto;">
            <div class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-[#374151]/20 dark:border-gray-600">
              <div class="px-4 py-2 bg-white rounded-t-lg dark:bg-gray-800">
                <label for="text-msg" class="sr-only">Your text-msg</label>
                <textarea
                tabindex="1"
                  style="
    padding: 7px 4px 3px 5px;
"
                  id="text-msg" name="text-msg" rows="4" class="w-full px-0 text-sm  bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400" placeholder="Escriba su mensaje..." required></textarea>
              </div>
              <div class="flex items-center justify-between px-3 py-2 border-t dark:border-gray-600">
                <button type="submit" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                  Enviar <span><?php include './php/svg/enviar.svg'?></span>
                </button>
                <div class="flex ps-0 space-x-1 rtl:space-x-reverse sm:ps-2">
                  <button type="button" class="inline-flex justify-center items-center p-2  rounded cursor-pointer hover:text-gray-900 hover:bg-[#374151]/50 text-gray-400 dark:hover:text-white dark:hover:bg-[#374151]/20">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 20">
                      <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M1 6v8a5 5 0 1 0 10 0V4.5a3.5 3.5 0 1 0-7 0V13a2 2 0 0 0 4 0V6" />
                    </svg>
                    <span class="sr-only">Attach file</span>
                  </button>
                  <button type="button" class="inline-flex justify-center items-center p-2  rounded cursor-pointer hover:text-gray-900 hover:bg-[#374151]/50 text-gray-400 dark:hover:text-white dark:hover:bg-[#374151]/20">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                      <path d="M8 0a7.992 7.992 0 0 0-6.583 12.535 1 1 0 0 0 .12.183l.12.146c.112.145.227.285.326.4l5.245 6.374a1 1 0 0 0 1.545-.003l5.092-6.205c.206-.222.4-.455.578-.7l.127-.155a.934.934 0 0 0 .122-.192A8.001 8.001 0 0 0 8 0Zm0 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
                    </svg>
                    <span class="sr-only">Set location</span>
                  </button>
                  <button type="button" class="inline-flex justify-center items-center p-2  rounded cursor-pointer hover:text-gray-900 hover:bg-[#374151]/50 text-gray-400 dark:hover:text-white dark:hover:bg-[#374151]/20">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                      <path d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                    </svg>
                    <span class="sr-only">Upload image</span>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </li>
      </ul>
    </div>
    <!-- 
  
  <p class="ms-auto text-xs  text-gray-400">Remember, contributions to this topic should follow our <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline">Community Guidelines</a>.</p>
   -->

  </div>