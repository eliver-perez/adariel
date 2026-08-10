CREATE TABLE paises (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    pais                            VARCHAR(50) NOT NULL,
    abbr                            VARCHAR(5) DEFAULT NULL,
    lada                            SMALLINT DEFAULT NULL
);

CREATE TABLE estados (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    estado                          VARCHAR(80) NOT NULL,
    pais                            SMALLINT NOT NULL,
    CONSTRAINT FK_estados_pais FOREIGN KEY(pais) REFERENCES paises(id)
);

CREATE TABLE municipios (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    municipio                       VARCHAR(80) NOT NULL,
    estado                          INT NOT NULL,
    CONSTRAINT FK_municipios_estado FOREIGN KEY(estado) REFERENCES estados(id)
);


CREATE TABLE colonias (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    colonia                         VARCHAR(80) NOT NULL,
    municipio                       INT NOT NULL,
    cp                              VARCHAR(5) DEFAULT NULL,
    CONSTRAINT FK_colonias_municipio FOREIGN KEY(municipio) REFERENCES municipios(id)
);

CREATE TABLE empresas (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    consecutivo                     INT DEFAULT NULL,
    clave                           VARCHAR(20) DEFAULT NULL,
    empresa                         VARCHAR(120) NOT NULL,
    calle                           VARCHAR(120) DEFAULT NULL,
    num_ext                         VARCHAR(12) DEFAULT NULL,
    num_int                         VARCHAR(12) DEFAULT NULL,
    colonia                         INT DEFAULT NULL,
    cp                              VARCHAR(5) DEFAULT NULL,
    telefono                        VARCHAR(40) DEFAULT NULL,
    movil                           VARCHAR(40) DEFAULT NULL,
    email                           VARCHAR(255) DEFAULT NULL,
    encargado                       VARCHAR(255) DEFAULT NULL,
    activo                          TINYINT NOT NULL DEFAULT 1,
    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    CONSTRAINT UK_empresas_clave UNIQUE(clave),
    CONSTRAINT FK_empresas_colonia FOREIGN KEY(colonia) REFERENCES colonias(id)
);

-- INSERT INTO empresas(uuid, empresa, domicilio, esta_empresa) VALUES(X'30313866386333612d386630622d3762', 'Clinica 1', 'Domicilio Conocido 1');

CREATE TABLE sucursales (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    consecutivo                     INT DEFAULT NULL,
    clave                           VARCHAR(20) DEFAULT NULL,
    empresa                         INT NOT NULL,
    sucursal                        VARCHAR(120) NOT NULL,
    calle                           VARCHAR(120) DEFAULT NULL,
    num_ext                         VARCHAR(12) DEFAULT NULL,
    num_int                         VARCHAR(12) DEFAULT NULL,
    colonia                         INT DEFAULT NULL,
    cp                              VARCHAR(5) DEFAULT NULL,
    telefono                        VARCHAR(40) DEFAULT NULL,
    movil                           VARCHAR(40) DEFAULT NULL,
    email                           VARCHAR(255) DEFAULT NULL,
    encargado                       VARCHAR(255) DEFAULT NULL,
    activo                          TINYINT NOT NULL DEFAULT 1,
    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    CONSTRAINT UK_sucursales_clave UNIQUE(clave, empresa),
    CONSTRAINT FK_sucursales_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_sucursales_colonia FOREIGN KEY(colonia) REFERENCES colonias(id)
);

CREATE TABLE tipos_datos (
    id              TINYINT AUTO_INCREMENT PRIMARY KEY,
    codigo          VARCHAR(20) NOT NULL UNIQUE,
    tipo            VARCHAR(30) NOT NULL
);

INSERT INTO tipos_datos(codigo, tipo) VALUES('int', 'Entero'),
                                                    ('string', 'Texto'),
                                                    ('float', 'Flotante'),
                                                    ('double', 'Double'),
                                                    ('money', 'Dinero'),
                                                    ('date', 'Fecha'),
                                                    ('datetime', 'Fecha y Hora');

CREATE TABLE unidades (
    id                              VARCHAR(8) PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    unidad                          VARCHAR(40) NOT NULL
);

INSERT INTO unidades (id, codigo, unidad) VALUES
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

('ML',  'ml', 'Mililitro'),
('L',   'l', 'Litro'),
('CC',  'cc', 'Centímetro cúbico'),
('GOT', 'gotas', 'Gotas'),
('DL',  'dl', 'Decilitro'),

('G',   'g', 'Gramo'),
('MG',  'mg', 'Miligramo'),
('KG',  'kg', 'Kilogramo'),

('APL', 'aplic', 'Aplicación'),
('DOS', 'dosis', 'Dosis'),
('SES', 'sesion', 'Sesión'),
('CUR', 'curacion', 'Curación'),
('SER', 'serv', 'Servicio'),
('TRA', 'trat', 'Tratamiento'),

('GAS', 'gasa', 'Gasa'),
('COM', 'comp', 'Compresa'),
('HIS', 'hisopo', 'Hisopo'),
('CAM', 'campo', 'Campo quirúrgico'),
('JER', 'jeringa', 'Jeringa'),
('AGU', 'aguja', 'Aguja');

CREATE TABLE usuarios_tipos (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(30) NOT NULL
);

INSERT INTO usuarios_tipos(codigo, tipo) VALUES('superadmin', 'Super Administrador'),
                                                    ('administrador', 'Administrador'),
                                                    ('gerente', 'Gerente'),
                                                    ('supervisor', 'Supervisor'),
                                                    ('podologo', 'Podologo'),
                                                    ('finanzas', 'Finanzas'),
                                                    ('enfermero', 'Enfermero'),
                                                    ('caja', 'Caja');

CREATE TABLE puestos (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    puesto                          VARCHAR(30)
);

INSERT INTO puestos(codigo, puesto) VALUES('recepcion', 'Recepción'),
                                                ('supervisor', 'Supervisor'),
                                                ('caja', 'Caja'),
                                                ('medico', 'Medico'),
                                                ('enfermero', 'Enfermero'),
                                                ('contabilidad', 'Contabilidad');

CREATE TABLE especialidades (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(30) NOT NULL UNIQUE,
    especialidad                    VARCHAR(40) NOT NULL UNIQUE,
    descripcion                     VARCHAR(512) DEFAULT NULL
);

INSERT INTO especialidades(codigo, especialidad) VALUES('sin-especialidad', 'Sin Especialidad'),
                                                            ('medico-general', 'Medico General'),
                                                            ('podologo', 'Podologo');

CREATE TABLE generos (
    id                              VARCHAR(1) PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    genero                          VARCHAR(15) NOT NULL
);

INSERT INTO generos(id, codigo, genero) VALUES('N', 'N/D', 'N/D'), ('H', 'hombre', 'Hombre'), ('M', 'mujer', 'Mujer');

CREATE TABLE personal_estatus (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    estatus                         VARCHAR(30) NOT NULL
);

INSERT INTO personal_estatus(codigo, estatus) VALUES('active', 'Activo');
INSERT INTO personal_estatus(codigo, estatus) VALUES('not-working', 'Baja');
INSERT INTO personal_estatus(codigo, estatus) VALUES('suspended', 'Suspendido');
INSERT INTO personal_estatus(codigo, estatus) VALUES('vacations', 'Vacaciones');

CREATE TABLE personal (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    empresa                         INT NOT NULL,
    rfc                             VARCHAR(14) DEFAULT NULL,
    nombre                          VARCHAR(60) NOT NULL,
    paterno                         VARCHAR(40) DEFAULT NULL,
    materno                         VARCHAR(40) DEFAULT NULL,
    f_nacimiento                    DATE DEFAULT NULL,
    calle                           VARCHAR(120) DEFAULT NULL,
    num_ext                         VARCHAR(12) DEFAULT NULL,
    num_int                         VARCHAR(12) DEFAULT NULL,
    colonia                         INT DEFAULT NULL,
    email                           VARCHAR(255) DEFAULT NULL,
    curp                            VARCHAR(20) DEFAULT NULL,
    telefono                        VARCHAR(40) DEFAULT NULL,
    movil                           VARCHAR(40) DEFAULT NULL,
    genero                          VARCHAR(1) NOT NULL,
    puesto                          SMALLINT NOT NULL,
    estatus                         SMALLINT NOT NULL,
    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    CONSTRAINT FK_personal_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_personal_colonia FOREIGN KEY(colonia) REFERENCES colonias(id),
    CONSTRAINT FK_personal_genero FOREIGN KEY(genero) REFERENCES generos(id),
    CONSTRAINT FK_personal_puesto FOREIGN KEY(puesto) REFERENCES puestos(id),
    CONSTRAINT FK_personal_estatus FOREIGN KEY(estatus) REFERENCES personal_estatus(id)
);

-- INSERT INTO personal(uuid, nombre, paterno, puesto, estatus, genero, f_registro) VALUES(X'A6B3D104D4594260B300A8B2A0EAB2D7', 'Juan', 'Perez', 4, 1, 'H', NOW()),
--                                                                                         (X'3EFFBD0D74F1402796755BAC96ACE4BA', 'Eliver', 'Perez', 4, 1, 'H', NOW());

CREATE TABLE personal_sucursales (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,

    personal                        INT NOT NULL,
    sucursal                        INT NOT NULL,

    principal                       TINYINT NOT NULL DEFAULT 0,
    activo                          TINYINT NOT NULL DEFAULT 1,

    f_registro                      DATETIME NOT NULL,
    f_baja                          DATETIME DEFAULT NULL,

    CONSTRAINT UK_personalsucursales UNIQUE(personal, sucursal),
    CONSTRAINT FK_personalsucursales_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_personalsucursales_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id)
);

CREATE TABLE personal_profesional (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    personal                        INT NOT NULL,
    cedula                          VARCHAR(12) DEFAULT NULL,
    especialidad                    SMALLINT NOT NULL,
    universidad                     VARCHAR(250) DEFAULT NULL,
    egreso                          SMALLINT DEFAULT NULL,
    universidad_municipio           INT DEFAULT NULL,
    color_agenda                    VARCHAR(7) NOT NULL DEFAULT '#07F',
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    CONSTRAINT FK_personalprofesional_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_personalprofesional_especialidad FOREIGN KEY(especialidad) REFERENCES especialidades(id)
);

CREATE TABLE personal_altas (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    personal                        INT NOT NULL,
    f_alta                          DATE NOT NULL,
    f_baja                          DATE DEFAULT NULL,
    razon_baja                      VARCHAR(512) DEFAULT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    CONSTRAINT FK_personalaltas_personal FOREIGN KEY(personal) REFERENCES personal(id)
);

CREATE TABLE usuarios (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    empresa                         INT DEFAULT NULL,
    email                           VARCHAR(150) NOT NULL UNIQUE,
    nombre                          VARCHAR(120) DEFAULT NULL,
    password_hash                   VARCHAR(255) NOT NULL,
    tipo_usuario                    SMALLINT NOT NULL,
    activo                          SMALLINT NOT NULL DEFAULT 1,
    registro                        INT DEFAULT NULL,
    f_registro                      DATETIME NOT NULL,
    f_ultima_conexion               DATETIME DEFAULT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    CONSTRAINT UK_usuarios_email UNIQUE(email),
    CONSTRAINT FK_usuarios_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_usuarios_tipo FOREIGN KEY(tipo_usuario) REFERENCES usuarios_tipos(id),
    CONSTRAINT FK_usuarios_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

INSERT INTO usuarios(uuid, email, nombre, password_hash, tipo_usuario, activo, f_registro) 
                VALUES(X'C475E751FD1547DDA8500D566F200F24', 'eliverperez90@gmail.com', 'Administrador', '$2y$10$MqUTuFBUs.OIhkWSAxL3A.RfbglmDA9Uy/vgfYNOUvs2kI0EkBaYK', 1, 1, NOW());

-- INSERT INTO usuarios(uuid, nombre, usuario, password_hash, tipo_usuario, activo, f_registro) 
--                 VALUES(X'C475E751FD1547DDA8500D566F200F24', 'Admin', 'admin', '$2y$10$MqUTuFBUs.OIhkWSAxL3A.RfbglmDA9Uy/vgfYNOUvs2kI0EkBaYK', 1, 1, NOW()),
--                         (X'f47d622e396b465f968a75a9beaa966f', 'Juan', 'juan', '$2y$10$r/SCylnVyrMQ9kJybTpkj.UFvAOKpR6eRSw6/fgbs09Rw8Fe.RGGq', 4, 1, NOW()),
--                         (X'0xf47d622e396b465f968a75a9beaa966f', 'Eliver', 'eliver', '$2y$10$r/SCylnVyrMQ9kJybTpkj.UFvAOKpR6eRSw6/fgbs09Rw8Fe.RGGq', 4, 1, NOW());

ALTER TABLE personal ADD CONSTRAINT FK_personal_registro FOREIGN KEY(registro) REFERENCES usuarios(id);
ALTER TABLE empresas ADD CONSTRAINT FK_empresas_registro FOREIGN KEY(registro) REFERENCES usuarios(id);
ALTER TABLE sucursales ADD CONSTRAINT FK_sucursales_registro FOREIGN KEY(registro) REFERENCES usuarios(id);

CREATE TABLE personal_usuarios (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    personal                        INT NOT NULL,
    usuario                         INT NOT NULL,
    activo                          TINYINT NOT NULL DEFAULT 1,
    f_registro                      DATETIME NOT NULL,
    f_removido                      DATETIME DEFAULT NULL,
    CONSTRAINT FK_personalusuarios_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_personalusuarios_usuario FOREIGN KEY(usuario) REFERENCES usuarios(id)
);

-- INSERT INTO personal_usuarios(personal, usuario, f_registro) VALUES(1, 2, NOW()),
--                                                                     (2, 3, NOW());

CREATE TABLE personal_sueldos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    personal                        INT NOT NULL,
    sueldo_anterior                 NUMERIC(18, 2) NOT NULL DEFAULT 0,
    sueldo_actual                   NUMERIC(18, 2) NOT NULL DEFAULT 0,
    actualizo                       INT NOT NULL,
    f_apartir_de                    DATE NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_personalsueldos_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_personalsueldos_actualizo FOREIGN KEY(actualizo) REFERENCES usuarios(id)
);

CREATE TABLE usuarios_sucursales_roles (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    usuario                         INT NOT NULL,
    sucursal                        INT NOT NULL,
    tipo_usuario                    SMALLINT NOT NULL,
    activo                          TINYINT NOT NULL DEFAULT 1,
    f_registro                      DATETIME NOT NULL,
    f_baja                          DATETIME DEFAULT NULL,

    UNIQUE(usuario, sucursal, tipo_usuario),

    FOREIGN KEY(usuario) REFERENCES usuarios(id),
    FOREIGN KEY(sucursal) REFERENCES sucursales(id),
    FOREIGN KEY(tipo_usuario) REFERENCES usuarios_tipos(id)
);

-- INSERT INTO usuarios_sucursales_roles(usuario, sucursal, tipo_usuario, f_registro) VALUES(1, 1, 1, NOW()),
--                                                                                         (2, 1, 4, NOW()),
--                                                                                         (3, 1, 7, NOW());
                                                    
CREATE TABLE ajustes_tipo (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(15) NOT NULL UNIQUE,
    tipo                            VARCHAR(25) NOT NULL
);

INSERT INTO ajustes_tipo(codigo, tipo) VALUES('int', 'Entero'),
                                                    ('float', 'Float'),
                                                    ('money', 'Money'),
                                                    ('string', 'String'),
                                                    ('json', 'JSON'),
                                                    ('boolean', 'Boolean');

CREATE TABLE ajustes_categoria (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(15) NOT NULL UNIQUE,
    categoria                       VARCHAR(30) NOT NULL
);

INSERT INTO ajustes_categoria(codigo, categoria) VALUES('general', 'General'),
                                                            ('agenda', 'Agenda'),
                                                            ('facturacion', 'Facturacion'),
                                                            ('seguridad', 'Seguridad'),
                                                            ('notificaciones', 'Notificaciones');

CREATE TABLE ajustes (
    id                      VARCHAR(100) PRIMARY KEY,

    descripcion             VARCHAR(255) NOT NULL,

    valor_defecto           TEXT NOT NULL,

    categoria               SMALLINT NOT NULL,
    tipo                    SMALLINT NOT NULL DEFAULT 4,

    activo                  TINYINT NOT NULL DEFAULT 1,

    CONSTRAINT FK_ajustes_tipo
        FOREIGN KEY(tipo) REFERENCES ajustes_tipo(id),

    CONSTRAINT FK_ajustes_categoria
        FOREIGN KEY(categoria) REFERENCES ajustes_categoria(id)
);

CREATE INDEX idx_ajustes_categoria ON ajustes(categoria);

INSERT INTO ajustes (
    id,
    descripcion,
    valor_defecto,
    categoria,
    tipo
) VALUES
(
    'clinica',
    'Nombre de la Clinica.',
    'Mi Empresa',
    1,
    4
),
(
    'codigo_empresa',
    'Código para la clave de empresas.',
    'AE',
    1,
    4
),
(
    'codigo_sucursal',
    'Código para la clave de empresas.',
    'AES',
    1,
    4
),
(
    'codigo_paciente',
    'Código para la clave de pacientes.',
    'PE',
    1,
    4
),
(
    'codigo_cliente',
    'Codigo para la clave de clientes.',
    'CE',
    1,
    4
),
(
    'codigo_cita',
    'Codigo para las citas.',
    'AT',
    1,
    4
),
(
    'agenda_intervalo_minutos',
    'Intervalo de tiempo para bloques de citas.',
    '15',
    2,
    1
),
(
    'agenda_horario_empresa',
    'Horario general de atención de la empresa',
    '{
        "lunes": {
            "activo": true,
            "periodos": [
                {
                    "inicio": "10:00",
                    "fin": "19:00"
                }
            ]
        },
        "martes": {
            "activo": true,
            "periodos": [
                {
                    "inicio": "10:00",
                    "fin": "19:00"
                }
            ]
        },
        "miercoles": {
            "activo": true,
            "periodos": [
                {
                    "inicio": "10:00",
                    "fin": "19:00"
                }
            ]
        },
        "jueves": {
            "activo": true,
            "periodos": [
                {
                    "inicio": "10:00",
                    "fin": "19:00"
                }
            ]
        },
        "viernes": {
            "activo": true,
            "periodos": [
                {
                    "inicio": "10:00",
                    "fin": "19:00"
                }
            ]
        },
        "sabado": {
            "activo": true,
            "periodos": [
                {
                    "inicio": "09:00",
                    "fin": "16:00"
                }
            ]
        },
        "domingo": {
            "activo": false,
            "periodos": []
        }
    }',
    (SELECT id FROM ajustes_categoria WHERE codigo = 'agenda'),
    (SELECT id FROM ajustes_tipo WHERE codigo = 'json')
);

