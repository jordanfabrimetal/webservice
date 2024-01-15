var tabla;
var signaturePad;

//funcion que se ejecuta iniciando
function init(){
	mostarform();  
    


	$.post("../ajax/servicio.php?op=verificarsap", function(r){
		$("#inicio").html(r);
         $.post("../ajax/ascensor.php?op=selecttipollamada", function(r){
                    $("#tipollamada").html(r);
                    $("#tipollamada").selectpicker('refresh');
                    $("#codigo").selectpicker('refresh');
                });
                
	});

    $.post("../ajax/servicio.php?op=selectTecnico", function (r) {
		$("#idayud1").html(r);
		$("#idayud1").selectpicker('refresh');

        $("#idayud2").html(r);
		$("#idayud2").selectpicker('refresh');
	});
        
    $('#formularioinicio').on("submit", function(event){
		event.preventDefault();
        //let mensaje = mensajeParadas();
        //bootbox.confirm(mensaje + "¿Confirma que desea iniciar el servicio?", function (result) {
        bootbox.confirm("¿Confirma que desea iniciar el servicio?", function (result) {
            if (result) {
                $('button[data-bb-handler=confirm]').prop("disabled", true);
                if(navigator.geolocation){
                    navigator.geolocation.getCurrentPosition(
                        function(position){
                            $("#latitudso").val(position.coords.latitude);
                            console.log("latitud: "+position.coords.latitude)
                            $("#longitudso").val(position.coords.longitude);
                            console.log("longitud: "+position.coords.longitude)
                            IniciarServ();
                        },function(error){
                            new PNotify({
                                title: 'Geolocalización',
                                text: showError(error.code),
                                type: 'warning',
                                styling: 'bootstrap3'
                            });
                            $("#latitudso").val("-33.385232");
                            $("#longitudso").val("-70.776407");
                            IniciarServ();
                        },{timeout:5000}
                    );
                    //return false;
                }else{
                    new PNotify({
                        title: 'Geolocalización',
                        text: "La Geolocalización no es soportada por este navegador.",
                        type: 'warning',
                        styling: 'bootstrap3'
                    });
                    $("#latitudso").val("-33.385232");
                    $("#longitudso").val("-70.776407");
                    IniciarServ();
                }
                return false;
            }
        });
		//IniciarServ();
	});
        
    $('#formulariofin').on("submit", function(event){
		event.preventDefault();
        if (!$("#formulariofin")[0].checkValidity()){
            $("#formulariofin")[0].reportValidity();
        }else{
            if($("#opfirma").val() == "1"){
                if($("#firma").val() == ""){
                    new PNotify({
                        title: 'Error en la firma',
                        text: 'Debe validar la firma',
                        type: 'error',
                        styling: 'bootstrap3'
                    });
                }else{
                    if ($('#oppre').val() == '1'){ //si se genera presupuesto
                        FinalizarServ();
                    }
                }      
            }else{
                FinalizarServ();
            }
        }
	});
    $('body').on('submit', '#formnewinforme', function(e) {
        
        if($("#chkCertifica").length > 0){
            if ($('#chkCertifica').is(':checked')) {
                $("#certifica").removeClass('alert alert-danger');
                guardarinforme(e);
            
            }else{
                $('#chkCertifica').focus();
                $("#certifica").addClass('alert alert-danger');
                bootbox.alert("Falta certificar que se realizo la mantención correspondiente");
                return false;
            }  
        }else{
            guardarinforme(e);
        }
    });

}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            msg = "El usuario no permitió informar su posición.";
        break;
        case error.POSITION_UNAVAILABLE:
            msg = "El dispositivo no pudo recuperar la posición actual.";
        break;
        case error.TIMEOUT:
            msg = "Se ha superado el tiempo de espera.";
        break;
        case error.UNKNOWN_ERROR:
            msg = "Un error ha ocurrido.";
        break;
    }
    return msg;
}

function mostarform(){
        $("#inicioservicio").hide();
		$("#finservicio").hide();
		$("#inicio").show();		
}

