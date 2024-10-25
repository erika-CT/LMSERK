
--funcion para actualizar las fechas de modificacion
CREATE OR REPLACE FUNCTION update_fecha_modificacion()
RETURNS TRIGGER AS $$
BEGIN
        NEW.fecha_modificacion = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TABLE categoria(
    id_categoria SERIAL PRIMARY KEY,
    foto TEXT NULL,
    nombre VARCHAR(128) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE
);


CREATE TABLE rol (
    id_rol SERIAL PRIMARY KEY,
    nombre VARCHAR(256) NOT NULL,
    activo BOOLEAN DEFAULT TRUE
);

CREATE TABLE usuario (
    id_usuario SERIAL PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(150) UNIQUE NOT NULL,
    contra VARCHAR(255) NOT NULL,
    sexo VARCHAR(1) NOT NULL,
    foto TEXT NULL,
    fecha_nacimiento DATE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_inicio_sesion TIMESTAMP NULL,
    correo_verificado BOOLEAN DEFAULT FALSE,
    token_verificacion VARCHAR(255) NULL,
    token_expiracion TIMESTAMP NULL,
    bloqueado BOOLEAN DEFAULT FALSE,
    activo BOOLEAN DEFAULT TRUE,
    rol INTEGER NULL REFERENCES rol(id_rol) ON DELETE NO ACTION
);


CREATE TABLE curso (
    id_curso SERIAL PRIMARY KEY,
    nombre VARCHAR(128) NOT NULL,
    nombre_corto VARCHAR(20) NOT NULL,
    foto TEXT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_inicio TIMESTAMP NULL,
    fecha_fin TIMESTAMP NULL,
    activo BOOLEAN DEFAULT TRUE,
    visible BOOLEAN DEFAULT TRUE,
    es_de_pago BOOLEAN DEFAULT FALSE,
    precio decimal(10,2) null,
    id_categoria INTEGER NULL REFERENCES categoria(id_categoria) ON DELETE NO ACTION,
    id_instructor INTEGER NULL REFERENCES usuario(id_usuario) ON DELETE NO ACTION
);


ALTER TABLE curso
ADD COLUMN codigo_inscripcion character varying(50),
ADD COLUMN 	permite_inscribir BOOLEAN DEFAULT FALSE,  
ADD COLUMN sin_codigo BOOLEAN DEFAULT FALSE; 

CREATE INDEX idx_curso_categoria ON curso(id_categoria);

--funcion para cambiar el rol de un usuario si es rol es eliminado
CREATE
OR REPLACE FUNCTION cambiar_rol_al_eliminar() RETURNS TRIGGER AS $$
BEGIN
Update public.usuario set rol = null where usuario.rol = OLD.id_rol;
RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_rol_usuario BEFORE DELETE ON rol FOR EACH ROW EXECUTE FUNCTION cambiar_rol_al_eliminar();

CREATE TABLE curso_permiso (
    id_curso_permiso SERIAL PRIMARY KEY,
    nombre VARCHAR(256) NOT NULL,
    id_curso INTEGER NOT NULL REFERENCES curso(id_curso) ON DELETE CASCADE,
    roles_permitidos INTEGER [] NULL
);

CREATE TABLE mensaje (
    id_mensaje SERIAL PRIMARY KEY,
    id_usuario INTEGER NOT NULL REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    id_destinatario INTEGER NOT NULL REFERENCES usuario(id_usuario) ON DELETE NO ACTION,
    mensaje TEXT NULL,
    es_archivo BOOLEAN DEFAULT FALSE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    editado BOOLEAN DEFAULT FALSE,
    leido  BOOLEAN DEFAULT FALSE
);

CREATE TABLE amigo (
    id_amigo INTEGER NOT NULL REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    id_usuario INTEGER NOT NULL REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   
);

CREATE TABLE foro (
    id_foro SERIAL PRIMARY KEY,
    id_curso INTEGER NOT NULL REFERENCES curso(id_curso) ON DELETE CASCADE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_inicio TIMESTAMP NULL,
    fecha_fin TIMESTAMP NULL,
    activo BOOLEAN DEFAULT TRUE,
    visible BOOLEAN DEFAULT TRUE
);

