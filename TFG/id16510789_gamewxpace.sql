-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 17-06-2021 a las 21:02:18
-- Versión del servidor: 10.3.16-MariaDB
-- Versión de PHP: 7.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `id16510789_gamewxpace`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hilos`
--

CREATE TABLE `hilos` (
  `tema` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `autor` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create` timestamp NULL DEFAULT current_timestamp(),
  `id_tema` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `hilos`
--

INSERT INTO `hilos` (`tema`, `autor`, `create`, `id_tema`) VALUES
('Trucos para arkanoid', 'javier', '2021-05-21 12:34:05', 3),
('test', 'javier', '2021-05-28 22:24:23', 5),
('123', '123123', '2021-05-31 18:36:35', 27),
('qwe', 'javier', '2021-06-14 19:30:00', 32);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `likes`
--

CREATE TABLE `likes` (
  `id_like` int(5) NOT NULL,
  `fk_id_mensaje` int(10) NOT NULL,
  `like` bit(1) NOT NULL,
  `fk_id_usu` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `likes`
--

INSERT INTO `likes` (`id_like`, `fk_id_mensaje`, `like`, `fk_id_usu`) VALUES
(27, 11, b'0', 'javier'),
(28, 11, b'1', 'miki'),
(29, 12, b'1', 'javier'),
(30, 13, b'1', 'javier'),
(31, 19, b'1', 'javier'),
(32, 18, b'1', 'javier'),
(33, 16, b'1', 'miki'),
(34, 12, b'1', 'miki'),
(35, 23, b'1', 'javier'),
(36, 24, b'1', 'margarita'),
(37, 26, b'1', 'maratite'),
(38, 27, b'1', 'margarita'),
(39, 11, b'1', 'margarita'),
(40, 19, b'1', 'margarita'),
(41, 29, b'1', 'javier'),
(42, 14, b'0', 'javier'),
(43, 25, b'1', 'natalia'),
(44, 10, b'1', 'natalia'),
(46, 10, b'1', 'javier'),
(47, 38, b'1', 'javier'),
(48, 39, b'1', 'javier'),
(50, 31, b'1', 'javier'),
(53, 27, b'1', 'javier');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Mensajes`
--

CREATE TABLE `Mensajes` (
  `id_mensaje` int(11) NOT NULL,
  `autor` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `mensaje` longtext COLLATE utf8_unicode_ci NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fk_id_hilo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Mensajes`
--

INSERT INTO `Mensajes` (`id_mensaje`, `autor`, `mensaje`, `creacion`, `fk_id_hilo`) VALUES
(10, 'javier', 'miguel eres muy listo tio', '2021-05-30 18:31:34', 3),
(11, 'miki', 'Hola', '2021-05-30 18:36:01', 3),
(12, 'javier', 'Pues parece que funciona bien esta baina loca', '2021-05-30 18:41:46', 3),
(13, 'javier', 'asdasd', '2021-05-30 18:55:57', 3),
(14, 'javier', 'Esto esta en la pagina del test ', '2021-05-30 19:30:44', 3),
(15, 'javier', 'Mensaje de test para ver si rula esta baina loca', '2021-05-30 19:33:50', 3),
(16, 'javier', 'sdf', '2021-05-30 19:34:02', 3),
(17, 'javier', 'fgh', '2021-05-30 19:34:05', 3),
(18, 'javier', 'asd', '2021-05-30 19:35:02', 5),
(19, 'javier', 'mensaje apra ver si rula', '2021-05-30 19:35:08', 5),
(21, 'miki', 'prueba superada', '2021-05-30 20:51:17', 3),
(23, 'javier', 'Pues esta chulo el sitio este e mi like le dejo♥️', '2021-05-30 22:44:24', 5),
(24, '123123', 'que tal', '2021-05-31 18:36:42', 27),
(25, 'maratite', 'Pues funciona la baina lets go', '2021-05-31 18:37:29', 3),
(26, 'margarita', 'fenomenal :)', '2021-05-31 18:53:00', 27),
(27, 'maratite', 'Hola margaritaaa, que tal?', '2021-05-31 18:53:19', 27),
(28, 'margarita', 'quieres ser mi amiga y jugar a voley?n', '2021-05-31 18:55:16', 27),
(29, 'maratite', 'SIIIII', '2021-05-31 18:55:26', 27),
(30, 'maratite', '1', '2021-05-31 18:55:39', 27),
(31, 'margarita', 'Pues si que esta chulete el foro si.', '2021-06-04 13:07:45', 5),
(37, 'javier', 'wefsf', '2021-06-14 19:27:21', 3),
(38, 'javier', 'sss', '2021-06-14 19:27:41', 3),
(39, 'javier', 'funciona', '2021-06-14 19:30:06', 32),
(42, 'javier', 'hola', '2021-06-16 15:08:23', 32);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usu` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `naci` date NOT NULL,
  `create` timestamp NULL DEFAULT current_timestamp(),
  `puntosMax` int(11) DEFAULT 0,
  `TotalPunt` int(11) DEFAULT 0,
  `img_perf` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `totalPuntnav` int(20) NOT NULL DEFAULT 0,
  `maxPuntnav` int(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usu`, `pass`, `naci`, `create`, `puntosMax`, `TotalPunt`, `img_perf`, `totalPuntnav`, `maxPuntnav`) VALUES
