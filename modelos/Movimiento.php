<?php 

require "../config/conexion.php";

	Class Movimiento{
		//Constructor para instancias
		public function __construct(){

		}

		public function listar(){
			$sql="SELECT  d.idascensor, a.codigo ,e.nombre, e.calle, e.numero, SUM(d.movimientos) as movimiento, SUM(d.recorrido) as recorrido FROM datos d INNER JOIN ascensor a ON d.idascensor=a.idascensor INNER JOIN edificio e ON a.id_edificio=e.idedificio GROUP BY d.idascensor";
			return ejecutarConsulta($sql);
		}

		public function movimientos_ascensor($idascensor){
			$sql="SELECT * FROM datos WHERE idascensor='$idascensor'";
			return ejecutarConsulta($sql);
		}

		public function datos_ascensor(){
			$sql="SELECT * FROM datos WHERE idascensor='$idascensor'";
			return ejecutarConsulta($sql);
		}

		public function contar(){
			$sql="SELECT SUM(movimientos) as movimiento, SUM(recorrido) as recorrido FROM datos";
			return ejecutarConsulta($sql);
		}



	}
?>