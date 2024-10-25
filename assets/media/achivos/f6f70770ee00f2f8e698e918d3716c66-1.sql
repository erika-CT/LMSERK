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
    END AS estado_code
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
    ca.foto;




create view curso_estados_count
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


drop FUNCTION obtener_curso_con_todo;
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
    estado_curso TEXT,  -- Mantener como character varying
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
        curso AS c
    INNER JOIN 
        usuario AS u ON u.id_usuario = c.id_instructor
    INNER JOIN 
        categoria AS ca ON ca.id_categoria = c.id_categoria
    WHERE 
        c.id_curso = id_curso_param;
END;
$$ LANGUAGE plpgsql;


ALTER TABLE curso
ADD COLUMN codigo_inscripcion character varying(50),
ADD COLUMN 	permite_inscribir BOOLEAN DEFAULT FALSE,  
ADD COLUMN sin_codigo BOOLEAN DEFAULT FALSE; 



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
    (u.nombres || ' ' || u.apellidos) AS instructor
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
	u.apellidos;




CREATE OR REPLACE VIEW todos_los_cursos AS
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
    c.permite_inscribir,         -- Columna permite_inscribir
    c.sin_codigo                 -- Columna sin_codigo
FROM 
    curso AS c
INNER JOIN 
    usuario AS u ON u.id_usuario = c.id_instructor
INNER JOIN 
    categoria AS ca ON ca.id_categoria = c.id_categoria;




drop FUNCTION obtener_mensajes_conversacion;
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
