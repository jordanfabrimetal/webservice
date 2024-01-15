<?php 

require "../config/conexion.php";

	Class ContactoCliente{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($tipo,$nombre,$email,$telefonomovil){
			$sql="INSERT INTO contacto_cliente(tipo, nombre, email, telefonomovil) VALUES ('$tipo', '$nombre','$email','$telefonomovil')";
			return ejecutarConsulta_retornarID($sql);
		}

		public function contacto_cc($idcontacto_cliente, $idcontrato_cliente){
			$sql="INSERT INTO contacto_cc(idcontrato_cliente, idcontacto_cliente, condicion) VALUES ('$idcontrato_cliente','$idcontacto_cliente',1)";
			return ejecutarConsulta($sql);
		}

	}
?>