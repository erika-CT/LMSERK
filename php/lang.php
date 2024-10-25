<?php

//plantilla para los requerimientos de la contraseña
$pass_req = "
<ul class='max-w-md space-y-1 text-white list-inside'>
    <li class='flex items-center li-pass-r'>
        <svg class='pass-req w-3.5 h-3.5 me-2 flex-shrink-0' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 20 20'>
            <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z'/>
         </svg>
       Al menos una letra minúscula.
    </li>
    <li class='flex items-center li-pass-r'>
        <svg class='pass-req w-3.5 h-3.5 me-2 flex-shrink-0' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 20 20'>
            <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z'/>
         </svg>
        Al menos una letra mayúscula.
    </li>
    <li class='flex items-center li-pass-r'>
        <svg class='pass-req w-3.5 h-3.5 me-2 text-white flex-shrink-0' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 20 20'>
            <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z'/>
         </svg>
        Al menos un número.
    </li>
    <li class='flex items-center li-pass-r'>
        <svg class='pass-req w-3.5 h-3.5 me-2 text-white flex-shrink-0' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 20 20'>
            <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z'/>
         </svg>
        Al menos un carácter especial.
    </li>
    <li class='flex items-center li-pass-r'>
        <svg class='pass-req w-3.5 h-3.5 me-2 text-white flex-shrink-0' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 20 20'>
            <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z'/>
         </svg>
        Sin espacios.
    </li>
    <li class='flex items-center li-pass-r'>
        <svg class='pass-req w-3.5 h-3.5 me-2 text-white flex-shrink-0' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 20 20'>
            <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z'/>
         </svg>
        8 y 20 caracteres.
    </li>
</ul>";
//-----------------------------------------------------------


