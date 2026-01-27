-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-01-2026 a las 19:04:45
-- Versión del servidor: 10.4.32-MariaDB-log
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cft_empleabilidad`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas_empleo`
--

CREATE TABLE `areas_empleo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `areas_empleo`
--

INSERT INTO `areas_empleo` (`id`, `nombre`) VALUES
(4, 'Administración'),
(6, 'Educación'),
(1, 'Industrial'),
(7, 'Logística'),
(8, 'Otras Áreas'),
(2, 'Salud'),
(5, 'TI / Informática'),
(3, 'Turismo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `ruta_logo` varchar(255) DEFAULT NULL,
  `nombre_comercial` varchar(255) DEFAULT NULL,
  `razon_social` varchar(150) DEFAULT NULL,
  `rut` varchar(20) DEFAULT NULL,
  `rubro_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tamano_id` bigint(20) UNSIGNED DEFAULT NULL,
  `correo_contacto` varchar(150) NOT NULL,
  `telefono_contacto` varchar(50) NOT NULL,
  `sitio_web` varchar(255) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `recepcion_postulaciones` enum('plataforma','correo','url') NOT NULL DEFAULT 'plataforma',
  `correo_postulaciones` varchar(150) DEFAULT NULL,
  `url_postulaciones` varchar(255) DEFAULT NULL,
  `mostrar_sueldo` tinyint(1) NOT NULL DEFAULT 1,
  `mostrar_logo` tinyint(1) NOT NULL DEFAULT 1,
  `nombre_representante` varchar(150) DEFAULT NULL,
  `cargo_representante` varchar(150) DEFAULT NULL,
  `correo_representante` varchar(150) DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `usuario_id`, `ruta_logo`, `nombre_comercial`, `razon_social`, `rut`, `rubro_id`, `tamano_id`, `correo_contacto`, `telefono_contacto`, `sitio_web`, `region`, `ciudad`, `direccion`, `descripcion`, `linkedin`, `instagram`, `facebook`, `recepcion_postulaciones`, `correo_postulaciones`, `url_postulaciones`, `mostrar_sueldo`, `mostrar_logo`, `nombre_representante`, `cargo_representante`, `correo_representante`, `creado_en`, `actualizado_en`) VALUES
(1, 2, NULL, 'Empresa123', 'Empresa1', '12.345.678-9', NULL, NULL, 'empresa1@gmail.com', 'No informado', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'plataforma', NULL, NULL, 1, 1, NULL, NULL, NULL, '2025-11-18 18:44:28', '2025-12-09 14:57:26'),
(2, 4, NULL, 'Empresa2', 'Empresa2', '12.345.678-10', NULL, NULL, 'Empresa2@Empresa2.cl', 'No informado', 'https://empresa2.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'plataforma', NULL, NULL, 1, 1, NULL, NULL, NULL, '2025-11-27 15:16:23', '2025-12-16 16:13:21'),
(3, 5, 'empresas/vkcZXsMWYiJDUTDRNppMNgo7H006eGjQ5CPM7Zy9.jpg', 'Empresa234', 'Empresa2', '12.345.678-10', NULL, NULL, 'Empresa2@empresa2.com', '12345678', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'plataforma', NULL, NULL, 1, 1, NULL, NULL, NULL, '2025-11-27 15:16:58', '2025-12-29 15:30:42'),
(4, 13, NULL, NULL, 'empresa6', '13.111.111-1', NULL, NULL, 'empresa6@empresa6.cl', '+56983249135', 'https://empresa6.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'plataforma', NULL, NULL, 1, 1, NULL, NULL, NULL, '2025-12-09 14:55:20', '2025-12-09 14:55:20'),
(5, 17, 'empresas/gEqWlOfKI5nKJOkbWRkM9a2KLOGX96qfjOg81OBJ.jpg', 'Diego Empresa', NULL, '33.333.333-3', NULL, NULL, 'dcarrasco@innpack.cl', 'No informado', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'plataforma', NULL, NULL, 1, 1, NULL, NULL, NULL, '2025-12-16 16:39:55', '2025-12-16 17:11:04'),
(6, 18, NULL, 'Nueva Empresa', NULL, '44.444.444-4', NULL, NULL, 'Nuevaempresa@Nuevaempresa.cl', 'No informado', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'plataforma', NULL, NULL, 1, 1, NULL, NULL, NULL, '2025-12-17 13:46:07', '2025-12-17 13:46:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `run` varchar(20) DEFAULT NULL,
  `estado_carrera` varchar(50) DEFAULT NULL,
  `carrera` varchar(150) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `resumen` text DEFAULT NULL,
  `institucion` varchar(150) DEFAULT NULL,
  `anio_egreso` year(4) DEFAULT NULL,
  `cursos` text DEFAULT NULL,
  `ruta_cv` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `portfolio_url` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `area_interes_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jornada_preferencia_id` bigint(20) UNSIGNED DEFAULT NULL,
  `modalidad_preferencia_id` bigint(20) UNSIGNED DEFAULT NULL,
  `visibilidad` enum('publico','privado') NOT NULL DEFAULT 'publico',
  `frecuencia_alertas` enum('diario','semanal','ninguna') NOT NULL DEFAULT 'diario',
  `creado_en` timestamp NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `usuario_id`, `run`, `estado_carrera`, `carrera`, `telefono`, `ciudad`, `resumen`, `institucion`, `anio_egreso`, `cursos`, `ruta_cv`, `linkedin_url`, `portfolio_url`, `avatar`, `area_interes_id`, `jornada_preferencia_id`, `modalidad_preferencia_id`, `visibilidad`, `frecuencia_alertas`, `creado_en`, `actualizado_en`) VALUES
(1, 1, '180875344', 'Egresado/a', 'Desarrollo Web', '983249135', 'Santiago', 'asdasdasdasdasd', 'asdasdasdasd', '2025', 'asdasdasdasdasdas', 'cv/UzcVQtQSgd1LMnyuPbIBOl5gL1YYAWNpcgY5raRe.pdf', 'https://www.linkedin.com/in/diego-carrasco-ord%C3%B3%C3%B1ez-343aa9323/', NULL, 'avatars/SZxjWJhgj2dgodz6d3dnA5ifdDjSnrPbLp0tjJRC.jpg', 2, 1, 1, 'publico', 'diario', '2025-11-18 00:10:33', '2025-12-16 12:14:44'),
(2, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'publico', 'diario', '2025-11-19 17:09:25', '2025-11-19 17:09:25'),
(3, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'publico', 'diario', '2025-12-02 12:37:34', '2025-12-02 12:37:34'),
(4, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'publico', 'diario', '2025-12-02 15:14:17', '2025-12-02 15:14:17'),
(5, 10, NULL, 'Titulado', 'Turismo', '983249135', 'Santiago', 'Prueba', 'CFT Magallanes', '2025', 'Prueba', NULL, 'https://www.linkedin.com/in/diego-carrasco-ord%C3%B3%C3%B1ez-343aa9323/', NULL, NULL, NULL, NULL, NULL, 'publico', 'diario', '2025-12-05 14:52:42', '2025-12-05 14:52:42'),
(6, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'publico', 'diario', '2025-12-10 13:29:44', '2025-12-10 13:29:44'),
(7, 15, NULL, 'Titulado', 'Medicina', '983249135', 'Porvenir', 'Ejemplo', 'CFT Magallanes', '2025', 'Ejemplo', NULL, 'https://www.linkedin.com/in/diego-carrasco-ord%C3%B3%C3%B1ez-343aa9323/', 'https://www.linkedin.com/in/diego-carrasco-ord%C3%B3%C3%B1ez-343aa9323/', NULL, NULL, NULL, NULL, 'publico', 'diario', '2025-12-11 16:08:05', '2025-12-11 16:08:05'),
(8, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'publico', 'diario', '2025-12-16 16:23:17', '2025-12-16 16:23:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `experiencias_estudiante`
--

CREATE TABLE `experiencias_estudiante` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `puesto` varchar(150) NOT NULL,
  `empresa` varchar(150) NOT NULL,
  `periodo` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jornadas`
