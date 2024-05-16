<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '../config/conexion.php';

/**
 * Description of Encuesta
 *
 * @author aaron
 */
class Encuesta {
    
    function __construct() {
        
    }

    function nuevaVisita($arrParams){
        // echo "INSERT INTO `informevisita`(`enc_id`, `infv_ascensor`, `infv_fecha`, `infv_observaciones`, `infv_empleado`, `infv_firmaempleado`, `infv_cliente`, `infv_firmacliente`, `infv_comentario`) VALUES ({$arrParams['encuesta']}, {$arrParams['equipo']}, '{$arrParams['fecha']}', '{$arrParams['observaciones']}', {$arrParams['empleado']}, '{$arrParams['firmaempleado']}', {$arrParams['cliente']}, '{$arrParams['firmacliente']}', '{$arrParams['comentario']}')";

        $sql = "INSERT INTO `informevisita`(`enc_id`, `infv_ascensor`, `infv_servicio`, `infv_periodo`, `infv_observaciones`, `infv_empleado`, `infv_firmaempleado`, `infv_cliente`, `infv_firmacliente`, `infv_empresa`, `infv_direccion`, `infv_nomcli`, `infv_rutcli`, `infv_celularcli`, `infv_emailcli`, `infv_estado`, `infv_actividad`,`imgfoso`,`imgtecho`,`imgmaquina`,`imgoperador`) VALUES ({$arrParams['encuesta']}, '{$arrParams['equipo']}', {$arrParams['servicio']}, '{$arrParams['periodo']}', '{$arrParams['observaciones']}', {$arrParams['empleado']}, '{$arrParams['firmaempleado']}', '{$arrParams['cliente']}', '{$arrParams['firmacliente']}', '{$arrParams['empresa']}', '{$arrParams['direccion']}', '{$arrParams['nomcli']}', '{$arrParams['rutcli']}', '{$arrParams['celularcli']}', '{$arrParams['emailcli']}', '{$arrParams['estado']}', {$arrParams['actividad']}, '{$arrParams['imgfoso']}', '{$arrParams['imgtecho']}', '{$arrParams['imgmaquina']}', '{$arrParams['imgoperador']}')";
        return ejecutarConsu_retornarID($sql);
        // return ejecutarConsulta($sql);
    }

    function nuevaRespuestaVisita($visita, $respuestas){
        foreach($respuestas as $i => $item)
        {
            $tipo = $item['tipo'] . '';
            $data = $item['data'];
            foreach($data as $j => $resp)
            {
                $idtipo = $j;
                $respuesta = $resp;
                
                /*echo '<pre>';
                print_r($resp);
                echo '</pre>';*/

                if ($respuesta)
                {
                    $sql = "INSERT INTO `respuesta`(`infv_id`, `resp_tipo`, `resp_idtipo`, `resp_data`) VALUES ({$visita}, '{$tipo}', {$idtipo}, '{$respuesta}')";
                    ejecutarConsulta($sql);
                    error_log($sql);
                    //LOG
                    $logFile = fopen("log/log.txt", 'a') or die("Error creando archivo");
                    fwrite($logFile, "\n".date("d/m/Y H:i:s")." - Respuesta : ".$sql) or die("Error escribiendo en el archivo");
                    fclose($logFile);  
                }
            }
        }
        /*echo "INSERT INTO `respuesta`(`resp_id`, `infv_id`, `resp_tipo`, `resp_idtipo`, `resp_data`) VALUES ({$visita}, {$arrParams['equipo']}, '{$arrParams['fecha']}', '{$arrParams['observaciones']}', {$arrParams['empleado']}, '{$arrParams['firmaempleado']}', {$arrParams['cliente']}, '{$arrParams['firmacliente']}', '{$arrParams['comentario']}')";

        $sql = "INSERT INTO `informevisita`(`enc_id`, `infv_ascensor`, `infv_observaciones`, `infv_empleado`, `infv_firmaempleado`, `infv_cliente`, `infv_firmacliente`, `infv_comentario`) VALUES ({$arrParams['encuesta']}, {$arrParams['equipo']}, '{$arrParams['observaciones']}', {$arrParams['empleado']}, '{$arrParams['firmaempleado']}', {$arrParams['cliente']}, '{$arrParams['firmacliente']}', '{$arrParams['comentario']}')";
        return ejecutarConsu_retornarID($sql);*/
        // return ejecutarConsulta($sql);
        return true;
    }
    
