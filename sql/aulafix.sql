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
    id_estado INT NOT NULL,

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