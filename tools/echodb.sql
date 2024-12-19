-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-12-2024 a las 23:32:32
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `echodb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `session_activity`
--

CREATE TABLE `session_activity` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `passwordhash` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `rol` int(1) NOT NULL COMMENT '0 = usuario;\r\n1 = operador;\r\n2 = administrador;',
  `website` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `api_key` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `hostDB` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `userDB` varchar(50) COLLATE utf8_spanish2_ci DEFAULT '0',
  `passwordDB` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `databaseDB` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `typeDB` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `portDB` int(5) NOT NULL DEFAULT 0,
  `ssl_enabledDB` int(1) NOT NULL DEFAULT 0 COMMENT '0: No,\r\n1: Si',
  `charsetDB` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `contenido` text COLLATE utf8_spanish2_ci DEFAULT NULL,
  `vencimiento` datetime NOT NULL,
  `activo` int(1) NOT NULL DEFAULT 0,
  `token` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `verificado` int(1) NOT NULL DEFAULT 0 COMMENT '0: Usuario No Verificado;\r\n1: Usuario Si Verificado;',
  `licencia` int(1) NOT NULL DEFAULT 0 COMMENT '0: Gratis;\r\n1: Plan Básico;\r\n2: Plan Avanzado;\r\n3: Plan Profesional;',
  `tokenRecuperacion` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `session_activity`
--
ALTER TABLE `session_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `session_activity`
--
ALTER TABLE `session_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