CREATE TABLE ajustes_empresas (
    id                      INT AUTO_INCREMENT PRIMARY KEY,

    empresa                 INT NOT NULL,
    ajuste                  VARCHAR(100) NOT NULL,

    valor                   TEXT NOT NULL,

    registro                INT NOT NULL,
    f_registro              DATETIME NOT NULL,
    f_actualizacion         DATETIME DEFAULT NULL,

    CONSTRAINT UK_ajustesempresa UNIQUE(empresa, ajuste),
    CONSTRAINT FK_ajustesempresa_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_ajustesempresa_ajuste FOREIGN KEY(ajuste) REFERENCES ajustes(id),
    CONSTRAINT FK_ajustesempresa_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE permisos (
    id                              VARCHAR(30) PRIMARY KEY,
    permiso                         VARCHAR(255) NOT NULL,
    descripcion                     VARCHAR(1024) DEFAULT NULL,
    f_registro                      DATETIME NOT NULL
);

INSERT INTO permisos(id, permiso, f_registro) VALUES('superadmin', 'Administrador con permisos elevados.', NOW()),
                                                    ('admin', 'Usuario administrador', NOW()),
                                                    ('gerente-general', 'Gerente general de empresa', NOW()),
                                                    ('personal', 'Personal - Visualizar personal registrado.', NOW()),
                                                    ('personal-modificar', 'Personal - Registrar/Modificar.', NOW()),
                                                    ('usuarios', 'Usuario - Visualizar usuarios registrados.', NOW()),
                                                    ('usuarios-modificar', 'Usuarios - Modificar usuarios registrados.', NOW()),
                                                    ('permisos', 'Permisos - Visualizar roles y permisos.', NOW()),
                                                    ('permisos-modificar', 'Permisos - Modificar/Asignar roles y permisos.', NOW()),
                                                    ('horarios', 'Horarios - Visualizar horarios disponibles del personal.', NOW()),
                                                    ('horarios-modificar', 'Horarios - Modificar horarios disponibles del personal.', NOW()),
                                                    ('servicios', 'Servicios - Visualizar servicios registrados.', NOW()),
                                                    ('servicios-modificar', 'Servicios - Registrar/Modificar.', NOW()),
                                                    ('consentimientos', 'Consentimientos - Visualizar consentimientos registrados.', NOW()),
                                                    ('consentimientos-modificar', 'Consentimientos - Registrar/Modificar.', NOW()),
                                                    ('pacientes', 'Pacientes - Visualizar pacientes registrados.', NOW()),
                                                    ('pacientes-modificar', 'Pacientes - Registrar/Modificar.', NOW()),
                                                    ('agenda', 'Agenda - Visualizar citas registradas', NOW()),
                                                    ('agenda-modificar', 'Agenda - Registrar/Modificar citas en la agenda.', NOW()),
                                                    ('proveedores', 'Proveedores - Visualizar proveedores registrados.', NOW()),
                                                    ('proveedores-modificar', 'Proveedores - Registrar/Modificar.', NOW()),
                                                    ('inventario', 'Inventario - Visualizar inventario.', NOW()),
                                                    ('productos', 'Productos - Visualizar productos registrados', NOW()),
                                                    ('productos-modificar', 'Productos - Registrar/Modificar.', NOW()),
                                                    ('requisiciones', 'Requisiciones - Visualizar requisiciones registradas.', NOW()),
                                                    ('requisiciones-modificar', 'Requisiciones - Registrar/Modificar.', NOW()),
                                                    ('ordenes-compra', 'Ordenes de Compra - Visualizar ordenes de compra registradas.', NOW()),
                                                    ('ordenes-compra-modificar', 'Ordenes de Compra - Registrar/Modificar.', NOW()),
                                                    ('plantillas', 'Plantillas - Visualizar plantillas registradas.', NOW()),
                                                    ('ajustes', 'Ajustes - Visualizar ajustes registrados.', NOW()),
                                                    ('ajustes-modificar', 'Ajustes - Modificar ajustes de la plataforma.', NOW()),
                                                    ('citas-atender', 'Citas - Atender Citas.', NOW());

CREATE TABLE permisos_usuarios (
    uuid                            BINARY(16) NOT NULL UNIQUE,
    permiso                         VARCHAR(30) NOT NULL,
    usuario                         INT NOT NULL,
    sucursal                        INT DEFAULT NULL,
    valor                           SMALLINT NOT NULL DEFAULT 1,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_permisosusuarios_permiso FOREIGN KEY(permiso) REFERENCES permisos(id),
    CONSTRAINT FK_permisosusuarios_usuario FOREIGN KEY(usuario) REFERENCES usuarios(id),
    CONSTRAINT FK_permisosusuarios_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id)
);

INSERT INTO permisos_usuarios(permiso, usuario, uuid, valor, f_actualizacion) VALUES('superadmin', 1, X'A8DA1C3FF8EB4F4C9AD57350B8984EE1', 1, NOW());

CREATE TABLE permisos_usuarios_tipo (
    uuid                            BINARY(16) NOT NULL UNIQUE,
    permiso                         VARCHAR(30) NOT NULL,
    tipo                            SMALLINT NOT NULL,
    valor                           SMALLINT NOT NULL DEFAULT 1,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_permisosusuariostipo_permiso FOREIGN KEY(permiso) REFERENCES permisos(id),
    CONSTRAINT FK_permisosusuariostipo_tipo FOREIGN KEY(tipo) REFERENCES usuarios_tipos(id)
);

INSERT INTO permisos_usuarios_tipo(permiso, tipo, uuid, valor, f_actualizacion) VALUES('superadmin', 1, X'E8DABC3FFEEB444C9AD57350B8984EE1', 1, NOW());
INSERT INTO permisos_usuarios_tipo(permiso, tipo, uuid, valor, f_actualizacion) VALUES('gerente-general', 3, X'b1e75d0d33914a17a795925cf18776a6', 1, NOW());

-- INSERT INTO permisos_usuarios_tipo(permiso, tipo, uuid, valor, f_actualizacion) VALUES('superadmin', 1, X'E8DABC3FFEEB444C9AD57350B8984EE1', 1, NOW()),
--                                                                                     ('citas-atender', 4, X'9A5A8BB863B74902B9DF74150B522879', 1, NOW());

CREATE TABLE usuarios_sesiones (
    id                              BINARY(16) PRIMARY KEY,
    usuario                         INT NOT NULL,

    token_hash                      BINARY(32) NOT NULL,

    f_registro                      DATETIME NOT NULL,
    ultima_actividad                DATETIME NOT NULL,
    expira_en                       DATETIME NOT NULL,
    destruida_en                    DATETIME NULL,

    ip                              VARCHAR(255),
    user_agent                      VARCHAR(255),
    dispositivo                     VARCHAR(255),

    motivo_cierre                   VARCHAR(255),

    CONSTRAINT FK_usuariossesiones_usuario FOREIGN KEY (usuario) REFERENCES usuarios(id)
);

CREATE TABLE plantillas_horarios (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    empresa                         INT NOT NULL,
    nombre                          VARCHAR(80) NOT NULL,
    descripcion                     VARCHAR(255) DEFAULT NULL,
    usuario                         INT DEFAULT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_plantillashorarios_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_plantillashorarios_usuario FOREIGN KEY(usuario) REFERENCES usuarios(id)
);

CREATE TABLE plantillas_horarios_detalles (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    plantilla                       INT NOT NULL,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    dia_semana                      SMALLINT NOT NULL,
    hora_inicio                     SMALLINT NOT NULL,
    hora_fin                        SMALLINT NOT NULL,
    CONSTRAINT FK_plantillashorariosdetalles_plantilla FOREIGN KEY(plantilla) REFERENCES plantillas_horarios(id)
);

CREATE TABLE horarios_laborales (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    sucursal                        INT NOT NULL,
    personal                        INT NOT NULL,
    consultas                       SMALLINT NOT NULL DEFAULT 1,
    plantilla                       INT DEFAULT NULL,
    activo                          SMALLINT NOT NULL DEFAULT 1,
    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    CONSTRAINT FK_horarioslaborales_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id),
    CONSTRAINT FK_horarioslaborales_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_horarioslaborales_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

-- INSERT INTO horarios_laborales(id, personal, consultas, activo, registro, f_registro, f_actualizacion) VALUES(1, 1, 1, 1, 1, NOW(), NOW()),
--                                                                                                                 (2, 2, 1, 1, 1, NOW(), NOW());


CREATE TABLE horarios_laborales_detalles (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    horario                         INT NOT NULL,
    dia_semana                      SMALLINT NOT NULL,
    hora_inicio                     SMALLINT NOT NULL,
    hora_fin                        SMALLINT NOT NULL,
    CONSTRAINT FK_horarioslaboralesdetalles_horario FOREIGN KEY(horario) REFERENCES horarios_laborales(id)
);

CREATE INDEX IDX_horarioslaboralesdetalles_horario_dia ON horarios_laborales_detalles(horario, dia_semana);

-- INSERT INTO horarios_laborales_detalles(horario, dia_semana, hora_inicio, hora_fin) VALUES(1, 1, 480, 780),
--                                                                                                 (1, 2, 480, 900),
--                                                                                                 (1, 3, 480, 900),
--                                                                                                 (1, 4, 480, 900),
--                                                                                                 (1, 5, 480, 900),
--                                                                                                 (1, 6, 540, 900),

--                                                                                                 (2, 1, 480, 1080),
--                                                                                                 (2, 2, 480, 1080),
--                                                                                                 (2, 3, 480, 1080),
--                                                                                                 (2, 4, 480, 1080),
--                                                                                                 (2, 5, 480, 1080),
--                                                                                                 (2, 6, 540, 1080);

CREATE TABLE bloqueos_agenda_razones (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    razon                           VARCHAR(30)
);

INSERT INTO bloqueos_agenda_razones(codigo, razon) VALUES('vacaciones', 'Vacaciones'),
                                                                ('enfermedad', 'Enfermedad'),
                                                                ('formacion', 'Formación'),
                                                                ('reunion', 'Reunión'),
                                                                ('suspension', 'Suspensión'),
                                                                ('otro', 'Otro');

CREATE TABLE bloqueos_agenda (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    sucursal                        INT NOT NULL,
    personal                        INT NOT NULL,
    titulo                          VARCHAR(30) NOT NULL,
    razon                           SMALLINT NOT NULL,
    otra_razon                      VARCHAR(255) DEFAULT NULL,
    f_inicio                        DATE NOT NULL,
    f_fin                           DATE NOT NULL,
    h_inicio                        SMALLINT NOT NULL,
    h_fin                           SMALLINT NOT NULL,
    todo_el_dia                     SMALLINT NOT NULL DEFAULT 0,
    observaciones                   VARCHAR(512) DEFAULT NULL,
    registro                        INT NOT NULL,
    activo                          SMALLINT NOT NULL DEFAULT 1,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_bloqueosagenda_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id),
    CONSTRAINT FK_bloqueosagenda_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_bloqueosagenda_razon FOREIGN KEY(razon) REFERENCES bloqueos_agenda_razones(id),
    CONSTRAINT FK_bloqueosagenda_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE INDEX IDX_bloqueosagenda_personal_inicio_fin ON bloqueos_agenda(personal, f_inicio, f_fin);

CREATE TABLE clientes (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    empresa                         INT DEFAULT NULL,
    consecutivo                     INT DEFAULT NULL,
    clave                           VARCHAR(16) UNIQUE,
    es_empresa                      SMALLINT NOT NULL DEFAULT 0,
    razon_social                    VARCHAR(255) DEFAULT NULL,
    nombre                          VARCHAR(100) NOT NULL,
    paterno                         VARCHAR(80) DEFAULT NULL,
    materno                         VARCHAR(80) DEFAULT NULL,
    curp                            VARCHAR(20) DEFAULT NULL,
    genero                          VARCHAR(1) NOT NULL,
    f_nacimiento                    DATE DEFAULT NULL,
    calle                           VARCHAR(120) DEFAULT NULL,
    num_ext                         VARCHAR(12) DEFAULT NULL,
    num_int                         VARCHAR(12) DEFAULT NULL,
    colonia                         INT DEFAULT NULL,
    cp                              VARCHAR(5) DEFAULT NULL,
    telefono                        VARCHAR(40) DEFAULT NULL,
    movil                           VARCHAR(40) DEFAULT NULL,
    email                           VARCHAR(255) DEFAULT NULL,
    adeudo                          NUMERIC(18, 2) NOT NULL DEFAULT 0,
    ultimo_pago                     NUMERIC(18, 2) NOT NULL DEFAULT 0,
    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    f_ultimo_pago                   DATETIME DEFAULT NULL,
    CONSTRAINT FK_clientes_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_clientes_genero FOREIGN KEY(genero) REFERENCES generos(id),
    CONSTRAINT FK_clientes_colonia FOREIGN KEY(colonia) REFERENCES colonias(id),
    CONSTRAINT FK_clientes_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

INSERT INTO clientes(uuid, consecutivo, clave, nombre, genero, adeudo, ultimo_pago, registro, f_registro)
    VALUES(X'82822365C7604E9F8493DB9FD12E0A19', 1, 'Publico General', 'Público en General', 'N', 0, 0, 1, NOW());

CREATE TABLE facturacion_tipo_contribuyente (
    id                              CHAR(1) PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(30) NOT NULL
);

INSERT INTO facturacion_tipo_contribuyente(id, codigo, tipo) VALUES('F', 'persona-fisica', 'Persona Fisica'), ('M', 'persona-moral', 'Persona Moral');

CREATE TABLE facturacion_regimen (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(30) NOT NULL UNIQUE,
    tipo                            CHAR(1) DEFAULT NULL,
    codigo_sat                      VARCHAR(8) NOT NULL,
    regimen                         VARCHAR(255) NOT NULL,
    CONSTRAINT FK_facturacionregimen_tipo FOREIGN KEY(tipo) REFERENCES facturacion_tipo_contribuyente(id)
);

INSERT INTO facturacion_regimen(codigo, tipo, codigo_sat, regimen) VALUES('persona-moral', 'M', '601', 'REGIMEN GENERAL DE LEY PERSONAS MORALES'),
                                                                            ('persona-simp-moral', 'M', '602', 'RÉGIMEN SIMPLIFICADO DE LEY PERSONAS MORALES'),
                                                                            ('persona-moral-no-luc', 'M', '603', 'PERSONAS MORALES CON FINES NO LUCRATIVOS'),
                                                                            ('peq-contribuyente', 'F', '604', 'RÉGIMEN DE PEQUEÑOS CONTRIBUYENTES'),
                                                                            ('sueldos-salarios', 'F', '605', 'RÉGIMEN DE SUELDOS Y SALARIOS E INGRESOS ASIMILADOS A SALARIOS'),
                                                                            ('arrendamiento', 'F', '606', 'RÉGIMEN DE ARRENDAMIENTO'),
                                                                            ('enajenacion', 'F', '607', 'RÉGIMEN DE ENAJENACIÓN O ADQUISICIÓN DE BIENES'),
                                                                            ('demas-ingresos', 'F', '608', 'RÉGIMEN DE LOS DEMÁS INGRESOS'),
                                                                            ('consolidacion', 'M', '609', 'RÉGIMEN DE CONSOLIDACIÓN'),
                                                                            ('extranjeros-sin-est', NULL, '610', 'RÉGIMEN RESIDENTES EN EL EXTRANJERO SIN ESTABLECIMIENTO PERMANENTE EN MÉXICO'),
                                                                            ('dividendos', 'F', '611', 'RÉGIMEN DE INGRESOS POR DIVIDENDOS (SOCIOS Y ACCIONISTAS)'),
                                                                            ('actividad-empresarial', 'F', '612', 'RÉGIMEN DE LAS PERSONAS FÍSICAS CON ACTIVIDADES EMPRESARIALES Y PROFESIONALES'),
                                                                            ('int-act-empresarial', 'F', '613', 'RÉGIMEN INTERMEDIO DE LAS PERSONAS FÍSICAS CON ACTIVIDADES EMPRESARIALES'),
                                                                            ('intereses', 'F', '614', 'RÉGIMEN DE LOS INGRESOS POR INTERESES'),
                                                                            ('premios', 'F', '615', 'RÉGIMEN DE LOS INGRESOS POR OBTENCIÓN DE PREMIOS'),
                                                                            ('sin-obligaciones', NULL, '616', 'SIN OBLIGACIONES FISCALES'),
                                                                            ('pemex', 'M', '617', 'PEMEX'),
                                                                            ('simplificado-fisicas', 'F', '618', 'RÉGIMEN SIMPLIFICADO DE LEY PERSONAS FÍSICAS'),
                                                                            ('prestamos', 'F', '619', 'INGRESOS POR LA OBTENCIÓN DE PRÉSTAMOS'),
                                                                            ('produccion', 'M', '620', 'SOCIEDADES COOPERATIVAS DE PRODUCCIÓN QUE OPTAN POR DIFERIR SUS INGRESOS.'),
                                                                            ('rif', 'F', '621', 'RÉGIMEN DE INCORPORACIÓN FISCAL'),
                                                                            ('agricolas', 'F', '622', 'RÉGIMEN DE ACTIVIDADES AGRÍCOLAS, GANADERAS, SILVÍCOLAS Y PESQUERAS PM'),
                                                                            ('opcion-sociedades', 'M', '623', 'RÉGIMEN DE OPCIONAL PARA GRUPOS DE SOCIEDADES'),
                                                                            ('coordinados', 'M', '624', 'RÉGIMEN DE LOS COORDINADOS'),
                                                                            ('plataformas', 'F', '625', 'RÉGIMEN DE LAS ACTIVIDADES EMPRESARIALES CON INGRESOS A TRAVÉS DE PLATAFORMAS TECNOLÓGICAS.'),
                                                                            ('resico', 'F', '626', 'RÉGIMEN SIMPLIFICADO DE CONFIANZA');

CREATE TABLE clientes_facturacion (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    cliente                         INT NOT NULL,
    regimen                         SMALLINT NOT NULL,
    rfc                             VARCHAR(18) NOT NULL,
    razon_social                    VARCHAR(255) NOT NULL,
    calle                           VARCHAR(120) DEFAULT NULL,
    num_ext                         VARCHAR(12) DEFAULT NULL,
    num_int                         VARCHAR(12) DEFAULT NULL,
    colonia                         INT DEFAULT NULL,
    cp                              CHAR(5) NOT NULL,
    telefono                        VARCHAR(40) DEFAULT NULL,
    email                           VARCHAR(255) DEFAULT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    f_ultima_factura                DATETIME DEFAULT NULL,
    CONSTRAINT FK_clientesfacturacion_cliente FOREIGN KEY(cliente) REFERENCES clientes(id),
    CONSTRAINT FK_clientesfacturacion_regimen FOREIGN KEY(regimen) REFERENCES facturacion_regimen(id),
    CONSTRAINT FK_clientesfacturacion_colonia FOREIGN KEY(colonia) REFERENCES colonias(id)
);

CREATE TABLE parentescos (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) UNIQUE NOT NULL,
    descripcion                     VARCHAR(100) NOT NULL
);

