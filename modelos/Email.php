<?php 

require "../config/conexion.php";

	Class Email{
		//Constructor para instancias
		public function __construct(){

		}

                public function email($idservicio){
			$sql="SELECT c.email  FROM servicio s INNER JOIN ascensor a ON s.idascensor=a.idascensor INNER JOIN edificio e ON a.idedificio=e.idedificio INNER JOIN contacto c ON e.idedificio = c.idedificio WHERE idservicio='$idservicio'";
			return ejecutarConsulta($sql);
		}
                
                public function pdfs($idedificio, $mes){
			$sql="SELECT file FROM servicio s INNER JOIN ascensor a ON s.idascensor=a.idascensor  WHERE a.idedificio='$idedificio' AND s.firma is NOT NULL AND file IS NOT NULL AND MONTH(s.created_time)='$mes'";
			return ejecutarConsulta($sql);
		}
                
                public function pdfano($idedificio, $mes, $ano){
			$sql="SELECT file FROM servicio s INNER JOIN ascensor a ON s.idascensor=a.idascensor  WHERE a.idedificio='$idedificio' AND s.firma is NOT NULL AND file IS NOT NULL AND MONTH(s.created_time)='$mes' AND YEAR(s.created_time) = '$ano'";
			return ejecutarConsulta($sql);
		}
                
                public function gseanomes($idedificio, $mes, $ano){
			$sql="SELECT s.idservicio FROM servicio s INNER JOIN ascensor a ON s.idascensor=a.idascensor  WHERE a.idedificio='$idedificio' AND s.firma is NOT NULL AND file IS NOT NULL AND MONTH(s.created_time)='$mes' AND YEAR(s.created_time) = '$ano' UNION SELECT s.idservicio FROM servicio s INNER JOIN ascensor a ON s.idascensor=a.idascensor  WHERE a.idedificio='$idedificio' AND s.reqfirma = 0 is NOT NULL AND file IS NOT NULL AND MONTH(s.created_time)='$mes' AND YEAR(s.created_time) = '$ano'";
			return ejecutarConsulta($sql);
		}
     
                public function edificio($idedificio){
			$sql="SELECT s.nombre AS segmen, e.nombre AS edi, e.calle, e.numero, r.region_nombre AS region, c.comuna_nombre FROM edificio e INNER JOIN tsegmento s ON e.idtsegmento=s.idtsegmento INNER JOIN regiones r ON e.idregiones=r.region_id INNER JOIN comunas c ON e.idcomunas = c.comuna_id WHERE e.idedificio='$idedificio'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function emailedif($idedificio){
			$sql="SELECT email FROM contacto WHERE idedificio='$idedificio'";
			return ejecutarConsulta($sql);
		}
	}
?>