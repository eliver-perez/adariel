CREATE TABLE ajustes_tipo (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(15) NOT NULL UNIQUE,
    tipo                            VARCHAR(25) NOT NULL
);

INSERT INTO ajustes_tipo(id, codigo, tipo) VALUES(1, 'integer', 'Entero'),
                                                    (2, 'float', 'Float'),
                                                    (3, 'money', 'Money'),
                                                    (4, 'string', 'String'),
                                                    (5, 'json', 'JSON'),
                                                    (6, 'boolean', 'Boolean');

CREATE TABLE ajustes_categoria (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(15) NOT NULL UNIQUE,
    categoria                       VARCHAR(30) NOT NULL
);

INSERT INTO ajustes_categoria(id, codigo, categoria) VALUES(1, 'general', 'General'),
                                                            (2, 'agenda', 'Agenda'),
                                                            (3, 'facturacion', 'Facturacion'),
                                                            (4, 'seguridad', 'Seguridad'),
                                                            (5, 'notificaciones', 'Notificaciones');

CREATE TABLE ajustes (
    id                              VARCHAR(100) PRIMARY KEY,
    descripcion                     VARCHAR(255) NOT NULL,
    valor                           TEXT NOT NULL,
    valor_defecto                   TEXT NOT NULL,
    categoria                       SMALLINT NOT NULL,
    tipo                            SMALLINT NOT NULL DEFAULT 4,
    f_actualizacion                 TIMESTAMP NOT NULL,
    activo                          SMALLINT NOT NULL DEFAULT 1,
    CONSTRAINT FK_ajustes_tipo FOREIGN KEY(tipo) REFERENCES ajustes_tipo(id),
    CONSTRAINT FK_ajustes_categoria FOREIGN KEY(categoria) REFERENCES ajustes_categoria(id)
);

CREATE INDEX idx_ajustes_categoria ON ajustes(categoria);

INSERT INTO ajustes(id, descripcion, valor, valor_defecto, categoria, tipo, f_actualizacion) VALUES('agenda_intervalo_minutos', 'Intervalo de tiempo en minutos para busqueda de bloques de citas', '15', '15', 2, 1, CURRENT_TIMESTAMP);

CREATE TABLE paises (
    id                              SMALLINT PRIMARY KEY,
    pais                            VARCHAR(50) NOT NULL,
    abbr                            VARCHAR(5) DEFAULT NULL,
    lada                            SMALLINT DEFAULT NULL
);

CREATE TABLE estados (
    id                              INTEGER PRIMARY KEY,
    estado                          VARCHAR(80) NOT NULL,
    pais                            SMALLINT NOT NULL,
    CONSTRAINT FK_estados_pais FOREIGN KEY(pais) REFERENCES paises(id)
);

CREATE TABLE municipios (
    id                              INTEGER PRIMARY KEY,
    municipio                       VARCHAR(80) NOT NULL,
    estado                          INTEGER NOT NULL,
    CONSTRAINT FK_municipios_estado FOREIGN KEY(estado) REFERENCES estados(id)
);


CREATE TABLE colonias (
    id                              INTEGER PRIMARY KEY,
    colonia                         VARCHAR(80) NOT NULL,
    municipio                       INTEGER NOT NULL,
    cp                              VARCHAR(5) DEFAULT NULL,
    CONSTRAINT FK_colonias_municipio FOREIGN KEY(municipio) REFERENCES municipios(id)
);

CREATE TABLE unidades (
    id                              VARCHAR(8) PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    unidad                          VARCHAR(40) NOT NULL
);

INSERT INTO unidades (id, codigo, unidad) VALUES
-- Inventario general
('PZA', 'pza', 'Pieza'),
('PAR', 'par', 'Par'),
('CAJ', 'caja', 'Caja'),
('PAQ', 'paq', 'Paquete'),
('BOL', 'bolsa', 'Bolsa'),
('ROL', 'rollo', 'Rollo'),
('FRA', 'frasco', 'Frasco'),
('BOT', 'botella', 'Botella'),
('AMP', 'amp', 'Ampolleta'),
('VIA', 'vial', 'Vial'),
('TUB', 'tubo', 'Tubo'),
('KIT', 'kit', 'Kit'),
('BLI', 'blister', 'Blíster'),
('SOB', 'sobre', 'Sobre'),
('LAT', 'lata', 'Lata'),

-- Líquidos
('ML',  'ml', 'Mililitro'),
('L',   'l', 'Litro'),
('CC',  'cc', 'Centímetro cúbico'),
('GOT', 'gotas', 'Gotas'),
('DL',  'dl', 'Decilitro'),

-- Sólidos / peso
('G',   'g', 'Gramo'),
('MG',  'mg', 'Miligramo'),
('KG',  'kg', 'Kilogramo'),

-- Clínicas / servicios
('APL', 'aplic', 'Aplicación'),
('DOS', 'dosis', 'Dosis'),
('SES', 'sesion', 'Sesión'),
('CUR', 'curacion', 'Curación'),
('SER', 'serv', 'Servicio'),
('TRA', 'trat', 'Tratamiento'),

-- Material médico (opcionales semánticos)
('GAS', 'gasa', 'Gasa'),
('COM', 'comp', 'Compresa'),
('HIS', 'hisopo', 'Hisopo'),
('CAM', 'campo', 'Campo quirúrgico'),
('JER', 'jeringa', 'Jeringa'),
('AGU', 'aguja', 'Aguja');

CREATE TABLE usuarios_tipos (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(30) NOT NULL
);

INSERT INTO usuarios_tipos(id, codigo, tipo) VALUES(1, 'administrador', 'Administrador'),
                                                    (2, 'recepcion', 'Recepción'),
                                                    (3, 'supervisor', 'Supervisor'),
                                                    (4, 'doctor', 'Doctor'),
                                                    (5, 'finanzas', 'Finanzas'),
                                                    (6, 'enfermero', 'Enfermero'),
                                                    (7, 'caja', 'Caja');

CREATE TABLE puestos (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    puesto                          VARCHAR(30)
);

INSERT INTO puestos(id, codigo, puesto) VALUES(1, 'recepcion', 'Recepción'),
                                                (2, 'supervisor', 'Supervisor'),
                                                (3, 'caja', 'Caja'),
                                                (4, 'medico', 'Medico'),
                                                (5, 'enfermero', 'Enfermero'),
                                                (6, 'contabilidad', 'Contabilidad');

CREATE TABLE especialidades (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(30) NOT NULL UNIQUE,
    especialidad                    VARCHAR(40) NOT NULL UNIQUE,
    descripcion                     VARCHAR(512) DEFAULT NULL
);

INSERT INTO especialidades(id, codigo, especialidad) VALUES(1, 'sin-especialidad', 'Sin Especialidad'),
                                                            (2, 'medico-general', 'Medico General'),
                                                            (3, 'podologo', 'Podologo');

CREATE TABLE generos (
    id                              VARCHAR(1) PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    genero                          VARCHAR(15) NOT NULL
);

INSERT INTO generos(id, codigo, genero) VALUES('N', 'N/D', 'N/D'), ('H', 'hombre', 'Hombre'), ('M', 'mujer', 'Mujer');

CREATE TABLE personal_estatus (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    estatus                         VARCHAR(30) NOT NULL
);

INSERT INTO personal_estatus(id, codigo, estatus) VALUES(1, 'active', 'Activo');
INSERT INTO personal_estatus(id, codigo, estatus) VALUES(2, 'not-working', 'Baja');
INSERT INTO personal_estatus(id, codigo, estatus) VALUES(3, 'suspended', 'Suspendido');
INSERT INTO personal_estatus(id, codigo, estatus) VALUES(4, 'vacations', 'Vacaciones');

CREATE TABLE personal (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    rfc                             VARCHAR(14) DEFAULT NULL,
    nombre                          VARCHAR(60) NOT NULL,
    paterno                         VARCHAR(40) DEFAULT NULL,
    materno                         VARCHAR(40) DEFAULT NULL,
    f_nacimiento                    DATE DEFAULT NULL,
    calle                           VARCHAR(120) DEFAULT NULL,
    num_ext                         VARCHAR(12) DEFAULT NULL,
    num_int                         VARCHAR(12) DEFAULT NULL,
    colonia                         INTEGER DEFAULT NULL,
    email                           VARCHAR(255) DEFAULT NULL,
    curp                            VARCHAR(20) DEFAULT NULL,
    telefono                        VARCHAR(40) DEFAULT NULL,
    celular                         VARCHAR(40) DEFAULT NULL,
    genero                          VARCHAR(1) NOT NULL,
    puesto                          SMALLINT NOT NULL,
    estatus                         SMALLINT NOT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP DEFAULT NULL,
    CONSTRAINT FK_personal_colonia FOREIGN KEY(colonia) REFERENCES colonias(id),
    CONSTRAINT FK_personal_genero FOREIGN KEY(genero) REFERENCES generos(id),
    CONSTRAINT FK_personal_puesto FOREIGN KEY(puesto) REFERENCES puestos(id),
    CONSTRAINT FK_personal_estatus FOREIGN KEY(estatus) REFERENCES personal_estatus(id)
);

INSERT INTO personal(id, uuid, nombre, paterno, puesto, genero, estatus, f_registro) VALUES(1, 10, 'Juan', 'Perez', 4, 1, 'H', DATE('now')),
                                                                                            (2, 20, 'Eliver', 'Perez', 4, 1, 'H', DATE('now'));

CREATE TABLE personal_medicos (
    id                              INTEGER NOT NULL,
    cedula                          VARCHAR(12) DEFAULT NULL,
    especialidad                    SMALLINT NOT NULL,
    universidad                     VARCHAR(250) DEFAULT NULL,
    egreso                          SMALLINT DEFAULT NULL,
    universidad_municipio           INTEGER DEFAULT NULL,
    color_agenda                    VARCHAR(7) NOT NULL DEFAULT '#07F',
    CONSTRAINT FK_personalmedicos_id FOREIGN KEY(id) REFERENCES personal(id),
    CONSTRAINT FK_personalmedicos_especialidad FOREIGN KEY(especialidad) REFERENCES especialidades(id)
);

CREATE TABLE personal_altas (
    id                              INTEGER NOT NULL,
    f_alta                          DATE NOT NULL,
    f_baja                          DATE DEFAULT NULL,
    razon_baja                      VARCHAR(512) DEFAULT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP DEFAULT NULL,
    CONSTRAINT FK_personalaltas_id FOREIGN KEY(id) REFERENCES personal(id)
);

CREATE TABLE usuarios (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    nombre                          VARCHAR(120) DEFAULT NULL,
    usuario                         VARCHAR(30) NOT NULL UNIQUE,
    password_hash                   VARCHAR(255) NOT NULL,
    tipo_usuario                    SMALLINT NOT NULL,
    activo                          SMALLINT NOT NULL DEFAULT 1,
    f_registrado                    TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP DEFAULT NULL
);

