-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-12-2024 a las 19:33:58
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
-- Base de datos: `pt05_alberto_gonzalez`
--
CREATE DATABASE IF NOT EXISTS `pt05_alberto_gonzalez` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pt05_alberto_gonzalez`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `ID` int(11) NOT NULL,
  `titol` text NOT NULL,
  `cos` text NOT NULL,
  `usuari_id` int(11) DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articles`
--

INSERT INTO `articles` (`ID`, `titol`, `cos`, `usuari_id`, `imagen_url`, `data`) VALUES
(1, 'Torre d\'arqueres', 'La torre d\'arqueres és essencial per defensar la teva aldea dels atacs enemics.', 4, NULL, '2024-11-19 19:37:57'),
(2, 'Barbarians', 'Els barbarians són una de les tropes més potents per fer atacs ràpids i sorpresius.', 4, NULL, '2024-11-19 19:37:57'),
(3, 'Camp de Construcció', 'El camp de construcció et permet millorar les teves defenses i tropes més ràpidament.', 4, NULL, '2024-11-19 19:37:57'),
(4, 'Estratègia de guerra', 'Desenvolupa una bona estratègia per guanyar les batalles i superar els teus rivals.', 4, NULL, '2024-11-19 19:37:57'),
(5, 'Tropes de nivell 3', 'Les tropes de nivell 3 ofereixen millores significatives en el combat i en la defensa.', 4, NULL, '2024-11-19 19:37:57'),
(6, 'Actualitzacions de joc', 'Manten-te informat sobre les últimes actualitzacions per treure el màxim profit del joc.', 4, NULL, '2024-11-19 19:37:57'),
(7, 'Eixample d\'aldes', 'Aprofita l\'eixample d\'aldes per maximitzar els recursos i la defensa de la teva aldea.', 4, NULL, '2024-11-19 19:37:57'),
(8, 'Construccions ràpides', 'Aprèn a construir i millorar les estructures de manera ràpida per defensar millor.', 4, NULL, '2024-11-19 19:37:57'),
(9, 'Tàctiques de defensa', 'Utilitza tàctiques de defensa efectives per protegir el teu poble d\'envasions rivals.', 4, NULL, '2024-11-19 19:37:57'),
(75, 'Barbarians', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a blandit sem. Sed congue elit eget pulvinar dignissim. Fusce in maximus lacus, quis fringilla metus. Quisque lobortis laoreet quam. Vestibulum pellentesque rutrum tincidunt.', 1, NULL, '2024-12-04 15:25:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuaris`
--

DROP TABLE IF EXISTS `usuaris`;
CREATE TABLE `usuaris` (
  `id` int(11) NOT NULL,
  `usuari` varchar(255) DEFAULT NULL,
  `contrasenya` varchar(255) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `token_recuperacion` varchar(32) DEFAULT NULL,
  `token_expiracio_restablir` datetime DEFAULT NULL,
  `token_remember` varchar(64) DEFAULT NULL,
  `token_remember_expiracio` datetime DEFAULT NULL,
  `imatge` varchar(255) DEFAULT NULL,
  `rol` enum('admin','user') DEFAULT 'user',
  `aut_social` enum('si','no') DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuaris`
--

INSERT INTO `usuaris` (`id`, `usuari`, `contrasenya`, `correo`, `token_recuperacion`, `token_expiracio_restablir`, `token_remember`, `token_remember_expiracio`, `imatge`, `rol`, `aut_social`) VALUES
(1, 'Alberto', '$2y$10$ZDj1jgtY.A8chCsWKdjzA.CmRCGdHj1Ur9.Kjt.haMxC0xLPCaa2i', 'alberto.gb222@gmail.com', NULL, NULL, 'da303dbdf30b9ce649cecc799a2cbbd1528f2c0f4ae1fcd0b7de0532ac08809a', '2024-12-07 01:33:14', '../Imatges/perfil_674a155a2058f.webp', 'user', 'no'),
(2, 'a', '$2y$10$i60lBxviuk5vH1Gg3qF/9e8UwcN9n3U0y3cDQPBxodCdGFC.be..W', 'a', NULL, NULL, NULL, NULL, NULL, 'user', 'no'),
(4, 'Xavi', '$2y$10$U5yGmoOTsxcX7LHoHN5ZS.iUMM9QMwXhg91lYj.fUGiEfazYCi376', 'xmartin@sapalomera.cat', NULL, NULL, NULL, NULL, '../Imatges/perfil_674cbe9ee104c.jpg', 'admin', 'no'),
(5, 'admin', '$2y$10$oDZwsS1iCUqMRfK1SHfh0.cnDa9i3e1k6B9ydrX2cqy1kG8iMlaCa', 'admin@admin.com', NULL, NULL, NULL, NULL, NULL, 'admin', 'no'),
(13, 'Usuari1212', NULL, 'a.gonzalez7@sapalomera.cat', NULL, NULL, NULL, NULL, '../Imatges/perfil_675072dbc95ee.jpg', 'user', 'si'),
(14, 'Killerbam', '$2y$10$rtQGRl5P0MDkQBd6vCGUpuux2vc4Hs0ZOzcZ0dY/6O7S64fPo6zvu', 'killerbam2003@gmail.com', NULL, NULL, NULL, NULL, NULL, 'user', 'no');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_usuari_id` (`usuari_id`);

--
-- Indices de la tabla `usuaris`
--
ALTER TABLE `usuaris`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_correo` (`correo`),
  ADD UNIQUE KEY `unique_usuari` (`usuari`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articles`
--
ALTER TABLE `articles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `usuaris`
--
ALTER TABLE `usuaris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_usuari_id` FOREIGN KEY (`usuari_id`) REFERENCES `usuaris` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
