<?php
ob_start();
if (session_status() == PHP_SESSION_NONE)
    session_start();
require './php/db/conn.php';
require './php/lang.php';
$pagina = $_SERVER['REQUEST_URI'];
// Elimina parámetros de consulta (query string)
$pagina = strtolower(strtok(strtolower(strtok($pagina, '?')), '-'));  // Lowercase and remove query paramsW       // Split by '/' into parts
$ocultar = "";
$rol = "visitante";
$logueado = isset($_SESSION['LOGUEADO']) ? $_SESSION['LOGUEADO'] : false;
if ($logueado) {
    $rol = $_SESSION['ROL'];
    $usuario = $_SESSION['USUARIO_ID'];
    // Llama al procedimiento para actualizar el último inicio de sesión
    $db->exec('CALL actualizar_ultimo_inicio_sesion(' . $_SESSION['USUARIO_ID'] . ');');
} else {
    $pagina = "/cursos";
    $ocultar = "ocultar";
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS - ERIKA</title>
    <link rel="stylesheet" href="./assets/css/froala_editor.css">
    <link rel="stylesheet" href="./assets/css/froala_style.css">
    <link rel="stylesheet" href="./assets/css/plugins/code_view.css">
    <link rel="stylesheet" href="./assets/css/plugins/draggable.css">
    <link rel="stylesheet" href="./assets/css/plugins/colors.css">
    <link rel="stylesheet" href="./assets/css/plugins/emoticons.css">
    <link rel="stylesheet" href="./assets/css/plugins/image_manager.css">
    <link rel="stylesheet" href="./assets/css/plugins/image.css">
    <link rel="stylesheet" href="./assets/css/plugins/line_breaker.css">
    <link rel="stylesheet" href="./assets/css/plugins/table.css">
    <link rel="stylesheet" href="./assets/css/plugins/char_counter.css">
    <link rel="stylesheet" href="./assets/css/plugins/video.css">
    <link rel="stylesheet" href="./assets/css/plugins/fullscreen.css">
    <link rel="stylesheet" href="./assets/css/plugins/file.css">
    <link rel="stylesheet" href="./assets/css/plugins/quick_insert.css">
    <link rel="stylesheet" href="./assets/css/plugins/help.css">
    <link rel="stylesheet" href="./assets/css/third_party/spell_checker.css">
    <link rel="stylesheet" href="./assets/css/plugins/special_characters.css">

    <link rel="stylesheet" href="./assets/css/plugins/codemirror.min.css">
    <?php
    include './links/head.php';
    ?>
</head>

<body>
    <?php

    include './views/shared/header.php';
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


    <main class="transform translate-x-[12.5rem] mt-5  p-8 w-full">
        <?php

        $addEventCursos = false;


        // Definir rutas básicas
        switch ($pagina) {

            case '/':
            case 'index.php':
            case '/index.php':
            case "/lmserk/":
            case "/home":
            case '/categorias':
            case '/cursos':
            case '/curso-detalle':
            case '/curso':
            case '/usuario-detalle':
            case '/usuario':
            case '/usuarios':
            case '/instructores':
            case '/estudiantes':
            case '/mis-cursos':
            case '/mis-archivos':


                require 'views/home.php';
                require 'views/usuarios.php';
                require 'views/estudiantes.php';
                require 'views/cursos.php';
                require 'views/instructores.php';
                require 'views/mis-tareas.php';
                require 'views/mis-archivos.php';
                require 'views/mis-cursos.php';
                $addEventCursos = true;
                require 'views/categorias.php';
                break;
            default:
                http_response_code(404);
                header("Location: /404");
                exit;
                break;
        }
        include './links/foot.php';
        ?>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.vudio = null;
            window.froala = null;
            window.player = null;
            window.audioObj = null;
            window.canvasObj = null;
            window.ipSocket = 'ws://192.168.157.153:2512'
            window.usuario = <?php if ($logueado) echo $usuario;
                                else echo "null" ?>;
            window.socket;
            window.secciones = [];
            window.inscritos = [];
            window.media = [];
            window.mixer_inscritos = null;
            window.mixer_secciones = null;
            window.mixer_mensaje_lista = null;
            jQuery(function($) {
                "use strict";

                //todo aquel elemento que no tenga tabindex le ponemos tabindex = -1 para desactivae el foco del teclado
                $('*:not([tabindex])').attr('tabindex', '-1');

                //evento tecla espacio para la accesibilidad por teclado al dar espacio se accionan los elementos
               /* $(document).on('keydown', function(event) {
                    // Verifica si la tecla presionada es la barra espaciadora
                    if (event.which === 32) { // 32 es el código de la barra espaciadora
                        const focusedElement = $(document.activeElement);
                        // Verifica si el elemento está visible y se puede hacer clic
                        if (focusedElement.is('[tabindex]') && focusedElement[0].nodeName != "INPUT") {
                            event.preventDefault(); // Prevenir el comportamiento por defecto de la barra espaciadora
                            // Simular un clic en el elemento
                            focusedElement.click();
                        }
                    }
                });*/

                //evento para el menu lateral
                $('#menulateraladmin .con-tabs').on('mouseleave', function(e) {
                    // revisa si el mouse ya salio de tod el bloque y no esta en un nodo hijo 
                    if (!$(this).is(e.relatedTarget) && !$.contains(this, e.relatedTarget)) {
                        //verificamos que que el menu es no tenga la clase collapse-menu y que tenga data-float = true eno nos indica que se auto colapso el menu y cuando el usuario lo descolapse se va a colapsar automaticamente debido a que la pantalla es pequeña
                        if ($('main').data('float') && !$('#menulateraladmin').hasClass('collapse-menu')) {
                            $('#menulateraladmin').addClass('collapse-menu');
                            $('.offmenu').removeClass('hidden');
                            $('.onmenu').addClass('hidden');
                            $('main').data('colapsado', false);
                        }
                    }
                });
                //efecto glider para el menu lateral
                $('#menulateraladmin .tab').each((i, e) => {
                    $(e).data('i', i + 1);
                    $(e).on('mouseenter', function() {
                        $('#menulateraladmin .glider-hover')[0].className = 'glider-hover';
                        $('#menulateraladmin .glider-hover').addClass('s' + $(this).data('i'));
                    });
                    $(e).on('mouseleave', function() {
                        $('#menulateraladmin .glider-hover')[0].className = 'glider-hover';
                    });
                    $(e).on('click', function() {

                    });
                });
                //boton que colapsa manual el menu lateral
                $('.menu-izquierdo-collapser').on('click', function(event) {

                    event.preventDefault();
                    if ($('#menulateraladmin').hasClass('collapse-menu')) {
                        $('#menulateraladmin').removeClass('collapse-menu');
                        $(this).find('.offmenu').removeClass('hidden');
                        $(this).find('.onmenu').addClass('hidden');
                        if (!$('main').data('float')) {
                            $('section').removeClass('collapse-menu');
                            $('main').addClass('translate-x-[12.5rem]').removeClass('translate-x-[3rem]');

                        }
                        $('main').data('colapsado', false)
                    } else {
                        $(this).find('.offmenu').addClass('hidden');
                        $(this).find('.onmenu').removeClass('hidden');
                        $('main').data('colapsado', true)
                        $('section').addClass('collapse-menu');
                        $('main').removeClass('translate-x-[12.5rem]').addClass('translate-x-[3rem]');

                        $('#menulateraladmin').addClass('collapse-menu');
                    }
                });
                <?php if ($addEventCursos) {
                    echo "
                            var listView = document.querySelector('.list-view');
                            var gridView = document.querySelector('.grid-view');
                            var projectsList = document.querySelector('.project-boxes');
                            
                            listView.addEventListener('click', function () {
                                gridView.classList.add('active');
                                listView.classList.remove('active');
                                projectsList.classList.remove('jsGridView');
                                projectsList.classList.add('jsListView');
                            });
                            
                            gridView.addEventListener('click', function () {
                                gridView.classList.remove('active');
                                listView.classList.add('active');
                                projectsList.classList.remove('jsListView');
                                projectsList.classList.add('jsGridView');
                            });";
                } ?>


                setTimeout(() => {
                    $(`#menulateraladmin [data-seccion="${$('section:not(.hide)')[0].id}"]`).trigger('click');
                }, 1000);
                window.cargar_seccion = function(cual) {
                    if (player)
                        player.pause();
                    try {
                        videojs('videoPlayer-vid').pause()
                    } catch (e) {}
                    let seccionIdentifier = $(cual).data('seccion');
                    if (!seccionIdentifier)
                        return false;
                    $(`section#${$(cual).data('last')}`).addClass('hide');
                    $(`#menulateraladmin [data-seccion].active`).removeClass('active');
                    $(cual).addClass('active');
                    $('#menulateraladmin [data-seccion]').each(function() {
                        $(this).data('last', seccionIdentifier);
                    });
                    const section = document.querySelector(`section#${cual.dataset.seccion}`);
                    if (section) {
                        if (!$(section).data('loaded')) {
                            setTimeout(async () => {
                                try {
                                    let seccion_mixit = $(section).data('mixit');
                                    let data = await erika.getAll(`./php/db/get.php?a=${$(cual).data('seccion')}`);
                                    (window[seccion_mixit]).dataset(data).then(() => {
                                        window.lazyCaller();
                                        dominant();
                                        setTooltip();
                                        setTimeout(() => {
                                            $(section).data('loaded', false);

                                        }, 5000);
                                        responsividad($('nav').width());
                                    });
                                } catch (error) {
                                    console.log('Can load Section data')
                                }
                            }, 500);
                        }
                        $(`section#${$(cual).data('seccion')}`).removeClass('hide');
                        history.pushState(null, '', './' + seccionIdentifier.replaceAll('seccion-', ''));
                    } else {
                        fetch(`./php/getView.php?v=${$(cual).data('seccion')}`)
                            .then(response => response.text())
                            .then(data => {
                                let seccionDOM = document.createElement('section');
                                document.querySelector('main').appendChild(seccionDOM);
                                seccionDOM.outerHTML = data;
                                console.log(seccionIdentifier, seccionIdentifier.replaceAll('seccion-', ''))
                                history.pushState(null, '', './' + seccionIdentifier.replaceAll('seccion-', ''));
                            })
                            .catch(error => console.error('Error:', error));
                    }
                    console.log(cual)
                }
                window.updateCursoDate = async function(id_curso, cual, fecha) {
                    let formData = new FormData();
                    formData.append('fecha', fecha);
                    formData.append('id_curso', id_curso);
                    if (cual == 'ini') {
                        formData.append('a', 'curso_fecha_ini');
                    }
                    if (cual == 'fini') {
                        formData.append('a', 'curso_fecha_fini');
                    }
                    return fetch('./php/db/post.php', {
                            method: 'POST',
                            body: formData
                        }).then(response => response.json()) // Convert response to JSON
                        .catch(error => ({
                            error: true,
                            msg: 'Ocurrio un error!'
                        })); // Handle network errors
                }
                $('#menulateraladmin  [data-seccion]').on('click', function() {
                    window.cargar_seccion(this)
                });
                window.erika = {};
                window.erika.getAll = async function(url) { //url con todo y parametros get
                    try {
                        // Realiza la petición y espera la respuesta
                        const response = await fetch(url);
                        // Verifica si la respuesta fue exitosa
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        // Convierte la respuesta a texto
                        const data = await response.text();
                        return JSON.parse(data);
                    } catch (error) {
                        // Captura y maneja el error
                        console.error('Error fetching data:', error);
                        return null; // O algún valor para manejar el error
                    }
                };

                $('[erika-no-ajax]').each((i, e) => {
                    $(e).select2({
                        placeholder: $(e).data('select_placeholder'),
                        allowClear: false,
                        minimumResultsForSearch: -1,
                        templateResult: function formatState(state) {
                            if (!state.id) {
                                return state.text;
                            }
                            var $state = $(`<span class="flex h-[2.5rem] items-center text-white">
                                            <span style="display:${state.element.value.toLowerCase()=='f'?'block':'none'};"><?php include './php/svg/sexo-f.svg' ?></span>
                                            <span style="display:${state.element.value.toLowerCase()=='m'?'block':'none'};"><?php include './php/svg/sexo-m.svg' ?></span>
                                            <span>${state.text}</span>
                                            </span>`);
                            return $state;
                        },
                        templateSelection: function(state, container) {
                            // This is where you override the pillow rendering
                            if (!state.id) {
                                return state.text; // Return the placeholder text for unselected items
                            }
                            var $state = $(`<span class="h-[2.5rem] flex items-center text-white">
                                            <span style="display:${state.element.value.toLowerCase()=='f'?'block':'none'};"><?php include './php/svg/sexo-f.svg' ?></span>
                                            <span style="display:${state.element.value.toLowerCase()=='m'?'block':'none'};"><?php include './php/svg/sexo-m.svg' ?></span>
                                            <span>${state.text}</span>
                                            </span>`);
                            return $state;
                        },
                    });
                });

                window.setSelect2 = function(sel) {
                    $(sel).each(function(i, element) {
                        let $element = $(element);
                        let initialData = [];
                        try {
                            initialData = JSON.parse($element.attr('initial-data') || '[]');
                            initialData.unshift({
                                id: "",
                                text: "",
                                img: "",
                            });
                        } catch (e) {
                            initialData = [{
                                id: "",
                                img: "",
                                text: ""
                            }]
                            console.error('Error parsing initial-data:', e);
                        }

                        function initializeSelect2() {
                            let isInitialLoad = true;

                            let select2Config = {
                                multiple: $element.data('select_get_multiple') || false,
                                data: initialData,
                                language: "es",
                                placeholder: $element.data('select_placeholder'),
                                allowClear: true,
                                minimumResultsForSearch: 0, // Siempre muestra la caja de búsqueda
                                minimumInputLength: 0,
                                ajax: {
                                    url: './php/db/get.php',
                                    dataType: 'json',
                                    delay: 250,
                                    data: function(params) {
                                        return {
                                            a: $element.data('select_get'),
                                            q: params.term, // search term
                                            page: params.page,
                                            x: $element.data('select_get_x')
                                        };
                                    },
                                    processResults: function(data, params) {
                                        params.page = params.page || 1;
                                        let img = '../assets/media/img/site/categoria_placeholder-thumb.png';
                                        return {
                                            results: data.data.map(function(item) {
                                                return {
                                                    id: item.id,
                                                    text: item.text,
                                                    img: item.img == "" ? img : item.img == undefined ? img : item.img,
                                                    class: $element.data('select_get')
                                                };
                                            }),
                                            pagination: {
                                                more: (params.page * 30) < data.total_count
                                            }
                                        };
                                    },
                                    cache: true,
                                    transport: function(params, success, failure) {
                                        if (!isInitialLoad || (params.data.q && params.data.q.length > 0)) {
                                            return $.ajax(params).then(success).fail(failure);
                                        } else {
                                            success({
                                                data: initialData,
                                                total_count: initialData.length
                                            });
                                            isInitialLoad = false;
                                        }
                                    }
                                },
                                templateResult: function formatState(state) {
                                    if (!state.id) {
                                        return state.text;
                                    }
                                    var $state = $(
                                        `<span class="custom-pill">
                                              <?php include './php/svg/check.svg' ?>
                                            <img src="${state.img}" class="ml-3 ${state.class}">
                                            <span class="ml-3">${state.text}</span>
                                        </span>`
                                    );
                                    return $state;
                                },
                                templateSelection: function(data, container) {
                                    // This is where you override the pillow rendering
                                    if (!data.id) {
                                        return data.text; // Return the placeholder text for unselected items
                                    }

                                    // Custom rendering of the selected option
                                    var $pill = $(
                                        `<span class="custom-pill">
                                            <img src="${data.img}" class="${data.class}">
                                            <span class="ml-3">${data.text}</span>
                                        </span>`
                                    );

                                    return $pill;
                                },
                                escapeMarkup: function(markup) {
                                    return markup; // Allow custom HTML rendering
                                }
                            };

                            // Destruye la instancia existente si la hay
                            if ($element.data('select2')) {
                                $element.select2('destroy');
                            }

                            // Inicializa Select2
                            $element.select2(select2Config);

                            // Si no hay datos iniciales, fuerza una carga inicial
                            if (initialData.length === 0) {
                                $element.select2('open');
                                $element.select2('close');
                            }
                        }

                        // Inicialización inicial
                        initializeSelect2();
                        // Reinicializa Select2 cuando se cierra
                        $element.on('select2:close', function() {
                            setTimeout(initializeSelect2, 0);
                        });
                    });
                }
                window.setSelect2('[erika-select]:not([erika-no-ajax])')
                //elemento DOM con el atributo abrir-formulario el cual debe tene el ID del formulario a abrir

                //ENVIO de datos al servidor
                $('[erika-formulario]').on('submit', function(event) {
                    event.preventDefault();
                    tippy.hideAll({
                        duration: 0
                    });
                    let form = this;
                    let action = $(this).data('a');
                    let formData = new FormData();
                    let enviar = false;
                    let esActualizar = $(form).data('esActualizar');
                    if (esActualizar) {
                        formData.append('actualizar', $(this).data('id'));
                        let aditionals = $(this).data('aditionals');
                        if (aditionals)
                            aditionals.forEach(d => {
                                formData.append(d.p, d.v);
                            })
                    }
                    formData.append('a', $(this).data('a'));
                    $(this).find('.input-erika').each((i, e) => {
                        const telefonoRegex = /^\+(\d{1,3})[\s-]?(\d{1,4})[\s-]?(\d{1,4})[\s-]?(\d{1,9})$/;
                        const correoRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                        const imageRegex = /.webp$|.jpg$|.png$|.jpeg$/gi;
                        const pass = /^(?=.*\d)(?=(.*\W){1})(?=.*[a-zA-Z])(?!.*\s).{8,20}$/
                        const urlRegex = /^(https?:\/\/)?([a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})(\/[\w .-]*)*\/?(\?[;&a-zA-Z0-9%_.+-=]*)?(\#[a-zA-Z0-9_-]*)?$/;
                        const nombreApellidoRegex = /^[A-Za-zÀ-ÿ' ]*$/;
                        const dinero = /^\$?\d{1,3}(,\d{3})*(\.\d{2})?$/;
                        let validaciones = $(e).attr('erika-validator').split(',');
                        let el, val, files;
                        let esValido = true;
                        $(e).parent().removeClass('invalid');
                        el = $(e)[0];
                        if ($(el).attr('erika-target-validate')) {
                            el = $($(e).attr('erika-target-validate'))[0];
                        }
                        if (el.type == 'checkbox') {
                            val = el.checked
                        } else if ($(e).attr('erika-type') && $(e).attr('erika-type') == 'fecha') {
                            if ($(e).data('fecha').selectedDates.length > 0)
                                val = new Date($(e).data('fecha').selectedDates[0].getTime() - ($(e).data('fecha').selectedDates[0].getTimezoneOffset() * 60000)).toISOString().slice(0, 19);
                            else
                                val = ''
                        } else
                            val = $(e).val().trim();

                        esValido = !validaciones.some(v => {
                            v = v.trim().toLowerCase().replace(/:[^,]*/g, '');
                            switch (v.trim().toLowerCase().replace(/:[^,]*/g, '')) {
                                case 'requerido':
                                    if (val.length === 0 && !$(e).data('noval')) {
                                        if ($(e).attr('erika-validar-check')) {
                                            let _checked = $($(e).attr('erika-validar-check'))[0].checked;
                                            if (!_checked)
                                                break;
                                        }
                                        $(e).parent()[0].scrollIntoView({
                                            behavior: "smooth"
                                        });
                                        $(e).parent().addClass('invalid');
                                        $(e).data('vali', $(e).data('requerido'));
                                        if ($(e).data('notooltip')) {
                                            $($(e).data('notooltip'))[0]._tippy.show();
                                        } else
                                            $(e)[0]._tippy.show();
                                        return true;
                                    } else
                                        break;
                                case 'alfa':
                                    if (!nombreApellidoRegex.test(val)) {
                                        if (erikaValidator_alertar(e))
                                            return true;
                                        else break;
                                    }
                                    break;
                                case 'correo':
                                    if (!correoRegex.test(val)) {
                                        if (erikaValidator_alertar(e))
                                            return true;
                                        else break;
                                    }
                                    break;
                                case 'contraseña':
                                    if (!pass.test(val) && $(e).val().length > 0) {
                                        if (erikaValidator_alertar(e))
                                            return true;
                                        else break;
                                    }
                                    break;
                                case 'formato':
                                    files = FilePond.find(document.querySelector($(e).attr('erika-target-validate'))).getFiles();
                                    if (files.length === 0) {
                                        if (validaciones.indexOf('requerido') > -1) {
                                            return true;
                                        }
                                    }
                                    return true;
                                    break;
                                case 'max':
                                    let max = +v.replace(/.*:/, '');
                                    if (val.length > max) {
                                        if ($(e).attr('erika-validar-check')) {
                                            let _checked = $($(e).attr('erika-validar-check'))[0].checked;
                                            if (!_checked)
                                                break;
                                        }
                                        $(e).parent()[0].scrollIntoView({
                                            behavior: "smooth"
                                        });
                                        $(e).parent().addClass('invalid');
                                        $(e).data('vali', $(e).data('max'));
                                        if ($(e).data('notooltip')) {
                                            $($(e).data('notooltip'))[0]._tippy.show();
                                        } else
                                            $(e)[0]._tippy.show();
                                        return true;
                                    }
                                    break;
                                case 'archivo':
                                    let filePon = FilePond.find(document.querySelector($(e).attr('erika-target-validate')));
                                    files = filePon.getFiles();
                                    if (files.length === 0) {
                                        if (validaciones.indexOf('requerido') > -1) {
                                            return true;
                                        }
                                    } else {
                                        if (filePon.allowMultiple) {
                                            val = filePon.getFiles();
                                        } else {
                                            val = filePon.getFile().file;
                                            let ext = val.type.replace('image/', '');
                                            !(['jpeg', 'jpg', 'png', 'webp', 'gif', 'avif'].indexOf(ext) > -1)

                                        }
                                    }
                                    break;
                            }
                        })

                        if (esValido)
                            formData.append(e.id == "" ? $(e).attr('erika-target-validate').replaceAll('#', '') : e.id, val);
                        return (enviar = esValido);
                    });
                    if (enviar) {
                        //ocultar los botones y mostrar el loader
                        $(form).addClass('desactivar'); //desactivamos la interaccion para evitar posibles errores
                        ocultarBotonesFormulario(form, true)
                        // Realizar la petición AJAX
                        setTimeout(() => {
                            $.ajax({
                                url: './php/db/post.php', // Cambia esto por la ruta correcta
                                type: 'POST', // Método de envío
                                dataType: 'json', // Esperamos que la respuesta sea JSON
                                data: formData, // Enviar los datos del formulario
                                processData: false, // Evita que jQuery procese los datos
                                contentType: false, // Evita que jQuery establezca el contentType
                                timeout: 10000,
                                success: function(response, textStatus, jqXHR) {
                                    var statusCode = jqXHR.status; // Obtener el código de estado HTTP
                                    $(form).find('.campo').each((i, e) => {
                                        animar(e, i % 2 == 0 ? 'ocultar_derecha' : 'ocultar_izquierda')
                                    })
                                    animar($(form).find('[data-anterior]')[0], 'ocultar_derecha');
                                    animar($(form).find('[data-siguiente]')[0], 'ocultar_derecha');
                                    $(form).find('.campo').each((i, e) => {
                                        animar(e, i % 2 == 0 ? 'ocultar_derecha' : 'ocultar_izquierda')
                                    })
                                    try {
                                        var response = JSON.parse(jqXHR.responseText);
                                        if (!response.error) {
                                            $(form)[0].reset();
                                            $('select').trigger('change');
                                            $(form).data('id', null);
                                            $(form).data('aditionals', null);
                                            FilePond.find($(form).find('.filepond--root')[0]).removeFile();
                                            $(form).find('[erika-type="fecha"]').each((i, e) => {
                                                $(e).data('fecha').clear()
                                            });
                                            $('select').trigger('change');
                                        }
                                        $(form).find('.mensaje-ajax').html(response.msg)
                                        animar($(form).find('.mensaje-ajax')[0], 'mostrar_hacia_arriba')
                                        mostrarBotonesFormulario(form, true);
                                        $(form).removeClass('desactivar');
                                        $('#menulateraladmin .tab.active').trigger('click');
                                    } catch (e) {
                                        $(form).find('.mensaje-ajax').html(response.msg)
                                        animar($(form).find('.mensaje-ajax')[0], 'mostrar_hacia_arriba')
                                        mostrarBotonesFormulario(form, true);
                                        $(form).removeClass('desactivar');
                                        console.error('La respuesta no es un JSON válido:', jqXHR.responseText);
                                        return;
                                    }
                                    // Comprobar el estado y manejar la respuesta
                                    switch (statusCode) {
                                        case 200:

                                            console.log('Solicitud exitosa. Datos recibidos:', response);
                                            break;
                                        case 201:
                                            console.log('Recurso creado exitosamente.', response);
                                            break;
                                        case 204:
                                            console.log('Solicitud exitosa, pero sin contenido.');
                                            break;
                                        default:
                                            console.log('Solicitud completada con código de estado:', statusCode);
                                            break;
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {


                                    var statusCode = jqXHR.status; // Obtener el código de estado HTTP en caso de error
                                    $(form).find('.campo').each((i, e) => {
                                        animar(e, i % 2 == 0 ? 'ocultar_derecha' : 'ocultar_izquierda')
                                    })
                                    animar($(form).find('[data-anterior]')[0], 'ocultar_derecha');
                                    animar($(form).find('[data-siguiente]')[0], 'ocultar_derecha');
                                    $(form).find('.campo').each((i, e) => {
                                        animar(e, i % 2 == 0 ? 'ocultar_derecha' : 'ocultar_izquierda')
                                    })
                                    if (textStatus === 'timeout') {
                                        $(form).find('.mensaje-ajax').html(`<span class="text-[#ff5722]">Hubo un probleba con el envio de los datos, por favor verificar la conexion a internet</span>`)
                                        animar($(form).find('.mensaje-ajax')[0], 'mostrar_hacia_arriba')
                                        $(form).removeClass('desactivar');
                                        mostrarBotonesFormulario(form, 2, true)
                                        return;
                                    }
                                    // Intentar convertir la respuesta en JSON
                                    try {
                                        var response = JSON.parse(jqXHR.responseText);
                                        $(form).removeClass('desactivar');
                                        $(form).find('.btn-siguiente').data('stagestype', 'siguiente');
                                        mostrarBotonesFormulario(form, 2, true)
                                    } catch (e) {
                                        $(form).find('.mensaje-ajax').html(response.msg)
                                        animar($(form).find('.mensaje-ajax')[0], 'mostrar_hacia_arriba')
                                        mostrarBotonesFormulario(form, true);
                                        $(form).removeClass('desactivar');
                                        console.error('La respuesta no es un JSON válido:', jqXHR.responseText);
                                        return;
                                    }
                                    // Manejar los diferentes códigos de error y la respuesta JSON
                                    switch (statusCode) {
                                        case 400:
                                            console.log('Solicitud incorrecta (400):', response);
                                            break;
                                        case 401:
                                            console.log('No autorizado (401):', response);
                                            break;
                                        case 403:
                                            console.log('Prohibido (403):', response);
                                            break;
                                        case 404:
                                            console.log('No encontrado (404):', response);
                                            break;
                                        case 500:
                                            console.log('Error interno del servidor (500):', response);
                                            break;
                                        default:
                                            console.log('Error desconocido:', statusCode, response);
                                            break;
                                    }
                                }
                            });
                        }, 1000);
                    }
                    console.log(enviar)
                });
                window.setAbrirformlarios = function(el) {
                    if (el) {
                        $(el).on('click', function() {
                            $('body').data('form_abierto', $(this).data('formulario'));
                            console.log($(this).data('formulario'))
                            abriForumlario($(this).data('formulario'));
                        });
                    } else {
                        $('[abrir-formulario]').on('click', function() {
                            $('body').data('form_abierto', $(this).data('formulario'));
                            console.log($(this).data('formulario'))
                            abriForumlario($(this).data('formulario'));
                        });
                    }
                }
                window.setAbrirformlarios();

                window.erikaValidator_alertar = function(e) {
                    if ($(e).attr('erika-validar-check')) {
                        let _checked = $($(e).attr('erika-validar-check'))[0].checked;
                        if (!_checked)
                            return false;
                    }
                    $(e).parent()[0].scrollIntoView({
                        behavior: "smooth"
                    });
                    $(e).parent().addClass('invalid');
                    $(e).data('vali', $(e).data('mensaje'));
                    if ($(e).data('notooltip')) {
                        $($(e).data('notooltip'))[0]._tippy.show();
                    } else
                        $(e)[0]._tippy.show();
                    return true
                }
                //preparacion de los mensajes emergentes usando tippy
                window.setTooltip = function(sel = '[erika-tooltip]', triggerOnlyHover) {
                    $(sel).each((i, e) => {
                        if (!e._tippy)
                            tippy(e, {
                                onShow(instance) {
                                    let contentSetted = false;
                                    if (instance.reference.type && instance.reference.type == "password") {
                                        let cadena = instance.reference.value;
                                        const regexMinusc = /(?=.*[a-z])/; // Al menos una letra minúscula
                                        const regexMayusc = /(?=.*[A-Z])/; // Al menos una letra mayúscula
                                        const regexNumero = /(?=.*\d)/; // Al menos un número
                                        const regexEspecial = /(?=.*[\W_])/; // Al menos un carácter especial
                                        const regexSinEspacios = /^\S*$/; // sin espacios
                                        let $pass = $(`<span></span>`).html(instance.reference.dataset.mensaje);

                                        $pass.find('li').removeClass('text-green-500')
                                        $pass.find('svg').removeClass('fill-green-500')
                                        if (regexMinusc.test(cadena)) {
                                            $($pass.find('li')[0]).find('svg').addClass('fill-green-500')
                                            $($pass.find('li')[0]).addClass('text-green-500')

                                        }

                                        if (regexMayusc.test(cadena)) {
                                            $($pass.find('li')[1]).find('svg').addClass('fill-green-500')
                                            $($pass.find('li')[1]).addClass('text-green-500')
                                        }
                                        if (regexNumero.test(cadena)) {
                                            $($pass.find('li')[2]).find('svg').addClass('fill-green-500')
                                            $($pass.find('li')[2]).addClass('text-green-500')
                                        }
                                        if (regexEspecial.test(cadena)) {
                                            $($pass.find('li')[3]).find('svg').addClass('fill-green-500')
                                            $($pass.find('li')[3]).addClass('text-green-500')
                                        }
                                        if (regexSinEspacios.test(cadena)) {
                                            $($pass.find('li')[4]).find('svg').addClass('fill-green-500')
                                            $($pass.find('li')[4]).addClass('text-green-500')
                                        }
                                        if (cadena.length >= 8 && cadena.length <= 20) {
                                            $($pass.find('li')[5]).find('svg').addClass('fill-green-500')
                                            $($pass.find('li')[5]).addClass('text-green-500')
                                        }
                                        console.log($pass)
                                        instance.setContent($pass[0].innerHTML)
                                        console.log('tootltip set passqwsdfsd')
                                        contentSetted = true;

                                    }
                                    let status;
                                    try {
                                        status = $('#' + $('body').data('form_abierto')).find(`[${$(instance.reference).attr('erika-tooltip')}]`)[0].style.display;
                                    } catch (e) {}
                                    if (status == 'none') {
                                        $('[data-stagesType="atras"]').trigger('click');
                                        setTimeout(() => {
                                            instance.hide();
                                        }, 10);
                                        setTimeout(() => {
                                            instance.show();
                                        }, 500);

                                    }
                                    if (!contentSetted)
                                        instance.setContent($(instance.reference).data('vali') || $(instance.reference).attr('tooltip-content'));
                                },
                                trigger: $(e).attr('tooltip-trigger') || 'mouseenter focus',
                                triggerTarget: e,
                                touch: false,
                                placement: $(e).attr('tooltip-placement') || 'auto',
                                offset: $(e).attr('tooltip-offset') != undefined ? JSON.parse($(e).attr('tooltip-offset')) : [],
                                interactive: ($(e).attr('tooltip-interactive') != undefined) || false,
                                allowHTML: true,

                            });
                    });
                }
                $('[erika-no-espacios]').each((i, e) => {
                    $(e).on('input', function() {
                        $(this).val($(this).val().replaceAll(' ', ''))
                    });
                });
                $("form .loading").attr('style', 'opacity: 0; transform: translateY(100%);')
                $('[erika-toggler]').each((i, e) => {
                    $($(e).attr('erika-toggler')).attr('style', 'opacity: 0; transform: translateX(100%);')
                    $(e).on('change', function() {
                        let target = $($(this).attr('erika-toggler'))[0]
                        if (!e.checked) {
                            animar(target, 'ocultar_derecha')
                        } else {
                            animar(target, 'mostrar_izquierda')

                        }
                        console.log($($(this).attr('erika-toggler'))[0], );
                    })
                })
                $('[modal-cerrar]').on('click', function() {
                    cerrarFormulario(this.getAttribute('modal-cerrar'), true)
                });

                $('[erika-otro-registro-form]').on('click', function() {
                    let form = $('#' + $(this).attr('erika-otro-registro-form'));

                    $(form)[0].reset();
                    $('select').trigger('change');
                    $(form).data('id', null);
                    $(form).data('aditionals', null);
                    FilePond.find($(form).find('.filepond--root')[0]).removeFile();
                    $(form).find('[erika-type="fecha"]').each((i, e) => {
                        $(e).data('fecha').clear()
                    });
                    $(form).find('.campo').each((i, e) => {
                        animar(e, i % 2 == 0 ? 'mostrar_derecha' : 'mostrar_izquierda')
                    })
                    animar($(form).find('.btn-otro')[0], 'ocultar_izquierda')
                    animar($(form).find('.mensaje-ajax')[0], 'ocultar_hacia_abajo')
                    if ($(form).find('.otro-btn').data('moved')) {
                        $(form).find('.otro-btn').removeData('moved');
                        $(form).find('.btn-dont').before($(form).find('.btn-ok')[0]);
                        $(form).find('.otro-btn').append($(form).find('.btn-otro')[0]);

                    }
                    animar($(form).find('.btn-ok')[0], 'mostrar_derecha') //boton aceptar
                });
                window.setFormSwitcher = function(s) {
                    $(s).on('click', function() {
                        let f_from = $(this).attr('erika-form-from');
                        let f_to = $(this).attr('erika-form-open');
                        cerrarFormulario(f_from, false);
                        $('#' + f_to).data('from_other_form', true);
                        abriForumlario(f_to);
                        let intervalClose = setInterval(() => {
                            if (!$('#' + f_to).data('from_other_form')) {
                                clearInterval(intervalClose);
                                abriForumlario(f_from);
                            }
                        }, 500);
                    });
                }
                window.setFormSwitcher('[erika-form-open]');
                //ejemplo aclararDominat([255,255,255],0.5)
                // factorClaridad =  1 - factorClaridad ejemplo 1-0.5 = 50% mas claro, 1 - 0.7 = 30% mas claro
                window.aclararDominante = function(rgbColor, factorClaridad) {
                    // Asegurar que el factor esté entre 0 y 1
                    factorClaridad = Math.max(0, Math.min(1, factorClaridad));

                    let [r, g, b] = rgbColor; // Destructuramos el array de [r, g, b]

                    // Calcular la luminosidad percibida (brillo) usando la fórmula de luminosidad
                    let brillo = (0.299 * r + 0.587 * g + 0.114 * b);

                    // Si el brillo ya es alto (color claro), no aclararlo más
                    if (brillo > 200) {
                        return [r, g, b]; // Devolver el color original sin aclarar
                    }

                    // Aumentamos cada valor acercándolo a 255 para aclarar
                    const rClaro = Math.floor(r + (255 - r) * factorClaridad);
                    const gClaro = Math.floor(g + (255 - g) * factorClaridad);
                    const bClaro = Math.floor(b + (255 - b) * factorClaridad);

                    return [rClaro, gClaro, bClaro];
                };

                //ejemplo oscurecerDominant([255,255,255],0.5)
                // factorOscuridad =  1 - factorOscuridad ejemplo 1-0.5 = 50% mas oscuro, 1 - 0.7 = 30% mas oscuro
                window.oscurecerDominant = function(rgbColor, factorOscuridad) {
                    // Asegurar que el factor esté entre 0 y 1
                    factorOscuridad = Math.max(0, Math.min(1, factorOscuridad));

                    let [r, g, b] = rgbColor; // Destructuramos el array de [r, g, b]

                    // Calcular la luminosidad percibida (brillo) usando la fórmula de luminosidad
                    let brillo = (0.299 * r + 0.587 * g + 0.114 * b);

                    // Si el color es demasiado claro (alto brillo), ajustar el factor de oscuridad
                    if (brillo > 200) {
                        factorOscuridad = factorOscuridad * 0.5; // Oscurecer más si es muy brillante
                    }

                    // Multiplicamos cada valor por el factor de oscuridad
                    const rOscuro = Math.floor(r * factorOscuridad);
                    const gOscuro = Math.floor(g * factorOscuridad);
                    const bOscuro = Math.floor(b * factorOscuridad);

                    return [rOscuro, gOscuro, bOscuro];
                }
                //funcion que obtiene el color dominante de una imagen haciendo uso de la libreria color thief
                window.dominant = function() {
                    $('[data-dominant-color]').each((i, e) => {
                        const tempImg = new Image();
                        tempImg.src = $(e).data('thumb');
                        let property = $(e).data('dominantColor')
                        let oscurecer = $(e).data('dominantOscurecer') !== undefined;
                        // Usar Color Thief para extraer el color dominante
                        const colorThief = new ColorThief();
                        // Asegurarse de que la imagen esté completamente cargada
                        tempImg.onload = function() {
                            const dominantColor = oscurecer ? oscurecerDominant(colorThief.getColor(tempImg), 0.2) : aclararDominat(colorThief.getColor(tempImg), 0.2); // Obtiene el color dominante
                            console.log(dominantColor); // [r, g, b]
                            // Cambiar el fondo o cualquier propiedad
                            e.style.setProperty('--card-bg', `rgb(${dominantColor[0]}, ${dominantColor[1]}, ${dominantColor[2]})`);
                        };
                    })
                }
                window.lazyObserver_img = lozad('.lazy-img', {
                    loaded: function(el) {
                        el.src = el.dataset.src;
                    }
                });
                window.lazyObserver_background = lozad('.lazy-background', {
                    loaded: function(el) {
                        el.style.backgroundImage = `url(${el.dataset.backgroundImage})`;
                    }
                });
                window.lazyCaller = function() {
                    try {
                        lazyObserver_background.observe();
                    } catch (error) {

                    }
                    try {
                        lazyObserver_img.observe();

                    } catch (error) {

                    }
                }
                window.lazyCaller();
                var updateCanvaSizeTimeOut = setTimeout(function() {}, 10); //variable que guarda un timeout para poder cancelarlo con  clearTimeout con el fin de poder cancelar todas las llamadas que provoca el cambio de tamaño de  curso-menu-2 ya que si cambia su tamaño hay que actualizar el canva del window.vudio  asi que si se llaman 1000 veces se cancelan 999 y se deja ejecutar el ultimo
                var observer = new ResizeObserver(function(entries) { //observador que se ejecuta al cambiar el tamaño de curso-menu-2
                    for (let entry of entries) {
                        clearTimeout(updateCanvaSizeTimeOut); //cancela el timeout anterior
                        updateCanvaSizeTimeOut = setTimeout(function() { //nuevo timeout

                            updateCanvaSize(); //si no se cancela el timeout se llama esta funcion que actualiza el canva de window.vudio
                            console.log(entry.target.nodeName, entry.contentRect.width)


                            responsividad(entry.contentRect.width)
                        }, 10); //10 es el delay en milisegundos

                    }
                });
                observer.observe(document.getElementById('erika-navbar')); //se establece es elemento a observar
                //observer.observe(document.getElementById('curso-menu-2')); //se establece es elemento a observar
                window.updateCanvaSize = function() {
                    if (window.vudio) { //si window.vudio ya fue inicializado 
                        window.vudio.option.width = $('#audio0')[0].offsetWidth; //ancho del padre de window.vudio
                        window.canvasObj.width = $('#audio0')[0].offsetWidth;
                        window.vudio.width = $('#audio0')[0].offsetWidth;
                        window.vudio.option.height = 50; //5
                        window.vudio.height = 50;
                        window.canvasObj.height = 50;
                    }
                }
                window.actualizarCategoria = function(cual) {
                    {
                        query: ""
                    }
                }

                window.ocultarBotonesFormulario = function(form, otro) {
                    console.log('ocultando botones')
                    if (otro) { //para pedirle al usuario si desea agregar otro registro
                        if ($(form).attr('erika-formulario-stages') === undefined) {
                            setTimeout(() => {
                                $(form).find('.btn-dont').before($(form).find('.btn-otro')[0]);
                                $(form).find('.otro-btn').append($(form).find('.btn-ok')[0]);
                                $(form).find('.otro-btn').append($(form).find('.btn-reintentar')[0]);
                                $(form).find('.otro-btn').data('moved', true);
                            }, 1000);
                        } else {
                            $(form).find('.btn-siguiente').removeClass(['bg-[#ec4899]/50', 'hover:bg-[#ec4899]/90'])
                        }
                    }
                    animar($(form).find('.btn-atras')[0], 'ocultar_izquierda')
                    animar($(form).find('.btn-siguiente')[0], 'ocultar_hacia_abajo')
                    animar($(form).find('.btn-ok')[0], 'ocultar_izquierda') //boton aceptar
                    animar($(form).find('.btn-otro')[0], 'ocultar_izquierda')
                    animar($(form).find('.btn-reintentar')[0], 'ocultar_izquierda')
                    animar($(form).find('.btn-dont')[0], 'ocultar_derecha')
                    animar($(form).find('.loading')[0], 'mostrar_hacia_arriba')
                }
                window.mostrarBotonesFormulario = function(form, otro, error) {
                    console.log('mostrando botones')
                    animar($(form).find('.btn-dont')[0], 'mostrar_izquierda')
                    if (!$(form).data('esActualizar')) {
                        animar($(form).find('.btn-atras')[0], 'mostrar_derecha')
                        animar($(form).find('.btn-siguiente')[0], 'mostrar_hacia_arriba')

                    }
                    animar($(form).find('.loading')[0], 'ocultar_hacia_abajo') //indicador de carga
                    $(form).removeClass('desactivado')
                    $(form).find('.btn-siguiente').text($(form).find('.btn-siguiente').data('btnotro'))
                    $(form).find('.btn-siguiente').data('stagestype', 'siguiente-sent');
                    $(form).find('.btn-atras').addClass('desactivado');
                    if (error) {
                        if ($(form).attr('erika-formulario-stages') === undefined) {
                            $(form).find('.btn-dont').before($(form).find('.btn-reintentar')[0]);
                            $(form).find('.otro-btn').append($(form).find('.btn-otro')[0]);
                            $(form).find('.otro-btn').data('moved', true);
                        } else {
                            $(form).find('.btn-siguiente').text('Reintentar').addClass(['bg-[#f3c121]/50', 'hover:bg-[#f3c121]/90'])
                            $(form).find('.btn-siguiente').data('stagestype', 'siguiente');
                            $(form).find('.btn-atras').removeClass('desactivado');

                        }
                    }
                    if (!$(form).data('esActualizar'))
                        if (otro) {
                            if (otro == true)
                                animar($(form).find('.btn-otro')[0], 'mostrar_derecha') //boton registrar mas
                            else
                            if ($(form).attr('erika-formulario-stages') === undefined)
                                animar($(form).find('.btn-reintentar')[0], 'mostrar_derecha') //boton registrar mas
                        } else {
                            animar($(form).find('.btn-ok')[0], 'mostrar_derecha') //boton aceptar
                        }
                    $(form).data('esActualizar', null);
                }
                window.responsividad = function(width) {
                    console.log(565)
                    $('.project-boxes.jsGridView .project-box-wrapper').css('width', '33%')
                    $('.project-boxes.jsGridView .card-erika').css('width', '95%');
                    $('.search-wrapper').css('max-width', '400px');



                    if (width <= 700)
                        $('.project-boxes.jsGridView .card-erika').css('width', '95%');


                    if (width > 700 && width <= 900)
                        $('.project-boxes.jsGridView .card-erika').css('width', '85%');



                    if (width > 1500)
                        $('.project-boxes.jsGridView .card-erika').css('width', '45%');



                    if (width < 615) {
                        if (!$('main').data('colapsado'))
                            $('.search-wrapper').css('max-width', '275px');
                    }



                    if (width > 615 && width < 725) {
                        if (!$('main').data('colapsado'))
                            $('.search-wrapper').css('max-width', '350px');

                    }
                    if (width < 460) {
                        $('.project-boxes.jsGridView .project-box-wrapper').css('width', '95%');
                        $('.project-boxes.jsGridView .card-erika').css('width', '95%').addClass('movil');

                    }
                    if (width > 460 && width < 660)
                        $('.project-boxes.jsGridView .project-box-wrapper').css('width', '90%')



                    $('.app-name').css('display', '')
                    if (width < 850) {
                        $('.project-boxes.jsGridView .project-box-wrapper').css('width', '70%')
                        $('.app-name').css('display', 'none')

                    }
                    if (width >= 850)
                        $('.project-boxes.jsGridView .project-box-wrapper').css('width', '60%');
                    if (width >= 1000 && width < 1280)
                        $('.project-boxes.jsGridView .project-box-wrapper').css('width', '45%');


                    if (width >= 1280 && width < 1500)
                        $('.project-boxes.jsGridView .project-box-wrapper').css('width', '35%')
                    if (width >= 1500 && width < 1700)
                        $('.project-boxes.jsGridView .project-box-wrapper').css('width', '33%')
                    if (width > 1700)
                        $('.project-boxes.jsGridView .project-box-wrapper').css('width', '23%')


                    if ($('body').width() <= 700) {

                        if (!$('main').data('colapsado')) {
                            $('.project-boxes.jsGridView .project-box-wrapper').css('width', '90%')
                            $('#menulateraladmin').addClass('collapse-menu')
                            $('section').addClass('collapse-menu');
                            $('main').removeClass('translate-x-[12.5rem]').addClass('translate-x-[3rem]');
                            $('main').data('float', true);
                        }
                    } else {
                        if (!$('main').data('colapsado')) {
                            $('#menulateraladmin').removeClass('collapse-menu')
                            $('section').removeClass('collapse-menu');
                            $('main').addClass('translate-x-[12.5rem]').removeClass('translate-x-[3rem]');
                            $('main').data('float', false);
                        }

                    }


                }

                window.animar = async function(target, anim, duration = 500) {
                    if (target == undefined)
                        return;
                    switch (anim) {
                        case 'opacidadOut':
                            target.style.opacity = '1'; // Set initial opacity to 0
                            anime({
                                targets: target,
                                opacity: 0, // desvanecimiento
                                duration: duration, // Duration of the animation in milliseconds
                                easing: 'easeOutQuad', // Easing function
                                complete: function() {
                                    target.style.pointerEvents = 'none';
                                    target.style.display = 'none';
                                }
                            });
                            break
                        case 'opacidadIn':
                            target.style.display = '';
                            target.style.opacity = '0'; // Set initial opacity to 0
                            anime({
                                targets: target,
                                opacity: 1, // desvanecimiento
                                duration: duration, // Duration of the animation in milliseconds
                                easing: 'easeOutQuad', // Easing function
                                complete: function() {
                                    target.style.pointerEvents = '';
                                }
                            });
                            break
                        case 'ocultar_derecha':
                            anime({
                                targets: target,
                                translateX: '100%', // mueve el elemento el 100% de su anchura
                                opacity: 0, // desvanecimiento
                                duration: duration, // Duration of the animation in milliseconds
                                easing: 'easeOutQuad', // Easing function
                                complete: function() {
                                    target.style.pointerEvents = 'none';
                                    target.style.display = 'none';
                                }
                            });
                            break
                        case 'ocultar_hacia_abajo':
                            anime({
                                targets: target,
                                translateY: '100%', // mueve el elemento el 100% de su anchura
                                opacity: 0, // desvanecimiento
                                duration: duration, // Duration of the animation in milliseconds
                                easing: 'easeInOutQuad', // Easing function
                                complete: function() {
                                    target.style.pointerEvents = 'none';
                                    target.style.display = 'none';
                                }
                            });
                            break
                        case 'ocultar_izquierda':
                            anime({
                                targets: target,
                                translateX: '-100%', // mueve el elemento el 100% de su anchura
                                opacity: 0, // desvanecimiento
                                duration: duration, // Duration of the animation in milliseconds
                                easing: 'easeInOutQuad', // Easing function
                                complete: function() {
                                    target.style.pointerEvents = 'none';
                                    target.style.display = 'none';
                                }
                            });
                            break;
                        case 'mostrar_izquierda':
                            target.style.display = ''; // Set display to block
                            target.style.opacity = '0'; // Set initial opacity to 0
                            anime({
                                targets: target,
                                translateX: '0%', // Move back to original position
                                opacity: 1, // Fade in
                                duration: duration, // Duration of the animation in milliseconds
                                easing: 'easeInQuad', // Easing function
                                complete: function() {
                                    target.style.pointerEvents = '';
                                }
                            });
                            break;
                        case 'mostrar_derecha':
                            target.style.display = ''; // Set display to block
                            target.style.opacity = '0'; // Set initial opacity to 0
                            anime({
                                targets: target,
                                translateX: '0%', // Move back to original position
                                opacity: 1, // Fade in
                                duration: duration, // Duration of the animation in milliseconds
                                easing: 'easeInOutQuad', // Easing function
                                complete: function() {
                                    target.style.pointerEvents = '';
                                }
                            });
                            break;
                        case 'mostrar_derecha_extra':
                            target.style.display = ''; // Set display to block
                            target.style.opacity = '0'; // Set initial opacity to 0
                            anime({
                                targets: target,
                                translateX: '-107%', // Move back to original position
                                opacity: 1, // Fade in
                                duration: duration, // Duration of the animation in milliseconds
                                easing: 'easeInOutQuad', // Easing function
                                complete: function() {
                                    target.style.pointerEvents = '';
                                }
                            });
                            break;
                        case 'mostrar_hacia_arriba':
                            target.style.display = ''; // Set display to block
                            target.style.opacity = '0'; // Set initial opacity to 0
                            anime({
                                targets: target,
                                translateY: '0%', // Move back to original position
                                opacity: 1, // Fade in
                                duration: duration, // Duration of the animation in milliseconds
                                easing: 'easeInOutQuad', // Easing function
                                complete: function() {
                                    target.style.pointerEvents = '';
                                }
                            });
                            break;
                    }
                }
                window.generarColor = function(opacidades) {
                    // Generar valores RGB aleatorios
                    const r = Math.floor(Math.random() * 128); // 0-127 para un tono más oscuro
                    const g = Math.floor(Math.random() * 128);
                    const b = Math.floor(Math.random() * 128);

                    // Convertir a hexadecimal
                    const rHex = r.toString(16).padStart(2, '0');
                    const gHex = g.toString(16).padStart(2, '0');
                    const bHex = b.toString(16).padStart(2, '0');

                    // Mapear cada opacidad a su correspondiente color ARGB
                    return opacidades.map(opacidad => {
                        // Asegurarse de que la opacidad esté en el rango de 0 a 1
                        opacidad = Math.max(0, Math.min(1, opacidad));

                        // Convertir la opacidad a formato hexadecimal (00 a FF)
                        const opacityHex = Math.round(opacidad * 255).toString(16).padStart(2, '0');

                        // Retornar el color en formato hexadecimal ARGB para cada opacidad
                        return `#${rHex}${gHex}${bHex}${opacityHex}`;
                    });
                }
                window.addCategoria = function(categoria) {
                    console.log(categoria)
                    let classStatus = 'green';
                    let textStatus = 'Activa';
                    if (!categoria.activo) {
                        textStatus = 'Inactiva';
                        classStatus = 'red';

                    }
                    return `<div
                    data-thumb="${categoria.foto}"
                    data-dominant-color="--card-bg"
                    data-dominant-oscurecer
                    data-background-image="${categoria.foto?.replaceAll('-thumb','')}"
                    id="categoria_${categoria.id_categoria}" class="lozad  lazy-background card-item project-box-wrapper animado categoria" style="--card-bg:;--card-bg-hover:;">
                            <div class="absolute left-5 top-5">
                                <span class="status mb-2 inline-flex items-center bg-${classStatus}-100 text-${classStatus}-800 dark:bg-${classStatus}-900 dark:text-${classStatus}-300 text-xs font-medium px-2.5 py-0.5 rounded-full " tabindex="-1">
                                <span class="w-2 h-2 me-1 bg-${classStatus}-500 rounded-full" tabindex="-1" id="erk_erk_2"></span>
                                ${textStatus}
                                </span>
                            </div>
                            <div class="item-desc">
                                <h3>${categoria.nombre}</h3>
                                <div class="project-box-footer">
                                    <div class="participants">
                                        <ul class="flex space-x-2 font-medium">
                                            <li>
                                                <span 
                                                onclick="editarCategoria(${categoria.id_categoria})"
                                                erika-tooltip
                                                tooltip-content="Editar Categoria"
                                                class="flex w-auto flex items-center p-2 rounded-full bg-[#03a9f4]/20 hover:bg-[#03a9f4]/50 w-min group  cursor-pointer">
                                                    <?php include './php/svg/editar.svg' ?>
                                                </span>
                                            </li>
                                            <li>
                                              
                                                <span 
                                                erika-tooltip
                                                tooltip-content="Eliminar Categoria"
                                                onclick="eliminarRegistros(${categoria.id_categoria},this,'eliminar_categoria','¿De verdad quiere eliminar esta categoria?','top',[50,50])" class="flex w-auto flex items-center p-2 rounded-full bg-[#c60e0e]/20 hover:bg-[#c60e0e]/50 w-min group  cursor-pointer">
                                                    <?php include './php/svg/eliminar.svg' ?>
                                                </span>
                                                  <span class="alertbox"></span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="days-left bg-[#000]/20 text-[orange]">
                                        en ${categoria.cursos} Cursos
                                    </div>
                                </div>
                            </div>
                        </div>`;
                }
                window.addCurso = function(curso) {
                    console.log("CURSO", curso)


                    const options = {
                        day: '2-digit',
                        month: 'short', // Esto te da 'oct'
                        year: 'numeric',
                        hour: 'numeric',
                        minute: 'numeric',
                        hour12: true
                    };

                    const curso_fecha_fin = new Intl.DateTimeFormat('es-ES', options).format(new Date(curso.curso_fecha_fin));
                    const curso_fecha_inicio = new Intl.DateTimeFormat('es-ES', options).format(new Date(curso.curso_fecha_inicio));



                    console.log(curso)
                    let ocultar =<?php if ($rol != 1) echo "true;"; else echo "false;" ?>
                    let estado = 'green';
                    if (curso.estado_code == 1)
                        estado = 'purple';
                    if (curso.estado_code == 3)
                        estado = 'gray';

                    //let clrs = generarColor([0.2, 0.5]);
                    return `<div class="${(ocultar && curso.estado_code==3)?'ocultar':''} project-box-wrapper animado" style="--delay: 0.2s">
                            <div class="project-box bg-[#000]/50 hover:bg-[#000]/80 rounded-xl ">
                                <div class="flex flex-col">
                                    <div><span class="status mb-2 inline-flex items-center bg-${estado}-100 text-${estado}-800 dark:bg-${estado}-900 dark:text-${estado}-300 text-xs font-medium px-2.5 py-0.5 rounded-full " tabindex="-1">
                        <span class="w-2 h-2 me-1 bg-${estado}-500 rounded-full" tabindex="-1"></span>
                ${curso.estado_curso}
                    </span></div>
                                    <span  class="text-white">Inicia:<b class="opacity-50">${curso_fecha_inicio}</b></span>
                                    <span  class="text-white">Finaliza:<b class="opacity-50">${curso_fecha_fin}</b></span>
                                </div>
                                <div class="mt-2 project-box-content-header">
                                    <div class="img-curso rounded-xl mb-1" style="--bg:url(../.${curso.curso_foto?.replaceAll('-thumb','')});">
                                        </div>
                                    <p onclick="verDetallesCurso(${curso.id_curso})" class="w-auto text-center box-content-header bold ">${curso.curso_nombre}</p>
                                    <p class="hidden box-content-subheader ">Sección "B"</p>
                                </div>
                                <div class="hidden box-progress-wrapper flex">
                                    <p class="box-progress-header text-white bold mr-2">Progeso</p>
                                    <div class="box-progress-bar">
                                        <span class="box-progress" style="width:0%; background-color: #00BCD4"></span>
                                    </div>
                                    <div class="flex ml-2 justify-end mb-1">
                                        <p class=" box-progress-percentage rounded rounded-lg bg-[#000]/50 w-min p-1">0%</p>
                                    </div>
                                </div>
                                <div class="project-box-footer">
                                    <div class="w-[2rem] h-[2rem] participants">
                                         <img
                                                erika-tooltip
                                                tooltip-placement="top"
                                                tooltip-content="<img style='max-width:200px;' class='rounded' src='../.${curso.usuario_foto?.replace('-thumb','')}' alt='${curso.instructor}'><div>${curso.instructor}</div>"
                                                class="cursor-pointer" src="../.${curso.usuario_foto}" alt="${curso.instructor}">
                                    </div>
                                    ${curso.estado_code!=3?`<span onclick="cargarAlumnosParaInscribir(${curso.id_curso})"
                                                erika-tooltip
                                                abrir-formulario
                                                data-formulario="nuevainscripcion"
                                                tooltip-content="Inscribir Estudiantes"
                                                class="ocultar justify-center flex w-[2rem] h-[2rem] flex items-center p-2 rounded-full bg-[#fff]/20 hover:bg-[#fff]/50  group  cursor-pointer">
                                                    <?php include './php/svg/add-1.svg' ?>
                                                </span>`:''}
                                    
                                      
                                    <div class="ocultar days-left bg-[#000]/20 text-[orange]">
                                        ${curso.inscritos} Inscritos
                                    </div>
                                     <div class="ocultar">
                                              
                                                <span 
                                                erika-tooltip
                                                tooltip-content="Eliminar curso"
                                                onclick="eliminarRegistros(${curso.id_curso},this,'eliminar_curso','¿De verdad quiere eliminar este curso?','top',[50,50])" class="flex w-auto flex items-center p-2 rounded-full bg-[#c60e0e]/20 hover:bg-[#c60e0e]/50 w-min group  cursor-pointer">
                                                    <?php include './php/svg/eliminar.svg' ?>
                                                </span>
                                                  <span class="alertbox"></span>
                                            </div>
                                    <div onclick="verDetallesCurso(${curso.id_curso})" class="cursor-pointer days-left bg-[#000]/20 text-[pink]">
                                       <?php if ($rol == 1) echo "Administrar";
                                        else echo "ir al curso" ?>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                }
                window.addMiCurso = function(curso) {

                    const curso_fecha_inicio = new Intl.DateTimeFormat('es-ES', {
                        month: 'long',
                        day: 'numeric',
                        year: 'numeric',
                        hour: 'numeric',
                        minute: 'numeric',
                        second: 'numeric',
                        hour12: true // Esto cambia a formato 12 horas
                    }).format(new Date(curso.curso_fecha_inicio));
                    const curso_fecha_fin = new Intl.DateTimeFormat('es-ES', {
                        month: 'long',
                        day: 'numeric',
                        year: 'numeric',
                        hour: 'numeric',
                        minute: 'numeric',
                        second: 'numeric',
                        hour12: true // Esto cambia a formato 12 horas
                    }).format(new Date(curso.curso_fecha_fin));
                    console.log(curso)
                    let estado = 'green';
                    if (curso.estado_code == 1)
                        estado = 'purple';
                    if (curso.estado_code == 3)
                        estado = 'gray';

                    //let clrs = generarColor([0.2, 0.5]);
                    return `<div class=" project-box-wrapper animado" style="--delay: 0.2s">
                                    <div class="project-box bg-[#000]/50 hover:bg-[#000]/80 rounded-xl ">
                                        <div class="flex flex-col text-[0.7rem]">
                                            <div><span class="status mb-2 inline-flex items-center bg-${estado}-100 text-${estado}-800 dark:bg-${estado}-900 dark:text-${estado}-300 text-xs font-medium px-2.5 py-0.5 rounded-full " tabindex="-1">
                                <span class="w-2 h-2 me-1 bg-${estado}-500 rounded-full" tabindex="-1"></span>
                            ${curso.estado_curso}
                            </span></div>
                                            <span  class="text-white">Inicia:<b class="opacity-50">${curso_fecha_inicio}</b></span>
                                            <span  class="text-white">Finaliza:<b class="opacity-50">${curso_fecha_fin}</b></span>
                                        </div>
                                        <div class="mt-2 project-box-content-header">
                                            <div class="img-curso rounded-xl mb-1" style="--bg:url(../.${curso.curso_foto?.replaceAll('-thumb','')});">
                                                </div>
                                            <p onclick="verDetallesCurso(${curso.id_curso})" class="w-auto text-center box-content-header bold ">${curso.curso_nombre}</p>
                                            <p class="hidden box-content-subheader ">Sección "B"</p>
                                        </div>
                                        <div class="noocultar box-progress-wrapper flex">
                                            <p class="box-progress-header text-white bold mr-2">Progeso</p>
                                            <div class="box-progress-bar">
                                                <span class="box-progress" style="width:0%; background-color: #00BCD4"></span>
                                            </div>
                                            <div class="flex ml-2 justify-end mb-1">
                                                <p class=" box-progress-percentage rounded rounded-lg bg-[#000]/50 w-min p-1">0%</p>
                                            </div>
                                        </div>
                                        <div class="project-box-footer">
                                            <div class="w-[2rem] h-[2rem] participants">
                                                <img
                                                erika-tooltip
                                                tooltip-content="<img style='max-width:200px;' class='rounded' src='../.${curso.usuario_foto?.replace('-thumb','')}' alt='${curso.instructor}'><div>${curso.instructor}</div>"
                                                class="cursor-pointer" src="../.${curso.usuario_foto}" alt="${curso.instructor}">
                                            </div>
                                            ${curso.estado_code!=3?`<span onclick="cargarAlumnosParaInscribir(${curso.id_curso})"
                                                        erika-tooltip
                                                        abrir-formulario
                                                        data-formulario="nuevainscripcion"
                                                        tooltip-content="Inscribir Estudiantes"
                                                        class="ocultar justify-center flex w-[2rem] h-[2rem] flex items-center p-2 rounded-full bg-[#fff]/20 hover:bg-[#fff]/50  group  cursor-pointer">
                                                            <?php include './php/svg/add-1.svg' ?>
                                                        </span>`:''}
                                            
                                            
                                            <div class="ocultar days-left bg-[#000]/20 text-[orange]">
                                                ${curso.inscritos} Inscritos
                                            </div>
                                            <div class="ocultar">
                                                    
                                                        <span 
                                                        erika-tooltip
                                                        tooltip-content="Eliminar curso"
                                                        onclick="eliminarRegistros(${curso.id_curso},this,'eliminar_curso','¿De verdad quiere eliminar este curso?','top',[50,50])" class="flex w-auto flex items-center p-2 rounded-full bg-[#c60e0e]/20 hover:bg-[#c60e0e]/50 w-min group  cursor-pointer">
                                                            <?php include './php/svg/eliminar.svg' ?>
                                                        </span>
                                                        <span class="alertbox"></span>
                                                    </div>
                                            <div onclick="verDetallesCurso(${curso.id_curso})" class="cursor-pointer days-left bg-[#000]/20 text-[pink]">
                                            <?php if ($rol == 1) echo "Administrar";
                                            else echo "ir al curso" ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                }
                window.addInstructor = function(instructor) {
                    if (instructor.fecha_registro == null || instructor.fecha_registro == "")
                        instructor.fecha_registro = "Nunca"
                    else
                        instructor.fecha_registro = window.tiempoTranscurrido(new Date(instructor.fecha_registro));
                    if (instructor.fecha_modificacion == null || instructor.fecha_modificacion == "")
                        instructor.fecha_modificacion = "Nunca"
                    else
                        instructor.fecha_modificacion = window.tiempoTranscurrido(new Date(instructor.fecha_modificacion));
                    if (instructor.ultimo_inicio_sesion == null || instructor.ultimo_inicio_sesion == "")
                        instructor.ultimo_inicio_sesion = "Nunca"
                    else
                        instructor.ultimo_inicio_sesion = window.tiempoTranscurrido(new Date(instructor.ultimo_inicio_sesion));
                    let classStatus = 'red';
                    let textStatus = 'Activo';
                    if (!instructor.activo)
                        textStatus = 'Inactivo';
                    if (instructor.bloqueado)
                        textStatus = 'Bloqueado';
                    if (instructor.activo && !instructor.bloqueado)
                        classStatus = 'green';
                    return `<div class="card-erika">
                    <div class="user-additional">
                    <div class="user-card-erika">
                    <span class="status mb-2 inline-flex items-center bg-${classStatus}-100 text-${classStatus}-800 dark:bg-${classStatus}-900 dark:text-${classStatus}-300 text-xs font-medium px-2.5 py-0.5 rounded-full " tabindex="-1">
                        <span class="w-2 h-2 me-1 bg-${classStatus}-500 rounded-full" tabindex="-1"></span>
                        ${textStatus}
                    </span>
                    <div class="account-profile" tabindex="-1">
                        <img src="${instructor.foto}"  data-src="${instructor.foto.replaceAll('-thumb','')}" alt="" tabindex="1" class="lozad lazy-img cursor-pointer">
                    </div>
                    <div class="user-points mt-2">
                        en ${instructor.cursos} cursos
                    </div>
                    </div>
                    <div class="user-more-info">
                    <div class="user-action">
                        <div class="flex flex-col">
                        <div>
                        <span onclick="eliminarRegistros(${instructor.id_usuario},this,'eliminar_usuario','¿De verdad quiere eliminar este instructor?','right',[-20,10])" class="flex mr-2 flex items-center p-2 rounded-full bg-[#c60e0e]/20 hover:bg-[#c60e0e]/50 w-10 h-10 group  cursor-pointer"
                            erika-tooltip
                            tooltip-content="Eliminar Instructor">
                        
                            <?php include './php/svg/eliminar.svg' ?>
                        </span><span class="alertbox"></span>
                        </div>

                        <span onclick="editarInstructor(${instructor.id_usuario})" class="flex mr-2  flex items-center p-2 rounded-full bg-[#03a9f4]/20 hover:bg-[#03a9f4]/50 w-10 h-10 group  cursor-pointer"
                            erika-tooltip
                            tooltip-content="Editar Instructor">
                            <?php include './php/svg/editar.svg' ?>
                        </span>
                        </div>
                        <div class="flex flex-col">
                        <div class="switchContainer">
                            <label tooltip-content="Bloquear/Desbloquear" tooltip-placement="top" erika-tooltip tooltip-offset="[80,20]" class="toggleSwitch">
                            <input class="checkbox" type="checkbox" onchange="activar_desactivar_bloquear_desbloquear_usuario(${instructor.id_usuario},'bloquear_desbloquear',this)" ${instructor.bloqueado?'checked':''} />
                            <div class="slider" style="--swt_on:var(--lock_on);--swt_off:var(--lock_off);--swt_loading:var(--spinner);">
                                <span><?php include './php/svg/lock-off.svg' ?></span>
                                <span><?php include './php/svg/lock-on.svg' ?></span>
                            </div>
                            </label>
                        </div>
                        <div class="switchContainer">
                            <label class="toggleSwitch">
                            <input class="checkbox" type="checkbox" ${instructor.activo?'':'checked'} onchange="activar_desactivar_bloquear_desbloquear_usuario(${instructor.id_usuario},'activar_desactivar',this)" />
                            <div class="slider" style="--swt_on:'Activo'; --swt_off:'Inactivo';--swt_loading:var(--spinner);">
                                <span>Activo</span>
                                <span>Inactivo</span>
                            </div>
                            </label>
                        </div>
                        </div>

                            </div>
                            </div>
                        </div>
                        <div class="user-general">
                            <h1 class="w-max text-sm md:text-lg lg:text-xl">${instructor.nombres} ${instructor.apellidos}
                            <hr class="w-full opacity-1/2 mb-2">
                            </h1>

                            <span class="flex">
                            <span class="mr-2" erika-tooltip tooltip-content="Correo Electrónico">
                                <?php include './php/svg/email.svg' ?>
                            </span><b>${instructor.correo}</b>
                            </span>

                            <span class="flex mb-1"><span class="mr-2" erika-tooltip tooltip-content="Fecha de registro"><?php include './php/svg/reloj.svg' ?></span class="ml-2">Registrado: <b class="ml-2">${instructor.fecha_registro}</b></span>

                            <span class="flex mb-1"><span class="mr-2" erika-tooltip tooltip-content="Correo Electrónico"><?php include './php/svg/reloj-1.svg' ?></span class="ml-2">Modificado: <b class="ml-2">${instructor.fecha_modificacion}</b></span>

                            <span class="flex mb-1"><span class="mr-2" erika-tooltip tooltip-content="Correo Electrónico"><?php include './php/svg/reloj-1.svg' ?></span>Ultimo inicio: <b class="ml-2">${instructor.ultimo_inicio_sesion}</b></span>

                            <span class="flex mb-1"><span class="mr-2" erika-tooltip tooltip-content="Correo Electrónico">
                            <span class="${instructor.sexo=="F"?"hidden":""}"><?php include './php/svg/sexo-m.svg' ?></span>
                            <span class="${instructor.sexo=="F"?"":"hidden"}"><?php include './php/svg/sexo-f.svg' ?></span>
                            </span>Sexo: <b class="ml-2">${instructor.sexo=="F"?"Femenino":"Masculino"}</b></span>
                        </div>
                        </div>`;
                }
                window.addEstudiante = function(estudiante) {
                    if (estudiante.fecha_registro == null || estudiante.fecha_registro == "")
                        estudiante.fecha_registro = "Nunca"
                    else
                        estudiante.fecha_registro = new Intl.DateTimeFormat('es-ES', {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric',
                            hour: 'numeric',
                            minute: 'numeric',
                            second: 'numeric',
                            hour12: true // Esto cambia a formato 12 horas
                        }).format(new Date(estudiante.fecha_registro));
                    if (estudiante.fecha_modificacion == null || estudiante.fecha_modificacion == "")
                        estudiante.fecha_modificacion = "Nunca"
                    else
                        estudiante.fecha_modificacion = new Intl.DateTimeFormat('es-ES', {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric',
                            hour: 'numeric',
                            minute: 'numeric',
                            second: 'numeric',
                            hour12: true // Esto cambia a formato 12 horas
                        }).format(new Date(estudiante.fecha_modificacion));
                    if (estudiante.ultimo_inicio_sesion == null || estudiante.ultimo_inicio_sesion == "")
                        estudiante.ultimo_inicio_sesion = "Nunca"
                    else estudiante.ultimo_inicio_sesion = new Intl.DateTimeFormat('es-ES', {
                        month: 'long',
                        day: 'numeric',
                        year: 'numeric',
                        hour: 'numeric',
                        minute: 'numeric',
                        second: 'numeric',
                        hour12: true // Esto cambia a formato 12 horas
                    }).format(new Date(estudiante.ultimo_inicio_sesion));
                    let classStatus = 'red';
                    let textStatus = 'Activo';
                    if (!estudiante.activo)
                        textStatus = 'Inactivo';
                    if (estudiante.bloqueado)
                        textStatus = 'Bloqueado';
                    if (estudiante.activo && !estudiante.bloqueado)
                        classStatus = 'green';
                    return `<div class="card-erika">
                    <div class="user-additional">
                    <div class="user-card-erika">
                    <span class="status mb-2 inline-flex items-center bg-${classStatus}-100 text-${classStatus}-800 dark:bg-${classStatus}-900 dark:text-${classStatus}-300 text-xs font-medium px-2.5 py-0.5 rounded-full " tabindex="-1">
                        <span class="w-2 h-2 me-1 bg-${classStatus}-500 rounded-full" tabindex="-1"></span>
                        ${textStatus}
                    </span>
                    <div class="account-profile" tabindex="-1">
                        <img src="${estudiante.foto}" data-src="${estudiante.foto.replaceAll('-thumb','')}" alt="" tabindex="1" class="lazy-img cursor-pointer">
                    </div>
                    <div class="user-points mt-2">
                        en ${estudiante.cursos} cursos
                    </div>
                    </div>
                    <div class="user-more-info">
                    <div class="user-action">
                        <div class="flex flex-col">
                        <div>
                        <span onclick="eliminarRegistros(${estudiante.id_usuario},this,'eliminar_usuario','¿De verdad quiere eliminar este estudiante?','right',[-20,10])" class="flex mr-2 flex items-center p-2 rounded-full bg-[#c60e0e]/20 hover:bg-[#c60e0e]/50 w-10 h-10 group  cursor-pointer"
                            erika-tooltip
                            tooltip-content="Eliminar estudiante">
                        
                            <?php include './php/svg/eliminar.svg' ?>
                        </span><span class="alertbox"></span>
                        </div>

                        <span onclick="editarEstudiante(${estudiante.id_usuario})" class="flex mr-2  flex items-center p-2 rounded-full bg-[#03a9f4]/20 hover:bg-[#03a9f4]/50 w-10 h-10 group  cursor-pointer"
                            erika-tooltip
                            tooltip-content="Editar estudiante">
                            <?php include './php/svg/editar.svg' ?>
                        </span>
                        </div>
                        <div class="flex flex-col">
                        <div class="switchContainer">
                            <label tooltip-content="Bloquear/Desbloquear" tooltip-placement="top" erika-tooltip tooltip-offset="[80,20]" class="toggleSwitch">
                            <input class="checkbox" type="checkbox" onchange="activar_desactivar_bloquear_desbloquear_usuario(${estudiante.id_usuario},'bloquear_desbloquear',this)" ${estudiante.bloqueado?'checked':''} />
                            <div class="slider" style="--swt_on:var(--lock_on);--swt_off:var(--lock_off);--swt_loading:var(--spinner);">
                                <span><?php include './php/svg/lock-off.svg' ?></span>
                                <span><?php include './php/svg/lock-on.svg' ?></span>
                            </div>
                            </label>
                        </div>
                        <div class="switchContainer">
                            <label class="toggleSwitch">
                            <input class="checkbox" type="checkbox" ${estudiante.activo?'':'checked'} onchange="activar_desactivar_bloquear_desbloquear_usuario(${estudiante.id_usuario},'activar_desactivar',this)" />
                            <div class="slider" style="--swt_on:'Activo'; --swt_off:'Inactivo';--swt_loading:var(--spinner);">
                                <span>Activo</span>
                                <span>Inactivo</span>
                            </div>
                            </label>
                        </div>
                        </div>

                    </div>
                    </div>
                    </div>
                    <div class="user-general">
                    <h1 class="w-max text-sm md:text-lg lg:text-xl">${estudiante.nombres} ${estudiante.apellidos}
                    <hr class="w-full opacity-1/2 mb-2">
                    </h1>

                    <span class="flex">
                    <span class="mr-2" erika-tooltip tooltip-content="Correo Electrónico">
                        <?php include './php/svg/email.svg' ?>
                    </span><b>${estudiante.correo}</b>
                    </span>

                    <span class="flex mb-1"><span class="mr-2" erika-tooltip tooltip-content="Fecha de registro"><?php include './php/svg/reloj.svg' ?></span class="ml-2">Registrado: <b class="ml-2">${estudiante.fecha_registro}</b></span>

                    <span class="flex mb-1"><span class="mr-2" erika-tooltip tooltip-content="Correo Electrónico"><?php include './php/svg/reloj-1.svg' ?></span class="ml-2">Modificado: <b class="ml-2">${estudiante.fecha_modificacion}</b></span>

                    <span class="flex mb-1"><span class="mr-2" erika-tooltip tooltip-content="Correo Electrónico"><?php include './php/svg/reloj-1.svg' ?></span>Ultimo inicio: <b class="ml-2">${estudiante.ultimo_inicio_sesion}</b></span>

                    <span class="flex mb-1"><span class="mr-2" erika-tooltip tooltip-content="Correo Electrónico">
                    <span class="${estudiante.sexo=="F"?"hidden":""}"><?php include './php/svg/sexo-m.svg' ?></span>
                    <span class="${estudiante.sexo=="F"?"":"hidden"}"><?php include './php/svg/sexo-f.svg' ?></span>
                    </span>Sexo: <b class="ml-2">${estudiante.sexo=="F"?"Femenino":"Masculino"}</b></span>
                    </div>
                    </div>`;
                }
                window.addUsuario = function(usuario) {
                    if (usuario.fecha_registro == null || usuario.fecha_registro == "")
                        usuario.fecha_registro = "Nunca"
                    else
                        usuario.fecha_registro = tiempoTranscurrido(new Date(usuario.fecha_registro))
                    if (usuario.fecha_modificacion == null || usuario.fecha_modificacion == "")
                        usuario.fecha_modificacion = "Nunca"
                    else
                        usuario.fecha_modificacion = tiempoTranscurrido(new Date(usuario.fecha_modificacion))
                    if (usuario.ultimo_inicio_sesion == null || usuario.ultimo_inicio_sesion == "")
                        usuario.ultimo_inicio_sesion = "Nunca"
                    else
                        usuario.ultimo_inicio_sesion = tiempoTranscurrido(new Date(usuario.ultimo_inicio_sesion))
                    let classStatus = 'red';
                    let textStatus = 'Activo';
                    if (!usuario.activo)
                        textStatus = 'Inactivo';
                    if (usuario.bloqueado)
                        textStatus = 'Bloqueado';
                    if (usuario.activo && !usuario.bloqueado)
                        classStatus = 'green';
                    return `<div class="card-erika">
                    <div class="user-additional">
                    <div class="user-card-erika">
                    <span class="status mb-2 inline-flex items-center bg-${classStatus}-100 text-${classStatus}-800 dark:bg-${classStatus}-900 dark:text-${classStatus}-300 text-xs font-medium px-2.5 py-0.5 rounded-full " tabindex="-1">
                        <span class="w-2 h-2 me-1 bg-${classStatus}-500 rounded-full" tabindex="-1"></span>
                        ${textStatus}
                    </span>
                    <span class="status mb-2 inline-flex items-center bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded-full " tabindex="-1">
                        ${usuario.rol}
                    </span>
                    <div class="account-profile" tabindex="-1">
                        <img src="${usuario.foto}"  data-src="${usuario.foto.replaceAll('-thumb','')}" alt="" tabindex="1" class="lozad lazy-img cursor-pointer">
                    </div>
                    <div class="user-points mt-2">
                        en ${usuario.cursos} cursos
                    </div>
                    </div>
                    <div class="user-more-info">
                    <div class="user-action">
                        <div class="flex flex-col">
                        <div>
                        <span onclick="eliminarRegistros(${usuario.id_usuario},this,'eliminar_usuario','¿De verdad quiere eliminar este usuario?','right',[-20,10])" class="flex mr-2 flex items-center p-2 rounded-full bg-[#c60e0e]/20 hover:bg-[#c60e0e]/50 w-10 h-10 group  cursor-pointer"
                            erika-tooltip
                            tooltip-content="Eliminar usuario">
                        
                            <?php include './php/svg/eliminar.svg' ?>
                        </span><span class="alertbox"></span>
                        </div>

                        <span onclick="editarUsuario(${usuario.id_usuario})" class="flex mr-2  flex items-center p-2 rounded-full bg-[#03a9f4]/20 hover:bg-[#03a9f4]/50 w-10 h-10 group  cursor-pointer"
                            erika-tooltip
                            tooltip-content="Editar usuario">
                            <?php include './php/svg/editar.svg' ?>
                        </span>
                        </div>
                        <div class="flex flex-col">
                        <div class="switchContainer">
                            <label tooltip-content="Bloquear/Desbloquear" tooltip-placement="top" erika-tooltip tooltip-offset="[80,20]" class="toggleSwitch">
                            <input class="checkbox" type="checkbox" onchange="activar_desactivar_bloquear_desbloquear_usuario(${usuario.id_usuario},'bloquear_desbloquear',this)" ${usuario.bloqueado?'checked':''} />
                            <div class="slider" style="--swt_on:var(--lock_on);--swt_off:var(--lock_off);--swt_loading:var(--spinner);">
                                <span><?php include './php/svg/lock-off.svg' ?></span>
                                <span><?php include './php/svg/lock-on.svg' ?></span>
                            </div>
                            </label>
                        </div>
                        <div class="switchContainer">
                            <label class="toggleSwitch">
                            <input class="checkbox" type="checkbox" ${usuario.activo?'':'checked'} onchange="activar_desactivar_bloquear_desbloquear_usuario(${usuario.id_usuario},'activar_desactivar',this)" />
                            <div class="slider" style="--swt_on:'Activo'; --swt_off:'Inactivo';--swt_loading:var(--spinner);">
                                <span>Activo</span>
                                <span>Inactivo</span>
                            </div>
                            </label>
                        </div>
                        </div>

                    </div>
                    </div>
                    </div>
                    <div class="user-general">
                    <h1 class="w-max text-sm md:text-lg lg:text-xl">${usuario.nombres} ${usuario.apellidos}
                    <hr class="w-full opacity-1/2 mb-2">
                    </h1>

                    <span class="flex">
                    <span class="mr-2" erika-tooltip tooltip-content="Correo Electrónico">
                        <?php include './php/svg/email.svg' ?>
                    </span><b>${usuario.correo}</b>
                    </span>

                    <span class="flex mb-1"><span class="mr-2" erika-tooltip tooltip-content="Fecha de registro"><?php include './php/svg/reloj.svg' ?></span class="ml-2">Registrado: <b class="ml-2">${usuario.fecha_registro}</b></span>

                    <span class="flex mb-1"><span class="mr-2" erika-tooltip tooltip-content="Correo Electrónico"><?php include './php/svg/reloj-1.svg' ?></span class="ml-2">Modificado: <b class="ml-2">${usuario.fecha_modificacion}</b></span>

                    <span class="flex mb-1"><span class="mr-2" erika-tooltip tooltip-content="Correo Electrónico"><?php include './php/svg/reloj-1.svg' ?></span>Ultimo inicio: <b class="ml-2">${usuario.ultimo_inicio_sesion}</b></span>
                    <span class="flex mb-1"><span class="mr-2" erika-tooltip tooltip-content="Correo Electrónico">
                    <span class="${usuario.sexo=="F"?"hidden":""}"><?php include './php/svg/sexo-m.svg' ?></span>
                    <span class="${usuario.sexo=="F"?"":"hidden"}"><?php include './php/svg/sexo-f.svg' ?></span>
                    </span>Sexo: <b class="ml-2">${usuario.sexo=="F"?"Femenino":"Masculino"}</b></span>
                    </div>
                    </div>`;
                }
                window.globalCallbak = function(c) {
                    if (c) {
                        c();
                        return
                    }
                    $('#menulateraladmin .tab.active').trigger('click');
                };
                window.localParams = function(p, v) {

                    if (!localStorage.getItem('extra_parametros')) {
                        localStorage.setItem('extra_parametros', '[]')
                    }
                    let params = JSON.parse(localStorage.getItem('extra_parametros'));
                    if (params) {
                        params.push({
                            p: p,
                            v: v
                        });
                        localStorage.setItem('extra_parametros', JSON.stringify(params))
                    }
                }
                window.eventosTippy = function(btn, datos, accion, placement, offset) {
                    btn.style.pointerEvents = 'none'
                    btn._tippy?.disable();
                    let tippyTooltip;
                    let span = document.createElement('SPAN');
                    span.innerHTML = `<span class="alertmsg">${datos.msg}</span><br>`;


                    let div = document.createElement('DIV');
                    div.className = 'flex btns mt-5 mb-2'



                    span.appendChild(div);
                    let btn1 = document.createElement('BUTTON');
                    btn1.className = 'ok relative btn ml-3 w-full text-white bg-[#ec4899]/50 hover:bg-[#ec4899]/90 font-medium rounded-lg text-sm px-2 py-2 text-center';

                    btn1.innerText = datos.btn1;


                    let btn2 = document.createElement('BUTTON');
                    btn2.className = 'dont ml-3 w-full text-white bg-[#2196f3]/50 hover:bg-[#2196f3]/90 font-medium rounded-lg text-sm px-2 py-2 text-center';
                    btn2.innerText = datos.btn2;
                    div.appendChild(btn1)
                    div.appendChild(btn2)

                    $(btn).data('clear', setTimeout(() => {
                        tippyTooltip.destroy();
                        btn._tippy?.enable();
                        btn.style.pointerEvents = ''
                    }, 10000));


                    btn1.addEventListener('click', function(event) {
                        event.stopPropagation();
                        $(this).addClass('desactivado');
                        $(this).addClass('loading');
                        clearTimeout($(btn).data('clear'));
                        let formData = new FormData();
                        formData.append('a', datos.a)
                        datos.data.forEach(dato => {
                            formData.append(dato.p, dato.v)
                        })
                        let extra_parametros = localStorage.getItem('extra_parametros');
                        if (extra_parametros) {
                            extra_parametros = JSON.parse(extra_parametros);
                            for (let i = 0; i < extra_parametros.length; i++) {
                                formData.append(extra_parametros[i].p, extra_parametros[i].v)
                            }
                            localStorage.removeItem('extra_parametros')
                        }
                        $.ajax({
                                url: './php/db/post.php',
                                method: 'POST',
                                data: formData,
                                processData: false, // Prevent jQuery from automatically transforming the data into a query string
                                contentType: false, // Prevent jQuery from overriding the Content-Type header
                                timeout: 5000 // Set the timeout in milliseconds (5000 ms = 5 seconds)
                            })
                            .done(function(data) {
                                btn1.remove();
                                span.querySelector('.alertmsg').innerHTML = data.msg;
                                if (data.error) {
                                    $(this).removeClass('desactivado');
                                } else {
                                    btn2.addEventListener('click', function() {
                                        btn._tippy?.enable();
                                        btn.style.pointerEvents = '';
                                        window.globalCallbak();
                                    })
                                }

                            })
                            .fail(function(jqXHR, textStatus, errorThrown) {
                                btn.style.pointerEvents = '';
                                btn1.remove();
                                span.querySelector('.alertmsg').innerHTML = 'Ocurrio un error, vuelva a intentar';
                                btn._tippy?.enable();
                                if (textStatus === 'timeout') {
                                    console.error('Request timed out');
                                } else {
                                    console.error('Error:', errorThrown);
                                }
                            });

                    })
                    btn2.addEventListener('click', function(event) {
                        btn.style.pointerEvents = '';
                        event.stopPropagation();
                        btn._tippy?.enable();
                        setTimeout(() => {
                            tippyTooltip.destroy();
                        }, 10);
                    })
                    tippyTooltip = tippy($(btn).next()[0], {
                        content: span,
                        interactive: true,
                        trigger: 'manual',
                        touch: false,
                        placement: placement,
                        offset: offset,
                        allowHTML: true,
                        onHidden(instance) {
                              btn.style.pointerEvents = ''
                          }
                    });
                    tippyTooltip.show();
                }
                window.eliminarRegistros = function(quien, btn, que_registro, mensaje, placement, offset) {
                    btn.style.pointerEvents = 'none'
                    btn._tippy?.disable();
                    let tippyTooltip;

                    let span = document.createElement('SPAN');
                    span.innerHTML = `<span class="alertmsg">${mensaje}</span><br>`;
                    let div = document.createElement('DIV');
                    div.className = 'flex btns mt-5 mb-2'
                    span.appendChild(div);
                    let btn1 = document.createElement('BUTTON');
                    btn1.className = 'ok relative btn ml-3 w-full text-white bg-[#ec4899]/50 hover:bg-[#ec4899]/90 font-medium rounded-lg text-sm px-2 py-2 text-center';
                    btn1.innerText = 'Eliminar';
                    let btn2 = document.createElement('BUTTON');
                    btn2.className = 'dont ml-3 w-full text-white bg-[#2196f3]/50 hover:bg-[#2196f3]/90 font-medium rounded-lg text-sm px-2 py-2 text-center';
                    btn2.innerText = 'Cancelar';
                    div.appendChild(btn1)
                    div.appendChild(btn2)

                    $(btn).data('clear', setTimeout(() => {
                        tippyTooltip.destroy();
                        btn._tippy?.enable();
                        btn.style.pointerEvents = ''
                    }, 10000));


                    btn1.addEventListener('click', function(event) {
                        event.stopPropagation();
                        $(this).addClass('desactivado');
                        $(this).addClass('loading');
                        clearTimeout($(btn).data('clear'));
                        let formData = new FormData();
                        formData.append('a', que_registro)
                        formData.append('id', quien)
                        let extra_parametros = localStorage.getItem('extra_parametros');
                        if (extra_parametros) {
                            extra_parametros = JSON.parse(extra_parametros);
                            for (let i = 0; i < extra_parametros.length; i++) {
                                formData.append(extra_parametros[i].p, extra_parametros[i].v)
                            }
                            localStorage.removeItem('extra_parametros')
                        }
                        $.ajax({
                                url: './php/db/post.php',
                                method: 'POST',
                                data: formData,
                                processData: false, // Prevent jQuery from automatically transforming the data into a query string
                                contentType: false, // Prevent jQuery from overriding the Content-Type header
                                timeout: 5000 // Set the timeout in milliseconds (5000 ms = 5 seconds)
                            })
                            .done(function(data) {
                                btn1.remove();
                                span.querySelector('.alertmsg').innerHTML = data.msg;
                                if (data.error) {
                                    $(this).removeClass('desactivado');
                                } else {
                                    btn2.addEventListener('click', function() {
                                        btn._tippy?.enable();
                                        btn.style.pointerEvents = '';
                                        window.globalCallbak();
                                    })
                                }

                            })
                            .fail(function(jqXHR, textStatus, errorThrown) {
                                btn1.remove();
                                span.querySelector('.alertmsg').innerHTML = 'Ocurrio un error, vuelva a intentar';
                                btn._tippy?.enable();
                                btn.style.pointerEvents = '';
                                if (textStatus === 'timeout') {
                                    console.error('Request timed out');
                                } else {
                                    console.error('Error:', errorThrown);
                                }
                            });

                    })
                    btn2.addEventListener('click', function(event) {
                        event.stopPropagation();
                        btn.style.pointerEvents = '';
                        btn._tippy?.enable();
                        setTimeout(() => {
                            tippyTooltip.destroy();
                        }, 10);
                    })
                    tippyTooltip = tippy($(btn).next()[0], {
                        content: span,
                        interactive: true,
                        trigger: 'manual',
                        touch: false,
                        placement: placement,
                        offset: offset,
                        allowHTML: true,
                    });
                    tippyTooltip.show();
                }
                window.actualizarRegistros = function(quien, btn, que_registro, mensaje, placement, offset) {
                    btn.style.pointerEvents = 'none'
                    btn._tippy.disable();
                    let tippyTooltip;

                    let span = document.createElement('SPAN');
                    span.innerHTML = `<span class="alertmsg">${mensaje}</span><br>`;
                    let div = document.createElement('DIV');
                    div.className = 'flex btns mt-5 mb-2'
                    span.appendChild(div);
                    let btn1 = document.createElement('BUTTON');
                    btn1.className = 'ok relative btn ml-3 w-full text-white bg-[#ec4899]/50 hover:bg-[#ec4899]/90 font-medium rounded-lg text-sm px-2 py-2 text-center';
                    btn1.innerText = 'Eliminar';
                    let btn2 = document.createElement('BUTTON');
                    btn2.className = 'dont ml-3 w-full text-white bg-[#2196f3]/50 hover:bg-[#2196f3]/90 font-medium rounded-lg text-sm px-2 py-2 text-center';
                    btn2.innerText = 'Cancelar';
                    div.appendChild(btn1)
                    div.appendChild(btn2)

                    $(btn).data('clear', setTimeout(() => {
                        tippyTooltip.destroy();
                        btn._tippy?.enable();
                        btn.style.pointerEvents = ''
                    }, 10000));


                    btn1.addEventListener('click', function(event) {
                        event.stopPropagation();
                        $(this).addClass('desactivado');
                        $(this).addClass('loading');
                        clearTimeout($(btn).data('clear'));
                        let formData = new FormData();
                        formData.append('a', que_registro)
                        formData.append('id', quien)
                        $.ajax({
                                url: './php/db/post.php',
                                method: 'POST',
                                data: formData,
                                processData: false, // Prevent jQuery from automatically transforming the data into a query string
                                contentType: false, // Prevent jQuery from overriding the Content-Type header
                                timeout: 5000 // Set the timeout in milliseconds (5000 ms = 5 seconds)
                            })
                            .done(function(data) {
                                btn1.remove();
                                span.querySelector('.alertmsg').innerHTML = data.msg;
                                if (data.error) {
                                    $(this).removeClass('desactivado');
                                } else {
                                    btn2.addEventListener('click', function() {
                                        btn._tippy?.enable();
                                        btn.style.pointerEvents = '';
                                        $('#menulateraladmin .tab.active').trigger('click');
                                    })
                                }

                            })
                            .fail(function(jqXHR, textStatus, errorThrown) {
                                btn1.remove();
                                span.querySelector('.alertmsg').innerHTML = 'Ocurrio un error, vuelva a intentar';
                                btn._tippy?.enable();
                                btn.style.pointerEvents = '';
                                if (textStatus === 'timeout') {
                                    console.error('Request timed out');
                                } else {
                                    console.error('Error:', errorThrown);
                                }
                            });

                    })
                    btn2.addEventListener('click', function(event) {
                        event.stopPropagation();
                        btn.style.pointerEvents = '';
                        btn._tippy?.enable();
                        setTimeout(() => {
                            tippyTooltip.destroy();
                        }, 10);
                    })
                    tippyTooltip = tippy($(btn).next()[0], {
                        content: span,
                        interactive: true,
                        trigger: 'manual',
                        touch: false,
                        placement: placement,
                        offset: offset,
                        allowHTML: true,
                    });
                    tippyTooltip.show();
                }
                window.activar_desactivar_bloquear_desbloquear_usuario = function(quien, que, btn) {
                    $(btn).closest('.toggleSwitch ').addClass('loading');
                    $(btn).closest('.card-erika').addClass('loading');

                    let formData = new FormData();
                    //que es el parametro que indica al servidor que realizar si una activacion o bloqueo se esperan valores como activar_desactivar y bloquear_desbloquear
                    //btn es el boton que triggea la funcion se espera que sea un checkbok 
                    formData.append('a', que)
                    formData.append('id_usuario', quien)
                    if (que == "bloquear_desbloquear")
                        formData.append('estado', btn.checked)
                    else
                        formData.append('estado', !btn.checked) //hay que invertir el valor poque cuando es true hay que desactivar el usuario y cuando es falso hay que activarlo 
                    // Assuming `btn` and `formData` are already defined
                    $.ajax({
                            url: './php/db/post.php',
                            method: 'POST',
                            data: formData,
                            processData: false, // Prevent jQuery from automatically transforming the data into a query string
                            contentType: false, // Prevent jQuery from overriding the Content-Type header
                            timeout: 5000 // Set the timeout in milliseconds (5000 ms = 5 seconds)
                        })
                        .done(function(data) {
                            $(btn).closest('.toggleSwitch').removeClass('loading');
                            $(btn).closest('.card-erika').removeClass('loading');
                            if (data.error) {
                                btn.checked = !btn.checked;
                            }
                            $('#menulateraladmin .tab.active').trigger('click');
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            $(btn).closest('.toggleSwitch').removeClass('loading');
                            $(btn).closest('.card-erika').removeClass('loading');
                            btn.checked = !btn.checked;

                            if (textStatus === 'timeout') {
                                console.error('Request timed out');
                            } else {
                                console.error('Error:', errorThrown);
                            }
                        });

                    console.log(btn)
                }


     
                window.addMensaje = function(Mensaje) {
                    let sc = 'red';;
                    let st = 'Desconectado';
                    if (Mensaje.conectado) {
                        sc = 'green';
                        st = 'Conectado';
                    }
                    return `<li onclick="abrirChat('${Mensaje.id_contacto}')" erk-conn="${Mensaje.id_contacto}" class="chat-item py-3 sm:py-4" tabindex="-1">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse" tabindex="-1">
                    <div class="flex-shrink-0" tabindex="-1">
                        <img class="w-8 h-8 rounded-full lazy-img" src="${Mensaje.foto?Mensaje.foto:'./assets/media/img/site/user.png'}"  data-src="${Mensaje.foto?Mensaje.foto.replace('-thumb',''):'./assets/media/img/site/user.png'}"alt="Neil image" tabindex="-1">
                    </div>
                    <div class="flex-1 min-w-0" tabindex="-1">
                        <p class="text-sm font-semibold  truncate dark:text-white" tabindex="-1">
                        ${Mensaje.destinatario}
                        </p>
                        <p class="text-sm  truncate text-gray-400" tabindex="-1">
                        ${Mensaje.mensaje}
                        </p>
                    </div>
                        <span class="status inline-flex items-center bg-${sc}-100 text-${sc}-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-${sc}-900 dark:text-${sc}-300" tabindex="-1">
                            <span class="w-2 h-2 me-1 bg-${sc}-500 rounded-full" tabindex="-1" id="erk_${Mensaje.id_contacto}"></span>
                            ${st}
                        </span>
                        </div>
                    </li>`
                }
                window.addContacto = function(contacto) {
                    let sc = 'red';;
                    let st = 'Desconectado';
                    if (contacto.conectado) {
                        sc = 'green';
                        st = 'Conectado';
                    }
                    return `<li onclick="abrirChatnoContacto('${contacto.id_amigo}')" erk-conn="${contacto.id_amigo}" class="chat-item py-3 sm:py-4" tabindex="-1">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse" tabindex="-1">
                    <div class="flex-shrink-0" tabindex="-1">
                        <img class="w-8 h-8 rounded-full lazy-img" src="${contacto.foto?contacto.foto:'./assets/media/img/site/user.png'}"  data-src="${contacto.foto?contacto.foto.replace('-thumb',''):'./assets/media/img/site/user.png'}"alt="Neil image" tabindex="-1">
                    </div>
                    <div class="flex-1 min-w-0" tabindex="-1">
                        <p class="text-sm font-semibold  truncate dark:text-white" tabindex="-1">
                        ${contacto.contacto}
                        </p>
                        
                    </div>
                        <span class="status inline-flex items-center bg-${sc}-100 text-${sc}-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-${sc}-900 dark:text-${sc}-300" tabindex="-1">
                            <span class="w-2 h-2 me-1 bg-${sc}-500 rounded-full" tabindex="-1" id="erk_${contacto.id_usuario}"></span>
                            ${st}
                        </span>
                        </div>
                    </li>`
                }
                window.addmensajeLista = function(mensaje) {
                    let dq = 'flex-row-reverse';
                    let dq1 = 'justify-start flex-row-reverse';
                    if (mensaje.id_emisor == window.usuario) {
                        dq1 = dq = '';
                    }
                    console.log(mensaje);
                    return `<li dir="rtl" tabindex="-1">
              <div class="flex mt-3 ${dq}  items-start gap-2.5" tabindex="-1">
                <img class="w-8 h-8 rounded-full" src="${mensaje.foto}" data-src="${mensaje.foto?.replace('-thumb','')}" alt="Jese image" tabindex="-1">
                <div class="flex flex-col gap-1 w-full max-w-[320px]" tabindex="-1">
                  <div class="flex ${dq1}  items-center space-x-2 e" tabindex="-1">
                    <span class=" text-sm  font-semibold text-gray-900 dark:text-white" tabindex="-1">${mensaje.nombre_emisor}</span>
                    <span class="text-sm pr-2 font-normal text-gray-500 dark:text-gray-400" tabindex="-1">11:46</span>
                  </div>
                  <div class="flex flex-col leading-1.5 p-4 border-gray-200 bg-[#374151]/50 rounded-e-xl rounded-es-xl dark:bg-[#374151]/20" tabindex="-1">
                    <p class="text-sm  whitespace-pre-line font-normal text-gray-900 dark:text-white" tabindex="-1">${mensaje.mensaje}</p>
                  </div>
                  <span class="text-sm font-normal text-gray-500 dark:text-gray-400" tabindex="-1">Delivered</span>
                </div>
                <button id="dropdownMenuIconButton" data-dropdown-toggle="dropdownDots" data-dropdown-placement="bottom-start" class="inline-flex self-center items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-[#374151]/50 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800 dark:focus:ring-gray-600" type="button" tabindex="-1">
                  <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15" tabindex="-1">
                    <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" tabindex="-1"></path>
                  </svg>
                </button>
                <div id="dropdownDots" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-40 dark:bg-[#374151]/20 dark:divide-gray-600" tabindex="-1" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(-1136px, 10px);" data-popper-reference-hidden="" data-popper-escaped="" data-popper-placement="bottom-start">
                  <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownMenuIconButton" tabindex="-1">
                    <li tabindex="-1">
                      <a href="#" class="block px-4 py-2 hover:bg-[#374151]/50 dark:hover:bg-[#374151]/20 dark:hover:text-white" tabindex="-1">Reply</a>
                    </li>
                    <li tabindex="-1">
                      <a href="#" class="block px-4 py-2 hover:bg-[#374151]/50 dark:hover:bg-[#374151]/20 dark:hover:text-white" tabindex="-1">Forward</a>
                    </li>
                    <li tabindex="-1">
                      <a href="#" class="block px-4 py-2 hover:bg-[#374151]/50 dark:hover:bg-[#374151]/20 dark:hover:text-white" tabindex="-1">Copy</a>
                    </li>
                    <li tabindex="-1">
                      <a href="#" class="block px-4 py-2 hover:bg-[#374151]/50 dark:hover:bg-[#374151]/20 dark:hover:text-white" tabindex="-1">Report</a>
                    </li>
                    <li tabindex="-1">
                      <a href="#" class="block px-4 py-2 hover:bg-[#374151]/50 dark:hover:bg-[#374151]/20 dark:hover:text-white" tabindex="-1">Delete</a>
                    </li>
                  </ul>
                </div>
              </div>
            </li>`;
                }

                window.mixer_categorias = mixitup($('#seccion-categorias .project-boxes.jsGridView')[0], {
                    data: {
                        uidKey: 'id_categoria',
                        dirtyCheck: true
                    },
                    render: {
                        target: addCategoria
                    },
                    selectors: {
                        target: '[data-ref="item"]'
                    },
                    controls: {
                        enable: false
                    },
                    animation: {
                        enable: false
                    },
                    callbacks: {
                        onMixEnd: function(state) {
                            let data = state.activeDataset.reduce((contador, categoria) => {
                                if (categoria.activo) contador.activas++;
                                else contador.inactivas++;
                                return contador;
                            }, {
                                activas: 0,
                                inactivas: 0
                            });
                            $('.categoria_counter').text(state.activeDataset.length);
                            $('.status-number.categorias.activas').text(data.activas);
                            $('.status-number.categorias.inactivas').text(data.inactivas);
                            window.lazyCaller();
                            dominant();
                        }
                    }
                });
                window.mixer_instructores = mixitup($('#seccion-instructores .project-boxes.jsGridView')[0], {
                    data: {
                        uidKey: 'id_usuario',
                        dirtyCheck: true
                    },
                    render: {
                        target: addInstructor
                    },
                    selectors: {
                        target: '[data-ref="item"]'
                    },
                    controls: {
                        enable: false
                    },
                    animation: {
                        enable: false
                    },
                    callbacks: {
                        onMixEnd: function(state) {
                            let data = state.activeDataset.reduce((contador, usuario) => {
                                if (usuario.activo) contador.activos++;
                                else contador.inactivos++;
                                if (usuario.bloqueado) contador.bloqueados++;
                                return contador;
                            }, {
                                activos: 0,
                                inactivos: 0,
                                bloqueados: 0
                            });
                            $('.instructor_counter').text(state.activeDataset.length);
                            $('.status-number.instructores.activos').text(data.activos);
                            $('.status-number.instructores.inactivos').text(data.inactivos);
                            $('.status-number.instructores.bloqueados').text(data.bloqueados);
                            window.lazyCaller();
                            dominant();
                        }
                    }
                });
                window.mixer_estudiantes = mixitup($('#seccion-estudiantes .project-boxes.jsGridView')[0], {
                    data: {
                        uidKey: 'id_usuario',
                        dirtyCheck: true
                    },
                    render: {
                        target: addEstudiante
                    },
                    selectors: {
                        target: '[data-ref="item"]'
                    },
                    controls: {
                        enable: false
                    },
                    animation: {
                        enable: false
                    },
                    callbacks: {
                        onMixEnd: function(state) {
                            let data = state.activeDataset.reduce((contador, usuario) => {
                                if (usuario.activo) contador.activos++;
                                else contador.inactivos++;
                                if (usuario.bloqueado) contador.bloqueados++;
                                return contador;
                            }, {
                                activos: 0,
                                inactivos: 0,
                                bloqueados: 0
                            });
                            $('.estudiante_counter').text(state.activeDataset.length);
                            $('.status-number.estudiantes.activos').text(data.activos);
                            $('.status-number.estudiantes.inactivos').text(data.inactivos);
                            $('.status-number.estudiantes.bloqueados').text(data.bloqueados);
                            window.lazyCaller();
                            dominant();
                        }
                    }
                });
                window.addInscrito = function(inscrito) {
                    if (inscrito.fecha_inscripcion == null || inscrito.fecha_inscripcion == "")
                        inscrito.fecha_inscripcion = tiempoTranscurrido(new Date(inscrito.fecha_inscripcion))
                    return `<div class="glass-bg p-2 account-profile  mb-2  w-full flex justify-start  items-center">
                                
                                <img class="big lozad lazy-img cursor-pointer" src="${inscrito.foto}" data-src="${inscrito.foto.replaceAll('-thumb','')}" alt="">

                                <div class="flex w-full justify-between items-center">
                                <div class="flex flex-col items-start justify-center ml-5 p-2">
                                    <div class="text-left text-white">${inscrito.nombres}</div>
                                    <div class="text-left text-white">Inscrita desde: <b>${inscrito.fecha_inscripcion}</b></div>
                                </div>
                                  <div>
                                        <span
                                            onclick="event.stopPropagation();eventosTippy(this,{btn1:'Agregar',btn2:'Cancelar',a:'nuevo_contacto',data:[{p:'id_amigo',v:${inscrito.id_usuario}}],msg:'¿Agregar a <b>${inscrito.nombres}</b> como contacto?'},'confirmar','top',[-20,10])"
                                            class="flex mr-2 flex items-center p-2 rounded-full bg-[#fff]/20 hover:bg-[#fff]/50 w-10 h-10 group  cursor-pointer"
                                            erika-tooltip tooltip-content="Agregar como contacto">
                                            <?php include './php/svg/amigo.svg' ?>
                                        </span><span class="alertbox"></span>
                                    </div>
                                </div>
                            </div>`;
                }
                window.addSeccion = function(seccion) {
                    console.log('SECCION', seccion)
                    return `<div class="accordion-erk">
                            <h3 class="titulo-seccion">
                            <div class="flex w-full items-center justify-between">
                            <span class="text-lg md:text-1xl lg:text-2xl">${seccion.nombre}</span>
                                <div class="flex">
                                    <div>
                                        <span
                                            onclick="event.stopPropagation();localParams('id_curso',${seccion.id_curso}); eliminarRegistros(${seccion.id_seccion},this,'eliminar_seccion','¿De verdad quiere eliminar este seccion?','right',[-20,10])"
                                            class="ocultar flex mr-2 flex items-center p-2 rounded-full bg-[#c60e0e]/20 hover:bg-[#c60e0e]/50 w-10 h-10 group  cursor-pointer"
                                            erika-tooltip tooltip-content="Eliminar seccion">
                                            <?php include './php/svg/eliminar.svg' ?>
                                        </span><span class="alertbox"></span>
                                    </div>

                                    <span
                                    
                                    onclick="event.stopPropagation(); editarseccion(${seccion.id_seccion})"
                                        class="ocultar flex mr-2  flex items-center p-2 rounded-full bg-[#03a9f4]/20 hover:bg-[#03a9f4]/50 w-10 h-10 group  cursor-pointer"
                                        erika-tooltip tooltip-content="Editar seccion"
                                         abrir-formulario
                            data-formulario="froala-container"
                            >
                                        <?php include './php/svg/editar.svg' ?>
                                    </span>
                                </div>
                                </div>
                            </h3>
                            <div>${seccion.html}</div>
                        </div>`;
                }
                window.editarseccion= function (id_seccion){
                    console.log(4444444)
                    localStorage.setItem('editando_seccion',id_seccion)
                    
                    $.ajax({
                        url: `./php/db/get.php?a=seccion_data&u=${id_seccion}`,
                        method: 'GET',
                        processData: false,
                        contentType: false,
                        timeout: 5000
                    }).done(function(data) {
                        console.log(data)
                        FroalaEditor('#editorTexto').html.set(data.html);
                        $("#seccion_nombre").val(data.nombre)
                    })
                }
                window.mixer_usuarios = mixitup($('#seccion-usuarios .project-boxes.jsGridView')[0], {
                    data: {
                        uidKey: 'id_usuario',
                        dirtyCheck: true
                    },
                    render: {
                        target: addUsuario
                    },
                    selectors: {
                        target: '[data-ref="item"]'
                    },
                    controls: {
                        enable: false
                    },
                    animation: {
                        enable: false
                    },
                    callbacks: {
                        onMixEnd: function(state) {
                            let data = state.activeDataset.reduce((contador, usuario) => {
                                if (usuario.activo) contador.activos++;
                                else contador.inactivos++;
                                if (usuario.bloqueado) contador.bloqueados++;
                                return contador;
                            }, {
                                activos: 0,
                                inactivos: 0,
                                bloqueados: 0
                            });
                            $('.usuarios_counter').text(state.activeDataset.length);
                            $('.status-number.usuarios.activos').text(data.activos);
                            $('.status-number.usuarios.inactivos').text(data.inactivos);
                            $('.status-number.usuarios.bloqueados').text(data.bloqueados);
                            window.lazyCaller();
                            dominant();
                        }
                    }
                });
                window.mixer_mensajes = mixitup($('#mensajesListErk')[0], {
                    data: {
                        uidKey: 'id_contacto',
                        dirtyCheck: true
                    },
                    render: {
                        target: addMensaje
                    },
                    selectors: {
                        target: '[data-ref="item"]'
                    },

                    animation: {
                        enable: false,
                        duration: 500,
                    }
                });
                window.mixer_contactos = mixitup($('#contactosListErk')[0], {
                    data: {
                        uidKey: 'id_amigo',
                        dirtyCheck: true
                    },
                    render: {
                        target: addContacto
                    },
                    selectors: {
                        target: '[data-ref="item"]'
                    },

                    animation: {
                        enable: false,
                        duration: 500,
                    }
                });
                window.mixer_cursos = mixitup($('#seccion-cursos .project-boxes.jsGridView')[0], {
                    data: {
                        uidKey: 'id_curso',
                        dirtyCheck: true
                    },
                    render: {
                        target: addCurso
                    },
                    selectors: {
                        target: '[data-ref="item"]'
                    },

                    animation: {
                        enable: false,
                        duration: 500,
                    },
                    callbacks: {
                        onMixEnd: function(state) {

                            let data = state.activeDataset.reduce((contador, curso) => {
                                if (curso.estado_code == 1) contador.proximo++;
                                if (curso.estado_code == 2) contador.impartiendose++;
                                if (curso.estado_code == 3) contador.finalizados++;
                                return contador;
                            }, {
                                proximo: 0,
                                impartiendose: 0,
                                finalizados: 0
                            });
                            $('.curso_counter').text(state.activeDataset.length);
                            $('.status-number.curso-finalizado').text(data.finalizados);
                            $('.status-number.curso-proximo').text(data.proximo);
                            $('.status-number.curso-impartiendose').text(data.impartiendose);
                            window.lazyCaller();
                            dominant();
                            window.setAbrirformlarios('#seccion-cursos [abrir-formulario]');
                        }
                    }
                });
                window.mixer_mis_cursos = mixitup($('#seccion-mis-cursos .project-boxes.jsGridView')[0], {
                    data: {
                        uidKey: 'id_curso',
                        dirtyCheck: true
                    },
                    render: {
                        target: addMiCurso
                    },
                    selectors: {
                        target: '[data-ref="item"]'
                    },

                    animation: {
                        enable: false,
                        duration: 500,
                    },
                    callbacks: {
                        onMixEnd: function(state) {
                            let data = state.activeDataset.reduce((contador, curso) => {
                                if (curso.estado_code == 1) contador.proximo++;
                                if (curso.estado_code == 2) contador.impartiendose++;
                                if (curso.estado_code == 3) contador.finalizados++;
                                return contador;
                            }, {
                                proximo: 0,
                                impartiendose: 0,
                                finalizados: 0
                            });
                            $('.mis_curso_counter').text(state.activeDataset.length);
                            $('.status-number.mis-cursos-finalizado').text(data.finalizados);
                            $('.status-number.mis-cursos-proximo').text(data.proximo);
                            $('.status-number.mis-cursos-impartiendose').text(data.impartiendose);

                            window.lazyCaller();
                            dominant();
                        }
                    }
                });


                //funcion que abre los formularios, donde cual es el objetivo
                window.abriForumlario = function(cual, setData, actu) {
                    tippy.hideAll({
                        duration: 0
                    });
                    cual = cual?.replaceAll('#', '');
                    const overlay = document.getElementById(cual);
                    const modal = document.getElementById('modal_' + cual);
                    // Show the overlay and animate the modal to slide in from the right
                    overlay.classList.remove('hidden');
                    anime({
                        targets: overlay,
                        opacity: [0, 1],
                        easing: 'easeOutQuad',
                        duration: 100
                    });

                    //setData debe ser un objeto {query:"",inputs:[]} donde query son los parametros par pedirle al servidor los datos e inputs es un arreglo de los inputs que deben ser rellenados en pase al id y tipo de input
                    if (setData) {
                        $(modal).find('.btn-ok').text(actu)
                        $(modal).find('h1').text($(modal).find('h1').data('actualizar'));
                        $(modal).find('.btn-ok').text($(modal).find('h1').data('actualizar'));
                        $(modal).find('form').data('esActualizar', true)
                        $(modal).find('form').data('id', setData.id);

                        //$(modal).find('form').addClass('desactivar')
                        //ocultarBotonesFormulario(modal.querySelector('form'))
                        for (let i = 0; i < setData.data.length; i++) {
                            let _i = setData.data[i];
                            console.log(_i)
                            if (_i.type == "native")
                                $(modal).find(_i.input).val(_i.value)
                            if (_i.type == "filepon") {
                                let filepon = FilePond.find($(_i.input)[0]);
                                if (_i.value) {
                                    filepon.addFile(_i.value.replace('-thumb', ''));
                                    $(modal).find('form').data('aditionals', [{
                                        p: 'foto',
                                        v: _i.value.replace('-thumb', '')
                                    }]);
                                    setTimeout(() => {
                                        $(modal).find('.filepond--drop-label').attr('style', 'opacity:0;display:none;')
                                    }, 500);
                                }
                            }
                            if (_i.type == 'select2') {
                                $(modal).find(_i.input).val(_i.value).trigger('change');
                            }
                            if (_i.type == 'optional') {
                                $(modal).find(_i.input).data('noval', true);
                            }
                            if (_i.type == 'airdate') {
                                $(modal).find(_i.input).data('fecha').selectDate(_i.value);
                            }
                        }
                    } else {
                        let reg = $(modal).find('h1').data('registrar');
                        $(modal).find('.btn-ok').text(reg);
                        $(modal).find('h1').text(reg)
                    }
                    // Animate modal sliding in from the right
                    anime({
                        targets: modal,
                        opacity: [0, 1],
                        translateX: ['100%', '0%'], // Slide from 100% to 0%
                        easing: 'easeOutQuad',
                        duration: 200
                    });
                }

                window.cerrarFormulario = function(cual, reset = true) {
                    console.log('cerrando formulario')
                    tippy.hideAll({
                        duration: 0
                    });
                    const modal = document.getElementById('modal_' + cual);
                    const overlay = document.getElementById(cual);
                    // Animate modal sliding out to the left
                    anime({
                        targets: modal,
                        opacity: [1, 0],
                        translateX: ['0%', '-100%'], // Slide out to the left
                        easing: 'easeInQuad',
                        duration: 100,
                        complete: function() {
                            // Hide the overlay once animation completes
                            anime({
                                targets: overlay,
                                opacity: [1, 0],
                                easing: 'easeOutQuad',
                                duration: 200,
                                complete: function() {
                                    overlay.classList.add('hidden');

                                    if (reset) {
                                        if ($(overlay).find('[data-anterior]')[0]) {
                                            $(overlay).find('.btn-atras').trigger('click')
                                        }
                                        animar($(overlay).find('.mensaje-ajax')[0], 'ocultar_hacia_abajo')
                                        animar($(overlay).find('.btn-atras')[0], 'mostrar_derecha')
                                        animar($(overlay).find('.btn-siguiente')[0], 'mostrar_hacia_arriba')
                                        animar($(overlay).find('.btn-ok')[0], 'mostrar_derecha')
                                        $(overlay).find('.campo').each((i, e) => {
                                            animar(e, i % 2 == 0 ? 'mostrar_derecha' : 'mostrar_izquierda')
                                        })
                                        $('form .invalid').removeClass('invalid');
                                        $(overlay).find('form')[0].reset();
                                        $('select').trigger('change');
                                        $(overlay).find('form').data('id', null);
                                        $(overlay).find('form').data('aditionals', null);
                                        let filepon_intance = FilePond.find($(overlay).find('.filepond--root')[0]);
                                        if (filepon_intance)
                                            filepon_intance.removeFile();
                                        $(overlay).find('[erika-type="fecha"]').each((i, e) => {
                                            $(e).data('fecha').clear()
                                        });
                                        $(overlay).removeData('from_other_form');
                                        $(overlay).find('.otro-btn').removeData('moved');
                                        $(overlay).find('.btn-dont').before($(overlay).find('.btn-ok')[0]);
                                        $(overlay).find('.otro-btn').append($(overlay).find('.btn-otro')[0]);
                                        $(overlay).find('.otro-btn').append($(overlay).find('.btn-reintentar')[0]);
                                        if ($(overlay).find('form').data('otro_registro')) {
                                            return;
                                        }
                                    }
                                }
                            });
                        }
                    });
                }
            });
            var EsLocale = {
                days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                daysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                daysMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                today: 'Hoy',
                clear: 'Limpiar',
                dateFormat: 'dd/MM/yyyy',
                timeFormat: 'hh:mm aa',
                firstDay: 1
            };
            var actualizandoFecha = false;
            window.cargarAlumnosParaInscribir = async function(id_curso) {
                $('#modal_nuevainscripcion').html(` <h1 class="mt-5">Por favor espere mientras se cargan los estudiantes</h1>
                <span class=" ml-3 loading" style="text-align: center;display: flex;justify-content: center;align-items: center;transform: translateY(76px);">
                    <div class="backgroundLoader" style="scale: 1;">
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
                </span>`);
                $.ajax({
                        url: `./php/getView.php?v=nueva_inscripcion_data&id_curso=` + id_curso,
                        method: 'GET',
                        processData: false, // Prevent jQuery from automatically transforming the data into a query string
                        contentType: false, // Prevent jQuery from overriding the Content-Type header
                        timeout: 5000 // Set the timeout in milliseconds (5000 ms = 5 seconds)
                    })
                    .done(function(data) {
                        console.log(data)
                        if (!data.error) {
                            $('#modal_nuevainscripcion').html(data.html);
                            setTooltip('#modal_nuevainscripcion [erika-tooltip]');
                            setTimeout(() => {
                                window.lazyCaller();
                                try {} catch (error) {

                                }
                                $('#modal_nuevainscripcion [modal-cerrar]').on('click', function() {
                                    window.cerrarFormulario(this.getAttribute('modal-cerrar'), true)
                                });
                                window.setAbrirformlarios('#modal_nuevainscripcion [abrir-formulario]');
                                window.setSelect2('#estudiantes_inscripcion');
                                window.setFormSwitcher('#modal_nuevainscripcion [erika-form-open]')
                                $('#modal_nuevainscripcion form').on('submit', async function(event) {
                                    event.preventDefault();
                                    let paraInscribir = $(event.target["estudiantes_inscripcion"]).select2('data');
                                    console.log(paraInscribir);
                                    if (paraInscribir.length === 0) {

                                        return;
                                    }

                                    let success = true; // Para rastrear el éxito general
                                    for (let i = 0; i < paraInscribir.length; i++) {
                                        let formData = new FormData();
                                        formData.append('a', "inscribir");
                                        formData.append('id_usuario', paraInscribir[i].id);
                                        formData.append('id_curso', id_curso);
                                        try {
                                            // Enviamos la solicitud AJAX y guardamos la respuesta
                                            let response = await $.ajax({
                                                url: './php/db/post.php',
                                                method: 'POST',
                                                processData: false,
                                                contentType: false,
                                                timeout: 5000, // 5 segundos de timeout
                                                data: formData
                                            });
                                            // Aquí puedes verificar si la respuesta fue exitosa
                                            if (response.error) {
                                                success = false;
                                                break; // Detenemos el bucle si hubo error
                                            } else {
                                                let option = event.target["estudiantes_inscripcion"].querySelector(`option[value='${paraInscribir[i].id}']`);
                                                $(option).remove(); // Eliminar del DOM
                                                $(event.target["estudiantes_inscripcion"]).trigger('change');
                                                if (window.location.href.indexOf('curso-detalle-') == -1)
                                                    $('#menulateraladmin .tab.active').trigger('click')
                                            }
                                        } catch (error) {
                                            // En caso de error de red o tiempo de espera, manejamos la excepción
                                            success = false;
                                            break; // Detener si hay un error
                                        }
                                    }
                                    if (success) {
                                        swal.fire({
                                            title: "Todoss los estudantes fueron inscrito",
                                            icon: "success"
                                        }).then(() => {
                                            window.cerrarFormulario('nuevainscripcion', false)
                                            window.location.reload();
                                        })
                                    }
                                    //acamix
                                });
                            }, 20);
                        } else {

                            console.log('algo oasa')
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        console.log('algo oasa')
                    });
            }
            window.verDetallesCurso = function(id_curso) {
                localStorage.setItem('curso', id_curso)
                $(`section#seccion-cursos`).addClass('hide');
                $(`section#seccion-curso-detalle`).html(`<span class=" ml-3 loading">
                                    <div class="backgroundLoader" style="scale:1;">
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

                                </span>`).addClass('items-center').removeClass('hide');

                fetch(`./php/getView.php?v=curso_detalle&id_curso=` + id_curso)
                    .then(response => response.json())
                    .then(data => {

                        $(`section#seccion-curso-detalle`).html(data.html);
                        let inscritosList = $('#inscritos_list_container');
                        if (data.inscritos.length > 0) {
                            inscritosList.html(``)
                            $('.inscritos_counter').text(data.inscritos.length);

                        }

                        let inisetdate = $("#cambiar_fecha_ini").data('setdate');
                        let finsetdate = $("#cambiar_fecha_fin").data('setdate');
                        $("#cambiar_fecha_ini").data("fecha", new AirDatepicker("#cambiar_fecha_ini", {
                            timepicker: true, // Permite la selección de horas
                            minHours: 0,
                            maxHours: 23,
                            buttons: [{
                                    content: 'Aceptar',
                                    onClick: (dp) => {
                                        const curso_fecha_inicio = new Intl.DateTimeFormat('es-ES', {
                                            month: 'long',
                                            day: 'numeric',
                                            year: 'numeric',
                                            hour: 'numeric',
                                            minute: 'numeric',
                                            second: 'numeric',
                                            hour12: true // Esto cambia a formato 12 horas
                                        }).format(new Date(dp.selectedDates[0]));

                                        $("#cambiar_fecha_ini").next().addClass('changing');
                                        $("#cambiar_fecha_fini").next().addClass('desactivado');
                                        $("#cambiar_fecha_ini").attr('tooltip-content', "Cambiando...");
                                        animar($('#cambiar_fecha_ini')[0], 'ocultar_derecha', 200);



                                        updateCursoDate(id_curso, 'ini', (new Date(dp.selectedDates[0].getTime() - (dp.selectedDates[0].getTimezoneOffset() * 60000)).toISOString().slice(0, 19))).then((data) => {
                                            $("#cambiar_fecha_ini").next().removeClass('changing');
                                            $("#cambiar_fecha_fini").next().removeClass('desactivado');
                                            $("#cambiar_fecha_ini-btn").attr('tooltip-content', data.msg);
                                            $('#cambiar_fecha_ini-btn')[0]._tippy.enable();
                                            $('#cambiar_fecha_ini-btn')[0]._tippy.show();
                                            if (!data.error) {
                                                $('.fecha_ini')[0].innerText = curso_fecha_inicio;
                                                inisetdate = dp.selectedDates[0];
                                            } else {
                                                animar($('#cambiar_fecha_ini')[0], 'mostrar_izquierda', 200);
                                                $('#cambiar_fecha_ini-btn').data('hide', true);
                                            }
                                            $("#cambiar_fecha_ini-btn").attr('tooltip-content', 'Cambiar fecha de inicio del curso');
                                        })

                                        //TODO aca actualizar la fecha del curso de inicio
                                    }
                                },
                                {
                                    content: 'Cancelar',
                                    className: 'cambiar-fecha-btnCancelar',
                                    onClick: (dp) => {
                                        dp.clear();
                                        $('#cambiar_fecha_ini-btn')[0]._tippy.enable();
                                        animar($('#cambiar_fecha_ini')[0], 'ocultar_derecha', 200)
                                    }
                                }
                            ],
                            onSelect({
                                date
                            }) {
                                if (!actualizandoFecha) {
                                    console.log(date)
                                    actualizandoFecha = true; // Deshabilitar onSelect mientras se actualiza
                                    $("#cambiar_fecha_fin").data("fecha").update({
                                        minDate: date
                                    })
                                    setTimeout(() => {
                                        actualizandoFecha = false; // Rehabilitar onSelect
                                    }, 500);
                                }
                            },
                            selectedDates: [inisetdate],
                            minDate: new Date(),
                            maxDate: new Date(finsetdate),
                            locale: EsLocale,
                        }));

                        $("#cambiar_fecha_fin").data("fecha", new AirDatepicker("#cambiar_fecha_fin", {
                            timepicker: true, // Permite la selección de horas
                            minHours: 0,
                            maxHours: 23,
                            buttons: [{
                                content: 'Aceptar',
                                className: 'cambiar-fecha-btn',
                                onClick: (dp) => {
                                    const curso_fecha_fin = new Intl.DateTimeFormat('es-ES', {
                                        month: 'long',
                                        day: 'numeric',
                                        year: 'numeric',
                                        hour: 'numeric',
                                        minute: 'numeric',
                                        second: 'numeric',
                                        hour12: true // Esto cambia a formato 12 horas
                                    }).format(new Date(dp.selectedDates[0]));

                                    $("#cambiar_fecha_fin").next().addClass('changing');
                                    $("#cambiar_fecha_ini").next().addClass('desactivado');
                                    $("#cambiar_fecha_fin").attr('tooltip-content', "Cambiando...");

                                    animar($('#cambiar_fecha_fin')[0], 'ocultar_derecha', 200);
                                    updateCursoDate(id_curso, 'fini', (new Date(dp.selectedDates[0].getTime() - (dp.selectedDates[0].getTimezoneOffset() * 60000)).toISOString().slice(0, 19))).then((data) => {
                                        $("#cambiar_fecha_fin").next().removeClass('changing');
                                        $("#cambiar_fecha_ini").next().removeClass('desactivado');
                                        $("#cambiar_fecha_fin-btn").attr('tooltip-content', data.msg);
                                        $('#cambiar_fecha_fin-btn')[0]._tippy.enable();
                                        $('#cambiar_fecha_fin-btn')[0]._tippy.show();
                                        if (!data.error) {
                                            $('.fecha_fini')[0].innerText = curso_fecha_fin;
                                            finsetdate = dp.selectedDates[0];
                                        } else {
                                            animar($('#cambiar_fecha_fin')[0], 'mostrar_izquierda', 200);
                                        }
                                        $("#cambiar_fecha_fin-btn").attr('tooltip-content', 'Cambiar fecha de finalizacion del curso');
                                    })

                                    //TODO aca actualizar la fecha del curso de inicio
                                }
                            }, {
                                content: 'Cancelar',
                                className: 'cambiar-fecha-btnCancelar',
                                onClick: (dp) => {
                                    dp.clear();
                                    $('#cambiar_fecha_fin-btn')[0]._tippy.enable();
                                    animar($('#cambiar_fecha_fin')[0], 'ocultar_derecha', 200)
                                }
                            }],
                            onSelect({
                                date
                            }) {
                                if (!actualizandoFecha) {
                                    actualizandoFecha = true; // Deshabilitar onSelect mientras se actualiza
                                    $("#cambiar_fecha_ini").data("fecha").update({
                                        maxDate: date
                                    })
                                    setTimeout(() => {
                                        actualizandoFecha = false; // Rehabilitar onSelect
                                    }, 500);
                                }

                            },
                            selectedDates: [finsetdate],
                            minDate: new Date(inisetdate),
                            locale: EsLocale,
                        }));
                        $('#cambiar_fecha_ini-btn').on('click', function(event) {
                            if (event.target === this) {
                                this._tippy.disable();
                                $("#cambiar_fecha_ini").data("fecha").selectDate(inisetdate, {
                                    silent: true
                                })
                                animar($('#cambiar_fecha_ini')[0], 'mostrar_izquierda', 200)
                                animar($('#cambiar_fecha_fin')[0], 'ocultar_derecha', 200)
                            }
                        })
                        $('#cambiar_fecha_fin-btn').on('click', function() {
                            if (event.target === this) {
                                this._tippy.disable();
                                $("#cambiar_fecha_fin").data("fecha").selectDate(finsetdate, {
                                    silent: true
                                })
                                animar($('#cambiar_fecha_ini')[0], 'ocultar_derecha', 200)
                                animar($('#cambiar_fecha_fin')[0], 'mostrar_izquierda', 200)
                            }
                        })
                        setTooltip('section#seccion-curso-detalle [erika-tooltip]');
                        $('#curso-menu-2 .tab').on('click', function() {
                            console.log('asdasdgahsdjljahgsldjhagljsdhgaljshgdljahgsdljhagsl')
                            $('#curso-menu-2 .tab').addClass('disabled');
                            let active = $('#curso-menu-2 .active')[0];
                            let activeNext = $($(this).data('activar'))[0];
                            anime({
                                targets: active,
                                opacity: [1, 0],
                                duration: 500,
                                easing: 'easeInOutQuad',
                                complete: function() {
                                    active.style.display = 'none';
                                    activeNext.style.display = 'flex';
                                    active.classList.remove('active');
                                    activeNext.classList.add('active');
                                    anime({
                                        targets: activeNext,
                                        opacity: [0, 1],
                                        translateY: [-50, 0],
                                        duration: 500,
                                        easing: 'easeInOutQuad',
                                        complete: function() {
                                            $('#curso-menu-2 .tab').removeClass('disabled');
                                        }
                                    });
                                }
                            });

                        });

                        $("#accordion-grupos").accordion({
                            collapsible: true
                        });

                        let histo = './curso-detalle-' + id_curso;
                        history.pushState(null, '', histo);
                        setTimeout(() => {
                            console.log('setAbrirformlarios')
                            window.lazyCaller();
                            try {
                                window.setPlayers();
                            } catch (error) {

                            }
                            window.setAbrirformlarios('section#seccion-curso-detalle [abrir-formulario]');
                            if (window.mixer_inscritos)
                                window.mixer_inscritos.destroy()
                            if (window.mixer_secciones)
                                window.mixer_secciones.destroy()
                            window.mixer_inscritos = mixitup($('#inscritos_list_container')[0], {
                                data: {
                                    uidKey: 'id_usuario',
                                    dirtyCheck: true
                                },
                                render: {
                                    target: addInscrito
                                },
                                selectors: {
                                    target: '[data-ref="item"]'
                                },
                                controls: {
                                    enable: false
                                },
                                animation: {
                                    enable: false
                                },
                                callbacks: {
                                    onMixEnd: function(state) {
                                        $('.inscritos_counter').text(state.activeDataset.length);
                                        if (state.activeDataset.length === 0) {
                                            $('#inscritos_list_container').html(`<div class="animado glass-bg no-content seccionesimg" style="--delay:0.7s;">
                            <h1>No hay inscritos</h1>
                            <img src="../assets/media/img/site/inscritos.png" alt="">
                        </div>`)
                                        }
                                        window.lazyCaller();
                                        dominant();
                                    }
                                }
                            });
                            $('#secciones-list-container').html('')
                            window.mixer_secciones = mixitup($('#secciones-list-container')[0], {
                                data: {
                                    uidKey: 'id_seccion',
                                    dirtyCheck: true
                                },
                                render: {
                                    target: addSeccion
                                },
                                selectors: {
                                    target: '[data-ref="item"]'
                                },
                                controls: {
                                    enable: false
                                },
                                animation: {
                                    enable: false
                                },
                                callbacks: {
                                    onMixEnd: function(state) {
                                        $('.secciones_counter').text(state.activeDataset.length);

                                        if (state.activeDataset.length === 0) {
                                            $('#secciones-list-container').html(`<div class="animado glass-bg no-content seccionesimg" style="--delay:0.7s;">
                            <h1>No hay contenido agregado</h1>
                            <img src="../assets/media/img/site/no-content.png" alt="">
                        </div>`)
                                            return
                                        }

                                        $('.accordion-erk').accordion({
                                            collapsible: true,
                                            active: false,
                                            icons: {
                                                "header": "erk-p",
                                                "activeHeader": "erk-m"
                                            },
                                            animate: 200,
                                            heightStyle: "content"
                                        });
                                        window.setAbrirformlarios('#secciones-list-container [abrir-formulario]');
                                    }
                                }
                            });
                            window.mixer_inscritos.dataset(data.inscritos);
                            window.mixer_secciones.dataset(data.secciones);

                        }, 20);
                    })
                    .catch(error => console.error('Error:', error));
                $('#menulateraladmin [data-seccion]').each(function() {
                    $(this).data('last', 'seccion-curso-detalle');
                });

                // history.pushState(null, '', './' + 'seccion-curso-detalle'.replaceAll('seccion-', ''));
            }
            window.editarCategoria = function(cat) {
                $.ajax({
                        url: './php/db/get.php?a=categoria&id=' + cat,
                        method: 'GET',
                        processData: false, // Prevent jQuery from automatically transforming the data into a query string
                        contentType: false, // Prevent jQuery from overriding the Content-Type header
                        timeout: 5000 // Set the timeout in milliseconds (5000 ms = 5 seconds)
                    })
                    .done(function(data) {
                        console.log(data)
                        if (data.error) {} else {
                            let setData = {
                                id: data.data.id_categoria,
                                data: [{
                                        type: 'native',
                                        input: "#categoria_nombre",
                                        value: data.data.nombre
                                    },
                                    {
                                        type: 'filepon',
                                        input: "#categoria_imagen",
                                        value: data.data.foto
                                    }
                                ]
                            }
                            abriForumlario('nuevacategoria', setData, "Actualizar Categoria");
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {});
            }
            window.editarInstructor = function(instru) {
                $.ajax({
                        url: './php/db/get.php?a=instructor&id=' + instru,
                        method: 'GET',
                        processData: false, // Prevent jQuery from automatically transforming the data into a query string
                        contentType: false, // Prevent jQuery from overriding the Content-Type header
                        timeout: 5000 // Set the timeout in milliseconds (5000 ms = 5 seconds)
                    })
                    .done(function(data) {
                        console.log(data)
                        if (data.error) {} else {
                            let setData = {
                                id: data.data.id_usuario,
                                data: [{
                                        type: 'native',
                                        input: "#instructor_nombre",
                                        value: data.data.nombres
                                    },
                                    {
                                        type: 'native',
                                        input: "#instructor_apellido",
                                        value: data.data.apellidos
                                    },
                                    {
                                        type: 'native',
                                        input: "#instructor_correo",
                                        value: data.data.correo
                                    },
                                    {
                                        type: 'native',
                                        input: "#instructor_correo",
                                        value: data.data.correo
                                    },
                                    {
                                        type: 'optional',
                                        input: "#instructor_pass"
                                    },
                                    {
                                        type: 'select2',
                                        input: "#instructor_sexo",
                                        value: data.data.sexo
                                    },
                                    {
                                        type: 'airdate',
                                        input: "#instructor_fecha_nacimiento",
                                        value: data.data.fecha_nacimiento
                                    },
                                    {
                                        type: data.data.foto ? 'filepon' : 'empty',
                                        input: "#instructor_imagen",
                                        value: data.data.foto
                                    }
                                ]
                            }
                            abriForumlario('nuevoInstructor', setData, "Actualizar Instructor");
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {});
            }
            window.editarEstudiante = function(instru) {
                $.ajax({
                        url: './php/db/get.php?a=estudiante&id=' + instru,
                        method: 'GET',
                        processData: false, // Prevent jQuery from automatically transforming the data into a query string
                        contentType: false, // Prevent jQuery from overriding the Content-Type header
                        timeout: 5000 // Set the timeout in milliseconds (5000 ms = 5 seconds)
                    })
                    .done(function(data) {
                        console.log(data)
                        if (data.error) {} else {
                            let setData = {
                                id: data.data.id_usuario,
                                data: [{
                                        type: 'native',
                                        input: "#estudiante_nombre",
                                        value: data.data.nombres
                                    },
                                    {
                                        type: 'native',
                                        input: "#estudiante_apellido",
                                        value: data.data.apellidos
                                    },
                                    {
                                        type: 'native',
                                        input: "#estudiante_correo",
                                        value: data.data.correo
                                    },
                                    {
                                        type: 'native',
                                        input: "#estudiante_correo",
                                        value: data.data.correo
                                    },
                                    {
                                        type: 'optional',
                                        input: "#estudiante_pass"
                                    },
                                    {
                                        type: 'select2',
                                        input: "#estudiante_sexo",
                                        value: data.data.sexo
                                    },
                                    {
                                        type: 'airdate',
                                        input: "#estudiante_fecha_nacimiento",
                                        value: data.data.fecha_nacimiento
                                    },
                                    {
                                        type: data.data.foto ? 'filepon' : 'empty',
                                        input: "#estudiante_imagen",
                                        value: data.data.foto
                                    }
                                ]
                            }
                            abriForumlario('nuevoEstudiante', setData, "Actualizar Estudiante");
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {});
            }
            window.editarUsuario = function(instru) {
                $.ajax({
                        url: './php/db/get.php?a=usuario&id=' + instru,
                        method: 'GET',
                        processData: false, // Prevent jQuery from automatically transforming the data into a query string
                        contentType: false, // Prevent jQuery from overriding the Content-Type header
                        timeout: 5000 // Set the timeout in milliseconds (5000 ms = 5 seconds)
                    })
                    .done(function(data) {
                        console.log(data)
                        if (data.error) {} else {
                            let setData = {
                                id: data.data.id_usuario,
                                data: [{
                                        type: 'native',
                                        input: "#usuario_nombre",
                                        value: data.data.nombres
                                    },
                                    {
                                        type: 'native',
                                        input: "#usuario_apellido",
                                        value: data.data.apellidos
                                    },
                                    {
                                        type: 'native',
                                        input: "#usuario_correo",
                                        value: data.data.correo
                                    },
                                    {
                                        type: 'native',
                                        input: "#usuario_correo",
                                        value: data.data.correo
                                    },
                                    {
                                        type: 'optional',
                                        input: "#usuario_pass"
                                    },
                                    {
                                        type: 'select2',
                                        input: "#usuario_sexo",
                                        value: data.data.sexo
                                    },
                                    {
                                        type: 'airdate',
                                        input: "#usuario_fecha_nacimiento",
                                        value: data.data.fecha_nacimiento
                                    },
                                    {
                                        type: data.data.foto ? 'filepon' : 'empty',
                                        input: "#usuario_imagen",
                                        value: data.data.foto
                                    }
                                ]
                            }
                            abriForumlario('nuevoUsuario', setData, "Actualizar Usuario");
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {});
            }
            var activeurl = window.location.pathname.trim().toLowerCase();

            $('.btn-cerrar-sesion').on('click', function() {
                console.log(555, 'logout')
                fetch(`./php/logout.php`)
                    .then(response => response.json())
                    .then(data => {
                        try {

                            if (!data.error) {
                                window.location.href = './';
                            }
                        } catch (error) {

                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

            $('.datepicker_usuarios').each((i, e) => {
                $(e).data("fecha", new AirDatepicker(e, {
                    timepicker: false, // Permite la selección de horas
                    autoClose: true,
                    maxDate: new Date(new Date().setFullYear(new Date().getFullYear() - 10)).toISOString().split('T')[0], //-10 años para evitar menores de 10
                    locale: EsLocale, // Configura el locale a español    
                    navTitles: {
                        days: '<div class="flex flex-col"><span>Fecha Nacimiento</span><span><strong>yyyy</strong> <i>MMMM</i></span></div>',
                    }
                }));
            })

            $("#curso_fecha_ini").data("fecha", new AirDatepicker("#curso_fecha_ini", {
                timepicker: true, // Permite la selección de horas
                autoClose: true,
                minHours: 0,
                maxHours: 23,
                onSelect({
                    date
                }) {
                    $("#curso_fecha_finalizacion").data("fecha").update({
                        minDate: date
                    })
                },
                minDate: new Date(),
                locale: EsLocale, // Configura el locale a español    
            }));

            $("#curso_fecha_finalizacion").data("fecha", new AirDatepicker("#curso_fecha_finalizacion", {
                timepicker: true, // Permite la selección de horas
                autoClose: true,
                minHours: 0,
                maxHours: 23,
                onSelect({
                    date
                }) {
                    $("#curso_fecha_ini").data("fecha").update({
                        maxDate: date
                    })
                },
                locale: EsLocale, // Configura el locale a español    
            }));

            $("#accordion-grupos").accordion({
                collapsible: true
            });


            $('span.password-icon').each((i, e) => {
                e.addEventListener('click', (r) => {
                    if ($(e).hasClass('ocultar')) {
                        $(e).removeClass('ocultar').next().attr('type', 'password');
                    } else {
                        $(e).addClass('ocultar').next().attr('type', 'text');
                    }
                });
            })
            $('[erika-stages]').on('click', function(event) {
                let modal = $('#' + $(this).attr('erika-stages'));
                $btn = $(this);
                let stageType = $(this).data('stagestype');
                switch (stageType) {
                    case "siguiente":
                        if (!$(this).data('staged')) {
                            event.preventDefault();
                            $btn.data('staged', true);
                            $btn.prop('type', 'submit');
                            $btn.prev().removeClass('desactivado');
                            $btn.text($btn.data('btnregistrar'));
                            $btn.addClass(['bg-[#ec4899]/50', 'hover:bg-[#ec4899]/90']);
                            $btn.removeClass(['bg-[#f3c121]/50', 'hover:bg-[#f3c121]/90'])
                            animar($(modal).find('[data-anterior]')[0], 'ocultar_izquierda', 300);
                            animar($(modal).find('[data-siguiente]')[0], 'mostrar_izquierda', 300);

                        }
                        if (modal.find('form').data('esActualizar')) {
                            let f = modal.find('form h1').data('actualizar');
                            $btn.text(f);
                        }
                        break;
                    case "siguiente-sent":
                        $btn.data('stagestype', 'siguiente');
                        $btn = $btn.prev();
                    case "atras":

                        event.preventDefault();
                        $btn.next().data('staged', false);
                        $btn.next().data('stagestype', 'siguiente');
                        console.log($btn.next().data('btnsiguiente'))
                        $btn.next().text($btn.next().data('btnsiguiente'))
                        $btn.prop('type', 'button');
                        $btn.addClass('desactivado');
                        $btn.next().removeClass(['bg-[#ec4899]/50', 'hover:bg-[#ec4899]/90', 'bg-[#f3c121]/50', 'hover:bg-[#f3c121]/90'])

                        $(modal).find('form .campo').each((i, e) => {
                            animar(e, i % 2 == 0 ? 'mostrar_izquierda' : 'mostrar_derecha')
                        })
                        animar($(modal).find('.mensaje-ajax')[0], 'ocultar_hacia_abajo')
                        animar($(modal).find('[data-anterior]')[0], 'mostrar_izquierda', 300);
                        animar($(modal).find('[data-siguiente]')[0], 'ocultar_derecha', 300);

                        break;
                }
                console.log(4)

            })
            FilePond.registerPlugin(
                FilePondPluginFileValidateType,
                FilePondPluginImageExifOrientation,
                FilePondPluginImagePreview,
                FilePondPluginImageCrop,
                FilePondPluginImageResize,
                FilePondPluginImageTransform,
                FilePondPluginImageEdit
            );
            FilePond.setOptions({
                labelIdle: '<span class="flex label-filepon-erika"><svg style="position:relative;bottom:4px;" xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13a1 1 0 0 0-1 1v.38l-1.48-1.48a2.79 2.79 0 0 0-3.93 0l-.7.7l-2.48-2.48a2.85 2.85 0 0 0-3.93 0L4 12.6V7a1 1 0 0 1 1-1h7a1 1 0 0 0 0-2H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3v-5a1 1 0 0 0-1-1M5 20a1 1 0 0 1-1-1v-3.57l2.9-2.9a.79.79 0 0 1 1.09 0l3.17 3.17l4.3 4.3Zm13-1a.9.9 0 0 1-.18.53L13.31 15l.7-.7a.77.77 0 0 1 1.1 0L18 17.21Zm4.71-14.71l-3-3a1 1 0 0 0-.33-.21a1 1 0 0 0-.76 0a1 1 0 0 0-.33.21l-3 3a1 1 0 0 0 1.42 1.42L18 4.41V10a1 1 0 0 0 2 0V4.41l1.29 1.3a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.42" /></svg><span>Arrastra y suelta para subir fotografia o</span><span class="ml-2 filepond--label-action"> Buscar</span></span>',
            });
            $('[type="file"]').each((i, e) => {
                if ($(e).hasClass('circle-erk')) {
                    FilePond.create(e, {
                        acceptedFileTypes: ['image/avif', 'image/jpg', 'image/jpeg', 'image/png', 'image/webp', 'image/gif'],
                        imageCropAspectRatio: '1:1',
                        stylePanelLayout: 'compact circle',
                        allowImageCrop: true,
                        imageResizeMode: 'cover',
                        styleLoadIndicatorPosition: 'center bottom',
                        styleProgressIndicatorPosition: 'right bottom',
                        styleButtonRemoveItemPosition: 'left bottom',
                        styleButtonProcessItemPosition: 'right bottom',
                        imagePreviewHeight: 100,
                        dropValidation: true,
                        beforeAddFile: (file) => {
                            console.log('DROPED')
                            const allowedExtensions = ['avif', 'jpeg', 'jpg', 'png', 'webp', 'gif'];
                            const fileExtension = file.filename.split('.').pop().toLowerCase();

                            // Verificar si la extensión no está permitida
                            if (!allowedExtensions.includes(fileExtension)) {
                                return false; // Denegar el archivo
                            }
                            return true;
                        }
                    })
                } else {
                    FilePond.create(e, {
                        imageResizeMode: 'cover',
                        imagePreviewHeight: 200
                    });
                }
                console.log(e)
            });
            document.addEventListener('FilePond:removefile', (e) => {
                $('.filepond--drop-label').removeAttr('style')
            });
            //FilePond.parse(document.body);
            window.ocultarDrawer = function(sel) {
                window.animar($(sel)[0], 'ocultar_derecha', 100)
                window.animar($(`[data-overlayed="${sel}"]`)[0], 'opacidadOut', 200)
            }
            //muestra el panel de la derecha o cajon de mensajes
            window.mostrarDrawer = function(sel) {
                window.animar($(sel)[0], 'mostrar_izquierda', 100)
                window.animar($(`[data-overlayed="${sel}"]`)[0], 'opacidadIn', 200)
            }
            window.tiempoTranscurrido = function(fechaStr) {
                const fecha = new Date(fechaStr);
                const ahora = new Date();
                const diferencia = ahora - fecha;
                const segundos = Math.floor(diferencia / 1000);
                const minutos = Math.floor(segundos / 60);
                const horas = Math.floor(minutos / 60);
                const dias = Math.floor(horas / 24);

                if (segundos < 60) {
                    return "ahora";
                } else if (minutos < 60) {
                    return `hace ${minutos} ${minutos === 1 ? "minuto" : "minutos"}`;
                } else if (horas < 24) {
                    return `hace ${horas} ${horas === 1 ? "hora" : "horas"}`;
                } else {
                    // Si es mayor a 24 horas, devolver la fecha y la hora en formato legible con AM/PM
                    const opciones = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: 'numeric',
                        minute: 'numeric',
                        hour12: true // Formato 12 horas con AM/PM
                    };
                    return fecha.toLocaleDateString('es-ES', opciones);
                }
            };

            $('button[data-overlayed-toogle="#erika-mensajes"]').on('click', async function() {
                let data = await erika.getAll(`./php/db/get.php?a=mensajes&u=<?php if (isset($_SESSION["USUARIO_ID"])) {
                                                                                    echo $_SESSION["USUARIO_ID"];
                                                                                } else echo -1; ?>`);
                mixer_mensajes.dataset(data).then(() => {
                    window.lazyCaller();
                    dominant();
                });
            })

            $('[data-overlayed-toogle]').on('click', function() {
                if ($($(this).data('overlayedToogle')).data('active')) {
                    ocultarDrawer($(this).data('overlayedToogle'));
                    $($(this).data('overlayedToogle')).data('active', false)
                } else {
                    mostrarDrawer($(this).data('overlayedToogle'));
                    $($(this).data('overlayedToogle')).data('active', true)
                }
            });
            <?php require './php/bg.php' ?>

            $('#menulateraladmin [data-seccion]').each(function() {
                console.log(activeurl)
                switch (activeurl) {
                    case "/":
                    case "":
                    case "/home":
                    case '/index.php':
                        $(this).data('last', 'seccion-home')
                        break;
                    default:
                        let s = `seccion-${activeurl.replaceAll('/','')}`;
                        $(this).data('last', s);
                        break;
                };
            });
            window.setPlayers = function() {
                window.audioObj = document.querySelector('#audio1');
                window.canvasObj = document.querySelector('#canvasAudio');
                var supportsAudio = !!document.createElement("audio").canPlayType;
                if (supportsAudio) {
                    // initialize plyr
                    player = new Plyr("#audio1", {
                        controls: ["restart", "play", "progress", "current-time", "duration", "mute", "volume", "download"]
                    });
                    // initialize playlist and controls
                    var index = 0,
                        playing = false,
                        mediaPath = "http://localhost/LMSERK/assets/media/audio/",
                        extension = ".mp3",
                        tracks = [{
                                track: 0,
                                name: "Dawn - Tie a Yellow Ribbon Round the Ole Oak Tree",
                                size: "8.18 MB",
                                duration: "3:29",
                                file: "audio1"
                            }, {
                                track: 1,
                                name: "Roy Orbison - Oh pretty woman",
                                size: "7.99 MB",
                                duration: "2:58",
                                file: "audio2"
                            },
                            {
                                track: 2,
                                name: "Pixies - Where Is My Mind_",
                                size: "8.96 MB",
                                duration: "3:49",
                                file: "audio3"
                            },
                            {
                                track: 3,
                                name: "Radiohead - Paranoid Android",
                                size: "14.98 MB",
                                duration: "6:23",
                                file: "audio4"
                            },



                        ],
                        videoFiles = [{
                            track: 0,
                            name: "Dawn - Tie a Yellow Ribbon Round the Ole Oak Tree",
                            size: "8.18 MB",
                            duration: "3:29",
                            file: "audio1"
                        }],

                        buildPlaylist = $.each(tracks, function(key, value) {
                            var trackNumber = value.track,
                                trackName = value.name,
                                size = value.size,
                                trackDuration = value.duration;

                            $("#lista-audios ul").append(
                                `<li tabindex="0" class="glass-bg px-1 py-2 mb-2 hover:bg-[#000]/50 pl-2">
                                    <div class="plItem flex justify-between">
                                        <span class="plNum flex">
                                            <span class="counterplay bg-[#3F51B5] mr-2">${trackNumber+1}</span>
                                            <span data-i="${trackNumber}" class="play-button flex w-[2rem] h-[2rem] flex items-center p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-gray-200">
                                                    <?php include './php/svg/play.svg' ?>
                                            </span>
                                        </span>
                                        <span class="plTitle w-full ml-5">${trackName}</span>
                                        <span class="plLength mr-5">${trackDuration}</span>
                                    </div>
                                    <div class="ml-2 p-0 account-profile  mb-2 small w-full flex justify-start  items-center">
                                        <img src="https://images.unsplash.com/photo-1550314124-301ca0b773ae?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=2215&amp;q=80" alt="">
                                        <div class="flex flex-col items-start justify-center ml-5 p-2">
                                            <div class="text-white">Subido por: <b>Erika Angelica Chavez Turcios</b></div>
                                            <div class="text-white">Tamaño: ${size}</div>
                                        </div>
                                    </div>
                             </li>`);
                        }),

                        r = $("#accordion-multimedia").accordion({
                            collapsible: true,
                            heightStyle: "content",
                            active: false
                        }),
                        trackCount = tracks.length,
                        npAction = $("#npAction"),
                        npTitle = $("#npTitle"),
                        audio = $("#audio1")
                        .on("play", function() {
                            try {
                                videojs('videoPlayer-vid').pause()
                            } catch (e) {}
                            playing = true;
                            if (vudio)
                                vudio.dance();
                        })
                        .on("pause", function() {
                            playing = false;
                            if (vudio)
                                vudio.pause();
                        })
                        .on("ended", function() {

                        })
                        .get(0),
                        li = $("#lista-audios li .play-button").on("click", function() {
                            var id = parseInt($(this).data('i'));
                            $('.audioplayer').addClass('show');
                            this.parentElement.parentElement.parentElement.appendChild($('.audioplayer')[0]);
                            // 
                            playTrack(id);
                            //  }
                            console.log(id)
                        }),
                        loadTrack = function(id) {
                            if (tracks.length == 0)
                                return
                            $(".plSel").removeClass("plSel");
                            $("#lista-audios li:eq(" + id + ")").addClass("plSel");
                            npTitle.text(tracks[id].name);
                            index = id;
                            audio.src = mediaPath + tracks[id].file + extension;

                            if (!vudio) {
                                window.vudio = new Vudio(window.audioObj, window.canvasObj, {
                                    effect: 'waveform', // waveform, circlewave, circlebar, lighting (4 visual effect)
                                    accuracy: 128, // number of freqBar, must be pow of 2.
                                    width: $('#audio0')[0].offsetWidth || 256, // canvas width
                                    height: 50, // canvas height
                                    waveform: {
                                        maxHeight: 50, // max waveform bar height
                                        minHeight: 1, // min waveform bar height
                                        spacing: 1, // space between bars
                                        color: '#F0F', // string | [string] color or waveform bars
                                        shadowBlur: 0, // blur of bars
                                        shadowColor: '#f00',
                                        fadeSide: true, // fading tail
                                        horizontalAlign: 'center', // left/center/right, only effective in 'waveform'/'lighting'
                                        verticalAlign: 'bottom' // top/middle/bottom, only effective in 'waveform'/'lighting'
                                    }
                                });
                                $(window.audioObj).data('window.vudio', window.vudio);
                            } else {
                                if (window.canvasObj.width == 0) {
                                    updateCanvaSize();
                                }
                                window.vudio.dance()
                            }
                            updateDownload(id, audio.src);
                        },
                        updateDownload = function(id, source) {
                            player.on("loadedmetadata", function() {
                                $('a[data-plyr="download"]').attr("href", source);
                            });
                        },
                        playTrack = function(id) {
                            console.log('play -' + id)
                            loadTrack(id);
                            audio.play();
                        };
                    extension = audio.canPlayType("audio/mpeg") ? ".mp3" : audio.canPlayType("audio/ogg") ? ".ogg" : "";
                    loadTrack(index);


                    var videoPlayer,
                        videoFiles = [{
                            track: 0,
                            name: "video 1",
                            size: "8.18 MB",
                            duration: "3:29",
                            file: "1.mp4"
                        }],
                        buildPlaylistVideo = $.each(videoFiles, function(key, value) {
                            var trackNumber = value.track,
                                trackName = value.name,
                                size = value.size,
                                file = value.file,
                                trackDuration = value.duration;

                            $("#video-lista ul").append(
                                `<li tabindex="0" childclick="play-button" class="glass-bg px-1 py-2 mb-2 hover:bg-[#000]/50 pl-2">
                                    <div class="plItem flex justify-between">
                                        <span class="plNum flex">
                                            <span class="counterplay bg-[#3F51B5] mr-2">${trackNumber+1}</span>
                                            <span data-video="http://localhost/LMSERK/assets/media/video/${file}" class="play-button flex w-[2rem] h-[2rem] flex items-center p-1 rounded-full bg-[#000000]/50 dark:bg-[#ffffff61] group border cursor-pointer border-transparent hover:border-gray-200">
                                                    <?php include './php/svg/play.svg' ?>
                                            </span>
                                        </span>
                                        <span class="plTitle w-full ml-5">${trackName}</span>
                                        <span class="plLength mr-5">${trackDuration}</span>
                                    </div>
                                    <div class="ml-2 p-0 account-profile  mb-2 small w-full flex justify-start  items-center">
                                        <img src="https://images.unsplash.com/photo-1550314124-301ca0b773ae?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=2215&amp;q=80" alt="">
                                        <div class="flex flex-col items-start justify-center ml-5 p-2">
                                            <div class="text-white">Subido por: <b>Erika Angelica Chavez Turcios</b></div>
                                            <div class="text-white">Tamaño: ${size}</div>
                                        </div>
                                    </div>
                             </li>`
                            );
                        }),
                        li = $("#video-lista li .play-button").on("click", function() {
                            var vid = $(this).data('video');
                            $('.videoPlayer').removeClass('hidden');
                            this.parentElement.parentElement.parentElement.appendChild($('.videoPlayer')[0]);
                            videoPlayer.src(vid)
                            videoPlayer.play()

                        }),
                        videoPlayer = videojs('videoPlayer-vid', {
                            controls: true,
                            height: 200,
                            width: 500
                        });
                    videoPlayer.on('play', () => {
                        if (player) player.pause();
                    })

                } else {
                    // no audio support
                    $(".column").addClass("hidden");
                    var noSupport = $("#audio1").text();
                    $(".container").append('<p class="no-support">' + noSupport + "</p>");
                }
            }
            const match = activeurl.match(/curso-detalle-(\d+)/);
            let numeral = match ? match[1] : null;
            if (numeral) {
                verDetallesCurso(numeral);
            }
            new AirDatepicker("#datelateral", {
                timepicker: false, // Permite la selección de horas
                inline: true,
                selectedDates: new Date(),
                locale: EsLocale,
            });

            window.abrirChat = function(id_contacto) {
                console.log(id_contacto)
                animar($('#li_list_chats')[0], 'ocultar_derecha', 300);
                animar($('#li_enviar_mensaje')[0], 'mostrar_derecha', 300)
                animar($('#atras_chat')[0], 'mostrar_izquierda', 300);
                $('#enviar-mensaje-textor').data('id', id_contacto);
                window.cargarMensajes(id_contacto);
            }
            window.abrirChatnoContacto = function(id_contacto) {
                console.log(id_contacto)
                $('#enviar-mensaje-textor').data('id', id_contacto);
                    animar($('#li_list_contactos')[0], 'ocultar_derecha', 300);
                    animar($('#li_enviar_mensaje')[0], 'mostrar_derecha', 300)
                    animar($('#atras_chat')[0], 'mostrar_izquierda', 300)
                }

            window.cargarMensajes = function(id_contacto) {

                try {
                    if (window.mixer_mensaje_lista);
                    window.mixer_mensaje_lista.destroy();
                } catch (error) {

                }
                $('#listado_de_mensajes').html('');
                window.mixer_mensaje_lista = mixitup($('#listado_de_mensajes')[0], {
                    data: {
                        uidKey: 'id_mensaje',
                        dirtyCheck: true
                    },
                    render: {
                        target: addmensajeLista
                    },
                    selectors: {
                        target: '[data-ref="item"]'
                    },
                    controls: {
                        enable: false
                    },
                    animation: {
                        enable: false
                    },
                    callbacks: {
                        onMixEnd: function(state) {
                            window.lazyCaller()
                        }
                    }
                });


                $.ajax({
                    url: `./php/db/get.php?a=mensajes_contacto&u=${id_contacto}`,
                    method: 'GET',
                    processData: false,
                    contentType: false,
                    timeout: 5000
                }).done(function(data) {
                    console.log(data)
                    window.mixer_mensaje_lista.dataset(data).then(()=>{
                        $('#listado_de_mensajes').animate({scrollTop: $('#listado_de_mensajes').prop("scrollHeight")}, 10);
                    })
                })
            }


            $('#radio-mensaje-2').on('change', function() {
                if (this.checked) {
                    animar($('#li_list_contactos')[0], 'mostrar_izquierda', 300);
                    animar(document.querySelector("#li_list_chats"), 'ocultar_izquierda', 200);
                    animar($('#li_enviar_mensaje')[0], 'ocultar_izquierda', 300);
                    animar($('#atras_chat')[0], 'ocultar_derecha', 300)



                    $.ajax({
                        url: `./php/db/get.php?a=contactos&u=${window.usuario}`,
                        method: 'GET',
                        processData: false,
                        contentType: false,
                        timeout: 5000
                    }).done(function(data) {
                        mixer_contactos.dataset(data).then(() => {});
                        window.lazyCaller();
                        dominant();
                    })
                }
            })
            $('#radio-mensaje-1').on('change', function() {
                if (this.checked) {
                    animar(document.querySelector("#li_list_chats"), 'mostrar_derecha', 200);
                    animar($('#li_list_contactos')[0], 'ocultar_derecha', 300);
                    animar($('#li_enviar_mensaje')[0], 'ocultar_izquierda', 300);
                    animar($('#atras_chat')[0], 'ocultar_derecha', 300)
                }
            })
            $('#atras_chat').on('click', function() {
                if ($('#radio-mensaje-1')[0].checked) {
                    animar($('#li_list_chats')[0], 'mostrar_izquierda', 300);
                } else {
                    animar($('#li_list_contactos')[0], 'mostrar_izquierda', 300);

                }
                animar($('#li_enviar_mensaje')[0], 'ocultar_izquierda', 300);
                animar($('#atras_chat')[0], 'ocultar_derecha', 300)
            })
            $('#enviar-mensaje-textor').on('submit', function(event) {
                event.preventDefault();
                if (this["text-msg"].value.length === 0)
                    return
                let formData = new FormData();
                let id = $(this).data('id')

                formData.append('a', 'enviar_mensaje')
                formData.append('id_amigo', id)
                formData.append('msg', this["text-msg"].value)
                this.reset();
                $.ajax({
                    url: `./php/db/post.php`,
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    timeout: 5000,
                    data: formData
                }).done(function(data) {
                    console.log(data)

                    $.ajax({
                        url: `./php/db/get.php?a=mensajes_contacto&u=${id}`,
                        method: 'GET',
                        processData: false,
                        contentType: false,
                        timeout: 5000
                    }).done(function(data) {
                        console.log(data)
                        window.mixer_mensaje_lista.dataset(data).then(() => {
                            $('#listado_de_mensajes').animate({scrollTop: $('#listado_de_mensajes').prop("scrollHeight")}, 10);
                        })
                    })
                })
            })
            window.connectWebSocket = function() {
                // Reemplaza 'ws://localhost:8080' con la URL de tu servidor WebSocket
                window.socket = new WebSocket(window.ipSocket);

                // Evento que se dispara cuando la conexión se establece
                window.socket.onopen = function(event) {
                    console.log('Conexión establecida con el servidor WebSocket');

                    // Envía el nombre de usuario al servidor
                    if (window.usuario) {
                        const connectMessage = JSON.stringify({
                            action: 'connect',
                            username: window.usuario
                        });
                        window.socket.send(connectMessage);
                    }
                };

                // Evento que se dispara cuando se recibe un mensaje del servidor
                window.socket.onmessage = function(event) {
                    const response = JSON.parse(event.data);
                    console.log('Mensaje del servidor:', response);
                };

                // Evento que se dispara cuando la conexión se cierra
                window.socket.onclose = function(event) {
                    console.log('Conexión cerrada:', event);
                    // Intenta reconectar después de 5 segundos
                    setTimeout(connectWebSocket, 5000);
                };

                // Evento que se dispara en caso de error
                window.socket.onerror = function(error) {
                    console.log('Error en la conexión:', error);
                };
            }

            // Función para verificar la conexión cada 5 segundos
            window.checkConnection = function() {
                if (window.socket && window.socket.readyState !== WebSocket.OPEN) {
                    console.log('La conexión se ha perdido. Intentando reconectar...');
                    connectWebSocket();
                }
            }

            // Iniciar la conexión WebSocket
            connectWebSocket();

            // Verificar la conexión cada 5 segundos
            setInterval(checkConnection, 5000);


            setTimeout(() => {
                setTooltip()
                $('.gradient-bg').removeClass('loading');
                $('.loader-video-container video')[0].style.display = 'none';
            }, 1000);
        });
    </script>

    <?php require './partial/formulario_nuevo_curso.php' ?>
    <?php require './partial/formulario_nueva_categoria.php' ?>
    <?php require './partial/formulario_nuevo_instructor.php' ?>
    <?php require './partial/formulario_nuevo_usuario.php' ?>
    <?php require './partial/formulario_nuevo_estudiante.php' ?>
    <?php require './partial/nueva_inscripcion.php' ?>
    <?php require './partial/froala.php' ?>

    <script type="text/javascript" src="./assets/js/froala_editor.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/align.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/char_counter.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/code_beautifier.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/code_view.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/colors.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/draggable.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/emoticons.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/entities.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/file.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/font_size.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/font_family.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/fullscreen.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/image.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/image_manager.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/line_breaker.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/inline_style.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/link.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/lists.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/paragraph_format.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/paragraph_style.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/quick_insert.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/quote.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/table.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/save.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/url.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/video.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/help.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/print.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/special_characters.min.js"></script>
    <script type="text/javascript" src="./assets/js/plugins/word_paste.min.js"></script>
    <script type="text/javascript" src="./assets/js/languages/es.js"></script>

    <script>
        (function() {
            // Definir los iconos personalizados
            FroalaEditor.DefineIcon('Guardar', {
                SRC: './php/svg/guardar.svg',
                ALT: 'Guardar Sección',
                template: 'image'
            });
            FroalaEditor.DefineIcon('cancelar', {
                SRC: './php/svg/cancelar.svg',
                ALT: 'Cancelar Sección',
                template: 'image'
            });
            FroalaEditor.DefineIcon('erika_archivos', {
                SRC: './php/svg/cancelar.svg',
                ALT: 'Mis Archivos',
                template: 'image'
            });

            // Registrar el comando "Guardar"
            FroalaEditor.RegisterCommand('Guardar', {
                title: 'Guardar Sección',
                focus: true,
                undo: true,
                icon: 'Guardar',
                refreshAfterCallback: true,
                callback: function() {
                    if ($('#seccion_nombre').val().length === 0) {
                        swal.fire({
                            title: "Ingrese el nombre de la seccion",
                            icon: "warning"
                        })
                        return
                    }
                    let formData = new FormData();
                    let esActualizar =  localStorage.getItem('editando_seccion');
                    localStorage.removeItem('editando_seccion');
                    if(esActualizar){
                        formData.append('a', 'actualizar_seccion');
                        formData.append('id', esActualizar);
                    }
                    else
                    formData.append('a', 'crear_seccion');
                    formData.append('html', this.html.get().replaceAll(`<p data-f-id="pbf" style="text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;">Powered by <a href="https://www.froala.com/wysiwyg-editor?pb=1" title="Froala Editor">Froala Editor</a></p>`, ''))
                    this.html.set('');
                    formData.append('id_curso', localStorage.getItem('curso'))
                    formData.append('nombre', $('#seccion_nombre').val())
                    $.ajax({
                            url: './php/db/post.php',
                            method: 'POST',
                            processData: false, // Prevent jQuery from automatically transforming the data into a query string
                            contentType: false, // Prevent jQuery from overriding the Content-Type header
                            timeout: 5000 // Set the timeout in milliseconds (5000 ms = 5 seconds)
                                ,
                            data: formData
                        }).done(function(data) {
                            console.log(data)
                            swal.fire({
                                title: esActualizar?"Se actualizo la seccion":"Se cre la seccion",
                                icon: "success"
                            }).then(() => {
                                localStorage.removeItem('editando_seccion');
                                window.cerrarFormulario('froala-container', false);
                                window.mixer_secciones.dataset(data.secciones);
                                window.mixer_secciones.forceRender()
                                window.location.reload();
                            })

                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            swal.fire({
                                title: "Algo salio mal",
                                icon: "warning"
                            }).then(() => {
                                window.cerrarFormulario('froala-container', false)
                            })
                        });

                }
            });

            // Registrar el comando "Cancelar"
            FroalaEditor.RegisterCommand('cancelar', {
                title: 'Cancelar',
                focus: true,
                undo: true,
                icon: 'cancelar',
                refreshAfterCallback: true,
                callback: function() {
                    this.html.set(''); // Limpiar el contenido del editor
                    window.cerrarFormulario('froala-container', false)
                    localStorage.removeItem('editando_seccion')
                }
            });
            // Registrar el comando "Cancelar"
            FroalaEditor.RegisterCommand('erika_archivos', {
                title: 'Mis Archivos',
                focus: true,
                undo: true,
                icon: 'erika_archivos',
                refreshAfterCallback: true,
                callback: function() {
                    //this.html.set(''); // Limpiar el contenido del editor
                    // window.cerrarFormulario('froala-container', false)
                }
            });

            // Iniciar el editor Froala
            new FroalaEditor("#editorTexto", {
                language: "es",
                placeholderText: "Ingrese su contenido",

                // Configuración de los botones en la toolbar
                toolbarButtons: {
                    moreText: {
                        buttons: ["Guardar", "cancelar", "bold", "italic", "underline", "strikeThrough", "subscript", "superscript", "fontFamily", "fontSize", "textColor", "backgroundColor", "inlineClass", "inlineStyle", "clearFormatting"],
                        align: "left",
                        buttonsVisible: 55
                    },
                    moreParagraph: {
                        buttons: ["alignLeft", "alignCenter", "formatOLSimple", "alignRight", "alignJustify", "formatOL", "formatUL", "paragraphFormat", "paragraphStyle", "lineHeight", "outdent", "indent", "quote"],
                        align: "left",
                        buttonsVisible: 0
                    },
                    moreRich: {
                        buttons: ["insertImage", "insertVideo", "insertFile", "erika_archivos", "insertLink", "insertTable", "emoticons", "fontAwesome", "specialCharacters", "embedly", "insertHR"],
                        align: "left",
                        buttonsVisible: 4
                    },
                    moreMisc: {
                        buttons: ["undo", "redo", "print", "spellChecker", "html", "help"],
                        align: "right",
                        buttonsVisible: 2
                    }
                },

                // Subida de archivos
                fileUploadParam: 'file_param',
                fileUploadURL: './php/subir_archivo.php',
                fileUploadParams: {
                    file_type: 'file' // Indicar que se está subiendo un archivo general
                        ,
                    id_curso: localStorage.getItem('curso')
                },
                fileUploadMethod: 'POST',
                fileMaxSize: 20 * 1024 * 1024, // 20MB para archivos generales
                fileAllowedTypes: ['*'], // Permitir cualquier tipo de archivo

                // Subida de imágenes
                imageUploadParam: 'file_param',
                imageUploadURL: './php/subir_archivo.php',
                imageUploadParams: {
                    file_type: 'image' // Indicar que se está subiendo una imagen
                        ,
                    id_curso: localStorage.getItem('curso')
                },
                imageUploadMethod: 'POST',
                imageMaxSize: 10 * 1024 * 1024, // 10MB para imágenes
                imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif', 'webp'],

                // Subida de videos
                videoUploadParam: 'file_param',
                videoUploadURL: './php/subir_archivo.php',
                videoUploadParams: {
                    file_type: 'video',
                    id_curso: localStorage.getItem('curso') // Indicar que se está subiendo un video
                },
                videoUploadMethod: 'POST',
                videoMaxSize: 50 * 1024 * 1024, // 50MB para videos
                videoAllowedTypes: ['mp4', 'webm', 'ogg'],

                // Eventos de subida de archivo
                events: {
                    'file.uploaded': function(response) {
                        console.log('Archivo subido correctamente', response);
                    },
                    'file.error': function(error, response) {
                        console.log('Error al subir archivo', error);
                    }
                }
            });
        })();
    </script>
</body>
<?php ob_end_flush(); ?>

</html>