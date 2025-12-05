-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS mantto CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE mantto;

-- Tabla de login
DROP TABLE IF EXISTS `login`;
CREATE TABLE `login` (
  `usuario` varchar(45) NOT NULL,
  `contraseña` varchar(10) NOT NULL,
  PRIMARY KEY (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar usuario por defecto
INSERT INTO `login` (`usuario`, `contraseña`) VALUES ('eli', 'eli');

-- Tabla de mantenimiento
DROP TABLE IF EXISTS `mantenimiento`;
CREATE TABLE `mantenimiento` (
  `id` int(10) NOT NULL,
  `areaa` varchar(45) NOT NULL,
  `actividad` varchar(45) NOT NULL,
  `frecuencia` varchar(45) NOT NULL,
  `folio` varchar(45) NOT NULL,
  `observaciones` varchar(45) NOT NULL,
  `material` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar datos de ejemplo en mantenimiento
INSERT INTO `mantenimiento` (`id`, `areaa`, `actividad`, `frecuencia`, `folio`, `observaciones`, `material`) VALUES 
(1, 'CANCHA', 'PODAR', 'CADA 3 DIAS', 'IVAN', 'NINGUNA', 'PODADORA'),
(2, 'LABORATORIO', 'LIMPIEZA', 'DIARIO', 'MARIA', 'EQUIPO DELICADO', 'DETERGENTE');

-- Tabla de personal
DROP TABLE IF EXISTS `personal`;
CREATE TABLE `personal` (
  `id` int(10) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `cargo` varchar(45) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar datos de ejemplo en personal
INSERT INTO `personal` (`id`, `nombre`, `cargo`, `telefono`, `email`) VALUES 
(1, 'IVAN', 'MANTENIMIENTO', '7353582466', 'ivan@cecyte.com'),
(2, 'ELIZABETH', 'SUPERVISOR', '7353582467', 'elizabeth@cecyte.com');