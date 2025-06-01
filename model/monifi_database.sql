-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-06-2025 a las 19:35:37
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `monifi_database`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_cuenta`
--

CREATE TABLE `tbl_cuenta` (
  `id` int(11) NOT NULL,
  `propietario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `salario` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_deuda`
--

CREATE TABLE `tbl_deuda` (
  `id` int(11) NOT NULL,
  `cuenta_id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `tipo_deuda_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuarios`
--

CREATE TABLE `tbl_usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`id`, `nombre_usuario`, `nombre`, `apellidos`, `user_password`) VALUES
(1, 'Dynox', 'Jeronimo', 'Buitrago', '$2y$10$wKHa1tRnDs6INYpevSnsFuCtWpImEJNlGx7g/ARr5hDJZx6erRqcC');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_deuda`
--

CREATE TABLE `tipo_deuda` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_cuenta`
--
ALTER TABLE `tbl_cuenta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_propietario` (`propietario`);

--
-- Indices de la tabla `tbl_deuda`
--
ALTER TABLE `tbl_deuda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbl_deuda_tbl_cuenta` (`cuenta_id`),
  ADD KEY `fk_tbl_deuda_tipo_deuda` (`tipo_deuda_id`);

--
-- Indices de la tabla `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`);

--
-- Indices de la tabla `tipo_deuda`
--
ALTER TABLE `tipo_deuda`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_cuenta`
--
ALTER TABLE `tbl_cuenta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_deuda`
--
ALTER TABLE `tbl_deuda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipo_deuda`
--
ALTER TABLE `tipo_deuda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tbl_cuenta`
--
ALTER TABLE `tbl_cuenta`
  ADD CONSTRAINT `fk_propietario` FOREIGN KEY (`propietario`) REFERENCES `tbl_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_deuda`
--
ALTER TABLE `tbl_deuda`
  ADD CONSTRAINT `fk_tbl_deuda_tbl_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `tbl_cuenta` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tbl_deuda_tipo_deuda` FOREIGN KEY (`tipo_deuda_id`) REFERENCES `tipo_deuda` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