function BuscarEquipo(){        
    if($("#codigo").val() == null){
        new PNotify({
            title: 'Error en el equipo',
            text: 'Debe seleccionar un equipo',
            type: 'error',
            styling: 'bootstrap3'
        });                        
                        
    }else{
        if($("#tipollamada").val() == 17){
            $.post("../ajax/ascensor.php?op=llamadaserviciovisita",{servicecall:$("#codigo").val()}, function(data,status){
                data = JSON.parse(data);
                $("#servicecallID").val(data.ServiceCallID);
                $("#customerCode").val(data.CustomerCode);
                $("#tascensorso").val(data.tipoequipo);
                $("#itemname").val(data.modelo);  
                $("#fm").val(data.InternalSerialNum);
                $("#itemcode").val(data.ItemCode);
                $("#manufacturer").val(data.Manufacturer);   
                $("#edificio").val(data.edificio);
                $("#direccion").val(data.direccion);
                $("#calltype").val(data.CallType);
                //$("#subject").val(data.Subject);
                $("#status").val(data.status);
                $("#codcli").val(data.nomenclatura);
                $("#codclisap").val(data.nomenclatura);
                $("#garantia").val(data.garantiaF);
                $("#equipmentcardnum").val(data.equipmentcardnum);
                $("#inicio").hide();
                $("#inicioservicio").show();
           });
        }else if($("#tipollamada").val() == 5){
            $.post("../ajax/ascensor.php?op=llamadaservicionormalizacion",{servicecall:$("#codigo").val()}, function(data,status){
                data = JSON.parse(data);
                console.log(data);
                $("#servicecallID").val(data.ServiceCallID);
                $("#customerCode").val(data.CustomerCode);
                $("#tascensorso").val(data.tipoequipo);
                $("#itemname").val(data.modelo);  
                $("#fm").val(data.InternalSerialNum);
                $("#itemcode").val(data.ItemCode);
                $("#manufacturer").val(data.Manufacturer);   
                $("#edificio").val(data.edificio);
                $("#direccion").val(data.direccion);
                $("#calltype").val(data.CallType);
                //$("#subject").val(data.Subject);
                $("#status").val(data.status);
                $("#codcli").val(data.nomenclatura);
                $("#codclisap").val(data.nomenclatura);
                $("#garantia").val(data.garantiaF);
                $("#equipmentcardnum").val(data.equipmentcardnum);
                $("#inicio").hide();
                $("#inicioservicio").show();
           });
        }
        else if($("#tipollamada").val() != 4){
            $.post("../ajax/ascensor.php?op=llamadaservicio",{servicecall:$("#codigo").val()}, function(data,status){
                data = JSON.parse(data);
                $("#servicecallID").val(data.ServiceCallID);
                $("#customerCode").val(data.CustomerCode);
                $("#fm").val(data.InternalSerialNum);
                $("#itemcode").val(data.ItemCode);
                $("#manufacturer").val(data.Manufacturer);   
                $("#tascensorso").val(data.tipoequipo);
                $("#itemname").val(data.modelo);  
                $("#edificio").val(data.edificio);
                $("#direccion").val(data.direccion);
                $("#calltype").val(data.CallType);
                $("#subject").val(data.Subject);
                $("#status").val(data.status);
                $("#codcli").val(data.nomenclatura);
                $("#codclisap").val(data.nomenclatura);
                $("#garantia").val(data.garantiaF);
                $("#equipmentcardnum").val(data.equipmentcardnum);
                $("#inicio").hide();
                $("#inicioservicio").show();
    	   });
        }else{
            $.post("../ajax/ascensor.php?op=llamadaservicioemergencia",{servicecall:$("#codigo").val()}, function(data,status){
                data = JSON.parse(data);
                $("#servicecallID").val(data.ServiceCallID);
                $("#customerCode").val(data.CustomerCode);
                $("#tascensorso").val(data.tipoequipo);
                $("#itemname").val(data.modelo);  
                $("#fm").val(data.InternalSerialNum);
                $("#itemcode").val(data.ItemCode);
                $("#manufacturer").val(data.Manufacturer);   
                $("#edificio").val(data.edificio);
                $("#direccion").val(data.direccion);
                $("#calltype").val(data.CallType);
                $("#subject").val(data.Subject);
                $("#status").val(data.status);
                $("#codcli").val(data.nomenclatura);
                $("#codclisap").val(data.nomenclatura);
                $("#garantia").val(data.garantiaF);
                $("#equipmentcardnum").val(data.equipmentcardnum);
                $("#inicio").hide();
                $("#inicioservicio").show();
           });
        }
    }
    cargaPptoPendiente();
}

