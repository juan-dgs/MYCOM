-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-04-2025 a las 08:13:29
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

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_preparar_base_actividades` (IN `p_fecha_inicio` DATE)   BEGIN
    -- Crear tabla temporal global (persiste durante la sesión)
    DROP TEMPORARY TABLE IF EXISTS temp_base_actividades;
    CREATE TEMPORARY TABLE temp_base_actividades (
        folio VARCHAR(50),
        id_usuario_resp INT,
        fh_captura DATETIME,
        f_plan_i DATETIME,
        f_plan_f DATETIME,
        hr_min DECIMAL(10,2),
        hr_max DECIMAL(10,2),
        fh_finaliza DATETIME,
        c_tipo_act VARCHAR(20),
        c_clasifica_act VARCHAR(20),
        c_prioridad VARCHAR(10),
        c_estatus CHAR(1),
        id_cliente INT,
        calificacion DECIMAL(3,1),
        avance DECIMAL(5,2),
        horas_plan DECIMAL(10,2),
        horas_real DECIMAL(10,2),
        horas_totales_plan DECIMAL(10,2),
        horas_totales_real DECIMAL(10,2),
        INDEX (id_usuario_resp),
        INDEX (c_estatus),
        INDEX (id_cliente)
    );
    
    -- Insertar datos calculados
    INSERT INTO temp_base_actividades
    SELECT 
        a.folio,
        a.id_usuario_resp,
        a.fh_captura,
        a.f_plan_i,
        a.f_plan_f,
        p.hr_min,
        p.hr_max,
        a.fh_finaliza,
        a.c_tipo_act,
        a.c_clasifica_act,
        a.c_prioridad,
        a.c_estatus,
        a.calificacion,
        a.avance,
        a.id_cliente,
        IF(f_plan_f IS NULL,
            p.hr_max,
            fn_calcular_horas_laborables(
                IFNULL(a.f_plan_i, a.fh_captura),
                IFNULL(a.f_plan_f, DATE_ADD(fh_captura, INTERVAL p.hr_max HOUR))
            )) AS horas_plan,
        fn_calcular_horas_laborables(
            IFNULL(a.f_plan_i, a.fh_captura),
            IFNULL(a.fh_finaliza, NOW())
        ) AS horas_real,
        ROUND(TIMESTAMPDIFF(SECOND, 
            IFNULL(a.f_plan_i, a.fh_captura), 
            IFNULL(a.f_plan_f, DATE_ADD(fh_captura, INTERVAL p.hr_max HOUR))) / 3600.0, 2) AS horas_totales_plan,
        ROUND(TIMESTAMPDIFF(SECOND, 
            IFNULL(a.f_plan_i, a.fh_captura), 
            IFNULL(a.fh_finaliza, NOW())) / 3600.0, 2) AS horas_totales_real
    FROM actividades a 
    LEFT JOIN act_c_prioridades as p ON p.codigo = a.c_prioridad
    WHERE (a.fh_captura > p_fecha_inicio OR a.c_estatus ='A' OR a.fh_finaliza > p_fecha_inicio);
    
    SELECT 'Datos base preparados correctamente' AS mensaje;
END$$

--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_calcular_horas_laborables` (`inicio` DATETIME, `fin` DATETIME) RETURNS DECIMAL(10,2) READS SQL DATA COMMENT 'Calcula horas laborales reales, en base al horario de trabajo' BEGIN
    DECLARE total_horas DECIMAL(10,2) DEFAULT 0;
    DECLARE fecha_actual DATE;
    DECLARE dia_numero TINYINT;
    DECLARE es_feriado BOOLEAN;
    DECLARE h_inicio TIME;
    DECLARE h_fin TIME;
    DECLARE es_dia_laboral BOOLEAN;
    DECLARE horas_comida FLOAT;
    DECLARE horas_dia DECIMAL(10,2);
    
    SET fecha_actual = DATE(inicio);
    
    WHILE fecha_actual <= DATE(fin) DO
        -- Obtener número de día (1=Dom, 2=Lun, ..., 7=Sáb)
        SET dia_numero = DAYOFWEEK(fecha_actual);
        
        -- Consultar horario para este día (incluyendo horas de comida)
        SELECT 
            hora_inicio, 
            hora_fin, 
            es_laboral,
            hr_comida
        INTO 
            h_inicio, 
            h_fin, 
            es_dia_laboral,
            horas_comida
        FROM core_horarios_laborales
        WHERE dia_semana = dia_numero;
        
        -- Verificar si es feriado
        SELECT EXISTS(
            SELECT 1 FROM core_feriados 
            WHERE fecha = fecha_actual
        ) INTO es_feriado;
        
        -- Calcular horas si es día laborable y no feriado
        IF es_dia_laboral AND NOT es_feriado THEN
            -- Ajustar inicio y fin para el día actual
            SET @inicio_dia = GREATEST(
                IF(DATE(inicio) = fecha_actual, TIME(inicio), h_inicio),
                h_inicio
            );
            
            SET @fin_dia = LEAST(
                IF(DATE(fin) = fecha_actual, TIME(fin), h_fin),
                h_fin
            );
            
            -- Sumar horas si el rango es válido
            IF @inicio_dia < @fin_dia THEN
                -- Calcular horas trabajadas en el día
                SET horas_dia = TIMESTAMPDIFF(
                    SECOND,
                    CONCAT(fecha_actual, ' ', @inicio_dia),
                    CONCAT(fecha_actual, ' ', @fin_dia)
                ) / 3600.0;
                
                -- Restar horas de comida (si existen)
                IF horas_comida IS NOT NULL AND horas_comida > 0 THEN
                    SET horas_dia = horas_dia - horas_comida;
                END IF;
                
                -- Sumar al total (solo si sigue siendo positivo)
                IF horas_dia > 0 THEN
                    SET total_horas = total_horas + horas_dia;
                END IF;
            END IF;
        END IF;
        
        SET fecha_actual = DATE_ADD(fecha_actual, INTERVAL 1 DAY);
    END WHILE;
    
    RETURN ROUND(total_horas, 2);
