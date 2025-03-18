-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 20-04-2024 a las 18:42:33
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `msa`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `msa_surtidos` (IN `f` DATETIME, IN `cats` INT, IN `cost` DECIMAL(4,2), IN `p` INT, IN `prove` INT, OUT `estado` TINYINT)   BEGIN
   SET @actualP:= (SELECT Cantidad_disponible FROM msa_productos WHERE ID=p);
    IF @actualP >=0 THEN
        INSERT INTO msa_surtidos VALUES (f, cats, cost, p, prove);
UPDATE msa_productos SET Cantidad_disponible = @actualP + cats WHERE ID = p;
SET estado = 1;
ELSE
        SET estado = 0;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `msa_ventas` (IN `p` INT, IN `t` INT, IN `cant` INT, IN `f` DATETIME, OUT `estado` TINYINT)   BEGIN
SET @existencia:= (SELECT Cantidad_disponible FROM msa_productos WHERE ID = p);
IF cant <= @existencia THEN
	INSERT INTO msa_ventas_productos (Fecha, msa_pdo_id, msa_tbr_id, Cantidad)
    VALUES (f,p,t,cant);
UPDATE msa_productos SET Cantidad_disponible = Cantidad_disponible - cant WHERE ID = p;
SET estado = 1;
ELSE
    SET estado = 0;
END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `msa_historial_precios`
--

