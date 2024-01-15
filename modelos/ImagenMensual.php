<?php 

require "../config/conexion.php";

	Class ImagenMensual{
		//Constructor para instancias
		public function __construct(){

		}
		public function listar(){
			$sql="SELECT * FROM imagen_mensual";
			return ejecutarConsulta($sql);
		}

		public function periodo($mes, $anio){
			$sql="SELECT * FROM imagen_mensual WHERE mes = $mes AND anio = $anio";
			return ejecutarConsulta($sql);
		}

		public function insertar_imagen_mensual($data){
			$sql = "INSERT INTO `imagen_mensual_actividad`(`equipoFM`,`servicioSAP`,`actividadSAP`,`imagenmensualID`,`imagen`) VALUES ('{$data['equipoFM']}', {$data['servicioSAP']}, {$data['actividadSAP']}, {$data['imagenmensualID']}, '{$data['imagen']}' )";
			return ejecutarConsu_retornarID($sql);
		}

		public function imagen_actividad($actividad){
			$sql = "SELECT enc_id AS 'tipo' FROM informevisita WHERE infv_actividad = $actividad";
			//$sql="SELECT COUNT(*) AS 'contador'  FROM imagen_mensual_actividad WHERE actividadSAP = $actividad";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function rescatar_imagen($actividad,$servicio){
			$sql = "SELECT im.titulo AS 'titulo', im.descripcion AS 'descripcion', ima.imagen AS 'imagen' FROM imagen_mensual_actividad ima INNER JOIN imagen_mensual im ON ima.imagenmensualID = im.imagenmensualID WHERE ima.actividadSAP = ".$actividad." AND ima.servicioSAP = ".$servicio." ORDER BY ima.imagenmensualactividadID DESC LIMIT 1";
			//$sql="SELECT * FROM imagen_mensual_actividad WHERE actividadSAP = ".$actividad." AND servicioSAP = ".$servicio." ORDER BY imagenmensualactividadID DESC LIMIT 1";
			return ejecutarConsulta($sql);
		}
	}
?>