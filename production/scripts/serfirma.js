var tabla, tabla2, signaturePad;

//funcion que se ejecuta iniciando
function init() {
    mostarform();
    listar();

    $('[data-toggle="tooltip"]').tooltip();

    $('#formulario').on("submit", function (event) {
        event.preventDefault();

        if ($("#firma").val() == "") {
            new PNotify({
                title: 'Error en la firma',
                text: 'Debe validar la firma',
                type: 'error',
                styling: 'bootstrap3'
            });
        } else {
            firmarSap();
        }
    });

    $('body').on('submit', '#formfirmarinforme', function(e) {
        firmarinforme(e);
    });
}


// Otras funciones
function limpiar() {
    $("#nombre").val("");
    $("#apellido").val("");
    $("#rut").val("");
    $("#cargo").val("");
    $("#firma").val("");
    $("#firmavali").val("");
}

function mostarform(flag) {
    $("#formularioservicio").hide();
    $("#listadoservicios").show();
}

function cancelarform() {
    bootbox.alert("Seguro que desea cancelar?", function () {
        $(location).attr("href", "estado.php");
    });
}


function listar() {
    tabla = $('#tblservicios').dataTable({
        "responsive": true,
        "aProcessing": true,
        "aServerSide": true,
        "ajax": {
            url: '../ajax/servicio.php?op=lsfsap',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, //Paginacion 10 items
        "order": [[1, "asc"]] //Ordenar en base a la columna 0 descendente
    }).DataTable();

    tabla2 = $('#tblencporfirmar').dataTable({
        "responsive": true,
        "aProcessing": true,
        "aServerSide": true,
        "ajax": {
            url: '../ajax/servicio.php?op=encxfirmar',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, //Paginacion 10 items
        "order": [[1, "asc"]] //Ordenar en base a la columna 0 descendente
    }).DataTable();
}


function firmar() {
    var formData = new FormData($("#formulario")[0]);
    $("#btnGuardar").prop("disabled", true);
    $.ajax({
        url: '../ajax/servicio.php?op=firmar',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            bootbox.alert(datos, function () {
                $(location).attr("href", "estado.php");
            });
        }
    });
}

function fijarfirma() {
    if (signaturePad.isEmpty()) {
        new PNotify({
            title: 'Error en la firma',
            text: 'La firma no puede estar vacia',
            type: 'error',
            styling: 'bootstrap3'
        });
    } else {
        const padfirma = signaturePad.toDataURL();
        if (padfirma) {
            $("#firmavali").val("Firma validada");
            $("#firmavali").addClass(' border border-success');
            $("#firma").val(padfirma);
            $("#firmapad").hide();
        } else {
            $("#firmavali").val("Error al validar");
            $("#firmavali").addClass(' border border-danger');
        }
    }
}

function borrarfirma() {
    signaturePad.clear();
    $("#firmapad").show();
}


function formfirmar(idservicio) {
    $("#formularioservicio").show();
    $("#listadoservicios").hide();

    $.post("../ajax/servicio.php?op=ffirma", {idservicio: idservicio}, function (data, status) {
        data = JSON.parse(data);
        $("#idserfirma").val(data.idservicio);
        $("#codigo").val(data.codigo);
        $("#tascensor").val(data.tipo);
        $("#marca").val(data.marca);
        $("#modelo").val(data.modelo);
        $("#edificio").val(data.edificio);
        $("#direccion").val(data.calle + ' ' + data.numero);
        $("#fechaini").val(data.fechaini);
        $("#horaini").val(data.horaini);
        $("#tservicio").val(data.tiposer);
        $("#estadoini").val(data.estadoini);
        $("#observacionini").val(data.observacionini);
        $("#fechafin").val(data.fechafin);
        $("#horafin").val(data.horafin);
        $("#estadofin").val(data.estadofn);
        $("#observacionfin").val(data.observacionfin);

        var canvas = document.getElementById('firmafi');
        canvas.height = canvas.offsetHeight;
        canvas.width = canvas.offsetWidth;
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

    });
}

function informesPendientes(idactividad) {
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
                formfirmarsap(idactividad);
        }
    });
}