    /*function Insertar($nombre, $descripcion, $vigencia){
        $sql = "INSERT INTO `bodega`(`nombre`, `descripcion`, `vigencia`) "
                . "VALUES ('$nombre', '$descripcion', $vigencia)";
        return ejecutarConsulta($sql);
    }
    
    function Editar($idbodega, $nombre, $descripcion, $vigencia){
        $sql = "UPDATE `bodega` SET "
                . "`nombre`= '$nombre',"
                . "`descripcion`= '$descripcion',"
                . "`vigencia`= $vigencia "
                . " WHERE `idbodega`= $idbodega";
        return ejecutarConsulta($sql);
    }
    
    function mostrar($idbodega){
        $sql = "SELECT * FROM `bodega` WHERE `idbodega`= $idbodega";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    function Listar(){
        $sql = "SELECT * FROM `bodega`";
        return ejecutarConsulta($sql);
    }*/

    function firmarVisita($arrParams){

        $sql = "UPDATE `informevisita` SET `infv_firmacliente` = '{$arrParams['firmacliente']}', `infv_nomcli` = '{$arrParams['nomcli']}', `infv_rutcli` = '{$arrParams['rutcli']}', `infv_celularcli` = '{$arrParams['celularcli']}', `infv_emailcli` = '{$arrParams['emailcli']}', `infv_estado` = '{$arrParams['estado']}', `infv_fechamod` = now() WHERE infv_id = {$arrParams['idinforme']}";
        // return ejecutarConsu_retornarID($sql);
        return ejecutarConsulta($sql);
    }
    
    function bloques($idencuesta){
        $sql = "SELECT * FROM `bloque` Where enc_id = $idencuesta ORDER BY blq_id";
        return ejecutarConsulta($sql);
    }

    function preguntas($idbloque){
        $sql = "SELECT * FROM `pregunta` Where blq_id = $idbloque order by preg_orden";
        return ejecutarConsulta($sql);
    }

    function respPreguntas($idencuesta){
        $sql = "SELECT res.* FROM `pregunta` AS pre INNER JOIN `respuesta` AS res ON res.resp_idtipo = pre.preg_id WHERE res.infv_id = $idencuesta order by res.resp_tipo, pre.preg_orden";
        return ejecutarConsulta($sql);
    }

