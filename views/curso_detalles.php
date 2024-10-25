<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();
$rol = "";
if (isset($_GET['id_curso'])) {
    $curso = ($db->exec('select * from obtener_curso_con_todo(' . $_GET['id_curso'] . ')'))[0];
    $curso_nombre = $curso["curso_nombre"];
    $id_curso = $curso["id_curso"];
    $usuario_nombre = $curso["instructor"];
    $curso_foto = $curso["curso_foto"];
    $usuario_foto = $curso["usuario_foto"];
    $curso["curso_fecha_inicio"];
    $categoria_nombre = $curso["categoria_nombre"];
    $fecha_ini = (new DateTime($curso["curso_fecha_inicio"]))->format('d \d\e F \d\e Y g:i A');
    if (isset($_SESSION["USUARIO_ID"])) {
        $rol = $_SESSION["ROL"];
        $id_usuario = $_SESSION["USUARIO_ID"];
        $usuarioInscrito = $db->getCol("SELECT esta_inscrito_en_curso($id_curso, $id_usuario);");
    } else
        $usuarioInscrito = false;
    $fecha_fin = (new DateTime($curso["curso_fecha_fin"]))->format('d \d\e F \d\e Y g:i A');
?>

    <div class="videoPlayer hidden">
        <video id="videoPlayer-vid" class="video-js">
            <source src="//vjs.zencdn.net/v/oceans.mp4">
        </video>
    </div>


    <div class="main-container animado" style="--delay:0.7s;">

        <div style="z-index: 1;" class=" user-box  flex  flex-col lg:flex-row">

            <div class="glass-bg" style="--delay: 0.2s">
                <div class="account-profile flex flex-col items-center">
                    <img src="<?= $usuario_foto ?>" class="lozad lazy-img" data-src="<?= str_replace("-thumb", "", $usuario_foto) ?>" alt="">
                    <div class="blob-wrap">
                        <div class="blob"></div>
                        <div class="blob"></div>
                        <div class="blob"></div>
                    </div>
                    <div class="account-name text-white"><?= $usuario_nombre ?></div>
                    <div class="account-title  text-white">Instructor del Curso</div>
                </div>
            </div>
            <div class="ml-0 lg:ml-5 mt-0 lg:mt-3 glass-bg w-full px-4 py-3" style="--delay: 0.7s">
                <div class="flex items-center">
                    <span
                        erika-tooltip
                        tooltip-content="Cambiar nombre del curso"
                        id="cambiar-curso-nombre-btn"
                        class="ocultar relative flex w-[2rem] h-[2rem] mr-2 flex items-center p-2 rounded-full bg-[#ffffff61] w-min group border cursor-pointer border-transparent hover:border-gray-200">

                        <div
                            class="absolute z-20 top-[2rem]"
                            id="cambiar-curso-nombre"
                            data-setdate="<?= $curso["curso_fecha_inicio"] ?>"
                            style="transform: translateX(100%); opacity: 0; pointer-events: none; display: none;"></div>


                        <span class="relative icon-holder flex m-0 items-center justify-center  w-8 h-8">
                            <span class="absolute loader-date"><?php include '../php/svg/loader.svg' ?></span>
                            <span class="absolute edit"><?php include '../php/svg/editar.svg' ?></span>
                        </span>
                    </span>
                    <div class="flex">
                        <span class="text-lg md:text-xl lg:text-2xl"><?= $curso_nombre ?></span>
                        <?php if ($curso["permite_inscribir"]) { ?>
                            <button
                                tabindex="1"
                                type="button" class="h-9 whitespace-nowrap  ml-3 btn-siguiente w-full text-white bg-[#2196f3]/50 hover:bg-[#2196f3]/90 font-medium rounded-lg text-sm px-2 py-2 text-center">Inscribirme a este curso</button>
                        <?php } ?>
                    </div>
                </div>
                <div class="tabs horizontal">

                    <input type="radio" name="c2" id="radio-cat-1" name="tabs" checked />
                    <label class="tab" for="radio-cat-1" tabindex="1">Detalles</label>

                    <input type="radio" name="c2" id="radio-cat-2" name="tabs" />
                    <label class="ocultar tab" for="radio-cat-2" tabindex="2">
                        Permisos
                    </label>

                    <span class="glider horizontal"></span>
                    <span class="glider-hover horizontal"></span>
                </div>
                <div class="glass-bg flex flex-col text-white">

                    <div class="flex items-center">
                        <span
                            erika-tooltip
                            tooltip-content="Cambiar fecha de inicio del curso"
                            id="cambiar_fecha_ini-btn"
                            class="ocultar relative flex w-[2rem] h-[2rem] mr-2 flex items-center p-2 rounded-full bg-[#ffffff61] w-min group border cursor-pointer border-transparent hover:border-gray-200">

                            <div
                                class="absolute  z-20 top-[2rem]"
                                id="cambiar_fecha_ini"
                                data-setdate="<?= $curso["curso_fecha_inicio"] ?>"
                                style="transform: translateX(100%); opacity: 0; pointer-events: none; display: none;"></div>


                            <span class="ocultar relative icon-holder flex m-0 items-center justify-center  w-8 h-8">
                                <span class="absolute loader-date"><?php include '../php/svg/loader.svg' ?></span>
                                <span class="absolute edit"><?php include '../php/svg/editar.svg' ?></span>
                            </span>
                        </span><span>Fecha de inicio: <b class="fecha_ini"><?= $fecha_ini ?></b></span>
                    </div>





                    <div class="flex mt-2 mb-2 items-center">
                        <span
                            erika-tooltip
                            tooltip-content="Cambiar fecha de finalizacion del curso"
                            id="cambiar_fecha_fin-btn"
                            class="ocultar relative flex w-[2rem] h-[2rem] mr-2 flex items-center p-2 rounded-full bg-[#ffffff61] w-min group border cursor-pointer border-transparent hover:border-gray-200">
                            <div class="absolute z-20 top-[2rem]"
                                id="cambiar_fecha_fin"
                                data-setdate="<?= $curso["curso_fecha_fin"] ?>"
                                data-fecha-min="<?= $curso["curso_fecha_inicio"] ?>"
                                style="transform: translateX(100%); opacity: 0; pointer-events: none; display: none;"></div>
                            <span class="relative icon-holder flex m-0 items-center justify-center  w-8 h-8">
                                <span class="absolute loader-date"><?php include '../php/svg/loader.svg' ?></span>
                                <span class="absolute edit"><?php include '../php/svg/editar.svg' ?></span>
                            </span>
                        </span><span>Fecha de finalizacion: <b class="fecha_fini"><?= $fecha_fin ?></b></span>
                    </div>
                    <div class="flex items-center">

                        <span
                            onclick="cargarAlumnosParaInscribir(<?= $_GET['id_curso'] ?>)"
                            abrir-formulario
                            data-formulario="nuevainscripcion"
                            erika-tooltip
                            tooltip-content="Inscribir Estudiantes"
                            id="cambiar_fecha_fin-btn"
                            class="ocultar relative flex w-[2rem] h-[2rem] mr-2 flex items-center p-2 rounded-full bg-[#ffffff61] w-min group border cursor-pointer ocultar border-transparent hover:border-gray-200">
                            <span class="relative icon-holder flex m-0 items-center justify-center  w-8 h-8">
                                <span class="absolute"> <?php include '../php/svg/add-1.svg' ?></span>
                            </span>
                        </span>
                        <span>Total inscritos: <b class="inscritos_counter">0</b></span>
                    </div>
                    <span class="ocultar">Total media: <b class="total_media"></b></span>
                </div>
            </div>
        </div>
        <div class="text-center glass-bg mt-10 animado mostrar" style="--delay: 0.2s">

            <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white mt-2 mb-5">¿Que aprenderás?</h2>
            <div class="flex justify-center">
                <div class="mr-5">

                    <ul class="space-y-4 text-left text-gray-500 dark:text-gray-400" style="--delay: 0.2s">
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Individual configuration</span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>No setup, or hidden fees</span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Team size: <span class="font-semibold text-gray-900 dark:text-white">1 developer</span></span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Premium support: <span class="font-semibold text-gray-900 dark:text-white">6 months</span></span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Free updates: <span class="font-semibold text-gray-900 dark:text-white">6 months</span></span>
                        </li>
                    </ul>

                    <ul class="space-y-4 text-left text-gray-500 dark:text-gray-400" style="--delay: 0.2s">
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Individual configuration</span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>No setup, or hidden fees</span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Team size: <span class="font-semibold text-gray-900 dark:text-white">1 developer</span></span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Premium support: <span class="font-semibold text-gray-900 dark:text-white">6 months</span></span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Free updates: <span class="font-semibold text-gray-900 dark:text-white">6 months</span></span>
                        </li>
                    </ul>

                </div>
                <div>

                    <ul class="space-y-4 text-left text-gray-500 dark:text-gray-400" style="--delay: 0.2s">
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Individual configuration</span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>No setup, or hidden fees</span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Team size: <span class="font-semibold text-gray-900 dark:text-white">1 developer</span></span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Premium support: <span class="font-semibold text-gray-900 dark:text-white">6 months</span></span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Free updates: <span class="font-semibold text-gray-900 dark:text-white">6 months</span></span>
                        </li>
                    </ul>

                    <ul class="space-y-4 text-left text-gray-500 dark:text-gray-400" style="--delay: 0.2s">
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Individual configuration</span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>No setup, or hidden fees</span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Team size: <span class="font-semibold text-gray-900 dark:text-white">1 developer</span></span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Premium support: <span class="font-semibold text-gray-900 dark:text-white">6 months</span></span>
                        </li>
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                            <span>Free updates: <span class="font-semibold text-gray-900 dark:text-white">6 months</span></span>
                        </li>
                    </ul>

                </div>
            </div>

        </div>
        <div class="user-box ocultar logueado ">

            <div class="mt-5 glass-bg w-full px-4 py-3 animado" style="--delay: 0.3s" id="curso-menu-2">
                <div class="tabs horizontal">

                    <input type="radio" name="c1" id="radio-cur-1" name="tabs" />
                    <label tabindex="0" class="tab" for="radio-cur-1" data-activar="#curso_secciones">Secciones <span class="counter-badge secciones_counter " tabindex="-1"></span></label>
                    <input type="radio" name="c1" id="radio-cur-2" name="tabs" />
                    <label tabindex="0" class="tab" for="radio-cur-2" data-activar="#curso_inscritos"><span class="ocultar">Inscritos</span><span class="noocultar">Participantes</span> <span class="counter-badge inscritos_counter " tabindex="-1"></span></label>
                    <?php if ($rol == 1) { ?>
                        <input type="radio" name="c1" id="radio-cur-3" name="tabs" />
                        <label tabindex="0" class="ocultar tab" for="radio-cur-3" data-activar="#curso-multimedia">Multimedia <span class="ocultar counter-badge multimedia_counter " tabindex="-1"></span></label>
                    <?php } ?>
                    <span class="glider horizontal"></span>
                    <span class="glider-hover horizontal"></span>
                </div>
                <div class=" flex p-0 flex-col text-white" id="curso_inscritos" style="display: none;">

                    <div class="w-full flex justify-end mb-2">
                        <button
                            onclick="cargarAlumnosParaInscribir(<?= $_GET['id_curso'] ?>)"
                            abrir-formulario
                            data-formulario="nuevainscripcion"
                            tooltip-content="Inscribir Alumnos"
                            erika-tooltip
                            class="add-btn ocultar">
                            <span class=" flex w-auto flex items-center p-2 rounded-full bg-[#ffffff61] w-min group cursor-pointer ">
                                <?php include '../php/svg/add-1.svg' ?>
                            </span>
                        </button>
                    </div>

                    <div id="inscritos_list_container">
                        <div class="glass-bg no-content seccionesimg">
                            <h1>No hay inscritos</h1>
                            <img src="../assets/media/img/site/inscritos.png" alt="">
                        </div>
                    </div>
                </div>
                <div class=" flex p-0 flex-col text-white active" id="curso_secciones">
                    <div class="flex justify-end mb-2">
                        <span
                            abrir-formulario
                            data-formulario="froala-container"
                            tooltip-content="Nueva Seccion"
                            erika-tooltip
                            class=" flex w-auto flex ocultar items-center p-2 rounded-full bg-[#ffffff61] w-min group cursor-pointer ">
                            <?php include '../php/svg/add-1.svg'; ?>
                        </span>
                    </div>
                    <div id="secciones-list-container">

                        <div class="animado glass-bg no-content seccionesimg" style="--delay:0.2s;">
                            <h1>No hay contenido agregado</h1>
                            <img src="../assets/media/img/site/no-content.png" alt="">
                        </div>
                    </div>

                </div>
                <div class="flex p-0 flex-col text-white " id="curso-multimedia" style="display: none;">
                    <div class="flex justify-end mb-2">
                        <span id="subirMediaCurso" class="ocultar  flex w-auto flex items-center p-2 rounded-full bg-[#ffffff61] w-min group cursor-pointer ">
                            <?php include '../php/svg/add-1.svg'; ?>
                        </span>
                    </div>
                    <div class="glass-bg mb-3 p-0" id="lista-audios">
                            <ul>


                            </ul>
                            <div class="audioplayer relative">
                                <canvas id="canvasAudio"></canvas>
                                <div>
                                    <div class="column add-bottom">
                                        <div id="mainwrap">
                                            <div id="nowPlay">
                                                <span style="display: none;" id="npAction">Paused...</span>
                                                <span style="display: none;" id="npTitle"></span>
                                            </div>
                                            <div id="audiowrap">
                                                <div id="audio0">
                                                    <audio crossorigin="anonymous" id="audio1" preload controls>Your browser does not support HTML5 Audio! 😢</audio>
                                                </div>
                                                <div style="display: none;" id="tracks">
                                                    <a id="btnPrev">&larr;</a><a id="btnNext">&rarr;</a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <h3 class="flex items-center glass-bg p-2 mt-3">
                            <span class="flex w-[2rem]  flex items-center p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group
        border cursor-pointer border-transparent hover:border-gray-200">
                                <?php require '../php/svg/video.svg'; ?>
                            </span>
                            <span class="ml-3 flex items-center">Video <span class="counterplay bg-[#3F51B5] mr-2">3</span></span>
                        </h3>
                        <div class="glass-bg mb-3" id="video-lista">

                            <ul></ul>


                        </div>

                </div>
            </div>
        </div>
    </div>
<?php } ?>