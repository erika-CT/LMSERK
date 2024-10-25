<br>
<br>
<br>
<br>
<div class="animado erika-container flex container items-center justify-evenly">
<img src="../assets/media/img/site/login-small.png" 
data-src="../assets/media/img/site/login.png" alt="" class="sideimage hidden lg:block animado" style="--delay:1.7s;">
    <div class="flex items-center flex-col mb-5 animado" style="--delay:0.7s;" >
        <form class="animado relative mx-auto erika-form glass-bg w-[90vw] md:w-[600px]" style="--delay:1.7s;"  id="login_form">
            <h1 class="text-4xl text-white"><?= $lan['iniciar_sesion'] ?></h1>

            <div class="mb-5">
                <div class="relative campo">
                    <?php include_once '../php/svg/email.svg' ?>
                    <div  class="translate-y-[3.5rem] absolute z-[1000] invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-[#000000]/50 rounded-lg shadow-sm opacity-0 tooltip " data-mensaje="<?= $lan["validaciones"]["correo"] ?>" data-requerido="<?= $lan["validaciones"]["requerido"] ?>">
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <input require type="text" id="correo" name="correo" class="glassinput block pl-[40px] px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " autocomplete="off" />
                    <label for="correo" class="bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 translate-x-[15px]"><?= $lan["correo"] ?></label>
                </div>
            </div>




            <div class="mb-5">
                <div class="relative campo password">
                    <?php include_once '../php/svg/password.svg' ?>
                    <div  class="translate-y-[45px  ] absolute z-[1000] invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-[#000000]/50 rounded-lg shadow-sm opacity-0 tooltip " data-mensaje="<?= $lan["validaciones"]["pass"] ?>" data-requerido="<?= $lan["validaciones"]["requerido"] ?>">
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <span class="password-icon"> <?php include_once '../php/svg/eye-1.svg' ?>
                        <?php include_once '../php/svg/eye-2.svg' ?></span>
                    <input require type="password" id="usuario_pass" name="contra" class="pl-[40px] glassinput block px-2.5 pb-2.5 pt-4 w-full bg-transparent rounded-lg border-1 border-gray-300 appearance-none text-white  focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " autocomplete="off" />
                    <label for="usuario_pass" class="translate-x-[15px] bg-transparent absolute text-md text-white  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-white 0 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-85 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"><?= $lan["pass"] ?></label>
                </div>
            </div>



            <div class="erika_btns flex mt-3 justify-center">
                <button
                
                    erika-tooltip
                    tooltip-content="Correo o contraseña invalida"
                    tooltip-trigger="manual"
                    type="submit"
                    class="w-full text-white bg-[#ec4899]/50 hover:bg-[#ec4899]/90 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2 text-center"><?= $lan["iniciar_sesion"] ?></button>

                <a href="#" class="flex justify-center items-center ml-3 text-white hover:text-white bg-[#8754c3]/80 hover:bg-[#8754c3] focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm w-full px-5 text-center"><?= $lan["cancelar"] ?></a>
            </div>





            <div class="w-full mt-5">
                <label class="text-white mb-3" for=""><?= $lan["inicio_externo"] ?></label>


                <div class="mt-2">
                    <a href="../php/google-oauth.php" class="text-white bg-[#000000]/20 hover:bg-[#000000]/50 focus:ring-4 focus:outline-none  font-medium rounded-lg text-sm px-1.5  py-1.5 text-center inline-flex items-center focus:ring-[#3b5998]/55 me-2 mb-2 svg-white" title="">
                        <?php include_once '../php/svg/google.svg' ?>
                    </a>
                    <a href="#" class="text-white bg-[#000000]/20 hover:bg-[#000000]/50 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-1.5  py-1.5 text-center inline-flex items-center focus:ring-[#3b5998]/55 me-2 mb-2 svg-white" title="">
                        <?php include_once '../php/svg/facebook.svg' ?>
                    </a>
                    <a href="#" class="text-white bg-[#000000]/20 hover:bg-[#000000]/50 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-1.5  py-1.5 text-center inline-flex items-center focus:ring-[#3b5998]/55 me-2 mb-2 svg-white" title="">
                        <?php include_once '../php/svg/twitter.svg' ?>
                    </a>
                </div>


            </div>
        </form>

    </div>
</div>