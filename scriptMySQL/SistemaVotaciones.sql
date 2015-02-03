-- Script que crea la base de datos para la aplicación SistemaVotaciones

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Schema elecciones
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `elecciones`;
CREATE SCHEMA IF NOT EXISTS `elecciones` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `elecciones`;


-- -----------------------------------------------------
-- Table `Personas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Personas`;
CREATE TABLE IF NOT EXISTS `Personas` (
  `PK_Cedula` VARCHAR(11) NOT NULL,
  `Nombre` VARCHAR(50) NOT NULL,
  `Apellido1` VARCHAR(50) NOT NULL,
  `Apellido2` VARCHAR(50) NOT NULL,
  `Provincia` VARCHAR(20) NOT NULL,
  `Contrasena` VARCHAR(40) NOT NULL,
  PRIMARY KEY (`PK_Cedula`)
)
ENGINE = InnoDB DEFAULT CHARSET = utf8;

-- -----------------------------------------------------
-- Inserts table `Personas`
-- -----------------------------------------------------

-- select md5('Contrasena') para encriptar
INSERT INTO `Personas`(`PK_Cedula`, `Nombre`, `Apellido1`, `Apellido2`, `Provincia`, `Contrasena`) VALUES 
('2-0561-0777', 'Adolfo', 'Alvarado', 'Matamoros', 'Guanacaste','58e9a041eb2830a72e67b6abec104aa2'), -- --> AdAlMa
('3-1837-0771', 'Alejandro', 'Menendez', 'Zuniga', 'Cartago','0a9621457c34309790a8f5886b60355c'), -- --> AlMeZu
('6-0158-0047', 'Anthony', 'Salazar', 'Gomez', 'Puntarenas','eaf1b1839a553dbaa82672e8e3045d50'), -- --> AnSaGo
('1-0151-1322', 'Axel', 'Myers', 'Mc Cook', 'Puntarenas','89627aa5d23c3e56712ec7cbb1bb1761'), -- --> AxMyMc
('1-1427-1642', 'Carlos', 'Fuentes', 'Calderon', 'Heredia','71f7e0bc61f1e65c160e58d55063759b'), -- --> CaFuCa
('5-1747-1636', 'Carlos', 'Mora', 'Aguilar', 'Alajuela','53539eacd55569aa8207291341d0bbab'), -- --> CaMoAg
('3-1023-0912', 'Daniel', 'Aguilar', 'Granados', 'Heredia','00b64bcdfa26b0b3993b127eefc32393'), -- --> DaAgGr
('4-1017-1232', 'Daniel', 'Jimenez', 'Sanchez', 'San José','d04cd6b08762fe793dd022ce239efe8b'), -- --> DaJiSa
('2-0293-0509', 'Daniela', 'Montero', 'Urena', 'Limón','f12cc74d6813cd97e54323a339b8537d'), -- --> DaMoUr
('2-0613-1784', 'Diego', 'Mena', 'Ruiz', 'Heredia','b429fb63bba231635d6c673de4cf33e8'), -- --> DiMeRu
('3-1888-1777', 'Fernando', 'Vargas', 'Sandi', 'Alajuela','a06163fef99df4655eaa78c1b9df303d'), -- --> FeVaSa
('3-0454-0214', 'Gilberth', 'Molina', 'Castrillo', 'Cartago','1f339914dfcad679439ffaa929c45d6e'), -- --> GiMoCa
('4-0203-1374', 'Gustavo', 'Enriquez', 'Arcia', 'Puntarenas','55f46a7894f4788d688aa3a021f48239'), -- --> GuEnAr
('2-5555-5555', 'Heiner', 'Morales', 'Morera', 'Guanacaste','0e9c12f7b17eaf6e7aadc6165e42e38c'), -- --> HeMoMo
('4-1478-1694', 'Jose Daniel', 'Mora', 'Perez', 'San José','b06bddb8311f1f178404b9cb04b4fd72'), -- --> JoMoPe
('5-0755-1688', 'Jeison', 'Leandro', 'Hernandez', 'Cartago','e9c0798510fa6ec81d79a31cce712123'), -- --> JeLeHe
('3-1075-0964', 'Jorge', 'Leandro', 'Benavidez', 'Heredia','4a46be684cfe11a6f7645d3bca19fb80'), -- --> JoLeBe
('4-1068-0240', 'Jorge', 'Ortega', 'Solis', 'Limón','91fa81fe6f46f63062864953b4194bcd'), -- --> JoOrSo
('4-0345-0560', 'Kevin', 'Brenes', 'Martinez', 'Limón','61db114a910208e720dc2271cced2da1'), -- --> KeBrMa
('2-0665-0554', 'Kevin', 'Otarola', 'Solano', 'San José','32b6b8ee94994b8b6f572c6531ccff2f'), -- --> KeOtSo
('6-1940-1829', 'Maria Fernanda', 'Cortes', 'Ramos', 'Limón','4bba01d1351f92ea0b25c9c490882ac2'), -- --> MaCoRa
('1-1934-0150', 'Rodrigo', 'Perez', 'Porras', 'Guanacaste','307ce1fe1878fff787edc6a3949857cb'), -- --> RoPePo
('4-1210-1426', 'Rolando', 'Arguedas', 'Marin', 'Heredia','7e7679247a7f7e30d039be7a6139f7f3'), -- --> RoArMa
('4-1530-1419', 'Rudy', 'Campos', 'Badilla', 'Alajuela','5cab7a603e9895440dcf3184fcfe9427'), -- --> RuCaBa
('5-0806-0695', 'Sergio', 'Serrano', 'Hernandez', 'San José','001ba13275ef3f44f96585aecdd5ca25'), -- --> SeSeHe
('3-0800-1016', 'Stephanie', 'Cartin', 'Marin', 'Cartago','5d76e2e534630ecd49551c6d508e5771'), -- --> StCaMa
('1-1120-0292', 'Victor', 'Salgado', 'Martinez', 'Puntarenas','32309695f6d9826ba5995757b02b606d'), -- --> ViSaMa
('1-0396-0612', 'Yeimy', 'Bolanos', 'Cardenas', 'Guanacaste','129e887b4a838fabc24ecbcb6286c3e5'), -- --> YeBoCa
-- Usuarios para pruebas de la aplicacion
('1-1234-5678', 'John', 'Doe', 'Bloggs', 'San José','68bcd95b53b2809c2ff1e63d9337fabe'), -- --> JoDoBl
('3-4350-8654', 'James', 'Doe', 'Bloggs', 'Cartago','cdb477b98b0aea5945a2b025c70fc303'), -- --> JaDoBl
('4-5455-0987', 'Judy', 'Doe', 'Bloggs', 'Alajuela','2f4aa18f1df0dd3e009a8d82fd6ecf45'); -- --> JuDoBl


