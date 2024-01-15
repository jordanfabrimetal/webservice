<?php 
	session_name('SESS_GSAP');
	session_start();
	require_once "../modelos/Servicio.php";
	require_once "../modelos/Ascensor.php";

	$servicio = new Servicio();
	$ascensor = new Ascensor();

	switch ($_GET["op"]) {
		case 'selectcliente':
			/*
			LISTADO DE TIPOS DE LLAMADOS EN SAP
			1 => "Mantención"
			2 => "Reparación"
			3 => "Reparación Mayor"
			4 => "Emergencia"
			5 => "Normalización"
			6 => "Apoyo especializado"
			7 => "Levantamiento Ingres"
			8 => "Revisión para firma"
			9 => "Acompañamiento Certi"
			10 => "Acompañamiento a otr"
			11 => "Charla de rescate"
			12 => "Auditoría mantencion"
			13 => "Auditoría técnica"
			14 => "Auditoría de segurid"
			*/
			$listado = array();
			$rspta = $servicio->SelectCliente();
			echo '<option value="" selected disabled>SELECCIONE CLIENTE</option>';
			foreach ($rspta['value'] as $val) {
				$val['CardCode'] = ltrim($val['CardCode'], 'C');
				$rut = explode("-",$val['CardCode']);
				$rut = $rut[0].'-'.$rut[1];
				if (!in_array($rut, $listado)){
					array_push($listado, $rut);
					echo '<option value='.$rut.'>'.$rut.' / '.$val['CardName'].'</option>';
				}
			}
		break;

		case 'selectedificio':
			$cliente = $_POST['cliente'];
			$rspta=$servicio->SelectEdificio($cliente);
			echo '<option value="" selected disabled>SELECCIONE EDIFICIO</option>';
			$listado = array();
			foreach ($rspta['value'] as $val) {
				if (!in_array($val['InstallLocation'], $listado)){
					array_push($listado, $val['InstallLocation']);
					echo '<option value='.str_replace(' ','##',$val['InstallLocation']).'>'.$val['InstallLocation'].'</option>';
				}
			}
		break;

		case 'listarfm':
			$cliente = $_GET['cliente'];
			$edificio = str_replace('##',' ',$_GET['edificio']);
			$cencosto = $_GET['cencosto'];
			$rspta=$ascensor->ListarFM($cliente,$edificio,$cencosto);
			$data = Array();
			foreach ($rspta['value'] as $reg) {
				$data[] = array(
					"0" => '<button class="btn btn-info btn-xs" onclick="generarllamada(\'' . $reg['CustomerEquipmentCards']['InternalSerialNum'] . '\')"><i class="fa fa-exclamation-triangle"></i></button>',
					"1" => $reg['CustomerEquipmentCards']['InternalSerialNum'],
					"2" => $reg['CustomerEquipmentCards']['CustomerCode'].' / '.$reg['CustomerEquipmentCards']['CustomerName'],
					"3" => $reg['CustomerEquipmentCards']['InstallLocation'],
					"4" => $reg['CustomerEquipmentCards']['Street'].' '.$reg['CustomerEquipmentCards']['StreetNo'],
					"5" => $reg['U_NX_ESTADOS_FM']['Name']
					
				);
			}
			$results = array(
				"sEcho" => 1,
				"iTotalRecords" => count($data),
				"iTotalDisplayRecords" => count($data),
				"aaData" => $data
			);

			echo json_encode($results);
		break;

		case 'crearllamada':
			$rspta = $servicio->CrearLlamada($_POST);
        	echo $rspta ? "Servicio iniciado con exito" : "El servicio no pudo ser iniciado";
		break;
	}
?>