<div id="nuevacategoria" class="modal-overlay hidden">
    <div class="modal-overlay-back dark:bg-[#000]/40 bg-[#fff]/40"></div>
    <div id="modal_nuevacategoria" class="modal">
        <div id="modal-body">
            <div class="erika-container flex container items-center justify-evenly">
                <img src="../assets/media/img/site/categoria-small.png" data-src="../assets/media/img/site/categoria.png" alt="" class="lozod lazy-img sideimage hidden lg:block">
                <div class="flex items-center flex-col mb-5">
                    <form class="relative mx-auto erika-form  glass-bg w-[90vw] md:w-[600px]"
                        id="nuevacategoriaFormulario"
                        data-a="categoria"
                        erika-formulario>
                        <h1 class="text-4xl form-title text-white" 
                        data-registrar="<?= $lan["categoria_nueva"] ?>"
                        data-actualizar="Actualizar Categoría"><?= $lan["categoria_nueva"] ?></h1>
                        <div class="mensaje-ajax" style="transform: translateY(-100%);opacity: 0;display:none;">
                           Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam cupiditate dolore accusantium magnam autem quos saepe, assumenda qui reiciendis est veritatis perferendis aperiam exercitationem animi! Sunt eius illum tempora commodi.
                        </div>
                        <div class="mb-5 mt-3">
                            <div class="relative campo">
                                <?php include './php/svg/texto.svg' ?>
                                <input
                                    tabindex="1"
                                    type="text"
                                    erika-tooltip
                                    erika-type="text"
                                    tooltip-trigger="manual"
                                    erika-validator="requerido,alfanumerico"
                                    data-mensaje="<?= $lan["validaciones"]["categoria_nombre"] ?>"
                                    data-requerido="<?= $lan["validaciones"]["requerido"] ?>"
                                    id="categoria_nombre"
                                    class="input-erika glassinput block pl-[40px] px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " autocomplete="off" />
                                <label for="categoria_nombre" class="bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 translate-x-[1.5rem]"><?= $lan["categoria_nombre"] ?></label>
                            </div>
                        </div>
                        <div
                            class="input-erika"
                            erika-tooltip
                            tooltip-trigger="manual"
                            data-mensaje="<?= $lan["validaciones"]["archivos_permitidos_1"] . $lan["validaciones"]["imagenes_validas"] .  $lan["validaciones"]["archivos_permitidos_2"] ?>"
                            erika-validator="archivo"
                            erika-files="<?= $lan["validaciones"]["imagenes_validas"] ?>"
                            erika-target-validate="#categoria_imagen"></div>
                        <span class="campo">
                            <input  id="categoria_imagen" type="file" class="filepond" accept="image/*">
                        </span>

                        <div class="erika_btns relative h-6 mt-3">
                            <div class="otro-btn hidden ">
                                <button
                                tabindex="1"
                                    erika-otro-registro-form="nuevacategoriaFormulario"
                                    style="transform: translateX(-100%);opacity: 0;"
                                    type="button" class="whitespace-nowrap h-9 btn-otro w-full text-white bg-[#2196f3]/50 hover:bg-[#2196f3]/90 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2 text-center"><?= $lan["categoria_registrar_otra"] ?></button>
                                <button
                                    style="transform: translateX(-100%);opacity: 0;"
                                    type="submit" class="whitespace-nowrap h-9 btn-reintentar w-full text-white bg-[#f3c121]/50 hover:bg-[#f3c121]/90 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2 text-center"><?= $lan["reintentar"] ?></button>

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
                            <div class="action-btn flex absolute w-full justify-center">
                                <button

                                    tabindex="1"
                                    type="submit" class="whitespace-nowrap h-9 btn-ok w-full text-white bg-[#ec4899]/50 hover:bg-[#ec4899]/90 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2 text-center"><?= $lan["categoria_registrar"] ?></button>
                                    <a
                                    tabindex="1"
                                    erika-btn-override
                                    erika-btn-override-target="erika-btn-text-override-cat-reg"

                                    modal-cerrar="nuevacategoria" href="#" class="whitespace-nowrap h-9 btn-dont flex justify-center items-center ml-3 text-white hover:text-white bg-[#8754c3]/80 hover:bg-[#8754c3] focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm w-full px-5 text-center"><?= $lan["cerrar"] ?></a>
                            </div>
                        </div>




                    </form>

                </div>
            </div>
        </div>

    </div>
</div>