INSERT INTO parentescos(codigo, descripcion) VALUES('self', 'El cliente es el mismo paciente'),
                                                        ('spouse', 'Esposo / Esposa'),
                                                        ('parent', 'Padre / Madre'),
                                                        ('child', 'Hijo / Hija'),
                                                        ('friend', 'Amigo / Amiga'),
                                                        ('employer', 'Empleador'),
                                                        ('tutor', 'Tutor legal');

CREATE TABLE pacientes (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    empresa                         INT NOT NULL,
    consecutivo                     INT DEFAULT NULL,
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
    colonia                         INT DEFAULT NULL,
    cp                              VARCHAR(5) DEFAULT NULL,
    medicamentos                    VARCHAR(2048) DEFAULT NULL,
    suplementos                     VARCHAR(2048) DEFAULT NULL,
    antecedentes_familiares         VARCHAR(2048) DEFAULT NULL,
    observaciones_generales         VARCHAR(2048) DEFAULT NULL,
    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    f_ultima_visita                 DATETIME DEFAULT NULL,
    CONSTRAINT FK_pacientes_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_pacientes_genero FOREIGN KEY(genero) REFERENCES generos(id),
    CONSTRAINT FK_pacientes_colonia FOREIGN KEY(colonia) REFERENCES colonias(id),
    CONSTRAINT FK_pacientes_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE clientes_pacientes (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    cliente                         INT NOT NULL,
    paciente                        INT NOT NULL,
    parentesco                      SMALLINT NOT NULL,
    principal                       TINYINT DEFAULT NULL,
    registro                        INT NOT NULL,
    activo                          SMALLINT NOT NULL DEFAULT 1,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    CONSTRAINT UK_clientespaciente_paciente UNIQUE(paciente, principal),
    CONSTRAINT FK_clientespacientes_cliente FOREIGN KEY(cliente) REFERENCES clientes(id),
    CONSTRAINT FK_clientespacientes_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_clientespacientes_parentesco FOREIGN KEY(parentesco) REFERENCES parentescos(id),
    CONSTRAINT FK_clientespacientes_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

/*
|--------------------------------------------------------------------------
| TIPOS DE CAMPO
|--------------------------------------------------------------------------
*/

CREATE TABLE hc_tipos_campo (
    id                      SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                  VARCHAR(30) NOT NULL UNIQUE,
    tipo                    VARCHAR(60) NOT NULL,
    tipo_valor              VARCHAR(20) NOT NULL,
    usa_opciones            TINYINT NOT NULL DEFAULT 0,
    seleccion_multiple      TINYINT NOT NULL DEFAULT 0,
    activo                  TINYINT NOT NULL DEFAULT 1
);


INSERT INTO hc_tipos_campo (
    codigo,
    tipo,
    tipo_valor,
    usa_opciones,
    seleccion_multiple
) VALUES
('texto',               'Texto corto',           'texto',    0, 0),
('texto_largo',         'Texto largo',           'texto',    0, 0),
('entero',              'Número entero',         'numero',   0, 0),
('decimal',             'Número decimal',        'numero',   0, 0),
('fecha',               'Fecha',                 'fecha',    0, 0),
('fecha_hora',          'Fecha y hora',          'datetime', 0, 0),
('si_no',               'Sí / No',               'boolean',  0, 0),
('seleccion',           'Selección única',       'opcion',   1, 0),
('seleccion_multiple',  'Selección múltiple',    'opcion',   1, 1);

/*
|--------------------------------------------------------------------------
| PLANTILLAS DE HISTORIA CLÍNICA
|--------------------------------------------------------------------------
*/

CREATE TABLE hc_plantillas (
    id                      INT AUTO_INCREMENT PRIMARY KEY,
    uuid                    BINARY(16) NOT NULL UNIQUE,

    empresa                 INT NOT NULL,

    nombre                  VARCHAR(120) NOT NULL,
    descripcion             VARCHAR(500) DEFAULT NULL,

    activo                  TINYINT NOT NULL DEFAULT 1,

    registro                INT NOT NULL,
    f_registro              DATETIME NOT NULL,
    f_actualizacion         DATETIME DEFAULT NULL,

    CONSTRAINT UK_hcplantilla_nombre
        UNIQUE(empresa, nombre),

    CONSTRAINT FK_hcplantilla_empresa
        FOREIGN KEY(empresa) REFERENCES empresas(id),

    CONSTRAINT FK_hcplantilla_registro
        FOREIGN KEY(registro) REFERENCES usuarios(id)
);

/*
|--------------------------------------------------------------------------
| VERSIONES DE PLANTILLAS
|--------------------------------------------------------------------------
*/

CREATE TABLE hc_plantillas_versiones (
    id                      INT AUTO_INCREMENT PRIMARY KEY,
    uuid                    BINARY(16) NOT NULL UNIQUE,

    plantilla               INT NOT NULL,
    numero_version          SMALLINT NOT NULL,

    descripcion             VARCHAR(500) DEFAULT NULL,

    publicada               TINYINT NOT NULL DEFAULT 0,
    vigente                 TINYINT NOT NULL DEFAULT 0,

    registro                INT NOT NULL,
    publico                 INT DEFAULT NULL,

    f_registro              DATETIME NOT NULL,
    f_publicacion           DATETIME DEFAULT NULL,
    f_actualizacion         DATETIME DEFAULT NULL,

    CONSTRAINT UK_hcversion
        UNIQUE(plantilla, numero_version),

    CONSTRAINT FK_hcversion_plantilla
        FOREIGN KEY(plantilla) REFERENCES hc_plantillas(id),

    CONSTRAINT FK_hcversion_registro
        FOREIGN KEY(registro) REFERENCES usuarios(id),

    CONSTRAINT FK_hcversion_publico
        FOREIGN KEY(publico) REFERENCES usuarios(id)
);

/*
|--------------------------------------------------------------------------
| SECCIONES
|--------------------------------------------------------------------------
*/

CREATE TABLE hc_secciones (
    id                      INT AUTO_INCREMENT PRIMARY KEY,
    uuid                    BINARY(16) NOT NULL UNIQUE,

    version                 INT NOT NULL,

    codigo                  VARCHAR(60) NOT NULL,
    nombre                  VARCHAR(120) NOT NULL,
    descripcion             VARCHAR(500) DEFAULT NULL,

    /*
     * Si es repetible, permite agregar múltiples grupos de respuestas.
     *
     * Ejemplo:
     *
     * Antecedentes familiares
     *   Padre    | Diabetes
     *   Madre    | Hipertensión
     *   Abuelo   | Cáncer
     */
    repetible               TINYINT NOT NULL DEFAULT 0,
    min_instancias          SMALLINT NOT NULL DEFAULT 0,
    max_instancias          SMALLINT DEFAULT NULL,

    orden                   SMALLINT NOT NULL DEFAULT 0,
    activo                  TINYINT NOT NULL DEFAULT 1,

    f_registro              DATETIME NOT NULL,

    CONSTRAINT UK_hcseccion_codigo
        UNIQUE(version, codigo),

    CONSTRAINT FK_hcseccion_version
        FOREIGN KEY(version) REFERENCES hc_plantillas_versiones(id)
);

/*
|--------------------------------------------------------------------------
| CAMPOS
|--------------------------------------------------------------------------
*/

CREATE TABLE hc_campos (
    id                      INT AUTO_INCREMENT PRIMARY KEY,
    uuid                    BINARY(16) NOT NULL UNIQUE,

    seccion                 INT NOT NULL,
    tipo                    SMALLINT NOT NULL,

    codigo                  VARCHAR(60) NOT NULL,
    etiqueta                VARCHAR(150) NOT NULL,

    descripcion             VARCHAR(500) DEFAULT NULL,
    placeholder             VARCHAR(255) DEFAULT NULL,
    ayuda                   VARCHAR(500) DEFAULT NULL,

    obligatorio             TINYINT NOT NULL DEFAULT 0,

    /*
     * Valor inicial, si se requiere.
     */
    valor_defecto           LONGTEXT DEFAULT NULL,

    /*
     * Configuración adicional del campo en JSON.
     * Lo dejamos LONGTEXT para no depender del tipo JSON del motor.
     *
     * Ejemplo:
     *
     * {
     *     "min": 0,
     *     "max": 250,
     *     "decimales": 2
     * }
     */
    configuracion           LONGTEXT DEFAULT NULL,

    orden                   SMALLINT NOT NULL DEFAULT 0,
    activo                  TINYINT NOT NULL DEFAULT 1,

    f_registro              DATETIME NOT NULL,

    CONSTRAINT UK_hccampo_codigo
        UNIQUE(seccion, codigo),

    CONSTRAINT FK_hccampo_seccion
        FOREIGN KEY(seccion) REFERENCES hc_secciones(id),

    CONSTRAINT FK_hccampo_tipo
        FOREIGN KEY(tipo) REFERENCES hc_tipos_campo(id)
);

/*
|--------------------------------------------------------------------------
| OPCIONES
|--------------------------------------------------------------------------
*/

CREATE TABLE hc_campo_opciones (
    id                      INT AUTO_INCREMENT PRIMARY KEY,
    uuid                    BINARY(16) NOT NULL UNIQUE,

    campo                   INT NOT NULL,

    valor                   VARCHAR(100) NOT NULL,
    etiqueta                VARCHAR(150) NOT NULL,

    orden                   SMALLINT NOT NULL DEFAULT 0,
    activo                  TINYINT NOT NULL DEFAULT 1,

    f_registro              DATETIME NOT NULL,

    CONSTRAINT UK_hcopcion
        UNIQUE(campo, valor),

    CONSTRAINT FK_hcopcion_campo
        FOREIGN KEY(campo) REFERENCES hc_campos(id)
);

/*
|--------------------------------------------------------------------------
| HISTORIAS CLÍNICAS DE PACIENTES
|--------------------------------------------------------------------------
*/

CREATE TABLE pacientes_historias_clinicas (
    id                      INT AUTO_INCREMENT PRIMARY KEY,
    uuid                    BINARY(16) NOT NULL UNIQUE,

    empresa                 INT NOT NULL,
    paciente                INT NOT NULL,

    version                 INT NOT NULL,

    /*
     * Permite dejarla incompleta y continuar después.
     */
    finalizada              TINYINT NOT NULL DEFAULT 0,
    anulada                 TINYINT NOT NULL DEFAULT 0,

    observaciones           TEXT DEFAULT NULL,

    registro                INT NOT NULL,
    finalizo                INT DEFAULT NULL,
    anulo                   INT DEFAULT NULL,

    f_registro              DATETIME NOT NULL,
    f_actualizacion         DATETIME DEFAULT NULL,
    f_finalizacion          DATETIME DEFAULT NULL,
    f_anulacion             DATETIME DEFAULT NULL,

    CONSTRAINT FK_pachist_empresa
        FOREIGN KEY(empresa) REFERENCES empresas(id),

    CONSTRAINT FK_pachist_paciente
        FOREIGN KEY(paciente) REFERENCES pacientes(id),

    CONSTRAINT FK_pachist_version
        FOREIGN KEY(version) REFERENCES hc_plantillas_versiones(id),

    CONSTRAINT FK_pachist_registro
        FOREIGN KEY(registro) REFERENCES usuarios(id),

    CONSTRAINT FK_pachist_finalizo
        FOREIGN KEY(finalizo) REFERENCES usuarios(id),

    CONSTRAINT FK_pachist_anulo
        FOREIGN KEY(anulo) REFERENCES usuarios(id)
);

/*
|--------------------------------------------------------------------------
| RESPUESTAS
|--------------------------------------------------------------------------
*/

CREATE TABLE pacientes_historias_respuestas (
    id                      BIGINT AUTO_INCREMENT PRIMARY KEY,
    uuid                    BINARY(16) NOT NULL UNIQUE,

    historia                INT NOT NULL,
    campo                   INT NOT NULL,

    /*
     * Para secciones repetibles.
     *
     * Sección normal:
     * instancia = 1
     *
     * Sección repetible:
     *
     * Padre:
     * instancia = 1
     *
     * Madre:
     * instancia = 2
     *
     * Abuelo:
     * instancia = 3
     */
    instancia               SMALLINT NOT NULL DEFAULT 1,

    /*
     * Texto, número, fecha y boolean se almacenan aquí.
     * El tipo real se obtiene desde hc_campos -> hc_tipos_campo.
     */
    valor                   LONGTEXT DEFAULT NULL,

    registro                INT NOT NULL,
    f_registro              DATETIME NOT NULL,
    f_actualizacion         DATETIME DEFAULT NULL,

    CONSTRAINT UK_pachistrespuesta
        UNIQUE(historia, campo, instancia),

    CONSTRAINT FK_pachistresp_historia
        FOREIGN KEY(historia) REFERENCES pacientes_historias_clinicas(id),

    CONSTRAINT FK_pachistresp_campo
        FOREIGN KEY(campo) REFERENCES hc_campos(id),

    CONSTRAINT FK_pachistresp_registro
        FOREIGN KEY(registro) REFERENCES usuarios(id)
);

/*
|--------------------------------------------------------------------------
| OPCIONES SELECCIONADAS
|--------------------------------------------------------------------------
*/

CREATE TABLE pacientes_historias_respuestas_opciones (
    respuesta               BIGINT NOT NULL,
    opcion                  INT NOT NULL,
    PRIMARY KEY(respuesta, opcion),
    CONSTRAINT FK_pachistrespop_respuesta FOREIGN KEY(respuesta) REFERENCES pacientes_historias_respuestas(id),
    CONSTRAINT FK_pachistrespop_opcion FOREIGN KEY(opcion) REFERENCES hc_campo_opciones(id)
);

CREATE TABLE articulos_categoria (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(30) NOT NULL UNIQUE,
    empresa                         INT DEFAULT NULL,
    categoria                       VARCHAR(80) NOT NULL,
    descripcion                     VARCHAR(255) DEFAULT NULL,
    CONSTRAINT FK_articuloscategoria_empresa FOREIGN KEY(empresa) REFERENCES empresas(id)
);

CREATE TABLE articulos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    empresa                         INT NOT NULL,
    clave                           VARCHAR(12) NOT NULL UNIQUE,
    codigo_barras                   VARCHAR(32) NOT NULL UNIQUE,
    nombre                          VARCHAR(100) NOT NULL,
    nombre_ticket                   VARCHAR(32) NOT NULL,
    categoria                       INT NOT NULL,
    descripcion                     VARCHAR(255) DEFAULT NULL,
    unidad_uso                      VARCHAR(8) NOT NULL DEFAULT 1,
    unidad_compra                   VARCHAR(8) NOT NULL DEFAULT 1,
    factor_conversion               NUMERIC(12, 4) NOT NULL DEFAULT 1,
    factor_conversion_venta         NUMERIC(12, 4) NOT NULL DEFAULT 1,
    costo_unidad                    NUMERIC(18, 2) NOT NULL DEFAULT 0,
    minimo_inventario               NUMERIC(12, 4) NOT NULL DEFAULT 0,
    habilitado_venta                SMALLINT NOT NULL DEFAULT 1,
    activo                          SMALLINT NOT NULL DEFAULT 1,
    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_articulos_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_articulos_categoria FOREIGN KEY(categoria) REFERENCES articulos_categoria(id),
    CONSTRAINT FK_articulos_registro FOREIGN KEY(registro) REFERENCES usuarios(id),
    CONSTRAINT FK_articulos_unidaduso FOREIGN KEY(unidad_uso) REFERENCES unidades(id),
    CONSTRAINT FK_articulos_unidadcompra FOREIGN KEY(unidad_compra) REFERENCES unidades(id)
);

CREATE TABLE servicios (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    empresa                         INT NOT NULL,
    servicio                        VARCHAR(120) NOT NULL,
    descripcion                     VARCHAR(500) DEFAULT NULL,
    duracion_min                    SMALLINT NOT NULL,
    costo_base                      NUMERIC(18,2) NOT NULL,
    requiere_material               BOOLEAN NOT NULL DEFAULT 0,
    es_procedimiento                BOOLEAN NOT NULL DEFAULT 0,
    registro                        INT NOT NULL,
    activo                          BOOLEAN NOT NULL DEFAULT 1,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    CONSTRAINT FK_servicios_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_servicios_usuario FOREIGN KEY(registro) REFERENCES usuarios(id)
);

-- INSERT INTO servicios (uuid, codigo, servicio, descripcion, duracion_min, costo_base, requiere_material, es_procedimiento, f_registro, f_actualizacion) VALUES

-- (0x62d6d842b600493496ce7ec7491f5dfc, 'consulta-general',        'Consulta General Podológica',        'Valoración inicial del estado del pie y diagnóstico básico',                  30, 500, 0, 0, NOW(), NOW()),
-- (0x4d9414124c5249398909cb991285db12, 'consulta-seguimiento',    'Consulta de Seguimiento',             'Revisión de evolución de tratamiento o control posterior',                   20, 350, 0, 0, NOW(), NOW()),

-- (0x48e8284b8cd5478aa8ccac03216f6a22, 'limpieza-podologica',     'Limpieza Podológica',                 'Corte de uñas, limado, limpieza de hiperqueratosis leve',                     45, 700, 1, 0, NOW(), NOW()),
-- (0xf3b204852e3440f5b83a1256f9365d77, 'corte-unas-especial',     'Corte Especializado de Uñas',         'Corte clínico para uñas engrosadas o deformadas',                              30, 400, 0, 0, NOW(), NOW()),

-- (0xe54747640a2c4faf93ee1772800a002d, 'una-encarnada',           'Tratamiento de Uña Encarnada',        'Retiro parcial de espícula ungueal',                                            60, 1200, 1, 1, NOW(), NOW()),
-- (0x375d0571ab8042a3b50516bea8f2ec5b, 'onicomicosis-control',    'Control de Onicomicosis',              'Limpieza y control de uñas con hongo',                                          45, 650, 1, 0, NOW(), NOW()),
-- (0xa3eb7ca2d3a24e138f6f87f3f2438b55, 'hiperqueratosis',         'Retiro de Hiperqueratosis',            'Eliminación de durezas profundas',                                              40, 600, 1, 0, NOW(), NOW()),
-- (0x53d445624e4e4caa8f2a899ecfde8856, 'helomas',                 'Eliminación de Helomas (Callos)',      'Retiro de callosidades nucleadas',                                              40, 650, 1, 1, NOW(), NOW()),

-- (0xa2e36819385d461bbbe8ad7b6fefd919, 'valoracion-pie-diabetico','Valoración de Pie Diabético',          'Evaluación de riesgo y cuidado especializado',                                  30, 550, 0, 0, NOW(), NOW()),
-- (0xb663ebc64b1a40fead4219c3b34fadcb, 'curacion-pie-diabetico',  'Curación Pie Diabético',               'Curación de lesiones en paciente diabético',                                    45, 750, 1, 1, NOW(), NOW()),

-- (0x85398f105542413a8a0167f7bd12aca2, 'curacion-simple',         'Curación Simple',                      'Limpieza y vendaje de lesión leve',                                             20, 300, 1, 0, NOW(), NOW()),
-- (0xe174df4fd2cf489882f10af20e4166e3, 'curacion-avanzada',       'Curación Avanzada',                    'Curación de herida con mayor profundidad o riesgo',                             40, 600, 1, 1, NOW(), NOW()),

-- (0x91bbb379b2a649d28e7e7471e408ba32, 'valoracion-ortesis',      'Valoración para Órtesis',              'Evaluación para colocación de correctores ungueales',                           30, 400, 0, 0, NOW(), NOW()),
-- (0xbb173cf1d8714f5f8252a4dbd6a40969, 'colocacion-ortesis',      'Colocación de Órtesis Ungueal',        'Instalación de corrector para uña encarnada',                                   45, 900, 1, 1, NOW(), NOW()),

-- (0x73c0104d7e6c451c86f6fe79ed2b0926, 'terapia-laser-hongos',    'Terapia Láser para Hongos',            'Sesión de tratamiento láser para onicomicosis',                                 30, 1000, 1, 1, NOW(), NOW()),
-- (0xd6c80b43c5ac4ce3a711cb22b34bdb7d, 'deslaminacion-ungueal',   'Deslaminación Ungueal',                'Reducción mecánica de grosor en uñas',                                          30, 500, 1, 0, NOW(), NOW()),

-- (0xa210e1b448be4d02b137dbdded3bb028, 'pedicure-clinico',        'Pedicure Clínico',                     'Servicio estético con enfoque en salud del pie',                                60, 800, 1, 0, NOW(), NOW());

CREATE TABLE personal_servicios (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) UNIQUE,
    personal                        INT NOT NULL,
    servicio                        INT NOT NULL,
    costo                           NUMERIC(18,2) NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_baja                          DATETIME DEFAULT NULL,
    CONSTRAINT FK_personalservicios_personal FOREIGN KEY (personal) REFERENCES personal(id),
    CONSTRAINT FK_personalservicios_servicio FOREIGN KEY (servicio) REFERENCES servicios(id)
);