CREATE TABLE `msa_historial_precios` (
  `Precio_publico` decimal(4,2) NOT NULL,
  `FECHA_INICIO` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FECHA_FIN` date DEFAULT NULL,
  `MSA_PDO_ID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `msa_historial_precios`
--

INSERT INTO `msa_historial_precios` (`Precio_publico`, `FECHA_INICIO`, `FECHA_FIN`, `MSA_PDO_ID`) VALUES
(18.50, '2024-04-05 00:14:44', NULL, 1),
(16.00, '2024-04-05 00:15:21', '2023-08-01', 1),
(12.00, '2024-04-05 00:16:14', NULL, 2),
(10.50, '2024-04-05 00:16:20', '2023-06-15', 2),
(32.00, '2024-04-05 00:16:54', NULL, 3),
(28.00, '2024-04-05 00:16:59', '2023-03-01', 3),
(24.00, '2024-04-05 00:17:24', NULL, 4),
(22.00, '2024-04-05 00:17:32', '2023-09-10', 4),
(14.50, '2024-04-05 00:18:10', NULL, 5),
(12.00, '2024-04-05 00:18:15', '2023-07-20', 5),
(15.00, '2024-04-05 00:18:55', NULL, 6),
(12.50, '2024-04-05 00:19:00', '2023-01-01', 6),
(25.00, '2024-04-05 00:19:26', NULL, 7),
(22.00, '2024-04-05 00:19:30', '2023-05-15', 7),
(12.00, '2024-04-05 00:19:59', NULL, 8),
(10.00, '2024-04-05 00:20:04', '2023-03-01', 8),
(28.00, '2024-04-05 00:20:41', NULL, 9),
(24.50, '2024-04-05 00:20:46', '2023-07-01', 9),
(18.00, '2024-04-05 00:21:09', NULL, 10),
(16.50, '2024-04-05 00:21:19', '2023-09-15', 10),
(15.00, '2024-04-20 18:41:05', NULL, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `msa_productos`
--

CREATE TABLE `msa_productos` (
  `ID` int NOT NULL,
  `Nombre_producto` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Tipo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Cantidad_disponible` int NOT NULL DEFAULT '0',
  `Presentacion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Contenido` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Marca` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Fecha_caducidad` datetime DEFAULT CURRENT_TIMESTAMP,
  `Imagen` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `msa_productos`
--

INSERT INTO `msa_productos` (`ID`, `Nombre_producto`, `Tipo`, `Cantidad_disponible`, `Presentacion`, `Contenido`, `Marca`, `Fecha_caducidad`, `Imagen`) VALUES
(1, 'Coca-Cola', 'Refresco', 146, 'Botella', '600 ml', 'Coca-Cola', '2024-10-04 02:08:57', 'assets/productos/cocacola.png'),
(2, 'Sabritas', 'Botana', 102, 'Bolsa', '170 g', 'Sabritas', '2024-07-04 02:08:57', 'assets/productos/sabritas.png'),
(3, 'Bimbo Blanco', 'Pan', 80, 'Paquete', '680 g', 'Bimbo', '2024-04-11 02:08:57', 'assets/productos/binboblanco.png'),
(4, 'Leche Alpura', 'Lácteo', 156, 'Cartón', '1 L', 'Alpura', '2024-04-14 02:08:57', 'assets/productos/lechealpura.png'),
(5, 'Galletas Marías', 'Galleta', 109, 'Paquete', '200 g', 'Gamesa', '2024-06-04 02:08:57', 'assets/productos/galletasmarias.png'),
(6, 'Jugo Del Valle', 'Jugo', 175, 'Cartón', '1 L', 'Del Valle', '2024-05-04 02:08:57', 'assets/productos/jugovalle.png'),
(7, 'Papel higiénico Petalo', 'Higiene', 95, 'Paquete', '4 rollos', 'Petalo', NULL, 'assets/productos/papelpetalo.png'),
(8, 'Jabón Zote', 'Higiene', 85, 'Barra', '400 g', 'Zote', NULL, 'assets/productos/zote.png'),
(9, 'Aceite 1-2-3', 'Aceite', 120, 'Botella', '1 L', '1-2-3', '2025-01-04 02:08:57', 'assets/productos/aceite.png'),
(10, 'Arroz Maravilla', 'Grano', 140, 'Bolsa', '1 kg', 'Maravilla', '2025-04-04 02:08:57', 'assets/productos/arroz.png'),
(12, 'Pepsi', 'Refresco', 25, 'Lata', '237ml', 'Pepsi', '2024-06-20 00:00:00', 'assets/productos/pepsi.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `msa_proveedores`
--

CREATE TABLE `msa_proveedores` (
  `ID` int NOT NULL,
  `Nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Telefono` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Lapso_surte` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `msa_proveedores`
--

INSERT INTO `msa_proveedores` (`ID`, `Nombre`, `Telefono`, `Lapso_surte`) VALUES
(1, 'Distribuidora ABC', '5512345678', 'Semanal'),
(2, 'Mayorista XYZ', '5587654321', 'Quincenal'),
(3, 'Proveedores Unidos', '5598765432', 'Mensual'),
(4, 'Suministros DEF', '5512349876', 'Semanal'),
(5, 'Abarrotes GHI', '5587659012', 'Quincenal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `msa_roles`
--

CREATE TABLE `msa_roles` (
  `ID` int NOT NULL,
  `Rol` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `msa_roles`
--

INSERT INTO `msa_roles` (`ID`, `Rol`) VALUES
(1, 'ADMIN'),
(2, 'WORKER');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `msa_servicios`
--

CREATE TABLE `msa_servicios` (
  `ID` int NOT NULL,
  `Compania` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Precio` decimal(6,2) NOT NULL,
  `Imagen` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `msa_servicios`
--

INSERT INTO `msa_servicios` (`ID`, `Compania`, `Precio`, `Imagen`) VALUES
(1, 'AT&T', 100.00, 'assets/servicios/at&t.png'),
(2, 'Telcel', 150.00, 'assets/servicios/telcel.png'),
(3, 'Movistar', 50.00, 'assets/servicios/movistar.png'),
(4, 'Unefon', 10.00, 'assets/servicios/unefon.png'),
(5, 'Telcel', 200.00, 'assets/servicios/telcel.png'),
(6, 'Telcel', 30.00, 'assets/servicios/telcel.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `msa_surtidos`
--

CREATE TABLE `msa_surtidos` (
  `Fecha` datetime NOT NULL,
  `Cantidad_surtida` int NOT NULL,
  `Costo_unitario` decimal(4,2) NOT NULL,
  `MSA_PDO_ID` int NOT NULL,
  `MSA_PVR_ID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `msa_surtidos`
--

INSERT INTO `msa_surtidos` (`Fecha`, `Cantidad_surtida`, `Costo_unitario`, `MSA_PDO_ID`, `MSA_PVR_ID`) VALUES
('2024-04-04 02:13:05', 100, 5.50, 1, 1),
('2024-04-04 02:13:05', 75, 8.75, 2, 2),
('2024-04-04 02:13:05', 50, 12.20, 3, 3),
('2024-04-04 02:13:05', 120, 6.80, 4, 4),
('2024-04-04 02:13:05', 90, 9.95, 5, 5),
('2024-04-04 02:13:05', 150, 4.30, 6, 1),
('2024-04-04 02:13:05', 80, 7.65, 7, 2),
('2024-04-04 02:13:05', 65, 10.40, 8, 3),
('2024-04-04 02:13:05', 110, 6.20, 9, 4),
('2024-04-04 02:13:05', 95, 8.85, 10, 5),
('2024-04-19 17:58:22', 2, 12.20, 3, 2),
('2024-04-19 18:30:48', 2, 12.20, 3, 3),
('2024-04-19 18:46:21', 8, 12.20, 3, 3),
('2024-04-20 08:12:58', 0, 0.00, 1, 1),
('2024-04-20 08:13:32', 0, 0.00, 1, 1),
('2024-04-20 08:16:45', 1, 10.00, 1, 1),
('2024-04-20 08:17:08', 0, 0.00, 1, 1),
('2024-04-20 18:41:36', 25, 8.00, 12, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `msa_trabajadores`
--

CREATE TABLE `msa_trabajadores` (
  `ID` int NOT NULL,
  `Nombre` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Apellido` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Telefono` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Estado` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Ciudad` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `CalleyNum` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `MSA_TBR_ID` int DEFAULT NULL,
  `Password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Foto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `id_rol` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `msa_trabajadores`
--

INSERT INTO `msa_trabajadores` (`ID`, `Nombre`, `Apellido`, `Telefono`, `Estado`, `Ciudad`, `CalleyNum`, `MSA_TBR_ID`, `Password`, `Foto`, `id_rol`) VALUES
(1, 'Juan', 'Pérez', '5512345678', 'Ciudad de México', 'Ciudad de México', 'Av. Insurgentes #123', NULL, 'f1b4dea0aceeb5b732d62bad9545cd35', 'assets/trabajadores/juan.jpg', 2),
(2, 'María', 'García', '5587654321', 'Jalisco', 'Guadalajara', 'Calle Morelos #456', NULL, 'c9050e7078a260e808a8991e5cc1b3f0', 'assets/trabajadores/maria.jpg', 1),
(3, 'Carlos', 'Rodríguez', '5598765432', 'Nuevo León', 'Monterrey', 'Av. Constitución #789', NULL, '90fe2049445178a1840bd71dc6c07ce8', 'assets/trabajadores/carlos.jpg', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `msa_ventas_productos`
--

CREATE TABLE `msa_ventas_productos` (
  `Fecha` datetime NOT NULL,
  `MSA_PDO_ID` int NOT NULL,
  `MSA_TBR_ID` int NOT NULL,
  `Cantidad` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `msa_ventas_productos`
--

INSERT INTO `msa_ventas_productos` (`Fecha`, `MSA_PDO_ID`, `MSA_TBR_ID`, `Cantidad`) VALUES
('2024-04-04 02:13:27', 1, 1, 5),
('2024-04-04 02:13:27', 2, 2, 3),
('2024-04-04 02:13:27', 3, 3, 2),
('2024-04-04 02:13:27', 4, 1, 4),
('2024-04-04 02:13:27', 5, 2, 6),
('2024-04-18 00:00:00', 6, 1, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `msa_ventas_servicios`
--

CREATE TABLE `msa_ventas_servicios` (
  `Fecha` datetime NOT NULL,
  `MSA_TBR_ID` int NOT NULL,
  `MSA_SVO_ID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `msa_ventas_servicios`
--

INSERT INTO `msa_ventas_servicios` (`Fecha`, `MSA_TBR_ID`, `MSA_SVO_ID`) VALUES
('2024-04-04 02:14:00', 1, 1),
('2024-04-04 02:14:00', 2, 1),
('2024-04-04 02:14:00', 2, 2),
('2024-04-04 02:14:00', 1, 3),
('2024-04-04 02:14:00', 3, 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `msa_historial_precios`
--
ALTER TABLE `msa_historial_precios`
  ADD PRIMARY KEY (`MSA_PDO_ID`,`FECHA_INICIO`);

--
-- Indices de la tabla `msa_productos`
--
ALTER TABLE `msa_productos`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `msa_proveedores`
--
ALTER TABLE `msa_proveedores`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `msa_roles`
--
ALTER TABLE `msa_roles`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `msa_servicios`
--
ALTER TABLE `msa_servicios`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `msa_surtidos`
--
ALTER TABLE `msa_surtidos`
  ADD PRIMARY KEY (`Fecha`,`MSA_PDO_ID`,`MSA_PVR_ID`),
  ADD KEY `MSA_STO_MSA_PDO_FK` (`MSA_PDO_ID`),
  ADD KEY `MSA_STO_MSA_PVR_FK` (`MSA_PVR_ID`);

--
-- Indices de la tabla `msa_trabajadores`
--
ALTER TABLE `msa_trabajadores`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Password` (`Password`),
  ADD KEY `MSA_TBR_MSA_TBR_FK` (`MSA_TBR_ID`),
  ADD KEY `fk_trabajadores_roles` (`id_rol`);

--
-- Indices de la tabla `msa_ventas_productos`
--
ALTER TABLE `msa_ventas_productos`
  ADD PRIMARY KEY (`Fecha`,`MSA_PDO_ID`,`MSA_TBR_ID`),
  ADD KEY `MSA_VPO_MSA_PDO_FK` (`MSA_PDO_ID`),
  ADD KEY `MSA_VPO_MSA_TBR_FK` (`MSA_TBR_ID`);

--
-- Indices de la tabla `msa_ventas_servicios`
--
ALTER TABLE `msa_ventas_servicios`
  ADD PRIMARY KEY (`Fecha`,`MSA_TBR_ID`,`MSA_SVO_ID`),
  ADD KEY `MSA_VSO_MSA_SVO_FK` (`MSA_SVO_ID`),
  ADD KEY `MSA_VSO_MSA_TBR_FK` (`MSA_TBR_ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `msa_productos`
--
ALTER TABLE `msa_productos`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `msa_proveedores`
--
ALTER TABLE `msa_proveedores`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `msa_roles`
--
ALTER TABLE `msa_roles`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `msa_servicios`
--
ALTER TABLE `msa_servicios`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `msa_trabajadores`
--
ALTER TABLE `msa_trabajadores`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `msa_historial_precios`
--
ALTER TABLE `msa_historial_precios`
  ADD CONSTRAINT `MSA_HPO_MSA_PDO_FK` FOREIGN KEY (`MSA_PDO_ID`) REFERENCES `msa_productos` (`ID`);

--
-- Filtros para la tabla `msa_surtidos`
--
ALTER TABLE `msa_surtidos`
  ADD CONSTRAINT `MSA_STO_MSA_PDO_FK` FOREIGN KEY (`MSA_PDO_ID`) REFERENCES `msa_productos` (`ID`),
  ADD CONSTRAINT `MSA_STO_MSA_PVR_FK` FOREIGN KEY (`MSA_PVR_ID`) REFERENCES `msa_proveedores` (`ID`);

--
-- Filtros para la tabla `msa_trabajadores`
--
ALTER TABLE `msa_trabajadores`
  ADD CONSTRAINT `fk_trabajadores_roles` FOREIGN KEY (`id_rol`) REFERENCES `msa_roles` (`ID`),
  ADD CONSTRAINT `MSA_TBR_MSA_TBR_FK` FOREIGN KEY (`MSA_TBR_ID`) REFERENCES `msa_trabajadores` (`ID`);

--
-- Filtros para la tabla `msa_ventas_productos`
--
ALTER TABLE `msa_ventas_productos`
  ADD CONSTRAINT `MSA_VPO_MSA_PDO_FK` FOREIGN KEY (`MSA_PDO_ID`) REFERENCES `msa_productos` (`ID`),
  ADD CONSTRAINT `MSA_VPO_MSA_TBR_FK` FOREIGN KEY (`MSA_TBR_ID`) REFERENCES `msa_trabajadores` (`ID`);

--
-- Filtros para la tabla `msa_ventas_servicios`
--
ALTER TABLE `msa_ventas_servicios`
  ADD CONSTRAINT `MSA_VSO_MSA_SVO_FK` FOREIGN KEY (`MSA_SVO_ID`) REFERENCES `msa_servicios` (`ID`),
  ADD CONSTRAINT `MSA_VSO_MSA_TBR_FK` FOREIGN KEY (`MSA_TBR_ID`) REFERENCES `msa_trabajadores` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