END$$

DELIMITER ;

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
(1, '25T030001', 'TICK', 1, 'U', 'CCTV', 'pruebas', '', '', '', 1, NULL, NULL, '2025-03-22 00:04:17', 5, NULL, 5, 0, 100, 'F'),
(15, '25T030003', 'TICK', 1, '1', 'CCTV', '<p>ddddd</p>', '', '', '', 1, NULL, NULL, '2025-03-29 22:51:47', 1, NULL, NULL, 0, 0, 'A'),
(21, '25T030004', 'TICK', 1, '2', 'CCTV', '<p>prueba</p>', '', '', '||', 24, NULL, NULL, '2025-03-30 12:52:20', 1, NULL, NULL, 0, 0, 'A'),
(23, '25P030001', 'PROY', 1, '3', 'CCTV', '<p>prueba</p>', '', '', '', 23, NULL, NULL, '2025-03-30 12:55:44', 5, NULL, NULL, 0, 0, 'A'),
(24, '25L030001', 'LEVA', 1, '2', 'REDS', '<p><font color=\"#000000\"><span style=\"background-color: rgb(255, 255, 0);\">ssdsadasdasdasd</span></font></p>', '', '', '', 5, NULL, NULL, '2025-03-30 12:57:19', 5, NULL, NULL, 0, 0, 'A'),
(25, '25L030002', 'LEVA', 1, '1', 'CCTV', '<p>prueba</p>', '', '', '||', 5, NULL, NULL, '2025-03-30 13:00:29', 5, NULL, NULL, 0, 0, 'A'),
(26, '25P030002', 'PROY', 1, '1', 'CCTV', '<p>prueba</p>', '', '', '', 23, NULL, NULL, '2025-03-30 13:00:59', 5, NULL, NULL, 0, 0, 'A'),
(27, '25T030005', 'TICK', 1, '1', 'CCTV', '<p>pruebas</p>', '', '', '', 1, NULL, NULL, '2025-03-30 13:01:58', 5, NULL, NULL, 0, 0, 'A'),
(28, '25T030006', 'TICK', 1, '1', 'CCTV', '<p>pruebas</p>', '', '', '', 5, NULL, NULL, '2025-03-30 13:08:48', 5, NULL, NULL, 0, 0, 'A'),
(29, '25P030003', 'PROY', 1, '2', 'CCTV', '<ul><li><font face=\"Impact\">prueba desc</font></li><li><font face=\"Impact\">....</font></li></ul>', '', '<ol><li style=\"text-align: center; \"><u>Notas</u></li></ol>', '', 5, NULL, NULL, '2025-03-30 13:13:04', 5, NULL, NULL, 0, 0, 'A'),
(30, '25P030004', 'PROY', 1, '2', 'CCTV', '<ul><li><font face=\"Impact\">prueba desc</font></li><li><font face=\"Impact\">....</font></li></ul>', '', '<ol><li style=\"text-align: center; \"><u>Notas</u></li></ol>', '', 5, NULL, NULL, '2025-03-30 13:15:44', 5, NULL, NULL, 0, 0, 'A'),
(31, '25T030007', 'TICK', 1, '2', 'CCTV', '<p><font color=\"#000000\" style=\"background-color: rgb(255, 255, 0);\"><b>ddd</b></font></p>', '<p style=\"text-align: justify; \"><b style=\"background-color: rgb(0, 0, 255);\"><font color=\"#ffe79c\">comentarios</font></b></p>', '', '', 5, NULL, NULL, '2025-03-30 13:17:45', 5, NULL, NULL, 0, 0, 'A'),
(32, '25T030008', 'TICK', 1, '2', 'CCTV', '<p><font color=\"#000000\" style=\"background-color: rgb(255, 255, 0);\"><b>ddd</b></font></p>', '<p style=\"text-align: justify; \"><b style=\"background-color: rgb(0, 0, 255);\"><font color=\"#ffe79c\">comentarios</font></b></p>', '', '', 5, NULL, NULL, '2025-03-30 13:18:43', 5, NULL, NULL, 0, 0, 'A'),
(33, '25L030003', 'LEVA', 1, '2', 'CCTV', '<p>test2321</p>', '', '', '||', 5, '2025-03-01', '2025-03-01', '2025-03-30 13:21:46', 5, '2025-04-08 09:19:32', 5, 0, 100, 'F'),
(34, '25L030004', 'LEVA', 1, '2', 'CCTV', '<p>test2321</p>', '', '', '||', 1, '2025-03-01', '2025-03-30', '2025-03-30 13:22:12', 5, NULL, NULL, 0, 0, 'A'),
(35, '25T030009', 'TICK', 1, '3', 'CCTV', '<p>test</p>', '', '', '', 1, NULL, NULL, '2025-03-30 14:22:00', 5, NULL, NULL, 0, 0, 'A'),
(36, '25P030005', 'PROY', 1, '1', 'REDS', '<p>prueba</p>', '', '', 'serie|mac|otro', 5, '2025-04-01', NULL, '2025-03-30 14:27:50', 5, '2025-04-07 19:52:45', 5, 0, 100, 'F'),
(37, '25T040001', 'TICK', 1, '2', 'CCTV', '<ol><li>prueba 010425</li></ol>', '<h1 class=\"\"><b>comentario X</b></h1>', '<p><font color=\"#000000\" style=\"background-color: rgb(255, 255, 0);\">NOTA Y</font></p>', '100210|test|100210', 1, '2025-04-01', '2025-04-05', '2025-04-01 20:13:32', 5, NULL, NULL, 0, 15, 'X'),
(38, '25L040001', 'LEVA', 1, '2', 'CCTV', '<p>pruebas</p>', '<p>test</p>', '', '||', 1, NULL, NULL, '2025-04-01 20:14:59', 5, NULL, NULL, 0, 0, 'X'),
(39, '25T040002', 'TICK', 1, '1', 'CCTV', 'test 010425', '', '', '||', 5, NULL, NULL, '2025-04-01 20:57:28', 5, NULL, NULL, 0, 0, 'X'),
(40, '25P040001', 'PROY', 1, '1', 'CCTV', '<p>......</p>', '', '', '||', 23, NULL, NULL, '2025-04-01 20:58:40', 5, NULL, NULL, 0, 0, 'X'),
(41, '25P040002', 'PROY', 1, '1', 'CCTV', '<p>......</p>', '', '', '||', 23, NULL, NULL, '2025-04-01 21:00:26', 5, NULL, NULL, 0, 0, 'X'),
(42, '25P040003', 'PROY', 1, '1', 'CCTV', '<p>......</p>', '', '', '||', 23, NULL, NULL, '2025-04-01 21:00:50', 5, NULL, NULL, 0, 0, 'X'),
(43, '25T040003', 'TICK', 1, '1', 'CCTV', '<p>.....</p>', '', '', '||', 5, NULL, NULL, '2025-04-01 21:02:38', 5, NULL, NULL, 0, 0, 'X'),
(44, '25T040004', 'TICK', 1, '2', 'CCTV', '<p>..... preuba edit12</p>', 'comenta', 'nota', '1|2|3', 5, '2025-04-01', '2025-04-04', '2025-04-01 21:05:09', 5, NULL, NULL, 0, 0, 'X'),
(45, '25T040005', 'TICK', 1, '2', 'CCTV', '<p>PRUEBA</p>', '', '', '||', 24, NULL, NULL, '2025-04-01 21:42:45', 5, NULL, NULL, 0, 0, 'X'),
(46, '25T040006', 'TICK', 1, '2', 'CCTV', '<p>TE4ST</p>', '', '', '||', 1, NULL, NULL, '2025-04-01 21:43:09', 5, NULL, NULL, 0, 10, 'X'),
(47, '25T040007', 'TICK', 1, '1', 'CCTV', 'prueba 10000', '', '', '||', 5, NULL, NULL, '2025-04-04 19:17:03', 5, NULL, NULL, 0, 0, 'X'),
(48, '25T040008', 'TICK', 1, 'U', 'CCTV', 'prueba', '', '', '||', 23, NULL, NULL, '2025-04-07 15:53:29', 5, '2025-04-07 19:53:42', 5, 0, 100, 'F'),
(49, '25P040004', 'PROY', 1, '2', 'REDS', 'preuab', '', '', '||', 5, NULL, NULL, '2025-04-08 09:37:19', 5, '2025-04-10 20:48:19', 5, 0, 100, 'F'),
(50, '25C040001', 'COTI', 1, '3', 'CCTV', 'prueba', '', '', '||', 5, NULL, NULL, '2025-04-17 00:09:11', 5, NULL, NULL, 0, 0, 'A'),
(51, '25C040002', 'COTI', 1, '2', 'ELEC', 'prueba', '', '', '||', 23, NULL, NULL, '2025-04-17 00:09:41', 5, NULL, NULL, 0, 0, 'A');

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
(2, 'REDS', 'Redes', 1, NULL, NULL),
(6, 'ELEC', 'Electrico', 1, '2025-04-10 21:31:12', NULL);

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
  `fh_registro` datetime DEFAULT NULL,
  `dir_logo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_c_clientes`