-- INSERT INTO personal_servicios(personal, servicio, costo, f_registro) VALUES(1, 1, 500, NOW());
-- INSERT INTO personal_servicios(personal, servicio, costo, f_registro) VALUES(1, 4, 600, NOW());
-- INSERT INTO personal_servicios(personal, servicio, costo, f_registro) VALUES(1, 6, 840, NOW());
-- INSERT INTO personal_servicios(personal, servicio, costo, f_registro) VALUES(1, 7, 920, NOW());
-- INSERT INTO personal_servicios(personal, servicio, costo, f_registro) VALUES(1, 11, 300, NOW());
-- INSERT INTO personal_servicios(personal, servicio, costo, f_registro) VALUES(1, 15, 1400, NOW());


-- INSERT INTO personal_servicios(personal, servicio, costo, f_registro) VALUES(2, 1, 450, NOW());
-- INSERT INTO personal_servicios(personal, servicio, costo, f_registro) VALUES(2, 4, 660, NOW());
-- INSERT INTO personal_servicios(personal, servicio, costo, f_registro) VALUES(2, 6, 790, NOW());
-- INSERT INTO personal_servicios(personal, servicio, costo, f_registro) VALUES(2, 11, 400, NOW());
-- INSERT INTO personal_servicios(personal, servicio, costo, f_registro) VALUES(2, 15, 1100, NOW());

CREATE TABLE indicaciones_tipo (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(20) NOT NULL
);

INSERT INTO indicaciones_tipo(codigo, tipo) VALUES('previa', 'Previa'), ('posterior', 'Posterior');

CREATE TABLE indicaciones (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(30) NOT NULL UNIQUE,
    empresa                         INT DEFAULT NULL,
    tipo                            SMALLINT NOT NULL,
    descripcion                     VARCHAR(500) NOT NULL,
    activo                          BOOLEAN NOT NULL DEFAULT 1,
    CONSTRAINT FK_indicaciones_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_indicaciones_tipo FOREIGN KEY(tipo) REFERENCES indicaciones_tipo(id)
);

INSERT INTO indicaciones(codigo, tipo, descripcion) VALUES('pies-limpios', 1, 'Acudir con pies limpios y sin esmalte'),
                                                                ('no-aplicar-crema', 1, 'No aplicar cremas 24h antes'),
                                                                ('no-mojar-area-tratada', 1, 'No mojar el área tratada por 12 horas'),
                                                                ('aplicar-medicamento', 1, 'Aplicar medicamento tópico indicado');

CREATE TABLE servicios_indicaciones (
    servicio                        INT NOT NULL,
    indicacion                      INT NOT NULL,
    obligatoria                     BOOLEAN NOT NULL DEFAULT 1,
    CONSTRAINT PK_serviciosindicaciones PRIMARY KEY (servicio, indicacion),
    CONSTRAINT FK_serviciosindicaciones_servicio FOREIGN KEY (servicio) REFERENCES servicios(id),
    CONSTRAINT FK_serviciosindicaciones_indicacion FOREIGN KEY (indicacion) REFERENCES indicaciones(id)
);

CREATE TABLE servicios_articulos (
    servicio                        INT NOT NULL,
    articulo                        INT NOT NULL,
    cantidad_base                   NUMERIC(18,2) NOT NULL,
    CONSTRAINT PK_serviciosarticulos PRIMARY KEY (servicio, articulo),
    CONSTRAINT FK_serviciosarticulos_servicio FOREIGN KEY (servicio) REFERENCES servicios(id),
    CONSTRAINT FK_serviciosarticulos_articulo FOREIGN KEY (articulo) REFERENCES articulos(id)
);

CREATE TABLE citas_asuntos (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    asunto                          VARCHAR(40) NOT NULL
);

INSERT INTO citas_asuntos(codigo, asunto) VALUES('consulta', 'Consulta'),
                                                    ('seguimiento', 'Seguimiento'),
                                                    ('tratamiento', 'Tratamiento');

CREATE TABLE citas_estatus (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    estatus                         VARCHAR(40) NOT NULL,
    text_color                      VARCHAR(20) DEFAULT NULL,
    classname                       VARCHAR(20) DEFAULT NULL,
    background                      VARCHAR(10) DEFAULT NULL
);

INSERT INTO citas_estatus(codigo, estatus, text_color, classname, background) VALUES('agendada', 'Cita Agendada', '#A0DADB', 'secondary', '#145D5E'),
                                                                                    ('rechazada', 'Cita Rechazada', '#B22222', 'warning', '#CC7F7F'),
                                                                                    ('en_espera', 'En Espera', '#FA8B0C', 'primary', '#686CE3'),
                                                                                    ('en_proceso', 'En Proceso', '#5840FF', 'primary', '#5F63F2'),
                                                                                    ('no_presento', 'No se presento', '#B22222', 'warning', '#E9D502'),
                                                                                    ('finalizada', 'Cita Finalizada', '#2C6846', 'success', '#45A26E'),
                                                                                    ('cancelada', 'Cita Cancelada', '#8E1B1B', 'warning', '#C58686');

CREATE TABLE citas_formas (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    forma                           VARCHAR(40) NOT NULL
);

INSERT INTO citas_formas(codigo, forma) VALUES('presencial', 'Presencial'),
                                                    ('telefonica', 'Teléfono'),
                                                    ('correo', 'E-Mail'),
                                                    ('whatsapp', 'WhatsApp'),
                                                    ('agenda_digital', 'Agenda Digital'),
                                                    ('walk_in', 'Espontanea');

CREATE TABLE citas (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    sucursal                        INT NOT NULL,
    ejercicio                       SMALLINT DEFAULT NULL,
    consecutivo                     INT DEFAULT NULL,
    folio                           VARCHAR(30) DEFAULT NULL,
    paciente                        INT NOT NULL,
    asunto                          SMALLINT DEFAULT NULL,
    forma                           SMALLINT DEFAULT NULL,
    descripcion                     TEXT DEFAULT NULL,
    motivo_consulta                 TEXT DEFAULT NULL,
    fecha                           DATE NOT NULL,
    h_inicio                        SMALLINT DEFAULT NULL,
    duracion                        SMALLINT NOT NULL,
    h_fin                           SMALLINT DEFAULT NULL,
    estatus                         SMALLINT NOT NULL,
    registro                        INT DEFAULT NULL,
    costo                           NUMERIC(18, 2) NOT NULL DEFAULT 0,
    adeudo                          NUMERIC(18, 2) NOT NULL DEFAULT 0,
    pagado                          NUMERIC(18, 2) NOT NULL DEFAULT 0,
    bonificacion                    NUMERIC(18, 2) NOT NULL DEFAULT 0,
    checkin                         INT DEFAULT NULL,
    inicio_cita                     INT DEFAULT NULL,
    termino_cita                    INT DEFAULT NULL,
    cancelo_cita                    INT DEFAULT NULL,
    f_checkin                       DATETIME DEFAULT NULL,
    f_inicio_cita                   DATETIME DEFAULT NULL,
    f_termino_cita                  DATETIME DEFAULT NULL,
    f_cancelo_cita                  DATETIME DEFAULT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    CONSTRAINT UK_citas_consecutivo UNIQUE(sucursal, ejercicio, consecutivo),
    CONSTRAINT FK_citas_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id),
    CONSTRAINT FK_citas_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_citas_asunto FOREIGN KEY(asunto) REFERENCES citas_asuntos(id),
    CONSTRAINT FK_citas_forma FOREIGN KEY(forma) REFERENCES citas_formas(id),
    CONSTRAINT FK_citas_estatus FOREIGN KEY(estatus) REFERENCES citas_estatus(id),
    CONSTRAINT FK_citas_checkin FOREIGN KEY(checkin) REFERENCES usuarios(id),
    CONSTRAINT FK_citas_iniciocita FOREIGN KEY(inicio_cita) REFERENCES usuarios(id),
    CONSTRAINT FK_citas_terminocita FOREIGN KEY(termino_cita) REFERENCES usuarios(id),
    CONSTRAINT FK_citas_cancelocita FOREIGN KEY(cancelo_cita) REFERENCES usuarios(id)
);


CREATE TABLE citas_bloques_estatus (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    estatus                         VARCHAR(40) NOT NULL
);

INSERT INTO citas_bloques_estatus(codigo, estatus) VALUES('agendada', 'Cita Agendada'),
                                                                ('rechazada', 'Cita Rechazada'),
                                                                ('en_espera', 'En Espera'),
                                                                ('en_proceso', 'En Proceso'),
                                                                ('no_presento', 'No se presento'),
                                                                ('finalizada', 'Cita Finalizada'),
                                                                ('cancelada', 'Cita Cancelada');

CREATE TABLE citas_bloques (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    cita                            INT NOT NULL,
    personal                        INT NOT NULL,
    servicio                        INT NOT NULL,
    descripcion                     TEXT DEFAULT NULL,
    orden                           SMALLINT NOT NULL DEFAULT 1,
    h_inicio                        SMALLINT NOT NULL,
    h_fin                           SMALLINT NOT NULL,
    duracion                        SMALLINT NOT NULL,
    estatus                         SMALLINT NOT NULL,
    checkin                         INT DEFAULT NULL,
    inicio_cita                     INT DEFAULT NULL,
    termino_cita                    INT DEFAULT NULL,
    cancelo_cita                    INT DEFAULT NULL,
    f_checkin                       DATETIME DEFAULT NULL,
    f_inicio_cita                   DATETIME DEFAULT NULL,
    f_termino_cita                  DATETIME DEFAULT NULL,
    f_cancelo_cita                  DATETIME DEFAULT NULL,
    CONSTRAINT FK_citasbloques_cita FOREIGN KEY (cita) REFERENCES citas(id),
    CONSTRAINT FK_citasbloques_personal FOREIGN KEY (personal) REFERENCES personal(id),
    CONSTRAINT FK_citasbloques_servicio FOREIGN KEY (servicio) REFERENCES servicios(id),
    CONSTRAINT FK_citasbloques_estatus FOREIGN KEY(estatus) REFERENCES citas_bloques_estatus(id),
    CONSTRAINT FK_citasbloques_checkin FOREIGN KEY(checkin) REFERENCES usuarios(id),
    CONSTRAINT FK_citasbloques_iniciocita FOREIGN KEY(inicio_cita) REFERENCES usuarios(id),
    CONSTRAINT FK_citasbloques_terminocita FOREIGN KEY(termino_cita) REFERENCES usuarios(id),
    CONSTRAINT FK_citasbloques_cancelocita FOREIGN KEY(cancelo_cita) REFERENCES usuarios(id)
);

CREATE INDEX IDX_citasbloques_servicio ON citas_bloques(personal, servicio);
CREATE INDEX IDX_citasbloques_inicio ON citas_bloques(personal, h_inicio);
CREATE INDEX IDX_citasbloques_fin ON citas_bloques(personal, h_fin);

CREATE TABLE citas_servicios (
    cita                            INT NOT NULL,
    servicio                        INT NOT NULL,
    personal                        INT NOT NULL,
    costo                           NUMERIC(18,2) NOT NULL,
    bonificacion                    NUMERIC(18,2) NOT NULL DEFAULT 0,
    CONSTRAINT PK_citasservicios PRIMARY KEY (cita, servicio),
    CONSTRAINT FK_citasservicios_cita FOREIGN KEY (cita) REFERENCES citas(id),
    CONSTRAINT FK_citasservicios_servicio FOREIGN KEY (servicio) REFERENCES servicios(id),
    CONSTRAINT FK_citasservicios_personal FOREIGN KEY (personal) REFERENCES personal(id)
);

CREATE TABLE citas_servicios_articulos (
    cita                            INT NOT NULL,
    servicio                        INT NOT NULL,
    articulo                        INT NOT NULL,
    cantidad_utilizada              NUMERIC(18,2) NOT NULL,
    CONSTRAINT PK_citasserviciosarticulos PRIMARY KEY (cita, servicio, articulo),
    CONSTRAINT FK_citasserviciosarticulos_cita FOREIGN KEY (cita) REFERENCES citas(id),
    CONSTRAINT FK_citasserviciosarticulos_servicio FOREIGN KEY (servicio) REFERENCES servicios(id),
    CONSTRAINT FK_citasserviciosarticulos_articulo FOREIGN KEY (articulo) REFERENCES articulos(id)
);

CREATE TABLE consultas (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,

    cita                            INT NOT NULL,
    sucursal                        INT NOT NULL,
    paciente                        INT NOT NULL,
    personal                        INT NOT NULL,

    observacion_inicial             TEXT DEFAULT NULL,
    motivo_consulta                 TEXT DEFAULT NULL,
    padecimiento_actual             TEXT DEFAULT NULL,
    exploracion_resumen             TEXT DEFAULT NULL,
    diagnostico_resumen             TEXT DEFAULT NULL,
    plan_tratamiento                TEXT DEFAULT NULL,
    observaciones                   TEXT DEFAULT NULL,
    indicaciones                    TEXT DEFAULT NULL,

    estatus                         SMALLINT NOT NULL DEFAULT 1,

    f_inicio                        DATETIME DEFAULT NULL,
    f_fin                           DATETIME DEFAULT NULL,
    registro                        INT NOT NULL,
    finalizo                        INT DEFAULT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    CONSTRAINT FK_consultas_cita FOREIGN KEY (cita) REFERENCES citas(id),
    CONSTRAINT FK_consultas_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id),
    CONSTRAINT FK_consultas_paciente FOREIGN KEY (paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_consultas_personal FOREIGN KEY (personal) REFERENCES personal(id),
    CONSTRAINT FK_consultas_registro FOREIGN KEY (registro) REFERENCES usuarios(id),
    CONSTRAINT FK_consultas_finalizo FOREIGN KEY (finalizo) REFERENCES usuarios(id)
);