function cargaPptoPendiente(){
    tabla = $('#tblpresupuestos').dataTable({
        "responsive": true,
        "aProcessing": true,
        "aServerSide": true,
        "ajax": {
            url: '../ajax/servicio.php?op=lspptopend&fm=' + $("#codigo").val(),
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 3, //Paginacion 10 items
        "order": [[0, "desc"]] //Ordenar en base a la columna 0 descendente
    }).DataTable();
}

//function fijarUbicacion(position){
//	$("#latitudso").val(position.coords.latitude);
//        $("#longitudso").val(position.coords.longitude);
//}
//
//function fijaPredeterminada(fail){
//    console.log(fail.message);
//    if(fail.message.indexOf("Only secure origins are allowed") == 0) {
//      $("#latitudso").val("-33.385232");
//      $("#longitudso").val("-70.776407");
//    }	
//}
//


function listar(){
	tabla=$('#tblsolicitudesid').dataTable({
		"aProcessing":true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons:[
			'copyHtml5',
			'print',
			'excelHtml5',
			'csvHtml5',
			'pdf'
		],
		"ajax":{
			url:'../ajax/contrato.php?op=listarsoid',
			type:"get",
			dataType:"json",
			error: function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 10, //Paginacion 10 items
		"order" : [[1 , "desc"]] //Ordenar en base a la columna 0 descendente
	}).DataTable();
}


function IniciarServ(){
    var modnomenclatura = '';
    if($("#codclisap").val() != $("#codcli").val()){
        modnomenclatura = '&nomen=' + $("#codcli").val() + '&codascen=' + $("#equipmentcardnum").val();
        console.log("nomenclatura: "+modnomenclatura);
    }
	var formData = new FormData($("#formularioinicio")[0]);
    formData.forEach(function (value, key){
        console.log("Respuesta del formulario: "+key+": "+value);
    })
    console.log("formdata: "+formData);
    //$("#btnIniciar").prop("disabled", true);
    if($("#servicecallID").val() != ''){
        console.log("servicecallID es distinto a vacio");
        //alert("tiene servcallID "+$("#servicecallID").val()+" - customercode "+$("#customerCode").val());
        var gps = $("#latitudso").val()+','+$("#longitudso").val();
    	$.ajax({
    		url:'../ajax/servicio.php?op=iniciarsap&servicecallID='+$("#servicecallID").val()+'&customerCode='+$("#customerCode").val()+'&estado='+$("#status").val()+modnomenclatura+'&gps='+gps,
    		type:"GET",
    		contentType: false,
    		processData:false,

    		success: function(datos){
                bootbox.alert(datos, function(){ 
                    $(location).attr("href", "estado.php");
                });
    		}
    	});
    }else{
        var gps = $("#latitudso").val()+','+$("#longitudso").val();
        if($("#tipollamada").val() == 17){
            $.ajax({
                url:'../ajax/servicio.php?op=iniciarsapvisita&servicecallID='+$("#servicecallID").val()+'&customerCode='+$("#customerCode").val()+'&itemcode='+$("#itemcode").val()+'&fm='+$("#fm").val()+'&estado='+$("#status").val()+modnomenclatura+'&gps='+gps,
                type:"GET",
                contentType: false,
                processData:false,

                success: function(datos){
                    bootbox.alert(datos, function(){ 
                        $(location).attr("href", "estado.php");
                    });
                }
            });
        }else if($("#tipollamada").val() == 5){
            $.ajax({
                url:'../ajax/servicio.php?op=iniciarsapnormalizacion&servicecallID='+$("#servicecallID").val()+'&customerCode='+$("#customerCode").val()+'&itemcode='+$("#itemcode").val()+'&fm='+$("#fm").val()+'&estado='+$("#status").val()+modnomenclatura+'&gps='+gps+'&subject='+$("#subject").val(),
                type:"GET",
                contentType: false,
                processData:false,

                success: function(datos){
                    bootbox.alert(datos, function(){ 
                        $(location).attr("href", "estado.php");
                    });
                }
            });
        }
        else{
            $.ajax({
                url:'../ajax/servicio.php?op=iniciarsapemergencia&servicecallID='+$("#servicecallID").val()+'&customerCode='+$("#customerCode").val()+'&itemcode='+$("#itemcode").val()+'&fm='+$("#fm").val()+'&estado='+$("#status").val()+modnomenclatura+'&gps='+gps+'&subject='+$("#subject").val(),
                type:"GET",
                contentType: false,
                processData:false,

                success: function(datos){
                    bootbox.alert(datos, function(){ 
                        $(location).attr("href", "estado.php");
                    });
                }
            });
        }
        //alert("no tiene servcallID");
    }
    //return false;
}

function FinalizarServ(){
    

    if (fijarfirma()) {
        bootbox.confirm("¿Desea dar por terminado el servicio?", function (result) {
            if (result) {

                var formData = new FormData($("#formulariofin")[0]);

                formData.append('estadoascensor', $("#idestadofi option:selected" ).text());
                if ($("#opayu").val() == 'S'){
                    if ($("#cantAyud").val() == 1){
                        formData.append('cantayu', 1);
                        formData.append('ayudante1', $("#idayud1 option:selected" ).text());
                    }
                    if ($("#cantAyud").val() == 2){
                        formData.append('cantayu', 2);
                        formData.append('ayudante1', $("#idayud1 option:selected" ).text());
                        formData.append('ayudante2', $("#idayud2 option:selected" ).text());
                    }
                }
                else{
                    formData.append('cantayu', 0);
                }
                $("#btnFinalizar").prop("disabled", true);
                formData.forEach(function (value, key) {
                    console.log('Respuesta dek form: '+key + ': ' + value);
                    console.log('formData dice: '+formData);
                });
                $.ajax({
                    url:'../ajax/servicio.php?op=finalizarsap',
                    type:"POST",
                    data:formData,
                    contentType: false,
                    processData:false,

                    success: function(datos){
                        bootbox.alert(datos, function(){ 
                            $(location).attr("href", "estado.php");
                        });
                        
                    }
                });
            }
        });
    }
}

function bloqueaObs(){    
    $("#obsInt").hide();
}

function formfinalizar(idservicio, idascensor, tipoencuesta, idactividad){

    //ESTO SOLO DEBE MOSTRAR CUANDO SEA MANTENCION - FALTA ESA VALIDACION
    /*
    $.post("../ajax/servicio.php?op=modalperiodoinforme", function (data, status) {
        $("#contenido").html(data);
        $('#modalPreview').modal('show');

        $('body').on('click', '#modalPreview .modal-footer button', function(e) {
            var botonclick = $(this);

            if (botonclick.attr('id') == 'selperiodo') {
                var periodo = $("#periodo").val();
                // alert(periodo);

                $.post("../ajax/servicio.php?op=formnewinforme", {idservicio: idservicio, idascensor: idascensor, tipoencuesta: tipoencuesta, periodo: periodo, idactividad: idactividad}, function (data, status) {
                    $("#otradata").html(data); //data es el html procesada de la plantilla

                    $("#inicioservicio").hide();
                    $("#finservicio").hide();
                    $("#inicio").hide();
                    $("#otradata").show();

                    canvas = document.getElementById('firmafi');
                    if (canvas) {
                        canvas.height = canvas.offsetHeight;
                        canvas.width = canvas.offsetWidth;
                        signaturePad = new SignaturePad(canvas, {
                            backgroundColor: 'rgb(255, 255, 255)',
                            penColor: 'rgb(0, 0, 0)'
                        });
                    }
                    return;
                });
            } else {
                return;
            };
        });

    });
*/

    $.post("../ajax/servicio.php?op=guiaPorCerrar",{idactividad:idactividad}, function(data,status){
        data = JSON.parse(data);
        // alert(data.cerrarguia);
        /*if (data.cerrarguia == 0) {
            bootbox.alert('Puede terminar este servicio despues de esta fecha y hora: ' + data.fechamincierre);
            // alert('Fecha mínima de termino de servicio: ' + data.fechamincierre);
            return;
        }else{*/
            $.post("../ajax/servicio.php?op=selecttestadossap", function(r){
                $("#idestadofi").html(r);
                $("#idestadofi").selectpicker('refresh');
            });
            $.post("../ajax/servicio.php?op=infoguiasap",{idserviciofi:idservicio,idactividad:idactividad}, function(data,status){             
                $("#inicio").hide();
                $("#finservicio").show();
                console.log(data);
                data = JSON.parse(data);
                $("#codigofi").val(data.codigo);
                $("#marcafi").val(data.Manufacturer);
                $("#modelofi").val(data.modelo);
                $("#direccionfi").val(data.direccion);
                $("#edificiofi").val(data.edificio);
                $("#fechainifi").val(data.fecha);
                $("#horainifi").val(data.hora);
                $("#tserviciofi").val(data.CallType);
                $("#estadoinifi").val(data.status);
                $("#observacioniniso").val(data.Subject);
                $("#tascensorfi").val(data.tipoequipo);
                $("#codigofmfi").val(data.codigo);
                $("#customercodefi").val(data.CustomerCode);
                $("#actividadIDfi").val(data.activityID);
                $("#servicecallIDfi").val(data.ServiceCallID);
                $("#ascensorIDfi").val(data.idascensor);
                $("#supervisorID").val(data.supervisorID);
                $("#codclifi").val(data.nomenclatura);
                if(data.idtservicio == 4){
                    var html2 = '<div class="col-md-12 col-sm-12 col-xs-12 form-group"><label>¿Falla por terceros?</label>'+
                                '<select class="form-control" name="terceros" id="terceros"><option value="false">NO</option><option value="true">SI</option></select>'+
                                '</div> ';
                    $("#formdoc").append(html2);
                    $("#terminado option[value='y'").attr("selected",true);
                    $("#terminoreparacion").hide();
                }else if(data.idtservicio !== 2){
                    $("#terminado option[value='y'").attr("selected",true);
                    $("#terminoreparacion").hide();
                }

                /* dejar comentado
                $("#formdoc").empty();
                if (data.idtservicio == 2 || data.idtservicio == 3) {
                    var html = '<div class="col-md-12 col-sm-12 col-xs-12 form-group">'+
                        '<label>Numero presupuesto</label>'+
                        '<input type="text" class="form-control" name="nrodocumento" id="nrodocumento" >'+
                    '</div> ';
                }else if(data.idtservicio == 4){
                    var html = '<div class="col-md-12 col-sm-12 col-xs-12 form-group">'+
                        '<label>Numero de ticket de emergencia</label>'+
                        '<input type="text" class="form-control" name="nrodocumento" id="nrodocumento" >'+
                    '</div> ';
                }else if(data.idtservicio == 1){
                    var html = '<div class="col-md-12 col-sm-12 col-xs-12 form-group">'+
                        '<label>Numero de lista de inspeccion</label>'+
                        '<input type="text" class="form-control" name="nrodocumento" id="nrodocumento" >'+
                    '</div> ';
                }else if(data.idtservicio == 5){
                    var html = '<div class="col-md-12 col-sm-12 col-xs-12 form-group">'+
                        '<label>Numero de ticket ingenieria</label>'+
                        '<input type="text" class="form-control" name="nrodocumento" id="nrodocumento">'+
                    '</div> ';
                }
                $("#formdoc").append(html);
                */
                return false;
            });

            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition(
                    function(position){
                        $("#latitudfi").val(position.coords.latitude);
                        $("#longitudfi").val(position.coords.longitude);
                    },function(error){
                        new PNotify({
                            title: 'Geolocalización',
                            text: showError(error.code),
                            type: 'warning',
                            styling: 'bootstrap3'
                        });
                        $("#latitudfi").val("-33.385232");
                        $("#longitudfi").val("-70.776407");
                    },{timeout:5000}
                );return false;
            }else{
                new PNotify({
                    title: 'Geolocalización',
                    text: "La Geolocalización no es soportada por este navegador.",
                    type: 'warning',
                    styling: 'bootstrap3'
                });
                $("#latitudfi").val("-33.385232");
                $("#longitudfi").val("-70.776407");
            }
        //}
    });        
}

function addvalidform(){
            $("#formfirma").empty();
            var opcion= $("#opfirma").val();
            if (opcion == '1'){
            var myvar = '<div class="col-md-12 col-sm-12 col-xs-12 form-group">                                          <label>Nombres</label>'+
            '<input type="hidden" name="porfirmar" id="porfirmar" value="true">'+
            '<input type="text" class="form-control" name="nombresfi" id="nombresfi" required="Campo requerido">'+
            '</div> '+
            '<div class="col-md-12 col-sm-12 col-xs-12 form-group">                                           <label>Apellidos</label>'+
            '<input type="text" class="form-control" name="apellidosfi" id="apellidosfi" required="Campo requerido">'+
            '</div> '+
            '<div class="col-md-12 col-sm-12 col-xs-12 form-group">                                         <label>Rut</label>'+
            '<input type="text" class="form-control" name="rutfi" id="rutfi" required="Campo requerido">'+
            '</div> '+
            '<div class="col-md-12 col-sm-12 col-xs-12 form-group">                                           <label>Cargo</label>'+
            '<input type="text" class="form-control" name="cargofi" id="cargofi" required="Campo requerido">'+
            '</div>'+
            '<div class="col-md-12 col-sm-12 col-xs-12 form-group" required="Campo requerido">                                           <label>Firma</label>'+ 
            '<div class="input-group">'+
            '<input type="hidden" name="firma" id="firma">'+
            '<input type="text" disabled="disabled" class="form-control" name="firmavali" id="firmavali" required="Campo requerido">'+
            '<div class="input-group-btn">'+
            '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Opciones <span class="caret"></span></button>'+
            '<ul class="dropdown-menu dropdown-menu-right" role="menu">'+
            '<li><a onclick="fijarfirma()">Validar</a></li>'+
            '<li class="divider"></li>'+
            '<li><a onclick="borrarfirma()">Borrar firma</a></li></ul>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '<div class="col-md-12 col-sm-12 col-xs-12 form-group" id="firmapad" name="firmapad" >'+
            '<div class="well">'+
            '<canvas id="firmafi" id="firmafi" class="firmafi" style="border: 2px dashed #888; width: 100%;"></canvas>'+
            '</div>'+
            '</div>';
            $("#formfirma").append(myvar);
            
            var canvas = document.getElementById('firmafi');
            canvas.height = canvas.offsetHeight;
            canvas.width = canvas.offsetWidth;
            signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
            });
            }
            
}



