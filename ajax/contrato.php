<?php 
session_name('SESS_GSAP');
session_start();
require_once "../modelos/Contrato.php";
require_once "../modelos/Cliente.php";
require_once "../modelos/Edificio.php";
require_once "../modelos/Ascensor.php";
require_once "../modelos/ContactoCliente.php";
require_once '../modelos/Contacto.php';
require_once '../modelos/CentroCosto.php';

$contrato = new Contrato();
$cliente = new Cliente();
$edificio = new Edificio();
$ascensor = new Ascensor();
$contactocli = new ContactoCliente();
$contactoedif = new Contacto();
$centros = new CentroCosto();


//Datos desde el formulario - Seccion de contrato
$idcontrato=isset($_POST["idcontrato"])?limpiarCadena($_POST["idcontrato"]):"";
$tcontrato=isset($_POST["tcontrato"])?limpiarCadena($_POST["tcontrato"]):"";
$ncontrato=isset($_POST["ncontrato"])?limpiarCadena($_POST["ncontrato"]):"";
$nexterno=isset($_POST["nexterno"])?limpiarCadena($_POST["nexterno"]):"";
$nreferencia=isset($_POST["nreferencia"])?limpiarCadena($_POST["nreferencia"]):"";
$fecha=isset($_POST["fecha"])?limpiarCadena($_POST["fecha"]):"";
$fecha_ini=isset($_POST["fecha_ini"])?limpiarCadena($_POST["fecha_ini"]):NULL;
$fecha_fin=isset($_POST["fecha_fin"])?limpiarCadena($_POST["fecha_fin"]):NULL;
$idperiocidad=isset($_POST["idperiocidad"])?limpiarCadena($_POST["idperiocidad"]):"";
$ubicacion=isset($_POST["ubicacion"])?limpiarCadena($_POST["ubicacion"]):"";
$observaciones=isset($_POST["observaciones"])?limpiarCadena($_POST["observaciones"]):"";
$nclientes=isset($_POST["nclientes"])?limpiarCadena($_POST["nclientes"]):"";
$nedificios=isset($_POST["nedificios"])?limpiarCadena($_POST["nedificios"]):"";
$ncon_edi=isset($_POST["ncon_edi"])?limpiarCadena($_POST["ncon_edi"]):"";