//objeto principal de leyandas en el sistema
$lan = [
    //metadatos
    "info" =>
        [
            "idioma" => "es",
            "traducido_por" => "ErkMind Team."
        ],
    //CURSOS--------------------------------
        "cursos" => "Cursos",
        "curso_nuevo" => "Nuevo Curso",
        "curso_nombre" => "Nombre del curso",
        "curso_nombre_corto" => "Nombre corto del Curso",
        "curso_fecha_inicio" => "Fecha de inicio",
        "curso_fecha_fin" => "Fecha de finalización",
        "curso_registrar" => "Crear Curso Nuevo",
        "curso_registrar_otro" => "Crear otro Curso nuevo",
        "curso_categoria" => "Selecciona una Categoría",
        "curso_instructor" => "Selecciona un Instructor",
        "curso_administracion" => "Administración de Cursos",
        "curso_de_pago" => "El curso es de pago",
        "curso_precio" => "Precio del Curso",
        "curso_registrado" => "<span>El curso <b>{{1}}</b> se registró con éxito</span>",
        "curso_visible" => "El curso será visible antes de fecha de inico",
        //--------------------------------

    //CATEGORIAS--------------------------------
        "categorias" => "Categorías",
        "categoria_nueva" => "Nueva Categoría",
        "categoria_nombre" => "Nombre de la Categoría",
        "categoria_registrar" => "Registrar Categoría",
        "categoria_registrar_otra" => "Registrar otra Categoría",
        "categoria_registrada" => "<span>La categoría <b>{{1}}</b> se registró con éxito</span>",
        "categoria_administracion" => "Administración de Categorías",
        "categoria_eliminar" => "¿Está seguro de eliminar la categoría <b>{{1}}</b>?",
        "categoria_eliminar?error" => "No se puede eliminar la categoría <b>{{1}}</b> porque hay {{2}} cursos usandola",
        //--------------------------------

    //GENERAL--------------------------------------------
    "mensajeria"=>"Mensajeria",
    "conectado"=>"Conectado",
    "desconectado"=>"Desconectado",
    "enviar_mensaje"=>"Enviar mensaje",
    "escribe_mensaje"=>"Escribe tu mensaje",
    "siguiente"=>"Siguiente",
    "atras"=>"Atras",
    "usuario_no_encontrado"=>"EL usuario no se encontro",
    //--------------------------------
    //ESTUDIANTES---------------------------------------------
    "estudiantes"=>"Estudiantes",
    "estudiantes_administracion"=>"Administración de Estudiantes",
    "estudiante_nuevo" => "Nuevo Estudiante",
    "estudiante_registrar" => "Registrar Estudiante",
    "estudiante_registrar_otra" => "Registrar otro Estudiante",
    "estudiante_administracion" => "Administración de Estudiantees",
    "estudiante_registrado" => "<span>El estudiante <b>{{1}}</b> se registró con éxito</span>",
    "estudiante_nombre" => "Nombres",
    "estudiante_apellido" => "Apellidos",
    "estudiante_pass" => "Contraseña",
    "estudiantes_administracion" => "Administración de Usuarios",
    "estudiante_correo" => "Correo Electrónico",
    //---------------------------------------------------
    //BOTONES--------------------------------------------
        "reintentar" => "Reintentar",
        "cancelar" => "Cancelar",
        "cerrar" => "Cerrar",
        "aceptar" => "Aceptar",
        "correo" => "Correo Electrónico",
        "pass" => "Contraseña",
        "cerrar_sesion" => "Cerrar Sesión",
        "administracion" => "Administración",
        "filtrar" => "Busqueda Avanzada",
        //--------------------------------------------

    //INICIO Y REGISTRO---------------------------------------------
        "iniciar_sesion" => "Iniciar Sesión",
        "registrarse" => "Registrarse",
        "inicio_externo" => "También puede iniciar sesión con:",
        "registro_externo" => "También puede registrarse con:",
        //--------------------------------------------

    //USUARIOS--------------------------------------------------
        "usuarios" => "Usuarios",
        "usuario_nombre" => "Nombres",
        "usuario_apellido" => "Apellidos",
        "usuario_pass" => "Contraseña",
        "usuarios_administracion" => "Administración de Usuarios",
        "usuario_correo" => "Correo Electrónico",
        "usuario_registrar" => "Registrar Usuario",
        "usuario_registrar_otra" => "Registrar otro Usuario",
        //---------------------------------------------------------------


    //INTRUCTORES----------------------------------------------
    "instructores" => "Instructores",
    "instructor_nuevo" => "Nuevo Instructor",
    "instructor_registrar" => "Registrar Instructor",
    "instructor_registrar_otra" => "Registrar otro Instructor",
    "instructor_administracion" => "Administración de Instructores",
    "instructor_registrado" => "<span>El instructor <b>{{1}}</b> se registró con éxito</span>",
    //------------------------------------------


    "validaciones" => [
        //CATEGORIA--------------------------------------------------
        "categoria_descripcion" => "La descripción es muy extensa por favor reduzca",
        "categoria_nombre" => "Ingrese un nombre de Categoría válida",
        //--------------------------------------------



        //GENERAL---------------------------------------------------
        "requerido" => "Campo Requerido",
        "pass" => "La contraseña ingresada no es válida:<br><span class='ml-4'>" . $pass_req . "</span>",
        "correo" => "El correo no es válido<br><span class='ml-4'>Ejemplo: erkmind@libreria.com</span>",
        "imagenes_validas" => "jpeg, jpg, png, webp, gif",
        "faltan_datos" => "Faltan Datos",
        "imagenes_permitidas" => "<div><span>Solo se permiten imagenes:</span><br><span class='bold'>{{}}</span></div>",
        "archivos_permitidos_1" => "<div><span>Solo se permiten archivos:</span><br><span class='bold'>",
        "archivos_permitidos_2" => "</span></div>",
        //--------------------------------------------

        //INSTRUCTORES-----------------------------------------------------------
        "instructor_nombre"=>"Los nombres ingresados no son validos por favor no ingrese carácteres especiales",
        "instructor_apellido"=>"Los apellidos ingresados no son validos por favor no ingrese carácteres especiales",
        //----------------------------------------------------------------------
        
        //INSTRUCTORES-----------------------------------------------------------
        "estudiante_nombre"=>"Los nombres ingresados no son validos por favor no ingrese carácteres especiales",
        "estudiante_apellido"=>"Los apellidos ingresados no son validos por favor no ingrese carácteres especiales",
        "estudiante_pass" => "La contraseña ingresada no es válida:<br><span class='ml-4'>" . $pass_req . "</span>",
        "estudiante_correo" => "El correo no es válido<br><span class='ml-4'>Ejemplo: example@erkMind.com</span>",
        //----------------------------------------------------------------------



        //CURSOS---------------------------------------------------
        "curso_fecha_inicio" => "La fecha de inicio es invalida",
        "curso_fecha_fin" => "La fecha de finalización es invalida",
        "curso_nombre" => "El nombre del curso es requerido",
        "curso_nombre_corto" => "El nombre corto del curso es requerido",
        "curso_nombre_corto_max" => "<span>El nombre corto debe ser de <b>{{1}}</b> letras</span>",
        "curso_precio" => "Precio del curso es requerido porque ha selecionado que es de pago",
        //--------------------------------------------

        //USUARIOS-------------------------------------------------------------------
        "usuario_nombre" => "Los nombre ingresasos no son válidos<br><span class='ml-4'>Por favor ingrese solo letras</span>",
        "usuario_apellido" => "Los apellidos ingresasos no son válidos<br><span class='ml-4'>Por favor ingrese solo letras</span>",
        "usuario_pass" => "La contraseña ingresada no es válida:<br><span class='ml-4'>" . $pass_req . "</span>",
        "usuario_correo" => "El correo no es válido<br><span class='ml-4'>Ejemplo: example@erkMind.com</span>",
        
        //--------------------------------------------
    ],
    "validaciones_backend" => [
        "error" => [
            "autor_nombre" => "El nombre de Autor esta vacio",
            "no_logeado_rol" => "Está intentando registrar un usuario con rol, Debe iniciar sesion en una cuenta con permisos elevados",
            "no_permiso" => "No tiene permisos para la acción que quiere realizar",
            "usuario_nombre" => "Por favor ingrese su nombre",
            "usuario_apellido" => "Por favor ingrese su apellido",
            "usuario_correo" => "Por favor ingrese su correo electrónico",
            "usuario_contra" => "Por favor ingrese su contraseña",
        ],
        "ok" => [
            "autor" => "El Autor se registró con exito",
            "editorial" => "La Editorial se registró con exito",
            "proveedor" => "El Proveedor se registró con exito",
            "libro" => "El libro se registró con exito",
            "categoria" => "La Categoría se registró con exito",
            "usuario" => "Se registró con exito",
        ]
    ],
    "errores_sql" => [
        "fotografía_autor" => "Ocurrió un error al enlazar la fotografía con el Autor",
        "telefono" => "Ocurrió un error al registrar en telefono",
        "telefono_editorial" => "Ocurrió un error al enlazar el telefono a la Editorial",
        "telefono_proveedor" => "Ocurrió un error al enlazar el telefono con el Proveedor",
        "fotografía" => "Ocurrió un error al guardar la fotografía",
        "logo" => "Ocurrió un error al guardar el logo",
        "editorial" => "Ocurrió un error al registrar la Editorial",
        "imagen" => "Ocurrió un error al guardar la imagen",
        "categoria" => "<span class='text-[#ec484880]'>Ocurrió un error registrando la Categoría <b>{{1}}</b></span>",
        "proveedor" => "Ocurrió un error al registrar el Proveedor",
        "usuario" => "Ocurrió un error al registrar el usuario",
        "usuario_rol" => "Ocurrió un error al establecer rol de usuario",
        "thumb" => "Ocurrió un error al registrar la miniatura",
        "autor" => "Ocurrió un error al registrar el autor",
        "libro_titulo" => "Ocurrió un error al registrar el libro",
    ],
    "errores_php" => [
        "thumb" => "Ocurrió un error al crear la miniatura",
        "extension" => "El formato de imagen no se soporta",
        "ruta" => "Ocurrió un error creando una carpeta",
    ],
    "fechaLang" => [
        "hoy" => "Hoy",
        "limpiar" => "Limpiar",
        "dias" => ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
        "diasCortos" => ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
        "diasMasCortos" => ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
        "meses" => ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        "mesesCortos" => ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        "formato" => "dd-mm-yyyy"
    ],
    "mensajesRegistrar" => [
        "autor_biografia" => "Biografia muy extensa, por favor reduzca",
        "error" => "Opps!, Parece que algo va mal.",
        "categoria" => "¿Registrar la Categoría?",
        "autor" => "¿Registrar el Autor?",
        "proveedor" => "¿Registrar el Proveedor?",
        "editorial" => "¿Registrar la Editorial?",
        "libro" => "¿Registrar el Libro?",
        "usuario" => "¿Enviar datos para Registrarse?",
        "back" => [
            "categoria" => "¿Le gustaria establecer la Categoría registrada para el libro que esta registrando?",
            "autor" => "¿Le gustaria establecer el Autor registrado para el libro que esta registrando?",
            "proveedor" => "¿Le gustaria establecer el Proveedor registrado para el libro que esta registrando?",
            "editorial" => "¿Le gustaria establecer la Editorial registrada para el libro que esta registrando?",
        ],
        "btn" => [
            "ok" => "Registrar",
            "establecer" => "Establecer",
            "cancel" => "Cancelar"
        ]
    ],
    "admin_navbar" => [
        "categoria" => "Administrar Categorías",
        "proveedor" => "Administrar Proveedores",
        "editorial" => "Administrar Editoriales",
        "autor" => "Administrar Autores",
        "libro" => "Administrar Libros",
        "usuario" => "Administrar Usuarios",
        "ajuste" => "Administrar Configuraciones",
        "compra" => "Realizar y Administrar Compras",
        "venta" => "Administrar pedidos y Ventas",
        "oferta" => "Administrar Ofertas",
    ]
];
