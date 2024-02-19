<?php 
session_name('SESS_GSAP');
session_start();
require_once "../modelos/Ascensor.php";

$ascensor = new Ascensor();

//Datos desde el formulario - Editar ascensor
$idascensor = isset($_POST["idascensor"]) ? limpiarCadena($_POST["idascensor"]) : "";
$idtascensor = isset($_POST["idtascensor"]) ? limpiarCadena($_POST["idtascensor"]) : "";
$marca = isset($_POST["marca"]) ? limpiarCadena($_POST["marca"]) : "";
$modelo = isset($_POST["modelo"]) ? limpiarCadena($_POST["modelo"]) : "";
$ken = isset($_POST["ken"]) ? limpiarCadena($_POST["ken"]) : "";
$pservicio = isset($_POST["pservicio"]) ? limpiarCadena($_POST["pservicio"]) : "";
$gtecnica = isset($_POST["gtecnica"]) ? limpiarCadena($_POST["gtecnica"]) : "";
$valoruf = isset($_POST["valoruf"]) ? limpiarCadena($_POST["valoruf"]) : "";
$valorclp = isset($_POST["valorclp"]) ? limpiarCadena($_POST["valorclp"]) : "";
$paradas = isset($_POST["paradas"]) ? limpiarCadena($_POST["paradas"]) : "";
$capkg = isset($_POST["capkg"]) ? limpiarCadena($_POST["capkg"]) : "";
$capper = isset($_POST["capper"]) ? limpiarCadena($_POST["capper"]) : "";
$velocidad = isset($_POST["velocidad"]) ? limpiarCadena($_POST["velocidad"]) : "";
$dcs = isset($_POST["dcs"]) ? limpiarCadena($_POST["dcs"]) : "";
$elink = isset($_POST["elink"]) ? limpiarCadena($_POST["elink"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$servicecall = isset($_POST["servicecall"]) ? limpiarCadena($_POST["servicecall"]) : "";

switch ($_GET["op"]) {
	
            case 'solid_edifid':
            $idcontrato = $_GET["id"]; 
            $rspta=$ascensor->solid_ascid($idcontrato);
            $ascensores = Array();
		while ($reg = $rspta->fetch_object()){
			$ascensores[] = array(
					"0"=>$reg->idascensor,
					"1"=>$reg->nombre.", ".$reg->calle." ".$reg->numero,
					"2"=>$reg->region_ordinal,
					"3"=>$reg->marca,
					"4"=>$reg->modelo
				);
		}

		$results = array(
				"ascensores"=>$ascensores,
				"nascensores"=>count($ascensores)
			);
                
		echo json_encode($results);
		break;


	case 'listarsoid':
	    $rspta=$contrato->listarsolid();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
					"0"=>'<button class="btn btn-info btn-xs" onclick="mostar('.$reg->idcontrato.')" data-tooltip="tooltip" title="Asignar IDs" ><i class="fa fa-hashtag"></i></button>',
					"1"=>$reg->ncontrato,
					"2"=>$reg->fecha,
					"3"=>$reg->region_nombre.' - '.$reg->region_ordinal,
					"4"=>$reg->nedificios,
					"5"=>$reg->nascensores
				);
		}
		$results = array(
				"sEcho"=>1,
				"iTotalRecords"=>count($data),
				"iTotalDisplayRecords"=>count($data), 
				"aaData"=>$data
			);

		echo json_encode($results);
		break;
        
        case 'InsertarIds':
            $idascensor = $_GET["id"]; 
            $iduser=$_SESSION['iduser'];
            $idascensores = $_POST['idascensor'];
            $codigos = $_POST['codigo'];
            foreach( $idascensores as $index => $idascensor ) {
                $ascensor->InsertarIds($idascensor, $codigos[$index], $iduser);
            }
            echo "Identificadores registrados";

        break;
        
        case 'listarascensor':
        $rspta = $ascensor->listar();
        $data = Array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-info btn-xs" onclick="mostrar(' . $reg->idascensor . ')"><i class="fa fa-list-alt"></i></button><button class="btn btn-info btn-xs" onclick="editar(' . $reg->idascensor . ')"><i class="fa fa-pencil"></i></button>',
                "1" => $reg->codigo,
                "2" => $reg->edificio,
                "3" => $reg->contrato,
                "4" => $reg->valoruf,
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
        
        
        case 'editar':
        $iduser = $_SESSION['iduser'];
        if (!empty($idascensor)) {
            $rspta = $ascensor->editar($idascensor, $idtascensor, $marca, $modelo, $ken, $pservicio, $gtecnica, $valoruf, $valorclp, $paradas, $capkg, $capper, $velocidad, $dcs, $elink, $iduser);
            echo $rspta ? "Ascensor editado" : "Ascensor no pudo ser editado";
        }
        break;
        
        case 'mostrar':
        $dascensor = $ascensor->mostrar($idascensor);

        if ($dascensor['dcs'] = 1) {
            $dascensor['dcs'] = "SI";
        } else {
            $dascensor['dcs'] = "NO";
        }

        if ($dascensor['elink'] = 1) {
            $dascensor['elink'] = "SI";
        } else {
            $dascensor['elink'] = "NO";
        }

        if (is_null($dascensor['codigo'])) {
            $dascensor['codigo'] = "S/C";
        }
        
        if (is_null($dascensor['ken'])) {
            $dascensor['ken'] = "S/C";
        }
        
        if (is_null($dascensor['valorclp'])) {
            $dascensor['valorclp'] = "-";
        }
        
        if (is_null($dascensor['paradas'])) {
            $dascensor['paradas'] = "-";
        }
        
        if (is_null($dascensor['capper'])) {
            $dascensor['capper'] = "-";
        }
        
        if (is_null($dascensor['capkg'])) {
            $dascensor['capkg'] = "-";
        }
        
        if (is_null($dascensor['velocidad'])) {
            $dascensor['velocidad'] = "-";
        }
        
        if (is_null($dascensor['gtecnica'])) {
            $dascensor['gtecnica'] = "S/F";
        }
        
        if (is_null($dascensor['pservicio'])) {
            $dascensor['pservicio'] = "S/F";
        }

        echo json_encode($dascensor);
        break;
        
        case 'formeditar':
        $rspta = $ascensor->formeditar($idascensor);
        echo json_encode($rspta);
        break;
    
        case 'dguia':
        $rspta = $ascensor->guia($codigo);
        echo json_encode($rspta);
        break;
    
        case 'selecttipollamada':
            /*
                LISTADO DE TIPOS DE LLAMADOS EN SAP
                1 = "Mantención"
                2 = "Reparación"
                3 = "Reparación Mayor"
                4 = "Emergencia"
                5 = "Normalización"
                6 = "Apoyo especializado"
                7 = "Levantamiento Ingres"
                8 = "Revisión para firma"
                9 = "Acompañamiento Certi"
                10 = "Acompañamiento a otr"
                11 = "Charla de rescate"
                12 = "Auditoría mantencion"
                13 = "Auditoría técnica"
                14 = "Auditoría de segurid"
                15 = "Reclamo"
                16 = "Ingeniería de campo"
                17 = "Visita"
                18 = "Modernización"
            */
            $listadoValido = array(1,2,4,5,13,16,17,18);
            $rspta = $ascensor->SelectTipoServicio();
            echo '<option value="" selected disabled>SELECCIONE TIPO SERVICIO</option>';
            foreach ($rspta['value'] as $val) {
                if (in_array($val['CallTypeID'], $listadoValido)){
                    echo '<option value='.$val['CallTypeID'].'>'.$val['Name'].'</option>';
                }
            }
        break;

        
        case 'selecttipollamadaandroid':
            /*
                LISTADO DE TIPOS DE LLAMADOS EN SAP
                1 = "Mantención"
                2 = "Reparación"
                3 = "Reparación Mayor"
                4 = "Emergencia"
                5 = "Normalización"
                6 = "Apoyo especializado"
                7 = "Levantamiento Ingres"
                8 = "Revisión para firma"
                9 = "Acompañamiento Certi"
                10 = "Acompañamiento a otr"
                11 = "Charla de rescate"
                12 = "Auditoría mantencion"
                13 = "Auditoría técnica"
                14 = "Auditoría de segurid"
                15 = "Reclamo"
                16 = "Ingeniería de campo"
                17 = "Visita"
                18 = "Modernización"
            */
            $listadoValido = array(1, 2, 4, 5, 17, 18);
            $rspta = $ascensor->SelectTipoServicio();
            $opciones = array();
        
            foreach ($rspta['value'] as $val) {
                if (in_array($val['CallTypeID'], $listadoValido)) {
                    $opcion = array(
                        'CallTypeID' => $val['CallTypeID'],
                        'Name' => $val['Name']
                    );
                    $opciones[] = $opcion;
                }
            }
        
            // Devuelve las opciones del código en formato JSON
            echo json_encode($opciones);
            break;
        

        case 'selectascfiltro':
            echo '<option value="" selected disabled>SELECCIONE EQUIPO</option>';
            error_log("El id del servicio es: ".$_POST['idservicio']);
            if($_POST['idservicio'] == 17){
                $rspta=$ascensor->SelectVisita();
                foreach($rspta as $val){
                    echo '<option value='.$val['InternalSerialNum'].'>'.$val['InternalSerialNum'].' - '.$val['BuildingFloorRoom'].'</option>';
                }
            }
            else if($_POST['idservicio'] == 5){
                $rspta=$ascensor->SelectNormalizacion();
                foreach($rspta as $val){
                    echo '<option value='.$val['InternalSerialNum'].'>'.$val['InternalSerialNum'].' - '.$val['BuildingFloorRoom'].'</option>';
                }
            }
            else if($_POST['idservicio'] != 4){
                $_POST['idservicio'];
                $rspta=$ascensor->SelectAscensorServicio($_POST['idservicio']);
                //print_r($rspta);
                foreach($rspta as $val){
                    echo '<option paradas="'.$val['Paradas'].'" tipoequipo="'.$val['TipoEquipo'].'" value='.$val['ServiceCallID'].'>'.$val['InternalSerialNum'].' - '.$val['BuildingFloorRoom'].'</option>';
                }
            }else{
                $dataJSON = $ascensor->Holidays();
				$feriados = array();
				foreach ($dataJSON['HolidayDates'] as $val) {
					if($val['StartDate'] == $val['EndDate']){
						array_push($feriados, $val['StartDate']);
					}else{
						$fechaInicio=strtotime($val['StartDate']);
						$fechaFin=strtotime($val['EndDate']);
						for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
							array_push($feriados, date("Y-m-d", $i));
						}
					}
				}

				$dia = date('Y-m-d');
				$hora = date('H:i');
				if(in_array($dia, $feriados) || date('N',strtotime($dia)) >= 6){
					$currentTime = strtotime($dia.' '.$hora);
					$startTime = strtotime($dia.' 08:00');
					$endTime = strtotime($dia.' 17:59');
					if($currentTime >= $startTime && $currentTime <= $endTime){
					    $rspta=$ascensor->SelectEmergenciaSAP();
						foreach($rspta as $val){
							echo '<option value='.$val['InternalSerialNum'].'>'.$val['InternalSerialNum'].' - '.$val['BuildingFloorRoom'].'</option>';
						}
					}else{
						$rspta=$ascensor->SelectEmergencia();
						foreach($rspta as $val){
							echo '<option value='.$val['InternalSerialNum'].'>'.$val['InternalSerialNum'].' - '.$val['BuildingFloorRoom'].'</option>';
						}
					}
				}else{
					$currentTime = strtotime($dia.' '.$hora);
					$startTime = strtotime($dia.' 00:00');
					$endTime = strtotime($dia.' 06:59');
					if($currentTime >= $startTime && $currentTime <= $endTime){
						$rspta=$ascensor->SelectEmergencia();
						foreach($rspta as $val){
							echo '<option value='.$val['InternalSerialNum'].'>'.$val['InternalSerialNum'].' - '.$val['BuildingFloorRoom'].'</option>';
						}
					}else{
						$rspta=$ascensor->SelectEmergenciaSAP();
						foreach($rspta as $val){
							echo '<option value='.$val['InternalSerialNum'].'>'.$val['InternalSerialNum'].' - '.$val['BuildingFloorRoom'].'</option>';
						}
					}
				}
            }
            // print_r($rspta);
        break;

        
        case 'selectascfiltroandroid':
            $idsap= $_POST['idSAP_form'];
			$idrol = $_POST['idrole'];
            $idservicio = $_POST['idservicio'];
            error_log("El id del servicio es: ".$_POST['idservicio']);
            $response = array();
            switch($_POST['idservicio']){
                case 1: 
                    $rspta=$ascensor->SelectAscensorServicioSAP($idservicio, $idsap);
                    foreach($rspta as $val){
                        $item = array(
                            'value' => $val['ServiceCallID'], // Cambiado a ServiceCallID como valor
                            'text' => $val['InternalSerialNum'].' - '.$val['BuildingFloorRoom'],
                            'paradas' => $val['Paradas'], // Agregado paradas como atributo adicional
                            'tipoequipo' => $val['TipoEquipo'] // Agregado TipoEquipo como atributo adicional
                        );
                        array_push($response, $item);
                    }
                    echo json_encode($response);
                break;
                case 17: 
                    $rspta=$ascensor->SelectAuditoriaTecnicaSAP();
                    foreach($rspta as $val){
                        //echo '<option value='.$val['InternalSerialNum'].'>'.$val['InternalSerialNum'].' - '.$val['BuildingFloorRoom'].'</option>';
                        $item = array(
                            'value' => $val['InternalSerialNum'],
                            'text' => $val['InternalSerialNum'].' - '.$val['BuildingFloorRoom']
                        );
                        array_push($response, $item);
                    }
                    echo json_encode($response);
                break;
                case 4: 
                    $rspta=$ascensor->SelectEmergenciaSAP();
                    foreach($rspta as $val){
                        $item = array(
                            'value' => $val['InternalSerialNum'],
                            'text' => $val['InternalSerialNum'].' - '.$val['BuildingFloorRoom']
                        );
                        array_push($response, $item); 
                    }
                    echo json_encode($response);
                break;
                case 5: 
                    $rspta=$ascensor->SelectNormalizacion();
                    foreach($rspta as $val){
                    // echo '<option value='.$val['InternalSerialNum'].'>'.$val['InternalSerialNum'].' - '.$val['BuildingFloorRoom'].'</option>';
                        $item = array(
                            'value' => $val['InternalSerialNum'],
                            'text' => $val['InternalSerialNum'].' - '.$val['BuildingFloorRoom']
                        );
                        array_push($response, $item);
                    }
                    echo json_encode($response);
                break;
                case 18: 
                    $rspta=$ascensor->SelectAscensorServicioSAP($idservicio, $idsap);
                    foreach($rspta as $val){
                        $item = array(
                            'value' => $val['ServiceCallID'], // Cambiado a ServiceCallID como valor
                            'text' => $val['InternalSerialNum'].' - '.$val['BuildingFloorRoom'],
                            'paradas' => $val['Paradas'], // Agregado paradas como atributo adicional
                            'tipoequipo' => $val['TipoEquipo'] // Agregado TipoEquipo como atributo adicional
                        );
                        array_push($response, $item);
                    }
                    echo json_encode($response);
                break;
                case 2: 
                    $rspta=$ascensor->SelectAscensorServicioSAP($idservicio, $idsap);
                    foreach($rspta as $val){
                        $item = array(
                            'value' => $val['ServiceCallID'], // Cambiado a ServiceCallID como valor
                            'text' => $val['InternalSerialNum'].' - '.$val['BuildingFloorRoom'],
                            'paradas' => $val['Paradas'], // Agregado paradas como atributo adicional
                            'tipoequipo' => $val['TipoEquipo'] // Agregado TipoEquipo como atributo adicional
                        );
                        array_push($response, $item);
                    }
                    echo json_encode($response);
                break;
                default:
                // Código a ejecutar si no coincide con ningún caso
                break;
            }
        break;            

		case 'selectasc':
            $rspta=$ascensor->SelectAscensorServicioSAP($idservicio, $idsap);
            // print_r($rspta);
			echo '<option value="" selected disabled>SELECCIONE EQUIPO</option>';
			foreach($rspta as $val){
				echo '<option value='.$val['ServiceCallID'].'>('.$val['CallType'].') - '.$val['InternalSerialNum'].' - '.$val['BuildingFloorRoom'].'</option>';
			}
		break;

        case 'selectasc2':
            $rspta=$ascensor->SelectAscensorQUERY();
            // print_r($rspta);
            echo '<option value="" selected disabled>SELECCIONE EQUIPO</option>';
            foreach($rspta['value'] as $val){
                echo '<option value='.$val['ServiceCalls']['ServiceCallID'].'>('.$val['ServiceCallTypes']['Name'].') - '.$val['ServiceCalls']['InternalSerialNum'].' - '.$val['CustomerEquipmentCards']['BuildingFloorRoom'].'</option>';
            }
        break;

		case 'llamadaservicio':
            $servicecall = $_POST["servicecall"];
            $rspta=$ascensor->MostrarInformacion($servicecall);
            error_log("SERVICECALL ".$servicecall);
            error_log("RSPTA VARIADOS ".$rspta);
			echo $rspta;
		break;
        
        case 'llamadaservicioemergencia':
            $servicecall = $_POST["servicecall"];
            $rspta=$ascensor->MostrarInformacionEmergencia($servicecall);
            error_log("SERVICECALL ".$servicecall);
            error_log("RSPTA EMERGENCIA ".$rspta);
            echo $rspta;
        break;
        
        case 'llamadaserviciovisita':
            $servicecall = $_POST["servicecall"];
            $rspta=$ascensor->MostrarInformacionVisita($servicecall);
            error_log("SERVICECALL ".$servicecall);
            error_log("RSPTA VISITA ".$rspta);
            echo $rspta;
        break;

        case 'llamadaservicionormalizacion':
            $servicecall = $_POST["servicecall"];
            $rspta=$ascensor->MostrarInformacionNormalizacion($servicecall);
            error_log("SERVICECALL ".$servicecall);
            error_log("RSPTA NORMALIZACION ".$rspta);
            //ECHO RSPTA es para los html
            echo $rspta;
        break;

     
}

 ?>