INSERT INTO usuarios(id, uuid, nombre, usuario, password_hash, tipo_usuario, activo, f_registrado) 
                VALUES(1, 10, 'Admin', 'admin', '$2a$10$Grq/omzeEADhClCAGip3O.wLSYVTi2RwfwZF2RnLjyrrVzbG7XWfC', 1, 1, CURRENT_TIMESTAMP),
                        (2, 20, 'Juan', 'juan', '$2a$10$Grq/omzeEADhClCAGip3O.wLSYVTi2RwfwZF2RnLjyrrVzbG7XWfC', 4, 1, CURRENT_TIMESTAMP),
                        (3, 30, 'Eliver', 'eliver', '$2a$10$Grq/omzeEADhClCAGip3O.wLSYVTi2RwfwZF2RnLjyrrVzbG7XWfC', 4, 1, CURRENT_TIMESTAMP);

CREATE TABLE personal_usuarios (
    personal                        INTEGER NOT NULL,
    usuario                         INTEGER NOT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    CONSTRAINT FK_personalusuarios_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_personalusuarios_usuario FOREIGN KEY(usuario) REFERENCES usuarios(id)
);

INSERT INTO personal_usuarios(personal, usuario, f_registro) VALUES(1, 2, CURRENT_TIMESTAMP),
                                                                    (2, 3, CURRENT_TIMESTAMP);

CREATE TABLE personal_sueldos (
    id                              INTEGER NOT NULL,
    sueldo_anterior                 NUMERIC(18, 2) NOT NULL DEFAULT 0,
    sueldo_actual                   NUMERIC(18, 2) NOT NULL DEFAULT 0,
    actualizo                       INTEGER NOT NULL,
    f_apartir_de                    DATE NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_personalsueldos_id FOREIGN KEY(id) REFERENCES personal(id),
    CONSTRAINT FK_personalsueldos_actualizo FOREIGN KEY(actualizo) REFERENCES usuarios(id)
);

CREATE TABLE empresas (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    empresa                         VARCHAR(120) NOT NULL,
    domicilio                       VARCHAR(255) DEFAULT NULL,
    esta_empresa                    SMALLINT NOT NULL DEFAULT 0
);

/**
*
* REGISTRO DE EMPRESAS SOLO PARA FINES DE PRUEBAS, EN PRODUCCIÓN SE REGISTRARAN DIRECTO EN PLATAFORMA
*
**/
INSERT INTO empresas(id, uuid, empresa, domicilio, esta_empresa) VALUES(1, X'30313866386333612d386630622d376239632d626434332d366631613763396431653535', 'Clinica 1', 'Domicilio Conocido 1', 1);
INSERT INTO empresas(id, uuid, empresa, domicilio, esta_empresa) VALUES(2, X'40313866386333612d386630622d376239632d626434332d366631613763396431653535', 'Clinica 2', 'Domicilio Conocido 2', 0);

CREATE TABLE permisos (
    id                              VARCHAR(30) PRIMARY KEY,
    permiso                         VARCHAR(255) NOT NULL,
    descripcion                     VARCHAR(1024) DEFAULT NULL,
    f_registro                      TIMESTAMP NOT NULL
);

CREATE TABLE permisos_usuarios (
    permiso                         VARCHAR(30) NOT NULL,
    usuario                         INTEGER NOT NULL,
    empresa                         INTEGER NOT NULL,
    uuid                            BLOB NOT NULL UNIQUE,
    valor                           SMALLINT NOT NULL DEFAULT 1,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_permisosusuarios_permiso FOREIGN KEY(permiso) REFERENCES permisos(id),
    CONSTRAINT FK_permisosusuarios_usuario FOREIGN KEY(usuario) REFERENCES usuarios(id),
    CONSTRAINT FK_permisosusuarios_empresa FOREIGN KEY(empresa) REFERENCES empresas(id)
);

CREATE TABLE permisos_usuarios_tipo (
    permiso                         VARCHAR(30) NOT NULL,
    tipo                            SMALLINT NOT NULL,
    empresa                         INTEGER NOT NULL,
    uuid                            BLOB NOT NULL UNIQUE,
    valor                           SMALLINT NOT NULL DEFAULT 1,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_permisosusuariostipo_permiso FOREIGN KEY(permiso) REFERENCES permisos(id),
    CONSTRAINT FK_permisosusuariostipo_tipo FOREIGN KEY(tipo) REFERENCES usuarios_tipos(id),
    CONSTRAINT FK_permisosusuariostipo_empresa FOREIGN KEY(empresa) REFERENCES empresas(id)
);

CREATE TABLE usuarios_sesiones (
    id                              BLOB PRIMARY KEY,      -- UUIDv7
    usuario                         INTEGER NOT NULL,

    token_hash                      BLOB NOT NULL,          -- NUNCA el token en texto

    f_registro                      TIMESTAMP NOT NULL,
    ultima_actividad                TIMESTAMP NOT NULL,
    expira_en                       TIMESTAMP NOT NULL,
    destruida_en                    TIMESTAMP NULL,

    ip                              VARCHAR(255),
    user_agent                      VARCHAR(255),
    dispositivo                     VARCHAR(255),

    motivo_cierre                   VARCHAR(255),

    CONSTRAINT FK_usuariossesiones_usuario FOREIGN KEY (usuario) REFERENCES usuarios(id)
);


/**
*
*   HORARIOS
*
**/

CREATE TABLE plantillas_horarios (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    nombre                          VARCHAR(80) NOT NULL,
    descripcion                     VARCHAR(255) DEFAULT NULL,
    usuario                         INTEGER DEFAULT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_plantillashorarios_usuario FOREIGN KEY(usuario) REFERENCES usuarios(id)
);

CREATE TABLE plantillas_horarios_detalles (
    id                              INTEGER PRIMARY KEY,
    plantilla                       INTEGER NOT NULL,
    uuid                            BLOB NOT NULL UNIQUE,
    dia_semana                      SMALLINT NOT NULL,
    hora_inicio                     TIME NOT NULL,
    hora_fin                        TIME NOT NULL,
    CONSTRAINT FK_plantillashorariosdetalles_plantilla FOREIGN KEY(plantilla) REFERENCES plantillas_horarios(id)
);

