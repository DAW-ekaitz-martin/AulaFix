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
CREATE TABLE incidencias (
    id_incidencia INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_cierre DATETIME NULL,

    id_usuario_creador INT NOT NULL DEFAULT 2,
    id_responsable INT NULL,
    id_categoria INT NOT NULL,
    estado ENUM('Abierta', 'En proceso', 'Resuelta', 'Cerrada') DEFAULT "Abierta",

    prioridad ENUM('Baja', 'Media', 'Alta', 'Urgente'),

    FOREIGN KEY (id_usuario_creador)
        REFERENCES usuarios(id_usuario),

    FOREIGN KEY (id_responsable)
        REFERENCES usuarios(id_usuario),

    FOREIGN KEY (id_categoria)
        REFERENCES categorias(id_categoria)
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