    // function infoEquipo($idequipo) {
    function infoEquipo($idservicio) {
        $query = 'sml.svc/LISTA_ACTIVIDADES?$filter=srvCodigo eq ' . $idservicio . '&$select=equEdificio,equSnInterno,artModelo,artTipoEquipo,artFabricante,cliNombre,equCalle,equCalleNro,equCiudad,cliCodigo,tecNombre,tecApellido';
        $rspta = Query($query);
        $rsptaJson = json_decode($rspta, true);
        if (is_array($rsptaJson['value'])) {
            if (count($rsptaJson['value'])) { //para saber cuantas filas de datos vienen
                $data = $rsptaJson['value'];
                return json_encode($data);
            } else {
                return 'NODATASAP';
            }
        }
        else
            return 'NODATASAP';
    }

    
    function infoVisita($idvisita) {
        /*$sql = "Select p.idproyecto, p.idventa, p.nombre, a.codigo, a.idascensor, a.estadoins estado, DATE(p.created_time) as fecha, m.nombre AS modelo, t.nombre AS tipoascensor,
            i.infv_fecha, i.infv_ascensor, i.infv_observaciones, i.infv_cliente, i.infv_firmacliente, i.infv_empleado, i.infv_empresa, i.infv_direccion, u.firma, u.filefir, u.num_documento, concat(u.nombre, ' ', u.apellido) as 'nomusuario',
            i.infv_nomcli, i.infv_rutcli, i.infv_celularcli, i.infv_emailcli,
            e.nombre as 'estadonomb', e.color, e.carga, c.codigo as 'ccnomb' , 
            concat(pm.nombre, ' ', pm.apellido) as 'pm', concat(s.nombre, ' ', s.apellido) as 'supervisor',
            DATE_FORMAT(a.updated_time, '%d-%m-%Y') as 'updated_time', 
            ifnull(datediff(curdate(), a.updated_time),0) as 'dias', 
            ifnull(datediff(curdate(), a.created_time),0) as 'dias_comienzo',
            cli.razon_social, edi.nombre AS nomedificio, edi.calle, edi.numero
            from ascensor a
            LEFT JOIN venta ve ON ve.idventa = a.idventa 
            LEFT JOIN estadopro e on e.estado = a.estadoins 
            LEFT JOIN centrocosto c on ve.idcentrocosto=c.idcentrocosto 
            LEFT JOIN proyecto p on p.idventa = ve.idventa 
            LEFT JOIN pm pm on pm.idpm = p.idpm 
            LEFT JOIN supervisorins s on s.idsupervisorins = p.idsupervisor 
            LEFT JOIN modelo m on m.marca = a.marca and m.idmodelo = a.modelo
            LEFT JOIN tascensor t on t.idtascensor = a.idtascensor
            LEFT JOIN contrato con on a.idcontrato = con.idcontrato
            LEFT JOIN cliente cli on con.idcliente = cli.idcliente
            LEFT JOIN edificio edi on a.idedificio = edi.idedificio
            LEFT JOIN informevisita i on i.infv_ascensor = a.idascensor
            LEFT JOIN user u on u.iduser = i.infv_empleado
            WHERE i.infv_id = $idvisita";*/
        $sql = "Select i.*,u.firma, u.filefir, u.num_documento, concat(u.nombre, ' ', u.apellido) as 'nomusuario' from informevisita i LEFT JOIN user u on u.iduser = i.infv_empleado WHERE i.infv_id = $idvisita";
        return ejecutarConsulta($sql);
        /*$result = ejecutarConsulta($sql);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $data = array();
        if (count($rows))
            $data = $rows[0];
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit();*/
        // return ejecutarConsulta($sql);
    }

    function visitasEquipo($idequipo) {
        $sql = "SELECT inv.*, enc.enc_nombre, enc.enc_id FROM informevisita AS inv INNER JOIN encuesta AS enc ON inv.enc_id = enc.enc_id WHERE infv_ascensor = $idequipo ORDER BY infv_id desc";
        return ejecutarConsulta($sql);
    }

    //se obtiene el numero de visita tomando en cuenta el tipo de encuesta, el equipo y id de la visita
    function numeroDeVisita($tipoencuesta, $idascensor, $idvisita)
    {
        $sql = "SELECT COUNT(*) as nvisita FROM informevisita WHERE infv_id <= $idvisita and infv_ascensor = '$idascensor' and enc_id = $tipoencuesta";
        return ejecutarConsultaSimpleFila($sql);
    }

    function ultimaVisita($tipoencuesta,$idascensor){
        $sql = "SELECT * FROM informevisita WHERE infv_ascensor = $idascensor AND enc_id = $tipoencuesta ORDER BY infv_id DESC LIMIT 1";
        return ejecutarConsultaSimpleFila($sql);
    }

    //se obtiene el total de informes del servicio asociado al equipo
    function numInformeEquipo($tipoencuesta, $idascensor, $idservicio)
    {
        $sql = "SELECT COUNT(*) as ninformes FROM informevisita WHERE infv_ascensor = '$idascensor' and enc_id = $tipoencuesta and infv_servicio = $idservicio";
        $resultado = ejecutarConsultaSimpleFila($sql);
        error_log("Resultado de modelos/Encuesta.php>numInformeEquipo: ".print_r($resultado, true));
        return ejecutarConsultaSimpleFila($sql);
    }

