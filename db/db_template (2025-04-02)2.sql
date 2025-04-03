-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-04-2025 a las 06:02:54
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
-- Base de datos: `db_template`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `folio` varchar(9) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `c_tipo_act` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `c_prioridad` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `c_clasifica_act` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `descripcion` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `comentario` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `notas` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `dispositivo` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_usuario_resp` int(11) DEFAULT NULL,
  `f_plan_i` date DEFAULT NULL,
  `f_plan_f` date DEFAULT NULL,
  `fh_captura` datetime NOT NULL,
  `id_usuario_captura` int(11) NOT NULL,
  `fh_finaliza` datetime DEFAULT NULL,
  `id_usuario_finaliza` int(11) DEFAULT NULL,
  `calificacion` int(11) NOT NULL,
  `avance` int(3) NOT NULL DEFAULT 0,
  `c_estatus` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `folio`, `c_tipo_act`, `id_cliente`, `c_prioridad`, `c_clasifica_act`, `descripcion`, `comentario`, `notas`, `dispositivo`, `id_usuario_resp`, `f_plan_i`, `f_plan_f`, `fh_captura`, `id_usuario_captura`, `fh_finaliza`, `id_usuario_finaliza`, `calificacion`, `avance`, `c_estatus`) VALUES
(1, '25T030001', 'TICK', 1, 'U', 'CCTV', 'pruebas', '', '', '', 1, NULL, NULL, '2025-03-22 00:04:17', 5, NULL, 5, 0, 50, 'A'),
(15, '25T030003', 'TICK', 1, '1', 'CCTV', '<p>ddddd</p>', '', '', '', 1, NULL, NULL, '2025-03-29 22:51:47', 1, NULL, NULL, 0, 0, 'A'),
(21, '25T030004', 'TICK', 1, '2', 'CCTV', '<p>prueba</p>', '', '', '', NULL, NULL, NULL, '2025-03-30 12:52:20', 1, NULL, NULL, 0, 0, 'A'),
(23, '25P030001', 'PROY', 1, '3', 'CCTV', '<p>prueba</p>', '', '', '', 23, NULL, NULL, '2025-03-30 12:55:44', 5, NULL, NULL, 0, 0, 'A'),
(24, '25L030001', 'LEVA', 1, '2', 'REDS', '<p><font color=\"#000000\"><span style=\"background-color: rgb(255, 255, 0);\">ssdsadasdasdasd</span></font></p>', '', '', '', 5, NULL, NULL, '2025-03-30 12:57:19', 5, NULL, NULL, 0, 0, 'A'),
(25, '25L030002', 'LEVA', 1, '1', 'CCTV', '<p>prueba</p>', '', '', '', 5, NULL, NULL, '2025-03-30 13:00:29', 5, NULL, NULL, 0, 0, 'A'),
(26, '25P030002', 'PROY', 1, '1', 'CCTV', '<p>prueba</p>', '', '', '', 23, NULL, NULL, '2025-03-30 13:00:59', 5, NULL, NULL, 0, 0, 'A'),
(27, '25T030005', 'TICK', 1, '1', 'CCTV', '<p>pruebas</p>', '', '', '', 1, NULL, NULL, '2025-03-30 13:01:58', 5, NULL, NULL, 0, 0, 'A'),
(28, '25T030006', 'TICK', 1, '1', 'CCTV', '<p>pruebas</p>', '', '', '', 5, NULL, NULL, '2025-03-30 13:08:48', 5, NULL, NULL, 0, 0, 'A'),
(29, '25P030003', 'PROY', 1, '2', 'CCTV', '<ul><li><font face=\"Impact\">prueba desc</font></li><li><font face=\"Impact\">....</font></li></ul>', '', '<ol><li style=\"text-align: center; \"><u>Notas</u></li></ol>', '', 5, NULL, NULL, '2025-03-30 13:13:04', 5, NULL, NULL, 0, 0, 'A'),
(30, '25P030004', 'PROY', 1, '2', 'CCTV', '<ul><li><font face=\"Impact\">prueba desc</font></li><li><font face=\"Impact\">....</font></li></ul>', '', '<ol><li style=\"text-align: center; \"><u>Notas</u></li></ol>', '', 5, NULL, NULL, '2025-03-30 13:15:44', 5, NULL, NULL, 0, 0, 'A'),
(31, '25T030007', 'TICK', 1, '2', 'CCTV', '<p><font color=\"#000000\" style=\"background-color: rgb(255, 255, 0);\"><b>ddd</b></font></p>', '<p style=\"text-align: justify; \"><b style=\"background-color: rgb(0, 0, 255);\"><font color=\"#ffe79c\">comentarios</font></b></p>', '', '', 5, NULL, NULL, '2025-03-30 13:17:45', 5, NULL, NULL, 0, 0, 'A'),
(32, '25T030008', 'TICK', 1, '2', 'CCTV', '<p><font color=\"#000000\" style=\"background-color: rgb(255, 255, 0);\"><b>ddd</b></font></p>', '<p style=\"text-align: justify; \"><b style=\"background-color: rgb(0, 0, 255);\"><font color=\"#ffe79c\">comentarios</font></b></p>', '', '', 5, NULL, NULL, '2025-03-30 13:18:43', 5, NULL, NULL, 0, 0, 'A'),
(33, '25L030003', 'LEVA', 1, '2', 'CCTV', '<p>test2321</p>', '', '', '', NULL, '2025-03-01', '2025-03-01', '2025-03-30 13:21:46', 5, NULL, NULL, 0, 0, 'A'),
(34, '25L030004', 'LEVA', 1, '2', 'CCTV', '<p>test2321</p>', '', '', '', NULL, '2025-03-01', '2025-03-30', '2025-03-30 13:22:12', 5, NULL, NULL, 0, 0, 'A'),
(35, '25T030009', 'TICK', 1, '3', 'CCTV', '<p>test</p>', '', '', '', 1, NULL, NULL, '2025-03-30 14:22:00', 5, NULL, NULL, 0, 0, 'A'),
(36, '25P030005', 'PROY', 1, '1', 'REDS', '<p>prueba</p>', '', '', 'serie|mac|otro', 5, NULL, NULL, '2025-03-30 14:27:50', 5, NULL, NULL, 0, 0, 'A'),
(37, '25T040001', 'TICK', 1, '2', 'CCTV', '<ol><li>prueba 010425</li></ol>', '<h1 class=\"\"><b>comentario X</b></h1>', '<p><font color=\"#000000\" style=\"background-color: rgb(255, 255, 0);\">NOTA Y</font></p>', '100210|test|100210', 1, '2025-04-01', '2025-04-05', '2025-04-01 20:13:32', 5, NULL, NULL, 0, 0, 'A'),
(38, '25L040001', 'LEVA', 1, '2', 'CCTV', '<p>pruebas</p>', '<p>test</p>', '', '||', 1, NULL, NULL, '2025-04-01 20:14:59', 5, NULL, NULL, 0, 0, 'A'),
(39, '25T040002', 'TICK', 1, '1', 'CCTV', 'test 010425', '', '', '||', 5, NULL, NULL, '2025-04-01 20:57:28', 5, NULL, NULL, 0, 0, 'A'),
(40, '25P040001', 'PROY', 1, '1', 'CCTV', '<p>......</p>', '', '', '||', 23, NULL, NULL, '2025-04-01 20:58:40', 5, NULL, NULL, 0, 0, 'A'),
(41, '25P040002', 'PROY', 1, '1', 'CCTV', '<p>......</p>', '', '', '||', 23, NULL, NULL, '2025-04-01 21:00:26', 5, NULL, NULL, 0, 0, 'A'),
(42, '25P040003', 'PROY', 1, '1', 'CCTV', '<p>......</p>', '', '', '||', 23, NULL, NULL, '2025-04-01 21:00:50', 5, NULL, NULL, 0, 0, 'A'),
(43, '25T040003', 'TICK', 1, '1', 'CCTV', '<p>.....</p>', '', '', '||', 5, NULL, NULL, '2025-04-01 21:02:38', 5, NULL, NULL, 0, 0, 'A'),
(44, '25T040004', 'TICK', 1, '2', 'CCTV', '<p>..... preuba edit12</p>', 'comenta', 'nota', '1|2|3', 5, '2025-04-01', '2025-04-04', '2025-04-01 21:05:09', 5, NULL, NULL, 0, 0, 'A'),
(45, '25T040005', 'TICK', 1, '2', 'CCTV', '<p>PRUEBA</p>', '', '', '||', 24, NULL, NULL, '2025-04-01 21:42:45', 5, NULL, NULL, 0, 0, 'A'),
(46, '25T040006', 'TICK', 1, '2', 'CCTV', '<p>TE4ST</p>', '', '', '||', 1, NULL, NULL, '2025-04-01 21:43:09', 5, NULL, NULL, 0, 0, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_c_clasificacion`
--

