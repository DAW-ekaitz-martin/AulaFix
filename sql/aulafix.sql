CREATE DATABASE biblioteca_incidencias;
USE biblioteca_incidencias;
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150),
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('administrador', 'bibliotecario', 'usuario') NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);
CREATE TABLE estados (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);
INSERT INTO estados (nombre) VALUES
('Abierta'),
('En proceso'),
('Resuelta'),
('Cerrada');
CREATE TABLE incidencias (
    id_incidencia INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_cierre DATETIME NULL,

    id_usuario_creador INT NOT NULL,
    id_responsable INT NULL,
    id_categoria INT NOT NULL,
    id_estado INT NOT NULL DEFAULT 'Abierta',

    prioridad ENUM('Baja', 'Media', 'Alta', 'Urgente') DEFAULT 'Media',

    FOREIGN KEY (id_usuario_creador)
        REFERENCES usuarios(id_usuario),

    FOREIGN KEY (id_responsable)
        REFERENCES usuarios(id_usuario),

    FOREIGN KEY (id_categoria)
        REFERENCES categorias(id_categoria),

    FOREIGN KEY (id_estado)
        REFERENCES estados(id_estado)
);
CREATE TABLE comentarios (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    contenido TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    id_incidencia INT NOT NULL,
    id_usuario INT NOT NULL,

    FOREIGN KEY (id_incidencia)
        REFERENCES incidencias(id_incidencia)
        ON DELETE CASCADE,


    FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
);
CREATE TABLE historial_incidencias (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_incidencia INT NOT NULL,
    accion VARCHAR(200) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT NOT NULL,

    FOREIGN KEY (id_incidencia)
        REFERENCES incidencias(id_incidencia)
        ON DELETE CASCADE,

    FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
);

INSERT INTO usuarios (nombre, apellidos, email, password, rol) VALUES
('Ana','Martínez López','ana@biblioteca.com','1234','administrador'),
('Carlos','García Pérez','carlos@biblioteca.com','1234','bibliotecario'),
('Lucía','Fernández Ruiz','lucia@biblioteca.com','1234','bibliotecario'),
('David','Sánchez Gómez','david@correo.com','1234','usuario'),
('María','Navarro Díaz','maria@correo.com','1234','usuario'),
('Javier','Romero Ortiz','javier@correo.com','1234','usuario'),
('Paula','Torres Gil','paula@correo.com','1234','usuario'),
('Sergio','Vega León','sergio@correo.com','1234','usuario'),
('Elena','Castro Mora','elena@correo.com','1234','usuario'),
('Raúl','Jiménez Soto','raul@correo.com','1234','usuario'),
('Alba','Ramos Vidal','alba@correo.com','1234','usuario'),
('Miguel','Ortega Pérez','miguel@correo.com','1234','usuario'),
('Cristina','Delgado Ruiz','cristina@correo.com','1234','usuario'),
('Pablo','Herrera López','pablo@correo.com','1234','usuario'),
('Sara','Molina Gil','sara@correo.com','1234','usuario'),
('Adrián','Fuentes Ortega','adrian@correo.com','1234','usuario'),
('Laura','Núñez Martín','laura@correo.com','1234','usuario'),
('Rubén','Peña Castro','ruben@correo.com','1234','usuario'),
('Patricia','Santos Díaz','patricia@correo.com','1234','usuario'),
('Hugo','Silva Ramos','hugo@correo.com','1234','usuario'),
('Irene','Blanco Ruiz','irene@correo.com','1234','usuario'),
('Álvaro','Moreno Pérez','alvaro@correo.com','1234','usuario'),
('Noelia','Reyes Martín','noelia@correo.com','1234','usuario'),
('Víctor','Campos León','victor@correo.com','1234','usuario'),
('Marta','Iglesias Soto','marta@correo.com','1234','usuario');

INSERT INTO categorias (nombre, descripcion) VALUES
('Equipos Informáticos','Problemas con ordenadores'),
('Red WiFi','Conectividad WiFi'),
('Préstamos','Incidencias en préstamos'),
('Devoluciones','Problemas de devolución'),
('Catálogo','Errores de catálogo'),
('Impresoras','Problemas de impresión'),
('Sala de Estudio','Incidencias en salas'),
('Iluminación','Problemas de luz'),
('Climatización','Aire acondicionado y calefacción'),
('Mobiliario','Mesas y sillas'),
('Acceso','Control de acceso'),
('Software','Aplicaciones informáticas'),
('Página Web','Incidencias del portal'),
('Reserva de Salas','Reservas'),
('Bases de Datos','Errores de base de datos'),
('Escáneres','Problemas de escaneo'),
('Seguridad','Seguridad física'),
('Limpieza','Higiene instalaciones'),
('Biblioteca Digital','Acceso digital'),
('Libros Extraviados','Gestión de pérdidas'),
('Ruido','Quejas por ruido'),
('Electricidad','Problemas eléctricos'),
('Proyectores','Equipos audiovisuales'),
('Inventario','Control de materiales'),
('Otros','Incidencias generales');