CREATE TABLE foro_mensaje (
    id_mensaje SERIAL PRIMARY KEY,
    id_usuario INTEGER NOT NULL REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    id_foro INTEGER NOT NULL REFERENCES foro(id_foro) ON DELETE NO ACTION,
    mensaje TEXT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE foro_mensaje_edit (
    id_mensaje_edit SERIAL PRIMARY KEY,
    id_mensaje INTEGER NOT NULL REFERENCES foro_mensaje(id_mensaje) ON DELETE CASCADE,
    mensaje TEXT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT unique_id_mensaje_edit_id_mensaje UNIQUE (id_mensaje_edit, id_mensaje)
);


CREATE TABLE seccion_curso (
    id_seccion SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NULL,
    ruta VARCHAR(256) NOT NULL,
    id_curso INTEGER NOT NULL REFERENCES curso(id_curso) ON DELETE CASCADE,
    orden INTEGER NULL,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT unique_id_seccion_id_curso UNIQUE (id_seccion, id_curso)
);

CREATE TABLE seccion_item (
    id_seccion_item SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NULL,
    id_seccion INTEGER NOT NULL REFERENCES seccion_curso(id_seccion) ON DELETE CASCADE,
    CONSTRAINT unique_id_seccion_item_id_seccion UNIQUE (id_seccion_item, id_seccion)
);

CREATE TABLE seccion_item_carpeta (
    id_seccion_item_carpeta SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NULL,
    id_seccion_item INTEGER NOT NULL REFERENCES seccion_item(id_seccion_item) ON DELETE CASCADE
);

CREATE TABLE seccion_curso_carpeta (
    id_seccion_curso_carpeta SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NULL,
    id_seccion INTEGER NOT NULL REFERENCES seccion_curso(id_seccion) ON DELETE CASCADE
);

CREATE TABLE tarea (
    id_tarea SERIAL PRIMARY KEY,
    tipo VARCHAR(10) NULL,
    ruta VARCHAR(256) NOT NULL,
    id_curso INTEGER NOT NULL REFERENCES curso(id_curso) ON DELETE CASCADE,
    es_archivo BOOLEAN DEFAULT FALSE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_inicio TIMESTAMP NULL,
    fecha_fin TIMESTAMP NULL,
    permite_editar BOOLEAN DEFAULT TRUE,
    modo_borrador BOOLEAN DEFAULT FALSE,
    permite_envios_tras_cierre BOOLEAN DEFAULT FALSE,
    activo BOOLEAN DEFAULT TRUE
);

CREATE TABLE curso_calificacion (
    id_calificacio SERIAL PRIMARY KEY,
    id_tarea INTEGER NOT NULL REFERENCES tarea(id_tarea) ON DELETE CASCADE,
    calificacion numeric(2, 7) NOT NULL DEFAULT 0,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--Crear la tabla de ajustes
CREATE TABLE ajuste (
    clave VARCHAR(255) PRIMARY KEY,
    valor TEXT,
    tipo VARCHAR(50),
    descripcion TEXT,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE inscripcion (
    id_inscripcion  SERIAL PRIMARY KEY,
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_usuario INTEGER NOT NULL REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    id_curso INTEGER NOT NULL REFERENCES curso(id_curso) ON DELETE CASCADE,
    CONSTRAINT unique_id_usuario_id_curso UNIQUE (id_usuario, id_curso)
);
-- Función para obtener un ajuste
CREATE OR REPLACE FUNCTION obtener_ajuste(p_clave VARCHAR(255))
RETURNS TEXT AS $$
DECLARE
    v_valor TEXT;
BEGIN
    SELECT valor INTO v_valor FROM ajustes_sistema WHERE clave = p_clave;
    RETURN v_valor;
END;
$$ LANGUAGE plpgsql;

-- Función para establecer un ajuste
CREATE OR REPLACE FUNCTION establecer_ajuste(
    p_clave VARCHAR(255),
    p_valor TEXT,
    p_tipo VARCHAR(50) DEFAULT 'string',
    p_descripcion TEXT DEFAULT NULL
)
RETURNS VOID AS $$
BEGIN
    INSERT INTO ajustes_sistema (clave, valor, tipo, descripcion)
    VALUES (p_clave, p_valor, p_tipo, p_descripcion)
    ON CONFLICT (clave) DO UPDATE
    SET valor = EXCLUDED.valor,
        tipo = EXCLUDED.tipo,
        descripcion = EXCLUDED.descripcion;
END;
$$ LANGUAGE plpgsql;


/*


-- Ejemplos de uso
SELECT establecer_ajuste('nombre_sitio', 'Mi Sitio Web', 'string', 'Nombre del sitio web');
SELECT establecer_ajuste('max_usuarios', '1000', 'integer', 'Número máximo de usuarios permitidos');
SELECT establecer_ajuste('modo_mantenimiento', 'false', 'boolean', 'Indica si el sitio está en modo mantenimiento');
*/










CREATE TRIGGER update_timestamp_curso_calificacion BEFORE
UPDATE
    ON curso_calificacion FOR EACH ROW EXECUTE FUNCTION update_fecha_modificacion();

CREATE TRIGGER update_timestamp_ajuste BEFORE
UPDATE
    ON ajuste FOR EACH ROW EXECUTE FUNCTION update_fecha_modificacion();

CREATE TRIGGER update_timestamp_curso BEFORE
UPDATE
    ON curso FOR EACH ROW EXECUTE FUNCTION update_fecha_modificacion();

CREATE TRIGGER update_timestamp_foro BEFORE
UPDATE
    ON foro FOR EACH ROW EXECUTE FUNCTION update_fecha_modificacion();

CREATE TRIGGER update_timestamp_seccion_curso BEFORE
UPDATE
    ON seccion_curso FOR EACH ROW EXECUTE FUNCTION update_fecha_modificacion();

CREATE TRIGGER update_timestamp_categoria BEFORE
UPDATE
    ON categoria FOR EACH ROW EXECUTE FUNCTION update_fecha_modificacion();

INSERT INTO
    public.rol(nombre, activo)
VALUES
('Super Administrador', TRUE),
('Administrador', TRUE),
('Instructor', TRUE),
('Estudiante', TRUE);

---VOLCADO USUARIO
INSERT INTO
    public.usuario(
        nombres,--1
        apellidos,--2
        correo,--3
        contra,--4
        foto,--5
        ultimo_inicio_sesion,--6
        correo_verificado,--7
        token_verificacion,--8
        token_expiracion,--9
        bloqueado,--10
        activo, --11
        rol, --12
        sexo, --13
        fecha_nacimiento --14
    ) 
VALUES
    (
        'Erika Angelica',--1
        'Chavez Turcios',--2
        'erika.chavez@catolica.edu.sv',--3
        '$2y$10$VLYtavxUE3iOMcAaeOnrteoVxTTugZN6EFnOFFXRjXjDJToz7yIea',--4 Admin123!
        './assets/media/img/usuarios/erk-thumb.jpeg',--5
        NULL,--6
        FALSE,--7
        NULL,--8
        NULL,--9
        FALSE,--10
        TRUE, --11
        1, --12
        'F', --13
        '2024-03-25'
    );


    INSERT INTO
    public.usuario(
        nombres,--1
        apellidos,--2
        correo,--3
        contra,--4
        foto,--5
        ultimo_inicio_sesion,--6
        correo_verificado,--7
        token_verificacion,--8
        token_expiracion,--9
        bloqueado,--10
        activo,
        rol, --12
        sexo, --13
        fecha_nacimiento --14
    ) 
VALUES
    (
        'Maria del Carmen',--1
        'Chavez Turcios',--2
        'maria.chavez@catolica.edu.sv',--3
        '$2y$10$VLYtavxUE3iOMcAaeOnrteoVxTTugZN6EFnOFFXRjXjDJToz7yIea',--4
        NULL,--5
        NULL,--6
        FALSE,--7
        NULL,--8
        NULL,--9
        FALSE,--10
        TRUE, --11
        4, --12
        'F', --13
        '2024-03-25'
    );


    INSERT INTO
    public.usuario(
        nombres,--1
        apellidos,--2
        correo,--3
        contra,--4
        foto,--5
        ultimo_inicio_sesion,--6
        correo_verificado,--7
        token_verificacion,--8
        token_expiracion,--9
        bloqueado,--10
        activo,
         rol, --12
        sexo, --13
        fecha_nacimiento --14
    ) 
VALUES
    (
        'Karen',--1
        'Chavez Turcios',--2
        'karen.chavez@catolica.edu.sv',--3
        '$2y$10$VLYtavxUE3iOMcAaeOnrteoVxTTugZN6EFnOFFXRjXjDJToz7yIea',--4
        NULL,--5
        NULL,--6
        FALSE,--7
        NULL,--8
        NULL,--9
        FALSE,--10
        TRUE, --11
        4, --12
        'F', --13
        '2024-03-25'
    );
insert into amigo (id_amigo,id_usuario)
VALUES
(1,2),
(3,1);


insert into mensaje(id_usuario,id_destinatario,mensaje)
VALUES (1,2,'Hola'),
 (1,2,'Hola x2'),
 (1,3,'Hola Karen');



CREATE OR REPLACE FUNCTION obtener_ultimos_mensajes(usuario_id INTEGER)
RETURNS TABLE (
    id_interlocutor TEXT,
    fecha_registro TIMESTAMP,
    mensaje TEXT,
    foto TEXT,
    destinatario character varying(100),
	conectado Boolean
) AS $$
BEGIN
    RETURN QUERY
    WITH ranked_messages AS (
        SELECT 
            CASE 
                WHEN m.id_usuario = usuario_id THEN m.id_destinatario
                ELSE m.id_usuario
            END AS id_interlocutor,
            m.fecha_registro,
            m.mensaje,
            u.foto,
			u.nombres AS destinatario,
            ROW_NUMBER() OVER (
                PARTITION BY 
                    CASE 
                        WHEN m.id_usuario = usuario_id THEN m.id_destinatario
                        ELSE m.id_usuario
                    END
                ORDER BY m.fecha_registro DESC
            ) AS rn
        FROM 
            mensaje m
        JOIN 
            usuario u ON (
                (m.id_destinatario = u.id_usuario AND m.id_usuario = usuario_id) OR
                (m.id_usuario = u.id_usuario AND m.id_destinatario = usuario_id)
            )
        WHERE 
            m.id_usuario = usuario_id OR m.id_destinatario = usuario_id
    )
    SELECT 
        'erk_' || rm.id_interlocutor,
        rm.fecha_registro,
        rm.mensaje,
        rm.foto,
		rm.destinatario,
		FALSE
    FROM 
        ranked_messages rm
    WHERE 
        rm.rn = 1
    ORDER BY 
        rm.fecha_registro DESC;
END;
$$ LANGUAGE plpgsql;


--select obtener_ultimos_mensajes(1);


create view instructores as
SELECT 
	u.id_usuario,
	u.nombres,
	u.apellidos,
	u.correo,
	u.activo,
	u.bloqueado,
	u.foto,
	u.fecha_registro,
	u.fecha_modificacion,
	u.ultimo_inicio_sesion,
	Count(c.id_curso) as cursos
	from usuario as u
	left join curso as c on c.id_instructor = u.id_usuario
	where u.rol = 3 and u.activo is true and u.bloqueado is false
	group by  u.id_usuario;


create view get_instructores as
SELECT id_usuario, nombres, apellidos, correo, sexo, foto, fecha_nacimiento, fecha_registro, fecha_modificacion, ultimo_inicio_sesion, correo_verificado,  bloqueado, activo, rol
FROM public.usuario where rol = 3;

create view get_estudiante as
SELECT id_usuario, nombres, apellidos, correo, sexo, foto, fecha_nacimiento, fecha_registro, fecha_modificacion, ultimo_inicio_sesion, correo_verificado,  bloqueado, activo, rol
FROM public.usuario where rol = 4;

create view get_usuario as
SELECT id_usuario, nombres, apellidos, correo, sexo, foto, fecha_nacimiento, fecha_registro, fecha_modificacion, ultimo_inicio_sesion, correo_verificado,  bloqueado, activo, rol
FROM public.usuario;


create view todos_instructores as
SELECT 
	u.id_usuario,
	u.nombres,
	u.apellidos,
	u.correo,
	u.activo,
	u.bloqueado,
	u.sexo,
	CASE 
               WHEN u.sexo = 'F' THEN 
                   (CASE WHEN u.foto IS NULL OR u.foto = '' 
                        THEN './assets/media/img/site/estudiante_f-thumb.jpg' 
                        ELSE u.foto 
                   END)
               ELSE 
                   (CASE WHEN u.foto IS NULL OR u.foto = '' 
                        THEN './assets/media/img/site/estudiante_m-thumb.jpg' 
                        ELSE u.foto 
                   END)
           END AS foto,
	u.fecha_registro,
	u.fecha_modificacion,
	u.ultimo_inicio_sesion,
	Count(c.id_curso) as cursos
	from usuario as u
	left join curso as c on c.id_instructor = u.id_usuario
	where u.rol = 3
	group by  u.id_usuario;


CREATE OR REPLACE FUNCTION obtener_mensajes_conversacion(usuario_id INTEGER, amigo_id INTEGER)
RETURNS TABLE (
    id_mensaje INTEGER,
    id_emisor INTEGER,
    id_receptor INTEGER,
    fecha_registro TIMESTAMP,
    mensaje TEXT,
    foto TEXT,
    nombre_emisor VARCHAR(100),
    editado BOOLEAN,
    leido BOOLEAN
) AS $$
BEGIN
    RETURN QUERY
    SELECT 
        m.id_mensaje,
        m.id_usuario AS id_emisor,
        m.id_destinatario AS id_receptor,
        m.fecha_registro,
        m.mensaje,
        u.foto,
        u.nombres AS nombre_emisor,
        m.editado,
        m.leido
    FROM 
        mensaje m
    JOIN 
        usuario u ON m.id_usuario = u.id_usuario
    WHERE 
        (m.id_usuario = usuario_id AND m.id_destinatario = amigo_id) OR
        (m.id_usuario = amigo_id AND m.id_destinatario = usuario_id)
    ORDER BY 
        m.fecha_registro ASC;
END;
$$ LANGUAGE plpgsql;



--select obtener_mensajes_conversacion(1 , 2 )


create view categoria_activas as
select * from categoria where activo is true;

create view todos_estudiantes as
SELECT 
	u.id_usuario,
	u.nombres,
	u.apellidos,
	u.correo,
	u.activo,
	u.bloqueado,
	u.sexo,
	CASE 
               WHEN u.sexo = 'F' THEN 
                   (CASE WHEN u.foto IS NULL OR u.foto = '' 
                        THEN './assets/media/img/site/estudiante_f-thumb.jpg' 
                        ELSE u.foto 
                   END)
               ELSE 
                   (CASE WHEN u.foto IS NULL OR u.foto = '' 
                        THEN './assets/media/img/site/estudiante_m-thumb.jpg' 
                        ELSE u.foto 
                   END)
           END AS foto,
	u.fecha_registro,
	u.fecha_modificacion,
	u.ultimo_inicio_sesion,
	Count(c.id_curso) as cursos
	from usuario as u
	left join curso as c on c.id_instructor = u.id_usuario
	where u.rol = 4
	group by  u.id_usuario;


create view todos_usuarios as
SELECT 
	u.id_usuario,
	u.nombres,
	u.apellidos,
	u.correo,
	u.activo,
	u.bloqueado,
	u.sexo,
	CASE 
               WHEN u.sexo = 'F' THEN 
                   (CASE WHEN u.foto IS NULL OR u.foto = '' 
                        THEN './assets/media/img/site/estudiante_f-thumb.jpg' 
                        ELSE u.foto 
                   END)
               ELSE 
                   (CASE WHEN u.foto IS NULL OR u.foto = '' 
                        THEN './assets/media/img/site/estudiante_m-thumb.jpg' 
                        ELSE u.foto 
                   END)
           END AS foto,
	u.fecha_registro,
	u.fecha_modificacion,
	u.ultimo_inicio_sesion,
    r.nombre as rol,
	Count(c.id_curso) as cursos
	from usuario as u
	left join curso as c on c.id_instructor = u.id_usuario
	left join rol as r on r.id_rol = u.rol
	group by  u.id_usuario,r.nombre;




create view todas_categorias as
select 
	c.id_categoria,
	c.nombre,
	case
        when c.foto is null
        or c.foto = '' then './assets/media/img/site/categoria_placeholder.jpg'
        else c.foto
    end as foto,
    c.activo,
	count(cu.id_curso) as cursos
	from categoria as c
left join curso as cu on cu.id_categoria = c.id_categoria
group by c.id_categoria;



CREATE OR REPLACE VIEW public.todos_los_cursos AS
SELECT 
    c.id_curso,
    c.nombre AS curso_nombre,
    c.nombre_corto AS curso_nombre_corto,
    CASE
        WHEN c.foto IS NULL OR c.foto = '' THEN './assets/media/img/site/cursos-bg.jpg'
        ELSE c.foto
    END AS curso_foto,
    c.fecha_inicio AS curso_fecha_inicio,
    c.fecha_fin AS curso_fecha_fin,
    c.activo AS curso_activo,
    c.visible AS curso_visible,
    c.es_de_pago AS curso_es_de_pago,
    c.precio AS curso_precio,
    ca.id_categoria,
    CASE
        WHEN u.sexo = 'F' THEN 
            CASE
                WHEN u.foto IS NULL OR u.foto = '' THEN './assets/media/img/site/estudiante_f-thumb.jpg'
                ELSE u.foto
            END
        ELSE 
            CASE
                WHEN u.foto IS NULL OR u.foto = '' THEN './assets/media/img/site/estudiante_m-thumb.jpg'
                ELSE u.foto
            END
    END AS usuario_foto,
    CASE
        WHEN ca.foto IS NULL OR ca.foto = '' THEN './assets/media/img/site/categoria_placeholder.jpg'
        ELSE ca.foto
    END AS categoria_foto,
    COUNT(i.id_usuario) AS inscritos,
    CASE
        WHEN CURRENT_TIMESTAMP > c.fecha_fin THEN 'Finalizado'
        WHEN CURRENT_TIMESTAMP BETWEEN c.fecha_inicio AND c.fecha_fin THEN 'Impartiéndose'
        WHEN CURRENT_TIMESTAMP < c.fecha_inicio THEN 'Próximo'
    END AS estado_curso,
    CASE
        WHEN CURRENT_TIMESTAMP > c.fecha_fin THEN 3  -- Finalizado
        WHEN CURRENT_TIMESTAMP BETWEEN c.fecha_inicio AND c.fecha_fin THEN 2  -- Impartiéndose
        WHEN CURRENT_TIMESTAMP < c.fecha_inicio THEN 1  -- Próximo
    END AS estado_code,
    (u.nombres || ' ' || u.apellidos) AS instructor,
    c.permite_inscribir,  -- Nueva columna: permite inscribir
    c.sin_codigo          -- Nueva columna: sin código
FROM 
    curso c
JOIN 
    usuario u ON u.id_usuario = c.id_instructor
JOIN 
    categoria ca ON ca.id_categoria = c.id_categoria
LEFT JOIN 
    inscripcion i ON i.id_curso = c.id_curso
GROUP BY 
    c.id_curso, 
    ca.id_categoria, 
    u.sexo, 
    c.nombre, 
    c.nombre_corto, 
    c.foto, 
    c.fecha_inicio, 
    c.fecha_fin, 
    c.activo, 
    c.visible, 
    c.es_de_pago, 
    c.precio, 
    u.foto, 
    ca.foto,
	u.nombres,
	u.apellidos,
    c.permite_inscribir,   -- Asegurar que estas columnas se incluyan en el GROUP BY
    c.sin_codigo;



create view todos_los_cursos_activos as
select c.id_curso,
c.nombre as curso_nombre,
c.nombre_corto as curso_nombre_corto,
case when c.foto is null or c.foto ='' then './assets/media/img/site/cursos-bg.jpg' else c.foto end  as curso_foto,
c.fecha_inicio as curso_fecha_inicio,
c.fecha_fin as curso_fecha_fin,
c.activo as curso_activo,
c.visible as curso_visible,
c.es_de_pago as curso_es_de_pago,
c.precio as curso_precio,
ca.id_categoria,
case when ca.foto is null or ca.foto='' then './assets/media/img/site/categoria_placeholder.jpg' else ca.foto end as categoria_foto
	from curso as c
inner join usuario as u on u.id_usuario = c.id_instructor
inner join categoria as ca on ca.id_categoria = c.id_categoria
where c.activo is true;


CREATE OR REPLACE FUNCTION obtener_curso_con_todo(id_curso_param INTEGER)
RETURNS TABLE (
    id_curso INTEGER,
    curso_nombre character varying(128),
    curso_nombre_corto character varying(128),
    curso_foto TEXT,
    curso_fecha_inicio TIMESTAMP,
    curso_fecha_fin TIMESTAMP,
    curso_activo BOOLEAN,
    curso_visible BOOLEAN,
    curso_es_de_pago BOOLEAN,
    curso_precio NUMERIC,
    id_categoria INTEGER,
    usuario_foto TEXT,
    instructor TEXT,
    categoria_foto TEXT,
    categoria_nombre TEXT,
    estado_curso TEXT,
    estado_code INTEGER,
    codigo_inscripcion character varying(50),  -- Nueva columna
    permite_inscribir BOOLEAN,                  -- Nueva columna
    sin_codigo BOOLEAN                          -- Nueva columna
) AS $$
BEGIN
    RETURN QUERY
    SELECT 
        c.id_curso,
        c.nombre AS curso_nombre,
        c.nombre_corto AS curso_nombre_corto,
        COALESCE(c.foto, './assets/media/img/site/cursos-bg.jpg') AS curso_foto,
        c.fecha_inicio AS curso_fecha_inicio,
        c.fecha_fin AS curso_fecha_fin,
        c.activo AS curso_activo,
        c.visible AS curso_visible,
        c.es_de_pago AS curso_es_de_pago,
        c.precio AS curso_precio,
        ca.id_categoria,
        u.foto AS usuario_foto,
        (u.nombres || ' ' || u.apellidos) AS instructor,
        COALESCE(ca.foto, './assets/media/img/site/categoria_placeholder.jpg') AS categoria_foto,
        CAST(ca.nombre AS TEXT) AS categoria_nombre,
        CASE
            WHEN CURRENT_TIMESTAMP > c.fecha_fin THEN 'Finalizado'
            WHEN CURRENT_TIMESTAMP BETWEEN c.fecha_inicio AND c.fecha_fin THEN 'Impartiéndose'
            WHEN CURRENT_TIMESTAMP < c.fecha_inicio THEN 'Próximo'
        END AS estado_curso,
        CASE
            WHEN CURRENT_TIMESTAMP > c.fecha_fin THEN 3
            WHEN CURRENT_TIMESTAMP BETWEEN c.fecha_inicio AND c.fecha_fin THEN 2
            WHEN CURRENT_TIMESTAMP < c.fecha_inicio THEN 1
        END AS estado_code,
        c.codigo_inscripcion,                       -- Nueva columna
        c.permite_inscribir,                        -- Nueva columna
        c.sin_codigo                                 -- Nueva columna
    FROM 
        curso AS c
    INNER JOIN 
        usuario AS u ON u.id_usuario = c.id_instructor
    INNER JOIN 
        categoria AS ca ON ca.id_categoria = c.id_categoria
    WHERE 
        c.id_curso = id_curso_param;
END;
$$ LANGUAGE plpgsql;






--SELECT * FROM obtener_curso_con_todo(4);


CREATE OR REPLACE PROCEDURE actualizar_ultimo_inicio_sesion(id INT)
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE usuario
    SET ultimo_inicio_sesion = NOW()
    WHERE id_usuario = id;
    
    -- Verifica si el usuario fue encontrado y actualizado
    IF NOT FOUND THEN
        RAISE EXCEPTION 'Usuario con id % no existe.', id;
    END IF;
END;
$$;



create view instructores_activos_inactivos_bloqueados_count as
select 
	(SELECT COUNT(id_usuario) from usuario where rol = 3 and activo = true) as activos,
	(SELECT COUNT(id_usuario) from usuario where rol = 3 and activo = false) as inactivos,
	(SELECT COUNT(id_usuario) from usuario where rol = 3 and bloqueado = true) as bloqueados;

create view estudiantes_activos_inactivos_bloqueados_count as
select 
	(SELECT COUNT(id_usuario) from usuario where rol = 4 and activo = true) as activos,
	(SELECT COUNT(id_usuario) from usuario where rol = 4 and activo = false) as inactivos,
	(SELECT COUNT(id_usuario) from usuario where rol = 4 and bloqueado = true) as bloqueados;



create view usuarios_activos_inactivos_bloqueados_count as
select 
	(SELECT COUNT(id_usuario) from usuario where activo = true) as activos,
	(SELECT COUNT(id_usuario) from usuario where activo = false) as inactivos,
	(SELECT COUNT(id_usuario) from usuario where bloqueado = true) as bloqueados;



CREATE OR REPLACE FUNCTION obtener_estudiantes_sin_inscribir(id_curso_param INT)
RETURNS TABLE (
    id_usuario INT,
    nombres TEXT,
    foto TEXT
) 
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT u.id_usuario,
           u.nombres || ' ' || u.apellidos as nombres,
           CASE 
               WHEN u.sexo = 'F' THEN 
                   (CASE WHEN u.foto IS NULL OR u.foto = '' 
                        THEN './assets/media/img/site/estudiante_f-thumb.jpg' 
                        ELSE u.foto 
                   END)
               ELSE 
                   (CASE WHEN u.foto IS NULL OR u.foto = '' 
                        THEN './assets/media/img/site/estudiante_m-thumb.jpg' 
                        ELSE u.foto 
                   END)
           END AS foto
    FROM usuario u
    WHERE u.rol = 4 
      AND u.id_usuario NOT IN (
          SELECT i.id_usuario 
          FROM inscripcion i 
          WHERE i.id_curso = id_curso_param
      );
END;
$$;



CREATE OR REPLACE FUNCTION obtener_estudiantes_inscritos(id_curso_param INT)
RETURNS TABLE (
    id_usuario INT,
    nombres TEXT,
    foto TEXT,
	fecha_inscripcion timestamp 
) 
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT u.id_usuario,
           u.nombres || ' ' || u.apellidos as nombres,
           CASE 
               WHEN u.sexo = 'F' THEN 
                   (CASE WHEN u.foto IS NULL OR u.foto = '' 
                        THEN './assets/media/img/site/estudiante_f-thumb.jpg' 
                        ELSE u.foto 
                   END)
               ELSE 
                   (CASE WHEN u.foto IS NULL OR u.foto = '' 
                        THEN './assets/media/img/site/estudiante_m-thumb.jpg' 
                        ELSE u.foto 
                   END)
           END AS foto,
		   i.fecha_inscripcion
    FROM usuario u
	inner join inscripcion as i on i.id_usuario = u.id_usuario
    WHERE u.id_usuario IN (
          SELECT i.id_usuario 
          FROM inscripcion i 
          WHERE i.id_curso = id_curso_param
      );
END;
$$;

CREATE TABLE archivo_curso (
    id_archivo SERIAL PRIMARY KEY,
    ruta VARCHAR(256) NOT NULL,
    id_usuario INTEGER NOT NULL REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    id_curso INTEGER NOT NULL REFERENCES curso(id_curso) ON DELETE CASCADE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT unique_id_archivos_id_usuario UNIQUE (id_archivo, id_usuario)
);

	
	CREATE OR REPLACE FUNCTION obtener_ultimo_orden(id_curso_param INTEGER)
	RETURNS INTEGER AS $$
	DECLARE
	    ultimo INTEGER;
	BEGIN
	    SELECT COALESCE(MAX(orden), 0) INTO ultimo
	    FROM seccion_curso
	    WHERE id_curso = id_curso_param;
	
	    RETURN ultimo;
	END;
	$$ LANGUAGE plpgsql;
CREATE OR REPLACE FUNCTION esta_inscrito_en_curso(
    id_curso_param INT, 
    id_usuario_param INT
) 
RETURNS BOOLEAN 
LANGUAGE plpgsql
AS $$
DECLARE
    resultado BOOLEAN;
BEGIN
    SELECT 
        EXISTS (
            SELECT 1 
            FROM inscripcion i 
            WHERE i.id_curso = id_curso_param AND i.id_usuario = id_usuario_param
        ) INTO resultado;  -- Verifica si el usuario está inscrito

    RETURN resultado;  -- Devuelve el resultado
END;
$$;




CREATE OR REPLACE FUNCTION esta_inscrito_en_curso(
    id_curso_param INT, 
    id_usuario_param INT
) 
RETURNS BOOLEAN 
LANGUAGE plpgsql
AS $$
DECLARE
    resultado BOOLEAN;
BEGIN
    SELECT 
        EXISTS (
            SELECT 1 
            FROM inscripcion i 
            WHERE i.id_curso = id_curso_param AND i.id_usuario = id_usuario_param
        ) INTO resultado;  -- Verifica si el usuario está inscrito

    RETURN resultado;  -- Devuelve el resultado
END;
$$;


CREATE OR REPLACE FUNCTION obtener_cursos_inscritos_con_todo(id_usuario_param INTEGER)
RETURNS TABLE (
    id_curso INTEGER,
    curso_nombre character varying(128),
    curso_nombre_corto character varying(128),
    curso_foto TEXT,
    curso_fecha_inicio TIMESTAMP,
    curso_fecha_fin TIMESTAMP,
    curso_activo BOOLEAN,
    curso_visible BOOLEAN,
    curso_es_de_pago BOOLEAN,
    curso_precio NUMERIC,
    id_categoria INTEGER,
    usuario_foto TEXT,
    instructor TEXT,
    categoria_foto TEXT,
    categoria_nombre TEXT,
    estado_curso TEXT,
    estado_code INTEGER
) AS $$
BEGIN
    RETURN QUERY
    SELECT 
        c.id_curso,
        c.nombre AS curso_nombre,
        c.nombre_corto AS curso_nombre_corto,
        COALESCE(c.foto, './assets/media/img/site/cursos-bg.jpg') AS curso_foto,
        c.fecha_inicio AS curso_fecha_inicio,
        c.fecha_fin AS curso_fecha_fin,
        c.activo AS curso_activo,
        c.visible AS curso_visible,
        c.es_de_pago AS curso_es_de_pago,
        c.precio AS curso_precio,
        ca.id_categoria,
        u.foto AS usuario_foto,
        (u.nombres || ' ' || u.apellidos) AS instructor,
        COALESCE(ca.foto, './assets/media/img/site/categoria_placeholder.jpg') AS categoria_foto,
        CAST(ca.nombre AS TEXT) AS categoria_nombre, -- Asegurar el tipo aquí
        CASE
            WHEN CURRENT_TIMESTAMP > c.fecha_fin THEN 'Finalizado'
            WHEN CURRENT_TIMESTAMP BETWEEN c.fecha_inicio AND c.fecha_fin THEN 'Impartiéndose'
            WHEN CURRENT_TIMESTAMP < c.fecha_inicio THEN 'Próximo'
        END AS estado_curso,
        CASE
            WHEN CURRENT_TIMESTAMP > c.fecha_fin THEN 3
            WHEN CURRENT_TIMESTAMP BETWEEN c.fecha_inicio AND c.fecha_fin THEN 2
            WHEN CURRENT_TIMESTAMP < c.fecha_inicio THEN 1
        END AS estado_code
    FROM 
        inscripcion AS i
    INNER JOIN 
        curso AS c ON c.id_curso = i.id_curso
    INNER JOIN 
        usuario AS u ON u.id_usuario = c.id_instructor
    INNER JOIN 
        categoria AS ca ON ca.id_categoria = c.id_categoria
    WHERE 
        i.id_usuario = id_usuario_param;
END;
$$ LANGUAGE plpgsql;




CREATE OR REPLACE FUNCTION obtener_amigos_y_mensajes_no_leidos(id_usuario_param INTEGER)
RETURNS TABLE (
    id_amigo INTEGER,
    id_usuario INTEGER,
    contacto TEXT,
    usuario_foto TEXT,
    sin_leer INTEGER
) AS $$
BEGIN
    RETURN QUERY
    SELECT 
        a.id_amigo,
        a.id_usuario,
        (u.nombres || ' ' || u.apellidos) AS contacto,
        CASE
            WHEN u.sexo = 'F' AND u.rol = 3 THEN
                COALESCE(u.foto, './assets/media/img/site/instructor_f-thumb.jpg')
            WHEN u.sexo = 'M' AND u.rol = 3 THEN
                COALESCE(u.foto, './assets/media/img/site/instructor_m-thumb.jpg')
            WHEN u.sexo = 'F' AND u.rol = 4 THEN
                COALESCE(u.foto, './assets/media/img/site/estudiante_f-thumb.jpg')
            WHEN u.sexo = 'M' AND u.rol = 4 THEN
                COALESCE(u.foto, './assets/media/img/site/estudiante_m-thumb.jpg')
        END AS usuario_foto,
        Cast(COUNT(m.id_mensaje) as INTEGER) AS sin_leer
    FROM  
        amigo AS a
    INNER JOIN 
        usuario AS u ON u.id_usuario = a.id_amigo
    LEFT JOIN 
        obtener_mensajes_conversacion(a.id_usuario, a.id_amigo) m ON m.leido IS FALSE
    WHERE 
        a.id_usuario = id_usuario_param
    GROUP BY 
        a.id_amigo, a.id_usuario, u.nombres, u.apellidos, u.sexo, u.rol, u.foto;
END;
$$ LANGUAGE plpgsql;


CREATE TABLE usuarios_conectados (
    id_usuario INTEGER NOT NULL ,
    CONSTRAINT unique_id_usuario UNIQUE (id_usuario)
);

CREATE OR REPLACE FUNCTION obtener_amigos(id_usuario_param INTEGER)
RETURNS TABLE (
    id_amigo INTEGER,
    id_usuario INTEGER,
    contacto TEXT,
    usuario_foto TEXT,
    conectado BOOLEAN
) AS $$
BEGIN
    RETURN QUERY
    SELECT 
        a.id_amigo,
        a.id_usuario,
        (u.nombres || ' ' || u.apellidos) AS contacto,
        CASE
            WHEN u.sexo = 'F' AND u.rol = 3 THEN
                COALESCE(u.foto, './assets/media/img/site/instructor_f-thumb.jpg')
            WHEN u.sexo = 'M' AND u.rol = 3 THEN
                COALESCE(u.foto, './assets/media/img/site/instructor_m-thumb.jpg')
            WHEN u.sexo = 'F' AND u.rol = 4 THEN
                COALESCE(u.foto, './assets/media/img/site/estudiante_f-thumb.jpg')
            WHEN u.sexo = 'M' AND u.rol = 4 THEN
                COALESCE(u.foto, './assets/media/img/site/estudiante_m-thumb.jpg')
            ELSE
                './assets/media/img/site/default-user-thumb.jpg'  -- Imagen por defecto si no se cumple ninguna condición
        END AS usuario_foto,
        -- Contar si el usuario está conectado
        COUNT(uc.id_usuario) > 0 AS conectado
    FROM  
        amigo AS a
    INNER JOIN 
        usuario AS u ON u.id_usuario = a.id_amigo
    LEFT JOIN 
        public.usuarios_conectados AS uc ON uc.id_usuario = a.id_amigo
    LEFT JOIN 
        obtener_mensajes_conversacion(a.id_usuario, a.id_amigo) AS m ON m.leido IS FALSE
    WHERE 
        a.id_usuario = id_usuario_param
    GROUP BY 
        a.id_amigo, a.id_usuario, u.nombres, u.apellidos, u.sexo, u.rol, u.foto;
END;
$$ LANGUAGE plpgsql;





create view curso_estados_count as
SELECT  
    nombre,
    fecha_inicio,
    fecha_fin,
    CASE 
        WHEN CURRENT_TIMESTAMP > fecha_fin THEN 'Finalizado'
        WHEN CURRENT_TIMESTAMP BETWEEN fecha_inicio AND fecha_fin THEN 'Impartiéndose'
        WHEN CURRENT_TIMESTAMP < fecha_inicio THEN 'Próximo'
    END AS estado_curso
FROM 
    curso;



CREATE OR REPLACE FUNCTION obtener_estado_curso(p_id_curso INTEGER)
RETURNS TABLE(nombre VARCHAR, fecha_inicio TIMESTAMP, fecha_fin TIMESTAMP, estado_curso VARCHAR) AS $$
BEGIN
    RETURN QUERY
    SELECT 
        c.nombre,
        c.fecha_inicio,
        c.fecha_fin,
        CASE 
            WHEN CURRENT_TIMESTAMP > c.fecha_fin THEN 'Finalizado'::VARCHAR
            WHEN CURRENT_TIMESTAMP BETWEEN c.fecha_inicio AND c.fecha_fin THEN 'Impartiéndose'::VARCHAR
            WHEN CURRENT_TIMESTAMP < c.fecha_inicio THEN 'Próximo'::VARCHAR
        END AS estado_curso
    FROM 
        curso c
    WHERE 
        c.id_curso = p_id_curso; -- Usar alias para la tabla
END;
$$ LANGUAGE plpgsql;