('123123', '202cb962ac59075b964b07152d234b70', '2021-04-29', '2021-05-31 18:18:27', 0, 0, 'nes-ash', 0, 0),
('alex', '202cb962ac59075b964b07152d234b70', '2021-06-15', '2021-06-04 14:08:52', 0, 0, 'nes-squirtle', 0, 0),
('javier', '202cb962ac59075b964b07152d234b70', '2021-05-18', '2021-05-20 23:58:11', 122, 587, 'nes-mario', 60, 59),
('maratite', '202cb962ac59075b964b07152d234b70', '2021-04-28', '2021-05-31 18:37:11', 5, 19, 'nes-kirby', 0, 0),
('margarita', 'd38e99d9790733c939e88698afbc30b6', '2020-02-10', '2021-05-31 18:47:44', 101, 108, 'nes-ash', 75, 21),
('miguel', '202cb962ac59075b964b07152d234b70', '2021-05-13', '2021-05-21 01:04:09', 3, 0, 'nes-bulbasaur', 0, 0),
('miki', 'e99a18c428cb38d5f260853678922e03', '2021-05-20', '2021-05-30 18:34:13', 11, 0, 'nes-squirtle', 0, 0),
('natalia', '202cb962ac59075b964b07152d234b70', '2021-06-15', '2021-06-12 09:30:07', 0, 0, 'nes-kirby', 0, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `hilos`
--
ALTER TABLE `hilos`
  ADD PRIMARY KEY (`id_tema`),
  ADD KEY `FK_autor_idx` (`autor`);

--
-- Indices de la tabla `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id_like`),
  ADD KEY `fk_id_usu` (`fk_id_usu`),
  ADD KEY `fk_id_mensaje` (`fk_id_mensaje`);

--
-- Indices de la tabla `Mensajes`
--
ALTER TABLE `Mensajes`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `fk_id_hilo` (`fk_id_hilo`),
  ADD KEY `fk_men_usu` (`autor`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usu`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `hilos`
--
ALTER TABLE `hilos`
  MODIFY `id_tema` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `likes`
--
ALTER TABLE `likes`
  MODIFY `id_like` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de la tabla `Mensajes`
--
ALTER TABLE `Mensajes`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `hilos`
--
ALTER TABLE `hilos`
  ADD CONSTRAINT `fk_autor` FOREIGN KEY (`autor`) REFERENCES `usuarios` (`id_usu`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`fk_id_mensaje`) REFERENCES `Mensajes` (`id_mensaje`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`fk_id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Mensajes`
--
ALTER TABLE `Mensajes`
  ADD CONSTRAINT `fk_id_hilo` FOREIGN KEY (`fk_id_hilo`) REFERENCES `hilos` (`id_tema`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_men_usu` FOREIGN KEY (`autor`) REFERENCES `usuarios` (`id_usu`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
