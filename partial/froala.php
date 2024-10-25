<div id="froala-container" data-filepon-circle class="modal-overlay hidden">
    <div class="modal-overlay-back dark:bg-[#000]/40 bg-[#fff]/40"></div>
    <div id="modal_froala-container" class="modal">

        <div id="modal-body">
            <div class="erika-container flex flex-col container items-center justify-evenly">
                <div class="relative w-full campo">
                    <?php include './php/svg/texto.svg' ?>
                    <input
                        type="text"

                        id="seccion_nombre"
                        class="input-erika glassinput block pl-[40px] px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " autocomplete="off" />
                    <label for="seccion_nombre" class="bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 translate-x-[1.5rem]">Nombre de la seccion</label>
                </div>
                <div id="editor">
                    <div id='editorTexto' style="margin-top: 30px;">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>