<?php

require_once "../config/conexion.php";
require_once "../config/conexionSap.php";

class Servicio
{
	//Constructor para instanciassss
	public function __construct()
	{

	}

	public function ObtenerImagenBase64($num_documento){

		$sql = "SELECT * FROM user WHERE num_documento = '$num_documento'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function Dispositivo($actividad, $servicio, $responsable, $tiposervicio, $modulo, $nombre_log, $tipoequipo, $resultado){
		if($actividad !== ' ' || $actividad !== ''){
			$dispositivo = "Aplicación Movil";
			// Escapar los valores de las variables y agregar comillas solo para los valores de tipo cadena
			$actividad = is_numeric($actividad) ? $actividad : "'$actividad'";
			$servicio = is_numeric($servicio) ? $servicio : "'$servicio'";
			$creado = date("Y-m-d H:i:s");

			// Construir la consulta SQL
			$sql = "INSERT INTO registro_dispositivo (dispositivo, actividad, servicio, responsable, tipo_servicio, modulo, creado, log, tipo_equipo, resultado) VALUES ('$dispositivo', $actividad, $servicio, '$responsable', '$tiposervicio', '$modulo', '$creado', '$nombre_log', '$tipoequipo', '$resultado')";
			
			// Ejecutar la consulta SQL
			return ejecutarConsulta($sql);
		}
	}

	public function existepresupuesto($idactividad)
	{
		$sql = "SELECT * FROM presupuesto_sap WHERE actividadID = $idactividad ORDER BY presupuestosapID DESC LIMIT 1";
		return ejecutarConsulta($sql);
	}