CREATE TABLE diagnosticos_tipos (
    id                              TINYINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(40) NOT NULL
);

INSERT INTO diagnosticos_tipos(codigo, tipo) VALUES('principal', 'Principal'),
                                                    ('secundario', 'Secundario'),
                                                    ('sospecha', 'Sospecha'),
                                                    ('seguimiento', 'Seguimiento');

CREATE TABLE diagnosticos_categorias (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(40) NOT NULL UNIQUE,
    empresa                         INT DEFAULT NULL,
    categoria                       VARCHAR(80) NOT NULL,
    especialidad                    SMALLINT DEFAULT NULL,
    activo                          TINYINT NOT NULL DEFAULT 1,

    CONSTRAINT FK_diagnosticoscategorias_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_diagnosticoscategorias_especialidad FOREIGN KEY(especialidad) REFERENCES especialidades(id)
);

INSERT INTO diagnosticos_categorias(
    codigo,
    categoria,
    especialidad
) VALUES
('unas', 'Alteraciones Ungueales', 1),
('piel', 'Alteraciones de la Piel', 1),
('pie_diabetico', 'Pie Diabético', 1),
('deformidades', 'Deformidades del Pie', 1),
('marcha', 'Alteraciones de la Marcha', 1),
('vascular', 'Alteraciones Vasculares', 1),
('neurologico', 'Alteraciones Neurológicas', 1),
('traumatico', 'Lesiones Traumáticas', 1),
('infeccioso', 'Procesos Infecciosos', 1),
('otros', 'Otros Diagnósticos', 1);

CREATE TABLE diagnosticos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,

    empresa                         INT DEFAULT NULL,

    codigo                          VARCHAR(60) NOT NULL UNIQUE,
    diagnostico                     VARCHAR(150) NOT NULL,
    categoria                       INT DEFAULT NULL,
    especialidad                    SMALLINT DEFAULT NULL,

    descripcion                     VARCHAR(500) DEFAULT NULL,
    activo                          TINYINT NOT NULL DEFAULT 1,

    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,

    CONSTRAINT FK_diagnosticos_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_diagnosticos_categoria FOREIGN KEY(categoria) REFERENCES diagnosticos_categorias(id),
    CONSTRAINT FK_diagnosticos_especialidad FOREIGN KEY(especialidad) REFERENCES especialidades(id)
);

INSERT INTO diagnosticos(
    uuid,
    codigo,
    diagnostico,
    categoria,
    especialidad,
    f_registro
) VALUES