switch ($_GET["op"]) {
	
	case 'guardaryeditar':
		$resp ="";
		$iduser=$_SESSION['iduser'];
	    if(empty($idcontrato)){
            $idcon=$contrato->insertar($tcontrato,$ncontrato,$nexterno,$nreferencia,$ubicacion, $fecha, $fecha_ini, $fecha_fin,$idperiocidad, $observaciones);
			if($idcon > 0){
				for ($i=0; $i < (int)$nclientes; $i++) {
					$id=$cliente->VerCliente($_POST['rut'.$i.'']);
					if(!empty($id)){
						$idcli=intval($id["idcliente"]);
					}else{
						$idcli=$cliente->insertar($iduser, $_POST['tcliente'.$i.''], $_POST['rut'.$i.''], $_POST['razon_social'.$i.''], $_POST['calle'.$i.''], $_POST['numero'.$i.''], $_POST['oficina'.$i.''], $_POST['idregiones'.$i.''], $_POST['idprovincias'.$i.''], $_POST['idcomunas'.$i.'']);
					}
					if($idcli>0){
						$idconcli=$contrato->contrato_cliente($idcli, $idcon);
						if($idconcli>0){
                                                    for ($o=0; $o < (int)$_POST['ncon_cli'.$i.'']; $o++) {
                                                        $idcontacli = $contactocli->insertar($_POST['tipocon'.$i.''.$o.''], $_POST['nombre_concli'.$i.''.$o.''], $_POST['numero_concli'.$i.''.$o.''], $_POST['email_concli'.$i.''.$o.'']);
                                                        if($idcontacli>0){
                                                            $respconcli=$contactocli->contacto_cc($idcontacli, $idconcli);
                                                            if($respconcli){
                                                                echo "Contrato - Cliente (OK) / ";
                                                            }else{
                                                                echo "Error asociaciones contacto-cliente-contrato";
                                                            }
                                                        }else{
                                                            echo "Error contacto cliente";
                                                        }
                                                    }	
                                                }else{
                                                    echo "Error asociaciones contrato-clientes";
                                                }
					}else{
						echo "Error Cliente";
					}
					
				}

				for ($i=0; $i < (int)$nedificios; $i++) { 
					$id=$edificio->VerEdificio($_POST['nombre'.$i.''],  $_POST['calle_ed'.$i.''],  $_POST['numero_ed'.$i.'']);
					if(!empty($id)){
						$idedi=intval($id["idedificio"]);
					}else{
						$idedi=$edificio->insertar($_POST['nombre'.$i.''], $_POST['calle_ed'.$i.''], $_POST['numero_ed'.$i.''], $_POST['idtsegmento'.$i.''], $_POST['corcorreo'.$i.''], $_POST['residente'.$i.''], $_POST['idregiones_ed'.$i.''], $_POST['idprovincias_ed'.$i.''], $_POST['idcomunas_ed'.$i.'']);
					}
					if($idedi>0){
						$idconedi=$contrato->edificio_contrato($idedi, $idcon);
						if($idconedi>0){
                                                        for ($w=0; $w < (int)$_POST['ncon_edi'.$i.'']; $w++) {
                                                            $idcontaedif = $contactoedif->insertar($_POST['nombre_conedi'.$i.''.$w.''], $_POST['numero_conedi'.$i.''.$w.''], $_POST['email_conedi'.$i.''.$w.'']);
                                                            if($idcontaedif>0){
                                                                $respconedi=$contactoedif->contacto_ec($idcontaedif, $idconedi);
                                                                if($respconedi){
                                                                    echo "Contrato - Edificio (OK) / ";
                                                                }else{
                                                                    echo "Error asociaciones contacto-cliente-contrato";
                                                                }
                                                            }else{
                                                                echo "Error contacto edificio";
                                                            }
							}
                                                        
							for ($o=0; $o < (int)$_POST['nascensores'.$i.'']; $o++) {
                                                                //echo $iduser.' / '.$idconedi.' / '.$_POST['idtascensor'.$i.''.$o.''].' / '.$_POST['marca'.$i.''.$o.''].' / '.$_POST['modelo'.$i.''.$o.''].' / '.$_POST['valoruf'.$i.''.$o.''].' / '.$_POST['valorclp'.$i.''.$o.''].' / '.$_POST['paradas'.$i.''.$o.''].' / '.$_POST['capper'.$i.''.$o.''].' / '.$_POST['capkg'.$i.''.$o.''].' / '.$_POST['velocidad'.$i.''.$o.''].' / '.$_POST['pservicio'.$i.''.$o.''].' / '.$_POST['gtecnica'.$i.''.$o.''].' / '.$_POST['ken'.$i.''.$o.''].' / '.$_POST['dcs'.$i.''.$o.''].' / '.$_POST['elink'.$i.''.$o.''].'<br/>';
								//$ascensor->insertar($iduser, $idconedi, $_POST['idtascensor'.$i.''.$o.''],$_POST['marca'.$i.''.$o.''],$_POST['modelo'.$i.''.$o.''],$_POST['valoruf'.$i.''.$o.''],$_POST['valorclp'.$i.''.$o.'']);
                                                                $respas=$ascensor->insertar($iduser, $idconedi, $_POST['idtascensor'.$i.''.$o.''], $_POST['marca'.$i.''.$o.''], $_POST['modelo'.$i.''.$o.''], $_POST['valoruf'.$i.''.$o.''], $_POST['valorclp'.$i.''.$o.''], $_POST['paradas'.$i.''.$o.''], $_POST['capper'.$i.''.$o.''], $_POST['capkg'.$i.''.$o.''], $_POST['velocidad'.$i.''.$o.''], $_POST['pservicio'.$i.''.$o.''], $_POST['gtecnica'.$i.''.$o.''], $_POST['ken'.$i.''.$o.''], $_POST['dcs'.$i.''.$o.''], $_POST['elink'.$i.''.$o.'']);
                                                                if($respas){
                                                                    echo "Ascensores (OK)";
                                                                }else{
                                                                    echo "Error ascensores";
                                                                }
							}   
						}else{
							echo " Error asociaciones contrato-edificios";
						}
					}else{
						echo "Error datos edificios";
					}				
				}
			echo "Contrato registrado";
			}else{
				echo " Error datos de contrato";
			}
		}
		break;
                
        case 'editar':
            $idcontrato=isset($_POST["fidcontrato"])?limpiarCadena($_POST["fidcontrato"]):"";
            $tcontrato=isset($_POST["ftcontrato"])?limpiarCadena($_POST["ftcontrato"]):"";
            $ncontrato=isset($_POST["fncontrato"])?limpiarCadena($_POST["fncontrato"]):"";
            $nexterno=isset($_POST["fnexterno"])?limpiarCadena($_POST["fnexterno"]):"";
            $nreferencia=isset($_POST["fnreferencia"])?limpiarCadena($_POST["fnreferencia"]):"";
            $fecha=isset($_POST["ffecha"])?limpiarCadena($_POST["ffecha"]):"";
            $fecha_ini=isset($_POST["ffecha_ini"])?limpiarCadena($_POST["ffecha_ini"]):NULL;
            $fecha_fin=isset($_POST["ffecha_fin"])?limpiarCadena($_POST["ffecha_fin"]):NULL;
            $idperiocidad=isset($_POST["fidperiocidad"])?limpiarCadena($_POST["fidperiocidad"]):"";
            $ubicacion=isset($_POST["fubicacion"])?limpiarCadena($_POST["fubicacion"]):"";
            $observaciones=isset($_POST["fobservaciones"])?limpiarCadena($_POST["fobservaciones"]):"";
            $calle=isset($_POST["fcalle"])?limpiarCadena($_POST["fcalle"]):"";
            $numero=isset($_POST["fnumero"])?limpiarCadena($_POST["fnumero"]):"";
            $oficina=isset($_POST["foficina"])?limpiarCadena($_POST["foficina"]):"";
            $idregiones=isset($_POST["fidregiones"])?limpiarCadena($_POST["fidregiones"]):"";
            $idprovincias=isset($_POST["fidprovincias"])?limpiarCadena($_POST["fidprovincias"]):"";
            $idcomunas=isset($_POST["fidcomunas"])?limpiarCadena($_POST["fidcomunas"]):"";
            
            if (!empty($idcontrato)) {
                $rspta = $contrato ->editar($idcontrato, $tcontrato, $ncontrato, $nexterno, $nreferencia, $ubicacion, $fecha, $fecha_ini, $fecha_fin, $idregiones, $idprovincias, $idcomunas, $calle, $numero, $oficina, $observaciones, $idperiocidad);
                echo $rspta ? "Contrato editado" : "Contrato no pudo ser editado";
            }
        break;
		

	case 'listarcontrato':
	    $rspta=$contrato->listar();  
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
					"0"=>'<button class="btn btn-info btn-xs" onclick="mostar('.$reg->idcontrato.')"><i class="fa fa-list-alt"></i></button><button class="btn btn-info btn-xs" onclick="editar(' . $reg->idcontrato . ')"><i class="fa fa-pencil"></i></button>',
					"1"=>$reg->ncontrato,
					"2"=>$reg->fecha,
					"3"=>$reg->tipo,
					"4"=>$reg->razon_social.' - RUT '.$reg->rut,
                                        "5"=>$reg->ubicacion,
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

		
	case 'listarsoid':
	    $rspta=$contrato->listarsolid();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
					"0"=>'<button class="btn btn-info btn-xs" onclick="addformasc('.$reg->idcontrato.','.$reg->nascensores.')" data-tooltip="tooltip" title="Asignar IDs" ><i class="fa fa-hashtag"></i> Asignar IDs</button>',
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
        
        case 'clientes_contrato':
            $rspta=$cliente->clientes_contrato($idcontrato);
            $data = Array();
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0" => '<button class="btn btn-info btn-xs" data-tooltip="tooltip" title="Modificar"><i class="fa fa-pencil"></i></button>',
                    "1" => $reg->rut,
                    "2" => $reg->razon_social,
                    "3" => $reg->calle.' '.$reg->numero,
                    "4" => $reg->tipo,
                    "5" => $reg->region,
                    "6" => $reg->comuna                   
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
        
        case 'edificio_contrato':
            $rspta=$edificio->edificios_contrato($idcontrato);
            $data = Array();
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0" => '<button class="btn btn-info btn-xs" data-tooltip="tooltip" title="Modificar"><i class="fa fa-pencil"></i></button>',
                    "1" => $reg->nombre,
                    "2" => $reg->calle.' '.$reg->numero,
                    "3" => $reg->segmento,
                    "4" => $reg->region,
                    "5" => $reg->comuna                
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
            
        case 'ascensor_contrato':
            $rspta=$ascensor->ascensores_contrato($idcontrato);
            $data = Array();
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0" => '<button class="btn btn-info btn-xs" data-tooltip="tooltip" title="Modificar"><i class="fa fa-pencil"></i></button>',
                    "1" => $reg->codigo,
                    "2" => $reg->marca,
                    "3" => $reg->modelo,
                    "4" => $reg->valoruf,
                    "5" => $reg->edificio,
                    "6" => $reg->region,
                    "7" => $reg->comuna 
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
        
        case 'centrosc_contrato':
	    $rspta=$centros->centrosc_contrato($idcontrato);
            while ($reg = $rspta->fetch_object()){

                echo '<tr>
                        <th scope="row">'.$reg->codigo.'</th>
                        <td>'.$reg->nombre.'</td>
                        <td>'.$reg->tipo.'</td>                        
                        <td><button class="btn btn-info btn-xs" data-tooltip="tooltip" title="Modificar"><i class="fa fa-pencil"></i></button></td>
                    </tr>';
		}
	break;
        
        case 'mostrar_contrato':
	    $dcontrato=$contrato->mostrar($idcontrato);
            $nclientes=$cliente->contar_clientes($idcontrato);
            $nedificios=$edificio->contar_edificios($idcontrato);
            $nascensores=$ascensor->contar_ascensores($idcontrato);
            $ncentros=$centros->contar_centros($idcontrato);
            
            if(is_null($dcontrato['fin'])){
                $dcontrato['fin']="INDEFINIDO";
            }
            
            if(is_null($dcontrato['inicio'])){
                $dcontrato['inicio']="S/F";
            }
            
            if(empty($dcontrato['nexterno'])){
                $dcontrato['nexterno']="S/C";
            }
            
            if(empty($dcontrato['nreferencia'])){
                $dcontrato['nreferencia']="S/C";
            }
            
            if(empty($dcontrato['ubicacion'])){
                $dcontrato['ubicacion']="S/C";
            }
            
            $results = array(
                "dcontrato"=>$dcontrato,
                "nclientes"=>$nclientes,
                "nedificios"=>$nedificios,
                "nascensores"=>$nascensores,
                "ncentros"=>$ncentros,
                "prueba"=>$prueba,
                );
            
            echo json_encode($results);
	break;
        
        case 'formeditar':
            $rspta = $contrato->formeditar($idcontrato);
            echo json_encode($rspta);
        break;

}

 ?>