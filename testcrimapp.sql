-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-01-2017 a las 06:47:52
-- Versión del servidor: 5.6.24
-- Versión de PHP: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `testcrimapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `delito`
--

CREATE TABLE IF NOT EXISTS `delito` (
  `ID_DELITO` int(11) NOT NULL,
  `NDELITO` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `delito`
--

INSERT INTO `delito` (`ID_DELITO`, `NDELITO`) VALUES
(1, 'Hurto Simple\r\n'),
(2, 'Hurto Agravado'),
(3, 'Hurto de Uso'),
(4, 'Robo con fuerza en las cosas'),
(5, 'Robo con Violencia o Intimidacion en las Personas'),
(6, 'Robo Agravado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE IF NOT EXISTS `genero` (
  `ID_GENERO` int(11) NOT NULL,
  `NOMBRE` varchar(20) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`ID_GENERO`, `NOMBRE`) VALUES
(1, 'Femenino'),
(2, 'Masculino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte`
--

CREATE TABLE IF NOT EXISTS `reporte` (
  `ID_REPORTE` int(11) NOT NULL,
  `ID_USUARIO` int(11) DEFAULT NULL,
  `ID_DELITO` int(11) DEFAULT NULL,
  `DIRECCION` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `DESCRIP` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `FECHA` date DEFAULT NULL,
  `HORA` varchar(50) DEFAULT NULL,
  `LATITUD` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `LONGITUD` varchar(100) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `reporte`
--

INSERT INTO `reporte` (`ID_REPORTE`, `ID_USUARIO`, `ID_DELITO`, `DIRECCION`, `DESCRIP`, `FECHA`, `HORA`, `LATITUD`, `LONGITUD`) VALUES
(145, 50, 1, 'URAV', 'Dos tipos en motos', '2017-01-05', '12:12', '12.127078892779046', ' -86.2457200884819'),
(146, 51, 5, 'UNi', 'Motociclista', '2017-01-10', '10:00', '12.12240267140717', ' -86.23490810394287'),
(147, 50, 2, 'sa', 'as', '2017-01-24', '12:22', '12.093846366744012', ' -86.24915331602097'),
(148, 50, 3, 'as', 'Cold', '2017-01-24', '12:12', '12.127078892779046', ' -86.22821062803268'),
(149, 50, 1, 'sad', 'sasd', '2017-01-06', '12:02', '12.114155624167536', ' -86.2347337603569'),
(151, 50, 1, 'Pista la sabana ', 'Silvio lloron', '2017-01-24', '12:12', '12.128085874427818', ' -86.21894091367722'),
(152, 50, 2, 'Bolonia', 'Genaro', '2017-01-03', '12:12', '12.13748418638027', ' -86.27970904111862'),
(153, 50, 1, 'EL Zumen', 'Dos tipos motorizados. Estaba afuera de la casa. Etc', '2017-01-24', '12:15', '12.12726245742554', ' -86.29973039031029'),
(154, 52, 1, 'Kari', 'asd', '2017-01-11', '12:21', '12.10878473105557', ' -86.23748034238815');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `ID_USUARIO` int(11) NOT NULL,
  `NOMBRE` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `CORREO` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `ID_FACEBOOK` int(100) NOT NULL,
  `ID_GENERO` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID_USUARIO`, `NOMBRE`, `CORREO`, `ID_FACEBOOK`, `ID_GENERO`) VALUES
(50, 'Jake ZeledÃ³n', 'jackiezeledon2@hotmail.com', 2147483647, 2),
(51, 'Juno Text', 'junotest2017@gmail.com', 2147483647, 2),
(52, 'Kary Salinas', 'ana.karina.c@hotmail.com', 2147483647, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `delito`
--
ALTER TABLE `delito`
  ADD PRIMARY KEY (`ID_DELITO`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`ID_GENERO`);

--
-- Indices de la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD PRIMARY KEY (`ID_REPORTE`), ADD KEY `FK_REPUSUAR` (`ID_USUARIO`), ADD KEY `FK_REPDELI` (`ID_DELITO`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID_USUARIO`), ADD KEY `FK_GenUsua` (`ID_GENERO`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `delito`
--
ALTER TABLE `delito`
  MODIFY `ID_DELITO` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `ID_GENERO` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `reporte`
--
ALTER TABLE `reporte`
  MODIFY `ID_REPORTE` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=155;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID_USUARIO` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=53;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reporte`
--
ALTER TABLE `reporte`
ADD CONSTRAINT `FK_REPDELI` FOREIGN KEY (`ID_DELITO`) REFERENCES `delito` (`ID_DELITO`),
ADD CONSTRAINT `FK_REPUSUAR` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
ADD CONSTRAINT `FK_GenUsua` FOREIGN KEY (`ID_GENERO`) REFERENCES `genero` (`ID_GENERO`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
