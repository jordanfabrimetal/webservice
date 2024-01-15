<?php 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');


require_once "../modelos/CentroCosto.php";

$centrocosto = new CentroCosto();

$idcentro_costo=isset($_POST["idcentro_costo"])?limpiarCadena($_POST["idcentro_costo"]):"";
$idtipo_centro_costo=isset($_POST["idtipo_centro_costo"])?limpiarCadena($_POST["idtipo_centro_costo"]):"";
$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])?limpiarCadena($_POST["descripcion"]):"";
$numero_centro=isset($_POST["numero_centro"])?limpiarCadena($_POST["numero_centro"]):"";


switch ($_GET["op"]) {
	case 'cguardaryeditar':

	    if(empty($idcentro_costo)){
			$rspta=$centrocosto->insertar($numero_centro, $descripcion, $id_tipo_centro_costo);
			echo $rspta ? "Centro costo ingresado" : "Centro costo no ingresado";
		}
		else{
		    $rspta=$centrocosto->editar($idcentro_costo, $numero_centro, $descripcion, $id_tipo_centro_costo);
			echo $rspta ? "Centro de costo editado" : "Centro de costo no pudo ser editado";
		}
		break;
	
	case 'tguardaryeditar':
	    if(empty($idtipo_centro_costo)){
	        $rspta=$centrocosto->insertar_tipo($nombre, $descripcion);
	        echo $rspta ? "Tipo de centro de costo registrado" : "Tipo de centro de costo no pudo ser registrado";
	    }
	    else{
	        $rspta=$centrocosto->editar_tipo($idtipo_centro_costo, $nombre, $descripcion);	  
	        echo $rspta ? "Tipo de centro de costo editado" : "Tipo de centro de costo no pudo ser editado";
	    }
	    break;

	case 'desactivar':
		$rspta=$centrocosto->desactivar($idcentro_costo);
			echo $rspta ? "Centro de costo inhabilitado" : "Centro de costo no se pudo inhabilitar";
		break;

	case 'activar':
	    $rspta=$centrocosto->activar($idcentro_costo);
			echo $rspta ? "Centro de costo habilitado" : "Centro de costo no se pudo habilitar";
		break;

	case 'mostar':
		$rspta=$instructor->mostrar($idinstructor);
			echo json_encode($rspta);
		break;
			
	case 'listarcentro':
	    $rspta=$centrocosto->listar_centro();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
					"0"=>($reg->condicion)?
			         '<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idcentro_costo.')"><i class="fa fa-pencil"></i></button>'.
			         ' <button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idcentro_costo.')"><i class="fa fa-close"></i></button>':
			         '<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idcentro_costo.')"><i class="fa fa-pencil"></i></button>'.
			         ' <button class="btn btn-primary btn-xs" onclick="activar('.$reg->idcentro_costo.')"><i class="fa fa-check"></i></button>',
					"1"=>$reg->numero_centro,
					"2"=>$reg->descripcion,
					"3"=>$reg->nombre,					
					"4"=>($reg->condicion)?'<span class="label bg-green">Habilitado</span>':'<span class="label bg-red">Inhabilitado</span>'
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
		
	case 'listartipo':
	    $rspta=$centrocosto->listar_tipo();
	    $data = Array();
	    while ($reg = $rspta->fetch_object()){
	        $data[] = array(
	            "0"=>($reg->condicion)?
	            '<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idtipo_centro_costo.')"><i class="fa fa-pencil"></i></button>'.
	            ' <button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idtipo_centro_costo.')"><i class="fa fa-close"></i></button>':
	            '<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idtipo_centro_costo.')"><i class="fa fa-pencil"></i></button>'.
	            ' <button class="btn btn-primary btn-xs" onclick="activar('.$reg->idtipo_centro_costo.')"><i class="fa fa-check"></i></button>',
	            "1"=>$reg->nombre,
	            "2"=>$reg->descripcion,
	            "3"=>($reg->condicion)?'<span class="label bg-green">Habilitado</span>':'<span class="label bg-red">Inhabilitado</span>'
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
	    
	   case 'listarcentrotipo':
	    $idtipo_centro_costo = $_GET["id"];
	    $rspta=$centrocosto->listar_centro_tipo($idtipo_centro_costo);
	    $data = Array();
	    while ($reg = $rspta->fetch_object()){
	        $data[] = array(
	            "0"=>$reg->numero_centro,
	            "1"=>$reg->descripcion,
	            "2"=>$reg->nombre	        );
	    }
	    $results = array(
	        "iTotalRecords"=>count($data),
	        "aaData"=>$data
	    );
	    
	    echo json_encode($results);
	    break;
	    
	    
	   case 'limpio':
	       $rspta=$centrocosto->limpio();
	       $data = Array();
	       while ($reg = $rspta->fetch_object()){
	           $data[] = array(
	               "0"=>$reg->numero_centro,
	               "1"=>$reg->descripcion,
	               "2"=>$reg->nombre	        );
	       }
	       
	       echo json_encode($data);
	       break;
		

		case 'in_selectcentro':
		    $idtipo_centro_costo = $_GET["id"];
		    $rspta = $centrocosto->selectcentro($idtipo_centro_costo);
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idcentro_costo.'>'.$reg->descripcion.' - '.$reg->nombre.'</option>';
			}
		break;
		
		case 'ex_selectcentro':
		    $idtipo_centro_costo = $_GET["id"];
		    $rspta = $centrocosto->selectcentro($idtipo_centro_costo);
		    while($reg = $rspta->fetch_object()){
		        echo '<option value='.$reg->numero_centro.'>'.$reg->descripcion.' - '.$reg->nombre.'</option>';
		    }
	   break;
	   
            case 'selecttipo':
		    $rspta = $centrocosto->selecttipo();
		    while($reg = $rspta->fetch_object()){
		        echo '<option value='.$reg->idtipo_centro_costo.'>'.$reg->nombre.'</option>';
		    }
	    break;
            
            case 'selecttipojson':
		    $rspta = $centrocosto->selecttipo();	       
                    echo json_encode($rspta);

	    break;

}

 ?>