function formfirmarsap(idactividad) {
    $("#formularioservicio").show();
    $("#listadoservicios").hide();

    $.post("../ajax/servicio.php?op=ffirmasap", {idactividad: idactividad}, function (data, status) {
        data = JSON.parse(data);
        console.log(data.srvCodigo);
        $("#idserfirma").val(data.srvCodigo);
        $("#codigo").val(data.equSnInterno);
        $("#tascensor").val(data.artTipoEquipo);
        $("#marca").val(data.artFabricante);
        $("#modelo").val(data.artModelo);
        $("#edificio").val(data.equEdificio);
        $("#direccion").val(data.equCalle + ' ' + data.equCalleNro);
        $("#fechaini").val(data.actFechaIni);
        $("#horaini").val(data.actHoraIni);
        $("#tservicio").val(data.srvTipoLlamada);
        $("#estadoini").val(data.actEstEquiIni);
        $("#observacionini").val(data.srvAsunto);
        $("#fechafin").val(data.actFechaFin);
        $("#horafin").val(data.actHoraFin);
        $("#estadofin").val(data.actEstEquiFin);
        $("#observacionfin").val(data.actComentario);
        $("#idactividad").val(idactividad);
        $("#supervisorID").val(data.equSupId);
        
        var canvas = document.getElementById('firmafi');
        canvas.height = canvas.offsetHeight;
        canvas.width = canvas.offsetWidth;
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

    });
}

function formfirmarinforme(idinforme) {
    $.post("../ajax/servicio.php?op=formfirmarinforme", {idinforme: idinforme}, function (data, status) {
        $("#otradata").html(data); //data es el html procesada de la plantilla

        $("#listadoservicios").hide();
        $("#finservicio").hide();
        $("#inicio").hide();
        $("#otradata").show();

        canvas = document.getElementById('firmafi2');
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
}

function firmarSap() {
    var formData = new FormData($("#formulario")[0]);
    $("#btnGuardar").prop("disabled", true);
    $.ajax({
        url: '../ajax/servicio.php?op=firmarsap',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            bootbox.alert(datos, function () {
                $(location).attr("href", "estado.php");
            });
        }
    });
}

function fijarfirma2(){
    if ($('#firmapad2').length) {
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
            $("#firma2").val(padfirma);
            $("#firmapad2").hide();
            return 1;
            }else{
                $("#firmavali").val("Error al validar");
                $("#firmavali").addClass(' border border-danger');
            }     
        }
    }
}


function borrarfirma2(){
          signaturePad.clear();
          $("#firmapad2").show();
}

function firmarinforme(e) {
    e.preventDefault();

    if (fijarfirma2()) {
        $("#btnGuardar").prop("disabled", true);
        var formData = new FormData($("#formfirmarinforme")[0]);

        bootbox.confirm("¿Desea firmar el informe y enviarlo por email al cliente?", function (result) {
            if (result) {
                $.ajax({
                    url: '../ajax/servicio.php?op=formguardarfirmarinforme',
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
                        tabla2.ajax.reload();
                    }
                });
            }
        });

        // limpiar();
    }
}

function MostrarPreview(tipo, id){
    console.log(tipo);
    console.log(id);
    if(tipo == 'informeservicio'){        
        // var url = '../ajax/servicio.php?op=PDFINFSERVICIO&visita=462,FM131067,3';
        var url = '../ajax/servicio.php?op=PDFINFSERVICIO&visita='+id;
        var object = '<object class="PDFdoc" width="100%" style="height: 45vw;" data="'+ url +' "></object>';
        $("#contenido").html(object);
    }
    $('#modalPreview').modal('show');
}

function informesPendientesnuevo(idactividad){

    $.ajax({
            data:  {idactividad:idactividad}, //datos que se envian a traves de ajax
            url:   '../ajax/servicio.php?op=formfirmarinformenuevo', //archivo que recibe la peticion
            type:  'post', //método de envio
            beforeSend: function () {
                $("#listadoservicios").hide();
                $("#finservicio").hide();
                $("#inicio").hide();
            },
            success:  function (data) { //una vez que el archivo recibe el request lo procesa y lo devuelve
                    $("#otradata").html(data); //data es el html procesada de la plantilla
                    $("#otradata").show();
            }
    });/*
     $.post("", , function (data, status) {
        
    });*/
   setTimeout(function(){formfirma();}, 3000);
                    

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

function formfirma(){
    //alert('asdasd');
        var canvas = document.getElementById('firmafi3');
        if (canvas) {
            canvas.height = canvas.offsetHeight;
            canvas.width = canvas.offsetWidth;
            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });
        }
    }
init();