INSERT INTO incidencias
(titulo, descripcion, fecha_cierre, id_usuario_creador, id_responsable, id_categoria, id_estado, prioridad)
VALUES
('Ordenador no enciende','PC de consulta apagado',NULL,4,2,1,1,'Alta'),
('WiFi lenta','Conexión muy lenta',NULL,5,3,2,2,'Media'),
('Error préstamo','No permite registrar préstamo',NULL,6,2,3,2,'Alta'),
('Libro sin devolver','Sistema muestra devolución pendiente','2026-01-10',7,2,4,3,'Media'),
('Libro no aparece','Error en catálogo',NULL,8,3,5,1,'Media'),
('Impresora atascada','Papel atascado','2026-02-03',9,2,6,3,'Baja'),
('Sala reservada incorrectamente','Conflicto de reservas',NULL,10,3,14,2,'Alta'),
('Luz fundida','Bombilla fundida','2026-03-02',11,2,8,4,'Baja'),
('Aire acondicionado averiado','No enfría',NULL,12,3,9,2,'Urgente'),
('Silla rota','Silla dañada',NULL,13,2,10,1,'Baja'),
('Tarjeta acceso no funciona','No abre la puerta',NULL,14,3,11,1,'Alta'),
('Software bloqueado','Aplicación congelada',NULL,15,2,12,2,'Media'),
('Web caída','Portal inaccesible',NULL,16,3,13,2,'Urgente'),
('Reserva duplicada','Dos usuarios misma sala','2026-04-15',17,2,14,3,'Media'),
('Error consulta BD','Consulta lenta',NULL,18,3,15,2,'Alta'),
('Escáner desconectado','No detectado',NULL,19,2,16,1,'Media'),
('Cámara averiada','No graba',NULL,20,3,17,2,'Alta'),
('Falta limpieza','Zona descuidada','2026-02-20',21,2,18,4,'Baja'),
('No accede biblioteca digital','Error autenticación',NULL,22,3,19,1,'Alta'),
('Libro perdido','No localizado',NULL,23,2,20,2,'Alta'),
('Exceso ruido','Usuarios hablando',NULL,24,3,21,1,'Media'),
('Enchufe roto','No suministra corriente','2026-03-12',25,2,22,3,'Media'),
('Proyector sin imagen','Pantalla negra',NULL,4,3,23,2,'Alta'),
('Material desaparecido','Falta inventario',NULL,5,2,24,2,'Media'),
('Consulta general','Incidencia diversa',NULL,6,3,25,1,'Baja');

INSERT INTO comentarios (contenido, id_incidencia, id_usuario) VALUES
('Incidencia registrada',1,2),
('Se está revisando',2,3),
('Pendiente de solución',3,2),
('Resuelto correctamente',4,2),
('Se necesitan más datos',5,3),
('Técnico avisado',6,2),
('En seguimiento',7,3),
('Incidencia cerrada',8,2),
('Revisando equipo',9,3),
('Solicitud recibida',10,2),
('Acceso verificado',11,3),
('Actualización realizada',12,2),
('Servidor reiniciado',13,3),
('Reserva corregida',14,2),
('Base de datos optimizada',15,3),
('Escáner reiniciado',16,2),
('Equipo de seguridad informado',17,3),
('Limpieza programada',18,2),
('Usuario informado',19,3),
('Búsqueda iniciada',20,2),
('Aviso al personal',21,3),
('Electricista asignado',22,2),
('Prueba realizada',23,3),
('Inventario revisado',24,2),
('Pendiente de evaluación',25,3);

INSERT INTO historial_incidencias
(id_incidencia, accion, id_usuario)
VALUES
(1,'Creación de incidencia',4),
(2,'Cambio a En proceso',3),
(3,'Asignada a técnico',2),
(4,'Marcada como resuelta',2),
(5,'Creación de incidencia',8),
(6,'Cierre de incidencia',2),
(7,'Actualización de estado',3),
(8,'Incidencia cerrada',2),
(9,'Asignación responsable',3),
(10,'Creación de incidencia',13),
(11,'Estado actualizado',3),
(12,'Software actualizado',2),
(13,'Reinicio de servicio',3),
(14,'Incidencia resuelta',2),
(15,'Optimización realizada',3),
(16,'Revisión técnica',2),
(17,'Notificación enviada',3),
(18,'Cierre definitivo',2),
(19,'Validación usuario',3),
(20,'Investigación iniciada',2),
(21,'Creación de incidencia',24),
(22,'Trabajo completado',2),
(23,'Equipo sustituido',3),
(24,'Inventario actualizado',2),
(25,'Incidencia abierta',6);
