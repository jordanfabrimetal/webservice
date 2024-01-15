<?php 

require "../config/conexion.php";

	Class Arduino{
		//Constructor para instancias
		public function __construct(){

		}
		/*
		public function insertar($fabricante,$region,$codigo, $lat, $lon){
			$sql="INSERT INTO ascensor (fabricante, region, codigo, lat, lon, condicion) VALUES ('$fabricante', '$region', '$codigo','$lat','$lon','1)";
			return ejecutarConsulta($sql);
		}

		public function editar($idascensor,$fabricante,$region,$codigo){
			$sql="UPDATE ascensor SET fabricante='$fabricante', region='$region', codigo='$codigo', updated_time=CURRENT_TIMESTAMP WHERE idascensor='$idascensor'";
			return ejecutarConsulta($sql);
		}
		*/

		public function desactivar($idarduino){
			$sql="UPDATE arduino SET estado='0', updated_time=CURRENT_TIMESTAMP WHERE idarduino='$idarduino'";
			return ejecutarConsulta($sql);
		}

		public function activar($idarduino){
			$sql="UPDATE arduino SET estado='1', updated_time=CURRENT_TIMESTAMP WHERE idarduino='$idarduino'";
			return ejecutarConsulta($sql);
		}

		public function updated($idarduino){
			$sql="UPDATE arduino SET updated_time=CURRENT_TIMESTAMP WHERE idarduino='$idarduino'";
			return ejecutarConsulta($sql);
		}

		public function id_arduino($codigo){
			$sql="SELECT idarduino FROM arduino WHERE codigo='$codigo'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function verificarultimo($codigo){
			$sql="SELECT estado FROM arduino WHERE codigo='$codigo'";
			return ejecutarConsultaSimpleFila($sql);
		}
        

		public function listar(){
			$sql="SELECT * FROM arduino";
			return ejecutarConsulta($sql);
		}

		public function desactivados(){
			$sql="SELECT a.codigo, a.ubicacion, a.funcion, e.nombre, e.calle, e.numero, a.estado, a.updated_time FROM arduino a INNER JOIN edificio e ON a.idedificio = e.idedificio WHERE estado='0' AND a.updated_time != 'null'";
			return ejecutarConsulta($sql);
		}

		public function contar(){
			$sql="SELECT estado FROM arduino WHERE updated_time != 'null'";
			return ejecutarConsulta($sql);
		}
        
        public function verificar(){
			//$sql="SELECT codigo FROM arduino WHERE TIMESTAMPDIFF(MINUTE, `updated_time`, NOW())>60";
			$sql="UPDATE arduino SET estado='0' WHERE TIMESTAMPDIFF(MINUTE, updated_time, NOW())>30";
			return ejecutarConsulta($sql);
		}
        

	}
?>