    function ultimoInforme($tipoencuesta, $idascensor, $idservicio){
        $sql = "SELECT * FROM informevisita WHERE infv_ascensor = '$idascensor' and enc_id = $tipoencuesta and infv_servicio = $idservicio ORDER BY infv_id DESC LIMIT 1";
        $result = ejecutarConsultaSimpleFila($sql);
        error_log("resultado modelos/Encuesta.php>ultimoIndorme: ".print_r($result, true));
        return ejecutarConsultaSimpleFila($sql);
    }

    function encuestasPorFirmar($iduser) {
        $sql = "SELECT * FROM informevisita WHERE infv_estado = 'porfirmar' AND infv_empleado = '$iduser' AND enc_id < 5 ORDER BY infv_id";
        return ejecutarConsulta($sql);
    }

    //se obtiene el total de informes pendientes de firmar para un id de actividad
    function informesPendientes($idactividad)
    {
        $sql = "SELECT COUNT(*) as ninformes FROM informevisita WHERE infv_actividad = $idactividad and infv_estado = 'porfirmar'";
        return ejecutarConsultaSimpleFila($sql);
    }

    function infoVisitaNuevo($idactividad) {
        /*$sql = "Select p.idproyecto, p.idventa, p.nombre, a.codigo, a.idascensor, a.estadoins estado, DATE(p.created_time) as fecha, m.nombre AS modelo, t.nombre AS tipoascensor,
            i.infv_fecha, i.infv_ascensor, i.infv_observaciones, i.infv_cliente, i.infv_firmacliente, i.infv_empleado, i.infv_empresa, i.infv_direccion, u.firma, u.filefir, u.num_documento, concat(u.nombre, ' ', u.apellido) as 'nomusuario',
            i.infv_nomcli, i.infv_rutcli, i.infv_celularcli, i.infv_emailcli,
            e.nombre as 'estadonomb', e.color, e.carga, c.codigo as 'ccnomb' , 
            concat(pm.nombre, ' ', pm.apellido) as 'pm', concat(s.nombre, ' ', s.apellido) as 'supervisor',
            DATE_FORMAT(a.updated_time, '%d-%m-%Y') as 'updated_time', 
            ifnull(datediff(curdate(), a.updated_time),0) as 'dias', 
            ifnull(datediff(curdate(), a.created_time),0) as 'dias_comienzo',
            cli.razon_social, edi.nombre AS nomedificio, edi.calle, edi.numero
            from ascensor a
            LEFT JOIN venta ve ON ve.idventa = a.idventa 
            LEFT JOIN estadopro e on e.estado = a.estadoins 
            LEFT JOIN centrocosto c on ve.idcentrocosto=c.idcentrocosto 
            LEFT JOIN proyecto p on p.idventa = ve.idventa 
            LEFT JOIN pm pm on pm.idpm = p.idpm 
            LEFT JOIN supervisorins s on s.idsupervisorins = p.idsupervisor 
            LEFT JOIN modelo m on m.marca = a.marca and m.idmodelo = a.modelo
            LEFT JOIN tascensor t on t.idtascensor = a.idtascensor
            LEFT JOIN contrato con on a.idcontrato = con.idcontrato
            LEFT JOIN cliente cli on con.idcliente = cli.idcliente
            LEFT JOIN edificio edi on a.idedificio = edi.idedificio
            LEFT JOIN informevisita i on i.infv_ascensor = a.idascensor
            LEFT JOIN user u on u.iduser = i.infv_empleado
            WHERE i.infv_id = $idvisita";*/
        $sql = "Select i.*,u.firma, u.filefir, u.num_documento, concat(u.nombre, ' ', u.apellido) as 'nomusuario' from informevisita i LEFT JOIN user u on u.iduser = i.infv_empleado WHERE i.infv_actividad = '$idactividad' ORDER BY i.infv_id DESC";
        return ejecutarConsulta($sql);
        /*$result = ejecutarConsulta($sql);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $data = array();
        if (count($rows))
            $data = $rows[0];
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit();*/
        // return ejecutarConsulta($sql);
    }
}
