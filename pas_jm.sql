-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 05-12-2025 a las 01:29:09
-- Versión del servidor: 8.0.31
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pas_jm`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actas`
--

DROP TABLE IF EXISTS `actas`;
CREATE TABLE IF NOT EXISTS `actas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `lugar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `constatacion` text COLLATE utf8mb4_unicode_ci,
  `inspector_id` bigint UNSIGNED NOT NULL,
  `administrado_id` bigint UNSIGNED DEFAULT NULL,
  `lat` decimal(10,7) DEFAULT NULL,
  `lng` decimal(10,7) DEFAULT NULL,
  `estado` enum('borrador','emitida','notificada','anulada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrador',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `actas_numero_unique` (`numero`),
  KEY `actas_inspector_id_foreign` (`inspector_id`),
  KEY `actas_administrado_id_foreign` (`administrado_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `actas`
--

INSERT INTO `actas` (`id`, `numero`, `fecha`, `hora`, `lugar`, `constatacion`, `inspector_id`, `administrado_id`, `lat`, `lng`, `estado`, `created_at`, `updated_at`) VALUES
(1, NULL, '2025-10-26', '12:10:00', 'Iquitos', 'test', 1, 3, '-12.0464000', '-77.0428000', 'borrador', '2025-10-26 11:01:31', '2025-10-26 11:01:31'),
(2, 'ACT-000004', '2025-10-26', '12:10:00', 'Iquitos', 'test', 1, 3, NULL, NULL, 'emitida', '2025-10-26 11:16:20', '2025-11-22 05:58:23'),
(3, 'ACT-000001', '2025-10-26', '12:10:00', 'Iquitos', 'test', 1, 3, '-3.7496930', '-73.2540460', 'emitida', '2025-10-26 11:33:11', '2025-10-27 16:27:04'),
(4, 'ACT-000002', '2025-10-26', '12:10:00', 'Iquitos', 'test', 1, 3, '-12.0621000', '-77.0460000', 'emitida', '2025-10-26 11:38:15', '2025-10-29 02:40:53'),
(5, 'ACT-000003', '2025-10-29', '19:03:00', 'Lima', 'Infraccion sin luces', 4, 3, '-12.0793370', '-77.0485570', 'emitida', '2025-10-29 05:05:02', '2025-11-22 05:36:59'),
(6, 'ACT-000005', '2025-10-15', '09:30:00', 'Av. San Felipe cdra. 10, Jesús María', 'Vehículo estacionado en zona rígida frente a cochera.', 9, 5, '-12.0790000', '-77.0505000', 'emitida', '2025-10-15 15:00:00', '2025-10-15 15:00:00'),
(7, 'ACT-000006', '2025-10-20', '11:15:00', 'Jr. Huiracocha 123, Jesús María', 'Comercio ambulatorio sin autorización municipal.', 9, 4, '-12.0743000', '-77.0498000', 'notificada', '2025-10-20 16:30:00', '2025-11-02 14:15:00'),
(8, 'ACT-000007', '2025-11-01', '08:45:00', 'Parque San José, zona central', 'Ruido elevado por equipos de sonido en horario restringido.', 9, 2, '-12.0762000', '-77.0425000', 'emitida', '2025-11-01 14:00:00', '2025-11-01 14:00:00'),
(9, 'ACT-000008', '2025-11-03', '19:10:00', 'Av. Brasil cdra. 15, esquina con Jr. Teodoro Cárdenas', 'Venta de bebidas alcohólicas en la vía pública.', 9, 3, '-12.0789000', '-77.0542000', 'borrador', '2025-11-04 00:30:00', '2025-11-04 00:30:00'),
(10, 'ACT-000009', '2025-11-05', '16:20:00', 'Av. General Garzón cdra. 8', 'Publicidad exterior (panel) sin autorización municipal.', 9, 6, '-12.0715000', '-77.0489000', 'emitida', '2025-11-05 21:45:00', '2025-11-05 21:45:00'),
(11, 'ACT-000010', '2025-11-10', '10:05:00', 'Jr. Horacio Urteaga 450', 'Establecimiento funcionaba fuera del horario permitido.', 9, 6, '-12.0719000', '-77.0423000', 'anulada', '2025-11-10 15:30:00', '2025-11-18 14:00:00'),
(12, 'ACT-000011', '2025-11-15', '21:30:00', 'Av. Salaverry cdra. 30', 'Fiesta con equipos de sonido sin licencia de funcionamiento.', 9, 1, '-12.0860000', '-77.0500000', 'emitida', '2025-11-16 03:00:00', '2025-11-16 03:00:00'),
(13, 'ACT-000012', '2025-11-20', '09:00:00', 'Jr. Ciro Alegría 220', 'Construcción de segundo nivel sin licencia de obra.', 9, 5, '-12.0735000', '-77.0475000', 'notificada', '2025-11-20 14:30:00', '2025-12-02 16:10:00'),
(14, 'ACT-000013', '2025-11-25', '18:40:00', 'Parque Cáceres, zona norte', 'Consumo de bebidas alcohólicas en espacio público.', 1, 2, '-12.0758000', '-77.0418000', 'emitida', '2025-11-26 00:00:00', '2025-11-26 00:00:00'),
(15, 'ACT-000014', '2025-11-28', '07:50:00', 'Av. Arenales cdra. 21', 'Vehículo aparentemente abandonado ocupando vía pública.', 1, 4, '-12.0748000', '-77.0379000', 'emitida', '2025-11-28 13:15:00', '2025-11-28 13:15:00'),
(16, 'ACT-000015', '2025-12-01', '12:05:00', 'Jr. Talara 310', 'Obstaculización de vereda con mercadería.', 9, 7, '-12.0710000', '-77.0440000', 'emitida', '2025-12-01 17:30:00', '2025-12-01 17:30:00'),
(17, 'ACT-000016', '2025-12-05', '13:20:00', 'Mercado San José, ingreso principal', 'Descarga de mercadería en zona no autorizada.', 9, 4, '-12.0772000', '-77.0456000', 'notificada', '2025-12-05 18:45:00', '2025-12-12 15:00:00'),
(18, 'ACT-000017', '2025-12-10', '17:45:00', 'Av. Cuba cdra. 9', 'Uso de parlantes en la vía pública sin permiso.', 9, 3, '-12.0760000', '-77.0468000', 'emitida', '2025-12-10 23:00:00', '2025-12-10 23:00:00'),
(19, 'ACT-000018', '2025-12-15', '20:10:00', 'Jr. Moore 560', 'Local sin certificado vigente de Inspección Técnica de Seguridad en Edificaciones.', 9, 6, '-12.0742000', '-77.0515000', 'anulada', '2025-12-16 01:30:00', '2025-12-22 14:30:00'),
(20, 'ACT-000019', '2025-12-20', '09:55:00', 'Av. San Felipe cdra. 12', 'Obra civil ocupando vereda sin señalización adecuada.', 9, 7, '-12.0783000', '-77.0492000', 'emitida', '2025-12-20 15:15:00', '2025-12-20 15:15:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acta_infraccion`
--

DROP TABLE IF EXISTS `acta_infraccion`;
CREATE TABLE IF NOT EXISTS `acta_infraccion` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `acta_id` bigint UNSIGNED NOT NULL,
  `infraccion_id` bigint UNSIGNED NOT NULL,
  `gravedad` enum('leve','grave','muy_grave') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `acta_infraccion_acta_id_infraccion_id_unique` (`acta_id`,`infraccion_id`),
  KEY `acta_infraccion_infraccion_id_foreign` (`infraccion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `acta_infraccion`
--

INSERT INTO `acta_infraccion` (`id`, `acta_id`, `infraccion_id`, `gravedad`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, '2025-10-26 11:01:31', '2025-10-26 11:01:31'),
(2, 6, 1, 'grave', '2025-10-15 15:05:00', '2025-10-15 15:05:00'),
(3, 7, 1, 'grave', '2025-10-20 16:35:00', '2025-10-20 16:35:00'),
(4, 8, 4, 'leve', '2025-11-01 14:05:00', '2025-11-01 14:05:00'),
(5, 9, 5, 'leve', '2025-11-04 00:35:00', '2025-11-04 00:35:00'),
(6, 10, 1, 'muy_grave', '2025-11-05 21:50:00', '2025-11-05 21:50:00'),
(7, 12, 4, 'grave', '2025-11-16 03:05:00', '2025-11-16 03:05:00'),
(8, 13, 1, 'grave', '2025-11-20 14:35:00', '2025-11-20 14:35:00'),
(9, 14, 5, 'leve', '2025-11-26 00:05:00', '2025-11-26 00:05:00'),
(10, 16, 5, 'leve', '2025-12-01 17:35:00', '2025-12-01 17:35:00'),
(11, 18, 4, 'leve', '2025-12-10 23:05:00', '2025-12-10 23:05:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrados`
--

DROP TABLE IF EXISTS `administrados`;
CREATE TABLE IF NOT EXISTS `administrados` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipo_doc` enum('DNI','RUC','CE','PAS') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_doc` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razon_social` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombres` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellidos` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `administrados_tipo_doc_numero_doc_unique` (`tipo_doc`,`numero_doc`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `administrados`
--

INSERT INTO `administrados` (`id`, `tipo_doc`, `numero_doc`, `razon_social`, `nombres`, `apellidos`, `email`, `telefono`, `direccion`, `created_at`, `updated_at`) VALUES
(1, 'RUC', '20123456789', 'Comercial XYZ', NULL, NULL, 'contacto@xyz.com', '999999999', 'Av. Brasil 1234', '2025-10-26 03:59:07', '2025-10-26 03:59:07'),
(2, 'DNI', '44556677', 'Juan Pérez', NULL, NULL, 'juan@example.com', '988887777', 'Jr. Los Olivos 456', '2025-10-26 03:59:07', '2025-10-26 03:59:07'),
(3, 'DNI', '41676622', 'tet', NULL, NULL, 'christian1827@gmail.com', '44444', 'tet', '2025-10-26 11:01:31', '2025-10-26 11:01:31'),
(4, 'RUC', '20600111222', 'Bodega San Felipe', NULL, NULL, 'bodega.sanfelipe@example.com', '999111222', 'Av. San Felipe 1020, Jesús María', '2025-10-15 19:00:00', '2025-10-15 19:00:00'),
(5, 'DNI', '47894561', 'María López Huamán', NULL, NULL, 'maria.lopez@example.com', '988776655', 'Jr. Ciro Alegría 220, Jesús María', '2025-10-18 14:30:00', '2025-10-18 14:30:00'),
(6, 'RUC', '20555666777', 'Restaurante El Buen Sabor', NULL, NULL, 'elbuen.sabor@example.com', '987654321', 'Av. General Garzón 860, Jesús María', '2025-10-20 16:15:00', '2025-10-20 16:15:00'),
(7, 'DNI', '41223344', 'Carlos Ramírez Torres', NULL, NULL, 'carlos.ramirez@example.com', '986543210', 'Jr. Talara 310, Jesús María', '2025-10-22 21:45:00', '2025-10-22 21:45:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boletas`
--

DROP TABLE IF EXISTS `boletas`;
CREATE TABLE IF NOT EXISTS `boletas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `serie` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A001',
  `numero` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acta_id` bigint UNSIGNED DEFAULT NULL,
  `administrado_id` bigint UNSIGNED DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado` enum('emitida','notificada','anulada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'emitida',
  `notified_at` timestamp NULL DEFAULT NULL,
  `pdf_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_hash` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `boletas_serie_numero_unique` (`serie`,`numero`),
  KEY `boletas_acta_id_foreign` (`acta_id`),
  KEY `boletas_administrado_id_foreign` (`administrado_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `boletas`
--

INSERT INTO `boletas` (`id`, `serie`, `numero`, `acta_id`, `administrado_id`, `monto`, `estado`, `notified_at`, `pdf_path`, `qr_hash`, `created_at`, `updated_at`) VALUES
(1, 'A001', '000004', 4, NULL, '450.00', 'emitida', NULL, NULL, NULL, '2025-10-27 09:30:56', '2025-10-27 09:30:56'),
(2, 'A001', '000005', 4, NULL, '450.00', 'emitida', NULL, NULL, NULL, '2025-10-27 09:31:58', '2025-10-27 09:31:58'),
(3, 'A001', '000006', 4, NULL, '450.00', 'emitida', NULL, NULL, NULL, '2025-10-27 09:42:52', '2025-10-27 09:42:52'),
(4, 'A001', '000007', 4, NULL, '450.00', 'emitida', NULL, NULL, NULL, '2025-10-27 09:43:16', '2025-10-27 09:43:16'),
(5, 'A001', '000008', 4, NULL, '450.00', 'emitida', NULL, NULL, NULL, '2025-10-27 09:58:08', '2025-10-27 09:58:08'),
(6, 'A001', '000009', 3, NULL, '0.00', 'emitida', NULL, NULL, NULL, '2025-10-27 10:19:58', '2025-10-27 10:19:58'),
(7, 'A001', '000010', 5, NULL, '500.00', 'emitida', NULL, NULL, NULL, '2025-10-29 05:06:24', '2025-10-29 05:06:24'),
(8, 'A001', '000011', 2, NULL, '0.00', 'emitida', NULL, NULL, NULL, '2025-11-22 05:38:00', '2025-11-22 05:38:00'),
(9, 'A001', '000012', 5, 3, '500.00', 'notificada', '2025-11-25 20:20:00', NULL, NULL, '2025-11-25 20:00:00', '2025-11-25 20:20:00'),
(10, 'A001', '000013', 6, 5, '920.00', 'emitida', NULL, NULL, NULL, '2025-10-15 15:20:00', '2025-10-15 15:20:00'),
(11, 'A001', '000014', 7, 4, '920.00', 'notificada', '2025-11-03 15:00:00', NULL, NULL, '2025-10-20 16:50:00', '2025-11-03 15:00:00'),
(12, 'A001', '000015', 8, 2, '550.00', 'emitida', NULL, NULL, NULL, '2025-11-01 14:20:00', '2025-11-01 14:20:00'),
(13, 'A001', '000016', 10, 6, '920.00', 'emitida', NULL, NULL, NULL, '2025-11-05 22:00:00', '2025-11-05 22:00:00'),
(14, 'A001', '000017', 12, 1, '550.00', 'notificada', '2025-11-23 03:30:00', NULL, NULL, '2025-11-16 03:15:00', '2025-11-23 03:30:00'),
(15, 'A001', '000018', 13, 5, '920.00', 'emitida', NULL, NULL, NULL, '2025-11-20 14:45:00', '2025-11-20 14:45:00'),
(16, 'A001', '000019', 14, 2, '200.00', 'emitida', NULL, NULL, NULL, '2025-11-26 00:10:00', '2025-11-26 00:10:00'),
(17, 'A001', '000020', 15, 4, '200.00', 'anulada', NULL, NULL, NULL, '2025-11-28 13:20:00', '2025-12-05 14:00:00'),
(18, 'A001', '000021', 16, 7, '200.00', 'emitida', NULL, NULL, NULL, '2025-12-01 17:45:00', '2025-12-01 17:45:00'),
(19, 'A001', '000022', 17, 4, '200.00', 'notificada', '2025-12-15 16:00:00', NULL, NULL, '2025-12-05 18:50:00', '2025-12-15 16:00:00'),
(20, 'A001', '000023', 18, 3, '550.00', 'emitida', NULL, NULL, NULL, '2025-12-10 23:15:00', '2025-12-10 23:15:00'),
(21, 'A001', '000024', 19, 6, '920.00', 'anulada', NULL, NULL, NULL, '2025-12-16 01:35:00', '2025-12-22 14:35:00'),
(22, 'A001', '000025', 20, 7, '920.00', 'emitida', NULL, NULL, NULL, '2025-12-20 15:20:00', '2025-12-20 15:20:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogs`
--

DROP TABLE IF EXISTS `catalogs`;
CREATE TABLE IF NOT EXISTS `catalogs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `group` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `catalogs_group_code_unique` (`group`,`code`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `catalogs`
--

INSERT INTO `catalogs` (`id`, `group`, `code`, `label`, `meta`, `created_at`, `updated_at`) VALUES
(1, 'doc', 'DNI', 'DNI', NULL, '2025-10-26 03:59:07', '2025-10-26 03:59:07'),
(2, 'doc', 'RUC', 'RUC', NULL, '2025-10-26 03:59:07', '2025-10-26 03:59:07'),
(3, 'doc', 'CE', 'Carné de Extranjería', NULL, '2025-10-26 03:59:07', '2025-10-26 03:59:07'),
(4, 'doc', 'PAS', 'Pasaporte', NULL, '2025-10-26 03:59:07', '2025-10-26 03:59:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evidencias`
--

DROP TABLE IF EXISTS `evidencias`;
CREATE TABLE IF NOT EXISTS `evidencias` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `acta_id` bigint UNSIGNED NOT NULL,
  `tipo` enum('foto','video') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'foto',
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumb_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint UNSIGNED DEFAULT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hash` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size_bytes` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evidencias_acta_id_foreign` (`acta_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `evidencias`
--

INSERT INTO `evidencias` (`id`, `acta_id`, `tipo`, `path`, `thumb_path`, `mime`, `size`, `original_name`, `hash`, `size_bytes`, `created_at`, `updated_at`) VALUES
(1, 4, 'foto', 'actas/4/e247cf7a-1953-43b6-9d6f-ec33e8f0bb49.png', 'actas/4/thumbs/e247cf7a-1953-43b6-9d6f-ec33e8f0bb49_th.jpg', 'image/png', 169884, 'perfil.png', NULL, NULL, '2025-10-27 08:43:01', '2025-10-27 08:43:01'),
(3, 5, 'foto', 'actas/5/531892c0-5214-4756-865e-534873bc5a96.jpg', 'actas/5/thumbs/531892c0-5214-4756-865e-534873bc5a96_th.jpg', 'image/jpeg', 35063, 'miperfil.jpg', NULL, NULL, '2025-10-29 05:05:20', '2025-10-29 05:05:20'),
(4, 6, 'foto', 'actas/6/20251015-foto-01.jpg', 'actas/6/thumbs/20251015-foto-01_th.jpg', 'image/jpeg', 180000, 'vehiculo-zona-rigida.jpg', NULL, 180000, '2025-10-15 15:10:00', '2025-10-15 15:10:00'),
(5, 7, 'foto', 'actas/7/20251020-foto-01.jpg', 'actas/7/thumbs/20251020-foto-01_th.jpg', 'image/jpeg', 160000, 'comercio-ambulatorio.jpg', NULL, 160000, '2025-10-20 16:40:00', '2025-10-20 16:40:00'),
(6, 8, 'foto', 'actas/8/20251101-foto-01.jpg', 'actas/8/thumbs/20251101-foto-01_th.jpg', 'image/jpeg', 175000, 'parlantes-parque.jpg', NULL, 175000, '2025-11-01 14:10:00', '2025-11-01 14:10:00'),
(7, 10, 'foto', 'actas/10/20251105-foto-01.jpg', 'actas/10/thumbs/20251105-foto-01_th.jpg', 'image/jpeg', 190000, 'panel-publicitario.jpg', NULL, 190000, '2025-11-05 21:55:00', '2025-11-05 21:55:00'),
(8, 12, 'foto', 'actas/12/20251115-foto-01.jpg', 'actas/12/thumbs/20251115-foto-01_th.jpg', 'image/jpeg', 210000, 'evento-nocturno.jpg', NULL, 210000, '2025-11-16 03:10:00', '2025-11-16 03:10:00'),
(9, 13, 'foto', 'actas/13/20251120-foto-01.jpg', 'actas/13/thumbs/20251120-foto-01_th.jpg', 'image/jpeg', 200000, 'obra-sin-licencia.jpg', NULL, 200000, '2025-11-20 14:40:00', '2025-11-20 14:40:00'),
(10, 16, 'foto', 'actas/16/20251201-foto-01.jpg', 'actas/16/thumbs/20251201-foto-01_th.jpg', 'image/jpeg', 150000, 'mercaderia-vereda.jpg', NULL, 150000, '2025-12-01 17:40:00', '2025-12-01 17:40:00'),
(11, 18, 'foto', 'actas/18/20251210-foto-01.jpg', 'actas/18/thumbs/20251210-foto-01_th.jpg', 'image/jpeg', 165000, 'parlantes-via-publica.jpg', NULL, 165000, '2025-12-10 23:10:00', '2025-12-10 23:10:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expedientes`
--

DROP TABLE IF EXISTS `expedientes`;
CREATE TABLE IF NOT EXISTS `expedientes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acta_id` bigint UNSIGNED DEFAULT NULL,
  `estado` enum('abierto','en_tramite','concluido','archivado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'abierto',
  `derivado_a` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_apertura` date DEFAULT NULL,
  `fecha_cierre` date DEFAULT NULL,
  `observacion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expedientes_codigo_unique` (`codigo`),
  KEY `expedientes_acta_id_foreign` (`acta_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `expedientes`
--

INSERT INTO `expedientes` (`id`, `codigo`, `acta_id`, `estado`, `derivado_a`, `fecha_apertura`, `fecha_cierre`, `observacion`, `created_at`, `updated_at`) VALUES
(1, 'EXP-2025-0001', 3, 'concluido', 'Subgerencia de Transporte', '2025-10-27', '2025-11-10', 'Expediente concluido con sanción confirmada.', '2025-10-27 13:30:00', '2025-11-10 14:00:00'),
(2, 'EXP-2025-0002', 4, 'en_tramite', 'Gerencia de Fiscalización', '2025-10-28', NULL, 'En evaluación de descargos presentados por el administrado.', '2025-10-28 14:00:00', '2025-11-25 15:30:00'),
(3, 'EXP-2025-0003', 5, 'abierto', 'Subgerencia de Circulación Vial', '2025-10-29', NULL, 'Pendiente de notificación formal al administrado.', '2025-10-29 10:10:00', '2025-10-29 10:10:00'),
(4, 'EXP-2025-0004', 6, 'en_tramite', 'Gerencia de Asesoría Jurídica', '2025-10-16', NULL, 'Se recibió recurso de reconsideración.', '2025-10-16 14:00:00', '2025-11-20 15:00:00'),
(5, 'EXP-2025-0005', 7, 'concluido', 'Gerencia de Fiscalización', '2025-10-21', '2025-11-05', 'Se confirmó la sanción y se registró el pago de la multa.', '2025-10-21 15:00:00', '2025-11-05 21:00:00'),
(6, 'EXP-2025-0006', 10, 'abierto', 'Subgerencia de Autorizaciones', '2025-11-06', NULL, 'Pendiente de presentación de licencia de publicidad exterior.', '2025-11-06 14:30:00', '2025-11-06 14:30:00'),
(7, 'EXP-2025-0007', 12, 'en_tramite', 'Gerencia de Fiscalización', '2025-11-16', NULL, 'Se solicitó informe complementario al área de serenazgo.', '2025-11-16 15:15:00', '2025-12-01 16:00:00'),
(8, 'EXP-2025-0008', 13, 'concluido', 'Subgerencia de Obras Privadas', '2025-11-21', '2025-12-10', 'Se regularizó la licencia de obra y se archivó la sanción.', '2025-11-21 14:00:00', '2025-12-10 14:30:00'),
(9, 'EXP-2025-0009', 18, 'archivado', 'Gerencia de Fiscalización', '2025-12-11', '2025-12-20', 'Se determinó que no correspondía sanción por subsanación voluntaria.', '2025-12-11 14:15:00', '2025-12-20 15:00:00'),
(10, 'EXP-2025-0010', 19, 'archivado', 'Gerencia de Fiscalización', '2025-12-16', '2025-12-23', 'Acta anulada y expediente archivado por error material en la intervención.', '2025-12-16 14:30:00', '2025-12-23 13:45:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `infracciones`
--

DROP TABLE IF EXISTS `infracciones`;
CREATE TABLE IF NOT EXISTS `infracciones` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_legal` text COLLATE utf8mb4_unicode_ci,
  `multa` decimal(10,2) NOT NULL DEFAULT '0.00',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `infracciones_codigo_unique` (`codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `infracciones`
--

INSERT INTO `infracciones` (`id`, `codigo`, `descripcion`, `base_legal`, `multa`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'INF-001', 'Giro distinto al autorizado', 'Ord. XXXX', '920.00', 1, '2025-10-26 03:59:07', '2025-10-26 03:59:07'),
(4, 'INF-002', 'Pase luz roja', 'Multa', '550.00', 1, '2025-10-26 07:29:11', '2025-10-26 07:29:11'),
(5, 'No tiene licensia', 'Sin licencia', '0001', '200.00', 1, '2025-10-29 05:02:38', '2025-10-29 05:02:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_01_01_000001_create_administrados_table', 1),
(6, '2025_01_01_000002_create_actas_table', 1),
(7, '2025_01_01_000003_create_evidencias_table', 1),
(8, '2025_01_01_000004_create_infracciones_and_tipificaciones', 1),
(9, '2025_01_01_000005_create_boletas_table', 1),
(10, '2025_01_01_000006_create_expedientes_table', 1),
(11, '2025_01_01_000007_create_sequences_and_settings', 1),
(12, '2025_01_01_000008_create_catalogs_table', 1),
(13, '2025_10_26_063509_create_tipificaciones_table', 2),
(14, '2025_10_27_034156_alter_evidencias_add_thumb_and_meta', 3),
(15, '2025_10_27_042822_add_notified_at_to_boletas_table', 4),
(16, '2025_12_04_002636_create_roles_table', 5),
(17, '2025_12_04_002720_create_role_user_table', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', 'Full access to the system.', '2025-12-04 00:55:06', '2025-12-04 00:55:06'),
(2, 'Inspector', 'inspector', 'Can manage inspection records (actas).', '2025-12-04 00:55:06', '2025-12-04 00:55:06'),
(3, 'Chief', 'jefe', 'Can review and approve inspection records.', '2025-12-04 00:55:06', '2025-12-04 00:55:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_user`
--

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE IF NOT EXISTS `role_user` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_user_user_role_unique` (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_user`
--

INSERT INTO `role_user` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 8, 1, '2025-12-04 00:55:51', '2025-12-04 00:55:51'),
(2, 9, 2, '2025-12-04 01:01:12', '2025-12-04 01:01:12'),
(3, 10, 3, '2025-12-04 01:01:12', '2025-12-04 01:01:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sequences`
--

DROP TABLE IF EXISTS `sequences`;
CREATE TABLE IF NOT EXISTS `sequences` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` bigint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sequences_key_unique` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sequences`
--

INSERT INTO `sequences` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'actas', 19, '2025-10-26 03:59:07', '2025-12-04 02:04:31'),
(2, 'boleta_A001', 25, '2025-10-26 03:59:07', '2025-12-04 02:04:31'),
(3, 'expedientes', 10, '2025-10-26 03:59:07', '2025-12-04 02:04:31'),
(4, 'boleta_A002', 4, '2025-10-26 07:33:07', '2025-10-26 07:33:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'org.name', NULL, '2025-10-26 09:20:07', '2025-10-26 09:20:07'),
(2, 'ui.theme', 'jm', '2025-10-26 03:59:07', '2025-10-26 03:59:07'),
(3, 'app.logo', 'https://images.seeklogo.com/logo-png/21/1/jesus-maria-logo-png_seeklogo-211165.png', '2025-12-04 11:01:31', '2025-12-04 11:01:31'),
(4, 'app.primary', '#176936', '2025-12-04 11:01:31', '2025-12-04 11:01:31'),
(5, 'mail.from', '', '2025-12-04 11:01:31', '2025-12-04 11:01:31'),
(6, 'pdf.footer', 'Municipalidad de Jesús María — PAS', '2025-12-04 11:01:31', '2025-12-04 11:01:31'),
(7, 'app.name', 'Municipalidad de Jesus Maria', '2025-12-04 11:01:31', '2025-12-04 11:01:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipificaciones`
--

DROP TABLE IF EXISTS `tipificaciones`;
CREATE TABLE IF NOT EXISTS `tipificaciones` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `acta_id` bigint UNSIGNED NOT NULL,
  `infraccion_id` bigint UNSIGNED NOT NULL,
  `multa` decimal(10,2) DEFAULT NULL,
  `observacion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tipificaciones_acta_id_index` (`acta_id`),
  KEY `tipificaciones_infraccion_id_index` (`infraccion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipificaciones`
--

INSERT INTO `tipificaciones` (`id`, `acta_id`, `infraccion_id`, `multa`, `observacion`, `created_at`, `updated_at`) VALUES
(3, 4, 1, '450.00', 'test', '2025-10-27 09:12:54', '2025-10-27 09:12:54'),
(4, 5, 4, NULL, NULL, '2025-10-29 05:05:02', '2025-10-29 05:05:02'),
(5, 5, 1, '500.00', 'fatal error', '2025-10-29 05:05:41', '2025-10-29 05:05:41'),
(6, 5, 4, '100.00', 'paso la luz roja', '2025-11-22 05:36:34', '2025-11-22 05:36:34'),
(7, 6, 1, '920.00', 'Estacionamiento en zona rígida señalizada.', '2025-10-15 15:05:00', '2025-10-15 15:05:00'),
(8, 7, 1, '920.00', 'Reincidencia de comercio ambulatorio en la misma cuadra.', '2025-10-20 16:35:00', '2025-10-20 16:35:00'),
(9, 8, 4, '550.00', 'Ruido por parlantes durante horario nocturno.', '2025-11-01 14:05:00', '2025-11-01 14:05:00'),
(10, 9, 5, '200.00', 'Venta en vía pública sin autorización ni licencia.', '2025-11-04 00:35:00', '2025-11-04 00:35:00'),
(11, 10, 1, '920.00', 'Panel publicitario sin autorización en fachada.', '2025-11-05 21:50:00', '2025-11-05 21:50:00'),
(12, 12, 4, '550.00', 'Evento nocturno con música a alto volumen.', '2025-11-16 03:05:00', '2025-11-16 03:05:00'),
(13, 13, 1, '920.00', 'Construcción sin licencia ni planos aprobados.', '2025-11-20 14:35:00', '2025-11-20 14:35:00'),
(14, 14, 5, '200.00', 'Consumo de licor en espacio público.', '2025-11-26 00:05:00', '2025-11-26 00:05:00'),
(15, 16, 5, '200.00', 'Mercadería sobre la vereda obstaculizando el paso.', '2025-12-01 17:35:00', '2025-12-01 17:35:00'),
(16, 18, 4, '550.00', 'Uso de equipo de sonido en la vía.', '2025-12-10 23:05:00', '2025-12-10 23:05:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(8, 'System Admin', 'admin@pas-jm.test', NULL, '$2y$10$hF/hXk1UyYHKloxhfj943e5Bc5YR9pqmWKSuzx/q2EKGH4P8BLRcm', 'm9sMKZHxdnhrhbZQGJozZM4GAG0wyscxWHsMmhIRfMyCcFMt60EilIFm6XQb', '2025-12-04 00:55:51', '2025-12-04 00:55:51'),
(9, 'Inspector Usuario', 'inspector@pas-jm.test', NULL, '$2b$12$gh/lZLLxGJN5IxW/rvcGVezJyPQBOXluGGsCbXZhpDI7oAtnU0sBG', 'VobYgyTkBhaCRsl9swHIaanlOSmfJnMxMr5w7YOrvRb9fJi4N5NxUd6fcRKH', '2025-12-04 01:00:57', '2025-12-04 01:00:57'),
(10, 'Jefe Usuario', 'jefe@pas-jm.test', NULL, '$2b$12$gh/lZLLxGJN5IxW/rvcGVezJyPQBOXluGGsCbXZhpDI7oAtnU0sBG', 'JyzsDFmWxcsunuzlL2qwGh629sU0zevgSisH5HYM5ood0ZFEDCt136nXdrmc', '2025-12-04 01:00:57', '2025-12-04 01:00:57');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
