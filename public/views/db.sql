CREATE TABLE `seg_usuarios` (
    `IdUsuario` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `UserName` varchar(30) COLLATE latin1_spanish_ci DEFAULT NULL,
    `Nombre` varchar(30) COLLATE latin1_spanish_ci DEFAULT NULL,
    `ApellidoPaterno` varchar(30) COLLATE latin1_spanish_ci DEFAULT NULL,
    `ApellidoMaterno` varchar(30) COLLATE latin1_spanish_ci DEFAULT NULL,
    `passwd` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
    `UsuarioPerfilId_fk` int(11) DEFAULT NULL,
    `DepartamentoId` int(11) DEFAULT NULL,
    `EsActivo` tinyint(4) DEFAULT NULL,
    `EMail` varchar(250) COLLATE latin1_spanish_ci DEFAULT NULL,
    `CapturadoPor` int(10) DEFAULT NULL,
    `FechaCaptura` int(10) unsigned DEFAULT NULL,
    `HoraCaptura` time DEFAULT NULL,
    `IdAppInicio` int(11) DEFAULT NULL,
    `TipoSistema` tinyint(1) DEFAULT '1' COMMENT '1 Sistemas(normal), 2 Operaciones(nuevo)',
    PRIMARY KEY (`IdUsuario`),
    UNIQUE KEY `UK_USRNAME` (`UserName`)
) ENGINE=MyISAM AUTO_INCREMENT=435 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci


