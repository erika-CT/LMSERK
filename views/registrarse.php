
<br>
<br>
<div class="erika-container flex container items-center justify-evenly">
    <img src="./img/registrarse.png" alt="" class="sideimage">
    <div class="flex items-center flex-col mb-5">
        <form class=" mx-auto erika-form glassform">
            <h1 class="form-title text-pink mb-3">Registrarse</h1>

            <div class="mb-5">
                <div class="relative campo">
                    <?php include './php/svg/user-data.svg' ?>

                    <div erika-tooltip role="tooltip" class="translate-y-[3.5rem] absolute z-[1000] invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-[#000000]/50 rounded-lg shadow-sm opacity-0 tooltip " data-mensaje="<?= $lan["validaciones"]["usuario_nombres"]?>" data-requerido="<?= $lan["validaciones"]["requerido"]?>">
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <input type="text" id="usuario_nombres" data-tooltip-placement="bottom" name='usuario_nombres' class="glassinput block pl-[40px] px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" autocomplete="off" placeholder=" " />
                    <label for="usuario_nombres" class="bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 translate-x-[15px]"><?= $lan["usuario_nombres"] ?></label>
                </div>
            </div>
            <div class="mb-5">
                <div class="relative campo">
                    <?php include './php/svg/user-data.svg' ?>
                    <div erika-tooltip role="tooltip" class="translate-y-[3.5rem] absolute z-[1000] invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-[#000000]/50 rounded-lg shadow-sm opacity-0 tooltip " data-mensaje="<?= $lan["validaciones"]["usuario_apellidos"]?>"  data-requerido="<?= $lan["validaciones"]["requerido"]?>">
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <input type="text" id="usuario_apellidos" class="glassinput block pl-[40px] px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " autocomplete="off" />
                    <label for="usuario_apellidos" class="bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 translate-x-[15px]"><?= $lan["usuario_apellidos"] ?></label>
                </div>
            </div>

            <div class="mb-5">
                <div class="relative campo">
                    <?php include_once './php/svg/email.svg' ?>
                    <div erika-tooltip role="tooltip" class="translate-y-[3.5rem] absolute z-[1000] invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-[#000000]/50 rounded-lg shadow-sm opacity-0 tooltip " data-mensaje="<?= $lan["validaciones"]["usuario_correo"]?>"  data-requerido="<?= $lan["validaciones"]["requerido"]?>">
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <input type="text" id="usuario_correo" class="glassinput block pl-[40px] px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" "  autocomplete="off"/>
                    <label for="usuario_correo" class="bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 translate-x-[15px]"><?= $lan["usuario_correo"] ?></label>
                </div>
            </div>



            <div class="mb-5">
                <div class="relative campo password">
                    <?php include_once './php/svg/password.svg' ?>
                    <div erika-tooltip role="tooltip" class="translate-y-[45px  ] absolute z-[1000] invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-[#000000]/50 rounded-lg shadow-sm opacity-0 tooltip " data-mensaje="<?= $lan["validaciones"]["usuario_pass"] ?>"  data-requerido="<?= $lan["validaciones"]["requerido"]?>">
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <span class="password-icon"> <?php include_once './php/svg/eye-1.svg' ?>
                    <?php include_once './php/svg/eye-2.svg' ?></span>
                    <input type="password" id="usuario_pass" class="pl-[40px] glassinput block px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " autocomplete="off" />
                    <label for="usuario_pass" class="translate-x-[15px] bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"><?= $lan["usuario_pass"] ?></label>
                </div>
            </div>


            
            <div class="erika_btns flex mt-3 justify-center">
                <button type="submit" class="w-full text-white bg-[#ec4899]/50 hover:bg-[#ec4899]/90 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2 text-center"><?= $lan["registrarse"] ?></button>

                <a href="#" class="flex justify-center items-center ml-3 text-white hover:text-white bg-[#8754c3]/80 hover:bg-[#8754c3] focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm w-full px-5 text-center"><?= $lan["cancelar"] ?></a>
            </div>

            <div class="w-full mt-5">
                <label class="text-white mb-3" for=""><?= $lan["registro_externo"] ?></label>


                <div class="mt-2">
                    <button type="button" class="text-white bg-[#000000]/50 hover:bg-[#000000]/50 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/55 font-medium rounded-lg text-sm px-1.5  py-1.5 text-center inline-flex items-center me-2 mb-2 svg-white" title="">
                        <?php include_once './php/svg/google.svg' ?>
                    </button>
                    <button type="button" class="text-white bg-[#000000]/50 hover:bg-[#000000]/50 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/55 font-medium rounded-lg text-sm px-1.5  py-1.5 text-center inline-flex items-center me-2 mb-2 svg-white" title="">
                        <?php include_once './php/svg/facebook.svg' ?>
                    </button>
                    <button type="button" class="text-white bg-[#000000]/50 hover:bg-[#000000]/50 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/55 font-medium rounded-lg text-sm px-1.5  py-1.5 text-center inline-flex items-center me-2 mb-2 svg-white" title="">
                        <?php include_once './php/svg/twitter.svg' ?>
                    </button>
                </div>


            </div>
        </form>

    </div>
</div>