	public function existepresupuestoPostegacion($idactividad){
		$sql="SELECT * FROM presupuesto_sap WHERE actividadID = $idactividad ORDER BY presupuestosapID DESC LIMIT 1";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function iniciar($idtservicio, $iduser, $idtecnico, $idascensor, $estadoini, $observacionini, $latini, $lonini)
	{
		$sql = "INSERT INTO servicio (idtservicio, iduser, idtecnico, idascensor, estadoini, observacionini, latini, lonini) VALUES ('$idtservicio','$iduser','$idtecnico', '$idascensor', '$estadoini','$observacionini','$latini','$lonini')";
		return ejecutarConsulta($sql);
	}

	public function finalizar($idservicio, $estadofin, $observacionfin, $nrodocumento, $nombre, $apellido, $rut, $cargo, $firma, $latfin, $lonfin)
	{
		if (is_null($nombre) && is_null($apellido) && is_null($rut) && is_null($cargo) && is_null($firma)) {
			$sql = "UPDATE servicio SET estadofin='$estadofin', observacionfin='$observacionfin', nrodocumento='$nrodocumento', closed_time=CURRENT_TIMESTAMP, latfin='$latfin', lonfin='$lonfin'  WHERE idservicio='$idservicio'";
		} else {
			$sql = "UPDATE servicio SET estadofin='$estadofin', observacionfin='$observacionfin', nrodocumento='$nrodocumento', nombre='$nombre', apellidos='$apellido', rut='$rut', firma='$firma', closed_time=CURRENT_TIMESTAMP, latfin='$latfin', lonfin='$lonfin'  WHERE idservicio='$idservicio'";
		}
		return ejecutarConsulta($sql);
	}

	public function firmar($idservicio, $nombre, $apellido, $rut, $cargo, $firma)
	{
		$sql = "UPDATE servicio SET nombre='$nombre', apellidos='$apellido', rut='$rut', firma='$firma'  WHERE idservicio='$idservicio'";
		return ejecutarConsulta($sql);
	}

	public function firmapendiente($idactividad){
		$sql = "SELECT * FROM informevisita  WHERE infv_actividad = '$idactividad' ORDER BY infv_id DESC LIMIT 1";
		return ejecutarConsulta($sql);
	}

	public function firmapendientes($idactividad, $rut, $firma){
		$sql = "UPDATE informevisita 
        SET infv_rutcli = '$rut', 
            infv_firmacliente = '$firma', 
            infv_estado = 'terminado', 
            infv_fechamod = NOW()
        WHERE infv_actividad = '$idactividad'";
		return ejecutarConsulta($sql);
	}

	public function datosservicio($iduser)
	{
		$sql = "SELECT s.idservicio, DATE(s.created_time) AS fecha, TIME(s.created_time) AS hora, s.estadoini, s.observacionini, w.nombre as tiposer, a.codigo, x.nombre AS tipo, m.nombre AS marca, n.nombre AS modelo, o.nombre as cargotec, t.nombre, t.apellidos, t.rut, e.nombre as edificio, e.calle, e.numero, a.idascensor FROM servicio s INNER JOIN ascensor a ON s.idascensor=a.idascensor INNER JOIN tascensor x ON a.idtascensor=x.idtascensor INNER JOIN marca m ON a.marca=m.idmarca INNER JOIN modelo n ON a.modelo=n.idmodelo INNER JOIN tecnico t ON s.idtecnico=t.idtecnico INNER JOIN tservicio w ON s.idtservicio=w.idtservicio INNER JOIN edificio e ON a.idedificio=e.idedificio INNER JOIN cargotec o ON t.idcargotec=o.idcargotec WHERE s.iduser='$iduser' AND s.estadofin IS NULL";
		return ejecutarConsulta($sql);
	}

	public function verificarservicio($iduser)
	{
		$sql = "SELECT idservicio FROM servicio WHERE iduser='$iduser' AND estadofin IS NULL";
		return NumeroFilas($sql);
	}

	public function nofirma($idservicio)
	{
		$sql = "UPDATE servicio SET reqfirma=0 WHERE idservicio='$idservicio'";
		return ejecutarConsulta($sql);
	}

	public function infoguia($idservicio)
	{
		$sql = "SELECT s.idservicio, DATE(s.created_time) AS fecha, TIME(s.created_time) AS hora, z.nombre AS estado , s.observacionini, w.nombre as tiposer, a.codigo, x.nombre AS tipo, m.nombre AS marca, n.nombre AS modelo, o.nombre as cargotec, t.nombre, t.apellidos, t.rut, e.nombre as edificio, e.calle, e.numero, a.idascensor, s.idtservicio FROM servicio s INNER JOIN ascensor a ON s.idascensor=a.idascensor INNER JOIN tascensor x ON a.idtascensor=x.idtascensor INNER JOIN marca m ON a.marca=m.idmarca INNER JOIN modelo n ON a.modelo=n.idmodelo INNER JOIN tecnico t ON s.idtecnico=t.idtecnico INNER JOIN tservicio w ON s.idtservicio=w.idtservicio INNER JOIN edificio e ON a.idedificio=e.idedificio INNER JOIN cargotec o ON t.idcargotec=o.idcargotec INNER JOIN testado z ON s.estadoini = z.id WHERE s.idservicio='$idservicio'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function LSF($iduser)
	{
		$sql = "SELECT s.idservicio, DATE(s.created_time) AS fecha, TIME(s.created_time) AS inicio, TIME(s.closed_time) AS fin, a.codigo, e.nombre as edificio FROM servicio s INNER JOIN ascensor a ON s.idascensor=a.idascensor INNER JOIN edificio e ON a.idedificio=e.idedificio WHERE s.iduser='$iduser' AND s.reqfirma = 1 AND s.firma IS NULL AND s.estadofin IS NOT NULL AND MONTH(s.created_time)  IN (MONTH(NOW()), MONTH(NOW())-1)";
		return ejecutarConsulta($sql);
	}

	public function formfirma($idservicio)
	{
		$sql = "SELECT s.idservicio, DATE(s.created_time) AS fechaini, TIME(s.created_time) AS horaini, DATE(s.closed_time) AS fechafin, TIME(s.closed_time) AS horafin, z.nombre AS estadoini, s.observacionini, p.nombre AS estadofn, s.observacionfin, w.nombre as tiposer, a.codigo, x.nombre AS tipo, m.nombre AS marca, n.nombre AS modelo, o.nombre as cargotec, t.nombre, t.apellidos, t.rut, e.nombre as edificio, e.calle, e.numero FROM servicio s INNER JOIN ascensor a ON s.idascensor=a.idascensor INNER JOIN tascensor x ON a.idtascensor=x.idtascensor INNER JOIN marca m ON a.marca=m.idmarca INNER JOIN modelo n ON a.modelo=n.idmodelo INNER JOIN tecnico t ON s.idtecnico=t.idtecnico INNER JOIN tservicio w ON s.idtservicio=w.idtservicio INNER JOIN edificio e ON a.idedificio=e.idedificio INNER JOIN cargotec o ON t.idcargotec=o.idcargotec INNER JOIN testado z ON s.estadoini = z.id INNER JOIN testado p ON s.estadofin=p.id WHERE s.idservicio='$idservicio'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function email($idservicio)
	{
		$sql = "SELECT s.idservicio, u.nombre AS esini, s.observacionini, i.nombre AS esfin, s.observacionfin, s.created_time AS ini, s.closed_time AS fin, a.codigo, a.codigocli, a.ubicacion, q.nombre AS tascen, m.nombre AS marca, n.nombre AS modelo, t.nombre AS tser, e.nombre AS edi, e.calle, e.numero, r.region_nombre AS region, c.comuna_nombre AS comuna, w.nombre AS segmen, p.nombre AS nomtec, p.apellidos AS apetec, p.rut AS ruttec, o.nombre AS cartec, s.file, s.filefir, s.nombre AS nomvali, s.apellidos AS apevali, s.rut AS rutvali, s.firma, s.reqfirma FROM servicio s INNER JOIN ascensor a ON s.idascensor = a.idascensor INNER JOIN edificio e ON a.idedificio = e.idedificio INNER JOIN tascensor q ON a.idtascensor = q.idtascensor INNER JOIN marca m ON a.marca=m.idmarca INNER JOIN modelo n ON a.modelo = n.idmodelo INNER JOIN tsegmento w ON e.idtsegmento = w.idtsegmento INNER JOIN regiones r ON e.idregiones = r.region_id INNER JOIN comunas c ON e.idcomunas = c.comuna_id INNER JOIN tservicio t ON s.idtservicio = t.idtservicio INNER JOIN testado u ON s.estadoini = u.id INNER JOIN testado i ON s.estadofin = i.id INNER JOIN tecnico p ON s.idtecnico = p.idtecnico INNER JOIN cargotec o ON p.idcargotec = o.idcargotec WHERE s.idservicio = '$idservicio'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function pdf($idservicio)
	{
		$sql = "SELECT s.idservicio, u.nombre AS esini, s.observacionini, i.nombre AS esfin, s.observacionfin, s.nombre AS nomvali, s.apellidos AS apevali, s.rut AS rutvali, s.firma, s.filefir, s.created_time AS ini, s.reqfirma, a.codigo, a.codigocli, a.ubicacion, q.nombre AS tascen, m.nombre AS marca, n.nombre AS modelo, t.nombre AS tser, e.nombre AS edi, e.calle, e.numero, r.region_nombre AS region, c.comuna_nombre AS comuna, p.nombre AS nomtec, p.apellidos AS apetec, p.rut AS ruttec FROM servicio s INNER JOIN ascensor a ON s.idascensor = a.idascensor INNER JOIN edificio e ON a.idedificio = e.idedificio INNER JOIN tascensor q ON a.idtascensor = q.idtascensor INNER JOIN marca m ON a.marca=m.idmarca INNER JOIN modelo n ON a.modelo = n.idmodelo INNER JOIN tsegmento w ON e.idtsegmento = w.idtsegmento INNER JOIN regiones r ON e.idregiones = r.region_id INNER JOIN comunas c ON e.idcomunas = c.comuna_id INNER JOIN tservicio t ON s.idtservicio = t.idtservicio INNER JOIN testado u ON s.estadoini = u.id INNER JOIN testado i ON s.estadofin = i.id INNER JOIN tecnico p ON s.idtecnico = p.idtecnico INNER JOIN cargotec o ON p.idcargotec = o.idcargotec WHERE s.idservicio = '$idservicio'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function UpFile($archivo, $idservicio)
	{
		$sql = "UPDATE servicio SET file='$archivo'WHERE idservicio='$idservicio'";
		return ejecutarConsulta($sql);
	}

	public function UpFirma($archivo, $idservicio)
	{
		$sql = "UPDATE servicio SET filefir='$archivo'WHERE idservicio='$idservicio'";
		return ejecutarConsulta($sql);
	}

	public function SolPre($idservicio, $idascensor, $idsupervisor, $idtecnico, $descripcion)
	{
		$sql = "INSERT INTO presupuesto (idservicio, idascensor, idsupervisor, idtecnico, descripcion) VALUES ('$idservicio','$idascensor','$idsupervisor','$idtecnico','$descripcion')";
		return ejecutarConsulta($sql);
	}


	public function verificarservicioSAP()
	{
		$sql = 'ServiceCalls/$count?$filter=Status eq 4 and TechnicianCode eq ' . $_SESSION['idSAP'];
		error_log("El resultado de /modelos/Servicio.php>verificarservicioSAP: " . Query($sql));
		return Query($sql);
	}


	public function verificarservicioSAPandroid($idSAP)
	{
		$sql = 'ServiceCalls/$count?$filter=Status eq 4 and TechnicianCode eq ' . $idSAP;
		error_log("El resultado de verificarservicioSAPandroid: " . Query($sql));
		return Query($sql);
	}

	public function datosserviciosap()
	{
		$entity = 'ServiceCalls';
		$select = '*';
		$filter = 'Status eq 4 and TechnicianCode eq ' . $_SESSION['idSAP'];
		$result = ConsultaEntity($entity, $select, $filter);

		error_log("El resultado de modelos/Servicio.php>datosserviciossap: " . print_r(json_decode($result, true), true));
		return json_decode(ConsultaEntity($entity, $select, $filter), true);
	}



	public function datosserviciosapandroid($idSAP)
	{
		$entity = 'ServiceCalls';
		$select = '*';
		$filter = 'Status eq 4 and TechnicianCode eq ' . $idSAP;
		$result = ConsultaEntity($entity, $select, $filter);

		error_log("El resultado de modelos/Servicio.php>datosserviciossap: " . print_r(json_decode($result, true), true));
		return json_decode(ConsultaEntity($entity, $select, $filter), true);
	}

	public function selectestadosap()
	{
		$entity = 'U_NX_ESTADOS_FM';
		$select = 'Code,Name';
		$filter = "U_Mostrar eq 'Y'";
		$result = ConsultaEntity($entity, $select, $filter);
		error_log("La respuesta de modelos/Servicio.php>selectestadosap: " . $result);
		return json_decode(ConsultaEntity($entity, $select, $filter), true);
	}


	public function infoActividadSAP($idactividad)
	{
		/* Inicio ServicaCalls */
		$QueryPath = '$crossjoin(Activities,ServiceCalls,ServiceCallTypes,CustomerEquipmentCards,Items,Manufacturers,EmployeesInfo,EmployeePosition,ItemGroups)';
		$QueryOption = '$expand=ServiceCalls($select=ServiceCallID,CallType,ItemCode,InternalSerialNum,Subject,CreationDate,CustomerCode),ServiceCallTypes($select=Name),CustomerEquipmentCards($select=InternalSerialNum,BuildingFloorRoom,Street,StreetNo,InstallLocation,ItemDescription),Items($select=Manufacturer,ItemCode,ItemName,U_NX_TIPEQUIPO,U_NX_MODELO),Manufacturers($select=ManufacturerName),Activities($select=ActivityDate,ActivityTime,ActivityCode),EmployeesInfo($select=FirstName,MiddleName,LastName,PassportNumber,Position),EmployeePosition($select=Description),ItemGroups($select=Number,GroupName)&$filter=ServiceCalls/CallType eq ServiceCallTypes/CallTypeID and ServiceCalls/InternalSerialNum eq CustomerEquipmentCards/InternalSerialNum and ServiceCalls/ItemCode eq Items/ItemCode and Items/Manufacturer eq Manufacturers/Code and CustomerEquipmentCards/ItemCode eq Items/ItemCode and Activities/ParentObjectId eq ServiceCalls/ServiceCallID and ServiceCalls/TechnicianCode eq EmployeesInfo/EmployeeID and EmployeesInfo/Position eq EmployeePosition/PositionID and Items/ItemsGroupCode eq ItemGroups/Number and Activities/ActivityCode eq ' . $idactividad;
		$rspta = postQuery($QueryPath, $QueryOption);

		$rsptaJson = json_decode($rspta);
		$data = $rsptaJson->value[0];

		return json_encode(
			array(
				"eqCodigo" => $data->ServiceCalls->InternalSerialNum . '',
				"eqTipo" => $data->Items->U_NX_TIPEQUIPO . '',
				"eqFabricante" => $data->Manufacturers->ManufacturerName . '',
				"eqModelo" => $data->Items->U_NX_MODELO . '',
				"tcCargo" => $data->EmployeePosition->Description . '',
				"tcNombres" => $data->EmployeesInfo->FirstName . ' ' . $data->EmployeesInfo->MiddleName,
				"tcApellido" => $data->EmployeesInfo->LastName . '',
				"tcRut" => $data->EmployeesInfo->PassportNumber . '',
				"edNombre" => $data->CustomerEquipmentCards->InstallLocation . '',
				"edDireccion" => $data->CustomerEquipmentCards->Street . ' ' . $data->CustomerEquipmentCards->StreetNo,
				"srNumServ" => $data->ServiceCalls->ServiceCallID . '',
				"srTipo" => $data->ServiceCallTypes->Name . '',
				"srFecIni" => $data->Activities->ActivityDate . '',
				"srHoraIni" => $data->Activities->ActivityTime . '',
				"srObsIni" => $data->ServiceCalls->Subject . '',
				"activityCode" => $data->Activities->ActivityCode . ''
			)
		);
	}

	public function MostrarInformacion($id, $idactividad)
	{
		// echo "<br>select $id<br>";
		$entity = 'ServiceCalls';
		$select = 'ServiceCallID,ItemCode,InternalSerialNum,CallType,Subject,CustomerCode,ServiceCallActivities';
		$servcall = json_decode(ConsultaIDNum($entity, $id, $select), true);
		error_log("Respuesta de servall modelos/Servicio.php>MostrarInformacion: " . print_r($servcall, true));

		$customerCode = $servcall['CustomerCode'];
		if (!empty($servcall['CallType'])) {
			$CallType = '';
			$entity = 'ServiceCallTypes';
			$id = $servcall["CallType"];
			$select = 'Name';
			$tipo = json_decode(ConsultaIDNum($entity, $id, $select), true);
			error_log("Resultado de tipo modelos/Servicio.php>MostrarInformacion: " . print_r($tipo, true));
		}

		$entity = 'Activities';
		$id = $idactividad;
		$select = 'StartDate,StartTime,U_EstadoInicio';
		$actividad = json_decode(ConsultaIDNum($entity, $id, $select), true);
		error_log("Resultado de actividad modelos/Servicio.php>MostrarInformacion: " . print_r($actividad, true));

		$crossjoin = '$crossjoin(CustomerEquipmentCards,U_NX_ESTADOS_FM,Items,ItemGroups,Manufacturers)?$expand=U_NX_ESTADOS_FM($select=Name),CustomerEquipmentCards($select=U_NX_SUPERVISOR,U_NX_NOMENCLATURACL,EquipmentCardNum,InternalSerialNum,ItemDescription,InstallLocation,Street,StreetNo),Items($select=ItemName,U_NX_TIPEQUIPO,U_NX_MODELO),ItemGroups($select=GroupName),Manufacturers($select=ManufacturerName)&$filter=CustomerEquipmentCards/U_NX_ESTADOFM eq U_NX_ESTADOS_FM/Code and CustomerEquipmentCards/ItemCode eq Items/ItemCode and Items/ItemsGroupCode eq ItemGroups/Number and Items/Manufacturer eq Manufacturers/Code and CustomerEquipmentCards/InternalSerialNum eq \'' . $servcall['InternalSerialNum'] . '\' and CustomerEquipmentCards/ItemCode eq \'' . $servcall['ItemCode'] . '\'';

		$rspta = json_decode(Query($crossjoin), true);
		error_log("Resultado de crossjoin modelos/Servicio.php>MostrarInformacion: " . print_r($rspta, true));

		$conteo = count($servcall['ServiceCallActivities']);
		$actividadID = $servcall['ServiceCallActivities'][$conteo - 1]['ActivityCode'];
		$rspta = $rspta['value'][0];
		error_log("Ek 2° valor de rspta modificado en modelos/Servicio.php>MostrarInformacion: " . print_r($rspta, true));

		$data = array(
			"CustomerCode" => $customerCode,
			"ServiceCallID" => $servcall["ServiceCallID"],
			"activityID" => $idactividad,
			"modelo" => $rspta["Items"]['U_NX_MODELO'],
			"tipoequipo" => $rspta["Items"]['U_NX_TIPEQUIPO'],
			"ItemCode" => $servcall['ItemCode'],
			"Manufacturer" => $rspta['Manufacturers']['ManufacturerName'],
			"ItemName" => $rspta['Items']['ItemName'],
			"codigo" => $servcall['InternalSerialNum'],
			"edificio" => $rspta['CustomerEquipmentCards']['InstallLocation'],
			"direccion" => $rspta['CustomerEquipmentCards']['Street'] . ' ' . $rspta['CustomerEquipmentCards']['StreetNo'],
			"fecha" => $actividad['StartDate'],
			"hora" => $actividad['StartTime'],
			"CallType" => $tipo['Name'],
			"status" => $actividad['U_EstadoInicio'],
			"Subject" => $servcall['Subject'],
			"idtservicio" => $servcall['CallType'],
			"idascensor" => $rspta["CustomerEquipmentCards"]["EquipmentCardNum"],
			"nomenclatura" => $rspta["CustomerEquipmentCards"]["U_NX_NOMENCLATURACL"],
			"supervisorID" => $rspta['CustomerEquipmentCards']['U_NX_SUPERVISOR']
		);
		/*if (!empty($rspta['CustomerEquipmentCards']['U_NX_SUPERVISOR'])) {
				  $entity = 'U_NX_SUPERVISOR';
				  $select = 'U_EmpleadoID';
				  $filter = "Code eq '".$rspta['CustomerEquipmentCards']['U_NX_SUPERVISOR']."'";
				  $supervisor = json_decode(ConsultaEntity($entity,$select, $filter), true);
				  $data["supervisorID"] = $supervisor['value'][0]['U_EmpleadoID'];
			  }*/

		$data_json = json_encode($data);

		// Imprimir en el error_log
		error_log("Contenido del arreglo data y ultimo resultado modelos/Servicio.php>MostrarInformacion: " . $data_json);

		return json_encode($data);
	}

	public function finalizarActividadAndroid($data, $actividadIDfi, $guiafimada){
			$data = json_decode($data);

			$entity = 'Activities';
			$id = $actividadIDfi;
			$firma = 'N';
			$status = 1;
			if (isset($guiafimada)) {
					$actividad = json_encode(array("Closed" => "Y", "U_PorFirmar" => $firma, "AttachmentEntry" => $guiafimada));
			} else {
					$actividad = json_encode(array("Closed" => "Y", "U_PorFirmar" => $firma));
			}
			$rsptaactv = EditardatosNum($entity, $id, $actividad);

			$entity = 'ServiceCalls';
			$id = $data->servicecallIDfi;
			$terceros = 'no';
			$servicecall = json_encode(array("Status" => $status, "U_FallaTercero" => $terceros));
			$rsptaservcall = EditardatosNum($entity, $id, $servicecall);

			$sql = "INSERT INTO logactividad (actividadID,data) VALUES ('$data->actividadIDfi','$actividad')";
			ejecutarConsulta($sql);
		return true;
	}


	public function finalizarActividadMantencion($data)
	{
		$logFile = fopen("log/log.txt", 'a') or die("Error creando archivo");
		fwrite($logFile, "\n".date("d/m/Y H:i:s")." - Data dentro de modelo : ".$data) or die("Error escribiendo en el archivo");
		fclose($logFile);  

		$dataObject = json_decode($data);
	
		if (isset($dataObject->estadofintext)) {
			$estadofintext = $dataObject->estadofintext;
		} else {
			$estadofintext = $dataObject->estadoascensor;
		}
	
		if (isset($dataObject->terceros) && $dataObject->terceros == "true") {
			$terceros = 'Si';
		} else {
			$terceros = 'No';
		}

		if ($dataObject->oppre == 1) {
			// No es necesario decodificar el JSON nuevamente
			// $arrayData = json_decode($data, true);
			$arrayData = $dataObject; // Usa $dataObject directamente
	
			// Eliminar las claves específicas
			unset($arrayData->preg);
			unset($arrayData->estadofintext);
			unset($arrayData->empresa);
			unset($arrayData->direccion);
			unset($arrayData->periodo);
			unset($arrayData->observaciones);
			unset($arrayData->chkCertifica);

			$data = $arrayData;

			// Codificar el array modificado a JSON
			$datapresupuesto = json_encode($arrayData, JSON_UNESCAPED_UNICODE);
	
			$supervisorValue = $dataObject->supervisorID !== null ? $dataObject->supervisorID : NULL;
			$actividadID = $dataObject->actividadIDfi !== null || $dataObject->actividadIDfi !== '' ? intval($dataObject->actividadIDfi) : 0;
			$sql = "INSERT INTO presupuesto_sap (actividadID, supervisorID, informacion) VALUES ($actividadID,$supervisorValue,'$datapresupuesto')";
			ejecutarConsulta($sql);

			//LOG
			$logFile = fopen("log/log.txt", 'a') or die("Error creando archivo");
			fwrite($logFile, "\n".date("d/m/Y H:i:s")." - Presupuesto  : ".ejecutarConsulta($sql)." - Actividad ".$actividadID." - Supervisor".$supervisorValue) or die("Error escribiendo en el archivo");
			fclose($logFile);  

			$entity = 'Activities';
			$id = $data->actividadIDfi;
			if ($data->opfirma == 2) {
				$firma = 'Y';
				$status = 5;
			} else {
				$firma = 'N';
				$status = ((isset($data->terminado) && $data->terminado == 'y') ? 1 : '-3');
			}
			
			if (isset($data->guiafimada)) {
				if($_POST['idSAP']){
					$actividad = array("HandledByEmployee" => $_POST['idSAP'], "Closed" => "Y", "U_PorFirmar" => $firma, "EndDueDate" => date("Y-m-d"), "EndTime" => date("H:i:s"), "AttachmentEntry" => $data->guiafimada, "Notes" => $data->observacionfi, "U_EstadoFin" => $estadofintext, "U_GPSFin" => $data->latitudfi . ',' . $data->longitudfi, "U_OBSINTERNA" => $data->observacionint);
					error_log("variable acrividad de presupuesto: ".print_r($actividad, true));
				}
				if ($data->opayu == 'S') {
					$actividad["U_TieneAyudante"] = $data->opayu;
					if (isset($data->idayud1) && !empty($data->idayud1)) {
						$actividad["U_AYUDANTE1"] = $data->idayud1;
					}
					if (isset($data->idayud2) && !empty($data->idayud2)) {
						$actividad["U_AYUDANTE2"] = $data->idayud2;
					}
				}
				$actividad = json_encode($actividad);
			} else {
				if($_POST['idSAP']){
					$actividad = array("HandledByEmployee" => $_POST['idSAP'], "Closed" => "Y", "U_PorFirmar" => $firma, "EndDueDate" => date("Y-m-d"), "EndTime" => date("H:i:s"), "Notes" => $data->observacionfi, "U_EstadoFin" => $estadofintext, "U_GPSFin" => $data->latitudfi . ',' . $data->longitudfi, "U_OBSINTERNA" => $data->observacionint);
				}
				if ($data->opayu == 'S') {
					$actividad["U_TieneAyudante"] = $data->opayu;
					if (isset($data->idayud1) && !empty($data->idayud1)) {
						$actividad["U_AYUDANTE1"] = $data->idayud1;
					}
					if (isset($data->idayud2) && !empty($data->idayud2)) {
						$actividad["U_AYUDANTE2"] = $data->idayud2;
					}
				}
				$actividad = json_encode($actividad);
			}
			$editarActividad = EditardatosNum($entity, $id, $actividad);

			//LOG
			$logFile = fopen("log/log.txt", 'a') or die("Error creando archivo");
			fwrite($logFile, "\n".date("d/m/Y H:i:s")." - Editar Actividad  : ".$editarActividad." - Entity ".$entity." - ID ".$id." - Actividad ".$actividad) or die("Error escribiendo en el archivo");
			fclose($logFile);  
			
			$actividadID = $data->actividadIDfi !== null || $data->actividadIDfi !== '' ? intval($data->actividadIDfi) : 0;
			$sql = "INSERT INTO logactividad (actividadID,data) VALUES ($actividadID,'$actividad')";
			ejecutarConsulta($sql);
			error_log($sql);
			//LOG
			$logFile = fopen("log/log.txt", 'a') or die("Error creando archivo");
			fwrite($logFile, "\n".date("d/m/Y H:i:s")." - Log Actividad  : ".ejecutarConsulta($sql)." - ActividadID ".$actividadID." - Actividad ".$actividad ) or die("Error escribiendo en el archivo");
			fclose($logFile);  
			
			$entity = 'CustomerEquipmentCards';
			$select = '*';
			$filter = "CustomerCode eq '".$data->customercodefi."' and InternalSerialNum eq '".$data->codigoEquipo."'";
			$equipo = json_decode(ConsultaEntity($entity,$select,$filter), true);
			$idascensor = $equipo['value'][0]['EquipmentCardNum'];
			//LOG
			$logFile = fopen("log/log.txt", 'a') or die("Error creando archivo");
			fwrite($logFile, "\n".date("d/m/Y H:i:s")." - ID Ascensor  : ".$idascensor ) or die("Error escribiendo en el archivo");
			fclose($logFile);  
			
			$entity = 'CustomerEquipmentCards';
			$id = $idascensor;
			$servicecall = json_encode(array("U_NX_ESTADOFM" => $data->idestadofi));
			$editarEquipo = EditardatosNum($entity, $id, $servicecall);
			error_log($editarEquipo);
			//LOG
			$logFile = fopen("log/log.txt", 'a') or die("Error creando archivo");
			fwrite($logFile, "\n".date("d/m/Y H:i:s")." - Editar Tarjeta de Equipo  : ".$editarEquipo." - Entidad ".$entity." - ID ".$id." - Servicecall ".$servicecall) or die("Error escribiendo en el archivo");
			fclose($logFile);  

			$entity = 'ServiceCalls';
			$id = intval($data->servicecallIDfi);
			$servicecall = json_encode(array("Status" => $status, "U_FallaTercero" => $terceros));
			$EditarServicio = EditardatosNum($entity, $id, $servicecall);
			error_log($EditarServicio);
			//LOG
			$logFile = fopen("log/log.txt", 'a') or die("Error creando archivo");
			fwrite($logFile, "\n".date("d/m/Y H:i:s")." - Editar Servicio  : ".$EditarServicio." - Entidad ".$entity." - ID ".$id." - Servicecall ".$servicecall) or die("Error escribiendo en el archivo");
			fclose($logFile);  
		} 
		else 
		{
			/*
			--------------------------- SI NO HAY PRESUPUESTO POR AQUI-------------------------------------------------------------------------------
			*/
			// No es necesario decodificar el JSON nuevamente
			// $arrayData = json_decode($data, true);
			$arrayData = $dataObject; // Usa $dataObject directamente
	
			// Eliminar las claves específicas
			unset($arrayData->preg);
			unset($arrayData->estadofintext);
			unset($arrayData->empresa);
			unset($arrayData->direccion);
			unset($arrayData->periodo);
			unset($arrayData->observaciones);
			unset($arrayData->chkCertifica);

			$data = $arrayData;
		
			if (isset($data->estadofintext)) {
				$estadofintext = $data->estadofintext;
			} else {
				$estadofintext = $data->estadoascensor;
			}
			$entity = 'Activities';
			$id = $data->actividadIDfi;
			if ($data->opfirma == 2) {
				$firma = 'Y';
				$status = 5;
			} else {
				$firma = 'N';
				$status = ((isset($data->terminado) && $data->terminado == 'y') ? 1 : '-3');
			}
			if (isset($data->guiafimada)) {
				if($_POST['idSAP']){
					$actividad = array("HandledByEmployee" => $_POST['idSAP'], "Closed" => "Y", "U_PorFirmar" => $firma, "EndDueDate" => date("Y-m-d"), "EndTime" => date("H:i:s"), "AttachmentEntry" => $data->guiafimada, "Notes" => $data->observacionfi, "U_EstadoFin" => $estadofintext, "U_GPSFin" => $data->latitudfi . ',' . $data->longitudfi, "U_OBSINTERNA" => $data->observacionint);
				}
				if ($data->opayu == 'S') {
					$actividad["U_TieneAyudante"] = $data->opayu;
					if (isset($data->idayud1) && !empty($data->idayud1)) {
						$actividad["U_AYUDANTE1"] = $data->idayud1;
					}
					if (isset($data->idayud2) && !empty($data->idayud2)) {
						$actividad["U_AYUDANTE2"] = $data->idayud2;
					}
				}
				$actividad = json_encode($actividad);
			} else {
				if($_POST['idSAP']){
					$actividad = array("HandledByEmployee" => $_POST['idSAP'], "Closed" => "Y", "U_PorFirmar" => $firma, "EndDueDate" => date("Y-m-d"), "EndTime" => date("H:i:s"), "Notes" => $data->observacionfi, "U_EstadoFin" => $estadofintext, "U_GPSFin" => $data->latitudfi . ',' . $data->longitudfi, "U_OBSINTERNA" => $data->observacionint);
				}
				if ($data->opayu == 'S') {
					$actividad["U_TieneAyudante"] = $data->opayu;
					if (isset($data->idayud1) && !empty($data->idayud1)) {
						$actividad["U_AYUDANTE1"] = $data->idayud1;
					}
					if (isset($data->idayud2) && !empty($data->idayud2)) {
						$actividad["U_AYUDANTE2"] = $data->idayud2;
					}
				}
				$actividad = json_encode($actividad);
			}
			//echo '<pre>';print_r($actividad);echo '</pre><br><br>'.$terceros;die;
			$rsptaactv = EditardatosNum($entity, $id, $actividad);

			//LOG
			$logFile = fopen("log/log.txt", 'a') or die("Error creando archivo");
			fwrite($logFile, "\n".date("d/m/Y H:i:s")." - Actividad  : ".$entity." - ID :".$id." - Info :".$actividad) or die("Error escribiendo en el archivo");
			fclose($logFile);  

			$actividadID = $data->actividadIDfi !== null || $data->actividadIDfi !== '' ? intval($data->actividadIDfi) : 0;
			$sql = "INSERT INTO logactividad (actividadID,data) VALUES ($actividadID,'$actividad')";
			ejecutarConsulta($sql);
			error_log($sql);
			//LOG
			$logFile = fopen("log/log.txt", 'a') or die("Error creando archivo");
			fwrite($logFile, "\n".date("d/m/Y H:i:s")." - Log Actividad  : ".ejecutarConsulta($sql)." - ActividadID ".$actividadID." - Actividad ".$actividad ) or die("Error escribiendo en el archivo");
			fclose($logFile);

			$entity = 'CustomerEquipmentCards';
			$select = '*';
			$filter = "CustomerCode eq '".$data->customercodefi."' and InternalSerialNum eq '".$data->codigoEquipo."'";
			$equipo = json_decode(ConsultaEntity($entity,$select,$filter), true);
			$idascensor = $equipo['value'][0]['EquipmentCardNum'];
			//LOG
			$logFile = fopen("log/log.txt", 'a') or die("Error creando archivo");
			fwrite($logFile, "\n".date("d/m/Y H:i:s")." - ID Ascensor  : ".$idascensor ) or die("Error escribiendo en el archivo");
			fclose($logFile);  
			
			$entity = 'CustomerEquipmentCards';
			$id = $idascensor;
			$servicecall = json_encode(array("U_NX_ESTADOFM" => $data->idestadofi));
			$editarEquipo = EditardatosNum($entity, $id, $servicecall);
			error_log($editarEquipo);
			//LOG
			$logFile = fopen("log/log.txt", 'a') or die("Error creando archivo");
			fwrite($logFile, "\n".date("d/m/Y H:i:s")." - Editar Tarjeta de Equipo  : ".$editarEquipo." - Entidad ".$entity." - ID ".$id." - Servicecall ".$servicecall) or die("Error escribiendo en el archivo");
			fclose($logFile);  


			$entity = 'ServiceCalls';
			$id = intval($data->servicecallIDfi);
			$servicecall = json_encode(array("Status" => $status, "U_FallaTercero" => $terceros));
			$EditarServicio = EditardatosNum($entity, $id, $servicecall);
			error_log($EditarServicio);
			//LOG
			$logFile = fopen("log/log.txt", 'a') or die("Error creando archivo");
			fwrite($logFile, "\n".date("d/m/Y H:i:s")." - Editar Servicio  : ".$EditarServicio." - Entidad ".$entity." - ID ".$id." - Servicecall ".$servicecall) or die("Error escribiendo en el archivo");
			fclose($logFile);  

		}
		return true;
	}


	public function finalizarActividad($data, $actividadsap)
	{
		error_log("Estamos en funcion finalizarActividad de Modelo Servicio.php");
		error_log("El idSAP: ".$_POST['idSAP']);
		$data = json_decode($data);
		error_log("variable data en finalizarActividad: " . print_r($data, true));
		if (isset($data->estadofintext)) {
			$estadofintext = $data->estadofintext;
			error_log("variable estadofintext en finalizarActividad: " . $estadofintext);	
		} else {
			$estadofintext = $data->estadoascensor;
			error_log("variable estadofintext en finalizarActividad: " . $estadofintext);	
		}
		if (isset($data->terceros) && $data->terceros == "true") {
			$terceros = 'Si';
			error_log("variable tercero es SI: " . $terceros);
		} else {
			$terceros = 'No';
			error_log("variable tercero es NO: " . $terceros);
		}
		if ($data->oppre == 1) {
			error_log("Estamos en 1 de oppre");
			/*$presupuesto = json_encode(array("U_NX_AREA"=>"VTA_PPTO","OpportunityName"=>"GSE - ".$data->servicecallIDfi." - ".$data->actividadIDfi,"Remarks"=>$data->descripcion,"CardCode"=>$data->customercodefi,"U_NX_CODIGOFM"=>$data->codigofmfi,"AttachmentEntry"=>$data->attachments,"SalesOpportunitiesLines"=>array(array("MaxLocalTotal"=>"1.0"))));
						   $entity = 'SalesOpportunities';
						   $rsptapre = InsertarDatos($entity,$presupuesto);
						   $datosOpportunidad = json_decode($rsptapre);

						   $comercial = json_encode(array("DataOwnershipfield"=>$data->comercialID));
						   $id = $datosOpportunidad->SequentialNo;
						   $estexto = false;
						   Editardatos($entity,$id,$comercial,$estexto);*/
			$datapresupuesto = json_encode($data, JSON_UNESCAPED_UNICODE);
			echo $datapresupuesto;
			error_log("Variable datapresupuesto en finalizarActividad: " . $datapresupuesto);
			$actividadss = $data->actividadIDfi;
			$supervisorss = $data->supervisorID;
			$supervisorValue = $supervisorss !== null ? $supervisorss : NULL;
			$actividadID = $data->actividadIDfi !== null || $data->actividadIDfi !== '' ? intval($data->actividadIDfi) : 0;
			error_log("data actividadIDfi: " . $actividadss." data supervisorID: ".$supervisorss);
			echo $sql = "INSERT INTO presupuesto_sap (actividadID, supervisorID, informacion) VALUES ($actividadID,$supervisorValue,'$datapresupuesto')";
			ejecutarConsulta($sql);
			if(ejecutarConsulta($sql)){
				error_log("Se inserto correctamente en presupuesto_sap");
			}else{
				error_log("No se inserto correctamente en presupuesto_sap: ");
			}
			$entity = 'Activities';
			$id = $data->actividadIDfi;
			error_log("id actividad: ".$id);
			error_log("data opfirma: ".$data->opfirma);
			if ($data->opfirma == 2) {
				$firma = 'Y';
				$status = 5;
				error_log("la opfirma que viene de data es 2m firma queda en Y y status es 5");
			} else {
				$firma = 'N';
				$status = ((isset($data->terminado) && $data->terminado == 'y') ? 1 : '-3');
				error_log("la opfirma que viene de data es 1 o 3 firma queda en N y status es: ".$status);

			}
			error_log("data guiafirmada: ".$data->guiafimada); 
			if (isset($data->guiafimada)) {
				error_log("guiafirmada que viene de data no es vacia");
				//$actividad = array("HandledByEmployee"=>$_SESSION['idSAP'],"Closed"=>"Y","U_PorFirmar"=>$firma,"EndDueDate"=>date("Y-m-d"),"EndTime"=>date("H:i:s"),"AttachmentEntry"=>$data->guiafimada,"U_NX_OPP"=>$datosOpportunidad->SequentialNo,"Notes"=>$data->txtObsFin,"U_EstadoFin"=>$data->estadoascensor,"U_GPSFin"=>$data->latitudfi.','.$data->longitudfi,"U_OBSINTERNA"=>$data->observacionint);
				if($_SESSION['idSAP']){
					$actividad = array("HandledByEmployee" => $_SESSION['idSAP'], "Closed" => "Y", "U_PorFirmar" => $firma, "EndDueDate" => date("Y-m-d"), "EndTime" => date("H:i:s"), "AttachmentEntry" => $data->guiafimada, "Notes" => $data->observacionfi, "U_EstadoFin" => $estadofintext, "U_GPSFin" => $data->latitudfi . ',' . $data->longitudfi, "U_OBSINTERNA" => $data->observacionint);	
					error_log("variable acrividad de presupuesto: ".print_r($actividad, true));
				}elseif($_POST['idSAP']){
					$actividad = array("HandledByEmployee" => $_POST['idSAP'], "Closed" => "Y", "U_PorFirmar" => $firma, "EndDueDate" => date("Y-m-d"), "EndTime" => date("H:i:s"), "AttachmentEntry" => $data->guiafimada, "Notes" => $data->observacionfi, "U_EstadoFin" => $estadofintext, "U_GPSFin" => $data->latitudfi . ',' . $data->longitudfi, "U_OBSINTERNA" => $data->observacionint);
					error_log("variable acrividad de presupuesto: ".print_r($actividad, true));
				}
				error_log("data opayu: ".$data->opayu);
				error_log("data idayud1: ".$data->idayud1);
				error_log("data idayud2: ".$data->idayud2);
				if ($data->opayu == 'S') {
					error_log("opayu es S, existen ayudantes");
					$actividad["U_TieneAyudante"] = $data->opayu;
					if (isset($data->idayud1) && !empty($data->idayud1)) {
						$actividad["U_AYUDANTE1"] = $data->idayud1;
					}
					if (isset($data->idayud2) && !empty($data->idayud2)) {
						$actividad["U_AYUDANTE2"] = $data->idayud2;
					}
				}
				$actividad = json_encode($actividad);
				error_log("Variable actividad en finalizarActividad: " . $actividad);
			} else {
				if($_SESSION['idSAP']){
					$actividad = array("HandledByEmployee" => $_SESSION['idSAP'], "Closed" => "Y", "U_PorFirmar" => $firma, "EndDueDate" => date("Y-m-d"), "EndTime" => date("H:i:s"), "Notes" => $data->observacionfi, "U_EstadoFin" => $estadofintext, "U_GPSFin" => $data->latitudfi . ',' . $data->longitudfi, "U_OBSINTERNA" => $data->observacionint);
					error_log("variable acrividad de presupuesto: ".$actividad);
				}elseif($_POST['idSAP']){
					$actividad = array("HandledByEmployee" => $_POST['idSAP'], "Closed" => "Y", "U_PorFirmar" => $firma, "EndDueDate" => date("Y-m-d"), "EndTime" => date("H:i:s"), "Notes" => $data->observacionfi, "U_EstadoFin" => $estadofintext, "U_GPSFin" => $data->latitudfi . ',' . $data->longitudfi, "U_OBSINTERNA" => $data->observacionint);
					error_log("variable acrividad de presupuesto: ".$actividad);
				}
				if ($data->opayu == 'S') {
					$actividad["U_TieneAyudante"] = $data->opayu;
					if (isset($data->idayud1) && !empty($data->idayud1)) {
						$actividad["U_AYUDANTE1"] = $data->idayud1;
					}
					if (isset($data->idayud2) && !empty($data->idayud2)) {
						$actividad["U_AYUDANTE2"] = $data->idayud2;
					}
				}
				$actividad = json_encode($actividad);
				error_log("Variable actividad post ayudante: " . $actividad);
			}
			$rsptaactv = EditardatosNum($entity, $id, $actividad);
			error_log("rsptaactv: ".$rsptaactv);

			$actividadID = $data->actividadIDfi !== null || $data->actividadIDfi !== '' ? intval($data->actividadIDfi) : 0;
			$sql = "INSERT INTO logactividad (actividadID,data) VALUES ($actividadID,'$actividad')";
			ejecutarConsulta($sql);

			$entity = 'CustomerEquipmentCards';
			$id = $data->ascensorIDfi;
			error_log("id ascensor: ".$id);
			$servicecall = json_encode(array("U_NX_ESTADOFM" => $data->idestadofi));
			error_log("variable servicecall: ".$servicecall);
			$rsptaservcall = EditardatosNum($entity, $id, $servicecall);
			error_log("rsptaservcall 1: ".$rsptaservcall);

			$entity = 'ServiceCalls';
			$id = $data->servicecallIDfi;
			error_log("nuevo id: ".$id);
			$servicecall = json_encode(array("Status" => $status, "U_FallaTercero" => $terceros));
			error_log("nuevo servicecakk: ".$servicecall);
			$rsptaservcall = EditardatosNum($entity, $id, $servicecall);
			error_log("rsptaservcall 2: ".$rsptaservcall);

		} else {
			error_log("estamos fuera de la opcion 1 de oppre");
			error_log("data estadofintext: ".$data->estadofintext);
			error_log("data estadoascensor: ".$data->estadoascensor);
			if (isset($data->estadofintext)) {
				$estadofintext = $data->estadofintext;
			} else {
				$estadofintext = $data->estadoascensor;
			}
			$entity = 'Activities';
			$id = $data->actividadIDfi;
			error_log("id actividad en el else oppre: ".$id);
			error_log("data opfirma en el else oppre: ".$data->opfirma);
			if ($data->opfirma == 2) {
				$firma = 'Y';
				$status = 5;
			} else {
				$firma = 'N';
				$status = ((isset($data->terminado) && $data->terminado == 'y') ? 1 : '-3');
			}
			error_log("data guiafimada en el else oppre: ".$data->guiafimada);
			if (isset($data->guiafimada)) {
				//$mifecha= date('Y-m-d H:i:s'); 
				//$NuevaFecha = strtotime ( '-4 hour' , strtotime ($mifecha) ) ; 

				if($_SESSION['idSAP']){
					$actividad = array("HandledByEmployee" => $_SESSION['idSAP'], "Closed" => "Y", "U_PorFirmar" => $firma, "EndDueDate" => date("Y-m-d"), "EndTime" => date("H:i:s"), "AttachmentEntry" => $data->guiafimada, "Notes" => $data->observacionfi, "U_EstadoFin" => $estadofintext, "U_GPSFin" => $data->latitudfi . ',' . $data->longitudfi, "U_OBSINTERNA" => $data->observacionint);
					error_log("variable actividad en el else oppre: ".$actividad);
				}elseif($_POST['idSAP']){
					$actividad = array("HandledByEmployee" => $_POST['idSAP'], "Closed" => "Y", "U_PorFirmar" => $firma, "EndDueDate" => date("Y-m-d"), "EndTime" => date("H:i:s"), "AttachmentEntry" => $data->guiafimada, "Notes" => $data->observacionfi, "U_EstadoFin" => $estadofintext, "U_GPSFin" => $data->latitudfi . ',' . $data->longitudfi, "U_OBSINTERNA" => $data->observacionint);
					error_log("variable actividad en el else oppre: ".$actividad);
				}
				error_log("data opayu en el else oppre: ".$data->opayu);
				if ($data->opayu == 'S') {
					$actividad["U_TieneAyudante"] = $data->opayu;
					error_log("data idayud1 en el else oppre: ".$data->idayud1);
					error_log("data idayud2 en el else oppre: ".$data->idayud2);
					if (isset($data->idayud1) && !empty($data->idayud1)) {
						$actividad["U_AYUDANTE1"] = $data->idayud1;
					}
					if (isset($data->idayud2) && !empty($data->idayud2)) {
						$actividad["U_AYUDANTE2"] = $data->idayud2;
					}
				}
				$actividad = json_encode($actividad);
				error_log("variable actividad en el else oppre: ".$actividad);
			} else {
				//$mifecha= date('Y-m-d H:i:s'); 
				//$NuevaFecha = strtotime ( '-4 hour' , strtotime ($mifecha) ) ; 
				if($_SESSION['idSAP']){
					$actividad = array("HandledByEmployee" => $_SESSION['idSAP'], "Closed" => "Y", "U_PorFirmar" => $firma, "EndDueDate" => date("Y-m-d"), "EndTime" => date("H:i:s"), "Notes" => $data->observacionfi, "U_EstadoFin" => $estadofintext, "U_GPSFin" => $data->latitudfi . ',' . $data->longitudfi, "U_OBSINTERNA" => $data->observacionint);
					error_log("variable actividad en el else oppre y sin firma: ".$actividad);
				}if($_POST['idSAP']){
					$actividad = array("HandledByEmployee" => $_POST['idSAP'], "Closed" => "Y", "U_PorFirmar" => $firma, "EndDueDate" => date("Y-m-d"), "EndTime" => date("H:i:s"), "Notes" => $data->observacionfi, "U_EstadoFin" => $estadofintext, "U_GPSFin" => $data->latitudfi . ',' . $data->longitudfi, "U_OBSINTERNA" => $data->observacionint);
					error_log("variable actividad en el else oppre y sin firma: ".$actividad);
				}
				error_log("data opayu en  el else oppre y sin firma: ".$data->opayu);
				error_log("data idayud1 en  el else oppre y sin firma: ".$data->idayud1);
				error_log("data idayud2 en  el else oppre y sin firma: ".$data->idayud2);
				if ($data->opayu == 'S') {
					$actividad["U_TieneAyudante"] = $data->opayu;
					if (isset($data->idayud1) && !empty($data->idayud1)) {
						$actividad["U_AYUDANTE1"] = $data->idayud1;
					}
					if (isset($data->idayud2) && !empty($data->idayud2)) {
						$actividad["U_AYUDANTE2"] = $data->idayud2;
					}
				}
				$actividad = json_encode($actividad);
				error_log("variable actividad en el else oppre y sin firma: ".$actividad);
			}
			//echo '<pre>';print_r($actividad);echo '</pre><br><br>'.$terceros;die;
			$rsptaactv = EditardatosNum($entity, $id, $actividad);
			error_log("variable rsptaactv en  el else oppre y sin firma: ".$rsptaactv);

			$actividadID = $data->actividadIDfi !== null || $data->actividadIDfi !== '' ? intval($data->actividadIDfi) : 0;
			$sql = "INSERT INTO logactividad (actividadID,data) VALUES ($actividadID,'$actividad')";
			ejecutarConsulta($sql);

			$entity = 'CustomerEquipmentCards';
			error_log("id ascensor  en el else oppre y sin firma: ".$data->ascensorIDfi);
			$id = $data->ascensorIDfi;
			$servicecall = json_encode(array("U_NX_ESTADOFM" => $data->idestadofi));
			error_log("variable servicecall en  el else oppre y sin firma: ".$servicecall);
			$rsptaservcall = EditardatosNum($entity, $id, $servicecall);
			error_log("variable rsptaservcall en  el else oppre y sin firma: ".$rsptaservcall);

			$entity = 'ServiceCalls';
			$id = $data->servicecallIDfi;
			$servicecall = json_encode(array("Status" => $status, "U_FallaTercero" => $terceros));
			error_log("variable servicecall en  el else oppre y sin firma: ".$servicecall);
			$rsptaservcall = EditardatosNum($entity, $id, $servicecall);
			error_log("variable rsptaservcall en  el else oppre y sin firma: ".$rsptaservcall);
		}
		return true;
	}

	public function SelectTecnico()
	{
		$query = '$crossjoin(EmployeesInfo, EmployeesInfo/EmployeeRolesInfoLines, EmployeeRolesSetup)?$expand=EmployeesInfo($select=EmployeeID,FirstName,LastName)&$filter=EmployeesInfo/EmployeeID eq EmployeesInfo/EmployeeRolesInfoLines/EmployeeID and EmployeesInfo/EmployeeRolesInfoLines/RoleID eq EmployeeRolesSetup/TypeID and EmployeeRolesSetup/TypeID eq -2';

		$response = Query($query); 

		// Imprimir la respuesta en el registro de errores (error_log)
		error_log("Respuesta de SelectTecnico: " . json_encode($response));
		return json_decode(Query($query), true);
	}

	public function UsuarioCompleto($idSAP)
	{
		$query = '$crossjoin(EmployeesInfo, EmployeesInfo/EmployeeRolesInfoLines, EmployeeRolesSetup)?$expand=EmployeesInfo($select=EmployeeID,FirstName,LastName)&$filter=EmployeesInfo/EmployeeID eq EmployeesInfo/EmployeeRolesInfoLines/EmployeeID and EmployeesInfo/EmployeeRolesInfoLines/RoleID eq EmployeeRolesSetup/TypeID and EmployeeRolesSetup/TypeID eq -2';

		$entity = 'EmployeesInfo';
		$select = 'LastName,FirstName';
		$filter = 'EmployeeID eq ' . $idSAP;
		return json_decode(ConsultaEntity($entity, $select, $filter), true);
	}


	public function LSFSAP($id)
	{
		$sql = "sml.svc/LISTA_ACTIVIDADES?\$select=srvCodigo,actCodigo,srvTipoLlamada,equSnInterno,equEdificio,actFecha,actFechaIni,actHoraIni,actFechaFin,actHoraFin&\$filter=actPorFirmar eq 'Y' and actEmplAsistId eq ". $id;
		return json_decode(Query($sql), true);
	}

	public function LSPPTOPEND($fm)
	{
		$entity = 'SalesOpportunities';
		$select = '*';
		$filter = 'ClosingPercentage ne 100.0 and U_NX_CODIGOFM eq \'' . $fm . '\'';
		return json_decode(ConsultaEntity($entity, $select, $filter), true);
	}

	public function finalizarActividadPorFirmarM($data, $srvCodigo)
	{
		$data = json_decode($data);
		//echo '<pre>';print_r($data);echo '</pre>';die;

		$entity = 'Activities';
		$id = $data->idactividad;
		$firma = 'N';
		$status = 1;

		if (isset($data->guiafimada)) {
			$actividad = json_encode(array("Closed" => "Y", "U_PorFirmar" => $firma, "AttachmentEntry" => $data->guiafimada));
		} else {
			$actividad = json_encode(array("Closed" => "Y", "U_PorFirmar" => $firma));
		}

		$abrir = json_encode(array("Closed" => "N"));
		EditardatosNum($entity, $id, $abrir);
		$rsptaactv = EditardatosNum($entity, $id, $actividad);

		$entity = 'ServiceCalls';
		$id = $data->idserfirma;
		$servicecall = json_encode(array("Status" => $status));
		$rsptaservcall = EditardatosNum($entity, $id, $servicecall);
		return true;
	}

	public function finalizarActividadPorFirmar($data)
	{
		$data = json_decode($data);
		//echo '<pre>';print_r($data);echo '</pre>';die;

		$entity = 'Activities';
		$id = $data->idactividad;
		$firma = 'N';
		$status = 1;

		if (isset($data->guiafimada)) {
			$actividad = json_encode(array("Closed" => "Y", "U_PorFirmar" => $firma, "AttachmentEntry" => $data->guiafimada));
		} else {
			$actividad = json_encode(array("Closed" => "Y", "U_PorFirmar" => $firma));
		}

		$abrir = json_encode(array("Closed" => "N"));
		EditardatosNum($entity, $id, $abrir);
		$rsptaactv = EditardatosNum($entity, $id, $actividad);

		$entity = 'ServiceCalls';
		$id = $data->idserfirma;
		$servicecall = json_encode(array("Status" => $status));
		$rsptaservcall = EditardatosNum($entity, $id, $servicecall);
		return true;
	}

	public function listarobservacionfin()
	{
		$sql = "SELECT idobservaciones_cierre_gse, UPPER(descripcion) descripcion FROM observaciones_cierre_gse WHERE condicion = 1;";
		return ejecutarConsulta($sql);
	}

	public function formfirmasap($idactividad)
	{
		$sql = "sml.svc/LISTA_ACTIVIDADES?\$select=srvCodigo,actCodigo,equSnInterno,artTipoEquipo,artFabricante,artModelo,equEdificio,equCalle,equCalleNro,actFechaIni,actHoraIni,srvTipoLlamada,actEstEquiIni,srvAsunto,actFechaFin,actHoraFin,actEstEquiFin,actComentario,equSupId&\$filter=actCodigo eq " . $idactividad;
		return json_decode(Query($sql), true);
	}

	public function formfirmasap2($idservicio)
	{
		$sql = "sml.svc/LISTA_ACTIVIDADES?\$select=srvCodigo,actCodigo,equSnInterno,artTipoEquipo,artFabricante,artModelo,equEdificio,equCalle,equCalleNro,actFechaIni,actHoraIni,srvTipoLlamada,actEstEquiIni,srvAsunto,actFechaFin,actHoraFin,actEstEquiFin,actComentario,equSupId&\$filter=srvCodigo eq " . $idservicio;
		return json_decode(Query($sql), true);
	}

	public function subirArchivosSap($files, $timestamp = false)
	{
		return UploadFile($files, $timestamp);
	}

	public function Actividad($actividadID)
	{
		$sql = "sml.svc/LISTA_ACTIVIDADES?\$filter=actCodigo eq " . $actividadID;
		$result = print_r(json_decode(Query($sql), true), true);
		error_log("resultado de /modelos/Servicio.php>Actividad: " . $result);
		return json_decode(Query($sql), true);
	}

	public function SelectCliente()
	{
		$entity = 'BusinessPartners';
		$select = 'CardCode,CardName';
		$filter = "startswith(CardCode,'C')";
		return json_decode(ConsultaEntity($entity, $select, $filter), true);
	}

	public function SelectEdificio($cliente)
	{
		$entity = 'CustomerEquipmentCards';
		$select = 'InternalSerialNum,InstallLocation';
		$filter = "startswith(CustomerCode,'C" . $cliente . "')";
		return json_decode(ConsultaEntity($entity, $select, $filter), true);
	}

	public function SelectClientes($fm, $code)
	{
		$entity = 'CustomerEquipmentCards';
		$select = 'InternalSerialNum,CustomerCode,CustomerName,InstallLocation';
		$filter = "InternalSerialNum eq '$fm' and CustomerCode eq '$code'";
		return json_decode(ConsultaEntity($entity, $select, $filter), true);
	}

	public function CrearLlamada($data)
	{
		/*insertar nueva llamada de servicio*/
		$entity = 'ServiceCalls';
		$data = json_encode(
			array(
				"Subject" => $data['subject'],
				"CustomerCode" => $data['customercode'],
				"InternalSerialNum" => $data['fm'],
				"ItemCode" => $data['itemcode'],
				"Priority" => "M",
				"CallType" => "4",
				"Origin" => "-3",
				"Status" => 4
			)
		);

		return InsertarDatos($entity, $data);
	}

	public function guiaPorCerrar($idactividad)
	{ //0 = no se puede cerrar, 1 = se puede cerrar
		$sql = "sml.svc/LISTA_ACTIVIDADES?\$filter=actCodigo eq " . $idactividad;
		$response = json_decode(Query($sql), true);
		error_log("Respuesta de /moddelos/Servicio.php>guiaPorCerrar: " . print_r($response, true));
		return json_decode(Query($sql), true);

		/*$sql = "SELECT (CASE WHEN (RES.tipoequipo='ASCENSOR' AND RES.tiposervicio='MANTENCION') THEN CASE WHEN RES.fechaactual>=date_add(RES.fechacreacion,INTERVAL RES.minutosespera MINUTE) THEN 1 ELSE 0 END ELSE 1 END) AS cerrarguia,date_add(RES.fechacreacion,INTERVAL RES.minutosespera MINUTE) AS fechamincierre FROM (SELECT A.paradas*{$minutosXparada} AS minutosespera,TA.nombre AS tipoequipo,TS.nombre AS tiposervicio,S.created_time AS fechacreacion,CURRENT_TIMESTAMP () AS fechaactual FROM ascensor AS A INNER JOIN servicio AS S ON A.idascensor=S.idascensor INNER JOIN tascensor AS TA ON A.idtascensor=TA.idtascensor INNER JOIN tservicio AS TS ON S.idtservicio=TS.idtservicio WHERE S.idservicio={$idservicio}) AS RES";
					   return ejecutarConsultaSimpleFila($sql);*/
	}
}
?>