<div id="modal-body">
            <div class="erika-container flex w-full items-center justify-center">
                <div class="flex items-center flex-col mb-5">
                    <form class="relative mx-auto erika-form glass-bg w-[90vw] "
                        id="nuevoinscripcionFormulario"
                        data-a="inscripcion"
                        erika-formulario>
                        <div class="flex items-center flex-col mb-5">
                            <h1 class="text-lg md:text-3xl">Inscribir estudiantes al curso</h1>
                            <div class="flex w-full justify-center">
                                <div class="mb-5 w-full mt-3">
                                    <div class="relative flex items-center btnFloat">
                                        <?php include '../php/svg/estudiantes.svg' ?>
                                        <select
                                            data-select_get_x="<?= $_GET['id_curso']?>"
                                            data-select_get_multiple="true"
                                            erika-select
                                            erika-type="select"
                                            erika-validator="requerido"
                                            erika-tooltip
                                            tooltip-trigger="manual"
                                            data-requerido="Seleccione almenos un estudiante"
                                            id="estudiantes_inscripcion"
                                            data-select_placeholder="Seleccione estudiantes"
                                            data-select_get="estudiantes_select"
                                            class="estudiantes_inscripcion-sel glassinput input-erika ml-2" name="estudiantes" style="width: 90%"
                                            initial-data='<?= json_encode(array_map(function ($cate) {
                                                                $img = $cate['foto'];

                                                                if ($img == "" || $img == null)
                                                                    $img = './assets/media/img/site/categoria_placeholder-thumb.png';
                                                                return [
                                                                    "class"=>"img-circle",
                                                                    "id" => $cate['id_usuario'],
                                                                    "text" => addslashes($cate['nombres']),
                                                                    "img" => "../." . addslashes($img)
                                                                ];
                                                            }, $db->exec('select * from obtener_estudiantes_sin_inscribir(' . $_GET['id_curso'] . ')'))); ?>'>
                                        </select>
                                        <div class="icono-btn inline"><a
                                                erika-form-open="nuevoEstudiante"
                                                erika-form-from="nuevainscripcion"
                                                erika-nuevo-valor-set="curso_instructor"
                                                tooltip-content="Nuevo estudiante"
                                                erika-tooltip="data-siguente"
                                                href="#"><?php include '../php/svg/add.svg' ?></a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="w-full mb-5 mt-3 flex">
                                    <button
                                        type="submit" class="whitespace-nowrap h-9 btn-otro w-full text-white bg-[#ec4899]/50 hover:bg-[#ec4899]/90 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2 text-center mr-5">Inscribir Estudiantes</button>
                                    <button
                                        modal-cerrar="nuevainscripcion"
                                        type="button" class="h-9 btn-otro w-full text-white bg-[#2196f3]/50 hover:bg-[#2196f3]/90 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2 text-center">Cancelar</button>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>


            </div>
        </div>