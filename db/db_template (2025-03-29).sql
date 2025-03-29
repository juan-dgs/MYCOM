-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-03-2025 a las 17:10:53
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
  `id_usuario_resp` int(11) NOT NULL,
  `f_plan_i` date NOT NULL,
  `f_plan_f` date NOT NULL,
  `fh_captura` datetime NOT NULL,
  `id_usuario_captura` int(11) NOT NULL,
  `fh_finaliza` datetime NOT NULL,
  `id_usuario_finaliza` int(11) NOT NULL,
  `calificacion` int(11) NOT NULL,
  `avance` int(3) NOT NULL DEFAULT 0,
  `c_estatus` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `folio`, `c_tipo_act`, `id_cliente`, `c_prioridad`, `c_clasifica_act`, `descripcion`, `comentario`, `notas`, `dispositivo`, `id_usuario_resp`, `f_plan_i`, `f_plan_f`, `fh_captura`, `id_usuario_captura`, `fh_finaliza`, `id_usuario_finaliza`, `calificacion`, `avance`, `c_estatus`) VALUES
(1, '25T010001', 'TICK', 1, 'U', 'CCTV', 'pruebas', '', '', '', 1, '0000-00-00', '0000-00-00', '2025-03-22 00:04:17', 5, '2025-03-22 00:04:17', 5, 0, 50, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_c_clasificacion`
--

CREATE TABLE `act_c_clasificacion` (
  `id` int(11) NOT NULL,
  `codigo` varchar(4) NOT NULL,
  `descripcion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_c_clasificacion`
--

INSERT INTO `act_c_clasificacion` (`id`, `codigo`, `descripcion`) VALUES
(1, 'CCTV', 'CCTV'),
(2, 'REDS', 'Redes');

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
(1, 'PTR', 'Plastic', 'Plastic Trends SAPI de CV', 'Dr Roberto Michel 750', 'Juan Garcia', 'juan.garcia@plastictrends.com.mx', '3323101175', 1, NULL, '', NULL),
(2, 'p', 'p', 'p', 'p', 'p', 'p', 'p', 0, '2025-03-28 18:39:54', '1', '2025-03-26 20:07:58'),
(3, 'prueba', 'prueba', 'prueba', 'prueba', 'prueba', 'prueba', 'prueba', 0, '2025-03-28 20:18:07', '1', '2025-03-26 20:12:45'),
(4, 'pruebaprueba', 'prueba', 'prueba', 'prueba', '', '', '', 0, '2025-03-28 20:18:13', '1', '2025-03-26 20:13:06'),
(5, '', 'asdfgbn', 'asdfgvbn', '', '', '', '', 0, '2025-03-28 20:17:53', '1', '2025-03-26 20:28:15'),
(6, 'as', 'asassasas', 'asasassass', '', '', '', '', 0, '2025-03-28 20:17:46', '1', '2025-03-26 20:32:41'),
(7, 'a', 'asasaas', 'asaasasas', '', '', '', '', 0, '2025-03-28 19:46:32', '1', '2025-03-26 20:34:05'),
(8, '1', 'prueba111111111111111', 'prueba', '', '', '', '', 0, '2025-03-28 20:17:57', '1', '2025-03-26 20:34:24'),
(9, NULL, 'sdfghjkm', 'sadfghjk', NULL, NULL, NULL, NULL, 0, '2025-03-28 18:40:41', '1', '2025-03-26 20:45:14'),
(10, 'sdfghjkl', 'sadfghjkm', 'sdfghjk', NULL, NULL, NULL, NULL, 0, '2025-03-28 20:18:16', '1', '2025-03-26 20:58:29'),
(11, 'Paaaa', 'Paaaaa', 'Paaaaa', 'Paaaaa', 'Paaaaa', 'P@GMAIL.COM', 'Paaaaaa', 0, '2025-03-28 20:21:57', '1', '2025-03-28 20:20:07'),
(12, NULL, 'PEPE', 'PEPE', NULL, NULL, NULL, NULL, 1, NULL, '', '2025-03-28 20:22:07'),
(13, 'a', 'aa', 'a', '', '', 'a@a.com', '', 1, NULL, '', '2025-03-28 21:02:02'),
(14, NULL, 'dfg', 'sdfgh', NULL, NULL, NULL, NULL, 1, NULL, '', '2025-03-28 21:23:05'),
(15, NULL, 'werftg', 'dsfghh', NULL, NULL, NULL, NULL, 1, NULL, '', '2025-03-28 21:25:41');

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
  `icono` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_c_prioridades`
--

INSERT INTO `act_c_prioridades` (`id`, `codigo`, `descripcion`, `color_hex`, `hr_min`, `hr_max`, `icono`) VALUES
(1, '1', 'Baja', '', 48, 60, ''),
(2, '2', 'Media', '', 24, 48, ''),
(3, '3', 'Alta', '', 3, 24, ''),
(4, 'U', 'Urgente', '', 1, 3, ''),
(5, 'P', 'Proyecto', '', 0, 0, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_c_tipos`
--

CREATE TABLE `act_c_tipos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(4) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `pre` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_c_tipos`
--

INSERT INTO `act_c_tipos` (`id`, `codigo`, `descripcion`, `pre`) VALUES
(1, 'TICK', 'Tickets de Soporte', 'T'),
(2, 'PROY', 'Proyectos', 'P'),
(3, 'LEVA', 'Levantamientos', 'L');

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
(1, '25T010001', 5);

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
(3, 'XSIS', 1, 1, 0, 'MEN', 'Sistema', NULL, 'fa-home', '', 1, 'Sistema'),
(4, 'CTUS', 1, 1, 1, 'CAT', 'Tipos Usuario', 'tiposusuario', 'fa-home', 'catalogs/user_types.php', 1, 'Tipos Usuario'),
(5, 'CUSU', 1, 1, 2, 'CAT', 'Usuarios', 'usuarios', 'fa-home', 'catalogs/users.php', 1, 'Usuarios'),
(6, 'CPRO', 1, 1, 3, 'CAT', 'Programas', 'programas', 'fa-home', 'catalogs/programs.php', 1, 'Programas'),
(7, 'XTIC', 1, 2, 0, 'MEN', 'Actividades', NULL, 'fa-home', '', 1, 'Tickets'),
(8, 'CPRI', 1, 2, 1, 'CAT', 'Prioridad', 'prioridad', 'fa-home', '', 1, 'Prioridad'),
(9, 'CTAC', 1, 2, 2, 'CAT', 'Tipos Actividad', 'tiposact', 'fa-home', '', 1, 'Tipos Actividad'),
(10, 'CCLA', 1, 2, 3, 'CAT', 'Clasificacion Actividades', 'clasact', 'fa-home', '', 1, 'Clasificacion Actividades'),
(11, 'CCLI', 1, 3, 0, 'CAT', 'Clientes', 'clientes', 'fa-home', 'catalogs/clients.php', 1, 'Clientes'),
(12, 'ACTI', 2, 0, 0, 'MOD', 'Gestión Actividades', 'gestion_actividades', 'fa-home', 'actividades/gestion_actividades.php', 1, 'gestion de actividades');

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
(4, '', '', '', '', 'SPUS', '2025-03-03 19:49:11', '', '', '', 0, '2025-03-06 15:26:45', 0),
(5, 'Juan David', 'Garcia', 'Sotelo', '', 'SPUS', '2025-03-03 19:50:12', 'juangar', '65402f90ef3ceb04c9a50fe3b5aa895d', 'juan.d78@gmail.com', 1, NULL, 0),
(6, 'Juan David', 'Garcia', 'Sotelo', '', 'ADUS', '2025-03-03 19:51:15', 'juangar1', '123', 'juan.d781@gmail.com', 0, '2025-03-05 21:30:58', 0),
(7, 'paco', 'Díaz', 'paco', '', 'ADUS', '2025-03-03 19:54:16', 'cronobreak1', '0724f6e53943f05267de060f9760a98d', 'pancho123@xxx.com', 0, '2025-03-14 21:23:02', 0),
(8, 'OMI', 'ORTIZ', '', '', 'ADUS', '2025-03-03 20:16:37', 'OMARORT', 'e83af75a33a0fd4afb3a7fdb5a34be3b', 'OMAROR@HOT69.COM', 0, '2025-03-14 21:23:08', 0),
(9, 'paquito', 'cabeza', 'portillo', '', 'ADUS', '2025-03-05 19:59:09', 'paquin', 'da4bded1544150b5ba2ed3ea49295391', 'paquin@www.com', 0, '2025-03-05 21:30:04', 0),
(10, '', '', '', '', 'SPUS', '2025-03-05 20:04:26', 'gera', '2e2d3886d4963debcd133840a555365b', 'gera@gmail.com', 0, '2025-03-05 21:29:36', 0),
(11, 'FDGHJ', 'FDGHJK', 'FDGHJ', '', 'ADUS', '2025-03-05 20:10:56', 'DFSGHJ', 'd7d7394ac45599d5ab6c27f84ed2607f', 'SFDGHJHHGFDSGHJ', 0, '2025-03-05 21:21:08', 0),
(12, 'SDAFGHJ', 'SFSFDFS', 'FDGHJK', '', 'SPUS', '2025-03-05 20:55:45', 'DFGHJKL', '293ebc3c2f8959fcb60fbf62ad4adcf3', 'DFGHGJFDGHFJG', 0, '2025-03-05 21:20:14', 0),
(13, 'angel antonio', 'herrera', 'lopez', '', 'ADUS', '2025-03-05 21:31:58', 'angelin', '4d0b8461881feba94385445bc166f88d', 'angel@gmail.com', 0, '2025-03-06 21:24:12', 0),
(14, 'fdghjkl', 'fdgshj', 'fdsghj', '', 'ADUS', '2025-03-06 15:26:33', 'gfhdj', 'cb5b4ac28aa989eaf784c1d8fea33f60', 'gera123porsi@outlook.com', 0, '2025-03-21 15:51:19', 0),
(15, 'asdfghjk', 'dsfghjkl', 'fdghjkl', '', 'ADUS', '2025-03-06 17:48:22', 'sdfghjkl', '4892395902e21a694f5913e47e65052b', 'gera@123.c', 0, '2025-03-14 17:07:51', 0),
(16, 'dsfghjk', 'fdghjk', 'gfhnjm', '', 'ADUS', '2025-03-06 17:59:11', 'dfghnjm', '12d116efbd2e5837ec22cd233bce09bb', 'elgera@hotmail.com', 0, '2025-03-14 21:24:06', 0),
(17, 'sdfghjkl', 'sadfghjkl', 'sdfghjkl', '', 'ADUS', '2025-03-06 18:06:52', 'dsafghjkl', 'f2e8da4c8b1a9db23ce968336446fa12', 'gera123@gmail.com', 0, '2025-03-14 21:24:04', 0),
(18, 'alexander ', '..', '..', '', 'ADUS', '2025-03-06 18:23:39', 'alexander salva', 'db14eedf4dbe3fd04bd3123735ddf8d6', 'marianosegura@hotmail.com', 0, '2025-03-14 21:24:02', 0),
(19, 'ASDFGHJKLJHGFDsdfghgfdfghgfgfghhfgfgffhf', 'ASDFGHJKLJHGFDsdfghgfdfghgfgfghhfgfgffhf', 'ASDFGHJKLJHGFDsdfghgfdfghgfgfghhfgfgffhf', '', 'ADUS', '2025-03-06 18:54:52', 'asdfghjkljhgfds', '4219d06e3727fe822b4ff67b7691a5ba', 'dfsdgfhjkgdfghfjhfgd@gmail.com', 0, '2025-03-14 19:28:49', 0),
(20, 'ghjfkljhgfghfsgthhjhjfdfjkgaretsrytuyiuo', 'ghjfkljhgfghfsgthhjhjfdfjkgaretsrytuyiuo', 'ghjfkljhgfghfsgthhjhjfdfjkgaretsrytuyiuo', '', 'SPUS', '2025-03-06 21:17:05', 'ghjfkljhgfghfsg', '095139b248b10849b4f153749b13669b', 'gerardiño@gmail.com', 0, '2025-03-06 21:17:28', 0),
(21, 'wazsexdcrtfvygbuhnjimjinhbgvfcdxrcftv', ' jvgvhkhjvk bnnm', 'bbjjknbnklbknl bk', '', 'ADUS', '2025-03-06 22:18:27', 'jkjñlbfjlbnf.ef', 'c0b852d45de3074dd6dad8b0ffe7da16', 'geera@gmail.comeeeee', 0, '2025-03-14 19:29:07', 0),
(22, 'chavacano', 'brambila', 'brambila', '', 'ADUS', '2025-03-14 21:19:01', 'sbb', '08ddc7423ffd01e4dcc7f92f39017935', 'abbhh@vv.cc', 0, '2025-03-14 21:23:59', 0),
(23, 'jose', 'maria', 'pelayo', '', 'SPUS', '2025-03-29 08:27:35', 'pelayo', 'e49ef5899582a8a124c53e51120ce6d7', 'jose.pelayo@mycom.com.mx', 1, NULL, 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `act_c_clasificacion`
--
ALTER TABLE `act_c_clasificacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `act_c_clientes`
--
ALTER TABLE `act_c_clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
-- AUTO_INCREMENT de la tabla `act_r_comentarios`
--
ALTER TABLE `act_r_comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `act_r_involucrados`
--
ALTER TABLE `act_r_involucrados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
