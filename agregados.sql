use fabrimetalcl_appcontrato;

CREATE TABLE registro_dispositivo(
	id             INT(11) AUTO_INCREMENT PRIMARY KEY,
    dispositivo    VARCHAR(20) NOT NULL,
    actividad      INT(11) NOT NULL,
    servicio       INT(11) NOT NULL,
    responsable    VARCHAR(50) NOT NULL,
    fecha          DATETIME DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB;
