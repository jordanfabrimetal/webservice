//funcion que se ejecuta iniciando
function init() {
	mostarform();
	listar();

	$.post("../ajax/llamadaemergencia.php?op=selectcliente", function (r) {
		$("#cliente").html(r);
		$("#cliente").selectpicker('refresh');
	});
	
	$("#cliente").on("change", function () {
		$.post("../ajax/llamadaemergencia.php?op=selectedificio", {"cliente": $("#cliente").val()}, function (r) {
			$("#edificio").html(r);
			$("#edificio").selectpicker('refresh');
		});
	});
	$("#edificio").on("change", function () {
		listar();
	});

	$('#formularioemergencia').on("submit", function(event){
		event.preventDefault();
		if($("#observacioniniform").val() == ''){
			new PNotify({
				title: 'Error',
				text: 'Debe llenar las observaciones',
				type: 'error',
				styling: 'bootstrap3'
			});
			return false;
		}else{
			FinalizarServ();
		}
	});
}

function mostarform(flag) {
	$("#formularioemergencia").hide();
	$("#listadoemergencia").show();
}

function cancelarform() {
	bootbox.alert("Seguro que desea cancelar?", function () {
		$(location).attr("href", "estado.php");
	});
}


function listar() {
	var cliente = $("#cliente").val();
    var edificio = $("#edificio").val();
    var cencosto = $("#cencosto").val();

	tabla = $('#tblemergencia').dataTable({
		"responsive": true,
		"aProcessing": true,
		"aServerSide": true,
		"lengthChange": false,
		"ajax": {
			url: '../ajax/llamadaemergencia.php?op=listarfm',
			type: "get",
			data: {cliente: cliente, edificio: edificio, cencosto: cencosto},
			dataType: "json",
			error: function (e) {
				console.log(e.responseText);
			}
		},
		"columnDefs": [
			{ "width": "10px", "targets": 0 },
		],
		"bDestroy": true,
		"iDisplayLength": 10, //Paginacion 10 items
		"order": [[1, "asc"]] //Ordenar en base a la columna 0 descendente
	}).DataTable();
}

function generarllamada(fm){
	$.post("../ajax/ascensor.php?op=llamadaservicioemergencia",{servicecall:fm}, function(data,status){
		console.log(data);
		data = JSON.parse(data);
		$("#fmform").val(data.InternalSerialNum);
		$("#codigoform").val(data.InternalSerialNum);
		$("#tascensorform").val(data.tipoequipo);
		$("#marcaform").val(data.Manufacturer);
		$("#modeloform").val(data.modelo);
		$("#edificioform").val(data.edificio);
		$("#direccionform").val(data.direccion);
		$("#tservicioform").val(data.CallType);
		$("#estadoiniform").val(data.status);
		$("#itemcodeform").val(data.ItemCode);
		$("#customercodeform").val(data.CustomerCode);
	});
	$("#listadoemergencia").hide();
	$("#formularioemergencia").show();
}

function FinalizarServ(){
	var formData = new FormData();
	formData.append("subject", $("#observacioniniform").val());
	formData.append("fm", $("#fmform").val());
	formData.append("customercode", $("#customercodeform").val());
	formData.append("itemcode", $("#itemcodeform").val());
	$("#btnFinalizar").prop("disabled", true);
	$.ajax({
		url:'../ajax/llamadaemergencia.php?op=crearllamada',
		type:"POST",
		data:formData,
		contentType: false,
		processData:false,

		success: function(datos){
			bootbox.alert(datos, function(){ 
				$(location).attr("href", "llamadaemergencia.php");
			});
		}
	});
}
init();