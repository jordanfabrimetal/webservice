<?php

    $fm = $_POST['fm'];

    error_log("fm dice ".$fm);
    /* ver si existe alguno abierto o antendido*/
    $sql = "ServiceCalls/\$count?\$filter=Status eq -3 and InternalSerialNum eq '".$fm."' and CallType eq 5";
    $contador = Query($sql);
    if($contador >= 1){
        error_log("en 1");
        /* Inicio ServicaCalls */
        $entity = 'ServiceCalls';
        $select = 'ServiceCallID,ItemCode,InternalSerialNum,CallType,Subject,CustomerCode';
        $filter = "Status eq -3 and InternalSerialNum eq '".$fm."' and CallType eq 5";
        $servcall = json_decode(ConsultaEntity($entity,$select,$filter), true);
        $servcall = $servcall['value'][0];
        //print_r($servcall);die;
        /* Fin ServicaCalls */
        $customerCode = $servcall['CustomerCode'];
        /* Inicio CallType */
        if(!empty($servcall['CallType'])){
            $CallType = '';
            $entity = 'ServiceCallTypes';
            $id = $servcall["CallType"];
            $select = 'Name';
            $tipo = json_decode(ConsultaIDNum($entity,$id,$select), true);
        }
        /* Fin CallType */

        /* Inicio CustomerEquipmentCards */
        $select = 'EquipmentCardNum,InternalSerialNum,BuildingFloorRoom,Street,StreetNo,InstallLocation,U_NX_ESTADOFM,U_NX_NOMENCLATURACL,U_NX_GarantiaF';
        $entity = 'CustomerEquipmentCards';
        $filter = "InternalSerialNum eq '".$servcall['InternalSerialNum']."' and ItemCode eq '".$servcall['ItemCode']."'";
        $rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
        foreach ($rspta as $val=>$value) {
            switch ($val) {
                case 'value':
                    foreach ($value as $data) {
                        $nombre = $data['InstallLocation'];
                        $direccion = $data['Street'].' '.$data['StreetNo'];
                        $nomenclatura = $data['U_NX_NOMENCLATURACL'];
                        $garantiaF = $data['U_NX_GarantiaF'];
                        $equipmentcardnum = $data['EquipmentCardNum'];
                        $status = '';
                        if(!empty($data['U_NX_ESTADOFM'])){
                            $entityEstado = 'U_NX_ESTADOS_FM';
                            $idEstado = $data['U_NX_ESTADOFM'];
                            $selectEstado = 'Name';
                            $retornaEstado = json_decode(ConsultaIDLet($entityEstado,$idEstado,$selectEstado),true);
                            $status = (!empty($retornaEstado['Name'])) ? $retornaEstado['Name'] : "";
                        }

                        if(!empty($servcall['ItemCode'])){
                            /* Items */
                            $entity = 'Items';
                            $select = 'Manufacturer,ItemName,U_NX_MODELO,U_NX_TIPEQUIPO';
                            $filter = "ItemCode eq '".$servcall['ItemCode']."'";
                            $datamanu = json_decode(ConsultaEntity($entity,$select,$filter), true);
                            foreach ($datamanu as $val=>$value) {
                                switch ($val) {
                                    case 'value':
                                        foreach($value as $val){
                                            if(!empty($val['Manufacturer'])){
                                                $Manufacturer = '';
                                                $entity = 'Manufacturers';
                                                $id = $val['Manufacturer'];
                                                $select = 'ManufacturerName';
                                                $manufacturer = json_decode(ConsultaIDNum($entity,$id,$select), true);
                                            }
                                            $ItemName = $val['ItemName'];
                                            $modelo = $val['U_NX_MODELO'];
                                            $tipoequipo = $val['U_NX_TIPEQUIPO'];
                                        }
                                    break;
                                }
                            }
                        }
                    }
                break;
            }
        }
        /* Fin CustomerEquipmentCards */
        return json_encode(
            array(
                "CustomerCode"=>$customerCode,
                "ServiceCallID"=>$servcall["ServiceCallID"],
                "ItemCode"=>$servcall['ItemCode'],
                "Manufacturer"=>$manufacturer['ManufacturerName'],
                "ItemName"=>$ItemName,
                "edificio"=>$nombre,
                "direccion"=>$direccion,
                "CallType"=>$tipo['Name'],
                "status"=>$status,
                "Subject"=>$servcall['Subject'],
                "InternalSerialNum"=>$servcall['InternalSerialNum'],
                "modelo"=>$modelo,
                "tipoequipo"=>$tipoequipo,
                "nomenclatura"=>$nomenclatura,
                "garantiaF"=>$garantiaF,
                "equipmentcardnum"=>$equipmentcardnum
            )
        );
    }else{
        error_log("en 2");
        $select = 'EquipmentCardNum,CustomerCode,InternalSerialNum,ItemCode,BuildingFloorRoom,Street,StreetNo,InstallLocation,U_NX_ESTADOFM,U_NX_NOMENCLATURACL,U_NX_GarantiaF';
        $entity = 'CustomerEquipmentCards';
        $filter = "InternalSerialNum eq '".$fm."'";
        $rspta = json_decode(ConsultaEntity($entity,$select,$filter), true);
        error_log("rspta44 ".json_encode($rspta));
        foreach ($rspta['value'] as $val) {
            
            $nombre = $val['InstallLocation'];
            $direccion = $val['Street'].' '.$val['StreetNo'];
            $nomenclatura = $val['U_NX_NOMENCLATURACL'];
            $garantiaF = $val['U_NX_GarantiaF'];
            $equipmentcardnum = $val['EquipmentCardNum'];
            $ItemCode = $val['ItemCode'];
            $customerCode = $val['CustomerCode'];
            $InternalSerialNum = $val['InternalSerialNum'];
            if(!empty($val['U_NX_ESTADOFM'])){
                $entityEstado = 'U_NX_ESTADOS_FM';
                $idEstado = $val['U_NX_ESTADOFM'];
                $selectEstado = 'Name';
                $retornaEstado = json_decode(ConsultaIDLet($entityEstado,$idEstado,$selectEstado),true);
            }
            $status = (!empty($retornaEstado['Name'])) ? $retornaEstado['Name'] : "";

            if(!empty($val['ItemCode'])){
                /* Items */
                $entity = 'Items';
                $select = 'Manufacturer,ItemName,U_NX_MODELO,U_NX_TIPEQUIPO';
                $filter = "ItemCode eq '".$val['ItemCode']."'";
                $datamanu = json_decode(ConsultaEntity($entity,$select,$filter), true);
                foreach ($datamanu['value'] as $value) {
                    if(!empty($value['Manufacturer'])){
                        $Manufacturer = '';
                        $entity = 'Manufacturers';
                        $id = $value['Manufacturer'];
                        $select = 'ManufacturerName';
                        $manufacturer = json_decode(ConsultaIDNum($entity,$id,$select), true);
                    }
                    $ItemName = $value['ItemName'];
                    $modelo = $value['U_NX_MODELO'];
                    $tipoequipo = $value['U_NX_TIPEQUIPO'];
                }
            }
        }
        /* Fin CustomerEquipmentCards */
        return json_encode(
            array(
                "ServiceCallID"=>"",
                "CustomerCode"=>$customerCode,
                "ItemCode"=>$ItemCode,
                "Manufacturer"=>$manufacturer['ManufacturerName'],
                "ItemName"=>$ItemName,
                "edificio"=>$nombre,
                "direccion"=>$direccion,
                "CallType"=>"NORMALIZACIÓN",
                "CallTypeID"=>5,
                "status"=>$status,
                "Subject"=>"Visita generada por Integración",
                "InternalSerialNum"=>$InternalSerialNum,
                "modelo"=>$modelo,
                "tipoequipo"=>$tipoequipo,
                "nomenclatura"=>$nomenclatura,
                "garantiaF"=>$garantiaF,
                "equipmentcardnum"=>$equipmentcardnum
            )
        );
    }




?>