--

INSERT INTO `act_c_clientes` (`id`, `rfc`, `alias`, `razon_social`, `domicilio`, `contacto`, `correo`, `telefono`, `activo`, `fh_inactivo`, `u_inactivo`, `fh_registro`, `dir_logo`) VALUES
(1, 'PTRKA', 'Plastic trends', 'Plastic Trends SAPI de CV', 'Dr Roberto Michel 750', 'Juan Garcia', 'juan.garcia@plastictrends.com.mx', '3323101175', 1, '2025-04-10 21:32:56', '5', '2025-04-01 21:33:27', 'client_1_1744167854.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_c_estatus`
--

CREATE TABLE `act_c_estatus` (
  `id` int(11) NOT NULL,
  `codigo` varchar(1) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `fh_registro` datetime DEFAULT NULL,
  `fh_inactivo` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_c_estatus`
--

INSERT INTO `act_c_estatus` (`id`, `codigo`, `descripcion`, `fh_registro`, `fh_inactivo`) VALUES
(1, 'A', 'Activa', NULL, NULL),
(2, 'X', 'Cancelada', NULL, NULL),
(3, 'F', 'Finalizada', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_c_prioridades`
--

CREATE TABLE `act_c_prioridades` (
  `id` int(11) NOT NULL,
  `codigo` varchar(1) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `color_hex` varchar(7) NOT NULL,
  `hr_min` int(11) NOT NULL,
  `hr_max` int(11) NOT NULL,
  `icono` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fh_registro` datetime DEFAULT NULL,
  `fh_inactivo` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_c_prioridades`
--

INSERT INTO `act_c_prioridades` (`id`, `codigo`, `descripcion`, `color_hex`, `hr_min`, `hr_max`, `icono`, `activo`, `fh_registro`, `fh_inactivo`) VALUES
(1, '1', 'Baja', '#00b30c', 48, 60, 'fab fa-angellist', 1, NULL, NULL),
(2, '2', 'Media', '#dcc009', 24, 48, 'fas fa-triangle-exclamation', 1, NULL, NULL),
(3, '3', 'Alta', '#ff0000', 3, 24, 'fas fa-face-angry', 1, NULL, NULL),
(4, 'U', 'Urgente', '#7a0000', 1, 3, 'fas fa-bolt', 1, NULL, NULL),
(5, 'P', 'Proyecto', '#6b6b6b', 0, 0, 'fas fa-folder-open', 1, NULL, NULL),
(11, 'M', 'Muy baja', '#2bff00', 1, 2, 'fab fa-android', 0, '2025-04-10 19:27:35', '2025-04-10 20:18:54'),
(12, 'A', 'asgfbsd', '#ff0000', 2, 4, 'fab fa-42-group', 0, '2025-04-10 19:30:43', '2025-04-10 20:18:47'),
(13, 'Z', 'sadfghn', '#ff0000', 0, 0, 'fas fa-anchor', 0, '2025-04-10 19:31:03', '2025-04-10 20:19:12'),
(14, 'X', 'afhgjhj,', '#ff0000', 2, 3, 'fab fa-airbnb', 0, '2025-04-10 19:31:24', '2025-04-10 20:19:06'),
(15, 'S', 'ddd', '#ff0000', 1, 1, 'fas fa-5', 0, '2025-04-10 20:03:59', '2025-04-10 20:19:01'),
(16, 'Y', 'xprueba', '#ff0000', 1, 2, 'fas fa-5', 0, '2025-04-10 20:24:56', '2025-04-10 20:25:28'),
(17, 'S', 'jhjjh', '#ff0000', -5, -5, 'fas fa-4', 1, '2025-04-10 21:24:05', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_c_tipos`
--

CREATE TABLE `act_c_tipos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(4) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `pre` varchar(1) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fh_registro` datetime DEFAULT NULL,
  `fh_inactivo` datetime DEFAULT NULL,
  `color_hex` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_c_tipos`
--

INSERT INTO `act_c_tipos` (`id`, `codigo`, `descripcion`, `pre`, `activo`, `fh_registro`, `fh_inactivo`, `color_hex`) VALUES
(1, 'TICK', 'Tickets de Soporte', 'T', 1, NULL, NULL, '#33FF57'),
(2, 'PROY', 'Proyectos', 'P', 1, NULL, NULL, '#3366FF'),
(3, 'LEVA', 'Levantamientos', 'L', 1, NULL, NULL, '#17a2b8'),
(7, 'COTI', 'Cotización', 'C', 1, '2025-04-10 21:26:13', NULL, '#000000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_r_adjuntos`
--

CREATE TABLE `act_r_adjuntos` (
  `id` int(11) NOT NULL,
  `folio_act` varchar(9) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_u_registra` int(11) NOT NULL,
  `fh_registra` datetime NOT NULL,
  `dir` varchar(100) NOT NULL,
  `fh_registro` datetime DEFAULT NULL,
  `fh_inactivo` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
  `visto_por` text NOT NULL,
  `fh_registro` datetime DEFAULT NULL,
  `fh_inactivo` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_r_comentarios`
--

INSERT INTO `act_r_comentarios` (`id`, `folio_act`, `id_u_registra`, `fh_registra`, `comentario`, `avance`, `visto_por`, `fh_registro`, `fh_inactivo`) VALUES
(10, '25P040004', 5, '2025-04-10 20:48:15', 'listo', 100, '*5*', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `act_r_involucrados`
--

CREATE TABLE `act_r_involucrados` (
  `id` int(11) NOT NULL,
  `folio` varchar(9) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fh_registro` datetime DEFAULT NULL,
  `fh_inactivo` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `act_r_involucrados`
--

INSERT INTO `act_r_involucrados` (`id`, `folio`, `id_usuario`, `fh_registro`, `fh_inactivo`) VALUES
(22, '25C040001', 23, NULL, NULL);

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
-- Estructura de tabla para la tabla `core_feriados`
--

CREATE TABLE `core_feriados` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `es_recurrente` tinyint(1) DEFAULT NULL,
  `fh_registro` datetime DEFAULT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `core_feriados`
--

INSERT INTO `core_feriados` (`id`, `fecha`, `nombre`, `es_recurrente`, `fh_registro`, `activo`) VALUES
(1, '2025-01-01', 'Año Nuevo', 1, NULL, 1),
(3, '2025-02-03', 'Día de la Constitución (primer lunes de febrero)', 1, NULL, 1),
(4, '2025-03-17', 'Natalicio de Benito Juárez (tercer lunes de marzo)', 1, NULL, 1),
(9, '2025-05-01', 'Día del Trabajo', 1, NULL, 1),
(10, '2025-09-16', 'Día de la Independencia', 1, NULL, 1),
(11, '2025-11-17', 'Día de la Revolución (tercer lunes de noviembre)', 1, NULL, 1),
(12, '2025-12-25', 'Navidad', 1, NULL, 1),
(13, '2025-04-25', 'mi cumple', 1, '2025-04-10 21:09:19', 0),
(14, '2025-04-24', 'otra cosa', 1, '2025-04-10 21:09:38', 1),
(16, '2025-04-26', 'b', 1, '2025-04-10 21:12:25', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `core_horarios_laborales`
--

CREATE TABLE `core_horarios_laborales` (
  `dia_semana` tinyint(4) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `es_laboral` tinyint(1) DEFAULT 1,
  `hr_comida` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `core_horarios_laborales`
--

INSERT INTO `core_horarios_laborales` (`dia_semana`, `hora_inicio`, `hora_fin`, `es_laboral`, `hr_comida`) VALUES
(1, '08:00:00', '17:00:00', 1, 1),
(2, '08:30:00', '18:30:00', 1, 0),
(3, '08:30:00', '18:30:00', 1, 1),
(4, '08:30:00', '18:30:00', 1, 0),
(5, '08:00:00', '18:30:00', 1, 0),
(6, '08:00:00', '18:30:00', 1, 0),
(7, '08:00:00', '14:00:00', 1, 0);

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
  `tipo` set('MOD','MEN','CAT','GRL') NOT NULL COMMENT 'mod=modulo,men=menu,cat=catalogo',
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
(1, 'MINI', 0, 0, 0, 'GRL', 'Inicio', 'panel', 'fa-home', 'home.php', 1, 'Inicio'),
(2, 'XCAT', 1, 0, 0, 'MEN', 'Catalogos', NULL, 'fa-book', '', 1, 'Catalogo'),
(3, 'XSIS', 1, 1, 0, 'MEN', 'Sistema', NULL, 'fa-fas fa-book fa-flip-vertical', '', 1, 'Sistema'),
(4, 'CTUS', 1, 1, 1, 'CAT', 'Tipos Usuario', 'tiposusuario', 'fas fa-user-cog', 'catalogs/user_types.php', 1, 'Tipos Usuario'),
(5, 'CUSU', 1, 1, 2, 'CAT', 'Usuarios', 'usuarios', 'fas fa-user-tie', 'catalogs/users.php', 1, 'Usuarios'),
(6, 'CPRO', 1, 1, 3, 'CAT', 'Programas', 'programas', 'fas fa-list-ul', 'catalogs/programs.php', 1, 'Programas'),
(7, 'XTIC', 1, 2, 0, 'MEN', 'Actividades', NULL, 'fas fa-book fa-flip-vertical', '', 1, 'Tickets'),
(8, 'CPRI', 1, 2, 1, 'CAT', 'Prioridad', 'prioridad', 'fas fa-bolt', 'catalogs/priorities.php', 1, 'Prioridad'),
(9, 'CTAC', 1, 2, 2, 'CAT', 'Tipos Actividad', 'tiposact', 'far fa-bookmark', 'catalogs/typeactivity.php', 1, 'Tipos Actividad'),
(10, 'CCLA', 1, 2, 3, 'CAT', 'Clasificacion Actividades', 'clasact', 'fas fa-bookmark', 'catalogs/clasifications.php', 1, 'Clasificacion Actividades'),
(11, 'CCLI', 1, 3, 0, 'CAT', 'Clientes', 'clientes', 'fas fa-hands-helping', 'catalogs/clients.php', 1, 'Clientes'),
(12, 'ACTI', 2, 0, 0, 'MOD', 'Gestión Actividades', 'gestion_actividades', 'fas fa-people-carry', 'actividades/gestion_actividades.php', 1, 'gestion de actividades'),
(13, 'PERF', 1, 0, 0, 'GRL', 'Perfil', 'miperfil', 'fa-solid fa-user', 'profile/my_profile.php', 1, 'Mi Perfil'),
(14, 'CCOF', 1, 1, 4, 'CAT', 'Dias Feriados', 'Feriados', 'fas fa-calendar', 'catalogs/holidays.php', 1, 'Dias Feriados'),
(15, 'CHLA', 1, 1, 5, 'CAT', 'Horarios Laborales', 'horarioslaborales', 'far fa-clock', 'catalogs/horarios.php', 1, 'Horarios Laborales');

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
(12, 'SPUS', 'CCOF'),
(13, 'SPUS', 'CHLA'),
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
  `dir_foto` varchar(50) NOT NULL,
  `c_tipo_usuario` varchar(4) NOT NULL,
  `f_registro` datetime NOT NULL,
  `usuario` varchar(15) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `correo` varchar(250) NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT 1,
  `f_inactivo` datetime DEFAULT NULL,
  `telefono` int(10) NOT NULL,
  `color_Hex` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `apellido_p`, `apellido_m`, `dir_foto`, `c_tipo_usuario`, `f_registro`, `usuario`, `clave`, `correo`, `activo`, `f_inactivo`, `telefono`, `color_Hex`) VALUES
(1, 'Luis Gerardo', 'Saucedo', 'Perez', 'lic_pugberto.jpg', 'SPUS', '2025-02-21 02:15:17', 'cronobreak', '65402f90ef3ceb04c9a50fe3b5aa895d', 'lsaucedolucas@gmail.com', 1, '2025-03-05 21:19:26', 0, ''),
(5, 'Juan David', 'Garcia', 'Sotelo', 'jd.jpg', 'SPUS', '2025-03-03 19:50:12', 'juangar', '65402f90ef3ceb04c9a50fe3b5aa895d', 'juan.d78@gmail.com', 1, NULL, 0, ''),
(23, 'Jose', 'maria', 'pelayo', 'user_2367f886b6de364.jpg', 'SPUS', '2025-03-29 08:27:35', 'pelayo', 'ad9b69fcfabe9d2d96209719bb2b8353', 'jose.pelayo@mycom.com.mx', 1, NULL, 0, ''),
(24, 'USER', 'USER', 'USER', 'user_2467f5daaa03fa6.jpg', 'SOPO', '2025-04-01 21:29:38', 'user123', '65402f90ef3ceb04c9a50fe3b5aa895d', 'evaristover63@gmail.com', 1, NULL, 0, '');

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
(37, 'sdas', '456534543////////', '2025-04-10 20:59:51', 0);

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
  ADD UNIQUE KEY `pre` (`pre`),
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
-- Indices de la tabla `core_feriados`
--
ALTER TABLE `core_feriados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fecha` (`fecha`),
  ADD KEY `id` (`id`);

--
-- Indices de la tabla `core_horarios_laborales`
--
ALTER TABLE `core_horarios_laborales`
  ADD PRIMARY KEY (`dia_semana`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `act_c_clasificacion`
--
ALTER TABLE `act_c_clasificacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `act_c_tipos`
--
ALTER TABLE `act_c_tipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `act_r_adjuntos`
--
ALTER TABLE `act_r_adjuntos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `act_r_comentarios`
--
ALTER TABLE `act_r_comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `act_r_involucrados`
--
ALTER TABLE `act_r_involucrados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `core`
--
ALTER TABLE `core`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `core_feriados`
--
ALTER TABLE `core_feriados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `menu_permission`
--
ALTER TABLE `menu_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `users_types`
--
ALTER TABLE `users_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
