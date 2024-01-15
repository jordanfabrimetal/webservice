<?php

session_name('SESS_GSAP');
session_start();
require_once "../modelos/Contrato.php";
require_once "../modelos/Edificio.php";
require_once "../modelos/Ascensor.php";
require_once '../modelos/Contacto.php';

$contrato = new Contrato();
$edificio = new Edificio();
$ascensor = new Ascensor();
$contactoedif = new Contacto();

//Datos desde el formulario - Seccion de contrato
$idedificio = isset($_POST["idedificio"]) ? limpiarCadena($_POST["idedificio"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$calle = isset($_POST["calle"]) ? limpiarCadena($_POST["calle"]) : "";
$numero = isset($_POST["numero"]) ? limpiarCadena($_POST["numero"]) : "";
$oficina = isset($_POST["oficina"]) ? limpiarCadena($_POST["oficina"]) : "";
$idtsegmento = isset($_POST["idtsegmento"]) ? limpiarCadena($_POST["idtsegmento"]) : "";
$coordinacion = isset($_POST["coordinacion"]) ? limpiarCadena($_POST["coordinacion"]) : "";
$residente = isset($_POST["residente"]) ? limpiarCadena($_POST["residente"]) : "";
$idregiones = isset($_POST["idregiones"]) ? limpiarCadena($_POST["idregiones"]) : "";
$idprovincias = isset($_POST["idprovincias"]) ? limpiarCadena($_POST["idprovincias"]) : "";
$idcomunas = isset($_POST["idcomunas"]) ? limpiarCadena($_POST["idcomunas"]) : "";

switch ($_GET["op"]) {

    case 'editar':
        $iduser = $_SESSION['iduser'];
        if (!empty($idedificio)) {
            $rspta = $edificio->editar($idedificio, $nombre, $calle, $numero, $oficina, $idtsegmento, $coordinacion, $residente, $idregiones, $idprovincias, $idcomunas);
            echo $rspta ? "Edificio editado" : "Edificio no pudo ser editado";
        }
        break;


    case 'listaredificio':
        $rspta = $edificio->listar();
        $data = Array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-info btn-xs" onclick="mostrar(' . $reg->idedificio . ')"><i class="fa fa-list-alt"></i></button><button class="btn btn-info btn-xs" onclick="editar(' . $reg->idedificio . ')"><i class="fa fa-pencil"></i></button>',
                "1" => $reg->nombre,
                "2" => $reg->calle . ' ' . $reg->numero,
                "3" => $reg->region,
                "4" => $reg->comuna,
                "5" => $reg->segmento,
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

    case 'contratos_edificio':
        $rspta = $contrato->contratos_edificio($idedificio);
        while ($reg = $rspta->fetch_object()) {
            echo '<tr>
                        <th scope="row">' . $reg->ncontrato . '</th>
                        <td>' . $reg->fecha . '</td>
                        <td>' . $reg->tipo . '</td>
                        <td><button class="btn btn-info btn-xs" data-tooltip="tooltip" title="Modificar"><i class="fa fa-pencil"></i></button></td>
                     </tr>';
        }
        break;

    case 'ascensor_edificio':
        $rspta = $ascensor->ascensores_edificio($idedificio);
        while ($reg = $rspta->fetch_object()) {

            echo '<tr>
                        <th scope="row">' . $reg->codigo . '</th>
                        <td>' . $reg->tipo . '</td>
                        <td>' . $reg->marca . '</td>
                        <td>' . $reg->modelo . '</td>
                        <td>' . $reg->valoruf . '</td>
                        <td>' . $reg->ncontrato . '</td>
                        <td><button class="btn btn-info btn-xs" data-tooltip="tooltip" title="Modificar"><i class="fa fa-pencil"></i></button></td>
                    </tr>';
        }
        break;


    case 'mostrar':
        $dedificio = $edificio->mostrar($idedificio);

        if ($dedificio['coordinacion'] = 1) {
            $dedificio['coordinacion'] = "SI";
        } else {
            $dedificio['coordinacion'] = "NO";
        }

        if ($dedificio['residente'] = 1) {
            $dedificio['residente'] = "SI";
        } else {
            $dedificio['residente'] = "NO";
        }

        if (is_null($dedificio['oficina'])) {
            $dedificio['oficina'] = "S/N";
        }

        echo json_encode($dedificio);
        break;

    case 'formeditar':
        $rspta = $edificio->formeditar($idedificio);
        echo json_encode($rspta);
        break;
}
?>