(X'79A5323D0F7C48A39FBDA63E460593D4', 'onicocriptosis', 'Onicocriptosis (Uña Encarnada)', 1, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593D5', 'onicomicosis', 'Onicomicosis', 1, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593D6', 'onicodistrofia', 'Onicodistrofia', 1, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593D7', 'traumatismo_ungueal', 'Traumatismo Ungueal', 1, 1, NOW()),

(X'79A5323D0F7C48A39FBDA63E460593D8', 'hiperqueratosis', 'Hiperqueratosis', 2, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593D9', 'heloma', 'Heloma (Callo)', 2, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593DA', 'verruga_plantar', 'Verruga Plantar', 2, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593DB', 'dermatomicosis', 'Dermatomicosis', 2, 1, NOW()),

(X'79A5323D0F7C48A39FBDA63E460593DC', 'pie_diabetico_bajo', 'Pie Diabético Riesgo Bajo', 3, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593DD', 'pie_diabetico_moderado', 'Pie Diabético Riesgo Moderado', 3, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593DE', 'pie_diabetico_alto', 'Pie Diabético Riesgo Alto', 3, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593DF', 'ulcera_diabetica', 'Úlcera Diabética', 3, 1, NOW()),

(X'79A5323D0F7C48A39FBDA63E460593E0', 'hallux_valgus', 'Hallux Valgus (Juanete)', 4, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593E1', 'dedos_martillo', 'Dedos en Martillo', 4, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593E2', 'pie_plano', 'Pie Plano', 4, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593E3', 'pie_cavo', 'Pie Cavo', 4, 1, NOW()),

(X'79A5323D0F7C48A39FBDA63E460593E4', 'alteracion_marcha', 'Alteración de la Marcha', 5, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593E5', 'fascitis_plantar', 'Fascitis Plantar', 5, 1, NOW()),

(X'79A5323D0F7C48A39FBDA63E460593E6', 'insuficiencia_venosa', 'Insuficiencia Venosa', 6, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593E7', 'compromiso_vascular', 'Compromiso Vascular Periférico', 6, 1, NOW()),

(X'79A5323D0F7C48A39FBDA63E460593E8', 'neuropatia_periferica', 'Neuropatía Periférica', 7, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593E9', 'perdida_sensibilidad', 'Pérdida de Sensibilidad', 7, 1, NOW()),

(X'79A5323D0F7C48A39FBDA63E460593EA', 'contusion', 'Contusión', 8, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593EB', 'herida', 'Herida en Pie', 8, 1, NOW()),

(X'79A5323D0F7C48A39FBDA63E460593EC', 'celulitis', 'Celulitis', 9, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593ED', 'absceso', 'Absceso', 9, 1, NOW()),

(X'79A5323D0F7C48A39FBDA63E460593EE', 'dolor_podal', 'Dolor Podal', 10, 1, NOW()),
(X'79A5323D0F7C48A39FBDA63E460593EF', 'sin_hallazgos', 'Sin Hallazgos Patológicos', 10, 1, NOW());

CREATE TABLE consultas_diagnosticos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,

    consulta                        INT NOT NULL,
    tipo_diagnostico                TINYINT DEFAULT NULL,
    diagnostico_catalogo            INT DEFAULT NULL,
    diagnostico                     VARCHAR(255) NOT NULL,
    observaciones                   TEXT DEFAULT NULL,

    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    CONSTRAINT FK_consultasdiagnosticos_consulta FOREIGN KEY (consulta) REFERENCES consultas(id),
    CONSTRAINT FK_consultasdiagnosticos_catalogo FOREIGN KEY(diagnostico_catalogo) REFERENCES diagnosticos(id),
    CONSTRAINT FK_consultasdiagnosticos_tipo FOREIGN KEY (tipo_diagnostico) REFERENCES diagnosticos_tipos(id),
    CONSTRAINT FK_consultasdiagnosticos_registro FOREIGN KEY (registro) REFERENCES usuarios(id)
);

CREATE TABLE consultas_notas (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,

    consulta                        INT NOT NULL,
    tipo                            VARCHAR(30) NOT NULL DEFAULT 'nota',
    nota                            TEXT NOT NULL,

    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    
    CONSTRAINT FK_consultasnotas_consulta FOREIGN KEY(consulta) REFERENCES consultas(id),
    CONSTRAINT FK_consultasnotas_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE consultas_indicaciones (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,

    consulta                        INT NOT NULL,
    indicacion                      INT DEFAULT NULL,
    descripcion                     TEXT NOT NULL,

    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    CONSTRAINT FK_consultasindicaciones_consulta FOREIGN KEY(consulta) REFERENCES consultas(id),
    CONSTRAINT FK_consultasindicaciones_indicacion FOREIGN KEY(indicacion) REFERENCES indicaciones(id),
    CONSTRAINT FK_consultasindicaciones_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE consultas_archivos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,

    consulta                        INT NOT NULL,
    archivo                         VARCHAR(500) NOT NULL,
    nombre_original                 VARCHAR(255) DEFAULT NULL,
    mime_type                       VARCHAR(120) DEFAULT NULL,
    tipo                            VARCHAR(30) DEFAULT NULL,
    descripcion                     VARCHAR(500) DEFAULT NULL,

    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,

    CONSTRAINT FK_consultasarchivos_consulta FOREIGN KEY(consulta) REFERENCES consultas(id),
    CONSTRAINT FK_consultasarchivos_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE consultas_modulos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,

    codigo                          VARCHAR(50) NOT NULL UNIQUE,
    nombre                          VARCHAR(100) NOT NULL,
    descripcion                     VARCHAR(255) NULL,

    orden_default                   INT NOT NULL DEFAULT 0,
    activo                          TINYINT NOT NULL DEFAULT 1,

    f_registro                      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO consultas_modulos (
    uuid,
    codigo,
    nombre,
    descripcion,
    orden_default
) VALUES
(
    X'0193f7b44b217a309b4c2c2a6a001001',
    'observacion_inicial',
    'Observación inicial',
    'Motivo de consulta y observaciones iniciales del paciente',
    1
),
(
    X'0193f7b44b217a309b4c2c2a6a001002',
    'exploracion_podologica',
    'Exploración podológica',
    'Evaluación clínica general de los pies',
    2
),
(
    X'0193f7b44b217a309b4c2c2a6a001003',
    'procedimientos',
    'Procedimientos realizados',
    'Procedimientos y tratamientos aplicados durante la consulta',
    3
),
(
    X'0193f7b44b217a309b4c2c2a6a001004',
    'diagnosticos',
    'Diagnósticos',
    'Diagnósticos identificados durante la consulta',
    4
),
(
    X'0193f7b44b217a309b4c2c2a6a001005',
    'lesiones_ulceras',
    'Lesiones y úlceras',
    'Registro y seguimiento de lesiones, heridas y úlceras',
    5
),
(
    X'0193f7b44b217a309b4c2c2a6a001006',
    'plantillas',
    'Recomendación de plantillas',
    'Recomendación de plantillas ortopédicas o soportes',
    6
),
(
    X'0193f7b44b217a309b4c2c2a6a001007',
    'evidencia-fotografica',
    'Evidencia Fotográfica',
    'Fotografías antes y después del tratamiento',
    7
),
(
    X'0193f7b44b217a309b4c2c2a6a001008',
    'indicaciones',
    'Indicaciones',
    'Indicaciones y recomendaciones para el paciente',
    8
),
(
    X'0193f7b44b217a309b4c2c2a6a001009',
    'proxima_cita',
    'Próxima cita',
    'Programación y seguimiento de próxima consulta',
    9
),
(
    X'0193f7b44b217a309b4c2c2a6a001010',
    'archivos_adjuntos',
    'Archivos adjuntos',
    'Documentos y archivos relacionados con la consulta',
    10
),
(
    X'0193f7b44b217a309b4c2c2a6a001011',
    'evolucion',
    'Evolución',
    'Notas de evolución y seguimiento clínico',
    11
);

CREATE TABLE servicios_consulta_modulos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,

    servicio                        INT NOT NULL,
    modulo                          INT NOT NULL,

    obligatorio                     TINYINT NOT NULL DEFAULT 0,
    visible                         TINYINT NOT NULL DEFAULT 1,
    orden                           INT NOT NULL DEFAULT 0,

    activo                          TINYINT NOT NULL DEFAULT 1,

    f_registro                      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY UK_servicio_modulo (servicio, modulo),
    CONSTRAINT FK_scm_servicio FOREIGN KEY (servicio) REFERENCES servicios(id),
    CONSTRAINT FK_scm_modulo FOREIGN KEY (modulo) REFERENCES consultas_modulos(id)
);

CREATE TABLE consultas_procedimientos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,

    consulta                        INT NOT NULL,
    servicio                        INT NOT NULL,

    cantidad                        NUMERIC(18,2) NOT NULL DEFAULT 1,
    precio_unitario                 NUMERIC(18,2) NOT NULL DEFAULT 0,
    bonificacion                    NUMERIC(18,2) NOT NULL DEFAULT 0,
    total                           NUMERIC(18,2) NOT NULL DEFAULT 0,

    cobrable                        TINYINT NOT NULL DEFAULT 1,
    origen                          VARCHAR(20) NOT NULL DEFAULT 'manual',
    observaciones                   TEXT NULL,

    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,

    CONSTRAINT FK_consultasprocedimientos_consulta FOREIGN KEY(consulta) REFERENCES consultas(id),
    CONSTRAINT FK_consultasprocedimientos_servicio FOREIGN KEY(servicio) REFERENCES servicios(id),
    CONSTRAINT FK_consultasprocedimientos_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE consultas_evidencia (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    consulta                        INT NOT NULL,
    uuid                            BINARY(16) NOT NULL,
    tipo                            ENUM('antes', 'despues') NOT NULL,
    extension                       VARCHAR(15) NOT NULL,
    ancho                           INT NOT NULL,
    alto                            INT NOT NULL,
    peso                            INT NOT NULL,
    peso_miniatura                  INT NOT NULL,
    hash                            CHAR(64) NOT NULL,
    hash_miniatura                  CHAR(64) NOT NULL,
    raiz                            TEXT NOT NULL,
    archivo                         TEXT NOT NULL,
    raiz_miniatura                  TEXT NOT NULL,
    miniatura                       TEXT NOT NULL,
    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    
    INDEX idx_consulta (consulta),
    CONSTRAINT FK_consultasevidencia_consulta FOREIGN KEY(consulta) REFERENCES consultas(id),
    CONSTRAINT FK_consultasevidencia_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE plantillas_estatus (
    id                              TINYINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(30) NOT NULL,
    estatus                         VARCHAR(60) NOT NULL
);

INSERT INTO plantillas_estatus(codigo, estatus) VALUES('borrador', 'Borrador'),
                                                            ('activo', 'Activo'),
                                                            ('inactivo', 'Inactivo');

CREATE TABLE consentimientos_plantillas_variables (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(50) NOT NULL UNIQUE,
    descripcion                     VARCHAR(255) DEFAULT NULL,
    origen                          VARCHAR(50) NOT NULL,
    tipo_dato                       TINYINT NOT NULL,
    activo                          TINYINT NOT NULL DEFAULT 1,
    CONSTRAINT FK_consentimientosplantillasvariables_tipodato FOREIGN KEY(tipo_dato) REFERENCES tipos_datos(id)
);

CREATE TABLE consentimientos_plantillas (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    empresa                         INT DEFAULT NULL,
    codigo                          VARCHAR(50) NOT NULL,
    nombre                          VARCHAR(150) NOT NULL,
    version                         SMALLINT NOT NULL,
    plantilla                       TINYINT NOT NULL,
    logo                            TEXT DEFAULT NULL,
    logo_checksum                   CHAR(32) DEFAULT NULL,
    logo_width                      TINYINT DEFAULT 30,
    interlineado                    DECIMAL(6, 2) DEFAULT 1,
    font_size                       DECIMAL(6, 2) DEFAULT 1,
    delta_borrador                  LONGTEXT NOT NULL,
    documento_borrador              LONGTEXT NOT NULL,
    delta_json                      LONGTEXT NOT NULL,
    contenido_html                  LONGTEXT NOT NULL,
    estatus                         TINYINT NOT NULL,
    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT UK_consentimientosplantillas_codigo UNIQUE(empresa, codigo),
    CONSTRAINT FK_consentimientosplantillas_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_consentimientosplantillas_estatus FOREIGN KEY(estatus) REFERENCES plantillas_estatus(id),
    CONSTRAINT FK_consentimientosplantillas_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE servicios_consentimientos (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    servicio            INT NOT NULL,
    plantilla           INT NOT NULL,
    obligatorio         BOOLEAN NOT NULL DEFAULT 1,
    vigencia_dias       INT DEFAULT NULL,
    activo              BOOLEAN NOT NULL DEFAULT 1,
    CONSTRAINT FK_serviciosconsentimientos_servicio FOREIGN KEY(servicio) REFERENCES servicios(id),
    CONSTRAINT FK_serviciosconsentimientos_plantilla FOREIGN KEY(plantilla) REFERENCES consentimientos_plantillas(id)
);

CREATE TABLE consentimiento_items_tipo (
    id                  SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo              VARCHAR(20) NOT NULL UNIQUE,
    tipo                VARCHAR(40) NOT NULL
);

INSERT INTO consentimiento_items_tipo(codigo, tipo) VALUES ('riesgo', 'Riesgo'),
                                                            ('beneficio', 'Beneficio'),
                                                            ('alternativa', 'Alternativa'),
                                                            ('indicacion', 'Indicación'),
                                                            ('contraindicacion', 'Contraindicación');

CREATE TABLE servicios_consentimiento_items (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    servicio            INT NOT NULL,
    tipo                SMALLINT NOT NULL,
    descripcion         VARCHAR(700) NOT NULL,
    orden               SMALLINT NOT NULL DEFAULT 1,
    activo              BOOLEAN NOT NULL DEFAULT 1,
    CONSTRAINT FK_serviciosconsentimientoitems_servicio FOREIGN KEY(servicio) REFERENCES servicios(id),
    CONSTRAINT FK_serviciosconsentimientoitems_tipo FOREIGN KEY(tipo) REFERENCES consentimiento_items_tipo(id)
);

CREATE TABLE pacientes_consentimientos (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    uuid                BINARY(16) NOT NULL UNIQUE,
    paciente            INT NOT NULL,
    cita                INT DEFAULT NULL,
    servicio            INT DEFAULT NULL,
    plantilla           INT NOT NULL,

    version             VARCHAR(20) NOT NULL,
    archivo_pdf         VARCHAR(500) NOT NULL,
    firma_archivo       VARCHAR(500) DEFAULT NULL,
    hash_pdf            CHAR(64) DEFAULT NULL,

    firmado_por         VARCHAR(160) NOT NULL,
    parentesco          SMALLINT DEFAULT NULL,

    registro            INT NOT NULL,
    f_firma             DATETIME NOT NULL,
    f_registro          DATETIME NOT NULL,

    CONSTRAINT FK_pacientesconsentimientos_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_pacientesconsentimientos_cita FOREIGN KEY(cita) REFERENCES citas(id),
    CONSTRAINT FK_pacientesconsentimientos_servicio FOREIGN KEY(servicio) REFERENCES servicios(id),
    CONSTRAINT FK_pacientesconsentimientos_plantilla FOREIGN KEY(plantilla) REFERENCES consentimientos_plantillas(id),
    CONSTRAINT FK_pacientesconsentimientos_parentesco FOREIGN KEY(parentesco) REFERENCES parentescos(id),
    CONSTRAINT FK_pacientesconsentimientos_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE lateralidades (
    id                              TINYINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(10) NOT NULL UNIQUE,
    lateralidad                     VARCHAR(30) NOT NULL
);

INSERT INTO lateralidades (codigo, lateralidad) VALUES
('IZQ', 'Izquierdo'),
('DER', 'Derecho'),
('AMB', 'Ambos');

CREATE TABLE tipos_pies (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(60) NOT NULL
);

INSERT INTO tipos_pies (codigo, tipo) VALUES
('NORMAL', 'Pie normal'),
('PLANO', 'Pie plano'),
('CAVO', 'Pie cavo'),
('VALGO', 'Pie valgo'),
('VARO', 'Pie varo'),
('MIXTO', 'Pie mixto'),
('NO_DEFINIDO', 'No definido');

CREATE TABLE tipos_pulso (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(60) NOT NULL
);

INSERT INTO tipos_pulso (codigo, tipo) VALUES
('AUSENTE', 'Ausente'),
('DEBIL', 'Débil'),
('NORMAL', 'Normal'),
('AUMENTADO', 'Aumentado'),
('IRREGULAR', 'Irregular');

CREATE TABLE tipos_temperatura_pie (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(60) NOT NULL
);

INSERT INTO tipos_temperatura_pie (codigo, tipo) VALUES
('MUY_FRIO', 'Muy frío'),
('FRIO', 'Frío'),
('NORMAL', 'Normal'),
('CALIENTE', 'Caliente'),
('MUY_CALIENTE', 'Muy caliente');

CREATE TABLE tipos_sensibilidad (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(60) NOT NULL
);

INSERT INTO tipos_sensibilidad (codigo, tipo) VALUES
('CONSERVADA', 'Conservada'),
('DISMINUIDA', 'Disminuida'),
('AUSENTE', 'Ausente'),
('HIPERSENSIBLE', 'Hipersensible'),
('ALTERADA', 'Alterada');

CREATE TABLE formula_metatarsal (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    formula                            VARCHAR(60) NOT NULL
);

INSERT INTO formula_metatarsal (codigo, formula) VALUES
('INDEX_PLUS', 'Index Plus'),
('INDEX_MINUS', 'Index Minus'),
('INDEX_PLUS_MINUS', 'Index Plus Minus'),
('NO_DEFINIDA', 'No definida');

CREATE TABLE tipos_coloracion_pie (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(60) NOT NULL
);

INSERT INTO tipos_coloracion_pie (codigo, tipo) VALUES
('NORMAL', 'Normal'),
('PALIDO', 'Pálido'),
('ROJIZO', 'Rojizo'),
('CIANOTICO', 'Cianótico'),
('OSCURECIDO', 'Oscurecido');

CREATE TABLE exploracion_podologica (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    paciente                        INT NOT NULL,
    consulta                        INT NOT NULL,
    personal                        INT NOT NULL,
    tipo_pie                        SMALLINT DEFAULT NULL,
    formula_metatarsal              SMALLINT DEFAULT NULL,
    alteraciones_marcha             VARCHAR(255) DEFAULT NULL,
    pulso_pedio_derecho             SMALLINT DEFAULT NULL,
    pulso_pedio_izquierdo           SMALLINT DEFAULT NULL,
    sensibilidad_derecho            SMALLINT DEFAULT NULL,
    sensibilidad_izquierdo          SMALLINT DEFAULT NULL,
    temperatura_pies                SMALLINT DEFAULT NULL,
    coloracion_pies                 SMALLINT DEFAULT NULL,
    observaciones                   TEXT DEFAULT NULL,
    recomendaciones                 TEXT DEFAULT NULL,
    f_exploracion                   DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    registro                        INT NOT NULL,
    CONSTRAINT UK_exploracionpodologica UNIQUE(consulta),
    CONSTRAINT FK_exploracionpodologica_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_exploracionpodologica_consulta FOREIGN KEY(consulta) REFERENCES consultas(id),
    CONSTRAINT FK_exploracionpodologica_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_exploracionpodologica_tipopie FOREIGN KEY(tipo_pie) REFERENCES tipos_pies(id),
    CONSTRAINT FK_exploracionpodologica_formulametatarsal FOREIGN KEY(formula_metatarsal) REFERENCES formula_metatarsal(id),
    CONSTRAINT FK_exploracionpodologica_pulsopedioderecho FOREIGN KEY(pulso_pedio_derecho) REFERENCES tipos_pulso(id),
    CONSTRAINT FK_exploracionpodologica_puslopedioizquierdo FOREIGN KEY(pulso_pedio_izquierdo) REFERENCES tipos_pulso(id),
    CONSTRAINT FK_exploracionpodologica_sensibilidadderecho FOREIGN KEY(sensibilidad_derecho) REFERENCES tipos_sensibilidad(id),
    CONSTRAINT FK_exploracionpodologica_sensibilidadizquierdo FOREIGN KEY(sensibilidad_izquierdo) REFERENCES tipos_sensibilidad(id),
    CONSTRAINT FK_exploracionpodologica_temperaturapies FOREIGN KEY(temperatura_pies) REFERENCES tipos_temperatura_pie(id),
    CONSTRAINT FK_exploracionpodologica_coloracionpies FOREIGN KEY(coloracion_pies) REFERENCES tipos_coloracion_pie(id),
    CONSTRAINT FK_exploracionpodologica_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);


CREATE TABLE parametros_medicos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(30) NOT NULL UNIQUE,
    empresa                         INT DEFAULT NULL,
    parametro                       VARCHAR(80) NOT NULL,
    descripcion                     VARCHAR(1024) DEFAULT NULL,
    unidad                          VARCHAR(8) NOT NULL,
    minimo                          NUMERIC(12, 8) NOT NULL DEFAULT 0,
    recomendado                     NUMERIC(12, 8) NOT NULL DEFAULT 0,
    maximo                          NUMERIC(12, 8) NOT NULL DEFAULT 0,
    digitos                         SMALLINT NOT NULL DEFAULT 0,
    CONSTRAINT FK_parametrosmedicos_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_parametrosmedicos_unidad FOREIGN KEY(unidad) REFERENCES unidades(id)
);

CREATE TABLE seguimiento_parametros (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    consulta                        INT DEFAULT NULL,
    paciente                        INT NOT NULL,
    personal                        INT NOT NULL,
    parametro                       INT NOT NULL,
    valor                           NUMERIC(12, 8) NOT NULL DEFAULT 0,
    observaciones                   VARCHAR(1024) DEFAULT NULL,
    f_medicion                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_seguimientoparametros_consulta FOREIGN KEY(consulta) REFERENCES consultas(id),
    CONSTRAINT FK_seguimientoparametros_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_seguimientoparametros_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_seguimientoparametros_parametro FOREIGN KEY(parametro) REFERENCES parametros_medicos(id)
);

CREATE TABLE tipos_tratamiento (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(60) NOT NULL,
    descripcion                     VARCHAR(512) DEFAULT NULL
);

CREATE TABLE pacientes_tratamientos_podologicos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    paciente                        INT NOT NULL,
    consulta                        INT NOT NULL,
    personal                        INT NOT NULL,
    tipo_tratamiento                SMALLINT NOT NULL,
    descripcion                     VARCHAR(1024) DEFAULT NULL,
    diagnostico_asociado            VARCHAR(1024) DEFAULT NULL,
    material_utilizado              VARCHAR(1024) DEFAULT NULL,
    observaciones                   VARCHAR(1024) DEFAULT NULL,
    f_tratamiento                   DATETIME NOT NULL,
    f_proxima_revision              DATETIME DEFAULT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_pacientestratamientospodologicos_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_pacientestratamientospodologicos_consulta FOREIGN KEY(consulta) REFERENCES consultas(id),
    CONSTRAINT FK_pacientestratamientospodologicos_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_pacientestratamientospodologicos_tipotratamiento FOREIGN KEY(tipo_tratamiento) REFERENCES tipos_tratamiento(id)
);

CREATE TABLE tipos_lesiones (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(40) NOT NULL
);

INSERT INTO tipos_lesiones(codigo, tipo) VALUES
('ulcera', 'Úlcera'),
('herida', 'Herida'),
('ampolla', 'Ampolla'),
('fisura', 'Fisura'),
('heloma_ulcerado', 'Heloma Ulcerado'),
('verruga', 'Verruga Plantar'),
('erosion', 'Erosión'),
('escoriacion', 'Escoriación'),
('abrasion', 'Abrasión'),
('quemadura', 'Quemadura'),
('hematoma', 'Hematoma'),
('laceracion', 'Laceración'),
('incision', 'Incisión'),
('puncion', 'Punción'),
('otra', 'Otra');

CREATE TABLE tipos_localizacion_lesion (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    localizacion                    VARCHAR(40) NOT NULL
);

CREATE TABLE tipos_evolucion (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(40) NOT NULL
);

INSERT INTO tipos_evolucion(codigo, tipo) VALUES
('nueva', 'Nueva'),
('estable', 'Estable'),
('mejorando', 'Mejorando'),
('empeorando', 'Empeorando'),
('cicatrizando', 'Cicatrizando'),
('resuelta', 'Resuelta'),
('recidivante', 'Recidivante');

CREATE TABLE tipos_tejido (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(40) NOT NULL
);

INSERT INTO tipos_tejido(codigo, tipo) VALUES
('epitelial', 'Tejido Epitelial'),
('granulacion', 'Tejido de Granulación'),
('fibrinoso', 'Tejido Fibrinoso'),
('esfacelado', 'Tejido Esfacelado'),
('necrotico', 'Tejido Necrótico'),
('mixto', 'Mixto'),
('hiperqueratosico', 'Tejido Hiperqueratósico'),
('desconocido', 'No Determinado');

CREATE TABLE grado_wagner (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    grado                           VARCHAR(40) NOT NULL,
    descripcion                     VARCHAR(255) DEFAULT NULL
);

INSERT INTO grado_wagner(codigo, grado, descripcion) VALUES('grado-0', 'Grado 0', 'No hay lesiones, pie de riesgo. Callos gruesos y alguna deformidad ósea.'),
                                                                ('grado-1', 'Grado 1', 'Úlceras superficiales. Destrucción total del espesor de la piel.'),
                                                                ('grado-2', 'Grado 2', 'Úlceras profundas. Penetran la piel grasa pero no afecta la zona ósea.'),
                                                                ('grado-3', 'Grado 3', 'Úlcera más profunda con absceso (Osteomielitis). Compromete el tejido óseo y presencia de mal olor'),
                                                                ('grado-4', 'Grado 4', 'Gangrena limitada. Necrosis en una zona del pie, en los dedos, talón o planta.'),
                                                                ('grado-5', 'Grado 5', 'Gangrena extensa. La gangrena se extiende e invade todo el pie.');


CREATE TABLE seguimiento_pie_diabetico (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    consulta                        INT DEFAULT NULL,
    paciente                        INT NOT NULL,
    personal                        INT NOT NULL,
    grado_wagner                    SMALLINT NOT NULL,
    localizacion_lesion             SMALLINT NOT NULL,
    pie_afectado                    TINYINT NOT NULL,
    tamanyo_lesion_cm               NUMERIC(6, 4) NOT NULL DEFAULT 0,
    profundidad_lesion_cm           NUMERIC(6, 4) NOT NULL DEFAULT 0,
    presenta_infeccion              SMALLINT NOT NULL DEFAULT 0,
    presenta_necrosis               SMALLINT NOT NULL DEFAULT 0,
    tratamiento_aplicado            TEXT DEFAULT NULL,
    curas_semanales                 TEXT DEFAULT NULL,
    evolucion                       SMALLINT NOT NULL,
    observaciones                   TEXT DEFAULT NULL,
    registro                        INT NOT NULL,
    f_seguimiento                   DATETIME NOT NULL,
    f_proximo_control               DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_seguimientopiediabetico_consulta FOREIGN KEY(consulta) REFERENCES consultas(id),
    CONSTRAINT FK_seguimientopiediabetico_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_seguimientopiediabetico_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_seguimientopiediabetico_gradowagner FOREIGN KEY(grado_wagner) REFERENCES grado_wagner(id),
    CONSTRAINT FK_seguimientopiediabetico_localizacionlesion FOREIGN KEY(localizacion_lesion) REFERENCES tipos_localizacion_lesion(id),
    CONSTRAINT FK_seguimientopiediabetico_pieafectado FOREIGN KEY(pie_afectado) REFERENCES lateralidades(id),
    CONSTRAINT FK_seguimientopiediabetico_evolucion FOREIGN KEY(evolucion) REFERENCES tipos_evolucion(id),
    CONSTRAINT FK_seguimientopiediabetico_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE tipos_exudado (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    tipo                            VARCHAR(40) NOT NULL
);

INSERT INTO tipos_exudado(codigo, tipo) VALUES
('ninguno', 'Ninguno'),
('leve', 'Leve'),
('moderado', 'Moderado'),
('abundante', 'Abundante'),
('purulento', 'Purulento'),
('sanguinolento', 'Sanguinolento'),
('seroso', 'Seroso');

CREATE TABLE color_exudado (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    color                           VARCHAR(40) NOT NULL
);

INSERT INTO color_exudado(codigo, color) VALUES
('transparente', 'Transparente'),
('seroso', 'Seroso'),
('serosanguinolento', 'Serosanguinolento'),
('sanguinolento', 'Sanguinolento'),
('amarillo', 'Amarillo'),
('verdoso', 'Verdoso'),
('marron', 'Marrón'),
('purulento', 'Purulento');

CREATE TABLE tipos_dolor (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    dolor                           VARCHAR(40) NOT NULL
);

CREATE TABLE consultas_lesiones_ulceras (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,

    consulta                        INT NOT NULL,

    tipo_lesion                     SMALLINT NOT NULL,
    lateralidad                     TINYINT DEFAULT NULL,
    ubicacion                       VARCHAR(255) DEFAULT NULL,

    largo_cm                        DECIMAL(6,2) DEFAULT NULL,
    ancho_cm                        DECIMAL(6,2) DEFAULT NULL,
    profundidad_cm                  DECIMAL(6,2) DEFAULT NULL,

    grado_wagner                    SMALLINT DEFAULT NULL,
    tipo_tejido                     SMALLINT DEFAULT NULL,
    tipo_evolucion                  SMALLINT DEFAULT NULL,
    tipo_exudado                    SMALLINT DEFAULT NULL,
    color_exudado                   SMALLINT DEFAULT NULL,

    signos_infeccion                TINYINT NOT NULL DEFAULT 0,
    dolor                           TINYINT DEFAULT NULL,

    observaciones                   TEXT DEFAULT NULL,

    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,

    CONSTRAINT FK_consultaslesiones_consulta FOREIGN KEY(consulta) REFERENCES consultas(id),
    CONSTRAINT FK_consultaslesiones_tipolesion FOREIGN KEY(tipo_lesion) REFERENCES tipos_lesiones(id),
    CONSTRAINT FK_consultaslesiones_lateralidad FOREIGN KEY(lateralidad) REFERENCES lateralidades(id),
    CONSTRAINT FK_consultaslesiones_wagner FOREIGN KEY(grado_wagner) REFERENCES grado_wagner(id),
    CONSTRAINT FK_consultaslesiones_tejido FOREIGN KEY(tipo_tejido) REFERENCES tipos_tejido(id),
    CONSTRAINT FK_consultaslesiones_evolucion FOREIGN KEY(tipo_evolucion) REFERENCES tipos_evolucion(id),
    CONSTRAINT FK_consultaslesiones_exudado FOREIGN KEY(tipo_exudado) REFERENCES tipos_exudado(id),
    CONSTRAINT FK_consultaslesiones_colorexudado FOREIGN KEY(color_exudado) REFERENCES color_exudado(id),
    CONSTRAINT FK_consultaslesiones_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE registro_ulceras (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    consulta                        INT DEFAULT NULL,
    paciente                        INT NOT NULL,
    personal                        INT NOT NULL,
    ubicacion_anatomica             VARCHAR(255) NOT NULL,
    pie_afectado                    TINYINT NOT NULL,
    largo_cm                        NUMERIC(6, 4) NOT NULL DEFAULT 0,
    ancho_cm                        NUMERIC(6, 4) NOT NULL DEFAULT 0,
    profundidad_cm                  NUMERIC(6, 4) NOT NULL DEFAULT 0,
    tejido                          SMALLINT NOT NULL,
    exudado                         SMALLINT NOT NULL,
    color_exudado                   SMALLINT NOT NULL,
    signos_infeccion                SMALLINT NOT NULL DEFAULT 0,
    olor_desagradable               SMALLINT NOT NULL DEFAULT 0,
    dolor                           SMALLINT NOT NULL,
    tratamiento_aplicado            TEXT DEFAULT NULL,
    tipo_aposito                    TEXT DEFAULT NULL,
    observaciones                   TEXT DEFAULT NULL,
    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_curacion                      DATETIME NOT NULL,
    f_proxima_curacion              DATETIME DEFAULT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_registroulceras_consulta FOREIGN KEY(consulta) REFERENCES consultas(id),
    CONSTRAINT FK_registroulceras_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_registroulceras_personal FOREIGN KEY(personal) REFERENCES personal(id),
    CONSTRAINT FK_registroulceras_pieafectado FOREIGN KEY(pie_afectado) REFERENCES lateralidades(id),
    CONSTRAINT FK_registroulceras_tejido FOREIGN KEY(tejido) REFERENCES tipos_tejido(id),
    CONSTRAINT FK_registroulceras_exudado FOREIGN KEY(exudado) REFERENCES tipos_exudado(id),
    CONSTRAINT FK_registroulceras_colorexudado FOREIGN KEY(color_exudado) REFERENCES color_exudado(id),
    CONSTRAINT FK_registroulceras_dolor FOREIGN KEY(dolor) REFERENCES tipos_dolor(id),
    CONSTRAINT FK_registroulceras_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE material_plantillas (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    material                        VARCHAR(40) NOT NULL
);

CREATE TABLE tipos_efectividad (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    efectividad                     VARCHAR(40) NOT NULL
);

CREATE TABLE tipos_plantillas (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    plantillas                      VARCHAR(40) NOT NULL
);

CREATE TABLE plantillas_ortesis (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    consulta                            INT DEFAULT NULL,
    paciente                        INT NOT NULL,
    personal                        INT NOT NULL,
    plantilla                       SMALLINT NOT NULL,
    descripcion                     VARCHAR(1024) DEFAULT NULL,
    material                        SMALLINT NOT NULL,
    caracteristicas_tecnicas        VARCHAR(1024) DEFAULT NULL,
    f_preinscripcion                DATETIME DEFAULT NULL,
    f_fabricacion                   DATETIME DEFAULT NULL,
    f_entrega                       DATETIME DEFAULT NULL,
    efectividad                     SMALLINT NOT NULL,
    observaciones_seguimiento       TEXT DEFAULT NULL,
    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_plantillasortesis_consulta FOREIGN KEY(consulta) REFERENCES consultas(id),
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
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    empresa                         INT NOT NULL,
    rfc                             VARCHAR(15) DEFAULT NULL,
    razon_social                    VARCHAR(255) NOT NULL,
    representante                   VARCHAR(255) NOT NULL,
    tipo_contribuyente              CHAR(1) NOT NULL,
    telefono_1                      VARCHAR(15) DEFAULT NULL,
    telefono_2                      VARCHAR(15) DEFAULT NULL,
    movil                           VARCHAR(15) DEFAULT NULL,
    email                           VARCHAR(255) DEFAULT NULL,
    calle                           VARCHAR(120) DEFAULT NULL,
    num_ext                         VARCHAR(12) DEFAULT NULL,
    num_int                         VARCHAR(12) DEFAULT NULL,
    colonia                         INT DEFAULT NULL,
    adeudos                         NUMERIC(18, 2) NOT NULL DEFAULT 0,
    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_proveedores_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_proveedores_tipocontribuyente FOREIGN KEY(tipo_contribuyente) REFERENCES tipo_contribuyente(id),
    CONSTRAINT FK_proveedores_colonia FOREIGN KEY(colonia) REFERENCES colonias(id),
    CONSTRAINT FK_proveedores_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE requisiciones_estatus (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL,
    estatus                         VARCHAR(40) NOT NULL
);

INSERT INTO requisiciones_estatus(codigo, estatus) VALUES('capturada', 'Orden Capturada'),
                                                                ('enviada', 'Orden Enviada'),
                                                                ('autorizada', 'Orden Autorizada'),
                                                                ('rechazada', 'Orden Rechazada'),
                                                                ('finalizada', 'Orden Finalizada');

CREATE TABLE requisiciones (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    sucursal                        INT NOT NULL,
    solicito                        INT NOT NULL,
    autorizo                        INT NOT NULL,
    f_solicitud                     DATETIME NOT NULL,
    f_autorizacion                  DATETIME DEFAULT NULL,
    f_rechazada                     DATETIME DEFAULT NULL,
    estatus                         SMALLINT NOT NULL,
    notas                           VARCHAR(512) DEFAULT NULL,
    registro                        INT NOT NULL,
    f_registrada                    DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_requisiciones_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id),
    CONSTRAINT FK_requisiciones_solicito FOREIGN KEY(solicito) REFERENCES usuarios(id),
    CONSTRAINT FK_requisiciones_autorizo FOREIGN KEY(autorizo) REFERENCES usuarios(id),
    CONSTRAINT FK_requisiciones_estatus FOREIGN KEY(estatus) REFERENCES requisiciones_estatus(id),
    CONSTRAINT FK_requisiciones_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE requisiciones_articulos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    requisicion                     INT NOT NULL,
    articulo                        INT NOT NULL,
    cantidad_solicitada             NUMERIC(12, 4) NOT NULL DEFAULT 0,
    cantidad_autorizada             NUMERIC(12, 4) NOT NULL DEFAULT 0,
    notas                           VARCHAR(255) DEFAULT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_requisicionesarticulos_requisicion FOREIGN KEY(requisicion) REFERENCES requisiciones(id),
    CONSTRAINT FK_requisicionesarticulos_articulo FOREIGN KEY(articulo) REFERENCES articulos(id)
);

CREATE TABLE ordenes_compra_estatus (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL,
    estatus                         VARCHAR(40) NOT NULL
);

INSERT INTO ordenes_compra_estatus (codigo, estatus) VALUES('capturada', 'Capturada'),
                                                                ('autorizada', 'Lista para enviarse'),
                                                                ('enviada', 'Proveedor la recibio'),
                                                                ('confirmada', 'Proveedor acepto'),
                                                                ('parcial-recibida', 'Parcialmente recibida'),
                                                                ('recibida', 'Recibida'),
                                                                ('parcial-facturada', 'Facturación incompleta'),
                                                                ('facturada', 'Facturada'),
                                                                ('cancelada', 'Anulada antes de terminar'),
                                                                ('cerrada', 'Proceso terminado');

CREATE TABLE ordenes_compra (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    sucursal                        INT NOT NULL,
    proveedor                       INT NOT NULL,
    requisicion                     INT DEFAULT NULL,
    f_orden                         DATETIME NOT NULL,
    f_autorizacion                  DATETIME DEFAULT NULL,
    f_enviada                       DATETIME DEFAULT NULL,
    f_cancelada                     DATETIME DEFAULT NULL,
    f_cerrada                       DATETIME DEFAULT NULL,
    plazo_dias                      INT NOT NULL,
    f_esperada                      DATETIME NOT NULL,
    estatus                         SMALLINT NOT NULL,
    subtotal                        NUMERIC(18, 2) NOT NULL DEFAULT 0,
    impuestos                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    total                           NUMERIC(18, 2) NOT NULL DEFAULT 0,
    pagado                          NUMERIC(18, 2) NOT NULL DEFAULT 0,
    f_ultimo_pago                   DATETIME DEFAULT NULL,
    solicito                        INT NOT NULL,
    autorizo                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_ordenescompra_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id),
    CONSTRAINT FK_ordenescompra_proveedor FOREIGN KEY(proveedor) REFERENCES proveedores(id),
    CONSTRAINT FK_ordenescompra_requisicion FOREIGN KEY(requisicion) REFERENCES requisiciones(id),
    CONSTRAINT FK_ordenescompra_estatus FOREIGN KEY(estatus) REFERENCES ordenes_compra_estatus(id),
    CONSTRAINT FK_ordenescompra_solicito FOREIGN KEY(solicito) REFERENCES usuarios(id),
    CONSTRAINT FK_ordenescompra_autorizo FOREIGN KEY(autorizo) REFERENCES usuarios(id)
);

CREATE TABLE impuestos (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    descripcion                     VARCHAR(60) NOT NULL
);

INSERT INTO impuestos(codigo, descripcion) VALUES('IVA', 'Impuesto al Valor Agregado'),
                                                    ('IEPS', 'Impuesto Especial sobre Producción y Servicios'),
                                                    ('ISR', 'Impuesto Sobre la Renta');

CREATE TABLE perfil_impuestos (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    perfil                          VARCHAR(30) NOT NULL
);

INSERT INTO perfil_impuestos(codigo, perfil) VALUES('general', 'General'),
                                                    ('frontera', 'Frontera'),
                                                    ('exento', 'Exento');

CREATE TABLE perfil_impuestos_detalle (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    perfil                          SMALLINT NOT NULL,
    impuesto                        SMALLINT NOT NULL,
    tasa                            NUMERIC(8, 6) NOT NULL,
    vigente_desde                   DATETIME NOT NULL,
    vigente_hasta                   DATETIME DEFAULT NULL,
    CONSTRAINT FK_perfilimpuestosdetalle_perfil FOREIGN KEY(perfil) REFERENCES perfil_impuestos(id),
    CONSTRAINT FK_perfilimpuestosdetalle_impuesto FOREIGN KEY(impuesto) REFERENCES impuestos(id)
);

INSERT INTO perfil_impuestos_detalle(perfil, impuesto, tasa, vigente_desde) VALUES(1, 1, 16, '2010-01-01'),
                                                                                    (2, 1, 16, '2010-01-01'),
                                                                                    (3, 1, 0, '2010-01-01');

CREATE TABLE ordenes_compra_articulos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    orden_compra                    INT NOT NULL,
    articulo                        INT NOT NULL,
    cantidad_solicitada             NUMERIC(12, 4) NOT NULL DEFAULT 0,
    cantidad_autorizada             NUMERIC(12, 4) NOT NULL DEFAULT 0,
    cantidad_recibida               NUMERIC(12, 4) NOT NULL DEFAULT 0,
    costo_unidad                    NUMERIC(18, 2) NOT NULL DEFAULT 0,
    subtotal                        NUMERIC(18, 2) NOT NULL DEFAULT 0,
    impuestos                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    total                           NUMERIC(18, 2) NOT NULL DEFAULT 0,
    tasa                            NUMERIC(8, 6) NOT NULL DEFAULT 0,
    perfil_impuesto                 SMALLINT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_ordenescompraarticulos_ordencompra FOREIGN KEY(orden_compra) REFERENCES ordenes_compra(id),
    CONSTRAINT FK_ordenescompraarticulos_articulo FOREIGN KEY(articulo) REFERENCES articulos(id),
    CONSTRAINT FK_ordenescompraarticulos_perfilimpuesto FOREIGN KEY(perfil_impuesto) REFERENCES perfil_impuestos(id)
);

CREATE TABLE productos_categoria (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    empresa                         INT DEFAULT NULL,
    categoria                       VARCHAR(60) NOT NULL,
    sistema                         TINYINT NOT NULL DEFAULT 0,
    activo                          TINYINT NOT NULL DEFAULT 1,
    CONSTRAINT FK_productoscategoria_empresa FOREIGN KEY (empresa) REFERENCES empresas(id),
    CONSTRAINT UK_productos_categoria UNIQUE (empresa, categoria)
);

INSERT INTO productos_categoria (uuid, categoria, sistema, activo) VALUES (X'840e450fe81f44c6bae5664dc16a8c28', 'Medicamentos', 1, 1),
                                                                            (X'3a57588216054946a62d4778afc709de', 'Material', 1, 1),
                                                                            (X'9af34d98f48f4b76b91e0c500641cb4e', 'Instrumental', 1, 1),
                                                                            (X'a6f494d3d3cc4fbeac831b52db530c25', 'Equipo', 1, 1),
                                                                            (X'3797b154d4d4410fb91ecf25a4429e4b', 'Accesorios', 1, 1),
                                                                            (X'5be1bc2ef8244f9b907809dcc99b768d', 'Otros', 1, 1);

CREATE TABLE productos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    empresa                         INT NOT NULL,
    clave                           VARCHAR(12) DEFAULT NULL UNIQUE,
    codigo_barras                   VARCHAR(32) DEFAULT NULL UNIQUE,
    nombre                          VARCHAR(100) NOT NULL,
    nombre_ticket                   VARCHAR(32) NOT NULL,
    categoria                       INT NOT NULL,
    descripcion                     VARCHAR(255) DEFAULT NULL,
    unidad                          VARCHAR(8) NOT NULL DEFAULT 1,
    precio_base                     NUMERIC(18, 2) NOT NULL DEFAULT 0,
    porc_impuestos                  NUMERIC(6, 2) NOT NULL DEFAULT 0,
    impuestos                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    precio_total                    NUMERIC(18, 2) NOT NULL DEFAULT 0,
    habilitado_venta                SMALLINT NOT NULL DEFAULT 1,
    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    CONSTRAINT FK_productos_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_productos_categoria FOREIGN KEY(categoria) REFERENCES productos_categoria(id),
    CONSTRAINT FK_productos_unidad FOREIGN KEY(unidad) REFERENCES unidades(id),
    CONSTRAINT FK_productos_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE productos_articulos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    producto                        INT NOT NULL,
    articulo                        INT NOT NULL,
    cantidad                        NUMERIC(12, 4) NOT NULL DEFAULT 0,
    vigente_desde                   DATETIME NOT NULL,
    vigente_hasta                   DATETIME DEFAULT NULL,
    CONSTRAINT FK_productosarticulos_producto FOREIGN KEY(producto) REFERENCES productos(id),
    CONSTRAINT FK_productosarticulos_articulo FOREIGN KEY(articulo) REFERENCES articulos(id)
);

CREATE TABLE productos_precios (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    producto                        INT NOT NULL,
    perfil_impuesto                 SMALLINT NOT NULL,
    precio_base                     NUMERIC(18, 2) NOT NULL DEFAULT 0,
    porc_impuestos                  NUMERIC(6, 2) NOT NULL DEFAULT 0,
    impuestos                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    precio_total                    NUMERIC(18, 2) NOT NULL DEFAULT 0,
    registro                        INT NOT NULL,
    fecha_registro                  DATETIME NOT NULL,
    vigente_desde                   DATETIME NOT NULL,
    vigente_hasta                   DATETIME DEFAULT NULL,
    CONSTRAINT FK_productosprecios_producto FOREIGN KEY(producto) REFERENCES productos(id),
    CONSTRAINT FK_productosprecios_perfilimpuesto FOREIGN KEY(perfil_impuesto) REFERENCES perfil_impuestos(id),
    CONSTRAINT FK_productosprecios_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);

CREATE TABLE ventas_estatus (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    estatus                         VARCHAR(60) NOT NULL
);

INSERT INTO ventas_estatus(codigo, estatus) VALUES('pendiente', 'Pendiente'),
                                                    ('pagado', 'Pagado'),
                                                    ('cancelado', 'Cancelado');

CREATE TABLE ventas (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    sucursal                        INT NOT NULL,
    ejercicio                       SMALLINT DEFAULT NULL,
    consecutivo                     INT NOT NULL,
    folio                           VARCHAR(30) NOT NULL UNIQUE,
    consulta                        INT DEFAULT NULL,
    cita                            INT DEFAULT NULL,
    cliente                         INT NOT NULL,
    paciente                        INT DEFAULT NULL,
    subtotal                        NUMERIC(18, 2) NOT NULL DEFAULT 0,
    impuestos                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    total                           NUMERIC(18, 2) NOT NULL DEFAULT 0,
    descuento                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    pagado                          NUMERIC(18, 2) NOT NULL DEFAULT 0,
    adeudo                          NUMERIC(18, 2) NOT NULL DEFAULT 0,
    estatus                         SMALLINT NOT NULL,
    observaciones                   VARCHAR(1024) NOT NULL,
    registro                        INT NOT NULL,
    f_venta                         DATETIME NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    CONSTRAINT UK_ventas_consecutivo UNIQUE(sucursal, ejercicio, consecutivo),
    CONSTRAINT FK_ventas_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id),
    CONSTRAINT FK_ventas_consulta FOREIGN KEY(consulta) REFERENCES consultas(id),
    CONSTRAINT FK_ventas_cita FOREIGN KEY(cita) REFERENCES citas(id),
    CONSTRAINT FK_ventas_cliente FOREIGN KEY(cliente) REFERENCES clientes(id),
    CONSTRAINT FK_ventas_paciente FOREIGN KEY(paciente) REFERENCES pacientes(id),
    CONSTRAINT FK_ventas_registro FOREIGN KEY(registro) REFERENCES usuarios(id),
    CONSTRAINT FK_ventas_estatus FOREIGN KEY(estatus) REFERENCES ventas_estatus(id)
);

CREATE TABLE folios_consecutivos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    sucursal                        INT NOT NULL,
    tipo                            VARCHAR(30) NOT NULL,
    ejercicio                       SMALLINT NOT NULL,
    consecutivo                     INT NOT NULL DEFAULT 0,

    CONSTRAINT UK_foliosconsecutivos UNIQUE(sucursal, tipo, ejercicio),
    CONSTRAINT FK_foliosconsecutivos_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id)
);

CREATE TABLE tipos_descuentos (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    descuento                       VARCHAR(30) NOT NULL
);

CREATE TABLE ventas_detalles (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    venta                           INT NOT NULL,
    servicio                        INT DEFAULT NULL,
    producto                        INT DEFAULT NULL,
    descripcion                     VARCHAR(255) NOT NULL,
    cantidad                        NUMERIC(12,4) NOT NULL DEFAULT 1,
    precio_base                     NUMERIC(18,2) NOT NULL DEFAULT 0,
    subtotal                        NUMERIC(18,2) NOT NULL DEFAULT 0,
    impuestos                       NUMERIC(18,2) NOT NULL DEFAULT 0,
    total                           NUMERIC(18,2) NOT NULL DEFAULT 0,
    descuento                       NUMERIC(18,2) NOT NULL DEFAULT 0,
    pagado                          NUMERIC(18,2) NOT NULL DEFAULT 0,
    adeudo                          NUMERIC(18,2) NOT NULL DEFAULT 0,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,

    CONSTRAINT FK_ventasdetalles_venta FOREIGN KEY (venta) REFERENCES ventas(id),
    CONSTRAINT FK_ventasdetalles_servicio FOREIGN KEY (servicio) REFERENCES servicios(id),
    CONSTRAINT FK_ventasdetalles_producto FOREIGN KEY(producto) REFERENCES productos(id),
    CHECK (
        (servicio IS NOT NULL AND producto IS NULL)
        OR
        (servicio IS NULL AND producto IS NOT NULL)
    )
);

CREATE TABLE metodos_pago (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    metodo                          VARCHAR(60) NOT NULL,
    referencia                      SMALLINT DEFAULT 0
);

INSERT INTO metodos_pago(codigo, metodo, referencia) VALUES('efectivo', 'Efectivo', 0),
                                                                ('t-debito', 'Tarjeta Debito', 1),
                                                                ('t-credito', 'Tarjeta Credito', 1),
                                                                ('transferencia', 'Transferencia', 1),
                                                                ('cheque', 'Cheque', 1),
                                                                ('internet', 'Internet', 1),
                                                                ('credito', 'Credito', 1);

CREATE TABLE impresoras_conexion (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL UNIQUE,
    conexion                        VARCHAR(30) NOT NULL
);

INSERT INTO impresoras_conexion(codigo, conexion) VALUES('usb', 'Cable USB'),
                                                        ('network', 'Red'),
                                                        ('bluetooth', 'Bluetooth');

CREATE TABLE impresoras (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    sucursal                        INT NOT NULL,
    tipo_conexion                   SMALLINT NOT NULL,
    direccion                       VARCHAR(30) NOT NULL,
    puerto                          VARCHAR(10) DEFAULT NULL,
    mac_address                     VARCHAR(20) DEFAULT NULL,
    ubicacion                       VARCHAR(60) DEFAULT NULL,
    CONSTRAINT FK_impresoras_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id),
    CONSTRAINT FK_impresoras_tipoconexion FOREIGN KEY(tipo_conexion) REFERENCES impresoras_conexion(id)
);

CREATE TABLE cajas (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    sucursal                        INT DEFAULT NULL,
    codigo                          VARCHAR(30) NOT NULL UNIQUE,
    caja                            VARCHAR(30) NOT NULL,
    ubicacion                       VARCHAR(60) DEFAULT NULL,
    oculta                          SMALLINT NOT NULL DEFAULT 0,
    activa                          SMALLINT NOT NULL DEFAULT 1,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    CONSTRAINT FK_cajas_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id)
);

INSERT INTO cajas(uuid, codigo, caja, ubicacion, f_registro) VALUES(X'382B5216EF014D39A07D1E96D19CC5B6', 'caja', 'Caja', '', NOW());

CREATE TABLE cajas_impresoras (
    caja                            SMALLINT NOT NULL,
    impresora                       SMALLINT NOT NULL,
    CONSTRAINT FK_cajasimpresoras_caja FOREIGN KEY(caja) REFERENCES cajas(id),
    CONSTRAINT FK_cajasimpresoras_impresora FOREIGN KEY(impresora) REFERENCES impresoras(id)
);

CREATE TABLE cortes_estatus (
    id                              SMALLINT AUTO_INCREMENT PRIMARY KEY,
    codigo                          VARCHAR(20) NOT NULL,
    estatus                         VARCHAR(30) NOT NULL
);

INSERT INTO cortes_estatus(codigo, estatus) VALUES('open', 'Abierta'),
                                                    ('closed', 'Cerrada'),
                                                    ('waiting', 'En Espera'),
                                                    ('busy', 'Ocupada');

CREATE TABLE cortes (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    sucursal                        INT NOT NULL,
    ejercicio                       SMALLINT NOT NULL,
    consecutivo                     INT DEFAULT NULL,
    folio                           VARCHAR(30) DEFAULT NULL,
    caja                            SMALLINT NOT NULL,
    abierta_por                     INT NOT NULL,
    f_abierta                       DATETIME NOT NULL,
    monto_apertura                  NUMERIC(18, 2) NOT NULL,
    cerrada_por                     INT DEFAULT NULL,
    f_cierre                        DATETIME DEFAULT NULL,
    monto_cierre                    NUMERIC(18, 2) DEFAULT NULL,
    efectivo                        NUMERIC(18, 2) NOT NULL DEFAULT 0,
    otros_medios                    NUMERIC(18, 2) NOT NULL DEFAULT 0,
    total_venta                     NUMERIC(18, 2) NOT NULL DEFAULT 0,
    efectivo_esperado               NUMERIC(18, 2) NOT NULL DEFAULT 0,
    retiros                         NUMERIC(18, 2) NOT NULL DEFAULT 0,
    depositos                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    diferencia                      NUMERIC(18, 2) NOT NULL DEFAULT 0,
    cancelado                       NUMERIC(18, 2) NOT NULL DEFAULT 0,
    estatus                         SMALLINT NOT NULL,
    observaciones                   VARCHAR(1024) DEFAULT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    CONSTRAINT UK_cortes_consecutivo UNIQUE(sucursal, ejercicio, consecutivo),
    CONSTRAINT FK_cortes_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id),
    CONSTRAINT FK_cortes_caja FOREIGN KEY(caja) REFERENCES cajas(id),
    CONSTRAINT FK_cortes_abiertapor FOREIGN KEY(abierta_por) REFERENCES usuarios(id),
    CONSTRAINT FK_cortes_cerradapor FOREIGN KEY(cerrada_por) REFERENCES usuarios(id),
    CONSTRAINT FK_cortes_estatus FOREIGN KEY(estatus) REFERENCES cortes_estatus(id)
);

CREATE TABLE cortes_depositos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    corte                           INT NOT NULL,
    efectivo_antes                  NUMERIC(18, 2) NOT NULL DEFAULT 0,
    monto                           NUMERIC(18, 2) NOT NULL DEFAULT 0,
    efectivo_despues                NUMERIC(18, 2) NOT NULL DEFAULT 0,
    cajero                          INT NOT NULL,
    entrego                         INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_cortesdepositos_corte FOREIGN KEY(corte) REFERENCES cortes(id),
    CONSTRAINT FK_cortesdepositos_cajero FOREIGN KEY(cajero) REFERENCES usuarios(id),
    CONSTRAINT FK_cortesdepositos_entrego FOREIGN KEY(entrego) REFERENCES usuarios(id)
);

CREATE TABLE cortes_retiros (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    corte                           INT NOT NULL,
    efectivo_antes                  NUMERIC(18, 2) NOT NULL DEFAULT 0,
    monto                           NUMERIC(18, 2) NOT NULL DEFAULT 0,
    efectivo_despues                NUMERIC(18, 2) NOT NULL DEFAULT 0,
    cajero                          INT NOT NULL,
    retiro                          INT NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME NOT NULL,
    CONSTRAINT FK_cortesretiros_corte FOREIGN KEY(corte) REFERENCES cortes(id),
    CONSTRAINT FK_cortesretiros_cajero FOREIGN KEY(cajero) REFERENCES usuarios(id),
    CONSTRAINT FK_cortesretiros_retiro FOREIGN KEY(retiro) REFERENCES usuarios(id)
);

CREATE TABLE pagos (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    sucursal                        INT NOT NULL,
    ejercicio                       SMALLINT NOT NULL,
    folio                           VARCHAR(30) NOT NULL UNIQUE,
    consecutivo                     INT NOT NULL DEFAULT 0,
    cliente                         INT DEFAULT NULL,
    corte                           INT NOT NULL,
    metodo_pago                     SMALLINT NOT NULL,
    referencia                      VARCHAR(25) DEFAULT NULL,
    observaciones                   VARCHAR(500) DEFAULT NULL,
    adeudo_anterior                 NUMERIC(18, 2) NOT NULL DEFAULT 0,
    monto_pago                      NUMERIC(18, 2) NOT NULL DEFAULT 0,
    adeudo                          NUMERIC(18, 2) NOT NULL DEFAULT 0,
    estatus                         SMALLINT NOT NULL DEFAULT 1,
    registro                        INT NOT NULL,
    cancelado_por                   INT DEFAULT NULL,
    f_cancelacion                   DATETIME DEFAULT NULL,
    motivo_cancelacion              VARCHAR(500) DEFAULT NULL,
    f_pago                          DATETIME NOT NULL,
    f_registro                      DATETIME NOT NULL,
    f_actualizacion                 DATETIME DEFAULT NULL,
    CONSTRAINT UK_pagos_consecutivo UNIQUE(sucursal, ejercicio, consecutivo),
    CONSTRAINT FK_pagos_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id),
    CONSTRAINT FK_pagos_cliente FOREIGN KEY(cliente) REFERENCES clientes(id),
    CONSTRAINT FK_pagos_registro FOREIGN KEY(registro) REFERENCES usuarios(id),
    CONSTRAINT FK_pagos_corte FOREIGN KEY(corte) REFERENCES cortes(id),
    CONSTRAINT FK_pagos_metodopago FOREIGN KEY(metodo_pago) REFERENCES metodos_pago(id)
);

CREATE TABLE pagos_ventas (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    pago                            INT NOT NULL,
    venta                           INT NOT NULL,
    adeudo_anterior                 NUMERIC(18, 2) NOT NULL,
    monto_pago                      NUMERIC(18, 2) NOT NULL,
    adeudo_actual                   NUMERIC(18, 2) NOT NULL,
    f_registro                      DATETIME NOT NULL,
    CONSTRAINT UK_pagosventas UNIQUE(pago, venta),
    CONSTRAINT FK_pagosventas_pago FOREIGN KEY(pago) REFERENCES pagos(id),
    CONSTRAINT FK_pagosventas_venta FOREIGN KEY(venta) REFERENCES ventas(id)
);

CREATE TABLE pagos_ventas_detalles (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL UNIQUE,
    pago                            INT NOT NULL,
    venta                           INT NOT NULL,
    venta_detalle                   INT NOT NULL,
    adeudo_anterior                 NUMERIC(18, 2) NOT NULL,
    monto_pago                      NUMERIC(18, 2) NOT NULL,
    adeudo_actual                   NUMERIC(18, 2) NOT NULL,
    CONSTRAINT FK_pagosventasdetalles_pago FOREIGN KEY(pago) REFERENCES pagos(id),
    CONSTRAINT FK_pagosventasdetalles_venta FOREIGN KEY(venta) REFERENCES ventas(id),
    CONSTRAINT FK_pagosventasdetalles_ventadetalle FOREIGN KEY(venta_detalle) REFERENCES ventas_detalles(id)
);

CREATE TABLE integraciones_whatsapp (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL,

    empresa                         INT NOT NULL,
    proveedor                       VARCHAR(30) NOT NULL,
    nombre                          VARCHAR(80) NOT NULL DEFAULT 'WhatsApp principal',

    configuracion                   JSON NOT NULL,
    credenciales                    TEXT NOT NULL,

    activo                          TINYINT NOT NULL DEFAULT 1,

    ultima_prueba_at                DATETIME NULL,
    ultima_prueba_exitosa           TINYINT NULL,
    ultimo_error                    TEXT NULL,

    registrado_por                  INT NOT NULL,
    actualizado_por                 INT NULL,

    f_registro                      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    f_actualizacion                 DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY UK_integracioneswhatsapp_uuid (uuid),
    UNIQUE KEY UK_integracioneswhatsapp_empresa (empresa),

    KEY IDX_integracioneswhatsapp_proveedor (proveedor),

    CONSTRAINT FK_integracioneswhatsapp_empresa FOREIGN KEY (empresa) REFERENCES empresas(id),
    CONSTRAINT FK_integracioneswhatsapp_registrado_por FOREIGN KEY (registrado_por) REFERENCES usuarios(id),
    CONSTRAINT FK_integracioneswhatsapp_actualizado_por FOREIGN KEY (actualizado_por) REFERENCES usuarios(id)
);

CREATE TABLE mensajes_whatsapp (
    id                              INT AUTO_INCREMENT PRIMARY KEY,
    uuid                            BINARY(16) NOT NULL,

    empresa                         INT NOT NULL,
    integracion                     INT NOT NULL,

    proveedor                       VARCHAR(30) NOT NULL,
    tipo                            VARCHAR(30) NOT NULL,

    evento                          VARCHAR(60) NULL,
    referencia_tipo                 VARCHAR(40) NULL,
    referencia_id                   INT NULL,

    destinatario                    VARCHAR(25) NOT NULL,

    plantilla                       VARCHAR(120) NULL,
    idioma                          VARCHAR(10) NULL,
    contenido                       TEXT NULL,
    parametros                      JSON NULL,

    proveedor_mensaje_id            VARCHAR(255) NULL,

    estatus                         VARCHAR(30) NOT NULL DEFAULT 'pendiente',

    codigo_http                     SMALLINT NULL,
    codigo_error                    VARCHAR(100) NULL,
    error                           TEXT NULL,

    solicitud                       JSON NULL,
    respuesta                       JSON NULL,

    intentos                        SMALLINT NOT NULL DEFAULT 1,
    es_prueba                       TINYINT NOT NULL DEFAULT 0,

    enviado_at                      DATETIME NULL,
    entregado_at                    DATETIME NULL,
    leido_at                        DATETIME NULL,
    fallido_at                      DATETIME NULL,

    registrado_por                  INT NULL,

    f_registro                      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    f_actualizacion                 DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY UK_mensajes_whatsapp_uuid (uuid),

    UNIQUE KEY UK_mensajes_whatsapp_proveedor_id (
        proveedor,
        proveedor_mensaje_id
    ),

    KEY IDX_mensajes_whatsapp_empresa_estatus (
        empresa,
        estatus
    ),

    KEY IDX_mensajes_whatsapp_integracion (
        integracion
    ),

    KEY IDX_mensajes_whatsapp_destinatario (
        destinatario
    ),

    KEY IDX_mensajes_whatsapp_referencia (
        referencia_tipo,
        referencia_id
    ),

    CONSTRAINT FK_mensajes_whatsapp_empresa FOREIGN KEY (empresa) REFERENCES empresas(id),
    CONSTRAINT FK_mensajes_whatsapp_integracion FOREIGN KEY (integracion) REFERENCES integraciones_whatsapp(id),
    CONSTRAINT FK_mensajes_whatsapp_registrado_por FOREIGN KEY (registrado_por) REFERENCES usuarios(id)
);

CREATE TABLE archivos (
    uuid                            BINARY(16) NOT NULL,
    empresa                         INT NOT NULL,
    sucursal                        INT DEFAULT NULL,

    tipo                            VARCHAR(30) NOT NULL,
    referencia                      BINARY(16) NULL,

    nombre_original                 VARCHAR(255) NOT NULL,
    nombre_archivo                  VARCHAR(255) NOT NULL,
    ruta                            VARCHAR(500) NOT NULL,
    mime_type                       VARCHAR(100) NOT NULL,
    tamanio                         BIGINT UNSIGNED NOT NULL,

    registro                        INT NOT NULL,
    f_registro                      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (uuid),
    INDEX idx_archivos_empresa (empresa),
    INDEX idx_archivos_sucursal (sucursal),
    INDEX idx_archivos_referencia (referencia),
    CONSTRAINT FK_archivos_empresa FOREIGN KEY(empresa) REFERENCES empresas(id),
    CONSTRAINT FK_archivos_sucursal FOREIGN KEY(sucursal) REFERENCES sucursales(id),
    CONSTRAINT FK_archivos_registro FOREIGN KEY(registro) REFERENCES usuarios(id)
);









DELIMITER $$

CREATE TRIGGER validar_rango_bloque
BEFORE INSERT ON citas_bloques
FOR EACH ROW
BEGIN
    IF NEW.h_inicio < 0 OR NEW.h_inicio >= 1440 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Hora inicio fuera de rango';
    END IF;

    IF NEW.h_fin <= 0 OR NEW.h_fin > 1440 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Hora fin fuera de rango';
    END IF;

    IF NEW.h_fin <= NEW.h_inicio THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La hora fin debe ser mayor que inicio';
    END IF;

    IF NEW.duracion <> (NEW.h_fin - NEW.h_inicio) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duración inconsistente con horas';
    END IF;
END$$

CREATE TRIGGER validar_empalme_personal
BEFORE INSERT ON citas_bloques
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1
        FROM citas_bloques b
        INNER JOIN citas c ON c.id = b.cita
        WHERE b.personal = NEW.personal
          AND c.fecha = (SELECT fecha FROM citas WHERE id = NEW.cita)
          AND NEW.h_inicio < b.h_fin
          AND NEW.h_fin > b.h_inicio
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El personal ya tiene una cita en ese horario';
    END IF;
END$$

CREATE TRIGGER validar_rango_bloque_update
BEFORE UPDATE ON citas_bloques
FOR EACH ROW
BEGIN
    IF NEW.h_inicio < 0 OR NEW.h_inicio >= 1440 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Hora inicio fuera de rango';
    END IF;

    IF NEW.h_fin <= 0 OR NEW.h_fin > 1440 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Hora fin fuera de rango';
    END IF;

    IF NEW.h_fin <= NEW.h_inicio THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La hora fin debe ser mayor que inicio';
    END IF;

    IF NEW.duracion <> (NEW.h_fin - NEW.h_inicio) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duración inconsistente con horas';
    END IF;
END$$

CREATE TRIGGER validar_empalme_personal_update
BEFORE UPDATE ON citas_bloques
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1
        FROM citas_bloques b
        INNER JOIN citas c ON c.id = b.cita
        WHERE b.personal = NEW.personal
          AND c.fecha = (SELECT fecha FROM citas WHERE id = NEW.cita)
          AND NEW.h_inicio < b.h_fin
          AND NEW.h_fin > b.h_inicio
          AND b.id <> NEW.id
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El personal ya tiene una cita en ese horario';
    END IF;
END$$

DELIMITER ;


/* SELECT count(*) FROM sqlite_master WHERE type = 'table'; */

/*

INSERT INTO pacientes(id, uuid, clave, nombre, paterno, materno, calle, num_ext, colonia, cp, genero, email, f_nacimiento, telefono, movil, registro, f_registro, f_actualizacion) VALUES(1, 1, 'PE-000001', 'Paciente', 'Numero', '1', 'Domicilio Conocido', '123', 1275, '66004', 'H', 'paciente_1@adariel.com', '2001-01-12', '555 555 5555', '565 456 1245', 1, NOW(), NOW()),
                                                                                                                                                                                            (2, 2, 'PE-000002', 'Paciente', 'Numero', '2', 'Domicilio Conocido', '456', 1180, '66036', 'H', 'paciente_2@adariel.com', '1994-03-28', '123 456 7890', '265 456 1245', 1, NOW(), NOW());
*/