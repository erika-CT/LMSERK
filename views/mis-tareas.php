
<section id="seccion-mis-tareas" data-mixit="mixer_mis-tareas" class="flex flex-col justify-center <?php if($pagina!="/mis-tareas"){echo " hide";}?>">
    <div class="app-container dark:bg-[#000]/40 bg-[#fff]/40 rounded rounded-xl">
        <div class="app-header">
            <div class="app-header-left">
                <span class="app-icon"></span>
                <p class="<?=$ocultar?> app-name  text-white"><?=$lan["curso_administracion"]?></p>
                <div class="search-wrapper rounded-xl bg-[#ffffff61]">
                    <input class="search-input text-white placeholder-[#ffffff61]" type="text" placeholder="Search">    
                    <?php include './php/svg/buscar.svg'?>
                    <span 
                    tooltip-content="<?= $lan["filtrar"] ?>"
                    erika-tooltip><?php include './php/svg/filtrar.svg'?></span>
                </div>
            </div>
            <div class=" <?=$ocultar?> app-header-right">
                <button
                abrir-formulario
                data-formulario="nuevoCurso"
                tooltip-content="<?= $lan["curso_nuevo"] ?>"
                erika-tooltip
                class="add-btn">
                <span class=" flex w-auto flex items-center p-2 rounded-full bg-[#ffffff61] w-min group border cursor-pointer border-transparent hover:border-gray-200">
                    <?php include './php/svg/add-1.svg'?>
                    </span>
                </button>
            </div>
        </div>
        <div class="app-content ">

            <div class="projects-section">
                <div class="projects-section-header text-white">
                    <p class=" text-white"><?=$lan["mis-tareas"]?></p>
                </div>
                <div class="projects-section-line">
                    <div class="projects-status">
                        <div class="item-status">
                            <span class="status-number text-white">0</span>
                            <span class="status-type text-white">Finalizados</span>
                        </div>
                        <div class="item-status">
                            <span class="status-number text-white">0</span>
                            <span class="status-type text-white">Proximos</span>
                        </div>
                        <div class="item-status">
                            <span class="status-number text-white">0</span>
                            <span class="status-type text-white">Impartiendose</span>
                        </div>
                    </div>
                    <div class="view-actions">


                        <button class="view-btn list-view active" title="List View">
                            <span class=" flex w-auto flex items-center p-2 rounded-full bg-[#ffffff61] w-min group border cursor-pointer border-transparent hover:border-gray-200">
                                <svg class="w-5 h-5  transition duration-75 text-[#fff] group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list">
                                    <line x1="8" y1="6" x2="21" y2="6" />
                                    <line x1="8" y1="12" x2="21" y2="12" />
                                    <line x1="8" y1="18" x2="21" y2="18" />
                                    <line x1="3" y1="6" x2="3.01" y2="6" />
                                    <line x1="3" y1="12" x2="3.01" y2="12" />
                                    <line x1="3" y1="18" x2="3.01" y2="18" />
                                </svg>
                            </span>
                        </button>



                        <button class="view-btn grid-view " title="Grid View">
                        <span class=" flex w-auto flex items-center p-2 rounded-full bg-[#ffffff61] w-min group border cursor-pointer border-transparent hover:border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid w-5 h-5  transition duration-75 text-[#fff] group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" >
                                <rect x="3" y="3" width="7" height="7" />
                                <rect x="14" y="3" width="7" height="7" />
                                <rect x="14" y="14" width="7" height="7" />
                                <rect x="3" y="14" width="7" height="7" />
                            </svg>
                        </span>
                        </button>
                    </div>
                </div>
                <div class="project-boxes jsGridView">
                </div>
            </div>

        </div>
    </div>
</section>
