<?php 

require "../config/conexion.php";

	Class Contrato{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($tcontrato,$ncontrato,$nexterno,$nreferencia,$ubicacion,$fecha, $fecha_ini, $fecha_fin ,$idperiocidad, $observaciones){
			$sql="INSERT INTO contrato(tcontrato, ncontrato, nexterno, nreferencia, ubicacion, fecha, fecha_ini, fecha_fin, idperiocidad, observaciones) VALUES ('$tcontrato', '$ncontrato','$nexterno','$nreferencia','$ubicacion','$fecha', '$fecha_ini', '$fecha_fin','$idperiocidad', '$observaciones')";
			return ejecutarConsulta_retornarID($sql);
		}
                
                public function editar($idcontrato, $tcontrato, $ncontrato, $nexterno, $nreferencia, $ubicacion, $fecha, $fecha_ini, $fecha_fin, $idregiones, $idprovincias, $idcomunas, $calle, $numero, $oficina, $observaciones, $idperiocidad){
		    $sql="UPDATE contrato SET tcontrato='$tcontrato', ncontrato='$ncontrato', nexterno='$nexterno', nreferencia='$nreferencia', ubicacion='$ubicacion', fecha='$fecha', fecha_ini='$fecha_ini', fecha_fin='$fecha_fin', idregiones='$idregiones', idprovincia='$idprovincias', idcomuna='$idcomunas', calle='$calle', numero='$numero', oficina='$oficina', observaciones='$observaciones', idperiocidad='$idperiocidad' WHERE idcontrato='$idcontrato'";
			return ejecutarConsulta($sql);
		}

		public function contrato_cliente($idcliente, $idcontrato){
			$sql="INSERT INTO contrato_cliente(idcliente, idcontrato) VALUES ('$idcliente', '$idcontrato')";
			return ejecutarConsulta_retornarID($sql);
		}

		public function edificio_contrato($idedificio, $idcontrato){
			$sql="INSERT INTO edificio_contrato(idedificio, idcontrato) VALUES ('$idedificio', '$idcontrato')";
			return ejecutarConsulta_retornarID($sql);
		}
		
		public function listar(){
			$sql="SELECT c.idcontrato ,c.ncontrato, w.razon_social, w.rut, t.nombre AS tipo, c.ubicacion, DATE(c.fecha) as fecha FROM contrato c INNER JOIN cliente w ON c.idcliente=w.idcliente INNER JOIN tcontrato t ON c.tcontrato=t.idtcontrato";
			return ejecutarConsulta($sql);
		}

		public function listarsolid(){
			$sql="SELECT c.idcontrato ,c.ncontrato, COUNT(distinct r.idedificio) as nedificios, t.region_nombre, t.region_ordinal,  COUNT(y.idascensor) AS nascensores, DATE(c.fecha) as fecha FROM contrato c INNER JOIN edificio_contrato e ON c.idcontrato=e.idcontrato INNER JOIN edificio r ON e.idedificio=r.idedificio INNER JOIN ascensor y ON e.idedificio_contrato = y.idedificio_contrato INNER JOIN regiones t ON r.idregiones = t.region_id WHERE y.codigo is null GROUP BY c.idcontrato";
			return ejecutarConsulta($sql);
		}

		public function listarsolcc(){
			$sql="SELECT c.idcontrato ,c.ncontrato, COUNT(distinct r.idedificio) as nedificios, t.region_nombre, t.region_ordinal,  COUNT(y.idascensor) AS nascensores, DATE(c.fecha) as fecha FROM contrato c INNER JOIN edificio_contrato e ON c.idcontrato=e.idcontrato INNER JOIN edificio r ON e.idedificio=r.idedificio INNER JOIN ascensor y ON e.idedificio_contrato = y.idedificio_contrato INNER JOIN regiones t ON r.idregiones = t.region_id WHERE y.codigo is null GROUP BY c.idcontrato";
			return ejecutarConsulta($sql);
		}
                
                public function mostrar($idcontrato){
		    $sql="SELECT c.ncontrato, c.calle, c.numero, c.nexterno, c.nreferencia, c.ubicacion, c.observaciones, DATE(c.fecha) AS firma, DATE(c.fecha_ini) AS inicio, DATE(c.fecha_fin) AS fin, t.nombre AS tipo, p.nombre AS periocidad, r.region_nombre AS region, q.provincia_nombre AS provincia, w.comuna_nombre AS comuna FROM contrato c INNER JOIN tcontrato t ON c.tcontrato=t.idtcontrato INNER JOIN periocidad p ON c.idperiocidad=p.idperiocidad INNER JOIN regiones r ON c.idregiones=r.region_id INNER JOIN provincias q ON c.idprovincia = q.provincia_id INNER JOIN comunas w ON c.idcomuna = w.comuna_id  WHERE c.idcontrato='$idcontrato'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function contratos_edificio($idedificio){
		    $sql="SELECT c.idcontrato, c.ncontrato, DATE(c.fecha) AS fecha, t.nombre AS tipo FROM contrato c INNER JOIN edificio_contrato q ON c.idcontrato=q.idcontrato INNER JOIN tcontrato t ON c.tcontrato = t.idtcontrato WHERE q.idedificio='$idedificio'";
		    return ejecutarConsulta($sql);
		}
                
                public function contratos_cliente($idcliente){
		    $sql="SELECT c.idcontrato, c.ncontrato, DATE(c.fecha) AS fecha, t.nombre AS tipo FROM contrato c INNER JOIN contrato_cliente q ON c.idcontrato=q.idcontrato INNER JOIN tcontrato t ON c.tcontrato = t.idtcontrato WHERE q.idcliente='$idcliente'";
		    return ejecutarConsulta($sql);
		}
                
                public function formeditar($idcontrato){
		    $sql="SELECT idcontrato, tcontrato, ncontrato, nexterno, nreferencia, ubicacion, idregiones, idprovincia, idcomuna, DATE(fecha) AS fecha, DATE(fecha_ini) AS incicio, DATE(fecha_fin) AS fin, calle, numero, oficina, idperiocidad, observaciones  FROM contrato WHERE idcontrato='$idcontrato'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                

	}
?>