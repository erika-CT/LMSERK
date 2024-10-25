<li class="loginregister">
<!-- Cambios en html-->

<!-- Cambios de prueba en git-->
  <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar" class="flex items-center justify-between w-full py-2 px-3 text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
    <img
      <?php
      if (isset($_SESSION['loggedin'])) { ?>
      src="<?=$_SESSION['fotografia']?>"
      <?php } else { ?>
      src="./img/user.png"
      <?php } ?>
      class="userlink" alt=""> <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
      <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
    </svg>
  </button>
  <!-- Dropdown menu -->
  <div id="dropdownNavbar" class="z-10 hidden font-normal  divide-y  rounded-lg shadow w-44 ">
    <ul class="menuitemdrop glassdrop py-2 text-sm text-gray-200" aria-labelledby="dropdownLargeButton">
      <?php


      if (!isset($_SESSION['loggedin'])) { ?>
        <li>
          <a href=".?k=./view/login/mostrar" class="block px-4 py-2 menulinkitem hover:text-white">Login</a>
        </li>
        <li>
          <a href=".?k=./view/registrar/mostrar" class="block px-4 py-2 menulinkitem hover:text-white">Registrarse</a>
        </li>
      <?php } else { ?>
        <li>
          <a href="#" class="block px-4 py-2 menulinkitem hover:text-white"><?= $_SESSION["nombreCorto"] ?></a>
        </li>
        <?php if (isset($_SESSION["rol"])) {
          if ($_SESSION["rol"] == "admin") {  ?>
            <li>
              <a href="./admin.php" class="block px-4 py-2 menulinkitem hover:text-white"><?= $lan["administrar"] ?></a>
            </li>

            <li>
              <form action="./php/logout.php" method="POST">

                <button type="submit" class="block w-full px-4 py-2 menulinkitem hover:text-white"><?= $lan["cerrar_sesion"] ?></a>
              </form>
            </li>
      <?php }
        }
      } ?>
    </ul>
  </div>
</li>