-- -----------------------------------------------------
-- Table `Votaciones`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Votaciones`;
CREATE TABLE IF NOT EXISTS `Votaciones` (
  `PK_IdVotacion` INT NOT NULL AUTO_INCREMENT,
  `FechaInicio` DATETIME NOT NULL,
  `FechaFin` DATETIME NOT NULL,
  `Tipo` CHAR(1) NULL,
  PRIMARY KEY (`PK_IdVotacion`)
)
ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;

-- -----------------------------------------------------
-- Inserts table `Votaciones`
-- -----------------------------------------------------

INSERT INTO `Votaciones` (`FechaInicio`, `FechaFin`, `Tipo`) VALUES
('2014-04-20 06:00:00', '2014-05-04 20:00:00', 'P'),
('2014-04-19 06:00:00', '2014-05-03 19:00:00', 'M'),
('2014-04-18 06:00:00', '2014-05-02 18:00:00', 'R');


-- -----------------------------------------------------
-- Table `Votaciones_Opciones`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Votaciones_Opciones`;
CREATE TABLE IF NOT EXISTS `Votaciones_Opciones` (
  `PK_IdOpcion` INT NOT NULL AUTO_INCREMENT,
  `FK_IdVotacion` INT NOT NULL,
  `Bandera` VARCHAR(300) NOT NULL,
  `Partido` VARCHAR(100) NOT NULL,
  `Candidato` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`PK_IdOpcion`),
  INDEX `FK_Votaciones_Opciones_Votaciones_idx` (`FK_IdVotacion` ASC),
  CONSTRAINT `FK_Votaciones_Opciones_Votaciones`
    FOREIGN KEY (`FK_IdVotacion`)
    REFERENCES `Votaciones` (`PK_IdVotacion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;

-- -----------------------------------------------------
-- Inserts table `Votaciones_Opciones`
-- -----------------------------------------------------

INSERT INTO `Votaciones_Opciones`(`FK_IdVotacion`, `Bandera`, `Partido`, `Candidato`) VALUES
('1', '../images/banderas/bandera_Presidencial_PAC.png', 'Partido Accion Ciudadana', 'Luis Guillermo Solis Rivera'),
('1', '../images/banderas/bandera_Presidencial_PLN.png', 'Partido Liberacion Nacional', 'Johnny Araya Monge'),
('2', '../images/banderas/bandera_Municipal_PAC.png', 'Partido Accion Ciudadana', 'Victor Morales Mora'),
('2', '../images/banderas/bandera_Municipal_PAC.png', 'Partido Accion Ciudadana', 'Abelino Torres Reyes'),
('2', '../images/banderas/bandera_Municipal_PAC.png', 'Partido Accion Ciudadana', 'Eduardo Pineda Alvarado'),
('2', '../images/banderas/bandera_Municipal_PLN.png', 'Partido Liberacion Nacional', 'Manuel Espinoza Campos'),
('2', '../images/banderas/bandera_Municipal_PLN.png', 'Partido Liberacion Nacional', 'Bernardo Barboza Picado'),
('2', '../images/banderas/bandera_Municipal_PLN.png', 'Partido Liberacion Nacional', 'Gerardo Oviedo Espinoza'),
('3', '../images/banderas/bandera_Referendum_TLCSI.png', '¿Está de acuerdo con el TLC?', 'Sí'),
('3', '../images/banderas/bandera_Referendum_TLCNO.png', '¿Está de acuerdo con el TLC?', 'No');


-- -----------------------------------------------------
-- Table `Votos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Votos`;
CREATE TABLE IF NOT EXISTS `Votos` (
  `PK_Cedula` VARCHAR(11) NOT NULL,
  `PK_IdVotacion` INT NOT NULL,
  `FK_IdOpcion` INT NOT NULL,
  `Firma` VARCHAR(1000) NOT NULL,
  `Fecha` DATETIME NOT NULL,
  PRIMARY KEY (`PK_Cedula`, `PK_IdVotacion`),
  INDEX `FK_Votos_Votaciones_idx` (`PK_IdVotacion` ASC),
  INDEX `FK_Votos_Opciones_idx` (`FK_IdOpcion` ASC),
  CONSTRAINT `FK_Votos_Personas`
    FOREIGN KEY (`PK_Cedula`)
    REFERENCES `Personas` (`PK_Cedula`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_Votos_Votaciones`
    FOREIGN KEY (`PK_IdVotacion`)
    REFERENCES `Votaciones` (`PK_IdVotacion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_Votos_Opciones`
    FOREIGN KEY (`FK_IdOpcion`)
    REFERENCES `Votaciones_Opciones` (`PK_IdOpcion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB DEFAULT CHARSET = utf8;

-- -----------------------------------------------------
-- Inserts table `Votos`
-- -----------------------------------------------------

INSERT INTO `Votos`(`PK_Cedula`, `PK_IdVotacion`, `FK_IdOpcion`, `Firma`, `Fecha`) VALUES
('1-0151-1322', 1, 1, 'Firma de Axel Myers Mc Cook', '2014-04-21 11:30:00'),
('1-0396-0612', 1, 1, 'Firma de Yeimy Bolanos Cardenas', '2014-04-21 11:30:00'),
('1-1120-0292', 1, 1, 'Firma de Victor Salgado Martinez', '2014-04-21 11:30:00'),
('1-1427-1642', 1, 1, 'Firma de Carlos Fuentes Calderon', '2014-04-21 11:30:00'),
('1-1934-0150', 1, 1, 'Firma de Rodrigo Perez Porras', '2014-04-21 11:30:00'),
('2-0293-0509', 1, 1, 'Firma de Daniela Montero Urena', '2014-04-21 11:30:00'),
('2-0561-0777', 1, 1, 'Firma de Adolfo Alvarado Matamoros', '2014-04-21 11:30:00'),
('2-0613-1784', 1, 1, 'Firma de Diego Mena Ruiz', '2014-04-21 11:30:00'),
('2-0665-0554', 1, 1, 'Firma de Kevin Otarola Solano', '2014-04-21 11:30:00'),
('2-5555-5555', 1, 1, 'Firma de Heiner Morales Morera', '2014-04-21 11:30:00'),
('3-0800-1016', 1, 1, 'Firma de Stephanie Cartin Marin', '2014-04-21 11:30:00'),
('3-1023-0912', 1, 1, 'Firma de Daniel Aguilar Granados', '2014-04-21 11:30:00'),
('3-1075-0964', 1, 1, 'Firma de Jorge Leandro Benavidez', '2014-04-21 11:30:00'),
('3-1837-0771', 1, 1, 'Firma de Alejandro Menendez Zuniga', '2014-04-21 11:30:00'),
('3-1888-1777', 1, 1, 'Firma de Fernando Vargas Sandi', '2014-04-21 11:30:00'),
('4-0203-1374', 1, 1, 'Firma de Gustavo Enriquez Arcia', '2014-04-21 11:30:00'),
('3-0454-0214', 1, 1, 'Firma de Gilberth Molina Castrillo', '2014-04-21 11:30:00'),
('4-0345-0560', 1, 2, 'Firma de Kevin Brenes Martinez', '2014-04-21 11:30:00'),
('4-1017-1232', 1, 2, 'Firma de Daniel Jimenez Sanchez', '2014-04-21 11:30:00'),
('4-1068-0240', 1, 2, 'Firma de Jorge Ortega Solis', '2014-04-21 11:30:00'),
('4-1210-1426', 1, 2, 'Firma de Rolando Arguedas Marin', '2014-04-21 11:30:00'),
('4-1478-1694', 1, 2, 'Firma de Jose Daniel Mora Perez', '2014-04-21 11:30:00'),
('4-1530-1419', 1, 2, 'Firma de Rudy Campos Badilla', '2014-04-21 11:30:00'),
('5-0755-1688', 1, 2, 'Firma de Jeison Leandro Hernandez', '2014-04-21 11:30:00'),
('5-0806-0695', 1, 2, 'Firma de Sergio Serrano Hernandez', '2014-04-21 11:30:00'),
('5-1747-1636', 1, 2, 'Firma de Carlos Mora Aguilar', '2014-04-21 11:30:00'),
('6-0158-0047', 1, 2, 'Firma de Anthony Salazar Gomez', '2014-04-21 11:30:00'),
('6-1940-1829', 1, 2, 'Firma de Maria Fernanda Cortes Ramos', '2014-04-21 11:30:00');

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

COMMIT;

USE elecciones;