--

CREATE TABLE `jornadas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `jornadas`
--

INSERT INTO `jornadas` (`id`, `nombre`) VALUES
(2, 'Part-time'),
(1, 'Tiempo completo'),
(3, 'Turnos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(5, '2025_12_05_130100_add_soft_delete_to_usuarios_table', 2),
(6, '2025_12_09_144849_update_apellido_nullable_in_usuarios_table', 3),
(7, '2025_12_09_145022_update_nombre_comercial_nullable_in_empresas_table', 4),
(8, '2025_12_09_155656_update_ofertas_trabajo_workflow', 5),
(9, '2025_12_11_183004_create_recursos_empleabilidad_table', 6),
(10, '2025_12_17_131231_add_otras_areas_to_areas_empleo', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modalidades`
--

CREATE TABLE `modalidades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modalidades`
--

INSERT INTO `modalidades` (`id`, `nombre`) VALUES
(3, 'Híbrido'),
(1, 'Presencial'),
(2, 'Remoto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ofertas_favoritas`
--

CREATE TABLE `ofertas_favoritas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `oferta_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_guardado` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ofertas_trabajo`
--

CREATE TABLE `ofertas_trabajo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `area_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_contrato_id` bigint(20) UNSIGNED NOT NULL,
  `modalidad_id` bigint(20) UNSIGNED NOT NULL,
  `jornada_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vacantes` int(10) UNSIGNED DEFAULT 1,
  `fecha_cierre` date DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `sueldo_min` decimal(12,2) DEFAULT NULL,
  `sueldo_max` decimal(12,2) DEFAULT NULL,
  `mostrar_sueldo` tinyint(1) NOT NULL DEFAULT 1,
  `beneficios` text DEFAULT NULL,
  `descripcion` text NOT NULL,
  `requisitos` text NOT NULL,
  `habilidades_deseadas` text DEFAULT NULL,
  `ruta_archivo` varchar(255) DEFAULT NULL,
  `nombre_contacto` varchar(150) NOT NULL,
  `correo_contacto` varchar(150) NOT NULL,
  `telefono_contacto` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 0,
  `motivo_rechazo` text DEFAULT NULL,
  `revisada_en` timestamp NULL DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ofertas_trabajo`
--

INSERT INTO `ofertas_trabajo` (`id`, `empresa_id`, `titulo`, `area_id`, `tipo_contrato_id`, `modalidad_id`, `jornada_id`, `vacantes`, `fecha_cierre`, `region`, `ciudad`, `direccion`, `sueldo_min`, `sueldo_max`, `mostrar_sueldo`, `beneficios`, `descripcion`, `requisitos`, `habilidades_deseadas`, `ruta_archivo`, `nombre_contacto`, `correo_contacto`, `telefono_contacto`, `estado`, `motivo_rechazo`, `revisada_en`, `creado_en`, `actualizado_en`) VALUES
(1, 1, 'Desarrollador Web Junior (Oferta de Prueba)', 1, 1, 1, 1, 1, NULL, 'Magallanes', 'Punta Arenas', '—', 550000.00, 750000.00, 1, 'Ambiente amigable, oportunidades de crecimiento.', 'Esta es una oferta de prueba para validar el módulo de postulaciones.', 'Conocimientos básicos en PHP, Laravel o similares.', 'Trabajo en equipo, Git básico.', NULL, 'RRHH CFT', 'contacto@empresa.cl', '912345678', 1, NULL, NULL, '2025-11-19 13:49:53', '2025-11-24 17:05:31'),
(2, 1, 'Desarrollador Web Junior', 1, 1, 1, 1, 2, '2025-12-31', 'Magallanes', 'Punta Arenas', 'Av. Principal 123', 550000.00, 750000.00, 1, 'Seguro complementario, snacks, capacitación interna', 'Buscamos un desarrollador junior para apoyar en proyectos web institucionales.', 'Conocimientos en HTML, CSS, JavaScript. Ganas de aprender.', 'HTML, CSS, JS, Git', NULL, 'María López', 'mlopez@empresa1.cl', '+56911111111', 1, 'PRUEBA 2', '2025-12-09 22:48:56', '2025-11-24 15:52:54', '2025-12-09 22:48:56'),
(3, 1, 'Asistente Administrativo', 2, 2, 1, 1, 1, '2025-11-30', 'Magallanes', 'Punta Arenas', 'Calle Los Robles 456', 500000.00, 650000.00, 1, 'Tickets de colación, días administrativos', 'Apoyo en tareas administrativas, manejo de documentos y coordinación interna.', 'Manejo de Office, orden y responsabilidad.', 'Excel, Redacción, Organización', NULL, 'Juan Pérez', 'jperez@empresa1.cl', '+56922222222', 1, NULL, NULL, '2025-11-24 15:53:03', '2025-11-24 17:05:31'),
(4, 1, 'Técnico Soporte TI', 3, 1, 3, 1, 1, '2025-12-15', 'Magallanes', 'Punta Arenas', 'Bories 789', 600000.00, 800000.00, 1, 'Colación, seguro complementario', 'Soporte presencial, resolución de incidencias, mantenimiento básico.', 'Experiencia básica en soporte TI, redes y hardware.', 'Redes, Hardware, Atención usuario', NULL, 'Carlos Soto', 'csoto@empresa1.cl', '+56933333333', 1, NULL, '2025-12-09 20:50:09', '2025-11-24 15:53:10', '2025-12-09 20:50:09'),
(13, 3, 'desarrollo 2', 8, 1, 1, 1, 2, '2025-12-17', 'Magallanes', 'Punta Arenas', 'ejemplo123', 8000000.00, 8000000.00, 1, 'Prueba1', 'asjdhasfabsdbasjdahsjdhajshdjahsdasd', 'asdasdasd', 'asdasdasd', NULL, 'Prueba1', 'Prueba1@prueba1.com', '+5691234567', 4, 'Prueba', '2025-12-09 22:40:27', '2025-12-02 18:21:14', '2025-12-17 17:48:38'),
(14, 3, 'Desarrollo Web', 5, 1, 1, 1, 2, '2025-12-05', 'Magallanes', 'Punta Arenas', 'ejemplo123', 500000.00, 500000.00, 1, 'prueba123', '123456', '123456', '123456', NULL, 'prueba123', 'prueba123@prueba123.com', '+5691234567', 1, 'Corrección requerida.', '2025-12-09 21:04:03', '2025-12-02 18:25:13', '2025-12-09 21:04:03'),
(15, 3, 'prueba nueva', 4, 1, 1, 1, 3, '2025-12-31', 'Magallanes', 'Porvenir', 'ejemplo123', 500000.00, 600000.00, 1, 'prueba123', 'Prueba 1234', 'NA', 'NA', NULL, 'Diego Carrasco', 'contacto@empresa.cl', '+5691234567', 1, NULL, NULL, '2025-12-09 21:05:42', '2025-12-09 21:07:44'),
(16, 3, 'Tecnico en Nada', 2, 1, 1, 2, 10, '2025-12-17', 'Magallanes', 'Porvenir', 'ejemplo123', 5000000.00, 550000.00, 1, 'prueba123', 'NADA', 'NADA', 'NADA', NULL, 'Diego Carrasco', 'Prueba1@prueba1.com', '+5691234567', 4, 'Corrección requerida.', '2025-12-09 22:41:14', '2025-12-09 21:18:00', '2025-12-17 20:11:07'),
(17, 3, 'Tecnico en Todo', 5, 1, 2, 1, 5, '2025-12-17', 'Magallanes', 'Prueba1', 'ejemplo123', 400000.00, 450000.00, 1, 'prueba123', 'adsdasd', 'asdasda', 'sdasdasd', NULL, 'Diego Carrasco', 'Prueba1@prueba1.com', '+5691234567', 4, NULL, '2025-12-09 22:44:21', '2025-12-09 22:43:16', '2025-12-17 17:40:57'),
(18, 3, 'Desarrollo Web3.2', 5, 1, 1, 1, 10, '2025-12-31', 'Magallanes', 'Porvenir', 'ejemplo123', 550000.00, 800000.00, 1, 'prueba123', 'NADA', 'NADA', 'NADA', NULL, 'Diego Carrasco', 'Prueba1@prueba1.com', '+5691234567', 1, NULL, '2025-12-09 23:00:07', '2025-12-09 22:59:13', '2025-12-09 23:00:07'),
(19, 3, 'Actualizacion', 3, 3, 1, 3, 1, '2025-12-31', 'Magallanes', 'Santiago', 'ejemplo123', 500000.00, 550000.00, 1, 'prueba123', 'NADA', 'NADA', 'NADA', NULL, 'Diego Carrasco', 'Prueba1@prueba1.com', '+5691234567', 1, NULL, '2025-12-10 19:59:05', '2025-12-10 19:58:34', '2025-12-10 19:59:05'),
(20, 3, 'Viernes', 1, 1, 1, 1, 2, '2025-12-17', 'Magallanes', 'Porvenir', 'ejemplo123', 500000.00, 550000.00, 1, 'prueba123', 'NADA', 'NADA', 'NADA', NULL, 'Diego Carrasco', 'Prueba1@prueba1.com', '+5691234567', 4, NULL, '2025-12-12 18:32:00', '2025-12-12 18:29:43', '2025-12-17 20:26:01'),
(21, 3, 'Lunes', 1, 1, 1, 1, 10, NULL, 'Magallanes', 'Santiago', 'ejemplo123', 500000.00, 500000.00, 1, 'prueba123', 'nasdasdasdas', 'NADA', 'NADA', NULL, 'NADA', 'NADA@NADA.CL', '12345678', 1, NULL, '2025-12-15 22:07:03', '2025-12-15 22:04:58', '2025-12-17 17:10:58'),
(22, 5, 'Diego Empresa', 5, 1, 1, 1, 2, '2025-12-31', 'Magallanes', 'Punta Arenas', 'ejemplo123', 600000.00, 650000.00, 1, 'prueba123', 'Sin Descripcion', 'Sin Requisitos', 'Sin conocimientos deseables', NULL, 'Diego Carrasco', 'dcarrasco@innpack.cl', '+5691234567', 1, NULL, '2025-12-16 19:42:00', '2025-12-16 19:41:25', '2025-12-16 19:42:00'),
(23, 5, 'Oferta 2', 1, 1, 1, 1, 3, '2025-12-31', 'Magallanes', 'Punta Arenas', 'ejemplo123', 750000.00, 800000.00, 1, 'prueba123', 'Sin Descripcion', 'Sin Requisitos', 'Sin Conocimientos Deseables', NULL, 'Diego Carrasco', 'dcarrasco@innpack.cl', '+5691234567', 1, NULL, '2025-12-16 20:02:39', '2025-12-16 20:02:02', '2025-12-16 20:02:39'),
(25, 5, 'Oferta 3', 8, 2, 2, 1, 10, NULL, 'Magallanes', 'Prueba1', 'ejemplo123', 550000.00, 550000.00, 1, 'Sin Beneficios', 'Sin D', 'Si R', 'SIN CD', NULL, 'Diego Carrasco', 'dcarrasco@innpack.cl', '+5691234567', 1, 'prueba', '2025-12-17 19:54:57', '2025-12-16 21:17:15', '2025-12-17 19:54:57'),
(26, 3, 'Prueba6', 8, 1, 1, 1, 3, '2025-12-31', 'Magallanes', 'Punta Arenas', 'ejemplo123', 600000.00, 650000.00, 1, 'prueba123', 'NADA', 'NADA', 'NADA', NULL, 'Diego Carrasco', 'dcarrasco@innpack.cl', '+5691234567', 1, 'Prueba', '2025-12-17 20:26:22', '2025-12-17 16:54:54', '2025-12-17 20:26:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postulaciones`
--

CREATE TABLE `postulaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `oferta_id` bigint(20) UNSIGNED NOT NULL,
  `estado_postulacion` varchar(50) DEFAULT 'pendiente',
  `fecha_postulacion` timestamp NULL DEFAULT current_timestamp(),
  `notas` text DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `postulaciones`
--

INSERT INTO `postulaciones` (`id`, `estudiante_id`, `oferta_id`, `estado_postulacion`, `fecha_postulacion`, `notas`, `creado_en`, `actualizado_en`) VALUES
(1, 1, 1, 'pendiente', '2025-11-19 17:27:10', NULL, '2025-11-19 17:27:10', '2025-11-19 17:27:10'),
(2, 1, 3, 'pendiente', '2025-11-24 19:34:56', NULL, '2025-11-24 19:34:56', '2025-11-24 19:34:56'),
(3, 1, 2, 'retirada', '2025-11-24 20:28:05', NULL, '2025-11-24 20:28:05', '2025-12-18 22:58:41'),
(6, 1, 4, 'pendiente', '2025-11-28 17:59:53', NULL, '2025-11-28 17:59:53', '2025-11-28 17:59:53'),
(7, 4, 2, 'pendiente', '2025-12-02 18:16:06', NULL, '2025-12-02 18:16:06', '2025-12-02 18:16:06'),
(8, 4, 1, 'pendiente', '2025-12-02 18:32:18', NULL, '2025-12-02 18:32:18', '2025-12-02 18:32:18'),
(9, 4, 4, 'pendiente', '2025-12-02 18:32:32', NULL, '2025-12-02 18:32:32', '2025-12-02 18:32:32'),
(10, 1, 13, 'pendiente', '2025-12-02 19:05:54', NULL, '2025-12-02 19:05:54', '2025-12-02 19:05:54'),
(11, 1, 14, 'pendiente', '2025-12-02 19:20:41', NULL, '2025-12-02 19:20:41', '2025-12-02 19:20:41'),
(12, 4, 13, 'pendiente', '2025-12-04 19:56:00', NULL, '2025-12-04 19:56:00', '2025-12-04 19:56:00'),
(13, 4, 14, 'pendiente', '2025-12-04 19:56:16', NULL, '2025-12-04 19:56:16', '2025-12-04 19:56:16'),
(14, 5, 2, 'pendiente', '2025-12-05 17:53:17', NULL, '2025-12-05 17:53:17', '2025-12-05 17:53:17'),
(15, 5, 4, 'pendiente', '2025-12-05 17:53:41', NULL, '2025-12-05 17:53:41', '2025-12-05 17:53:41'),
(16, 4, 15, 'pendiente', '2025-12-09 21:08:31', NULL, '2025-12-09 21:08:31', '2025-12-09 21:08:31'),
(17, 6, 4, 'pendiente', '2025-12-10 16:30:12', NULL, '2025-12-10 16:30:12', '2025-12-10 16:30:12'),
(18, 1, 19, 'retirada', '2025-12-10 20:01:29', NULL, '2025-12-10 20:01:29', '2025-12-17 15:50:55'),
(19, 8, 22, 'pendiente', '2025-12-16 19:42:40', NULL, '2025-12-16 19:42:40', '2025-12-16 19:42:40'),
(20, 1, 23, 'pendiente', '2025-12-17 16:00:21', NULL, '2025-12-17 16:00:21', '2025-12-17 16:00:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recursos_empleabilidad`
--

CREATE TABLE `recursos_empleabilidad` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `resumen` varchar(250) DEFAULT NULL,
  `contenido` longtext DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 0,
  `creado_en` timestamp NULL DEFAULT NULL,
  `actualizado_en` timestamp NULL DEFAULT NULL,
  `eliminado_en` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `recursos_empleabilidad`
--

INSERT INTO `recursos_empleabilidad` (`id`, `titulo`, `resumen`, `contenido`, `imagen`, `estado`, `creado_en`, `actualizado_en`, `eliminado_en`) VALUES
(1, 'Cómo preparar tu primera entrevista laboral', 'Prepararte para una entrevista laboral es clave para causar una buena impresión. Conoce qué evaluar, cómo responder preguntas frecuentes y qué errores evitar para aumentar tus posibilidades de éxito.', 'Enfrentar tu primera entrevista laboral puede generar nervios e inseguridad, pero con una buena preparación puedes transformar esa experiencia en una gran oportunidad.\r\n\r\nAntes de la entrevista, investiga sobre la empresa: su rubro, valores y funciones principales. Esto demuestra interés y compromiso. Además, revisa cuidadosamente la oferta laboral y prepara ejemplos concretos de tus habilidades, estudios o experiencias prácticas.\r\n\r\nDurante la entrevista, mantén una postura segura, escucha atentamente y responde con claridad. No temas reconocer si no tienes experiencia laboral previa; enfócate en tus competencias, motivación y disposición para aprender.\r\n\r\nFinalmente, recuerda que la entrevista es una instancia de diálogo. Puedes hacer preguntas sobre el cargo, el equipo de trabajo o el proceso de inducción. Mostrar interés y actitud positiva puede marcar la diferencia.', 'recursos/HkclwQbf97Ey4JDX5rlZz6D1Mje55ioBsi24tD0y.jpg', 1, '2025-12-15 15:23:49', '2025-12-15 17:56:37', NULL),
(2, 'Claves para crear un currículum vitae atractivo y efectivo', 'Un buen currículum puede abrirte la puerta a tu primer empleo o práctica profesional. Aprende cómo estructurarlo correctamente, qué información incluir y cómo destacar tus fortalezas sin experiencia previa.', 'El currículum vitae es tu carta de presentación ante las empresas, por lo que debe ser claro, ordenado y fácil de leer. No es necesario que sea extenso: una o dos páginas son suficientes.\r\n\r\nIncluye tus datos personales básicos, formación académica, habilidades técnicas y competencias personales. Si no cuentas con experiencia laboral, destaca prácticas, proyectos académicos, trabajos voluntarios o actividades relevantes.\r\n\r\nUtiliza un lenguaje sencillo y evita errores de ortografía. Un diseño limpio, con títulos claros y buena separación visual, facilitará la lectura del reclutador.\r\n\r\nPor último, adapta tu currículum a cada oferta laboral. Resaltar las habilidades que se relacionan directamente con el cargo aumentará tus posibilidades de ser llamado a entrevista.', 'recursos/z8uD8sxoLYSrdvh4orXq0eIdVA8DrVTGJOdqIxfB.jpg', 1, '2025-12-15 17:58:06', '2025-12-15 18:03:07', NULL),
(3, 'Errores comunes al buscar empleo y cómo evitarlos', 'Buscar trabajo puede ser un proceso desafiante. Identifica los errores más frecuentes al postular a empleos y descubre cómo mejorar tu estrategia para aumentar tus oportunidades laborales.', 'Uno de los errores más comunes al buscar empleo es postular sin leer detenidamente la oferta laboral. Esto puede generar postulaciones poco efectivas y frustración.\r\n\r\nOtro error frecuente es no mantener actualizado el currículum o utilizar el mismo documento para todas las postulaciones. Cada cargo requiere un enfoque distinto y destacar habilidades específicas.\r\n\r\nTambién es importante cuidar la comunicación: responder correos de manera formal, llegar puntualmente a entrevistas y mantener una actitud respetuosa en todo el proceso.\r\n\r\nBuscar empleo requiere constancia y organización. Planificar tus postulaciones, mejorar continuamente tu perfil y aprender de cada experiencia te acercará cada vez más a tu objetivo laboral.', 'recursos/WZFEyZYEs0yUO6I1RDFfeE1dxze98PRziCOqI5Jq.jpg', 1, '2025-12-15 18:04:35', '2025-12-15 18:13:11', NULL),
(4, 'ejemplo', 'ejemplo', 'ejemplo', 'recursos/tdf42vgSlI9pNZabOIWHmqKzjVQ2QKy7tOYfCX34.jpg', 1, '2025-12-15 22:08:29', '2025-12-19 18:39:01', '2025-12-19 18:39:01'),
(5, 'Prueba 2', 'pba', 'pba', 'recursos/guzrGOVOpWIE4rfTArNtrts17ihRy2WIcjDOBNxl.jpg', 1, '2025-12-22 17:38:38', '2025-12-22 22:15:04', '2025-12-22 22:15:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`) VALUES
(1, 'administrador', 'Acceso completo al sistema'),
(2, 'empresa', 'Usuario empresa que publica ofertas'),
(3, 'estudiante', 'Usuario postulante/estudiante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubros`
--

CREATE TABLE `rubros` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rubros`
--

INSERT INTO `rubros` (`id`, `nombre`) VALUES
(7, 'Administración'),
(1, 'Construcción'),
(4, 'Educación'),
(2, 'Industrial'),
(3, 'Salud'),
(5, 'Servicios'),
(8, 'TI / Informática'),
(6, 'Turismo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('KKmQLVzjvCebbEBfA3tPyPJMNhM74prRHEpXYbR8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieDJmT2dxYTU3TDhibG1KZ2xkcWFnUGNFM3pXM25qRlZHQmtVSW1DWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1768500860);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tamanos_empresa`
--

CREATE TABLE `tamanos_empresa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tamanos_empresa`
--

INSERT INTO `tamanos_empresa` (`id`, `nombre`) VALUES
(1, '1-10'),
(2, '11-50'),
(4, '201-500'),
(5, '500+'),
(3, '51-200');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_contrato`
--

CREATE TABLE `tipos_contrato` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipos_contrato`
--

INSERT INTO `tipos_contrato` (`id`, `nombre`) VALUES
(4, 'Honorarios'),
(2, 'Indefinido'),
(1, 'Plazo fijo'),
(3, 'Práctica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rol_id` tinyint(3) UNSIGNED NOT NULL,
  `rut` varchar(20) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `email_verificado_en` timestamp NULL DEFAULT NULL,
  `token_recordar` varchar(100) DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `rol_id`, `rut`, `nombre`, `apellido`, `email`, `contrasena`, `email_verificado_en`, `token_recordar`, `creado_en`, `actualizado_en`, `deleted_at`) VALUES
(1, 3, NULL, 'Diego', 'Carrasco', 'diegocarrasco.ordonez@gmail.com', '$2y$12$03oXcLtV5TPy5YJPdwXDgeVj1iKkhcWDp71tyTTHFnJQ6BZTIx8he', NULL, NULL, '2025-11-18 00:10:33', '2025-12-29 15:19:58', NULL),
(2, 2, '12.345.678-10', 'Empresa1', NULL, 'empresa1@gmail.com', '$2y$12$f/uwr7C70CcE2C5iCB/3i.1G8Kq.nr.p.hBgJrfyHIQb2BhSscXQi', NULL, NULL, '2025-11-18 18:44:28', '2025-12-09 14:57:26', NULL),
(3, 3, NULL, 'Usuario2', 'Usuario2', 'pba@pba.com', '$2y$12$MEd.FyAcZ90QZtkWfqDVDeWpMz6dAMJVQWLyOQV87vVEWydWGt6Pm', NULL, NULL, '2025-11-19 17:09:25', '2025-11-19 17:09:25', NULL),
(4, 2, '2.222.222-2', 'Empresa2', NULL, 'Empresa2@Empresa2.cl', '$2y$12$w.TKP5SuSIOpkHTXdBVS7u.5a11JUIhqiQxUW3NtHVQpbHONlW4.a', NULL, NULL, '2025-11-27 15:16:23', '2025-12-16 16:13:21', NULL),
(5, 2, '77.777.777-7', 'Empresa2', 'Empresa2', 'Empresa2@empresa2.com', '$2y$12$yeOIJSnqWYcfjTRgkBWUzOwOlJwurgekvL/YL8kjj7FYgBQtZvKS6', NULL, NULL, '2025-11-27 15:16:58', '2025-12-29 15:30:42', NULL),
(7, 1, NULL, 'admin', 'admin', 'admin@cft.cl', '$2y$12$OQojOMvgO.n9ShaENOH2WO4iM84vTgGDtIMi5pu0pdDSmbIPqlLYK', NULL, NULL, '2025-12-02 12:37:34', '2025-12-02 12:38:54', NULL),
(8, 3, NULL, 'ejemplo123', 'ejemplo123', 'ejemplo123@ejemplo123.com', '$2y$12$WjNZidNR2hliwPIIE1wjruqh1ihQiYAvBwiWTzVsBN57RNbOLah8m', NULL, NULL, '2025-12-02 15:14:17', '2025-12-09 11:34:08', NULL),
(9, 3, '12.345.678-11', 'Prueba formulario', '1', 'formulario@forumlario.com', '$2y$12$eoDAe1XHXn.gpjWdZXZQbe6YgO4peyKG7GXQzmjAqXWw/C8LBAyk6', NULL, NULL, '2025-12-05 17:35:47', '2025-12-11 14:38:05', '2025-12-11 17:38:05'),
(10, 3, '12.345.678-12', 'Prueba', 'Forumlario 2', 'formulario2@forumlario.com', '$2y$12$emCBH.nUOhSJ85vVnVHIt..n/JQTzRkHultQ/enMYXgfw5Gb2WEta', NULL, NULL, '2025-12-05 14:52:42', '2025-12-05 14:52:42', NULL),
(11, 2, '11.111.111-1', 'empres4', NULL, 'empresa4@empresa4.cl', '$2y$12$vOAiXYbhJLlq4f8mmas8yu3YoliH.HGumbCCdFTAtWk5bwpNLhAAy', NULL, NULL, '2025-12-09 14:49:13', '2025-12-11 14:38:26', '2025-12-11 17:38:26'),
(12, 2, '12.111.111-1', 'empresa5', NULL, 'empresa5@empresa5.cl', '$2y$12$ke/XMME4S1BPX2ena5b8CuNSD5vVeM9FIpshBEC0FI49KhLrmMo66', NULL, NULL, '2025-12-09 14:52:26', '2025-12-09 14:55:50', NULL),
(13, 2, '13.111.111-1', 'empresa7', NULL, 'empresa6@empresa6.cl', '$2y$12$W665UciESX.1cCdkwor1AelosMv6GLrcFaoavkiNummwVWGrU366q', NULL, NULL, '2025-12-09 14:55:20', '2025-12-09 14:56:00', NULL),
(14, 3, NULL, 'miercoles', 'miercoles', 'miercoles@miercoles.cl', '$2y$12$V2K9Uy46M5MoU.pbyC93zO4hFXJMFqtHq.q76.SHT6CMZ.50JRwxq', NULL, NULL, '2025-12-10 13:29:44', '2025-12-10 13:29:44', NULL),
(15, 3, '22.222.222.-2', 'Jueves1', 'Jueves1', 'jueves@jueves.cl', '$2y$12$aiDypUacc0L.17XCoRKpFeF62WOZOx6P2dFBmjDZO5LcA1fNT2NK.', NULL, NULL, '2025-12-11 16:08:05', '2025-12-16 19:12:11', NULL),
(16, 3, NULL, 'Diego2', 'Carrasco2', 'dicarrasco@cftdemagallanes.cl', '$2y$12$Mei.EJhOZihAxT67FnlFAuCwhRwnhVQjXD/iJpTQ8On/PK9IovTIG', NULL, NULL, '2025-12-16 16:23:17', '2025-12-16 16:23:17', NULL),
(17, 2, NULL, 'Diego Empresa', 'Diego Empresa', 'dcarrasco@innpack.cl', '$2y$12$AZsBZ.73IVcYM61mlxFZbOwvYaE9qX10vfNlSy6Gd8v9VYLMMIv6m', NULL, NULL, '2025-12-16 16:39:55', '2025-12-16 16:39:55', NULL),
(18, 2, NULL, 'Nueva Empresa', 'Nueva Empresa', 'Nuevaempresa@Nuevaempresa.cl', '$2y$12$6J3rBf7Xb6Tz2vDgs5rSUOI60orzjuk9/nI.ADa7zos1pClNDOqtG', NULL, NULL, '2025-12-17 13:46:07', '2025-12-17 13:46:07', NULL),
(19, 1, '9.999.999-9', 'Diego', 'Carrasco', 'admin2@cft.cl', '$2y$12$1uSkXWszBSMpXYIA0dWtnOGA2BSkCjiSkUtxmrIbtmaXRiuVao1qm', NULL, NULL, '2026-01-15 21:45:48', '2026-01-15 21:45:48', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas_empleo`
--
ALTER TABLE `areas_empleo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`),
  ADD KEY `fk_empresas_rubro` (`rubro_id`),
  ADD KEY `fk_empresas_tamano` (`tamano_id`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`),
  ADD KEY `fk_estudiantes_area` (`area_interes_id`),
  ADD KEY `fk_estudiantes_jornada` (`jornada_preferencia_id`),
  ADD KEY `fk_estudiantes_modalidad` (`modalidad_preferencia_id`);

--
-- Indices de la tabla `experiencias_estudiante`
--
ALTER TABLE `experiencias_estudiante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_experiencias_estudiante` (`estudiante_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `jornadas`
--
ALTER TABLE `jornadas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modalidades`
--
ALTER TABLE `modalidades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `ofertas_favoritas`
--
ALTER TABLE `ofertas_favoritas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `estudiante_id` (`estudiante_id`,`oferta_id`),
  ADD KEY `fk_favoritas_oferta` (`oferta_id`);

--
-- Indices de la tabla `ofertas_trabajo`
--
ALTER TABLE `ofertas_trabajo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ofertas_empresa` (`empresa_id`),
  ADD KEY `fk_ofertas_area` (`area_id`),
  ADD KEY `fk_ofertas_tipo_contrato` (`tipo_contrato_id`),
  ADD KEY `fk_ofertas_modalidad` (`modalidad_id`),
  ADD KEY `fk_ofertas_jornada` (`jornada_id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `estudiante_id` (`estudiante_id`,`oferta_id`),
  ADD KEY `fk_postulaciones_oferta` (`oferta_id`);

--
-- Indices de la tabla `recursos_empleabilidad`
--
ALTER TABLE `recursos_empleabilidad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `rubros`
--
ALTER TABLE `rubros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `tamanos_empresa`
--
ALTER TABLE `tamanos_empresa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `tipos_contrato`
--
ALTER TABLE `tipos_contrato`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuarios_rol` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas_empleo`
--
ALTER TABLE `areas_empleo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `experiencias_estudiante`
--
ALTER TABLE `experiencias_estudiante`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jornadas`
--
ALTER TABLE `jornadas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `modalidades`
--
ALTER TABLE `modalidades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ofertas_favoritas`
--
ALTER TABLE `ofertas_favoritas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ofertas_trabajo`
--
ALTER TABLE `ofertas_trabajo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `recursos_empleabilidad`
--
ALTER TABLE `recursos_empleabilidad`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rubros`
--
ALTER TABLE `rubros`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tamanos_empresa`
--
ALTER TABLE `tamanos_empresa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipos_contrato`
--
ALTER TABLE `tipos_contrato`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD CONSTRAINT `fk_empresas_rubro` FOREIGN KEY (`rubro_id`) REFERENCES `rubros` (`id`),
  ADD CONSTRAINT `fk_empresas_tamano` FOREIGN KEY (`tamano_id`) REFERENCES `tamanos_empresa` (`id`),
  ADD CONSTRAINT `fk_empresas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `fk_estudiantes_area` FOREIGN KEY (`area_interes_id`) REFERENCES `areas_empleo` (`id`),
  ADD CONSTRAINT `fk_estudiantes_jornada` FOREIGN KEY (`jornada_preferencia_id`) REFERENCES `jornadas` (`id`),
  ADD CONSTRAINT `fk_estudiantes_modalidad` FOREIGN KEY (`modalidad_preferencia_id`) REFERENCES `modalidades` (`id`),
  ADD CONSTRAINT `fk_estudiantes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `experiencias_estudiante`
--
ALTER TABLE `experiencias_estudiante`
  ADD CONSTRAINT `fk_experiencias_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ofertas_favoritas`
--
ALTER TABLE `ofertas_favoritas`
  ADD CONSTRAINT `fk_favoritas_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favoritas_oferta` FOREIGN KEY (`oferta_id`) REFERENCES `ofertas_trabajo` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ofertas_trabajo`
--
ALTER TABLE `ofertas_trabajo`
  ADD CONSTRAINT `fk_ofertas_area` FOREIGN KEY (`area_id`) REFERENCES `areas_empleo` (`id`),
  ADD CONSTRAINT `fk_ofertas_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ofertas_jornada` FOREIGN KEY (`jornada_id`) REFERENCES `jornadas` (`id`),
  ADD CONSTRAINT `fk_ofertas_modalidad` FOREIGN KEY (`modalidad_id`) REFERENCES `modalidades` (`id`),
  ADD CONSTRAINT `fk_ofertas_tipo_contrato` FOREIGN KEY (`tipo_contrato_id`) REFERENCES `tipos_contrato` (`id`);

--
-- Filtros para la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  ADD CONSTRAINT `fk_postulaciones_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_postulaciones_oferta` FOREIGN KEY (`oferta_id`) REFERENCES `ofertas_trabajo` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
