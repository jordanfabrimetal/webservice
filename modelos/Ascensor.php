<?php 

require_once "../config/conexion.php";
require_once "../config/conexionSap.php";

	Class Ascensor{
		//Constructor para instancias
		public function __construct(){

		}

		public function InsertarIds($idascensor, $codigo, $iduser){
                        $sql="UPDATE ascensor SET codigo= '$codigo', updated_time=CURRENT_TIMESTAMP, updated_user='$iduser' WHERE idascensor='$idascensor'";
			return ejecutarConsulta($sql);
		}
                
                public function insertar($iduser,$idedicon,$idtascensor,$marca,$modelo, $valoruf, $valorclp, $paradas, $capper, $capkg, $velocidad, $pservicio, $gtecnica, $ken, $dcs, $elink){
			$sql="INSERT INTO ascensor (iduser, idedificio_contrato, idtascensor, marca, modelo, ken, paradas, capper, capkg, velocidad, dcs, elink, valoruf, valorclp, pservicio, gtecnica, condicion,  created_user) VALUES ('$iduser', '$idedicon', '$idtascensor','$marca','$modelo', '$ken', '$paradas', '$capper', '$capkg', '$velocidad', '$dcs', '$elink', '$valoruf', '$valorclp', '$pservicio', '$gtecnica', 1,'$iduser')";
			return ejecutarConsulta($sql);
		}


		public function solid_ascid($idcontrato){
			$sql="SELECT a.idascensor, m.nombre as marca, o.nombre as modelo, e.nombre, e.calle, e.numero, e.idedificio, r.region_nombre, r.region_ordinal FROM ascensor a INNER JOIN edificio_contrato w ON a.idedificio_contrato=w.idedificio_contrato INNER JOIN edificio e ON w.idedificio=e.idedificio INNER JOIN regiones r ON e.idregiones = r.region_id INNER JOIN marca m ON a.marca=m.idmarca INNER JOIN modelo o ON a.modelo=o.idmodelo WHERE w.idcontrato='$idcontrato' AND a.codigo IS null";
			return ejecutarConsulta($sql);
		}

		public function solid_edifid($idcontrato){
			$sql="SELECT COUNT(a.idascensor) AS nascensores, e.idedificio FROM ascensor a INNER JOIN edificio_contrato w ON a.idedificio_contrato=w.idedificio_contrato INNER JOIN edificio e ON w.idedificio=e.idedificio WHERE w.idcontrato='$idcontrato' GROUP BY e.idedificio";
			return ejecutarConsulta($sql);
		}
                
                public function contar_ascensores($idcontrato){
		    $sql="SELECT COUNT(idascensor) AS nascensores FROM ascensor a INNER JOIN edificio_contrato q ON a.idedificio_contrato=q.idedificio_contrato WHERE q.idcontrato='$idcontrato'";
		    return ejecutarConsultaSimpleFila($sql);
		}
                
                public function ascensores_contrato($idcontrato){
		    $sql="SELECT a.codigo, m.nombre AS marca, b.nombre AS modelo, a.valoruf, e.nombre AS edificio, r.region_nombre AS region, n.comuna_nombre AS comuna FROM ascensor a INNER JOIN edificio_contrato q ON a.idedificio_contrato=q.idedificio_contrato INNER JOIN edificio e ON q.idedificio = e.idedificio INNER JOIN marca m ON a.marca = m.idmarca INNER JOIN modelo b ON a.modelo = b.idmodelo INNER JOIN regiones r ON e.idregiones = r.region_id INNER JOIN comunas n ON e.idcomunas = n.comuna_id  WHERE q.idcontrato = '$idcontrato'";
		    return ejecutarConsulta($sql);
		}
                
                public function ascensores_edificio($idedificio){
		    $sql="SELECT a.codigo, m.nombre AS marca, b.nombre AS modelo, a.valoruf,c.ncontrato, x.nombre AS tipo FROM ascensor a INNER JOIN edificio_contrato q ON a.idedificio_contrato=q.idedificio_contrato INNER JOIN contrato c ON q.idcontrato=c.idcontrato INNER JOIN edificio e ON q.idedificio = e.idedificio INNER JOIN marca m ON a.marca = m.idmarca INNER JOIN modelo b ON a.modelo = b.idmodelo INNER JOIN tascensor x ON a.idtascensor=x.idtascensor WHERE e.idedificio='$idedificio'";
		    return ejecutarConsulta($sql);
		}
                
                public function ascensores_cliente($idcliente){
		    $sql="SELECT a.codigo, m.nombre AS marca, b.nombre AS modelo, a.valoruf,c.ncontrato, e.nombre AS edificio, x.nombre AS tipo FROM ascensor a INNER JOIN edificio_contrato q ON a.idedificio_contrato=q.idedificio_contrato INNER JOIN contrato c ON q.idcontrato=c.idcontrato INNER JOIN contrato_cliente r ON c.idcontrato = r.idcontrato INNER JOIN edificio e ON q.idedificio = e.idedificio INNER JOIN marca m ON a.marca = m.idmarca INNER JOIN modelo b ON a.modelo = b.idmodelo INNER JOIN tascensor x ON a.idtascensor=x.idtascensor WHERE r.idcliente = '$idcliente'";
		    return ejecutarConsulta($sql);
		}
                
                public function listar(){
		    $sql="SELECT a.idascensor, a.codigo, a.valoruf, e.nombre AS edificio, c.ncontrato AS contrato  FROM ascensor a INNER JOIN edificio e ON a.idedificio=e.idedificio INNER JOIN contrato c ON a.idcontrato = c.idcontrato ORDER BY e.nombre ASC";
		    return ejecutarConsulta($sql);
		}
                
                public function editar($idascensor, $idtascensor, $marca, $modelo, $ken, $pservicio, $gtecnica, $valoruf, $valorclp, $paradas, $capkg, $capper, $velocidad, $dcs, $elink, $iduser){
                    $sql="UPDATE ascensor SET idtascensor='$idtascensor', marca='$marca', modelo='$modelo', ken='$ken', pservicio='$pservicio', gtecnica='$gtecnica', valoruf='$valoruf', valorclp='$valorclp', paradas='$paradas', capkg='$capkg', capper='$capper', velocidad='$velocidad', dcs='$dcs', elink='$elink', updated_time=CURRENT_TIMESTAMP, updated_user='$iduser' WHERE idascensor='$idascensor'";
			return ejecutarConsulta($sql);
                }
                
                public function mostrar($idascensor){
		    $sql="SELECT a.*, e.nombre AS edificio, c.ncontrato AS contrato, i.razon_social AS cliente, n.nombre AS modelo, m.nombre AS marca, v.nombre AS tipo FROM ascensor a INNER JOIN edificio_contrato	w ON a.idedificio_contrato = w.idedificio_contrato INNER JOIN edificio e ON w.idedificio=e.idedificio INNER JOIN contrato c ON w.idcontrato = c.idcontrato INNER JOIN contrato_cliente q ON c.idcontrato=q.idcontrato INNER JOIN cliente i ON q.idcliente = i.idcliente INNER JOIN marca m ON a.marca=m.idmarca INNER JOIN modelo n ON a.modelo=n.idmodelo INNER JOIN tascensor v ON a.idtascensor = v.idtascensor WHERE a.idascensor='$idascensor'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function formeditar($idascensor){
		    $sql="SELECT * FROM ascensor WHERE idascensor='$idascensor'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function guia($codigo){
                        $sql="SELECT a.idascensor, a.codigo , e.nombre AS edificio, e.calle, e.numero, n.nombre AS modelo, m.nombre AS marca, v.nombre AS tipo, w.nombre AS estado, w.id AS idtestado FROM ascensor a INNER JOIN edificio e ON a.idedificio=e.idedificio INNER JOIN marca m ON a.marca=m.idmarca INNER JOIN modelo n ON a.modelo=n.idmodelo INNER JOIN tascensor v ON a.idtascensor = v.idtascensor INNER JOIN testado w ON a.estado=w.id WHERE a.codigo='$codigo'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function UpEstado($idascensor, $estado){
                    $sql="UPDATE ascensor SET estado='$estado'WHERE idascensor='$idascensor'";
			return ejecutarConsulta($sql);
                }
                
                public function SelectAsc(){
                    $sql="SELECT a.codigo, e.nombre FROM ascensor a INNER JOIN edificio e ON a.idedificio=e.idedificio WHERE a.codigo IS NOT NULL";
		    return ejecutarConsulta($sql);
                }

				public function SelectTipoServicio() {
					$query = 'ServiceCallTypesService_GetServiceCallTypeList';
					$result = Query($query);
					$decodedResult = json_decode($result, true);
					$logMessage = json_encode($decodedResult); // Convertir el array a una cadena JSON
				
					error_log("Arreglo de tiposervicio: ".$logMessage);
					return $decodedResult;
				}     
		public function SelectAscensor(){
			$entity = 'ServiceCalls';
			$select = 'ItemCode,InternalSerialNum,CallType,ServiceCallID';
			$filter = "InternalSerialNum ne null and InternalSerialNum ne '' and Status eq -3";
			$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
			$listado = array();
			foreach ($rspta as $val=>$value) {
				switch ($val) {
					case 'value':
						foreach ($value as $data) {
							/* CustomerEquipmentCards */
							$select = 'InternalSerialNum,BuildingFloorRoom,InstallLocation';
							$entity = 'CustomerEquipmentCards';
							$filter = "InternalSerialNum eq '".$data['InternalSerialNum']."' and ItemCode eq '".$data['ItemCode']."'";
							$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
							if(!empty($data['CallType'])){
								/* CallType*/
								$entity = 'ServiceCallTypes';
								$id = $data["CallType"];
								$select = 'Name';
								$tipo = json_decode(ConsultaIDNum($entity,$id,$select), true);
								$nombre = $tipo['Name'];
							}
							foreach ($rspta as $val=>$value) {
								switch ($val) {
									case 'value':
										foreach ($value as $data3) {
											$listado[] =array("ServiceCallID"=>$data["ServiceCallID"],"InternalSerialNum"=>$data3["InternalSerialNum"],"BuildingFloorRoom"=>$data3["InstallLocation"],"CallType"=>$nombre);
										}
									break;
								}
							}
						}
					break;
				}
			}
			return $listado;
		}
  

  		public function SelectEmergencia(){
  			$listado = array();
  			$select = 'InternalSerialNum,BuildingFloorRoom,InstallLocation,U_NX_NCC';
			$entity = 'CustomerEquipmentCards';
			//$filter = "InternalSerialNum eq '".$data['InternalSerialNum']."' and ItemCode eq '".$data['ItemCode']."'";
			$rspta = json_decode(ConsultaEntity($entity,$select), true);
			foreach ($rspta['value'] as $val) {
				$listado[] =array("InternalSerialNum"=>$val["InternalSerialNum"],"BuildingFloorRoom"=>$val["U_NX_NCC"]);
			}
			return $listado;
  		}
  		
  		public function SelectEmergenciaSAP(){
  			$listado = array();
  			$query = "ServiceCalls?\$apply=filter(CallType eq 4 and Status eq -3)/groupby((ItemCode))";
			$rspta = json_decode(Query($query),true);
			foreach ($rspta["value"] as $val) {
				$select = 'U_NX_NCC';
		  		$entity = 'CustomerEquipmentCards';
		  		$filter = "InternalSerialNum eq '".$val["ItemCode"]."'";
		  		$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);

				$listado[] =array("InternalSerialNum"=>$val["ItemCode"],"BuildingFloorRoom"=>$rspta["value"][0]["U_NX_NCC"]);
			}
			return $listado;
  		}

		  public function SelectNormalizacion(){
			$listado = array();
			$select = 'InternalSerialNum,BuildingFloorRoom,InstallLocation,U_NX_NCC';
		  $entity = 'CustomerEquipmentCards';
		  //$filter = "InternalSerialNum eq '".$data['InternalSerialNum']."' and ItemCode eq '".$data['ItemCode']."'";
		  $rspta = json_decode(ConsultaEntity($entity,$select), true);
		  foreach ($rspta['value'] as $val) {
			  $listado[] =array("InternalSerialNum"=>$val["InternalSerialNum"],"BuildingFloorRoom"=>$val["U_NX_NCC"]);
		  }
		  return $listado;
		}


        /*public function SelectVisita(){
  			$listado = array();
  			$select = 'InternalSerialNum,BuildingFloorRoom,InstallLocation';
			$entity = 'CustomerEquipmentCards';
			$filter = "U_NX_SUPERVISOR eq '".$_SESSION['idSAP']."'";
			$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
			foreach ($rspta['value'] as $val) {
				$listado[] =array("InternalSerialNum"=>$val["InternalSerialNum"],"BuildingFloorRoom"=>$val["InstallLocation"]);
			}
			return $listado;
  		}*/
  		
  		public function SelectVisita(){
  			$listado = array();
  			if($_SESSION['idrole'] == 18 || $_SESSION['idrole'] == 7){
	  			$select = 'InternalSerialNum,BuildingFloorRoom,InstallLocation,U_NX_NCC';
				$entity = 'CustomerEquipmentCards';
				$filter = "U_NX_SUPERVISOR eq '".$_SESSION['idSAP']."'";
				$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
				foreach ($rspta['value'] as $val) {
					$listado[] =array("InternalSerialNum"=>$val["InternalSerialNum"],"BuildingFloorRoom"=>$val["U_NX_NCC"]);
				}
  			}else{
  				$query = "\$crossjoin(ServiceCalls,ServiceCallTypes,CustomerEquipmentCards,Items)?\$expand=ServiceCalls(\$select=ServiceCallID,CallType,ItemCode,InternalSerialNum,Subject),ServiceCallTypes(\$select=Name),CustomerEquipmentCards(\$select=InternalSerialNum,BuildingFloorRoom,Street,StreetNo,InstallLocation,U_NX_NCC),Items(\$select=U_NX_TIPEQUIPO,U_NX_PARADAS)&\$filter=ServiceCalls/CallType eq ServiceCallTypes/CallTypeID and ServiceCalls/InternalSerialNum eq CustomerEquipmentCards/InternalSerialNum and ServiceCalls/Status eq -3 and ServiceCalls/CallType eq 17 and ServiceCalls/TechnicianCode eq ".$_SESSION['idSAP']." and ServiceCalls/ItemCode eq CustomerEquipmentCards/ItemCode and CustomerEquipmentCards/InternalSerialNum eq Items/ItemCode&\$orderby=ServiceCalls/ServiceCallID";
        		$rsptaJson = json_decode(Query($query));
				$listado = array();
	        	foreach ($rsptaJson->value as $val) {
					$listado[] =array(
						"ServiceCallID"=>$val->ServiceCalls->ServiceCallID,
						"InternalSerialNum"=>$val->ServiceCalls->InternalSerialNum,
						"BuildingFloorRoom"=>$val->CustomerEquipmentCards->U_NX_NCC,
						"CallType"=>$val->ServiceCallTypes->Name,
						"TipoEquipo"=>$val->Items->U_NX_TIPEQUIPO,
						"Paradas"=>$val->Items->U_NX_PARADAS
					);
				}
  			}
			return $listado;
  		}


		  public function SelectVisitaandroid($idsap, $idrol){
			$listado = array();
			if($idrol == 18 || $idrol == 7){
				$select = 'InternalSerialNum,BuildingFloorRoom,InstallLocation,U_NX_NCC';
			  $entity = 'CustomerEquipmentCards';
			  $filter = "U_NX_SUPERVISOR eq '".$idsap."'";
			  $rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
			  foreach ($rspta['value'] as $val) {
				  $listado[] =array("InternalSerialNum"=>$val["InternalSerialNum"],"BuildingFloorRoom"=>$val["U_NX_NCC"]);
			  }
			}else{
				$query = "\$crossjoin(ServiceCalls,ServiceCallTypes,CustomerEquipmentCards,Items)?\$expand=ServiceCalls(\$select=ServiceCallID,CallType,ItemCode,InternalSerialNum,Subject),ServiceCallTypes(\$select=Name),CustomerEquipmentCards(\$select=InternalSerialNum,BuildingFloorRoom,Street,StreetNo,InstallLocation,U_NX_NCC),Items(\$select=U_NX_TIPEQUIPO,U_NX_PARADAS)&\$filter=ServiceCalls/CallType eq ServiceCallTypes/CallTypeID and ServiceCalls/InternalSerialNum eq CustomerEquipmentCards/InternalSerialNum and ServiceCalls/Status eq -3 and ServiceCalls/CallType eq 17 and ServiceCalls/TechnicianCode eq ".$idsap." and ServiceCalls/ItemCode eq CustomerEquipmentCards/ItemCode and CustomerEquipmentCards/InternalSerialNum eq Items/ItemCode&\$orderby=ServiceCalls/ServiceCallID";
			  $rsptaJson = json_decode(Query($query));
			  $listado = array();
			  foreach ($rsptaJson->value as $val) {
				  $listado[] =array(
					  "ServiceCallID"=>$val->ServiceCalls->ServiceCallID,
					  "InternalSerialNum"=>$val->ServiceCalls->InternalSerialNum,
					  "BuildingFloorRoom"=>$val->CustomerEquipmentCards->U_NX_NCC,
					  "CallType"=>$val->ServiceCallTypes->Name,
					  "TipoEquipo"=>$val->Items->U_NX_TIPEQUIPO,
					  "Paradas"=>$val->Items->U_NX_PARADAS
				  );
			  }
			}
		  return $listado;
		}
		
		public function SelectAuditoriaTecnicaSAP(){
			$listado = array();
		  $select = 'InternalSerialNum,BuildingFloorRoom,InstallLocation,U_NX_NCC';
		  $entity = 'CustomerEquipmentCards';
		  //$filter = "InternalSerialNum eq '".$data['InternalSerialNum']."' and ItemCode eq '".$data['ItemCode']."'";
		  $rspta = json_decode(ConsultaEntity($entity,$select), true);
		  foreach ($rspta['value'] as $val) {
			  $listado[] =array("InternalSerialNum"=>$val["InternalSerialNum"],"BuildingFloorRoom"=>$val["U_NX_NCC"]);
		  }
		  return $listado;
		}
  		
  		public function SelectAscensorServicio($idservicio){
  			/* verificamos la cantidad */
  			//$sql = "ServiceCalls/\$count?\$filter=InternalSerialNum ne null and InternalSerialNum ne '' and Status eq -3 and CallType eq ".$idservicio;
		    //$cant = Query($sql);
			$idsap = $_SESSION['idSAP'];
		    $query = "\$crossjoin(ServiceCalls,ServiceCallTypes,CustomerEquipmentCards,Items)?\$expand=ServiceCalls(\$select=ServiceCallID,CallType,ItemCode,InternalSerialNum,Subject),ServiceCallTypes(\$select=Name),CustomerEquipmentCards(\$select=InternalSerialNum,BuildingFloorRoom,Street,StreetNo,InstallLocation,U_NX_NCC),Items(\$select=U_NX_TIPEQUIPO,U_NX_PARADAS)&\$filter=ServiceCalls/CallType eq ServiceCallTypes/CallTypeID and ServiceCalls/InternalSerialNum eq CustomerEquipmentCards/InternalSerialNum and ServiceCalls/Status eq -3 and ServiceCalls/CallType eq ".$idservicio." and ServiceCalls/TechnicianCode eq ".$_SESSION['idSAP']." and ServiceCalls/ItemCode eq CustomerEquipmentCards/ItemCode and CustomerEquipmentCards/InternalSerialNum eq Items/ItemCode&\$orderby=ServiceCalls/ServiceCallID";
        	$rsptaJson = json_decode(Query($query));
        	//echo '<pre>';print_r($rsptaJson);echo '</pre>';
			$listado = array();
        	foreach ($rsptaJson->value as $val) {
				$listado[] =array(
					"ServiceCallID"=>$val->ServiceCalls->ServiceCallID,
					"InternalSerialNum"=>$val->ServiceCalls->InternalSerialNum,
					"BuildingFloorRoom"=>$val->CustomerEquipmentCards->U_NX_NCC,
					"CallType"=>$val->ServiceCallTypes->Name,
					"TipoEquipo"=>$val->Items->U_NX_TIPEQUIPO,
					"Paradas"=>$val->Items->U_NX_PARADAS
				);
			}

  			/*$entity = 'ServiceCalls';
			$select = 'ItemCode,InternalSerialNum,CallType,ServiceCallID';
			$filter = "InternalSerialNum ne null and InternalSerialNum ne '' and Status eq -3 and CallType eq ".$idservicio;
			$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
			$listado = array();
			foreach ($rspta as $val=>$value) {
				switch ($val) {
					case 'value':
						foreach ($value as $data) {
							$select = 'InternalSerialNum,BuildingFloorRoom,InstallLocation';
							$entity = 'CustomerEquipmentCards';
							$filter = "InternalSerialNum eq '".$data['InternalSerialNum']."' and ItemCode eq '".$data['ItemCode']."'";
							$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
							if(!empty($data['CallType'])){
								$entity = 'ServiceCallTypes';
								$id = $data["CallType"];
								$select = 'Name';
								$tipo = json_decode(ConsultaIDNum($entity,$id,$select), true);
								$nombre = $tipo['Name'];
							}
							foreach ($rspta as $val=>$value) {
								switch ($val) {
									case 'value':
										foreach ($value as $data3) {
											$listado[] =array("ServiceCallID"=>$data["ServiceCallID"],"InternalSerialNum"=>$data3["InternalSerialNum"],"BuildingFloorRoom"=>$data3["InstallLocation"],"CallType"=>$nombre);
										}
									break;
								}
							}
						}
					break;
				}
			}*/
			return $listado;
  		}

  		public function SelectAscensorServicioSAP($idservicio ,$idsap){
			/* verificamos la cantidad */
			//$sql = "ServiceCalls/\$count?\$filter=InternalSerialNum ne null and InternalSerialNum ne '' and Status eq -3 and CallType eq ".$idservicio;
		  //$cant = Query($sql);
		  $query = "\$crossjoin(ServiceCalls,ServiceCallTypes,CustomerEquipmentCards,Items)?\$expand=ServiceCalls(\$select=ServiceCallID,CallType,ItemCode,InternalSerialNum,Subject),ServiceCallTypes(\$select=Name),CustomerEquipmentCards(\$select=InternalSerialNum,BuildingFloorRoom,Street,StreetNo,InstallLocation,U_NX_NCC),Items(\$select=U_NX_TIPEQUIPO,U_NX_PARADAS)&\$filter=ServiceCalls/CallType eq ServiceCallTypes/CallTypeID and ServiceCalls/InternalSerialNum eq CustomerEquipmentCards/InternalSerialNum and ServiceCalls/Status eq -3 and ServiceCalls/CallType eq ".$idservicio." and ServiceCalls/TechnicianCode eq ".$idsap." and ServiceCalls/ItemCode eq CustomerEquipmentCards/ItemCode and CustomerEquipmentCards/InternalSerialNum eq Items/ItemCode&\$orderby=ServiceCalls/ServiceCallID";
		  $rsptaJson = json_decode(Query($query));
		  //echo '<pre>';print_r($rsptaJson);echo '</pre>';
		  $listado = array();
		  foreach ($rsptaJson->value as $val) {
			  $listado[] =array(
				  "ServiceCallID"=>$val->ServiceCalls->ServiceCallID,
				  "InternalSerialNum"=>$val->ServiceCalls->InternalSerialNum,
				  "BuildingFloorRoom"=>$val->CustomerEquipmentCards->U_NX_NCC,
				  "CallType"=>$val->ServiceCallTypes->Name,
				  "TipoEquipo"=>$val->Items->U_NX_TIPEQUIPO,
				  "Paradas"=>$val->Items->U_NX_PARADAS
			  );
		  }

			/*$entity = 'ServiceCalls';
		  $select = 'ItemCode,InternalSerialNum,CallType,ServiceCallID';
		  $filter = "InternalSerialNum ne null and InternalSerialNum ne '' and Status eq -3 and CallType eq ".$idservicio;
		  $rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
		  $listado = array();
		  foreach ($rspta as $val=>$value) {
			  switch ($val) {
				  case 'value':
					  foreach ($value as $data) {
						  $select = 'InternalSerialNum,BuildingFloorRoom,InstallLocation';
						  $entity = 'CustomerEquipmentCards';
						  $filter = "InternalSerialNum eq '".$data['InternalSerialNum']."' and ItemCode eq '".$data['ItemCode']."'";
						  $rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
						  if(!empty($data['CallType'])){
							  $entity = 'ServiceCallTypes';
							  $id = $data["CallType"];
							  $select = 'Name';
							  $tipo = json_decode(ConsultaIDNum($entity,$id,$select), true);
							  $nombre = $tipo['Name'];
						  }
						  foreach ($rspta as $val=>$value) {
							  switch ($val) {
								  case 'value':
									  foreach ($value as $data3) {
										  $listado[] =array("ServiceCallID"=>$data["ServiceCallID"],"InternalSerialNum"=>$data3["InternalSerialNum"],"BuildingFloorRoom"=>$data3["InstallLocation"],"CallType"=>$nombre);
									  }
								  break;
							  }
						  }
					  }
				  break;
			  }
		  }*/
		  return $listado;
		}



		public function SelectAscensorQUERY(){
			$query = '$crossjoin(ServiceCalls,ServiceCallTypes,CustomerEquipmentCards,Items,Manufacturers)?$expand=ServiceCalls($select=ServiceCallID,CallType,ItemCode,InternalSerialNum,Subject),ServiceCallTypes($select=Name),CustomerEquipmentCards($select=InternalSerialNum,BuildingFloorRoom,Street,StreetNo,InstallLocation),Items($select=Manufacturer,ItemCode,ItemName),Manufacturers($select=ManufacturerName)&$filter=ServiceCalls/CallType eq ServiceCallTypes/CallTypeID and ServiceCalls/InternalSerialNum eq CustomerEquipmentCards/InternalSerialNum and ServiceCalls/ItemCode eq Items/ItemCode and Items/Manufacturer eq Manufacturers/Code and CustomerEquipmentCards/ItemCode eq Items/ItemCode';
			$rspta = json_decode(Query($query), true);

			$listado = $rspta;

			return $listado;
		}

        public function MostrarInformacion($id){
        	/* Inicio ServicaCalls */
        	$entity = 'ServiceCalls';
        	$select = 'ServiceCallID,ItemCode,InternalSerialNum,CallType,Subject,CustomerCode';
        	$servcall = json_decode(ConsultaIDNum($entity,$id,$select), true);
			/* Fin ServicaCalls */
			$customerCode = $servcall['CustomerCode'];
			/* Inicio CallType */
        	if(!empty($servcall['CallType'])){
				$CallType = '';
				$entity = 'ServiceCallTypes';
				$id = $servcall["CallType"];
				$select = 'Name';
				$tipo = json_decode(ConsultaIDNum($entity,$id,$select), true);
			}
			/* Fin CallType */

			/* Inicio CustomerEquipmentCards */
			$select = 'EquipmentCardNum,InternalSerialNum,BuildingFloorRoom,Street,StreetNo,InstallLocation,U_NX_ESTADOFM,U_NX_NOMENCLATURACL,U_NX_GarantiaF,U_NX_NCC';
			$entity = 'CustomerEquipmentCards';
			$filter = "InternalSerialNum eq '".$servcall['InternalSerialNum']."' and ItemCode eq '".$servcall['ItemCode']."'";
			$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
			foreach ($rspta as $val=>$value) {
				switch ($val) {
					case 'value':
						foreach ($value as $data) {
							$nombre = $data['U_NX_NCC'];
							$direccion = $data['Street'].' '.$data['StreetNo'];
							$status = '';
							$nomenclatura = $data['U_NX_NOMENCLATURACL'];
							$garantiaF = $data['U_NX_GarantiaF'];
							$equipmentcardnum = $data['EquipmentCardNum'];
							if(!empty($data['U_NX_ESTADOFM'])){
								$entityEstado = 'U_NX_ESTADOS_FM';
								$idEstado = $data['U_NX_ESTADOFM'];

								$selectEstado = 'Name';
								$retornaEstado = json_decode(ConsultaIDLet($entityEstado,$idEstado,$selectEstado),true);
								$status = $retornaEstado['Name'];
							}

							if(!empty($servcall['ItemCode'])){
								/* Items */
								$entity = 'Items';
					        	$select = 'Manufacturer,ItemName,U_NX_MODELO,U_NX_TIPEQUIPO';
					        	$filter = "ItemCode eq '".$servcall['ItemCode']."'";
					        	$datamanu = json_decode(ConsultaEntity($entity,$select,$filter), true);
					        	foreach ($datamanu as $val=>$value) {
									switch ($val) {
										case 'value':
											foreach($value as $val){
												if(!empty($val['Manufacturer'])){
													$Manufacturer = '';
													$entity = 'Manufacturers';
													$id = $val['Manufacturer'];
													$select = 'ManufacturerName';
													$manufacturer = json_decode(ConsultaIDNum($entity,$id,$select), true);
												}
												$ItemName = $val['ItemName'];
												$modelo = $val['U_NX_MODELO'];
												$tipoequipo = $val['U_NX_TIPEQUIPO'];
											}
										break;
									}
								}
							}
						}
					break;
				}
			}
			/* Fin CustomerEquipmentCards */
        	return json_encode(
    			array(
    				"CustomerCode"=>$customerCode,
    				"ServiceCallID"=>$servcall["ServiceCallID"],
    				"ItemCode"=>$servcall['ItemCode'],
    				"Manufacturer"=>$manufacturer['ManufacturerName'],
    				"ItemName"=>$ItemName,
    				"edificio"=>$nombre,
    				"direccion"=>$direccion,
    				"CallType"=>$tipo['Name'],
    				"status"=>$status,
    				"Subject"=>$servcall['Subject'],
    				"InternalSerialNum"=>$servcall['InternalSerialNum'],
    				"modelo"=>$modelo,
	    			"tipoequipo"=>$tipoequipo,
    				"nomenclatura"=>$nomenclatura,
    				"garantiaF"=>$garantiaF,
    				"equipmentcardnum"=>$equipmentcardnum
    			)
    		);
        }


        public function MostrarInformacionEmergencia($fm){
        	/* ver si existe alguno abierto o antendido*/
        	$sql = "ServiceCalls/\$count?\$filter=Status eq -3 and InternalSerialNum eq '".$fm."' and CallType eq 4";
        	$contador = Query($sql);
        	if($contador >= 1){
	        	/* Inicio ServicaCalls */
	        	$entity = 'ServiceCalls';
	        	$select = 'ServiceCallID,ItemCode,InternalSerialNum,CallType,Subject,CustomerCode';
	        	$filter = "Status eq -3 and InternalSerialNum eq '".$fm."' and CallType eq 4";
	        	$servcall = json_decode(ConsultaEntity($entity,$select,$filter), true);
	        	$servcall = $servcall['value'][0];
	        	//print_r($servcall);die;
				/* Fin ServicaCalls */
				$customerCode = $servcall['CustomerCode'];
				/* Inicio CallType */
	        	if(!empty($servcall['CallType'])){
					$CallType = '';
					$entity = 'ServiceCallTypes';
					$id = $servcall["CallType"];
					$select = 'Name';
					$tipo = json_decode(ConsultaIDNum($entity,$id,$select), true);
				}
				/* Fin CallType */

				/* Inicio CustomerEquipmentCards */
				$select = 'EquipmentCardNum,InternalSerialNum,BuildingFloorRoom,Street,StreetNo,InstallLocation,U_NX_ESTADOFM,U_NX_NOMENCLATURACL,U_NX_GarantiaF,U_NX_NCC';
				$entity = 'CustomerEquipmentCards';
				$filter = "InternalSerialNum eq '".$servcall['InternalSerialNum']."' and ItemCode eq '".$servcall['ItemCode']."'";
				$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
				foreach ($rspta as $val=>$value) {
					switch ($val) {
						case 'value':
							foreach ($value as $data) {
								$nombre = $data['U_NX_NCC'];
								$direccion = $data['Street'].' '.$data['StreetNo'];
								$nomenclatura = $data['U_NX_NOMENCLATURACL'];
								$garantiaF = $data['U_NX_GarantiaF'];
								$equipmentcardnum = $data['EquipmentCardNum'];
								$status = '';
								if(!empty($data['U_NX_ESTADOFM'])){
									$entityEstado = 'U_NX_ESTADOS_FM';
									$idEstado = $data['U_NX_ESTADOFM'];
									$selectEstado = 'Name';
									$retornaEstado = json_decode(ConsultaIDLet($entityEstado,$idEstado,$selectEstado),true);
									$status = (!empty($retornaEstado['Name'])) ? $retornaEstado['Name'] : "";
								}

								if(!empty($servcall['ItemCode'])){
									/* Items */
									$entity = 'Items';
						        	$select = 'Manufacturer,ItemName,U_NX_MODELO,U_NX_TIPEQUIPO';
						        	$filter = "ItemCode eq '".$servcall['ItemCode']."'";
						        	$datamanu = json_decode(ConsultaEntity($entity,$select,$filter), true);
						        	foreach ($datamanu as $val=>$value) {
										switch ($val) {
											case 'value':
												foreach($value as $val){
													if(!empty($val['Manufacturer'])){
														$Manufacturer = '';
														$entity = 'Manufacturers';
														$id = $val['Manufacturer'];
														$select = 'ManufacturerName';
														$manufacturer = json_decode(ConsultaIDNum($entity,$id,$select), true);
													}
													$ItemName = $val['ItemName'];
													$modelo = $val['U_NX_MODELO'];
													$tipoequipo = $val['U_NX_TIPEQUIPO'];
												}
											break;
										}
									}
								}
							}
						break;
					}
				}
				/* Fin CustomerEquipmentCards */
	        	return json_encode(
	    			array(
	    				"CustomerCode"=>$customerCode,
	    				"ServiceCallID"=>$servcall["ServiceCallID"],
	    				"ItemCode"=>$servcall['ItemCode'],
	    				"Manufacturer"=>$manufacturer['ManufacturerName'],
	    				"ItemName"=>$ItemName,
	    				"edificio"=>$nombre,
	    				"direccion"=>$direccion,
	    				"CallType"=>$tipo['Name'],
	    				"status"=>$status,
	    				"Subject"=>$servcall['Subject'],
    					"InternalSerialNum"=>$servcall['InternalSerialNum'],
    					"modelo"=>$modelo,
	    				"tipoequipo"=>$tipoequipo,
	    				"nomenclatura"=>$nomenclatura,
	    				"garantiaF"=>$garantiaF,
	    				"equipmentcardnum"=>$equipmentcardnum
	    			)
	    		);
        	}else{
        		$select = 'EquipmentCardNum,CustomerCode,InternalSerialNum,ItemCode,BuildingFloorRoom,Street,StreetNo,InstallLocation,U_NX_ESTADOFM,U_NX_NOMENCLATURACL,U_NX_GarantiaF,U_NX_NCC';
				$entity = 'CustomerEquipmentCards';
				$filter = "InternalSerialNum eq '".$fm."'";
				$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
				foreach ($rspta['value'] as $val) {
					
					$nombre = $val['U_NX_NCC'];
					$direccion = $val['Street'].' '.$val['StreetNo'];
					$nomenclatura = $val['U_NX_NOMENCLATURACL'];
					$garantiaF = $val['U_NX_GarantiaF'];
					$equipmentcardnum = $val['EquipmentCardNum'];
					$ItemCode = $val['ItemCode'];
					$customerCode = $val['CustomerCode'];
					$InternalSerialNum = $val['InternalSerialNum'];
					if(!empty($val['U_NX_ESTADOFM'])){
						$entityEstado = 'U_NX_ESTADOS_FM';
						$idEstado = $val['U_NX_ESTADOFM'];
						$selectEstado = 'Name';
						$retornaEstado = json_decode(ConsultaIDLet($entityEstado,$idEstado,$selectEstado),true);
					}
					$status = (!empty($retornaEstado['Name'])) ? $retornaEstado['Name'] : "";

					if(!empty($val['ItemCode'])){
						/* Items */
						$entity = 'Items';
			        	$select = 'Manufacturer,ItemName,U_NX_MODELO,U_NX_TIPEQUIPO';
			        	$filter = "ItemCode eq '".$val['ItemCode']."'";
			        	$datamanu = json_decode(ConsultaEntity($entity,$select,$filter), true);
			        	foreach ($datamanu['value'] as $value) {
			        		if(!empty($value['Manufacturer'])){
								$Manufacturer = '';
								$entity = 'Manufacturers';
								$id = $value['Manufacturer'];
								$select = 'ManufacturerName';
								$manufacturer = json_decode(ConsultaIDNum($entity,$id,$select), true);
							}
							$ItemName = $value['ItemName'];
							$modelo = $value['U_NX_MODELO'];
							$tipoequipo = $value['U_NX_TIPEQUIPO'];
						}
					}
				}
				/* Fin CustomerEquipmentCards */
	        	return json_encode(
	    			array(
	    				"ServiceCallID"=>"",
	    				"CustomerCode"=>$customerCode,
	    				"ItemCode"=>$ItemCode,
	    				"Manufacturer"=>$manufacturer['ManufacturerName'],
	    				"ItemName"=>$ItemName,
	    				"edificio"=>$nombre,
	    				"direccion"=>$direccion,
	    				"CallType"=>"EMERGENCIA",
	    				"CallTypeID"=>4,
	    				"status"=>$status,
	    				"Subject"=>"Emergencia generada por técnico de turno",
	    				"InternalSerialNum"=>$InternalSerialNum,
	    				"modelo"=>$modelo,
	    				"tipoequipo"=>$tipoequipo,
	    				"nomenclatura"=>$nomenclatura,
	    				"garantiaF"=>$garantiaF,
	    				"equipmentcardnum"=>$equipmentcardnum
	    			)
	    		);
        	}

        }

        public function MostrarInformacionVisita($fm){
        	/* ver si existe alguno abierto o antendido*/
        	$sql = "ServiceCalls/\$count?\$filter=Status eq -3 and InternalSerialNum eq '".$fm."' and CallType eq 17";
        	$contador = Query($sql);
        	if($contador >= 1){
	        	/* Inicio ServicaCalls */
	        	$entity = 'ServiceCalls';
	        	$select = 'ServiceCallID,ItemCode,InternalSerialNum,CallType,Subject,CustomerCode';
	        	$filter = "Status eq -3 and InternalSerialNum eq '".$fm."' and CallType eq 17";
	        	$servcall = json_decode(ConsultaEntity($entity,$select,$filter), true);
	        	$servcall = $servcall['value'][0];
	        	//print_r($servcall);die;
				/* Fin ServicaCalls */
				$customerCode = $servcall['CustomerCode'];
				/* Inicio CallType */
	        	if(!empty($servcall['CallType'])){
					$CallType = '';
					$entity = 'ServiceCallTypes';
					$id = $servcall["CallType"];
					$select = 'Name';
					$tipo = json_decode(ConsultaIDNum($entity,$id,$select), true);
				}
				/* Fin CallType */

				/* Inicio CustomerEquipmentCards */
				$select = 'EquipmentCardNum,InternalSerialNum,BuildingFloorRoom,Street,StreetNo,InstallLocation,U_NX_ESTADOFM,U_NX_NOMENCLATURACL,U_NX_GarantiaF';
				$entity = 'CustomerEquipmentCards';
				$filter = "InternalSerialNum eq '".$servcall['InternalSerialNum']."' and ItemCode eq '".$servcall['ItemCode']."'";
				$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
				foreach ($rspta as $val=>$value) {
					switch ($val) {
						case 'value':
							foreach ($value as $data) {
								$nombre = $data['InstallLocation'];
								$direccion = $data['Street'].' '.$data['StreetNo'];
								$nomenclatura = $data['U_NX_NOMENCLATURACL'];
								$garantiaF = $data['U_NX_GarantiaF'];
								$equipmentcardnum = $data['EquipmentCardNum'];
								$status = '';
								if(!empty($data['U_NX_ESTADOFM'])){
									$entityEstado = 'U_NX_ESTADOS_FM';
									$idEstado = $data['U_NX_ESTADOFM'];
									$selectEstado = 'Name';
									$retornaEstado = json_decode(ConsultaIDLet($entityEstado,$idEstado,$selectEstado),true);
									$status = (!empty($retornaEstado['Name'])) ? $retornaEstado['Name'] : "";
								}

								if(!empty($servcall['ItemCode'])){
									/* Items */
									$entity = 'Items';
						        	$select = 'Manufacturer,ItemName,U_NX_MODELO,U_NX_TIPEQUIPO';
						        	$filter = "ItemCode eq '".$servcall['ItemCode']."'";
						        	$datamanu = json_decode(ConsultaEntity($entity,$select,$filter), true);
						        	foreach ($datamanu as $val=>$value) {
										switch ($val) {
											case 'value':
												foreach($value as $val){
													if(!empty($val['Manufacturer'])){
														$Manufacturer = '';
														$entity = 'Manufacturers';
														$id = $val['Manufacturer'];
														$select = 'ManufacturerName';
														$manufacturer = json_decode(ConsultaIDNum($entity,$id,$select), true);
													}
													$ItemName = $val['ItemName'];
													$modelo = $val['U_NX_MODELO'];
													$tipoequipo = $val['U_NX_TIPEQUIPO'];
												}
											break;
										}
									}
								}
							}
						break;
					}
				}
				/* Fin CustomerEquipmentCards */
	        	return json_encode(
	    			array(
	    				"CustomerCode"=>$customerCode,
	    				"ServiceCallID"=>$servcall["ServiceCallID"],
	    				"ItemCode"=>$servcall['ItemCode'],
	    				"Manufacturer"=>$manufacturer['ManufacturerName'],
	    				"ItemName"=>$ItemName,
	    				"edificio"=>$nombre,
	    				"direccion"=>$direccion,
	    				"CallType"=>$tipo['Name'],
	    				"status"=>$status,
	    				"Subject"=>$servcall['Subject'],
    					"InternalSerialNum"=>$servcall['InternalSerialNum'],
    					"modelo"=>$modelo,
	    				"tipoequipo"=>$tipoequipo,
	    				"nomenclatura"=>$nomenclatura,
	    				"garantiaF"=>$garantiaF,
	    				"equipmentcardnum"=>$equipmentcardnum
	    			)
	    		);
        	}else{
        		$select = 'EquipmentCardNum,CustomerCode,InternalSerialNum,ItemCode,BuildingFloorRoom,Street,StreetNo,InstallLocation,U_NX_ESTADOFM,U_NX_NOMENCLATURACL,U_NX_GarantiaF';
				$entity = 'CustomerEquipmentCards';
				$filter = "InternalSerialNum eq '".$fm."'";
				$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
				foreach ($rspta['value'] as $val) {
					
					$nombre = $val['InstallLocation'];
					$direccion = $val['Street'].' '.$val['StreetNo'];
					$nomenclatura = $val['U_NX_NOMENCLATURACL'];
					$garantiaF = $val['U_NX_GarantiaF'];
					$equipmentcardnum = $val['EquipmentCardNum'];
					$ItemCode = $val['ItemCode'];
					$customerCode = $val['CustomerCode'];
					$InternalSerialNum = $val['InternalSerialNum'];
					if(!empty($val['U_NX_ESTADOFM'])){
						$entityEstado = 'U_NX_ESTADOS_FM';
						$idEstado = $val['U_NX_ESTADOFM'];
						$selectEstado = 'Name';
						$retornaEstado = json_decode(ConsultaIDLet($entityEstado,$idEstado,$selectEstado),true);
					}
					$status = (!empty($retornaEstado['Name'])) ? $retornaEstado['Name'] : "";

					if(!empty($val['ItemCode'])){
						/* Items */
						$entity = 'Items';
			        	$select = 'Manufacturer,ItemName,U_NX_MODELO,U_NX_TIPEQUIPO';
			        	$filter = "ItemCode eq '".$val['ItemCode']."'";
			        	$datamanu = json_decode(ConsultaEntity($entity,$select,$filter), true);
			        	foreach ($datamanu['value'] as $value) {
			        		if(!empty($value['Manufacturer'])){
								$Manufacturer = '';
								$entity = 'Manufacturers';
								$id = $value['Manufacturer'];
								$select = 'ManufacturerName';
								$manufacturer = json_decode(ConsultaIDNum($entity,$id,$select), true);
							}
							$ItemName = $value['ItemName'];
							$modelo = $value['U_NX_MODELO'];
							$tipoequipo = $value['U_NX_TIPEQUIPO'];
						}
					}
				}
				/* Fin CustomerEquipmentCards */
	        	return json_encode(
	    			array(
	    				"ServiceCallID"=>"",
	    				"CustomerCode"=>$customerCode,
	    				"ItemCode"=>$ItemCode,
	    				"Manufacturer"=>$manufacturer['ManufacturerName'],
	    				"ItemName"=>$ItemName,
	    				"edificio"=>$nombre,
	    				"direccion"=>$direccion,
	    				"CallType"=>"VISITA",
	    				"CallTypeID"=>17,
	    				"status"=>$status,
	    				"Subject"=>"Visita generada por Integración",
	    				"InternalSerialNum"=>$InternalSerialNum,
	    				"modelo"=>$modelo,
	    				"tipoequipo"=>$tipoequipo,
	    				"nomenclatura"=>$nomenclatura,
	    				"garantiaF"=>$garantiaF,
	    				"equipmentcardnum"=>$equipmentcardnum
	    			)
	    		);
        	}

        }

		public function MostrarInformacionNormalizacion($fm){
			error_log("fm dice ".$fm);
        	/* ver si existe alguno abierto o antendido*/
        	$sql = "ServiceCalls/\$count?\$filter=Status eq -3 and InternalSerialNum eq '".$fm."' and CallType eq 5";
        	$contador = Query($sql);
        	if($contador >= 1){
				error_log("en 1");
	        	/* Inicio ServicaCalls */
	        	$entity = 'ServiceCalls';
	        	$select = 'ServiceCallID,ItemCode,InternalSerialNum,CallType,Subject,CustomerCode';
	        	$filter = "Status eq -3 and InternalSerialNum eq '".$fm."' and CallType eq 5";
	        	$servcall = json_decode(ConsultaEntity($entity,$select,$filter), true);
	        	$servcall = $servcall['value'][0];
	        	//print_r($servcall);die;
				/* Fin ServicaCalls */
				$customerCode = $servcall['CustomerCode'];
				/* Inicio CallType */
	        	if(!empty($servcall['CallType'])){
					$CallType = '';
					$entity = 'ServiceCallTypes';
					$id = $servcall["CallType"];
					$select = 'Name';
					$tipo = json_decode(ConsultaIDNum($entity,$id,$select), true);
				}
				/* Fin CallType */

				/* Inicio CustomerEquipmentCards */
				$select = 'EquipmentCardNum,InternalSerialNum,BuildingFloorRoom,Street,StreetNo,InstallLocation,U_NX_ESTADOFM,U_NX_NOMENCLATURACL,U_NX_GarantiaF';
				$entity = 'CustomerEquipmentCards';
				$filter = "InternalSerialNum eq '".$servcall['InternalSerialNum']."' and ItemCode eq '".$servcall['ItemCode']."'";
				$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
				foreach ($rspta as $val=>$value) {
					switch ($val) {
						case 'value':
							foreach ($value as $data) {
								$nombre = $data['InstallLocation'];
								$direccion = $data['Street'].' '.$data['StreetNo'];
								$nomenclatura = $data['U_NX_NOMENCLATURACL'];
								$garantiaF = $data['U_NX_GarantiaF'];
								$equipmentcardnum = $data['EquipmentCardNum'];
								$status = '';
								if(!empty($data['U_NX_ESTADOFM'])){
									$entityEstado = 'U_NX_ESTADOS_FM';
									$idEstado = $data['U_NX_ESTADOFM'];
									$selectEstado = 'Name';
									$retornaEstado = json_decode(ConsultaIDLet($entityEstado,$idEstado,$selectEstado),true);
									$status = (!empty($retornaEstado['Name'])) ? $retornaEstado['Name'] : "";
								}

								if(!empty($servcall['ItemCode'])){
									/* Items */
									$entity = 'Items';
						        	$select = 'Manufacturer,ItemName,U_NX_MODELO,U_NX_TIPEQUIPO';
						        	$filter = "ItemCode eq '".$servcall['ItemCode']."'";
						        	$datamanu = json_decode(ConsultaEntity($entity,$select,$filter), true);
						        	foreach ($datamanu as $val=>$value) {
										switch ($val) {
											case 'value':
												foreach($value as $val){
													if(!empty($val['Manufacturer'])){
														$Manufacturer = '';
														$entity = 'Manufacturers';
														$id = $val['Manufacturer'];
														$select = 'ManufacturerName';
														$manufacturer = json_decode(ConsultaIDNum($entity,$id,$select), true);
													}
													$ItemName = $val['ItemName'];
													$modelo = $val['U_NX_MODELO'];
													$tipoequipo = $val['U_NX_TIPEQUIPO'];
												}
											break;
										}
									}
								}
							}
						break;
					}
				}
				/* Fin CustomerEquipmentCards */
	        	return json_encode(
	    			array(
	    				"CustomerCode"=>$customerCode,
	    				"ServiceCallID"=>$servcall["ServiceCallID"],
	    				"ItemCode"=>$servcall['ItemCode'],
	    				"Manufacturer"=>$manufacturer['ManufacturerName'],
	    				"ItemName"=>$ItemName,
	    				"edificio"=>$nombre,
	    				"direccion"=>$direccion,
	    				"CallType"=>$tipo['Name'],
	    				"status"=>$status,
	    				"Subject"=>$servcall['Subject'],
    					"InternalSerialNum"=>$servcall['InternalSerialNum'],
    					"modelo"=>$modelo,
	    				"tipoequipo"=>$tipoequipo,
	    				"nomenclatura"=>$nomenclatura,
	    				"garantiaF"=>$garantiaF,
	    				"equipmentcardnum"=>$equipmentcardnum
	    			)
	    		);
        	}else{
				error_log("en 2");
        		$select = 'EquipmentCardNum,CustomerCode,InternalSerialNum,ItemCode,BuildingFloorRoom,Street,StreetNo,InstallLocation,U_NX_ESTADOFM,U_NX_NOMENCLATURACL,U_NX_GarantiaF';
				$entity = 'CustomerEquipmentCards';
				$filter = "InternalSerialNum eq '".$fm."'";
				$rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
				error_log("rspta44 ".json_encode($rspta));
				foreach ($rspta['value'] as $val) {
					
					$nombre = $val['InstallLocation'];
					$direccion = $val['Street'].' '.$val['StreetNo'];
					$nomenclatura = $val['U_NX_NOMENCLATURACL'];
					$garantiaF = $val['U_NX_GarantiaF'];
					$equipmentcardnum = $val['EquipmentCardNum'];
					$ItemCode = $val['ItemCode'];
					$customerCode = $val['CustomerCode'];
					$InternalSerialNum = $val['InternalSerialNum'];
					if(!empty($val['U_NX_ESTADOFM'])){
						$entityEstado = 'U_NX_ESTADOS_FM';
						$idEstado = $val['U_NX_ESTADOFM'];
						$selectEstado = 'Name';
						$retornaEstado = json_decode(ConsultaIDLet($entityEstado,$idEstado,$selectEstado),true);
					}
					$status = (!empty($retornaEstado['Name'])) ? $retornaEstado['Name'] : "";

					if(!empty($val['ItemCode'])){
						/* Items */
						$entity = 'Items';
			        	$select = 'Manufacturer,ItemName,U_NX_MODELO,U_NX_TIPEQUIPO';
			        	$filter = "ItemCode eq '".$val['ItemCode']."'";
			        	$datamanu = json_decode(ConsultaEntity($entity,$select,$filter), true);
			        	foreach ($datamanu['value'] as $value) {
			        		if(!empty($value['Manufacturer'])){
								$Manufacturer = '';
								$entity = 'Manufacturers';
								$id = $value['Manufacturer'];
								$select = 'ManufacturerName';
								$manufacturer = json_decode(ConsultaIDNum($entity,$id,$select), true);
							}
							$ItemName = $value['ItemName'];
							$modelo = $value['U_NX_MODELO'];
							$tipoequipo = $value['U_NX_TIPEQUIPO'];
						}
					}
				}
				/* Fin CustomerEquipmentCards */
	        	return json_encode(
	    			array(
	    				"ServiceCallID"=>"",
	    				"CustomerCode"=>$customerCode,
	    				"ItemCode"=>$ItemCode,
	    				"Manufacturer"=>$manufacturer['ManufacturerName'],
	    				"ItemName"=>$ItemName,
	    				"edificio"=>$nombre,
	    				"direccion"=>$direccion,
	    				"CallType"=>"NORMALIZACIÓN",
	    				"CallTypeID"=>5,
	    				"status"=>$status,
	    				"Subject"=>"Visita generada por Integración",
	    				"InternalSerialNum"=>$InternalSerialNum,
	    				"modelo"=>$modelo,
	    				"tipoequipo"=>$tipoequipo,
	    				"nomenclatura"=>$nomenclatura,
	    				"garantiaF"=>$garantiaF,
	    				"equipmentcardnum"=>$equipmentcardnum
	    			)
	    		);
        	}

        }



        public function MostrarInformacionQUERY($id){
        	/* Inicio ServicaCalls */
        	$query = '$crossjoin(ServiceCalls,ServiceCallTypes,CustomerEquipmentCards,Items,Manufacturers)?$expand=ServiceCalls($select=ServiceCallID,CallType,ItemCode,InternalSerialNum,Subject,CreationDate,CustomerCode),ServiceCallTypes($select=Name),CustomerEquipmentCards($select=InternalSerialNum,BuildingFloorRoom,Street,StreetNo,InstallLocation),Items($select=Manufacturer,ItemCode,ItemName),Manufacturers($select=ManufacturerName)&$filter=ServiceCalls/CallType eq ServiceCallTypes/CallTypeID and ServiceCalls/InternalSerialNum eq CustomerEquipmentCards/InternalSerialNum and ServiceCalls/ItemCode eq Items/ItemCode and Items/Manufacturer eq Manufacturers/Code and CustomerEquipmentCards/ItemCode eq Items/ItemCode and ServiceCalls/ServiceCallID eq ' . $id;

        	$rspta = Query($query);
        	$rsptaJson = json_decode($rspta);
        	$data = $rsptaJson->value[0];
        	

        	return json_encode(array(
        	    "CustomerCode"  => $data->ServiceCalls->CustomerCode,
        	    "ServiceCallID" => $data->ServiceCalls->ServiceCallID,
        	    "ItemCode"      => $data->ServiceCalls->ItemCode,
        	    "Manufacturer"  => $data->Manufacturers->ManufacturerName,
        	    "ItemName"      => $data->Items->ItemName,
        	    "edificio"      => $data->CustomerEquipmentCards->BuildingFloorRoom,
        	    "direccion"     => $data->CustomerEquipmentCards->Street.' '.$data->CustomerEquipmentCards->StreetNo,
        	    "CallType"      => $data->ServiceCallTypes->Name,
        	    "status"        => '',
        	    "Subject"       => $data->ServiceCalls->Subject
        	));

        	// echo $rspta;
        }

        public function IniciarServicio($servicecallID,$customerCode,$status,$gps){
			//$mifecha= date('Y-m-d H:i:s'); 
			//$NuevaFecha = strtotime ( '-4 hour' , strtotime ($mifecha) ) ; 

        	$entity = "Activities";
			$data = json_encode(array("HandledByEmployee"=>$_SESSION['idSAP'],"ActivityDate"=>date("Y-m-d"),"ActivityTime"=>date("H:i:s"),"CardCode"=>$customerCode,"StartDate"=>date("Y-m-d"),"StartTime"=>date("H:i:s"),"U_EstadoInicio"=>$status,"U_GPSInicio"=>$gps));
			$rspta = InsertarDatos($entity,$data);
			$datos = json_decode($rspta);
			$entity = 'ServiceCalls';
			$data = json_encode(array("Status"=>4,"TechnicianCode"=> $_SESSION['idSAP'],"ServiceCallActivities"=>array(array("ActivityCode"=>$datos->ActivityCode))));
        	return EditardatosNum($entity,$servicecallID,$data);
        }
		
		public function IniciarServicioAndroid($servicecallID,$customerCode,$status,$gps, $idSAP){
			//$mifecha= date('Y-m-d H:i:s'); 
			//$NuevaFecha = strtotime ( '-4 hour' , strtotime ($mifecha) ) ; 

        	$entity = "Activities";
			$data = json_encode(array("HandledByEmployee"=>$idSAP,"ActivityDate"=>date("Y-m-d"),"ActivityTime"=>date("H:i:s"),"CardCode"=>$customerCode,"StartDate"=>date("Y-m-d"),"StartTime"=>date("H:i:s"),"U_EstadoInicio"=>$status,"U_GPSInicio"=>$gps));
			$rspta = InsertarDatos($entity,$data);
			$datos = json_decode($rspta);
			$entity = 'ServiceCalls';
			$data = json_encode(array("Status"=>4,"TechnicianCode"=> $idSAP,"ServiceCallActivities"=>array(array("ActivityCode"=>$datos->ActivityCode))));
        	return EditardatosNum($entity,$servicecallID,$data);
        }


        public function IniciarServicioEmergencia($subject,$fm,$customercode,$itemcode,$status,$gps){
			//$mifecha= date('Y-m-d H:i:s'); 
			//$NuevaFecha = strtotime ( '-4 hour' , strtotime ($mifecha) ) ; 

        	$entity = "Activities";
			$data = json_encode(array("HandledByEmployee"=>$_SESSION['idSAP'],"ActivityDate"=>date("Y-m-d"),"ActivityTime"=>date("H:i:s"),"CardCode"=>$customercode,"StartDate"=>date("Y-m-d"),"StartTime"=>date("H:i:s"),"U_EstadoInicio"=>$status,"U_GPSInicio"=>$gps));
			$rspta = InsertarDatos($entity,$data);
			$datos = json_decode($rspta);
			/*insertar nueva llamada de servicio*/
			$entity = 'ServiceCalls';
			$data = json_encode(
				array(
					"Subject"=>$subject, 
					"CustomerCode"=>$customercode, 
					"InternalSerialNum"=>$fm,
					"ItemCode"=>$itemcode,
					"Priority"=>"M",
					"CallType"=>"4",
					"Origin"=>"1",
					"Status"=>4,
					"TechnicianCode"=> $_SESSION['idSAP'],
					"ServiceCallActivities"=>array(
						array(
							"ActivityCode"=>$datos->ActivityCode
						)
					)
				)
			);

        	return InsertarDatos($entity,$data);
        }

		public function IniciarServicioEmergenciaAndroid($subject,$fm,$customercode,$itemcode,$status,$gps,$idSAP){
			//$mifecha= date('Y-m-d H:i:s'); 
			//$NuevaFecha = strtotime ( '-4 hour' , strtotime ($mifecha) ) ; 

        	$entity = "Activities";
			$data = json_encode(array("HandledByEmployee"=>$idSAP,"ActivityDate"=>date("Y-m-d"),"ActivityTime"=>date("H:i:s"),"CardCode"=>$customercode,"StartDate"=>date("Y-m-d"),"StartTime"=>date("H:i:s"),"U_EstadoInicio"=>$status,"U_GPSInicio"=>$gps));
			$rspta = InsertarDatos($entity,$data);
			$datos = json_decode($rspta);
			/*insertar nueva llamada de servicio*/
			$entity = 'ServiceCalls';
			$data = json_encode(
				array(
					"Subject"=>$subject, 
					"CustomerCode"=>$customercode, 
					"InternalSerialNum"=>$fm,
					"ItemCode"=>$itemcode,
					"Priority"=>"M",
					"CallType"=>"4",
					"Origin"=>"1",
					"Status"=>4,
					"TechnicianCode"=> $idSAP,
					"ServiceCallActivities"=>array(
						array(
							"ActivityCode"=>$datos->ActivityCode
						)
					)
				)
			);

        	return InsertarDatos($entity,$data);
        }

        public function IniciarServicioVisita($fm,$customercode,$itemcode,$status,$gps){
			//$mifecha= date('Y-m-d H:i:s'); 
			//$NuevaFecha = strtotime ( '-4 hour' , strtotime ($mifecha) ) ; 

        	$entity = "Activities";
			$data = json_encode(array("HandledByEmployee"=>$_SESSION['idSAP'],"ActivityDate"=>date("Y-m-d"),"ActivityTime"=>date("H:i:s"),"CardCode"=>$customercode,"StartDate"=>date("Y-m-d"),"StartTime"=>date("H:i:s"),"U_EstadoInicio"=>$status,"U_GPSInicio"=>$gps));
			$rspta = InsertarDatos($entity,$data);
			$datos = json_decode($rspta);
			/*insertar nueva llamada de servicio*/
			$entity = 'ServiceCalls';
			$data = json_encode(
				array(
					"Subject"=>"Visita Generada por Integracion", 
					"CustomerCode"=>$customercode, 
					"InternalSerialNum"=>$fm,
					"ItemCode"=>$itemcode,
					"Priority"=>"M",
					"CallType"=>"17",
					"Origin"=>"1",
					"Status"=>4,
					"TechnicianCode"=> $_SESSION['idSAP'],
					"ServiceCallActivities"=>array(
						array(
							"ActivityCode"=>$datos->ActivityCode
						)
					)
				)
			);

        	return InsertarDatos($entity,$data);
        }

		public function IniciarServicioVisitaAndroid($fm,$customercode,$itemcode,$status,$gps,$idSAP){
			//$mifecha= date('Y-m-d H:i:s'); 
			//$NuevaFecha = strtotime ( '-4 hour' , strtotime ($mifecha) ) ; 

        	$entity = "Activities";
			$data = json_encode(array("HandledByEmployee"=>$idSAP,"ActivityDate"=>date("Y-m-d"),"ActivityTime"=>date("H:i:s"),"CardCode"=>$customercode,"StartDate"=>date("Y-m-d"),"StartTime"=>date("H:i:s"),"U_EstadoInicio"=>$status,"U_GPSInicio"=>$gps));
			$rspta = InsertarDatos($entity,$data);
			$datos = json_decode($rspta);
			/*insertar nueva llamada de servicio*/
			$entity = 'ServiceCalls';
			$data = json_encode(
				array(
					"Subject"=>"Visita Generada por Integracion", 
					"CustomerCode"=>$customercode, 
					"InternalSerialNum"=>$fm,
					"ItemCode"=>$itemcode,
					"Priority"=>"M",
					"CallType"=>"17",
					"Origin"=>"1",
					"Status"=>4,
					"TechnicianCode"=> $idSAP,
					"ServiceCallActivities"=>array(
						array(
							"ActivityCode"=>$datos->ActivityCode
						)
					)
				)
			);

        	return InsertarDatos($entity,$data);
        }

		public function IniciarServicioNormalizacion($observacionini,$fm,$customercode,$itemcode,$status,$gps){
			//$mifecha= date('Y-m-d H:i:s'); 
			//$NuevaFecha = strtotime ( '-4 hour' , strtotime ($mifecha) ) ; 

        	$entity = "Activities";
			$data = json_encode(array("HandledByEmployee"=>$_SESSION['idSAP'],"ActivityDate"=>date("Y-m-d"),"ActivityTime"=>date("H:i:s"),"CardCode"=>$customercode,"StartDate"=>date("Y-m-d"),"StartTime"=>date("H:i:s"),"U_EstadoInicio"=>$status,"U_GPSInicio"=>$gps));
			$rspta = InsertarDatos($entity,$data);
			$datos = json_decode($rspta);
			/*insertar nueva llamada de servicio*/
			$entity = 'ServiceCalls';
			$data = json_encode(
				array(
					"Subject"=>$observacionini, 
					"CustomerCode"=>$customercode, 
					"InternalSerialNum"=>$fm,
					"ItemCode"=>$itemcode,
					"Priority"=>"M",
					"CallType"=>"5",
					"Origin"=>"1",
					"Status"=>4,
					"TechnicianCode"=> $_SESSION['idSAP'],
					"ServiceCallActivities"=>array(
						array(
							"ActivityCode"=>$datos->ActivityCode
						)
					)
				)
			);

        	return InsertarDatos($entity,$data);
        }
        
		public function IniciarServicioNormalizacionandroid($observacionini,$fm,$customercode,$itemcode,$status,$gps, $idSAP){
			//$mifecha= date('Y-m-d H:i:s'); 
			//$NuevaFecha = strtotime ( '-4 hour' , strtotime ($mifecha) ) ; 

        	$entity = "Activities";
			$data = json_encode(array("HandledByEmployee"=>$idSAP,"ActivityDate"=>date("Y-m-d"),"ActivityTime"=>date("H:i:s"),"CardCode"=>$customercode,"StartDate"=>date("Y-m-d"),"StartTime"=>date("H:i:s"),"U_EstadoInicio"=>$status,"U_GPSInicio"=>$gps));
			$rspta = InsertarDatos($entity,$data);
			$datos = json_decode($rspta);
			/*insertar nueva llamada de servicio*/
			$entity = 'ServiceCalls';
			$data = json_encode(
				array(
					"Subject"=>$observacionini, 
					"CustomerCode"=>$customercode, 
					"InternalSerialNum"=>$fm,
					"ItemCode"=>$itemcode,
					"Priority"=>"M",
					"CallType"=>"5",
					"Origin"=>"1",
					"Status"=>4,
					"TechnicianCode"=> $idSAP,
					"ServiceCallActivities"=>array(
						array(
							"ActivityCode"=>$datos->ActivityCode
						)
					)
				)
			);

        	return InsertarDatos($entity,$data);
        }
        
        
        public function ListarFM($cliente,$edificio,$cencosto){
        	$query = "\$crossjoin(CustomerEquipmentCards,U_NX_ESTADOS_FM)?\$expand=CustomerEquipmentCards(\$select=InternalSerialNum,CustomerCode,CustomerName,BuildingFloorRoom,Street,StreetNo,InstallLocation),U_NX_ESTADOS_FM(\$select=Name)&\$filter=CustomerEquipmentCards/U_NX_ESTADOFM eq U_NX_ESTADOS_FM/Code and CustomerEquipmentCards/InstallLocation eq '".$edificio."' and startswith(CustomerCode,'C".$cliente."')";
			return json_decode(Query($query), true);
        }

        public function ModificaNomenclatura($id,$nomenclatura){
        	$entity = 'CustomerEquipmentCards';
    		$servicecall = json_encode(array("U_NX_NOMENCLATURACL"=>$nomenclatura));
    		$rsptaservcall = EditardatosNum($entity,$id,$servicecall);
        }
        
        public function Holidays(){
        	$select = 'HolidayDates';
			$entity = 'Holidays';
			$id = date('Y');
			$retorna = json_decode(ConsultaIDLet($entity,$id,$select),true);
			return $retorna;
        }
	}
?>