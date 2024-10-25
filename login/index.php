
<?php
ob_start();
//este archivo se va a encargar de cargar las dependencias del login y la vista tomemos de base el index principal

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS - ERIKA</title>
    <?php
    include '../links/head.php'; //aca esta el problema
    include '../php/lang.php';
    ?>
</head>

<body>
    <?php
    //include './views/shared/header.php';
    ?>
    <div class="gradient-bg loading">
        <div class="loader-video-container"><video autoplay loop muted playsinline>
                <source src="./assets/media/img/site/cursos-bg.webm" type="video/webm">
                Your browser does not support the video tag.
            </video></div>


        <svg xmlns="http://www.w3.org/2000/svg">
            <defs>
                <filter id="goo">
                    <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
                    <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -8" result="goo" />
                    <feBlend in="SourceGraphic" in2="goo" />
                </filter>
            </defs>
        </svg>
        <div class="gradients-container">
            <div class="g1"></div>
            <div class="g2"></div>
            <div class="g3"></div>
            <div class="g4"></div>
            <div class="g5"></div>
            <div class="interactive"></div>
        </div>
    </div>
    <main class=" p-8 w-full">
        <?php include '../views/login.php';?>
    </main>
        <?php

        include '../links/foot.php';
        ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            var tooltip = tippy($('[erika-tooltip]')[0], {
                            trigger:'manual',
                            touch: false,
                            content: "Correo o contraseña invalida",
                            placement: 'auto',
                            interactive: false,
                            allowHTML: true,
                        });
            
            var tooltipCorreo = tippy($('#correo')[0], {
                            trigger:'manual',
                            touch: false,
                            content: "Ingrese su correo",
                            placement: 'auto',
                            interactive: false,
                            allowHTML: true,
                        });
            var tooltipContra = tippy($('#usuario_pass')[0], {
                            trigger:'manual',
                            touch: false,
                            content: "Ingrese su contraseña",
                            placement: 'auto',
                            interactive: false,
                            allowHTML: true,
                        });


            $('#login_form').submit(function(event) {
                event.preventDefault(); // Evita que el formulario se envíe
                let form =this;
                // Recolectar los datos del formulario
                var formData = $(form).serialize(); // Serializa los datos del formulario en formato URL
                if(form['correo'].value.length===0){
                        
                    tooltipCorreo.show();
                    return
                }
                if(form['usuario_pass'].value.length===0){
                    tooltipContra.show();
                    return
                }
                // Realizar la petición AJAX
                $.ajax({
                    url: './php/login.php', // URL a la que se enviarán los datos
                    type: 'POST', // Método de envío
                    data: formData, // Datos a enviar
                    success: function(response) {
                        try {
                           let data = JSON.parse(response);
                           if(!data.error){
                            window.location.href = './';
                           }else {
                            tooltip.show();
                                 
                           }
                        } catch (error) {
                            
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Lógica a ejecutar en caso de error
                        console.log('Error al enviar el formulario:', textStatus, errorThrown);
                    }
                });
            });
            setTimeout(() => {
                $('.gradient-bg').removeClass('loading');
                $('.loader-video-container video')[0].style.display = 'none';
            }, 500);
           
           
            <?php require '../php/bg.php' ?>
        });
    </script>
</body>
<?php ob_end_flush(); ?>

</html>