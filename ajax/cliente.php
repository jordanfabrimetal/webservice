<?php

session_name('SESS_GSAP');
session_start();
require_once "../modelos/Contrato.php";
require_once "../modelos/Edificio.php";
require_once "../modelos/Ascensor.php";
require_once '../modelos/Contacto.php';
require_once "../modelos/Cliente.php";

$contrato = new Contrato();
$edificio = new Edificio();
$ascensor = new Ascensor();
$cliente = new Cliente();

//Datos desde el formulario - Seccion de contrato
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$razon_social = isset($_POST["razon"]) ? limpiarCadena($_POST["razon"]) : "";
$rut = isset($_POST["rut"]) ? limpiarCadena($_POST["rut"]) : "";
$idtcliente = isset($_POST["idtcliente"]) ? limpiarCadena($_POST["idtcliente"]) : "";
$calle = isset($_POST["calle"]) ? limpiarCadena($_POST["calle"]) : "";
$numero = isset($_POST["numero"]) ? limpiarCadena($_POST["numero"]) : "";
$oficina = isset($_POST["oficina"]) ? limpiarCadena($_POST["oficina"]) : "";
$idregiones = isset($_POST["idregiones"]) ? limpiarCadena($_POST["idregiones"]) : "";
$idprovincias = isset($_POST["idprovincias"]) ? limpiarCadena($_POST["idprovincias"]) : "";
$idcomunas = isset($_POST["idcomunas"]) ? limpiarCadena($_POST["idcomunas"]) : "";

switch ($_GET["op"]) {

    case 'editar':
        $iduser = $_SESSION['iduser'];
        if (!empty($idcliente)) {
            $rspta = $cliente->editar($idcliente, $rut, $razon_social, $calle, $numero, $oficina, $idregiones, $idprovincias, $idcomunas, $idtcliente);
            echo $rspta ? "Cliente editado" : "Cliente no pudo ser editado";
        }
        break;


    case 'listarclientes':
        $rspta = $cliente->listar_clientes();
        $data = Array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-info btn-xs" onclick="mostrar(' . $reg->idcliente . ')"><i class="fa fa-list-alt"></i></button><button class="btn btn-info btn-xs" onclick="editar(' . $reg->idcliente . ')"><i class="fa fa-pencil"></i></button>',
                "1" => $reg->razon_social,
                "2" => $reg->rut,
                "3" => $reg->calle . ' ' . $reg->numero . ' ' .$reg->oficina,
                "4" => $reg->comuna,
                "5" => $reg->region
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

    case 'contratos_cliente':
        $rspta = $contrato->contratos_cliente($idcliente);
        $data = Array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-info btn-xs" data-tooltip="tooltip" title="Modificar"><i class="fa fa-pencil"></i></button>',
                "1" => $reg->ncontrato,
                "2" => $reg->fecha,
                "3" => $reg->tipo              
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
        
    case 'edificios_cliente':
        $rspta = $edificio->edificios_cliente($idcliente);
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

    case 'ascensores_cliente':
        $rspta = $ascensor->ascensores_cliente($idcliente);
        $data = Array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-info btn-xs" data-tooltip="tooltip" title="Modificar"><i class="fa fa-pencil"></i></button>',
                "1" => $reg->codigo,
                "2" => $reg->tipo,
                "3" => $reg->marca,
                "4" => $reg->modelo,
                "5" => $reg->valoruf,
                "6" => $reg->edificio,
                "7" => $reg->ncontrato
                
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


    case 'mostrar':
        $dcliente = $cliente->mostrar($idcliente);
        echo json_encode($dcliente);
        break;

    case 'formeditar':
        $rspta = $cliente->formeditar($idcliente);
        echo json_encode($rspta);
        break;
}
?>