function addayudante1(){    

    var opcion= $("#opayu").val();
    if (opcion == 'S'){
        $("#cantAyu").show();
        $("#cantAyud").prop('required',true);
    }
    else{
        $("#cantAyud").prop('required',false);
        $("#cantAyu").hide();
        $("#ayudantes").html('');
    }
}

function addayudante2(){
    var opcion= $("#cantAyud").val();
    var i = 1;
    $("#ayudantes").html('');
    var listado = '';
    $.ajax({
       type: "POST",
       url: "../ajax/servicio.php?op=selectTecnico",
       async: false,
       success: function(response) { listado = response; }
    });
    while (i <= opcion) {
        var div = '<div class="col-md-12 col-sm-12 col-xs-12 form-group" id="ayud'+i+'"">'+
            '<label for="idayud1">Ingrese ayudante '+i+'</label>'+
            '<select class="form-control selectpicker" data-live-search="true" id="idayud'+i+'" name="idayud'+i+'" required="Campo requerido">'+
                '<option value="" selected disabled>Seleccione Técnico</option>'+
            '</select>'+
        '</div>';
        $("#ayudantes").append(div);
        $("#idayud"+i).html(listado);
        $("#idayud"+i).selectpicker('refresh');
        i++;
    }
    /*if (opcion == '1'){
        $("#ayud1").show();
        $("#ayud2").hide();
    }
    else{
        $("#ayud1").show();
        $("#ayud2").show();
    }*/
}

