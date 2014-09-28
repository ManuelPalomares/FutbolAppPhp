# SQL Manager 2007 for MySQL 4.1.2.1
# ---------------------------------------
# Host     : localhost
# Port     : 3306
# Database : escuelafutbol


SET FOREIGN_KEY_CHECKS=0;

#
# Structure for the `opciones` table : 
#

CREATE TABLE `opciones` (
  `CODIGO` bigint(20) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION_LARGA` varchar(100) DEFAULT NULL,
  `DESCRIPCION` varchar(50) DEFAULT NULL,
  `MODULO` char(1) DEFAULT NULL,
  `CODIGO_PADRE` bigint(20) DEFAULT NULL,
  `NOMBRE_VIEW` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`CODIGO`),
  KEY `opciones_fk` (`CODIGO_PADRE`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# Structure for the `opciones_acciones` table : 
#

CREATE TABLE `opciones_acciones` (
  `CODIGO_ACCION` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO_OPCION` bigint(20) NOT NULL,
  `DESCRIPCION_ACCION` varchar(100) DEFAULT NULL,
  `COMENTARIO` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`CODIGO_ACCION`),
  UNIQUE KEY `CODIGO_ACCION` (`CODIGO_ACCION`),
  KEY `CODIGO_OPCION` (`CODIGO_OPCION`),
  CONSTRAINT `opciones_acciones_fk` FOREIGN KEY (`CODIGO_OPCION`) REFERENCES `opciones` (`CODIGO`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# Structure for the `roles` table : 
#

CREATE TABLE `roles` (
  `CODIGO` bigint(20) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`CODIGO`),
  UNIQUE KEY `CODIGO` (`CODIGO`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# Structure for the `roles_opciones` table : 
#

CREATE TABLE `roles_opciones` (
  `OPCION` bigint(20) NOT NULL,
  `ROL` bigint(20) NOT NULL,
  UNIQUE KEY `ROLES_OPCIONES__UN` (`OPCION`,`ROL`),
  KEY `roles_opciones_fk` (`ROL`),
  KEY `OPCION` (`OPCION`),
  CONSTRAINT `roles_opciones_fk1` FOREIGN KEY (`OPCION`) REFERENCES `opciones` (`CODIGO`),
  CONSTRAINT `roles_opciones_fk` FOREIGN KEY (`ROL`) REFERENCES `roles` (`CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Structure for the `suscriptores` table : 
#

CREATE TABLE `suscriptores` (
  `CODIGO` int(15) NOT NULL AUTO_INCREMENT COMMENT 'CODIGO DE CLIENTE',
  `NOMBRES` varchar(100) DEFAULT NULL COMMENT 'NOMBRE DEL CLIENTE',
  `APELLIDOS` varchar(100) DEFAULT NULL COMMENT 'EL APPELIDO DEL CLIENTE',
  `TELEFONO` varchar(20) DEFAULT NULL,
  `CELULAR` varchar(20) DEFAULT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `GENERO` varchar(1) DEFAULT NULL,
  `ESTADO` int(11) DEFAULT NULL,
  `FECHA_INGRESO` date DEFAULT NULL COMMENT 'FECHA DE REGISTRO EN EL SISTEMA',
  `FECHA_INCRIPCION` date DEFAULT NULL COMMENT 'FECHA DE INICIO INSCRIPCION',
  `DIRECCION` varchar(100) DEFAULT NULL COMMENT 'DIRECCION DE LA CASA',
  PRIMARY KEY (`CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Structure for the `usuarios` table : 
#

CREATE TABLE `usuarios` (
  `CODIGO` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Codigo del usuario',
  `USUARIO` varchar(100) DEFAULT NULL COMMENT 'nombre del usuario',
  `PASSWORD` varchar(100) DEFAULT NULL COMMENT 'contraseña de usuario',
  `NOMBRE` varchar(100) DEFAULT NULL COMMENT 'Nombre completo usuario',
  `CORREO` varchar(100) DEFAULT NULL COMMENT 'Email usuario',
  `ESTADO` varchar(2) DEFAULT NULL COMMENT 'Estado del usuario A Activo I Inactivo',
  PRIMARY KEY (`CODIGO`),
  UNIQUE KEY `usuarios_PK` (`CODIGO`),
  UNIQUE KEY `usuarios__UNv1` (`USUARIO`,`PASSWORD`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# Structure for the `usuarios_rol` table : 
#

CREATE TABLE `usuarios_rol` (
  `CODIGO_USUARIO` bigint(20) NOT NULL,
  `ROL` bigint(20) NOT NULL,
  `ESTADO` char(1) DEFAULT NULL,
  UNIQUE KEY `USUARIOS_ROL__UN` (`CODIGO_USUARIO`,`ROL`),
  KEY `CODIGO_USUARIO` (`CODIGO_USUARIO`),
  KEY `usuarios_rol_fk1` (`ROL`),
  CONSTRAINT `usuarios_rol_fk1` FOREIGN KEY (`ROL`) REFERENCES `roles` (`CODIGO`),
  CONSTRAINT `usuarios_rol_fk` FOREIGN KEY (`CODIGO_USUARIO`) REFERENCES `usuarios` (`CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Definition for the `PR_SQ_USUARIOS` procedure : 
#

CREATE DEFINER = 'root'@'localhost' PROCEDURE `PR_SQ_USUARIOS`(out sq_codigo int)
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN

SELECT MAX(CODIGO)+1 INTO SQ_CODIGO  FROM USUARIOS;


END;

#
# Data for the `opciones` table  (LIMIT 0,500)
#

INSERT INTO `opciones` (`CODIGO`, `DESCRIPCION_LARGA`, `DESCRIPCION`, `MODULO`, `CODIGO_PADRE`, `NOMBRE_VIEW`) VALUES 
  (1,'Aplicativo Escuela de futbol','Escuela de futbol','0',NULL,NULL);

COMMIT;

#
# Data for the `opciones_acciones` table  (LIMIT 0,500)
#

INSERT INTO `opciones_acciones` (`CODIGO_ACCION`, `CODIGO_OPCION`, `DESCRIPCION_ACCION`, `COMENTARIO`) VALUES 
  (1,1,'CONECTARSE','Accion de conectarse al aplicativo para toods los usuarios');

COMMIT;

#
# Data for the `roles` table  (LIMIT 0,500)
#

INSERT INTO `roles` (`CODIGO`, `DESCRIPCION`) VALUES 
  (1,'CONEX');

COMMIT;

#
# Data for the `roles_opciones` table  (LIMIT 0,500)
#

INSERT INTO `roles_opciones` (`OPCION`, `ROL`) VALUES 
  (1,1);

COMMIT;

#
# Data for the `usuarios` table  (LIMIT 0,500)
#

INSERT INTO `usuarios` (`CODIGO`, `USUARIO`, `PASSWORD`, `NOMBRE`, `CORREO`, `ESTADO`) VALUES 
  (1,'manuel','123','Manuel','manuel936@gmail.com','A');

COMMIT;

#
# Data for the `usuarios_rol` table  (LIMIT 0,500)
#

INSERT INTO `usuarios_rol` (`CODIGO_USUARIO`, `ROL`, `ESTADO`) VALUES 
  (1,1,'A');

COMMIT;

