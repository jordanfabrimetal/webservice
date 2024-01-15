<?php 

require "../config/conexion.php";

	Class Testado{
		//Constructor para instancias
		public function __construct(){

		}

		public function selecttestado(){
			$sql="SELECT * FROM testado"; 
			return ejecutarConsulta($sql);
		}
                
                public function selecttestadofi(){
			$sql="SELECT * FROM testado WHERE fin='1'"; 
			return ejecutarConsulta($sql);
		}
                

	}
?>