function fijarfirma(){
    if ($('#porfirmar').val() == 'true') { //campo nuevo para saber si se posterga o no la firma, default true
        if ($('#firmapad').length) {
            if(signaturePad.isEmpty()){
                new PNotify({
                            title: 'Error en la firma',
                            text: 'La firma no puede estar vacia',
                            type: 'error',
                            styling: 'bootstrap3'
                        });
            }else{
                const padfirma = signaturePad.toDataURL();
                if(padfirma){
                $("#firmavali").val("Firma validada");
                $("#firmavali").addClass(' border border-success');
                $("#firma").val(padfirma);
                $("#firmapad").hide();
                return 1;
                }else{
                    $("#firmavali").val("Error al validar");
                    $("#firmavali").addClass(' border border-danger');
                }     
            }
        }
    }
    else
        return 1;
                  
}


function borrarfirma(){
          signaturePad.clear();
          $("#firmapad").show();
}

function ScanQR(){
      let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
      scanner.addListener('scan', function (content) {
        $("#codigo").val(content);
      });
      
      Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
          scanner.start(cameras[1]);
        }
      }).catch(function (e) {
        console.error(e);
      });          
}

function cancelarform() {
    bootbox.alert("Seguro que desea cancelar?", function () {
        $(location).attr("href", "estado.php");
    });
}

