<?php
session_name('SESS_GSAP');
session_start();
require_once "../modelos/Estado.php";


$estado = new Estado();

switch ($_GET["op"]) {

    /*case 'ContarDatos':
        $iduser=$_SESSION['iduser'];
        $a���o = $estado->serviciosa���o($iduser);
        $mes = $estado->serviciosmes($iduser);
        $dia = $estado->serviciosdia($iduser);
        $results = array(
                "a���o"=>$a���o,
                "mes"=>$mes, 
                "dia"=>$dia
            );
        echo json_encode($results);
        break;
        
    case 'DatosGrafico':
        $iduser=$_SESSION['iduser'];
        $rspta=$estado->contarservicios($iduser);
        $data = Array();
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                "0"=>$reg->mes,
                "1"=>$reg->servicios
            );
        }
        $results = array(
            "Totalservicios"=>count($data),
            "aaData"=>$data
        );
        
        echo json_encode($results);
        break;*/
    case 'traerdatos':
        $datos = json_decode($estado->TraerDatos(), true);
        $grafico = array();
        foreach ($datos['grafico'] as $key => $value) {
            $grafico[] = array(
                "0" => $key,
                "1" => $value
            );
        }
        $grafico = array(
            "Totalservicios" => $datos['datos']['anio'],
            "aaData" => $grafico
        );
        $datos = array(
            "anio" => $datos['datos']['anio'],
            "mes" => $datos['datos']['mes'],
            "dia" => $datos['datos']['dia']
        );
        echo json_encode(array("datos" => $datos, "grafico" => $grafico));
        break;

    case 'traerdatosandroid':
        $idSAP_form = $_POST['idSAP_form'];
        error_log("ID QUE VIENE DEL FORM: ".$idSAP_form);

        $datos = json_decode($estado->TraerDatosAndroid($idSAP_form), true);
        $grafico = array();
        foreach ($datos['grafico'] as $key => $value) {
            $grafico[] = array(
                "x" => $key,
                "y" => $value
            );
        }
        $grafico = array(
            "Totalservicios" => $datos['datos']['anio'],
            "aaData" => $grafico
        );
        $datos = array(
            "anio" => $datos['datos']['anio'],
            "mes" => $datos['datos']['mes'],
            "dia" => $datos['datos']['dia']
        );
        echo json_encode(array("datos" => $datos, "grafico" => $grafico));
        break;

}

?>