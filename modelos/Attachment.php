<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/conexion.php';
require_once "../config/conexionSap.php";
/**
 * Description of Attachment
 *
 * @author ocontreras
 */
class Attachment {
    
    function __construct() {
        
    }

    public function subirArchivosSap($files, $timestamp = false) {
        return UploadFile($files, $timestamp);
    }

    public function obtenerArchivosSap($idattachment) {
        $query = 'Attachments2('.$idattachment.')';

        $rspta = Query($query);

        $rsptaJson = json_decode($rspta, true);

        if (count($rsptaJson['Attachments2_Lines'])) { //para saber cuantas filas de datos vienen
            $data = $rsptaJson['Attachments2_Lines'];

            return json_encode($data);

        }
        else
            return 'NODATASAP';
    }

    public function obtenerBinarioArchivo($idattachment, $archivo) {
        $query = 'Attachments2(' . $idattachment . ')/$value?filename=\'' . $archivo . '\'';

        $rspta = Query($query);

        $rsptaJson = json_decode($rspta, true);

        if (!isset($rsptaJson['error']))
            return $rspta;
        else
            return 'NODATASAP';
    }
}