function postergarfirma() {
    bootbox.confirm("Seguro que desea postergar la firma del cliente?", function(result){
        if (result) {
            $('#formfirma2').toggle();
            $('#firmavali').prop('required', !$('#firmavali').prop('required'));
            $('#nomcli').prop('required', !$('#nomcli').prop('required'));
            $('#emailcli').prop('required', !$('#emailcli').prop('required'));
            $('#porfirmar').val($('#firmavali').prop('required'));
            $('#observaciones').focus();
            $('#btnPostergar').prop('disabled', 'disabled');
            // disabled="disabled"
        }
            // console.log('This was logged in the callback: ' + result); 
    });
    /*bootbox.alert("Seguro que desea postergar la firma del cliente?", function () {
        $('#formfirma2').toggle();
    });*/
}

function mostrarservicios(id){
    $.post("../ajax/ascensor.php?op=selectascfiltro",{idservicio:id}, function(r){
        $("#codigo").html(r);
        $("#codigo").selectpicker('refresh');
    });
}

function formnewinforme(idservicio, idascensor, tipoencuesta, idactividad) {
    $("#finservicio").html('');
    $.post("../ajax/servicio.php?op=modalperiodoinforme", function (data, status) {
        $("#contenido").html(data);
        $('#modalPreview').modal('show');

        $('body').on('click', '#modalPreview .modal-footer button', function(e) {
            var botonclick = $(this);

            if (botonclick.attr('id') == 'selperiodo') {
                var periodo = $("#periodo").val();
                // alert(periodo);

                $.post("../ajax/servicio.php?op=formnewinforme", {idservicio: idservicio, idascensor: idascensor, tipoencuesta: tipoencuesta, periodo: periodo, idactividad: idactividad}, function (data, status) {
                    $("#otradata").html(data); //data es el html procesada de la plantilla

                    $("#inicioservicio").hide();
                    $("#finservicio").hide();
                    $("#inicio").hide();
                    $("#otradata").show();

                    canvas = document.getElementById('firmafi');
                    if (canvas) {
                        canvas.height = canvas.offsetHeight;
                        canvas.width = canvas.offsetWidth;
                        signaturePad = new SignaturePad(canvas, {
                            backgroundColor: 'rgb(255, 255, 255)',
                            penColor: 'rgb(0, 0, 0)'
                        });
                    }
                    return;
                });
            } else {
                return;
            };
        });

        // console.log(lco);
        // alert(periodo);

        /*$.post("../ajax/servicio.php?op=formnewinforme", {idservicio: idservicio, idascensor: idascensor, tipoencuesta: tipoencuesta}, function (data, status) {
            $("#otradata").html(data); //data es el html procesada de la plantilla

            $("#inicioservicio").hide();
            $("#finservicio").hide();
            $("#inicio").hide();
            $("#otradata").show();

            canvas = document.getElementById('firmafi');
            if (canvas) {
                canvas.height = canvas.offsetHeight;
                canvas.width = canvas.offsetWidth;
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255, 255, 255)',
                    penColor: 'rgb(0, 0, 0)'
                });
            }
            return;
        });*/
    });
}

