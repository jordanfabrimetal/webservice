<?php 

require "../config/conexion.php";

	Class Cliente{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($id_user, $idtcliente, $rut, $razon_social, $calle, $numero, $oficina, $idregiones, $idprovincias, $idcomunas){
			$sql="INSERT INTO cliente(id_user, idtcliente, rut, razon_social, calle, numero, oficina, condicion, idregiones, idprovincias, idcomunas) VALUES ('$id_user','$idtcliente','$rut', '$razon_social', '$calle','$numero','$oficina',1,'$idregiones','$idprovincias','$idcomunas')";
			return ejecutarConsulta_retornarID($sql);
		}

		public function VerCliente($rut){
			$sql="SELECT idcliente FROM cliente WHERE rut='$rut'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function clientes_contrato($idcontrato){
		    $sql="SELECT q.idcontrato, c.rut, c.razon_social, c.calle, c.numero, t.nombre AS tipo, r.region_nombre AS region, n.comuna_nombre AS comuna FROM contrato_cliente q INNER JOIN cliente c ON q.idcliente=c.idcliente INNER JOIN tcliente t ON c.idtcliente=t.idtcliente INNER JOIN regiones r ON c.idregiones = r.region_id INNER JOIN comunas n ON c.idcomunas = n.comuna_id  WHERE q.idcontrato = '$idcontrato'";
		    return ejecutarConsulta($sql);
		}
                
                public function contar_clientes($idcontrato){
		    $sql="SELECT COUNT(idcontrato_cliente) AS nclientes FROM contrato_cliente WHERE idcontrato='$idcontrato'";
		    return ejecutarConsultaSimpleFila($sql);
		}
                
                public function editar($idcliente,$rut,$razon_social,$calle, $numero, $oficina, $idregiones, $idprovincias, $idcomunas, $idtcliente){
		    $sql="UPDATE cliente SET rut='$rut', razon_social='$razon_social', calle='$calle', numero='$numero', oficina='$oficina', idregiones='$idregiones', idprovincias='$idprovincias', idcomunas='$idcomunas', idtcliente='$idtcliente', updated_time=CURRENT_TIMESTAMP WHERE idcliente='$idcliente'";
			return ejecutarConsulta($sql);
		}
                
                public function listar_clientes(){
			$sql="SELECT c.idcliente, c.rut, c.razon_social, c.calle, c.numero, c.oficina, r.region_nombre AS region, o.comuna_nombre AS comuna FROM cliente c INNER JOIN regiones r ON c.idregiones = r.region_id INNER JOIN comunas o ON c.idcomunas = o.comuna_id  ORDER BY c.razon_social ASC";
			return ejecutarConsulta($sql);
		}
                
                public function mostrar($idcliente){
		    $sql="SELECT c.razon_social, c.rut, c.calle, c.numero, t.nombre AS tipo, r.region_nombre AS region, p.provincia_nombre AS provincia, w.comuna_nombre AS comuna FROM cliente c INNER JOIN regiones r ON c.idregiones=r.region_id INNER JOIN provincias p ON c.idprovincias=p.provincia_id INNER JOIN comunas w ON c.idcomunas=w.comuna_id INNER JOIN tcliente t ON c.idtcliente = t.idtcliente WHERE c.idcliente = '$idcliente'";
			return ejecutarConsultaSimpleFila($sql);
		}
		
                public function formeditar($idcliente){
		    $sql="SELECT * FROM cliente WHERE idcliente='$idcliente'";
			return ejecutarConsultaSimpleFila($sql);
		}
	}
?>