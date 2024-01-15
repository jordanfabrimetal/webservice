<?php 

require "../config/conexion.php";

	Class Tecnico{
		//Constructor para instancias
		public function __construct(){

		}
                
                public function Idtecnico($rut){
			$sql="SELECT idtecnico FROM tecnico WHERE rut='$rut'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function Email($idservicio){
			$sql="SELECT t.email_interno AS email FROM servicio s INNER JOIN tecnico t ON s.idtecnico=t.idtecnico WHERE s.idservicio='$idservicio'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function EmailSup($idservicio){
			$sql="SELECT u.email_interno AS email FROM servicio s INNER JOIN tecnico t ON s.idtecnico=t.idtecnico INNER JOIN supervisor u ON t.idsupervisor=u.idsupervisor  WHERE s.idservicio='$idservicio'";
			return ejecutarConsultaSimpleFila($sql);
		}

	}
?>