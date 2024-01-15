<?php 

require "../config/conexion.php";

	Class Tcontrato{
		//Constructor para instancias
		public function __construct(){

		}

		public function selecttcontrato(){
			$sql="SELECT * FROM tcontrato"; 
			return ejecutarConsulta($sql);
		}

		/*
		public function insertar($nombre, $calle, $numero, $oficina, $idtsegmento, $idregiones, $idprovincias, $idcomunas){
			$sql="INSERT INTO edificio(nombre, calle, numero, oficina, condicion, idtsegmento, idregiones, idprovincias, idcomunas) VALUES ('$nombre','$calle','$numero','$oficina',1,'$idtsegmento','$idregiones','$idprovincias','$idcomunas')";
			return ejecutarConsulta($sql);
		}
		
		public function insertar_tipo($nombre,$descripcion){
		    $sql="INSERT INTO tipo_centro_costo (nombre, descripcion,condicion) VALUES ('$nombre', '$descripcion',1)";
		    return ejecutarConsulta($sql);
		}

		public function editar($idcentro_costo,$numero_centro,$descripcion,$id_tipo_centro_costo){
		    $sql="UPDATE centro_costo SET numero_centro='$numero_centro', descripcion='$descripcion', id_tipo_centro_costo='$id_tipo_centro_costo', updated_time=CURRENT_TIMESTAMP WHERE idcentro_costo='$idcentro_costo'";
			return ejecutarConsulta($sql);
		}
		
		public function editar_tipo($idtipo_centro_costo,$nombre,$descripcion){
		    $sql="UPDATE tipo_centro_costo SET nombre='$nombre', descripcion='$descripcion' WHERE idtipo_centro_costo='$idtipo_centro_costo'";
		    return ejecutarConsulta($sql);
		}

		public function desactivar($idcentro_costo){
		    $sql="UPDATE centro_costo SET condicion='0' WHERE idascensor='$idcentro_costo'";
			return ejecutarConsulta($sql);
		}

		public function activar($idcentro_costo){
		    $sql="UPDATE centro_costo SET condicion='1' WHERE idascensor='$idcentro_costo'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idcentro_costo){
		    $sql="SELECT * FROM centro_costo WHERE idcentro_costo='$idcentro_costo'";
			return ejecutarConsultaSimpleFila($sql);
		}
		
		public function mostrar_tipo($idtipo_centro_costo){
		    $sql="SELECT * FROM tipo_centro_costo WHERE idtipo_centro_costo='$idtipo_centro_costo'";
		    return ejecutarConsultaSimpleFila($sql);
		}

		public function listar_centro(){
			$sql="SELECT c.*, t.nombre FROM centro_costo c INNER JOIN tipo_centro_costo t ON c.id_tipo_centro_costo=t.idtipo_centro_costo ";
			return ejecutarConsulta($sql);
		}
		
		public function listar_tipo(){
		    $sql="SELECT * FROM tipo_centro_costo";
		    return ejecutarConsulta($sql);
		}
		
		public function listar_centro_tipo($idtipo_centro_costo){
		    $sql="SELECT c.*, t.nombre FROM centro_costo c INNER JOIN tipo_centro_costo t ON c.id_tipo_centro_costo=t.idtipo_centro_costo WHERE c.id_tipo_centro_costo='$idtipo_centro_costo' AND c.condicion=1";
		    return ejecutarConsulta($sql);
		}
		
		public function limpio(){
		    $sql="SELECT c.*, t.nombre FROM centro_costo c INNER JOIN tipo_centro_costo t ON c.id_tipo_centro_costo=t.idtipo_centro_costo WHERE c.condicion=1";
		    return ejecutarConsulta($sql);
		}
		
			
		public function selectcentro($idtipo_centro_costo){
			$sql="SELECT c.idcentro_costo, c.numero_centro, c.descripcion, t.nombre FROM centro_costo c INNER JOIN tipo_centro_costo t ON c.id_tipo_centro_costo = t.idtipo_centro_costo WHERE c.id_tipo_centro_costo='$idtipo_centro_costo' AND c.condicion=1"; 
			return ejecutarConsulta($sql);
		}
		
		
		public function selecttipo(){
		    $sql="SELECT * FROM tipo_centro_costo WHERE condicion=1";
		    return ejecutarConsulta($sql);
		}
	*/

	}
?>