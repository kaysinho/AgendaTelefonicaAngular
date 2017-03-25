CREATE DATABASE IF NOT EXISTS dbAgendaTelefonica;
 
USE dbAgendaTelefonica;

CREATE TABLE IF NOT EXISTS Contactos (
  intId int(11) NOT NULL AUTO_INCREMENT,
  Nombres varchar(100) NOT NULL,
  Apellidos varchar(100) NOT NULL,
  Correo varchar(100) NOT NULL,
  Telefono varchar(20) NOT NULL,
  Celular varchar(20) DEFAULT NULL,
  PRIMARY KEY (intId)
);


INSERT INTO Contactos (Nombres, Apellidos, Correo, Telefono, Celular) VALUES
('Jhonatan','Plata','kaysinhoadsi@hotmail.com','59321645','3113439001'),
('Carolaine','Plata','kaysinho@hotmail.com','59321645','3513439001');