/* seg_usuarios_clientes */
CREATE TABLE `seg_usuarios_estaciones` (
  `IdUsuario` int(10) unsigned NOT NULL,
  `IdEstacion_fk` int(11) unsigned NOT NULL,
  `IdSucursal` int(11) DEFAULT NULL,
  `EsUnico` tinyint(4) DEFAULT NULL COMMENT 'Indica que el cliente / sucursal es el unico que puede ver ese usuario',
  PRIMARY KEY (`IdUsuario`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci


/* srv_articulos */
CREATE TABLE `productos` (
  `IdArticulo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Clave` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CodigoBarras` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `IdGrupo` int(11) DEFAULT NULL,
  `IdSubGrupo` int(11) DEFAULT NULL,
  `IdTipo` int(11) DEFAULT NULL COMMENT '1 Servicios, 2 Productos',
  `IdAlmacen` int(11) DEFAULT NULL,
  `Descripcion` varchar(250) COLLATE latin1_spanish_ci DEFAULT NULL,
  `IdProveedor1_fk` int(11) DEFAULT NULL,
  `IdProveedor2_fk` int(11) DEFAULT NULL,
  `foto` varchar(250) COLLATE latin1_spanish_ci DEFAULT NULL,
  `EsActivo` tinyint(3) unsigned DEFAULT NULL,
  `EsNumerosSerie` tinyint(3) unsigned DEFAULT NULL,
  `Existencia` decimal(16,2) DEFAULT NULL,
  `Costo` decimal(16,2) DEFAULT NULL,
  `CostoPromedio` decimal(16,2) DEFAULT NULL,
  `Precio1` decimal(16,2) DEFAULT NULL,
  `Precio2` decimal(16,2) DEFAULT NULL,
  `Precio3` decimal(16,2) DEFAULT NULL,
  `Precio4` decimal(16,2) DEFAULT NULL,
  `Precio5` decimal(16,2) DEFAULT NULL,
  `Moneda` char(3) COLLATE latin1_spanish_ci DEFAULT 'MXN',
  `IdImpuesto` int(11) DEFAULT NULL,
  `PcImpuesto` decimal(16,2) DEFAULT NULL,
  `UnidadMedida` varchar(3) COLLATE latin1_spanish_ci DEFAULT NULL,
  `IdUnidadMedida` smallint(6) DEFAULT NULL,
  `Contenido` decimal(16,4) DEFAULT NULL,
  `Minimo` decimal(16,2) DEFAULT NULL,
  `Maximo` decimal(16,2) DEFAULT NULL,
  `EsEditablePrecio` tinyint(4) DEFAULT NULL,
  `EsEditableDescripcion` tinyint(4) DEFAULT NULL,
  `EsImpuestoIncluido` tinyint(4) DEFAULT '1',
  `Almacen1` decimal(16,4) DEFAULT NULL COMMENT 'Existencia almacen 1',
  `Almacen2` decimal(16,4) DEFAULT NULL,
  `Almacen3` decimal(16,4) DEFAULT NULL,
  `Almacen4` decimal(16,4) DEFAULT NULL,
  `Almacen5` decimal(16,4) DEFAULT NULL,
  PRIMARY KEY (`IdArticulo`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC

/*  */
CREATE TABLE `seg_aplicaciones` (
  `IdApp` int(11) NOT NULL AUTO_INCREMENT,
  `FileName` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `Descripcion` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `IdTipo` tinyint(4) NOT NULL,
  PRIMARY KEY (`IdApp`)
) ENGINE=MyISAM AUTO_INCREMENT=127 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC


/* tiendas */
CREATE TABLE `Estaciones` (
  `IdEstacion` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `CdEstacion` varchar(20) COLLATE latin1_spanish_ci NOT NULL COMMENT 'Numero de estacion',
  `CdEstacionCre` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Nombre` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `EmpresaID` int(11) DEFAULT NULL,
  `IdCertificado` int(11) DEFAULT NULL,
  -- `EsquemaTrabajo` tinyint(1) DEFAULT NULL,
  -- `DocXSustitucion` tinyint(1) DEFAULT NULL,
  -- `EsNombreEstacionPdf` tinyint(1) DEFAULT NULL,
  -- `EsEnviarZip` tinyint(1) DEFAULT NULL,
  -- `IdJornada` int(11) DEFAULT NULL,
  `Calle` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `NoExterior` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `NoInterior` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Colonia` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Localidad` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Municipio` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Estado` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Pais` varchar(3) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CP` varchar(20) COLLATE latin1_spanish_ci DEFAULT NULL,
  `EsActivo` tinyint(4) DEFAULT '1',
  -- `ExpedidoEn` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `VersionCfdi` varchar(5) COLLATE latin1_spanish_ci DEFAULT NULL COMMENT 'Indica la version en la que se van a timbrar las facturas de esta estacion en el portal de Gasofac. Valores: <vacio> y 3.3 = 3.3 | 4.0',
  `DbName` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `DbNameReplica` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `IPAddress` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `IPAddressReplica` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CdRegion` varchar(20) COLLATE latin1_spanish_ci DEFAULT NULL COMMENT 'Region donde esta el servidor',
  `IdGrupo` int(10) DEFAULT NULL,
  `sync_status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`IdEstacion`),
  UNIQUE KEY `CdEstacionCre` (`CdEstacionCre`)
) ENGINE=MyISAM AUTO_INCREMENT=86 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci

CREATE TABLE `seg_usuarioperfil` (
  `UsuarioPerfilId` int(11) NOT NULL AUTO_INCREMENT,
  `NombrePerfil` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `Nivel` smallint(6) DEFAULT NULL COMMENT 'Un nivel inferior no puede "ver" usuarios ni perfiles iguales o mas alto que el. Iguales solo si parametro lo indica',
  `ModificaNivelIgual` tinyint(4) DEFAULT NULL,
  `NivelSeries` tinyint(3) unsigned DEFAULT NULL,
  `AreaInicial` int(11) DEFAULT NULL,
  `Notas` varchar(250) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `FechaCaptura` int(11) DEFAULT NULL,
  `HoraCaptura` time DEFAULT NULL,
  `CapturadoPor` int(11) DEFAULT NULL,
  `EsActivo` tinyint(4) NOT NULL DEFAULT '1',
  `EsAdmin` tinyint(4) NOT NULL DEFAULT '0',
  `sync_status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`UsuarioPerfilId`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1

CREATE TABLE `proveedores` (
  `IdProveedor` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `NombreCorto` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `IdSegmento` smallint(6) unsigned DEFAULT NULL,
  `CodFiscal` varchar(30) COLLATE latin1_spanish_ci DEFAULT NULL,
  `NoIdentidad` varchar(30) COLLATE latin1_spanish_ci DEFAULT NULL,
  `EsActivo` tinyint(4) unsigned DEFAULT NULL,
  `IdDiasCredito` smallint(5) unsigned DEFAULT NULL,
  `Email` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Direccion` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Ciudad` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Estado` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Pais` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CodigoPostal` varchar(10) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Telefono` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Contacto` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Moneda` char(3) COLLATE latin1_spanish_ci DEFAULT 'MXN',
  `Notas` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CapturadoPor` int(10) unsigned DEFAULT NULL,
  `FechaCaptura` int(10) unsigned DEFAULT NULL,
  `HoraCaptura` time DEFAULT NULL,
  `Saldo` decimal(16,4) DEFAULT NULL,
  `IdImpuestoRegional` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`IdProveedor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci

CREATE TABLE `seg_perfilesaplicaciones` (
  `IdApp` int(11) NOT NULL,
  `IdUsuarioPerfil_fk` int(11) NOT NULL,
  PRIMARY KEY (`IdApp`,`IdUsuarioPerfil_fk`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC

CREATE TABLE `InvCotizaciones` (
  `IdCotizacion` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `FechaCaptura` INT(11) DEFAULT NULL,
  `empleado` VARCHAR(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `referencia` VARCHAR(255)  DEFAULT NULL,
  `total` DECIMAL(11) DEFAULT NULL,
  `pagado` INT(11) COLLATE latin1_spanish_ci DEFAULT NULL,
  `IdProveedor_fk` INT(11) UNSIGNED DEFAULT NULL,
  `status` INT UNSIGNED DEFAULT NULL,
  `notas` VARCHAR(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `CapturadoPor` INT DEFAULT NULL,
  `AplicadoPor` INT UNSIGNED DEFAULT NULL,
  `FechaAplicacion` INT(11) DEFAULT NULL,
  PRIMARY KEY (`IdCotizacion`)
) ENGINE=MYISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci


CREATE TABLE `InvCotizacionesDt` (
  `IdCotizacionDt` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `IdCotizacion_fk` INT(11) DEFAULT NULL,
  `IdArticulo_fk` INT(11) DEFAULT NULL,
  `Descripcion` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `notas` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `cantidad` INT(11) DEFAULT NULL,
  `CostoAlmacen` DECIMAL(11) DEFAULT NULL,
  `Costo` DECIMAL(11) DEFAULT NULL,
  `iva` DECIMAL(11) DEFAULT NULL,
  PRIMARY KEY (`IdCotizacionDt`)
) ENGINE=MYISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci