<?php
session_start();
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../modelos/Attachment.php';

$attachment = new Attachment();

switch ($_GET['op']){
    case "adjuntosSap":
        $arrResult = array();
        foreach($_POST as $key => $value) {
            $rspta = $attachment->obtenerArchivosSap($value);
            $rsptaJson = json_decode($rspta); //true para que sea array, no objeto
            $arrResult[$key] = $rsptaJson;
        }
        //arrResult es un arreglo donde el key es el titulo y el valor es el resultados de los adjuntos encontrados en SAP
        //todo se pasa despues al componente adjuntos para que muestre la info
        echo json_encode($arrResult);
        break;

    case "previewAdjunto":
        $arrResult = array();
        $idattachment = isset($_REQUEST['idattachment']) ? $_REQUEST['idattachment'] : 0;
        $archivo = isset($_REQUEST['archivo']) ? $_REQUEST['archivo'] : 0;

        $rspta = $attachment->obtenerBinarioArchivo($idattachment, $archivo);
        
        $f = finfo_open(); //funcion para obtener informacion del contenido

        $mime_type = finfo_buffer($f, $rspta, FILEINFO_MIME_TYPE); //ej: image/png

        $arrResult = array('mimetype' => $mime_type, 'data' => base64_encode($rspta));

        echo json_encode($arrResult);
        break;
}
