<?php 

require "../config/conexion.php";

	Class Tascensor{
		//Constructor para instancias
		public function __construct(){

		}

		public function selecttascensor(){
			$sql="SELECT * FROM tascensor"; 
			return ejecutarConsulta($sql);
		}

	}
?>