CREATE TABLE horarios_laborales (
    id                              INTEGER PRIMARY KEY,
    personal                        INTEGER NOT NULL,
    consultas                       SMALLINT NOT NULL DEFAULT 1,
    plantilla                       INTEGER DEFAULT NULL,
    activo                          SMALLINT NOT NULL DEFAULT 1,
    registro                        INTEGER NOT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_horarioslaborales_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_horarioslaborales_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

INSERT INTO horarios_laborales(id, personal, consultas, activo, registro, f_registro, f_actualizacion) VALUES(1, 1, 1, 1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
                                                                                                                (2, 2, 1, 1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

/*
    dia semana:
    0 - Domingo
    1 - Lunes
    2 - Martes
    3 - Miercoles
    4 - Jueves
    5 - Viernes
    6 - Sabado
*/
CREATE TABLE horarios_laborales_detalles (
    id                              INTEGER PRIMARY KEY,
    horario                         INTEGER NOT NULL,
    dia_semana                      SMALLINT NOT NULL,
    hora_inicio                     SMALLINT NOT NULL,
    hora_fin                        SMALLINT NOT NULL,
    CONSTRAINT FK_horarioslaboralesdetalles_horario FOREIGN KEY(horario) REFERENCES horarios_laborales(id)
);

CREATE INDEX IDX_horarioslaboralesdetalles_horario_dia ON horarios_laborales_detalles(horario, dia_semana);

INSERT INTO horarios_laborales_detalles(id, horario, dia_semana, hora_inicio, hora_fin) VALUES(1, 1, 1, 480, 780),
                                                                                                (2, 1, 2, 480, 900),
                                                                                                (3, 1, 3, 480, 900),
                                                                                                (4, 1, 4, 480, 900),
                                                                                                (5, 1, 5, 480, 900),
                                                                                                (6, 1, 6, 540, 900),

                                                                                                (7, 2, 1, 480, 1080),
                                                                                                (8, 2, 2, 480, 1080),
                                                                                                (9, 2, 3, 480, 1080),
                                                                                                (10, 2, 4, 480, 1080),
                                                                                                (11, 2, 5, 480, 1080),
                                                                                                (12, 2, 6, 540, 1080);

CREATE TABLE bloqueos_agenda_razones (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    razon                           VARCHAR(30)
);

INSERT INTO bloqueos_agenda_razones(id, codigo, razon) VALUES(1, 'vacaciones', 'Vacaciones'),
                                                                (2, 'enfermedad', 'Enfermedad'),
                                                                (3, 'formacion', 'Formación'),
                                                                (4, 'reunion', 'Reunión'),
                                                                (5, 'suspension', 'Suspensión'),
                                                                (6, 'otro', 'Otro');

CREATE TABLE bloqueos_agenda (
    id                              INTEGER PRIMARY KEY,
    personal                        INTEGER NOT NULL,
    titulo                          VARCHAR(30) NOT NULL,
    razon                           SMALLINT NOT NULL,
    otra_razon                      VARCHAR(255) DEFAULT NULL,
    f_inicio                        DATE NOT NULL,
    f_fin                           DATE NOT NULL,
    h_inicio                        SMALLINT NOT NULL,
    h_fin                           SMALLINT NOT NULL,
    todo_el_dia                     SMALLINT NOT NULL DEFAULT 0, --Ignora h_inicio y h_fin
    observaciones                   VARCHAR(512) DEFAULT NULL,
    registro                        INTEGER NOT NULL,
    activo                          SMALLINT NOT NULL DEFAULT 1,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_bloqueosagenda_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_bloqueosagenda_razon FOREIGN KEY(razon) REFERENCES bloqueos_agenda_razones(id),
    CONSTRAINT FK_bloqueosagenda_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE INDEX IDX_bloqueosagenda_personal_inicio_fin ON bloqueos_agenda(personal, f_inicio, f_fin);

CREATE TABLE clientes (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    clave                           VARCHAR(16) UNIQUE,
    es_empresa                      SMALLINT NOT NULL DEFAULT 0,
    empresa                         VARCHAR(255) DEFAULT NULL,
    nombre                          VARCHAR(100) NOT NULL,
    paterno                         VARCHAR(80) DEFAULT NULL,
    materno                         VARCHAR(80) DEFAULT NULL,
    curp                            VARCHAR(20) DEFAULT NULL,
    genero                          VARCHAR(1) NOT NULL,
    f_nacimiento                    DATE DEFAULT NULL,
    calle                           VARCHAR(120) DEFAULT NULL,
    num_ext                         VARCHAR(12) DEFAULT NULL,
    num_int                         VARCHAR(12) DEFAULT NULL,
    colonia                         INTEGER DEFAULT NULL,
    cp                              VARCHAR(5) DEFAULT NULL,
    telefono                        VARCHAR(40) DEFAULT NULL,
    movil                           VARCHAR(40) DEFAULT NULL,
    email                           VARCHAR(255) DEFAULT NULL,
    adeudo                          NUMERIC(18, 2) NOT NULL DEFAULT 0,
    ultimo_pago                     NUMERIC(18, 2) NOT NULL DEFAULT 0,
    registro                        INTEGER NOT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    f_ultimo_pago                   TIMESTAMP DEFAULT NULL,
    CONSTRAINT FK_clientes_genero FOREIGN KEY(genero) REFERENCES generos(id),
    CONSTRAINT FK_clientes_colonia FOREIGN KEY(colonia) REFERENCES colonias(id),
    CONSTRAINT FK_clientes_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE facturacion_tipo_contribuyente (
    id                              CHAR(1) PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(30) NOT NULL
);

INSERT INTO facturacion_tipo_contribuyente(id, codigo, tipo) VALUES('F', 'persona-fisica', 'Persona Fisica'), ('M', 'persona-moral', 'Persona Moral');

CREATE TABLE facturacion_regimen (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(30) NOT NULL UNIQUE,
    tipo                            CHAR(1) DEFAULT NULL,
    codigo_sat                      VARCHAR(8) NOT NULL,
    regimen                         VARCHAR(255) NOT NULL,
    CONSTRAINT FK_facturacionregimen_tipo FOREIGN KEY(tipo) REFERENCES facturacion_tipo_contribuyente(id)
);

INSERT INTO facturacion_regimen(id, codigo, tipo, codigo_sat, regimen) VALUES(1, 'persona-moral', 'M', '601', 'REGIMEN GENERAL DE LEY PERSONAS MORALES'),
                                                                            (2, 'persona-simp-moral', 'M', '602', 'RÉGIMEN SIMPLIFICADO DE LEY PERSONAS MORALES'),
                                                                            (3, 'persona-moral-no-luc', 'M', '603', 'PERSONAS MORALES CON FINES NO LUCRATIVOS'),
                                                                            (4, 'peq-contribuyente', 'F', '604', 'RÉGIMEN DE PEQUEÑOS CONTRIBUYENTES'),
                                                                            (5, 'sueldos-salarios', 'F', '605', 'RÉGIMEN DE SUELDOS Y SALARIOS E INGRESOS ASIMILADOS A SALARIOS'),
                                                                            (6, 'arrendamiento', 'F', '606', 'RÉGIMEN DE ARRENDAMIENTO'),
                                                                            (7, 'enajenacion', 'F', '607', 'RÉGIMEN DE ENAJENACIÓN O ADQUISICIÓN DE BIENES'),
                                                                            (8, 'demas-ingresos', 'F', '608', 'RÉGIMEN DE LOS DEMÁS INGRESOS'),
                                                                            (9, 'consolidacion', 'M', '609', 'RÉGIMEN DE CONSOLIDACIÓN'),
                                                                            (10, 'extranjeros-sin-est', NULL, '610', 'RÉGIMEN RESIDENTES EN EL EXTRANJERO SIN ESTABLECIMIENTO PERMANENTE EN MÉXICO'),
                                                                            (11, 'dividendos', 'F', '611', 'RÉGIMEN DE INGRESOS POR DIVIDENDOS (SOCIOS Y ACCIONISTAS)'),
                                                                            (12, 'actividad-empresarial', 'F', '612', 'RÉGIMEN DE LAS PERSONAS FÍSICAS CON ACTIVIDADES EMPRESARIALES Y PROFESIONALES'),
                                                                            (13, 'int-act-empresarial', 'F', '613', 'RÉGIMEN INTERMEDIO DE LAS PERSONAS FÍSICAS CON ACTIVIDADES EMPRESARIALES'),
                                                                            (14, 'intereses', 'F', '614', 'RÉGIMEN DE LOS INGRESOS POR INTERESES'),
                                                                            (15, 'premios', 'F', '615', 'RÉGIMEN DE LOS INGRESOS POR OBTENCIÓN DE PREMIOS'),
                                                                            (16, 'sin-obligaciones', NULL, '616', 'SIN OBLIGACIONES FISCALES'),
                                                                            (17, 'pemex', 'M', '617', 'PEMEX'),
                                                                            (18, 'simplificado-fisicas', 'F', '618', 'RÉGIMEN SIMPLIFICADO DE LEY PERSONAS FÍSICAS'),
                                                                            (19, 'prestamos', 'F', '619', 'INGRESOS POR LA OBTENCIÓN DE PRÉSTAMOS'),
                                                                            (20, 'produccion', 'M', '620', 'SOCIEDADES COOPERATIVAS DE PRODUCCIÓN QUE OPTAN POR DIFERIR SUS INGRESOS.'),
                                                                            (21, 'rif', 'F', '621', 'RÉGIMEN DE INCORPORACIÓN FISCAL'),
                                                                            (22, 'agricolas', 'F', '622', 'RÉGIMEN DE ACTIVIDADES AGRÍCOLAS, GANADERAS, SILVÍCOLAS Y PESQUERAS PM'),
                                                                            (23, 'opcion-sociedades', 'M', '623', 'RÉGIMEN DE OPCIONAL PARA GRUPOS DE SOCIEDADES'),
                                                                            (24, 'coordinados', 'M', '624', 'RÉGIMEN DE LOS COORDINADOS'),
                                                                            (25, 'plataformas', 'F', '625', 'RÉGIMEN DE LAS ACTIVIDADES EMPRESARIALES CON INGRESOS A TRAVÉS DE PLATAFORMAS TECNOLÓGICAS.'),
                                                                            (26, 'resico', 'F', '626', 'RÉGIMEN SIMPLIFICADO DE CONFIANZA');

CREATE TABLE clientes_facturacion (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    cliente                         INTEGER NOT NULL,
    regimen                         SMALLINT NOT NULL,
    rfc                             VARCHAR(18) NOT NULL,
    razon_social                    VARCHAR(255) NOT NULL,
    calle                           VARCHAR(120) DEFAULT NULL,
    num_ext                         VARCHAR(12) DEFAULT NULL,
    num_int                         VARCHAR(12) DEFAULT NULL,
    colonia                         INTEGER DEFAULT NULL,
    cp                              CHAR(5) NOT NULL,
    email                           VARCHAR(255) DEFAULT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    f_ultima_factura                TIMESTAMP DEFAULT NULL,
    CONSTRAINT FK_clientesfacturacion_cliente FOREIGN KEY(cliente) REFERENCES clientes(id),
    CONSTRAINT FK_clientesfacturacion_regimen FOREIGN KEY(regimen) REFERENCES facturacion_regimen(id),
    CONSTRAINT FK_clientesfacturacion_colonia FOREIGN KEY(colonia) REFERENCES colonias(id)
);

CREATE TABLE parentescos (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) UNIQUE NOT NULL,
    descripcion                     VARCHAR(100) NOT NULL
);

INSERT INTO parentescos(id, codigo, descripcion) VALUES(1, 'self', 'El cliente es el mismo paciente'),
                                                        (2, 'spouse', 'Esposo / Esposa'),
                                                        (3, 'parent', 'Padre / Madre'),
                                                        (4, 'child', 'Hijo / Hija'),
                                                        (5, 'friend', 'Amigo / Amiga'),
                                                        (6, 'employer', 'Empleador'),
                                                        (7, 'tutor', 'Tutor legal');

CREATE TABLE pacientes (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    clave                           VARCHAR(16) UNIQUE,
    nombre                          VARCHAR(60) NOT NULL,
    paterno                         VARCHAR(60) DEFAULT NULL,
    materno                         VARCHAR(60) DEFAULT NULL,
    curp                            VARCHAR(20) DEFAULT NULL,
    f_nacimiento                    DATE DEFAULT NULL,
    genero                          VARCHAR(1) NOT NULL,
    telefono                        VARCHAR(40) DEFAULT NULL,
    movil                           VARCHAR(40) DEFAULT NULL,
    email                           VARCHAR(255) DEFAULT NULL,
    calle                           VARCHAR(120) DEFAULT NULL,
    num_ext                         VARCHAR(12) DEFAULT NULL,
    num_int                         VARCHAR(12) DEFAULT NULL,
    colonia                         INTEGER DEFAULT NULL,
    cp                              VARCHAR(5) DEFAULT NULL,
    registro                        INTEGER NOT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    f_ultima_visita                 TIMESTAMP DEFAULT NULL,
    medicamentos                    VARCHAR(2048) DEFAULT NULL,
    suplementos                     VARCHAR(2048) DEFAULT NULL,
    antecedentes_familiares         VARCHAR(2048) DEFAULT NULL,
    observaciones_generales         VARCHAR(2048) DEFAULT NULL,
    CONSTRAINT FK_pacientes_genero FOREIGN KEY(genero) REFERENCES generos(id),
    CONSTRAINT FK_pacientes_colonia FOREIGN KEY(colonia) REFERENCES colonias(id),
    CONSTRAINT FK_pacientes_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

INSERT INTO pacientes(id, uuid, clave, nombre, paterno, materno, calle, num_ext, colonia, cp, genero, email, f_nacimiento, telefono, movil, registro, f_registro, f_actualizacion) VALUES(1, 1, 'PE-000001', 'Paciente', 'Numero', '1', 'Domicilio Conocido', '123', 1275, '66004', 'H', 'paciente_1@adariel.com', '2001-01-12', '555 555 5555', '565 456 1245', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
                                                                                                                                                                                            (2, 2, 'PE-000002', 'Paciente', 'Numero', '2', 'Domicilio Conocido', '456', 1180, '66036', 'H', 'paciente_2@adariel.com', '1994-03-28', '123 456 7890', '265 456 1245', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

CREATE TABLE clientes_pacientes (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    cliente                         INTEGER NOT NULL,
    paciente                        INTEGER NOT NULL,
    parentesco                      SMALLINT NOT NULL,
    principal                       SMALLINT NOT NULL DEFAULT 0, --EN CASO DE HABER MAS DE UN CLIENTE PARA EL PACIENTE
    registro                        INTEGER NOT NULL,
    activo                          SMALLINT NOT NULL DEFAULT 1,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT UK_clientespaciente_paciente UNIQUE(cliente, paciente),
    CONSTRAINT FK_clientespacientes_cliente FOREIGN KEY(cliente) REFERENCES clientes(id),
    CONSTRAINT FK_clientespacientes_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_clientespacientes_parentesco FOREIGN KEY(parentesco) REFERENCES parentescos(id),
    CONSTRAINT FK_clientespacientes_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE antecedentes_categorias (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(30) UNIQUE NOT NULL,
    nombre                          VARCHAR(100) NOT NULL,
    descripcion                     VARCHAR(512) DEFAULT NULL
);

INSERT INTO antecedentes_categorias(id, codigo, nombre) VALUES(1, 'personal_patologico', 'Antecedentes personales patológicos'),
                                                                (2, 'quirurgico', 'Antecedentes quirúrgicos'),
                                                                (3, 'alergico', 'Antecedentes alérgicos'),
                                                                (4, 'familiar', 'Antecedentes familiares'),
                                                                (5, 'no_patologico', 'Antecedentes no patológicos'),
                                                                (6, 'gineco', 'Gineco-obstétricos'),
                                                                (7, 'infecciones', 'Antecedentes infecciosos');

CREATE TABLE antecedentes_tipos (
    id                              SMALLINT PRIMARY KEY,
    categoria                       SMALLINT NOT NULL,
    codigo                          VARCHAR(40) UNIQUE NOT NULL,
    nombre                          VARCHAR(100) NOT NULL,
    descripcion                     VARCHAR(512) DEFAULT NULL,
    CONSTRAINT FK_tiposantecedentes_categoria FOREIGN KEY (categoria) REFERENCES antecedentes_categorias(id)
);

INSERT INTO antecedentes_tipos(id, categoria, codigo, nombre) VALUES(1, 1, 'diabetes-mellitus', 'Diabetes mellitus'),
                                                                    (2, 1, 'hipertension-arterial', 'Hipertensión arterial'),
                                                                    (3, 1, 'dislipidemia', 'Dislipidemia (colesterol / triglicéridos altos)'),
                                                                    (4, 1, 'cardiopatia', 'Cardiopatía'),
                                                                    (5, 1, 'arritmias', 'Arritmias'),
                                                                    (6, 1, 'insuficiencia-renal', 'Insuficiencia renal'),
                                                                    (7, 1, 'insuficiencia-hepatica', 'Insuficiencia hepática'),
                                                                    (8, 1, 'asma', 'Asma'),
                                                                    (9, 1, 'epoc', 'EPOC'),
                                                                    (10, 1, 'tiroides', 'Enfermedad tiroidea (hipotiroidismo / hipertiroidismo)'),
                                                                    (11, 1, 'epilepsia', 'Epilepsia'),
                                                                    (12, 1, 'cerebrovascular', 'Enfermedad cerebrovascular (EVC / derrame)'),
                                                                    (13, 1, 'coagulacion', 'Trastornos de coagulación'),
                                                                    (14, 1, 'cancer', 'Cáncer (neoplasia)'),
                                                                    (15, 1, 'vih', 'VIH'),
                                                                    (16, 1, 'hepatitis', 'Hepatitis'),
                                                                    (17, 1, 'tuberculosis', 'Tuberculosis'),
                                                                    (18, 1, 'artritis', 'Artritis / enfermedades reumatológicas'),
                                                                    (19, 1, 'autoinmune', 'Enfermedades autoinmunes'),
                                                                    (20, 2, 'cirugia-previa', 'Cirugías previas'),
                                                                    (21, 2, 'cirugia-cardiovascular', 'Cirugía cardiovascular'),
                                                                    (22, 2, 'cirugia-abdominal', 'Cirugía abdominal'),
                                                                    (23, 2, 'cesarea', 'Cesárea'),
                                                                    (24, 2, 'apendicectomia', 'Apendicectomía'),
                                                                    (25, 2, 'colecistectomia', 'Colecistectomía'),
                                                                    (26, 2, 'protesis', 'Prótesis / implantes'),
                                                                    (27, 2, 'transplante', 'Transplante'),
                                                                    (28, 3, 'alergia-medicamentos', 'Alergia a medicamentos'),
                                                                    (29, 3, 'alergia-alimentos', 'Alergia a alimentos'),
                                                                    (30, 3, 'alergia-anestesicos', 'Alergia a anestésicos'),
                                                                    (31, 3, 'alergia-latex', 'Alergia a látex'),
                                                                    (32, 3, 'alergia-otros', 'Otras alergias'),
                                                                    (33, 4, 'diabetes-familiar', 'Diabetes familiar'),
                                                                    (34, 4, 'hipertension-familiar', 'Hipertensión familiar'),
                                                                    (35, 4, 'cardiopatia-familiar', 'Cardiopatía familiar'),
                                                                    (36, 4, 'cancer-familiar', 'Cáncer familiar'),
                                                                    (37, 4, 'hereditarias', 'Enfermedades hereditarias'),
                                                                    (38, 5, 'tabaquismo', 'Tabaquismo'),
                                                                    (39, 5, 'alcoholismo', 'Alcoholismo'),
                                                                    (40, 5, 'drogadiccion', 'Drogadicción'),
                                                                    (41, 5, 'sedentarismo', 'Sedentarismo'),
                                                                    (42, 5, 'obesidad', 'Obesidad'),
                                                                    (43, 5, 'actividad-fisica-regular', 'Actividad física regular'),
                                                                    (44, 5, 'exposicion-laboral-riesgos', 'Exposición laboral a riesgos'),
                                                                    (45, 6, 'embarazo', 'Embarazo actual'),
                                                                    (46, 6, 'embarazo-previo', 'Embarazos previos'),
                                                                    (47, 6, 'parto', 'Partos'),
                                                                    (48, 6, 'aborto', 'Abortos'),
                                                                    (49, 6, 'cesareas', 'Cesáreas'),
                                                                    (50, 6, 'complicaciones-obstetricas', 'Complicaciones obstétricas'),
                                                                    (51, 6, 'menopausia', 'Menopausia'),
                                                                    (52, 7, 'covid', 'COVID-19'),
                                                                    (53, 7, 'influenza-recurrente', 'Influenza recurrente'),
                                                                    (54, 7, 'infecciones-respiratorias-frecuentes', 'Infecciones respiratorias frecuentes'),
                                                                    (55, 7, 'infecciones-urinarias-frecuentes', 'Infecciones urinarias recurrentes');

CREATE TABLE pacientes_antecedentes (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    paciente                        INTEGER NOT NULL,
    tipo_antecedente                SMALLINT NOT NULL,
    diagnostico                     VARCHAR(512) DEFAULT NULL,
    fecha_diagnostico               DATE DEFAULT NULL,
    tratamiento_actual              VARCHAR(512) DEFAULT NULL,
    observaciones                   VARCHAR(1024) DEFAULT NULL,
    registro                        INTEGER NOT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_pacientesantecedentes_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_pacientesantecedentes_tipo FOREIGN KEY(tipo_antecedente) REFERENCES antecedentes_tipos(id),
    CONSTRAINT FK_pacientesantecedentes_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE articulos_categoria (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(30) NOT NULL UNIQUE,
    categoria                       VARCHAR(80) NOT NULL,
    descripcion                     VARCHAR(255) DEFAULT NULL
);

CREATE TABLE articulos (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    clave                           VARCHAR(12) NOT NULL UNIQUE,
    codigo_barras                   VARCHAR(32) NOT NULL UNIQUE,
    nombre                          VARCHAR(100) NOT NULL,
    nombre_ticket                   VARCHAR(32) NOT NULL,
    categoria                       SMALLINT NOT NULL,
    descripcion                     VARCHAR(255) DEFAULT NULL,
    unidad_uso                      VARCHAR(8) NOT NULL DEFAULT 1,
    unidad_compra                   VARCHAR(8) NOT NULL DEFAULT 1,
    factor_conversion               NUMERIC(12, 4) NOT NULL DEFAULT 1,
    factor_conversion_venta         NUMERIC(12, 4) NOT NULL DEFAULT 1,
    costo_unidad                    NUMERIC(18, 2) NOT NULL DEFAULT 0,
    minimo_inventario               NUMERIC(12, 4) NOT NULL DEFAULT 0,
    habilitado_venta                SMALLINT NOT NULL DEFAULT 1,
    activo                          SMALLINT NOT NULL DEFAULT 1,
    registro                        INTEGER NOT NULL,
    f_registrado                    TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_articulos_categoria FOREIGN KEY(categoria) REFERENCES articulos_categoria(id),
    CONSTRAINT FK_articulos_registro FOREIGN KEY(registro) REFERENCES usuarios(id),
    CONSTRAINT FK_articulos_unidaduso FOREIGN KEY(unidad_uso) REFERENCES unidades(id),
    CONSTRAINT FK_articulos_unidadcompra FOREIGN KEY(unidad_compra) REFERENCES unidades(id)
);

CREATE TABLE servicios (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    servicio                        VARCHAR(120) NOT NULL,
    descripcion                     VARCHAR(500) DEFAULT NULL,
    duracion_min                    SMALLINT NOT NULL,
    costo_base                      NUMERIC(18,2) NOT NULL,
    requiere_material               BOOLEAN NOT NULL DEFAULT 0,
    es_procedimiento                BOOLEAN NOT NULL DEFAULT 0,
    activo                          BOOLEAN NOT NULL DEFAULT 1
);

INSERT INTO servicios (id, codigo, servicio, descripcion, duracion_min, costo_base, requiere_material, es_procedimiento) VALUES

-- CONSULTAS
(1,  'consulta-general',        'Consulta General Podológica',        'Valoración inicial del estado del pie y diagnóstico básico',                  30, 500, 0, 0),
(2,  'consulta-seguimiento',    'Consulta de Seguimiento',             'Revisión de evolución de tratamiento o control posterior',                   20, 350, 0, 0),

-- SERVICIOS BÁSICOS
(3,  'limpieza-podologica',     'Limpieza Podológica',                 'Corte de uñas, limado, limpieza de hiperqueratosis leve',                     45, 700, 1, 0),
(4,  'corte-unas-especial',     'Corte Especializado de Uñas',         'Corte clínico para uñas engrosadas o deformadas',                              30, 400, 0, 0),

-- PROCEDIMIENTOS COMUNES
(5,  'una-encarnada',           'Tratamiento de Uña Encarnada',        'Retiro parcial de espícula ungueal',                                            60, 1200, 1, 1),
(6,  'onicomicosis-control',    'Control de Onicomicosis',              'Limpieza y control de uñas con hongo',                                          45, 650, 1, 0),
(7,  'hiperqueratosis',         'Retiro de Hiperqueratosis',            'Eliminación de durezas profundas',                                              40, 600, 1, 0),
(8,  'helomas',                 'Eliminación de Helomas (Callos)',      'Retiro de callosidades nucleadas',                                              40, 650, 1, 1),

-- PIE DIABÉTICO
(9,  'valoracion-pie-diabetico','Valoración de Pie Diabético',          'Evaluación de riesgo y cuidado especializado',                                  30, 550, 0, 0),
(10, 'curacion-pie-diabetico',  'Curación Pie Diabético',               'Curación de lesiones en paciente diabético',                                    45, 750, 1, 1),

-- CURACIONES Y SEGUIMIENTOS
(11, 'curacion-simple',         'Curación Simple',                      'Limpieza y vendaje de lesión leve',                                             20, 300, 1, 0),
(12, 'curacion-avanzada',       'Curación Avanzada',                    'Curación de herida con mayor profundidad o riesgo',                             40, 600, 1, 1),

-- ORTESIS Y APOYOS
(13, 'valoracion-ortesis',      'Valoración para Órtesis',              'Evaluación para colocación de correctores ungueales',                           30, 400, 0, 0),
(14, 'colocacion-ortesis',      'Colocación de Órtesis Ungueal',        'Instalación de corrector para uña encarnada',                                   45, 900, 1, 1),

-- TRATAMIENTOS COMPLEMENTARIOS
(15, 'terapia-laser-hongos',    'Terapia Láser para Hongos',            'Sesión de tratamiento láser para onicomicosis',                                 30, 1000, 1, 1),
(16, 'deslaminacion-ungueal',   'Deslaminación Ungueal',                'Reducción mecánica de grosor en uñas',                                          30, 500, 1, 0),

-- SERVICIOS ESTÉTICOS (muchas clínicas los ofrecen)
(17, 'pedicure-clinico',        'Pedicure Clínico',                     'Servicio estético con enfoque en salud del pie',                                60, 800, 1, 0);

CREATE TABLE personal_servicios (
    personal                        INTEGER,
    servicio                        SMALLINT,
    costo                           NUMERIC(18,2) NOT NULL,
    CONSTRAINT PK_personalservicios PRIMARY KEY (personal, servicio),
    CONSTRAINT FK_personalservicios_personal FOREIGN KEY (personal) REFERENCES personal(id),
    CONSTRAINT FK_personalservicios_servicio FOREIGN KEY (servicio) REFERENCES servicios(id)
);



INSERT INTO personal_servicios(personal, servicio, costo) VALUES(1, 1, 500);
INSERT INTO personal_servicios(personal, servicio, costo) VALUES(1, 4, 600);
INSERT INTO personal_servicios(personal, servicio, costo) VALUES(1, 6, 840);
INSERT INTO personal_servicios(personal, servicio, costo) VALUES(1, 7, 920);
INSERT INTO personal_servicios(personal, servicio, costo) VALUES(1, 11, 300);
INSERT INTO personal_servicios(personal, servicio, costo) VALUES(1, 15, 1400);


INSERT INTO personal_servicios(personal, servicio, costo) VALUES(2, 1, 450);
INSERT INTO personal_servicios(personal, servicio, costo) VALUES(2, 4, 660);
INSERT INTO personal_servicios(personal, servicio, costo) VALUES(2, 6, 790);
INSERT INTO personal_servicios(personal, servicio, costo) VALUES(2, 11, 400);
INSERT INTO personal_servicios(personal, servicio, costo) VALUES(2, 15, 1100);



CREATE TABLE indicaciones_tipo (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(20) NOT NULL
);

INSERT INTO indicaciones_tipo(id, codigo, tipo) VALUES(1, 'previa', 'Previa'), (2, 'posterior', 'Posterior');

CREATE TABLE indicaciones (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(30) NOT NULL UNIQUE,
    tipo                            SMALLINT NOT NULL,
    descripcion                     VARCHAR(500) NOT NULL,
    activo                          BOOLEAN NOT NULL DEFAULT 1,
    CONSTRAINT FK_indicaciones_tipo FOREIGN KEY(tipo) REFERENCES indicaciones_tipo(id)
);

INSERT INTO indicaciones(id, codigo, tipo, descripcion) VALUES(1, 'pies-limpios', 1, 'Acudir con pies limpios y sin esmalte'),
                                                                (2, 'no-aplicar-crema', 1, 'No aplicar cremas 24h antes'),
                                                                (3, 'no-mojar-area-tratada', 1, 'No mojar el área tratada por 12 horas'),
                                                                (4, 'aplicar-medicamento', 1, 'Aplicar medicamento tópico indicado');

CREATE TABLE servicios_indicaciones (
    servicio                        SMALLINT NOT NULL,
    indicacion                      SMALLINT NOT NULL,
    obligatoria                     BOOLEAN NOT NULL DEFAULT 1,
    CONSTRAINT PK_serviciosindicaciones PRIMARY KEY (servicio, indicacion),
    CONSTRAINT FK_serviciosindicaciones_servicio FOREIGN KEY (servicio) REFERENCES servicios(id),
    CONSTRAINT FK_serviciosindicaciones_indicacion FOREIGN KEY (indicacion) REFERENCES indicaciones(id)
);

CREATE TABLE servicios_articulos (
    servicio                        SMALLINT NOT NULL,
    articulo                        INTEGER NOT NULL,
    cantidad_base                   NUMERIC(18,2) NOT NULL,
    CONSTRAINT PK_serviciosarticulos PRIMARY KEY (servicio, articulo),
    CONSTRAINT FK_serviciosarticulos_servicio FOREIGN KEY (servicio) REFERENCES servicios(id),
    CONSTRAINT FK_serviciosarticulos_articulo FOREIGN KEY (articulo) REFERENCES articulos(id)
);

CREATE TABLE citas_asuntos (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    asunto                          VARCHAR(40) NOT NULL
);

INSERT INTO citas_asuntos(id, codigo, asunto) VALUES(1, 'consulta', 'Consulta'),
                                                    (2, 'seguimiento', 'Seguimiento'),
                                                    (3, 'tratamiento', 'Tratamiento');

CREATE TABLE citas_estatus (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    estatus                         VARCHAR(40) NOT NULL
);

INSERT INTO citas_estatus(id, codigo, estatus) VALUES(1, 'agendada', 'Cita Agendada'),
                                                        (2, 'rechazada', 'Cita Rechazada'),
                                                        (3, 'en_espera', 'En Espera'),
                                                        (4, 'en_proceso', 'En Proceso'),
                                                        (5, 'no_presento', 'No se presento'),
                                                        (6, 'finalizada', 'Cita Finalizada'),
                                                        (7, 'cancelada', 'Cita Cancelada');

CREATE TABLE citas_formas (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    forma                           VARCHAR(40) NOT NULL
);

INSERT INTO citas_formas(id, codigo, forma) VALUES(1, 'presencial', 'Presencial'),
                                                    (2, 'telefonica', 'Teléfono'),
                                                    (3, 'correo', 'E-Mail'),
                                                    (4, 'agenda_digital', 'Agenda Digital');

CREATE TABLE citas (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    paciente                        INTEGER NOT NULL,
    asunto                          SMALLINT DEFAULT NULL,
    forma                           SMALLINT DEFAULT NULL,
    descripcion                     VARCHAR(2000) DEFAULT NULL,
    motivo_consulta                 VARCHAR(2000) DEFAULT NULL,
    fecha                           DATE NOT NULL,
    h_inicio                        SMALLINT DEFAULT NULL,
    duracion                        SMALLINT NOT NULL,
    h_fin                           SMALLINT DEFAULT NULL,
    estatus                         SMALLINT NOT NULL,
    registro                        INTEGER DEFAULT NULL,
    costo                           NUMERIC(18, 2) NOT NULL DEFAULT 0,
    adeudo                          NUMERIC(18, 2) NOT NULL DEFAULT 0,
    pagado                          NUMERIC(18, 2) NOT NULL DEFAULT 0,
    bonificacion                    NUMERIC(18, 2) NOT NULL DEFAULT 0,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_citas_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_citas_asunto FOREIGN KEY(asunto) REFERENCES citas_asuntos(id),
    CONSTRAINT FK_citas_forma FOREIGN KEY(forma) REFERENCES citas_formas(id),
    CONSTRAINT FK_citas_estatus FOREIGN KEY(estatus) REFERENCES citas_estatus(id),
    CONSTRAINT FK_citas_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE citas_bloques_estatus (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    estatus                         VARCHAR(40) NOT NULL
);

INSERT INTO citas_bloques_estatus(id, codigo, estatus) VALUES(1, 'agendada', 'Cita Agendada'),
                                                                (2, 'rechazada', 'Cita Rechazada'),
                                                                (3, 'en_espera', 'En Espera'),
                                                                (4, 'en_proceso', 'En Proceso'),
                                                                (5, 'no_presento', 'No se presento'),
                                                                (6, 'finalizada', 'Cita Finalizada'),
                                                                (7, 'cancelada', 'Cita Cancelada');

CREATE TABLE citas_bloques (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    cita                            INTEGER NOT NULL,
    personal                        INTEGER NOT NULL,
    servicio                        SMALLINT NOT NULL,
    descripcion                     VARCHAR(2000) DEFAULT NULL,
    orden                           SMALLINT NOT NULL DEFAULT 1,
    h_inicio                        SMALLINT NOT NULL,
    h_fin                           SMALLINT NOT NULL,
    duracion                        SMALLINT NOT NULL,
    estatus                         SMALLINT NOT NULL,
    CONSTRAINT FK_citasbloques_cita FOREIGN KEY (cita) REFERENCES citas(id),
    CONSTRAINT FK_citasbloques_cita FOREIGN KEY (personal) REFERENCES personal(id),
    CONSTRAINT FK_citasbloques_cita FOREIGN KEY (servicio) REFERENCES servicios(id)
);

CREATE INDEX IDX_citasbloques_servicio ON citas_bloques(personal, servicio);
CREATE INDEX IDX_citasbloques_inicio ON citas_bloques(personal, h_inicio);
CREATE INDEX IDX_citasbloques_fin ON citas_bloques(personal, h_fin);

CREATE TABLE citas_servicios (
    cita                            INTEGER NOT NULL,
    servicio                        SMALLINT NOT NULL,
    personal                        INTEGER NOT NULL,
    costo                           NUMERIC(18,2) NOT NULL,
    bonificacion                    NUMERIC(18,2) NOT NULL DEFAULT 0,
    CONSTRAINT PK_citasservicios PRIMARY KEY (cita, servicio),
    CONSTRAINT FK_citasservicios_cita FOREIGN KEY (cita) REFERENCES citas(id),
    CONSTRAINT FK_citasservicios_servicio FOREIGN KEY (servicio) REFERENCES servicios(id),
    CONSTRAINT FK_citasservicios_personal FOREIGN KEY (personal) REFERENCES personal(id)
);

CREATE TABLE citas_servicios_articulos (
    cita                            INTEGER NOT NULL,
    servicio                        SMALLINT NOT NULL,
    articulo                        INTEGER NOT NULL,
    cantidad_utilizada              NUMERIC(18,2) NOT NULL,
    CONSTRAINT PK_citasserviciosarticulos PRIMARY KEY (cita, servicio, articulo),
    CONSTRAINT FK_citasserviciosarticulos_cita FOREIGN KEY (cita) REFERENCES citas(id),
    CONSTRAINT FK_citasserviciosarticulos_servicio FOREIGN KEY (servicio) REFERENCES servicios(id),
    CONSTRAINT FK_citasserviciosarticulos_articulo FOREIGN KEY (articulo) REFERENCES articulos(id)
);

CREATE TABLE tipos_pies (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(60) NOT NULL
);

CREATE TABLE tipos_pulso (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(60) NOT NULL
);

CREATE TABLE tipos_temperatura_pie (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(60) NOT NULL
);

CREATE TABLE tipos_sensibilidad (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(60) NOT NULL
);

CREATE TABLE formula_metatarsal (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    formula                            VARCHAR(60) NOT NULL
);

CREATE TABLE tipos_coloracion_pie (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(60) NOT NULL
);

CREATE TABLE exploracion_podologica (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    paciente                        INTEGER NOT NULL,
    cita                            INTEGER NOT NULL,
    personal                        INTEGER NOT NULL,
    tipo_pie                        SMALLINT DEFAULT NULL,
    formula_metatarsal              SMALLINT DEFAULT NULL,
    alteraciones_marcha             VARCHAR(255) DEFAULT NULL,
    pulso_pedio_derecho             SMALLINT DEFAULT NULL,
    pulso_pedio_izquierdo           SMALLINT DEFAULT NULL,
    sensibilidad_derecho            SMALLINT DEFAULT NULL,
    sensibilidad_izquierdo          SMALLINT DEFAULT NULL,
    temperatura_pies                SMALLINT DEFAULT NULL,
    coloracion_pies                 SMALLINT DEFAULT NULL,
    observaciones                   VARCHAR(2000) DEFAULT NULL,
    recomendaciones                 VARCHAR(2000) DEFAULT NULL,
    f_exploracion                   TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_exploracionpodologica_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_exploracionpodologica_cita FOREIGN KEY(cita) REFERENCES citas(id),
    CONSTRAINT FK_exploracionpodologica_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_exploracionpodologica_tipopie FOREIGN KEY(tipo_pie) REFERENCES tipos_pies(id),
    CONSTRAINT FK_exploracionpodologica_formulametatarsal FOREIGN KEY(formula_metatarsal) REFERENCES formula_metatarsal(id),
    CONSTRAINT FK_exploracionpodologica_pulsopedioderecho FOREIGN KEY(pulso_pedio_derecho) REFERENCES tipos_pulso(id),
    CONSTRAINT FK_exploracionpodologica_puslopedioizquierdo FOREIGN KEY(pulso_pedio_izquierdo) REFERENCES tipos_pulso(id),
    CONSTRAINT FK_exploracionpodologica_sensibilidadderecho FOREIGN KEY(sensibilidad_derecho) REFERENCES tipos_sensibilidad(id),
    CONSTRAINT FK_exploracionpodologica_sensibilidadizquierdo FOREIGN KEY(sensibilidad_izquierdo) REFERENCES tipos_sensibilidad(id),
    CONSTRAINT FK_exploracionpodologica_temperaturapies FOREIGN KEY(temperatura_pies) REFERENCES tipos_temperatura_pie(id),
    CONSTRAINT FK_exploracionpodologica_coloracionpies FOREIGN KEY(coloracion_pies) REFERENCES tipos_coloracion_pie(id)
);


CREATE TABLE parametros_medicos (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(30) NOT NULL UNIQUE,
    parametro                       VARCHAR(80) NOT NULL,
    descripcion                     VARCHAR(1024) DEFAULT NULL,
    unidad                          VARCHAR(8) NOT NULL,
    minimo                          NUMERIC(12, 8) NOT NULL DEFAULT 0,
    recomendado                     NUMERIC(12, 8) NOT NULL DEFAULT 0,
    maximo                          NUMERIC(12, 8) NOT NULL DEFAULT 0,
    digitos                         SMALLINT NOT NULL DEFAULT 0,
    CONSTRAINT FK_parametrosmedicos_unidad FOREIGN KEY(unidad) REFERENCES unidades(id)
);

CREATE TABLE seguimiento_parametros (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    cita                            INTEGER DEFAULT NULL,
    paciente                        INTEGER NOT NULL,
    personal                        INTEGER NOT NULL,
    parametro                       SMALLINT NOT NULL,
    valor                           NUMERIC(12, 8) NOT NULL DEFAULT 0,
    observaciones                   VARCHAR(1024) DEFAULT NULL,
    f_medicion                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_seguimientoparametros_cita FOREIGN KEY(cita) REFERENCES citas(id),
    CONSTRAINT FK_seguimientoparametros_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_seguimientoparametros_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_seguimientoparametros_parametro FOREIGN KEY(parametro) REFERENCES parametros_medicos(id)
);

CREATE TABLE tipos_tratamiento (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(60) NOT NULL,
    descripcion                     VARCHAR(512) DEFAULT NULL
);

CREATE TABLE pacientes_tratamientos_podologicos (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    paciente                        INTEGER NOT NULL,
    cita                            INTEGER NOT NULL,
    personal                        INTEGER NOT NULL,
    tipo_tratamiento                SMALLINT NOT NULL,
    descripcion                     VARCHAR(1024) DEFAULT NULL,
    diagnostico_asociado            VARCHAR(1024) DEFAULT NULL,
    material_utilizado              VARCHAR(1024) DEFAULT NULL,
    observaciones                   VARCHAR(1024) DEFAULT NULL,
    f_tratamiento                   TIMESTAMP NOT NULL,
    f_proxima_revision              TIMESTAMP DEFAULT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_pacientestratamientospodologicos_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_pacientestratamientospodologicos_cita FOREIGN KEY(cita) REFERENCES citas(id),
    CONSTRAINT FK_pacientestratamientospodologicos_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_pacientestratamientospodologicos_tipotratamiento FOREIGN KEY(tipo_tratamiento) REFERENCES tipos_tratamiento(id)
);

CREATE TABLE tipos_localizacion_lesion (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    localizacion                    VARCHAR(40) NOT NULL
);

CREATE TABLE tipos_evolucion (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    evolucion                       VARCHAR(40) NOT NULL
);

CREATE TABLE tipos_tejido (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tejido                          VARCHAR(40) NOT NULL
);

CREATE TABLE grado_wagner (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    grado                           VARCHAR(40) NOT NULL,
    descripcion                     VARCHAR(255) DEFAULT NULL
);

INSERT INTO grado_wagner(id, codigo, grado, descripcion) VALUES(1, 'grado-0', 'Grado 0', 'No hay lesiones, pie de riesgo. Callos gruesos y alguna deformidad ósea.'),
                                                                (2, 'grado-1', 'Grado 1', 'Úlceras superficiales. Destrucción total del espesor de la piel.'),
                                                                (3, 'grado-2', 'Grado 2', 'Úlceras profundas. Penetran la piel grasa pero no afecta la zona ósea.'),
                                                                (4, 'grado-3', 'Grado 3', 'Úlcera más profunda con absceso (Osteomielitis). Compromete el tejido óseo y presencia de mal olor'),
                                                                (5, 'grado-4', 'Grado 4', 'Gangrena limitada. Necrosis en una zona del pie, en los dedos, talón o planta.'),
                                                                (6, 'grado-5', 'Grado 5', 'Gangrena extensa. La gangrena se extiende e invade todo el pie.');

CREATE TABLE pie_lado (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    lado                            VARCHAR(40) NOT NULL
);

INSERT INTO pie_lado(id, codigo, lado) VALUES(1, 'derecho', 'Pie derecho'),
                                                (2, 'izquierdo', 'Pie izquierdo');

CREATE TABLE seguimiento_pie_diabetico (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    cita                            INTEGER DEFAULT NULL,
    paciente                        INTEGER NOT NULL,
    personal                        INTEGER NOT NULL,
    grado_wagner                    SMALLINT NOT NULL, -- AGREGAR
    localizacion_lesion             SMALLINT NOT NULL,
    pie_afectado                    SMALLINT NOT NULL,
    tamanyo_lesion_cm               NUMERIC(6, 4) NOT NULL DEFAULT 0,
    profundidad_lesion_cm           NUMERIC(6, 4) NOT NULL DEFAULT 0,
    presenta_infeccion              SMALLINT NOT NULL DEFAULT 0,
    presenta_necrosis               SMALLINT NOT NULL DEFAULT 0,
    tratamiento_aplicado            VARCHAR(2000) DEFAULT NULL,
    curas_semanales                 VARCHAR(2000) DEFAULT NULL,
    evolucion                       SMALLINT NOT NULL,
    observaciones                   VARCHAR(2000) DEFAULT NULL,
    registro                        INTEGER NOT NULL,
    f_seguimiento                   TIMESTAMP NOT NULL,
    f_proximo_control               TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_seguimientopiediabetico_cita FOREIGN KEY(cita) REFERENCES citas(id),
    CONSTRAINT FK_seguimientopiediabetico_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_seguimientopiediabetico_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_seguimientopiediabetico_gradowagner FOREIGN KEY(grado_wagner) REFERENCES grado_wagner(id),
    CONSTRAINT FK_seguimientopiediabetico_localizacionlesion FOREIGN KEY(localizacion_lesion) REFERENCES tipos_localizacion_lesion(id),
    CONSTRAINT FK_seguimientopiediabetico_pieafectado FOREIGN KEY(pie_afectado) REFERENCES pie_lado(id),
    CONSTRAINT FK_seguimientopiediabetico_evolucion FOREIGN KEY(evolucion) REFERENCES tipos_evolucion(id),
    CONSTRAINT FK_seguimientopiediabetico_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE tipos_exudado (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(40) NOT NULL
);

CREATE TABLE color_exudado (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    color                           VARCHAR(40) NOT NULL
);

CREATE TABLE tipos_dolor (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    dolor                           VARCHAR(40) NOT NULL
);

CREATE TABLE registro_ulceras (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    cita                            INTEGER DEFAULT NULL,
    paciente                        INTEGER NOT NULL,
    personal                        INTEGER NOT NULL,
    ubicacion_anatomica             VARCHAR(255) NOT NULL,
    pie_afectado                    SMALLINT NOT NULL,
    largo_cm                        NUMERIC(6, 4) NOT NULL DEFAULT 0,
    ancho_cm                        NUMERIC(6, 4) NOT NULL DEFAULT 0,
    profundidad_cm                  NUMERIC(6, 4) NOT NULL DEFAULT 0,
    tejido                          SMALLINT NOT NULL,
    exudado                         SMALLINT NOT NULL,
    color_exudado                   SMALLINT NOT NULL,
    signos_infeccion                SMALLINT NOT NULL DEFAULT 0,
    olor_desagradable               SMALLINT NOT NULL DEFAULT 0,
    dolor                           SMALLINT NOT NULL,
    tratamiento_aplicado            VARCHAR(2000) DEFAULT NULL,
    tipo_aposito                    VARCHAR(2000) DEFAULT NULL, --VERIFICAR SI AGREGO TABLA APOSITOS
    observaciones                   VARCHAR(2000) DEFAULT NULL,
    registro                        INTEGER NOT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_curacion                      TIMESTAMP NOT NULL,
    f_proxima_curacion              TIMESTAMP DEFAULT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_registroulceras_cita FOREIGN KEY(cita) REFERENCES citas(id),
    CONSTRAINT FK_registroulceras_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_registroulceras_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_registroulceras_pieafectado FOREIGN KEY(pie_afectado) REFERENCES pie_lado(id),
    CONSTRAINT FK_registroulceras_tejido FOREIGN KEY(tejido) REFERENCES tipos_tejido(id),
    CONSTRAINT FK_registroulceras_exudado FOREIGN KEY(exudado) REFERENCES tipos_exudado(id),
    CONSTRAINT FK_registroulceras_colorexudado FOREIGN KEY(color_exudado) REFERENCES color_exudado(id),
    CONSTRAINT FK_registroulceras_dolor FOREIGN KEY(dolor) REFERENCES tipos_dolor(id),
    CONSTRAINT FK_registroulceras_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE material_plantillas (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    material                        VARCHAR(40) NOT NULL
);

CREATE TABLE tipos_efectividad (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    efectividad                     VARCHAR(40) NOT NULL
);

CREATE TABLE tipos_plantillas (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    plantillas                      VARCHAR(40) NOT NULL
);

CREATE TABLE plantillas_ortesis (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    cita                            INTEGER DEFAULT NULL,
    paciente                        INTEGER NOT NULL,
    personal                        INTEGER NOT NULL,
    plantilla                       SMALLINT NOT NULL,
    descripcion                     VARCHAR(1024) DEFAULT NULL,
    material                        SMALLINT NOT NULL,
    caracteristicas_tecnicas        VARCHAR(1024) DEFAULT NULL,
    f_preinscripcion                TIMESTAMP DEFAULT NULL,
    f_fabricacion                   TIMESTAMP DEFAULT NULL,
    f_entrega                       TIMESTAMP DEFAULT NULL,
    efectividad                     SMALLINT NOT NULL,
    observaciones_seguimiento       VARCHAR(2000) DEFAULT NULL,
    registro                        INTEGER NOT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_plantillasortesis_cita FOREIGN KEY(cita) REFERENCES citas(id),
    CONSTRAINT FK_plantillasortesis_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_plantillasortesis_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_plantillasortesis_plantilla FOREIGN KEY(plantilla) REFERENCES tipos_plantillas(id),
    CONSTRAINT FK_plantillasortesis_material FOREIGN KEY(material) REFERENCES material_plantillas(id),
    CONSTRAINT FK_plantillasortesis_efectividad FOREIGN KEY(efectividad) REFERENCES tipos_efectividad(id),
    CONSTRAINT FK_plantillasortesis_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE tipo_contribuyente (
    id                              CHAR(1) PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(40) NOT NULL
);

INSERT INTO tipo_contribuyente(id, codigo, tipo) VALUES('F', 'fisica', 'Persona Fisica'), ('M', 'moral', 'Persona Moral');

CREATE TABLE proveedores (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    rfc                             VARCHAR(15) DEFAULT NULL,
    razon_social                    VARCHAR(255) NOT NULL,
    representante                   VARCHAR(255) NOT NULL,
    tipo_contribuyente              CHAR(1) NOT NULL,
    telefono_1                      VARCHAR(15) DEFAULT NULL,
    telefono_2                      VARCHAR(15) DEFAULT NULL,
    celular                         VARCHAR(15) DEFAULT NULL,
    email                           VARCHAR(255) DEFAULT NULL,
    calle                           VARCHAR(120) DEFAULT NULL,
    num_ext                         VARCHAR(12) DEFAULT NULL,
    num_int                         VARCHAR(12) DEFAULT NULL,
    colonia                         INTEGER DEFAULT NULL,
    adeudos                         NUMERIC(18, 2) NOT NULL DEFAULT 0,
    registro                        INTEGER NOT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_proveedores_tipocontribuyente FOREIGN KEY(tipo_contribuyente) REFERENCES tipo_contribuyente(id),
    CONSTRAINT FK_proveedores_colonia FOREIGN KEY(colonia) REFERENCES colonias(id),
    CONSTRAINT FK_proveedores_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE requisiciones_estatus (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL,
    estatus                         VARCHAR(40) NOT NULL
);

INSERT INTO requisiciones_estatus(id, codigo, estatus) VALUES(1, 'capturada', 'Orden Capturada'),
                                                                (2, 'enviada', 'Orden Enviada'),
                                                                (3, 'autorizada', 'Orden Autorizada'),
                                                                (4, 'rechazada', 'Orden Rechazada'),
                                                                (5, 'finalizada', 'Orden Finalizada');

CREATE TABLE requisiciones (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    solicito                        INTEGER NOT NULL,
    autorizo                        INTEGER NOT NULL,
    f_solicitud                     TIMESTAMP NOT NULL,
    f_autorizacion                  TIMESTAMP DEFAULT NULL,
    f_rechazada                     TIMESTAMP DEFAULT NULL,
    estatus                         SMALLINT NOT NULL,
    notas                           VARCHAR(512) DEFAULT NULL,
    registro                        INTEGER NOT NULL,
    f_registrada                    TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_requisiciones_solicito FOREIGN KEY(solicito) REFERENCES usuarios(id),
    CONSTRAINT FK_requisiciones_autorizo FOREIGN KEY(autorizo) REFERENCES usuarios(id),
    CONSTRAINT FK_requisiciones_estatus FOREIGN KEY(estatus) REFERENCES requisiciones_estatus(id),
    CONSTRAINT FK_requisiciones_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE requisiciones_articulos (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    requisicion                     INTEGER NOT NULL,
    articulo                        INTEGER NOT NULL,
    cantidad_solicitada             NUMERIC(12, 4) NOT NULL DEFAULT 0,
    cantidad_autorizada             NUMERIC(12, 4) NOT NULL DEFAULT 0,
    notas                           VARCHAR(255) DEFAULT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_requisicionesarticulos_requisicion FOREIGN KEY(requisicion) REFERENCES requisiciones(id),
    CONSTRAINT FK_requisicionesarticulos_articulo FOREIGN KEY(articulo) REFERENCES articulos(id)
);

CREATE TABLE ordenes_compra_estatus (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL,
    estatus                         VARCHAR(40) NOT NULL
);

INSERT INTO ordenes_compra_estatus (id, codigo, estatus) VALUES(1, 'capturada', 'Capturada'),
                                                                (2, 'autorizada', 'Lista para enviarse'),
                                                                (3, 'enviada', 'Proveedor la recibio'),
                                                                (4, 'confirmada', 'Proveedor acepto'),
                                                                (5, 'parcial-recibida', 'Parcialmente recibida'),
                                                                (6, 'recibida', 'Recibida'),
                                                                (7, 'parcial-facturada', 'Facturación incompleta'),
                                                                (8, 'facturada', 'Facturada'),
                                                                (9, 'cancelada', 'Anulada antes de terminar'),
                                                                (10, 'cerrada', 'Proceso terminado');

CREATE TABLE ordenes_compra (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    proveedor                       INTEGER NOT NULL,
    requisicion                     INTEGER DEFAULT NULL,
    f_orden                         TIMESTAMP NOT NULL,
    f_autorizacion                  TIMESTAMP DEFAULT NULL,
    f_enviada                       TIMESTAMP DEFAULT NULL,
    f_cancelada                     TIMESTAMP DEFAULT NULL,
    f_cerrada                       TIMESTAMP DEFAULT NULL,
    plazo_dias                      INTEGER NOT NULL,
    f_esperada                      TIMESTAMP NOT NULL,
    estatus                         SMALLINT NOT NULL,
    subtotal                        NUMERIC(18, 2) NOT NULL DEFAULT 0,
    impuestos                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    total                           NUMERIC(18, 2) NOT NULL DEFAULT 0,
    pagado                          NUMERIC(18, 2) NOT NULL DEFAULT 0,
    f_ultimo_pago                   TIMESTAMP DEFAULT NULL,
    solicito                        INTEGER NOT NULL,
    autorizo                        INTEGER NOT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_ordenescompra_proveedor FOREIGN KEY(proveedor) REFERENCES proveedores(id),
    CONSTRAINT FK_ordenescompra_requisicion FOREIGN KEY(requisicion) REFERENCES requisiciones(id),
    CONSTRAINT FK_ordenescompra_estatus FOREIGN KEY(estatus) REFERENCES ordenes_compra_estatus(id),
    CONSTRAINT FK_ordenescompra_solicito FOREIGN KEY(solicito) REFERENCES usuarios(id),
    CONSTRAINT FK_ordenescompra_autorizo FOREIGN KEY(autorizo) REFERENCES usuarios(id)
);

CREATE TABLE impuestos (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    descripcion                     VARCHAR(30) NOT NULL
);

INSERT INTO impuestos(id, codigo, descripcion) VALUES(1, 'IVA', 'Impuesto al Valor Agregado'), (2, 'IEPS', 'Impuesto Especial sobre Producción y Servicios'), (3, 'ISR', 'Impuesto Sobre la Renta');

CREATE TABLE perfil_impuestos (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    perfil                          VARCHAR(30) NOT NULL
);

INSERT INTO perfil_impuestos(id, codigo, perfil) VALUES(1, 'general', 'General'), (2, 'frontera', 'Frontera'), (3, 'exento', 'Exento');

CREATE TABLE perfil_impuestos_detalle (
    id                              SMALLINT PRIMARY KEY,
    perfil                          SMALLINT NOT NULL,
    impuesto                        SMALLINT NOT NULL,
    tasa                            NUMERIC(8, 6) NOT NULL,
    vigente_desde                   TIMESTAMP NOT NULL,
    vigente_hasta                   TIMESTAMP NOT NULL,
    CONSTRAINT FK_perfilimpuestosdetalle_perfil FOREIGN KEY(perfil) REFERENCES perfil_impuestos(id),
    CONSTRAINT FK_perfilimpuestosdetalle_impuesto FOREIGN KEY(impuesto) REFERENCES impuestos(id)
);

CREATE TABLE ordenes_compra_articulos (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    orden_compra                    INTEGER NOT NULL,
    articulo                        INTEGER NOT NULL,
    cantidad_solicitada             NUMERIC(12, 4) NOT NULL DEFAULT 0,
    cantidad_autorizada             NUMERIC(12, 4) NOT NULL DEFAULT 0,
    cantidad_recibida               NUMERIC(12, 4) NOT NULL DEFAULT 0,
    costo_unidad                    NUMERIC(18, 2) NOT NULL DEFAULT 0,
    subtotal                        NUMERIC(18, 2) NOT NULL DEFAULT 0,
    impuestos                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    total                           NUMERIC(18, 2) NOT NULL DEFAULT 0,
    tasa                            NUMERIC(8, 6) NOT NULL DEFAULT 0,
    perfil_impuesto                 SMALLINT NOT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_ordenescompraarticulos_ordencompra FOREIGN KEY(orden_compra) REFERENCES ordenes_compra(id),
    CONSTRAINT FK_ordenescompraarticulos_articulo FOREIGN KEY(articulo) REFERENCES articulos(id),
    CONSTRAINT FK_ordenescompraarticulos_perfilimpuesto FOREIGN KEY(perfil_impuesto) REFERENCES perfil_impuestos(id)
);

CREATE TABLE productos_categoria (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    categoria                       VARCHAR(60) NOT NULL
);

CREATE TABLE productos (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    clave                           VARCHAR(12) NOT NULL UNIQUE,
    codigo_barras                   VARCHAR(32) NOT NULL UNIQUE,
    nombre                          VARCHAR(100) NOT NULL,
    nombre_ticket                   VARCHAR(32) NOT NULL,
    categoria                       SMALLINT NOT NULL,
    descripcion                     VARCHAR(255) DEFAULT NULL,
    unidad                          VARCHAR(8) NOT NULL DEFAULT 1,
    habilitado_venta                SMALLINT NOT NULL DEFAULT 1,
    registro                        INTEGER NOT NULL,
    f_registrado                    TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_productos_categoria FOREIGN KEY(categoria) REFERENCES productos_categoria(id),
    CONSTRAINT FK_productos_unidad FOREIGN KEY(unidad) REFERENCES unidades(id),
    CONSTRAINT FK_productos_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE productos_articulos (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    producto                        INTEGER NOT NULL,
    articulo                        INTEGER NOT NULL,
    cantidad                        NUMERIC(12, 4) NOT NULL DEFAULT 0,
    vigente_desde                   TIMESTAMP NOT NULL,
    vigente_hasta                   TIMESTAMP DEFAULT NULL,
    CONSTRAINT FK_productosarticulos_producto FOREIGN KEY(producto) REFERENCES productos(id),
    CONSTRAINT FK_productosarticulos_articulo FOREIGN KEY(articulo) REFERENCES articulos(id)
);

CREATE TABLE productos_precios (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    producto                        INTEGER NOT NULL,
    precio_base                     NUMERIC(18, 2) NOT NULL DEFAULT 0,
    perfil_impuesto                 SMALLINT NOT NULL,
    vigente_desde                   TIMESTAMP NOT NULL,
    vigente_hasta                   TIMESTAMP NOT NULL,
    CONSTRAINT FK_productosprecios_producto FOREIGN KEY(producto) REFERENCES productos(id),
    CONSTRAINT FK_productosprecios_perfilimpuesto FOREIGN KEY(perfil_impuesto) REFERENCES perfil_impuestos(id)
);

CREATE TABLE ventas_estatus (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    estatus                         VARCHAR(60) NOT NULL
);

CREATE TABLE ventas (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    folio                           VARCHAR(10) NOT NULL UNIQUE,
    consecutivo                     SMALLINT NOT NULL,
    f_venta                         TIMESTAMP NOT NULL,
    cliente                         INTEGER NOT NULL,
    paciente                        INTEGER NOT NULL,
    registro                        INTEGER NOT NULL,
    subtotal                        NUMERIC(18, 2) NOT NULL DEFAULT 0,
    impuestos                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    total                           NUMERIC(18, 2) NOT NULL DEFAULT 0,
    descuento                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    estatus                         SMALLINT NOT NULL,
    observaciones                   VARCHAR(1024) NOT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_ventas_cliente FOREIGN KEY(cliente) REFERENCES clientes(id),
    CONSTRAINT FK_ventas_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_ventas_registro FOREIGN KEY(registro) REFERENCES usuarios(id),
    CONSTRAINT FK_ventas_estatus FOREIGN KEY(estatus) REFERENCES ventas_estatus(id)
);

CREATE TABLE tipos_descuentos (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    descuento                       VARCHAR(30) NOT NULL
);

CREATE TABLE ventas_productos (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    venta                           INTEGER NOT NULL,
    producto                        INTEGER NOT NULL,
    cita                            INTEGER DEFAULT NULL,
    cantidad                        NUMERIC(12, 4) NOT NULL DEFAULT 0,
    precio_base                     NUMERIC(18, 2) NOT NULL DEFAULT 0,
    subtotal                        NUMERIC(18, 2) NOT NULL DEFAULT 0,
    impuestos                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    total                           NUMERIC(18, 2) NOT NULL DEFAULT 0,
    descuento                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_ventasproductos_venta FOREIGN KEY(venta) REFERENCES ventas(id),
    CONSTRAINT FK_ventasproductos_producto FOREIGN KEY(producto) REFERENCES productos(id),
    CONSTRAINT FK_ventasproductos_cita FOREIGN KEY(cita) REFERENCES citas(id)
);

CREATE TABLE metodos_pago (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    metodo                          VARCHAR(60) NOT NULL,
    referencia                      SMALLINT DEFAULT 0
);

INSERT INTO metodos_pago(id, codigo, metodo, referencia) VALUES(1, 'efectivo', 'Efectivo', 0),
                                                                (2, 't-debito', 'Tarjeta Debito', 1),
                                                                (3, 't-credito', 'Tarjeta Credito', 1),
                                                                (4, 'transferencia', 'Transferencia', 1),
                                                                (5, 'cheque', 'Cheque', 1),
                                                                (6, 'internet', 'Internet', 1),
                                                                (7, 'credito', 'Credito', 1);

CREATE TABLE impresoras_conexion (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    conexion                        VARCHAR(30) NOT NULL
);

INSERT INTO impresoras_conexion(id, codigo, conexion) VALUES(1, 'usb', 'Cable USB'), (2, 'network', 'Red'), (3, 'bluetooth', 'Bluetooth');

CREATE TABLE impresoras (
    id                              SMALLINT PRIMARY KEY,
    tipo_conexion                   SMALLINT NOT NULL,
    direccion                       VARCHAR(30) NOT NULL,
    puerto                          VARCHAR(10) DEFAULT NULL,
    mac_address                     VARCHAR(20) DEFAULT NULL,
    ubicacion                       VARCHAR(60) DEFAULT NULL,
    CONSTRAINT FK_impresoras_tipoconexion FOREIGN KEY(tipo_conexion) REFERENCES impresoras_conexion(id)
);

CREATE TABLE cajas (
    id                              SMALLINT PRIMARY KEY,
    caja                            VARCHAR(30) NOT NULL,
    codigo                          VARCHAR(30) NOT NULL UNIQUE,
    ubicacion                       VARCHAR(60) DEFAULT NULL,
    oculta                          SMALLINT NOT NULL DEFAULT 0,
    activa                          SMALLINT NOT NULL DEFAULT 1,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL                        
);

CREATE TABLE cajas_impresoras (
    caja                            SMALLINT NOT NULL,
    impresora                       SMALLINT NOT NULL,
    CONSTRAINT FK_cajasimpresoras_caja FOREIGN KEY(caja) REFERENCES cajas(id),
    CONSTRAINT FK_cajasimpresoras_impresora FOREIGN KEY(impresora) REFERENCES impresoras(id)
);

CREATE TABLE cortes_estatus (
    id                              SMALLINT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL,
    estatus                         VARCHAR(30) NOT NULL
);

INSERT INTO cortes_estatus(id, codigo, estatus) VALUES(1, 'open', 'Abierta'), (2, 'closed', 'Cerrada'), (3, 'waiting', 'En Espera'), (4, 'busy', 'Ocupada');

CREATE TABLE cortes (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    caja                            SMALLINT NOT NULL,
    abierta_por                     INTEGER NOT NULL,
    f_abierta                       TIMESTAMP NOT NULL,
    monto_apertura                  NUMERIC(18, 2) NOT NULL,
    cerrada_por                     INTEGER DEFAULT NULL,
    f_cierre                        TIMESTAMP DEFAULT NULL,
    monto_cierre                    NUMERIC(18, 2) DEFAULT NULL,
    efectivo_esperado               NUMERIC(18, 2) DEFAULT NULL,
    retiros                         NUMERIC(18, 2) NOT NULL DEFAULT 0,
    depositos                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    diferencia                      NUMERIC(18, 2) DEFAULT NULL,
    estatus                         SMALLINT NOT NULL,
    observaciones                   VARCHAR(1024) DEFAULT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_cortes_caja FOREIGN KEY(caja) REFERENCES cajas(id),
    CONSTRAINT FK_cortes_abiertapor FOREIGN KEY(abierta_por) REFERENCES usuarios(id),
    CONSTRAINT FK_cortes_cerradapor FOREIGN KEY(cerrada_por) REFERENCES usuarios(id),
    CONSTRAINT FK_cortes_estatus FOREIGN KEY(estatus) REFERENCES cortes_estatus(id)
);

CREATE TABLE cortes_depositos (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    corte                           INTEGER NOT NULL,
    efectivo_antes                  NUMERIC(18, 2) NOT NULL DEFAULT 0,
    monto                           NUMERIC(18, 2) NOT NULL DEFAULT 0,
    efectivo_despues                NUMERIC(18, 2) NOT NULL DEFAULT 0,
    cajero                          INTEGER NOT NULL,
    entrego                         INTEGER NOT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_cortesdepositos_corte FOREIGN KEY(corte) REFERENCES cortes(id),
    CONSTRAINT FK_cortesdepositos_cajero FOREIGN KEY(cajero) REFERENCES usuarios(id),
    CONSTRAINT FK_cortesdepositos_entrego FOREIGN KEY(entrego) REFERENCES usuarios(id)
);

CREATE TABLE cortes_retiros (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    corte                           INTEGER NOT NULL,
    efectivo_antes                  NUMERIC(18, 2) NOT NULL DEFAULT 0,
    monto                           NUMERIC(18, 2) NOT NULL DEFAULT 0,
    efectivo_despues                NUMERIC(18, 2) NOT NULL DEFAULT 0,
    cajero                          INTEGER NOT NULL,
    retiro                          INTEGER NOT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_cortesretiros_corte FOREIGN KEY(corte) REFERENCES cortes(id),
    CONSTRAINT FK_cortesretiros_cajero FOREIGN KEY(cajero) REFERENCES usuarios(id),
    CONSTRAINT FK_cortesretiros_retiro FOREIGN KEY(retiro) REFERENCES usuarios(id)
);

CREATE TABLE pagos (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    folio                           VARCHAR(10) NOT NULL UNIQUE,
    consecutivo                     SMALLINT NOT NULL DEFAULT 0,
    f_pago                          TIMESTAMP NOT NULL,
    cliente                         INTEGER DEFAULT NULL,
    registro                        INTEGER NOT NULL,
    corte                           INTEGER NOT NULL,
    metodo_pago                     SMALLINT NOT NULL,
    referencia                      VARCHAR(25) DEFAULT NULL,
    observaciones                   VARCHAR(500) DEFAULT NULL,
    f_registro                      TIMESTAMP NOT NULL,
    f_actualizacion                 TIMESTAMP NOT NULL,
    CONSTRAINT FK_pagos_cliente FOREIGN KEY(cliente) REFERENCES clientes(id),
    CONSTRAINT FK_pagos_registro FOREIGN KEY(registro) REFERENCES usuarios(id),
    CONSTRAINT FK_pagos_corte FOREIGN KEY(corte) REFERENCES cortes(id),
    CONSTRAINT FK_pagos_metodopago FOREIGN KEY(metodo_pago) REFERENCES metodos_pago(id)
);

CREATE TABLE pagos_ventas (
    id                              INTEGER PRIMARY KEY,
    uuid                            BLOB NOT NULL UNIQUE,
    pago                            INTEGER NOT NULL,
    venta                           INTEGER NOT NULL,
    adeudo_anterior                 NUMERIC(18, 2) NOT NULL,
    monto_pago                      NUMERIC(18, 2) NOT NULL,
    adeudo_actual                   NUMERIC(18, 2) NOT NULL,
    CONSTRAINT FK_pagosventas_pago FOREIGN KEY(pago) REFERENCES pagos(id),
    CONSTRAINT FK_pagosventas_venta FOREIGN KEY(venta) REFERENCES ventas(id)
);








/****************************************************************************************************************************************************************
*****************************************************************************************************************************************************************
********************************************************** VALIDAR CITA MISMO DIA *******************************************************************************
*****************************************************************************************************************************************************************
*****************************************************************************************************************************************************************/

CREATE TRIGGER validar_rango_bloque
BEFORE INSERT ON citas_bloques
FOR EACH ROW
BEGIN

    -- rango válido
    SELECT CASE
        WHEN NEW.h_inicio < 0 OR NEW.h_inicio >= 1440
        THEN RAISE(ABORT, 'Hora inicio fuera de rango')
    END;

    SELECT CASE
        WHEN NEW.h_fin <= 0 OR NEW.h_fin > 1440
        THEN RAISE(ABORT, 'Hora fin fuera de rango')
    END;

    -- orden lógico
    SELECT CASE
        WHEN NEW.h_fin <= NEW.h_inicio
        THEN RAISE(ABORT, 'La hora fin debe ser mayor que inicio')
    END;

    -- coherencia duración
    SELECT CASE
        WHEN NEW.duracion <> (NEW.h_fin - NEW.h_inicio)
        THEN RAISE(ABORT, 'Duración inconsistente con horas')
    END;

END;

CREATE TRIGGER validar_empalme_personal
BEFORE INSERT ON citas_bloques
FOR EACH ROW
BEGIN
    SELECT CASE
        WHEN EXISTS (
            SELECT 1
            FROM citas_bloques b
            JOIN citas c ON c.id = b.cita
            WHERE b.personal = NEW.personal
              AND c.fecha = (SELECT fecha FROM citas WHERE id = NEW.cita)
              AND NEW.h_inicio < b.h_fin
              AND NEW.h_fin > b.h_inicio
        )
        THEN RAISE(ABORT, 'El personal ya tiene una cita en ese horario')
    END;
END;


/* SELECT count(*) FROM sqlite_master WHERE type = 'table'; */