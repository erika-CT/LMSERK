<div id="nuevoCurso" class="modal-overlay hidden">
    <div class="modal-overlay-back dark:bg-[#000]/40 bg-[#fff]/40"></div>
    <div id="modal_nuevoCurso" class="modal">
        <div id="modal-body">
            <div class="erika-container flex container items-center justify-evenly">
                <img src="../assets/media/img/site/login.png" alt="" class="sideimage hidden lg:block">
                <div class="flex items-center flex-col mb-5">
                    <form erika='curso' class=" mx-auto relative erika-form  glass-bg w-[90vw] md:w-[600px]"
                        id="nuevoCursoFormulario"
                        data-a="curso"
                        erika-formulario
                        erika-formulario-stages>
                        <h1 
                        data-registrar="Nuevo Curso"
                        data-actualizar="Actualizar curso"
                        class="text-4xl text-white"><?= $lan["curso_nuevo"] ?></h1>
                        <div class="mensaje-ajax" style="transform: translateY(-100%);opacity: 0;">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam cupiditate dolore accusantium magnam autem quos saepe, assumenda qui reiciendis est veritatis perferendis aperiam exercitationem animi! Sunt eius illum tempora commodi.
                        </div>
                        <div class="form-stages h-[27.5rem] relative">
                            <div data-anterior class="absolute w-full">
                                <!-- Nombre del curso -->
                                <div class="mb-5">
                                    <div class="relative campo">
                                        <?php include './php/svg/texto.svg' ?>
                                        <input
                                            type="text"
                                            erika-tooltip="data-anterior"
                                            tooltip-trigger="manual"
                                            erika-type="text"
                                            erika-validator="requerido,alfanumerico"
                                            data-mensaje="<?= $lan["validaciones"]["curso_nombre"] ?>"
                                            data-requerido="<?= $lan["validaciones"]["requerido"] ?>"
                                            id="curso_nombre"
                                            class="input-erika glassinput block pl-[40px] px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " autocomplete="off" />
                                        <label for="curso_nombre" class="bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 translate-x-[1.5rem]"><?= $lan["curso_nombre"] ?></label>
                                    </div>
                                </div>
                                <!-- nombre corto del curso -->
                                <div class="mb-5">
                                    <div class="relative campo">
                                        <?php include './php/svg/texto.svg' ?>

                                        <input
                                            maxlength="10"
                                            type="text"
                                            data-mensaje="<?= $lan["validaciones"]["curso_nombre_corto"] ?>"
                                            data-requerido="<?= $lan["validaciones"]["requerido"] ?>"
                                            data-max="<?= $lan["validaciones"]["curso_nombre_corto_max"] ?>"
                                            erika-type="text"
                                            erika-validator="requerido,alfanumerico,max:10"
                                            id="curso_nombre_corto"
                                            erika-tooltip="data-anterior"
                                            tooltip-trigger="manual"
                                            class="input-erika glassinput block pl-[40px] px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " autocomplete="off" />
                                        <label for="curso_nombre_corto" class="bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 translate-x-[1.5rem]"><?= $lan["curso_nombre_corto"] ?></label>
                                    </div>
                                </div>


                                <!--  Fecha de inicio del curso -->
                                <div class="flex mb-5 justify-between">
                                    <div class="relative">
                                        <div class="relative max-w-sm">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 ">
                                                <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                </svg>
                                            </div>
                                            <input id="curso_fecha_ini"
                                                readonly
                                                data-clear-btn="Reset Date"
                                                type="text"
                                                erika-type="fecha"
                                                erika-validator="requerido,fecha,mayor_hoy"
                                                erika-fecha-menor="curso_fecha_finalizacion"
                                                data-mensaje="<?= $lan["validaciones"]["curso_fecha_inicio"] ?>"
                                                data-requerido="<?= $lan["validaciones"]["requerido"] ?>"
                                                erika-tooltip="data-anterior"
                                                tooltip-trigger="manual"
                                                class="input-erika glassinput bg-erika border border-gray-300  text-sm rounded-lg block w-full ps-10 p-2.5 placeholder-white text-white" placeholder="<?= $lan["curso_fecha_inicio"] ?>">
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <div class="relative max-w-sm">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 ">
                                                <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                </svg>
                                            </div>
                                            <input id="curso_ini_erika" type="date" hidden>
                                            <input
                                                erika-tooltip="data-anterior"
                                                tooltip-trigger="manual"
                                                readonly
                                                id="curso_fecha_finalizacion"
                                                erika-type="fecha"
                                                erika-validator="requerido,fecha,mayor_hoy"
                                                erika-fecha-mayor="curso_fecha_ini"
                                                data-requerido="<?= $lan["validaciones"]["requerido"] ?>"
                                                data-mensaje="<?= $lan['validaciones']["curso_fecha_fin"] ?>"
                                                type="text" class="input-erika glassinput bg-erika border border-gray-300  text-sm rounded-lg block w-full ps-10 p-2.5 placeholder-white text-white" placeholder="<?= $lan["curso_fecha_fin"] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col md:flex-row mb-5  justify-start md:justify-between items-start md:items-center overflow-hidden">
                                    <div class="relative flex items-center">
                                        <div class="relative max-w-sm">
                                            <div class="flex items-center">
                                                <?php include './php/svg/money.svg' ?>
                                                </svg>
                                                <label class="ml-2 inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" value="" id="curso_de_pago"
                                                        erika-type="checkbook"
                                                        erika-validator=""
                                                        erika-toggler="#curso_precio_container"
                                                        class="sr-only peer input-erika">
                                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:w-5 after:h-5 after:transition-all  peer-checked:bg-[#8754c3]/80"></div>
                                                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"><?= $lan['curso_de_pago'] ?></span>
                                                </label>
                                            </div>


                                        </div>
                                    </div>
                                    <div id="curso_precio_container" class="relative campo mt-3 md:mt-0">
                                        <?php include './php/svg/money.svg' ?>
                                        <input
                                            type="text"
                                            erika-validar-check="#curso_de_pago"
                                            data-requerido="<?= $lan["validaciones"]["curso_precio"] ?>"
                                            erika-type="text"
                                            erika-validator="requerido,alfanumerico,max:10"
                                            erika-no-espacios
                                            id="curso_precio"
                                            erika-tooltip="data-anterior"
                                            tooltip-trigger="manual"
                                            class="input-erika glassinput block pl-[40px] px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " autocomplete="off" />
                                        <label for="curso_precio" class="bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 translate-x-[1.5rem]"><?= $lan["curso_precio"] ?></label>
                                    </div>
                                </div>
                                <div class="flex mb-5 justify-between overflow-hidden">
                                    <div class="relative flex items-center">
                                        <div class="relative max-w-sm">
                                            <div class="flex items-center">
                                                <?php include './php/svg/visible.svg' ?>
                                                </svg>
                                                <label class="ml-2 inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" value="" id="curso_visible"
                                                        erika-type="checkbook"
                                                        erika-validator=""
                                                        class="sr-only peer input-erika">
                                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:w-5 after:h-5 after:transition-all  peer-checked:bg-[#8754c3]/80"></div>
                                                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"><?= $lan['curso_visible'] ?></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-siguiente class="absolute w-full mt-10" style="opacity: 0; transform: translateX(100%); pointer-events: none; display: none;">
                                <div class="mb-5 mt-3">
                                    <div class="relative flex items-center btnFloat">
                                        <?php include './php/svg/categoria.svg' ?>
                                        <select
                                            erika-select
                                            erika-type="select"
                                            erika-validator="requerido"
                                            data-requerido="Seleccione una categoria"
                                            erika-tooltip
                                            id="curso_categoria"
                                            data-select_placeholder="<?= $lan["curso_categoria"] ?>"
                                            data-select_get="categorias_select"
                                            class="glassinput input-erika ml-2" name="categoria" style="width: 90%"
                                            initial-data='<?= json_encode(array_map(function ($cate) {
                                                                $img = $cate['foto'];

                                                                if ($img == "" || $img == null)
                                                                    $img = './assets/media/img/site/categoria_placeholder-thumb.png';
                                                                return [
                                                                    "class"=>"img-rounded",
                                                                    "id" => $cate['id_categoria'],
                                                                    "text" => addslashes($cate['nombre']),
                                                                    "img" => "../." . addslashes($img)
                                                                ];
                                                            }, $db->getAll('categoria_activas'))); ?>'>

                                        </select>
                                        <div class="icono-btn inline"><a
                                                erika-tooltip="data-siguente"
                                                erika-form-open="nuevacategoria"
                                                erika-form-from="nuevoCurso"
                                                erika-nuevo-valor-set="curso_categoria"
                                                tooltip-content="<?= $lan["categoria_nueva"] ?>"
                                                href="#"><?php include './php/svg/add.svg' ?></a></div>
                                    </div>
                                </div>

                                <div class="mb-5 mt-3">
                                    <div class="relative flex items-center btnFloat">
                                        <?php include './php/svg/instructores.svg' ?>
                                        <select
                                            erika-select
                                            erika-type="select"
                                            erika-validator="requerido"
                                            erika-tooltip="data-siguente"
                                            tooltip-trigger="manual"
                                            data-requerido="Seleccione un instructor"
                                            id="curso_instructor"
                                            data-select_placeholder="<?= $lan["curso_instructor"] ?>"
                                            data-select_get="instructores_select"
                                            class="glassinput input-erika ml-2" name="instructor" style="width: 90%"
                                            initial-data='<?= json_encode(array_map(function ($cate) {
                                                                $img = $cate['foto'];
                                                                if ($img == "" || $img == null)
                                                                    $img = './assets/media/img/site/instructor-thumb.jpg';
                                                                return [
                                                                    "class"=>"img-circle",
                                                                    "id" => $cate['id_usuario'],
                                                                    "text" => addslashes($cate['nombres']),
                                                                    "img" => "../." . addslashes($img)
                                                                ];
                                                            }, $db->getAll('instructores'))); ?>'>
                                            >
                                        </select>
                                        <div class="icono-btn inline"><a
                                                erika-form-open="nuevoInstructor"
                                                erika-form-from="nuevoCurso"
                                                erika-nuevo-valor-set="curso_instructor"
                                                tooltip-content="<?= $lan["instructor_nuevo"] ?>"
                                                erika-tooltip="data-siguente"
                                                href="#"><?php include './php/svg/add.svg' ?></a></div>
                                    </div>
                                </div>
                                <div
                                    class="input-erika"
                                    erika-tooltip="data-siguente"
                                    tooltip-trigger="manual"
                                    data-mensaje="<?= $lan["validaciones"]["archivos_permitidos_1"] . $lan["validaciones"]["imagenes_validas"] .  $lan["validaciones"]["archivos_permitidos_2"] ?>"
                                    erika-validator="archivo"
                                    erika-files="<?= $lan["validaciones"]["imagenes_validas"] ?>"
                                    erika-target-validate="#curso_imagen"></div>
                                <span class="campo">
                                    <input id="curso_imagen" type="file" class="filepond" accept="image/*">
                                </span>
                            </div>
                        </div>



                        <div class="erika_btns relative  mt-3">
                            <div class="otro-btn hidden ">

                                <button
                                    tabindex="1"
                                    erika-otro-registro-form="nuevoInstructorFormulario"
                                    style="transform: translateX(-100%);opacity: 0;"
                                    type="button" class="btn-otro w-full  whitespace-nowrap text-white h-9 bg-[#2196f3]/50 hover:bg-[#2196f3]/90 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2 text-center"><?= $lan["curso_registrar_otro"] ?></button>
                                <button
                                    style="transform: translateX(-100%);opacity: 0;"
                                    type="submit" class="btn-reintentar w-full h-9 whitespace-nowrap text-white bg-[#f3c121]/50 hover:bg-[#f3c121]/90 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2 text-center"><?= $lan["reintentar"] ?></button>


                            </div>

                            <div class="flex absolute translate-y-[-2rem] w-full justify-center">
                                <span class=" ml-3 loading">
                                    <div class="backgroundLoader">
                                        <div class="loader loader-left"></div>
                                        <div class="loader loader-right"></div>
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
                                            <defs>
                                                <filter id="gooLoader">
                                                    <fegaussianblur in="SourceGraphic" stddeviation="15" result="blur" color-interpolation-filters="sRGB"></fegaussianblur>
                                                    <fecolormatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 26 -7" result="gooLoader" color-interpolation-filters="sRGB"></fecolormatrix>
                                                    <feblend in="SourceGraphic" in2="gooLoader" color-interpolation-filters="sRGB"></feblend>
                                                </filter>
                                            </defs>
                                        </svg>
                                    </div>

                                </span>
                            </div>
                            <div class="action-btn flex absolute w-full justify-center bottom-0">
                                <button
                                    tabindex="1"
                                    data-stagesType="atras"
                                    erika-stages="nuevoCurso"
                                    type="button" class="h-9 whitespace-nowrap desactivado btn-atras w-full text-white bg-[#2196f3]/50 hover:bg-[#2196f3]/90 font-medium rounded-lg text-sm px-2 py-2 text-center"><?= $lan["atras"] ?></button>
                                <button
                                    tabindex="1"
                                    erika-stages="nuevoCurso"
                                    data-stagesType="siguiente"
                                    data-btnotro="<?= $lan["curso_registrar_otro"] ?>"
                                    data-btnRegistrar="<?= $lan["curso_registrar"] ?>"
                                    data-btnSiguiente="<?= $lan["siguiente"] ?>"
                                    type="button" class="ml-3 whitespace-nowrap h-9 btn-siguiente w-full text-white bg-[#2196f3]/50 hover:bg-[#2196f3]/90 font-medium rounded-lg text-sm px-2 py-2 text-center"><?= $lan["siguiente"] ?></button>
                                <a tabindex="1"
                                    erika-btn-override
                                    erika-btn-override-target="erika-btn-text-override-cat-reg"
                                    modal-cerrar="nuevoCurso" href="#" class="h-9  whitespace-nowrap btn-dont flex justify-center items-center ml-3 text-white hover:text-white bg-[#8754c3]/80 hover:bg-[#8754c3] focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm w-full px-2 text-center"><?= $lan["cerrar"] ?></a>
                            </div>
                        </div>




                    </form>

                </div>
            </div>
        </div>

    </div>
</div>