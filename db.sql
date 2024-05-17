DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    biografia VARCHAR(100),
    contrasena VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    foto_perfil VARCHAR(255),
    color_cabecera VARCHAR(7)
);

CREATE TABLE tweets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    mensaje VARCHAR(255) NOT NULL,
    fecha_hora DATETIME NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

CREATE TABLE tokens (
    token VARCHAR(255) PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha_validez DATETIME NOT NULL,
    consumido BOOLEAN NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

INSERT INTO usuarios (usuario, biografia, contrasena, email, foto_perfil) VALUES
('malmorox', 'Estudiante de DAW', '$2y$10$0N6oTYCuDQvOhJbqIv0Q1uCLJFBoqTODJXIqaGb4KPv9bHpQEQB1m', 'malmoroxcabrera@educa.madrid.org', 'media/fotos_perfil/foto_perfil1.jpeg'),
('juanitojuan', 'Tu peor pesadilla', '$2y$10$/oWJIuVsBXU0Fcx5M.kLzOA/NHL5N5E1CZ6.b5c1FgVYQTLD9vEqm', 'juanitojuan@educa.madrid.org', 'media/fotos_perfil/foto_perfil2.jpeg');