$('body').on('click', '#modalPreviewww .modal-footer button', function(e) {
    var botonclick = $(this);

    if (botonclick.attr('id') == 'selperiodo') {
        periodo = $("#periodo").val();
        return periodo;
    } else {
        periodo = '';
        return periodo;
    };
});

$('body').on('click', '#modalPPPPPreview .modal-footer button', function(e) {
    var botonclick = $(this);

    if (botonclick.attr('id') == 'selperiodo') {
        var periodo = $("#periodo").val();
        // alert(periodo);

        $.post("../ajax/servicio.php?op=formnewinforme", {idservicio: idservicio, idascensor: idascensor, tipoencuesta: tipoencuesta, periodo: periodo}, function (data, status) {
            $("#otradata").html(data); //data es el html procesada de la plantilla

            $("#inicioservicio").hide();
            $("#finservicio").hide();
            $("#inicio").hide();
            $("#otradata").show();

            canvas = document.getElementById('firmafi');
            if (canvas) {
                canvas.height = canvas.offsetHeight;
                canvas.width = canvas.offsetWidth;
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255, 255, 255)',
                    penColor: 'rgb(0, 0, 0)'
                });
            }
            return;
        });
    } else {
        return;
    };
    // alert('hola');
});

function guardarinforme(e) {
    e.preventDefault();


    if (fijarfirma()) {
        $("#btnGuardar").prop("disabled", true);
        var formData = new FormData($("#formnewinforme")[0]);

        bootbox.confirm("¿Desea generar un nuevo informe para este servicio/equipo?", function (result) {
            if (result) {
                $.ajax({
                    url: '../ajax/servicio.php?op=formguardarinforme',
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function (datos) {
                        bootbox.alert(datos);
                        mostarform(false);
                        $("#inicioservicio").hide();
                        $("#finservicio").hide();
                        $("#otradata").hide();
                        $("#inicio").show();
                        $('#btnInforme').hide();
                        $('#btnInforme2').show();
                        $('#btnTerminar').prop('disabled', false);
                    }
                });
            }
        });

        // limpiar();
    }
}

