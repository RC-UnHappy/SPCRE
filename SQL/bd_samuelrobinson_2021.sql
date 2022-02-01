-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-04-2021 a las 16:16:25
-- Versión del servidor: 10.4.17-MariaDB
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_sm`
--
CREATE DATABASE IF NOT EXISTS `db_sm` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `db_sm`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `cod_per` int(11) NOT NULL,
  `cod_diahbl` date NOT NULL,
  `marcacion` char(1) COLLATE utf8_spanish_ci NOT NULL COMMENT 'E=entrada, S=salida',
  `estatus` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `hora` time NOT NULL,
  `observacion` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aula`
--

CREATE TABLE `aula` (
  `cod_aula` int(11) NOT NULL,
  `nom_aula` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `estatus` char(1) COLLATE latin1_spanish_ci NOT NULL DEFAULT 'D'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `aula`
--

INSERT INTO `aula` (`cod_aula`, `nom_aula`, `estatus`) VALUES
(1, 'Aula - 1A', 'O'),
(2, 'Aula - 1B', 'D'),
(3, 'Aula - 2A', 'D'),
(4, 'Aula - 2B', 'O'),
(5, 'Aula - 3A', 'D'),
(6, 'Aula - 3B', 'D'),
(7, 'Aula - 4A', 'D'),
(8, 'Aula - 4B', 'D'),
(9, 'Aula - 5A', 'D'),
(10, 'Aula - 5B', 'D'),
(11, 'Ninguna', 'D');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `cod_cargo` int(11) NOT NULL,
  `nom_cargo` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`cod_cargo`, `nom_cargo`) VALUES
