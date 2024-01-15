<?php 

require_once "../config/conexion.php";
require_once "../config/conexionSap.php";

	Class Tservicio{
		//Constructor para instancias
		public function __construct(){

		}

		public function selecttservicio(){
			$sql="SELECT * FROM tservicio"; 
			return ejecutarConsulta($sql);
		}

		public function selecttserviciosap(){
        	$query = 'ServiceCallTypesService_GetServiceCallTypeList';
			return Query($query);
		}

	}
?>