/*function MostrarPreview(tipo, id){
    if(tipo == 'informeservicio'){        
        var url = '../ajax/servicio.php?op=PDFINFSERVICIO&visita='+id;
        var object = '<object class="PDFdoc" width="100%" style="height: 45vw;" data="'+ url +' "></object>';
        $("#contenido").html(object);
    }
    $('#modalPreview').modal('show');
}*/
function MostrarPreview(tipo, id){
    if(tipo == 'informeservicio'){
        var url = '../ajax/servicio.php?op=PDFINFSERVICIO&visita='+id;
        var object = '<object class="PDFdoc" width="100%" style="height: 45vw;" data="'+ url +' "></object>';
        $("#contenido").html(object);
    }else if(tipo == 'informeservicioescalera'){
        var url = '../ajax/servicio.php?op=PDFINFSERVICIO&tipo=escalera&visita='+id;
        var object = '<object class="PDFdoc" width="100%" style="height: 45vw;" data="'+ url +' "></object>';
        $("#contenido").html(object);
    }
    $('#modalPreview').modal('show');
}

function informesPendientes(idservicio, idactividad) {
    $.ajax({
        type: "POST",
        url: "../ajax/servicio.php?op=informesPendientes",
        data: {idactividad: idactividad},
        // async: false,
        success: function (data){
            var total = data * 1;
            if (total)
                alert('Servicio tiene informe de mantención pendiente por firmar.');
            else
                formfinalizar(idservicio, idactividad);
        }
    });
}

function addpreform2(){
            $("#formpre").empty();
            var opcion= $("#oppre").val();
            if (opcion == '1'){
                $("#zonaUpload").show();
                var myvar = '<div class="col-md-12 col-sm-12 col-xs-12 form-group"><label for="observacionfi">Descripcion de la solicitud</label>'+
                '<textarea type="text" id="descripcion" name="descripcion" required="Campo requerido" class="resizable_textarea form-control"></textarea>'+
                '</div> ';
                $("#formpre").append(myvar);
            }
            else
                $("#zonaUpload").hide();
}

/*
function mensajeParadas() { //solicitado 20220310
    let minutosXparada = 5;
    let valTipoServicio = $("#calltype").val();
    let mensaje = '';
    let valTipoEquipo = $("#codigo option:selected").attr('tipoequipo');
    let valParadas = $("#codigo option:selected").attr('paradas');
        
    if (valTipoServicio == 'Mantención' && valTipoEquipo == 'Ascensor') {
        mensaje = 'Estimado Técnico. Se informa que una vez iniciado el servicio de ' + valTipoServicio + ' del equipo ' + valTipoEquipo + ' seleccionado, dispone de un tiempo mínimo de <b style="color:blue">' + (valParadas * minutosXparada) + ' minutos</b> para poder cerrar la Guía de Servicio.<br><br>';
    }
    return mensaje;
}
*/

init();