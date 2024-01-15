<?php 

require "../config/conexion.php";

	Class Modelo{
		//Constructor para instancias
		public function __construct(){

		}

		public function selectmodelo($idmarca){
			$sql="SELECT * FROM modelo WHERE marca = '$idmarca'"; 
			return ejecutarConsulta($sql);
		}


	}
?>