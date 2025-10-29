CREATE DATABASE IF NOT EXISTS halloweendb;
USE halloweendb;

DROP TABLE disfraces, usuarios, votos;

CREATE TABLE disfraces ( id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL, nombre varchar(50) NOT NULL, descripcion text NOT NULL, votos int(11) NOT NULL, foto varchar(20) NOT NULL, foto_blob blob NOT NULL, eliminado int(11) NOT NULL DEFAULT 0 );
CREATE TABLE usuarios ( id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL, nombre varchar(50) NOT NULL, clave text NOT NULL );
CREATE TABLE votos ( id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL, id_usuario int(11) NOT NULL, id_disfraz int(11) NOT NULL );

CREATE USER 'userclient'@'localhost' IDENTIFIED BY 'userpass';
GRANT SELECT on usuarios to 'userclient'@'localhost';
GRANT SELECT on disfraces TO 'userclient'@'localhost';
GRANT SELECT, INSERT ON votos TO 'userclient'@'localhost';

CREATE USER 'useradmin'@'localhost' IDENTIFIED BY 'admin';
GRANT SELECT, INSERT, UPDATE, DELETE ON * TO 'useradmin'@'localhost';

INSERT INTO usuarios(nombre, clave) VALUES (
	'admin', '$2y$10$UDxB95HKRaJqUJ/2s6BPhObq6BewSiAVL3XJV8Cbwbhs4XWm2x5FK'
);

SELECT nombre, clave FROM usuarios;
SELECT * FROM disfraces;

