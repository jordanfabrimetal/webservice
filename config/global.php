<?php 
//PARAMETROS SERVIDOR FABRIMETAL
//Ip del servidor de BD
define("DB_HOST","127.0.0.1");

//Nombre de la Base de datos
define("DB_NAME","fabrimetalcl_appcontrato");

//Usuario BD
define("DB_USERNAME","root");

//Password Usuario
define("DB_PASSWORD","");

//Codificacion 
define("DB_ENCODE","utf8");

//Idiomar consultas fechas
define("DB_NAMES", "es_ES");

//Nombre proyecto
define("PRO_NOMBRE","Fabrimetal");

/*  Sistema SAP */

//Nombre de la Base de datos
define("DB_NAME_SAP","CLPRDFABRIMETAL_DESARROLLO");
//define("DB_NAME_SAP","CLTSTFABRIMETAL");

//Usuario BD
define("DB_USERNAME_SAP","integracion");
//define("DB_USERNAME_SAP","manager");

//Password Usuario
define("DB_PASSWORD_SAP","fm818820$");
//define("DB_PASSWORD_SAP","123456");

//Sever SAP
define("SERVER_SAP","https://172.16.34.44:50000/b1s/v2/");

//Login Sever SAP
define("LOGIN_SAP",SERVER_SAP."Login");

//Logout Sever SAP
define("LOGOUT_SAP",SERVER_SAP."Logout");

//Prefijo para las cookies de SAP
define('PREFIJO_COOKIES_SAP', 'APPGSAP_');

//Nombre de Cookie SAP: ROUTEID
define('ROUTEID', PREFIJO_COOKIES_SAP . 'ROUTEID');

//Nombre de Cookie SAP: B1SESSION
define('B1SESSION', PREFIJO_COOKIES_SAP . 'B1SESSION');

?>