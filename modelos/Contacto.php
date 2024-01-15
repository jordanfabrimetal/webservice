<?php 

require "../config/conexion.php";

	Class Contacto{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($nombre,$email,$telefonomovil){
			$sql="INSERT INTO contacto(nombre, email, telefonomovil) VALUES ('$nombre','$email','$telefonomovil')";
			return ejecutarConsulta_retornarID($sql);
		}

		public function contacto_ec($idcontacto, $idedificio_contrato){
			$sql="INSERT INTO contacto_ec(idedificio_contrato, idcontacto, condicion) VALUES ('$idedificio_contrato','$idcontacto',1)";
			return ejecutarConsulta($sql);
		}

	}
?>