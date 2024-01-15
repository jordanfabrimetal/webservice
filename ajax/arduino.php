<?php 
session_name('SESS_GSAP');
session_start();
require_once "../modelos/Arduino.php";


$arduino = new Arduino();

//Extraemos informacion por metodos GET y POST, En caso de utilizar formulario limpiamos la cadena para evitar caracteres especiales.
//$idinstructor=isset($_POST["idinstructor"])?limpiarCadena($_POST["idinstructor"]):"";

//$op=base64_decode($_GET["op"]);
$op=$_GET["op"];
switch ($op) {
        		
    case 'A':
        if(isset($_GET["C"])){
            $codigo = $_GET["C"];
            $ultimo=$arduino->verificarultimo($codigo);
            $idarduino=$arduino->id_arduino($codigo);
            $id=intval($idarduino["idarduino"]);
            if($ultimo["estado"] != 1){              
                $rspta=$arduino->activar($id);
                echo "OK";
            }else{
                $rsptaa = $arduino->updated($id);
                if($rsptaa){
                    echo "OK";
                }
            }
        }
    break;

    case 'D':
        if(isset($_GET["C"])){
            $codigo = $_GET["C"];
            $ultimo=$arduino->verificarultimo($codigo);
            if($ultimo["estado"] != 0){
                $idarduino=$arduino->id_arduino($codigo);
                $id=intval($idarduino["idarduino"]);
                $rspta=$arduino->desactivar($id);
                echo "OK";
            }else{
                echo "OK";
            }
        }
    break;
        
    
    case 'desactivados':
        $rspta=$arduino->desactivados();
        $data = Array();
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                    "0"=>$reg->codigo,
                    "1"=>$reg->nombre,
                    "2"=>$reg->calle.' '.$reg->numero,
                    "3"=>$reg->ubicacion,
                    "4"=>$reg->funcion,
                    "5"=>$reg->updated_time,
                    "6"=>($reg->estado)?'<span class="label bg-green">NORMAL</span>':'<span class="label bg-red">FALLA</span>'
                        );
        }
        $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data), 
                "aaData"=>$data
            );

        echo json_encode($results);
        break;

        case 'ContarEstado':
        $rspta=$arduino->contar();
        $data = Array();
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                    "0"=>$reg->estado
                        );
        }
        $results = array(
                "Totalestados"=>count($data), 
                "aaData"=>$data
            );

        echo json_encode($results);
        break;

      
    case 'verificar':
        $rspta=$arduino->verificar();
        echo $rspta ? "Arduinos verificados" : "No se logro verificar";
        break;
        
}

 ?>