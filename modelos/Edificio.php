<?php 

require "../config/conexion.php";

	Class Edificio{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($nombre, $calle, $numero, $idtsegmento, $coordinacion, $residente, $idregiones, $idprovincias, $idcomunas){
			$sql="INSERT INTO edificio(nombre, calle, numero, condicion, idtsegmento,coordinacion, residente, idregiones, idprovincias, idcomunas) VALUES ('$nombre','$calle','$numero',1,'$idtsegmento', '$coordinacion', '$residente','$idregiones','$idprovincias','$idcomunas')";
			return ejecutarConsulta_retornarID($sql);
		}

		public function VerEdificio($nombre, $calle, $numero){
			$sql="SELECT idedificio FROM edificio WHERE nombre='$nombre' AND calle='$calle' AND numero='$numero'";
			return ejecutarConsultaSimpleFila($sql);
		}
		
                public function editar($idedificio, $nombre, $calle, $numero, $oficina, $idtsegmento, $coordinacion, $residente, $idregiones, $idprovincias, $idcomunas){
                    $sql="UPDATE edificio SET nombre='$nombre', calle='$calle', numero='$numero', oficina='$oficina', coordinacion='$coordinacion', residente='$residente', idregiones='$idregiones', idprovincias='$idprovincias', idcomunas='$idcomunas', idtsegmento='$idtsegmento', updated_time=CURRENT_TIMESTAMP WHERE idedificio='$idedificio'";
			return ejecutarConsulta($sql);
                }
                
                public function listar(){
			$sql="SELECT e.idedificio, e.nombre, e.calle, e.numero, r.region_nombre AS region, c.comuna_nombre AS comuna, s.nombre AS segmento FROM edificio e INNER JOIN regiones r ON e.idregiones=r.region_id INNER JOIN comunas c ON e.idcomunas=c.comuna_id INNER JOIN tsegmento s ON e.idtsegmento = s.idtsegmento";
			return ejecutarConsulta($sql);
		}
                
                public function formeditar($idedificio){
		    $sql="SELECT idedificio, nombre, calle, numero,coordinacion, residente, idregiones, idprovincias, idcomunas, idtsegmento FROM edificio WHERE idedificio='$idedificio'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function mostrar($idedificio){
		    $sql="SELECT e.idedificio, e.nombre, e.calle, e.numero, e.oficina, r.region_nombre AS region, p.provincia_nombre AS provincia, c.comuna_nombre AS comuna, s.nombre AS segmento FROM edificio e INNER JOIN regiones r ON e.idregiones=r.region_id INNER JOIN provincias p ON e.idprovincias=p.provincia_id INNER JOIN comunas c ON e.idcomunas=c.comuna_id INNER JOIN tsegmento s ON e.idtsegmento =s.idtsegmento WHERE e.idedificio='$idedificio'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function contar_edificios($idcontrato){
		    $sql="SELECT COUNT(idedificio_contrato) AS nedificios FROM edificio_contrato WHERE idcontrato='$idcontrato'";
		    return ejecutarConsultaSimpleFila($sql);
		}
                
                public function edificios_contrato($idcontrato){
		    $sql="SELECT e.nombre, e.calle, e.numero, s.nombre AS segmento, r.region_nombre AS region, n.comuna_nombre AS comuna FROM edificio_contrato q INNER JOIN edificio e ON q.idedificio=e.idedificio INNER JOIN tsegmento s ON e.idtsegmento = s.idtsegmento INNER JOIN regiones r ON e.idregiones = r.region_id INNER JOIN comunas n ON e.idcomunas = n.comuna_id  WHERE q.idcontrato = '$idcontrato'";
		    return ejecutarConsulta($sql);
		}
                
                public function edificios_cliente($idcliente){
		    $sql="SELECT e.idedificio, e.nombre, e.calle, e.numero, s.nombre AS segmento, r.region_nombre AS region, t.comuna_nombre AS comuna FROM edificio e INNER JOIN edificio_contrato q ON e.idedificio=q.idedificio INNER JOIN contrato_cliente w ON q.idcontrato=w.idcontrato INNER JOIN tsegmento s ON e.idtsegmento=s.idtsegmento INNER JOIN regiones r ON e.idregiones = r.region_id INNER JOIN comunas t ON e.idcomunas = t.comuna_id WHERE w.idcliente = '$idcliente'";
		    return ejecutarConsulta($sql);
		}
                
                
	}
?>