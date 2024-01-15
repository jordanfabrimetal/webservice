<?php 

require "../config/conexion.php";

	Class Marca{
		//Constructor para instancias
		public function __construct(){

		}

		public function selectmarca(){
			$sql="SELECT * FROM marca"; 
			return ejecutarConsulta($sql);
		}

	}
?>