CREATE TABLE `act_c_clasificacion` (
  `id` int(11) NOT NULL,
  `codigo` varchar(4) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fh_registro` datetime DEFAULT NULL,
  `fh_inactivo` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_c_clasificacion`
--

INSERT INTO `act_c_clasificacion` (`id`, `codigo`, `descripcion`, `activo`, `fh_registro`, `fh_inactivo`) VALUES
(1, 'CCTV', 'CCTV', 1, NULL, NULL),
(2, 'REDS', 'Redes', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_c_clientes`
--

CREATE TABLE `act_c_clientes` (
  `id` int(11) NOT NULL,
  `rfc` varchar(13) DEFAULT NULL,
  `alias` varchar(30) NOT NULL,
  `razon_social` varchar(50) NOT NULL,
  `domicilio` varchar(250) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `correo` varchar(200) DEFAULT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  `fh_inactivo` datetime DEFAULT NULL,
  `u_inactivo` varchar(50) NOT NULL,
  `fh_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_c_clientes`
--

INSERT INTO `act_c_clientes` (`id`, `rfc`, `alias`, `razon_social`, `domicilio`, `contacto`, `correo`, `telefono`, `activo`, `fh_inactivo`, `u_inactivo`, `fh_registro`) VALUES
(1, 'PTRKA', 'Plastic trends', 'Plastic Trends SAPI de CV', 'Dr Roberto Michel 750', 'Juan Garcia', 'juan.garcia@plastictrends.com.mx', '3323101175', 1, NULL, '', NULL),
(16, NULL, 'Gera34111', 'gera sa de cv', 'de los olivos 922', 'el gera', NULL, NULL, 0, '2025-04-02 19:31:02', '', '2025-04-02 17:41:40'),
(17, NULL, 'Gera', 'gera sa de cv', NULL, NULL, NULL, NULL, 0, '2025-04-02 19:31:15', '', '2025-04-02 17:59:11'),
(18, NULL, 'Gera2', 'gera sa de cv', NULL, NULL, NULL, NULL, 0, '2025-04-02 19:31:12', '', '2025-04-02 17:59:31'),
(19, NULL, 'pepito', 'pepe sa de cv', NULL, NULL, NULL, NULL, 0, '2025-04-02 19:48:51', '', '2025-04-02 19:40:53'),
(20, NULL, 'prueba', 'pruebiña', NULL, NULL, NULL, NULL, 0, '2025-04-02 20:08:54', '1', '2025-04-02 20:06:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_c_estatus`
--

CREATE TABLE `act_c_estatus` (
  `id` int(11) NOT NULL,
  `codigo` varchar(1) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_c_estatus`
--

INSERT INTO `act_c_estatus` (`id`, `codigo`, `descripcion`) VALUES
(1, 'A', 'Activa'),
(2, 'X', 'Cancelada'),
(3, 'F', 'Finalizada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_c_prioridades`
--

CREATE TABLE `act_c_prioridades` (
  `id` int(11) NOT NULL,
  `codigo` varchar(1) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `color_hex` varchar(6) NOT NULL,
  `hr_min` int(11) NOT NULL,
  `hr_max` int(11) NOT NULL,
  `icono` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_c_prioridades`
--

INSERT INTO `act_c_prioridades` (`id`, `codigo`, `descripcion`, `color_hex`, `hr_min`, `hr_max`, `icono`, `activo`) VALUES
(1, '1', 'Baja', 'ff0000', 48, 60, 'fa fa-plus', 1),
(2, '2', 'Media', 'ff0000', 24, 48, 'fa fa-plus', 1),
(3, '3', 'Alta', 'ff0000', 3, 24, 'fa fa-plus', 1),
(4, 'U', 'Urgente', 'ff0000', 1, 3, 'fa fa-flash', 1),
(5, 'P', 'Proyecto', '', 0, 0, 'fa fa-plus', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_c_tipos`
--

CREATE TABLE `act_c_tipos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(4) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `pre` varchar(1) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_c_tipos`
--

INSERT INTO `act_c_tipos` (`id`, `codigo`, `descripcion`, `pre`, `activo`) VALUES
(1, 'TICK', 'Tickets de Soporte', 'T', 1),
(2, 'PROY', 'Proyectos', 'P', 1),
(3, 'LEVA', 'Levantamientos', 'L', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_r_adjuntos`
--

CREATE TABLE `act_r_adjuntos` (
  `id` int(11) NOT NULL,
  `folio_act` varchar(9) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_u_registra` int(11) NOT NULL,
  `fh_registra` datetime NOT NULL,
  `dir` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `act_r_adjuntos`
--

INSERT INTO `act_r_adjuntos` (`id`, `folio_act`, `id_u_registra`, `fh_registra`, `dir`) VALUES
(1, '25T040006', 1, '2025-04-02 06:23:07', 'prueba.jpg'),
(2, '25T040006', 1, '2025-04-02 07:24:06', 'prueba2.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_r_comentarios`
--

CREATE TABLE `act_r_comentarios` (
  `id` int(11) NOT NULL,
  `folio_act` varchar(9) NOT NULL,
  `id_u_registra` int(11) NOT NULL,
  `fh_registra` datetime NOT NULL,
  `comentario` text NOT NULL,
  `avance` int(11) NOT NULL,
  `visto_por` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_r_comentarios`
--

INSERT INTO `act_r_comentarios` (`id`, `folio_act`, `id_u_registra`, `fh_registra`, `comentario`, `avance`, `visto_por`) VALUES
(1, '25T040006', 5, '2025-04-02 06:09:40', 'pruebas', 1, ''),
(2, '25T040006', 1, '2025-04-02 06:10:25', 'que onda', 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_r_involucrados`
--

CREATE TABLE `act_r_involucrados` (
  `id` int(11) NOT NULL,
  `folio` varchar(9) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_r_involucrados`
--

INSERT INTO `act_r_involucrados` (`id`, `folio`, `id_usuario`) VALUES
(1, '25T030001', 5),
(3, '25T030009', 5),
(4, '25T030009', 23),
(5, '25P030005', 1),
(6, '25T040001', 23),
(7, '25L040001', 5),
(8, '25P040003', 5),
(17, '25T040004', 1),
(18, '25T040004', 23),
(19, '25T040006', 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `core`
--

CREATE TABLE `core` (
  `id` int(11) NOT NULL,
  `bloqueo` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `core`
--

INSERT INTO `core` (`id`, `bloqueo`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `c_modulo` varchar(4) NOT NULL,
  `nivel1` int(11) NOT NULL,
  `nivel2` int(11) NOT NULL,
  `nivel3` int(11) NOT NULL,
  `tipo` set('MOD','MEN','CAT','') NOT NULL COMMENT 'mod=modulo,men=menu,cat=catalogo',
  `titulo` varchar(100) NOT NULL,
  `vinculo` varchar(50) DEFAULT NULL,
  `icono` varchar(50) NOT NULL,
  `dir` text NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `keywords` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `c_modulo`, `nivel1`, `nivel2`, `nivel3`, `tipo`, `titulo`, `vinculo`, `icono`, `dir`, `activo`, `keywords`) VALUES
(1, 'MINI', 0, 0, 0, 'MOD', 'Inicio', 'panel', 'fa-home', 'home.php', 1, 'Inicio'),
(2, 'XCAT', 1, 0, 0, 'MEN', 'Catalogos', NULL, 'fa-book', '', 1, 'Catalogo'),
(3, 'XSIS', 1, 1, 0, 'MEN', 'Sistema', NULL, 'fa-fas fa-book fa-flip-vertical', '', 1, 'Sistema'),
(4, 'CTUS', 1, 1, 1, 'CAT', 'Tipos Usuario', 'tiposusuario', 'fas fa-user-cog', 'catalogs/user_types.php', 1, 'Tipos Usuario'),
(5, 'CUSU', 1, 1, 2, 'CAT', 'Usuarios', 'usuarios', 'fas fa-user-tie', 'catalogs/users.php', 1, 'Usuarios'),
(6, 'CPRO', 1, 1, 3, 'CAT', 'Programas', 'programas', 'fas fa-list-ul', 'catalogs/programs.php', 1, 'Programas'),
(7, 'XTIC', 1, 2, 0, 'MEN', 'Actividades', NULL, 'fas fa-book fa-flip-vertical', '', 1, 'Tickets'),
(8, 'CPRI', 1, 2, 1, 'CAT', 'Prioridad', 'prioridad', 'fas fa-bolt', '', 1, 'Prioridad'),
(9, 'CTAC', 1, 2, 2, 'CAT', 'Tipos Actividad', 'tiposact', 'far fa-bookmark', '', 1, 'Tipos Actividad'),
(10, 'CCLA', 1, 2, 3, 'CAT', 'Clasificacion Actividades', 'clasact', 'fas fa-bookmark', 'catalogs/clasifications.php', 1, 'Clasificacion Actividades'),
(11, 'CCLI', 1, 3, 0, 'CAT', 'Clientes', 'clientes', 'fas fa-hands-helping', 'catalogs/clients.php', 1, 'Clientes'),
(12, 'ACTI', 2, 0, 0, 'MOD', 'Gestión Actividades', 'gestion_actividades', 'fas fa-people-carry', 'actividades/gestion_actividades.php', 1, 'gestion de actividades');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_permission`
--

CREATE TABLE `menu_permission` (
  `id` int(11) NOT NULL,
  `c_tipo_usuario` varchar(4) NOT NULL,
  `c_modulo` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `menu_permission`
--

INSERT INTO `menu_permission` (`id`, `c_tipo_usuario`, `c_modulo`) VALUES
(10, 'SOPO', 'ACTI'),
(9, 'SPUS', 'ACTI'),
(1, 'SPUS', 'CCLA'),
(8, 'SPUS', 'CCLI'),
(3, 'SPUS', 'CPRI'),
(4, 'SPUS', 'CPRO'),
(5, 'SPUS', 'CTAC'),
(6, 'SPUS', 'CTUS'),
(7, 'SPUS', 'CUSU');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `apellido_p` varchar(40) NOT NULL,
  `apellido_m` varchar(40) NOT NULL,
  `dir_foto` text NOT NULL,
  `c_tipo_usuario` varchar(4) NOT NULL,
  `f_registro` datetime NOT NULL,
  `usuario` varchar(15) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `correo` varchar(250) NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  `f_inactivo` datetime DEFAULT NULL,
  `telefono` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `apellido_p`, `apellido_m`, `dir_foto`, `c_tipo_usuario`, `f_registro`, `usuario`, `clave`, `correo`, `activo`, `f_inactivo`, `telefono`) VALUES
(1, 'Luis Gerardo', 'Saucedo', 'Perez', '', 'SPUS', '2025-02-21 02:15:17', 'cronobreak', '65402f90ef3ceb04c9a50fe3b5aa895d', 'lsaucedolucas@gmail.com', 1, '2025-03-05 21:19:26', 0),
(5, 'Juan David', 'Garcia', 'Sotelo', 'jd.jpg', 'SPUS', '2025-03-03 19:50:12', 'juangar', '65402f90ef3ceb04c9a50fe3b5aa895d', 'juan.d78@gmail.com', 1, NULL, 0),
(23, 'jose', 'maria', 'pelayo', '', 'SPUS', '2025-03-29 08:27:35', 'pelayo', 'e49ef5899582a8a124c53e51120ce6d7', 'jose.pelayo@mycom.com.mx', 1, NULL, 0),
(24, 'USER', 'USER', 'USER', '', 'SOPO', '2025-04-01 21:29:38', 'user123', '65402f90ef3ceb04c9a50fe3b5aa895d', 'evaristover63@gmail.com', 1, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_types`
--

CREATE TABLE `users_types` (
  `id` int(11) NOT NULL,
  `codigo` varchar(4) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `fh_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `users_types`
--

INSERT INTO `users_types` (`id`, `codigo`, `descripcion`, `fh_registro`, `activo`) VALUES
(1, 'SPUS', 'Super Usuario', '2025-03-18 17:38:10', 1),
(3, 'ADUS', 'Administradores', '2025-03-18 17:38:10', 1),
(4, 'SOPO', 'Soporte', '2025-03-18 17:38:59', 1),
(22, 'ASDA', 'ADM1N1STRAD4R', '2025-03-25 19:41:48', 0),
(23, 'ACMI', 'ACMINISTRADOR12', '2025-03-25 19:44:47', 0),
(26, 'aAAA', 'A', '2025-03-26 17:52:53', 0),
(27, 'PISI', 'ACMInnn', '2025-03-26 17:53:51', 0),
(28, 'yupY', 'DGDaaass', '2025-03-26 17:55:52', 0),
(29, 'adsa', 'addadadaad', '2025-03-26 17:57:45', 0),
(30, 'ADAD', 'aAaAAA', '2025-03-26 17:58:42', 0),
(31, 'SAFA', 'AAAAAAAAAAAAAA', '2025-03-26 18:01:40', 0),
(32, 'ASSS', 'AJKJ', '2025-03-26 18:03:38', 0),
(33, 'CVB1', 'DW', '2025-03-26 18:05:34', 0),
(34, 'Q Q ', 'Q  Q Q Q Q  ', '2025-03-26 18:06:59', 0),
(35, 'SXAA', 'ADDDD SDDWD', '2025-03-26 18:15:36', 0),
(36, 'ASAS', 'WSASS', '2025-03-26 18:16:01', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folio` (`folio`),
  ADD KEY `c_tipo_act` (`c_tipo_act`),
  ADD KEY `c_cliente` (`id_cliente`),
  ADD KEY `c_prioridad` (`c_prioridad`),
  ADD KEY `c_clasifica_act` (`c_clasifica_act`),
  ADD KEY `id_usuario_resp` (`id_usuario_resp`),
  ADD KEY `id_usuario_captura` (`id_usuario_captura`),
  ADD KEY `id_usuario_finaliza` (`id_usuario_finaliza`),
  ADD KEY `c_estatus` (`c_estatus`);

--
-- Indices de la tabla `act_c_clasificacion`
--
ALTER TABLE `act_c_clasificacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_2` (`codigo`),
  ADD KEY `codigo` (`codigo`);

--
-- Indices de la tabla `act_c_clientes`
--
ALTER TABLE `act_c_clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `act_c_estatus`
--
ALTER TABLE `act_c_estatus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `codigo` (`codigo`);

--
-- Indices de la tabla `act_c_prioridades`
--
ALTER TABLE `act_c_prioridades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `codigo` (`codigo`);

--
-- Indices de la tabla `act_c_tipos`
--
ALTER TABLE `act_c_tipos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_2` (`codigo`),
  ADD KEY `codigo` (`codigo`);

--
-- Indices de la tabla `act_r_adjuntos`
--
ALTER TABLE `act_r_adjuntos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `folio_act` (`folio_act`),
  ADD KEY `id_u_registra` (`id_u_registra`);

--
-- Indices de la tabla `act_r_comentarios`
--
ALTER TABLE `act_r_comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `folio_act` (`folio_act`),
  ADD KEY `id_u_registra` (`id_u_registra`);

--
-- Indices de la tabla `act_r_involucrados`
--
ALTER TABLE `act_r_involucrados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `folio` (`folio`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `core`
--
ALTER TABLE `core`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `c_modulo` (`c_modulo`),
  ADD UNIQUE KEY `vinculo` (`vinculo`);

--
-- Indices de la tabla `menu_permission`
--
ALTER TABLE `menu_permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `U_tipo_modulo` (`c_tipo_usuario`,`c_modulo`) USING BTREE,
  ADD KEY `FK_tipo_usuario` (`c_tipo_usuario`),
  ADD KEY `FK_modulo` (`c_modulo`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tipo_usuario` (`c_tipo_usuario`);

--
-- Indices de la tabla `users_types`
--
ALTER TABLE `users_types`
  ADD PRIMARY KEY (`id`,`codigo`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `act_c_clasificacion`
--
ALTER TABLE `act_c_clasificacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `act_c_clientes`
--
ALTER TABLE `act_c_clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `act_c_estatus`
--
ALTER TABLE `act_c_estatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `act_c_prioridades`
--
ALTER TABLE `act_c_prioridades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `act_c_tipos`
--
ALTER TABLE `act_c_tipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `act_r_adjuntos`
--
ALTER TABLE `act_r_adjuntos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `act_r_comentarios`
--
ALTER TABLE `act_r_comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `act_r_involucrados`
--
ALTER TABLE `act_r_involucrados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `core`
--
ALTER TABLE `core`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `menu_permission`
--
ALTER TABLE `menu_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `users_types`
--
ALTER TABLE `users_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `actividades_ibfk_1` FOREIGN KEY (`c_tipo_act`) REFERENCES `act_c_tipos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `actividades_ibfk_2` FOREIGN KEY (`c_clasifica_act`) REFERENCES `act_c_clasificacion` (`codigo`),
  ADD CONSTRAINT `actividades_ibfk_3` FOREIGN KEY (`c_prioridad`) REFERENCES `act_c_prioridades` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `actividades_ibfk_4` FOREIGN KEY (`id_usuario_resp`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `actividades_ibfk_5` FOREIGN KEY (`id_usuario_captura`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `actividades_ibfk_6` FOREIGN KEY (`id_usuario_finaliza`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `actividades_ibfk_7` FOREIGN KEY (`id_cliente`) REFERENCES `act_c_clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `actividades_ibfk_8` FOREIGN KEY (`c_estatus`) REFERENCES `act_c_estatus` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `act_r_adjuntos`
--
ALTER TABLE `act_r_adjuntos`
  ADD CONSTRAINT `act_r_adjuntos_ibfk_1` FOREIGN KEY (`folio_act`) REFERENCES `actividades` (`folio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `act_r_adjuntos_ibfk_2` FOREIGN KEY (`id_u_registra`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `act_r_comentarios`
--
ALTER TABLE `act_r_comentarios`
  ADD CONSTRAINT `act_r_comentarios_ibfk_1` FOREIGN KEY (`folio_act`) REFERENCES `actividades` (`folio`),
  ADD CONSTRAINT `act_r_comentarios_ibfk_2` FOREIGN KEY (`id_u_registra`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `act_r_involucrados`
--
ALTER TABLE `act_r_involucrados`
  ADD CONSTRAINT `act_r_involucrados_ibfk_1` FOREIGN KEY (`folio`) REFERENCES `actividades` (`folio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `act_r_involucrados_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `menu_permission`
--
ALTER TABLE `menu_permission`
  ADD CONSTRAINT `menu_permission_ibfk_1` FOREIGN KEY (`c_tipo_usuario`) REFERENCES `users_types` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `menu_permission_ibfk_2` FOREIGN KEY (`c_modulo`) REFERENCES `menu` (`c_modulo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`c_tipo_usuario`) REFERENCES `users_types` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
