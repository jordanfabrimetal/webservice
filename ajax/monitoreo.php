<?php 
session_name('SESS_GSAP');
session_start();
require_once "../modelos/Monitoreo.php";


$monitoreo = new Monitoreo();

//Extraemos informacion por metodos GET y POST, En caso de utilizar formulario limpiamos la cadena para evitar caracteres especiales.
//$idinstructor=isset($_POST["idinstructor"])?limpiarCadena($_POST["idinstructor"]):"";

//$op=base64_decode($_GET["op"]);
$op=$_GET["op"];
switch ($op) {
        
    case 'A':
        //$cadena=base64_decode($_GET["S"]);
        $codigos = explode(",", $_GET["S"]);
        foreach ($codigos as $cadena) {
            if(isset($cadena)){
                    //Dividimos el codigo
                    //Codigo
                    $codigo = substr($cadena, 0, -1);
                    //Alerta
                    $alerta=$cadena[8];
                    $ultimo=$monitoreo->verificarultimo($codigo);
                    if($ultimo["alerta"] != $alerta){
                        $idascensor=$monitoreo->id_ascensor($codigo);
                        $id=intval($idascensor["idascensor"]);
                        $rspta=$monitoreo->insertar($codigo, $alerta, $id);
                        if(!$rspta){
                            $monitoreo->error($codigo, "Error al cargar a la BD");
                        }
                    }
                }else{
                    $monitoreo->error($codigo, "El parametro codigo viene vacio");    
                }
        }
        echo "OK";
		break;
		
    case 'I':
        //$cadena=base64_decode($_GET["S"]);
        $cadena=$_GET["S"];
        if(isset($_GET["S"])){
            //Dividimos el codigo
            //Codigo
            $codigo = substr($cadena, 0, -1);
            //Alerta
            $alerta=$cadena[8];
            $ultimo=$monitoreo->verificarultimo($codigo);
            if($ultimo["alerta"] != $alerta){
                $idascensor=$monitoreo->id_ascensor($codigo);
                $id=intval($idascensor["idascensor"]);
                $rspta=$monitoreo->insertar($codigo, $alerta, $id);
                if(!$rspta){
                    $monitoreo->error($codigo, "Error al cargar a la BD");
                }
            }
        }else{
            $monitoreo->error($codigo, "El parametro codigo viene vacio");
        }
        echo "OK";
        break;
        
    case 'E':
        $con = 0;
        $codigos = explode(",", $_GET["S"]);
        $movimientos = explode(",", $_GET["M"]);
        $recorridos = explode(",", $_GET["T"]);
        foreach ($codigos as $codigo) {  
            if(isset($codigo)){                   
                    $idascensor=$monitoreo->id_ascensor($codigo);
                    $id=intval($idascensor["idascensor"]);               
                    $rspta=$monitoreo->indatos($id, $recorridos[$con], $movimientos[$con]);
                    if($rspta){
                        echo "OK";
                    }else{
                        echo "Error BD";
                    }
            }
            $con++;
        }   
        break;

    case 'listarAlerta':
        $rspta=$monitoreo->listar();
        $data = Array();
        while ($reg = $rspta->fetch_object()){
            $monitoreo->estado($reg->codigo);
            $data[] = array(
                    "0"=>$reg->idmonitoreo,
                    "1"=>$reg->codigo,
                    "2"=>$reg->alerta,
                    "3"=>$reg->lat,
                    "4"=>$reg->lon,
                    "5"=>$reg->estado,
                    "6"=>$reg->nombre,
                    "7"=>$reg->calle.' '.$reg->numero,
                    "8"=>$reg->nregion.' - '.$reg->region.'. '.$reg->ciudad.', '.$reg->comuna,
                    "9"=>$reg->razon_social.' - RUT '.$reg->rut
                        );
        }
        $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data), 
                "aaData"=>$data
            );

        echo json_encode($results);
        break;
        
    case 'datos':
        $codigo=$_GET["C"];
        $rspta=$monitoreo->datos($codigo);
        echo json_encode($rspta);
        break;
}

 ?>