
function verDetallesCurso() {
    $(`section#seccion-cursos`).addClass('hide');
    $(`section#seccion-curso-detalle`).removeClass('hide');
    $('#menulateraladmin [data-seccion]').each(function() {
        $(this).data('last', 'seccion-curso-detalle');
    });

    history.pushState(null, '', './' + 'seccion-curso-detalle'.replaceAll('seccion-', ''));
}
document.addEventListener('DOMContentLoaded', () => {
    
    jQuery(function($) {
        "use strict";
        $('*:not([tabindex])').attr('tabindex', '-1');
        new AirDatepicker("#curso_fecha_ini",{ locale: locale});
        $(document).on('keydown', function(event) {
            // Verifica si la tecla presionada es la barra espaciadora
            if (event.which === 32) { // 32 es el código de la barra espaciadora
                const focusedElement = $(document.activeElement);
                // Verifica si el elemento está visible y se puede hacer clic
                if (focusedElement.is('[tabindex]')) {
                    event.preventDefault(); // Prevenir el comportamiento por defecto de la barra espaciadora
                    // Simular un clic en el elemento
                    focusedElement.click();
                }
            }
        });

        $('#menulateraladmin .con-tabs').on('mouseleave', function(e) {
            // Check if the mouse has left the entire element and not just moved to a child
            if (!$(this).is(e.relatedTarget) && !$.contains(this, e.relatedTarget)) {
                console.log('leaving');
                if ($('main').data('float') && !$('#menulateraladmin').hasClass('collapse-menu')) {
                    $('#menulateraladmin').addClass('collapse-menu');
                    $('.offmenu').removeClass('hidden');
                    $('.onmenu').addClass('hidden');
                    $('main').data('colapsado', false);
                }
            }
        });


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



        setTimeout(() => {
            $('.gradient-bg').removeClass('loading');
            $('.loader-video-container video')[0].style.display = 'none';
        }, 500);
        $('#menu-izquierdo-collapser').on('click', function(event) {

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
        /*
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
*/
        function subirMedia() {
            if (player)
                player.pause();
            document.querySelector('#subirMedia').click();
        }



        var activeurl = window.location.pathname.trim().toLowerCase();
        $('#menulateraladmin [data-seccion]').each(function() {
            console.log(activeurl)
            switch (activeurl) {
                case "/":
                case "":
                case "/home":
                case '/index.php':
                    console.log('HOME')
                    $(this).data('last', 'seccion-home')
                    break;
                case "/cursos":
                    $(this).data('last', 'seccion-cursos')
                    break;
                case "/categorias":
                    $(this).data('last', 'seccion-categorias')
                    break;
            };
        });
        $('#menulateraladmin  [data-seccion]').on('click', function(event) {
            if (player)
                player.pause();
            try {
                videojs('videoPlayer-vid').pause()
            } catch (e) {}
            let seccionIdentifier = $(this).data('seccion');
            if (!seccionIdentifier)
                return false;
            $(`section#${$(this).data('last')}`).addClass('hide');
            $(`#menulateraladmin [data-seccion].active`).removeClass('active');
            $(this).addClass('active');
            $('#menulateraladmin [data-seccion]').each(function() {
                $(this).data('last', seccionIdentifier)
            });
            const section = document.querySelector(`section#${this.dataset.seccion}`);
            if (section) {
                $(`section#${$(this).data('seccion')}`).removeClass('hide');
                history.pushState(null, '', './' + seccionIdentifier.replaceAll('seccion-', ''));
            } else {
                fetch(`./php/getView.php?v=${$(this).data('seccion')}`)
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
            console.log(this)
        });



        $('#curso_categoria').select2({
            placeholder: "<?=$lan['curso_categoria']?>", // Placeholder opcional
            ajax: {
                url: './php/db/get.php?a=categorias_select',
                dataType: 'json',
                delay: 250, // Agrega un pequeño retraso para evitar demasiadas solicitudes
                processResults: function(data) {
                    // Asegúrate de que el servidor devuelve los datos en el formato esperado
                    return {
                        results: data.data.map(function(categoria) {
                            return {
                                id: categoria.id, // El campo `id` de la categoría en tu base de datos
                                text: categoria.text // El campo `nombre` de la categoría
                            };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 4 // Mínimo número de caracteres para activar la búsqueda
        });
        document.getElementById('newCurso').addEventListener('click', function() {

            abriForumlario('nuevoCurso')
        });
        
        $('[erika-formulario]').on('submit',function(event){
            event.preventDefault();
            let action = $(this).data('a');
            let formData = new FormData();
            $(this).find('.input-erika').each((i,e)=>{
                let vali = $(e).attr('erika-validator').split(',')
               // if($(i).)
                formData.append(e.id,)
            });
            console.log(this)
        });



        $('[modal-cerrar]').on('click', function() {
            cerrarFormulario(this.getAttribute('modal-cerrar'))
        });

        $('#subirMediaCurso').on('click', subirMedia)
        $('#subirMedia').on('change', function(event) {
            const files = event.target.files; // Acceder a los archivos seleccionados

            if (files.length > 0) {
                console.log('Archivos seleccionados:');

                // Recorrer y mostrar información de los archivos
                for (let i = 0; i < files.length; i++) {
                    console.log(`Archivo ${i + 1}:`);
                    console.log('Nombre:', files[i].name);
                    console.log('Tamaño:', files[i].size, 'bytes');
                    console.log('Tipo:', files[i].type);
                }
            }

        });

        var updateCanvaSizeTimeOut = setTimeout(function() {}, 10); //variable que guarda un timeout para poder cancelarlo con  clearTimeout con el fin de poder cancelar todas las llamadas que provoca el cambio de tamaño de  curso-menu-2 ya que si cambia su tamaño hay que actualizar el canva del vudio  asi que si se llaman 1000 veces se cancelan 999 y se deja ejecutar el ultimo
        var observer = new ResizeObserver(function(entries) { //observador que se ejecuta al cambiar el tamaño de curso-menu-2
            for (let entry of entries) {
                clearTimeout(updateCanvaSizeTimeOut); //cancela el timeout anterior
                updateCanvaSizeTimeOut = setTimeout(function() { //nuevo timeout

                    updateCanvaSize(); //si no se cancela el timeout se llama esta funcion que actualiza el canva de vudio
                    console.log(entry.contentRect.width)

                    if (entry.contentRect.width < 400)
                        $('.project-boxes.jsGridView .project-box-wrapper').css('width', '100%')
                    if (entry.contentRect.width > 400 && entry.contentRect.width < 465)
                        $('.project-boxes.jsGridView .project-box-wrapper').css('width', '90%')
                    if (entry.contentRect.width > 465)
                        $('.project-boxes.jsGridView .project-box-wrapper').css('width', '50%')
                    if (entry.contentRect.width >= 740)
                        $('.project-boxes.jsGridView .project-box-wrapper').css('width', '33%')

                    if (entry.contentRect.width >= 1100)
                        $('.project-boxes.jsGridView .project-box-wrapper').css('width', '23%')
                    $('main').data('float', false);
                    if ($('body').width() <= 600) {
                        $('.project-boxes.jsGridView .project-box-wrapper').css('width', '90%')
                        $('#menulateraladmin').addClass('collapse-menu')
                        $('section').addClass('collapse-menu');
                        $('main').removeClass('translate-x-[12.5rem]').addClass('translate-x-[3rem]');
                        $('main').data('float', true);
                    }




                }, 10); //10 es el delay en milisegundos

            }
        });
        observer.observe(document.getElementById('curso-menu-2')); //se establece es elemento a observar

        function updateCanvaSize() {
            if (vudio) { //si vudio ya fue inicializado 
                vudio.option.width = $('#audio0')[0].offsetWidth; //ancho del padre de vudio
                canvasObj.width = $('#audio0')[0].offsetWidth;
                vudio.width = $('#audio0')[0].offsetWidth;
                vudio.option.height = 50; //5
                vudio.height = 50;
                canvasObj.height = 50;
            }
        }

        //funcion que abre los formularios, donde cual es el objetivo
        function abriForumlario(cual) {
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

            // Animate modal sliding in from the right
            anime({
                targets: modal,
                opacity: [0, 1],
                translateX: ['100%', '0%'], // Slide from 100% to 0%
                easing: 'easeOutQuad',
                duration: 200
            });
        }

        function cerrarFormulario(cual) {
            const overlay = document.getElementById(cual);
            const modal = document.getElementById('modal_' + cual);

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
                        }
                    });
                }
            });
        }
        var supportsAudio = !!document.createElement("audio").canPlayType;
        if (supportsAudio) {
            // initialize plyr
            var player = new Plyr("#audio1", {
                controls: [
                    "restart",
                    "play",
                    "progress",
                    "current-time",
                    "duration",
                    "mute",
                    "volume",
                    "download"
                ]
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
                audioObj = document.querySelector('#audio1'),
                canvasObj = document.querySelector('#canvasAudio'),
                vudio,
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
                     </li>`
                    );
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
                    $(".plSel").removeClass("plSel");
                    $("#lista-audios li:eq(" + id + ")").addClass("plSel");
                    npTitle.text(tracks[id].name);
                    index = id;
                    audio.src = mediaPath + tracks[id].file + extension;

                    if (!vudio) {
                        vudio = new Vudio(audioObj, canvasObj, {
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
                        $(audioObj).data('vudio', vudio);
                    } else {
                        if (canvasObj.width == 0) {
                            updateCanvaSize();
                        }
                        vudio.dance()
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
    });


    $("#accordion-grupos").accordion({
        collapsible: true
    });

    $('#curso-menu-2 .tab').on('click', function() {
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
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginImageResize,
        FilePondPluginImageTransform,
        FilePondPluginFileValidateType
    );
    FilePond.parse(document.body);
   
});