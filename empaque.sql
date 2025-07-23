-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-07-2025 a las 02:44:26
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
-- Base de datos: `empaque`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion`
--

CREATE TABLE `clasificacion` (
  `pk_clasificacion` int(11) NOT NULL,
  `fk_lote` int(11) NOT NULL,
  `primera_calidad` decimal(10,2) DEFAULT NULL,
  `segunda_calidad` decimal(10,2) DEFAULT NULL,
  `descarte` decimal(10,2) DEFAULT NULL,
  `uso` varchar(50) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_mango`
--

CREATE TABLE `compra_mango` (
  `pk_compra` int(11) NOT NULL,
  `fk_llegada` int(11) NOT NULL,
  `precio_kilo` decimal(10,2) DEFAULT NULL,
  `total_pagado` decimal(10,2) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dato_usuario`
--

CREATE TABLE `dato_usuario` (
  `pk_dato_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `edad` int(11) DEFAULT NULL,
  `sexo` enum('M','F') DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `dato_usuario`
--

INSERT INTO `dato_usuario` (`pk_dato_usuario`, `nombre`, `apellidos`, `edad`, `sexo`, `estado`, `fecha`, `hora`) VALUES
(1, 'Juan', 'Pérez López', 35, 'M', 1, '2025-07-02', '08:37:39'),
(2, 'Ana ', 'Martínez Gómez ', 28, 'F', 1, '2025-07-02', '08:38:38'),
(6, 'Prueba', 'prueba', 25, 'F', 1, '2025-07-07', '11:01:52'),
(7, 'Juan', 'Pérez García', 35, 'M', 1, '2025-07-07', '12:22:37'),
(8, 'María', 'Gómez Hernández', 28, 'F', 0, '2025-07-07', '12:22:37'),
(9, 'Pedro', 'Sánchez Ramírez', 42, 'M', 0, '2025-07-07', '12:22:37'),
(10, 'Ana', 'Jiménez Flores', 31, 'F', 1, '2025-07-07', '12:22:37'),
(11, 'Luis', 'Rodríguez Castillo', 27, 'M', 1, '2025-07-07', '12:22:37'),
(12, 'Carmen', 'Morales Gutiérrez', 39, 'F', 1, '2025-07-07', '12:22:37'),
(13, 'Jorge', 'Díaz Martínez', 24, 'M', 1, '2025-07-07', '12:22:37'),
(14, 'Sofía', 'Reyes Navarro', 33, 'F', 1, '2025-07-07', '12:22:37'),
(15, 'Miguel', 'Castillo Guzmán', 29, 'M', 1, '2025-07-07', '12:22:37'),
(16, 'Laura', 'Vargas Mendoza', 36, 'F', 1, '2025-07-07', '12:22:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_llegada`
--

CREATE TABLE `detalle_llegada` (
  `pk_detalle_llegada` int(11) NOT NULL,
  `fk_llegada` int(11) NOT NULL,
  `medio_transporte` varchar(100) DEFAULT NULL,
  `tipo_envase` varchar(100) DEFAULT NULL,
  `responsable` varchar(100) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentacion`
--

CREATE TABLE `documentacion` (
  `pk_documento` int(11) NOT NULL,
  `fk_lote` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento_salida`
--

CREATE TABLE `documento_salida` (
  `pk_documento_salida` int(11) NOT NULL,
  `fk_salida` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gasto`
--

CREATE TABLE `gasto` (
  `pk_gasto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL COMMENT 'Valores: llegada, salida',
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gasto_llegada`
--

CREATE TABLE `gasto_llegada` (
  `pk_gasto_llegada` int(11) NOT NULL,
  `fk_lote` int(11) NOT NULL,
  `fk_gasto` int(11) NOT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gasto_salida`
--

CREATE TABLE `gasto_salida` (
  `pk_gasto_salida` int(11) NOT NULL,
  `fk_salida` int(11) NOT NULL,
  `fk_gasto` int(11) NOT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `llegada_mango`
--

CREATE TABLE `llegada_mango` (
  `pk_llegada` int(11) NOT NULL,
  `fk_rancho` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `tipo_llegada` varchar(50) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote`
--

CREATE TABLE `lote` (
  `pk_lote` int(11) NOT NULL,
  `fk_llegada` int(11) NOT NULL,
  `numero_lote` varchar(50) NOT NULL,
  `variedad` varchar(50) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pesaje`
--

CREATE TABLE `pesaje` (
  `pk_pesaje` int(11) NOT NULL,
  `fk_llegada` int(11) NOT NULL,
  `peso_bruto` decimal(10,2) DEFAULT NULL,
  `peso_envase` decimal(10,2) DEFAULT NULL,
  `peso_neto` decimal(10,2) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productor`
--

CREATE TABLE `productor` (
  `pk_productor` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productor`
--

INSERT INTO `productor` (`pk_productor`, `nombre`, `telefono`, `estado`, `fecha`, `hora`) VALUES
(1, 'AgroMango S.A.', '555-1234', 1, '2025-07-07', '11:42:33'),
(2, 'EmpaqueMex', '555-1117', 1, '2025-07-07', '11:55:01'),
(3, 'Agromango S.A.', '555-1234', 1, '2025-07-07', '12:22:37'),
(4, 'Frutales del Sur', '555-5678', 1, '2025-07-07', '12:22:37'),
(5, 'Hacienda La Esperanza', '555-9012', 1, '2025-07-07', '12:22:37'),
(6, 'Agrícola El Rincón', '555-3456', 1, '2025-07-07', '12:22:37'),
(7, 'Frutas y Más', '555-7890', 1, '2025-07-07', '12:22:37'),
(8, 'Rancho San Isidro', '555-2345', 1, '2025-07-07', '12:22:37'),
(9, 'Productores Orgánicos', '555-6789', 1, '2025-07-07', '12:22:37'),
(10, 'Finca El Paraíso', '555-0123', 1, '2025-07-07', '12:22:37'),
(11, 'Agrícola Los Mangos', '555-4567', 1, '2025-07-07', '12:22:37'),
(12, 'Frutas del Trópico', '555-8901', 1, '2025-07-07', '12:22:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rancho`
--

CREATE TABLE `rancho` (
  `pk_rancho` int(11) NOT NULL,
  `fk_productor` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ubicacion` text DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_rendimiento`
--

CREATE TABLE `reporte_rendimiento` (
  `pk_reporte` int(11) NOT NULL,
  `fk_lote` int(11) NOT NULL,
  `kg_entrada` decimal(10,2) DEFAULT NULL,
  `kg_util` decimal(10,2) DEFAULT NULL,
  `kg_rechazo` decimal(10,2) DEFAULT NULL,
  `total_gastos` decimal(10,2) DEFAULT NULL,
  `total_ingresos` decimal(10,2) DEFAULT NULL,
  `ganancia` decimal(10,2) DEFAULT NULL,
  `perdida` decimal(10,2) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `revision_calidad`
--

CREATE TABLE `revision_calidad` (
  `pk_revision` int(11) NOT NULL,
  `fk_llegada` int(11) NOT NULL,
  `madurez` varchar(50) DEFAULT NULL COMMENT 'Valores definidos en formulario: Verde, Maduro, Muy Maduro',
  `plagas` tinyint(1) DEFAULT NULL,
  `daños` tinyint(1) DEFAULT NULL,
  `contaminantes` tinyint(1) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `pk_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`pk_rol`, `nombre`, `descripcion`, `estado`, `fecha`, `hora`) VALUES
(1, 'administrador', 'administrador del sistema', 1, '2025-07-02', '08:35:10'),
(2, 'empleado', 'Acceso limitado solo a ciertos módulos ', 1, '2025-07-02', '08:36:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ruta`
--

CREATE TABLE `ruta` (
  `pk_ruta` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `ruta` varchar(150) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ruta`
--

INSERT INTO `ruta` (`pk_ruta`, `nombre`, `ruta`, `estado`, `fecha`, `hora`) VALUES
(3, 'principal', 'vistas/modulos/principal.php', 1, '2025-07-02', '09:02:47'),
(5, 'login', 'vistas/modulos/login.php', 1, '2025-07-02', '09:05:47'),
(7, 'mostrar_dato_usuario', 'vistas/modulos/mostrar/mostrar_dato_usuario.php', 1, '2025-07-07', '00:31:41'),
(8, 'editar_dato_usuario', 'vistas/modulos/editar/editar_dato_usuario.php', 1, '2025-07-07', '01:48:12'),
(9, 'desactivar', 'vistas/modulos/desactivar/desactivar.php', 1, '2025-07-07', '02:33:54'),
(10, 'activar', 'vistas/modulos/activar/activar.php', 1, '2025-07-07', '02:34:10'),
(11, 'mostrar_productor', 'vistas/modulos/mostrar/mostrar_productor.php', 1, '2025-07-07', '11:32:40'),
(12, 'editar_productor', 'vistas/modulos/editar/editar_productor.php', 1, '2025-07-07', '12:05:56'),
(13, 'pdf', 'vistas/modulos/mostrar/pdf/pdf.php', 1, '2025-07-21', '22:09:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida_mango`
--

CREATE TABLE `salida_mango` (
  `pk_salida` int(11) NOT NULL,
  `fk_lote` int(11) NOT NULL,
  `tipo_salida` varchar(50) DEFAULT NULL,
  `cliente` varchar(100) DEFAULT NULL,
  `destino` text DEFAULT NULL,
  `transporte` varchar(100) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `pk_usuario` int(11) NOT NULL,
  `fk_dato_usuario` int(11) NOT NULL,
  `fk_rol` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`pk_usuario`, `fk_dato_usuario`, `fk_rol`, `usuario`, `contrasena`, `estado`, `fecha`, `hora`) VALUES
(1, 1, 1, 'admin ', '$2y$10$BvQefFSNKY.Vp2Ngq..Xcei7em8sG3d62Ola.IFbJ/gDuOmPzsWku', 1, '2025-07-02', '08:40:59'),
(2, 2, 2, 'empleado', '$2y$10$BvQefFSNKY.Vp2Ngq..Xcei7em8sG3d62Ola.IFbJ/gDuOmPzsWku', 1, '2025-07-02', '08:40:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `pk_venta` int(11) NOT NULL,
  `fk_salida` int(11) NOT NULL,
  `ingreso_total` decimal(10,2) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha` date DEFAULT curdate(),
  `hora` time DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
  ADD PRIMARY KEY (`pk_clasificacion`),
  ADD KEY `fk_lote` (`fk_lote`);

--
-- Indices de la tabla `compra_mango`
--
ALTER TABLE `compra_mango`
  ADD PRIMARY KEY (`pk_compra`),
  ADD KEY `fk_llegada` (`fk_llegada`);

--
-- Indices de la tabla `dato_usuario`
--
ALTER TABLE `dato_usuario`
  ADD PRIMARY KEY (`pk_dato_usuario`);

--
-- Indices de la tabla `detalle_llegada`
--
ALTER TABLE `detalle_llegada`
  ADD PRIMARY KEY (`pk_detalle_llegada`),
  ADD KEY `fk_llegada` (`fk_llegada`);

--
-- Indices de la tabla `documentacion`
--
ALTER TABLE `documentacion`
  ADD PRIMARY KEY (`pk_documento`),
  ADD KEY `fk_lote` (`fk_lote`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Indices de la tabla `documento_salida`
--
ALTER TABLE `documento_salida`
  ADD PRIMARY KEY (`pk_documento_salida`),
  ADD KEY `fk_salida` (`fk_salida`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Indices de la tabla `gasto`
--
ALTER TABLE `gasto`
  ADD PRIMARY KEY (`pk_gasto`);

--
-- Indices de la tabla `gasto_llegada`
--
ALTER TABLE `gasto_llegada`
  ADD PRIMARY KEY (`pk_gasto_llegada`),
  ADD KEY `fk_lote` (`fk_lote`),
  ADD KEY `fk_gasto` (`fk_gasto`);

--
-- Indices de la tabla `gasto_salida`
--
ALTER TABLE `gasto_salida`
  ADD PRIMARY KEY (`pk_gasto_salida`),
  ADD KEY `fk_salida` (`fk_salida`),
  ADD KEY `fk_gasto` (`fk_gasto`);

--
-- Indices de la tabla `llegada_mango`
--
ALTER TABLE `llegada_mango`
  ADD PRIMARY KEY (`pk_llegada`),
  ADD KEY `fk_rancho` (`fk_rancho`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Indices de la tabla `lote`
--
ALTER TABLE `lote`
  ADD PRIMARY KEY (`pk_lote`),
  ADD UNIQUE KEY `numero_lote` (`numero_lote`),
  ADD KEY `fk_llegada` (`fk_llegada`);

--
-- Indices de la tabla `pesaje`
--
ALTER TABLE `pesaje`
  ADD PRIMARY KEY (`pk_pesaje`),
  ADD KEY `fk_llegada` (`fk_llegada`);

--
-- Indices de la tabla `productor`
--
ALTER TABLE `productor`
  ADD PRIMARY KEY (`pk_productor`);

--
-- Indices de la tabla `rancho`
--
ALTER TABLE `rancho`
  ADD PRIMARY KEY (`pk_rancho`),
  ADD KEY `fk_productor` (`fk_productor`);

--
-- Indices de la tabla `reporte_rendimiento`
--
ALTER TABLE `reporte_rendimiento`
  ADD PRIMARY KEY (`pk_reporte`),
  ADD KEY `fk_lote` (`fk_lote`);

--
-- Indices de la tabla `revision_calidad`
--
ALTER TABLE `revision_calidad`
  ADD PRIMARY KEY (`pk_revision`),
  ADD KEY `fk_llegada` (`fk_llegada`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`pk_rol`);

--
-- Indices de la tabla `ruta`
--
ALTER TABLE `ruta`
  ADD PRIMARY KEY (`pk_ruta`);

--
-- Indices de la tabla `salida_mango`
--
ALTER TABLE `salida_mango`
  ADD PRIMARY KEY (`pk_salida`),
  ADD KEY `fk_lote` (`fk_lote`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`pk_usuario`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `fk_dato_usuario` (`fk_dato_usuario`),
  ADD KEY `fk_rol` (`fk_rol`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`pk_venta`),
  ADD KEY `fk_salida` (`fk_salida`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
  MODIFY `pk_clasificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compra_mango`
--
ALTER TABLE `compra_mango`
  MODIFY `pk_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `dato_usuario`
--
ALTER TABLE `dato_usuario`
  MODIFY `pk_dato_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `detalle_llegada`
--
ALTER TABLE `detalle_llegada`
  MODIFY `pk_detalle_llegada` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentacion`
--
ALTER TABLE `documentacion`
  MODIFY `pk_documento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documento_salida`
--
ALTER TABLE `documento_salida`
  MODIFY `pk_documento_salida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gasto`
--
ALTER TABLE `gasto`
  MODIFY `pk_gasto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gasto_llegada`
--
ALTER TABLE `gasto_llegada`
  MODIFY `pk_gasto_llegada` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gasto_salida`
--
ALTER TABLE `gasto_salida`
  MODIFY `pk_gasto_salida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `llegada_mango`
--
ALTER TABLE `llegada_mango`
  MODIFY `pk_llegada` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lote`
--
ALTER TABLE `lote`
  MODIFY `pk_lote` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pesaje`
--
ALTER TABLE `pesaje`
  MODIFY `pk_pesaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productor`
--
ALTER TABLE `productor`
  MODIFY `pk_productor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `rancho`
--
ALTER TABLE `rancho`
  MODIFY `pk_rancho` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reporte_rendimiento`
--
ALTER TABLE `reporte_rendimiento`
  MODIFY `pk_reporte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `revision_calidad`
--
ALTER TABLE `revision_calidad`
  MODIFY `pk_revision` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `pk_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ruta`
--
ALTER TABLE `ruta`
  MODIFY `pk_ruta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `salida_mango`
--
ALTER TABLE `salida_mango`
  MODIFY `pk_salida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `pk_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `pk_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
  ADD CONSTRAINT `clasificacion_ibfk_1` FOREIGN KEY (`fk_lote`) REFERENCES `lote` (`pk_lote`);

--
-- Filtros para la tabla `compra_mango`
--
ALTER TABLE `compra_mango`
  ADD CONSTRAINT `compra_mango_ibfk_1` FOREIGN KEY (`fk_llegada`) REFERENCES `llegada_mango` (`pk_llegada`);

--
-- Filtros para la tabla `detalle_llegada`
--
ALTER TABLE `detalle_llegada`
  ADD CONSTRAINT `detalle_llegada_ibfk_1` FOREIGN KEY (`fk_llegada`) REFERENCES `llegada_mango` (`pk_llegada`);

--
-- Filtros para la tabla `documentacion`
--
ALTER TABLE `documentacion`
  ADD CONSTRAINT `documentacion_ibfk_1` FOREIGN KEY (`fk_lote`) REFERENCES `lote` (`pk_lote`),
  ADD CONSTRAINT `documentacion_ibfk_2` FOREIGN KEY (`fk_usuario`) REFERENCES `usuario` (`pk_usuario`);

--
-- Filtros para la tabla `documento_salida`
--
ALTER TABLE `documento_salida`
  ADD CONSTRAINT `documento_salida_ibfk_1` FOREIGN KEY (`fk_salida`) REFERENCES `salida_mango` (`pk_salida`),
  ADD CONSTRAINT `documento_salida_ibfk_2` FOREIGN KEY (`fk_usuario`) REFERENCES `usuario` (`pk_usuario`);

--
-- Filtros para la tabla `gasto_llegada`
--
ALTER TABLE `gasto_llegada`
  ADD CONSTRAINT `gasto_llegada_ibfk_1` FOREIGN KEY (`fk_lote`) REFERENCES `lote` (`pk_lote`),
  ADD CONSTRAINT `gasto_llegada_ibfk_2` FOREIGN KEY (`fk_gasto`) REFERENCES `gasto` (`pk_gasto`);

--
-- Filtros para la tabla `gasto_salida`
--
ALTER TABLE `gasto_salida`
  ADD CONSTRAINT `gasto_salida_ibfk_1` FOREIGN KEY (`fk_salida`) REFERENCES `salida_mango` (`pk_salida`),
  ADD CONSTRAINT `gasto_salida_ibfk_2` FOREIGN KEY (`fk_gasto`) REFERENCES `gasto` (`pk_gasto`);

--
-- Filtros para la tabla `llegada_mango`
--
ALTER TABLE `llegada_mango`
  ADD CONSTRAINT `llegada_mango_ibfk_1` FOREIGN KEY (`fk_rancho`) REFERENCES `rancho` (`pk_rancho`),
  ADD CONSTRAINT `llegada_mango_ibfk_2` FOREIGN KEY (`fk_usuario`) REFERENCES `usuario` (`pk_usuario`);

--
-- Filtros para la tabla `lote`
--
ALTER TABLE `lote`
  ADD CONSTRAINT `lote_ibfk_1` FOREIGN KEY (`fk_llegada`) REFERENCES `llegada_mango` (`pk_llegada`);

--
-- Filtros para la tabla `pesaje`
--
ALTER TABLE `pesaje`
  ADD CONSTRAINT `pesaje_ibfk_1` FOREIGN KEY (`fk_llegada`) REFERENCES `llegada_mango` (`pk_llegada`);

--
-- Filtros para la tabla `rancho`
--
ALTER TABLE `rancho`
  ADD CONSTRAINT `rancho_ibfk_1` FOREIGN KEY (`fk_productor`) REFERENCES `productor` (`pk_productor`);

--
-- Filtros para la tabla `reporte_rendimiento`
--
ALTER TABLE `reporte_rendimiento`
  ADD CONSTRAINT `reporte_rendimiento_ibfk_1` FOREIGN KEY (`fk_lote`) REFERENCES `lote` (`pk_lote`);

--
-- Filtros para la tabla `revision_calidad`
--
ALTER TABLE `revision_calidad`
  ADD CONSTRAINT `revision_calidad_ibfk_1` FOREIGN KEY (`fk_llegada`) REFERENCES `llegada_mango` (`pk_llegada`);

--
-- Filtros para la tabla `salida_mango`
--
ALTER TABLE `salida_mango`
  ADD CONSTRAINT `salida_mango_ibfk_1` FOREIGN KEY (`fk_lote`) REFERENCES `lote` (`pk_lote`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`fk_dato_usuario`) REFERENCES `dato_usuario` (`pk_dato_usuario`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`fk_rol`) REFERENCES `rol` (`pk_rol`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`fk_salida`) REFERENCES `salida_mango` (`pk_salida`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
