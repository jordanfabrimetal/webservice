<?php 
session_name('SESS_GSAP');
session_start();
require_once "../modelos/Movimiento.php";


$movimiento = new Movimiento();

//Extraemos informacion por metodos GET y POST, En caso de utilizar formulario limpiamos la cadena para evitar caracteres especiales.
//$idinstructor=isset($_POST["idinstructor"])?limpiarCadena($_POST["idinstructor"]):"";

//$op=base64_decode($_GET["op"]);

$op=$_GET["op"];
switch ($op) {
        	   
    case 'listar':
        $rspta=$movimiento->listar();
        $data = Array();
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                    "0"=>'<button class="btn btn-info btn-xs" onclick="mostar('.$reg->idascensor.')"><i class="fa fa-list-alt"></i></button>',
                    "1"=>$reg->codigo,
                    "2"=>$reg->nombre,
                    "3"=>$reg->calle.' '.$reg->numero,
                    "4"=>$reg->movimiento.' Movimientos',
                    "5"=>$reg->recorrido.' Segundos'
                        );
        }
        $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data), 
                "aaData"=>$data
            );

        echo json_encode($results);
        break;

        case 'movimientos_ascensor':
        $idascensor = $_GET["id"];
        $rspta=$movimiento->movimientos_ascensor($idascensor);
        $data = Array();
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                    "0"=>$reg->recorrido,
                    "1"=>$reg->movimientos,
                    "2"=>$reg->created_time
                        );
        }
        $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data), 
                "aaData"=>$data
            );

        echo json_encode($results);
        break;

         case 'contar':
        $rspta=$movimiento->contar();
        $data = Array();
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                    "0"=>$reg->movimiento,
                    "1"=>$reg->recorrido
                        );
        }

        echo json_encode($data);
        break;

        
}

 ?>