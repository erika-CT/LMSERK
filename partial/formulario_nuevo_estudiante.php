<div id="nuevoEstudiante" data-filepon-circle class="modal-overlay hidden">
    <div class="modal-overlay-back dark:bg-[#000]/40 bg-[#fff]/40"></div>
    <div id="modal_nuevoEstudiante" class="modal">
        <div id="modal-body">
            <div class="erika-container flex container items-center justify-evenly">
                <img src="../assets/media/img/site/estudiante-small.png" data-src="../assets/media/img/site/estudiante.png" alt="" class="sideimage hidden lg:block">
                <div class="flex items-center flex-col mb-5">
                    <form class="relative mx-auto erika-form glass-bg w-[90vw] md:w-[600px]"
                        id="nuevoEstudianteFormulario"
                        data-a="estudiante"
                        erika-formulario
                        erika-formulario-stages>

                        <h1
                        data-registrar="Nuevo Estudiante"
                        data-actualizar="Actualizar Estudiante"
                        class="text-4xl text-white">Nuevo Estudiante</h1>
                        <div class="mensaje-ajax" style="transform: translateY(-100%);opacity: 0;display:none;">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam cupiditate dolore accusantium magnam autem quos saepe, assumenda qui reiciendis est veritatis perferendis aperiam exercitationem animi! Sunt eius illum tempora commodi.
                        </div>
                        <div class="form-stages h-[21.5rem] relative">
                            <div data-anterior class="absolute w-full">
                                <!-- nombres del intructor -->
                                <div class="mb-5 mt-3">
                                    <div class="relative campo">
                                        <?php include './php/svg/texto.svg' ?>
                                        <input
                                            tabindex="1"
                                            type="text"
                                            erika-tooltip="data-anterior"
                                            erika-type="text"
                                            tooltip-trigger="manual"
                                            erika-validator="requerido,alfa"
                                            data-mensaje="<?= $lan["validaciones"]["estudiante_nombre"] ?>"
                                            data-requerido="<?= $lan["validaciones"]["requerido"] ?>"
                                            id="estudiante_nombre"
                                            class="input-erika glassinput block pl-[40px] px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " autocomplete="off" />
                                        <label for="estudiante_nombre" class="bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 translate-x-[1.5rem]"><?= $lan["estudiante_nombre"] ?></label>
                                    </div>
                                </div>

                                <!-- apellidos del estudiante -->
                                <div class="mb-5 mt-3">
                                    <div class="relative campo">
                                        <?php include './php/svg/texto.svg' ?>
                                        <input
                                            tabindex="1"
                                            type="text"
                                            erika-tooltip="data-anterior"
                                            erika-type="text"
                                            tooltip-trigger="manual"
                                            erika-validator="requerido,alfa"
                                            data-mensaje="<?= $lan["validaciones"]["estudiante_apellido"] ?>"
                                            data-requerido="<?= $lan["validaciones"]["requerido"] ?>"
                                            id="estudiante_apellido"
                                            class="input-erika glassinput block pl-[40px] px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " autocomplete="off" />
                                        <label for="estudiante_apellido" class="bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 translate-x-[1.5rem]"><?= $lan["estudiante_apellido"] ?></label>
                                    </div>
                                </div>

                                <!-- correo del estudiante -->
                                <div class="mb-5">
                                    <div class="relative campo">
                                        <?php include './php/svg/email.svg' ?>
                                        <input
                                            tabindex="1"
                                            erika-tooltip="data-anterior"
                                            erika-type="text"
                                            tooltip-trigger="manual"
                                            erika-validator="requerido,correo"
                                            data-mensaje="<?= $lan["validaciones"]["estudiante_correo"] ?>"
                                            data-requerido="<?= $lan["validaciones"]["requerido"] ?>"
                                            id="estudiante_correo"
                                            class="input-erika glassinput block pl-[40px] px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " autocomplete="off" />
                                        <label for="estudiante_correo" class="bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 translate-x-[1.5rem]"><?= $lan["estudiante_correo"] ?></label>
                                    </div>
                                </div>


                                <!-- Contraseña del estudiante -->

                                <div class="mb-5">
                                    <div class="relative campo password">
                                        <?php include './php/svg/password.svg' ?>

                                        <span class="password-icon"> <?php include_once './php/svg/eye-1.svg' ?>
                                            <?php include './php/svg/eye-2.svg' ?></span>

                                        <input
                                            tabindex="1"
                                            erika-type="text"
                                            erika-tooltip="data-anterior"
                                            tooltip-trigger="manual"
                                            erika-validator="requerido,contraseña"
                                            data-mensaje="<?= $lan["validaciones"]["estudiante_pass"] ?>"
                                            data-requerido="<?= $lan["validaciones"]["requerido"] ?>"
                                            id="estudiante_pass"
                                            type="password"
                                            class="input-erika pl-[40px] glassinput block px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " autocomplete="off" />
                                        <label for="estudiante_pass" class="bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 translate-x-[1.5rem]"><?= $lan["estudiante_pass"] ?></label>
                                    </div>
                                </div>



                            </div>

                            <div class="absolute w-full mt-4"
                                data-siguiente
                                style="opacity: 0; transform: translateX(100%); pointer-events: none; display: none;">
                                <div class="mb-5 mt-3 md:flex">

                                    <div class="relative flex  w-full items-center btnFloat md:mr-2">
                                        <?php include './php/svg/sexo.svg' ?>
                                        <select
                                            erika-select
                                            erika-type="select"
                                            erika-no-ajax
                                            erika-validator="requerido"
                                            erika-tooltip
                                            tooltip-trigger="manual"
                                            data-requerido="Especifique el sexo del estudiante"
                                            id="estudiante_sexo"
                                            data-select_placeholder="Especifique el sexo del estudiante"
                                            class="glassinput input-erika ml-2" name="estudiante_sexo" style="width: 90%">
                                            <option value=""></option>
                                            <option value="F">Femenino</option>
                                            <option value="M">Masculino</option>
                                        </select>

                                    </div>

                                    <div class="relative mt-2  w-full">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 ">
                                            <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input id="estudiante_fecha_nacimiento"
                                            readonly
                                            data-clear-btn="Reset Date"
                                            type="text"
                                            erika-type="fecha"
                                            erika-validator="requerido,fecha"
                                            data-mensaje="La fecha de nacimento es requerida"
                                            data-requerido="La fecha de nacimento es requerida"
                                            erika-tooltip
                                            tooltip-trigger="manual"
                                            class="datepicker_usuarios input-erika glassinput bg-erika border border-gray-300  text-sm rounded-lg block w-full ps-10 p-2.5 placeholder-white text-white"
                                            placeholder="Fecha de nacimiento">

                                    </div>
                                </div>
                                <div
                                    class="input-erika"
                                    erika-tooltip="data-siguiente"
                                    tooltip-trigger="manual"
                                    data-mensaje="<?= $lan["validaciones"]["archivos_permitidos_1"] . $lan["validaciones"]["imagenes_validas"] .  $lan["validaciones"]["archivos_permitidos_2"] ?>"
                                    erika-validator="archivo"
                                    erika-files="<?= $lan["validaciones"]["imagenes_validas"] ?>"
                                    erika-target-validate="#estudiante_imagen"></div>
                                <span class="campo flex justify-center mt-[1rem]">
                                    <input id="estudiante_imagen" type="file" class="filepond circle-erk" accept="image/*">
                                </span>
                            </div>
                        </div>






                        <div class="erika_btns relative h-6 mt-3 ">
                            <div class="otro-btn hidden ">
                                <button
                                    tabindex="1"
                                    erika-otro-registro-form="nuevoEstudianteFormulario"
                                    style="transform: translateX(-100%);opacity: 0;"
                                    type="button" class="h-9 whitespace-nowrap btn-otro w-full text-white bg-[#2196f3]/50 hover:bg-[#2196f3]/90 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2 text-center"><?= $lan["estudiante_registrar_otra"] ?></button>
                                <button
                                    style="transform: translateX(-100%);opacity: 0;"
                                    type="submit" class="h-9 whitespace-nowrap btn-reintentar w-full text-white bg-[#f3c121]/50 hover:bg-[#f3c121]/90 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2 text-center"><?= $lan["reintentar"] ?></button>

                            </div>

                            <div class="flex absolute w-full justify-center">
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


                            <div class="erika_btns relative  mt-3">
                                <div class="otro-btn hidden ">

                                    <button
                                        tabindex="1"
                                        erika-otro-registro-form="nuevoEstudianteFormulario"
                                        style="transform: translateX(-100%);opacity: 0;"
                                        type="button" class="h-9 whitespace-nowrap btn-otro w-full text-white bg-[#2196f3]/50 hover:bg-[#2196f3]/90 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2 text-center"><?= $lan["curso_registrar_otro"] ?></button>
                                    <button
                                        style="transform: translateX(-100%);opacity: 0;"
                                        type="submit" class="h-9 whitespace-nowrap btn-reintentar w-full text-white bg-[#f3c121]/50 hover:bg-[#f3c121]/90 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2 text-center"><?= $lan["reintentar"] ?></button>


                                </div>

                                <div class="flex absolute w-full justify-center">
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
                                        erika-stages="nuevoEstudiante"
                                        type="button" class="h-9 whitespace-nowrap desactivado btn-atras w-full text-white bg-[#2196f3]/50 hover:bg-[#2196f3]/90 font-medium rounded-lg text-sm px-2 py-2 text-center"><?= $lan["atras"] ?></button>
                                    <button
                                        tabindex="1"
                                        erika-stages="nuevoEstudiante"
                                        data-stagesType="siguiente"
                                        data-btnotro="<?= $lan["estudiante_registrar_otra"] ?>"
                                        data-btnRegistrar="<?= $lan["estudiante_registrar"] ?>"
                                        data-btnSiguiente="<?= $lan["siguiente"] ?>"
                                        type="button" class="h-9 whitespace-nowrap ml-3 btn-siguiente w-full text-white bg-[#2196f3]/50 hover:bg-[#2196f3]/90 font-medium rounded-lg text-sm px-2 py-2 text-center"><?= $lan["siguiente"] ?></button>
                                    <a tabindex="1"
                                        erika-btn-override
                                        erika-btn-override-target="erika-btn-text-override-cat-reg"
                                        modal-cerrar="nuevoEstudiante" href="#" class="h-9 whitespace-nowrap btn-dont flex justify-center items-center ml-3 text-white hover:text-white bg-[#8754c3]/80 hover:bg-[#8754c3] focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm w-full px-2 text-center"><?= $lan["cerrar"] ?></a>
                                </div>
                            </div>

                        </div>




                    </form>

                </div>
            </div>
        </div>

    </div>
</div>