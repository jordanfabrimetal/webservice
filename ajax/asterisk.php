<?php 
session_name('SESS_GSAP');
session_start();
require_once "../modelos/DashAsterisk.php";


$asterisk = new DashAsterisk();

switch ($_GET["op"]) {
        
    case 'DL':
        $llamados = $asterisk->llamadas();
        $contestadas = $asterisk->contestadas();
        $nocontestadas = $asterisk->nocontestadas();
        $tcontestar = $asterisk->tcontestar();
        $tconversacion = $asterisk->tconversacion();

        $results = array(
                "llamados"=>$llamados,
                "contestadas"=>$contestadas, 
                "nocontestadas"=>$nocontestadas, 
                "tcontestar"=>$tcontestar,
                "tconversacion"=>$tconversacion
            );

        echo json_encode($results);
    break;
    
    case 'DLM':
        $llamadosmes = $asterisk->llamadasmes();
        $contestadasmes = $asterisk->contestadasmes();
        $nocontestadasmes = $asterisk->nocontestadasmes();
        $tcontestarmes = $asterisk->tcontestarmes();
        $tconversacionmes = $asterisk->tconversacionmes();

        $results = array(
                "llamadosmes"=>$llamadosmes,
                "contestadasmes"=>$contestadasmes, 
                "nocontestadasmes"=>$nocontestadasmes, 
                "tcontestarmes"=>$tcontestarmes,
                "tconversacionmes"=>$tconversacionmes
            );

        echo json_encode($results);
    break;

    case 'DG':
        $rspta=$asterisk->mesllamados();
        $data = Array();
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                "0"=>$reg->mes,
                "1"=>$reg->llamadas
            );
        }
        $results = array(
            "llamadas"=>count($data),
            "aaData"=>$data
        );
        
        echo json_encode($results);
    break;

    case 'DGau':
        $llamados = $asterisk->llamadas();
        $contestadas = $asterisk->contestadas();

        $results = array(
                "llamados"=>$llamados,
                "contestadas"=>$contestadas
            );

        echo json_encode($results);
    break;


    case 'DGauM':
        $llamadosmes = $asterisk->llamadasmes();
        $contestadasmes = $asterisk->contestadasmes();

        $results = array(
                "llamadosmes"=>$llamadosmes,
                "contestadasmes"=>$contestadasmes
            );

        echo json_encode($results);
    break;


      
}

 ?>