(1, 'DIRECTOR(A)'),
(3, 'SUBDIRECTOR(A)'),
(4, 'ADMINISTRATIVO'),
(5, 'DOCENTE'),
(7, 'COORDINADOR(A)'),
(8, 'PROF.'),
(9, 'OBRERO(A)'),
(10, 'NINGUNO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cond_infraestructura`
--

CREATE TABLE `cond_infraestructura` (
  `cod_cond_inf` int(11) NOT NULL,
  `nom_cond_inf` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cond_infraestructura`
--

INSERT INTO `cond_infraestructura` (`cod_cond_inf`, `nom_cond_inf`) VALUES
(1, 'Deplorable'),
(2, 'Deteriorada'),
(3, 'Regular'),
(4, 'Buena'),
(5, 'Excelente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cond_vivienda`
--

CREATE TABLE `cond_vivienda` (
  `cod_cond_vnda` int(11) NOT NULL,
  `nom_cond_vnda` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cond_vivienda`
--

INSERT INTO `cond_vivienda` (`cod_cond_vnda`, `nom_cond_vnda`) VALUES
(1, 'Al cuído'),
(2, 'Alquilada'),
(3, 'Anexo'),
(4, 'Arrimado'),
(5, 'Cedida'),
(6, 'Conserjería'),
(7, 'De la empresa'),
(8, 'Herencia/Sucesión'),
(9, 'Indígena'),
(10, 'Invasión/Tomada'),
(11, 'Otro'),
(12, 'Prestada'),
(13, 'Propia pagada'),
(14, 'Propia pagándose');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_periodo`
--

CREATE TABLE `conf_periodo` (
  `cod_periodo` int(11) NOT NULL,
  `apertura_insc_n` datetime NOT NULL,
  `cierre_insc_n` datetime NOT NULL,
  `apertura_insc_r` datetime NOT NULL,
  `cierre_insc_r` datetime NOT NULL,
  `fmi_desde` date NOT NULL COMMENT 'fecha de matricula inicial',
  `fmi_hasta` date NOT NULL COMMENT 'fecha de matricula inicial'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `conf_periodo`
--

INSERT INTO `conf_periodo` (`cod_periodo`, `apertura_insc_n`, `cierre_insc_n`, `apertura_insc_r`, `cierre_insc_r`, `fmi_desde`, `fmi_hasta`) VALUES
(1, '2021-01-01 08:30:00', '2021-12-03 15:41:00', '2021-01-01 00:10:00', '2021-12-03 15:43:00', '2021-01-01', '2021-02-28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_socioeconomicos`
--

CREATE TABLE `datos_socioeconomicos` (
  `cod_est` int(11) NOT NULL,
  `tipo_vnda` int(11) NOT NULL,
  `condicion_vnda` int(11) NOT NULL,
  `infraestructura_vnda` int(11) NOT NULL,
  `num_habitaciones` int(2) NOT NULL,
  `num_personas` int(2) NOT NULL,
  `num_personas_trbj` int(2) NOT NULL,
  `num_hermanos` int(2) NOT NULL,
  `num_hermanos_esc` int(2) NOT NULL,
  `ingreso_familiar` float(12,2) NOT NULL,
  `tiene_beca` tinyint(1) NOT NULL,
  `serial_canaima` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `datos_socioeconomicos`
--

INSERT INTO `datos_socioeconomicos` (`cod_est`, `tipo_vnda`, `condicion_vnda`, `infraestructura_vnda`, `num_habitaciones`, `num_personas`, `num_personas_trbj`, `num_hermanos`, `num_hermanos_esc`, `ingreso_familiar`, `tiene_beca`, `serial_canaima`) VALUES
(16, 1, 1, 1, 5, 5, 2, 2, 2, 6400000.00, 0, ''),
(17, 1, 1, 1, 5, 5, 5, 5, 5, 55555556.00, 0, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dia_habil`
--

CREATE TABLE `dia_habil` (
  `cod_diahbl` date NOT NULL,
  `cod_periodo` int(11) NOT NULL,
  `estatus` char(1) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `dia_habil`
--

INSERT INTO `dia_habil` (`cod_diahbl`, `cod_periodo`, `estatus`) VALUES
('0000-00-00', 1, 'I'),
('2020-09-01', 1, 'A'),
('2020-09-02', 1, 'A'),
('2020-09-03', 1, 'A'),
('2020-09-04', 1, 'A'),
('2021-03-24', 1, 'A'),
('2021-04-01', 1, 'A'),
('2021-04-02', 1, 'A'),
('2021-04-05', 1, 'A'),
('2021-04-06', 1, 'A'),
('2021-04-07', 1, 'A'),
('2021-04-08', 1, 'A'),
('2021-04-09', 1, 'A'),
('2021-04-12', 1, 'A'),
('2021-04-13', 1, 'A'),
('2021-04-14', 1, 'A'),
('2021-04-15', 1, 'A'),
('2021-04-16', 1, 'A'),
('2021-04-19', 1, 'A'),
('2021-04-20', 1, 'A'),
('2021-04-21', 1, 'A'),
('2021-04-22', 1, 'A'),
('2021-04-23', 1, 'A'),
('2021-04-24', 1, 'A'),
('2021-04-25', 1, 'A'),
('2021-04-26', 1, 'A'),
('2021-04-27', 1, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion`
--

CREATE TABLE `direccion` (
  `cod_dir` int(11) NOT NULL,
  `parroquia` int(11) NOT NULL,
  `sector` varchar(120) COLLATE utf8_spanish_ci NOT NULL,
  `calle` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `nro` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `direccion`
--

INSERT INTO `direccion` (`cod_dir`, `parroquia`, `sector`, `calle`, `nro`) VALUES
(1, 722, 'VILLA ARAURE 1', '2', '65-55'),
(2, 722, 'TRICENTENARIA', '', ''),
(3, 722, 'VILLA ARAURE 2', 'CALLE 3', '66-99');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion_persona`
--

CREATE TABLE `direccion_persona` (
  `cod_dir` int(11) NOT NULL,
  `cod_per` int(11) NOT NULL,
  `tipo_dir` char(1) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `direccion_persona`
--

INSERT INTO `direccion_persona` (`cod_dir`, `cod_per`, `tipo_dir`) VALUES
(1, 11, 'D'),
(2, 11, 'T'),
(1, 16, 'D'),
(3, 17, 'D');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discapacidad`
--

CREATE TABLE `discapacidad` (
  `cod_discpd` int(11) NOT NULL,
  `nom_discpd` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `discapacidad`
--

INSERT INTO `discapacidad` (`cod_discpd`, `nom_discpd`) VALUES
(1, 'NINGUNA'),
(2, 'FISICA'),
(3, 'AUDITIVA'),
(4, 'VISUAL'),
(5, 'INTELECTUAL'),
(6, 'PSIQUICA'),
(7, 'VISCERAL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `cod_est` int(11) NOT NULL,
  `ficha_vacunas` tinyint(1) NOT NULL,
  `p_nacimiento` tinyint(1) NOT NULL,
  `copia_ciRep` tinyint(1) NOT NULL,
  `copia_ciEst` tinyint(1) NOT NULL,
  `fotos_est` tinyint(1) NOT NULL,
  `foto_rep` tinyint(1) NOT NULL,
  `constancia_prom` tinyint(1) NOT NULL,
  `inf_desc` tinyint(1) NOT NULL,
  `boleta_retiro` tinyint(1) NOT NULL,
  `inf_medico` tinyint(1) NOT NULL,
  `otros` varchar(100) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `documentos`
--

INSERT INTO `documentos` (`cod_est`, `ficha_vacunas`, `p_nacimiento`, `copia_ciRep`, `copia_ciEst`, `fotos_est`, `foto_rep`, `constancia_prom`, `inf_desc`, `boleta_retiro`, `inf_medico`, `otros`) VALUES
(16, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, ''),
(17, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enfermedad`
--

CREATE TABLE `enfermedad` (
  `cod_enf` int(11) NOT NULL,
  `nom_enf` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `enfermedad`
--

INSERT INTO `enfermedad` (`cod_enf`, `nom_enf`) VALUES
(2, 'Covid-19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enf_padecida`
--

CREATE TABLE `enf_padecida` (
  `cod_est` int(11) NOT NULL,
  `cod_enf` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `cod_edo` int(11) NOT NULL,
  `nom_edo` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `pais` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`cod_edo`, `nom_edo`, `pais`) VALUES
(1, 'Amazonas', 232),
(2, 'Anzoátegui', 232),
(3, 'Apure', 232),
(4, 'Aragua', 232),
(5, 'Barinas', 232),
(6, 'Bolívar', 232),
(7, 'Carabobo', 232),
(8, 'Cojedes', 232),
(9, 'Delta Amacuro', 232),
(10, 'Falcón', 232),
(11, 'Guárico', 232),
(12, 'Lara', 232),
(13, 'Mérida', 232),
(14, 'Miranda', 232),
(15, 'Monagas', 232),
(16, 'Nueva Esparta', 232),
(17, 'Portuguesa', 232),
(18, 'Sucre', 232),
(19, 'Táchira', 232),
(20, 'Trujillo', 232),
(21, 'Vargas', 232),
(22, 'Yaracuy', 232),
(23, 'Zulia', 232),
(24, 'Distrito Capital', 232),
(26, 'Estado - Afganistán', 1),
(27, 'Estado - Islas Gland', 2),
(28, 'Estado - Albania', 3),
(29, 'Estado - Alemania', 4),
(30, 'Estado - Andorra', 5),
(31, 'Estado - Angola', 6),
(32, 'Estado - Anguilla', 7),
(33, 'Estado - Antártida', 8),
(34, 'Estado - Antigua y Barbuda', 9),
(35, 'Estado - Antillas Holandesas', 10),
(36, 'Estado - Arabia Saudí', 11),
(37, 'Estado - Argelia', 12),
(38, 'Estado - Argentina', 13),
(39, 'Estado - Armenia', 14),
(40, 'Estado - Aruba', 15),
(41, 'Estado - Australia', 16),
(42, 'Estado - Austria', 17),
(43, 'Estado - Azerbaiyán', 18),
(44, 'Estado - Bahamas', 19),
(45, 'Estado - Bahréin', 20),
(46, 'Estado - Bangladesh', 21),
(47, 'Estado - Barbados', 22),
(48, 'Estado - Bielorrusia', 23),
(49, 'Estado - Bélgica', 24),
(50, 'Estado - Belice', 25),
(51, 'Estado - Benin', 26),
(52, 'Estado - Bermudas', 27),
(53, 'Estado - Bhután', 28),
(54, 'Estado - Bolivia', 29),
(55, 'Estado - Bosnia y Herzegovina', 30),
(56, 'Estado - Botsuana', 31),
(57, 'Estado - Isla Bouvet', 32),
(58, 'Estado - Brasil', 33),
(59, 'Estado - Brunéi', 34),
(60, 'Estado - Bulgaria', 35),
(61, 'Estado - Burkina Faso', 36),
(62, 'Estado - Burundi', 37),
(63, 'Estado - Cabo Verde', 38),
(64, 'Estado - Islas Caimán', 39),
(65, 'Estado - Camboya', 40),
(66, 'Estado - Camerún', 41),
(67, 'Estado - Canadá', 42),
(68, 'Estado - República Centroafricana', 43),
(69, 'Estado - Chad', 44),
(70, 'Estado - República Checa', 45),
(71, 'Estado - Chile', 46),
(72, 'Estado - China', 47),
(73, 'Estado - Chipre', 48),
(74, 'Estado - Isla de Navidad', 49),
(75, 'Estado - Ciudad del Vaticano', 50),
(76, 'Estado - Islas Cocos', 51),
(77, 'Estado - Colombia', 52),
(78, 'Estado - Comoras', 53),
(79, 'Estado - República Democrática del Congo', 54),
(80, 'Estado - Congo', 55),
(81, 'Estado - Islas Cook', 56),
(82, 'Estado - Corea del Norte', 57),
(83, 'Estado - Corea del Sur', 58),
(84, 'Estado - Costa de Marfil', 59),
(85, 'Estado - Costa Rica', 60),
(86, 'Estado - Croacia', 61),
(87, 'Estado - Cuba', 62),
(88, 'Estado - Dinamarca', 63),
(89, 'Estado - Dominica', 64),
(90, 'Estado - República Dominicana', 65),
(91, 'Estado - Ecuador', 66),
(92, 'Estado - Egipto', 67),
(93, 'Estado - El Salvador', 68),
(94, 'Estado - Emiratos Árabes Unidos', 69),
(95, 'Estado - Eritrea', 70),
(96, 'Estado - Eslovaquia', 71),
(97, 'Estado - Eslovenia', 72),
(98, 'Estado - España', 73),
(99, 'Estado - Islas ultramarinas de Estados Unidos', 74),
(100, 'Estado - Estados Unidos', 75),
(101, 'Estado - Estonia', 76),
(102, 'Estado - Etiopía', 77),
(103, 'Estado - Islas Feroe', 78),
(104, 'Estado - Filipinas', 79),
(105, 'Estado - Finlandia', 80),
(106, 'Estado - Fiyi', 81),
(107, 'Estado - Francia', 82),
(108, 'Estado - Gabón', 83),
(109, 'Estado - Gambia', 84),
(110, 'Estado - Georgia', 85),
(111, 'Estado - Islas Georgias del Sur y Sandwich del Sur', 86),
(112, 'Estado - Ghana', 87),
(113, 'Estado - Gibraltar', 88),
(114, 'Estado - Granada', 89),
(115, 'Estado - Grecia', 90),
(116, 'Estado - Groenlandia', 91),
(117, 'Estado - Guadalupe', 92),
(118, 'Estado - Guam', 93),
(119, 'Estado - Guatemala', 94),
(120, 'Estado - Guayana Francesa', 95),
(121, 'Estado - Guinea', 96),
(122, 'Estado - Guinea Ecuatorial', 97),
(123, 'Estado - Guinea-Bissau', 98),
(124, 'Estado - Guyana', 99),
(125, 'Estado - Haití', 100),
(126, 'Estado - Islas Heard y McDonald', 101),
(127, 'Estado - Honduras', 102),
(128, 'Estado - Hong Kong', 103),
(129, 'Estado - Hungría', 104),
(130, 'Estado - India', 105),
(131, 'Estado - Indonesia', 106),
(132, 'Estado - Irán', 107),
(133, 'Estado - Iraq', 108),
(134, 'Estado - Irlanda', 109),
(135, 'Estado - Islandia', 110),
(136, 'Estado - Israel', 111),
(137, 'Estado - Italia', 112),
(138, 'Estado - Jamaica', 113),
(139, 'Estado - Japón', 114),
(140, 'Estado - Jordania', 115),
(141, 'Estado - Kazajstán', 116),
(142, 'Estado - Kenia', 117),
(143, 'Estado - Kirguistán', 118),
(144, 'Estado - Kiribati', 119),
(145, 'Estado - Kuwait', 120),
(146, 'Estado - Laos', 121),
(147, 'Estado - Lesotho', 122),
(148, 'Estado - Letonia', 123),
(149, 'Estado - Líbano', 124),
(150, 'Estado - Liberia', 125),
(151, 'Estado - Libia', 126),
(152, 'Estado - Liechtenstein', 127),
(153, 'Estado - Lituania', 128),
(154, 'Estado - Luxemburgo', 129),
(155, 'Estado - Macao', 130),
(156, 'Estado - ARY Macedonia', 131),
(157, 'Estado - Madagascar', 132),
(158, 'Estado - Malasia', 133),
(159, 'Estado - Malawi', 134),
(160, 'Estado - Maldivas', 135),
(161, 'Estado - Malí', 136),
(162, 'Estado - Malta', 137),
(163, 'Estado - Islas Malvinas', 138),
(164, 'Estado - Islas Marianas del Norte', 139),
(165, 'Estado - Marruecos', 140),
(166, 'Estado - Islas Marshall', 141),
(167, 'Estado - Martinica', 142),
(168, 'Estado - Mauricio', 143),
(169, 'Estado - Mauritania', 144),
(170, 'Estado - Mayotte', 145),
(171, 'Estado - México', 146),
(172, 'Estado - Micronesia', 147),
(173, 'Estado - Moldavia', 148),
(174, 'Estado - Mónaco', 149),
(175, 'Estado - Mongolia', 150),
(176, 'Estado - Montserrat', 151),
(177, 'Estado - Mozambique', 152),
(178, 'Estado - Myanmar', 153),
(179, 'Estado - Namibia', 154),
(180, 'Estado - Nauru', 155),
(181, 'Estado - Nepal', 156),
(182, 'Estado - Nicaragua', 157),
(183, 'Estado - Níger', 158),
(184, 'Estado - Nigeria', 159),
(185, 'Estado - Niue', 160),
(186, 'Estado - Isla Norfolk', 161),
(187, 'Estado - Noruega', 162),
(188, 'Estado - Nueva Caledonia', 163),
(189, 'Estado - Nueva Zelanda', 164),
(190, 'Estado - Omán', 165),
(191, 'Estado - Países Bajos', 166),
(192, 'Estado - Pakistán', 167),
(193, 'Estado - Palau', 168),
(194, 'Estado - Palestina', 169),
(195, 'Estado - Panamá', 170),
(196, 'Estado - Papúa Nueva Guinea', 171),
(197, 'Estado - Paraguay', 172),
(198, 'Estado - Perú', 173),
(199, 'Estado - Islas Pitcairn', 174),
(200, 'Estado - Polinesia Francesa', 175),
(201, 'Estado - Polonia', 176),
(202, 'Estado - Portugal', 177),
(203, 'Estado - Puerto Rico', 178),
(204, 'Estado - Qatar', 179),
(205, 'Estado - Reino Unido', 180),
(206, 'Estado - Reunión', 181),
(207, 'Estado - Ruanda', 182),
(208, 'Estado - Rumania', 183),
(209, 'Estado - Rusia', 184),
(210, 'Estado - Sahara Occidental', 185),
(211, 'Estado - Islas Salomón', 186),
(212, 'Estado - Samoa', 187),
(213, 'Estado - Samoa Americana', 188),
(214, 'Estado - San Cristóbal y Nevis', 189),
(215, 'Estado - San Marino', 190),
(216, 'Estado - San Pedro y Miquelón', 191),
(217, 'Estado - San Vicente y las Granadinas', 192),
(218, 'Estado - Santa Helena', 193),
(219, 'Estado - Santa Lucía', 194),
(220, 'Estado - Santo Tomé y Príncipe', 195),
(221, 'Estado - Senegal', 196),
(222, 'Estado - Serbia y Montenegro', 197),
(223, 'Estado - Seychelles', 198),
(224, 'Estado - Sierra Leona', 199),
(225, 'Estado - Singapur', 200),
(226, 'Estado - Siria', 201),
(227, 'Estado - Somalia', 202),
(228, 'Estado - Sri Lanka', 203),
(229, 'Estado - Suazilandia', 204),
(230, 'Estado - Sudáfrica', 205),
(231, 'Estado - Sudán', 206),
(232, 'Estado - Suecia', 207),
(233, 'Estado - Suiza', 208),
(234, 'Estado - Surinam', 209),
(235, 'Estado - Svalbard y Jan Mayen', 210),
(236, 'Estado - Tailandia', 211),
(237, 'Estado - Taiwán', 212),
(238, 'Estado - Tanzania', 213),
(239, 'Estado - Tayikistán', 214),
(240, 'Estado - Territorio Británico del Océano Índico', 215),
(241, 'Estado - Territorios Australes Franceses', 216),
(242, 'Estado - Timor Oriental', 217),
(243, 'Estado - Togo', 218),
(244, 'Estado - Tokelau', 219),
(245, 'Estado - Tonga', 220),
(246, 'Estado - Trinidad y Tobago', 221),
(247, 'Estado - Túnez', 222),
(248, 'Estado - Islas Turcas y Caicos', 223),
(249, 'Estado - Turkmenistán', 224),
(250, 'Estado - Turquía', 225),
(251, 'Estado - Tuvalu', 226),
(252, 'Estado - Ucrania', 227),
(253, 'Estado - Uganda', 228),
(254, 'Estado - Uruguay', 229),
(255, 'Estado - Uzbekistán', 230),
(256, 'Estado - Vanuatu', 231),
(257, 'Estado - Vietnam', 233),
(258, 'Estado - Islas Vírgenes Británicas', 234),
(259, 'Estado - Islas Vírgenes de los Estados Unidos', 235),
(260, 'Estado - Wallis y Futuna', 236),
(261, 'Estado - Yemen', 237),
(262, 'Estado - Yibuti', 238),
(263, 'Estado - Zambia', 239),
(264, 'Estado - Zimbabue', 240);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `cod_per` int(11) NOT NULL,
  `ced_esc` char(14) COLLATE utf8_spanish_ci NOT NULL,
  `cod_madre` int(11) NOT NULL DEFAULT 2,
  `cod_padre` int(11) NOT NULL DEFAULT 2,
  `lugar_nac` int(11) NOT NULL,
  `estatura` varchar(4) COLLATE utf8_spanish_ci NOT NULL,
  `peso` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `camisa` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `pantalon` varchar(4) COLLATE utf8_spanish_ci NOT NULL,
  `calzado` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `antp_embarazo` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `antp_parto` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `m_neuromotriz` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `antp_alimentacion` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `antp_sueno` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `antp_dentincion` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `antp_eppasos` char(2) COLLATE utf8_spanish_ci NOT NULL,
  `antp_lenguaje` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `antp_esfinteres` tinyint(1) NOT NULL,
  `observacion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `alergico` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `enf_afeccion_aesp` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `discapacidad` int(11) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `estatus` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`cod_per`, `ced_esc`, `cod_madre`, `cod_padre`, `lugar_nac`, `estatura`, `peso`, `camisa`, `pantalon`, `calzado`, `antp_embarazo`, `antp_parto`, `m_neuromotriz`, `antp_alimentacion`, `antp_sueno`, `antp_dentincion`, `antp_eppasos`, `antp_lenguaje`, `antp_esfinteres`, `observacion`, `alergico`, `enf_afeccion_aesp`, `discapacidad`, `fecha_ingreso`, `estatus`) VALUES
(16, 'V-11312963818', 11, 2, 1, '1.70', '60', '12', '12', '30', 'Normal', 'N', 'Normal', 'Regular', 'Regular', 'Normal', '10', 'Normal', 1, 'Sin observaciones', 'Nada', 'Nada xd', 1, '2021-04-03', '1'),
(17, 'V-11412963818', 11, 2, 1, '', '', '', '', '', '', 'N', '', '', '', '', '', '', 1, '', '', '', 2, '2021-04-03', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_vacuna`
--

CREATE TABLE `est_vacuna` (
  `cod_est` int(11) NOT NULL,
  `cod_vcna` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funcion`
--

CREATE TABLE `funcion` (
  `cod_funcion` int(11) NOT NULL,
  `nom_funcion` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `funcion`
--

INSERT INTO `funcion` (`cod_funcion`, `nom_funcion`) VALUES
(15, 'ASEADOR'),
(8, 'AULA INTEGRADA'),
(12, 'C.E.B.I.T'),
(11, 'C.R.A'),
(13, 'COORD. C.N.A.E'),
(5, 'CUARTO'),
(9, 'CULTURA'),
(10, 'DEPORTE'),
(1, 'NINGUNA'),
(18, 'OTRO'),
(17, 'PORTERO'),
(2, 'PRIMERO'),
(6, 'QUINTO'),
(14, 'SECRETARIA(O)'),
(3, 'SEGUNDO'),
(7, 'SEXTO'),
(4, 'TERCERO'),
(16, 'VIGILANTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grado_instruccion`
--

CREATE TABLE `grado_instruccion` (
  `cod_ginst` int(11) NOT NULL,
  `nom_ginst` varchar(40) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `grado_instruccion`
--

INSERT INTO `grado_instruccion` (`cod_ginst`, `nom_ginst`) VALUES
(1, 'Analfabeta'),
(2, 'Sin estudios'),
(3, 'Educación inicial'),
(4, 'Educación básica'),
(5, 'Educación media general'),
(6, 'Educación superior');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `indicador`
--

CREATE TABLE `indicador` (
  `cod_ind` int(11) NOT NULL,
  `nom_ind` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `cod_proyecto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `indicador`
--

INSERT INTO `indicador` (`cod_ind`, `nom_ind`, `cod_proyecto`) VALUES
(1, '<> * division (/) locura () -*- . %$·º1º|', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion`
--

CREATE TABLE `inscripcion` (
  `cod_insc` int(11) NOT NULL,
  `cod_est` int(11) NOT NULL,
  `cod_rep` int(11) NOT NULL,
  `parentesco` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `seccion` int(11) NOT NULL,
  `modalidad` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `condicion` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `escuela_proc` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `motivo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `estatus` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'A',
  `fecha_retiro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `inscripcion`
--

INSERT INTO `inscripcion` (`cod_insc`, `cod_est`, `cod_rep`, `parentesco`, `seccion`, `modalidad`, `condicion`, `fecha`, `escuela_proc`, `motivo`, `estatus`, `fecha_retiro`) VALUES
(1, 16, 11, '1', 1, 'N', 'P', '2021-04-03', 'U.E.N.B \"SAMUEL ROBINSON\"', '', 'A', '0000-00-00'),
(2, 17, 11, '1', 1, 'N', 'P', '2021-04-03', 'U.E.N.B \"SAMUEL ROBINSON\"', '', 'A', '0000-00-00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intentos_clv`
--

CREATE TABLE `intentos_clv` (
  `cod_usu` char(9) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_int` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intentos_preg`
--

CREATE TABLE `intentos_preg` (
  `cod_usu` char(9) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_int` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lapso`
--

CREATE TABLE `lapso` (
  `cod_lapso` int(11) NOT NULL,
  `cod_periodo` int(11) NOT NULL,
  `lapso` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_ini` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estatus` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `apertura_notas` datetime DEFAULT NULL,
  `cierre_notas` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `lapso`
--

INSERT INTO `lapso` (`cod_lapso`, `cod_periodo`, `lapso`, `fecha_ini`, `fecha_fin`, `estatus`, `apertura_notas`, `cierre_notas`) VALUES
(1, 1, '1', '2020-05-08', '2020-05-15', 'A', '2021-02-01 00:00:00', '2021-02-28 00:00:00'),
(2, 1, '2', '0000-00-00', '0000-00-00', 'N', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 1, '3', '0000-00-00', '0000-00-00', 'N', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugar_nacimiento`
--

CREATE TABLE `lugar_nacimiento` (
  `cod_lugar_nac` int(11) NOT NULL,
  `cod_parr` int(11) NOT NULL,
  `desc_lugar` varchar(250) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `lugar_nacimiento`
--

INSERT INTO `lugar_nacimiento` (`cod_lugar_nac`, `cod_parr`, `desc_lugar`) VALUES
(1, 722, 'VILLA ARAURE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `cod_modulo` int(11) NOT NULL,
  `nom_modulo` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `icono` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `estatus` char(1) CHARACTER SET armscii8 COLLATE armscii8_bin NOT NULL DEFAULT 'A',
  `pos` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`cod_modulo`, `nom_modulo`, `icono`, `estatus`, `pos`) VALUES
(13, 'Estudiante', 'user', 'A', 2),
(14, 'Reportes', 'doc-text', 'A', 3),
(15, 'Configuración', 'cog-alt', 'A', 4),
(16, 'Ayuda', 'help', 'A', 5),
(17, 'Personal', 'user', 'A', 1),
(18, 'Usuarios', 'users', 'A', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

CREATE TABLE `municipio` (
  `cod_mun` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `nom_mun` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `municipio`
--

INSERT INTO `municipio` (`cod_mun`, `estado`, `nom_mun`) VALUES
(1, 1, 'Alto Orinoco'),
(2, 1, 'Atabapo'),
(3, 1, 'Atures'),
(4, 1, 'Autana'),
(5, 1, 'Manapiare'),
(6, 1, 'Maroa'),
(7, 1, 'Río Negro'),
(8, 2, 'Anaco'),
(9, 2, 'Aragua'),
(10, 2, 'Manuel Ezequiel Bruzual'),
(11, 2, 'Diego Bautista Urbaneja'),
(12, 2, 'Fernando Peñalver'),
(13, 2, 'Francisco Del Carmen Carvajal'),
(14, 2, 'General Sir Arthur McGregor'),
(15, 2, 'Guanta'),
(16, 2, 'Independencia'),
(17, 2, 'José Gregorio Monagas'),
(18, 2, 'Juan Antonio Sotillo'),
(19, 2, 'Juan Manuel Cajigal'),
(20, 2, 'Libertad'),
(21, 2, 'Francisco de Miranda'),
(22, 2, 'Pedro María Freites'),
(23, 2, 'Píritu'),
(24, 2, 'San José de Guanipa'),
(25, 2, 'San Juan de Capistrano'),
(26, 2, 'Santa Ana'),
(27, 2, 'Simón Bolívar'),
(28, 2, 'Simón Rodríguez'),
(29, 3, 'Achaguas'),
(30, 3, 'Biruaca'),
(31, 3, 'Muñóz'),
(32, 3, 'Páez'),
(33, 3, 'Pedro Camejo'),
(34, 3, 'Rómulo Gallegos'),
(35, 3, 'San Fernando'),
(36, 4, 'Atanasio Girardot'),
(37, 4, 'Bolívar'),
(38, 4, 'Camatagua'),
(39, 4, 'Francisco Linares Alcántara'),
(40, 4, 'José Ángel Lamas'),
(41, 4, 'José Félix Ribas'),
(42, 4, 'José Rafael Revenga'),
(43, 4, 'Libertador'),
(44, 4, 'Mario Briceño Iragorry'),
(45, 4, 'Ocumare de la Costa de Oro'),
(46, 4, 'San Casimiro'),
(47, 4, 'San Sebastián'),
(48, 4, 'Santiago Mariño'),
(49, 4, 'Santos Michelena'),
(50, 4, 'Sucre'),
(51, 4, 'Tovar'),
(52, 4, 'Urdaneta'),
(53, 4, 'Zamora'),
(54, 5, 'Alberto Arvelo Torrealba'),
(55, 5, 'Andrés Eloy Blanco'),
(56, 5, 'Antonio José de Sucre'),
(57, 5, 'Arismendi'),
(58, 5, 'Barinas'),
(59, 5, 'Bolívar'),
(60, 5, 'Cruz Paredes'),
(61, 5, 'Ezequiel Zamora'),
(62, 5, 'Obispos'),
(63, 5, 'Pedraza'),
(64, 5, 'Rojas'),
(65, 5, 'Sosa'),
(66, 6, 'Caroní'),
(67, 6, 'Cedeño'),
(68, 6, 'El Callao'),
(69, 6, 'Gran Sabana'),
(70, 6, 'Heres'),
(71, 6, 'Piar'),
(72, 6, 'Angostura (Raúl Leoni)'),
(73, 6, 'Roscio'),
(74, 6, 'Sifontes'),
(75, 6, 'Sucre'),
(76, 6, 'Padre Pedro Chien'),
(77, 7, 'Bejuma'),
(78, 7, 'Carlos Arvelo'),
(79, 7, 'Diego Ibarra'),
(80, 7, 'Guacara'),
(81, 7, 'Juan José Mora'),
(82, 7, 'Libertador'),
(83, 7, 'Los Guayos'),
(84, 7, 'Miranda'),
(85, 7, 'Montalbán'),
(86, 7, 'Naguanagua'),
(87, 7, 'Puerto Cabello'),
(88, 7, 'San Diego'),
(89, 7, 'San Joaquín'),
(90, 7, 'Valencia'),
(91, 8, 'Anzoátegui'),
(92, 8, 'Tinaquillo'),
(93, 8, 'Girardot'),
(94, 8, 'Lima Blanco'),
(95, 8, 'Pao de San Juan Bautista'),
(96, 8, 'Ricaurte'),
(97, 8, 'Rómulo Gallegos'),
(98, 8, 'San Carlos'),
(99, 8, 'Tinaco'),
(100, 9, 'Antonio Díaz'),
(101, 9, 'Casacoima'),
(102, 9, 'Pedernales'),
(103, 9, 'Tucupita'),
(104, 10, 'Acosta'),
(105, 10, 'Bolívar'),
(106, 10, 'Buchivacoa'),
(107, 10, 'Cacique Manaure'),
(108, 10, 'Carirubana'),
(109, 10, 'Colina'),
(110, 10, 'Dabajuro'),
(111, 10, 'Democracia'),
(112, 10, 'Falcón'),
(113, 10, 'Federación'),
(114, 10, 'Jacura'),
(115, 10, 'José Laurencio Silva'),
(116, 10, 'Los Taques'),
(117, 10, 'Mauroa'),
(118, 10, 'Miranda'),
(119, 10, 'Monseñor Iturriza'),
(120, 10, 'Palmasola'),
(121, 10, 'Petit'),
(122, 10, 'Píritu'),
(123, 10, 'San Francisco'),
(124, 10, 'Sucre'),
(125, 10, 'Tocópero'),
(126, 10, 'Unión'),
(127, 10, 'Urumaco'),
(128, 10, 'Zamora'),
(129, 11, 'Camaguán'),
(130, 11, 'Chaguaramas'),
(131, 11, 'El Socorro'),
(132, 11, 'José Félix Ribas'),
(133, 11, 'José Tadeo Monagas'),
(134, 11, 'Juan Germán Roscio'),
(135, 11, 'Julián Mellado'),
(136, 11, 'Las Mercedes'),
(137, 11, 'Leonardo Infante'),
(138, 11, 'Pedro Zaraza'),
(139, 11, 'Ortíz'),
(140, 11, 'San Gerónimo de Guayabal'),
(141, 11, 'San José de Guaribe'),
(142, 11, 'Santa María de Ipire'),
(143, 11, 'Sebastián Francisco de Miranda'),
(144, 12, 'Andrés Eloy Blanco'),
(145, 12, 'Crespo'),
(146, 12, 'Iribarren'),
(147, 12, 'Jiménez'),
(148, 12, 'Morán'),
(149, 12, 'Palavecino'),
(150, 12, 'Simón Planas'),
(151, 12, 'Torres'),
(152, 12, 'Urdaneta'),
(179, 13, 'Alberto Adriani'),
(180, 13, 'Andrés Bello'),
(181, 13, 'Antonio Pinto Salinas'),
(182, 13, 'Aricagua'),
(183, 13, 'Arzobispo Chacón'),
(184, 13, 'Campo Elías'),
(185, 13, 'Caracciolo Parra Olmedo'),
(186, 13, 'Cardenal Quintero'),
(187, 13, 'Guaraque'),
(188, 13, 'Julio César Salas'),
(189, 13, 'Justo Briceño'),
(190, 13, 'Libertador'),
(191, 13, 'Miranda'),
(192, 13, 'Obispo Ramos de Lora'),
(193, 13, 'Padre Noguera'),
(194, 13, 'Pueblo Llano'),
(195, 13, 'Rangel'),
(196, 13, 'Rivas Dávila'),
(197, 13, 'Santos Marquina'),
(198, 13, 'Sucre'),
(199, 13, 'Tovar'),
(200, 13, 'Tulio Febres Cordero'),
(201, 13, 'Zea'),
(223, 14, 'Acevedo'),
(224, 14, 'Andrés Bello'),
(225, 14, 'Baruta'),
(226, 14, 'Brión'),
(227, 14, 'Buroz'),
(228, 14, 'Carrizal'),
(229, 14, 'Chacao'),
(230, 14, 'Cristóbal Rojas'),
(231, 14, 'El Hatillo'),
(232, 14, 'Guaicaipuro'),
(233, 14, 'Independencia'),
(234, 14, 'Lander'),
(235, 14, 'Los Salias'),
(236, 14, 'Páez'),
(237, 14, 'Paz Castillo'),
(238, 14, 'Pedro Gual'),
(239, 14, 'Plaza'),
(240, 14, 'Simón Bolívar'),
(241, 14, 'Sucre'),
(242, 14, 'Urdaneta'),
(243, 14, 'Zamora'),
(258, 15, 'Acosta'),
(259, 15, 'Aguasay'),
(260, 15, 'Bolívar'),
(261, 15, 'Caripe'),
(262, 15, 'Cedeño'),
(263, 15, 'Ezequiel Zamora'),
(264, 15, 'Libertador'),
(265, 15, 'Maturín'),
(266, 15, 'Piar'),
(267, 15, 'Punceres'),
(268, 15, 'Santa Bárbara'),
(269, 15, 'Sotillo'),
(270, 15, 'Uracoa'),
(271, 16, 'Antolín del Campo'),
(272, 16, 'Arismendi'),
(273, 16, 'García'),
(274, 16, 'Gómez'),
(275, 16, 'Maneiro'),
(276, 16, 'Marcano'),
(277, 16, 'Mariño'),
(278, 16, 'Península de Macanao'),
(279, 16, 'Tubores'),
(280, 16, 'Villalba'),
(281, 16, 'Díaz'),
(282, 17, 'Agua Blanca'),
(283, 17, 'Araure'),
(284, 17, 'Esteller'),
(285, 17, 'Guanare'),
(286, 17, 'Guanarito'),
(287, 17, 'Monseñor José Vicente de Unda'),
(288, 17, 'Ospino'),
(289, 17, 'Páez'),
(290, 17, 'Papelón'),
(291, 17, 'San Genaro de Boconoíto'),
(292, 17, 'San Rafael de Onoto'),
(293, 17, 'Santa Rosalía'),
(294, 17, 'Sucre'),
(295, 17, 'Turén'),
(296, 18, 'Andrés Eloy Blanco'),
(297, 18, 'Andrés Mata'),
(298, 18, 'Arismendi'),
(299, 18, 'Benítez'),
(300, 18, 'Bermúdez'),
(301, 18, 'Bolívar'),
(302, 18, 'Cajigal'),
(303, 18, 'Cruz Salmerón Acosta'),
(304, 18, 'Libertador'),
(305, 18, 'Mariño'),
(306, 18, 'Mejía'),
(307, 18, 'Montes'),
(308, 18, 'Ribero'),
(309, 18, 'Sucre'),
(310, 18, 'Valdéz'),
(341, 19, 'Andrés Bello'),
(342, 19, 'Antonio Rómulo Costa'),
(343, 19, 'Ayacucho'),
(344, 19, 'Bolívar'),
(345, 19, 'Cárdenas'),
(346, 19, 'Córdoba'),
(347, 19, 'Fernández Feo'),
(348, 19, 'Francisco de Miranda'),
(349, 19, 'García de Hevia'),
(350, 19, 'Guásimos'),
(351, 19, 'Independencia'),
(352, 19, 'Jáuregui'),
(353, 19, 'José María Vargas'),
(354, 19, 'Junín'),
(355, 19, 'Libertad'),
(356, 19, 'Libertador'),
(357, 19, 'Lobatera'),
(358, 19, 'Michelena'),
(359, 19, 'Panamericano'),
(360, 19, 'Pedro María Ureña'),
(361, 19, 'Rafael Urdaneta'),
(362, 19, 'Samuel Darío Maldonado'),
(363, 19, 'San Cristóbal'),
(364, 19, 'Seboruco'),
(365, 19, 'Simón Rodríguez'),
(366, 19, 'Sucre'),
(367, 19, 'Torbes'),
(368, 19, 'Uribante'),
(369, 19, 'San Judas Tadeo'),
(370, 20, 'Andrés Bello'),
(371, 20, 'Boconó'),
(372, 20, 'Bolívar'),
(373, 20, 'Candelaria'),
(374, 20, 'Carache'),
(375, 20, 'Escuque'),
(376, 20, 'José Felipe Márquez Cañizalez'),
(377, 20, 'Juan Vicente Campos Elías'),
(378, 20, 'La Ceiba'),
(379, 20, 'Miranda'),
(380, 20, 'Monte Carmelo'),
(381, 20, 'Motatán'),
(382, 20, 'Pampán'),
(383, 20, 'Pampanito'),
(384, 20, 'Rafael Rangel'),
(385, 20, 'San Rafael de Carvajal'),
(386, 20, 'Sucre'),
(387, 20, 'Trujillo'),
(388, 20, 'Urdaneta'),
(389, 20, 'Valera'),
(390, 21, 'Vargas'),
(391, 22, 'Arístides Bastidas'),
(392, 22, 'Bolívar'),
(407, 22, 'Bruzual'),
(408, 22, 'Cocorote'),
(409, 22, 'Independencia'),
(410, 22, 'José Antonio Páez'),
(411, 22, 'La Trinidad'),
(412, 22, 'Manuel Monge'),
(413, 22, 'Nirgua'),
(414, 22, 'Peña'),
(415, 22, 'San Felipe'),
(416, 22, 'Sucre'),
(417, 22, 'Urachiche'),
(418, 22, 'José Joaquín Veroes'),
(441, 23, 'Almirante Padilla'),
(442, 23, 'Baralt'),
(443, 23, 'Cabimas'),
(444, 23, 'Catatumbo'),
(445, 23, 'Colón'),
(446, 23, 'Francisco Javier Pulgar'),
(447, 23, 'Páez'),
(448, 23, 'Jesús Enrique Losada'),
(449, 23, 'Jesús María Semprún'),
(450, 23, 'La Cañada de Urdaneta'),
(451, 23, 'Lagunillas'),
(452, 23, 'Machiques de Perijá'),
(453, 23, 'Mara'),
(454, 23, 'Maracaibo'),
(455, 23, 'Miranda'),
(456, 23, 'Rosario de Perijá'),
(457, 23, 'San Francisco'),
(458, 23, 'Santa Rita'),
(459, 23, 'Simón Bolívar'),
(460, 23, 'Sucre'),
(461, 23, 'Valmore Rodríguez'),
(462, 24, 'Libertador'),
(463, 26, 'Municipio - Afganistán'),
(464, 27, 'Municipio - Islas Gland'),
(465, 28, 'Municipio - Albania'),
(466, 29, 'Municipio - Alemania'),
(467, 30, 'Municipio - Andorra'),
(468, 31, 'Municipio - Angola'),
(469, 32, 'Municipio - Anguilla'),
(470, 33, 'Municipio - Antártida'),
(471, 34, 'Municipio - Antigua y Barbuda'),
(472, 35, 'Municipio - Antillas Holandesas'),
(473, 36, 'Municipio - Arabia Saudí'),
(474, 37, 'Municipio - Argelia'),
(475, 38, 'Municipio - Argentina'),
(476, 39, 'Municipio - Armenia'),
(477, 40, 'Municipio - Aruba'),
(478, 41, 'Municipio - Australia'),
(479, 42, 'Municipio - Austria'),
(480, 43, 'Municipio - Azerbaiyán'),
(481, 44, 'Municipio - Bahamas'),
(482, 45, 'Municipio - Bahréin'),
(483, 46, 'Municipio - Bangladesh'),
(484, 47, 'Municipio - Barbados'),
(485, 48, 'Municipio - Bielorrusia'),
(486, 49, 'Municipio - Bélgica'),
(487, 50, 'Municipio - Belice'),
(488, 51, 'Municipio - Benin'),
(489, 52, 'Municipio - Bermudas'),
(490, 53, 'Municipio - Bhután'),
(491, 54, 'Municipio - Bolivia'),
(492, 55, 'Municipio - Bosnia y Herzegovina'),
(493, 56, 'Municipio - Botsuana'),
(494, 57, 'Municipio - Isla Bouvet'),
(495, 58, 'Municipio - Brasil'),
(496, 59, 'Municipio - Brunéi'),
(497, 60, 'Municipio - Bulgaria'),
(498, 61, 'Municipio - Burkina Faso'),
(499, 62, 'Municipio - Burundi'),
(500, 63, 'Municipio - Cabo Verde'),
(501, 64, 'Municipio - Islas Caimán'),
(502, 65, 'Municipio - Camboya'),
(503, 66, 'Municipio - Camerún'),
(504, 67, 'Municipio - Canadá'),
(505, 68, 'Municipio - República Centroafricana'),
(506, 69, 'Municipio - Chad'),
(507, 70, 'Municipio - República Checa'),
(508, 71, 'Municipio - Chile'),
(509, 72, 'Municipio - China'),
(510, 73, 'Municipio - Chipre'),
(511, 74, 'Municipio - Isla de Navidad'),
(512, 75, 'Municipio - Ciudad del Vaticano'),
(513, 76, 'Municipio - Islas Cocos'),
(514, 77, 'Municipio - Colombia'),
(515, 78, 'Municipio - Comoras'),
(516, 79, 'Municipio - República Democrática del Congo'),
(517, 80, 'Municipio - Congo'),
(518, 81, 'Municipio - Islas Cook'),
(519, 82, 'Municipio - Corea del Norte'),
(520, 83, 'Municipio - Corea del Sur'),
(521, 84, 'Municipio - Costa de Marfil'),
(522, 85, 'Municipio - Costa Rica'),
(523, 86, 'Municipio - Croacia'),
(524, 87, 'Municipio - Cuba'),
(525, 88, 'Municipio - Dinamarca'),
(526, 89, 'Municipio - Dominica'),
(527, 90, 'Municipio - República Dominicana'),
(528, 91, 'Municipio - Ecuador'),
(529, 92, 'Municipio - Egipto'),
(530, 93, 'Municipio - El Salvador'),
(531, 94, 'Municipio - Emiratos Árabes Unidos'),
(532, 95, 'Municipio - Eritrea'),
(533, 96, 'Municipio - Eslovaquia'),
(534, 97, 'Municipio - Eslovenia'),
(535, 98, 'Municipio - España'),
(536, 99, 'Municipio - Islas ultramarinas de Estados Unidos'),
(537, 100, 'Municipio - Estados Unidos'),
(538, 101, 'Municipio - Estonia'),
(539, 102, 'Municipio - Etiopía'),
(540, 103, 'Municipio - Islas Feroe'),
(541, 104, 'Municipio - Filipinas'),
(542, 105, 'Municipio - Finlandia'),
(543, 106, 'Municipio - Fiyi'),
(544, 107, 'Municipio - Francia'),
(545, 108, 'Municipio - Gabón'),
(546, 109, 'Municipio - Gambia'),
(547, 110, 'Municipio - Georgia'),
(548, 111, 'Municipio - Islas Georgias del Sur y Sandwich del Sur'),
(549, 112, 'Municipio - Ghana'),
(550, 113, 'Municipio - Gibraltar'),
(551, 114, 'Municipio - Granada'),
(552, 115, 'Municipio - Grecia'),
(553, 116, 'Municipio - Groenlandia'),
(554, 117, 'Municipio - Guadalupe'),
(555, 118, 'Municipio - Guam'),
(556, 119, 'Municipio - Guatemala'),
(557, 120, 'Municipio - Guayana Francesa'),
(558, 121, 'Municipio - Guinea'),
(559, 122, 'Municipio - Guinea Ecuatorial'),
(560, 123, 'Municipio - Guinea-Bissau'),
(561, 124, 'Municipio - Guyana'),
(562, 125, 'Municipio - Haití'),
(563, 126, 'Municipio - Islas Heard y McDonald'),
(564, 127, 'Municipio - Honduras'),
(565, 128, 'Municipio - Hong Kong'),
(566, 129, 'Municipio - Hungría'),
(567, 130, 'Municipio - India'),
(568, 131, 'Municipio - Indonesia'),
(569, 132, 'Municipio - Irán'),
(570, 133, 'Municipio - Iraq'),
(571, 134, 'Municipio - Irlanda'),
(572, 135, 'Municipio - Islandia'),
(573, 136, 'Municipio - Israel'),
(574, 137, 'Municipio - Italia'),
(575, 138, 'Municipio - Jamaica'),
(576, 139, 'Municipio - Japón'),
(577, 140, 'Municipio - Jordania'),
(578, 141, 'Municipio - Kazajstán'),
(579, 142, 'Municipio - Kenia'),
(580, 143, 'Municipio - Kirguistán'),
(581, 144, 'Municipio - Kiribati'),
(582, 145, 'Municipio - Kuwait'),
(583, 146, 'Municipio - Laos'),
(584, 147, 'Municipio - Lesotho'),
(585, 148, 'Municipio - Letonia'),
(586, 149, 'Municipio - Líbano'),
(587, 150, 'Municipio - Liberia'),
(588, 151, 'Municipio - Libia'),
(589, 152, 'Municipio - Liechtenstein'),
(590, 153, 'Municipio - Lituania'),
(591, 154, 'Municipio - Luxemburgo'),
(592, 155, 'Municipio - Macao'),
(593, 156, 'Municipio - ARY Macedonia'),
(594, 157, 'Municipio - Madagascar'),
(595, 158, 'Municipio - Malasia'),
(596, 159, 'Municipio - Malawi'),
(597, 160, 'Municipio - Maldivas'),
(598, 161, 'Municipio - Malí'),
(599, 162, 'Municipio - Malta'),
(600, 163, 'Municipio - Islas Malvinas'),
(601, 164, 'Municipio - Islas Marianas del Norte'),
(602, 165, 'Municipio - Marruecos'),
(603, 166, 'Municipio - Islas Marshall'),
(604, 167, 'Municipio - Martinica'),
(605, 168, 'Municipio - Mauricio'),
(606, 169, 'Municipio - Mauritania'),
(607, 170, 'Municipio - Mayotte'),
(608, 171, 'Municipio - México'),
(609, 172, 'Municipio - Micronesia'),
(610, 173, 'Municipio - Moldavia'),
(611, 174, 'Municipio - Mónaco'),
(612, 175, 'Municipio - Mongolia'),
(613, 176, 'Municipio - Montserrat'),
(614, 177, 'Municipio - Mozambique'),
(615, 178, 'Municipio - Myanmar'),
(616, 179, 'Municipio - Namibia'),
(617, 180, 'Municipio - Nauru'),
(618, 181, 'Municipio - Nepal'),
(619, 182, 'Municipio - Nicaragua'),
(620, 183, 'Municipio - Níger'),
(621, 184, 'Municipio - Nigeria'),
(622, 185, 'Municipio - Niue'),
(623, 186, 'Municipio - Isla Norfolk'),
(624, 187, 'Municipio - Noruega'),
(625, 188, 'Municipio - Nueva Caledonia'),
(626, 189, 'Municipio - Nueva Zelanda'),
(627, 190, 'Municipio - Omán'),
(628, 191, 'Municipio - Países Bajos'),
(629, 192, 'Municipio - Pakistán'),
(630, 193, 'Municipio - Palau'),
(631, 194, 'Municipio - Palestina'),
(632, 195, 'Municipio - Panamá'),
(633, 196, 'Municipio - Papúa Nueva Guinea'),
(634, 197, 'Municipio - Paraguay'),
(635, 198, 'Municipio - Perú'),
(636, 199, 'Municipio - Islas Pitcairn'),
(637, 200, 'Municipio - Polinesia Francesa'),
(638, 201, 'Municipio - Polonia'),
(639, 202, 'Municipio - Portugal'),
(640, 203, 'Municipio - Puerto Rico'),
(641, 204, 'Municipio - Qatar'),
(642, 205, 'Municipio - Reino Unido'),
(643, 206, 'Municipio - Reunión'),
(644, 207, 'Municipio - Ruanda'),
(645, 208, 'Municipio - Rumania'),
(646, 209, 'Municipio - Rusia'),
(647, 210, 'Municipio - Sahara Occidental'),
(648, 211, 'Municipio - Islas Salomón'),
(649, 212, 'Municipio - Samoa'),
(650, 213, 'Municipio - Samoa Americana'),
(651, 214, 'Municipio - San Cristóbal y Nevis'),
(652, 215, 'Municipio - San Marino'),
(653, 216, 'Municipio - San Pedro y Miquelón'),
(654, 217, 'Municipio - San Vicente y las Granadinas'),
(655, 218, 'Municipio - Santa Helena'),
(656, 219, 'Municipio - Santa Lucía'),
(657, 220, 'Municipio - Santo Tomé y Príncipe'),
(658, 221, 'Municipio - Senegal'),
(659, 222, 'Municipio - Serbia y Montenegro'),
(660, 223, 'Municipio - Seychelles'),
(661, 224, 'Municipio - Sierra Leona'),
(662, 225, 'Municipio - Singapur'),
(663, 226, 'Municipio - Siria'),
(664, 227, 'Municipio - Somalia'),
(665, 228, 'Municipio - Sri Lanka'),
(666, 229, 'Municipio - Suazilandia'),
(667, 230, 'Municipio - Sudáfrica'),
(668, 231, 'Municipio - Sudán'),
(669, 232, 'Municipio - Suecia'),
(670, 233, 'Municipio - Suiza'),
(671, 234, 'Municipio - Surinam'),
(672, 235, 'Municipio - Svalbard y Jan Mayen'),
(673, 236, 'Municipio - Tailandia'),
(674, 237, 'Municipio - Taiwán'),
(675, 238, 'Municipio - Tanzania'),
(676, 239, 'Municipio - Tayikistán'),
(677, 240, 'Municipio - Territorio Británico del Océano Índico'),
(678, 241, 'Municipio - Territorios Australes Franceses'),
(679, 242, 'Municipio - Timor Oriental'),
(680, 243, 'Municipio - Togo'),
(681, 244, 'Municipio - Tokelau'),
(682, 245, 'Municipio - Tonga'),
(683, 246, 'Municipio - Trinidad y Tobago'),
(684, 247, 'Municipio - Túnez'),
(685, 248, 'Municipio - Islas Turcas y Caicos'),
(686, 249, 'Municipio - Turkmenistán'),
(687, 250, 'Municipio - Turquía'),
(688, 251, 'Municipio - Tuvalu'),
(689, 252, 'Municipio - Ucrania'),
(690, 253, 'Municipio - Uganda'),
(691, 254, 'Municipio - Uruguay'),
(692, 255, 'Municipio - Uzbekistán'),
(693, 256, 'Municipio - Vanuatu'),
(694, 257, 'Municipio - Vietnam'),
(695, 258, 'Municipio - Islas Vírgenes Británicas'),
(696, 259, 'Municipio - Islas Vírgenes de los Estados Unidos'),
(697, 260, 'Municipio - Wallis y Futuna'),
(698, 261, 'Municipio - Yemen'),
(699, 262, 'Municipio - Yibuti'),
(700, 263, 'Municipio - Zambia'),
(701, 264, 'Municipio - Zimbabue');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivel`
--

CREATE TABLE `nivel` (
  `cod_nivel` int(11) NOT NULL,
  `nom_nivel` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `nivel`
--

INSERT INTO `nivel` (`cod_nivel`, `nom_nivel`, `descripcion`) VALUES
(1, 'ADMINISTRADOR CENTRAL', ''),
(2, 'ADMINISTRADOR', 'Usuarios para directores, subdirectores.'),
(3, 'SECRETARIA', 'Usuarios para secretarias'),
(4, 'DOCENTE', 'Usuarios para docentes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivel_metodo`
--

CREATE TABLE `nivel_metodo` (
  `codigo` char(10) COLLATE utf8_spanish_ci NOT NULL,
  `cod_nivel` int(11) NOT NULL,
  `cod_servicio` int(11) NOT NULL,
  `inc` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `modf` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `elm` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0',
  `cons` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `nivel_metodo`
--

INSERT INTO `nivel_metodo` (`codigo`, `cod_nivel`, `cod_servicio`, `inc`, `modf`, `elm`, `cons`) VALUES
('2-12', 2, 12, '1', '1', '0', '1'),
('2-13', 2, 13, '1', '1', '0', '1'),
('2-14', 2, 14, '1', '1', '0', '1'),
('2-15', 2, 15, '1', '1', '0', '1'),
('2-18', 2, 18, '0', '0', '0', '0'),
('2-19', 2, 19, '0', '0', '0', '0'),
('2-20', 2, 20, '0', '0', '0', '0'),
('2-21', 2, 21, '0', '0', '0', '0'),
('2-22', 2, 22, '1', '1', '1', '0'),
('2-23', 2, 23, '0', '1', '0', '0'),
('2-24', 2, 24, '1', '1', '0', '1'),
('2-25', 2, 25, '1', '1', '1', '1'),
('2-26', 2, 26, '1', '1', '1', '1'),
('2-27', 2, 27, '0', '1', '0', '0'),
('2-28', 2, 28, '1', '1', '1', '1'),
('2-29', 2, 29, '1', '1', '1', '1'),
('2-30', 2, 30, '1', '1', '1', '0'),
('2-31', 2, 31, '0', '0', '0', '0'),
('2-32', 2, 32, '1', '1', '0', '1'),
('2-33', 2, 33, '1', '1', '1', '1'),
('2-35', 2, 35, '0', '0', '0', '0'),
('2-36', 2, 36, '1', '1', '0', '1'),
('2-37', 2, 37, '1', '1', '0', '1'),
('2-41', 2, 41, '1', '1', '1', '1'),
('2-43', 2, 43, '1', '1', '1', '1'),
('2-46', 2, 46, '1', '1', '1', '1'),
('3-12', 3, 12, '1', '1', '0', '1'),
('3-13', 3, 13, '1', '1', '0', '1'),
('3-14', 3, 14, '1', '1', '0', '1'),
('3-15', 3, 15, '1', '1', '0', '1'),
('3-18', 3, 18, '0', '0', '0', '0'),
('3-19', 3, 19, '0', '0', '0', '0'),
('3-20', 3, 20, '0', '0', '0', '0'),
('3-21', 3, 21, '0', '0', '0', '0'),
('3-22', 3, 22, '0', '0', '0', '1'),
('3-24', 3, 24, '0', '0', '0', '1'),
('3-25', 3, 25, '1', '1', '1', '1'),
('3-26', 3, 26, '1', '1', '1', '1'),
('3-28', 3, 28, '1', '1', '1', '1'),
('3-29', 3, 29, '1', '1', '1', '1'),
('3-30', 3, 30, '1', '1', '1', '0'),
('3-31', 3, 31, '0', '0', '0', '0'),
('3-32', 3, 32, '1', '1', '0', '1'),
('3-33', 3, 33, '1', '1', '1', '1'),
('3-35', 3, 35, '0', '0', '0', '0'),
('3-46', 3, 46, '1', '1', '0', '1'),
('4-12', 4, 12, '1', '1', '0', '1'),
('4-13', 4, 13, '1', '1', '0', '1'),
('4-14', 4, 14, '1', '1', '0', '1'),
('4-15', 4, 15, '1', '1', '0', '1'),
('4-18', 4, 18, '0', '0', '0', '0'),
('4-19', 4, 19, '0', '0', '0', '0'),
('4-20', 4, 20, '0', '0', '0', '0'),
('4-21', 4, 21, '0', '0', '0', '0'),
('4-24', 4, 24, '0', '0', '0', '1'),
('4-25', 4, 25, '1', '1', '1', '1'),
('4-31', 4, 31, '0', '0', '0', '0'),
('4-32', 4, 32, '1', '1', '0', '0'),
('4-46', 4, 46, '1', '1', '0', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_final`
--

CREATE TABLE `nota_final` (
  `codigo` int(11) NOT NULL,
  `cod_insc` int(11) NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `recomendacion` text COLLATE utf8_spanish_ci NOT NULL,
  `literal` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `promovido` char(1) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_indicador`
--

CREATE TABLE `nota_indicador` (
  `codigo` int(12) NOT NULL,
  `cod_insc` int(11) NOT NULL,
  `cod_ind` int(11) NOT NULL,
  `nota` char(1) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_lapso`
--

CREATE TABLE `nota_lapso` (
  `codigo` int(11) NOT NULL,
  `cod_insc` int(11) NOT NULL,
  `cod_lapso` int(11) NOT NULL,
  `nota` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `pm` char(1) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ocupacion`
--

CREATE TABLE `ocupacion` (
  `cod_ocup` int(11) NOT NULL,
  `nom_ocup` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `ocupacion`
--

INSERT INTO `ocupacion` (`cod_ocup`, `nom_ocup`) VALUES
(1, 'Ninguna'),
(2, 'Prueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paises`
--

CREATE TABLE `paises` (
  `cod_pais` int(11) NOT NULL,
  `iso` char(3) COLLATE utf8_spanish_ci NOT NULL,
  `nom_pais` varchar(100) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `paises`
--

INSERT INTO `paises` (`cod_pais`, `iso`, `nom_pais`) VALUES
(1, 'AF', 'Afganistán'),
(2, 'AX', 'Islas Gland'),
(3, 'AL', 'Albania'),
(4, 'DE', 'Alemania'),
(5, 'AD', 'Andorra'),
(6, 'AO', 'Angola'),
(7, 'AI', 'Anguilla'),
(8, 'AQ', 'Antártida'),
(9, 'AG', 'Antigua y Barbuda'),
(10, 'AN', 'Antillas Holandesas'),
(11, 'SA', 'Arabia Saudí'),
(12, 'DZ', 'Argelia'),
(13, 'AR', 'Argentina'),
(14, 'AM', 'Armenia'),
(15, 'AW', 'Aruba'),
(16, 'AU', 'Australia'),
(17, 'AT', 'Austria'),
(18, 'AZ', 'Azerbaiyán'),
(19, 'BS', 'Bahamas'),
(20, 'BH', 'Bahréin'),
(21, 'BD', 'Bangladesh'),
(22, 'BB', 'Barbados'),
(23, 'BY', 'Bielorrusia'),
(24, 'BE', 'Bélgica'),
(25, 'BZ', 'Belice'),
(26, 'BJ', 'Benin'),
(27, 'BM', 'Bermudas'),
(28, 'BT', 'Bhután'),
(29, 'BO', 'Bolivia'),
(30, 'BA', 'Bosnia y Herzegovina'),
(31, 'BW', 'Botsuana'),
(32, 'BV', 'Isla Bouvet'),
(33, 'BR', 'Brasil'),
(34, 'BN', 'Brunéi'),
(35, 'BG', 'Bulgaria'),
(36, 'BF', 'Burkina Faso'),
(37, 'BI', 'Burundi'),
(38, 'CV', 'Cabo Verde'),
(39, 'KY', 'Islas Caimán'),
(40, 'KH', 'Camboya'),
(41, 'CM', 'Camerún'),
(42, 'CA', 'Canadá'),
(43, 'CF', 'República Centroafricana'),
(44, 'TD', 'Chad'),
(45, 'CZ', 'República Checa'),
(46, 'CL', 'Chile'),
(47, 'CN', 'China'),
(48, 'CY', 'Chipre'),
(49, 'CX', 'Isla de Navidad'),
(50, 'VA', 'Ciudad del Vaticano'),
(51, 'CC', 'Islas Cocos'),
(52, 'CO', 'Colombia'),
(53, 'KM', 'Comoras'),
(54, 'CD', 'República Democrática del Congo'),
(55, 'CG', 'Congo'),
(56, 'CK', 'Islas Cook'),
(57, 'KP', 'Corea del Norte'),
(58, 'KR', 'Corea del Sur'),
(59, 'CI', 'Costa de Marfil'),
(60, 'CR', 'Costa Rica'),
(61, 'HR', 'Croacia'),
(62, 'CU', 'Cuba'),
(63, 'DK', 'Dinamarca'),
(64, 'DM', 'Dominica'),
(65, 'DO', 'República Dominicana'),
(66, 'EC', 'Ecuador'),
(67, 'EG', 'Egipto'),
(68, 'SV', 'El Salvador'),
(69, 'AE', 'Emiratos Árabes Unidos'),
(70, 'ER', 'Eritrea'),
(71, 'SK', 'Eslovaquia'),
(72, 'SI', 'Eslovenia'),
(73, 'ES', 'España'),
(74, 'UM', 'Islas ultramarinas de Estados Unidos'),
(75, 'US', 'Estados Unidos'),
(76, 'EE', 'Estonia'),
(77, 'ET', 'Etiopía'),
(78, 'FO', 'Islas Feroe'),
(79, 'PH', 'Filipinas'),
(80, 'FI', 'Finlandia'),
(81, 'FJ', 'Fiyi'),
(82, 'FR', 'Francia'),
(83, 'GA', 'Gabón'),
(84, 'GM', 'Gambia'),
(85, 'GE', 'Georgia'),
(86, 'GS', 'Islas Georgias del Sur y Sandwich del Sur'),
(87, 'GH', 'Ghana'),
(88, 'GI', 'Gibraltar'),
(89, 'GD', 'Granada'),
(90, 'GR', 'Grecia'),
(91, 'GL', 'Groenlandia'),
(92, 'GP', 'Guadalupe'),
(93, 'GU', 'Guam'),
(94, 'GT', 'Guatemala'),
(95, 'GF', 'Guayana Francesa'),
(96, 'GN', 'Guinea'),
(97, 'GQ', 'Guinea Ecuatorial'),
(98, 'GW', 'Guinea-Bissau'),
(99, 'GY', 'Guyana'),
(100, 'HT', 'Haití'),
(101, 'HM', 'Islas Heard y McDonald'),
(102, 'HN', 'Honduras'),
(103, 'HK', 'Hong Kong'),
(104, 'HU', 'Hungría'),
(105, 'IN', 'India'),
(106, 'ID', 'Indonesia'),
(107, 'IR', 'Irán'),
(108, 'IQ', 'Iraq'),
(109, 'IE', 'Irlanda'),
(110, 'IS', 'Islandia'),
(111, 'IL', 'Israel'),
(112, 'IT', 'Italia'),
(113, 'JM', 'Jamaica'),
(114, 'JP', 'Japón'),
(115, 'JO', 'Jordania'),
(116, 'KZ', 'Kazajstán'),
(117, 'KE', 'Kenia'),
(118, 'KG', 'Kirguistán'),
(119, 'KI', 'Kiribati'),
(120, 'KW', 'Kuwait'),
(121, 'LA', 'Laos'),
(122, 'LS', 'Lesotho'),
(123, 'LV', 'Letonia'),
(124, 'LB', 'Líbano'),
(125, 'LR', 'Liberia'),
(126, 'LY', 'Libia'),
(127, 'LI', 'Liechtenstein'),
(128, 'LT', 'Lituania'),
(129, 'LU', 'Luxemburgo'),
(130, 'MO', 'Macao'),
(131, 'MK', 'ARY Macedonia'),
(132, 'MG', 'Madagascar'),
(133, 'MY', 'Malasia'),
(134, 'MW', 'Malawi'),
(135, 'MV', 'Maldivas'),
(136, 'ML', 'Malí'),
(137, 'MT', 'Malta'),
(138, 'FK', 'Islas Malvinas'),
(139, 'MP', 'Islas Marianas del Norte'),
(140, 'MA', 'Marruecos'),
(141, 'MH', 'Islas Marshall'),
(142, 'MQ', 'Martinica'),
(143, 'MU', 'Mauricio'),
(144, 'MR', 'Mauritania'),
(145, 'YT', 'Mayotte'),
(146, 'MX', 'México'),
(147, 'FM', 'Micronesia'),
(148, 'MD', 'Moldavia'),
(149, 'MC', 'Mónaco'),
(150, 'MN', 'Mongolia'),
(151, 'MS', 'Montserrat'),
(152, 'MZ', 'Mozambique'),
(153, 'MM', 'Myanmar'),
(154, 'NA', 'Namibia'),
(155, 'NR', 'Nauru'),
(156, 'NP', 'Nepal'),
(157, 'NI', 'Nicaragua'),
(158, 'NE', 'Níger'),
(159, 'NG', 'Nigeria'),
(160, 'NU', 'Niue'),
(161, 'NF', 'Isla Norfolk'),
(162, 'NO', 'Noruega'),
(163, 'NC', 'Nueva Caledonia'),
(164, 'NZ', 'Nueva Zelanda'),
(165, 'OM', 'Omán'),
(166, 'NL', 'Países Bajos'),
(167, 'PK', 'Pakistán'),
(168, 'PW', 'Palau'),
(169, 'PS', 'Palestina'),
(170, 'PA', 'Panamá'),
(171, 'PG', 'Papúa Nueva Guinea'),
(172, 'PY', 'Paraguay'),
(173, 'PE', 'Perú'),
(174, 'PN', 'Islas Pitcairn'),
(175, 'PF', 'Polinesia Francesa'),
(176, 'PL', 'Polonia'),
(177, 'PT', 'Portugal'),
(178, 'PR', 'Puerto Rico'),
(179, 'QA', 'Qatar'),
(180, 'GB', 'Reino Unido'),
(181, 'RE', 'Reunión'),
(182, 'RW', 'Ruanda'),
(183, 'RO', 'Rumania'),
(184, 'RU', 'Rusia'),
(185, 'EH', 'Sahara Occidental'),
(186, 'SB', 'Islas Salomón'),
(187, 'WS', 'Samoa'),
(188, 'AS', 'Samoa Americana'),
(189, 'KN', 'San Cristóbal y Nevis'),
(190, 'SM', 'San Marino'),
(191, 'PM', 'San Pedro y Miquelón'),
(192, 'VC', 'San Vicente y las Granadinas'),
(193, 'SH', 'Santa Helena'),
(194, 'LC', 'Santa Lucía'),
(195, 'ST', 'Santo Tomé y Príncipe'),
(196, 'SN', 'Senegal'),
(197, 'CS', 'Serbia y Montenegro'),
(198, 'SC', 'Seychelles'),
(199, 'SL', 'Sierra Leona'),
(200, 'SG', 'Singapur'),
(201, 'SY', 'Siria'),
(202, 'SO', 'Somalia'),
(203, 'LK', 'Sri Lanka'),
(204, 'SZ', 'Suazilandia'),
(205, 'ZA', 'Sudáfrica'),
(206, 'SD', 'Sudán'),
(207, 'SE', 'Suecia'),
(208, 'CH', 'Suiza'),
(209, 'SR', 'Surinam'),
(210, 'SJ', 'Svalbard y Jan Mayen'),
(211, 'TH', 'Tailandia'),
(212, 'TW', 'Taiwán'),
(213, 'TZ', 'Tanzania'),
(214, 'TJ', 'Tayikistán'),
(215, 'IO', 'Territorio Británico del Océano Índico'),
(216, 'TF', 'Territorios Australes Franceses'),
(217, 'TL', 'Timor Oriental'),
(218, 'TG', 'Togo'),
(219, 'TK', 'Tokelau'),
(220, 'TO', 'Tonga'),
(221, 'TT', 'Trinidad y Tobago'),
(222, 'TN', 'Túnez'),
(223, 'TC', 'Islas Turcas y Caicos'),
(224, 'TM', 'Turkmenistán'),
(225, 'TR', 'Turquía'),
(226, 'TV', 'Tuvalu'),
(227, 'UA', 'Ucrania'),
(228, 'UG', 'Uganda'),
(229, 'UY', 'Uruguay'),
(230, 'UZ', 'Uzbekistán'),
(231, 'VU', 'Vanuatu'),
(232, 'VE', 'Venezuela'),
(233, 'VN', 'Vietnam'),
(234, 'VG', 'Islas Vírgenes Británicas'),
(235, 'VI', 'Islas Vírgenes de los Estados Unidos'),
(236, 'WF', 'Wallis y Futuna'),
(237, 'YE', 'Yemen'),
(238, 'DJ', 'Yibuti'),
(239, 'ZM', 'Zambia'),
(240, 'ZW', 'Zimbabue');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parroquia`
--

CREATE TABLE `parroquia` (
  `cod_parr` int(11) NOT NULL,
  `municipio` int(11) NOT NULL,
  `nom_parr` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `parroquia`
--

INSERT INTO `parroquia` (`cod_parr`, `municipio`, `nom_parr`) VALUES
(1, 1, 'Alto Orinoco'),
(2, 1, 'Huachamacare Acanaña'),
(3, 1, 'Marawaka Toky Shamanaña'),
(4, 1, 'Mavaka Mavaka'),
(5, 1, 'Sierra Parima Parimabé'),
(6, 2, 'Ucata Laja Lisa'),
(7, 2, 'Yapacana Macuruco'),
(8, 2, 'Caname Guarinuma'),
(9, 3, 'Fernando Girón Tovar'),
(10, 3, 'Luis Alberto Gómez'),
(11, 3, 'Pahueña Limón de Parhueña'),
(12, 3, 'Platanillal Platanillal'),
(13, 4, 'Samariapo'),
(14, 4, 'Sipapo'),
(15, 4, 'Munduapo'),
(16, 4, 'Guayapo'),
(17, 5, 'Alto Ventuari'),
(18, 5, 'Medio Ventuari'),
(19, 5, 'Bajo Ventuari'),
(20, 6, 'Victorino'),
(21, 6, 'Comunidad'),
(22, 7, 'Casiquiare'),
(23, 7, 'Cocuy'),
(24, 7, 'San Carlos de Río Negro'),
(25, 7, 'Solano'),
(26, 8, 'Anaco'),
(27, 8, 'San Joaquín'),
(28, 9, 'Cachipo'),
(29, 9, 'Aragua de Barcelona'),
(30, 11, 'Lechería'),
(31, 11, 'El Morro'),
(32, 12, 'Puerto Píritu'),
(33, 12, 'San Miguel'),
(34, 12, 'Sucre'),
(35, 13, 'Valle de Guanape'),
(36, 13, 'Santa Bárbara'),
(37, 14, 'El Chaparro'),
(38, 14, 'Tomás Alfaro'),
(39, 14, 'Calatrava'),
(40, 15, 'Guanta'),
(41, 15, 'Chorrerón'),
(42, 16, 'Mamo'),
(43, 16, 'Soledad'),
(44, 17, 'Mapire'),
(45, 17, 'Piar'),
(46, 17, 'Santa Clara'),
(47, 17, 'San Diego de Cabrutica'),
(48, 17, 'Uverito'),
(49, 17, 'Zuata'),
(50, 18, 'Puerto La Cruz'),
(51, 18, 'Pozuelos'),
(52, 19, 'Onoto'),
(53, 19, 'San Pablo'),
(54, 20, 'San Mateo'),
(55, 20, 'El Carito'),
(56, 20, 'Santa Inés'),
(57, 20, 'La Romereña'),
(58, 21, 'Atapirire'),
(59, 21, 'Boca del Pao'),
(60, 21, 'El Pao'),
(61, 21, 'Pariaguán'),
(62, 22, 'Cantaura'),
(63, 22, 'Libertador'),
(64, 22, 'Santa Rosa'),
(65, 22, 'Urica'),
(66, 23, 'Píritu'),
(67, 23, 'San Francisco'),
(68, 24, 'San José de Guanipa'),
(69, 25, 'Boca de Uchire'),
(70, 25, 'Boca de Chávez'),
(71, 26, 'Pueblo Nuevo'),
(72, 26, 'Santa Ana'),
(73, 27, 'Bergatín'),
(74, 27, 'Caigua'),
(75, 27, 'El Carmen'),
(76, 27, 'El Pilar'),
(77, 27, 'Naricual'),
(78, 27, 'San Crsitóbal'),
(79, 28, 'Edmundo Barrios'),
(80, 28, 'Miguel Otero Silva'),
(81, 29, 'Achaguas'),
(82, 29, 'Apurito'),
(83, 29, 'El Yagual'),
(84, 29, 'Guachara'),
(85, 29, 'Mucuritas'),
(86, 29, 'Queseras del medio'),
(87, 30, 'Biruaca'),
(88, 31, 'Bruzual'),
(89, 31, 'Mantecal'),
(90, 31, 'Quintero'),
(91, 31, 'Rincón Hondo'),
(92, 31, 'San Vicente'),
(93, 32, 'Guasdualito'),
(94, 32, 'Aramendi'),
(95, 32, 'El Amparo'),
(96, 32, 'San Camilo'),
(97, 32, 'Urdaneta'),
(98, 33, 'San Juan de Payara'),
(99, 33, 'Codazzi'),
(100, 33, 'Cunaviche'),
(101, 34, 'Elorza'),
(102, 34, 'La Trinidad'),
(103, 35, 'San Fernando'),
(104, 35, 'El Recreo'),
(105, 35, 'Peñalver'),
(106, 35, 'San Rafael de Atamaica'),
(107, 36, 'Pedro José Ovalles'),
(108, 36, 'Joaquín Crespo'),
(109, 36, 'José Casanova Godoy'),
(110, 36, 'Madre María de San José'),
(111, 36, 'Andrés Eloy Blanco'),
(112, 36, 'Los Tacarigua'),
(113, 36, 'Las Delicias'),
(114, 36, 'Choroní'),
(115, 37, 'Bolívar'),
(116, 38, 'Camatagua'),
(117, 38, 'Carmen de Cura'),
(118, 39, 'Santa Rita'),
(119, 39, 'Francisco de Miranda'),
(120, 39, 'Moseñor Feliciano González'),
(121, 40, 'Santa Cruz'),
(122, 41, 'José Félix Ribas'),
(123, 41, 'Castor Nieves Ríos'),
(124, 41, 'Las Guacamayas'),
(125, 41, 'Pao de Zárate'),
(126, 41, 'Zuata'),
(127, 42, 'José Rafael Revenga'),
(128, 43, 'Palo Negro'),
(129, 43, 'San Martín de Porres'),
(130, 44, 'El Limón'),
(131, 44, 'Caña de Azúcar'),
(132, 45, 'Ocumare de la Costa'),
(133, 46, 'San Casimiro'),
(134, 46, 'Güiripa'),
(135, 46, 'Ollas de Caramacate'),
(136, 46, 'Valle Morín'),
(137, 47, 'San Sebastían'),
(138, 48, 'Turmero'),
(139, 48, 'Arevalo Aponte'),
(140, 48, 'Chuao'),
(141, 48, 'Samán de Güere'),
(142, 48, 'Alfredo Pacheco Miranda'),
(143, 49, 'Santos Michelena'),
(144, 49, 'Tiara'),
(145, 50, 'Cagua'),
(146, 50, 'Bella Vista'),
(147, 51, 'Tovar'),
(148, 52, 'Urdaneta'),
(149, 52, 'Las Peñitas'),
(150, 52, 'San Francisco de Cara'),
(151, 52, 'Taguay'),
(152, 53, 'Zamora'),
(153, 53, 'Magdaleno'),
(154, 53, 'San Francisco de Asís'),
(155, 53, 'Valles de Tucutunemo'),
(156, 53, 'Augusto Mijares'),
(157, 54, 'Sabaneta'),
(158, 54, 'Juan Antonio Rodríguez Domínguez'),
(159, 55, 'El Cantón'),
(160, 55, 'Santa Cruz de Guacas'),
(161, 55, 'Puerto Vivas'),
(162, 56, 'Ticoporo'),
(163, 56, 'Nicolás Pulido'),
(164, 56, 'Andrés Bello'),
(165, 57, 'Arismendi'),
(166, 57, 'Guadarrama'),
(167, 57, 'La Unión'),
(168, 57, 'San Antonio'),
(169, 58, 'Barinas'),
(170, 58, 'Alberto Arvelo Larriva'),
(171, 58, 'San Silvestre'),
(172, 58, 'Santa Inés'),
(173, 58, 'Santa Lucía'),
(174, 58, 'Torumos'),
(175, 58, 'El Carmen'),
(176, 58, 'Rómulo Betancourt'),
(177, 58, 'Corazón de Jesús'),
(178, 58, 'Ramón Ignacio Méndez'),
(179, 58, 'Alto Barinas'),
(180, 58, 'Manuel Palacio Fajardo'),
(181, 58, 'Juan Antonio Rodríguez Domínguez'),
(182, 58, 'Dominga Ortiz de Páez'),
(183, 59, 'Barinitas'),
(184, 59, 'Altamira de Cáceres'),
(185, 59, 'Calderas'),
(186, 60, 'Barrancas'),
(187, 60, 'El Socorro'),
(188, 60, 'Mazparrito'),
(189, 61, 'Santa Bárbara'),
(190, 61, 'Pedro Briceño Méndez'),
(191, 61, 'Ramón Ignacio Méndez'),
(192, 61, 'José Ignacio del Pumar'),
(193, 62, 'Obispos'),
(194, 62, 'Guasimitos'),
(195, 62, 'El Real'),
(196, 62, 'La Luz'),
(197, 63, 'Ciudad Bolívia'),
(198, 63, 'José Ignacio Briceño'),
(199, 63, 'José Félix Ribas'),
(200, 63, 'Páez'),
(201, 64, 'Libertad'),
(202, 64, 'Dolores'),
(203, 64, 'Santa Rosa'),
(204, 64, 'Palacio Fajardo'),
(205, 65, 'Ciudad de Nutrias'),
(206, 65, 'El Regalo'),
(207, 65, 'Puerto Nutrias'),
(208, 65, 'Santa Catalina'),
(209, 66, 'Cachamay'),
(210, 66, 'Chirica'),
(211, 66, 'Dalla Costa'),
(212, 66, 'Once de Abril'),
(213, 66, 'Simón Bolívar'),
(214, 66, 'Unare'),
(215, 66, 'Universidad'),
(216, 66, 'Vista al Sol'),
(217, 66, 'Pozo Verde'),
(218, 66, 'Yocoima'),
(219, 66, '5 de Julio'),
(220, 67, 'Cedeño'),
(221, 67, 'Altagracia'),
(222, 67, 'Ascensión Farreras'),
(223, 67, 'Guaniamo'),
(224, 67, 'La Urbana'),
(225, 67, 'Pijiguaos'),
(226, 68, 'El Callao'),
(227, 69, 'Gran Sabana'),
(228, 69, 'Ikabarú'),
(229, 70, 'Catedral'),
(230, 70, 'Zea'),
(231, 70, 'Orinoco'),
(232, 70, 'José Antonio Páez'),
(233, 70, 'Marhuanta'),
(234, 70, 'Agua Salada'),
(235, 70, 'Vista Hermosa'),
(236, 70, 'La Sabanita'),
(237, 70, 'Panapana'),
(238, 71, 'Andrés Eloy Blanco'),
(239, 71, 'Pedro Cova'),
(240, 72, 'Raúl Leoni'),
(241, 72, 'Barceloneta'),
(242, 72, 'Santa Bárbara'),
(243, 72, 'San Francisco'),
(244, 73, 'Roscio'),
(245, 73, 'Salóm'),
(246, 74, 'Sifontes'),
(247, 74, 'Dalla Costa'),
(248, 74, 'San Isidro'),
(249, 75, 'Sucre'),
(250, 75, 'Aripao'),
(251, 75, 'Guarataro'),
(252, 75, 'Las Majadas'),
(253, 75, 'Moitaco'),
(254, 76, 'Padre Pedro Chien'),
(255, 76, 'Río Grande'),
(256, 77, 'Bejuma'),
(257, 77, 'Canoabo'),
(258, 77, 'Simón Bolívar'),
(259, 78, 'Güigüe'),
(260, 78, 'Carabobo'),
(261, 78, 'Tacarigua'),
(262, 79, 'Mariara'),
(263, 79, 'Aguas Calientes'),
(264, 80, 'Ciudad Alianza'),
(265, 80, 'Guacara'),
(266, 80, 'Yagua'),
(267, 81, 'Morón'),
(268, 81, 'Yagua'),
(269, 82, 'Tocuyito'),
(270, 82, 'Independencia'),
(271, 83, 'Los Guayos'),
(272, 84, 'Miranda'),
(273, 85, 'Montalbán'),
(274, 86, 'Naguanagua'),
(275, 87, 'Bartolomé Salóm'),
(276, 87, 'Democracia'),
(277, 87, 'Fraternidad'),
(278, 87, 'Goaigoaza'),
(279, 87, 'Juan José Flores'),
(280, 87, 'Unión'),
(281, 87, 'Borburata'),
(282, 87, 'Patanemo'),
(283, 88, 'San Diego'),
(284, 89, 'San Joaquín'),
(285, 90, 'Candelaria'),
(286, 90, 'Catedral'),
(287, 90, 'El Socorro'),
(288, 90, 'Miguel Peña'),
(289, 90, 'Rafael Urdaneta'),
(290, 90, 'San Blas'),
(291, 90, 'San José'),
(292, 90, 'Santa Rosa'),
(293, 90, 'Negro Primero'),
(294, 91, 'Cojedes'),
(295, 91, 'Juan de Mata Suárez'),
(296, 92, 'Tinaquillo'),
(297, 93, 'El Baúl'),
(298, 93, 'Sucre'),
(299, 94, 'La Aguadita'),
(300, 94, 'Macapo'),
(301, 95, 'El Pao'),
(302, 96, 'El Amparo'),
(303, 96, 'Libertad de Cojedes'),
(304, 97, 'Rómulo Gallegos'),
(305, 98, 'San Carlos de Austria'),
(306, 98, 'Juan Ángel Bravo'),
(307, 98, 'Manuel Manrique'),
(308, 99, 'General en Jefe José Laurencio Silva'),
(309, 100, 'Curiapo'),
(310, 100, 'Almirante Luis Brión'),
(311, 100, 'Francisco Aniceto Lugo'),
(312, 100, 'Manuel Renaud'),
(313, 100, 'Padre Barral'),
(314, 100, 'Santos de Abelgas'),
(315, 101, 'Imataca'),
(316, 101, 'Cinco de Julio'),
(317, 101, 'Juan Bautista Arismendi'),
(318, 101, 'Manuel Piar'),
(319, 101, 'Rómulo Gallegos'),
(320, 102, 'Pedernales'),
(321, 102, 'Luis Beltrán Prieto Figueroa'),
(322, 103, 'San José (Delta Amacuro)'),
(323, 103, 'José Vidal Marcano'),
(324, 103, 'Juan Millán'),
(325, 103, 'Leonardo Ruíz Pineda'),
(326, 103, 'Mariscal Antonio José de Sucre'),
(327, 103, 'Monseñor Argimiro García'),
(328, 103, 'San Rafael (Delta Amacuro)'),
(329, 103, 'Virgen del Valle'),
(330, 10, 'Clarines'),
(331, 10, 'Guanape'),
(332, 10, 'Sabana de Uchire'),
(333, 104, 'Capadare'),
(334, 104, 'La Pastora'),
(335, 104, 'Libertador'),
(336, 104, 'San Juan de los Cayos'),
(337, 105, 'Aracua'),
(338, 105, 'La Peña'),
(339, 105, 'San Luis'),
(340, 106, 'Bariro'),
(341, 106, 'Borojó'),
(342, 106, 'Capatárida'),
(343, 106, 'Guajiro'),
(344, 106, 'Seque'),
(345, 106, 'Zazárida'),
(346, 106, 'Valle de Eroa'),
(347, 107, 'Cacique Manaure'),
(348, 108, 'Norte'),
(349, 108, 'Carirubana'),
(350, 108, 'Santa Ana'),
(351, 108, 'Urbana Punta Cardón'),
(352, 109, 'La Vela de Coro'),
(353, 109, 'Acurigua'),
(354, 109, 'Guaibacoa'),
(355, 109, 'Las Calderas'),
(356, 109, 'Macoruca'),
(357, 110, 'Dabajuro'),
(358, 111, 'Agua Clara'),
(359, 111, 'Avaria'),
(360, 111, 'Pedregal'),
(361, 111, 'Piedra Grande'),
(362, 111, 'Purureche'),
(363, 112, 'Adaure'),
(364, 112, 'Adícora'),
(365, 112, 'Baraived'),
(366, 112, 'Buena Vista'),
(367, 112, 'Jadacaquiva'),
(368, 112, 'El Vínculo'),
(369, 112, 'El Hato'),
(370, 112, 'Moruy'),
(371, 112, 'Pueblo Nuevo'),
(372, 113, 'Agua Larga'),
(373, 113, 'El Paují'),
(374, 113, 'Independencia'),
(375, 113, 'Mapararí'),
(376, 114, 'Agua Linda'),
(377, 114, 'Araurima'),
(378, 114, 'Jacura'),
(379, 115, 'Tucacas'),
(380, 115, 'Boca de Aroa'),
(381, 116, 'Los Taques'),
(382, 116, 'Judibana'),
(383, 117, 'Mene de Mauroa'),
(384, 117, 'San Félix'),
(385, 117, 'Casigua'),
(386, 118, 'Guzmán Guillermo'),
(387, 118, 'Mitare'),
(388, 118, 'Río Seco'),
(389, 118, 'Sabaneta'),
(390, 118, 'San Antonio'),
(391, 118, 'San Gabriel'),
(392, 118, 'Santa Ana'),
(393, 119, 'Boca del Tocuyo'),
(394, 119, 'Chichiriviche'),
(395, 119, 'Tocuyo de la Costa'),
(396, 120, 'Palmasola'),
(397, 121, 'Cabure'),
(398, 121, 'Colina'),
(399, 121, 'Curimagua'),
(400, 122, 'San José de la Costa'),
(401, 122, 'Píritu'),
(402, 123, 'San Francisco'),
(403, 124, 'Sucre'),
(404, 124, 'Pecaya'),
(405, 125, 'Tocópero'),
(406, 126, 'El Charal'),
(407, 126, 'Las Vegas del Tuy'),
(408, 126, 'Santa Cruz de Bucaral'),
(409, 127, 'Bruzual'),
(410, 127, 'Urumaco'),
(411, 128, 'Puerto Cumarebo'),
(412, 128, 'La Ciénaga'),
(413, 128, 'La Soledad'),
(414, 128, 'Pueblo Cumarebo'),
(415, 128, 'Zazárida'),
(416, 113, 'Churuguara'),
(417, 129, 'Camaguán'),
(418, 129, 'Puerto Miranda'),
(419, 129, 'Uverito'),
(420, 130, 'Chaguaramas'),
(421, 131, 'El Socorro'),
(422, 132, 'Tucupido'),
(423, 132, 'San Rafael de Laya'),
(424, 133, 'Altagracia de Orituco'),
(425, 133, 'San Rafael de Orituco'),
(426, 133, 'San Francisco Javier de Lezama'),
(427, 133, 'Paso Real de Macaira'),
(428, 133, 'Carlos Soublette'),
(429, 133, 'San Francisco de Macaira'),
(430, 133, 'Libertad de Orituco'),
(431, 134, 'Cantaclaro'),
(432, 134, 'San Juan de los Morros'),
(433, 134, 'Parapara'),
(434, 135, 'El Sombrero'),
(435, 135, 'Sosa'),
(436, 136, 'Las Mercedes'),
(437, 136, 'Cabruta'),
(438, 136, 'Santa Rita de Manapire'),
(439, 137, 'Valle de la Pascua'),
(440, 137, 'Espino'),
(441, 138, 'San José de Unare'),
(442, 138, 'Zaraza'),
(443, 139, 'San José de Tiznados'),
(444, 139, 'San Francisco de Tiznados'),
(445, 139, 'San Lorenzo de Tiznados'),
(446, 139, 'Ortiz'),
(447, 140, 'Guayabal'),
(448, 140, 'Cazorla'),
(449, 141, 'San José de Guaribe'),
(450, 141, 'Uveral'),
(451, 142, 'Santa María de Ipire'),
(452, 142, 'Altamira'),
(453, 143, 'El Calvario'),
(454, 143, 'El Rastro'),
(455, 143, 'Guardatinajas'),
(456, 143, 'Capital Urbana Calabozo'),
(457, 144, 'Quebrada Honda de Guache'),
(458, 144, 'Pío Tamayo'),
(459, 144, 'Yacambú'),
(460, 145, 'Fréitez'),
(461, 145, 'José María Blanco'),
(462, 146, 'Catedral'),
(463, 146, 'Concepción'),
(464, 146, 'El Cují'),
(465, 146, 'Juan de Villegas'),
(466, 146, 'Santa Rosa'),
(467, 146, 'Tamaca'),
(468, 146, 'Unión'),
(469, 146, 'Aguedo Felipe Alvarado'),
(470, 146, 'Buena Vista'),
(471, 146, 'Juárez'),
(472, 147, 'Juan Bautista Rodríguez'),
(473, 147, 'Cuara'),
(474, 147, 'Diego de Lozada'),
(475, 147, 'Paraíso de San José'),
(476, 147, 'San Miguel'),
(477, 147, 'Tintorero'),
(478, 147, 'José Bernardo Dorante'),
(479, 147, 'Coronel Mariano Peraza '),
(480, 148, 'Bolívar'),
(481, 148, 'Anzoátegui'),
(482, 148, 'Guarico'),
(483, 148, 'Hilario Luna y Luna'),
(484, 148, 'Humocaro Alto'),
(485, 148, 'Humocaro Bajo'),
(486, 148, 'La Candelaria'),
(487, 148, 'Morán'),
(488, 149, 'Cabudare'),
(489, 149, 'José Gregorio Bastidas'),
(490, 149, 'Agua Viva'),
(491, 150, 'Sarare'),
(492, 150, 'Buría'),
(493, 150, 'Gustavo Vegas León'),
(494, 151, 'Trinidad Samuel'),
(495, 151, 'Antonio Díaz'),
(496, 151, 'Camacaro'),
(497, 151, 'Castañeda'),
(498, 151, 'Cecilio Zubillaga'),
(499, 151, 'Chiquinquirá'),
(500, 151, 'El Blanco'),
(501, 151, 'Espinoza de los Monteros'),
(502, 151, 'Lara'),
(503, 151, 'Las Mercedes'),
(504, 151, 'Manuel Morillo'),
(505, 151, 'Montaña Verde'),
(506, 151, 'Montes de Oca'),
(507, 151, 'Torres'),
(508, 151, 'Heriberto Arroyo'),
(509, 151, 'Reyes Vargas'),
(510, 151, 'Altagracia'),
(511, 152, 'Siquisique'),
(512, 152, 'Moroturo'),
(513, 152, 'San Miguel'),
(514, 152, 'Xaguas'),
(515, 179, 'Presidente Betancourt'),
(516, 179, 'Presidente Páez'),
(517, 179, 'Presidente Rómulo Gallegos'),
(518, 179, 'Gabriel Picón González'),
(519, 179, 'Héctor Amable Mora'),
(520, 179, 'José Nucete Sardi'),
(521, 179, 'Pulido Méndez'),
(522, 180, 'La Azulita'),
(523, 181, 'Santa Cruz de Mora'),
(524, 181, 'Mesa Bolívar'),
(525, 181, 'Mesa de Las Palmas'),
(526, 182, 'Aricagua'),
(527, 182, 'San Antonio'),
(528, 183, 'Canagua'),
(529, 183, 'Capurí'),
(530, 183, 'Chacantá'),
(531, 183, 'El Molino'),
(532, 183, 'Guaimaral'),
(533, 183, 'Mucutuy'),
(534, 183, 'Mucuchachí'),
(535, 184, 'Fernández Peña'),
(536, 184, 'Matriz'),
(537, 184, 'Montalbán'),
(538, 184, 'Acequias'),
(539, 184, 'Jají'),
(540, 184, 'La Mesa'),
(541, 184, 'San José del Sur'),
(542, 185, 'Tucaní'),
(543, 185, 'Florencio Ramírez'),
(544, 186, 'Santo Domingo'),
(545, 186, 'Las Piedras'),
(546, 187, 'Guaraque'),
(547, 187, 'Mesa de Quintero'),
(548, 187, 'Río Negro'),
(549, 188, 'Arapuey'),
(550, 188, 'Palmira'),
(551, 189, 'San Cristóbal de Torondoy'),
(552, 189, 'Torondoy'),
(553, 190, 'Antonio Spinetti Dini'),
(554, 190, 'Arias'),
(555, 190, 'Caracciolo Parra Pérez'),
(556, 190, 'Domingo Peña'),
(557, 190, 'El Llano'),
(558, 190, 'Gonzalo Picón Febres'),
(559, 190, 'Jacinto Plaza'),
(560, 190, 'Juan Rodríguez Suárez'),
(561, 190, 'Lasso de la Vega'),
(562, 190, 'Mariano Picón Salas'),
(563, 190, 'Milla'),
(564, 190, 'Osuna Rodríguez'),
(565, 190, 'Sagrario'),
(566, 190, 'El Morro'),
(567, 190, 'Los Nevados'),
(568, 191, 'Andrés Eloy Blanco'),
(569, 191, 'La Venta'),
(570, 191, 'Piñango'),
(571, 191, 'Timotes'),
(572, 192, 'Eloy Paredes'),
(573, 192, 'San Rafael de Alcázar'),
(574, 192, 'Santa Elena de Arenales'),
(575, 193, 'Santa María de Caparo'),
(576, 194, 'Pueblo Llano'),
(577, 195, 'Cacute'),
(578, 195, 'La Toma'),
(579, 195, 'Mucuchíes'),
(580, 195, 'Mucurubá'),
(581, 195, 'San Rafael'),
(582, 196, 'Gerónimo Maldonado'),
(583, 196, 'Bailadores'),
(584, 197, 'Tabay'),
(585, 198, 'Chiguará'),
(586, 198, 'Estánquez'),
(587, 198, 'Lagunillas'),
(588, 198, 'La Trampa'),
(589, 198, 'Pueblo Nuevo del Sur'),
(590, 198, 'San Juan'),
(591, 199, 'El Amparo'),
(592, 199, 'El Llano'),
(593, 199, 'San Francisco'),
(594, 199, 'Tovar'),
(595, 200, 'Independencia'),
(596, 200, 'María de la Concepción Palacios Blanco'),
(597, 200, 'Nueva Bolivia'),
(598, 200, 'Santa Apolonia'),
(599, 201, 'Caño El Tigre'),
(600, 201, 'Zea'),
(601, 223, 'Aragüita'),
(602, 223, 'Arévalo González'),
(603, 223, 'Capaya'),
(604, 223, 'Caucagua'),
(605, 223, 'Panaquire'),
(606, 223, 'Ribas'),
(607, 223, 'El Café'),
(608, 223, 'Marizapa'),
(609, 224, 'Cumbo'),
(610, 224, 'San José de Barlovento'),
(611, 225, 'El Cafetal'),
(612, 225, 'Las Minas'),
(613, 225, 'Nuestra Señora del Rosario'),
(614, 226, 'Higuerote'),
(615, 226, 'Curiepe'),
(616, 226, 'Tacarigua de Brión'),
(617, 227, 'Mamporal'),
(618, 228, 'Carrizal'),
(619, 229, 'Chacao'),
(620, 230, 'Charallave'),
(621, 230, 'Las Brisas'),
(622, 231, 'El Hatillo'),
(623, 232, 'Altagracia de la Montaña'),
(624, 232, 'Cecilio Acosta'),
(625, 232, 'Los Teques'),
(626, 232, 'El Jarillo'),
(627, 232, 'San Pedro'),
(628, 232, 'Tácata'),
(629, 232, 'Paracotos'),
(630, 233, 'Cartanal'),
(631, 233, 'Santa Teresa del Tuy'),
(632, 234, 'La Democracia'),
(633, 234, 'Ocumare del Tuy'),
(634, 234, 'Santa Bárbara'),
(635, 235, 'San Antonio de los Altos'),
(636, 236, 'Río Chico'),
(637, 236, 'El Guapo'),
(638, 236, 'Tacarigua de la Laguna'),
(639, 236, 'Paparo'),
(640, 236, 'San Fernando del Guapo'),
(641, 237, 'Santa Lucía del Tuy'),
(642, 238, 'Cúpira'),
(643, 238, 'Machurucuto'),
(644, 239, 'Guarenas'),
(645, 240, 'San Antonio de Yare'),
(646, 240, 'San Francisco de Yare'),
(647, 241, 'Leoncio Martínez'),
(648, 241, 'Petare'),
(649, 241, 'Caucagüita'),
(650, 241, 'Filas de Mariche'),
(651, 241, 'La Dolorita'),
(652, 242, 'Cúa'),
(653, 242, 'Nueva Cúa'),
(654, 243, 'Guatire'),
(655, 243, 'Bolívar'),
(656, 258, 'San Antonio de Maturín'),
(657, 258, 'San Francisco de Maturín'),
(658, 259, 'Aguasay'),
(659, 260, 'Caripito'),
(660, 261, 'El Guácharo'),
(661, 261, 'La Guanota'),
(662, 261, 'Sabana de Piedra'),
(663, 261, 'San Agustín'),
(664, 261, 'Teresen'),
(665, 261, 'Caripe'),
(666, 262, 'Areo'),
(667, 262, 'Capital Cedeño'),
(668, 262, 'San Félix de Cantalicio'),
(669, 262, 'Viento Fresco'),
(670, 263, 'El Tejero'),
(671, 263, 'Punta de Mata'),
(672, 264, 'Chaguaramas'),
(673, 264, 'Las Alhuacas'),
(674, 264, 'Tabasca'),
(675, 264, 'Temblador'),
(676, 265, 'Alto de los Godos'),
(677, 265, 'Boquerón'),
(678, 265, 'Las Cocuizas'),
(679, 265, 'La Cruz'),
(680, 265, 'San Simón'),
(681, 265, 'El Corozo'),
(682, 265, 'El Furrial'),
(683, 265, 'Jusepín'),
(684, 265, 'La Pica'),
(685, 265, 'San Vicente'),
(686, 266, 'Aparicio'),
(687, 266, 'Aragua de Maturín'),
(688, 266, 'Chaguamal'),
(689, 266, 'El Pinto'),
(690, 266, 'Guanaguana'),
(691, 266, 'La Toscana'),
(692, 266, 'Taguaya'),
(693, 267, 'Cachipo'),
(694, 267, 'Quiriquire'),
(695, 268, 'Santa Bárbara'),
(696, 269, 'Barrancas'),
(697, 269, 'Los Barrancos de Fajardo'),
(698, 270, 'Uracoa'),
(699, 271, 'Antolín del Campo'),
(700, 272, 'Arismendi'),
(701, 273, 'García'),
(702, 273, 'Francisco Fajardo'),
(703, 274, 'Bolívar'),
(704, 274, 'Guevara'),
(705, 274, 'Matasiete'),
(706, 274, 'Santa Ana'),
(707, 274, 'Sucre'),
(708, 275, 'Aguirre'),
(709, 275, 'Maneiro'),
(710, 276, 'Adrián'),
(711, 276, 'Juan Griego'),
(712, 276, 'Yaguaraparo'),
(713, 277, 'Porlamar'),
(714, 278, 'San Francisco de Macanao'),
(715, 278, 'Boca de Río'),
(716, 279, 'Tubores'),
(717, 279, 'Los Baleales'),
(718, 280, 'Vicente Fuentes'),
(719, 280, 'Villalba'),
(720, 281, 'San Juan Bautista'),
(721, 281, 'Zabala'),
(722, 283, 'Capital Araure'),
(723, 283, 'Río Acarigua'),
(724, 284, 'Capital Esteller'),
(725, 284, 'Uveral'),
(726, 285, 'Guanare'),
(727, 285, 'Córdoba'),
(728, 285, 'San José de la Montaña'),
(729, 285, 'San Juan de Guanaguanare'),
(730, 285, 'Virgen de la Coromoto'),
(731, 286, 'Guanarito'),
(732, 286, 'Trinidad de la Capilla'),
(733, 286, 'Divina Pastora'),
(734, 287, 'Monseñor José Vicente de Unda'),
(735, 287, 'Peña Blanca'),
(736, 288, 'Capital Ospino'),
(737, 288, 'Aparición'),
(738, 288, 'La Estación'),
(739, 289, 'Páez'),
(740, 289, 'Payara'),
(741, 289, 'Pimpinela'),
(742, 289, 'Ramón Peraza'),
(743, 290, 'Papelón'),
(744, 290, 'Caño Delgadito'),
(745, 291, 'San Genaro de Boconoito'),
(746, 291, 'Antolín Tovar'),
(747, 292, 'San Rafael de Onoto'),
(748, 292, 'Santa Fe'),
(749, 292, 'Thermo Morles'),
(750, 293, 'Santa Rosalía'),
(751, 293, 'Florida'),
(752, 294, 'Sucre'),
(753, 294, 'Concepción'),
(754, 294, 'San Rafael de Palo Alzado'),
(755, 294, 'Uvencio Antonio Velásquez'),
(756, 294, 'San José de Saguaz'),
(757, 294, 'Villa Rosa'),
(758, 295, 'Turén'),
(759, 295, 'Canelones'),
(760, 295, 'Santa Cruz'),
(761, 295, 'San Isidro Labrador'),
(762, 296, 'Mariño'),
(763, 296, 'Rómulo Gallegos'),
(764, 297, 'San José de Aerocuar'),
(765, 297, 'Tavera Acosta'),
(766, 298, 'Río Caribe'),
(767, 298, 'Antonio José de Sucre'),
(768, 298, 'El Morro de Puerto Santo'),
(769, 298, 'Puerto Santo'),
(770, 298, 'San Juan de las Galdonas'),
(771, 299, 'El Pilar'),
(772, 299, 'El Rincón'),
(773, 299, 'General Francisco Antonio Váquez'),
(774, 299, 'Guaraúnos'),
(775, 299, 'Tunapuicito'),
(776, 299, 'Unión'),
(777, 300, 'Santa Catalina'),
(778, 300, 'Santa Rosa'),
(779, 300, 'Santa Teresa'),
(780, 300, 'Bolívar'),
(781, 300, 'Maracapana'),
(782, 302, 'Libertad'),
(783, 302, 'El Paujil'),
(784, 302, 'Yaguaraparo'),
(785, 303, 'Cruz Salmerón Acosta'),
(786, 303, 'Chacopata'),
(787, 303, 'Manicuare'),
(788, 304, 'Tunapuy'),
(789, 304, 'Campo Elías'),
(790, 305, 'Irapa'),
(791, 305, 'Campo Claro'),
(792, 305, 'Maraval'),
(793, 305, 'San Antonio de Irapa'),
(794, 305, 'Soro'),
(795, 306, 'Mejía'),
(796, 307, 'Cumanacoa'),
(797, 307, 'Arenas'),
(798, 307, 'Aricagua'),
(799, 307, 'Cogollar'),
(800, 307, 'San Fernando'),
(801, 307, 'San Lorenzo'),
(802, 308, 'Villa Frontado (Muelle de Cariaco)'),
(803, 308, 'Catuaro'),
(804, 308, 'Rendón'),
(805, 308, 'San Cruz'),
(806, 308, 'Santa María'),
(807, 309, 'Altagracia'),
(808, 309, 'Santa Inés'),
(809, 309, 'Valentín Valiente'),
(810, 309, 'Ayacucho'),
(811, 309, 'San Juan'),
(812, 309, 'Raúl Leoni'),
(813, 309, 'Gran Mariscal'),
(814, 310, 'Cristóbal Colón'),
(815, 310, 'Bideau'),
(816, 310, 'Punta de Piedras'),
(817, 310, 'Güiria'),
(818, 341, 'Andrés Bello'),
(819, 342, 'Antonio Rómulo Costa'),
(820, 343, 'Ayacucho'),
(821, 343, 'Rivas Berti'),
(822, 343, 'San Pedro del Río'),
(823, 344, 'Bolívar'),
(824, 344, 'Palotal'),
(825, 344, 'General Juan Vicente Gómez'),
(826, 344, 'Isaías Medina Angarita'),
(827, 345, 'Cárdenas'),
(828, 345, 'Amenodoro Ángel Lamus'),
(829, 345, 'La Florida'),
(830, 346, 'Córdoba'),
(831, 347, 'Fernández Feo'),
(832, 347, 'Alberto Adriani'),
(833, 347, 'Santo Domingo'),
(834, 348, 'Francisco de Miranda'),
(835, 349, 'García de Hevia'),
(836, 349, 'Boca de Grita'),
(837, 349, 'José Antonio Páez'),
(838, 350, 'Guásimos'),
(839, 351, 'Independencia'),
(840, 351, 'Juan Germán Roscio'),
(841, 351, 'Román Cárdenas'),
(842, 352, 'Jáuregui'),
(843, 352, 'Emilio Constantino Guerrero'),
(844, 352, 'Monseñor Miguel Antonio Salas'),
(845, 353, 'José María Vargas'),
(846, 354, 'Junín'),
(847, 354, 'La Petrólea'),
(848, 354, 'Quinimarí'),
(849, 354, 'Bramón'),
(850, 355, 'Libertad'),
(851, 355, 'Cipriano Castro'),
(852, 355, 'Manuel Felipe Rugeles'),
(853, 356, 'Libertador'),
(854, 356, 'Doradas'),
(855, 356, 'Emeterio Ochoa'),
(856, 356, 'San Joaquín de Navay'),
(857, 357, 'Lobatera'),
(858, 357, 'Constitución'),
(859, 358, 'Michelena'),
(860, 359, 'Panamericano'),
(861, 359, 'La Palmita'),
(862, 360, 'Pedro María Ureña'),
(863, 360, 'Nueva Arcadia'),
(864, 361, 'Delicias'),
(865, 361, 'Pecaya'),
(866, 362, 'Samuel Darío Maldonado'),
(867, 362, 'Boconó'),
(868, 362, 'Hernández'),
(869, 363, 'La Concordia'),
(870, 363, 'San Juan Bautista'),
(871, 363, 'Pedro María Morantes'),
(872, 363, 'San Sebastián'),
(873, 363, 'Dr. Francisco Romero Lobo'),
(874, 364, 'Seboruco'),
(875, 365, 'Simón Rodríguez'),
(876, 366, 'Sucre'),
(877, 366, 'Eleazar López Contreras'),
(878, 366, 'San Pablo'),
(879, 367, 'Torbes'),
(880, 368, 'Uribante'),
(881, 368, 'Cárdenas'),
(882, 368, 'Juan Pablo Peñalosa'),
(883, 368, 'Potosí'),
(884, 369, 'San Judas Tadeo'),
(885, 370, 'Araguaney'),
(886, 370, 'El Jaguito'),
(887, 370, 'La Esperanza'),
(888, 370, 'Santa Isabel'),
(889, 371, 'Boconó'),
(890, 371, 'El Carmen'),
(891, 371, 'Mosquey'),
(892, 371, 'Ayacucho'),
(893, 371, 'Burbusay'),
(894, 371, 'General Ribas'),
(895, 371, 'Guaramacal'),
(896, 371, 'Vega de Guaramacal'),
(897, 371, 'Monseñor Jáuregui'),
(898, 371, 'Rafael Rangel'),
(899, 371, 'San Miguel'),
(900, 371, 'San José'),
(901, 372, 'Sabana Grande'),
(902, 372, 'Cheregüé'),
(903, 372, 'Granados'),
(904, 373, 'Arnoldo Gabaldón'),
(905, 373, 'Bolivia'),
(906, 373, 'Carrillo'),
(907, 373, 'Cegarra'),
(908, 373, 'Chejendé'),
(909, 373, 'Manuel Salvador Ulloa'),
(910, 373, 'San José'),
(911, 374, 'Carache'),
(912, 374, 'La Concepción'),
(913, 374, 'Cuicas'),
(914, 374, 'Panamericana'),
(915, 374, 'Santa Cruz'),
(916, 375, 'Escuque'),
(917, 375, 'La Unión'),
(918, 375, 'Santa Rita'),
(919, 375, 'Sabana Libre'),
(920, 376, 'El Socorro'),
(921, 376, 'Los Caprichos'),
(922, 376, 'Antonio José de Sucre'),
(923, 377, 'Campo Elías'),
(924, 377, 'Arnoldo Gabaldón'),
(925, 378, 'Santa Apolonia'),
(926, 378, 'El Progreso'),
(927, 378, 'La Ceiba'),
(928, 378, 'Tres de Febrero'),
(929, 379, 'El Dividive'),
(930, 379, 'Agua Santa'),
(931, 379, 'Agua Caliente'),
(932, 379, 'El Cenizo'),
(933, 379, 'Valerita'),
(934, 380, 'Monte Carmelo'),
(935, 380, 'Buena Vista'),
(936, 380, 'Santa María del Horcón'),
(937, 381, 'Motatán'),
(938, 381, 'El Baño'),
(939, 381, 'Jalisco'),
(940, 382, 'Pampán'),
(941, 382, 'Flor de Patria'),
(942, 382, 'La Paz'),
(943, 382, 'Santa Ana'),
(944, 383, 'Pampanito'),
(945, 383, 'La Concepción'),
(946, 383, 'Pampanito II'),
(947, 384, 'Betijoque'),
(948, 384, 'José Gregorio Hernández'),
(949, 384, 'La Pueblita'),
(950, 384, 'Los Cedros'),
(951, 385, 'Carvajal'),
(952, 385, 'Campo Alegre'),
(953, 385, 'Antonio Nicolás Briceño'),
(954, 385, 'José Leonardo Suárez'),
(955, 386, 'Sabana de Mendoza'),
(956, 386, 'Junín'),
(957, 386, 'Valmore Rodríguez'),
(958, 386, 'El Paraíso'),
(959, 387, 'Andrés Linares'),
(960, 387, 'Chiquinquirá'),
(961, 387, 'Cristóbal Mendoza'),
(962, 387, 'Cruz Carrillo'),
(963, 387, 'Matriz'),
(964, 387, 'Monseñor Carrillo'),
(965, 387, 'Tres Esquinas'),
(966, 388, 'Cabimbú'),
(967, 388, 'Jajó'),
(968, 388, 'La Mesa de Esnujaque'),
(969, 388, 'Santiago'),
(970, 388, 'Tuñame'),
(971, 388, 'La Quebrada'),
(972, 389, 'Juan Ignacio Montilla'),
(973, 389, 'La Beatriz'),
(974, 389, 'La Puerta'),
(975, 389, 'Mendoza del Valle de Momboy'),
(976, 389, 'Mercedes Díaz'),
(977, 389, 'San Luis'),
(978, 390, 'Caraballeda'),
(979, 390, 'Carayaca'),
(980, 390, 'Carlos Soublette'),
(981, 390, 'Caruao Chuspa'),
(982, 390, 'Catia La Mar'),
(983, 390, 'El Junko'),
(984, 390, 'La Guaira'),
(985, 390, 'Macuto'),
(986, 390, 'Maiquetía'),
(987, 390, 'Naiguatá'),
(988, 390, 'Urimare'),
(989, 391, 'Arístides Bastidas'),
(990, 392, 'Bolívar'),
(991, 407, 'Chivacoa'),
(992, 407, 'Campo Elías'),
(993, 408, 'Cocorote'),
(994, 409, 'Independencia'),
(995, 410, 'José Antonio Páez'),
(996, 411, 'La Trinidad'),
(997, 412, 'Manuel Monge'),
(998, 413, 'Salóm'),
(999, 413, 'Temerla'),
(1000, 413, 'Nirgua'),
(1001, 414, 'San Andrés'),
(1002, 414, 'Yaritagua'),
(1003, 415, 'San Javier'),
(1004, 415, 'Albarico'),
(1005, 415, 'San Felipe'),
(1006, 416, 'Sucre'),
(1007, 417, 'Urachiche'),
(1008, 418, 'El Guayabo'),
(1009, 418, 'Farriar'),
(1010, 441, 'Isla de Toas'),
(1011, 441, 'Monagas'),
(1012, 442, 'San Timoteo'),
(1013, 442, 'General Urdaneta'),
(1014, 442, 'Libertador'),
(1015, 442, 'Marcelino Briceño'),
(1016, 442, 'Pueblo Nuevo'),
(1017, 442, 'Manuel Guanipa Matos'),
(1018, 443, 'Ambrosio'),
(1019, 443, 'Carmen Herrera'),
(1020, 443, 'La Rosa'),
(1021, 443, 'Germán Ríos Linares'),
(1022, 443, 'San Benito'),
(1023, 443, 'Rómulo Betancourt'),
(1024, 443, 'Jorge Hernández'),
(1025, 443, 'Punta Gorda'),
(1026, 443, 'Arístides Calvani'),
(1027, 444, 'Encontrados'),
(1028, 444, 'Udón Pérez'),
(1029, 445, 'Moralito'),
(1030, 445, 'San Carlos del Zulia'),
(1031, 445, 'Santa Cruz del Zulia'),
(1032, 445, 'Santa Bárbara'),
(1033, 445, 'Urribarrí'),
(1034, 446, 'Carlos Quevedo'),
(1035, 446, 'Francisco Javier Pulgar'),
(1036, 446, 'Simón Rodríguez'),
(1037, 446, 'Guamo-Gavilanes'),
(1038, 448, 'La Concepción'),
(1039, 448, 'San José'),
(1040, 448, 'Mariano Parra León'),
(1041, 448, 'José Ramón Yépez'),
(1042, 449, 'Jesús María Semprún'),
(1043, 449, 'Barí'),
(1044, 450, 'Concepción'),
(1045, 450, 'Andrés Bello'),
(1046, 450, 'Chiquinquirá'),
(1047, 450, 'El Carmelo'),
(1048, 450, 'Potreritos'),
(1049, 451, 'Libertad'),
(1050, 451, 'Alonso de Ojeda'),
(1051, 451, 'Venezuela'),
(1052, 451, 'Eleazar López Contreras'),
(1053, 451, 'Campo Lara'),
(1054, 452, 'Bartolomé de las Casas'),
(1055, 452, 'Libertad'),
(1056, 452, 'Río Negro'),
(1057, 452, 'San José de Perijá'),
(1058, 453, 'San Rafael'),
(1059, 453, 'La Sierrita'),
(1060, 453, 'Las Parcelas'),
(1061, 453, 'Luis de Vicente'),
(1062, 453, 'Monseñor Marcos Sergio Godoy'),
(1063, 453, 'Ricaurte'),
(1064, 453, 'Tamare'),
(1065, 454, 'Antonio Borjas Romero'),
(1066, 454, 'Bolívar'),
(1067, 454, 'Cacique Mara'),
(1068, 454, 'Carracciolo Parra Pérez'),
(1069, 454, 'Cecilio Acosta'),
(1070, 454, 'Cristo de Aranza'),
(1071, 454, 'Coquivacoa'),
(1072, 454, 'Chiquinquirá'),
(1073, 454, 'Francisco Eugenio Bustamante'),
(1074, 454, 'Idelfonzo Vásquez'),
(1075, 454, 'Juana de Ávila'),
(1076, 454, 'Luis Hurtado Higuera'),
(1077, 454, 'Manuel Dagnino'),
(1078, 454, 'Olegario Villalobos'),
(1079, 454, 'Raúl Leoni'),
(1080, 454, 'Santa Lucía'),
(1081, 454, 'Venancio Pulgar'),
(1082, 454, 'San Isidro'),
(1083, 455, 'Altagracia'),
(1084, 455, 'Faría'),
(1085, 455, 'Ana María Campos'),
(1086, 455, 'San Antonio'),
(1087, 455, 'San José'),
(1088, 456, 'Donaldo García'),
(1089, 456, 'El Rosario'),
(1090, 456, 'Sixto Zambrano'),
(1091, 457, 'San Francisco'),
(1092, 457, 'El Bajo'),
(1093, 457, 'Domitila Flores'),
(1094, 457, 'Francisco Ochoa'),
(1095, 457, 'Los Cortijos'),
(1096, 457, 'Marcial Hernández'),
(1097, 458, 'Santa Rita'),
(1098, 458, 'El Mene'),
(1099, 458, 'Pedro Lucas Urribarrí'),
(1100, 458, 'José Cenobio Urribarrí'),
(1101, 459, 'Rafael Maria Baralt'),
(1102, 459, 'Manuel Manrique'),
(1103, 459, 'Rafael Urdaneta'),
(1104, 460, 'Bobures'),
(1105, 460, 'Gibraltar'),
(1106, 460, 'Heras'),
(1107, 460, 'Monseñor Arturo Álvarez'),
(1108, 460, 'Rómulo Gallegos'),
(1109, 460, 'El Batey'),
(1110, 461, 'Rafael Urdaneta'),
(1111, 461, 'La Victoria'),
(1112, 461, 'Raúl Cuenca'),
(1113, 447, 'Sinamaica'),
(1114, 447, 'Alta Guajira'),
(1115, 447, 'Elías Sánchez Rubio'),
(1116, 447, 'Guajira'),
(1117, 462, 'Altagracia'),
(1118, 462, 'Antímano'),
(1119, 462, 'Caricuao'),
(1120, 462, 'Catedral'),
(1121, 462, 'Coche'),
(1122, 462, 'El Junquito'),
(1123, 462, 'El Paraíso'),
(1124, 462, 'El Recreo'),
(1125, 462, 'El Valle'),
(1126, 462, 'La Candelaria'),
(1127, 462, 'La Pastora'),
(1128, 462, 'La Vega'),
(1129, 462, 'Macarao'),
(1130, 462, 'San Agustín'),
(1131, 462, 'San Bernardino'),
(1132, 462, 'San José'),
(1133, 462, 'San Juan'),
(1134, 462, 'San Pedro'),
(1135, 462, 'Santa Rosalía'),
(1136, 462, 'Santa Teresa'),
(1137, 462, 'Sucre (Catia)'),
(1138, 462, '23 de enero'),
(1139, 282, 'Agua Blanca'),
(1140, 463, 'Parroquia - Afganistán'),
(1141, 464, 'Parroquia - Islas Gland'),
(1142, 465, 'Parroquia - Albania'),
(1143, 466, 'Parroquia - Alemania'),
(1144, 467, 'Parroquia - Andorra'),
(1145, 468, 'Parroquia - Angola'),
(1146, 469, 'Parroquia - Anguilla'),
(1147, 470, 'Parroquia - Antártida'),
(1148, 471, 'Parroquia - Antigua y Barbuda'),
(1149, 472, 'Parroquia - Antillas Holandesas'),
(1150, 473, 'Parroquia - Arabia Saudí'),
(1151, 474, 'Parroquia - Argelia'),
(1152, 475, 'Parroquia - Argentina'),
(1153, 476, 'Parroquia - Armenia'),
(1154, 477, 'Parroquia - Aruba'),
(1155, 478, 'Parroquia - Australia'),
(1156, 479, 'Parroquia - Austria'),
(1157, 480, 'Parroquia - Azerbaiyán'),
(1158, 481, 'Parroquia - Bahamas'),
(1159, 482, 'Parroquia - Bahréin'),
(1160, 483, 'Parroquia - Bangladesh'),
(1161, 484, 'Parroquia - Barbados'),
(1162, 485, 'Parroquia - Bielorrusia'),
(1163, 486, 'Parroquia - Bélgica'),
(1164, 487, 'Parroquia - Belice'),
(1165, 488, 'Parroquia - Benin'),
(1166, 489, 'Parroquia - Bermudas'),
(1167, 490, 'Parroquia - Bhután'),
(1168, 491, 'Parroquia - Bolivia'),
(1169, 492, 'Parroquia - Bosnia y Herzegovina'),
(1170, 493, 'Parroquia - Botsuana'),
(1171, 494, 'Parroquia - Isla Bouvet'),
(1172, 495, 'Parroquia - Brasil'),
(1173, 496, 'Parroquia - Brunéi'),
(1174, 497, 'Parroquia - Bulgaria'),
(1175, 498, 'Parroquia - Burkina Faso'),
(1176, 499, 'Parroquia - Burundi'),
(1177, 500, 'Parroquia - Cabo Verde'),
(1178, 501, 'Parroquia - Islas Caimán'),
(1179, 502, 'Parroquia - Camboya'),
(1180, 503, 'Parroquia - Camerún'),
(1181, 504, 'Parroquia - Canadá'),
(1182, 505, 'Parroquia - República Centroafricana'),
(1183, 506, 'Parroquia - Chad'),
(1184, 507, 'Parroquia - República Checa'),
(1185, 508, 'Parroquia - Chile'),
(1186, 509, 'Parroquia - China'),
(1187, 510, 'Parroquia - Chipre'),
(1188, 511, 'Parroquia - Isla de Navidad'),
(1189, 512, 'Parroquia - Ciudad del Vaticano'),
(1190, 513, 'Parroquia - Islas Cocos'),
(1191, 514, 'Parroquia - Colombia'),
(1192, 515, 'Parroquia - Comoras'),
(1193, 516, 'Parroquia - República Democrática del Congo'),
(1194, 517, 'Parroquia - Congo'),
(1195, 518, 'Parroquia - Islas Cook'),
(1196, 519, 'Parroquia - Corea del Norte'),
(1197, 520, 'Parroquia - Corea del Sur'),
(1198, 521, 'Parroquia - Costa de Marfil'),
(1199, 522, 'Parroquia - Costa Rica'),
(1200, 523, 'Parroquia - Croacia'),
(1201, 524, 'Parroquia - Cuba'),
(1202, 525, 'Parroquia - Dinamarca'),
(1203, 526, 'Parroquia - Dominica'),
(1204, 527, 'Parroquia - República Dominicana'),
(1205, 528, 'Parroquia - Ecuador'),
(1206, 529, 'Parroquia - Egipto'),
(1207, 530, 'Parroquia - El Salvador'),
(1208, 531, 'Parroquia - Emiratos Árabes Unidos'),
(1209, 532, 'Parroquia - Eritrea'),
(1210, 533, 'Parroquia - Eslovaquia'),
(1211, 534, 'Parroquia - Eslovenia'),
(1212, 535, 'Parroquia - España'),
(1213, 536, 'Parroquia - Islas ultramarinas de Estados Unidos'),
(1214, 537, 'Parroquia - Estados Unidos'),
(1215, 538, 'Parroquia - Estonia'),
(1216, 539, 'Parroquia - Etiopía'),
(1217, 540, 'Parroquia - Islas Feroe'),
(1218, 541, 'Parroquia - Filipinas'),
(1219, 542, 'Parroquia - Finlandia'),
(1220, 543, 'Parroquia - Fiyi'),
(1221, 544, 'Parroquia - Francia'),
(1222, 545, 'Parroquia - Gabón'),
(1223, 546, 'Parroquia - Gambia'),
(1224, 547, 'Parroquia - Georgia'),
(1225, 548, 'Parroquia - Islas Georgias del Sur y Sandwich del Sur'),
(1226, 549, 'Parroquia - Ghana'),
(1227, 550, 'Parroquia - Gibraltar'),
(1228, 551, 'Parroquia - Granada'),
(1229, 552, 'Parroquia - Grecia'),
(1230, 553, 'Parroquia - Groenlandia'),
(1231, 554, 'Parroquia - Guadalupe'),
(1232, 555, 'Parroquia - Guam'),
(1233, 556, 'Parroquia - Guatemala'),
(1234, 557, 'Parroquia - Guayana Francesa'),
(1235, 558, 'Parroquia - Guinea'),
(1236, 559, 'Parroquia - Guinea Ecuatorial'),
(1237, 560, 'Parroquia - Guinea-Bissau'),
(1238, 561, 'Parroquia - Guyana'),
(1239, 562, 'Parroquia - Haití'),
(1240, 563, 'Parroquia - Islas Heard y McDonald'),
(1241, 564, 'Parroquia - Honduras'),
(1242, 565, 'Parroquia - Hong Kong'),
(1243, 566, 'Parroquia - Hungría'),
(1244, 567, 'Parroquia - India'),
(1245, 568, 'Parroquia - Indonesia'),
(1246, 569, 'Parroquia - Irán'),
(1247, 570, 'Parroquia - Iraq'),
(1248, 571, 'Parroquia - Irlanda'),
(1249, 572, 'Parroquia - Islandia'),
(1250, 573, 'Parroquia - Israel'),
(1251, 574, 'Parroquia - Italia'),
(1252, 575, 'Parroquia - Jamaica'),
(1253, 576, 'Parroquia - Japón'),
(1254, 577, 'Parroquia - Jordania'),
(1255, 578, 'Parroquia - Kazajstán'),
(1256, 579, 'Parroquia - Kenia'),
(1257, 580, 'Parroquia - Kirguistán'),
(1258, 581, 'Parroquia - Kiribati'),
(1259, 582, 'Parroquia - Kuwait'),
(1260, 583, 'Parroquia - Laos'),
(1261, 584, 'Parroquia - Lesotho'),
(1262, 585, 'Parroquia - Letonia'),
(1263, 586, 'Parroquia - Líbano'),
(1264, 587, 'Parroquia - Liberia'),
(1265, 588, 'Parroquia - Libia'),
(1266, 589, 'Parroquia - Liechtenstein'),
(1267, 590, 'Parroquia - Lituania'),
(1268, 591, 'Parroquia - Luxemburgo'),
(1269, 592, 'Parroquia - Macao'),
(1270, 593, 'Parroquia - ARY Macedonia'),
(1271, 594, 'Parroquia - Madagascar'),
(1272, 595, 'Parroquia - Malasia'),
(1273, 596, 'Parroquia - Malawi'),
(1274, 597, 'Parroquia - Maldivas'),
(1275, 598, 'Parroquia - Malí'),
(1276, 599, 'Parroquia - Malta'),
(1277, 600, 'Parroquia - Islas Malvinas'),
(1278, 601, 'Parroquia - Islas Marianas del Norte'),
(1279, 602, 'Parroquia - Marruecos'),
(1280, 603, 'Parroquia - Islas Marshall'),
(1281, 604, 'Parroquia - Martinica'),
(1282, 605, 'Parroquia - Mauricio'),
(1283, 606, 'Parroquia - Mauritania'),
(1284, 607, 'Parroquia - Mayotte'),
(1285, 608, 'Parroquia - México'),
(1286, 609, 'Parroquia - Micronesia'),
(1287, 610, 'Parroquia - Moldavia'),
(1288, 611, 'Parroquia - Mónaco'),
(1289, 612, 'Parroquia - Mongolia'),
(1290, 613, 'Parroquia - Montserrat'),
(1291, 614, 'Parroquia - Mozambique'),
(1292, 615, 'Parroquia - Myanmar'),
(1293, 616, 'Parroquia - Namibia'),
(1294, 617, 'Parroquia - Nauru'),
(1295, 618, 'Parroquia - Nepal'),
(1296, 619, 'Parroquia - Nicaragua'),
(1297, 620, 'Parroquia - Níger'),
(1298, 621, 'Parroquia - Nigeria'),
(1299, 622, 'Parroquia - Niue'),
(1300, 623, 'Parroquia - Isla Norfolk'),
(1301, 624, 'Parroquia - Noruega'),
(1302, 625, 'Parroquia - Nueva Caledonia'),
(1303, 626, 'Parroquia - Nueva Zelanda'),
(1304, 627, 'Parroquia - Omán'),
(1305, 628, 'Parroquia - Países Bajos'),
(1306, 629, 'Parroquia - Pakistán'),
(1307, 630, 'Parroquia - Palau'),
(1308, 631, 'Parroquia - Palestina'),
(1309, 632, 'Parroquia - Panamá'),
(1310, 633, 'Parroquia - Papúa Nueva Guinea'),
(1311, 634, 'Parroquia - Paraguay'),
(1312, 635, 'Parroquia - Perú'),
(1313, 636, 'Parroquia - Islas Pitcairn'),
(1314, 637, 'Parroquia - Polinesia Francesa'),
(1315, 638, 'Parroquia - Polonia'),
(1316, 639, 'Parroquia - Portugal'),
(1317, 640, 'Parroquia - Puerto Rico'),
(1318, 641, 'Parroquia - Qatar'),
(1319, 642, 'Parroquia - Reino Unido'),
(1320, 643, 'Parroquia - Reunión'),
(1321, 644, 'Parroquia - Ruanda'),
(1322, 645, 'Parroquia - Rumania'),
(1323, 646, 'Parroquia - Rusia'),
(1324, 647, 'Parroquia - Sahara Occidental'),
(1325, 648, 'Parroquia - Islas Salomón'),
(1326, 649, 'Parroquia - Samoa'),
(1327, 650, 'Parroquia - Samoa Americana'),
(1328, 651, 'Parroquia - San Cristóbal y Nevis'),
(1329, 652, 'Parroquia - San Marino'),
(1330, 653, 'Parroquia - San Pedro y Miquelón'),
(1331, 654, 'Parroquia - San Vicente y las Granadinas'),
(1332, 655, 'Parroquia - Santa Helena'),
(1333, 656, 'Parroquia - Santa Lucía'),
(1334, 657, 'Parroquia - Santo Tomé y Príncipe'),
(1335, 658, 'Parroquia - Senegal'),
(1336, 659, 'Parroquia - Serbia y Montenegro'),
(1337, 660, 'Parroquia - Seychelles'),
(1338, 661, 'Parroquia - Sierra Leona'),
(1339, 662, 'Parroquia - Singapur'),
(1340, 663, 'Parroquia - Siria'),
(1341, 664, 'Parroquia - Somalia'),
(1342, 665, 'Parroquia - Sri Lanka'),
(1343, 666, 'Parroquia - Suazilandia'),
(1344, 667, 'Parroquia - Sudáfrica'),
(1345, 668, 'Parroquia - Sudán'),
(1346, 669, 'Parroquia - Suecia'),
(1347, 670, 'Parroquia - Suiza'),
(1348, 671, 'Parroquia - Surinam'),
(1349, 672, 'Parroquia - Svalbard y Jan Mayen'),
(1350, 673, 'Parroquia - Tailandia'),
(1351, 674, 'Parroquia - Taiwán'),
(1352, 675, 'Parroquia - Tanzania'),
(1353, 676, 'Parroquia - Tayikistán'),
(1354, 677, 'Parroquia - Territorio Británico del Océano Índico'),
(1355, 678, 'Parroquia - Territorios Australes Franceses'),
(1356, 679, 'Parroquia - Timor Oriental'),
(1357, 680, 'Parroquia - Togo'),
(1358, 681, 'Parroquia - Tokelau'),
(1359, 682, 'Parroquia - Tonga'),
(1360, 683, 'Parroquia - Trinidad y Tobago'),
(1361, 684, 'Parroquia - Túnez'),
(1362, 685, 'Parroquia - Islas Turcas y Caicos'),
(1363, 686, 'Parroquia - Turkmenistán'),
(1364, 687, 'Parroquia - Turquía'),
(1365, 688, 'Parroquia - Tuvalu'),
(1366, 689, 'Parroquia - Ucrania'),
(1367, 690, 'Parroquia - Uganda'),
(1368, 691, 'Parroquia - Uruguay'),
(1369, 692, 'Parroquia - Uzbekistán'),
(1370, 693, 'Parroquia - Vanuatu'),
(1371, 694, 'Parroquia - Vietnam'),
(1372, 695, 'Parroquia - Islas Vírgenes Británicas'),
(1373, 696, 'Parroquia - Islas Vírgenes de los Estados Unidos'),
(1374, 697, 'Parroquia - Wallis y Futuna'),
(1375, 698, 'Parroquia - Yemen'),
(1376, 699, 'Parroquia - Yibuti'),
(1377, 700, 'Parroquia - Zambia'),
(1378, 701, 'Parroquia - Zimbabue');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodo_escolar`
--

CREATE TABLE `periodo_escolar` (
  `cod_periodo` int(11) NOT NULL,
  `periodo` char(9) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_ini` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `cierre_sis` date NOT NULL,
  `estatus` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'A',
  `prom` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `periodo_escolar`
--

INSERT INTO `periodo_escolar` (`cod_periodo`, `periodo`, `fecha_ini`, `fecha_fin`, `cierre_sis`, `estatus`, `prom`) VALUES
(1, '2020-2021', '2021-02-04', '2021-02-28', '0000-00-00', 'A', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `cod_permiso` int(11) NOT NULL,
  `cod_per` int(11) NOT NULL,
  `modo` char(1) COLLATE utf8_spanish_ci NOT NULL COMMENT 'habiles o continuos',
  `fecha` date NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `cod_per` int(11) NOT NULL,
  `tipo_documento` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `cedula` char(13) COLLATE utf8_spanish_ci NOT NULL,
  `nom1` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `nom2` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `ape1` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `ape2` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `sexo` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `nacionalidad` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_nac` date NOT NULL,
  `email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `foto` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `vive` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`cod_per`, `tipo_documento`, `cedula`, `nom1`, `nom2`, `ape1`, `ape2`, `sexo`, `nacionalidad`, `fecha_nac`, `email`, `foto`, `vive`) VALUES
(1, 'V', 'AdminC', 'AdminC', '', '', '', '', '', '0000-00-00', '', '', 'S'),
(2, '', 'indefinido', 'indefinido', 'indefinido', 'indefinido', '', '', '', '0000-00-00', '', '', 'S'),
(3, 'V', '5953048', 'JORGE', '', 'RIVERO', '', 'M', '', '0000-00-00', '', '', 'S'),
(7, 'V', '25035640', 'JOSE', '', 'DANIEL', '', 'M', '', '0000-00-00', 'josedanielcv1996@gmail.com', '', 'S'),
(8, 'V', '22222222', 'LUIS', '', 'KÜNHERT', '', 'M', '', '0000-00-00', 'luis@gmail.com', '', 'S'),
(9, 'V', '11111111', 'ELIANNYS%S', 'MARIA', 'DOMINGUEZ', 'GABRIELA', 'M', '', '1994-02-11', 'eliannys@gmail.com', '', 'S'),
(10, 'V', '33333333', 'LUIS', '', 'GARCIA', '', 'M', '', '0000-00-00', 'luis@gmail.com', '', 'S'),
(11, 'V', '12963818', 'SUGELY', '', 'VALERA', '', 'F', 'V', '2021-04-10', 'sugely11m@hotmail.com', '', 'S'),
(16, 'V', '', 'JESUS', 'DAVID', 'COLMENAREZ', 'VALERA', 'M', 'V', '2013-02-10', '', '', 'S'),
(17, 'V', '', 'MARIA', 'GABRIELA', 'COLMENAREZ', 'VALERA', 'F', 'V', '2014-03-16', '', '', 'S'),
(19, 'V', '65465465', 'ISAA%C', 'MARIA', 'MARCHAN', 'GABRIELA', 'M', '', '0000-00-00', '', '', 'S');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `cod_per` int(11) NOT NULL,
  `cod_cargo` int(11) NOT NULL,
  `funcion` int(11) NOT NULL,
  `nivel` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '2' COMMENT '1=preescolar, 2=primaria, 3=media',
  `estatus` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`cod_per`, `cod_cargo`, `funcion`, `nivel`, `estatus`) VALUES
(3, 1, 1, '', 'A'),
(7, 5, 1, '', 'A'),
(8, 5, 1, '2', 'A'),
(9, 5, 2, '2', 'A'),
(10, 5, 1, '', 'A'),
(19, 9, 17, '2', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plantel`
--

CREATE TABLE `plantel` (
  `cod_director` int(11) NOT NULL,
  `nom_escuela` text COLLATE utf8_spanish_ci NOT NULL,
  `edo` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `mun` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `zonaeduc` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `codplantel` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `coddea` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `codestco` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` char(12) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `plantel`
--

INSERT INTO `plantel` (`cod_director`, `nom_escuela`, `edo`, `mun`, `zonaeduc`, `codplantel`, `coddea`, `codestco`, `direccion`, `correo`, `telefono`) VALUES
(3, 'Unidad Educativa Nacional Bolivariana “Samuel Robinson”', 'Portuguesa', 'Araure', 'Portuguesa', '007950586', 'OD04051802', '181416', 'Final Avenida Principal de la Urb. “Villas del Pilar”, con Calle 10 y 11 frente a los Tetras.', 'samuelrobinson.2005@gmail.com', '0255-6655264');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_ap`
--

CREATE TABLE `proyecto_ap` (
  `cod_proyecto` int(11) NOT NULL,
  `cod_seccion` int(11) NOT NULL,
  `cod_lapso` int(11) NOT NULL,
  `nom_pa` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `duracion` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `estatus` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `proyecto_ap`
--

INSERT INTO `proyecto_ap` (`cod_proyecto`, `cod_seccion`, `cod_lapso`, `nom_pa`, `duracion`, `estatus`) VALUES
(1, 1, 1, 'Proyecto 1', '3 MESES', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reposo`
--

CREATE TABLE `reposo` (
  `cod_reposo` int(11) NOT NULL,
  `cod_per` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `reposo`
--

INSERT INTO `reposo` (`cod_reposo`, `cod_per`, `fecha`, `desde`, `hasta`, `descripcion`) VALUES
(1, 10, '2021-04-15', '2021-04-24', '2021-04-25', 'enfermo'),
(2, 10, '2021-04-25', '2021-04-27', '2021-04-28', 'medico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representante`
--

CREATE TABLE `representante` (
  `cod_per` int(11) NOT NULL,
  `grado_instr` int(11) NOT NULL,
  `ocupacion` int(11) NOT NULL,
  `observacion` varchar(250) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `representante`
--

INSERT INTO `representante` (`cod_per`, `grado_instr`, `ocupacion`, `observacion`) VALUES
(2, 1, 1, 'importante no eliminar este registro'),
(11, 1, 1, 'sin observaciones');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retiro`
--

CREATE TABLE `retiro` (
  `cod_retiro` int(11) NOT NULL,
  `cod_insc` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `causa` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `observacion` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `estatus` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seccion`
--

CREATE TABLE `seccion` (
  `cod_seccion` int(11) NOT NULL,
  `periodo_esc` int(11) NOT NULL,
  `grado` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `letra` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `cupos` int(2) NOT NULL,
  `docente` int(11) NOT NULL,
  `aula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `seccion`
--

INSERT INTO `seccion` (`cod_seccion`, `periodo_esc`, `grado`, `letra`, `cupos`, `docente`, `aula`) VALUES
(1, 1, '1', 'A', 34, 7, 1),
(2, 1, '2', 'B', 36, 8, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `cod_servicio` int(11) NOT NULL,
  `nom_servicio` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `cod_modulo` int(11) NOT NULL,
  `icono` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `estatus` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'A',
  `link` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `mostrar_menu` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `pos` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`cod_servicio`, `nom_servicio`, `cod_modulo`, `icono`, `estatus`, `link`, `mostrar_menu`, `pos`) VALUES
(12, 'Datos Personales', 13, 'user', 'A', 'Estudiante=consultar', 'S', 1),
(13, 'Inscripción Regular', 13, 'edit', 'A', 'Estudiante=consultar&modo=regular', 'S', 2),
(14, 'Inscripción Nuevo Ing.', 13, 'edit', 'A', 'Estudiante=registrar&modo=nuevo', 'S', 3),
(15, 'Representante', 13, 'user', 'A', 'Representante=consultar', 'S', 5),
(18, 'Solicitud', 14, 'doc-text', 'A', 'ver=solicitud', 'S', 0),
(19, 'Matrícula', 14, 'doc-text', 'A', 'ver=matricula', 'S', 0),
(20, 'Boletín', 14, 'doc-text', 'A', 'ver=boletin', 'S', 0),
(21, 'Resumen', 14, 'doc-text', 'A', 'ver=resumen', 'S', 0),
(22, 'Año Escolar', 15, 'th-list', 'A', 'ver=a_escolar', 'S', 1),
(23, 'Lapso', 15, 'th-list', 'A', 'ver=lapsos', 'S', 3),
(24, 'Sección', 15, 'th-list', 'A', 'ver=seccion', 'S', 4),
(25, 'Indicador', 15, 'th-list', 'A', 'ver=indicadores', 'S', 5),
(26, 'Aulas De Clase', 15, 'th-list', 'A', 'ver=aulas', 'S', 6),
(27, 'Plantel', 15, 'edit', 'A', 'ver=plantel', 'S', 7),
(28, 'Enfermedad', 15, 'th-list', 'A', 'ver=enfermedad', 'S', 8),
(29, 'Vacuna', 15, 'th-list', 'A', 'ver=vacunas', 'S', 9),
(30, 'Ocupación', 15, 'th-list', 'A', 'ver=ocupacion', 'S', 10),
(31, 'Manuales', 16, 'help', 'A', 'ver=ayuda', 'S', 0),
(32, 'Cargar Notas', 13, 'edit', 'A', 'ver=lista_matricula', 'S', 6),
(33, 'Retiro', 13, 'logout', 'A', 'ver=retiro', 'S', 7),
(35, 'Nomina De Representantes', 14, 'doc-text', 'A', 'ver=nomina_rep', 'S', 0),
(36, 'Datos Del Personal', 17, 'user', 'A', 'Personal=consultar', 'S', 1),
(37, 'Lista Del Personal', 17, 'user', 'A', 'ver=personal', 'S', 2),
(41, 'Días Hábiles', 15, 'th-list', 'A', 'ver=dias_habiles', 'S', 2),
(43, 'Asistencia', 17, 'th-list', 'A', 'ver=asistencia_personal', 'S', 3),
(44, 'Permisos', 17, 'thlist', 'A', 'ver=permiso_personal', 'S', 4),
(45, 'Reposos', 17, 'thlist', 'A', 'ver=reposo_personal', 'S', 5),
(46, 'Asistencia', 13, 'th-list', 'A', 'ver=asistencia_estudiante', 'S', 4),
(47, 'Administrar Usuarios', 18, 'user', 'A', 'ver=usuarios', 'S', 0),
(48, 'Auditoría', 18, 'th-list', 'A', 'ver=auditoria', 'S', 0),
(49, 'Asistencia', 14, 'doctext', 'A', 'ver=reportes_asistencias', 'S', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefono`
--

CREATE TABLE `telefono` (
  `cod_tlf` int(11) NOT NULL,
  `numero` char(12) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `telefono`
--

INSERT INTO `telefono` (`cod_tlf`, `numero`) VALUES
(1, '0416-1223403'),
(2, '0416-9326737'),
(3, '0255-6359096'),
(4, '0212-6666666'),
(5, '02126666666');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefono_persona`
--

CREATE TABLE `telefono_persona` (
  `cod_tlf` int(11) NOT NULL,
  `cod_per` int(11) NOT NULL,
  `tipo` char(1) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `telefono_persona`
--

INSERT INTO `telefono_persona` (`cod_tlf`, `cod_per`, `tipo`) VALUES
(1, 3, 'M'),
(2, 7, 'M'),
(2, 8, 'M'),
(4, 9, 'M'),
(2, 10, 'M'),
(2, 11, 'M'),
(3, 11, 'F'),
(3, 11, 'T'),
(4, 17, 'M'),
(2, 19, 'M');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_vivienda`
--

CREATE TABLE `tipo_vivienda` (
  `cod_tipo_vda` int(11) NOT NULL,
  `nom_tipo_vda` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_vivienda`
--

INSERT INTO `tipo_vivienda` (`cod_tipo_vda`, `nom_tipo_vda`) VALUES
(1, 'Apartamento'),
(2, 'Aparto-Quinta'),
(3, 'Casa'),
(4, 'Casa de Vecindad'),
(5, 'Casa-Quinta'),
(6, 'Improvisada'),
(7, 'Quinta'),
(8, 'Rancho Rural'),
(9, 'Rancho Urbano'),
(10, 'Refugio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `cod_per` int(11) NOT NULL,
  `cod_usu` char(9) COLLATE utf8_spanish_ci NOT NULL,
  `clave` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `cod_nivel` int(11) NOT NULL,
  `preg1` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `resp1` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `preg2` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `resp2` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_cambio_clave` datetime NOT NULL,
  `ult_conex` datetime NOT NULL,
  `estatus` char(1) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`cod_per`, `cod_usu`, `clave`, `cod_nivel`, `preg1`, `resp1`, `preg2`, `resp2`, `fecha_cambio_clave`, `ult_conex`, `estatus`) VALUES
(9, 'V11111111', '$2y$10$XdtAXD/Mxm1CX6zSqydISOZ4uF7LJnlXKxLGAtfMwB5t.ZpO60MJ2', 4, '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'I'),
(8, 'V22222222', '$2y$10$F7ufgThYFDCCjbV3lt0eqe7TUw.sn2VAVAWoeG71igrIjfJcacjsm', 4, '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'A'),
(7, 'V25035640', '$2y$10$QRreJwiS9rh/SBexS/8lgOqBgI/J12ZVt0Xzv62UMLf/bJpgDPRbe', 4, '', '', '', '', '0000-00-00 00:00:00', '2021-04-03 23:18:57', 'A'),
(10, 'V33333333', '$2y$10$yd35zOhQRl9gTx92JItGGuxxEQ0UF/RVrgHIgRHcrvr./o2cVE4R6', 4, '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'A'),
(3, 'V5953048', '$2y$10$M3XXswBi97tzabsdIUz3JOykxrFXBtwh.qD0dZBfSDHG5Fc2ydhqS', 2, '', '', '', '', '0000-00-00 00:00:00', '2021-03-21 23:15:08', 'A'),
(19, 'V65465465', '$2y$10$5ebEVTgE1rZhgiL16AGIxuQMweJiXYDwXPlMdH3Srss6xav/vspvi', 4, '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'A'),
(1, 'VAdminC', '$2y$10$8wORhxsEaYl/qAndXcX/5eAovd4rhE5rr/ahbYVS8osKiZItBbO2W', 1, '', '', '', '', '0000-00-00 00:00:00', '2021-04-26 01:49:05', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacuna`
--

CREATE TABLE `vacuna` (
  `cod_vcna` int(11) NOT NULL,
  `nom_vcna` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `vacuna`
--

INSERT INTO `vacuna` (`cod_vcna`, `nom_vcna`) VALUES
(1, 'Covid-19');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD KEY `cod_per` (`cod_per`),
  ADD KEY `cod_diahl` (`cod_diahbl`);

--
-- Indices de la tabla `aula`
--
ALTER TABLE `aula`
  ADD PRIMARY KEY (`cod_aula`),
  ADD UNIQUE KEY `nom_aula` (`nom_aula`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`cod_cargo`);

--
-- Indices de la tabla `cond_infraestructura`
--
ALTER TABLE `cond_infraestructura`
  ADD PRIMARY KEY (`cod_cond_inf`);

--
-- Indices de la tabla `cond_vivienda`
--
ALTER TABLE `cond_vivienda`
  ADD PRIMARY KEY (`cod_cond_vnda`);

--
-- Indices de la tabla `conf_periodo`
--
ALTER TABLE `conf_periodo`
  ADD PRIMARY KEY (`cod_periodo`),
  ADD KEY `cod_periodo` (`cod_periodo`);

--
-- Indices de la tabla `datos_socioeconomicos`
--
ALTER TABLE `datos_socioeconomicos`
  ADD PRIMARY KEY (`cod_est`),
  ADD KEY `tipo_vnda` (`tipo_vnda`),
  ADD KEY `condicion_vnda` (`condicion_vnda`),
  ADD KEY `infraestructura_vnda` (`infraestructura_vnda`);

--
-- Indices de la tabla `dia_habil`
--
ALTER TABLE `dia_habil`
  ADD PRIMARY KEY (`cod_diahbl`),
  ADD KEY `cod_periodo` (`cod_periodo`);

--
-- Indices de la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD PRIMARY KEY (`cod_dir`),
  ADD KEY `parroquia` (`parroquia`);

--
-- Indices de la tabla `direccion_persona`
--
ALTER TABLE `direccion_persona`
  ADD KEY `cod_dir2` (`cod_dir`),
  ADD KEY `cod_per` (`cod_per`);

--
-- Indices de la tabla `discapacidad`
--
ALTER TABLE `discapacidad`
  ADD PRIMARY KEY (`cod_discpd`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD UNIQUE KEY `cod_est_2` (`cod_est`),
  ADD KEY `cod_est` (`cod_est`);

--
-- Indices de la tabla `enfermedad`
--
ALTER TABLE `enfermedad`
  ADD PRIMARY KEY (`cod_enf`),
  ADD UNIQUE KEY `nom_enf` (`nom_enf`);

--
-- Indices de la tabla `enf_padecida`
--
ALTER TABLE `enf_padecida`
  ADD KEY `cod_enf2` (`cod_enf`),
  ADD KEY `cod_est3` (`cod_est`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`cod_edo`),
  ADD KEY `pais` (`pais`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`ced_esc`),
  ADD UNIQUE KEY `cod_per_2` (`cod_per`),
  ADD KEY `cod_per` (`cod_per`),
  ADD KEY `discapacidad` (`discapacidad`),
  ADD KEY `cod_madre` (`cod_madre`),
  ADD KEY `cod_padre` (`cod_padre`),
  ADD KEY `lugar_nac` (`lugar_nac`);

--
-- Indices de la tabla `est_vacuna`
--
ALTER TABLE `est_vacuna`
  ADD KEY `cod_est2` (`cod_est`),
  ADD KEY `cod_vcna2` (`cod_vcna`);

--
-- Indices de la tabla `funcion`
--
ALTER TABLE `funcion`
  ADD PRIMARY KEY (`cod_funcion`),
  ADD UNIQUE KEY `nom_funcion` (`nom_funcion`);

--
-- Indices de la tabla `grado_instruccion`
--
ALTER TABLE `grado_instruccion`
  ADD PRIMARY KEY (`cod_ginst`);

--
-- Indices de la tabla `indicador`
--
ALTER TABLE `indicador`
  ADD PRIMARY KEY (`cod_ind`),
  ADD KEY `cod_lapso` (`cod_proyecto`);

--
-- Indices de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD PRIMARY KEY (`cod_insc`),
  ADD KEY `seccion` (`seccion`),
  ADD KEY `ci_est` (`cod_est`),
  ADD KEY `cod_rep` (`cod_rep`),
  ADD KEY `escuela_proc` (`escuela_proc`);

--
-- Indices de la tabla `intentos_clv`
--
ALTER TABLE `intentos_clv`
  ADD KEY `cod_usu` (`cod_usu`);

--
-- Indices de la tabla `intentos_preg`
--
ALTER TABLE `intentos_preg`
  ADD KEY `cod_usu` (`cod_usu`);

--
-- Indices de la tabla `lapso`
--
ALTER TABLE `lapso`
  ADD PRIMARY KEY (`cod_lapso`),
  ADD KEY `cod_periodo` (`cod_periodo`);

--
-- Indices de la tabla `lugar_nacimiento`
--
ALTER TABLE `lugar_nacimiento`
  ADD PRIMARY KEY (`cod_lugar_nac`),
  ADD KEY `parroquia` (`cod_parr`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`cod_modulo`),
  ADD UNIQUE KEY `nom_modulo` (`nom_modulo`);

--
-- Indices de la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD PRIMARY KEY (`cod_mun`),
  ADD KEY `fk_municipios_1_idx` (`estado`);

--
-- Indices de la tabla `nivel`
--
ALTER TABLE `nivel`
  ADD PRIMARY KEY (`cod_nivel`),
  ADD UNIQUE KEY `nom_nivel` (`nom_nivel`);

--
-- Indices de la tabla `nivel_metodo`
--
ALTER TABLE `nivel_metodo`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `cod_nivel` (`cod_nivel`),
  ADD KEY `cod_metodo` (`cod_servicio`);

--
-- Indices de la tabla `nota_final`
--
ALTER TABLE `nota_final`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `ced_esc` (`cod_insc`);

--
-- Indices de la tabla `nota_indicador`
--
ALTER TABLE `nota_indicador`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `ced_esc` (`cod_insc`),
  ADD KEY `cod_ind` (`cod_ind`);

--
-- Indices de la tabla `nota_lapso`
--
ALTER TABLE `nota_lapso`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `cod_est` (`cod_insc`),
  ADD KEY `cod_lapso` (`cod_lapso`);

--
-- Indices de la tabla `ocupacion`
--
ALTER TABLE `ocupacion`
  ADD PRIMARY KEY (`cod_ocup`),
  ADD UNIQUE KEY `nom_ocup` (`nom_ocup`);

--
-- Indices de la tabla `paises`
--
ALTER TABLE `paises`
  ADD PRIMARY KEY (`cod_pais`);

--
-- Indices de la tabla `parroquia`
--
ALTER TABLE `parroquia`
  ADD PRIMARY KEY (`cod_parr`),
  ADD KEY `fk_parroquias_1_idx` (`municipio`);

--
-- Indices de la tabla `periodo_escolar`
--
ALTER TABLE `periodo_escolar`
  ADD PRIMARY KEY (`cod_periodo`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`cod_permiso`),
  ADD KEY `cod_per` (`cod_per`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`cod_per`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`cod_per`),
  ADD KEY `cod_cargo` (`cod_cargo`),
  ADD KEY `funcion` (`funcion`);

--
-- Indices de la tabla `plantel`
--
ALTER TABLE `plantel`
  ADD UNIQUE KEY `cod_director_2` (`cod_director`),
  ADD KEY `cod_director` (`cod_director`);

--
-- Indices de la tabla `proyecto_ap`
--
ALTER TABLE `proyecto_ap`
  ADD PRIMARY KEY (`cod_proyecto`),
  ADD KEY `cod_lapso` (`cod_lapso`),
  ADD KEY `cod_seccion` (`cod_seccion`);

--
-- Indices de la tabla `reposo`
--
ALTER TABLE `reposo`
  ADD PRIMARY KEY (`cod_reposo`),
  ADD KEY `cod_per` (`cod_per`);

--
-- Indices de la tabla `representante`
--
ALTER TABLE `representante`
  ADD PRIMARY KEY (`cod_per`),
  ADD KEY `grado_instr` (`grado_instr`),
  ADD KEY `ocupacion` (`ocupacion`);

--
-- Indices de la tabla `retiro`
--
ALTER TABLE `retiro`
  ADD PRIMARY KEY (`cod_retiro`),
  ADD KEY `cod_est` (`cod_insc`);

--
-- Indices de la tabla `seccion`
--
ALTER TABLE `seccion`
  ADD PRIMARY KEY (`cod_seccion`),
  ADD KEY `docente` (`docente`),
  ADD KEY `aula` (`aula`),
  ADD KEY `periodo_esc` (`periodo_esc`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`cod_servicio`),
  ADD KEY `cod_modulo` (`cod_modulo`);

--
-- Indices de la tabla `telefono`
--
ALTER TABLE `telefono`
  ADD PRIMARY KEY (`cod_tlf`);

--
-- Indices de la tabla `telefono_persona`
--
ALTER TABLE `telefono_persona`
  ADD KEY `cod_tlf` (`cod_tlf`),
  ADD KEY `cod_per` (`cod_per`);

--
-- Indices de la tabla `tipo_vivienda`
--
ALTER TABLE `tipo_vivienda`
  ADD PRIMARY KEY (`cod_tipo_vda`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`cod_usu`),
  ADD UNIQUE KEY `cod_per_2` (`cod_per`),
  ADD KEY `cod_per` (`cod_per`),
  ADD KEY `cod_nivel` (`cod_nivel`);

--
-- Indices de la tabla `vacuna`
--
ALTER TABLE `vacuna`
  ADD PRIMARY KEY (`cod_vcna`),
  ADD UNIQUE KEY `nom_vcna` (`nom_vcna`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `aula`
--
ALTER TABLE `aula`
  MODIFY `cod_aula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `cod_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `cond_infraestructura`
--
ALTER TABLE `cond_infraestructura`
  MODIFY `cod_cond_inf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cond_vivienda`
--
ALTER TABLE `cond_vivienda`
  MODIFY `cod_cond_vnda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `direccion`
--
ALTER TABLE `direccion`
  MODIFY `cod_dir` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `discapacidad`
--
ALTER TABLE `discapacidad`
  MODIFY `cod_discpd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `enfermedad`
--
ALTER TABLE `enfermedad`
  MODIFY `cod_enf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `cod_edo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=265;

--
-- AUTO_INCREMENT de la tabla `funcion`
--
ALTER TABLE `funcion`
  MODIFY `cod_funcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `indicador`
--
ALTER TABLE `indicador`
  MODIFY `cod_ind` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `cod_insc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `lapso`
--
ALTER TABLE `lapso`
  MODIFY `cod_lapso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `lugar_nacimiento`
--
ALTER TABLE `lugar_nacimiento`
  MODIFY `cod_lugar_nac` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `cod_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `municipio`
--
ALTER TABLE `municipio`
  MODIFY `cod_mun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=702;

--
-- AUTO_INCREMENT de la tabla `nivel`
--
ALTER TABLE `nivel`
  MODIFY `cod_nivel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `nota_final`
--
ALTER TABLE `nota_final`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nota_indicador`
--
ALTER TABLE `nota_indicador`
  MODIFY `codigo` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nota_lapso`
--
ALTER TABLE `nota_lapso`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ocupacion`
--
ALTER TABLE `ocupacion`
  MODIFY `cod_ocup` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `paises`
--
ALTER TABLE `paises`
  MODIFY `cod_pais` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT de la tabla `parroquia`
--
ALTER TABLE `parroquia`
  MODIFY `cod_parr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1379;

--
-- AUTO_INCREMENT de la tabla `periodo_escolar`
--
ALTER TABLE `periodo_escolar`
  MODIFY `cod_periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `cod_permiso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `cod_per` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `proyecto_ap`
--
ALTER TABLE `proyecto_ap`
  MODIFY `cod_proyecto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `reposo`
--
ALTER TABLE `reposo`
  MODIFY `cod_reposo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `retiro`
--
ALTER TABLE `retiro`
  MODIFY `cod_retiro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `seccion`
--
ALTER TABLE `seccion`
  MODIFY `cod_seccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `cod_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `telefono`
--
ALTER TABLE `telefono`
  MODIFY `cod_tlf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo_vivienda`
--
ALTER TABLE `tipo_vivienda`
  MODIFY `cod_tipo_vda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `vacuna`
--
ALTER TABLE `vacuna`
  MODIFY `cod_vcna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`cod_per`) REFERENCES `persona` (`cod_per`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asistencia_ibfk_2` FOREIGN KEY (`cod_diahbl`) REFERENCES `dia_habil` (`cod_diahbl`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `conf_periodo`
--
ALTER TABLE `conf_periodo`
  ADD CONSTRAINT `conf_periodo_ibfk_1` FOREIGN KEY (`cod_periodo`) REFERENCES `periodo_escolar` (`cod_periodo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `datos_socioeconomicos`
--
ALTER TABLE `datos_socioeconomicos`
  ADD CONSTRAINT `datos_socioeconomicos_ibfk_2` FOREIGN KEY (`tipo_vnda`) REFERENCES `tipo_vivienda` (`cod_tipo_vda`) ON UPDATE CASCADE,
  ADD CONSTRAINT `datos_socioeconomicos_ibfk_3` FOREIGN KEY (`condicion_vnda`) REFERENCES `cond_vivienda` (`cod_cond_vnda`) ON UPDATE CASCADE,
  ADD CONSTRAINT `datos_socioeconomicos_ibfk_4` FOREIGN KEY (`infraestructura_vnda`) REFERENCES `cond_infraestructura` (`cod_cond_inf`) ON UPDATE CASCADE,
  ADD CONSTRAINT `datos_socioeconomicos_ibfk_5` FOREIGN KEY (`cod_est`) REFERENCES `estudiante` (`cod_per`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `dia_habil`
--
ALTER TABLE `dia_habil`
  ADD CONSTRAINT `dia_habil_ibfk_1` FOREIGN KEY (`cod_periodo`) REFERENCES `periodo_escolar` (`cod_periodo`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD CONSTRAINT `direccion_ibfk_2` FOREIGN KEY (`parroquia`) REFERENCES `parroquia` (`cod_parr`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `direccion_persona`
--
ALTER TABLE `direccion_persona`
  ADD CONSTRAINT `direccion_persona_ibfk_1` FOREIGN KEY (`cod_dir`) REFERENCES `direccion` (`cod_dir`) ON UPDATE CASCADE,
  ADD CONSTRAINT `direccion_persona_ibfk_2` FOREIGN KEY (`cod_per`) REFERENCES `persona` (`cod_per`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`cod_est`) REFERENCES `estudiante` (`cod_per`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `enf_padecida`
--
ALTER TABLE `enf_padecida`
  ADD CONSTRAINT `enf_padecida_ibfk_3` FOREIGN KEY (`cod_est`) REFERENCES `estudiante` (`cod_per`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `enf_padecida_ibfk_4` FOREIGN KEY (`cod_enf`) REFERENCES `enfermedad` (`cod_enf`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estado`
--
ALTER TABLE `estado`
  ADD CONSTRAINT `estado_ibfk_1` FOREIGN KEY (`pais`) REFERENCES `paises` (`cod_pais`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `estudiante_ibfk_2` FOREIGN KEY (`cod_per`) REFERENCES `persona` (`cod_per`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `estudiante_ibfk_3` FOREIGN KEY (`discapacidad`) REFERENCES `discapacidad` (`cod_discpd`) ON UPDATE CASCADE,
  ADD CONSTRAINT `estudiante_ibfk_4` FOREIGN KEY (`lugar_nac`) REFERENCES `lugar_nacimiento` (`cod_lugar_nac`) ON UPDATE CASCADE,
  ADD CONSTRAINT `estudiante_ibfk_5` FOREIGN KEY (`cod_madre`) REFERENCES `representante` (`cod_per`) ON UPDATE CASCADE,
  ADD CONSTRAINT `estudiante_ibfk_6` FOREIGN KEY (`cod_padre`) REFERENCES `representante` (`cod_per`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `est_vacuna`
--
ALTER TABLE `est_vacuna`
  ADD CONSTRAINT `est_vacuna_ibfk_2` FOREIGN KEY (`cod_est`) REFERENCES `estudiante` (`cod_per`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `est_vacuna_ibfk_3` FOREIGN KEY (`cod_vcna`) REFERENCES `vacuna` (`cod_vcna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `indicador`
--
ALTER TABLE `indicador`
  ADD CONSTRAINT `indicador_ibfk_1` FOREIGN KEY (`cod_proyecto`) REFERENCES `proyecto_ap` (`cod_proyecto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`seccion`) REFERENCES `seccion` (`cod_seccion`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inscripcion_ibfk_4` FOREIGN KEY (`cod_rep`) REFERENCES `representante` (`cod_per`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inscripcion_ibfk_5` FOREIGN KEY (`cod_est`) REFERENCES `estudiante` (`cod_per`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `intentos_clv`
--
ALTER TABLE `intentos_clv`
  ADD CONSTRAINT `intentos_clv_ibfk_1` FOREIGN KEY (`cod_usu`) REFERENCES `usuario` (`cod_usu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `intentos_preg`
--
ALTER TABLE `intentos_preg`
  ADD CONSTRAINT `intentos_preg_ibfk_1` FOREIGN KEY (`cod_usu`) REFERENCES `usuario` (`cod_usu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `lapso`
--
ALTER TABLE `lapso`
  ADD CONSTRAINT `lapso_ibfk_1` FOREIGN KEY (`cod_periodo`) REFERENCES `periodo_escolar` (`cod_periodo`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `lugar_nacimiento`
--
ALTER TABLE `lugar_nacimiento`
  ADD CONSTRAINT `lugar_nacimiento_ibfk_1` FOREIGN KEY (`cod_parr`) REFERENCES `parroquia` (`cod_parr`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD CONSTRAINT `fk_municipios_1` FOREIGN KEY (`estado`) REFERENCES `estado` (`cod_edo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `nivel_metodo`
--
ALTER TABLE `nivel_metodo`
  ADD CONSTRAINT `nivel_metodo_ibfk_1` FOREIGN KEY (`cod_nivel`) REFERENCES `nivel` (`cod_nivel`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nivel_metodo_ibfk_2` FOREIGN KEY (`cod_servicio`) REFERENCES `servicio` (`cod_servicio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `nota_final`
--
ALTER TABLE `nota_final`
  ADD CONSTRAINT `nota_final_ibfk_1` FOREIGN KEY (`cod_insc`) REFERENCES `inscripcion` (`cod_insc`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `nota_indicador`
--
ALTER TABLE `nota_indicador`
  ADD CONSTRAINT `nota_indicador_ibfk_1` FOREIGN KEY (`cod_ind`) REFERENCES `indicador` (`cod_ind`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nota_indicador_ibfk_2` FOREIGN KEY (`cod_insc`) REFERENCES `inscripcion` (`cod_insc`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `nota_lapso`
--
ALTER TABLE `nota_lapso`
  ADD CONSTRAINT `nota_lapso_ibfk_2` FOREIGN KEY (`cod_lapso`) REFERENCES `lapso` (`cod_lapso`) ON UPDATE CASCADE,
  ADD CONSTRAINT `nota_lapso_ibfk_3` FOREIGN KEY (`cod_insc`) REFERENCES `inscripcion` (`cod_insc`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `parroquia`
--
ALTER TABLE `parroquia`
  ADD CONSTRAINT `fk_parroquias_1` FOREIGN KEY (`municipio`) REFERENCES `municipio` (`cod_mun`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD CONSTRAINT `permiso_ibfk_1` FOREIGN KEY (`cod_per`) REFERENCES `personal` (`cod_per`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `personal`
--
ALTER TABLE `personal`
  ADD CONSTRAINT `personal_ibfk_1` FOREIGN KEY (`cod_per`) REFERENCES `persona` (`cod_per`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personal_ibfk_2` FOREIGN KEY (`cod_cargo`) REFERENCES `cargo` (`cod_cargo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `personal_ibfk_3` FOREIGN KEY (`funcion`) REFERENCES `funcion` (`cod_funcion`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `plantel`
--
ALTER TABLE `plantel`
  ADD CONSTRAINT `plantel_ibfk_1` FOREIGN KEY (`cod_director`) REFERENCES `personal` (`cod_per`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `proyecto_ap`
--
ALTER TABLE `proyecto_ap`
  ADD CONSTRAINT `proyecto_ap_ibfk_1` FOREIGN KEY (`cod_lapso`) REFERENCES `lapso` (`cod_lapso`) ON UPDATE CASCADE,
  ADD CONSTRAINT `proyecto_ap_ibfk_2` FOREIGN KEY (`cod_seccion`) REFERENCES `seccion` (`cod_seccion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reposo`
--
ALTER TABLE `reposo`
  ADD CONSTRAINT `reposo_ibfk_1` FOREIGN KEY (`cod_per`) REFERENCES `personal` (`cod_per`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `representante`
--
ALTER TABLE `representante`
  ADD CONSTRAINT `representante_ibfk_1` FOREIGN KEY (`grado_instr`) REFERENCES `grado_instruccion` (`cod_ginst`) ON UPDATE CASCADE,
  ADD CONSTRAINT `representante_ibfk_2` FOREIGN KEY (`ocupacion`) REFERENCES `ocupacion` (`cod_ocup`) ON UPDATE CASCADE,
  ADD CONSTRAINT `representante_ibfk_3` FOREIGN KEY (`cod_per`) REFERENCES `persona` (`cod_per`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `retiro`
--
ALTER TABLE `retiro`
  ADD CONSTRAINT `retiro_ibfk_3` FOREIGN KEY (`cod_insc`) REFERENCES `inscripcion` (`cod_insc`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `seccion`
--
ALTER TABLE `seccion`
  ADD CONSTRAINT `seccion_ibfk_1` FOREIGN KEY (`aula`) REFERENCES `aula` (`cod_aula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `seccion_ibfk_2` FOREIGN KEY (`docente`) REFERENCES `personal` (`cod_per`) ON UPDATE CASCADE,
  ADD CONSTRAINT `seccion_ibfk_3` FOREIGN KEY (`periodo_esc`) REFERENCES `periodo_escolar` (`cod_periodo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD CONSTRAINT `servicio_ibfk_1` FOREIGN KEY (`cod_modulo`) REFERENCES `modulo` (`cod_modulo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `telefono_persona`
--
ALTER TABLE `telefono_persona`
  ADD CONSTRAINT `telefono_persona_ibfk_2` FOREIGN KEY (`cod_tlf`) REFERENCES `telefono` (`cod_tlf`) ON UPDATE CASCADE,
  ADD CONSTRAINT `telefono_persona_ibfk_3` FOREIGN KEY (`cod_per`) REFERENCES `persona` (`cod_per`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`cod_per`) REFERENCES `persona` (`cod_per`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`cod_nivel`) REFERENCES `nivel` (`cod_nivel`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
